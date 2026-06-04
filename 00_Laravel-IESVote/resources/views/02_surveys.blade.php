<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Votación · IESJC</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap');

        :root {
            --bg: #0b0c10;
            --card: rgba(255, 255, 255, .06);
            --stroke: rgba(255, 255, 255, .12);
            --text: rgba(255, 255, 255, .92);
            --muted: rgba(255, 255, 255, .55);
            --success: #2ecc71;
            --error: #e74c3c;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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

        .wrapper {
            max-width: 640px;
            margin: 0 auto;
        }

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

        /* ── CARD DE ENCUESTA ─────────────────────────────────────── */
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
            margin-bottom: 6px;
            line-height: 1.4;
        }

        /* Badge de tipo */
        .tipo-info {
            font-size: 11px;
            letter-spacing: .1em;
            color: var(--muted);
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        /* ── SECCIÓN DE CATEGORÍA ─────────────────────────────────── */
        .categoria-titulo {
            font-size: 11px;
            letter-spacing: .16em;
            text-transform: uppercase;
            color: var(--muted);
            margin: 20px 0 10px;
            padding-bottom: 6px;
            border-bottom: 1px solid var(--stroke);
        }

        /* ── OPCIONES ─────────────────────────────────────────────── */
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
            margin-bottom: 10px;
            cursor: pointer;
            transition: .2s;
            user-select: none;
        }

        .opcion-item:hover {
            background: rgba(255, 255, 255, .05);
            border-color: rgba(255, 255, 255, .25);
        }

        .opcion-item.seleccionada {
            background: rgba(255, 255, 255, .07);
            border-color: rgba(255, 255, 255, .4);
        }

        .opcion-item input[type="radio"],
        .opcion-item input[type="checkbox"] {
            margin-right: 14px;
            accent-color: rgba(255, 255, 255, .92);
            cursor: pointer;
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }

        .opcion-item label {
            cursor: pointer;
            font-size: 14px;
            color: var(--text);
            width: 100%;
        }

        /* Contador de selecciones para tipos múltiples */
        .contador-selecciones {
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 16px;
            text-align: right;
        }

        .contador-selecciones span {
            color: var(--text);
            font-weight: 600;
        }

        /* ── BOTÓN VOTAR ──────────────────────────────────────────── */
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

        button:disabled {
            opacity: .35;
            cursor: not-allowed;
            transform: none;
        }

        /* ── ALERTAS FLASH ────────────────────────────────────────── */
        .alert {
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 25px;
            text-align: center;
            font-size: 14px;
        }

        .alert-success {
            background: rgba(46, 204, 113, .15);
            border: 1px solid var(--success);
            color: var(--success);
        }

        .alert-error {
            background: rgba(231, 76, 60, .15);
            border: 1px solid var(--error);
            color: var(--error);
        }

        /* ── YA VOTÓ ──────────────────────────────────────────────── */
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

        /* ── SIN ENCUESTAS ────────────────────────────────────────── */
        .no-data {
            text-align: center;
            color: var(--muted);
            font-size: 15px;
            padding: 40px 0;
        }

        /* ── NAV INFERIOR ─────────────────────────────────────────── */
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

    {{-- ── MENÚ USUARIO ──────────────────────────────────────────────── --}}
    <a href="{{ route('profile') }}" class="user-menu-btn"
        style="position:fixed; top:20px; right:20px; display:flex; align-items:center; gap:10px;
              background:rgba(255,255,255,.05); padding:8px 15px; border-radius:30px;
              text-decoration:none; border:1px solid rgba(255,255,255,.1); transition:.3s; z-index:100;">
        <div style="text-align:right;">
            <span style="font-size:12px; color:rgba(255,255,255,.9); display:block; font-weight:500;">
                {{ auth()->user()->name ?? 'Usuario' }}
            </span>
        </div>
        <div
            style="width:32px; height:32px; background:rgba(255,255,255,.1); border-radius:50%;
                    display:flex; align-items:center; justify-content:center;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" style="color:white;">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
        </div>
    </a>
    <style>
        .user-menu-btn:hover {
            background: rgba(255, 255, 255, .1) !important;
            border-color: rgba(255, 255, 255, .3) !important;
        }
    </style>

    <div class="wrapper">

        {{-- ── CABECERA ───────────────────────────────────────────────── --}}
        <div class="cabecera">
            <div class="kicker">Portal del Elector</div>
            <div class="titulo">Consultas Activas</div>
        </div>

        {{-- ── ALERTAS FLASH ───────────────────────────────────────────── --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        {{-- ── LISTADO DE ENCUESTAS ────────────────────────────────────── --}}
        @if ($surveys->isEmpty())
            <div class="card-encuesta">
                <p class="no-data">No hay ninguna consulta electoral publicada por el momento.</p>
            </div>
        @else
            @foreach ($surveys as $survey)
                <div class="card-encuesta">

                    <h2 class="titulo-encuesta">{{ $survey->title }}</h2>

                    {{-- Descripción del tipo --}}
                    <div class="tipo-info">
                        @switch($survey->type)
                            @case('single')
                                Una opción · 1 selección
                            @break

                            @case('single_cat')
                                Por categorías · 1 selección por categoría
                            @break

                            @case('multiple')
                                Varias opciones · hasta {{ $survey->max_selections }} selecciones
                            @break

                            @case('multiple_cat')
                                Por categorías · hasta {{ $survey->max_selections }} selecciones por categoría
                            @break
                        @endswitch
                    </div>

                    @if (in_array($survey->id, $votedSurveys))
                        {{-- Ya votó --}}
                        <div class="voto-registrado-box">
                            <span class="voto-registrado-tag">✓ PARTICIPACIÓN REGISTRADA</span>
                            Ya has emitido tu voto en este proceso electoral de manera correcta.
                        </div>
                    @else
                        {{-- Formulario de votación --}}
                        <form action="{{ route('surveys.vote') }}" method="POST" data-type="{{ $survey->type }}"
                            data-max="{{ $survey->max_selections }}">
                            @csrf

                            @php
                                $isMultiple = $survey->isMultiple();
                                $hasCategories = $survey->hasCategories();

                                // Para tipos con categoría agrupamos las opciones
                                $grouped = $hasCategories
                                    ? $survey->options->groupBy('category')
                                    : ['__all__' => $survey->options];
                            @endphp

                            {{-- Contador solo para tipos múltiples --}}
                            @if ($isMultiple)
                                <div class="contador-selecciones">
                                    Seleccionadas: <span class="cnt"
                                        data-max="{{ $survey->max_selections }}">0</span>
                                    / {{ $survey->max_selections }}
                                </div>
                            @endif

                            <div class="opciones-container">
                                @foreach ($grouped as $categoria => $opciones)
                                    {{-- Cabecera de categoría (tipos B y D) --}}
                                    @if ($hasCategories)
                                        <div class="categoria-titulo">{{ $categoria }}</div>
                                    @endif

                                    @foreach ($opciones as $option)
                                        <div class="opcion-item"
                                            onclick="toggleOpcion(this, '{{ $isMultiple ? 'checkbox' : 'radio' }}')">

                                            @if ($isMultiple)
                                                {{-- Tipo C o D: checkbox --}}
                                                <input type="checkbox" id="opcion_{{ $option->id }}"
                                                    name="option_ids[]" value="{{ $option->id }}"
                                                    class="input-opcion" data-survey="{{ $survey->id }}"
                                                    @if ($hasCategories) data-cat="{{ $option->category }}" @endif>
                                            @else
                                                {{-- Tipo A o B: radio --}}
                                                <input type="radio" id="opcion_{{ $option->id }}" name="option_id"
                                                    value="{{ $option->id }}" class="input-opcion"
                                                    data-survey="{{ $survey->id }}"
                                                    @if ($hasCategories) data-cat="{{ $option->category }}" @endif
                                                    required>
                                            @endif

                                            <label for="opcion_{{ $option->id }}">
                                                {{ $option->option_text }}
                                            </label>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>

                            <button type="submit" class="btn-votar" @if ($isMultiple) disabled @endif>
                                Emitir Voto Seguro
                            </button>
                        </form>
                    @endif

                </div>
            @endforeach
        @endif

        {{-- ── NAVEGACIÓN INFERIOR ────────────────────────────────────── --}}
        <div class="footer-navigation">
            <a href="{{ route('home') }}" class="btn-volver">Cerrar Sesión / Volver al Inicio</a>
            <a href="{{ route('surveys.last_receipt') }}" class="btn-volver">Ver Último Resguardo</a>
        </div>

    </div>

    <script>
        /**
         * toggleOpcion — gestiona el click en cada fila de opción.
         *
         * Para radio: simplemente marca el input (el onclick del div evita
         * que el label lo dispare dos veces).
         *
         * Para checkbox: marca/desmarca respetando max_selections y,
         * en tipos con categoría, garantiza 1 sola selección por categoría.
         */
        function toggleOpcion(div, tipo) {
            const input = div.querySelector('.input-opcion');
            const form = div.closest('form');

            if (tipo === 'radio') {
                // Quitar clase de todos y añadir al actual
                form.querySelectorAll('.opcion-item').forEach(d => d.classList.remove('seleccionada'));
                input.checked = true;
                div.classList.add('seleccionada');
                return;
            }

            // ── CHECKBOX ──────────────────────────────────────────────────────
            const max = parseInt(form.dataset.max) || 99;
            const hasCategories = form.dataset.type.includes('cat');
            const cat = input.dataset.cat || null;

            if (!input.checked) {
                // Intentamos marcar
                const yaSeleccionados = form.querySelectorAll('.input-opcion:checked').length;

                // Límite global de selecciones
                if (yaSeleccionados >= max) return;

                // En tipos con categoría: solo 1 por categoría
                if (hasCategories && cat) {
                    const mismaCategoria = form.querySelectorAll(`.input-opcion[data-cat="${cat}"]:checked`).length;
                    if (mismaCategoria >= 1) {
                        // Quitamos la selección previa de esa categoría
                        form.querySelectorAll(`.input-opcion[data-cat="${cat}"]`).forEach(cb => {
                            cb.checked = false;
                            cb.closest('.opcion-item').classList.remove('seleccionada');
                        });
                    }
                }

                input.checked = true;
                div.classList.add('seleccionada');
            } else {
                // Desmarcar
                input.checked = false;
                div.classList.remove('seleccionada');
            }

            // Actualizar contador y estado del botón
            const total = form.querySelectorAll('.input-opcion:checked').length;
            const cnt = form.querySelector('.cnt');
            if (cnt) cnt.textContent = total;

            const btn = form.querySelector('.btn-votar');
            if (btn) btn.disabled = (total === 0);
        }
    </script>

</body>

</html>
