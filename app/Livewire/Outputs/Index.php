<?php

namespace App\Livewire\Outputs;

use App\Models\Output;
use App\Models\Route;
use App\Models\User;
use App\Models\WaterjugOutput;
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
    public array $waterjug_codebars = [''];

    public bool $showForm = false;

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $outputId): void
    {
        $output = Output::with('waterjugOutputs')->findOrFail($outputId);

        $this->editingOutputId = $output->id;
        $this->user_id = (string) $output->user_id;
        $this->route_id = $output->route_id ? (string) $output->route_id : '';
        $this->waterjug_codebars = $output->waterjugOutputs
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
            'waterjug_codebars' => 'nullable|array',
            'waterjug_codebars.*' => 'nullable|string|max:255',
        ]);

        $payload = [
            'user_id' => (int) $validated['user_id'],
            'route_id' => $validated['route_id'] !== '' ? (int) $validated['route_id'] : null,
            'created_by' => Auth::id(),
        ];

        if ($this->editingOutputId) {
            $output = Output::findOrFail($this->editingOutputId);
            $output->update($payload);
            $output->waterjugOutputs()->delete();
            $message = 'Salida actualizada exitosamente';
        } else {
            $payload['timestamp'] = now();
            $output = Output::create($payload);
            $message = 'Salida registrada exitosamente';
        }

        $codebars = collect($validated['waterjug_codebars'] ?? [])
            ->map(fn ($codebar) => trim((string) $codebar))
            ->filter()
            ->values();

        foreach ($codebars as $codebar) {
            WaterjugOutput::create([
                'output_id' => $output->id,
                'waterjug_codebar' => $codebar,
                'timestamp' => now(),
            ]);
        }

        session()->flash('status', $message);
        $this->resetForm();
    }

    public function delete(int $outputId): void
    {
        $output = Output::findOrFail($outputId);
        $output->waterjugOutputs()->delete();
        $output->delete();

        if ($this->editingOutputId === $outputId) {
            $this->resetForm();
        }

        session()->flash('status', 'Salida eliminada exitosamente');
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
        $this->waterjug_codebars = [''];
        $this->showForm = false;
    }

    #[Computed]
    public function outputs()
    {
        return Output::with('user', 'route', 'waterjugOutputs')
            ->orderByDesc('timestamp')
            ->get()
            ->map(function ($output) {
                $output->waterjug_count = $output->waterjugOutputs->count();

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
