<?php

namespace App\Livewire\Sales;

use App\Models\Concept;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\Route;
use App\Models\User;
use App\Models\CarboySale;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Entregas / Ventas')]
class Index extends Component
{
    public ?int $editingSaleId = null;

    public string $user_id = '';

    public string $route_id = '';

    public string $customer_id = '';

    public string $cost = '';

    public string $concept_id = '';

    /** @var array<int, string> */
    public array $carboy_codebars = [''];

    public bool $showForm = false;

    public bool $showDetailsModal = false;
    public $detailsSale = null;

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $saleId): void
    {
        $sale = Sale::with('carboySales')->findOrFail($saleId);

        $this->editingSaleId = $sale->id;
        $this->user_id = (string) $sale->user_id;
        $this->route_id = $sale->route_id ? (string) $sale->route_id : '';
        $this->customer_id = (string) $sale->customer_id;
        $this->cost = (string) $sale->cost;
        $this->concept_id = (string) $sale->concept_id;
        $this->carboy_codebars = $sale->carboySales
            ->pluck('carboy_codebar')
            ->values()
            ->toArray();

        if ($this->carboy_codebars === []) {
            $this->carboy_codebars = [''];
        }

        $this->showForm = true;
        $this->resetValidation();
    }

    public function addCarboyInput(): void
    {
        $this->carboy_codebars[] = '';
    }

    public function removeCarboyInput(int $index): void
    {
        if (count($this->carboy_codebars) <= 1) {
            return;
        }

        unset($this->carboy_codebars[$index]);
        $this->carboy_codebars = array_values($this->carboy_codebars);
    }

    public function save(): void
    {
        $validated = $this->validate([
            'user_id' => 'required|exists:users,id',
            'route_id' => 'nullable|exists:routes,id',
            'customer_id' => 'required|exists:customers,id',
            'cost' => 'required|numeric|min:0',
            'concept_id' => 'required|exists:concepts,id',
            'carboy_codebars' => 'nullable|array',
            'carboy_codebars.*' => 'nullable|string|max:255',
        ]);

        $concept = Concept::query()
            ->whereKey((int) $validated['concept_id'])
            ->where('type', Concept::TYPE_INCOME)
            ->firstOrFail();

        $payload = [
            'user_id' => (int) $validated['user_id'],
            'route_id' => $validated['route_id'] !== '' ? (int) $validated['route_id'] : null,
            'customer_id' => (int) $validated['customer_id'],
            'cost' => (float) $validated['cost'],
            'concept_id' => (int) $validated['concept_id'],
            'created_by' => Auth::id(),
        ];

        if ($this->editingSaleId) {
            $sale = Sale::findOrFail($this->editingSaleId);
            $sale->update($payload);
            $sale->carboySales()->delete();
            $message = 'Entrega/Venta actualizada exitosamente';
        } else {
            $payload['timestamp'] = now();
            $sale = Sale::create($payload);
            $message = 'Entrega/Venta registrada exitosamente';
        }

        $codebars = $concept->allows_carboy
            ? collect($validated['carboy_codebars'] ?? [])
                ->map(fn ($codebar) => trim((string) $codebar))
                ->filter()
                ->values()
            : collect();

        foreach ($codebars as $codebar) {
            CarboySale::create([
                'sale_id' => $sale->id,
                'carboy_codebar' => $codebar,
                'timestamp' => now(),
            ]);
        }

        session()->flash('status', $message);
        $this->resetForm();
    }

    public function delete(int $saleId): void
    {
        $sale = Sale::findOrFail($saleId);
        $sale->carboySales()->delete();
        $sale->delete();

        if ($this->editingSaleId === $saleId) {
            $this->resetForm();
        }

        session()->flash('status', 'Entrega/Venta eliminada exitosamente');
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    protected function resetForm(): void
    {
        $this->resetValidation();

        $this->editingSaleId = null;
        $this->user_id = '';
        $this->route_id = '';
        $this->customer_id = '';
        $this->cost = '';
        $this->concept_id = '';
        $this->carboy_codebars = [''];
        $this->showForm = false;
    }

    #[Computed]
    public function sales()
    {
        return Sale::with('user', 'route', 'customer', 'concept')
            ->withCount('carboySales as carboy_count')
            ->orderByDesc('timestamp')
            ->get();
    }

    #[Computed]
    public function users()
    {
        return User::orderBy('name')->get();
    }

    #[Computed]
    public function routes()
    {
        return Route::orderBy('name')->get();
    }

    #[Computed]
    public function customers()
    {
        return Customer::with('route')->orderBy('name')->get();
    }

    #[Computed]
    public function concepts()
    {
        return Concept::where('type', Concept::TYPE_INCOME)
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function selectedConceptAllowsCarboys(): bool
    {
        if ($this->concept_id === '') {
            return true;
        }

        return (bool) $this->concepts
            ->firstWhere('id', (int) $this->concept_id)?->allows_carboy;
    }

    public function updatedConceptId(): void
    {
        if (!$this->selectedConceptAllowsCarboys) {
            $this->carboy_codebars = [''];
        }
    }

    public function showDetails(int $saleId): void
    {
        $this->detailsSale = Sale::with(['user', 'route', 'customer', 'concept', 'carboySales'])
            ->withCount('carboySales as carboy_count')
            ->findOrFail($saleId);
        $this->showDetailsModal = true;
    }

    public function closeDetails(): void
    {
        $this->showDetailsModal = false;
        $this->detailsSale = null;
    }
}
