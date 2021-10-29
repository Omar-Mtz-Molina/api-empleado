<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InfoController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

    public function __invoke(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'email' => $user->email,
            'name' => $user->name,
            'employee_code' => $user->employee_code,
        ]);

    }
}
