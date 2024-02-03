
<div class="col-md-12 text-right">

    {{-- <button type="button" onclick="report_excel()" class="btn btn-success"><i class="fa-solid fa-file-excel"></i> Excel</button> --}}
    <button type="button" onclick="report_print()" class="btn btn-dark"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
    @php
        $meses = ['', 'ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
    @endphp
</div>
<div class="col-md-12">
<div class="panel panel-bordered">
    <div class="panel-body">
        <div class="table-responsive">
            <table id="dataStyle" style="width:100%"  class="table table-bordered table-striped table-sm">
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
                        <th style="text-align: center; width:70px">DIAS</th>
                        <th style="text-align: center; width:70px">TOTAL A PAGAR</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $count = 1;
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
                                    <td style="text-align: center"><small>{{ $item->code}}</small></td>
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
                                        {{ date('d', strtotime($inicio)) }}/{{ $meses[intval(date('m', strtotime($inicio)))] }} al {{ date('d', strtotime($fin)) }}/{{ $meses[intval(date('m', strtotime($fin)))] }} de {{ date('Y', strtotime($fin)) }}
                                        @else
                                        {{ date('d', strtotime($inicio)) }}/{{ $meses[intval(date('m', strtotime($inicio)))] }}/{{ date('Y', strtotime($inicio)) }} al {{ date('d', strtotime($fin)) }}/{{ $meses[intval(date('m', strtotime($fin)))] }}/{{ date('Y', strtotime($fin)) }}
                                        @endif
                                    </td>
                                    <td style="text-align: right"><b>{{ $day?'Bs. '. number_format($day->amount,2, ',', '.'):'SN' }}</b></td>
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
                                    <td
                                        @if($atras->montoAtrasado > 0)                                     
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
                                        {{$atras->montoAtrasado?'Bs. '.number_format($atras->montoAtrasado,2,',','.'):'SN' }}
                                    </td>
                                </tr>
                                @php
                                    $count++;                        
                                @endphp
                            @endif
                        @endif
                    @empty
                        <tr style="text-align: center">
                            <td colspan="8">No se encontraron registros.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<script>
$(document).ready(function(){

})
</script>