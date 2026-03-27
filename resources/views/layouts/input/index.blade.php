<x-layouts::app :title="__('Entregas / Ventas')">
    <div class="flex w-full flex-1 flex-col gap-6">

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Registros de entrega/venta</p>
                <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ count($inputs) }}</p>
                <p class="mt-1 text-sm text-emerald-600 dark:text-emerald-400">Total en el sistema</p>
            </article>

            <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Garrafones escaneados</p>
                <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ $inputs->sum('waterjug_count') }}</p>
                <p class="mt-1 text-sm text-sky-600 dark:text-sky-400">Total agregado</p>
            </article>

            <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Clientes</p>
                <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ count($customers) }}</p>
                <p class="mt-1 text-sm text-purple-600 dark:text-purple-400">Disponibles</p>
            </article>

            <article class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Conceptos</p>
                <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ count($concepts) }}</p>
                <p class="mt-1 text-sm text-orange-600 dark:text-orange-400">Disponibles</p>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.9fr)_minmax(320px,0.9fr)]">
            <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <div class="flex flex-col gap-4 border-b border-neutral-200 px-6 py-5 dark:border-neutral-700 md:flex-row md:items-end md:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Gestión de entregas/ventas</h2>
                        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Administra ventas, entregas y garrafones escaneados por cliente.</p>
                    </div>

                    <flux:button type="button" variant="primary" color="sky" size="sm" icon="plus"
                        onclick="window.location.href='{{ route('inputs.index') }}'">
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
                            @forelse ($inputs as $inputItem)
                                <tr>
                                    <td class="px-6 py-4 text-zinc-900 dark:text-zinc-50">
                                        <div class="font-medium">{{ $inputItem->customer->name }}</div>
                                        <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $inputItem->customer->number }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">{{ $inputItem->concept->name }}</td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">$ {{ number_format((float) $inputItem->cost, 2) }}</td>
                                    <td class="px-6 py-4 text-center text-zinc-600 dark:text-zinc-300">
                                        <span class="inline-flex rounded-full bg-purple-100 px-2.5 py-1 text-xs font-medium text-purple-700 dark:bg-purple-950/70 dark:text-purple-300">
                                            {{ $inputItem->waterjug_count }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">{{ $inputItem->timestamp->format('Y-m-d H:i') }}</td>
                                    <td class="px-6 py-4 align-top">
                                        <div class="flex justify-end gap-2">
                                            <flux:button type="button" icon="pencil-square" size="sm" variant="filled"
                                                class="bg-zinc-500 hover:bg-zinc-600"
                                                onclick="window.location.href='{{ route('inputs.edit', $inputItem) }}'">
                                                Editar
                                            </flux:button>

                                            <form method="POST" action="{{ route('inputs.destroy', $inputItem) }}"
                                                onsubmit="return confirm('¿Seguro que deseas eliminar este registro?');">
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
                                        No hay entregas/ventas registradas
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
                        @isset($input)
                            Editar registro
                        @else
                            Nuevo registro
                        @endisset
                    </flux:heading>
                    <flux:text class="mt-1">
                        @isset($input)
                            Actualiza los datos de la entrega/venta.
                        @else
                            Registra una nueva entrega/venta y sus garrafones escaneados.
                        @endisset
                    </flux:text>
                </div>

                <form method="POST"
                    action="@isset($input){{ route('inputs.update', $input) }}@else{{ route('inputs.store') }}@endisset"
                    class="mt-6 space-y-5">
                    @csrf
                    @isset($input)
                        @method('PUT')
                    @endisset

                    <div>
                        <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Usuario</label>
                        <select name="user_id" required
                            class="block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-xs outline-none transition focus:border-zinc-400 focus:ring-2 focus:ring-accent focus:ring-offset-2 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 dark:focus:border-zinc-600 dark:focus:ring-offset-zinc-900">
                            <option value="">Selecciona un usuario</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @if (isset($input) && $input->user_id === $user->id) selected @endif>
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
                                <option value="{{ $route->id }}" @if (isset($input) && $input->route_id === $route->id) selected @endif>
                                    {{ $route->name }} ({{ $route->zone }})
                                </option>
                            @endforeach
                        </select>
                        @error('route_id')
                            <span class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Cliente</label>
                        <select name="customer_id" required
                            class="block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-xs outline-none transition focus:border-zinc-400 focus:ring-2 focus:ring-accent focus:ring-offset-2 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 dark:focus:border-zinc-600 dark:focus:ring-offset-zinc-900">
                            <option value="">Selecciona un cliente</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" @if (isset($input) && $input->customer_id === $customer->id) selected @endif>
                                    {{ $customer->name }} ({{ $customer->number }})
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <span class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Concepto</label>
                        <select name="concept_id" required
                            class="block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-xs outline-none transition focus:border-zinc-400 focus:ring-2 focus:ring-accent focus:ring-offset-2 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 dark:focus:border-zinc-600 dark:focus:ring-offset-zinc-900">
                            <option value="">Selecciona un concepto</option>
                            @foreach ($concepts as $concept)
                                <option value="{{ $concept->id }}" @if (isset($input) && $input->concept_id === $concept->id) selected @endif>
                                    {{ $concept->name }} ({{ $concept->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('concept_id')
                            <span class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</span>
                        @enderror
                    </div>

                    <flux:input name="cost" label="Costo" type="number" step="0.01" min="0" required
                        placeholder="0.00" value="{{ isset($input) ? $input->cost : '' }}" />

                    <div>
                        <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Códigos de barra escaneados</label>
                        <div id="waterjug-inputs" class="space-y-2">
                            @isset($input)
                                @forelse ($input->waterjugSales as $waterjug)
                                    <input type="text" name="waterjug_codebars[]"
                                        placeholder="Código de barra"
                                        value="{{ $waterjug->waterjug_codebar }}"
                                        class="block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-xs outline-none transition focus:border-zinc-400 focus:ring-2 focus:ring-accent focus:ring-offset-2 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 dark:focus:border-zinc-600 dark:focus:ring-offset-zinc-900" />
                                @empty
                                    <input type="text" name="waterjug_codebars[]"
                                        placeholder="Código de barra"
                                        class="block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-xs outline-none transition focus:border-zinc-400 focus:ring-2 focus:ring-accent focus:ring-offset-2 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 dark:focus:border-zinc-600 dark:focus:ring-offset-zinc-900" />
                                @endforelse
                            @else
                                <input type="text" name="waterjug_codebars[]"
                                    placeholder="Código de barra"
                                    class="block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-xs outline-none transition focus:border-zinc-400 focus:ring-2 focus:ring-accent focus:ring-offset-2 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 dark:focus:border-zinc-600 dark:focus:ring-offset-zinc-900" />
                            @endisset
                        </div>
                        <flux:button type="button" size="sm" variant="ghost" class="mt-2" onclick="addWaterjugInput()">
                            + Agregar garrafón
                        </flux:button>
                    </div>

                    <div class="flex justify-end gap-3">
                        <flux:button type="button" size="sm" variant="danger"
                            onclick="window.location.href='{{ route('inputs.index') }}'">
                            Cancelar
                        </flux:button>
                        <flux:button variant="primary" color="sky" size="sm" type="submit">
                            @isset($input)
                                Actualizar registro
                            @else
                                Guardar registro
                            @endisset
                        </flux:button>
                    </div>
                </form>
            </aside>
        </section>
    </div>

    <script>
        function addWaterjugInput() {
            const container = document.getElementById('waterjug-inputs');
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'waterjug_codebars[]';
            input.placeholder = 'Código de barra';
            input.className = 'block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-xs outline-none transition focus:border-zinc-400 focus:ring-2 focus:ring-accent focus:ring-offset-2 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 dark:focus:border-zinc-600 dark:focus:ring-offset-zinc-900';
            container.appendChild(input);
        }
    </script>
</x-layouts::app>
