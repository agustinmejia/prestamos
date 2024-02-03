@extends('layouts.template-print-alt')

@section('page_title', 'Reporte')

@section('content')
    @php
        $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');    
    @endphp

    <table width="100%">
        <tr>
            <td style="width: 20%"><img src="{{ asset('images/icon.png') }}" alt="CAPRESI" width="70px"></td>
            <td style="text-align: center;  width:70%">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                    EMPRESA "CAPRESI"<br>
                </h3>
                <h4 style="margin-bottom: 0px; margin-top: 5px">
                    LISTA DE COBRANZA
                    <br>
                    {{$message}}
                    {{-- Stock Disponible {{date('d/m/Y', strtotime($start))}} Hasta {{date('d/m/Y', strtotime($finish))}} --}}
                </h4>
                <small style="margin-bottom: 0px; margin-top: 5px">
                        {{ date('d') }} de {{ $months[intval(date('m'))] }} de {{ date('Y') }}
                   
                </small>
            </td>
            <td style="text-align: right; width:30%">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                   
                    <small style="font-size: 11px; font-weight: 100">Impreso por: {{ Auth::user()->name }} <br> {{ date('d/m/Y H:i:s') }}</small>
                </h3>
            </td>
        </tr>
    </table>
    <table style="width: 100%; font-size: 8px" border="1" cellspacing="0" cellpadding="4">
        <thead>
            <tr>
                <th rowspan="2" style="width:5px">N&deg;</th>
                <th rowspan="2" style="text-align: center; width:70px">CODIGO</th>
                <th rowspan="2" style="text-align: center">CLIENTE</th>
                <th rowspan="2" style="text-align: center">TELEFONO/CELULAR</th>
                <th rowspan="2" style="text-align: center">DURACIÓN</th>
                <th rowspan="2" style="text-align: center">PAGO DIARIO</th>
                <th colspan="3" style="text-align: center">RETRASO</th>
            </tr>
            <tr>
                <th style="text-align: center; width:50px">DIAS</th>
                <th style="text-align: center; width:50px">TOTAL A PAGAR</th>
            </tr>
        </thead>
        <tbody>
            @php
                $count = 1;
                $pago_diario = 0;
            @endphp
            @forelse ($data as $item)
                @php
                    $ok = Illuminate\Support\Facades\DB::table('loans as l')
                                ->join('loan_days as ld', 'ld.loan_id', 'l.id')
                                ->join('loan_day_agents as lda', 'lda.loanDay_id', 'ld.id')
                                ->where('l.id', $item->loan_id)
                                ->where('l.deleted_at', null)
                                ->where('ld.deleted_at', null)
                                ->where('lda.deleted_at', null)
                                ->whereDate('lda.created_at', date('Y-m-d', strtotime($date)))
                                ->select('*')
                                ->get();

                @endphp

                @if (count($ok)==0)
                    @php
                        $day = Illuminate\Support\Facades\DB::table('loans as l')
                                ->join('loan_days as ld', 'ld.loan_id', 'l.id')
                                ->where('l.id', $item->loan_id)
                                ->where('l.deleted_at', null)

                                ->where('ld.deleted_at', null)
                                ->where('ld.debt', '>', 0)
                                ->whereDate('ld.date', date('Y-m-d', strtotime($date)))
                                ->select('ld.debt', 'ld.amount')
                                ->first();

                        $atras = Illuminate\Support\Facades\DB::table('loans as l')
                                ->join('loan_days as ld', 'ld.loan_id', 'l.id')
                                ->join('people as p', 'p.id', 'l.people_id')

                                ->where('l.deleted_at', null)
                                ->where('ld.deleted_at', null)

                                ->where('l.debt', '>', 0)

                                ->where('ld.debt', '>', 0)
                                ->where('ld.late', 1)
                                ->where('l.id', $item->loan_id)
                                ->select(
                                    DB::raw("SUM(ld.late) as diasAtrasado"), DB::raw("SUM(ld.debt) as montoAtrasado")
                                )
                                ->first();
                                if ($item->loan_id== 2348) {
                                    // dump($atras);
                                }
                    @endphp

                    @if ($day || $atras->montoAtrasado > 0)
                        <tr style="text-align: center">
                            <td>{{ $count }}</td>
                            <td style="text-align: center"><b>{{ $item->code}}</b></td>
                            <td style="text-align: left">{{ $item->last_name1}} {{ $item->last_name2}} {{ $item->first_name}} <br> <small>{{ $item->ci }}</small> </td>
                            <td style="text-align: center">
                                @if ($item->cell_phone)
                                    {{ $item->cell_phone }}
                                @elseif($item->phone)
                                    {{ $item->phone }}
                                @endif
                            </td>
                            <td>
                                @php
                                    $dias = App\Models\LoanDay::where('loan_id', $item->loan_id)->get();
                                    $inicio = $dias->sortBy('date')->first()->date;
                                    $fin = $dias->sortByDesc('date')->first()->date;
                                @endphp
                                @if (date('Y', strtotime($inicio)) == date('Y', strtotime($fin)))
                                {{ date('d', strtotime($inicio)) }}/{{ $months[intval(date('m', strtotime($inicio)))] }} al {{ date('d', strtotime($fin)) }}/{{ $months[intval(date('m', strtotime($fin)))] }} de {{ date('Y', strtotime($fin)) }}
                                @else
                                {{ date('d', strtotime($inicio)) }}/{{ $months[intval(date('m', strtotime($inicio)))] }}/{{ date('Y', strtotime($inicio)) }} al {{ date('d', strtotime($fin)) }}/{{ $months[intval(date('m', strtotime($fin)))] }}/{{ date('Y', strtotime($fin)) }}
                                @endif
                            </td>
                            <td style="text-align: right"><b>{{ $day? number_format($day->amount,2, ',', '.'):'SN' }}</b></td>
                            <td @if($atras->montoAtrasado > 0)                                     
                                    @if ($atras->diasAtrasado > 0 && $atras->diasAtrasado <= 5)
                                        style="text-align: right; background-color: #F4DAD7" 
                                    @endif
                                    @if ($atras->diasAtrasado >= 6 && $atras->diasAtrasado <= 10)
                                        style="text-align: right; background-color: #EEAEA7" 
                                    @endif
                                    @if ($atras->diasAtrasado >= 11)
                                        style="text-align: right; background-color: #E1786C" 
                                    @endif
                                @else 
                                    style="text-align: right"
                                @endif>
                                {{$atras->diasAtrasado?$atras->diasAtrasado:'SN'}}
                            </td>
                            <td @if($atras->montoAtrasado > 0)                                     
                                    @if ($atras->diasAtrasado > 0 && $atras->diasAtrasado <= 5)
                                        style="text-align: right; background-color: #F4DAD7" 
                                    @endif
                                    @if ($atras->diasAtrasado >= 6 && $atras->diasAtrasado <= 10)
                                        style="text-align: right; background-color: #EEAEA7" 
                                    @endif
                                    @if ($atras->diasAtrasado >= 11)
                                        style="text-align: right; background-color: #E1786C" 
                                    @endif
                                @else 
                                    style="text-align: right"
                                @endif>
                                {{$atras->montoAtrasado?number_format($atras->montoAtrasado,2,',','.'):'SN' }}
                            </td>      
                            
                        </tr>
                        @php
                            $count++;
                            if($day){
                                $pago_diario += $day->amount;
                            }
                        @endphp
                    @endif
                @endif
               
            @empty
                <tr style="text-align: center">
                    <td colspan="8">No se encontraron registros.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="text-align: right"><b>TOTAL</b></td>
                <td style="text-align: right"><b>Bs. {{ $pago_diario }}</b></td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

@endsection

@section('css')
    <style>
        table, th, td {
            border-collapse: collapse;
        }
          
    </style>
@stop