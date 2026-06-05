<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión · Votación</title>

    <style>
        /* ─── FUENTE ─────────────────────────────────────────────────────── */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap');

        /* ─── VARIABLES GLOBALES DE COLOR ────────────────────────────────── */
        :root {
            --bg: #0b0c10;
            --card: rgba(255, 255, 255, .06);
            --stroke: rgba(255, 255, 255, .12);
            --text: rgba(255, 255, 255, .92);
            --muted: rgba(255, 255, 255, .55);
        }

        /* ─── RESET BÁSICO ───────────────────────────────────────────────── */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* ─── FONDO Y TIPOGRAFÍA GLOBAL ──────────────────────────────────── */
        body {
            font-family: Inter, sans-serif;
            background:
                radial-gradient(circle at 20% 20%, rgba(255, 255, 255, .08), transparent 35%),
                radial-gradient(circle at 80% 0%, rgba(255, 255, 255, .05), transparent 40%),
                var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* ─── LAYOUT PRINCIPAL ───────────────────────────────────────────── */
        .contenedor {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 40px 20px;
        }

        /* ─── CABECERA (kicker + título + subtítulo) ─────────────────────── */
        .cabecera {
            text-align: center;
            margin-bottom: 36px;
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

        /* ─── CARD (contenedor del formulario) ───────────────────────────── */
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
            transform: translateY(-3px);
        }

        /* ─── CAMPOS DEL FORMULARIO ──────────────────────────────────────── */
        .campo {
            margin-bottom: 18px;
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

        /* ─── BOTÓN DE ENVÍO ─────────────────────────────────────────────── */
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

        /* ─── MENSAJES DE ERROR (validación backend) ─────────────────────── */
        .error-backend {
            font-size: 12px;
            color: #ff6b6b;
            margin-top: 6px;
            display: block;
        }

        /* ─── ALERTAS FLASH (éxito / error de sesión) ────────────────────── */
        .alert-flash {
            padding: 16px 20px;
            border-radius: 12px;
            font-size: 15px;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 500;
        }

        .alert-error {
            background: rgba(255, 107, 107, 0.15);
            border: 1px solid rgba(255, 107, 107, 0.3);
            color: #ff8b8b;
        }

        .alert-success {
            background: rgba(129, 199, 132, 0.18);
            border: 1px solid rgba(129, 199, 132, 0.5);
            color: #81c784;
        }

        /* ─── FOOTER DEL FORMULARIO (enlace a registro) ──────────────────── */
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

        /* ─── BARRA DE PROGRESO ──────────────────────────────────────────── */
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

        /* ─── TEXTO DE ESTADO BAJO LA BARRA ──────────────────────────────── */
        .info-status-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 4px;
        }

        .contador {
            font-size: 11px;
            color: var(--muted);
        }

        /* ─── ANIMACIÓN SHAKE (error al enviar) ──────────────────────────── */
        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-4px);
            }

            50% {
                transform: translateX(4px);
            }

            75% {
                transform: translateX(-3px);
            }
        }
    </style>
</head>

