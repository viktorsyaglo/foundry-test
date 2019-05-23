<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    function main(Request $request)
    {
        Artisan::call("task1");
        return view('output')->with('output', Artisan::output());
    }
}
