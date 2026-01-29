@extends('voyager::master') @section('page_title', 'Todo') @section('content')



<div class="page-content container-fluid">

    <h1>Finanzas</h1>
    <form action="" method="GET" class="form-inline">
        <div class="form-group mb-2">
            <label for="start_date" class="mr-2">Desde:</label>
            <input type="date" class="form-control" id="start_date" name="start_date"
                value="{{ request('start_date') }}">
        </div>
        <div class="form-group mx-sm-3 mb-2">
            <label for="end_date" class="mr-2">Hasta:</label>
            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
        </div>

        <button type="submit" class="btn btn-primary mb-2">Filtrar</button>

    </form>
    @if ($estadistica)
    <div class="container-fluid">
        <div class="row">

            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="panel panel-success text-center">
                    <div class="panel-body">
                        <i class="fa fa-money fa-2x text-success"></i>
                        <h5>Total de facturas</h5>
                        <strong>
                            {{ number_format($estadistica['total_facturas'], 0, ',', '.') }} $
                        </strong>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="panel panel-danger text-center">
                    <div class="panel-body">
                        <i class="fa fa-money fa-2x text-danger"></i>
                        <h5>Total Domicilios</h5>
                        <strong>
                            {{ number_format($estadistica['total_domicilios'], 0, ',', '.') }} $
                        </strong>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="panel panel-success text-center">
                    <div class="panel-body">
                        <i class="fa fa-line-chart fa-2x text-success"></i>
                        <h5>Ganancias 30%</h5>
                        <strong>
                            {{ number_format($estadistica['ganancias_30'], 0, ',', '.') }} $
                        </strong>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3">
                <div class="panel panel-warning text-center">
                    <div class="panel-body">
                        <i class="fa fa-shopping-cart fa-2x text-warning"></i>
                        <h5># Ventas</h5>
                        <strong>
                            {{ $estadistica['numero_ventas'] }}
                        </strong>
                        <div class="row">
                            <div class="col-md-6 col-6">
                                <h5>Transferencias</h5>
                                 <strong>
                            {{ $estadistica['metodo']['Transferencia']['cantidad'] }}
                        </strong>
                            </div>
                            <div class="col-md-6 col-6">
                                <h5>Efectivo</h5>
                                 <strong>
                            {{ $estadistica['metodo']['Efectivo']['cantidad'] }}
                        </strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    @else
    <h1>Consulte un rango de fecha para poder visualizar la información</h1>
    @endif


</div>
</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.slim.js"
    integrity="sha256-UgvvN8vBkgO0luPSUl2s8TIlOSYRoGFAX4jlCIm9Adc=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.dataTables.css" />

<script src="https://cdn.datatables.net/2.3.1/js/dataTables.js"></script>

<script>
    /*$(document).ready(function() {
    $('#table_retiros').DataTable({
        // Configuraciones opcionales
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
        },
        pageLength: 10,
        order: [[0, 'desc']] // Ordena por la primera columna descendente
    });
});*/
</script>


@endsection