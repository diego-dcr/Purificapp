<?php

namespace App\Livewire\Outputs;

use App\Models\Output;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Salidas')]
class Index extends Component
{
    public ?int $selectedOutputId = null;

    public function showOutputDetails(int $outputId): void
    {
        $this->selectedOutputId = $outputId;
    }

    public function closeDetails(): void
    {
        $this->selectedOutputId = null;
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


}
