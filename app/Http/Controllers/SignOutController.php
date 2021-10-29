<?php

namespace App\Http\Controllers\Auth;

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SignOutController extends Controller
{
    //
    public function __invoke(){
        auth()->logout();
    }
}
