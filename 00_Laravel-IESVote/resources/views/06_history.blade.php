<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Historial de Votos · IESJC</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap');

        :root {
            --bg: #0b0c10;
            --card: rgba(255, 255, 255, .06);
            --text: rgba(255, 255, 255, .92);
            --success: #2ecc71;
            --muted: rgba(255, 255, 255, .55);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            min-height: 100vh;
        }

        .wrapper {
            width: 100%;
            max-width: 500px;
        }

        .card {
            background: var(--card);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 30px;
            backdrop-filter: blur(10px);
        }

        .voto-item {
            padding: 15px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .titulo-voto {
            font-weight: 500;
            margin-bottom: 4px;
        }

        .fecha {
            font-size: 11px;
            color: var(--muted);
        }

        .btn-volver {
            color: var(--success);
            text-decoration: none;
            display: block;
            margin-top: 20px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="card">
            <h1 style="font-size: 20px; margin-bottom: 5px;">Historial de {{ $user->name }}</h1>
            <p style="color: var(--muted); font-size: 13px; margin-bottom: 25px;">Documento Nacional de Identidad (DNI):
                {{ $user->dni }}</p>

            @forelse($votos as $voto)
                <div class="voto-item">
                    <div>
                        <div class="titulo-voto">{{ $voto->survey->title ?? 'Encuesta eliminada' }}</div>
                        <div class="fecha">Votado el: {{ $voto->created_at->format('d/m/Y') }}</div>
                    </div>
                    <div
                        style="font-size: 10px; color: var(--success); border: 1px solid var(--success); padding: 2px 6px; border-radius: 4px;">
                        REGISTRADO</div>
                </div>
            @empty
                <p style="text-align: center; color: var(--muted); padding: 20px 0;">No has realizado votaciones aún.
                </p>
            @endforelse

            <a href="{{ route('profile') }}" class="btn-volver">← Volver a mi perfil</a>
        </div>
    </div>
</body>

</html>
