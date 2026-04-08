<x-layouts::app :title="__('Ingresos')">
    <div class="flex w-full flex-1 flex-col gap-6">

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Total de ingresos</p>
                <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">$ {{ number_format((float) $totalIncome, 2) }}</p>
                <p class="mt-1 text-sm text-emerald-600 dark:text-emerald-400">Automáticos + agregados desde sistema</p>
            </article>

            <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Ingresos automáticos (inputs)</p>
                <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">$ {{ number_format((float) $totalAutomaticIncome, 2) }}</p>
                <p class="mt-1 text-sm text-sky-600 dark:text-sky-400">{{ $automaticIncomes->total() }} registros</p>
            </article>

            <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Ingresos agregados</p>
                <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">$ {{ number_format((float) $totalSystemIncome, 2) }}</p>
                <p class="mt-1 text-sm text-purple-600 dark:text-purple-400">{{ $systemIncomes->total() }} registros</p>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.8fr)_minmax(320px,1fr)]">
            <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <div class="border-b border-neutral-200 px-6 py-5 dark:border-neutral-700">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Detalle de ingresos (inputs)</h2>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Cada ingreso toma el concepto guardado al registrar la venta/entrega.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200 text-sm dark:divide-neutral-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/70">
                            <tr class="text-left text-zinc-500 dark:text-zinc-400">
                                <th class="px-6 py-3 font-medium">ID input</th>
                                <th class="px-6 py-3 font-medium">Concepto</th>
                                <th class="px-6 py-3 font-medium">Cliente</th>
                                <th class="px-6 py-3 font-medium">Monto</th>
                                <th class="px-6 py-3 font-medium">Fecha</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                            @forelse ($automaticIncomes as $incomeItem)
                                <tr>
                                    <td class="px-6 py-4 text-zinc-900 dark:text-zinc-50">#{{ $incomeItem->id }}</td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">{{ $incomeItem->concept->name ?? '—' }}</td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">{{ $incomeItem->customer->name ?? '—' }}</td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">$ {{ number_format((float) $incomeItem->cost, 2) }}</td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">{{ $incomeItem->timestamp?->format('Y-m-d H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-zinc-500 dark:text-zinc-400">
                                        No hay ingresos registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-neutral-200 px-6 py-4 dark:border-neutral-700">
                    {{ $automaticIncomes->appends(request()->except('automatic_page'))->links() }}
                </div>
            </div>

            <aside class="rounded-xl border border-neutral-200 bg-white p-5 dark:border-neutral-700 dark:bg-zinc-900">
                <flux:heading size="lg">Ingresos automáticos por concepto</flux:heading>
                <flux:text class="mt-1">Distribución de ingresos usando el concepto capturado en cada input.</flux:text>

                <div class="mt-6 space-y-3">
                    @forelse ($automaticIncomesByConcept as $conceptName => $amount)
                        <div class="flex items-center justify-between rounded-lg border border-neutral-200 px-3 py-2 dark:border-neutral-700">
                            <span class="text-sm text-zinc-700 dark:text-zinc-300">{{ $conceptName }}</span>
                            <span class="text-sm font-semibold text-zinc-900 dark:text-zinc-50">$ {{ number_format((float) $amount, 2) }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Sin datos para mostrar.</p>
                    @endforelse
                </div>
            </aside>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.7fr)_minmax(320px,0.9fr)]">
            <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <div class="flex flex-col gap-4 border-b border-neutral-200 px-6 py-5 dark:border-neutral-700 md:flex-row md:items-end md:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Ingresos agregados desde sistema</h2>
                        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Registros manuales con concepto, descripción y cantidad.</p>
                    </div>

                    <flux:button type="button" variant="primary" color="sky" size="sm" icon="plus"
                        onclick="window.location.href='{{ route('incomes.index') }}'">
                        Nuevo ingreso agregado
                    </flux:button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200 text-sm dark:divide-neutral-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/70">
                            <tr class="text-left text-zinc-500 dark:text-zinc-400">
                                <th class="px-6 py-3 font-medium">Concepto</th>
                                <th class="px-6 py-3 font-medium">Descripción</th>
                                <th class="px-6 py-3 font-medium">Cantidad</th>
                                <th class="px-6 py-3 font-medium">Fecha</th>
                                <th class="px-6 py-3 font-medium text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                            @forelse ($systemIncomes as $systemIncomeItem)
                                <tr>
                                    <td class="px-6 py-4 text-zinc-900 dark:text-zinc-50">{{ $systemIncomeItem->concept->name ?? '—' }}</td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">{{ $systemIncomeItem->description ?: '—' }}</td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">$ {{ number_format((float) $systemIncomeItem->amount, 2) }}</td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">{{ $systemIncomeItem->timestamp?->format('Y-m-d H:i') }}</td>
                                    <td class="px-6 py-4 align-top">
                                        <div class="flex justify-end gap-2">
                                            <flux:button type="button" icon="pencil-square" size="sm" variant="filled"
                                                class="bg-zinc-500 hover:bg-zinc-600"
                                                onclick="window.location.href='{{ route('incomes.edit', $systemIncomeItem) }}'">
                                                Editar
                                            </flux:button>

                                            <form method="POST" action="{{ route('incomes.destroy', $systemIncomeItem) }}"
                                                onsubmit="return confirm('¿Seguro que deseas eliminar este ingreso?');">
                                                @csrf
                                                @method('DELETE')
                                                <flux:button type="submit" icon="trash" size="sm" variant="danger">
                                                    Eliminar
                                                </flux:button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-zinc-500 dark:text-zinc-400">
                                        No hay ingresos agregados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-neutral-200 px-6 py-4 dark:border-neutral-700">
                    {{ $systemIncomes->appends(request()->except('system_page'))->links() }}
                </div>
            </div>

            <aside class="rounded-xl border border-neutral-200 bg-white p-5 dark:border-neutral-700 dark:bg-zinc-900">
                <div>
                    <flux:heading size="lg">
                        @isset($systemIncome)
                            Editar ingreso agregado
                        @else
                            Nuevo ingreso agregado
                        @endisset
                    </flux:heading>
                    <flux:text class="mt-1">
                        @isset($systemIncome)
                            Actualiza concepto, descripción y cantidad.
                        @else
                            Agrega un ingreso manual desde el sistema.
                        @endisset
                    </flux:text>
                </div>

                <form method="POST"
                    action="@isset($systemIncome){{ route('incomes.update', $systemIncome) }}@else{{ route('incomes.store') }}@endisset"
                    class="mt-6 space-y-5">
                    @csrf
                    @isset($systemIncome)
                        @method('PUT')
                    @endisset

                    <div>
                        <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Concepto</label>
                        <select name="concept_id" required
                            class="block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-xs outline-none transition focus:border-zinc-400 focus:ring-2 focus:ring-accent focus:ring-offset-2 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 dark:focus:border-zinc-600 dark:focus:ring-offset-zinc-900">
                            <option value="">Selecciona un concepto</option>
                            @foreach ($concepts as $concept)
                                <option value="{{ $concept->id }}" @if (isset($systemIncome) && $systemIncome->concept_id === $concept->id) selected @endif>
                                    {{ $concept->name }} ({{ $concept->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <flux:input name="description" label="Descripción" type="text"
                        value="{{ isset($systemIncome) ? $systemIncome->description : '' }}" />

                    <flux:input name="amount" label="Cantidad" type="number" step="0.01" min="0" required
                        value="{{ isset($systemIncome) ? $systemIncome->amount : '' }}" />

                    <flux:input name="timestamp" label="Fecha/Hora" type="datetime-local"
                        value="{{ isset($systemIncome) && $systemIncome->timestamp ? $systemIncome->timestamp->format('Y-m-d\\TH:i') : '' }}" />

                    <div class="flex justify-end gap-3">
                        <flux:button type="button" size="sm" variant="danger"
                            onclick="window.location.href='{{ route('incomes.index') }}'">
                            Cancelar
                        </flux:button>
                        <flux:button variant="primary" color="sky" size="sm" type="submit">
                            @isset($systemIncome)
                                Actualizar ingreso
                            @else
                                Guardar ingreso
                            @endisset
                        </flux:button>
                    </div>
                </form>
            </aside>
        </section>

        <section class="grid gap-6 xl:grid-cols-2">
            <div class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <flux:heading size="lg">Ingresos agregados por concepto</flux:heading>
                <div class="mt-4 space-y-3">
                    @forelse ($systemIncomesByConcept as $conceptName => $amount)
                        <div class="flex items-center justify-between rounded-lg border border-neutral-200 px-3 py-2 dark:border-neutral-700">
                            <span class="text-sm text-zinc-700 dark:text-zinc-300">{{ $conceptName }}</span>
                            <span class="text-sm font-semibold text-zinc-900 dark:text-zinc-50">$ {{ number_format((float) $amount, 2) }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Sin datos para mostrar.</p>
                    @endforelse
                </div>
            </div>
        </section>

    </div>
</x-layouts::app>
