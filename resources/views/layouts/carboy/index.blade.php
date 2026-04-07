<x-layouts::app :title="__('Garrafones')">
    <div class="flex w-full flex-1 flex-col gap-6">

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            <article
                class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Garrafones registrados</p>
                <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ count($carboys) }}</p>
                <p class="mt-1 text-sm text-emerald-600 dark:text-emerald-400">Total en el sistema</p>
            </article>

            <article
                class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Lotes disponibles</p>
                <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ count($lots) }}</p>
                <p class="mt-1 text-sm text-sky-600 dark:text-sky-400">Para asignación</p>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.6fr)_minmax(320px,0.9fr)]">
            <div
                class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <div
                    class="flex flex-col gap-4 border-b border-neutral-200 px-6 py-5 dark:border-neutral-700 md:flex-row md:items-end md:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Gestión de garrafones</h2>
                        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Administra el inventario y estado de cada garrafón.</p>
                    </div>

                    <div class="flex flex-col items-start gap-3 sm:flex-row sm:items-center">
                        <flux:button type="button" variant="primary" color="sky" size="sm" icon="plus"
                            onclick="window.location.href='{{ route('carboys.index') }}'">
                            Nuevo garrafón
                        </flux:button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200 text-sm dark:divide-neutral-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/70">
                            <tr class="text-left text-zinc-500 dark:text-zinc-400">
                                <th class="px-6 py-3 font-medium">Código de barras</th>
                                <th class="px-6 py-3 font-medium">Estado conservación</th>
                                <th class="px-6 py-3 font-medium">Lote</th>
                                <th class="px-6 py-3 font-medium">Estatus</th>
                                <th class="px-6 py-3 font-medium">Registro</th>
                                <th class="px-6 py-3 font-medium text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                            @forelse ($carboys as $carboyItem)
                                <tr>
                                    <td class="px-6 py-4 font-medium text-zinc-900 dark:text-zinc-50">
                                        <code class="text-xs">{{ $carboyItem->barcode }}</code>
                                    </td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">{{ $carboyItem->conservation_state }}</td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">{{ $carboyItem->lot?->lot_number ?: '—' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex rounded-full bg-sky-100 px-2.5 py-1 text-xs font-medium text-sky-700 dark:bg-sky-950/70 dark:text-sky-300">
                                            {{ $carboyItem->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-zinc-500 dark:text-zinc-400">{{ $carboyItem->timestamp?->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4 align-top">
                                        <div class="flex justify-end gap-2">
                                            <flux:button type="button" icon="pencil-square" size="sm" variant="filled"
                                                class="bg-zinc-500 hover:bg-zinc-600"
                                                onclick="window.location.href='{{ route('carboys.edit', $carboyItem) }}'">
                                                Editar
                                            </flux:button>

                                            <form method="POST" action="{{ route('carboys.destroy', $carboyItem) }}"
                                                onsubmit="return confirm('¿Seguro que deseas eliminar este garrafón?');">
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
                                        No hay garrafones registrados.
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
                        @isset($carboy)
                            Editar garrafón
                        @else
                            Nuevo garrafón
                        @endisset
                    </flux:heading>
                    <flux:text class="mt-1">
                        @isset($carboy)
                            Actualiza los datos del garrafón.
                        @else
                            Registra un nuevo garrafón en un lote.
                        @endisset
                    </flux:text>
                </div>

                <form method="POST"
                    action="@isset($carboy){{ route('carboys.update', $carboy) }}@else{{ route('carboys.store') }}@endisset"
                    class="mt-6 space-y-5">
                    @csrf
                    @isset($carboy)
                        @method('PUT')
                    @endisset

                    <flux:input name="barcode" label="Código de barras" type="text" required
                        value="{{ isset($carboy) ? $carboy->barcode : '' }}" />

                    <flux:input name="conservation_state" label="Estado de conservación" type="text" required
                        value="{{ isset($carboy) ? $carboy->conservation_state : '' }}" />

                    <div>
                        <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Lote</label>
                        <select name="lot_id"
                            class="block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-xs outline-none transition focus:border-zinc-400 focus:ring-2 focus:ring-accent focus:ring-offset-2 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 dark:focus:border-zinc-600 dark:focus:ring-offset-zinc-900">
                            <option value="">Selecciona un lote</option>
                            @foreach ($lots as $lot)
                                <option value="{{ $lot->id }}" @if (isset($carboy) && $carboy->lot_id === $lot->id) selected @endif>
                                    {{ $lot->lot_number }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Estatus</label>
                        <select name="status"
                            class="block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-xs outline-none transition focus:border-zinc-400 focus:ring-2 focus:ring-accent focus:ring-offset-2 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 dark:focus:border-zinc-600 dark:focus:ring-offset-zinc-900">
                            @foreach (['En_planta', 'En_ruta', 'Con_cliente', 'Retornado', 'Perdido', 'Mantenimiento', 'Retirado'] as $status)
                                <option value="{{ $status }}" @if ((isset($carboy) && $carboy->status === $status) || (!isset($carboy) && $status === 'En_planta')) selected @endif>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <flux:input name="timestamp" label="Fecha/Hora de registro" type="datetime-local"
                        value="{{ isset($carboy) && $carboy->timestamp ? $carboy->timestamp->format('Y-m-d\\TH:i') : '' }}" />

                    <div class="flex justify-end gap-3">
                        <flux:button type="button" size="sm" variant="danger"
                            onclick="window.location.href='{{ route('carboys.index') }}'">
                            Cancelar
                        </flux:button>
                        <flux:button variant="primary" color="sky" size="sm" type="submit">
                            @isset($carboy)
                                Actualizar garrafón
                            @else
                                Guardar garrafón
                            @endisset
                        </flux:button>
                    </div>
                </form>
            </aside>
        </section>

    </div>
</x-layouts::app>
