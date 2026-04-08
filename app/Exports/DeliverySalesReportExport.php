<?php

namespace App\Exports;

use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DeliverySalesReportExport implements FromArray, WithHeadings, ShouldAutoSize
{
    private array $headings = [];

    private array $rows = [];

    public function __construct(
        private readonly Carbon $from,
        private readonly Carbon $to,
        private readonly ?int $userId = null,
    ) {
        $this->buildRows();
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    private function buildRows(): void
    {
        $sales = Sale::with(['user', 'route', 'customer', 'concept', 'carboySales'])
            ->whereBetween('timestamp', [$this->from, $this->to])
            ->when($this->userId, fn ($query) => $query->where('user_id', $this->userId))
            ->orderByDesc('timestamp')
            ->get();

        $maxCarboys = max(1, (int) $sales->max(fn (Sale $sale) => $sale->carboySales->count()));

        $this->headings = [
            'codigo_reparto',
            'codigo_usuario',
            'ruta',
            'codigo_cliente',
            'nombre_cliente',
            'no_cliente',
            'latitud',
            'longitud',
            'costo',
            'concepto',
            'fecha',
            'hora',
        ];

        for ($index = 1; $index <= $maxCarboys; $index++) {
            $this->headings[] = 'codigo_garrafon_'.$index;
        }

        $this->rows = $sales
            ->map(function (Sale $sale) use ($maxCarboys): array {
                $carboyCodes = $sale->carboySales
                    ->sortBy('id')
                    ->pluck('carboy_codebar')
                    ->values()
                    ->all();

                $row = [
                    $sale->external_id ?: (string) $sale->id,
                    $sale->user?->username ?: (string) $sale->user_id,
                    $sale->route?->name ?: '',
                    $sale->customer?->barcode ?: '',
                    $sale->customer?->name ?: '',
                    $sale->customer?->number ?: '',
                    $sale->latitude ?: '',
                    $sale->longitude ?: '',
                    (float) $sale->cost,
                    $sale->concept?->name ?: '',
                    optional($sale->timestamp)?->format('Y-m-d') ?: '',
                    optional($sale->timestamp)?->format('H:i:s') ?: '',
                ];

                for ($index = 0; $index < $maxCarboys; $index++) {
                    $row[] = $carboyCodes[$index] ?? '';
                }

                return $row;
            })
            ->values()
            ->all();
    }
}