<body>
    <div class="contenedor">

        {{-- ── CABECERA ───────────────────────────────────────────────────── --}}
        <div class="cabecera">
            <div class="kicker">IES JC · Validación</div>
            <div class="titulo">Iniciar Sesión</div>
            <div class="subtitulo">Acceso seguro a las votaciones</div>
        </div>

        {{-- ── CARD DEL FORMULARIO ────────────────────────────────────────── --}}
        <div class="card" id="card">

            {{-- Alertas flash de sesión --}}
            @if (session('error'))
                <div class="alert-flash alert-error">{{ session('error') }}</div>
            @endif
            @if (session('success'))
                <div class="alert-flash alert-success">{{ session('success') }}</div>
            @endif

            <form id="form" method="POST" action="{{ route('login.post') }}">
                @csrf

                <div class="campo">
                    <label>Usuario</label>
                    <input id="nombre" name="nombre" type="text" placeholder="Ej: Juan"
                        value="{{ old('nombre') }}" required>
                    @error('nombre')
                        <div class="error-backend">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Campo: DNI con barra de progreso --}}
                <div class="campo">
                    <label>DNI</label>
                    <input id="dni" name="dni" type="text" maxlength="9" placeholder="12345678A"
                        value="{{ old('dni') }}" required>
                    @error('dni')
                        <div class="error-backend">{{ $message }}</div>
                    @enderror
                    <div class="barra-container">
                        <div class="barra" id="barraDNI"></div>
                    </div>
                    <div class="info-status-container">
                        <div class="contador" id="contadorDNI"></div>
                    </div>
                </div>

                {{-- Campo: Clave de administrador (opcional) --}}
                <div class="campo">
                    <label>Clave de Gestión Administrador (Opcional)</label>
                    <input id="password_admin" name="password_admin" type="password" placeholder="••••••••">
                    <div class="barra-container">
                        <div class="barra" id="barraPassword"></div>
                    </div>
                    <div class="info-status-container">
                        <div class="contador" id="contadorPassword"></div>
                    </div>
                </div>

                <button type="submit">Validar e Ingresar</button>
            </form>

            {{-- Enlace a registro --}}
            <div class="footer">
                ¿No tiene cuenta activa? <a href="{{ route('register') }}">Regístrate aquí</a>
            </div>
        </div>
    </div>

    <script>
        const inputNombre = document.getElementById("nombre");

        const inputDNI = document.getElementById("dni"),
            barraDNI = document.getElementById("barraDNI"),
            contadorDNI = document.getElementById("contadorDNI");

        const inputPassword = document.getElementById("password_admin"),
            barraPassword = document.getElementById("barraPassword"),
            contadorPassword = document.getElementById("contadorPassword");

        const card = document.getElementById("card"),
            form = document.getElementById("form");

        // Ponemos el foco automáticamente al cargar en el campo de usuario
        inputNombre.focus();

        // ─── FILTRO EN TIEMPO REAL PARA EL NOMBRE (SOLO LETRAS Y ESPACIOS) ───
        inputNombre.addEventListener("input", (e) => {
            // El regex elimina cualquier caracter que NO sea letra (mayúscula/minúscula), acento, eñe o espacio
            let valorFiltrado = e.target.value.replace(/[^a-zA-ZÁÉÍÓÚáéíóúÑñ\s]/g, "");
            e.target.value = valorFiltrado;
        });

        // ─── ANIMACIÓN DE ERROR ───────────────────────────────────────────────
        const shake = () => {
            card.style.animation = "shake .3s";
            setTimeout(() => card.style.animation = "", 300);
        };

        // ─── EXPRESIÓN REGULAR DEL DNI (8 números + 1 letra mayúscula) ───────
        const regexDNI = /^\d{8}[A-Z]$/;

        // ─── VALIDACIÓN EN TIEMPO REAL DEL DNI ───────────────────────────────
        inputDNI.addEventListener("input", (e) => {
            let valor = e.target.value.toUpperCase().replace(/[^0-9A-Z]/g, "");
            let numeros = valor.replace(/[^0-9]/g, "").slice(0, 8);
            let letra = valor.replace(/[^A-Z]/g, "").slice(0, 1);
            let res = numeros + letra;
            inputDNI.value = res;

            barraDNI.style.width = (res.length / 9) * 100 + "%";

            if (res.length === 9 && regexDNI.test(res)) {
                contadorDNI.textContent = "Formato correcto";
                barraDNI.style.background = "#4caf50";
            } else if (res.length > 0) {
                contadorDNI.textContent = res.length < 9 ? "DNI incompleto" : "Formato: 8 números + 1 letra";
                barraDNI.style.background = res.length < 9 ? "#f7b731" : "#ff6b6b";
            } else {
                contadorDNI.textContent = "";
            }
        });

        inputPassword.addEventListener("input", () => {
            if (inputPassword.value === "") {
                barraPassword.style.width = "0%";
                barraPassword.style.background = "rgba(255, 255, 255, 0.9)";
                contadorPassword.textContent = "";
                return;
            }

            const ok = inputPassword.value === "IESJCVote2026";
            barraPassword.style.width = "100%";
            barraPassword.style.background = ok ? "#4caf50" : "#ff6b6b";
            contadorPassword.textContent = ok ? "Contraseña correcta" : "Contraseña incorrecta";
        });

        // ─── VALIDACIÓN FINAL AL ENVIAR ───────────────────────────────────────
        form.addEventListener("submit", e => {
            if (!regexDNI.test(inputDNI.value)) {
                e.preventDefault();
                shake();
                contadorDNI.textContent = "DNI inválido";
                barraDNI.style.background = "#ff6b6b";
            }
        });
    </script>
</body>

</html>
