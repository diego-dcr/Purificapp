<?php

namespace App\Livewire\Movements;

use App\Models\Sale;
use App\Models\Output;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Movimientos')]
class Index extends Component
{
    public ?int $selectedSaleId = null;

    public ?int $selectedOutputId = null;

    public function showSaleDetails(int $saleId): void
    {
        $this->selectedSaleId = $saleId;
        $this->selectedOutputId = null;
    }

    public function showOutputDetails(int $outputId): void
    {
        $this->selectedOutputId = $outputId;
        $this->selectedSaleId = null;
    }

    public function closeDetails(): void
    {
        $this->selectedSaleId = null;
        $this->selectedOutputId = null;
    }

    #[Computed]
    public function sales()
    {
        return Sale::with('user', 'customer', 'carboySales')
            ->orderByDesc('timestamp')
            ->get()
            ->map(function ($sale) {
                $sale->carboy_count = $sale->carboySales->count();

                return $sale;
            });
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
    public function selectedSale(): ?Sale
    {
        if (! $this->selectedSaleId) {
            return null;
        }

        return Sale::with('carboySales', 'customer', 'user', 'concept', 'route')->find($this->selectedSaleId);
    }

    #[Computed]
    public function selectedOutput(): ?Output
    {
        if (! $this->selectedOutputId) {
            return null;
        }

        return Output::with('carboyOutputs', 'user', 'route')->find($this->selectedOutputId);
    }
}
