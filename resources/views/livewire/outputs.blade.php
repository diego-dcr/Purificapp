<div class="flex w-full flex-1 flex-col gap-6" wire:poll.5s>
    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Salidas registradas</p>
            <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ count($this->outputs) }}</p>
            <p class="mt-1 text-sm text-emerald-600 dark:text-emerald-400">Total en tiempo real</p>
        </article>

        <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Garrafones procesados</p>
            <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ $this->outputs->sum('waterjug_count') }}</p>
            <p class="mt-1 text-sm text-sky-600 dark:text-sky-400">Total agregado</p>
        </article>

        <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Usuarios activos</p>
            <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ count($this->users) }}</p>
            <p class="mt-1 text-sm text-purple-600 dark:text-purple-400">Disponibles</p>
        </article>

        <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Rutas activas</p>
            <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ count($this->routes) }}</p>
            <p class="mt-1 text-sm text-orange-600 dark:text-orange-400">Disponibles</p>
        </article>
    </section>

    @if (session('status'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-900/60 dark:bg-emerald-950/40 dark:text-emerald-300">
            {{ session('status') }}
        </div>
    @endif

    <section class="grid gap-6 xl:grid-cols-[minmax(0,1.6fr)_minmax(320px,0.9fr)]">
        <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
            <div class="flex flex-col gap-4 border-b border-neutral-200 px-6 py-5 dark:border-neutral-700 md:flex-row md:items-end md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Gestión de salidas (Livewire)</h2>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Actualización automática cada 5 segundos.</p>
                </div>

                <flux:button type="button" variant="primary" color="sky" size="sm" icon="plus" wire:click="create">
                    Nueva salida
                </flux:button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200 text-sm dark:divide-neutral-700">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/70">
                        <tr class="text-left text-zinc-500 dark:text-zinc-400">
                            <th class="px-6 py-3 font-medium">Usuario</th>
                            <th class="px-6 py-3 font-medium">Ruta</th>
                            <th class="px-6 py-3 font-medium text-center">Garrafones</th>
                            <th class="px-6 py-3 font-medium">Fecha</th>
                            <th class="px-6 py-3 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                        @forelse ($this->outputs as $outputItem)
                            <tr>
                                <td class="px-6 py-4 font-medium text-zinc-900 dark:text-zinc-50">{{ $outputItem->user->name }}</td>
                                <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">{{ $outputItem->route->name ?? '—' }}</td>
                                <td class="px-6 py-4 text-center text-zinc-600 dark:text-zinc-300">{{ $outputItem->waterjug_count }}</td>
                                <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">{{ $outputItem->timestamp->format('Y-m-d H:i') }}</td>
                                <td class="px-6 py-4 align-top">
                                    <div class="flex justify-end gap-2">
                                        <flux:button type="button" icon="pencil-square" size="sm" variant="filled" class="bg-zinc-500 hover:bg-zinc-600" wire:click="edit({{ $outputItem->id }})">
                                            Editar
                                        </flux:button>
                                        <flux:button type="button" icon="trash" size="sm" variant="danger" wire:click="delete({{ $outputItem->id }})" wire:confirm="¿Seguro que deseas eliminar esta salida?">
                                            Eliminar
                                        </flux:button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-zinc-500 dark:text-zinc-400">No hay salidas registradas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <aside class="rounded-xl border border-neutral-200 bg-white p-5 dark:border-neutral-700 dark:bg-zinc-900">
            <div>
                <flux:heading size="lg">{{ $editingOutputId ? 'Editar salida' : 'Nueva salida' }}</flux:heading>
                <flux:text class="mt-1">{{ $editingOutputId ? 'Actualiza los datos de la salida.' : 'Registra una nueva salida de garrafones.' }}</flux:text>
            </div>

            @if (! $showForm)
                <div class="mt-6 rounded-xl border border-dashed border-neutral-300 p-6 text-sm text-zinc-500 dark:border-neutral-700 dark:text-zinc-400">
                    Selecciona una salida para editar o crea una nueva.
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
                        <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Códigos de barras</label>
                        <div class="space-y-2">
                            @foreach ($waterjug_codebars as $index => $codebar)
                                <div class="flex gap-2">
                                    <input wire:model="waterjug_codebars.{{ $index }}" type="text" placeholder="Código de barra" class="block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100" />
                                    @if (count($waterjug_codebars) > 1)
                                        <flux:button type="button" size="sm" variant="danger" wire:click="removeWaterjugInput({{ $index }})">-</flux:button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <flux:button type="button" size="sm" variant="ghost" class="mt-2" wire:click="addWaterjugInput">+ Agregar garrafón</flux:button>
                    </div>

                    <div class="flex justify-end gap-3">
                        <flux:button type="button" size="sm" variant="danger" wire:click="cancel">Cancelar</flux:button>
                        <flux:button variant="primary" color="sky" size="sm" type="submit">{{ $editingOutputId ? 'Actualizar salida' : 'Guardar salida' }}</flux:button>
                    </div>
                </form>
            @endif
        </aside>
    </section>
</div>
