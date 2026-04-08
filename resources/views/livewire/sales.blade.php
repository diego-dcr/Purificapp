<div class="flex w-full flex-1 flex-col gap-6">
    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Registros de entrega/venta</p>
            <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ count($this->sales) }}</p>
            <p class="mt-1 text-sm text-emerald-600 dark:text-emerald-400">Total en tiempo real</p>
        </article>

        <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Garrafones escaneados</p>
            <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ $this->sales->sum('carboy_count') }}</p>
            <p class="mt-1 text-sm text-sky-600 dark:text-sky-400">Total agregado</p>
        </article>

        <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Clientes</p>
            <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ count($this->customers) }}</p>
            <p class="mt-1 text-sm text-purple-600 dark:text-purple-400">Disponibles</p>
        </article>

        <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Conceptos</p>
            <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ count($this->concepts) }}</p>
            <p class="mt-1 text-sm text-orange-600 dark:text-orange-400">Disponibles</p>
        </article>
    </section>

    @if (session('status'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-900/60 dark:bg-emerald-950/40 dark:text-emerald-300">
            {{ session('status') }}
        </div>
    @endif

    <section class="grid gap-6 xl:grid-cols-[minmax(0,1.9fr)_minmax(320px,0.9fr)]">
        <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
            <div class="flex flex-col gap-4 border-b border-neutral-200 px-6 py-5 dark:border-neutral-700 md:flex-row md:items-end md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Gestión de ventas</h2>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Actualización automática cada 5 segundos.</p>
                </div>

                <flux:button type="button" variant="primary" color="sky" size="sm" icon="plus" wire:click="create">
                    Nuevo registro
                </flux:button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200 text-sm dark:divide-neutral-700">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/70">
                        <tr class="text-left text-zinc-500 dark:text-zinc-400">
                            <th class="px-6 py-3 font-medium">Cliente</th>
                            <th class="px-6 py-3 font-medium">Concepto</th>
                            <th class="px-6 py-3 font-medium">Costo</th>
                            <th class="px-6 py-3 font-medium text-center">Garrafones</th>
                            <th class="px-6 py-3 font-medium">Fecha</th>
                            <th class="px-6 py-3 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                        @forelse ($this->sales as $saleItem)
                            <tr wire:key="sale-row-{{ $saleItem->id }}">
                                <td class="px-6 py-4 text-zinc-900 dark:text-zinc-50">
                                    <div class="font-medium">{{ $saleItem->customer->name }}</div>
                                    <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $saleItem->customer->number }}</div>
                                </td>
                                <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">{{ $saleItem->concept->name }}</td>
                                <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">$ {{ number_format((float) $saleItem->cost, 2) }}</td>
                                <td class="px-6 py-4 text-center text-zinc-600 dark:text-zinc-300">{{ $saleItem->carboy_count }}</td>
                                <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">{{ $saleItem->timestamp->format('Y-m-d H:i') }}</td>
                                <td class="px-6 py-4 align-top">
                                    <div class="flex justify-end gap-2">
                                        <flux:button type="button" icon="pencil-square" size="sm" variant="filled" class="bg-zinc-500 hover:bg-zinc-600" wire:click="edit({{ $saleItem->id }})">
                                            Editar
                                        </flux:button>
                                        <flux:button
                                            type="button"
                                            icon="eye"
                                            size="sm"
                                            variant="primary"
                                            color="lime"
                                            wire:click="showDetails({{ $saleItem->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="showDetails({{ $saleItem->id }})"
                                        >
                                            <span wire:loading.remove wire:target="showDetails({{ $saleItem->id }})">Ver detalles</span>
                                            <span wire:loading wire:target="showDetails({{ $saleItem->id }})">Cargando...</span>
                                        </flux:button>
                                        <flux:button type="button" icon="trash" size="sm" variant="danger" wire:click="delete({{ $saleItem->id }})" wire:confirm="¿Seguro que deseas eliminar este registro?">
                                            Eliminar
                                        </flux:button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-zinc-500 dark:text-zinc-400">No hay entregas/ventas registradas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <aside class="rounded-xl border border-neutral-200 bg-white p-5 dark:border-neutral-700 dark:bg-zinc-900">
            <div>
                <flux:heading size="lg">{{ $editingSaleId ? 'Editar registro' : 'Nuevo registro' }}</flux:heading>
                <flux:text class="mt-1">{{ $editingSaleId ? 'Actualiza los datos de la entrega/venta.' : 'Registra una nueva entrega/venta y sus garrafones.' }}</flux:text>
            </div>

            @if (! $showForm)
                <div class="mt-6 rounded-xl border border-dashed border-neutral-300 p-6 text-sm text-zinc-500 dark:border-neutral-700 dark:text-zinc-400">
                    Selecciona un registro para editar o crea uno nuevo.
                </div>
            @else
                <form wire:submit="save" class="mt-6 space-y-5">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Usuario</label>
                        <select wire:model="user_id" required class="block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100">
                            <option value="">Selecciona un usuario</option>
                            @foreach ($this->users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Ruta (opcional)</label>
                        <select wire:model="route_id" class="block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100">
                            <option value="">Selecciona una ruta</option>
                            @foreach ($this->routes as $route)
                                <option value="{{ $route->id }}">{{ $route->name }} ({{ $route->zone }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Cliente</label>
                        <select wire:model="customer_id" required class="block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100">
                            <option value="">Selecciona un cliente</option>
                            @foreach ($this->customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->number }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Concepto</label>
                        <select wire:model="concept_id" required class="block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100">
                            <option value="">Selecciona un concepto</option>
                            @foreach ($this->concepts as $concept)
                                <option value="{{ $concept->id }}">{{ $concept->name }} ({{ $concept->code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <flux:input wire:model="cost" label="Costo" type="number" step="0.01" min="0" required />

                    @if ($this->selectedConceptAllowsCarboys)
                        <div>
                            <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Códigos de barras</label>
                            <div class="space-y-2">
                                @foreach ($carboy_codebars as $index => $codebar)
                                    <div class="flex gap-2">
                                        <input wire:model="carboy_codebars.{{ $index }}" type="text" placeholder="Código de barra" class="block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100" />
                                        @if (count($carboy_codebars) > 1)
                                            <flux:button type="button" size="sm" variant="danger" wire:click="removeCarboyInput({{ $index }})">-</flux:button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <flux:button type="button" size="sm" variant="ghost" class="mt-2" wire:click="addCarboyInput">+ Agregar garrafón</flux:button>
                        </div>
                    @else
                        <div class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-sm text-amber-800 dark:border-amber-900/60 dark:bg-amber-950/30 dark:text-amber-300">
                            Este concepto no permite registrar garrafones.
                        </div>
                    @endif

                    <div class="flex justify-end gap-3">
                        <flux:button type="button" size="sm" variant="danger" wire:click="cancel">Cancelar</flux:button>
                        <flux:button variant="primary" color="sky" size="sm" type="submit">{{ $editingSaleId ? 'Actualizar registro' : 'Guardar registro' }}</flux:button>
                    </div>
                </form>
            @endif
        </aside>
    </section>

        {{-- Modal de detalles --}}
        @if ($showDetailsModal && $detailsSale)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
                <div class="w-full max-w-lg rounded-xl bg-white p-6 shadow-xl dark:bg-zinc-900">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Detalles de la entrega</h3>
                        <button class="text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-200" wire:click="closeDetails">&times;</button>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div><strong>Cliente:</strong> {{ $detailsSale->customer->name }} ({{ $detailsSale->customer->number }})</div>
                        <div><strong>Concepto:</strong> {{ $detailsSale->concept->name }} ({{ $detailsSale->concept->code }})</div>
                        <div><strong>Costo:</strong> ${{ number_format((float) $detailsSale->cost, 2) }}</div>
                        <div><strong>Garrafones:</strong> {{ $detailsSale->carboy_count }}</div>
                        <div><strong>Fecha:</strong> {{ $detailsSale->timestamp->format('Y-m-d H:i') }}</div>
                        <div><strong>Usuario:</strong> {{ $detailsSale->user->name }}</div>
                        <div><strong>Ruta:</strong> {{ $detailsSale->route?->name ?? '-' }}</div>
                        <div><strong>Códigos de garrafones:</strong>
                            <ul class="list-disc ml-6">
                                @if (!empty($detailsSale->carboySales))
                                    @foreach ($detailsSale->carboySales as $jug)
                                        <li>{{ $jug->carboy_codebar }}</li>
                                    @endforeach
                                @else
                                    <li>No hay garrafones registrados</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="flex justify-end mt-6">
                        <flux:button type="button" size="sm" variant="primary" wire:click="closeDetails">Cerrar</flux:button>
                    </div>
                </div>
            </div>
        @endif
</div>
