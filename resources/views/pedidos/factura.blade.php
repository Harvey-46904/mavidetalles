<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #444;
        }

        .container {
            width: 100%;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            width: 120px;
        }

        .pedido-info {
            text-align: right;
        }

        .pedido-info h2 {
            margin: 0;
            color: #b57a7a;
        }

        .cliente {
            margin-top: 20px;
        }

        .cliente h3 {
            color: #d88c8c;
            margin-bottom: 10px;
        }

        .line {
            border-bottom: 1px solid #e0bcbc;
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border-color: #444;
        }

        th {
            background: #e9b5b5;
            color: #fff;
            padding: 6px;
            font-weight: bold;
            text-align: center;
        }

        td {
            padding: 6px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        

        

        .total {
            font-size: 16px;
            font-weight: bold;
            color: #000;
        }

        @page {
            margin-bottom: 120px;
            /* espacio para el footer */
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="container">

        <!-- HEADER -->
        <div class="header">
            <table width="100%" cellspacing="0" cellpadding="0" style="margin-bottom:20px;">
                <tr>
                    <!-- COLUMNA 1: LOGO -->
                    <td width="30%" valign="top">
                        <img src="{{ public_path('storage/' . setting('admin.icon_image')) }}" style="width:120px;">

                    </td>

                    <!-- COLUMNA 2: DATOS PEDIDO -->
                    <td width="40%" valign="top" align="center">
                        <h2 style="margin:0;color:#b57a7a;">PEDIDO</h2>
                        <p style="margin:4px 0;">N°: {{ str_pad($pedido->id, 6, '0', STR_PAD_LEFT) }}</p>
                        <p style="margin:4px 0;">
                            {{ $pedido->created_at->translatedFormat('d \\d\\e F Y') }}
                        </p>
                    </td>

                    <!-- COLUMNA 3: MINI IMAGEN -->
                    <td width="30%" valign="top" align="right">
                        <img src="{{ public_path('storage/' . setting('admin.decoracionheader')) }}"
                            style="width:80px;">
                    </td>
                </tr>
            </table>
        </div>

        <!-- CLIENTE -->
        <div class="cliente">
            <h3>DATOS DEL CLIENTE:</h3>
            <p><strong>Nit:</strong> {{ $pedido->cliente->cedula }}</p>
            <div class="line"></div>

            <p><strong>Nombre:</strong> {{ $pedido->cliente->nombres }}</p>
            <div class="line"></div>

            <p><strong>Teléfono:</strong> {{$pedido->cliente->telefono }}</p>
            <div class="line"></div>
        </div>

        <!-- TABLA -->
        <table>
            <thead>
                <tr>
                    <th>Cantidad</th>
                    <th>Descripción</th>
                    <th>Unidad</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detalles as $detalle)
                <tr>
                    <td>{{ $detalle['cantidad'] }}</td>
                    <td>{{ $detalle['detalle'] }}</td>
                    <td>${{ number_format($detalle['precio'], 0, ',', '.') }}</td>
                    <td>${{ number_format($detalle['subtotal'], 0, ',', '.') }}</td>
                </tr>
                @endforeach

                @if ($pedido->costo_domicilio)
                <tr>
                    <td>1</td>
                    <td>Domicilio</td>
                    <td>${{ number_format($pedido->costo_domicilio, 0, ',', '.') }}</td>
                    <td>${{ number_format($pedido->costo_domicilio, 0, ',', '.') }}</td>
                </tr>
                @endif

            </tbody>
        </table>

        <!-- RESUMEN -->
        <table width="100%" cellspacing="0" cellpadding="0" style="margin-top:20px;">
    <tr>
        <!-- COLUMNA IZQUIERDA -->
        <td width="50%" valign="top" style="text-align:left;">
                @if ($pedido->costo_domicilio)
                <p><strong>Domicilio</strong></p>
            <p>Recibe: {{ $pedido->nombre_recibe}} </p>
            <p>whatsapp: {{ $pedido->telefono_whatsapp}}</p>
            <p>Dirección: {{ $pedido->direccion}} </p>
            <p>FECHA ENTREGA: {{ $pedido->fecha_entrega}} </p>
                @endif
           
        </td>

        <!-- COLUMNA DERECHA -->
        <td width="50%" valign="top" style="text-align:right;">
            <p class="total">
                TOTAL: ${{ number_format($pedido->total, 0, ',', '.') }}
            </p>

            @if ($pedido->total != $pedido->paga_cliente)
                <p class="total">
                    ABONOS: ${{ number_format($pedido->paga_cliente, 0, ',', '.') }}
                </p>
                <p class="total">
                    SALDOS: ${{ number_format($pedido->total - $pedido->paga_cliente, 0, ',', '.') }}
                </p>
            @endif
        </td>
    </tr>
</table>

        <!-- FOOTER -->
        <div class="footer">
            @if(setting('admin.decoracionfooter'))
            <img src="{{ public_path('storage/' . setting('admin.decoracionfooter')) }}"
                style="width:100%; display:block;">
            @endif
        </div>

    </div>
</body>

</html>