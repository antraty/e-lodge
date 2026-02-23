<?php

namespace App\Http\Controllers;

use App\Models\Chambre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

class ChambreController extends Controller
{
    use RefreshDatabase;
    public function create(){
        return view('chambres.create');
    }

    public function store(Request $request){
        $validate = $request->validate([
            'numero'=>'required',
            'type'=>'required',
            'status'=>'required',
            'prix'=>'required',
        ]);


        Chambre::create($validate);
    }
    
}
