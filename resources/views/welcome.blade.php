<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ERP Purificadora – Purificadora Arcoíris</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700;800&family=Barlow:wght@400;500;600&display=swap"
        rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --cyan: #00d4ff;
            --cyan2: #00a8cc;
            --emerald: #00e5a0;
            --dark: #080d12;
            --panel: rgba(10, 20, 30, 0.82);
            --border: rgba(0, 212, 255, 0.18);
            --text: #d6eaf4;
            --muted: #7a9ab0;
        }

        html,
        body {
            height: 100%;
            background: var(--dark);
            color: var(--text);
            font-family: 'Barlow', sans-serif;
            overflow-x: hidden;
        }

        /* ─── BACKGROUND ─── */
        .bg-scene {
            position: fixed;
            inset: 0;
            z-index: 0;
            background:
                radial-gradient(ellipse 80% 60% at 55% 40%, rgba(0, 180, 220, 0.10) 0%, transparent 70%),
                radial-gradient(ellipse 60% 40% at 20% 80%, rgba(0, 100, 150, 0.12) 0%, transparent 70%),
                linear-gradient(160deg, #060e17 0%, #080d12 50%, #040a10 100%);
        }

        /* grid lines */
        .bg-scene::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(0, 212, 255, 0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 212, 255, 0.04) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        /* glow orb */
        .bg-scene::after {
            content: '';
            position: absolute;
            top: -120px;
            left: 40%;
            width: 700px;
            height: 700px;
            background: radial-gradient(circle, rgba(0, 200, 255, 0.09) 0%, transparent 70%);
            pointer-events: none;
        }

        /* ─── LAYOUT ─── */
        .page {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ─── HEADER ─── */
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 48px;
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(12px);
            background: rgba(4, 10, 16, 0.6);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #00d4ff33, #00a8cc22);
            border: 1px solid rgba(0, 212, 255, 0.4);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-icon svg {
            width: 20px;
            height: 20px;
            fill: var(--cyan);
        }

        .logo-name {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 20px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #fff;
        }

        .logo-sub {
            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--muted);
            margin-top: 1px;
        }

        nav {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-ghost {
            padding: 8px 20px;
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, 0.15);
            background: transparent;
            color: var(--text);
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            letter-spacing: 0.5px;
            transition: border-color .2s, background .2s;
        }

        .btn-ghost:hover {
            border-color: var(--cyan);
            background: rgba(0, 212, 255, 0.07);
        }

        .btn-solid {
            padding: 8px 22px;
            border-radius: 6px;
            background: var(--cyan);
            color: #020c12;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            letter-spacing: 0.8px;
            transition: background .2s, box-shadow .2s;
        }

        .btn-solid:hover {
            background: #33ddff;
            box-shadow: 0 0 18px rgba(0, 212, 255, 0.4);
        }

        /* ─── MAIN GRID ─── */
        main {
            flex: 1;
            display: grid;
            grid-template-columns: 1fr 520px;
            gap: 0;
            max-width: 1440px;
            width: 100%;
            margin: 0 auto;
            padding: 0 48px 60px;
            align-items: start;
        }

        /* ─── LEFT COLUMN ─── */
        .left-col {
            padding-top: 56px;
            padding-right: 40px;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 5px 14px;
            border-radius: 999px;
            border: 1px solid rgba(0, 212, 255, 0.3);
            background: rgba(0, 212, 255, 0.08);
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: var(--cyan);
            margin-bottom: 20px;
        }

        .eyebrow-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--cyan);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: .4;
                transform: scale(.7);
            }
        }

        h1 {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: clamp(36px, 4.5vw, 64px);
            font-weight: 800;
            line-height: 1.05;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #fff;
        }

        h1 span {
            color: var(--cyan);
        }

        .hero-desc {
            margin-top: 18px;
            font-size: 15px;
            line-height: 1.7;
            color: var(--muted);
            max-width: 480px;
        }

        .cta-row {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-top: 32px;
        }

        .cta-primary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 13px 28px;
            border-radius: 8px;
            background: var(--cyan);
            color: #020c12;
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            text-decoration: none;
            transition: box-shadow .25s, background .2s;
        }

        .cta-primary:hover {
            background: #33ddff;
            box-shadow: 0 0 28px rgba(0, 212, 255, 0.5);
        }

        .cta-primary svg {
            width: 18px;
            height: 18px;
            fill: #020c12;
        }

        .cta-secondary {
            font-size: 13px;
            color: var(--muted);
            text-decoration: none;
            border-bottom: 1px solid transparent;
            transition: color .2s, border-color .2s;
        }

        .cta-secondary:hover {
            color: var(--cyan);
            border-color: var(--cyan);
        }

        /* ─── STAT CARDS ─── */
        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-top: 48px;
        }

        .stat-card {
            padding: 18px 20px;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: var(--panel);
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
            transition: border-color .25s;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--cyan), transparent);
            opacity: 0;
            transition: opacity .3s;
        }

        .stat-card:hover {
            border-color: rgba(0, 212, 255, 0.4);
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-label {
            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--muted);
        }

        .stat-value {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 32px;
            font-weight: 700;
            color: var(--cyan);
            line-height: 1.1;
            margin-top: 6px;
        }

        .stat-value.green {
            color: var(--emerald);
        }

        .stat-sub {
            font-size: 11px;
            color: var(--muted);
            margin-top: 4px;
        }

        /* ─── RIGHT COLUMN ─── */
        .right-col {
            padding-top: 36px;
            border-left: 1px solid var(--border);
            padding-left: 40px;
        }

        .modules-header {
            margin-bottom: 20px;
        }

        .modules-header h2 {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #fff;
        }

        .modules-header p {
            font-size: 12px;
            color: var(--muted);
            margin-top: 4px;
        }

        .module-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .module-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 14px 16px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: var(--panel);
            backdrop-filter: blur(8px);
            transition: border-color .25s, background .25s, transform .2s;
            cursor: default;
        }

        .module-item:hover {
            border-color: rgba(0, 212, 255, 0.45);
            background: rgba(0, 212, 255, 0.06);
            transform: translateX(4px);
        }

        .module-icon {
            flex-shrink: 0;
            width: 34px;
            height: 34px;
            background: rgba(0, 212, 255, 0.1);
            border: 1px solid rgba(0, 212, 255, 0.25);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .module-icon svg {
            width: 16px;
            height: 16px;
            stroke: var(--cyan);
            fill: none;
            stroke-width: 1.8;
        }

        .module-text h3 {
            font-size: 13px;
            font-weight: 600;
            color: #e8f4fb;
            line-height: 1.3;
        }

        .module-text p {
            font-size: 11px;
            color: var(--muted);
            margin-top: 3px;
            line-height: 1.5;
        }

        /* ─── BOTTOM BAND ─── */
        .bottom-band {
            border-top: 1px solid var(--border);
            padding: 18px 48px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(4, 10, 16, 0.5);
            backdrop-filter: blur(12px);
        }

        .version-badge {
            font-size: 11px;
            color: var(--muted);
            letter-spacing: 1px;
        }

        .version-badge strong {
            color: var(--cyan);
        }

        .status-row {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            color: var(--muted);
        }

        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--emerald);
            animation: pulse 2s infinite;
        }

        @media (max-width: 960px) {
            main {
                grid-template-columns: 1fr;
                padding: 0 24px 48px;
            }

            .right-col {
                border-left: none;
                border-top: 1px solid var(--border);
                padding-left: 0;
                padding-top: 32px;
                margin-top: 32px;
            }

            header {
                padding: 16px 24px;
            }

            .bottom-band {
                padding: 14px 24px;
            }
        }
    </style>
