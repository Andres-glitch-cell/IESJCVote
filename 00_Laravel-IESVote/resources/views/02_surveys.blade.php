<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Votación · IESJC</title>

    <style>
        /* ─── FUENTE ─────────────────────────────────────────────────────── */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap');

        /* ─── VARIABLES GLOBALES DE COLOR ────────────────────────────────── */
        :root {
            --bg: #0b0c10;
            --card: rgba(255, 255, 255, .06);
            --stroke: rgba(255, 255, 255, .12);
            --text: rgba(255, 255, 255, .92);
            --muted: rgba(255, 255, 255, .55);
            --success: #2ecc71;
            --error: #e74c3c;
        }

        /* ─── RESET BÁSICO ───────────────────────────────────────────────── */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* ─── FONDO Y TIPOGRAFÍA GLOBAL ──────────────────────────────────── */
        body {
            font-family: Inter, sans-serif;
            background:
                radial-gradient(circle at 20% 20%, rgba(255, 255, 255, .08), transparent 35%),
                radial-gradient(circle at 80% 0%, rgba(255, 255, 255, .05), transparent 40%),
                var(--bg);
            color: var(--text);
            min-height: 100vh;
            padding: 60px 20px;
        }

        /* ─── LAYOUT PRINCIPAL ───────────────────────────────────────────── */
        .wrapper {
            max-width: 600px;
            margin: 0 auto;
        }

        /* ─── CABECERA (kicker + título) ─────────────────────────────────── */
        .cabecera {
            text-align: center;
            margin-bottom: 40px;
        }

        .kicker {
            font-size: 11px;
            letter-spacing: .22em;
            text-transform: uppercase;
            color: var(--muted);
        }

        .titulo {
            font-size: 36px;
            font-weight: 500;
            margin-top: 10px;
        }

        /* ─── CARD DE CADA ENCUESTA ──────────────────────────────────────── */
        .card-encuesta {
            background: var(--card);
            border: 1px solid var(--stroke);
            border-radius: 18px;
            padding: 28px;
            backdrop-filter: blur(18px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, .4);
            margin-bottom: 30px;
            transition: .3s;
        }

        .card-encuesta:hover {
            transform: translateY(-2px);
            border-color: rgba(255, 255, 255, .2);
        }

        .titulo-encuesta {
            font-size: 20px;
            font-weight: 500;
            margin-bottom: 20px;
            line-height: 1.4;
        }

        /* ─── OPCIONES DE VOTACIÓN ───────────────────────────────────────── */
        .opciones-container {
            margin-bottom: 24px;
        }

        .opcion-item {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, .02);
            border: 1px solid var(--stroke);
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: .2s;
        }

        .opcion-item:hover {
            background: rgba(255, 255, 255, .05);
            border-color: rgba(255, 255, 255, .25);
        }

        .opcion-item input[type="radio"] {
            margin-right: 14px;
            accent-color: rgba(255, 255, 255, .92);
            cursor: pointer;
            width: 16px;
            height: 16px;
        }

        .opcion-item label {
            cursor: pointer;
            font-size: 14px;
            color: var(--text);
            width: 100%;
        }

        /* ─── BOTÓN EMITIR VOTO ──────────────────────────────────────────── */
        button {
            width: 100%;
            padding: 13px;
            border: 0;
            border-radius: 12px;
            background: rgba(255, 255, 255, .92);
            color: #0b0c10;
            font-weight: 500;
            font-size: 13px;
            letter-spacing: .08em;
            cursor: pointer;
            transition: .2s;
        }

        button:hover {
            opacity: .9;
            transform: translateY(-1px);
        }

        /* ─── ALERTAS FLASH (éxito / error) ──────────────────────────────── */
        .alert {
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 25px;
            text-align: center;
            font-size: 14px;
        }

        .alert-success {
            background: rgba(46, 204, 113, 0.15);
            border: 1px solid var(--success);
            color: var(--success);
        }

        .alert-error {
            background: rgba(231, 76, 60, 0.15);
            border: 1px solid var(--error);
            color: var(--error);
        }

        /* ─── BLOQUEO VISUAL (ya votó en esta encuesta) ──────────────────── */
        .voto-registrado-box {
            text-align: center;
            padding: 20px 0;
            color: var(--muted);
            font-size: 14px;
        }

        .voto-registrado-tag {
            color: var(--success);
            font-weight: 600;
            display: block;
            margin-bottom: 6px;
            letter-spacing: .05em;
        }

        /* ─── ESTADO VACÍO (sin encuestas activas) ───────────────────────── */
        .no-data {
            text-align: center;
            color: var(--muted);
            font-size: 15px;
            padding: 40px 0;
        }

        /* ─── NAVEGACIÓN INFERIOR ────────────────────────────────────────── */
        .footer-navigation {
            display: flex;
            justify-content: center;
            gap: 24px;
            margin-top: 40px;
        }

        .btn-volver {
            color: var(--muted);
            text-decoration: none;
            font-size: 13px;
            letter-spacing: .05em;
            transition: .2s;
        }

        .btn-volver:hover {
            color: var(--text);
            text-decoration: underline;
        }
    </style>
