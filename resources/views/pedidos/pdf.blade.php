<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans; }
        .box { border:1px solid #ddd; padding:10px; margin-bottom:10px; }
        h1 { text-align:center; }
    </style>
</head>
<body>

<h1>Reporte de Finanzas Mavi</h1>

<h2>Reporte de ventas entre las fechas {{ $inicio }} - {{ $final }}  </h2>
@if($estadistica)
    <div class="box">
        <strong>Total facturas:</strong>
        {{ number_format($estadistica['total_facturas'],0,',','.') }} $
    </div>

    <div class="box">
        <strong>Total domicilios:</strong>
        {{ number_format($estadistica['total_domicilios'],0,',','.') }} $
    </div>

    <div class="box">
        <strong>Ganancia 30%:</strong>
        {{ number_format($estadistica['ganancias_30'],0,',','.') }} $
    </div>

    <div class="box">
        <strong># Ventas:</strong> {{ $estadistica['numero_ventas'] }}
    </div>
@endif

</body>
</html>