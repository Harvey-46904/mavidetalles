@php
$edit = !is_null($dataTypeContent->getKey());
$add = is_null($dataTypeContent->getKey());
@endphp

@extends('voyager::master')

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', __('voyager::generic.'.($edit ? 'edit' : 'add')).'
'.$dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')
<h1 class="page-title">
    <i class="{{ $dataType->icon }}"></i>
    {{ __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular')
    }}
</h1>
@include('voyager::multilingual.language-selector')
@stop

@section('content')
<style>
    @media (max-width: 768px) {
        #tabla-detalles thead {
            display: none;
        }

        #tabla-detalles tr {
            display: block;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 6px;
            background: #fafafa;
        }

        #tabla-detalles td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: none;
            padding: 6px 0;
        }

        #tabla-detalles td::before {
            content: attr(data-label);
            font-weight: bold;
            margin-right: 10px;
            white-space: nowrap;
        }

        #tabla-detalles input {
            width: 60%;
        }
    }
</style>
<div class="page-content edit-add container-fluid">
    <div class="row">
        <div class="col-md-12">

            <div class="panel panel-bordered">
                <!-- form start -->
                <form role="form" class="form-edit-add"
                    action="{{ $edit ? route('voyager.'.$dataType->slug.'.update', $dataTypeContent->getKey()) : route('voyager.'.$dataType->slug.'.store') }}"
                    method="POST" enctype="multipart/form-data">
                    <!-- PUT Method if we are editing -->
                    @if($edit)
                    {{ method_field("PUT") }}
                    @endif

                    <!-- CSRF TOKEN -->
                    {{ csrf_field() }}

                    <div class="panel-body">

                        @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                      


                        <!-- Adding / Editing -->
                        @php
                        $dataTypeRows = $dataType->{($edit ? 'editRows' : 'addRows' )};
                        @endphp

                        @foreach($dataTypeRows as $row)
                        <!-- GET THE DISPLAY OPTIONS -->
                        @php
                        $display_options = $row->details->display ?? NULL;
                        if ($dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')}) {
                        $dataTypeContent->{$row->field} = $dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')};
                        }
                        @endphp
                        @if (isset($row->details->legend) && isset($row->details->legend->text))
                        <legend class="text-{{ $row->details->legend->align ?? 'center' }}"
                            style="background-color: {{ $row->details->legend->bgcolor ?? '#f0f0f0' }};padding: 5px;">{{
                            $row->details->legend->text }}</legend>
                        @endif

                        <div class="form-group @if($row->type == 'hidden') hidden @endif col-md-{{ $display_options->width ?? 12 }} {{ $errors->has($row->field) ? 'has-error' : '' }}"
                            @if(isset($display_options->id)){{ "id=$display_options->id" }}@endif>
                            {{ $row->slugify }}
                            <label class="control-label" for="name">{{ $row->getTranslatedAttribute('display_name')
                                }}</label>
                            @include('voyager::multilingual.input-hidden-bread-edit-add')
                            @if ($add && isset($row->details->view_add))
                            @include($row->details->view_add, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent'
                            => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'view' => 'add',
                            'options' => $row->details])
                            @elseif ($edit && isset($row->details->view_edit))
                            @include($row->details->view_edit, ['row' => $row, 'dataType' => $dataType,
                            'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'view'
                            => 'edit', 'options' => $row->details])
                            @elseif (isset($row->details->view))
                            @include($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' =>
                            $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'action' => ($edit ? 'edit'
                            : 'add'), 'view' => ($edit ? 'edit' : 'add'), 'options' => $row->details])
                            @elseif ($row->type === 'relationship')
                            @include('voyager::formfields.relationship', ['options' => $row->details])
                            @else
                            {!! app('voyager')->formField($row, $dataType, $dataTypeContent) !!}
                            @endif

                            @foreach (app('voyager')->afterFormFields($row, $dataType, $dataTypeContent) as $after)
                            {!! $after->handle($row, $dataType, $dataTypeContent) !!}
                            @endforeach
                            @if ($errors->has($row->field))
                            @foreach ($errors->get($row->field) as $error)
                            <span class="help-block">{{ $error }}</span>
                            @endforeach
                            @endif
                        </div>
                        @endforeach
                        {{-- CAMPOS EXTRA CLIENTE --}}


                        <input type="text" class="form-control" name="detalles" placeholder="Detalles" value="">
                        <div id="cliente-info" class="form-group col-md-12" style="display:none">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Nombre del cliente</label>
                                    <input type="text" class="form-control" id="cliente_nombre" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label>Celular</label>
                                    <input type="text" class="form-control" id="cliente_celular" readonly>
                                </div>
                            </div>

                        </div>


                        @if (isset($dataTypeContent))
                        @php
                        $detalles = isset($dataTypeContent)
                        ? $dataTypeContent->getRawOriginal('detalles')
                        : [];
                        // Decodificar JSON si viene como string
                        if (is_string($detalles)) {
                        $detalles = json_decode($detalles, true);
                        }
                        @endphp

                        @endif

                        @if (isset($detalles))

                        <input type="hidden" name="detalles" id="detalles_json"
                            value='{{ json_encode($detalles, JSON_UNESCAPED_UNICODE) }}'>
                        <div class="form-group col-md-12">
                            <label><strong>Detalles del pedido actualizados</strong></label>

                            <table class="table table-bordered" id="tabla-detalles">
                                <thead>
                                    <tr>
                                        <th>Detalle</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>@foreach($detalles as $item)

                                    <tr class="detalle-row">
                                        <td data-label="Detalle">
                                            <input type="text" value="{{ $item['detalle'] }}" class="form-control form-control-sm detalle" required>
                                        </td>

                                        <td data-label="Precio">
                                            <input type="number" class="form-control form-control-sm precio" min="0"
                                               value="{{ $item['precio'] }}"  required>
                                        </td>

                                        <td data-label="Cantidad">
                                            <input type="number" class="form-control form-control-sm cantidad" min="1"
                                              value="{{ $item['cantidad'] }}"  value="1" required>
                                        </td>

                                        <td class="text-center">
                                            <button type="button"
                                                class="btn btn-danger btn-sm remove-detalle">✕</button>
                                        </td>
                                    </tr>

                                    @endforeach
                                </tbody>
                            </table>

                            <button type="button" class="btn btn-success" id="add-detalle">
                                + Agregar detalle
                            </button>
                        </div>

                        <div class="col-md-12">
                            <label><strong>Precio Total</strong> <b class="precio_total">
                                    {{$dataTypeContent->getRawOriginal('total')}}$</b></label><br><br>
                            <label>
                                <strong>Saldo:</strong>
                                <b class="saldoupdate"> {{$dataTypeContent->getRawOriginal('total') -
                                    $dataTypeContent->getRawOriginal('paga_cliente') }} $</b>
                            </label>
                        </div>
                        @else
                        <input type="hidden" name="detalles" id="detalles_json">
                        <div class="form-group col-md-12">
                            <label><strong>Detalles del pedido</strong></label>

                            <div class="table-responsive">
                                <table class="table table-bordered" id="tabla-detalles">
                                    <thead>
                                        <tr>
                                            <th>Detalle</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                            <button type="button" class="btn btn-success" id="add-detalle">
                                + Agregar detalle
                            </button>
                        </div>
                        <div class="col-md-12">
                            <label>
                                <strong>Precio Total:</strong>
                                <b class="precio_total">0$</b>
                            </label><br>

                            <label>
                                <strong>Estado:</strong>
                                <b class="estado_pago">Pendiente</b>
                            </label><br>

                            <label>
                                <strong>Saldo:</strong>
                                <b class="saldo">0$</b>
                            </label>
                        </div>
                        @endif




                    </div><!-- panel-body -->

                    <div class="panel-footer">
                        @section('submit-buttons')
                        <button type="submit" class="btn btn-primary save">{{ __('voyager::generic.save') }}</button>
                        @stop
                        @yield('submit-buttons')
                    </div>
                </form>

                <div style="display:none">
                    <input type="hidden" id="upload_url" value="{{ route('voyager.upload') }}">
                    <input type="hidden" id="upload_type_slug" value="{{ $dataType->slug }}">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-danger" id="confirm_delete_modal">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="voyager-warning"></i> {{ __('voyager::generic.are_you_sure') }}</h4>
            </div>

            <div class="modal-body">
                <h4>{{ __('voyager::generic.are_you_sure_delete') }} '<span class="confirm_delete_name"></span>'</h4>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('voyager::generic.cancel')
                    }}</button>
                <button type="button" class="btn btn-danger" id="confirm_delete">{{
                    __('voyager::generic.delete_confirm') }}</button>
            </div>
        </div>
    </div>
