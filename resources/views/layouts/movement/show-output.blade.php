<x-layouts::app :title="__('Detalle de Output')">
    <div class="flex w-full flex-1 flex-col gap-6">
        <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-zinc-900 dark:text-zinc-50">Detalle de movimiento de salida #{{ $output->id }}</h2>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Información completa del output y códigos de garrafón ligados.</p>
                </div>
                <flux:button type="button" size="sm" variant="primary" color="sky"
                    onclick="window.location.href='{{ route('movements.index') }}'">
                    Volver a movimientos
                </flux:button>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <article class="rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Usuario</p>
                    <p class="mt-1 text-sm font-semibold text-zinc-900 dark:text-zinc-50">{{ $output->user->name ?? '—' }}</p>
                </article>
                <article class="rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Ruta</p>
                    <p class="mt-1 text-sm font-semibold text-zinc-900 dark:text-zinc-50">{{ $output->route->name ?? '—' }}</p>
                </article>
                <article class="rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Fecha de creación</p>
                    <p class="mt-1 text-sm font-semibold text-zinc-900 dark:text-zinc-50">{{ $output->timestamp?->format('Y-m-d H:i') }}</p>
                </article>
                <article class="rounded-lg border border-neutral-200 p-4 dark:border-neutral-700">
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Cantidad de garrafones</p>
                    <p class="mt-1 text-sm font-semibold text-zinc-900 dark:text-zinc-50">{{ $output->waterjugOutputs->count() }}</p>
                </article>
            </div>
        </div>

        <div class="rounded-xl border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-zinc-900">
            <div class="border-b border-neutral-200 px-6 py-5 dark:border-neutral-700">
                <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Códigos de garrafón ligados al output</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200 text-sm dark:divide-neutral-700">
                    <thead class="bg-zinc-50 dark:bg-zinc-800/70">
                        <tr class="text-left text-zinc-500 dark:text-zinc-400">
                            <th class="px-6 py-3 font-medium">Código de garrafón</th>
                            <th class="px-6 py-3 font-medium">Fecha de registro</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                        @forelse ($output->waterjugOutputs as $waterjug)
                            <tr>
                                <td class="px-6 py-4 font-medium text-zinc-900 dark:text-zinc-50">{{ $waterjug->waterjug_codebar }}</td>
                                <td class="px-6 py-4 text-zinc-600 dark:text-zinc-300">{{ $waterjug->timestamp?->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-6 py-8 text-center text-zinc-500 dark:text-zinc-400">
                                    Este output no tiene códigos de garrafón ligados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts::app>