</head>

<body>
    <div class="bg-scene"></div>
    <div class="page">

        <!-- HEADER -->
        <header>
            <div class="logo">
                <div class="logo-icon">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2C12 2 5 10 5 15a7 7 0 0014 0c0-5-7-13-7-13z" />
                    </svg>
                </div>
                <div>
                    <div class="logo-name">Purificadora Arcoíris</div>
                    <div class="logo-sub">Sistema ERP</div>
                </div>
            </div>
            <nav>
                <a href="#" class="btn-ghost">Ayuda y Soporte</a>
                <a href="{{ route('login') }}" class="btn-solid">Iniciar Sesión</a>
            </nav>
        </header>

        <!-- MAIN -->
        <main>
            <!-- LEFT -->
            <div class="left-col">
                <div class="eyebrow">
                    <div class="eyebrow-dot"></div>
                    Operación en tiempo real
                </div>
                <h1>
                    El Corazón<br>
                    <span>Operativo</span> de<br>
                    tu Purificadora
                </h1>
                <p class="hero-desc">
                    Optimiza cada etapa del servicio: desde el escaneo de garrafones hasta la cobranza final,
                    con trazabilidad completa de rutas, repartidores y movimientos operativos.
                </p>

                <div class="cta-row">
                    @auth
                        <a href="{{ route('dashboard') }}" class="cta-primary">
                            <svg viewBox="0 0 24 24">
                                <path d="M13 5l7 7-7 7M5 12h14" />
                            </svg>
                            Ir al Panel
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="cta-primary">
                            <svg viewBox="0 0 24 24">
                                <path d="M13 5l7 7-7 7M5 12h14" />
                            </svg>
                            Comenzar Ahora
                        </a>
                    @endauth
                    <a href="#" class="cta-secondary">Explorar Módulos →</a>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-label">Entregas registradas hoy</div>
                        <div class="stat-value">248</div>
                        <div class="stat-sub">+12% vs. ayer</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Garrafones en circulación</div>
                        <div class="stat-value">3,420</div>
                        <div class="stat-sub">Con trazabilidad por código</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Rutas activas</div>
                        <div class="stat-value">14</div>
                        <div class="stat-sub">Cobertura urbana y foránea</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-label">Balance del día</div>
                        <div class="stat-value green">$18,760</div>
                        <div class="stat-sub">Ingresos – gastos operativos</div>
                    </div>
                </div>
            </div>

            <!-- RIGHT -->
            <div class="right-col">
                <div class="modules-header">
                    <h2>Módulos del Sistema</h2>
                    <p>Diseñados para la gestión integral de la operación diaria.</p>
                </div>

                <div class="module-list">
                    <div class="module-item">
                        <div class="module-icon">
                            <svg viewBox="0 0 24 24">
                                <rect x="3" y="3" width="7" height="7" />
                                <rect x="14" y="3" width="7" height="7" />
                                <rect x="3" y="14" width="7" height="7" />
                                <path d="M14 17h7M17.5 14v7" />
                            </svg>
                        </div>
                        <div class="module-text">
                            <h3>Control de garrafones por código de barras</h3>
                            <p>Identifica altas, entregas, recolecciones y estado de cada unidad.</p>
                        </div>
                    </div>
                    <div class="module-item">
                        <div class="module-icon">
                            <svg viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="9" />
                                <path d="M12 3v4M12 17v4M3 12h4M17 12h4" />
                            </svg>
                        </div>
                        <div class="module-text">
                            <h3>Rutas y repartidores</h3>
                            <p>Asigna zonas, supervisa recorridos y productividad por operador.</p>
                        </div>
                    </div>
                    <div class="module-item">
                        <div class="module-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                            </svg>
                        </div>
                        <div class="module-text">
                            <h3>Administración de clientes</h3>
                            <p>Gestiona domicilios, historial de consumo y frecuencia de servicio.</p>
                        </div>
                    </div>
                    <div class="module-item">
                        <div class="module-icon">
                            <svg viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="1" />
                                <path
                                    d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83" />
                            </svg>
                        </div>
                        <div class="module-text">
                            <h3>Entregas y recolecciones en tiempo real</h3>
                            <p>Registra eventos al momento con visibilidad inmediata para oficina.</p>
                        </div>
                    </div>
                    <div class="module-item">
                        <div class="module-icon">
                            <svg viewBox="0 0 24 24">
                                <line x1="12" y1="1" x2="12" y2="23" />
                                <path d="M17 5H9.5a3.5 3.5 0 100 7h5a3.5 3.5 0 110 7H6" />
                            </svg>
                        </div>
                        <div class="module-text">
                            <h3>Control financiero</h3>
                            <p>Monitorea ingresos, gastos, flujo diario y rentabilidad por ruta.</p>
                        </div>
                    </div>
                    <div class="module-item">
                        <div class="module-icon">
                            <svg viewBox="0 0 24 24">
                                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                            </svg>
                        </div>
                        <div class="module-text">
                            <h3>Estadísticas y reportes</h3>
                            <p>Analiza indicadores clave para decisiones operativas y comerciales.</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- BOTTOM BAND -->
        <div class="bottom-band">
            <div class="version-badge">ERP Purificadora <strong>v2.0</strong> · Purificadora Arcoíris</div>
            <div class="status-row">
                <div class="status-dot"></div>
                Todos los sistemas operativos
            </div>
        </div>

    </div>
</body>

</html>
