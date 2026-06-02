<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración · IESJCVote</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap');

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
            font-family: Inter, sans-serif;
            background: radial-gradient(circle at 20% 20%, rgba(255, 255, 255, .08), transparent 35%),
                radial-gradient(circle at 80% 0%, rgba(255, 255, 255, .05), transparent 40%), var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            padding: 50px 20px;
        }

        .cabecera {
            text-align: center;
            margin-bottom: 50px;
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

        input {
            width: 100%;
            padding: 14px 16px;
            border-radius: 12px;
            border: 1px solid var(--stroke);
            background: rgba(255, 255, 255, .03);
            color: var(--text);
            font-size: 15px;
        }

        .bloque-opcion {
            display: flex;
            gap: 12px;
            margin-bottom: 12px;
            align-items: center;
        }

        button {
            padding: 13px 22px;
            border: 0;
            border-radius: 12px;
            background: rgba(255, 255, 255, .92);
            color: #0b0c10;
            font-weight: 500;
            cursor: pointer;
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

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: #111;
            padding: 40px;
            border-radius: 18px;
            width: 90%;
            max-width: 460px;
            border: 1px solid #333;
            text-align: center;
        }

        .modal-title {
            font-size: 21px;
            color: #ff6b6b;
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
            background: #2ecc71;
            color: white;
        }

        .modal-no {
            background: #333;
            color: #ccc;
            border: 1px solid #555;
        }

        @media (max-width: 950px) {
            .dashboard-grid {
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
            <!-- Formulario Crear -->
            <!-- Formulario Crear (Limpio) -->
            <form action="{{ route('surveys.store') }}" method="POST" class="card">
                @csrf
                <div class="seccion-titulo">Crear Nueva Votación</div>

                {{-- EL MENSAJE DE ÉXITO FUE ELIMINADO DE AQUÍ --}}

                @if (session('error'))
                    <div class="alert alert-error">{{ session('error') }}</div>
                @endif

                <div class="campo">
                    <label for="title">Tema / Pregunta de la Votación</label>
                    <input type="text" id="title" name="title" placeholder="Ej. ¿Quién debe ser el delegado?"
                        required>
                </div>

                <div class="campo">
                    <label>Opciones Disponibles</label>
                    <div id="contenedor-opciones">
                        <div class="bloque-opcion">
                            <input type="text" name="options[]" placeholder="Opción 1" required>
                        </div>
                        <div class="bloque-opcion">
                            <input type="text" name="options[]" placeholder="Opción 2" required>
                        </div>
                    </div>
                    <button type="button" class="btn-accion" id="btn-add-opcion" style="margin-top:12px;">
                        Añadir Opción
                    </button>
                </div>

                <button type="submit" class="btn-primario">Publicar e Iniciar Votación</button>
            </form>

            <!-- Información del Sistema -->
            <div class="card">
                <div class="seccion-titulo">Información del Sistema</div>
                <p style="color: var(--muted); font-size: 14.5px; line-height: 1.6;">
                    Gestiona el flujo de votaciones desde aquí. Recuerda verificar la integridad de los datos antes de
                    publicar.
                </p>
            </div>

            <!-- Tabla de Encuestas -->
            <div class="card">
                <div class="seccion-titulo">Encuestas Activas</div>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; color: var(--text); font-size: 14px;">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--stroke);">
                                <th style="text-align: left; padding: 16px 12px;">Título</th>
                                <th style="text-align: right; padding: 16px 12px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($surveys as $survey)
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                    <td style="padding: 16px 12px;">{{ $survey->title }}</td>
                                    <td style="text-align: right; padding: 16px 12px;">
                                        <button type="button"
                                            onclick="showDeleteModal('{{ $survey->id }}', '{{ addslashes($survey->title) }}')"
                                            class="btn-eliminar">Eliminar</button>

                                        <form id="delete-form-{{ $survey->id }}"
                                            action="{{ route('surveys.destroy', $survey->id) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <a href="{{ route('home') }}" class="btn-volver">← Volver al inicio</a>
    </div>

    <!-- MODAL -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-title">Confirmar Eliminación</div>
            <div class="modal-text" id="modalSurveyName"></div>
            <div class="modal-buttons">
                <button class="modal-btn modal-no" onclick="hideDeleteModal()">Cancelar</button>
                <button class="modal-btn modal-yes" id="confirmDeleteBtn">Eliminar Encuesta</button>
            </div>
        </div>
    </div>

    <script>
        // === AÑADIR OPCIÓN (CORREGIDO) ===
        document.getElementById("btn-add-opcion").addEventListener("click", function() {
            const contenedor = document.getElementById("contenedor-opciones");

            const nuevoBloque = document.createElement("div");
            nuevoBloque.className = "bloque-opcion";
            nuevoBloque.innerHTML = `
                <input type="text" name="options[]" placeholder="Nueva opción" required>
                <button type="button" class="btn-eliminar" onclick="this.parentElement.remove()">×</button>
            `;
            contenedor.appendChild(nuevoBloque);
        });

        // === MODAL ELIMINAR ===
        let surveyIdToDelete = null;

        function showDeleteModal(id, title) {
            surveyIdToDelete = id;
            document.getElementById('modalSurveyName').innerHTML = `¿Eliminar la encuesta <strong>"${title}"</strong>?`;
            document.getElementById('deleteModal').style.display = 'flex';
        }

        function hideDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
            if (surveyIdToDelete) {
                // En lugar de crear un formulario nuevo, buscamos el que ya existe en el HTML
                const form = document.getElementById(`delete-form-${surveyIdToDelete}`);
                if (form) {
                    form.submit();
                }
            }
        });
    </script>
</body>

</html>
