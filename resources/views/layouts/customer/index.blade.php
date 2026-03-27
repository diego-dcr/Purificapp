<x-layouts::app :title="__('Clientes')">
    <div class="flex w-full flex-1 flex-col gap-6">

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <article
                class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Clientes registrados</p>
                <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ count($customers) }}</p>
                <p class="mt-1 text-sm text-emerald-600 dark:text-emerald-400">Total en el sistema</p>
            </article>

            <article
                class="rounded-xl border border-neutral-200 bg-white p-5 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Rutas activas</p>
                <p class="mt-3 text-3xl font-semibold text-zinc-900 dark:text-zinc-50">{{ count($routes) }}</p>
                <p class="mt-1 text-sm text-sky-600 dark:text-sky-400">Disponibles</p>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.6fr)_minmax(320px,0.9fr)]">
            <div
                class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
                <div
                    class="flex flex-col gap-4 border-b border-neutral-200 px-6 py-5 dark:border-neutral-700 md:flex-row md:items-end md:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Gestión de clientes</h2>
                        <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">Administra el registro de clientes por ruta de entrega.</p>
                    </div>

                    <div class="flex flex-col items-start gap-3 sm:flex-row sm:items-center">
                        <flux:button type="button" variant="primary" color="sky" size="sm" icon="plus"
                            onclick="window.location.href='{{ route('customers.index') }}'">
                            Nuevo cliente
                        </flux:button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-neutral-200 text-sm dark:divide-neutral-700">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/70">
                            <tr class="text-left text-zinc-500 dark:text-zinc-400">
                                <th class="px-6 py-3 font-medium">Código de barras</th>
                                <th class="px-6 py-3 font-medium">No. Cliente</th>
                                <th class="px-6 py-3 font-medium">Nombre</th>
                                <th class="px-6 py-3 font-medium">Ruta</th>
                                <th class="px-6 py-3 font-medium text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                            @foreach ($customers as $customerItem)
                                <tr>
                                    <td class="px-6 py-4 font-medium text-zinc-900 dark:text-zinc-50">
                                        <code class="text-xs">{{ $customerItem->barcode }}</code>
                                    </td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">{{ $customerItem->number }}</td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">{{ $customerItem->name }}</td>
                                    <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">
                                        <span class="inline-flex rounded-full bg-sky-100 px-2.5 py-1 text-xs font-medium text-sky-700 dark:bg-sky-950/70 dark:text-sky-300">
                                            {{ $customerItem->route->name ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 align-top">
                                        <div class="flex justify-end gap-2">
                                            <flux:button type="button" icon="pencil-square" size="sm" variant="filled"
                                                class="bg-zinc-500 hover:bg-zinc-600"
                                                onclick="window.location.href='{{ route('customers.edit', $customerItem) }}'">
                                                Editar
                                            </flux:button>

                                            <form method="POST" action="{{ route('customers.destroy', $customerItem) }}"
                                                onsubmit="return confirm('¿Seguro que deseas eliminar este cliente?');">
                                                @csrf
                                                @method('DELETE')
                                                <flux:button type="submit" icon="trash" size="sm" variant="danger">
                                                    Eliminar
                                                </flux:button>
                                            </form>
                                        </div>
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
                        @isset($customer)
                            Editar cliente
                        @else
                            Nuevo cliente
                        @endisset
                    </flux:heading>
                    <flux:text class="mt-1">
                        @isset($customer)
                            Actualiza los datos del cliente.
                        @else
                            Registra un nuevo cliente asignándolo a una ruta.
                        @endisset
                    </flux:text>
                </div>

                <form method="POST"
                    action="@isset($customer){{ route('customers.update', $customer) }}@else{{ route('customers.store') }}@endisset"
                    class="mt-6 space-y-5">
                    @csrf
                    @isset($customer)
                        @method('PUT')
                    @endisset

                    <flux:input name="barcode" label="Código de barras" type="text" required
                        placeholder="EAN-13" value="{{ isset($customer) ? $customer->barcode : '' }}" />

                    <flux:input name="number" label="No. Cliente" type="text" required
                        placeholder="CLI-001" value="{{ isset($customer) ? $customer->number : '' }}" />

                    <flux:input name="name" label="Nombre del cliente" type="text" required
                        placeholder="Empresa ABC S.A." value="{{ isset($customer) ? $customer->name : '' }}" />

                    <div>
                        <label class="mb-2 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Ruta</label>
                        <select name="route_id"
                            class="block w-full rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm text-zinc-900 shadow-xs outline-none transition focus:border-zinc-400 focus:ring-2 focus:ring-accent focus:ring-offset-2 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 dark:focus:border-zinc-600 dark:focus:ring-offset-zinc-900">
                            <option value="">Selecciona una ruta</option>
                            @foreach ($routes as $route)
                                <option value="{{ $route->id }}" @if (isset($customer) && $customer->route_id === $route->id) selected @endif>
                                    {{ $route->name }} ({{ $route->zone }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end gap-3">
                        <flux:button type="button" size="sm" variant="danger"
                            onclick="window.location.href='{{ route('customers.index') }}'">
                            Cancelar
                        </flux:button>
                        <flux:button variant="primary" color="sky" size="sm" type="submit">
                            @isset($customer)
                                Actualizar cliente
                            @else
                                Guardar cliente
                            @endisset
                        </flux:button>
                    </div>
                </form>
            </aside>
        </section>

    </div>
</x-layouts::app>
