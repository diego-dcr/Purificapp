<?php

namespace App\Livewire\Retornos;

use App\Models\Retorno;
use App\Models\Route;
use App\Models\User;
use App\Models\CarboyOutput;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Salidas')]
class Index extends Component
{
    public ?int $editingRetornoId = null;

    public string $user_id = '';

    public string $route_id = '';

    /** @var array<int, string> */
    public array $carboy_codebars = [''];

    public bool $showForm = false;

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $retornoId): void
    {
        $retorno = Retorno::with('carboyRetornos')->findOrFail($retornoId);

        $this->editingRetornoId = $retorno->id;
        $this->user_id = (string) $retorno->user_id;
        $this->route_id = $retorno->route_id ? (string) $retorno->route_id : '';
        $this->carboy_codebars = $retorno->carboyRetornos
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
            'carboy_codebars' => 'nullable|array',
            'carboy_codebars.*' => 'nullable|string|max:255',
        ]);

        $payload = [
            'user_id' => (int) $validated['user_id'],
            'route_id' => $validated['route_id'] !== '' ? (int) $validated['route_id'] : null,
            'created_by' => Auth::id(),
        ];

        if ($this->editingRetornoId) {
            $retorno = Retorno::findOrFail($this->editingRetornoId);
            $retorno->update($payload);
            $retorno->carboyRetornos()->delete();
            $message = 'Retorno actualizado exitosamente';
        } else {
            $payload['timestamp'] = now();
            $retorno = Retorno::create($payload);
            $message = 'Retorno registrado exitosamente';
        }

        $codebars = collect($validated['carboy_codebars'] ?? [])
            ->map(fn ($codebar) => trim((string) $codebar))
            ->filter()
            ->values();

        foreach ($codebars as $codebar) {
            CarboyOutput::create([
                'retorno_id' => $retorno->id,
                'carboy_codebar' => $codebar,
                'timestamp' => now(),
            ]);
        }

        session()->flash('status', $message);
        $this->resetForm();
    }

    public function delete(int $retornoId): void
    {
        $retorno = Retorno::findOrFail($retornoId);
        $retorno->carboyRetornos()->delete();
        $retorno->delete();

        if ($this->editingRetornoId === $retornoId) {
            $this->resetForm();
        }

        session()->flash('status', 'Retorno eliminado exitosamente');
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    protected function resetForm(): void
    {
        $this->resetValidation();

        $this->editingRetornoId = null;
        $this->user_id = '';
        $this->route_id = '';
        $this->carboy_codebars = [''];
        $this->showForm = false;
    }

    #[Computed]
    public function retornos()
    {
        return Retorno::with('user', 'route', 'carboyRetornos')
            ->orderByDesc('timestamp')
            ->get()
            ->map(function ($retorno) {
                $retorno->carboy_count = $retorno->carboyRetornos->count();

                return $retorno;
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
}
