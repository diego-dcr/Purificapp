<x-layouts::app :title="__('KPIs Repartidores')">
    <div class="flex w-full flex-1 flex-col gap-6">

        {{-- ── Header + Filtros ────────────────────────────────────────────── --}}
        <section class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
            <div class="mb-4">
                <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-50">KPIs por Repartidor</h1>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Analiza el rendimiento de cada repartidor: garrafones entregados, ganancias, rutas y clientes mas visitados.</p>
            </div>

            <form method="GET" action="{{ route('delivery-kpi.index') }}" class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                <div>
                    <label class="mb-1 block text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Desde</label>
                    <input name="kpi_from_date" type="date" value="{{ $selectedFromDate }}"
                        class="block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100" />
                </div>

                <div>
                    <label class="mb-1 block text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Hasta</label>
                    <input name="kpi_to_date" type="date" value="{{ $selectedToDate }}"
                        class="block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100" />
                </div>

                <div>
                    <label class="mb-1 block text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Repartidor</label>
                    <select name="kpi_user_id"
                        class="block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100">
                        <option value="0">Todos los repartidores</option>
                        @foreach ($repartidores as $rep)
                            <option value="{{ $rep->id }}" @selected($selectedUserId === $rep->id)>{{ $rep->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    <flux:button variant="primary" color="sky" type="submit" class="w-full">
                        Aplicar filtro
                    </flux:button>
                </div>
            </form>

            @if ($selectedUserId > 0)
                @php $repName = $repartidores->firstWhere('id', $selectedUserId)?->name ?? 'Repartidor'; @endphp
                <div class="mt-3 flex items-center gap-2 rounded-lg border border-sky-200 bg-sky-50/60 px-4 py-2 dark:border-sky-800/50 dark:bg-sky-950/30">
                    <span class="text-sm font-medium text-sky-700 dark:text-sky-300">Filtrando por:</span>
                    <span class="text-sm text-sky-900 dark:text-sky-100">{{ $repName }}</span>
                    <span class="text-xs text-sky-600 dark:text-sky-400">— {{ $selectedRange }}</span>
                </div>
            @else
                <p class="mt-3 text-xs text-zinc-400 dark:text-zinc-500">Periodo: {{ $selectedRange }}</p>
            @endif
        </section>

        <section class="rounded-xl border border-emerald-200 bg-emerald-50/40 p-5 shadow-sm dark:border-emerald-900/60 dark:bg-emerald-950/20">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Reporte Excel de ventas por repartidor</h2>
                    <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-300">Descarga las ventas con datos de cliente, ruta, ubicacion y todos los codigos de garrafon del intervalo filtrado.</p>
                </div>

                <form method="GET" action="{{ route('delivery-kpi.export-sales-report') }}" class="grid w-full gap-3 md:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_minmax(0,1fr)_auto] xl:w-auto">
                    <input name="kpi_from_date" type="hidden" value="{{ $selectedFromDate }}" />
                    <input name="kpi_to_date" type="hidden" value="{{ $selectedToDate }}" />
                    <input name="kpi_user_id" type="hidden" value="{{ $selectedUserId }}" />

                    <div class="md:col-span-3">
                        <p class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Filtro aplicado</p>
                        <p class="mt-1 text-sm text-zinc-700 dark:text-zinc-200">
                            {{ $selectedRange }}
                            @if ($selectedUserId > 0)
                                · {{ $repName }}
                            @else
                                · Todos los repartidores
                            @endif
                        </p>
                    </div>

                    <div class="flex items-end">
                        <flux:button variant="primary" color="emerald" type="submit" class="w-full md:w-auto">
                            Exportar Excel
                        </flux:button>
                    </div>
                </form>
            </div>
        </section>

        {{-- ── KPI Cards ───────────────────────────────────────────────────── --}}
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Garrafones entregados</p>
                <p class="mt-2 text-3xl font-bold text-sky-600 dark:text-sky-400">{{ number_format($kpiCarboys) }}</p>
                <p class="mt-1 text-xs text-zinc-400 dark:text-zinc-500">En el rango seleccionado</p>
            </article>

            <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Ganancias generadas</p>
                <p class="mt-2 text-3xl font-bold text-emerald-600 dark:text-emerald-400">$ {{ number_format($kpiIncome, 2) }}</p>
                <p class="mt-1 text-xs text-zinc-400 dark:text-zinc-500">Total cobrado por ventas</p>
            </article>

            <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Clientes atendidos</p>
                <p class="mt-2 text-3xl font-bold text-violet-600 dark:text-violet-400">{{ number_format($kpiUniqCustomers) }}</p>
                <p class="mt-1 text-xs text-zinc-400 dark:text-zinc-500">Clientes unicos en el rango</p>
            </article>

            <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Entregas realizadas</p>
                <p class="mt-2 text-3xl font-bold text-amber-500 dark:text-amber-400">{{ number_format($kpiSalesCount) }}</p>
                <p class="mt-1 text-xs text-zinc-400 dark:text-zinc-500">Numero de ventas registradas</p>
            </article>
        </section>

        {{-- ── Comparativa de repartidores (siempre visible) ───────────────── --}}
        <section class="grid gap-6 xl:grid-cols-2">
            <div class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Garrafones por repartidor</h2>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Comparativa en el rango: {{ $selectedRange }}</p>
                <div class="mt-4 h-72">
                    <canvas id="comparisonCarboysChart"></canvas>
                </div>
            </div>

            <div class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Ganancias por repartidor</h2>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Monto cobrado. Rango: {{ $selectedRange }}</p>
                <div class="mt-4 h-72">
                    <canvas id="comparisonIncomeChart"></canvas>
                </div>
            </div>
        </section>

        {{-- ── Graficas mensuales (año derivado del filtro) ─────────────────── --}}
        <section class="grid gap-6 xl:grid-cols-2">
            <div class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Garrafones por mes</h2>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                    Mes con mas ventas de garrafones — Anio {{ $kpiYear }}
                    @if ($selectedUserId > 0) <span class="font-medium text-sky-600 dark:text-sky-400"> · {{ $repName }}</span> @endif
                </p>
                <div class="mt-4 h-72">
                    <canvas id="carboysByMonthChart"></canvas>
                </div>
            </div>

            <div class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Ganancias por mes</h2>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                    Mes con mas ganancias — Anio {{ $kpiYear }}
                    @if ($selectedUserId > 0) <span class="font-medium text-sky-600 dark:text-sky-400"> · {{ $repName }}</span> @endif
                </p>
                <div class="mt-4 h-72">
                    <canvas id="earningsByMonthChart"></canvas>
                </div>
            </div>
        </section>

        {{-- ── Clientes más visitados + Dia de la semana ───────────────────── --}}
        <section class="grid gap-6 xl:grid-cols-[1.4fr_1fr]">
            <div class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Clientes mas visitados (top 10)</h2>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Ordenado por numero de visitas en el rango seleccionado.</p>
                <div class="mt-4 h-96">
                    <canvas id="topVisitedChart"></canvas>
                </div>
            </div>

            <div class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Ventas por dia de la semana</h2>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Dias con mayor actividad en el rango seleccionado.</p>
                <div class="mt-4 h-96">
                    <canvas id="dowChart"></canvas>
                </div>
            </div>
        </section>

        {{-- ── Garrafones por ruta + Tabla detalle clientes ─────────────────── --}}
        <section class="grid gap-6 xl:grid-cols-2">
            <div class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Garrafones por ruta</h2>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Distribucion de garrafones entregados por ruta.</p>
                <div class="mt-4 h-72">
                    <canvas id="byRouteChart"></canvas>
                </div>
            </div>

            <div class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <h2 class="mb-4 text-lg font-semibold text-zinc-900 dark:text-zinc-50">Detalle clientes top 10</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200 text-sm dark:divide-neutral-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/60">
                            <tr class="text-left text-zinc-500 dark:text-zinc-400">
                                <th class="px-3 py-2 font-medium">#</th>
                                <th class="px-3 py-2 font-medium">Cliente</th>
                                <th class="px-3 py-2 text-right font-medium">Visitas</th>
                                <th class="px-3 py-2 text-right font-medium">Garrafones</th>
                                <th class="px-3 py-2 text-right font-medium">Monto</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                            @forelse ($topVisited as $i => $row)
                                <tr>
                                    <td class="px-3 py-2 text-zinc-400">{{ $i + 1 }}</td>
                                    <td class="px-3 py-2 font-medium text-zinc-700 dark:text-zinc-300">{{ $row->customer_name }}</td>
                                    <td class="px-3 py-2 text-right text-zinc-700 dark:text-zinc-300">{{ (int) $row->visits }}</td>
                                    <td class="px-3 py-2 text-right text-zinc-700 dark:text-zinc-300">{{ (int) $row->carboys }}</td>
                                    <td class="px-3 py-2 text-right text-zinc-700 dark:text-zinc-300">$ {{ number_format((float) $row->amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-3 py-6 text-center text-zinc-500 dark:text-zinc-400">
                                        No hay visitas registradas en este rango.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

    <script>
        (() => {
            const comparisonLabels  = @json($comparisonLabels);
            const comparisonCarboys = @json($comparisonCarboys);
            const comparisonIncome  = @json($comparisonIncome);
            const monthLabels       = @json($monthLabels);
            const carboyMonthData   = @json($carboyMonthData);
            const earningsMonthData = @json($earningsMonthData);
            const topVisitedLabels  = @json($topVisitedLabels);
            const topVisitedVisits  = @json($topVisitedVisits);
            const routeLabels       = @json($routeLabels);
            const routeCarboys      = @json($routeCarboys);
            const dowLabels         = @json($dowLabels);
            const dowData           = @json($dowData);

            const PALETTE = [
                'rgba(14,165,233,0.8)',
                'rgba(16,185,129,0.8)',
                'rgba(139,92,246,0.8)',
                'rgba(245,158,11,0.8)',
                'rgba(239,68,68,0.8)',
                'rgba(236,72,153,0.8)',
                'rgba(20,184,166,0.8)',
                'rgba(251,146,60,0.8)',
            ];

            window.deliveryKpiCharts = window.deliveryKpiCharts || {};

            const createCharts = () => {
                if (typeof window.Chart === 'undefined') {
                    window.setTimeout(createCharts, 100);
                    return;
                }

                Object.values(window.deliveryKpiCharts).forEach((c) => c.destroy());
                window.deliveryKpiCharts = {};

                // 1 — Comparativa garrafones por repartidor
                window.deliveryKpiCharts.compCarboys = new Chart(
                    document.getElementById('comparisonCarboysChart'), {
                        type: 'bar',
                        data: {
                            labels: comparisonLabels,
                            datasets: [{
                                label: 'Garrafones',
                                data: comparisonCarboys,
                                backgroundColor: comparisonLabels.map((_, i) => PALETTE[i % PALETTE.length]),
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { beginAtZero: true, ticks: { precision: 0 } }
                            }
                        }
                    }
                );

                // 2 — Comparativa ganancias por repartidor
                window.deliveryKpiCharts.compIncome = new Chart(
                    document.getElementById('comparisonIncomeChart'), {
                        type: 'bar',
                        data: {
                            labels: comparisonLabels,
                            datasets: [{
                                label: 'Ganancias ($)',
                                data: comparisonIncome,
                                backgroundColor: comparisonLabels.map((_, i) => PALETTE[i % PALETTE.length]),
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: (v) => '$ ' + v.toLocaleString()
                                    }
                                }
                            }
                        }
                    }
                );

                // 3 — Garrafones por mes
                window.deliveryKpiCharts.carboyMonth = new Chart(
                    document.getElementById('carboysByMonthChart'), {
                        type: 'bar',
                        data: {
                            labels: monthLabels,
                            datasets: [{
                                label: 'Garrafones',
                                data: carboyMonthData,
                                backgroundColor: 'rgba(14,165,233,0.75)',
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: { beginAtZero: true, ticks: { precision: 0 } }
                            }
                        }
                    }
                );

                // 4 — Ganancias por mes
                window.deliveryKpiCharts.earningsMonth = new Chart(
                    document.getElementById('earningsByMonthChart'), {
                        type: 'bar',
                        data: {
                            labels: monthLabels,
                            datasets: [{
                                label: 'Ganancias ($)',
                                data: earningsMonthData,
                                backgroundColor: 'rgba(16,185,129,0.75)',
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: (v) => '$ ' + v.toLocaleString()
                                    }
                                }
                            }
                        }
                    }
                );

                // 5 — Clientes más visitados (horizontal bar)
                window.deliveryKpiCharts.topVisited = new Chart(
                    document.getElementById('topVisitedChart'), {
                        type: 'bar',
                        data: {
                            labels: topVisitedLabels,
                            datasets: [{
                                label: 'Visitas',
                                data: topVisitedVisits,
                                backgroundColor: 'rgba(139,92,246,0.8)',
                            }]
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                x: { beginAtZero: true, ticks: { precision: 0 } }
                            }
                        }
                    }
                );

                // 6 — Día de la semana (radar or bar)
                window.deliveryKpiCharts.dow = new Chart(
                    document.getElementById('dowChart'), {
                        type: 'radar',
                        data: {
                            labels: dowLabels,
                            datasets: [{
                                label: 'Ventas',
                                data: dowData,
                                backgroundColor: 'rgba(245,158,11,0.25)',
                                borderColor: 'rgba(245,158,11,0.9)',
                                pointBackgroundColor: 'rgba(245,158,11,0.9)',
                                pointRadius: 4,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                r: {
                                    beginAtZero: true,
                                    ticks: { precision: 0, stepSize: 1 }
                                }
                            }
                        }
                    }
                );

                // 7 — Garrafones por ruta (doughnut)
                window.deliveryKpiCharts.byRoute = new Chart(
                    document.getElementById('byRouteChart'), {
                        type: 'doughnut',
                        data: {
                            labels: routeLabels.length ? routeLabels : ['Sin datos'],
                            datasets: [{
                                data: routeCarboys.length ? routeCarboys : [1],
                                backgroundColor: PALETTE,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { position: 'bottom' }
                            }
                        }
                    }
                );
            };

            document.addEventListener('DOMContentLoaded', createCharts, { once: true });
            document.addEventListener('livewire:navigated', createCharts);
        })();
    </script>
</x-layouts::app>
