@extends('voyager::master')

@section('page_title', 'Viendo Prestamos')

@if (auth()->user()->hasPermission('browse_loans'))

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-4" style="padding: 0px">
                            <h1 id="titleHead" class="page-title">
                                <i class="fa-solid fa-hand-holding-dollar"></i> Prestamos
                            </h1>

                            
                        </div>
                        <div class="col-md-8 text-right" style="padding: 0px">
                            <h1 id="titleHead" class="page-title money">
                                <i class="fa-solid fa-dollar-sign"></i> {{$balance}}
                            </h1>
                            @if (auth()->user()->hasPermission('add_loans'))
                                <a href="{{ route('loans.create') }}" class="btn btn-success">
                                    <i class="voyager-plus"></i> <span>Prestamo Diario</span>
                                </a>
                            @endif

                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="dataTables_length" id="dataTable_length">
                                    <label>Mostrar <select id="select-paginate" class="form-control input-sm">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select> registros</label>
                                </div>
                            </div>
                            {{-- <div class="col-sm-2">
                                <input type="text" id="input-search" class="form-control">
                            </div> --}}
                            <div class="col-sm-4" style="margin-bottom: 0px">
                                <input type="text" id="input-search" class="form-control" placeholder="Ingrese busqueda..."> <br>
                            </div>
                            <div class="col-md-12 text-right">
                                @if (!auth()->user()->hasRole('cobrador'))
                                    <label class="radio-inline"><input type="radio" class="radio-type" name="optradio" value="todo">Todos</label>
                                @endif
                                    <label class="radio-inline"><input type="radio" class="radio-type" name="optradio" value="entregado" checked>En Pagos</label>
                                
                                
                                @if (!auth()->user()->hasRole('cobrador'))
                                    <label class="radio-inline"><input type="radio" class="radio-type" name="optradio" value="aprobado">Por Entregar</label>
                                    <label class="radio-inline"><input type="radio" class="radio-type" name="optradio" value="verificado">Por Aprobar</label>
                                @endif
                                
                                    <label class="radio-inline"><input type="radio" class="radio-type" name="optradio" value="pendiente">Pendientes</label>
                             

                                @if (!auth()->user()->hasRole('cobrador'))
                                    <label class="radio-inline"><input type="radio" class="radio-type" name="optradio" value="pagado">Pagados</label>
                                    <label class="radio-inline"><input type="radio" class="radio-type" name="optradio" value="rechazado">Rechazados</label>
                                @endif
                                @if (!auth()->user()->hasRole('cobrador') && !auth()->user()->hasRole('cajero'))
                                    <label class="radio-inline"><input type="radio" class="radio-type" name="optradio" value="eliminado">Prestamos Eliminados</label>
                                @endif
                            </div>
                        </div>
                        <div class="row" id="div-results" style="min-height: 120px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-dark fade" data-backdrop="static" tabindex="-1" id="success-modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa-solid fa-money-check-dollar"></i> Aprobar Prestamo</h4>
                </div>
                <div class="modal-footer">
                    <form action="#" id="success_form" method="GET">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" id="id">

                            <div class="text-center" style="text-transform:uppercase">
                                <i class="fa-solid fa-money-check-dollar" style="color: rgb(68, 68, 68); font-size: 5em;"></i>
                                <br>
                                
                                <p><b>Desea aprobar el prestamo?</b></p>
                            </div>
                        <input type="submit" class="btn btn-dark pull-right delete-confirm" value="Sí, aprobar">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal modal-primary fade" data-backdrop="static" tabindex="-1" id="agent-modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><span class="icon fa-solid fa-person-digging"></span> Agente</h4>
                </div>
                <div class="modal-body">
                    <form action="#" id="agent_form" method="POST">
                        {{ csrf_field() }}
                    <div class="row">
                        <div class="form-group col-md-6">
                            <small>Cobrador Asignado al Prestamo</small>
                            <select name="loan_id" id="loan_id" class="form-control select2" required>
                                <option value="" disabled selected>-- Selecciona a la persona --</option>
                                @foreach ($collector as $item)
                                    @if ($item->role)
                                        <option value="{{$item->id}}">{{$item->name}}</option>                                                
                                    @endif
                                @endforeach
                            </select>
                        </div> 
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            {{-- <label for="observation"></label> --}}
                            <small>Observación</small>
                            <textarea name="observation" id="observation" class="form-control text" cols="30" rows="5"></textarea>
                        </div>                                  
                    </div>
                </div>
                <div class="modal-footer">
                    <form action="#" id="agent_form" method="POST">
                        {{ csrf_field() }}
                        
                        <input type="submit" class="btn btn-dark pull-right delete-confirm" value="Sí, cambiar">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>



    <div class="modal modal-danger fade" data-backdrop="static" tabindex="-1" id="delete-modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i> Desea eliminar el siguiente registro?</h4>
                </div>
                <div class="modal-footer">
                    <form action="#" id="delete_form" method="POST">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <input type="hidden" name="id" id="id">

                            <div class="text-center" style="text-transform:uppercase">
                                <i class="voyager-trash" style="color: red; font-size: 5em;"></i>
                                <br>
                                
                                <p><b>Desea eliminar el siguiente registro?</b></p>
                            </div>
                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="Sí, eliminar">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- modal para destruir un prestamo  con caja cerrada --}}
    <form action="#" id="destroy_form" method="POST">
        {{ method_field('DELETE') }}
        {{ csrf_field() }}
        <div class="modal modal-danger fade" data-backdrop="static" tabindex="-1" id="destroy-modal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="voyager-trash"></i> Desea eliminar el siguiente registro?</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <strong>Aviso: </strong>
                            <p> Usted esta eliminando un prestamo que ha sido entregado al beneficiario, por lo tanto al eliminar el prestamo usted debera contar usted con caja abierta para realizar la eliminacion de prestamo. </p>
                        </div> 
                        {{-- <input type="hidden" name="id" id="id"> --}}

                        <div class="text-center" style="text-transform:uppercase">
                            <i class="voyager-trash" style="color: red; font-size: 5em;"></i>
                            {{-- <br>                                     --}}
                            {{-- <p><b>Desea eliminar el siguiente registro?</b></p> --}}
                        </div>
                        <div class="form-group">
                            <label for="observation">Motivo</label>
                            <textarea name="destroyObservation" class="form-control" rows="5" placeholder="Describa el motivo de la anulación del prestamo" required></textarea>
                        </div>
                        <label class="checkbox-inline"><input type="checkbox" value="1" required>Confirmar eliminacion..!</label>
                    </div>

                    <div class="modal-footer">
                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="Sí, eliminar">
                        
                        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>



    {{-- para rechazar --}}
    <div class="modal modal-primary fade" data-backdrop="static" tabindex="-1" id="rechazar-modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa-solid fa-thumbs-down"></i> Desea rechazar el siguiente registro?</h4>
                </div>
                <div class="modal-footer">
                    <form action="#" id="rechazar_form" method="GET">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" id="id">

                            <div class="text-center" style="text-transform:uppercase">
                                <i class="fa-solid fa-thumbs-down" style="color: #353d47; font-size: 5em;"></i>
                                <br>
                                
                                <p><b>Desea rechazar el siguiente registro?</b></p>
                            </div>
                        <input type="submit" class="btn btn-dark pull-right delete-confirm" value="Sí, rechazar">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal modal-success fade" data-backdrop="static" tabindex="-1" id="notificar-modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa-brands fa-square-whatsapp"></i> Notificar</h4>
                </div>
                <div class="modal-body">
                        {{-- <input type="hidden" id="id"> --}}
                        <input type="hidden" id="phone">
                        <input type="hidden" id="name">
                </div>   
                
                <div class="modal-footer">
                    <div class="text-center" style="text-transform:uppercase">
                        <i class="fa-brands fa-square-whatsapp" style="color: #52ce5f; font-size: 5em;"></i>
                        <br>
                        <p><b>Desea notificar al beneficiario?</b></p>
                    </div>
                    <input type="submit" class="btn btn-success pull-right delete-confirm"  onclick="miFunc()" value="Sí, Enviar">
                    
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-success fade" data-backdrop="static" tabindex="-1" id="deliver-modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa-solid fa-money-check-dollar"></i> Entregar Dinero</h4>
                </div>
                <div class="modal-footer">
                                @if (!$cashier)  
                                    <div class="alert alert-warning">
                                        <strong>Advertencia:</strong>
                                        <p>No puedes entregar el prestamo debido a que no tiene una caja asignada.</p>
                                    </div>
                                @else     
                                    @if ($cashier->status != 'abierta')
                                        <div class="alert alert-warning">
                                            <strong>Advertencia:</strong>
                                            <p>No puedes entregar el prestamo debido a que no tiene una caja activa.</p>
                                        </div>
                                    @endif
                                @endif
                    <form action="#" id="deliver_form" method="POST">
                        {{ csrf_field() }}
                            <input type="hidden" name="cashier_id" value="{{$cashier_id}}">

                            <div class="text-center" style="text-transform:uppercase">
                                <i class="fa-solid fa-money-check-dollar" style="color: rgb(68, 68, 68); font-size: 5em;"></i>
                                <br>
                                
                                <p><b>Desea entregar el dinero al beneficiario?</b></p>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <small>Fecha</small>
                                    <input type="date"  class="form-control text" name="fechass" id="fechass">
                                </div>                                  
                            </div>
                            <br>
                            <br>


                        @if ($cashier)    
                            @if ($cashier->status == 'abierta')
                                <input type="submit" id="btn-submit-delivered" style="display:block" class="btn btn-success pull-right delete-confirm" value="Sí, entregar">
                            @endif
                        @endif                        
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
 
