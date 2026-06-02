<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario · IESJC</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap');

        :root {
            --bg: #0b0c10;
            --card: rgba(255, 255, 255, .06);
            --stroke: rgba(255, 255, 255, .12);
            --text: rgba(255, 255, 255, .92);
            --muted: rgba(255, 255, 255, .55);
            --success: #2ecc71;
            --accent: #3498db;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .wrapper {
            width: 100%;
            max-width: 450px;
        }

        .card-profile {
            background: var(--card);
            border: 1px solid var(--stroke);
            border-radius: 20px;
            padding: 40px;
            backdrop-filter: blur(18px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, .4);
        }

        .titulo {
            font-size: 24px;
            font-weight: 500;
            margin-bottom: 25px;
            text-align: center;
        }

        .section-label {
            font-size: 11px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin: 25px 0 15px;
            display: block;
            text-align: left;
        }

        .info-item {
            background: rgba(255, 255, 255, .03);
            border: 1px solid var(--stroke);
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 10px;
        }

        .valor {
            font-size: 15px;
            font-weight: 500;
        }

        .label {
            font-size: 10px;
            color: var(--muted);
            display: block;
            margin-bottom: 3px;
        }

        /* Estilo para items clicables */
        .action-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, .05);
            border: 1px solid var(--stroke);
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 8px;
            color: var(--text);
            font-size: 14px;
            text-decoration: none;
            transition: .2s;
        }

        .action-item:hover {
            background: rgba(255, 255, 255, .1);
            border-color: var(--success);
        }

        .maintenance-item {
            background: rgba(255, 255, 255, .02);
            border: 1px dashed var(--stroke);
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 8px;
            color: var(--muted);
            font-size: 13px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .badge-dev {
            font-size: 9px;
            background: rgba(255, 255, 255, 0.1);
            padding: 2px 6px;
            border-radius: 4px;
        }

        .badge-success {
            color: var(--success);
            font-weight: 600;
        }

        .btn-volver {
            display: block;
            margin-top: 30px;
            text-align: center;
            color: var(--muted);
            text-decoration: none;
            font-size: 14px;
            transition: .2s;
        }

        .btn-volver:hover {
            color: var(--text);
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="wrapper">
        <div class="card-profile">
            <h1 class="titulo">Mi Cuenta</h1>

            <span class="section-label">Información Personal</span>
            <div class="info-item">
                <span class="label">Nombre completo</span>
                <div class="valor">{{ $user->name }}</div>
            </div>
            <div class="info-item">
                <span class="label">DNI</span>
                <div class="valor">{{ $user->dni }}</div>
            </div>

            <span class="section-label">Opciones de configuración</span>

            <a href="{{ route('history') }}" class="action-item">
                <span>Historial de votos</span>
                <span class="badge-success">Ver registro →</span>
            </a>

            <div class="maintenance-item">
                <span>Cambiar contraseña</span>
                <span class="badge-dev">En mantenimiento</span>
            </div>
            <div class="maintenance-item">
                <span>Notificaciones</span>
                <span class="badge-dev">Próximamente</span>
            </div>

            <a href="{{ route('surveys') }}" class="btn-volver">← Volver al Panel</a>
        </div>
    </div>

</body>

</html>
