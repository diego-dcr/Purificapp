<x-layouts::app :title="__('Movimientos')">
    <div class="flex w-full flex-1 flex-col gap-6">

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Ventas</p>
                <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ count($sales) }}</p>
                <p class="mt-1 text-sm text-emerald-600 dark:text-emerald-400">Total registradas</p>
            </article>

            <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Retornos</p>
                <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ count($retornos) }}</p>
                <p class="mt-1 text-sm text-sky-600 dark:text-sky-400">Total registradas</p>
            </article>

            <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Garrafones en ventas</p>
                <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ $sales->sum('carboy_count') }}</p>
                <p class="mt-1 text-sm text-purple-600 dark:text-purple-400">Códigos ligados</p>
            </article>

            <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Garrafones en retornos</p>
                <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ $retornos->sum('carboy_count') }}</p>
                <p class="mt-1 text-sm text-orange-600 dark:text-orange-400">Códigos ligados</p>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-2">
            <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <div class="border-b border-neutral-200 px-6 py-5 dark:border-neutral-700">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Movimientos de venta</h2>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Consulta el detalle de cada venta y sus garrafones asociados.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200 text-sm dark:divide-neutral-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/70">
                            <tr class="text-left text-zinc-500 dark:text-zinc-400">
                                <th class="px-6 py-3 font-medium">ID</th>
                                <th class="px-6 py-3 font-medium">Cliente</th>
                                <th class="px-6 py-3 font-medium text-center">Garrafones</th>
                                <th class="px-6 py-3 font-medium">Fecha creación</th>
                                <th class="px-6 py-3 font-medium text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                            @forelse ($sales as $saleItem)
                                <tr>
                                    <td class="px-6 py-4 text-zinc-900 dark:text-zinc-50">#{{ $saleItem->id }}</td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">{{ $saleItem->customer->name ?? '—' }}</td>
                                    <td class="px-6 py-4 text-center text-zinc-600 dark:text-zinc-300">{{ $saleItem->carboy_count }}</td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">{{ $saleItem->timestamp?->format('Y-m-d H:i') }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-end">
                                            <flux:button type="button" size="sm" icon="eye" variant="primary" color="lime"
                                                onclick="window.location.href='{{ route('movements.sales.show', $saleItem) }}'">
                                                Ver detalles
                                            </flux:button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-zinc-500 dark:text-zinc-400">
                                        No hay movimientos de venta registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <div class="border-b border-neutral-200 px-6 py-5 dark:border-neutral-700">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Movimientos de retorno</h2>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Consulta el detalle de cada retorno y sus garrafones asociados.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200 text-sm dark:divide-neutral-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/70">
                            <tr class="text-left text-zinc-500 dark:text-zinc-400">
                                <th class="px-6 py-3 font-medium">ID</th>
                                <th class="px-6 py-3 font-medium">Usuario</th>
                                <th class="px-6 py-3 font-medium text-center">Garrafones</th>
                                <th class="px-6 py-3 font-medium">Fecha creación</th>
                                <th class="px-6 py-3 font-medium text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                            @forelse ($retornos as $retornoItem)
                                <tr>
                                    <td class="px-6 py-4 text-zinc-900 dark:text-zinc-50">#{{ $retornoItem->id }}</td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">{{ $retornoItem->user->name ?? '—' }}</td>
                                    <td class="px-6 py-4 text-center text-zinc-600 dark:text-zinc-300">{{ $retornoItem->carboy_count }}</td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">{{ $retornoItem->timestamp?->format('Y-m-d H:i') }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-end">
                                            <flux:button type="button" size="sm" variant="primary" color="lime" icon="eye"
                                                onclick="window.location.href='{{ route('movements.retornos.show', $retornoItem) }}'">
                                                Ver detalles
                                            </flux:button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-zinc-500 dark:text-zinc-400">
                                        No hay movimientos de retorno registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

    </div>
</x-layouts::app>
