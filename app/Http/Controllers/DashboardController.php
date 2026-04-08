<?php

namespace App\Http\Controllers;

use App\Exports\FinanceReportExport;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index()
    {
        $monthlyYear = (int) request('monthly_year', now()->year);

        $totalsFromDateInput = trim((string) request('totals_from_date', ''));
        $totalsToDateInput = trim((string) request('totals_to_date', ''));
        [$totalsFromDate, $totalsToDate, $totalsSelectedRange, $totalsSelectedFromDate, $totalsSelectedToDate] = $this->resolveRange($totalsFromDateInput, $totalsToDateInput);

        $topFromDateInput = trim((string) request('top_from_date', ''));
        $topToDateInput = trim((string) request('top_to_date', ''));
        [$topFromDate, $topToDate, $topSelectedRange, $topSelectedFromDate, $topSelectedToDate] = $this->resolveRange($topFromDateInput, $topToDateInput);

        $exportFromDateInput = trim((string) request('export_from_date', ''));
        $exportToDateInput = trim((string) request('export_to_date', ''));
        [, , , $exportSelectedFromDate, $exportSelectedToDate] = $this->resolveRange($exportFromDateInput, $exportToDateInput);

        $monthExpr = $this->monthExpression('timestamp');
        $yearExpr = $this->yearExpression('timestamp');
        $hasIncomesTable = Schema::hasTable('incomes');
        $hasExpensesTable = Schema::hasTable('expenses');

        $salesByMonth = Sale::query()
            ->selectRaw($monthExpr . ' as month_number, COUNT(*) as total')
            ->whereYear('timestamp', $monthlyYear)
            ->groupByRaw($monthExpr)
            ->pluck('total', 'month_number');

        $manualIncomeByMonth = $hasIncomesTable
            ? Income::query()
                ->selectRaw($monthExpr . ' as month_number, COUNT(*) as total')
                ->whereYear('timestamp', $monthlyYear)
                ->groupByRaw($monthExpr)
                ->pluck('total', 'month_number')
            : collect();

        $expenseByMonth = $hasExpensesTable
            ? Expense::query()
                ->selectRaw($monthExpr . ' as month_number, COUNT(*) as total')
                ->whereYear('timestamp', $monthlyYear)
                ->groupByRaw($monthExpr)
                ->pluck('total', 'month_number')
            : collect();

        $monthLabels = [
            'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun',
            'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic',
        ];

        $incomeCountByMonth = [];
        $expenseCountByMonth = [];

        for ($month = 1; $month <= 12; $month++) {
            $incomeCountByMonth[] = (int) ($salesByMonth[$month] ?? 0) + (int) ($manualIncomeByMonth[$month] ?? 0);
            $expenseCountByMonth[] = (int) ($expenseByMonth[$month] ?? 0);
        }

        $totalIncome = (float) Sale::whereBetween('timestamp', [$totalsFromDate, $totalsToDate])->sum('cost')
            + ($hasIncomesTable ? (float) Income::whereBetween('timestamp', [$totalsFromDate, $totalsToDate])->sum('amount') : 0.0);

        $totalExpense = $hasExpensesTable
            ? (float) Expense::whereBetween('timestamp', [$totalsFromDate, $totalsToDate])->sum('amount')
            : 0.0;

        $topCustomers = DB::table('sales')
            ->join('customers', 'customers.id', '=', 'sales.customer_id')
            ->leftJoinSub(
                DB::table('carboy_sales')
                    ->selectRaw('sale_id, COUNT(*) as carboys_count')
                    ->groupBy('sale_id'),
                'carboys_per_sale',
                fn ($join) => $join->on('carboys_per_sale.sale_id', '=', 'sales.id')
            )
            ->whereBetween('sales.timestamp', [$topFromDate, $topToDate])
            ->selectRaw('customers.name as customer_name, COUNT(sales.id) as sales_count, SUM(sales.cost) as total_amount, SUM(COALESCE(carboys_per_sale.carboys_count, 0)) as carboys_count')
            ->groupBy('customers.id', 'customers.name')
            ->orderByDesc('carboys_count')
            ->limit(10)
            ->get();

        $topCustomerLabels = $topCustomers->pluck('customer_name')->all();
        $topCustomerValues = $topCustomers->pluck('carboys_count')->map(fn ($value) => (int) $value)->all();

        $availableYears = collect([
            Sale::query()->selectRaw($yearExpr . ' as year_value')->pluck('year_value'),
            $hasIncomesTable ? Income::query()->selectRaw($yearExpr . ' as year_value')->pluck('year_value') : collect(),
            $hasExpensesTable ? Expense::query()->selectRaw($yearExpr . ' as year_value')->pluck('year_value') : collect(),
        ])->flatten()
            ->filter()
            ->unique()
            ->sortDesc()
            ->values();

        if ($availableYears->isEmpty()) {
            $availableYears = collect([now()->year]);
        }

        return view('dashboard', [
            'monthlyYear' => $monthlyYear,
            'totalsSelectedRange' => $totalsSelectedRange,
            'totalsSelectedFromDate' => $totalsSelectedFromDate,
            'totalsSelectedToDate' => $totalsSelectedToDate,
            'topSelectedRange' => $topSelectedRange,
            'topSelectedFromDate' => $topSelectedFromDate,
            'topSelectedToDate' => $topSelectedToDate,
            'exportSelectedFromDate' => $exportSelectedFromDate,
            'exportSelectedToDate' => $exportSelectedToDate,
            'availableYears' => $availableYears,
            'monthLabels' => $monthLabels,
            'incomeCountByMonth' => $incomeCountByMonth,
            'expenseCountByMonth' => $expenseCountByMonth,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'balance' => $totalIncome - $totalExpense,
            'topCustomers' => $topCustomers,
            'topCustomerLabels' => $topCustomerLabels,
            'topCustomerValues' => $topCustomerValues,
        ]);
    }

    public function exportFinanceReport()
    {
        $fromDateInput = trim((string) request('export_from_date', ''));
        $toDateInput = trim((string) request('export_to_date', ''));
        [$fromDate, $toDate] = $this->resolveRange($fromDateInput, $toDateInput);

        $filename = 'reporte-finanzas-'.$fromDate->format('Ymd').'-'.$toDate->format('Ymd').'.xlsx';

        return Excel::download(new FinanceReportExport($fromDate, $toDate), $filename);
    }

    /**
     * @return array{0: Carbon, 1: Carbon, 2: string, 3: string, 4: string}
     */
    private function resolveRange(string $fromDateInput, string $toDateInput): array
    {
        $fromDate = now()->startOfMonth();
        $toDate = now()->endOfDay();

        if ($fromDateInput !== '' && $toDateInput !== '') {
            try {
                $fromDate = Carbon::createFromFormat('Y-m-d', $fromDateInput)->startOfDay();
                $toDate = Carbon::createFromFormat('Y-m-d', $toDateInput)->endOfDay();

                if ($fromDate->gt($toDate)) {
                    [$fromDate, $toDate] = [$toDate->copy()->startOfDay(), $fromDate->copy()->endOfDay()];
                }
            } catch (\Throwable) {
                $fromDate = now()->startOfMonth();
                $toDate = now()->endOfDay();
            }
        }

        return [
            $fromDate,
            $toDate,
            $fromDate->format('Y/m/d').' - '.$toDate->format('Y/m/d'),
            $fromDate->format('Y-m-d'),
            $toDate->format('Y-m-d'),
        ];
    }

    private function monthExpression(string $column): string
    {
        return 'EXTRACT(MONTH FROM ' . $column . ')';
    }

    private function yearExpression(string $column): string
    {
        return 'EXTRACT(YEAR FROM ' . $column . ')';
    }
}
