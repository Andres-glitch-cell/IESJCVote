<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración · IESJCVote</title>

    <style>
        /* 1. CONFIGURACIÓN GENERAL Y VARIABLES
           ======================================================================== */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap');

        :root {
            --bg: #0b0c10;
            --card: rgba(255, 255, 255, .06);
            --stroke: rgba(255, 255, 255, .12);
            --text: rgba(255, 255, 255, .92);
            --muted: rgba(255, 255, 255, .55);
            --accent: rgba(255, 255, 255, 0.9);
            --error: #ef5350;
            --success: #81c784;
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
        }

        /* 2. ESTRUCTURA Y LAYOUT (CONTENEDORES)
           ======================================================================== */
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            padding: 40px 20px;
        }

        .cabecera {
            text-align: center;
            margin-bottom: 40px;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            width: 100%;
            max-width: 1200px;
            align-items: stretch;
        }

        .card {
            width: 100%;
            height: 100%;
            padding: 40px;
            border-radius: 18px;
            background: var(--card);
            border: 1px solid var(--stroke);
            backdrop-filter: blur(18px);
            box-shadow: 0 40px 100px rgba(0, 0, 0, .6);
            display: flex;
            flex-direction: column;
        }

        /* 3. COMPONENTES DE TEXTO Y TIPOGRAFÍA
           ======================================================================== */
        .kicker {
            font-size: 11px;
            letter-spacing: .22em;
            text-transform: uppercase;
            color: var(--muted);
        }

        .titulo {
            font-size: 38px;
            font-weight: 500;
            margin-top: 10px;
        }

        .seccion-titulo {
            font-size: 16px;
            font-weight: 600;
            letter-spacing: .05em;
            margin-bottom: 24px;
            text-transform: uppercase;
            border-bottom: 1px solid var(--stroke);
            padding-bottom: 10px;
            color: #fff;
        }

        .subseccion-titulo {
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .texto-doc {
            font-size: 13px;
            color: var(--muted);
            line-height: 1.6;
        }

        .texto-doc strong {
            color: var(--text);
        }

        /* 4. ELEMENTOS DE FORMULARIO
           ======================================================================== */
        .campo {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 11px;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid var(--stroke);
            background: rgba(255, 255, 255, .03);
            color: var(--text);
            outline: none;
            font-size: 14px;
            transition: .2s;
        }

        input:focus {
            border-color: rgba(255, 255, 255, .35);
            box-shadow: 0 0 0 4px rgba(255, 255, 255, .05);
        }

        .bloque-opcion {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
            align-items: center;
        }

        .bloque-opcion input {
            flex: 1;
        }

        /* 5. BOTONES Y ACCIONES
           ======================================================================== */
        button {
            padding: 13px 20px;
            border: 0;
            border-radius: 12px;
            background: rgba(255, 255, 255, .92);
            color: #0b0c10;
            font-weight: 500;
            font-size: 13px;
            letter-spacing: .06em;
            cursor: pointer;
            transition: .2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        button:hover {
            opacity: .9;
            transform: translateY(-1px);
        }

        .btn-primario {
            width: 100%;
            margin-top: auto;
            background: rgba(255, 255, 255, .92);
            color: #0b0c10;
        }

        .btn-accion {
            padding: 12px 15px;
            background: rgba(255, 255, 255, 0.1);
            color: var(--text);
            border: 1px solid var(--stroke);
        }

        .btn-accion:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .btn-eliminar {
            width: 42px;
            height: 44px;
            padding: 0;
            background: rgba(239, 83, 80, 0.15);
            color: var(--error);
            border: 1px solid rgba(239, 83, 80, 0.3);
        }

        .btn-eliminar:hover {
            background: rgba(239, 83, 80, 0.3);
            color: #ff6b6b;
        }

        .btn-volver {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 30px;
            color: var(--muted);
            font-size: 13px;
            text-decoration: none;
            transition: .2s;
        }

        .btn-volver:hover {
            color: var(--text);
            text-decoration: underline;
        }

        .btn-volver svg {
            transition: transform .2s;
        }

        .btn-volver:hover svg {
            transform: translateX(-3px);
        }

        /* 6. COMPONENTES DE DOCUMENTACIÓN Y LISTAS
           ======================================================================== */
        .subbloque-documentacion {
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, .05);
        }

        .subbloque-documentacion:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .lista-doc {
            padding-left: 18px;
            margin-top: 4px;
        }

        .lista-doc li {
            font-size: 13px;
            color: var(--muted);
            line-height: 1.6;
            margin-bottom: 4px;
        }

        .lista-doc li:last-child {
            margin-bottom: 0;
        }

        .lista-doc li strong {
            color: var(--text);
        }

        /* 7. PASOS DE DESPLIEGUE (CALENDARIO)
           ======================================================================== */
        .pasos-despliegue {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            list-style: none;
            padding-left: 0;
        }

        .pasos-despliegue li {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            background: rgba(255, 255, 255, 0.02);
            padding: 10px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.04);
        }

        .pasos-despliegue li svg {
            flex-shrink: 0;
            margin-top: 2px;
        }

        /* 8. ALERTAS de SISTEMA (FEEDBACK)
           ======================================================================== */
        .alert {
            padding: 12px 14px;
            border-radius: 12px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: rgba(76, 175, 80, 0.15);
            border: 1px solid rgba(76, 175, 80, 0.3);
            color: var(--success);
        }

        .alert-error {
            background: rgba(255, 107, 107, 0.15);
            border: 1px solid rgba(255, 107, 107, 0.3);
            color: #ff8b8b;
        }

        /* 9. MEDIA QUERIES (RESPONSIVE)
           ======================================================================== */
        @media (max-width: 950px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .pasos-despliegue {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="cabecera">
            <div class="kicker">Panel de Control Interno</div>
            <div class="titulo">Gestión Electoral e Infraestructura</div>
        </div>

        <div class="dashboard-grid">

            <form action="{{ route('surveys.store') }}" method="POST" class="card">
                @csrf
                <div class="seccion-titulo">Crear Nueva Votación</div>

                @if (session('success'))
                    <div class="alert alert-success">
                        <!-- // ! CHATGPT -->
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-11.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        <!-- // ! END CHATGPT -->

                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-error">
                        <!-- // ! CHATGPT -->
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <!-- // ! END CHATGPT -->
                        {{ session('error') }}
                    </div>
                @endif

                <div class="campo">
                    <label for="title">Tema / Pregunta de la Votación</label>
                    <input type="text" id="title" name="title"
                        placeholder="Ej. ¿Quién debe ser el delegado de clase?" required>
                </div>

                <div class="campo">
                    <label>Opciones Disponibles</label>

                    <div id="contenedor-opciones">
                        <div class="bloque-opcion">
                            <input type="text" name="options[]" placeholder="Opción" required>
                        </div>
                        <div class="bloque-opcion">
                            <input type="text" name="options[]" placeholder="Opción" required>
                        </div>
                    </div>

                    <button type="button" class="btn-accion" id="btn-add-opcion" style="margin-top: 10px;">
                        <!-- // ! CHATGPT -->
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <!-- // ! END CHATGPT -->
                        Añadir Opción
                    </button>
                </div>

                <button type="submit" class="btn-primario">
                    Publicar e Iniciar Votación
                </button>
            </form>

            <div class="card">
                <div class="seccion-titulo">Memoria de Infraestructura (Capítulo 5)</div>

                <div class="subbloque-documentacion">
                    <div class="subseccion-titulo">5.2 Inventario de Sistemas</div>
                    <ul class="lista-doc">
                        <li><strong>Servidor Central:</strong> Nodo local de infraestructura (Quad-Core, 8 GB RAM,
                            almacenamiento SSD).</li>
                        <li><strong>Clientes:</strong> Dispositivos de consulta del aula, tablets o smartphones vía
                            Wi-Fi corporativa.</li>
                        <li><strong>Software Base:</strong> Servidor Apache2 (XAMPP/Docker), MariaDB y entorno PHP 8.2.
                        </li>
                    </ul>
                </div>

                <div class="subbloque-documentacion">
                    <div class="subseccion-titulo">5.3 Arquitectura en 3 Capas</div>
                    <p class="texto-doc">Estructura desacoplada para garantizar integridad:
                        <strong>Presentación</strong> via interfaces Blade con estilos Glassmorphism;
                        <strong>Negocio</strong> mediante el Core del framework Laravel; y <strong>Datos</strong>
                        persistidos bajo base de datos MariaDB.
                    </p>
                </div>

                <div class="subbloque-documentacion">
                    <div class="subseccion-titulo">5.4 Transición Digital & Soporte</div>
                    <p class="texto-doc">Sustitución de papeletas tradicionales mediante volcado estructurado y anónimo
                        de ficheros <strong>.csv</strong>. En caso de caída de tensión de red, el sistema atómico
                        recupera estados por sesión, manteniendo un censo físico impreso como Rollback.</p>
                </div>

                <div class="subbloque-documentacion">
                    <div class="subseccion-titulo">5.5 Calendario de Despliegue</div>
                    <ul class="pasos-despliegue">
                        <li>
                            <!-- // ! CHATGPT -->
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                style="color: var(--muted);">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2">
                                </rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            <!-- // ! END CHATGPT -->

                            <span><strong>Días 1-3:</strong> Aislamiento intranet.</span>
                        </li>
                        <li>
                            <!-- // ! CHATGPT -->
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" style="color: var(--muted);">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2">
                                </rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            <!-- // ! END CHATGPT -->

                            <span><strong>Días 4-5:</strong> Importación censo.</span>
                        </li>
                        <li>
                            <!-- // ! CHATGPT -->
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" style="color: var(--muted);">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2">
                                </rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            <!-- // ! END CHATGPT -->
                            <span><strong>Días 6-8:</strong> Formación directiva.</span>
                        </li>
                        <li>
                            <!-- // ! CHATGPT -->
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" style="color: var(--muted);">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2">
                                </rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            <!-- // ! END CHATGPT -->

                            <span><strong>Días 9-10:</strong> Apertura del portal.</span>
                        </li>
                    </ul>
                </div>
            </div>

        </div>

        <a href="{{ route('home') }}" class="btn-volver">
            <!-- // ! CHATGPT -->
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Volver al inicio de la web
        </a>

    </div>

    <script>
        // ! 1. SELECCIÓN DE ELEMENTOS DEL DOM
        // ========================================================================
        const contenedor = document.getElementById("contenedor-opciones");
        const btnAdd = document.getElementById("btn-add-opcion");

        // ! 2. MANEJADORES DE EVENTOS
        // ========================================================================
        btnAdd.addEventListener("click", function() {
            const nuevoBloque = document.createElement("div");
            nuevoBloque.className = "bloque-opcion";

            nuevoBloque.innerHTML = `
                <input type="text" name="options[]" placeholder="Opción" required>
                <button type="button" class="btn-eliminar" title="Eliminar opción" onclick="this.parentElement.remove();">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            `;

            contenedor.appendChild(nuevoBloque);
        });
    </script>

</body>

</html>
