<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión – Purificadora Arcoíris</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700;800&family=Barlow:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --cyan:    #00d4ff;
            --cyan2:   #00a8cc;
            --emerald: #00e5a0;
            --dark:    #080d12;
            --panel:   rgba(10,20,30,0.88);
            --border:  rgba(0,212,255,0.18);
            --input-bg: rgba(4,12,20,0.8);
            --input-border: rgba(0,212,255,0.2);
            --text:    #d6eaf4;
            --muted:   #7a9ab0;
        }

        html, body {
            height: 100%;
            background: var(--dark);
            color: var(--text);
            font-family: 'Barlow', sans-serif;
        }

        /* ─── BACKGROUND ─── */
        .bg-scene {
            position: fixed; inset: 0; z-index: 0;
            background:
                radial-gradient(ellipse 70% 60% at 50% 30%, rgba(0,180,220,0.10) 0%, transparent 70%),
                radial-gradient(ellipse 50% 40% at 10% 90%, rgba(0,100,150,0.10) 0%, transparent 70%),
                linear-gradient(160deg, #060e17 0%, #080d12 50%, #040a10 100%);
        }
        .bg-scene::before {
            content: '';
            position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(0,212,255,0.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,212,255,0.035) 1px, transparent 1px);
            background-size: 60px 60px;
        }
        .bg-scene::after {
            content: '';
            position: absolute;
            top: -200px; left: 50%;
            transform: translateX(-50%);
            width: 700px; height: 700px;
            background: radial-gradient(circle, rgba(0,200,255,0.08) 0%, transparent 70%);
            pointer-events: none;
        }

        /* ─── PAGE LAYOUT ─── */
        .page {
            position: relative; z-index: 1;
            min-height: 100vh;
            display: flex; flex-direction: column;
        }

        /* ─── HEADER ─── */
        header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 20px 48px;
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(12px);
            background: rgba(4,10,16,0.6);
        }
        .logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .logo-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, #00d4ff22, #00a8cc18);
            border: 1px solid rgba(0,212,255,0.35);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }
        .logo-icon svg { width: 20px; height: 20px; fill: var(--cyan); }
        .logo-name {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 20px; font-weight: 800;
            letter-spacing: 1.5px; text-transform: uppercase; color: #fff;
        }
        .logo-sub {
            font-size: 10px; letter-spacing: 2px;
            text-transform: uppercase; color: var(--muted); margin-top: 1px;
        }
        .back-link {
            font-size: 13px; color: var(--muted);
            text-decoration: none;
            display: flex; align-items: center; gap: 6px;
            transition: color .2s;
        }
        .back-link:hover { color: var(--cyan); }
        .back-link svg { width: 14px; height: 14px; stroke: currentColor; fill: none; stroke-width: 2; }

        /* ─── CENTER CONTAINER ─── */
        .center {
            flex: 1; display: flex;
            align-items: center; justify-content: center;
            padding: 48px 24px;
        }

        /* ─── LOGIN CARD ─── */
        .card {
            width: 100%; max-width: 440px;
            border: 1px solid var(--border);
            border-radius: 18px;
            background: var(--panel);
            backdrop-filter: blur(18px);
            overflow: hidden;
            box-shadow: 0 32px 80px rgba(0,0,0,0.5), 0 0 0 1px rgba(0,212,255,0.06);
            position: relative;
        }
        /* top glow line */
        .card::before {
            content: '';
            position: absolute; top: 0; left: 15%; right: 15%; height: 1px;
            background: linear-gradient(90deg, transparent, var(--cyan), transparent);
            opacity: 0.7;
        }

        .card-top {
            padding: 36px 36px 0;
            text-align: center;
        }
        .card-icon {
            width: 52px; height: 52px; margin: 0 auto 20px;
            background: rgba(0,212,255,0.1);
            border: 1px solid rgba(0,212,255,0.3);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
        }
        .card-icon svg { width: 26px; height: 26px; fill: var(--cyan); }
        .card-title {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 26px; font-weight: 800;
            letter-spacing: 1px; text-transform: uppercase; color: #fff;
        }
        .card-desc {
            font-size: 13px; color: var(--muted);
            margin-top: 6px; line-height: 1.6;
        }

        .card-body { padding: 28px 36px 36px; }

        /* ─── SESSION STATUS ─── */
        .session-status {
            padding: 10px 14px;
            border-radius: 8px;
            background: rgba(0,229,160,0.08);
            border: 1px solid rgba(0,229,160,0.25);
            color: var(--emerald);
            font-size: 13px;
            margin-bottom: 20px;
            text-align: center;
        }

        /* ─── FORM ─── */
        form { display: flex; flex-direction: column; gap: 20px; }

        .field { display: flex; flex-direction: column; gap: 7px; }
        .field-row {
            display: flex; align-items: center; justify-content: space-between;
        }
        label {
            font-size: 12px; font-weight: 600;
            letter-spacing: 1.5px; text-transform: uppercase;
            color: var(--muted);
        }
        .forgot-link {
            font-size: 12px; color: var(--muted);
            text-decoration: none;
            transition: color .2s;
        }
        .forgot-link:hover { color: var(--cyan); }

        .input-wrap { position: relative; }
        .input-wrap svg.input-icon {
            position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
            width: 15px; height: 15px;
            stroke: var(--muted); fill: none; stroke-width: 1.8;
            pointer-events: none;
            transition: stroke .2s;
        }
        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 11px 14px 11px 40px;
            border-radius: 8px;
            border: 1px solid var(--input-border);
            background: var(--input-bg);
            color: var(--text);
            font-family: 'Barlow', sans-serif;
            font-size: 14px;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }
        input::placeholder { color: #3a5568; }
        input:focus {
            border-color: rgba(0,212,255,0.5);
            box-shadow: 0 0 0 3px rgba(0,212,255,0.08);
        }
        input:focus + svg.input-icon,
        .input-wrap:focus-within svg.input-icon { stroke: var(--cyan); }

        .toggle-pw {
            position: absolute; right: 13px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer; padding: 2px;
            color: var(--muted);
            transition: color .2s;
        }
        .toggle-pw:hover { color: var(--cyan); }
        .toggle-pw svg { width: 16px; height: 16px; stroke: currentColor; fill: none; stroke-width: 1.8; display: block; }

        /* ─── REMEMBER ME ─── */
        .checkbox-row {
            display: flex; align-items: center; gap: 10px;
        }
        .checkbox-row input[type="checkbox"] {
            width: 16px; height: 16px; padding: 0;
            accent-color: var(--cyan);
            border-radius: 4px;
        }
        .checkbox-row label {
            font-size: 13px; font-weight: 400;
            letter-spacing: 0; text-transform: none;
            color: var(--muted); cursor: pointer;
        }

        /* ─── SUBMIT BUTTON ─── */
        .btn-submit {
            width: 100%; padding: 13px;
            border-radius: 8px; border: none;
            background: var(--cyan); color: #020c12;
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 15px; font-weight: 700;
            letter-spacing: 2px; text-transform: uppercase;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: background .2s, box-shadow .25s;
        }
        .btn-submit svg { width: 16px; height: 16px; stroke: #020c12; fill: none; stroke-width: 2.5; }
        .btn-submit:hover {
            background: #33ddff;
            box-shadow: 0 0 28px rgba(0,212,255,0.45);
        }
        .btn-submit:active { transform: scale(.98); }

        /* ─── DIVIDER ─── */
        .divider {
            display: flex; align-items: center; gap: 12px;
            color: var(--muted); font-size: 11px;
        }
        .divider::before, .divider::after {
            content: ''; flex: 1;
            height: 1px; background: var(--border);
        }

        /* ─── REGISTER LINK ─── */
        .register-row {
            text-align: center;
            font-size: 13px; color: var(--muted);
        }
        .register-row a {
            color: var(--cyan); text-decoration: none;
            font-weight: 600;
            transition: color .2s;
        }
        .register-row a:hover { color: #33ddff; }

        /* ─── VALIDATION ERRORS ─── */
        .field-error { font-size: 11px; color: #ff6b8a; margin-top: 4px; }

        /* ─── BOTTOM BAND ─── */
        footer {
            border-top: 1px solid var(--border);
            padding: 14px 48px;
            display: flex; align-items: center; justify-content: space-between;
            background: rgba(4,10,16,0.5);
            backdrop-filter: blur(12px);
        }
        .version-badge { font-size: 11px; color: var(--muted); letter-spacing: 1px; }
        .version-badge strong { color: var(--cyan); }
        .status-row { display: flex; align-items: center; gap: 6px; font-size: 11px; color: var(--muted); }
        .status-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: var(--emerald);
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: .4; transform: scale(.7); }
        }
    </style>
</head>
<body>
<div class="bg-scene"></div>
<div class="page">

    <!-- HEADER -->
    <header>
        <a href="{{ url('/') }}" class="logo">
            <div class="logo-icon">
                <svg viewBox="0 0 24 24"><path d="M12 2C12 2 5 10 5 15a7 7 0 0014 0c0-5-7-13-7-13z"/></svg>
            </div>
            <div>
                <div class="logo-name">Purificadora Arcoíris</div>
                <div class="logo-sub">Sistema ERP</div>
            </div>
        </a>
        <a href="{{ url('/') }}" class="back-link">
            <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
            Volver al inicio
        </a>
    </header>

    <!-- CENTER -->
    <div class="center">
        <div class="card">
            <div class="card-top">
                <div class="card-icon">
                    <svg viewBox="0 0 24 24"><path d="M12 2C12 2 5 10 5 15a7 7 0 0014 0c0-5-7-13-7-13z"/></svg>
                </div>
                <div class="card-title">Acceder al Sistema</div>
                <p class="card-desc">Ingresa tus credenciales para continuar con la operación</p>
            </div>

            <div class="card-body">

                {{-- Session Status --}}
                @if (session('status'))
                    <div class="session-status">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('login.store') }}">
                    @csrf

                    {{-- Email --}}
                    <div class="field">
                        <label for="email">Correo electrónico</label>
                        <div class="input-wrap">
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="email@ejemplo.com"
                                required
                                autofocus
                                autocomplete="email"
                            >
                            <svg class="input-icon" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </div>
                        @error('email')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="field">
                        <div class="field-row">
                            <label for="password">Contraseña</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="forgot-link">¿Olvidaste tu contraseña?</a>
                            @endif
                        </div>
                        <div class="input-wrap">
                            <input
                                id="password"
                                type="password"
                                name="password"
                                placeholder="••••••••"
                                required
                                autocomplete="current-password"
                            >
                            <svg class="input-icon" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                            <button type="button" class="toggle-pw" onclick="togglePassword()" aria-label="Mostrar contraseña">
                                <svg id="eye-icon" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                        @error('password')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Remember Me --}}
                    <div class="checkbox-row">
                        <input
                            type="checkbox"
                            id="remember"
                            name="remember"
                            {{ old('remember') ? 'checked' : '' }}
                        >
                        <label for="remember">Mantener sesión iniciada</label>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn-submit">
                        <svg viewBox="0 0 24 24"><polyline points="20 12 20 22 4 22 4 12"/><polyline points="22 7 12 2 2 7"/></svg>
                        Iniciar Sesión
                    </button>

                    {{--@if (Route::has('register'))
                        <div class="divider">o</div>
                        <div class="register-row">
                            ¿No tienes cuenta?&nbsp;
                            <a href="{{ route('register') }}">Crear una cuenta</a>
                        </div>
                    @endif--}}
                </form>

            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer>
        <div class="version-badge">ERP Purificadora <strong>v2.0</strong> · Purificadora Arcoíris</div>
        <div class="status-row">
            <div class="status-dot"></div>
            Todos los sistemas operativos
        </div>
    </footer>

</div>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const icon  = document.getElementById('eye-icon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
        } else {
            input.type = 'password';
            icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
        }
    }
</script>
</body>
</html>