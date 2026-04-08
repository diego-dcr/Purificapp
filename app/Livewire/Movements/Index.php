<?php

namespace App\Livewire\Movements;

use App\Models\Sale;
use App\Models\Output;
use App\Models\CarboyOutput;
use App\Models\CarboySale;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Movimientos')]
class Index extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

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
        return Sale::with('user', 'customer')
            ->withCount('carboySales as carboy_count')
            ->orderByDesc('timestamp')
            ->paginate(100, ['*'], 'sales_page');
    }

    #[Computed]
    public function outputs()
    {
        return Output::with('user', 'route')
            ->withCount('carboyOutputs as carboy_count')
            ->orderByDesc('timestamp')
            ->paginate(100, ['*'], 'outputs_page');
    }

    #[Computed]
    public function salesTotal(): int
    {
        return Sale::query()->count();
    }

    #[Computed]
    public function outputsTotal(): int
    {
        return Output::query()->count();
    }

    #[Computed]
    public function salesCarboyTotal(): int
    {
        return CarboySale::query()->count();
    }

    #[Computed]
    public function outputsCarboyTotal(): int
    {
        return CarboyOutput::query()->count();
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