@stop

@section('css')
<style>

    /* LOADER 3 */
    
    #loader-3:before, #loader-3:after{
      content: "";
      width: 20px;
      height: 20px;
      position: absolute;
      top: 0;
      left: calc(50% - 10px);
      background-color: #5eaf4a;
      animation: squaremove 1s ease-in-out infinite;
    }
    
    #loader-3:after{
      bottom: 0;
      animation-delay: 0.5s;
    }
    
    @keyframes squaremove{
      0%, 100%{
        -webkit-transform: translate(0,0) rotate(0);
        -ms-transform: translate(0,0) rotate(0);
        -o-transform: translate(0,0) rotate(0);
        transform: translate(0,0) rotate(0);
      }
    
      25%{
        -webkit-transform: translate(40px,40px) rotate(45deg);
        -ms-transform: translate(40px,40px) rotate(45deg);
        -o-transform: translate(40px,40px) rotate(45deg);
        transform: translate(40px,40px) rotate(45deg);
      }
    
      50%{
        -webkit-transform: translate(0px,80px) rotate(0deg);
        -ms-transform: translate(0px,80px) rotate(0deg);
        -o-transform: translate(0px,80px) rotate(0deg);
        transform: translate(0px,80px) rotate(0deg);
      }
    
      75%{
        -webkit-transform: translate(-40px,40px) rotate(45deg);
        -ms-transform: translate(-40px,40px) rotate(45deg);
        -o-transform: translate(-40px,40px) rotate(45deg);
        transform: translate(-40px,40px) rotate(45deg);
      }
    }
    
    
    </style>
