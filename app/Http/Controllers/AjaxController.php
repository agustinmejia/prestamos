<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\LoanDay;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;
use App\Models\Cashier;
use App\Models\CashierMovement;
use App\Models\Garment;
use App\Models\GarmentsMonth;
use Illuminate\Support\Facades\Auth;

class AjaxController extends Controller
{
    // para poner en retrazado de forma automatica
    public function late()
    {
        $date = date("Y-m-d");
        // return $date;
        $data = LoanDay::where('deleted_at', null)
            ->where('deleted_at', null)
            ->where('debt', '>', 0)
            ->where('late', 0)
            ->where('date', '<', $date)
            ->get();
        foreach($data as $item)
        {
            $item->update(['late'=>1]);
        }
        return true;
    }
    public function lateGarment()
    {
        $date = date("Y-m-d");
        $data = Garment::where('deleted_at', null)->where('status', 'entregado')->get();
        foreach($data as $item)
        {
            $garment = Garment::where('id', $item->id)->first();
            $month = GarmentsMonth::where('garment_id', $garment->id)->where('deleted_at', null)->get();
            $monthMax = $month->max();
            if($date > $monthMax->finish)
            {
                $monthFirst =$month->first();
                $monthCant = $month->count()+1;
                $date = date("Y-m-d",strtotime($monthFirst->start));
                $diaInicio = date("d",strtotime($date));
                $mesInicio = date("Y-m",strtotime($date));
                $mesFin = date("Y-m-d",strtotime($mesInicio."+ ".$monthCant." month"));
                $anioSig = date("Y",strtotime($mesFin));
                $mesSig = date("m",strtotime($mesFin));
                $cantidadDiasFin = cal_days_in_month(CAL_GREGORIAN, $mesSig, $anioSig);

                if($diaInicio <= $cantidadDiasFin) {
                    $fechaFin = $anioSig.'-'.$mesSig.'-'.$diaInicio;
                } else {
                    $fechaFin = $anioSig.'-'.$mesSig.'-'.$cantidadDiasFin;
                }

                if($diaInicio == 31 && $cantidadDiasFin == 31) {
                    $fechaFin = $anioSig.'-'.$mesSig.'-30';
                }                

                GarmentsMonth::create([
                    'garment_id'=>$garment->id,
                    'start'=>$monthMax->finish,
                    'finish'=>$fechaFin,
                    'amount'=>$garment->amountPorcentage,
                    'status'=>'pendiente'
                ]);
                $garment->update(['amountTotal'=>$garment->amountTotal+$garment->amountPorcentage]);

                $month = GarmentsMonth::where('garment_id', $garment->id)->where('deleted_at', null)->where('status', 'pendiente')->get();
                $garment->update(['monthCant' => $month->count()]);
            }
        }
        return true;
    }

    public function notificationLate()
    {
        $data = DB::table('loans as l')
            ->join('loan_days as ld', 'ld.loan_id', 'l.id')
            ->join('people as p', 'p.id', 'l.people_id')
            ->where('l.deleted_at', null)
            ->where('ld.late', 1)
            ->where('ld.debt', '>', 0)
            ->whereDate('l.notificationDate', '<', date('Y-m-d'))
            ->select('l.id as loan', 'l.dateDelivered', 'p.id as people', 'p.first_name', 'p.last_name1', 'p.last_name2', 'p.cell_phone', 'p.ci', 'l.code')
            ->groupBy('loan')
            ->limit(50)
            ->get();
        foreach($data as $item)
        {
            $day = LoanDay::where('loan_id', $item->loan)->where('deleted_at', null)->where('late', 1)->where('debt', '>', 0)->get();
            $cadena = '';
            $i=1;
            $cant = count($day);
            $amountTotal =0;
            $amountDebt =0;
            foreach($day as $iten)
            {
                $cadena=$cadena.''.Carbon::parse($iten->date)->format('d/m/Y').'                     '.number_format($iten->amount-$iten->debt,2).'         '.$iten->amount.($i!=$cant?'%0A':'');
                $i++;
                $amountTotal+=$iten->amount;
                $amountDebt+=($iten->amount-$iten->debt);
            }
            // 
            try {
$message = '*COMPROBANTE DE DEUDA PENDIENTE*

CODIGO: '.$item->code.'                      
FECHA: '.Carbon::parse($item->dateDelivered)->format('d/m/Y').'
BENEFICIARIO: '.$item->last_name1.' '.$item->last_name2.' '.$item->first_name.'
CI: '.$item->ci.'
    
        *DETALLE TOTAL A PAGAR*
*DIAS ATRASADOS* | *CUOTAS* | *DEUDA*
___________________________________%0A'.
                    $cadena.'
___________________________________
TOTAL (BS)                  '.number_format($amountDebt,2).'         '.number_format($amountTotal,2).'
    
    
Gracias🤝😊';

                if (setting('servidores.whatsapp') && setting('servidores.whatsapp-session')) {
                    Http::post(setting('servidores.whatsapp').'/send?id='.setting('servidores.whatsapp-session'), [
                        'phone' => '591'.$item->cell_phone,
                        'text' => $message,
                        'image_url' => '',
                    ]);
                }

            } catch (\Throwable $th) {
                //throw $th;
            }
            $aux = Loan::where('id', $item->loan)->first();
            $aux->update(['notificationDate'=>date('Y-m-d'), 'notificationQuantity'=>$aux->notificationQuantity+1]);
            sleep(60);


        }
        return true;
    }


    public function balanceCashier($cashier_id)
    {
        $cashier = Cashier::with(['movements' => function($q){
            $q->where('deleted_at', NULL);
        }])
        ->where('id', $cashier_id)
        ->where('status', '=', 'abierta')
        ->where('deleted_at', NULL)->first();

        

        $balance = 0;
        if($cashier)
        {
            $balance = $cashier->movements->where('type', 'ingreso')->where('deleted_at', NULL)->sum('balance');        
        }
        return $balance;
    }
}
