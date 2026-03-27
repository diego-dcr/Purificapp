<div class="flex w-full flex-1 flex-col gap-6">
    <div class="grid gap-4 md:grid-cols-2">
        <div class="rounded-xl border border-neutral-200 bg-white p-5 dark:border-neutral-700 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Permisos registrados</p>
            <p class="mt-2 text-3xl font-semibold">{{ $this->totalPermissions }}</p>
        </div>

        <div class="rounded-xl border border-neutral-200 bg-white p-5 dark:border-neutral-700 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Gestionando</p>
            <p class="mt-2 text-3xl font-semibold text-violet-600 dark:text-violet-400">
                {{ $editingPermissionId ? 'Editar' : ($showForm ? 'Nuevo' : '—') }}
            </p>
        </div>
    </div>

    @if (session('status'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-900/60 dark:bg-emerald-950/40 dark:text-emerald-300">
            {{ session('status') }}
        </div>
    @endif

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.6fr)_minmax(340px,0.9fr)]">
        <section class="rounded-xl border border-neutral-200 bg-white p-5 dark:border-neutral-700 dark:bg-zinc-900">
            <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div>
                    <flux:heading size="lg">Administración de permisos</flux:heading>
                    <flux:text class="mt-1">Define los permisos disponibles para asignar a roles y usuarios.</flux:text>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                    <div class="min-w-64">
                        <flux:input wire:model.live.debounce.300ms="search" label="Buscar" type="text" size="sm" placeholder="Nombre del permiso" />
                    </div>

                    <flux:button variant="primary" color="sky" size="sm" icon="plus" wire:click="create">Nuevo permiso</flux:button>
                </div>
            </div>

            <div class="mt-6 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200 text-sm dark:divide-neutral-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/60">
                            <tr class="text-left text-zinc-500 dark:text-zinc-400">
                                <th class="px-4 py-3 font-medium">Permiso</th>
                                <th class="px-4 py-3 font-medium text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                            @forelse ($this->permissions as $permission)
                                <tr class="bg-white dark:bg-zinc-900">
                                    <td class="px-4 py-3 align-top">
                                        <span class="inline-flex rounded-full bg-violet-100 px-2.5 py-1 text-xs font-medium text-violet-700 dark:bg-violet-950/60 dark:text-violet-300">
                                            {{ $permission->name }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 align-top">
                                        <div class="flex justify-end gap-2">
                                            <flux:button
                                                size="sm"
                                                icon="pencil-square"
                                                variant="filled"
                                                class="bg-zinc-500 hover:bg-zinc-600"
                                                wire:click="edit({{ $permission->id }})"
                                            >
                                                Editar
                                            </flux:button>

                                            <flux:button
                                                size="sm"
                                                icon="trash"
                                                variant="danger"
                                                wire:click="delete({{ $permission->id }})"
                                                wire:confirm="¿Seguro que deseas eliminar este permiso? Se eliminará de todos los roles y usuarios que lo tengan."
                                            >
                                                Eliminar
                                            </flux:button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-4 py-8 text-center text-zinc-500 dark:text-zinc-400">
                                        No se encontraron permisos con el criterio actual.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $this->permissions->links() }}
            </div>
        </section>

        <aside class="rounded-xl border border-neutral-200 bg-white p-5 dark:border-neutral-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <flux:heading size="lg">{{ $editingPermissionId ? 'Editar permiso' : 'Nuevo permiso' }}</flux:heading>
                    <flux:text class="mt-1">
                        {{ $editingPermissionId ? 'Modifica el nombre del permiso.' : 'Define una clave que identifique la acción protegida.' }}
                    </flux:text>
                </div>
            </div>

            @if (! $showForm)
                <div class="mt-6 rounded-xl border border-dashed border-neutral-300 p-6 text-sm text-zinc-500 dark:border-neutral-700 dark:text-zinc-400">
                    Selecciona un permiso para editarlo o crea uno nuevo desde el botón superior.
                </div>
            @else
                <form wire:submit="save" class="mt-6 space-y-5">
                    <div>
                        <flux:input wire:model="name" label="Nombre del permiso" type="text" required placeholder="users.create" />
                        <p class="mt-1.5 text-xs text-zinc-400 dark:text-zinc-500">
                            Usa una convención consistente, p.ej.: <code class="font-mono">modulo.accion</code>
                        </p>
                        @error('name')
                            <div class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3">
                        <flux:button type="button" size="sm" variant="danger" wire:click="cancel">Cancelar</flux:button>
                        <flux:button variant="primary" color="sky" size="sm" type="submit">
                            {{ $editingPermissionId ? 'Actualizar permiso' : 'Crear permiso' }}
                        </flux:button>
                    </div>
                </form>
            @endif
        </aside>
    </div>
</div>
