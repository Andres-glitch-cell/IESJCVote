<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión · Votación</title>

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

        .error-backend {
            font-size: 12px;
            color: #ff6b6b;
            margin-top: 6px;
            display: block;
        }

        .alert-flash {
            padding: 12px 14px;
            border-radius: 12px;
            font-size: 13px;
            margin-bottom: 20px;
            line-height: 1.4;
        }

        .alert-error {
            background: rgba(255, 107, 107, 0.15);
            border: 1px solid rgba(255, 107, 107, 0.3);
            color: #ff8b8b;
        }

        .alert-success {
            background: rgba(76, 175, 80, 0.15);
            border: 1px solid rgba(76, 175, 80, 0.3);
            color: #81c784;
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

        .info-status-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 4px;
        }

        .contador {
            font-size: 11px;
            color: var(--muted);
            margin-top: 0;
            text-align: left;
        }

        .db-status-icon {
            display: inline-flex;
            align-items: center;
        }

        .db-status-icon svg {
            width: 14px;
            height: 14px;
            display: block;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .spin-animation {
            animation: spin 0.8s linear infinite;
        }
    </style>
</head>

<body>

    <div class="contenedor">

        <div class="cabecera">
            <div class="kicker">IES JC · Validación</div>
            <div class="titulo">Iniciar Sesión</div>
            <div class="subtitulo">Acceso seguro a las votaciones</div>
        </div>

        <div class="card {{ $errors->any() || session('error') ? 'shake-init' : '' }}" id="card">

            @if (session('error'))
                <div class="alert-flash alert-error">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="alert-flash alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form id="form" method="POST" action="{{ route('login.post') }}">
                @csrf

                <div class="campo">
                    <label>Nombre del Elector</label>
                    <input id="nombre" name="nombre" type="text" placeholder="Escriba su nombre completo"
                        value="{{ old('nombre') }}">
                    <div class="error" id="errNombre"></div>
                </div>

                <div class="campo">
                    <label>DNI del Elector</label>
                    <input id="dni" name="dni" type="text" maxlength="9" placeholder="12345678A"
                        value="{{ old('dni') }}">

                    <div class="error" id="errDni"></div>

                    @error('dni')
                        <div class="error-backend">{{ $message }}</div>
                    @enderror

                    <div class="barra-container">
                        <div class="barra" id="barraDNI"></div>
                    </div>

                    <div class="info-status-container">
                        <div class="contador" id="contadorDNI"></div>
                        <div class="db-status-icon" id="dbStatusIcon"></div>
                    </div>
                </div>

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

            <div class="footer">
                ¿No tiene cuenta activa? <a href="{{ route('register') }}">Regístrate aquí</a>
            </div>

        </div>
    </div>

    <script>
        const inputNombre = document.getElementById("nombre");
        const inputDNI = document.getElementById("dni");
        const barraDNI = document.getElementById("barraDNI");
        const contadorDNI = document.getElementById("contadorDNI");
        const dbStatusIcon = document.getElementById("dbStatusIcon");

        const inputPassword = document.getElementById("password_admin");
        const barraPassword = document.getElementById("barraPassword");
        const contadorPassword = document.getElementById("contadorPassword");
        const claveCorrecta = "IESJCVote2026";

        const card = document.getElementById("card");
        const form = document.getElementById("form");

        const svgCargando =
            `<svg class="spin-animation" viewBox="0 0 24 24" fill="none" stroke="#f7b731" stroke-width="3"><circle cx="12" cy="12" r="10" stroke="rgba(255,255,255,0.1)"/><path d="M22 12a10 10 0 01-10 10" stroke="#f7b731"/></svg>`;
        const svgCheck =
            `<svg viewBox="0 0 24 24" fill="none" stroke="#4caf50" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>`;
        const svgCruz =
            `<svg viewBox="0 0 24 24" fill="none" stroke="#ff6b6b" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>`;

        if (inputNombre.value === "") {
            inputNombre.focus();
        } else {
            inputDNI.focus();
        }

        function actualizarVisualDNI() {
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

            if (longitud === 0) {
                contadorDNI.textContent = "";
                dbStatusIcon.innerHTML = "";
            } else if (longitud < 9) {
                contadorDNI.textContent = "DNI incompleto";
                barraDNI.style.background = "#f7b731";
                dbStatusIcon.innerHTML = "";
            } else if (!dniValido) {
                contadorDNI.textContent = "DNI inválido";
                barraDNI.style.background = "#ff6b6b";
                dbStatusIcon.innerHTML = svgCruz;
            } else {
                contadorDNI.textContent = "Formato correcto";
                barraDNI.style.background = "#4caf50";
                verificarElectorEnBD(resultado);
            }
        }

        function verificarElectorEnBD(dniFormateado) {
            const nombreValor = inputNombre.value.trim();

            if (nombreValor === "") {
                contadorDNI.textContent = "Escriba primero el nombre";
                barraDNI.style.background = "#f7b731";
                dbStatusIcon.innerHTML = svgCruz;
                return;
            }

            dbStatusIcon.innerHTML = svgCargando;

            fetch("{{ route('login.verificar') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        nombre: nombreValor,
                        dni: dniFormateado
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.existe) {
                        dbStatusIcon.innerHTML = svgCheck;
                        contadorDNI.textContent = "Elector verificado";
                        barraDNI.style.background = "#4caf50";
                    } else {
                        dbStatusIcon.innerHTML = svgCruz;
                        contadorDNI.textContent = "No registrado en censo";
                        barraDNI.style.background = "#ff6b6b";
                    }
                })
                .catch(error => {
                    console.error("Error de censo:", error);
                    dbStatusIcon.innerHTML = "";
                });
        }

        inputDNI.addEventListener("input", actualizarVisualDNI);

        inputNombre.addEventListener("change", () => {
            if (inputDNI.value.length === 9) actualizarVisualDNI();
        });

        if (inputDNI.value.length > 0) {
            actualizarVisualDNI();
        }

        inputPassword.addEventListener("input", () => {
            const valor = inputPassword.value;

            if (valor.length === 0) {
                barraPassword.style.width = "0%";
                contadorPassword.textContent = "";
            } else if (valor === claveCorrecta) {
                barraPassword.style.width = "100%";
                barraPassword.style.background = "#4caf50";
                contadorPassword.textContent = "Contraseña correcta";
                contadorPassword.style.color = "#4caf50";
            } else {
                barraPassword.style.width = "100%";
                barraPassword.style.background = "#ff6b6b";
                contadorPassword.textContent = "Contraseña incorrecta";
                contadorPassword.style.color = "#ff6b6b";
            }
        });

        const shake = () => {
            card.style.animation = "shake .3s";
            setTimeout(() => card.style.animation = "", 300);
        };

        if (card.classList.contains('shake-init')) {
            card.classList.remove('shake-init');
            shake();
        }

        form.addEventListener("submit", e => {
            e.preventDefault();

            const nombre = inputNombre.value.trim();
            const dni = inputDNI.value.trim();

            const errNombre = document.getElementById("errNombre");
            const errDni = document.getElementById("errDni");

            errNombre.style.display = "none";
            errDni.style.display = "none";

            const backendErrorMsg = document.querySelector(".error-backend");
            if (backendErrorMsg) backendErrorMsg.style.display = "none";

            const flashAlerts = document.querySelectorAll(".alert-flash");
            flashAlerts.forEach(alert => alert.style.display = "none");

            if (nombre === "") {
                errNombre.textContent = "Por favor, introduzca su nombre registrado";
                errNombre.style.display = "block";
                inputNombre.focus();
                shake();
                return;
            }

            if (dni === "") {
                errDni.textContent = "Campo DNI vacío";
                errDni.style.display = "block";
                inputDNI.focus();
                shake();
                return;
            }

            if (!/^\d{8}[A-Z]$/.test(dni)) {
                errDni.textContent = "Formato DNI inválido (12345678A)";
                errDni.style.display = "block";
                inputDNI.focus();
                shake();
                return;
            }

            form.submit();
        });
    </script>

</body>

</html>
