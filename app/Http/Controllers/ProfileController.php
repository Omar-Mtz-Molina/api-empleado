<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SoapClient;

class ProfileController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    public function getProfile(Request $request)
    {
        $code = auth()->user()->employee_code;
        try {
            $servicio1 = "http://172.16.171.10/webservices/POREMP_Pro_Datos_Portalemp/Datos_Portalemp.asmx?WSDL";
            $parametros1 = array();
            $parametros1['P_PERNR'] = $code;
            $client1 = new SoapClient($servicio1, array('cache_wsdl' => WSDL_CACHE_NONE, 'trace' => true));
            $result1 = $client1->Vb_Datos_Portalemp($parametros1);
            $result1 = obj2array($result1);
            $noticias1 = $result1['Vb_Datos_PortalempResult']['MyResultData'];
            $collection = collect($noticias1)->first();
            return response()->json(
                $collection
            );
        } catch (Exception $e) {
            trigger_error($e->getMessage(), E_USER_WARNING);
        }
    }
}
