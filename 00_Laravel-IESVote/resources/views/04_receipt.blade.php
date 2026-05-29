<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resguardo de Votación Digital · IESJC</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap');

        :root {
            --bg: #0b0c10;
            --card: rgba(255, 255, 255, .06);
            --stroke: rgba(255, 255, 255, .12);
            --text: rgba(255, 255, 255, .92);
            --muted: rgba(255, 255, 255, .55);
            --success: #2ecc71;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Inter, sans-serif;
            background: radial-gradient(circle at 50% 30%, rgba(46, 204, 113, .1), transparent 50%), var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .receipt-container {
            background: var(--card);
            border: 1px solid var(--stroke);
            border-radius: 16px;
            padding: 40px;
            max-width: 550px;
            width: 100%;
            backdrop-filter: blur(20px);
            text-align: center;
        }

        .icon {
            width: 56px;
            height: 56px;
            background: rgba(46, 204, 113, 0.15);
            border: 1px solid var(--success);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 20px auto;
            color: var(--success);
            font-size: 24px;
        }

        h1 {
            font-size: 22px;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .desc {
            color: var(--muted);
            font-size: 14px;
            margin-bottom: 24px;
            line-height: 1.5;
        }

        .data-box {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid var(--stroke);
            border-radius: 10px;
            padding: 20px;
            text-align: left;
            margin-bottom: 24px;
        }

        .label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--muted);
            margin-bottom: 4px;
        }

        .value {
            font-size: 15px;
            margin-bottom: 16px;
            color: var(--text);
        }

        .hash-code {
            font-family: monospace;
            background: rgba(255, 255, 255, 0.04);
            padding: 12px;
            border-radius: 6px;
            font-size: 12px;
            word-break: break-all;
            border: 1px dashed rgba(255, 255, 255, 0.2);
            color: #ffcb6b;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 14px;
            border: 0;
            border-radius: 10px;
            background: rgba(255, 255, 255, .95);
            color: #0b0c10;
            font-weight: 500;
            font-size: 13px;
            text-decoration: none;
            cursor: pointer;
            transition: opacity .2s;
        }

        .btn:hover {
            opacity: .9;
        }

        .link-back {
            display: inline-block;
            color: var(--muted);
            text-decoration: none;
            font-size: 13px;
            margin-top: 16px;
        }

        .link-back:hover {
            color: var(--text);
            text-decoration: underline;
        }

        @media print {
            body {
                background: white;
                color: black;
            }

            .receipt-container {
                border: none;
                box-shadow: none;
                backdrop-filter: none;
                background: transparent;
            }

            .btn,
            .link-back {
                display: none;
            }

            .hash-code {
                color: black;
                border-color: black;
            }
        }
    </style>
</head>

<body>

    <div class="receipt-container">
        <div class="icon">✓</div>
        <h1>Voto emitido correctamente</h1>
        <p class="desc">El sistema digital de votaciones ha procesado y blindado tu participación de manera totalmente
            segura.</p>

        <div class="data-box">
            <div class="label">Proceso Electoral</div>
            <div class="value">{{ session('titulo_encuesta') }}</div>

            <div class="label">Identificación única del voto (Guarda este código)</div>
            <div class="hash-code">{{ session('codigo_resguardo') }}</div>
        </div>

        <button class="btn" onclick="window.print()">Imprimir / Guardar en PDF</button>
        <a href="{{ route('surveys') }}" class="link-back">Volver al panel de consultas</a>
    </div>

</body>

</html>
