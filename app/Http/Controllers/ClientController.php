<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ClientController extends Controller
{
    public function create(){
        return view('clients.create');
    }

    public function store(Request $request){
        $validate = $request->validate([
            'nom'=>'required',
            'prenom'=>'required',
            'telephone'=>'required',
            'adresse'=>'required'
        ]);
        Client::create($validate);
        return redirect()->route('clients.create');
    }

    public function index(){
        $clients = \App\Models\Client::all();
        return view('clients.index', compact('clients'));
    }
    
    public function edit($id){
        $client = \App\Models\Client::findOrFail($id);
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, $id){
        $validate = $request->validate([
            'nom'=>'required',
            'prenom'=>'required',
            'telephone'=>'required',
            'adresse'=>'required'
        ]);

        $client = \App\Models\Client::findOrFail($id);

         $client->update($validate);

        return redirect()->route('clients.index')->with('success', 'Client modifié avec succès');
    }

    public function destroy($id){
        $client = \App\Models\Client::findOrFail($id);
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'client retiré avec succèss');
    }
}