@stop

@section('javascript')
    {{-- <script src="{{ url('js/main.js') }}"></script> --}}
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> --}}
    <script>
        //para inpresion cuando es entregado elñ prestamo para que imprima
        $(document).ready(function(){

            @if(session('loan_id'))
                loan_id = "{{ session('loan_id') }}";

                window.open("{{ url('admin/loans/comprobante/print') }}/"+loan_id, "Recibo", `width=700, height=700`)

            @endif

        });
        function comprobanteDelivered(loan_id)
        {
            window.open("{{ url('admin/loans/comprobante/print') }}/"+loan_id, "Recibo", `width=700, height=700`)
        }

    </script>
    <script>

        $(document).ready(function(){
            $('#deliver_form').submit(function(e){
                $('#btn-submit-delivered').text('Guardando...');
                $('#btn-submit-delivered').attr('disabled', true);
            });
        })

        var countPage = 10, order = 'id', typeOrder = 'desc';

        var balance = 0;
        var cashier_id = {{$cashier_id}}
        $(function() {
            balance = {{$balance}};
            
            
            setInterval(            
                function () 
                {         
                    // alert(cashier_id)   
                    $.get('{{route('loans-cashier.balance')}}/'+cashier_id, function (data) {
                        balance =data;
                        // alert(balance);
                        $('.money').html('<i class="fa-solid fa-dollar-sign"></i>'+data);
                    });
                }, 5000 //para actualizar el balance de cada caja
            );
        
        });




        $(document).ready(() => {
            list();

            $('.radio-type').click(function(){
                list();
            });
            
            $('#input-search').on('keyup', function(e){
                if(e.keyCode == 13) {
                    list();
                }
            });

            $('#select-paginate').change(function(){
                countPage = $(this).val();
               
                list();
            });
        });

        function list(page = 1){
            $('#div-results').loading({message: 'Cargando...'});
            let type = $(".radio-type:checked").val();
            let url = "{{ url('admin/loans/ajax/list')}}/"+cashier_id;
            let search = $('#input-search').val() ? $('#input-search').val() : '';

            $.ajax({
                url: `${url}/${type}/${search}?paginate=${countPage}&page=${page}`,
                type: 'get',
                
                success: function(result){
                    $("#div-results").html(result);
                    $('#div-results').loading('toggle');
                }
            });

        }

        function deleteItem(url){
            $('#delete_form').attr('action', url);
        }

        //Para la destruccion de un prestamos pero con caja cerrada 
        function destroyItem(url){
            $('#destroy_form').attr('action', url);
        }
        
        function rechazarItem(url){
            $('#rechazar_form').attr('action', url);
        }
        function successItem(url){
            $('#success_form').attr('action', url);
        }

        function agentItem(url){
            $('#agent_form').attr('action', url);
        }

        var loanC = 0;

        function deliverItem(url, id, amountTotal){
            $('#deliver_form').attr('action', url);
            loanC = id;
            if(amountTotal > balance && cashier_id!=0)
            {
                // $('#btn-submit-delivered').attr('disabled', 'disabled');
                $('#btn-submit-delivered').css('display', 'none');

                Swal.fire({
                    target: document.getElementById('deliver_form'),
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Su saldo disponible de Caja es insuficiente!',
                    // footer: '<a href="">Why do I have this issue?</a>'
                })
            }
            if(amountTotal < balance && cashier_id!=0)
            {
                
                $('#btn-submit-delivered').css('display', 'block');
            }
        }

        


        function miFunc() {

            let phone = $('#phone').val();
            let name = $('#name').val();

            let timerInterval
            Swal.fire({
                title: 'Notificacion enviada',
                html: '<h2><i class="fa-regular fa-envelope"></i></h2>',
                timer: 2000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading()
                    const b = Swal.getHtmlContainer().querySelector('b')
                    timerInterval = setInterval(() => {
                    b.textContent = Swal.getTimerLeft()
                    }, 50)
                },
                willClose: () => {
                    clearInterval(timerInterval)
                }
                }).then((result) => {
                if (result.dismiss === Swal.DismissReason.timer) {
                    console.log('I was closed by the timer')
                }
            })

            // url = "http://whatsapp.capresi.net/?number=591"+phone+"&message=Hola *"+name+"*.%0A%0A*SU SOLICITUD DE PRESTAMO HA SIDO APROBADA EXITOSAMENTE*%0A%0APase por favor por las oficinas para entregarle su solicitud de prestamos%0A%0AGracias🤝😊";

            // const xhr = new XMLHttpRequest();
            // xhr.open("GET", url);
            // xhr.send();

            $("#notificar-modal").modal('hide');
        }
        $('#notificar-modal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) 
            var phone = button.data('phone')
            var name = button.data('name')
            var modal = $(this)
            modal.find('.modal-body #name').val(name)
            modal.find('.modal-body #phone').val(phone)
        });


        function loan(id)
        {
            // alert(id);
            loanC = id;
            printContract();
        }

        function printContract(){
            // window.open("https://trabajostop.com", "Recibo", `width=700, height=500`)
            window.open("{{ url('admin/loans/contract/daily') }}/"+loanC, "Recibo", `width=700, height=500`)
        }




        $('#deliver-modal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) 
            var data = button.data('fechass')
            // alert(data)
            var modal = $(this)
            modal.find('.modal-footer #fechass').val(data)
        });




        
        

    </script>
@stop
@endif