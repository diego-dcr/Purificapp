<?php

namespace App\Http\Controllers;

use App\Exports\DeliverySalesReportExport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DeliveryKpiController extends Controller
{
    public function index()
    {
        $fromDateInput = trim((string) request('kpi_from_date', ''));
        $toDateInput   = trim((string) request('kpi_to_date', ''));
        [$fromDate, $toDate, $selectedRange, $selectedFromDate, $selectedToDate] = $this->resolveRange($fromDateInput, $toDateInput);

        $selectedUserId = (int) request('kpi_user_id', 0);

        // All users that have made at least one sale, for the dropdown
        $repartidores = User::query()
            ->whereIn('id', DB::table('sales')->distinct()->pluck('user_id'))
            ->orderBy('name')
            ->get(['id', 'name']);

        // ── 1. Comparison: all repartidores in range ────────────────────────
        $comparisonData = DB::table('sales')
            ->join('users', 'users.id', '=', 'sales.user_id')
            ->leftJoinSub(
                DB::table('carboy_sales')
                    ->selectRaw('sale_id, COUNT(*) as cnt')
                    ->groupBy('sale_id'),
                'cs',
                fn ($j) => $j->on('cs.sale_id', '=', 'sales.id')
            )
            ->whereBetween('sales.timestamp', [$fromDate, $toDate])
            ->selectRaw('users.id as user_id, users.name as user_name, COUNT(sales.id) as sales_count, SUM(COALESCE(cs.cnt, 0)) as carboys_count, SUM(sales.cost) as total_amount')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('carboys_count')
            ->get();

        $comparisonLabels  = $comparisonData->pluck('user_name')->all();
        $comparisonCarboys = $comparisonData->pluck('carboys_count')->map(fn ($v) => (int) $v)->all();
        $comparisonIncome  = $comparisonData->pluck('total_amount')->map(fn ($v) => (float) $v)->all();

        // ── Per-repartidor queries (null = all within range) ─────────────────
        $userFilter = $selectedUserId > 0;

        // KPI cards
        $kpiCarboys = (int) DB::table('carboy_sales')
            ->join('sales', 'sales.id', '=', 'carboy_sales.sale_id')
            ->whereBetween('sales.timestamp', [$fromDate, $toDate])
            ->when($userFilter, fn ($q) => $q->where('sales.user_id', $selectedUserId))
            ->count();

        $kpiIncome = (float) DB::table('sales')
            ->whereBetween('timestamp', [$fromDate, $toDate])
            ->when($userFilter, fn ($q) => $q->where('user_id', $selectedUserId))
            ->sum('cost');

        $kpiUniqCustomers = (int) DB::table('sales')
            ->whereBetween('timestamp', [$fromDate, $toDate])
            ->when($userFilter, fn ($q) => $q->where('user_id', $selectedUserId))
            ->distinct('customer_id')
            ->count('customer_id');

        $kpiSalesCount = (int) DB::table('sales')
            ->whereBetween('timestamp', [$fromDate, $toDate])
            ->when($userFilter, fn ($q) => $q->where('user_id', $selectedUserId))
            ->count();

        // ── 2. Garrafones por mes (uses year extracted from fromDate) ─────────
        $kpiYear = (int) $fromDate->year;

        $carboysByMonth = DB::table('carboy_sales')
            ->join('sales', 'sales.id', '=', 'carboy_sales.sale_id')
            ->whereYear('sales.timestamp', $kpiYear)
            ->when($userFilter, fn ($q) => $q->where('sales.user_id', $selectedUserId))
            ->selectRaw('MONTH(sales.timestamp) as month_number, COUNT(*) as total')
            ->groupBy('month_number')
            ->pluck('total', 'month_number');

        // ── 3. Ganancias por mes ──────────────────────────────────────────────
        $earningsByMonth = DB::table('sales')
            ->whereYear('timestamp', $kpiYear)
            ->when($userFilter, fn ($q) => $q->where('user_id', $selectedUserId))
            ->selectRaw('MONTH(timestamp) as month_number, SUM(cost) as total')
            ->groupBy('month_number')
            ->pluck('total', 'month_number');

        $monthLabels     = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $carboyMonthData   = [];
        $earningsMonthData = [];

        for ($m = 1; $m <= 12; $m++) {
            $carboyMonthData[]   = (int) ($carboysByMonth[$m] ?? 0);
            $earningsMonthData[] = round((float) ($earningsByMonth[$m] ?? 0), 2);
        }

        // ── 4. Clientes más visitados ─────────────────────────────────────────
        $topVisited = DB::table('sales')
            ->join('customers', 'customers.id', '=', 'sales.customer_id')
            ->leftJoinSub(
                DB::table('carboy_sales')
                    ->selectRaw('sale_id, COUNT(*) as cnt')
                    ->groupBy('sale_id'),
                'cs',
                fn ($j) => $j->on('cs.sale_id', '=', 'sales.id')
            )
            ->whereBetween('sales.timestamp', [$fromDate, $toDate])
            ->when($userFilter, fn ($q) => $q->where('sales.user_id', $selectedUserId))
            ->selectRaw('customers.name as customer_name, COUNT(sales.id) as visits, SUM(COALESCE(cs.cnt, 0)) as carboys, SUM(sales.cost) as amount')
            ->groupBy('customers.id', 'customers.name')
            ->orderByDesc('visits')
            ->limit(10)
            ->get();

        $topVisitedLabels  = $topVisited->pluck('customer_name')->all();
        $topVisitedVisits  = $topVisited->pluck('visits')->map(fn ($v) => (int) $v)->all();

        // ── 5. Ventas por ruta ────────────────────────────────────────────────
        $byRoute = DB::table('sales')
            ->join('routes', 'routes.id', '=', 'sales.route_id')
            ->leftJoinSub(
                DB::table('carboy_sales')
                    ->selectRaw('sale_id, COUNT(*) as cnt')
                    ->groupBy('sale_id'),
                'cs',
                fn ($j) => $j->on('cs.sale_id', '=', 'sales.id')
            )
            ->whereBetween('sales.timestamp', [$fromDate, $toDate])
            ->when($userFilter, fn ($q) => $q->where('sales.user_id', $selectedUserId))
            ->selectRaw('routes.name as route_name, COUNT(sales.id) as sales_count, SUM(COALESCE(cs.cnt, 0)) as carboys_count')
            ->groupBy('routes.id', 'routes.name')
            ->orderByDesc('carboys_count')
            ->get();

        $routeLabels  = $byRoute->pluck('route_name')->all();
        $routeCarboys = $byRoute->pluck('carboys_count')->map(fn ($v) => (int) $v)->all();

        // Peak day of week (0=Sun … 6=Sat) for selected repartidor
        $byDayOfWeek = DB::table('sales')
            ->whereBetween('timestamp', [$fromDate, $toDate])
            ->when($userFilter, fn ($q) => $q->where('user_id', $selectedUserId))
            ->selectRaw('DAYOFWEEK(timestamp) - 1 as dow, COUNT(*) as total')
            ->groupBy('dow')
            ->pluck('total', 'dow');

        $dowLabels = ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'];
        $dowData = array_map(fn ($d) => (int) ($byDayOfWeek[$d] ?? 0), range(0, 6));

        return view('delivery-kpi', [
            'repartidores'      => $repartidores,
            'selectedUserId'    => $selectedUserId,
            'selectedFromDate'  => $selectedFromDate,
            'selectedToDate'    => $selectedToDate,
            'selectedRange'     => $selectedRange,
            'kpiYear'           => $kpiYear,
            // KPI cards
            'kpiCarboys'        => $kpiCarboys,
            'kpiIncome'         => $kpiIncome,
            'kpiUniqCustomers'  => $kpiUniqCustomers,
            'kpiSalesCount'     => $kpiSalesCount,
            // Comparison
            'comparisonLabels'  => $comparisonLabels,
            'comparisonCarboys' => $comparisonCarboys,
            'comparisonIncome'  => $comparisonIncome,
            // Monthly
            'monthLabels'       => $monthLabels,
            'carboyMonthData'   => $carboyMonthData,
            'earningsMonthData' => $earningsMonthData,
            // Top visited
            'topVisited'        => $topVisited,
            'topVisitedLabels'  => $topVisitedLabels,
            'topVisitedVisits'  => $topVisitedVisits,
            // By route
            'routeLabels'       => $routeLabels,
            'routeCarboys'      => $routeCarboys,
            // Day of week
            'dowLabels'         => $dowLabels,
            'dowData'           => $dowData,
        ]);
    }

    public function exportSalesReport()
    {
        $fromDateInput = trim((string) request('kpi_from_date', ''));
        $toDateInput = trim((string) request('kpi_to_date', ''));
        [$fromDate, $toDate] = $this->resolveRange($fromDateInput, $toDateInput);

        $selectedUserId = (int) request('kpi_user_id', 0);
        $userId = $selectedUserId > 0 ? $selectedUserId : null;

        $userCode = 'todos';

        if ($userId !== null) {
            $userCode = User::query()->find($userId)?->username ?: (string) $userId;
        }

        $filename = 'reporte-ventas-garrafones-'.$userCode.'-'.$fromDate->format('Ymd').'-'.$toDate->format('Ymd').'.xlsx';

        return Excel::download(new DeliverySalesReportExport($fromDate, $toDate, $userId), $filename);
    }

    /** @return array{0: Carbon, 1: Carbon, 2: string, 3: string, 4: string} */
    private function resolveRange(string $from, string $to): array
    {
        $fromDate = now()->startOfMonth();
        $toDate   = now()->endOfDay();

        if ($from !== '' && $to !== '') {
            try {
                $fromDate = Carbon::createFromFormat('Y-m-d', $from)->startOfDay();
                $toDate   = Carbon::createFromFormat('Y-m-d', $to)->endOfDay();

                if ($fromDate->gt($toDate)) {
                    [$fromDate, $toDate] = [$toDate->copy()->startOfDay(), $fromDate->copy()->endOfDay()];
                }
            } catch (\Throwable) {
                $fromDate = now()->startOfMonth();
                $toDate   = now()->endOfDay();
            }
        }

        return [
            $fromDate,
            $toDate,
            $fromDate->format('d/m/Y').' – '.$toDate->format('d/m/Y'),
            $fromDate->format('Y-m-d'),
            $toDate->format('Y-m-d'),
        ];
    }
}
