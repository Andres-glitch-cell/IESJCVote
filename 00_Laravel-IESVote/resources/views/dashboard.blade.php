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
            <div class="kicker">IES JC · Acceso</div>
            <div class="titulo">Votación digital</div>
            <div class="subtitulo">Acceso seguro verificado</div>
        </div>

        <div class="card" id="card">

            <form id="form" method="POST" action="{{ route('dashboard') }}">
                @csrf
                <div class="campo">
                    <label>Usuario</label>
                    <input id="nombre" type="text" placeholder="Ej: Juan">
                    <div class="barra-container">
                        <div class="barra" id="barraNombre"></div>
                    </div>
                    <div class="contador" id="contadorNombre"></div>
                    <!-- ! Insertar esto a como un innerHTML -->
                    <div class="error" id="errNombre">Usuario</div>
                </div>

                <div class="campo">
                    <label>DNI</label>
                    <input id="dni" type="text" maxlength="9" placeholder="12345678A">

                    <div class="barra-container">
                        <div class="barra" id="barraDNI"></div>
                    </div>
                    <div class="contador" id="contadorDNI"></div>

                    <!-- ! Insertar esto a como un innerHTML -->
                    <div class="error" id="errDni">DNI inválido</div>
                </div>

                <!-- /* ! INPUT ADMIN */ -->
                <!-- /* # Contraseña por el administrador -> alert('IESJCVote2026'); */ MAX 23 CARACTERES && MODO CONTRASEÑA EN JS -->
                <div class="campo">
                    <label>Contraseña Administrador</label>
                    <input id="contraseñaAdministrador" placeholder="Contraseña dada al administrador.">

                    <div class="barra-container">
                        <div class="barra" id="barraContraseñaAdministrador"></div>
                    </div>
                    <div class="contador" id="contadorContraseñaAdministrador"></div>
                    <div id="mensaje"></div>
                    <!-- ! Insertar esto a como un innerHTML -->
                    <div class="error" id="errContraseñaAdministrador">DNI inválido</div>
                </div>

                <div class="divider"></div>

                <button type="submit">Acceder</button>

            </form>

            <div class="footer">Sesión protegida</div>

        </div>

    </div>

    <script>
        // ! BARRA NOMBRE
        const inputNombre = document.getElementById("nombre");
        inputNombre.focus();
        const barra = document.getElementById("barraNombre");
        const contador = document.getElementById("contadorNombre");

        inputNombre.addEventListener("input", () => {
            let longitud = inputNombre.value.length;
            // Limitar a 10 caracteres
            if (longitud > 10) {
                inputNombre.value = inputNombre.value.slice(0, 10);
                longitud = 10;
            }
            // Porcentaje barra
            const porcentaje = (longitud / 10) * 100;
            barra.style.width = porcentaje + "%";

            if (longitud === 0) {
                contador.textContent = "";
            } else if (longitud <= 2) {
                contador.textContent = "Usuario corto";
                barra.style.background = "#ff6b6b";
                contador.style.color = "#ff6b6b";
            } else if (longitud <= 4) {
                contador.textContent = "Usuario aceptable";
                barra.style.background = "#f7b731";
                contador.style.color = "#f7b731";
            } else if (longitud <= 6) {
                contador.textContent = "Usuario bueno";
                barra.style.background = "#4dabf7";
                contador.style.color = "#4dabf7";
            } else {
                contador.textContent = "Usuario ideal";
                barra.style.background = "#4caf50";
                contador.style.color = "#4caf50";
            }
        });
        // ! BARRA NOMBRE

        // ! BARRA DNI

        const inputDNI = document.getElementById("dni");
        const barraDNI = document.getElementById("barraDNI");
        const contadorDNI = document.getElementById("contadorDNI");

        inputDNI.addEventListener("input", () => {
            let longitudDNI = inputDNI.value.length;
            // Limitar a 10 caracteres
            if (longitudDNI > 9) {
                inputDNI.value = inputDNI.value.slice(0, 9);
                longitudDNI = 9;
            }
            // Porcentaje barraDNI
            const porcentaje = (longitudDNI / 10) * 100;
            barraDNI.style.width = porcentaje + "%";
            const dniValido = /^\d{8}[A-Z]$/.test(inputDNI.value);
            if (longitudDNI === 0) {
                contadorDNI.textContent = "";
            } else if (!dniValido) {
                contadorDNI.textContent = "DNI inválido";
                barraDNI.style.background = "#ff6b6b";
                contadorDNI.style.color = "#ff6b6b";
            } else {
                contadorDNI.textContent = "DNI correcto";
                barraDNI.style.background = "#4caf50";
                contadorDNI.style.color = "#4caf50";
            }
        });

        // ! BARRA DNI

        const inputContraseñaAdmin = document.getElementById("contraseñaAdministrador");
        const barraContraseña = document.getElementById("barraContraseñaAdministrador");
        const contadorContraseña = document.getElementById("contadorContraseñaAdministrador");

        const MAX = 23;

        inputContraseñaAdmin.addEventListener("input", () => {
            let len = inputContraseñaAdmin.value.length;

            // limitar
            if (len > MAX) {
                inputContraseñaAdmin.value = inputContraseñaAdmin.value.slice(0, MAX);
                len = MAX;
            }

            // barra
            const porcentaje = (len / MAX) * 100;
            barraContraseña.style.width = porcentaje + "%";

            // validación
            const esValida = inputContraseñaAdmin.value === "alert('IESJCVote2026');";

            if (len === 0) {
                contadorContraseña.textContent = "";
                barraContraseña.style.background = "rgba(255,255,255,0.9)";
            } else if (esValida) {
                contadorContraseña.textContent = "Contraseña correcta";
                barraContraseña.style.background = "#4caf50";
                contadorContraseña.style.color = "#4caf50";
            } else {
                contadorContraseña.textContent = "Contraseña incorrecta";
                barraContraseña.style.background = "#ff6b6b";
                contadorContraseña.style.color = "#ff6b6b";
            }
        });

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

            const elementoNombreValue = nombre.value
            const elementoDNIValue = dni.value
            if (elementoNombreValue === "") {
                document.getElementById("errNombre").textContent = "Campo nombre vacío"
            }
            if (elementoDNIValue === "") {
                document.getElementById("errDni").textContent = "Campo DNI vacío"
            }
            if (okNombre && okDni) {
                location.href = "";
            } else shake();
        });
    </script>

</body>
