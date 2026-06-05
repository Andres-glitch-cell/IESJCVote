<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración · IESJCVote</title>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap');

        /* ==========================================================================
           1. VARIABLES GLOBALES Y RESETEO
           ========================================================================== */
        :root {
            --bg: #0b0c10;
            --card: rgba(255, 255, 255, .06);
            --stroke: rgba(255, 255, 255, .12);
            --text: rgba(255, 255, 255, .92);
            --muted: rgba(255, 255, 255, .55);
            --error: #ef5350;
            --success: #81c784;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background:
                radial-gradient(circle at 20% 20%, rgba(255, 255, 255, .08), transparent 35%),
                radial-gradient(circle at 80% 0%, rgba(255, 255, 255, .05), transparent 40%),
                var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* ==========================================================================
           2. LAYOUT PRINCIPAL (Grid y Contenedores)
           ========================================================================== */
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            padding: 50px 20px;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            width: 100%;
            max-width: 1250px;
        }

        .card {
            width: 100%;
            padding: 45px;
            border-radius: 20px;
            background: var(--card);
            border: 1px solid var(--stroke);
            backdrop-filter: blur(18px);
            box-shadow: 0 40px 100px rgba(0, 0, 0, .6);
        }

        /* ==========================================================================
           3. TIPOGRAFÍA Y CABECERAS
           ========================================================================== */
        .cabecera {
            text-align: center;
            margin-bottom: 50px;
        }

        .kicker {
            font-size: 11px;
            letter-spacing: .22em;
            text-transform: uppercase;
            color: var(--muted);
        }

        .titulo {
            font-size: 38px;
            font-weight: 500;
            margin-top: 12px;
        }

        .seccion-titulo {
            font-size: 16px;
            font-weight: 600;
            letter-spacing: .05em;
            margin-bottom: 28px;
            text-transform: uppercase;
            border-bottom: 1px solid var(--stroke);
            padding-bottom: 12px;
        }

        /* ==========================================================================
           4. FORMULARIOS E INPUTS
           ========================================================================== */
        .campo {
            margin-bottom: 26px;
        }

        label {
            display: block;
            font-size: 11px;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 10px;
        }

        input,
        select {
            width: 100%;
            padding: 14px 16px;
            border-radius: 12px;
            border: 1px solid var(--stroke);
            background: rgba(255, 255, 255, .03);
            color: var(--text);
            font-size: 15px;
            font-family: 'Inter', sans-serif;
        }

        select option {
            background: #16171d;
        }

        .bloque-opcion {
            display: grid;
            gap: 8px;
            margin-bottom: 12px;
        }

        /* Variantes de campos de opciones */
        .bloque-opcion.con-cat {
            grid-template-columns: 1fr 1fr auto;
            align-items: center;
        }

        .bloque-opcion.sin-cat {
            grid-template-columns: 1fr auto;
            align-items: center;
        }

        .cat-input {
            display: none;
        }

        .con-cat .cat-input {
            display: block;
        }

        #campo-max-selections {
            display: none;
        }

        /* ==========================================================================
           5. BOTONES
           ========================================================================== */
        button {
            padding: 13px 22px;
            border: 0;
            border-radius: 12px;
            background: rgba(255, 255, 255, .92);
            color: #0b0c10;
            font-weight: 500;
            cursor: pointer;
        }

        button:hover {
            filter: brightness(1.2);
        }

        .btn-primario {
            width: 100%;
            margin-top: 25px;
            padding: 15px;
        }

        .btn-eliminar {
            background: rgba(239, 83, 80, 0.15);
            color: var(--error);
            border: 1px solid rgba(239, 83, 80, 0.3);
        }

        .btn-volver {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 45px;
            color: var(--muted);
            text-decoration: none;
            font-size: 14.5px;
            transition: all 0.3s;
        }

        .btn-volver:hover {
            color: white;
            transform: translateX(6px);
        }

        /* ==========================================================================
           6. BADGES Y ETIQUETAS (Sistema visual)
           ========================================================================== */
        .tipo-badge {
            display: inline-block;
            font-size: 11px;
            padding: 3px 10px;
            border-radius: 20px;
            margin-left: 8px;
            vertical-align: middle;
        }

        .tipo-badge.radio {
            background: rgba(129, 199, 132, .15);
            color: var(--success);
            border: 1px solid rgba(129, 199, 132, .3);
        }

        .tipo-badge.checkbox {
            background: rgba(100, 181, 246, .15);
            color: #64b5f6;
            border: 1px solid rgba(100, 181, 246, .3);
        }

        .tag-tipo {
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: .08em;
            margin-left: 8px;
            opacity: .75;
        }

        .tag-single {
            background: rgba(129, 199, 132, .15);
            color: var(--success);
        }

        .tag-single_cat {
            background: rgba(100, 181, 246, .15);
            color: #64b5f6;
        }

        .tag-multiple {
            background: rgba(255, 183, 77, .15);
            color: #ffb74d;
        }

        .tag-multiple_cat {
            background: rgba(206, 147, 216, .15);
            color: #ce93d8;
        }

        /* ==========================================================================
           7. TOASTS (Notificaciones emergentes)
           ========================================================================== */
        .toast-success {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(46, 204, 113, 0.15);
            border: 1px solid var(--success);
            color: var(--success);
            padding: 16px 24px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            backdrop-filter: blur(10px);
            z-index: 3000;
            font-weight: 500;
            animation: slideIn .4s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* ==========================================================================
           8. MODAL (Confirmación de borrado)
           ========================================================================== */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(11, 12, 16, .85);
            backdrop-filter: blur(8px);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: #16171d;
            padding: 40px;
            border-radius: 20px;
            width: 90%;
            max-width: 400px;
            border: 1px solid rgba(255, 255, 255, .1);
            text-align: center;
            transform: scale(.95);
            transition: transform .3s ease;
        }

        .modal.show .modal-content {
            transform: scale(1);
        }

        .modal-title {
            font-size: 21px;
            color: #fff;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .modal-text {
            font-size: 16px;
            color: #ddd;
            margin-bottom: 32px;
            line-height: 1.55;
        }

        .modal-buttons {
            display: flex;
            gap: 16px;
            justify-content: center;
        }

        .modal-btn {
            padding: 13px 34px;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
        }

        .modal-yes {
            background: var(--error);
            color: white;
        }

        .modal-no {
            background: transparent;
            border: 1px solid #444;
            color: #fff;
        }
    </style>
