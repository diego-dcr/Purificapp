<div class="flex w-full flex-1 flex-col gap-6" wire:poll.5s>
    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Total de retornos</p>
            <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ count($this->outputs) }}</p>
            <p class="mt-1 text-sm text-sky-600 dark:text-sky-400">Actualización en vivo</p>
        </article>

        <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Garrafones devueltos</p>
            <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ $this->outputs->sum('carboy_count') }}</p>
            <p class="mt-1 text-sm text-orange-600 dark:text-orange-400">Códigos ligados</p>
        </article>

        <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Promedio por retorno</p>
            <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">
                @if (count($this->outputs) > 0)
                    {{ number_format($this->outputs->sum('carboy_count') / count($this->outputs), 1) }}
                @else
                    0
                @endif
            </p>
            <p class="mt-1 text-sm text-purple-600 dark:text-purple-400">Garrafones/movimiento</p>
        </article>
    </section>

    <section class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
        <div class="border-b border-neutral-200 px-6 py-5 dark:border-neutral-700">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Movimientos de salida</h2>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Consulta el registro de garrafones devueltos. Usa "Ver detalles" para ver los códigos individuales.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-neutral-200 text-sm dark:divide-neutral-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800/70">
                    <tr class="text-left text-zinc-500 dark:text-zinc-400">
                        <th class="px-6 py-3 font-medium">ID</th>
                        <th class="px-6 py-3 font-medium">Usuario</th>
                        <th class="px-6 py-3 font-medium">Ruta</th>
                        <th class="px-6 py-3 font-medium text-center">Garrafones</th>
                        <th class="px-6 py-3 font-medium">Fecha</th>
                        <th class="px-6 py-3 font-medium text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    @forelse ($this->outputs as $item)
                        <tr>
                            <td class="px-6 py-4">#{{ $item->id }}</td>
                            <td class="px-6 py-4">{{ $item->user->name ?? '—' }}</td>
                            <td class="px-6 py-4">{{ $item->route->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-center font-semibold text-orange-600 dark:text-orange-400">{{ $item->carboy_count }}</td>
                            <td class="px-6 py-4">{{ $item->timestamp?->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4 text-right">
                                <flux:button type="button" size="sm" icon="eye" variant="primary" color="lime" wire:click="showOutputDetails({{ $item->id }})">Ver detalles</flux:button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-8 text-center text-zinc-500 dark:text-zinc-400">No hay salidas registradas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    @if ($this->selectedOutputId)
        <section class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
            <div class="mb-4 flex items-center justify-between">
                <flux:heading size="lg">Detalle de salida</flux:heading>
                <flux:button type="button" size="sm" variant="danger" wire:click="closeDetails">Cerrar</flux:button>
            </div>

            @php
                $retorno = $this->outputs->find($this->selectedOutputId);
            @endphp

            @if ($retorno)
                <p class="mb-3 text-sm text-zinc-500 dark:text-zinc-400">
                    Salida #{{ $retorno->id }} · Usuario: {{ $retorno->user->name ?? '—' }} · Fecha: {{ $retorno->timestamp?->format('Y-m-d H:i') }}
                </p>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200 text-sm dark:divide-neutral-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/70">
                            <tr class="text-left text-zinc-500 dark:text-zinc-400">
                                <th class="px-6 py-3 font-medium">Código de garrafón</th>
                                <th class="px-6 py-3 font-medium">Fecha de registro</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                            @forelse ($retorno->carboyOutputs as $carboy)
                                <tr>
                                    <td class="px-6 py-4 font-mono text-orange-600 dark:text-orange-400">{{ $carboy->carboy_codebar }}</td>
                                    <td class="px-6 py-4">{{ $carboy->timestamp?->format('Y-m-d H:i') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="px-6 py-8 text-center text-zinc-500 dark:text-zinc-400">Sin códigos ligados.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    @endif
</div>
