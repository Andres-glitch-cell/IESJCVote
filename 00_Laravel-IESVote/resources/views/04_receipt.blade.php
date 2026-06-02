<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resguardo de Votación · IESJC</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap');

        :root {
            --bg: #0b0c10;
            --card: rgba(255, 255, 255, .06);
            --stroke: rgba(255, 255, 255, .12);
            --text: rgba(255, 255, 255, .92);
            --muted: rgba(255, 255, 255, .55);
            --success: #2ecc71;
            --warning: #ff4757;
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
            max-width: 560px;
            width: 100%;
            backdrop-filter: blur(20px);
            text-align: center;
        }

        /* Estilos ocultos por defecto que solo aparecen al imprimir */
        .print-header,
        .print-footer {
            display: none;
        }

        .icon {
            width: 64px;
            height: 64px;
            background: rgba(46, 204, 113, 0.15);
            border: 2px solid var(--success);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 20px auto;
        }

        h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .important-alert {
            background: rgba(255, 71, 87, 0.15);
            border: 1px solid rgba(255, 71, 87, 0.4);
            color: #ff6b7a;
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: 600;
            margin: 20px 0;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: center;
        }

        .desc {
            color: var(--muted);
            font-size: 14.5px;
            margin-bottom: 24px;
            line-height: 1.6;
        }

        .data-box {
            background: rgba(0, 0, 0, 0.25);
            border: 1px solid var(--stroke);
            border-radius: 12px;
            padding: 24px;
            text-align: left;
            margin-bottom: 28px;
        }

        .label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--muted);
            margin-bottom: 6px;
        }

        .value {
            font-size: 15px;
            color: var(--text);
            margin-bottom: 20px;
        }

        .hash-code {
            font-family: monospace;
            background: rgba(255, 71, 87, 0.08);
            border: 2px dashed var(--warning);
            padding: 16px;
            border-radius: 8px;
            font-size: 13.5px;
            word-break: break-all;
            color: #ffcb6b;
            text-align: center;
            font-weight: 500;
        }

        .warning-text {
            color: var(--warning);
            font-size: 13px;
            margin-top: 8px;
            font-weight: 500;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 15px;
            border: 0;
            border-radius: 10px;
            background: rgba(255, 255, 255, .95);
            color: #0b0c10;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            cursor: pointer;
            transition: all .2s;
            margin-bottom: 12px;
        }

        .link-back {
            display: inline-block;
            color: var(--muted);
            text-decoration: none;
            font-size: 13.5px;
        }

        /* SVG Styles */
        .success-svg {
            width: 32px;
            height: 32px;
            fill: var(--success);
        }

        .warning-svg {
            width: 24px;
            height: 24px;
            fill: #ff6b7a;
        }

        .print-svg {
            width: 20px;
            height: 20px;
            fill: #0b0c10;
            margin-right: 8px;
        }

        @media print {
            body {
                background: white !important;
                padding-top: 80px;
                padding-bottom: 50px;
            }

            /* Cabecera y Pie fijos */
            .print-header,
            .print-footer {
                display: block !important;
                position: fixed;
                width: 100%;
                left: 0;
                color: black !important;
            }

            .print-header {
                top: 10px;
                text-align: center;
                font-weight: bold;
                font-size: 16px;
                border-bottom: 2px solid black;
                padding-bottom: 10px;
            }

            .print-footer {
                bottom: 10px;
                font-size: 10px;
                display: flex;
                justify-content: space-between;
                padding: 0 40px;
            }

            /* Escala de grises y forzado de negro */
            body,
            .receipt-container,
            h1,
            p,
            .desc,
            .label,
            .value,
            .hash-code,
            .important-alert {
                color: black !important;
                -webkit-text-fill-color: black !important;
            }

            body {
                filter: grayscale(100%) !important;
                -webkit-filter: grayscale(100%) !important;
            }

            .receipt-container {
                border: 2px solid black !important;
                background: white !important;
                box-shadow: none !important;
            }

            .hash-code,
            .important-alert {
                border: 2px solid black !important;
                background: #f0f0f0 !important;
            }

            .success-svg,
            .warning-svg {
                fill: black !important;
            }

            .btn,
            .link-back {
                display: none !important;
            }
        }
    </style>
</head>

<body>

    <div class="print-header">IESJC · Sistema de Votación Segura</div>

    <div class="receipt-container">
        <div class="icon">
            <svg class="success-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M20.285 6.375L9.428 17.232 3.714 11.518 5.143 10.09 9.428 14.375 18.857 4.946 20.285 6.375Z" />
            </svg>
        </div>

        <h1>¡Voto registrado correctamente!</h1>

        <div class="important-alert">
            <svg class="warning-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2L2 22h20L12 2zm1 17h-2v-2h2v2zm0-4h-2v-5h2v5z" />
            </svg>
            IMPORTANTE — GUARDE ESTE CÓDIGO
        </div>

        <p class="desc">
            Su voto ha sido procesado con seguridad. Este código es tu <strong>única prueba</strong> de participación.
        </p>

        <div class="data-box">
            <div class="label">Proceso Electoral</div>
            <div class="value">{{ session('titulo_encuesta') }}</div>

            <div class="label">CÓDIGO DE RESGUARDO ÚNICO</div>
            <div class="hash-code">{{ session('codigo_resguardo') }}</div>
            <p class="warning-text">¡ Se le recomienda GUARDAR este código en un lugar SEGURO !</p>
        </div>

        <button class="btn" onclick="window.print()">
            <svg class="print-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path
                    d="M19 8H5c-1.1 0-2 .9-2 2v6c0 1.1.9 2 2 2h2v3c0 .55.45 1 1 1h8c.55 0 1-.45 1-1v-3h2c1.1 0 2-.9 2-2v-6c0-1.1-.9-2-2-2zm-2 10H7v-2h10v2zm0-4H7v-4h10v4z" />
            </svg>
            Imprimir o Guardar como PDF
        </button>

        <a href="{{ route('surveys') }}" class="link-back">← Volver al panel de encuestas</a>
    </div>

    <div class="print-footer">
        <div>Propietario derechos de Autor: Andrés Fernández Salaud</div>
        <div>{{ now()->format('d/m/Y H:i') }}</div>
    </div>

</body>

</html>