</head>

<body>

    <div class="container">

        {{-- ==========================================================================
             BLOQUE A: NOTIFICACIONES (TOASTS)
             ========================================================================== --}}

        {{-- Toast de Creación Exitosa --}}
        @if (session('success'))
            <div class="toast-success" id="notif">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span>Encuesta <strong>"{{ session('success') }}"</strong> creada correctamente.</span>
            </div>
            <script>
                setTimeout(() => {
                    $('#notif').fadeOut(300);
                }, 4000);
            </script>
        @endif

        {{-- Toast de Borrado Exitoso --}}
        @if (session('deleted'))
            <div class="toast-success" id="notif-del">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span>Encuesta eliminada correctamente.</span>
            </div>
            <script>
                setTimeout(() => {
                    $('#notif-del').fadeOut(300);
                }, 4000);
            </script>
        @endif

        {{-- ==========================================================================
             BLOQUE B: CABECERA DE LA VISTA
             ========================================================================== --}}
        <div class="cabecera">
            <div class="kicker">Panel de Control Interno</div>
            <div class="titulo">Gestión Electoral e Infraestructura</div>
        </div>

        <div class="dashboard-grid">

            {{-- ==========================================================================
                 BLOQUE C: FORMULARIO DE CREACIÓN DE ENCUESTAS
                 ========================================================================== --}}
            <form action="{{ route('surveys.store') }}" method="POST" class="card">
                @csrf
                <div class="seccion-titulo">Crear Nueva Votación</div>

                @if (session('error'))
                    <div style="color:var(--error); margin-bottom: 20px;">{{ session('error') }}</div>
                @endif

                {{-- Campo: Título --}}
                <div class="campo">
                    <label for="title">Tema / Pregunta de la Votación</label>
                    <input type="text" id="title" name="title" placeholder="Ej. ¿Quién debe ser el delegado?"
                        required>
                </div>

                {{-- Campo: Tipo de Votación --}}
                <div class="campo">
                    <label for="type">
                        Tipo de Votación
                        <span id="badge-tipo" class="tipo-badge radio">Radio · 1 selección</span>
                    </label>
                    <select id="type" name="type" required>
                        <option value="single" selected>A — Varias opciones · 1 selección</option>
                        <option value="single_cat"> B — Categorías · 1 selección por categoría</option>
                        <option value="multiple"> C — Varias opciones · varias selecciones</option>
                        <option value="multiple_cat">D — Categorías · varias selecciones por categoría</option>
                    </select>
                </div>

                {{-- Campo Dinámico: Máximo de selecciones (Solo tipos C y D) --}}
                <div class="campo" id="campo-max-selections">
                    <label for="max_selections">Máximo de selecciones permitidas</label>
                    <input type="number" id="max_selections" name="max_selections" min="2" value="2"
                        placeholder="Ej. 3">
                </div>

                {{-- Campo: Creación de Opciones --}}
                <div class="campo">
                    <label id="label-opciones">
                        Opciones Disponibles
                        <small id="hint-cat"
                            style="display:none; text-transform:none; letter-spacing:0; font-size:11px; color:var(--muted);">
                            — escribe la categoría en el campo de la derecha
                        </small>
                    </label>

                    <div id="contenedor-opciones">
                        {{-- Opción 1 --}}
                        <div class="bloque-opcion sin-cat">
                            <input type="text" name="options[]" placeholder="Opción 1" required>
                            <input type="text" name="categories[]" placeholder="Categoría" class="cat-input">
                        </div>
                        {{-- Opción 2 --}}
                        <div class="bloque-opcion sin-cat">
                            <input type="text" name="options[]" placeholder="Opción 2" required>
                            <input type="text" name="categories[]" placeholder="Categoría" class="cat-input">
                        </div>
                    </div>

                    {{-- Privacidad y Botón Añadir --}}
                    <div class="campo">
                        <label>Configuración de Privacidad</label>
                        <select name="is_anonymous">
                            <option value="1">Anónima (Ocultar identidad del votante)</option>
                            <option value="0">Nominativa (Registrar quien vota)</option>
                        </select>
                    </div>
                    <button type="button" id="btn-add-opcion" style="margin-top:12px;">Añadir Opción</button>
                </div>
                {{-- Campo: Roles permitidos --}}
                <div class="campo">
                    <label>Visible para</label>
                    <div style="display: flex; gap: 10px; margin-top: 4px;">

                        <label
                            style="display:flex; align-items:center; gap:6px; font-size:13px;
                       color:var(--text); text-transform:none; letter-spacing:0;
                       background:rgba(255,255,255,.03); border:1px solid var(--stroke);
                       padding:10px 14px; border-radius:10px; cursor:pointer;">
                            <input type="checkbox" name="allowed_roles[]" value="alumno"
                                style="width:auto; accent-color:white;">
                            Alumnos
                        </label>

                        <label
                            style="display:flex; align-items:center; gap:6px; font-size:13px;
                       color:var(--text); text-transform:none; letter-spacing:0;
                       background:rgba(255,255,255,.03); border:1px solid var(--stroke);
                       padding:10px 14px; border-radius:10px; cursor:pointer;">
                            <input type="checkbox" name="allowed_roles[]" value="profesor"
                                style="width:auto; accent-color:white;">
                            Profesores
                        </label>

                        <label
                            style="display:flex; align-items:center; gap:6px; font-size:13px;
                       color:var(--text); text-transform:none; letter-spacing:0;
                       background:rgba(255,255,255,.03); border:1px solid var(--stroke);
                       padding:10px 14px; border-radius:10px; cursor:pointer;">
                            <input type="checkbox" name="allowed_roles[]" value="padre"
                                style="width:auto; accent-color:white;">
                            Padres
                        </label>

                    </div>
                    <small style="color:var(--muted); font-size:11px; margin-top:6px; display:block;">
                        Si no marcas ninguno, la encuesta será visible para todos.
                    </small>
                </div>
                <button type="submit" class="btn-primario">Publicar Encuesta</button>
            </form>

            {{-- ==========================================================================
                 BLOQUE D: INFORMACIÓN DEL SISTEMA (LEYENDA)
                 ========================================================================== --}}
            <div class="card">
                <div class="seccion-titulo">Tipos de Votación</div>
                <div style="color:var(--muted); font-size:14px; line-height:2;">
                    <div><span class="tag-tipo tag-single">A</span> Varias opciones &mdash; 1 sola selección (radio)
                    </div>
                    <div><span class="tag-tipo tag-single_cat">B</span> Categorías &mdash; 1 selección por categoría
                        (radio)</div>
                    <div><span class="tag-tipo tag-multiple">C</span> Varias opciones &mdash; varias selecciones
                        (checkbox)</div>
                    <div><span class="tag-tipo tag-multiple_cat">D</span> Categorías &mdash; varias selecciones por
                        categoría (checkbox)</div>
                    <hr style="border-color:var(--stroke); margin: 18px 0;">
                    <p>Recuerda verificar la integridad de los datos antes de publicar.</p>
                </div>
            </div>

            {{-- ==========================================================================
                 BLOQUE E: TABLA DE ENCUESTAS ACTIVAS (CON BORRADO)
                 ========================================================================== --}}
            <div class="card" style="grid-column: 1 / -1;">
                <div class="seccion-titulo">Encuestas Activas</div>
                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:collapse; color:var(--text); font-size:14px;">
                        <thead>
                            <tr style="border-bottom:1px solid var(--stroke);">
                                <th style="text-align:left; padding:16px 12px;">Título</th>
                                <th style="text-align:left; padding:16px 12px;">Tipo</th>
                                <th style="text-align:right; padding:16px 12px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($surveys as $survey)
                                <tr style="border-bottom:1px solid rgba(255,255,255,0.05);">
                                    <td style="padding:16px 12px;">{{ $survey->title }}</td>
                                    <td style="padding:16px 12px;">
                                        <span class="tag-tipo tag-{{ $survey->type }}">
                                            {{ strtoupper($survey->type) }}
                                        </span>
                                    </td>
                                    <td style="text-align:right; padding:16px 12px;">
                                        {{-- Botón que activa el modal --}}
                                        <button type="button"
                                            onclick="showDeleteModal('{{ $survey->id }}', '{{ addslashes($survey->title) }}')"
                                            class="btn-eliminar">Eliminar</button>
                                        {{-- Formulario oculto de borrado seguro (CSRF + Method Spoofing) --}}
                                        <form id="delete-form-{{ $survey->id }}"
                                            action="{{ route('surveys.destroy', $survey->id) }}" method="POST"
                                            style="display:none;">
                                            @csrf @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ==========================================================================
                 BLOQUE F: GESTIÓN DE CONTROL Y RESULTADOS
                 ========================================================================== --}}
            <div class="card" style="grid-column: 1 / -1; margin-top: 20px;">
                <div class="seccion-titulo">Gestión de Control y Resultados</div>
                <table style="width:100%; border-collapse:collapse; color:var(--text); font-size:14px;">
                    <thead>
                        <tr style="border-bottom:1px solid var(--stroke);">
                            <th style="padding:16px 12px; text-align: left;">Votación</th>
                            <th style="padding:16px 12px; text-align: left;">Recuento</th>
                            <th style="padding:16px 12px; text-align: right;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($surveys as $survey)
                            <tr style="border-bottom:1px solid var(--stroke);">
                                <td style="padding:16px 12px;">{{ $survey->title }}</td>
                                {{-- Formulario Toggle Activar/Desactivar Votación --}}
                                <td style="padding:16px 12px;">
                                    <a href="{{ route('admin.toggle.maintenance') }}"
                                        style="display: flex; align-items: center; gap: 8px;
              background: {{ $survey->recount_active ? '#81c784' : '#444' }};
              color: white; border-radius: 6px; padding: 6px 12px;
              cursor: pointer; border: none; font-size: 12px;
              text-decoration: none; width: fit-content; transition: 0.3s;">
                                        <span
                                            style="width: 8px; height: 8px; border-radius: 50%;
                     background: {{ $survey->recount_active ? '#fff' : '#777' }};"></span>
                                        {{ $survey->recount_active ? 'DESACTIVAR' : 'ACTIVAR' }}
                                    </a>
                                </td>
                                <td style="padding:16px 12px; text-align: right;">
                                    <a href="{{ route('admin.maintenance') }}"
                                        style="color: #64b5f6; text-decoration: none; font-weight: 500;">
                                        Ver Resultados
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

        {{-- Botón de Navegación Inferior --}}
        <a href="{{ route('home') }}" class="btn-volver">← Volver al inicio</a>
    </div>

    {{-- ==========================================================================
         BLOQUE G: ESTRUCTURA DEL MODAL DE BORRADO
         ========================================================================== --}}
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <svg width="52" height="52" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg" style="margin-bottom:20px;">
                <path d="M12 2L2 20h20L12 2z" stroke="#f59e0b" stroke-width="1.5" stroke-linejoin="round"
                    fill="rgba(245,158,11,0.15)" />
                <line x1="12" y1="9" x2="12" y2="14" stroke="#f59e0b"
                    stroke-width="2" stroke-linecap="round" />
                <circle cx="12" cy="17" r="1" fill="#f59e0b" />
            </svg>
            <div class="modal-title">¿Estás seguro?</div>
            <div class="modal-text" id="modalSurveyName"></div>
            <div class="modal-buttons">
                <button class="modal-btn modal-no" onclick="hideDeleteModal()">Cancelar</button>
                <button class="modal-btn modal-yes" id="confirmDeleteBtn">Sí, eliminar</button>
            </div>
        </div>
    </div>

    {{-- ==========================================================================
         BLOQUE H: LÓGICA DE JAVASCRIPT (JQUERY)
         ========================================================================== --}}
    <script>
        $(document).ready(function() {

            /* -------------------------------------------------------------
               H.1 LÓGICA DEL TIPO DE VOTACIÓN (Manejo de UI dinámico)
               ------------------------------------------------------------- */
            const tipoDescripciones = {
                single: {
                    label: 'Radio · 1 selección',
                    cls: 'radio',
                    cat: false,
                    multi: false
                },
                single_cat: {
                    label: 'Radio · categorías',
                    cls: 'radio',
                    cat: true,
                    multi: false
                },
                multiple: {
                    label: 'Checkbox · varias selecciones',
                    cls: 'checkbox',
                    cat: false,
                    multi: true
                },
                multiple_cat: {
                    label: 'Checkbox · categorías',
                    cls: 'checkbox',
                    cat: true,
                    multi: true
                },
            };

            function aplicarTipo(tipo) {
                const cfg = tipoDescripciones[tipo];

                // Actualizar Badge Visual
                $('#badge-tipo')
                    .text(cfg.label)
                    .removeClass('radio checkbox')
                    .addClass(cfg.cls);

                // Ocultar/Mostrar Campo max_selections
                if (cfg.multi) {
                    $('#campo-max-selections').show();
                    $('#max_selections').attr('required', true);
                } else {
                    $('#campo-max-selections').hide();
                    $('#max_selections').removeAttr('required');
                }

                // Ocultar/Mostrar Columna de Categorías
                if (cfg.cat) {
                    $('#hint-cat').show();
                    $('.bloque-opcion').removeClass('sin-cat').addClass('con-cat');
                    $('.cat-input').attr('required', true);
                } else {
                    $('#hint-cat').hide();
                    $('.bloque-opcion').removeClass('con-cat').addClass('sin-cat');
                    $('.cat-input').removeAttr('required');
                }
            }

            // Escuchar cambios en el selector de tipo
            $('#type').on('change', function() {
                aplicarTipo($(this).val());
            });

            /* -------------------------------------------------------------
               H.2 AÑADIR NUEVA OPCIÓN DINÁMICAMENTE
               ------------------------------------------------------------- */
            $('#btn-add-opcion').click(function() {
                const tieneCategoria = $('#type').val().includes('cat');
                const claseRow = tieneCategoria ? 'con-cat' : 'sin-cat';
                const reqCat = tieneCategoria ? 'required' : '';
                const count = $('#contenedor-opciones .bloque-opcion').length + 1;

                // Plantilla HTML para la nueva fila
                const bloque = `
                    <div class="bloque-opcion ${claseRow}">
                        <input type="text" name="options[]"    placeholder="Opción ${count}" required>
                        <input type="text" name="categories[]" placeholder="Categoría" class="cat-input" ${reqCat}>
                        <button type="button" class="btn-eliminar" style="padding:10px 15px;" onclick="$(this).parent().remove()">×</button>
                    </div>`;

                $('#contenedor-opciones').append(bloque);
            });

            /* -------------------------------------------------------------
               H.3 GESTIÓN DEL MODAL DE ELIMINACIÓN DE ENCUESTAS
               ------------------------------------------------------------- */
            let surveyIdToDelete = null;

            // Función Global para abrir el modal
            window.showDeleteModal = function(id, title) {
                surveyIdToDelete = id;
                $('#modalSurveyName').html(
                    `Se eliminará permanentemente la encuesta <strong>"${title}"</strong>. Esta acción no se puede deshacer.`
                );
                $('#deleteModal').css('display', 'flex').hide().fadeIn(300);
            };

            // Función Global para cerrar el modal
            window.hideDeleteModal = function() {
                $('#deleteModal').fadeOut(300);
            };

            // Ejecutar el borrado si se confirma
            $('#confirmDeleteBtn').click(function() {
                if (surveyIdToDelete) {
                    $(`#delete-form-${surveyIdToDelete}`).submit();
                }
            });
        });
    </script>

</body>

</html>
