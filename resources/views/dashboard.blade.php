<x-layouts::app :title="__('Dashboard')">
    <div class="flex w-full flex-1 flex-col gap-6">
        <section class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-50">Dashboard financiero</h1>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Visualiza ingresos, egresos y clientes top. Cada grafica tiene su propio filtro.</p>
                </div>
            </div>
        </section>

        <section class="rounded-xl border border-emerald-200 bg-emerald-50/40 p-5 shadow-sm dark:border-emerald-900/60 dark:bg-emerald-950/20">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Reporte Excel de ingresos y egresos</h2>
                    <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-300">Selecciona un intervalo de fechas para generar el archivo.</p>
                </div>

                <form method="GET" action="{{ route('dashboard.export-finance-report') }}" class="grid w-full gap-3 md:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_auto] xl:w-auto">
                    <div>
                        <label class="mb-1 block text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Desde</label>
                        <input name="export_from_date" type="date" value="{{ $exportSelectedFromDate }}" class="block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100" />
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Hasta</label>
                        <input name="export_to_date" type="date" value="{{ $exportSelectedToDate }}" class="block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100" />
                    </div>

                    <div class="flex items-end">
                        <flux:button variant="primary" color="emerald" type="submit" class="w-full md:w-auto">
                            Exportar Excel
                        </flux:button>
                    </div>
                </form>
            </div>
        </section>

        <section class="grid gap-4 md:grid-cols-3">
            <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Total ingresos</p>
                <p class="mt-2 text-3xl font-semibold text-emerald-600 dark:text-emerald-400">$ {{ number_format($totalIncome, 2) }}</p>
                <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">Rango: {{ $totalsSelectedRange }}</p>
            </article>
            <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Total egresos</p>
                <p class="mt-2 text-3xl font-semibold text-rose-600 dark:text-rose-400">$ {{ number_format($totalExpense, 2) }}</p>
                <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">Rango: {{ $totalsSelectedRange }}</p>
            </article>
            <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Balance</p>
                <p class="mt-2 text-3xl font-semibold {{ $balance >= 0 ? 'text-sky-600 dark:text-sky-400' : 'text-amber-600 dark:text-amber-400' }}">$ {{ number_format($balance, 2) }}</p>
                <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">Ingresos - Egresos</p>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-2">
            <div class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Cantidad de ingresos y egresos por mes</h2>
                        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Anio {{ $monthlyYear }}</p>
                    </div>
                    <form method="GET" action="{{ route('dashboard') }}" class="flex items-end gap-2">
                        <input type="hidden" name="totals_from_date" value="{{ $totalsSelectedFromDate }}">
                        <input type="hidden" name="totals_to_date" value="{{ $totalsSelectedToDate }}">
                        <input type="hidden" name="top_from_date" value="{{ $topSelectedFromDate }}">
                        <input type="hidden" name="top_to_date" value="{{ $topSelectedToDate }}">
                        <input type="hidden" name="export_from_date" value="{{ $exportSelectedFromDate }}">
                        <input type="hidden" name="export_to_date" value="{{ $exportSelectedToDate }}">
                        <div>
                            <label class="mb-1 block text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Filtro anual</label>
                            <select name="monthly_year" class="block rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100">
                                @foreach ($availableYears as $year)
                                    <option value="{{ $year }}" @selected((int) $monthlyYear === (int) $year)>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <flux:button size="sm" variant="primary" color="sky" type="submit">Aplicar</flux:button>
                    </form>
                </div>
                <div class="mt-4 h-80">
                    <canvas id="monthlyMovementsChart"></canvas>
                </div>
            </div>

            <div class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Ingreso total vs egreso total</h2>
                        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Rango {{ $totalsSelectedRange }}</p>
                    </div>
                    <form method="GET" action="{{ route('dashboard') }}" class="grid gap-2 sm:grid-cols-2 md:grid-cols-3">
                        <input type="hidden" name="monthly_year" value="{{ $monthlyYear }}">
                        <input type="hidden" name="top_from_date" value="{{ $topSelectedFromDate }}">
                        <input type="hidden" name="top_to_date" value="{{ $topSelectedToDate }}">
                        <input type="hidden" name="export_from_date" value="{{ $exportSelectedFromDate }}">
                        <input type="hidden" name="export_to_date" value="{{ $exportSelectedToDate }}">
                        <input name="totals_from_date" type="date" value="{{ $totalsSelectedFromDate }}" class="block rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100" />
                        <input name="totals_to_date" type="date" value="{{ $totalsSelectedToDate }}" class="block rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100" />
                        <flux:button size="sm" variant="primary" color="sky" type="submit">Filtrar</flux:button>
                    </form>
                </div>
                <div class="mt-4 h-80">
                    <canvas id="totalsChart"></canvas>
                </div>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-[1.4fr_1fr]">
            <div class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Top 10 clientes que mas compran agua</h2>
                        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Ordenado por garrafones vendidos. Rango {{ $topSelectedRange }}</p>
                    </div>
                    <form method="GET" action="{{ route('dashboard') }}" class="grid gap-2 sm:grid-cols-2 md:grid-cols-3">
                        <input type="hidden" name="monthly_year" value="{{ $monthlyYear }}">
                        <input type="hidden" name="totals_from_date" value="{{ $totalsSelectedFromDate }}">
                        <input type="hidden" name="totals_to_date" value="{{ $totalsSelectedToDate }}">
                        <input type="hidden" name="export_from_date" value="{{ $exportSelectedFromDate }}">
                        <input type="hidden" name="export_to_date" value="{{ $exportSelectedToDate }}">
                        <input name="top_from_date" type="date" value="{{ $topSelectedFromDate }}" class="block rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100" />
                        <input name="top_to_date" type="date" value="{{ $topSelectedToDate }}" class="block rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100" />
                        <flux:button size="sm" variant="primary" color="sky" type="submit">Filtrar</flux:button>
                    </form>
                </div>
                <div class="mt-4 h-96">
                    <canvas id="topCustomersChart"></canvas>
                </div>
            </div>

            <div class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Detalle top clientes</h2>
                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200 text-sm dark:divide-neutral-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/60">
                            <tr class="text-left text-zinc-500 dark:text-zinc-400">
                                <th class="px-3 py-2 font-medium">Cliente</th>
                                <th class="px-3 py-2 font-medium text-right">Garrafones</th>
                                <th class="px-3 py-2 font-medium text-right">Monto</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                            @forelse ($topCustomers as $customer)
                                <tr>
                                    <td class="px-3 py-2 text-zinc-700 dark:text-zinc-300">{{ $customer->customer_name }}</td>
                                    <td class="px-3 py-2 text-right text-zinc-700 dark:text-zinc-300">{{ (int) $customer->carboys_count }}</td>
                                    <td class="px-3 py-2 text-right text-zinc-700 dark:text-zinc-300">$ {{ number_format((float) $customer->total_amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-3 py-6 text-center text-zinc-500 dark:text-zinc-400">No hay compras en el rango seleccionado.</td>
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
            const monthLabels = @json($monthLabels);
            const incomeCountByMonth = @json($incomeCountByMonth);
            const expenseCountByMonth = @json($expenseCountByMonth);
            const topCustomerLabels = @json($topCustomerLabels);
            const topCustomerValues = @json($topCustomerValues);
            const totalIncome = @json($totalIncome);
            const totalExpense = @json($totalExpense);

            window.dashboardCharts = window.dashboardCharts || {};

            const destroyIfExists = (key) => {
                if (window.dashboardCharts[key]) {
                    window.dashboardCharts[key].destroy();
                    delete window.dashboardCharts[key];
                }
            };

            const createCharts = () => {
                if (typeof window.Chart === 'undefined') {
                    window.setTimeout(createCharts, 100);
                    return;
                }

                const monthlyCanvas = document.getElementById('monthlyMovementsChart');
                const totalsCanvas = document.getElementById('totalsChart');
                const topCustomersCanvas = document.getElementById('topCustomersChart');

                if (monthlyCanvas) {
                    destroyIfExists('monthly');
                    window.dashboardCharts.monthly = new Chart(monthlyCanvas, {
                        type: 'bar',
                        data: {
                            labels: monthLabels,
                            datasets: [{
                                label: 'Ingresos',
                                data: incomeCountByMonth,
                                backgroundColor: 'rgba(16, 185, 129, 0.75)'
                            }, {
                                label: 'Egresos',
                                data: expenseCountByMonth,
                                backgroundColor: 'rgba(239, 68, 68, 0.75)'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: { precision: 0 }
                                }
                            }
                        }
                    });
                }

                if (totalsCanvas) {
                    destroyIfExists('totals');
                    window.dashboardCharts.totals = new Chart(totalsCanvas, {
                        type: 'doughnut',
                        data: {
                            labels: ['Ingresos', 'Egresos'],
                            datasets: [{
                                data: [totalIncome, totalExpense],
                                backgroundColor: ['rgba(16, 185, 129, 0.85)', 'rgba(239, 68, 68, 0.85)']
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false
                        }
                    });
                }

                if (topCustomersCanvas) {
                    destroyIfExists('topCustomers');
                    window.dashboardCharts.topCustomers = new Chart(topCustomersCanvas, {
                        type: 'bar',
                        data: {
                            labels: Array.isArray(topCustomerLabels) ? topCustomerLabels : [],
                            datasets: [{
                                label: 'Garrafones vendidos',
                                data: Array.isArray(topCustomerValues)
                                    ? topCustomerValues.map((value) => Number(value) || 0)
                                    : [],
                                backgroundColor: 'rgba(14, 165, 233, 0.8)'
                            }]
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                x: {
                                    beginAtZero: true,
                                    ticks: { precision: 0 }
                                }
                            }
                        }
                    });
                }
            };

            document.addEventListener('DOMContentLoaded', createCharts, { once: true });
            document.addEventListener('livewire:navigated', createCharts);
        })();
    </script>
</x-layouts::app>
