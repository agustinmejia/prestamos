@extends('layouts.template-print-alt')

@section('page_title', 'Reporte')

@section('content')
    @php
        $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');    
    @endphp

    <table width="100%">
        <tr>
            <td style="width: 20%"><img src="{{ asset('images/icon.png') }}" alt="CAPRESI" width="70px"></td>
            <td style="text-align: center;  width:50%">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                    EMPRESA "CAPRESI"
                </h3>
                <h4 style="margin-bottom: 0px; margin-top: 5px">
                    REPORTE DETALLADO DE RECAUDACION POR PERIODO
                    {{-- Stock Disponible {{date('d/m/Y', strtotime($start))}} Hasta {{date('d/m/Y', strtotime($finish))}} --}}
                </h4>
                <small style="margin-bottom: 0px; margin-top: 5px">
                    @if ($start == $finish)
                        {{ date('d', strtotime($start)) }} de {{ $months[intval(date('m', strtotime($start)))] }} de {{ date('Y', strtotime($start)) }}
                    @else
                        {{ date('d', strtotime($start)) }} de {{ $months[intval(date('m', strtotime($start)))] }} de {{ date('Y', strtotime($start)) }} Al {{ date('d', strtotime($finish)) }} de {{ $months[intval(date('m', strtotime($finish)))] }} de {{ date('Y', strtotime($finish)) }}
                    @endif
                </small>
            </td>
            <td style="text-align: right; width:30%">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                    <div id="qr_code">
                        @if ($start != $finish)
                            {!! QrCode::size(80)->generate('Total Cobrado: Bs'.number_format($amountTotal,2, ',', '.').', Recaudado en Fecha '.date('d', strtotime($start)).' de '.strtoupper($months[intval(date('m', strtotime($start)))] ).' de '.date('Y', strtotime($start)).' al '.date('d', strtotime($finish)).' de '.strtoupper($months[intval(date('m', strtotime($finish)))] ).' de '.date('Y', strtotime($finish))); !!}
                        @else
                            {!! QrCode::size(80)->generate('Total Cobrado: Bs'.number_format($amountTotal,2, ',', '.').', Recaudado en Fecha '.date('d', strtotime($start)).' de '.strtoupper($months[intval(date('m', strtotime($start)))] ).' de '.date('Y', strtotime($start))); !!}
                        @endif
                    </div>
                    <small style="font-size: 8px; font-weight: 100">Impreso por: {{ Auth::user()->name }} <br> {{ date('d/m/Y H:i:s') }}</small>
                </h3>
            </td>
        </tr>
    </table>
    
    <table style="width: 100%; font-size: 10px" border="1" class="print-friendly" cellspacing="0" cellpadding="2">
        <thead>
            <tr>
                <th rowspan="2" style="width:5px">N&deg;</th>   
                <th rowspan="2" style="text-align: center; width:5px">CI</th>
                <th rowspan="2" style="text-align: center">CLIENTE</th>
                <th rowspan="2" style="text-align: center">ATENDIDO POR</th>
                <th colspan="3" style="text-align: center">DETALLE DEL PRESTAMOS</th>
                <th colspan="3" style="text-align: center">DETALLE DE PAGO</th>
            </tr>
            <tr>    
                <th style="text-align: center; width:5px">CODIGO</th>
                <th style="text-align: center; width:5px">FECHA DEL CALENDARIO</th>
                <th style="text-align: center; width:5px">TOTAL DEL PRESTAMO</th>

                <th style="text-align: center; width:100px">N. TRANS.</th>
                <th style="text-align: center; width:5px">FECHA DE PAGO</th>
                <th style="text-align: center; width:70px">TOTAL PAGADO</th>
            </tr>
        </thead>
        <tbody>
            @php
                $count = 1;
                $total = 0;
            @endphp
            @forelse ($data as $item)
                <tr>
                    <td>{{ $count }}</td>
                    <td>{{ $item->ci }}</td>
                    <td>{{ strtoupper($item->last_name1)}} {{ strtoupper($item->last_name2)}} {{ strtoupper($item->first_name)}}</td>
                    <td>{{ strtoupper($item->name)}}</td>
                    <td style="text-align: center"><small>{{ $item->code}}</small>
                        @if ($item->deleted_at) <br>
                            <label style="color: red">Prestamo eliminado</label>
                        @endif
                    </td>
                    <td style="text-align: center">{{date('d/m/Y', strtotime($item->dateDay))}}</td>
                    <td style="text-align: right">{{ number_format($item->amountTotal,2, ',','.') }}</td>
                    <td style="text-align: left"><small>Nº: </small>{{ $item->transaction}}
                        <br>
                        <small>Tipo de Pago: </small>{{$item->type}}
                    </td>
                    <td style="text-align: center">{{date('d/m/Y', strtotime($item->loanDayAgent_fecha))}}<br>{{date('H:i:s', strtotime($item->loanDayAgent_fecha))}}</td>
                    <td style="text-align: right">{{ number_format($item->amount,2, ',','.') }}</td>                              
                                                                            
                </tr>
                @php
                    $count++;                 
                    $total+= $item->amount;                    
                @endphp
            @empty
                <tr style="text-align: center">
                    <td colspan="10">No se encontraron registros.</td>
                </tr>
            @endforelse
            <tr>
                <td colspan="9" style="text-align: right">Total</td>
                <td style="text-align: right"><strong>Bs. {{ number_format($total,2, ',','.') }}</strong></td>
            </tr>
        </tbody>
       
    </table>

@endsection
@section('css')
    <style>
        table, th, td {
            border-collapse: collapse;
        }
        /* @media print { div{ page-break-inside: avoid; } }  */
          
        table.print-friendly tr td, table.print-friendly tr th {
            page-break-inside: avoid;
        }
          
    </style>
@stop
