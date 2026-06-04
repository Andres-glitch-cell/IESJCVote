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

        .card:hover {
            transform: translateY(-3px)
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
            <div class="kicker">IES JC · Registro</div>
            <div class="titulo">Votación digital</div>
            <div class="subtitulo">Acceso seguro verificado</div>
        </div>

        <div class="card" id="card">

            @if (session('error'))
                <div class="error-server" style="margin-bottom: 14px; text-align: center;">
                    {{ session('error') }}
                </div>
            @endif

            <form id="form" method="POST" action="{{ route('register.post') }}">
                @csrf

                <div class="campo">
                    <label>Usuario</label>
                    <input id="username" name="username" type="text" placeholder="Ej: Juan"
                        value="{{ old('username') }}">
                    <div class="error" id="errNombre"></div>

                    @error('username')
                        <div class="error-server">{{ $message }}</div>
                    @enderror

                    <div class="barra-container">
                        <div class="barra" id="barraNombre"></div>
                    </div>
                    <div class="contador" id="contadorNombre"></div>
                </div>

                <div class="campo">
                    <label>DNI</label>
                    <input id="dni" name="dni" type="text" maxlength="9" placeholder="12345678A"
                        value="{{ old('dni') }}">
                    <div class="error" id="errDni"></div>

                    @error('dni')
                        <div class="error-server">{{ $message }}</div>
                    @enderror

                    <div class="barra-container">
                        <div class="barra" id="barraDNI"></div>
                    </div>
                    <div class="contador" id="contadorDNI"></div>
                </div>

                <button type="submit">Regístrese</button>
            </form>

            <div class="footer">
                Sesión protegida - <a href="{{ route('home') }}">Ya tiene cuenta, Inicie Sesión!</a>
            </div>

        </div>
    </div>

    <script>
        // ─── BARRA DE PROGRESO DEL NOMBRE ────────────────────────────────────────

        const inputNombre = document.getElementById("username");
        inputNombre.focus();

        const barraNombre = document.getElementById("barraNombre");
        const contadorNombre = document.getElementById("contadorNombre");

        inputNombre.addEventListener("input", () => {
            const errNombre = document.getElementById("errNombre");

            if (!/^[a-zA-ZÁÉÍÓÚáéíóúÑñ\s]*$/.test(inputNombre.value)) {
                errNombre.textContent = "Solo letras y espacios permitidos";
                errNombre.style.display = "block";
            } else if (inputNombre.value.trim().length > 0 && inputNombre.value.trim().length < 3) {
                errNombre.textContent = "Mínimo 3 caracteres";
                errNombre.style.display = "block";
            } else {
                errNombre.style.display = "none";
            }

            let longitud = inputNombre.value.length;

            if (longitud > 255) {
                inputNombre.value = inputNombre.value.slice(0, 255);
                longitud = 255;
            }

            const porcentaje = Math.min((longitud / 15) * 100, 100);
            barraNombre.style.width = porcentaje + "%";

            if (longitud === 0) contadorNombre.textContent = "";
            else if (longitud <= 4) {
                contadorNombre.textContent = "Usuario corto";
                barraNombre.style.background = "#ff6b6b";
            } else if (longitud <= 8) {
                contadorNombre.textContent = "Usuario aceptable";
                barraNombre.style.background = "#f7b731";
            } else if (longitud <= 12) {
                contadorNombre.textContent = "Usuario bueno";
                barraNombre.style.background = "#4dabf7";
            } else {
                contadorNombre.textContent = "Usuario ideal";
                barraNombre.style.background = "#4caf50";
            }
        });

        // ─── BARRA DE PROGRESO DEL DNI ───────────────────────────────────────────

        const inputDNI = document.getElementById("dni");
        const barraDNI = document.getElementById("barraDNI");
        const contadorDNI = document.getElementById("contadorDNI");

        inputDNI.addEventListener("input", () => {
            let valor = inputDNI.value.toUpperCase();
            let resultado = "";

            for (let i = 0; i < valor.length; i++) {
                const char = valor[i];
                if (i < 8 && char >= "0" && char <= "9") resultado += char;
                else if (i === 8 && char >= "A" && char <= "Z") resultado += char;
            }

            resultado = resultado.slice(0, 9);
            inputDNI.value = resultado;

            const longitud = resultado.length;
            const porcentaje = (longitud / 9) * 100;
            barraDNI.style.width = porcentaje + "%";

            const dniValido = /^\d{8}[A-Z]$/.test(resultado);

            if (longitud === 0) contadorDNI.textContent = "";
            else if (longitud < 9) {
                contadorDNI.textContent = "DNI incompleto";
                barraDNI.style.background = "#f7b731";
            } else if (!dniValido) {
                contadorDNI.textContent = "DNI inválido";
                barraDNI.style.background = "#ff6b6b";
            } else {
                contadorDNI.textContent = "DNI correcto";
                barraDNI.style.background = "#4caf50";
            }
        });

        // ─── VALIDACIÓN AL ENVIAR EL FORMULARIO ──────────────────────────────────

        const form = document.getElementById("form");
        const card = document.getElementById("card");

        const shake = () => {
            card.style.animation = "shake .3s";
            setTimeout(() => card.style.animation = "", 300);
        };

        form.addEventListener("submit", e => {
            e.preventDefault();

            const nombre = document.getElementById("username").value.trim();
            const dni = document.getElementById("dni").value.trim();

            const errNombre = document.getElementById("errNombre");
            const errDni = document.getElementById("errDni");

            errNombre.style.display = "none";
            errDni.style.display = "none";

            if (nombre === "") {
                errNombre.textContent = "Campo nombre vacío";
                errNombre.style.display = "block";
                shake();
                return;
            }

            if (nombre.length < 3) {
                errNombre.textContent = "Mínimo 3 caracteres";
                errNombre.style.display = "block";
                shake();
                return;
            }

            if (dni === "") {
                errDni.textContent = "Campo DNI vacío";
                errDni.style.display = "block";
                shake();
                return;
            }

            if (!/^\d{8}[A-Z]$/.test(dni)) {
                errDni.textContent = "Formato DNI inválido (12345678A)";
                errDni.style.display = "block";
                shake();
                return;
            }

            form.submit();
        });
    </script>

</body>

</html>
