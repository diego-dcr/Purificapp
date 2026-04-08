<x-layouts::app :title="__('Retornos')">
    <div class="flex w-full flex-1 flex-col gap-6">

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <article
                class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Retornos registrados</p>
                <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ count($outputs) }}</p>
                <p class="mt-1 text-sm text-emerald-600 dark:text-emerald-400">Total en el sistema</p>
            </article>

            <article
                class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Garrafas procesadas</p>
                <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ $outputs->sum('carboy_count') }}</p>
                <p class="mt-1 text-sm text-sky-600 dark:text-sky-400">Total agregado</p>
            </article>

            <article
                class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Usuarios activos</p>
                <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ count($users) }}</p>
                <p class="mt-1 text-sm text-purple-600 dark:text-purple-400">Disponibles</p>
            </article>

            <article
                class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Rutas activas</p>
                <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ count($routes) }}</p>
                <p class="mt-1 text-sm text-orange-600 dark:text-orange-400">Disponibles</p>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.6fr)_minmax(320px,0.9fr)]">
            <div
                class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <div
                    class="flex flex-col gap-4 border-b border-neutral-200 px-6 py-5 dark:border-neutral-700 md:flex-row md:items-end md:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Gestión de retornos</h2>
                        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Administra el registro de retornos de garrafones por usuario.</p>
                    </div>

                    <div class="flex flex-col items-start gap-3 sm:flex-row sm:items-center">
                        <flux:button type="button" variant="primary" color="sky" size="sm" icon="plus"
                            onclick="window.location.href='{{ route('retornos.index') }}'">
                            Nuevo retorno
                        </flux:button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200 text-sm dark:divide-neutral-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/70">
                            <tr class="text-left text-zinc-500 dark:text-zinc-400">
                                <th class="px-6 py-3 font-medium">Usuario</th>
                                <th class="px-6 py-3 font-medium">Ruta</th>
                                <th class="px-6 py-3 font-medium text-center">Garrafas</th>
                                <th class="px-6 py-3 font-medium">Fecha</th>
                                <th class="px-6 py-3 font-medium text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                            @forelse ($outputs as $outputItem)
                                <tr>
                                    <td class="px-6 py-4 font-medium text-zinc-900 dark:text-zinc-50">
                                        {{ $outputItem->user->name }}
                                    </td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">
                                        <span class="inline-flex rounded-full bg-orange-100 px-2.5 py-1 text-xs font-medium text-orange-700 dark:bg-orange-950/70 dark:text-orange-300">
                                            {{ $outputItem->route->name ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center text-zinc-600 dark:text-zinc-300">
                                        <span class="inline-flex rounded-full bg-purple-100 px-2.5 py-1 text-xs font-medium text-purple-700 dark:bg-purple-950/70 dark:text-purple-300">
                                            {{ $outputItem->carboy_count }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">
                                        {{ $outputItem->timestamp->format('Y-m-d H:i') }}
                                    </td>
                                    <td class="px-6 py-4 align-top">
                                        <div class="flex justify-end gap-2">
                                            <flux:button type="button" icon="pencil-square" size="sm" variant="filled"
                                                class="bg-zinc-500 hover:bg-zinc-600"
                                                onclick="window.location.href='{{ route('outputs.edit', $outputItem) }}'">
                                                Editar
                                            </flux:button>

                                            <form method="POST" action="{{ route('outputs.destroy', $outputItem) }}"
                                                onsubmit="return confirm('¿Seguro que deseas eliminar este retorno?');">
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
                                    <td colspan="5" class="px-6 py-8 text-center text-zinc-500 dark:text-zinc-400">
                                        No hay retornos registrados
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
                        @isset($retorno)
                            Editar retorno
                        @else
                            Nuevo retorno
                        @endisset
                    </flux:heading>
                    <flux:text class="mt-1">
                        @isset($retorno)
                            Actualiza los datos del retorno.
                        @else
                            Registra un nuevo retorno de garrafas.
                        @endisset
                    </flux:text>
                </div>

                <form method="POST"
                    action="@isset($retorno){{ route('outputs.update', $retorno) }}@else{{ route('outputs.store') }}@endisset"
                    class="mt-6 space-y-5">
                    @csrf
                    @isset($retorno)
                        @method('PUT')
                    @endisset

                    <div>
                        <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Usuario</label>
                        <select name="user_id" required
                            class="block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-xs outline-none transition focus:border-zinc-400 focus:ring-2 focus:ring-accent focus:ring-offset-2 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 dark:focus:border-zinc-600 dark:focus:ring-offset-zinc-900">
                            <option value="">Selecciona un usuario</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @if (isset($retorno) && $retorno->user_id === $user->id) selected @endif>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <span class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Ruta (opcional)</label>
                        <select name="route_id"
                            class="block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-xs outline-none transition focus:border-zinc-400 focus:ring-2 focus:ring-accent focus:ring-offset-2 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 dark:focus:border-zinc-600 dark:focus:ring-offset-zinc-900">
                            <option value="">Selecciona una ruta</option>
                            @foreach ($routes as $route)
                                <option value="{{ $route->id }}" @if (isset($retorno) && $retorno->route_id === $route->id) selected @endif>
                                    {{ $route->name }} ({{ $route->zone }})
                                </option>
                            @endforeach
                        </select>
                        @error('route_id')
                            <span class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Códigos de barras de garrafas</label>
                        <div id="carboy-inputs" class="space-y-2">
                            @isset($retorno)
                                @forelse ($retorno->carboyOutputs as $carboy)
                                    <input type="text" name="carboy_codebars[]" 
                                        placeholder="Código de barra"
                                        value="{{ $carboy->carboy_codebar }}"
                                        class="block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-xs outline-none transition focus:border-zinc-400 focus:ring-2 focus:ring-accent focus:ring-offset-2 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 dark:focus:border-zinc-600 dark:focus:ring-offset-zinc-900" />
                                @empty
                                    <input type="text" name="carboy_codebars[]" 
                                        placeholder="Código de barra"
                                        class="block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-xs outline-none transition focus:border-zinc-400 focus:ring-2 focus:ring-accent focus:ring-offset-2 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 dark:focus:border-zinc-600 dark:focus:ring-offset-zinc-900" />
                                @endforelse
                            @else
                                <input type="text" name="carboy_codebars[]" 
                                    placeholder="Código de barra"
                                    class="block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-xs outline-none transition focus:border-zinc-400 focus:ring-2 focus:ring-accent focus:ring-offset-2 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 dark:focus:border-zinc-600 dark:focus:ring-offset-zinc-900" />
                            @endisset
                        </div>
                        <flux:button type="button" size="sm" variant="ghost" class="mt-2" 
                            onclick="addCarboyInput()">
                            + Agregar garrafa
                        </flux:button>
                    </div>

                    <div class="flex justify-end gap-3">
                        <flux:button type="button" size="sm" variant="danger"
                            onclick="window.location.href='{{ route('retornos.index') }}'">
                            Cancelar
                        </flux:button>
                        <flux:button variant="primary" color="sky" size="sm" type="submit">
                            @isset($retorno)
                                Actualizar retorno
                            @else
                                Guardar retorno
                            @endisset
                        </flux:button>
                    </div>
                </form>
            </aside>
        </section>
    </div>

    <script>
        function addCarboyInput() {
            const container = document.getElementById('carboy-inputs');
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'carboy_codebars[]';
            input.placeholder = 'Código de barra';
            input.className = 'block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-xs outline-none transition focus:border-zinc-400 focus:ring-2 focus:ring-accent focus:ring-offset-2 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 dark:focus:border-zinc-600 dark:focus:ring-offset-zinc-900';
            container.appendChild(input);
        }
    </script>
</x-layouts::app>
