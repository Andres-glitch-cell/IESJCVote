<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>En Mantenimiento - IESJCVote</title>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap');

        :root {
            --bg: #0b0c10;
            --card: rgba(255, 255, 255, .06);
            --stroke: rgba(255, 255, 255, .12);
            --text: rgba(255, 255, 255, .92);
            --muted: rgba(255, 255, 255, .55);
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

        .contenedor {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 40px 20px;
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
            font-size: 42px;
            font-weight: 500;
            margin-top: 14px;
        }

        .subtitulo {
            margin-top: 12px;
            font-size: 16px;
            color: var(--muted);
            max-width: 420px;
        }

        .card {
            width: 100%;
            max-width: 460px;
            padding: 45px 35px;
            border-radius: 20px;
            background: var(--card);
            border: 1px solid var(--stroke);
            backdrop-filter: blur(18px);
            box-shadow: 0 40px 100px rgba(0, 0, 0, .6);
            text-align: center;
        }

        .icon {
            margin-bottom: 24px;
        }

        .mensaje {
            font-size: 17px;
            line-height: 1.65;
            color: var(--muted);
            margin-bottom: 35px;
        }

        .btn-volver {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #64b5f6;
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
            transition: all 0.3s;
        }

        .btn-volver:hover {
            color: white;
            transform: translateX(6px);
        }
    </style>
</head>

<body>

    <div class="contenedor">

        <div class="cabecera">
            <div class="kicker">Panel de Administración</div>
            <div class="titulo">Gestión Electoral</div>
            <div class="subtitulo">IESJCVote</div>
        </div>

        <div class="card">
            <div class="icon">
                <svg width="92" height="92" viewBox="0 0 24 24" fill="none" stroke="#64b5f6"
                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path
                        d="M19.43 12.98c.04-.32.07-.64.07-.98s-.03-.66-.07-.98l2.11-1.65c.19-.15.24-.42.12-.64l-2-3.46c-.12-.22-.39-.3-.61-.22l-2.49 1c-.52-.4-1.08-.73-1.69-.93l-.38-2.65C14.46 2.18 14.25 2 14 2h-4c-.25 0-.46.18-.49.42l-.38 2.65c-.61.2-1.17.53-1.69.93l-2.49-1c-.22-.08-.49 0-.61.22l-2 3.46c-.13.22-.07.49.12.64l2.11 1.65c-.04.32-.07.65-.07.98s.03.66.07.98l-2.11 1.65c-.19.15-.24.42-.12.64l2 3.46c.12.22.39.3.61.22l2.49-1c.52.4 1.08.73 1.69.93l.38 2.65c.03.24.24.42.49.42h4c.25 0 .46-.18.49-.42l.38-2.65c.61-.2 1.17-.53 1.69-.93l2.49 1c.22.08.49 0 .61-.22l2-3.46c.12-.22.07-.49-.12-.64l-2.11-1.65z">
                    </path>
                </svg>
            </div>

            <h2 style="font-size: 26px; margin-bottom: 16px;">Estamos en mantenimiento</h2>

            <p class="mensaje">
                La sección de <strong>Resultados</strong> se encuentra temporalmente en mantenimiento.<br><br>
                Estamos trabajando para que vuelva a estar disponible lo antes posible.
            </p>

            <a href="{{ route('admin.dashboard') }}" class="btn-volver">
                ← Volver al Panel de Administración
            </a>
        </div>

    </div>

</body>

</html>
