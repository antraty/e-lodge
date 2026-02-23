<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BonjourController extends Controller
{
    public function index()
    {
        return view('bonjour');
    }
}
