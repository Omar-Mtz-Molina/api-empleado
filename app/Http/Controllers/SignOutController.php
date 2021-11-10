<?php

namespace App\Http\Controllers\Auth;

namespace App\Http\Controllers;

class SignOutController extends Controller
{
    //
    public function __invoke()
    {
        auth()->logout();
    }
}