</div>
<!-- End Delete File Modal -->
@stop

@section('javascript')
<script>
    var params = {};
        var $file;

        function deleteHandler(tag, isMulti) {
          return function() {
            $file = $(this).siblings(tag);

            params = {
                slug:   '{{ $dataType->slug }}',
                filename:  $file.data('file-name'),
                id:     $file.data('id'),
                field:  $file.parent().data('field-name'),
                multi: isMulti,
                _token: '{{ csrf_token() }}'
            }

            $('.confirm_delete_name').text(params.filename);
            $('#confirm_delete_modal').modal('show');
          };
        }

        $('document').ready(function () {
            $('.toggleswitch').bootstrapToggle();

            //Init datepicker for date fields if data-datepicker attribute defined
            //or if browser does not handle date inputs
            $('.form-group input[type=date]').each(function (idx, elt) {
                if (elt.hasAttribute('data-datepicker')) {
                    elt.type = 'text';
                    $(elt).datetimepicker($(elt).data('datepicker'));
                } else if (elt.type != 'date') {
                    elt.type = 'text';
                    $(elt).datetimepicker({
                        format: 'L',
                        extraFormats: [ 'YYYY-MM-DD' ]
                    }).datetimepicker($(elt).data('datepicker'));
                }
            });

            @if ($isModelTranslatable)
                $('.side-body').multilingual({"editing": true});
            @endif

            $('.side-body input[data-slug-origin]').each(function(i, el) {
                $(el).slugify();
            });

            $('.form-group').on('click', '.remove-multi-image', deleteHandler('img', true));
            $('.form-group').on('click', '.remove-single-image', deleteHandler('img', false));
            $('.form-group').on('click', '.remove-multi-file', deleteHandler('a', true));
            $('.form-group').on('click', '.remove-single-file', deleteHandler('a', false));

            $('#confirm_delete').on('click', function(){
                $.post('{{ route('voyager.'.$dataType->slug.'.media.remove') }}', params, function (response) {
                    if ( response
                        && response.data
                        && response.data.status
                        && response.data.status == 200 ) {

                        toastr.success(response.data.message);
                        $file.parent().fadeOut(300, function() { $(this).remove(); })
                    } else {
                        toastr.error("Error removing file.");
                    }
                });

                $('#confirm_delete_modal').modal('hide');
            });
            $('[data-toggle="tooltip"]').tooltip();
        });
