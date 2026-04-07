<x-layouts::app :title="__('Lotes')">
    <div class="flex w-full flex-1 flex-col gap-6">

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            <article
                class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Lotes registrados</p>
                <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ count($lots) }}</p>
                <p class="mt-1 text-sm text-emerald-600 dark:text-emerald-400">Total en el sistema</p>
            </article>

            <article
                class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Garrafones en lotes</p>
                <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ $lots->sum('carboys_count') }}</p>
                <p class="mt-1 text-sm text-sky-600 dark:text-sky-400">Inventario asociado</p>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.6fr)_minmax(320px,0.9fr)]">
            <div
                class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <div
                    class="flex flex-col gap-4 border-b border-neutral-200 px-6 py-5 dark:border-neutral-700 md:flex-row md:items-end md:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Gestión de lotes</h2>
                        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Administra los lotes para el control de garrafones.</p>
                    </div>

                    <div class="flex flex-col items-start gap-3 sm:flex-row sm:items-center">
                        <flux:button type="button" variant="primary" color="sky" size="sm" icon="plus"
                            onclick="window.location.href='{{ route('lots.index') }}'">
                            Nuevo lote
                        </flux:button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200 text-sm dark:divide-neutral-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/70">
                            <tr class="text-left text-zinc-500 dark:text-zinc-400">
                                <th class="px-6 py-3 font-medium">No. lote</th>
                                <th class="px-6 py-3 font-medium">Proveedor</th>
                                <th class="px-6 py-3 font-medium">Cantidad</th>
                                <th class="px-6 py-3 font-medium">Producción</th>
                                <th class="px-6 py-3 font-medium">Caducidad</th>
                                <th class="px-6 py-3 font-medium text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                            @forelse ($lots as $lotItem)
                                <tr>
                                    <td class="px-6 py-4 font-medium text-zinc-900 dark:text-zinc-50">
                                        {{ $lotItem->lot_number }}
                                    </td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">{{ $lotItem->supplier ?: '—' }}</td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">{{ $lotItem->quantity }}</td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">{{ $lotItem->production_date ?: '—' }}</td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">{{ $lotItem->expiration_date ?: '—' }}</td>
                                    <td class="px-6 py-4 align-top">
                                        <div class="flex justify-end gap-2">
                                            <flux:button type="button" icon="pencil-square" size="sm" variant="filled"
                                                class="bg-zinc-500 hover:bg-zinc-600"
                                                onclick="window.location.href='{{ route('lots.edit', $lotItem) }}'">
                                                Editar
                                            </flux:button>

                                            <form method="POST" action="{{ route('lots.destroy', $lotItem) }}"
                                                onsubmit="return confirm('¿Seguro que deseas eliminar este lote?');">
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
                                    <td colspan="6" class="px-6 py-8 text-center text-zinc-500 dark:text-zinc-400">
                                        No hay lotes registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <aside class="rounded-xl border border-neutral-200 bg-white p-5 dark:border-neutral-700 dark:bg-zinc-900">
                <div>
                    <flux:heading size="lg">
                        @isset($lot)
                            Editar lote
                        @else
                            Nuevo lote
                        @endisset
                    </flux:heading>
                    <flux:text class="mt-1">
                        @isset($lot)
                            Actualiza la información del lote.
                        @else
                            Registra un nuevo lote para inventario.
                        @endisset
                    </flux:text>
                </div>

                <form method="POST"
                    action="@isset($lot){{ route('lots.update', $lot) }}@else{{ route('lots.store') }}@endisset"
                    class="mt-6 space-y-5">
                    @csrf
                    @isset($lot)
                        @method('PUT')
                    @endisset

                    <flux:input name="lot_number" label="No. lote" type="text" required
                        value="{{ isset($lot) ? $lot->lot_number : '' }}" />

                    <flux:input name="supplier" label="Proveedor" type="text"
                        value="{{ isset($lot) ? $lot->supplier : '' }}" />

                    <flux:input name="quantity" label="Cantidad" type="number" min="0" required
                        value="{{ isset($lot) ? $lot->quantity : 0 }}" />

                    <flux:input name="observations" label="Observaciones" type="text"
                        value="{{ isset($lot) ? $lot->observations : '' }}" />

                    <flux:input name="production_date" label="Fecha de producción" type="date"
                        value="{{ isset($lot) ? $lot->production_date : '' }}" />

                    <flux:input name="expiration_date" label="Fecha de caducidad" type="date"
                        value="{{ isset($lot) ? $lot->expiration_date : '' }}" />

                    <div class="flex justify-end gap-3">
                        <flux:button type="button" size="sm" variant="danger"
                            onclick="window.location.href='{{ route('lots.index') }}'">
                            Cancelar
                        </flux:button>
                        <flux:button variant="primary" color="sky" size="sm" type="submit">
                            @isset($lot)
                                Actualizar lote
                            @else
                                Guardar lote
                            @endisset
                        </flux:button>
                    </div>
                </form>
            </aside>
        </section>

    </div>
</x-layouts::app>
