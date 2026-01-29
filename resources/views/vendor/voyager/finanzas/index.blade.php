@extends('voyager::master') @section('page_title', 'Todo') @section('content')

<style>
    /* Clases de Bootstrap 4 */

    /* Estructura de filas */
    .row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
    }

    /* Clases de columnas generales */
    .col {
        position: relative;
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
    }

    /* Clases de columnas responsivas */
    .col-6 {
        flex: 0 0 50%;
        max-width: 50%;
    }

    .col-md-3 {
        flex: 0 0 25%;
        max-width: 25%;
    }

    /* Contenedor fluido */
    .container-fluid {
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
        margin-right: auto;
        margin-left: auto;
    }

    /* Bordes */
    .border {
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        background-color: #fff;
        box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
    }

    /* Espaciado interno */
    .p-3 {
        padding: 1rem !important;
    }

    /* Margen superior */
    .mt-2 {
        margin-top: 0.5rem !important;
    }

    /* Negrita */
    .font-weight-bold {
        font-weight: 700 !important;
    }

    /* Colores de texto */
    .text-primary {
        color: #007bff !important;
    }

    .text-danger {
        color: #dc3545 !important;
    }

    .text-success {
        color: #28a745 !important;
    }

    .text-warning {
        color: #ffc107 !important;
    }
</style>

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
     <div class="container">
        <div class="row justify-content-center px-5">

    <div class="col-md-3 col-6">
        <div class="border p-3">
            <i class="fas fa-money-check-alt fa-2x text-success"></i>
            <h5 class="mt-2">Total de facturas</h5>
            <p class="font-weight-bold">
                {{ number_format($estadistica['total_facturas'], 0, ',', '.') }} $
            </p>
        </div>
    </div>

    <div class="col-md-3 col-6">
        <div class="border p-3">
            <i class="fas fa-money-check-alt fa-2x text-danger"></i>
            <h5 class="mt-2">Total Domicilios</h5>
            <p class="font-weight-bold">
                {{ number_format($estadistica['total_domicilios'], 0, ',', '.') }} $
            </p>
        </div>
    </div>

    <div class="col-md-3 col-6">
        <div class="border p-3">
            <i class="fas fa-money-check-alt fa-2x text-success"></i>
            <h5 class="mt-2">Ganancias 30%</h5>
            <p class="font-weight-bold">
                {{ number_format($estadistica['ganancias_30'], 0, ',', '.') }} $
            </p>
        </div>
    </div>

    <div class="col-md-3 col-6">
        <div class="border p-3">
            <i class="fas fa-exchange-alt fa-2x text-warning"></i>
            <h5 class="mt-2">No de ventas</h5>
            <p class="font-weight-bold">
                {{ $estadistica['numero_ventas'] }}
            </p>
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
<script src="https://code.jquery.com/jquery-3.7.1.slim.js" integrity="sha256-UgvvN8vBkgO0luPSUl2s8TIlOSYRoGFAX4jlCIm9Adc=" crossorigin="anonymous"></script>
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