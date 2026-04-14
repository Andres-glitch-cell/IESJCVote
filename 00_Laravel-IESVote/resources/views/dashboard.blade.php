<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso · Votación</title>

<style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap');

        :root {
            --bg: #0b0c10;
            --card: rgba(255, 255, 255, .06);
            --stroke: rgba(255, 255, 255, .12);
            --text: rgba(255, 255, 255, .92);
            --muted: rgba(255, 255, 255, .55);
        }

        /* RESET */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box
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

        /* LAYOUT */
        .contenedor {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 40px 20px;
        }

        /* HEADER */
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

        /* CARD */
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

        .card:hover {
            transform: translateY(-3px)
        }

        /* FORM */
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

        /* BUTTON */
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

        /* ERROR */
        .error {
            font-size: 12px;
            color: #ff6b6b;
            margin-top: 6px;
            display: none;
        }

        /* FOOTER */
        .footer {
text-align: center;
            margin-top: 18px;
            font-size: 11px;
            color: rgba(255, 255, 255, .35);
        }

        /* DIVIDER */
        .divider {
            height: 1px;
            margin: 18px 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .1), transparent);
}

        /* SHAKE */
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
</style>
</head>

<body>

    <div class="contenedor">

        <div class="cabecera">
            <div class="kicker">IES JC · Acceso</div>
            <div class="titulo">Votación digital</div>
            <div class="subtitulo">Acceso seguro verificado</div>
</div>

        <div class="card" id="card">

            <form id="form">

                <div class="campo">
                    <label>Nombre completo</label>
                    <input id="nombre" type="text" placeholder="Ej: Juan García">
                    <div class="error" id="errNombre">Nombre inválido</div>
                </div>

                <div class="campo">
                    <label>DNI</label>
                    <input id="dni" type="text" maxlength="9" placeholder="12345678A">
                    <div class="error" id="errDni">DNI inválido</div>
                </div>

                <div class="divider"></div>

                <button type="submit">Acceder</button>

            </form>

            <div class="footer">Sesión protegida</div>

</div>

    </div>

<script>
        const form = document.getElementById("form");
        const card = document.getElementById("card");

        const shake = () => {
            card.style.animation = "shake .3s";
            setTimeout(() => card.style.animation = "", 300);
        };

        form.addEventListener("submit", e => {
            e.preventDefault();

            const nombre = document.getElementById("nombre");
            const dni = document.getElementById("dni");

            const okNombre = /^[a-zA-ZÁÉÍÓÚáéíóúÑñ\s]{3,}$/.test(nombre.value);
            const okDni = /^\d{8}[A-Z]$/.test(dni.value);

            document.getElementById("errNombre").style.display = okNombre ? "none" : "block";
            document.getElementById("errDni").style.display = okDni ? "none" : "block";

            if (okNombre && okDni) {
                location.href = "agradecimientos.html";
            } else shake();
        });
    </script>

</body>