</head>

<body>

    {{-- ── MENÚ DE USUARIO (esquina superior derecha) ────────────────────── --}}
    <a href="{{ route('profile') }}" class="user-menu-btn"
        style="position: fixed; top: 20px; right: 20px; display: flex; align-items: center; gap: 10px;
               background: rgba(255, 255, 255, 0.05); padding: 8px 15px; border-radius: 30px;
               text-decoration: none; border: 1px solid rgba(255, 255, 255, 0.1); transition: 0.3s; z-index: 100;">

        {{-- Nombre del usuario autenticado --}}
        <div style="text-align: right;">
            <span style="font-size: 12px; color: rgba(255, 255, 255, 0.9); display: block; font-weight: 500;">
                {{ auth()->user()->name ?? 'Usuario' }}
            </span>
        </div>

        {{-- Icono de perfil --}}
        <div
            style="width: 32px; height: 32px; background: rgba(255, 255, 255, 0.1); border-radius: 50%;
                    display: flex; align-items: center; justify-content: center;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" style="color: white;">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
        </div>
    </a>

    {{-- Hover del menú de usuario --}}
    <style>
        .user-menu-btn:hover {
            background: rgba(255, 255, 255, 0.1) !important;
            border-color: rgba(255, 255, 255, 0.3) !important;
        }
    </style>

    <div class="wrapper">

        {{-- ── CABECERA ───────────────────────────────────────────────────── --}}
        <div class="cabecera">
            <div class="kicker">Portal del Elector</div>
            <div class="titulo">Consultas Activas</div>
        </div>

        {{-- ── ALERTAS FLASH DE SESIÓN ────────────────────────────────────── --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        {{-- ── LISTADO DE ENCUESTAS ────────────────────────────────────────── --}}
        @if ($surveys->isEmpty())
            {{-- No hay encuestas publicadas --}}
            <div class="card-encuesta">
                <p class="no-data">No hay ninguna consulta electoral publicada por el momento.</p>
            </div>
        @else
            {{-- Renderizamos cada encuesta activa --}}
            @foreach ($surveys as $survey)
                <div class="card-encuesta">
                    <h2 class="titulo-encuesta">{{ $survey->title }}</h2>

                    @if (in_array($survey->id, $votedSurveys))
                        {{-- El usuario ya votó: mostramos bloqueo visual en lugar del formulario --}}
                        <div class="voto-registrado-box">
                            <span class="voto-registrado-tag">✓ PARTICIPACIÓN REGISTRADA</span>
                            Ya has emitido tu voto en este proceso electoral de manera correcta.
                        </div>
                    @else
                        {{-- El usuario no ha votado: mostramos el formulario de votación --}}
                        <form action="{{ route('surveys.vote') }}" method="POST">
                            @csrf

                            {{-- Opciones de la encuesta --}}
                            <div class="opciones-container">
                                @foreach ($survey->options as $option)
                                    {{-- Al hacer click en el div, marca el radio automáticamente --}}
                                    <div class="opcion-item"
                                        onclick="document.getElementById('opcion_{{ $option->id }}').checked = true">
                                        <input type="radio" id="opcion_{{ $option->id }}" name="option_id"
                                            value="{{ $option->id }}" required>
                                        <label for="opcion_{{ $option->id }}">{{ $option->option_text }}</label>
                                    </div>
                                @endforeach
                            </div>

                            <button type="submit">Emitir Voto Seguro</button>
                        </form>
                    @endif
                </div>
            @endforeach
        @endif

        {{-- ── NAVEGACIÓN INFERIOR ────────────────────────────────────────── --}}
        <div class="footer-navigation">
            <a href="{{ route('home') }}" class="btn-volver">Cerrar Sesión / Volver al Inicio</a>
            <a href="{{ route('surveys.last_receipt') }}" class="btn-volver">Ver Último Resguardo</a>
        </div>

    </div>
</body>

</html>
