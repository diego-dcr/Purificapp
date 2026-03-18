<x-layouts::app :title="__('Rutas')">
    <div class="flex w-full flex-1 flex-col gap-6">


        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <article
                class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Rutas programadas</p>
                <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">00</p>
                <p class="mt-1 text-sm text-emerald-600 dark:text-emerald-400">XXXXX</p>
            </article>

            <article
                class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Entregas pendientes</p>
                <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">00</p>
                <p class="mt-1 text-sm text-amber-600 dark:text-amber-400">XXXXX</p>
            </article>

            <article
                class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Recolecciones activas</p>
                <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">00</p>
                <p class="mt-1 text-sm text-sky-600 dark:text-sky-400">XXXXX</p>
            </article>

            <article
                class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Incidencias del dia</p>
                <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">00</p>
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-400">XXXXX</p>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.6fr)_minmax(320px,0.9fr)]">
            <div
                class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <div
                    class="flex flex-col gap-4 border-b border-neutral-200 px-6 py-5 dark:border-neutral-700 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Resumen de rutas</h2>
                        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Vista general del avance operativo por
                            zona y repartidor.</p>
                    </div>

                    <div class="flex flex-wrap gap-2 text-xs font-medium">
                        <span
                            class="rounded-full bg-emerald-100 px-3 py-1 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300">En
                            tiempo</span>
                        <span
                            class="rounded-full bg-amber-100 px-3 py-1 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300">En
                            riesgo</span>
                        <span
                            class="rounded-full bg-rose-100 px-3 py-1 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300">Retraso</span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200 text-sm dark:divide-neutral-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/70">
                            <tr class="text-left text-zinc-500 dark:text-zinc-400">
                                <th class="px-6 py-3 font-medium">Código</th>
                                <th class="px-6 py-3 font-medium">Ruta</th>
                                <th class="px-6 py-3 font-medium">Repartidor</th>
                                <th class="px-6 py-3 font-medium">Zona</th>
                                <th class="px-6 py-3 font-medium">Entregas</th>
                                <th class="px-6 py-3 font-medium">Garrafones</th>
                                <th class="px-6 py-3 font-medium">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                            @foreach ($routes as $route)
                                <tr>
                                    <td class="px-6 py-4 font-medium text-zinc-900 dark:text-zinc-50">
                                        {{ $route->code }}
                                    </td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">{{ $route->name }}</td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">{{ $route->user->name }}</td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">{{ $route->zone }}</td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">-</td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">-</td>
                                    <td class="px-6 py-4 flex items-center gap-2">
                                        <flux:button type="button" icon="pencil-square" size="sm" variant="filled"
                                            class="bg-zinc-500 hover:bg-zinc-600"
                                            onclick="window.location.href='{{ route('routes.edit', $route) }}'">
                                            Editar
                                        </flux:button>

                                        <form method="POST" action="{{ route('routes.destroy', $route) }}"
                                            onsubmit="return confirm('¿Seguro que deseas eliminar esta ruta?');">
                                            @csrf
                                            @method('DELETE')
                                            <flux:button type="submit" icon="trash" size="sm" variant="danger">
                                                Eliminar
                                            </flux:button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <aside class="rounded-xl border border-neutral-200 bg-white p-5 dark:border-neutral-700 dark:bg-zinc-900">
                <div>
                    <flux:heading size="lg">
                        @isset($route)
                            Editar ruta
                        @else
                            Nueva ruta
                        @endisset
                    </flux:heading>
                    <flux:text class="mt-1">
                        @isset($route)
                            Actualiza los datos de la ruta operativa.
                        @else
                            Registra una ruta operativa indicando su nombre, la zona de cobertura y el usuario responsable.
                        @endisset
                    </flux:text>

                    @isset($route)
                        <div class="mt-3">
                            <flux:button
                                type="button"
                                size="sm"
                                variant="ghost"
                                icon="plus"
                                onclick="window.location.href='{{ route('routes.index') }}'"
                            >
                                Crear nueva ruta
                            </flux:button>
                        </div>
                    @endisset
                </div>

                <form method="POST"
                    action="@isset($route){{ route('routes.update', $route) }}@else{{ route('routes.store') }}@endisset"
                    class="mt-6 space-y-5">
                    @csrf
                    @isset($route)
                        @method('PUT')
                    @endisset

                    <flux:input name="name" label="Nombre de la ruta" type="text" required
                        placeholder="Ruta Centro 1" value="{{ isset($route) ? $route->name : '' }}" />

                    <flux:input name="zone" label="Zona" type="text" required placeholder="Centro"
                        value="{{ isset($route) ? $route->zone : '' }}" />

                    <div>
                        <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Usuario
                            asignado</label>
                        <select name="user_id"
                            class="block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-xs outline-none transition focus:border-zinc-400 focus:ring-2 focus:ring-accent focus:ring-offset-2 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 dark:focus:border-zinc-600 dark:focus:ring-offset-zinc-900">
                            <option value="">Selecciona un usuario</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @if (isset($route) && $route->user_id === $user->id) selected @endif>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end gap-3">
                        <flux:button type="button" size="sm" variant="danger"
                            onclick="window.location.href='{{ route('routes.index') }}'">
                            Cancelar
                        </flux:button>
                        <flux:button variant="primary" color="sky" size="sm" type="submit">
                            @isset($route)
                                Actualizar ruta
                            @else
                                Guardar ruta
                            @endisset
                        </flux:button>
                    </div>
                </form>
            </aside>
        </section>

    </div>
</x-layouts::app>
