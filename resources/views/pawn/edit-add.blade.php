@extends('voyager::master')

@section('page_title', 'Registrar empeño')

@section('page_header')
    <h1 id="titleHead" class="page-title">
        <i class="fa-solid fa-handshake"></i> Registrar empeño
    </h1>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">    
        <form class="form-submit" action="{{ route('pawn.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="cashier_id" value="">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-body">
                            {{-- Verificar que se haya abierto caja --}}
                            @if (false)  
                                <div class="alert alert-warning">
                                    <strong>Advertencia:</strong>
                                    <p>No puedes registrar debido a que no tiene una caja asignada.</p>
                                </div>
                            @else     
                                @if (false)
                                    <div class="alert alert-warning">
                                        <strong>Advertencia:</strong>
                                        <p>No puedes registrar debido a que no tiene una caja activa.</p>
                                    </div>
                                @endif
                            @endif

                            <h5>Datos Generales</h5>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <small for="people_id">Beneficiario del Prestamo</small>
                                    <select name="people_id" class="form-control" id="select-people_id" required></select>
                                </div>
                                <div class="form-group col-md-6">
                                    <small for="interest_rate">Tasa de interes</small>
                                    <div class="input-group">
                                        <input type="number" name="interest_rate" class="form-control" value="10" step="1" required>
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <small for="date">Fecha del prestamo</small>
                                    <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <small for="date_limit">Fecha del límite de devolución</small>
                                    <input type="date" name="date_limit" class="form-control" value="{{ date('Y-m-d', strtotime(date('Y-m-d').' +1 months')) }}" required>
                                </div>
                            </div>
                            <hr>
                            <h5>Detalle de artículos</h5>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <small for="item_id">Tipo de artículo</small>
                                    <select name="item_id" class="form-control" id="select-item_id">
                                        <option value="" selected disabled>Seleccione tipo de artículo</option>
                                        @foreach (App\Models\ItemType::with(['category.features'])->where('status', 1)->get() as $item)
                                            <option value="{{ $item->id }}" data-item='@json($item)'>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>N&deg;</th>
                                                <th>Tipo</th>
                                                <th>Cantidad</th>
                                                <th>Precio</th>
                                                <th>Características</th>
                                                <th>Observaciones</th>
                                                <th class="text-right">Subtotal</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="table-details">
                                            <tr class="tr-empty">
                                                <td colspan="8">No hay artículos seleccionados</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td class="text-right" colspan="6">TOTAL Bs.</td>
                                                <td class="text-right" id="td-total"><h4>0.00</h4></td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-primary btn-submit">Guardar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>         
        </form>              
    </div>
    
    {{-- Create type items modal --}}
    <form action="{{ route('item_types.store') }}" id="form-type-items" class="form-submit" method="POST">
        @csrf
        <div class="modal modal-primary fade" tabindex="-1" id="type-items-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-tag"></i> Registrar tipo de artículo</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="contract_id">
                        <div class="form-group">
                            <label for="item_category_id">Categoría</label>
                            <select name="item_category_id" class="form-control" id="select-item_category_id" required>
                                <option value="">--Seleccionar categoría--</option>
                                @foreach (App\Models\ItemCategory::where('status', 1)->get() as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="unit">Unidad</label>
                            <select name="unit" class="form-control select2">
                                <option value="">Ninguna</option>
                                <option value="kg">kg</option>
                                <option value="g">g</option>
                                <option value="otra">Otra</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="price">Precio</label>
                            <input type="number" name="price" min="0.1" step="0.1" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="max_price">Precio máximo</label>
                            <input type="number" name="max_price" min="0.1" step="0.1" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="description">Descripción</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-dark btn-submit">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Create type items modal --}}
    <form action="{{ route('people.store') }}" id="form-person" class="form-submit" method="POST">
        @csrf
        <div class="modal modal-primary fade" tabindex="-1" id="person-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-tag"></i> Registrar beneficiario</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="first_name">Nombre(s)</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name1">Apellido paterno</label>
                            <input type="text" name="last_name1" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name2">Apellido materno</label>
                            <input type="text" name="last_name2" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="ci">CI</label>
                            <input type="text" name="ci" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="cell_phone">N&deg; de celular</label>
                            <input type="text" name="cell_phone" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="birth_date">Fecha de nac.</label>
                            <input type="date" name="birth_date" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="street">Dirección</label>
                            <textarea name="street" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-dark btn-submit">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@section('css')
    <style>
        .select2{
            width: 100% !important;
        }
        .input-feature{
            width: 120px;
            border: 0px !important
        }
    </style>
@stop

@section('javascript')
    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        var index = 0;
        var number_features = 0;
        $(document).ready(function(){
            
            customSelect('#select-people_id', '{{ url("admin/people/search/ajax") }}', formatResultPeople, data => data.first_name+' '+data.last_name1+' '+data.last_name2, null, 'createPerson()');
            
            $('#select-item_category_id').select2({
                tags: true,
                dropdownParent: '#type-items-modal',
                createTag: function (params) {
                    return {
                        id: params.term,
                        text: params.term,
                        newOption: true
                    }
                },
                templateResult: function (data) {
                    var $result = $("<span></span>");
                    $result.text(data.text);
                    if (data.newOption) {
                        $result.append(" <em>(ENTER para agregar)</em>");
                    }
                    return $result;
                }
            });

            $('#select-item_id').select2({
                language: {
                    noResults: function() {
                        return `Resultados no encontrados <button class="btn btn-link" onclick="dismissSelect2()" data-toggle="modal" data-target="#type-items-modal">Crear nuevo</a>`;
                    },
                },
                escapeMarkup: function(markup) {
                    return markup;
                }
            });

            $('#select-item_id').change(function(){
                let type = $('#select-item_id option:selected').data('item');
                if (type) {
                    console.log(type)
                    // Obetener la lista de características de cada tipo de item
                    let features = '';
                    type.category.features.map(item => {
                        features += `
                            <tr id="tr-features-${number_features}">
                                <td style="width:120px !important"><input type="hidden" name="features_${index}[]" value="${item.id}" /><b>${item.name}</b>&nbsp;</td>
                                <td><input type="text" name="features_value_${index}[]" ${item.required ? 'required' : ''} /></td>
                                <td><button type="button" class="btn-danger" onclick="removeTrFeature(${number_features})">x</button></td>
                            </tr>`;
                            number_features++;
                    });
                    
                    $('.tr-empty').css('display', 'none');
                    
                    $('#table-details').append(`
                        <tr id="tr-item-${index}">
                            <td class="td-number"></td>
                            <td>
                                ${type.name} <br>
                                <span style="font-size: 12px">${type.category.name}</span>
                                <input type="hidden" name="item_type_id[]" value="${type.id}" />
                            </td>
                            <td width="120px">
                                <div class="input-group">
                                    <input type="number" name="quantity[]" id="input-quantity-${index}" onchange="getSubtotal(${index})" onkeyup="getSubtotal(${index})" class="form-control" value="1" min="1" step="0.1" required>
                                    <span class="input-group-addon"><small>${type.unit ? type.unit : 'pza'}</small></span>
                                </div>
                            </td>
                            <td width="170px">
                                <div class="input-group">
                                    <input type="number" name="price[]" id="input-price-${index}" onchange="getSubtotal(${index})" onkeyup="getSubtotal(${index})" class="form-control" value="${type.price}" max="${type.max_price ? type.max_price : ''}" required>
                                    <span class="input-group-addon"><small>Bs.</small></span>
                                </div>
                            </td>
                            <td style="width: 300px" class="table-features">
                                <table id="table-features-${index}">${features}</table>
                                <a class="btn btn-link" onclick="addFeature(${index})" style="padding-left: 0px"><i class="voyager-plus"></i> agregar</a>
                            </td>
                            <td><textarea name="observation[]" class="form-control"></textarea></td>
                            <td id="td-subtotal-${index}" class="td-subtotal text-right">${type.price}</td>
                            <td class="text-right"><button type="button" class="btn btn-link" onclick="removeTr(${index})"><span class="voyager-trash text-danger"></span></button></td>
                        </td>
                    `);
                    generateNumber();
                    index++;
                    $('#select-item_id').val('').trigger('change');
                    getTotal();
                }
            });

            $('#form-person').submit(function(e){
                e.preventDefault();
                $.post($(this).attr('action'), $(this).serialize(), function(res){
                    if(res.success){
                        $('#person-modal').modal('hide');
                        toastr.success('Beneficiario registrado correctamente', 'Bien hecho!');
                        $(this).trigger('reset');
                    }else{
                        toastr.error(res.error, 'Error');
                    }
                    $('.form-submit .btn-submit').prop('disabled', false);
                });
            });

            $('#form-type-items').submit(function(e){
                e.preventDefault();
                $.post($(this).attr('action'), $(this).serialize(), function(res){
                    if(res.success){
                        let newOption = `<option value="${res.type.id}" data-item='${JSON.stringify(res.type)}'>${res.type.name}</option>`;
                        $('#select-item_id').append(newOption).trigger('change');
                        $('#type-items-modal').modal('hide');
                        toastr.success('Tipo registrado correctamente', 'Bien hecho!');
                        setTimeout(() => {
                            $('#select-item_id').val(res.type.id).trigger('change');
                        }, 250);
                    }else{
                        toastr.error('Ocurrió un error', 'Error');
                    }
                    $('.form-submit .btn-submit').prop('disabled', false);
                });
            });
        });

        function createPerson(){
            dismissSelect2();
            $('#person-modal').modal('show');
        }

        function addFeature(index){
            $(`#table-features-${index}`).append(`
                <tr id="tr-features-${number_features}">
                    <td><input type="text" name="features_${index}[]" placeholder="Nuevo..." autofocus class="input-feature" required /></td>
                    <td><input type="text" name="features_value_${index}[]" required /></td>
                    <td><button type="button" class="btn-danger" onclick="removeTrFeature(${number_features})">x</button></td>
                </tr>
            `);
            number_features++;
        }

        function getSubtotal(index){
            let price = $(`#input-price-${index}`).val() ? parseFloat($(`#input-price-${index}`).val()) : 0;
            let quantity = $(`#input-quantity-${index}`).val() ? parseFloat($(`#input-quantity-${index}`).val()) : 0;
            if (quantity > 0) {
                $(`#td-subtotal-${index}`).text((price*quantity).toFixed(2));
                getTotal();
            } else {
                $(`#input-quantity-${index}`).val(1);
                getSubtotal(index);
                toastr.warning('La cantidad debe ser de al menos 1', 'Advertencia');
            }
        }

        function getTotal(){
            let total = 0;
            $('.td-subtotal').each(function(){
                let value = parseFloat($(this).text());
                total += value;
            });
            $(`#td-total`).html(`<h4>${total.toFixed(2)}</h4>`);
        }

        function generateNumber(){
            let number = 1;
            $('.td-number').each(function(){
                $(this).text(number);
                number++;
            });

            // Si está vacío
            if(number == 1){
                $('.tr-empty').css('display', 'block');
            }
        }

        function removeTr(index){
            $(`#tr-item-${index}`).remove();
            generateNumber();
            getTotal();
        }

        function removeTrFeature(index){
            $(`#tr-features-${index}`).remove();
            generateNumber();
            getTotal();
        }

        function dismissSelect2(){
            $('#select-people_id').select2("close");
            $('#select-item_id').select2("close");
        }
    </script>
@stop