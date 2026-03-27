<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky collapsible="mobile"
        class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.header>
            <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
            <flux:sidebar.collapse class="lg:hidden" />
        </flux:sidebar.header>

        <flux:sidebar.nav>
            <flux:sidebar.group :heading="__('Inicio')" class="grid">
                <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                    wire:navigate>
                    {{ __('Dashboard') }}
                </flux:sidebar.item>
            </flux:sidebar.group>
        </flux:sidebar.nav>

        <flux:sidebar.nav>
            <flux:sidebar.group :heading="__('Operaciones')" class="grid">
                <flux:sidebar.item icon="map" :href="route('routes.index')"
                    :current="request()->routeIs('routes.index')" wire:navigate>
                    Rutas
                </flux:sidebar.item>

                {{-- <flux:sidebar.item icon="camera" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                    wire:navigate>
                    Escaneo
                </flux:sidebar.item> --}}

                <flux:sidebar.item icon="truck" :href="route('inputs.index')" :current="request()->routeIs('inputs.*')"
                    wire:navigate>
                    Entregas/Ventas
                </flux:sidebar.item>
            </flux:sidebar.group>
        </flux:sidebar.nav>

        <flux:sidebar.nav>
            <flux:sidebar.group :heading="__('Planta')" class="grid">
                <flux:sidebar.item icon="arrow-uturn-left" :href="route('outputs.index')"
                    :current="request()->routeIs('outputs.index')" wire:navigate>
                    Salidas
                </flux:sidebar.item>
                {{-- <flux:sidebar.item icon="wrench-screwdriver" :href="route('dashboard')"
                    :current="request()->routeIs('dashboard')" wire:navigate>
                    Producción
                </flux:sidebar.item>

                <flux:sidebar.item icon="cog-6-tooth" :href="route('dashboard')"
                    :current="request()->routeIs('dashboard')" wire:navigate>
                    Control Interno
                </flux:sidebar.item> --}}
            </flux:sidebar.group>
        </flux:sidebar.nav>

        <flux:sidebar.nav>
            <flux:sidebar.group :heading="__('Inventario')" class="grid">
                <flux:sidebar.item icon="cube" :href="route('waterjugs.index')"
                    :current="request()->routeIs('waterjugs.*')" wire:navigate>
                    Garrafones
                </flux:sidebar.item>

                <flux:sidebar.item icon="archive-box" :href="route('lots.index')"
                    :current="request()->routeIs('lots.*')" wire:navigate>
                    Lotes
                </flux:sidebar.item>

                <flux:sidebar.item icon="arrows-right-left" :href="route('movements.index')"
                    :current="request()->routeIs('movements.*')" wire:navigate>
                    Movimientos
                </flux:sidebar.item>
            </flux:sidebar.group>
        </flux:sidebar.nav>

        <flux:sidebar.nav>
            <flux:sidebar.group :heading="__('Clientes')" class="grid">
                <flux:sidebar.item icon="user-group" :href="route('customers.index')"
                    :current="request()->routeIs('customers.index')" wire:navigate>
                    Gestión de clientes
                </flux:sidebar.item>

                {{-- <flux:sidebar.item icon="map-pin" :href="route('dashboard')"
                    :current="request()->routeIs('dashboard')" wire:navigate>
                    Asignacion de rutas
                </flux:sidebar.item> --}}
            </flux:sidebar.group>
        </flux:sidebar.nav>

        <flux:sidebar.nav>
            <flux:sidebar.group :heading="__('Usuarios')" class="grid">
                <flux:sidebar.item icon="user" :href="route('users.index')"
                    :current="request()->routeIs('users.index')" wire:navigate>
                    Usuarios
                </flux:sidebar.item>

                <flux:sidebar.item icon="shield-check" :href="route('roles.index')"
                    :current="request()->routeIs('roles.index')" wire:navigate>
                    Roles
                </flux:sidebar.item>

                <flux:sidebar.item icon="key" :href="route('permissions.index')"
                    :current="request()->routeIs('permissions.index')" wire:navigate>
                    Permisos
                </flux:sidebar.item>
            </flux:sidebar.group>
        </flux:sidebar.nav>

        <flux:sidebar.nav>
            <flux:sidebar.group :heading="__('Finanzas')" class="grid">
                <flux:sidebar.item icon="arrow-trending-up" :href="route('incomes.index')"
                    :current="request()->routeIs('incomes.*')" wire:navigate>
                    Ingresos
                </flux:sidebar.item>

                <flux:sidebar.item icon="arrow-trending-down" :href="route('expenses.index')"
                    :current="request()->routeIs('expenses.*')" wire:navigate>
                    Egresos
                </flux:sidebar.item>
            </flux:sidebar.group>
        </flux:sidebar.nav>

        <flux:sidebar.nav>
            <flux:sidebar.group :heading="__('Reportes')" class="grid">
                <flux:sidebar.item icon="chart-bar" :href="route('dashboard')"
                    :current="request()->routeIs('dashboard')" wire:navigate>
                    Estadisticas
                </flux:sidebar.item>

                <flux:sidebar.item icon="trophy" :href="route('dashboard')"
                    :current="request()->routeIs('dashboard')" wire:navigate>
                    KPIs
                </flux:sidebar.item>
            </flux:sidebar.group>
        </flux:sidebar.nav>

        <flux:sidebar.nav>
            <flux:sidebar.group :heading="__('Configuración')" class="grid">
                <flux:sidebar.item icon="tag" :href="route('concepts.index')"
                    :current="request()->routeIs('concepts.index')" wire:navigate>
                    Conceptos
                </flux:sidebar.item>
            </flux:sidebar.group>
        </flux:sidebar.nav>

        <flux:spacer />
        <x-desktop-user-menu class="hidden lg:block" />
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                        {{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer" data-test="logout-button">
                        {{ __('Log out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    @fluxScripts
</body>

</html>
