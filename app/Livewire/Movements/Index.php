<?php

namespace App\Livewire\Movements;

use App\Models\Input;
use App\Models\Output;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Movimientos')]
class Index extends Component
{
    public ?int $selectedInputId = null;

    public ?int $selectedOutputId = null;

    public function showInputDetails(int $inputId): void
    {
        $this->selectedInputId = $inputId;
        $this->selectedOutputId = null;
    }

    public function showOutputDetails(int $outputId): void
    {
        $this->selectedOutputId = $outputId;
        $this->selectedInputId = null;
    }

    public function closeDetails(): void
    {
        $this->selectedInputId = null;
        $this->selectedOutputId = null;
    }

    #[Computed]
    public function inputs()
    {
        return Input::with('user', 'customer', 'waterjugSales')
            ->orderByDesc('timestamp')
            ->get()
            ->map(function ($input) {
                $input->waterjug_count = $input->waterjugSales->count();

                return $input;
            });
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
    public function selectedInput(): ?Input
    {
        if (! $this->selectedInputId) {
            return null;
        }

        return Input::with('waterjugSales', 'customer', 'user', 'concept', 'route')->find($this->selectedInputId);
    }

    #[Computed]
    public function selectedOutput(): ?Output
    {
        if (! $this->selectedOutputId) {
            return null;
        }

        return Output::with('waterjugOutputs', 'user', 'route')->find($this->selectedOutputId);
    }
}
