<?php

namespace App\Exports;

use App\Models\Expense;
use App\Models\Income;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FinanceReportExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function __construct(
        private readonly Carbon $from,
        private readonly Carbon $to,
    ) {
    }

    public function collection(): Collection
    {
        $salesRows = Sale::with(['concept', 'customer', 'user', 'route'])
            ->whereBetween('timestamp', [$this->from, $this->to])
            ->orderByDesc('timestamp')
            ->get()
            ->map(fn(Sale $sale) => [
                'semana' => $sale->timestamp?->weekOfYear,
                'fecha' => $sale->timestamp?->format('Y-m-d H:i:s'),
                'no_cliente' => $sale->customer?->number,
                'cliente' => $sale->customer?->name,
                'repartidor_usuario' => $sale->user?->name,
                'ruta' => $sale->route?->name,
                'concepto' => $sale->concept?->name ?? 'Sin concepto',
                'tipo_concepto' => 'Ingreso',
                'origen' => 'Venta',
                'monto' => (float) $sale->cost,
                'descripcion' => null,
            ]);

        $incomeRows = Income::with('concept')
            ->whereBetween('timestamp', [$this->from, $this->to])
            ->orderByDesc('timestamp')
            ->get()
            ->map(fn(Income $income) => [
                'semana' => $income->timestamp?->weekOfYear,
                'fecha' => $income->timestamp?->format('Y-m-d H:i:s'),
                'no_cliente' => null,
                'cliente' => null,
                'repartidor_usuario' => null,
                'ruta' => null,
                'concepto' => $income->concept?->name ?? 'Sin concepto',
                'tipo_concepto' => 'Ingreso',
                'origen' => 'Manual',
                'monto' => (float) $income->amount,
                'descripcion' => $income->description ?: null,
            ]);

        $expenseRows = Expense::with('concept')
            ->whereBetween('timestamp', [$this->from, $this->to])
            ->orderByDesc('timestamp')
            ->get()
            ->map(fn(Expense $expense) => [
                'semana' => $expense->timestamp?->weekOfYear,
                'fecha' => $expense->timestamp?->format('Y-m-d H:i:s'),
                'no_cliente' => null,
                'cliente' => null,
                'repartidor_usuario' => null,
                'ruta' => null,
                'concepto' => $expense->concept?->name ?? 'Sin concepto',
                'tipo_concepto' => 'Egreso',
                'origen' => 'Manual',
                'monto' => (float) $expense->amount,
                'descripcion' => $expense->description ?: null,
            ]);

        return $salesRows
            ->concat($incomeRows)
            ->concat($expenseRows)
            ->sortByDesc('fecha')
            ->values();
    }

    public function headings(): array
    {
        return [
            'Semana',
            'Fecha',
            'No cliente',
            'Cliente',
            'Repartidor/Usuario',
            'Ruta',
            'Concepto',
            'Tipo concepto',
            'Origen',
            'Monto',
            'Descripción',
        ];
    }
}
