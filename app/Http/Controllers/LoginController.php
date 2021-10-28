<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SoapClient;

class LoginController extends Controller
{
    //
    public function __invoke(Request $request)
    {
        if (!$token = auth()->attempt($request->only('employee_code', 'password'))) {
            return response(null, 401);
        }
        return response()->json(compact('token'));
    }
}
