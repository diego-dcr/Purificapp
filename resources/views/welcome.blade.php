<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>ERP Purificadora - {{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-slate-100 antialiased">
    <div class="relative isolate overflow-hidden">
        <div class="absolute inset-0 -z-10 bg-linear-to-br from-sky-900/50 via-slate-950 to-slate-900"></div>
        <div class="absolute -top-28 left-1/2 -z-10 h-96 w-96 -translate-x-1/2 rounded-full bg-cyan-500/20 blur-3xl"></div>

        <header class="mx-auto flex w-full max-w-7xl items-center justify-between px-6 py-6 lg:px-8">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-cyan-500/20 ring-1 ring-cyan-300/30">
                    <span class="text-lg font-semibold text-cyan-300">PA</span>
                </div>
                <div>
                    <p class="text-sm text-slate-300">Sistema ERP</p>
                    <p class="font-semibold">Purificadora Arcoíris</p>
                </div>
            </div>

            @if (Route::has('login'))
                <nav class="flex items-center gap-3 text-sm">
                    @auth
                        <a href="{{ route('dashboard') }}" class="rounded-lg bg-cyan-500 px-4 py-2 font-medium text-slate-900 transition hover:bg-cyan-400">
                            Ir al panel
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="rounded-lg border border-slate-700 px-4 py-2 font-medium text-slate-200 transition hover:border-slate-500">
                            Iniciar sesión
                        </a>
                    @endauth
                </nav>
            @endif
        </header>

        <main class="mx-auto grid w-full max-w-7xl gap-8 px-6 pb-14 pt-4 lg:grid-cols-5 lg:px-8 lg:pb-20">
            <section class="rounded-2xl border border-slate-800 bg-slate-900/70 p-8 shadow-2xl backdrop-blur lg:col-span-3">
                <p class="inline-flex rounded-full border border-cyan-400/30 bg-cyan-500/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-cyan-200">
                    Operación en tiempo real
                </p>
                <h1 class="mt-4 text-3xl font-bold leading-tight text-white sm:text-4xl">
                    Control total de logística, inventario y finanzas para tu purificadora
                </h1>
                <p class="mt-4 max-w-2xl text-slate-300">
                    Optimiza cada etapa del servicio: desde el escaneo de garrafones hasta la cobranza final,
                    con trazabilidad completa de rutas, repartidores y movimientos operativos.
                </p>

                <div class="mt-8 grid gap-4 sm:grid-cols-2">
                    <article class="rounded-xl border border-slate-800 bg-slate-950/70 p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-400">Entregas registradas hoy</p>
                        <p class="mt-2 text-2xl font-bold text-cyan-300">248</p>
                        <p class="text-sm text-slate-400">+12% vs. ayer</p>
                    </article>
                    <article class="rounded-xl border border-slate-800 bg-slate-950/70 p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-400">Garrafones en circulación</p>
                        <p class="mt-2 text-2xl font-bold text-cyan-300">3,420</p>
                        <p class="text-sm text-slate-400">Con trazabilidad por código</p>
                    </article>
                    <article class="rounded-xl border border-slate-800 bg-slate-950/70 p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-400">Rutas activas</p>
                        <p class="mt-2 text-2xl font-bold text-cyan-300">14</p>
                        <p class="text-sm text-slate-400">Cobertura urbana y foránea</p>
                    </article>
                    <article class="rounded-xl border border-slate-800 bg-slate-950/70 p-4">
                        <p class="text-xs uppercase tracking-wide text-slate-400">Balance del día</p>
                        <p class="mt-2 text-2xl font-bold text-emerald-300">$18,760</p>
                        <p class="text-sm text-slate-400">Ingresos - gastos operativos</p>
                    </article>
                </div>
            </section>

            <section class="rounded-2xl border border-slate-800 bg-slate-900/70 p-6 shadow-2xl backdrop-blur lg:col-span-2">
                <h2 class="text-lg font-semibold text-white">Módulos del sistema</h2>
                <p class="mt-1 text-sm text-slate-400">Diseñados para gestión integral de la operación diaria.</p>

                <div class="mt-6 space-y-3">
                    <article class="rounded-xl border border-slate-800 bg-slate-950/60 p-4">
                        <h3 class="font-medium text-cyan-200">Control de garrafones por código de barras</h3>
                        <p class="mt-1 text-sm text-slate-400">Identifica altas, entregas, recolecciones y estado de cada unidad.</p>
                    </article>
                    <article class="rounded-xl border border-slate-800 bg-slate-950/60 p-4">
                        <h3 class="font-medium text-cyan-200">Rutas y repartidores</h3>
                        <p class="mt-1 text-sm text-slate-400">Asigna zonas, supervisa recorridos y productividad por operador.</p>
                    </article>
                    <article class="rounded-xl border border-slate-800 bg-slate-950/60 p-4">
                        <h3 class="font-medium text-cyan-200">Administración de clientes</h3>
                        <p class="mt-1 text-sm text-slate-400">Gestiona domicilios, historial de consumo y frecuencia de servicio.</p>
                    </article>
                    <article class="rounded-xl border border-slate-800 bg-slate-950/60 p-4">
                        <h3 class="font-medium text-cyan-200">Entregas y recolecciones en tiempo real</h3>
                        <p class="mt-1 text-sm text-slate-400">Registra eventos al momento con visibilidad inmediata para oficina.</p>
                    </article>
                    <article class="rounded-xl border border-slate-800 bg-slate-950/60 p-4">
                        <h3 class="font-medium text-cyan-200">Control financiero</h3>
                        <p class="mt-1 text-sm text-slate-400">Monitorea ingresos, gastos, flujo diario y rentabilidad por ruta.</p>
                    </article>
                    <article class="rounded-xl border border-slate-800 bg-slate-950/60 p-4">
                        <h3 class="font-medium text-cyan-200">Estadísticas y reportes</h3>
                        <p class="mt-1 text-sm text-slate-400">Analiza indicadores clave para decisiones operativas y comerciales.</p>
                    </article>
                </div>
            </section>
        </main>
    </div>
</body>
</html>