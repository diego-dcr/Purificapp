<div class="flex w-full flex-1 flex-col gap-6">
    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-xl border border-neutral-200 bg-white p-5 dark:border-neutral-700 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Roles registrados</p>
            <p class="mt-2 text-3xl font-semibold">{{ $this->totalRoles }}</p>
        </div>

        <div class="rounded-xl border border-neutral-200 bg-white p-5 dark:border-neutral-700 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Permisos disponibles</p>
            <p class="mt-2 text-3xl font-semibold">{{ $this->totalPermissions }}</p>
        </div>

        <div class="rounded-xl border border-neutral-200 bg-white p-5 dark:border-neutral-700 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Permisos activos</p>
            <p class="mt-2 text-3xl font-semibold">{{ count($selectedPermissions) }}</p>
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
                    <flux:heading size="lg">Administración de roles</flux:heading>
                    <flux:text class="mt-1">Crea y edita roles, asignando los permisos que corresponde a cada uno.</flux:text>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                    <div class="min-w-64">
                        <flux:input wire:model.live.debounce.300ms="search" label="Buscar" type="text" size="sm" placeholder="Nombre del rol" />
                    </div>

                    <flux:button variant="primary" color="sky" size="sm" icon="plus" wire:click="create">Nuevo rol</flux:button>
                </div>
            </div>

            <div class="mt-6 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200 text-sm dark:divide-neutral-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/60">
                            <tr class="text-left text-zinc-500 dark:text-zinc-400">
                                <th class="px-4 py-3 font-medium">Nombre</th>
                                <th class="px-4 py-3 font-medium">Permisos asignados</th>
                                <th class="px-4 py-3 font-medium text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                            @forelse ($this->roles as $roleItem)
                                <tr class="bg-white dark:bg-zinc-900">
                                    <td class="px-4 py-3 align-top">
                                        <div class="font-medium text-zinc-900 dark:text-zinc-100">{{ $roleItem->name }}</div>
                                        <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $roleItem->permissions_count }} permisos</div>
                                    </td>
                                    <td class="px-4 py-3 align-top">
                                        <div class="flex flex-wrap gap-1.5">
                                            @forelse ($roleItem->permissions->take(5) as $perm)
                                                <span class="inline-flex rounded-full bg-violet-100 px-2.5 py-0.5 text-xs font-medium text-violet-700 dark:bg-violet-950/60 dark:text-violet-300">
                                                    {{ $perm->name }}
                                                </span>
                                            @empty
                                                <span class="text-xs text-zinc-400 dark:text-zinc-500">Sin permisos</span>
                                            @endforelse
                                            @if ($roleItem->permissions->count() > 5)
                                                <span class="inline-flex rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs font-medium text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">
                                                    +{{ $roleItem->permissions->count() - 5 }} más
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 align-top">
                                        <div class="flex justify-end gap-2">
                                            <flux:button
                                                size="sm"
                                                icon="pencil-square"
                                                variant="filled"
                                                class="bg-zinc-500 hover:bg-zinc-600"
                                                wire:click="edit({{ $roleItem->id }})"
                                            >
                                                Editar
                                            </flux:button>

                                            <flux:button
                                                size="sm"
                                                icon="trash"
                                                variant="danger"
                                                wire:click="delete({{ $roleItem->id }})"
                                                wire:confirm="¿Seguro que deseas eliminar este rol? Los usuarios que lo tengan asignado perderán sus permisos."
                                            >
                                                Eliminar
                                            </flux:button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-center text-zinc-500 dark:text-zinc-400">
                                        No se encontraron roles con el criterio actual.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $this->roles->links() }}
            </div>
        </section>

        <aside class="rounded-xl border border-neutral-200 bg-white p-5 dark:border-neutral-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <flux:heading size="lg">{{ $editingRoleId ? 'Editar rol' : 'Nuevo rol' }}</flux:heading>
                    <flux:text class="mt-1">
                        {{ $editingRoleId ? 'Modifica el nombre y los permisos del rol.' : 'Define un nombre y asigna los permisos correspondientes.' }}
                    </flux:text>
                </div>
            </div>

            @if (! $showForm)
                <div class="mt-6 rounded-xl border border-dashed border-neutral-300 p-6 text-sm text-zinc-500 dark:border-neutral-700 dark:text-zinc-400">
                    Selecciona un rol para editarlo o crea uno nuevo desde el botón superior.
                </div>
            @else
                <form wire:submit="save" class="mt-6 space-y-5">
                    <flux:input wire:model="name" label="Nombre del rol" type="text" required placeholder="admin" />

                    <div>
                        <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Permisos</label>

                        @if ($this->availablePermissions->isEmpty())
                            <p class="text-sm text-zinc-400 dark:text-zinc-500">
                                No hay permisos registrados.
                                <a href="{{ route('permissions.index') }}" wire:navigate class="text-sky-600 underline hover:text-sky-700 dark:text-sky-400">Crear permisos</a>
                            </p>
                        @else
                            <div class="max-h-64 overflow-y-auto rounded-lg border border-zinc-200 bg-zinc-50 p-3 dark:border-zinc-700 dark:bg-zinc-800/50">
                                <div class="grid gap-2 sm:grid-cols-2">
                                    @foreach ($this->availablePermissions as $permission)
                                        <label class="flex cursor-pointer items-center gap-2 rounded-lg p-1.5 hover:bg-zinc-100 dark:hover:bg-zinc-700/50">
                                            <input
                                                type="checkbox"
                                                wire:model="selectedPermissions"
                                                value="{{ $permission->name }}"
                                                class="h-4 w-4 rounded border-zinc-300 text-sky-600 focus:ring-sky-500 dark:border-zinc-600"
                                            />
                                            <span class="text-sm text-zinc-700 dark:text-zinc-300">{{ $permission->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <p class="mt-1.5 text-xs text-zinc-400 dark:text-zinc-500">
                                {{ count($selectedPermissions) }} de {{ $this->availablePermissions->count() }} permisos seleccionados
                            </p>
                        @endif

                        @error('name')
                            <div class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3">
                        <flux:button type="button" size="sm" variant="danger" wire:click="cancel">Cancelar</flux:button>
                        <flux:button variant="primary" color="sky" size="sm" type="submit">
                            {{ $editingRoleId ? 'Actualizar rol' : 'Crear rol' }}
                        </flux:button>
                    </div>
                </form>
            @endif
        </aside>
    </div>
</div>
