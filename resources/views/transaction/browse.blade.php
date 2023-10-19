@extends('voyager::master')

@section('page_title', 'Lista de transacciones')

{{-- @if (auth()->user()->hasPermission('add_contracts') || auth()->user()->hasPermission('edit_contracts')) --}}

    @section('page_header')
        <h1 id="titleHead" class="page-title">
            <i class="fa-solid fa-money-bill-transfer"></i> Transacciones
        </h1>
        <a href="{{ route('loans.index') }}" class="btn btn-warning">
            <i class="fa-solid fa-rotate-left"></i> <span>Volver</span>
        </a>
    @stop

    @section('content')
        <div class="page-content edit-add container-fluid">    
            
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-bordered">
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table id="dataStyle" class="table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th style="text-align: center; width:12%">N&deg; Transacción</th>
                                                <th style="text-align: center">Monto</th>
                                                <th style="text-align: center">Fecha</th>
                                                <th style="text-align: center">Atendido Por</th>
                                                <th style="text-align: right">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($data as $item)
                                                <tr>
                                                    <td>{{ $item->loan }}</td>
                                                    <td style="text-align: center">{{$item->transaction}}</td>
                                                    <td style="text-align: center">
                                                        @if ($item->deleted_at)
                                                            <del>BS. {{$item->amount}} <br></del>
                                                            <label class="label label-danger">Anulado por {{$item->eliminado}}</label>
                                                        @else
                                                        BS. {{$item->amount}}
                                                        @endif
                                                    </td>
                                                    <td style="text-align: center">
                                                        {{date('d/m/Y H:i:s', strtotime($item->created_at))}}<br><small>{{\Carbon\Carbon::parse($item->created_at)->diffForHumans()}}
                                                    </td>
                                                    <td style="text-align: center">{{$item->agentType}} <br> {{$item->name}}</td>
                                                    <td class="no-sort no-click bread-actions text-right">
                                                        @if(!$item->deleted_at)
                                                            <a href="{{ route('loans.payment.notification', $item->transaction_id) }}" data-phone="{{ $item->people_phone }}" class="btn btn-success btn-notification" title="Reenviar reibo">
                                                                <i class="fa fa-paper-plane"></i>
                                                            </a>
                                                            <a onclick="printDailyMoney({{$item->loan}}, {{$item->transaction_id}})" title="Imprimir"  class="btn btn-danger">
                                                                <i class="glyphicon glyphicon-print"></i>
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6">No hay datos registrados</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                
                         
        </div>
        
    @stop

    @section('css')
        <style>

        </style>
    @endsection

    @section('javascript')
        <script src="{{ url('js/main.js') }}"></script>
        <script>
            $(document).ready(function(){
                $('#dataStyle').DataTable({
                    language,
                    order: [[ 0, 'desc' ]],
                })
            });

            function printDailyMoney(loan_id, transaction_id)
            {
                // alert(loan_id);
                window.open("{{ url('admin/loans/daily/money/print') }}/"+loan_id+"/"+transaction_id, "Recibo", `width=320, height=700`)
            }

        </script>
    @stop

{{-- @endif --}}