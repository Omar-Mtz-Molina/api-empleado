<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SoapClient;

class PayrollController extends Controller
{
    public function getMonths(Request $request)
    {
        $end = strtotime(date("Y-m-01"));
        $start = $month = strtotime("-12 months", $end);
        $data_arr = array();
        $fortnight_1 = 1;
        $fortnight_2 = 2;
        while ($month < $end) {
            $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
            $data = date("m", $month);
            $year = date("Y", $month);
            $month_name = $meses[$data - 1];
            $data_arr[] = array("month" => $data, "year" => $year, "month_name" => $month_name, "fortnight_1" => $fortnight_1, "fortnight_2" => $fortnight_2);

            $month = strtotime("+1 month", $month);

        }
        return response()->json($data_arr);
    }

    public function getPayroll(Request $request)
    {
        $user = auth()->user()->employee_code;
        $fortnight = $request->fortnight;
        $month = $request->month;
        $year = $request->year;
        $type = $request->type;
        try {
            $servicio = "http://172.16.171.10/WebServices/NomIusa_Pro_ExtraeRecibo/ExtraeRecibo.asmx?WSDL";
            $parametros = array();
            $parametros['NOMEMP'] = "$user";
            $parametros['NOMQUI'] = "$fortnight";
            $parametros['NOMMES'] = "$month";
            $parametros['NOMANI'] = "$year";
            $client = new SoapClient($servicio, array('cache_wsdl' => WSDL_CACHE_NONE, 'trace' => true));
            $result = $client->Vb_ExtraeRecibo($parametros);
            $result = obj2array($result);
            $noticias = $result['Vb_ExtraeReciboResult']['MyResultData'];
            $collection2 = collect($noticias);
        } catch (Exception $e) {
            trigger_error($e->getMessage(), E_USER_WARNING);
        }
        if ($type == 'pdf') {
            $data = $collection2['NOMPDF'];
        } else if ($type == 'xml') {
            $data = $collection2['NOMXML'];
        }
        return response()->json(
            $data
        );
    }
}
