<div class="flex w-full flex-1 flex-col gap-6" wire:poll.5s>
    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Ventas</p>
            <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ $this->salesTotal }}</p>
            <p class="mt-1 text-sm text-emerald-600 dark:text-emerald-400">Actualización en vivo</p>
        </article>

        <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Retornos</p>
            <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ $this->outputsTotal }}</p>
            <p class="mt-1 text-sm text-sky-600 dark:text-sky-400">Actualización en vivo</p>
        </article>

        <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Garrafones en ventas</p>
            <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ $this->salesCarboyTotal }}</p>
            <p class="mt-1 text-sm text-purple-600 dark:text-purple-400">Códigos ligados</p>
        </article>

        <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Garrafones en retornos</p>
            <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ $this->outputsCarboyTotal }}</p>
            <p class="mt-1 text-sm text-orange-600 dark:text-orange-400">Códigos ligados</p>
        </article>
    </section>

    <section class="grid gap-6 xl:grid-cols-2">
        <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
            <div class="border-b border-neutral-200 px-6 py-5 dark:border-neutral-700">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Movimientos de venta</h2>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Usa "Ver detalles" para consultar códigos de garrafón y fecha.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200 text-sm dark:divide-neutral-700">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/70">
                        <tr class="text-left text-zinc-500 dark:text-zinc-400">
                            <th class="px-6 py-3 font-medium">ID</th>
                            <th class="px-6 py-3 font-medium">Cliente</th>
                            <th class="px-6 py-3 font-medium text-center">Garrafones</th>
                            <th class="px-6 py-3 font-medium">Fecha</th>
                            <th class="px-6 py-3 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                        @forelse ($this->sales as $item)
                            <tr>
                                <td class="px-6 py-4">#{{ $item->id }}</td>
                                <td class="px-6 py-4">{{ $item->customer->name ?? '—' }}</td>
                                <td class="px-6 py-4 text-center">{{ $item->carboy_count }}</td>
                                <td class="px-6 py-4">{{ $item->timestamp?->format('Y-m-d H:i') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <flux:button type="button" size="sm" icon="eye" variant="primary" color="lime" wire:click="showSaleDetails({{ $item->id }})">Ver detalles</flux:button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-8 text-center text-zinc-500 dark:text-zinc-400">No hay ventas registradas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-neutral-200 px-6 py-4 dark:border-neutral-700">
                {{ $this->sales->links() }}
            </div>
        </div>

        <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
            <div class="border-b border-neutral-200 px-6 py-5 dark:border-neutral-700">
                <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Movimientos de retorno</h2>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Usa "Ver detalles" para consultar códigos de garrafón y fecha.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200 text-sm dark:divide-neutral-700">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/70">
                        <tr class="text-left text-zinc-500 dark:text-zinc-400">
                            <th class="px-6 py-3 font-medium">ID</th>
                            <th class="px-6 py-3 font-medium">Usuario</th>
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
                                <td class="px-6 py-4 text-center">{{ $item->carboy_count }}</td>
                                <td class="px-6 py-4">{{ $item->timestamp?->format('Y-m-d H:i') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <flux:button type="button" size="sm" icon="eye" variant="primary" color="lime" wire:click="showOutputDetails({{ $item->id }})">Ver detalles</flux:button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-8 text-center text-zinc-500 dark:text-zinc-400">No hay retornos registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-neutral-200 px-6 py-4 dark:border-neutral-700">
                {{ $this->outputs->links() }}
            </div>
        </div>
    </section>

    @if ($this->selectedSale || $this->selectedOutput)
        <section class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
            <div class="mb-4 flex items-center justify-between">
                <flux:heading size="lg">Detalle de movimiento</flux:heading>
                <flux:button type="button" size="sm" variant="danger" wire:click="closeDetails">Cerrar</flux:button>
            </div>

            @if ($this->selectedSale)
                <p class="mb-3 text-sm text-zinc-500 dark:text-zinc-400">Venta #{{ $this->selectedSale->id }} · Fecha: {{ $this->selectedSale->timestamp?->format('Y-m-d H:i') }}</p>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200 text-sm dark:divide-neutral-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/70">
                            <tr class="text-left text-zinc-500 dark:text-zinc-400">
                                <th class="px-6 py-3 font-medium">Código de garrafón</th>
                                <th class="px-6 py-3 font-medium">Fecha de registro</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                            @forelse ($this->selectedSale->carboySales as $wj)
                                <tr>
                                    <td class="px-6 py-4">{{ $wj->carboy_codebar }}</td>
                                    <td class="px-6 py-4">{{ $wj->timestamp?->format('Y-m-d H:i') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="px-6 py-8 text-center text-zinc-500 dark:text-zinc-400">Sin códigos ligados.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @endif

            @if ($this->selectedOutput)
                <p class="mb-3 text-sm text-zinc-500 dark:text-zinc-400">Retorno #{{ $this->selectedOutput->id }} · Fecha: {{ $this->selectedOutput->timestamp?->format('Y-m-d H:i') }}</p>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200 text-sm dark:divide-neutral-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/70">
                            <tr class="text-left text-zinc-500 dark:text-zinc-400">
                                <th class="px-6 py-3 font-medium">Código de garrafón</th>
                                <th class="px-6 py-3 font-medium">Fecha de registro</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                            @forelse ($this->selectedOutput->carboyOutputs as $wj)
                                <tr>
                                    <td class="px-6 py-4">{{ $wj->carboy_codebar }}</td>
                                    <td class="px-6 py-4">{{ $wj->timestamp?->format('Y-m-d H:i') }}</td>
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
