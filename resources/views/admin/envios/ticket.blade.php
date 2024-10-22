<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Ticket de envio</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .logo {
            text-align: center;
            margin-bottom: 10px;
        }
        .qr-code {
            text-align: center;
            margin: 10px 0;
        }
        .qr-code img {
            width: 150px;
            height: 150px;
        }
        .info {
            margin-bottom: 5px;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0;">Planet express</h1>
        <p style="margin: 5px 0;">Ticket de Envío</p>
    </div>

    <div class="divider"></div>

    <div class="info">
        <p><strong>Código:</strong> {{ $envio->codigo }}</p>
        <p><strong>Fecha:</strong> {{ $envio->fecha_salida }}</p>
        <div class="divider"></div>
        <p><strong>Emisor:</strong> {{ $envio->name_emisor }}</p>
        <p><strong>DPI Emisor:</strong> {{ $envio->dni_emisor }}</p>
        <div class="divider"></div>
        <p><strong>Receptor:</strong> {{ $envio->name_receptor }}</p>
        <p><strong>DPI Receptor:</strong> {{ $envio->dni_receptor }}</p>
        <div class="divider"></div>
        <p><strong>Peso:</strong> {{ number_format($envio->peso, 3) }} kg</p>
        <p><strong>Contraseña:</strong> {{ $envio->contraseña }}</p>
        @if($envio->fragil == 1)
            <p><strong>¡FRÁGIL!</strong></p>
        @endif
    </div>

    <div class="qr-code">
        <img src="data:image/svg+xml;base64,{{ $qrCode }}" />
        <p style="font-size: 10px;">Escanea este código para ver los detalles del envío</p>
    </div>

    <div class="divider"></div>

    <div class="footer" style="text-align: center; font-size: 10px;">
        <p>Gracias por confiar en nosotros</p>
        <p>Planet Express</p>
    </div>
</body>
</html>