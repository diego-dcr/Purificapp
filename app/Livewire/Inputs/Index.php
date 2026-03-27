<?php

namespace App\Livewire\Inputs;

use App\Models\Concept;
use App\Models\Customer;
use App\Models\Input;
use App\Models\Route;
use App\Models\User;
use App\Models\WaterjugSale;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Entregas / Ventas')]
class Index extends Component
{
    public ?int $editingInputId = null;

    public string $user_id = '';

    public string $route_id = '';

    public string $customer_id = '';

    public string $cost = '';

    public string $concept_id = '';

    /** @var array<int, string> */
    public array $waterjug_codebars = [''];

    public bool $showForm = false;

    public bool $showDetailsModal = false;
    public $detailsInput = null;

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $inputId): void
    {
        $input = Input::with('waterjugSales')->findOrFail($inputId);

        $this->editingInputId = $input->id;
        $this->user_id = (string) $input->user_id;
        $this->route_id = $input->route_id ? (string) $input->route_id : '';
        $this->customer_id = (string) $input->customer_id;
        $this->cost = (string) $input->cost;
        $this->concept_id = (string) $input->concept_id;
        $this->waterjug_codebars = $input->waterjugSales
            ->pluck('waterjug_codebar')
            ->values()
            ->toArray();

        if ($this->waterjug_codebars === []) {
            $this->waterjug_codebars = [''];
        }

        $this->showForm = true;
        $this->resetValidation();
    }

    public function addWaterjugInput(): void
    {
        $this->waterjug_codebars[] = '';
    }

    public function removeWaterjugInput(int $index): void
    {
        if (count($this->waterjug_codebars) <= 1) {
            return;
        }

        unset($this->waterjug_codebars[$index]);
        $this->waterjug_codebars = array_values($this->waterjug_codebars);
    }

    public function save(): void
    {
        $validated = $this->validate([
            'user_id' => 'required|exists:users,id',
            'route_id' => 'nullable|exists:routes,id',
            'customer_id' => 'required|exists:customers,id',
            'cost' => 'required|numeric|min:0',
            'concept_id' => 'required|exists:concepts,id',
            'waterjug_codebars' => 'nullable|array',
            'waterjug_codebars.*' => 'nullable|string|max:255',
        ]);

        $payload = [
            'user_id' => (int) $validated['user_id'],
            'route_id' => $validated['route_id'] !== '' ? (int) $validated['route_id'] : null,
            'customer_id' => (int) $validated['customer_id'],
            'cost' => (float) $validated['cost'],
            'concept_id' => (int) $validated['concept_id'],
            'created_by' => Auth::id(),
        ];

        if ($this->editingInputId) {
            $input = Input::findOrFail($this->editingInputId);
            $input->update($payload);
            $input->waterjugSales()->delete();
            $message = 'Entrega/Venta actualizada exitosamente';
        } else {
            $payload['timestamp'] = now();
            $input = Input::create($payload);
            $message = 'Entrega/Venta registrada exitosamente';
        }

        $codebars = collect($validated['waterjug_codebars'] ?? [])
            ->map(fn($codebar) => trim((string) $codebar))
            ->filter()
            ->values();

        foreach ($codebars as $codebar) {
            WaterjugSale::create([
                'input_id' => $input->id,
                'waterjug_codebar' => $codebar,
                'timestamp' => now(),
            ]);
        }

        session()->flash('status', $message);
        $this->resetForm();
    }

    public function delete(int $inputId): void
    {
        $input = Input::findOrFail($inputId);
        $input->waterjugSales()->delete();
        $input->delete();

        if ($this->editingInputId === $inputId) {
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

        $this->editingInputId = null;
        $this->user_id = '';
        $this->route_id = '';
        $this->customer_id = '';
        $this->cost = '';
        $this->concept_id = '';
        $this->waterjug_codebars = [''];
        $this->showForm = false;
    }

    #[Computed]
    public function inputs()
    {
        return Input::with('user', 'route', 'customer', 'concept', 'waterjugSales')
            ->orderByDesc('timestamp')
            ->get()
            ->map(function ($input) {
                $input->waterjug_count = $input->waterjugSales->count();

                return $input;
            });
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
        return Concept::orderBy('name')->get();
    }

public function showDetails($inputId)
{
    $this->detailsInput = Input::with(['user', 'route', 'customer', 'concept', 'waterjugSales'])
        ->findOrFail($inputId);
    $this->showDetailsModal = true;
}

    public function closeDetails()
    {
        $this->showDetailsModal = false;
        $this->detailsInput = null;
    }
}