</script>

@stop

@push('javascript')
<script>
    document.addEventListener('DOMContentLoaded', function () {
    // RichText de Voyager
    const rich = document.querySelector('textarea[name="detalles"]');

    if (rich) {
        rich.disabled = true;      // <- CLAVE
        rich.removeAttribute('name');
        rich.style.display = 'none';
    }
});
</script>
<script>
    $(document).ready(function () {
    $('input[name="paga_cliente"]').val(0);
});
    $('input[name="detalles"]').hide();
    function toggleCampos() {

    const domicilio = document.querySelector('input[name="domicilio"].toggleswitch');
    if (!domicilio) return;

    

    const mostrar = domicilio.checked;

    ['nombre_recibe', 'telefono_whatsapp', 'direccion', 'fecha_entrega','costo_domicilio']
        .forEach(name => {
            const input = document.querySelector(`[name="${name}"]`);
            if (input) {
                const group = input.closest('.form-group');
                if (group) {
                    group.style.display = mostrar ? 'block' : 'none';
                }
            }
        });
}

// esperar a que Voyager cargue todo
setTimeout(toggleCampos, 200);

// evento bootstrap toggle (delegado)
$(document).on(
    'change.bootstrapToggle',
    'input[name="domicilio"].toggleswitch',
    toggleCampos
);
</script>
<script>
    function setFechaCompraHoy() {
    const input = document.querySelector('input[name="fecha_compra"]');
    if (!input || input.value) return;

    const now = new Date();

    const fecha = now.getFullYear() + '-' +
        String(now.getMonth() + 1).padStart(2, '0') + '-' +
        String(now.getDate()).padStart(2, '0') + ' ' +
        String(now.getHours()).padStart(2, '0') + ':' +
        String(now.getMinutes()).padStart(2, '0');

    input.value = fecha;
    input.setAttribute('readonly', true); // 👈 NO editable
}

