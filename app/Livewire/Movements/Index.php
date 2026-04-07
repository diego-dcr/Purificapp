<?php

namespace App\Livewire\Movements;

use App\Models\Sale;
use App\Models\Retorno;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Movimientos')]
class Index extends Component
{
    public ?int $selectedSaleId = null;

    public ?int $selectedRetornoId = null;

    public function showSaleDetails(int $saleId): void
    {
        $this->selectedSaleId = $saleId;
        $this->selectedRetornoId = null;
    }

    public function showRetornoDetails(int $retornoId): void
    {
        $this->selectedRetornoId = $retornoId;
        $this->selectedSaleId = null;
    }

    public function closeDetails(): void
    {
        $this->selectedSaleId = null;
        $this->selectedRetornoId = null;
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
    public function selectedSale(): ?Sale
    {
        if (! $this->selectedSaleId) {
            return null;
        }

        return Sale::with('carboySales', 'customer', 'user', 'concept', 'route')->find($this->selectedSaleId);
    }

    #[Computed]
    public function selectedRetorno(): ?Retorno
    {
        if (! $this->selectedRetornoId) {
            return null;
        }

        return Retorno::with('carboyRetornos', 'user', 'route')->find($this->selectedRetornoId);
    }
}
