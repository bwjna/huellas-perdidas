<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cartel — {{ $publicacion->titulo }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700;800&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Space Grotesk', sans-serif; }

        body {
            background: #ccc;
            padding: 24px 0 60px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Barra de acciones — no se imprime */
        .barra-acciones {
            width: 210mm;
            max-width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            padding: 0 4px;
        }
        .barra-acciones a { color: #333; text-decoration: none; font-size: 14px; font-weight: 600; }
        .btn-imprimir {
            background: #ff7b00;
            color: #fff;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            font-family: 'Space Grotesk', sans-serif;
        }

        /* HOJA A4 */
        .hoja {
            width: 210mm;
            height: 297mm;
            background: #fff;
            box-shadow: 0 4px 24px rgba(0,0,0,.25);
            padding: 12mm;
            display: flex;
            flex-direction: column;
        }

        .titulo-grande {
            text-align: center;
            font-size: 56px;
            font-weight: 800;
            color: {{ $publicacion->estado === 'encontrado' ? '#2563eb' : '#e11d1d' }};
            letter-spacing: 1px;
            margin-bottom: 14px;
            line-height: 1;
        }

        .foto-wrap {
            width: 100%;
            height: 105mm;
            border: 4px solid #111;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 16px;
            background: #eee;
            flex-shrink: 0;
        }
        .foto-wrap img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .foto-placeholder {
            width: 100%; height: 100%;
            display: flex; align-items: center; justify-content: center;
            font-size: 80px;
        }

        .nombre-mascota {
            text-align: center;
            font-size: 40px;
            font-weight: 800;
            color: #111;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .info-mascota {
            text-align: center;
            font-size: 17px;
            color: #222;
            line-height: 1.55;
            margin-bottom: 16px;
        }
        .info-mascota b { font-weight: 700; }

        .caja-contacto {
            background: {{ $publicacion->estado === 'encontrado' ? '#2563eb' : '#ff7b00' }};
            border-radius: 10px;
            padding: 14px 18px;
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .caja-contacto-texto { display: table-cell; vertical-align: middle; color: #fff; }
        .caja-contacto-texto .label { font-size: 15px; font-weight: 700; margin-bottom: 4px; }
        .caja-contacto-texto .telefono { font-size: 34px; font-weight: 800; letter-spacing: .5px; }
        .caja-contacto-qr { display: table-cell; vertical-align: middle; width: 100px; text-align: right; }
        .caja-contacto-qr canvas { background: #fff; padding: 6px; border-radius: 8px; }

        .pie {
            text-align: center;
            font-size: 13px;
            color: #555;
            margin-top: auto;
        }
        .pie b { color: #111; }

        @media print {
            body { background: #fff; padding: 0; }
            .barra-acciones { display: none; }
            .hoja { box-shadow: none; margin: 0; }
            @page { size: A4 portrait; margin: 0; }
        }
    </style>
</head>
<body>

    <div class="barra-acciones">
        <a href="{{ $urlPublicacion }}">← Volver a la publicación</a>
        <button class="btn-imprimir" onclick="window.print()">🖨️ Imprimir / Guardar como PDF</button>
    </div>

    <div class="hoja">
        <div class="titulo-grande">
            {{ $publicacion->estado === 'encontrado' ? '¡MASCOTA ENCONTRADA!' : '¡SE BUSCA!' }}
        </div>

        <div class="foto-wrap">
            @if($imagen)
                <img src="{{ $imagen }}" alt="{{ $publicacion->titulo }}">
            @else
                <div class="foto-placeholder">🐾</div>
            @endif
        </div>

        <div class="nombre-mascota">
            {{ $publicacion->mascota->nombre ?? $publicacion->titulo }}
        </div>

        <div class="info-mascota">
            @if($publicacion->mascota)
                <b>{{ ucfirst($publicacion->mascota->especie ?? '') }}</b>{{ $publicacion->mascota->raza ? ', ' . $publicacion->mascota->raza : '' }}{{ $publicacion->mascota->color ? ', ' . $publicacion->mascota->color : '' }}.<br>
            @endif
            @if($publicacion->descripcion)
                {{ $publicacion->descripcion }}<br>
            @endif
            @if($publicacion->zona)
                <b>{{ $publicacion->estado === 'encontrado' ? 'ENCONTRADA EN' : 'SE PERDIÓ EN' }}:</b> {{ $publicacion->zona }}.<br>
            @endif
            @if($publicacion->fecha_evento)
                <b>FECHA:</b> {{ \Carbon\Carbon::parse($publicacion->fecha_evento)->translatedFormat('d \d\e F \d\e Y') }}.
            @endif
        </div>

        <div class="caja-contacto">
            <div class="caja-contacto-texto">
                <div class="label">{{ $publicacion->estado === 'encontrado' ? 'SI ES TUYA, COMUNICATE AL:' : 'SI LO VISTE, COMUNICATE AL:' }}</div>
                <div class="telefono">{{ $publicacion->usuario->telefono ?? $publicacion->contacto ?? 'Sin teléfono cargado' }}</div>
            </div>
            <div class="caja-contacto-qr">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&margin=1&data={{ urlencode($urlPublicacion) }}" alt="QR de la publicación" style="background: #fff; padding: 6px; border-radius: 8px; width: 100px; height: 100px;">
            </div>
        </div>

        <div class="pie">
            Escaneá para ver la publicación en la web<br>
            <b>HUELLAS PERDIDAS</b> ({{ parse_url($urlPublicacion, PHP_URL_HOST) }})
        </div>
    </div>

    <!-- Carga de la librería del QR -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode/1.5.3/qrcode.min.js"></script>
    
    <!-- Script para dibujar el QR -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let url = "{!! $urlPublicacion !!}";
            let canvas = document.getElementById('qr');

            if (typeof QRCode !== 'undefined' && canvas) {
                QRCode.toCanvas(
                    canvas,
                    url,
                    { width: 100, margin: 1 },
                    function (error) {
                        if (error) console.error("Error al generar el QR:", error);
                    }
                );
            } else {
                console.error("La librería QRCode no pudo cargarse.");
            }
        });
    </script>
</body>
</html>