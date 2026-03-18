    <div class="flex w-full flex-1 flex-col gap-6">
    <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-xl border border-neutral-200 bg-white p-5 dark:border-neutral-700 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Usuarios registrados</p>
            <p class="mt-2 text-3xl font-semibold">{{ $this->totalUsers }}</p>
        </div>

        <div class="rounded-xl border border-neutral-200 bg-white p-5 dark:border-neutral-700 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Administradores</p>
            <p class="mt-2 text-3xl font-semibold">{{ $this->adminUsers }}</p>
        </div>

        <div class="rounded-xl border border-neutral-200 bg-white p-5 dark:border-neutral-700 dark:bg-zinc-900">
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Roles disponibles</p>
            <p class="mt-2 text-3xl font-semibold">{{ count($this->availableRoles) }}</p>
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
                    <flux:heading size="lg">Administración de usuarios</flux:heading>
                    <flux:text class="mt-1">Gestiona accesos, datos de contacto y roles operativos.</flux:text>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                    <div class="min-w-72">
                        <flux:input wire:model.live.debounce.300ms="search" label="Buscar" type="text" placeholder="Nombre, usuario o correo" />
                    </div>

                    <flux:button variant="primary" wire:click="create">Nuevo usuario</flux:button>
                </div>
            </div>

            <div class="mt-6 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200 text-sm dark:divide-neutral-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/60">
                            <tr class="text-left text-zinc-500 dark:text-zinc-400">
                                <th class="px-4 py-3 font-medium">Usuario</th>
                                <th class="px-4 py-3 font-medium">Correo</th>
                                <th class="px-4 py-3 font-medium">Rol</th>
                                <th class="px-4 py-3 font-medium">Estado</th>
                                <th class="px-4 py-3 font-medium text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                            @forelse ($this->users as $user)
                                <tr class="bg-white dark:bg-zinc-900">
                                    <td class="px-4 py-3 align-top">
                                        <div class="font-medium text-zinc-900 dark:text-zinc-100">{{ $user->name }}</div>
                                        <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ '@' . $user->username }}</div>
                                    </td>
                                    <td class="px-4 py-3 align-top text-zinc-700 dark:text-zinc-300">{{ $user->email }}</td>
                                    <td class="px-4 py-3 align-top">
                                        <span class="inline-flex rounded-full bg-cyan-100 px-2.5 py-1 text-xs font-medium text-cyan-700 dark:bg-cyan-950/70 dark:text-cyan-300">
                                            {{ $user->roles->pluck('name')->implode(', ') ?: 'Sin rol' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 align-top">
                                        @if ($user->email_verified_at)
                                            <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-950/70 dark:text-emerald-300">
                                                Activo
                                            </span>
                                        @else
                                            <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-medium text-amber-700 dark:bg-amber-950/70 dark:text-amber-300">
                                                Pendiente
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 align-top">
                                        <div class="flex justify-end gap-2">
                                            <flux:button size="sm" variant="ghost" wire:click="edit({{ $user->id }})">
                                                Editar
                                            </flux:button>

                                            @if (auth()->id() !== $user->id)
                                                <flux:button
                                                    size="sm"
                                                    variant="danger"
                                                    wire:click="delete({{ $user->id }})"
                                                >
                                                    Eliminar
                                                </flux:button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-zinc-500 dark:text-zinc-400">
                                        No se encontraron usuarios con el criterio actual.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $this->users->links() }}
            </div>
        </section>

        <aside class="rounded-xl border border-neutral-200 bg-white p-5 dark:border-neutral-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <flux:heading size="lg">{{ $editingUserId ? 'Editar usuario' : 'Nuevo usuario' }}</flux:heading>
                    <flux:text class="mt-1">
                        {{ $editingUserId ? 'Actualiza la información y el rol asignado.' : 'Captura los datos básicos del nuevo usuario.' }}
                    </flux:text>
                </div>

                @if ($showForm)
                    <flux:button variant="ghost" wire:click="cancel">Cancelar</flux:button>
                @endif
            </div>

            @if (! $showForm)
                <div class="mt-6 rounded-xl border border-dashed border-neutral-300 p-6 text-sm text-zinc-500 dark:border-neutral-700 dark:text-zinc-400">
                    Selecciona un usuario para editarlo o crea uno nuevo desde el botón superior.
                </div>
            @else
                <form wire:submit="save" class="mt-6 space-y-5">
                    <flux:input wire:model="name" label="Nombre" type="text" required />

                    <flux:input wire:model="username" label="Usuario" type="text" required placeholder="admin.ddcr" />

                    <flux:input wire:model="email" label="Correo" type="email" required placeholder="usuario@water" />

                    <div>
                        <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Rol</label>
                        <select wire:model="role" class="block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-xs outline-none transition focus:border-zinc-400 focus:ring-2 focus:ring-accent focus:ring-offset-2 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 dark:focus:border-zinc-600 dark:focus:ring-offset-zinc-900">
                            @foreach ($this->availableRoles as $availableRole)
                                <option value="{{ $availableRole }}">{{ $availableRole }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</div>
                        @enderror
                    </div>

                    <flux:input
                        wire:model="password"
                        :label="$editingUserId ? 'Nueva contraseña (opcional)' : 'Contraseña'"
                        type="password"
                        viewable
                    />

                    <div class="flex justify-end gap-3">
                        <flux:button variant="ghost" type="button" wire:click="cancel">Cancelar</flux:button>
                        <flux:button variant="primary" type="submit">
                            {{ $editingUserId ? 'Actualizar usuario' : 'Crear usuario' }}
                        </flux:button>
                    </div>
                </form>
            @endif
        </aside>
    </div>
</div>
