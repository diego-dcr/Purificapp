<x-layouts::app :title="__('Egresos')">
    <div class="flex w-full flex-1 flex-col gap-6">

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Total de egresos</p>
                <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">$ {{ number_format((float) $totalExpense, 2) }}</p>
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">Acumulado registrado</p>
            </article>

            <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Registros de egreso</p>
                <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ $expenses->total() }}</p>
                <p class="mt-1 text-sm text-sky-600 dark:text-sky-400">Con concepto ligado</p>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.7fr)_minmax(320px,0.9fr)]">
            <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <div class="flex flex-col gap-4 border-b border-neutral-200 px-6 py-5 dark:border-neutral-700 md:flex-row md:items-end md:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Gestión de egresos</h2>
                        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Registra egresos y liga un concepto con la cantidad correspondiente.</p>
                    </div>

                    <flux:button type="button" variant="primary" color="sky" size="sm" icon="plus"
                        onclick="window.location.href='{{ route('expenses.index') }}'">
                        Nuevo egreso
                    </flux:button>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200 text-sm dark:divide-neutral-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/70">
                            <tr class="text-left text-zinc-500 dark:text-zinc-400">
                                <th class="px-6 py-3 font-medium">Concepto</th>
                                <th class="px-6 py-3 font-medium">Monto</th>
                                <th class="px-6 py-3 font-medium">Descripción</th>
                                <th class="px-6 py-3 font-medium">Fecha</th>
                                <th class="px-6 py-3 font-medium text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                            @forelse ($expenses as $expenseItem)
                                <tr>
                                    <td class="px-6 py-4 text-zinc-900 dark:text-zinc-50">{{ $expenseItem->concept->name ?? '—' }}</td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">$ {{ number_format((float) $expenseItem->amount, 2) }}</td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">{{ $expenseItem->description ?: '—' }}</td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">{{ $expenseItem->timestamp?->format('Y-m-d H:i') }}</td>
                                    <td class="px-6 py-4 align-top">
                                        <div class="flex justify-end gap-2">
                                            <flux:button type="button" icon="pencil-square" size="sm" variant="filled"
                                                class="bg-zinc-500 hover:bg-zinc-600"
                                                onclick="window.location.href='{{ route('expenses.edit', $expenseItem) }}'">
                                                Editar
                                            </flux:button>

                                            <form method="POST" action="{{ route('expenses.destroy', $expenseItem) }}"
                                                onsubmit="return confirm('¿Seguro que deseas eliminar este egreso?');">
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
                                        No hay egresos registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-neutral-200 px-6 py-4 dark:border-neutral-700">
                    {{ $expenses->links() }}
                </div>
            </div>

            <aside class="rounded-xl border border-neutral-200 bg-white p-5 dark:border-neutral-700 dark:bg-zinc-900">
                <div>
                    <flux:heading size="lg">
                        @isset($expense)
                            Editar egreso
                        @else
                            Nuevo egreso
                        @endisset
                    </flux:heading>
                    <flux:text class="mt-1">
                        @isset($expense)
                            Actualiza el concepto y la cantidad de egreso.
                        @else
                            Registra un egreso ligándolo a un concepto.
                        @endisset
                    </flux:text>
                </div>

                <form method="POST"
                    action="@isset($expense){{ route('expenses.update', $expense) }}@else{{ route('expenses.store') }}@endisset"
                    class="mt-6 space-y-5">
                    @csrf
                    @isset($expense)
                        @method('PUT')
                    @endisset

                    <div>
                        <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Concepto</label>
                        <select name="concept_id" required
                            class="block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-xs outline-none transition focus:border-zinc-400 focus:ring-2 focus:ring-accent focus:ring-offset-2 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 dark:focus:border-zinc-600 dark:focus:ring-offset-zinc-900">
                            <option value="">Selecciona un concepto</option>
                            @foreach ($concepts as $concept)
                                <option value="{{ $concept->id }}" @if (isset($expense) && $expense->concept_id === $concept->id) selected @endif>
                                    {{ $concept->name }} ({{ $concept->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <flux:input name="amount" label="Cantidad de egreso" type="number" step="0.01" min="0" required
                        value="{{ isset($expense) ? $expense->amount : '' }}" />

                    <flux:input name="description" label="Descripción (opcional)" type="text"
                        value="{{ isset($expense) ? $expense->description : '' }}" />

                    <flux:input name="timestamp" label="Fecha/Hora" type="datetime-local"
                        value="{{ isset($expense) && $expense->timestamp ? $expense->timestamp->format('Y-m-d\\TH:i') : '' }}" />

                    <div class="flex justify-end gap-3">
                        <flux:button type="button" size="sm" variant="danger"
                            onclick="window.location.href='{{ route('expenses.index') }}'">
                            Cancelar
                        </flux:button>
                        <flux:button variant="primary" color="sky" size="sm" type="submit">
                            @isset($expense)
                                Actualizar egreso
                            @else
                                Guardar egreso
                            @endisset
                        </flux:button>
                    </div>
                </form>
            </aside>
        </section>

    </div>
</x-layouts::app>
