<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Envío</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 10px;
            line-height: 1.2;
            width: 80mm;
            margin: 0;
            padding: 5px;
        }
        .header, .footer {
            text-align: center;
            margin-bottom: 10px;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
        .label {
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            text-align: left;
            padding: 2px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>JLDIAZ ENVIOS</h1>
        <p>455 dirección<br>(+51) 888807405<br>company@example.com</p>
    </div>
    
    <div class="divider"></div>
    
    <p><span class="label">EMISOR:</span> {{$envio->name_emisor}}</p>
    <p><span class="label">DPI:</span> {{$envio->dni_emisor}}</p>
    
    <p><span class="label">RECEPTOR:</span> {{$envio->name_receptor}}</p>
    <p><span class="label">DPI:</span> {{$envio->dni_receptor}}</p>
    
    <div class="divider"></div>
    
    @if($envio->fragil != 0)
        <p class="label">FRAGIL</p>
    @endif
    
    <p><span class="label">Fecha de salida:</span> {{$envio->fecha_salida}}</p>
    <p><span class="label">Fecha de Entrega:</span> {{$envio->updated_at}}</p>
    
    <div class="divider"></div>
    
    <table>
        <tr>
            <th>Código</th>
            <th>Peso</th>
            <th>Precio</th>
        </tr>
        <tr>
            <td>{{$envio->codigo}}</td>
            <td>{{ number_format($envio->peso, 2) }} Kg</td>
            <td>{{ number_format($envio->precio, 2) }}</td>
        </tr>
    </table>
    
    <div class="divider"></div>
    
    <p><span class="label">TOTAL:</span> {{ number_format($envio->precio, 2) }}</p>
    
    <div class="divider"></div>

    <div>
    <img src="data:image/svg+xml;base64,{{ $qrCode }}" />
    <p>Escanea este código para ver los detalles del envío</p>
    <p>Código de envío: {{ $envio->codigo }}</p>
</div>
</div>
    
    <div class="footer">
        <p>Gracias por confiar en nosotros</p>
        <p>Este comprobante es válido sin firma ni sello.</p>
    </div>
</body>
</html>