// esperar a que Voyager termine de renderizar
setTimeout(setFechaCompraHoy, 200);
</script>
<script>
    $('#cliente-info').hide();
    $(document).on('select2:select', 'select[name="cliente_id"]', function (e) {
       
        
    const clienteId = e.params.data.id;
     console.log(clienteId);
    if (!clienteId) return;

    $.ajax({
    url: '/admin/clientesuni/' + clienteId,
    type: 'GET',
    success: function (res) {

        // mover el bloque justo después del select de clientes
        const clienteGroup = $('select[name="cliente_id"]').closest('.form-group');
        $('#cliente-info').insertAfter(clienteGroup);

        // mostrar datos
        $('#cliente-info').slideDown();
        $('#cliente_nombre').val(res.nombres);
        $('#cliente_celular').val(res.telefono);
    },
    error: function () {
        $('#cliente-info').hide();
    }
});
});
</script>
<script>
$(function () {
    $('#es_cliente').change(function () {
        if ($(this).prop('checked')) {
            // Es cliente
            $('#cliente-info').slideUp();
        } else {
            // No es cliente
            $('#cliente-info').slideDown();
        }
    });
});
</script>
<script>
    let index = 0;

$('#add-detalle').on('click', function () {
   const row = `
<tr class="detalle-row">
    <td data-label="Detalle">
        <input type="text" class="form-control form-control-sm detalle" required>
    </td>

    <td data-label="Precio">
        <input type="number" class="form-control form-control-sm precio" min="0" required>
    </td>

    <td data-label="Cantidad">
        <input type="number" class="form-control form-control-sm cantidad" min="1" value="1" required>
    </td>

    <td class="text-center">
        <button type="button" class="btn btn-danger btn-sm remove-detalle">✕</button>
    </td>
</tr>`;
    $('#tabla-detalles tbody').append(row);
});

$(document).on('click', '.remove-detalle', function () {
    $(this).closest('tr').remove();
});
</script>
<script>
    function calcularTotal() {
    let total = 0;

    // Total de detalles
    $('#tabla-detalles tbody tr').each(function () {
        const precio = parseFloat($(this).find('.precio').val()) || 0;
        const cantidad = parseInt($(this).find('.cantidad').val()) || 0;
        total += precio * cantidad;
    });

    // Domicilio si aplica
    const tieneDomicilio = $('input[name="domicilio"]').is(':checked');
    if (tieneDomicilio) {
        const costoDomicilio = parseFloat(
            $('input[name="costo_domicilio"]').val()
        ) || 0;
        total += costoDomicilio;
    }

    // Abono del cliente
    let pagaCliente = parseFloat(
        $('input[name="paga_cliente"]').val()
    ) || 0;

    if (pagaCliente < 0) pagaCliente = 0;
    if (pagaCliente > total) pagaCliente = total;

    $('input[name="paga_cliente"]').val(pagaCliente);

    const saldo = total - pagaCliente;

    // UI
    $('.precio_total').text(total.toLocaleString('es-CO') + '$');
    $('.saldo').text(saldo.toLocaleString('es-CO') + '$');

    // Estado
    let estado = 'Pendiente';
    if (pagaCliente === total && total > 0) {
        estado = 'Cancelado';
    } else if (pagaCliente > 0) {
        estado = `Abona ${pagaCliente.toLocaleString('es-CO')}$ y debe ${saldo.toLocaleString('es-CO')}$`;
    }

    $('.estado_pago').text(estado);
}

// Eventos
$(document).on('input', '.precio, .cantidad', calcularTotal);
$(document).on('input', 'input[name="costo_domicilio"]', calcularTotal);
$(document).on('change', 'input[name="domicilio"]', calcularTotal);
$(document).on('input', 'input[name="paga_cliente"]', calcularTotal);

$(document).on('click', '.remove-detalle', function () {
    $(this).closest('tr').remove();
    calcularTotal();
});
</script>
<script>
    $('.form-edit-add').on('submit', function () {
    
    let detalles = [];
    let total = 0;

    $('#tabla-detalles tbody tr').each(function () {
        const detalle = $(this).find('.detalle').val();
        const precio = parseInt($(this).find('.precio').val());
        const cantidad = parseInt($(this).find('.cantidad').val());

        if (!detalle || !precio || !cantidad) return;

        const subtotal = precio * cantidad;
        total += subtotal;

        detalles.push({
            detalle,
            precio,
            cantidad,
            subtotal
        });
    });     
    

    //$('#detalles_json').val(JSON.stringify(detalles));
    $('input[name="detalles"]').val(JSON.stringify(detalles));
   // alert("send data")
    // opcional: setear total directo
    $('input[name="total"]').val(total);
   
});
</script>
@endpush