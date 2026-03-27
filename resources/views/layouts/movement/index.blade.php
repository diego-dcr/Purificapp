<x-layouts::app :title="__('Movimientos')">
    <div class="flex w-full flex-1 flex-col gap-6">

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Entradas (inputs)</p>
                <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ count($inputs) }}</p>
                <p class="mt-1 text-sm text-emerald-600 dark:text-emerald-400">Total registradas</p>
            </article>

            <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Salidas (outputs)</p>
                <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ count($outputs) }}</p>
                <p class="mt-1 text-sm text-sky-600 dark:text-sky-400">Total registradas</p>
            </article>

            <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Garrafones en inputs</p>
                <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ $inputs->sum('waterjug_count') }}</p>
                <p class="mt-1 text-sm text-purple-600 dark:text-purple-400">Códigos ligados</p>
            </article>

            <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Garrafones en outputs</p>
                <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ $outputs->sum('waterjug_count') }}</p>
                <p class="mt-1 text-sm text-orange-600 dark:text-orange-400">Códigos ligados</p>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-2">
            <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <div class="border-b border-neutral-200 px-6 py-5 dark:border-neutral-700">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Movimientos de entrada (inputs)</h2>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Consulta el detalle de cada entrega/venta y sus garrafones asociados.</p>
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
                            @forelse ($inputs as $inputItem)
                                <tr>
                                    <td class="px-6 py-4 text-zinc-900 dark:text-zinc-50">#{{ $inputItem->id }}</td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">{{ $inputItem->customer->name ?? '—' }}</td>
                                    <td class="px-6 py-4 text-center text-zinc-600 dark:text-zinc-300">{{ $inputItem->waterjug_count }}</td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">{{ $inputItem->timestamp?->format('Y-m-d H:i') }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-end">
                                            <flux:button type="button" size="sm" icon="eye" variant="primary" color="lime"
                                                onclick="window.location.href='{{ route('movements.inputs.show', $inputItem) }}'">
                                                Ver detalles
                                            </flux:button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-zinc-500 dark:text-zinc-400">
                                        No hay movimientos de entrada registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <div class="border-b border-neutral-200 px-6 py-5 dark:border-neutral-700">
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Movimientos de salida (outputs)</h2>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Consulta el detalle de cada salida y sus garrafones asociados.</p>
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
                            @forelse ($outputs as $outputItem)
                                <tr>
                                    <td class="px-6 py-4 text-zinc-900 dark:text-zinc-50">#{{ $outputItem->id }}</td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">{{ $outputItem->user->name ?? '—' }}</td>
                                    <td class="px-6 py-4 text-center text-zinc-600 dark:text-zinc-300">{{ $outputItem->waterjug_count }}</td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">{{ $outputItem->timestamp?->format('Y-m-d H:i') }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-end">
                                            <flux:button type="button" size="sm" variant="primary" color="lime" icon="eye"
                                                onclick="window.location.href='{{ route('movements.outputs.show', $outputItem) }}'">
                                                Ver detalles
                                            </flux:button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-zinc-500 dark:text-zinc-400">
                                        No hay movimientos de salida registrados.
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
