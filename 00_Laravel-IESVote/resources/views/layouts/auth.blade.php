<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Votación')</title>
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
            font-family: Inter, sans-serif;
            background: radial-gradient(circle at 20% 20%, rgba(255, 255, 255, .08), transparent 35%), radial-gradient(circle at 80% 0%, rgba(255, 255, 255, .05), transparent 40%), var(--bg);
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
            margin-bottom: 36px
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
            margin-top: 10px;
            font-size: 14px;
            color: var(--muted);
        }

        .card {
            width: 100%;
            max-width: 420px;
            padding: 28px;
            border-radius: 18px;
            background: var(--card);
            border: 1px solid var(--stroke);
            backdrop-filter: blur(18px);
            box-shadow: 0 40px 100px rgba(0, 0, 0, .6);
            transition: .3s;
        }

        .campo {
            margin-bottom: 18px
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

        button {
            width: 100%;
            margin-top: 10px;
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

        .error {
            font-size: 12px;
            color: #ff6b6b;
            margin-top: 6px;
            display: none;
        }

        .error-server {
            font-size: 12px;
            color: #ff6b6b;
            margin-top: 6px;
            display: block;
        }

        .footer {
            text-align: center;
            margin-top: 18px;
            font-size: 11px;
            color: rgba(255, 255, 255, .35);
        }

        .footer a {
            color: var(--text);
            text-decoration: none;
            font-weight: 500;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0)
            }

            25% {
                transform: translateX(-4px)
            }

            50% {
                transform: translateX(4px)
            }

            75% {
                transform: translateX(-3px)
            }
        }

        .barra-container {
            width: 100%;
            height: 4px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 10px;
            overflow: hidden;
            margin-top: 15px;
        }

        .barra {
            height: 100%;
            width: 0%;
            background: rgba(255, 255, 255, 0.9);
            transition: width 0.2s ease;
        }

        .contador {
            font-size: 11px;
            color: var(--muted);
            margin-top: 4px;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="contenedor">
        <div class="cabecera">
            <div class="kicker">IES JC · @yield('kicker')</div>
            <div class="titulo">Votación digital</div>
            <div class="subtitulo">Acceso seguro verificado</div>
        </div>
        <div class="card" id="card">
            @if (session('error'))
                <div class="error-server" style="margin-bottom: 14px; text-align: center;">{{ session('error') }}</div>
            @endif
            @yield('content')
            <div class="footer">Sesión protegida</div>
        </div>
    </div>
    @yield('scripts')
</body>

</html>
