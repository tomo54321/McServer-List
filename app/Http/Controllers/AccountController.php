<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * New controller instance
     * 
     * @return void
     */
    public function __construct()
    {
        $this->middleware("auth");
    }
}
