<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración · IESJCVote</title>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

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
            font-family: 'Inter', sans-serif;
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

        /* Toast */
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
            animation: slideIn 0.4s ease-out;
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

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(11, 12, 16, 0.85);
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
            border: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            transform: scale(0.95);
            transition: transform 0.3s ease;
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

        {{-- Notificación de Éxito --}}
        @if (session('success'))
            <div class="toast-success" id="notif">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            <script>
                setTimeout(() => {
                    $('#notif').fadeOut(300);
                }, 4000);
            </script>
        @endif

        {{-- Notificación de Eliminación (asegúrate de tener este bloque también) --}}
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

        <div class="cabecera">
            <div class="kicker">Panel de Control Interno</div>
            <div class="titulo">Gestión Electoral e Infraestructura</div>
        </div>

        <div class="dashboard-grid">
            <!-- Formulario de creación -->
            <form action="{{ route('surveys.store') }}" method="POST" class="card">
                @csrf
                <div class="seccion-titulo">Crear Nueva Votación</div>

                @if (session('error'))
                    <div style="color:var(--error); margin-bottom: 20px;">{{ session('error') }}</div>
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
                    <button type="button" id="btn-add-opcion" style="margin-top:12px;">Añadir Opción</button>
                </div>

                <button type="submit" class="btn-primario">Publicar Encuesta</button>
            </form>

            <!-- Información del Sistema -->
            <div class="card">
                <div class="seccion-titulo">Información del Sistema</div>
                <p style="color: var(--muted); font-size: 14.5px; line-height: 1.6;">
                    Gestiona el flujo de votaciones desde aquí. Recuerda verificar la integridad de los datos antes de
                    publicar.
                </p>
            </div>

            <!-- Encuestas Activas -->
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
                                            @csrf @method('DELETE')
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

    <!-- Modal de Eliminación -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div style="font-size: 48px; margin-bottom: 20px;">⚠️</div>
            <div class="modal-title">¿Estás seguro?</div>
            <div class="modal-text" id="modalSurveyName"></div>
            <div class="modal-buttons">
                <button class="modal-btn modal-no" onclick="hideDeleteModal()">Cancelar</button>
                <button class="modal-btn modal-yes" id="confirmDeleteBtn">Sí, eliminar</button>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            // Añadir opción
            $("#btn-add-opcion").click(function() {
                const nuevoBloque = `
                    <div class="bloque-opcion">
                        <input type="text" name="options[]" placeholder="Nueva opción" required>
                        <button type="button" class="btn-eliminar" style="padding: 10px 15px;" onclick="$(this).parent().remove()">×</button>
                    </div>`;
                $("#contenedor-opciones").append(nuevoBloque);
            });

            // Modal de eliminación
            let surveyIdToDelete = null;

            window.showDeleteModal = function(id, title) {
                surveyIdToDelete = id;
                $("#modalSurveyName").html(
                    `Se eliminará permanentemente la encuesta <strong>"${title}"</strong>. Esta acción no se puede deshacer.`
                );
                $("#deleteModal").fadeIn(300);
            };

            window.hideDeleteModal = function() {
                $("#deleteModal").fadeOut(300);
            };

            $("#confirmDeleteBtn").click(function() {
                if (surveyIdToDelete) {
                    $(`#delete-form-${surveyIdToDelete}`).submit();
                }
            });

        });
    </script>
</body>

</html>
