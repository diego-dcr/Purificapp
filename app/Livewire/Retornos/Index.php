<?php

namespace App\Livewire\Retornos;

use App\Models\Output;
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
    public ?int $editingOutputId = null;

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

    public function edit(int $outputId): void
    {
        $output = Output::with('carboyOutputs')->findOrFail($outputId);

        $this->editingOutputId = $output->id;
        $this->user_id = (string) $output->user_id;
        $this->route_id = $output->route_id ? (string) $output->route_id : '';
        $this->carboy_codebars = $output->carboyOutputs
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

        if ($this->editingOutputId) {
            $output = Output::findOrFail($this->editingOutputId);
            $output->update($payload);
            $output->carboyOutputs()->delete();
            $message = 'Output actualizado exitosamente';
        } else {
            $payload['timestamp'] = now();
            $output = Output::create($payload);
            $message = 'Output registrado exitosamente';
        }

        $codebars = collect($validated['carboy_codebars'] ?? [])
            ->map(fn ($codebar) => trim((string) $codebar))
            ->filter()
            ->values();

        foreach ($codebars as $codebar) {
            CarboyOutput::create([
                'output_id' => $output->id,
                'carboy_codebar' => $codebar,
                'timestamp' => now(),
            ]);
        }

        session()->flash('status', $message);
        $this->resetForm();
    }

    public function delete(int $outputId): void
    {
        $output = Output::findOrFail($outputId);
        $output->carboyOutputs()->delete();
        $output->delete();

        if ($this->editingOutputId === $outputId) {
            $this->resetForm();
        }

        session()->flash('status', 'Output eliminado exitosamente');
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    protected function resetForm(): void
    {
        $this->resetValidation();

        $this->editingOutputId = null;
        $this->user_id = '';
        $this->route_id = '';
        $this->carboy_codebars = [''];
        $this->showForm = false;
    }

    #[Computed]
    public function outputs()
    {
        return Output::with('user', 'route', 'carboyOutputs')
            ->orderByDesc('timestamp')
            ->get()
            ->map(function ($output) {
                $output->carboy_count = $output->carboyOutputs->count();

                return $output;
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
