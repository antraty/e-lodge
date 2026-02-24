<?php

namespace App\Http\Controllers;

use App\Models\Chambre;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ChambreController extends Controller
{
    use RefreshDatabase;

    // Affiche le formulaire de création d'une chambre.
    public function create(){
        return view('chambres.create');
    }

    // Enregistre une nouvelle chambre.
    public function store(Request $request){
        $validate = $request->validate([
            'numero'=>'required',
            'type'=>'required',
            'status'=>'required',
            'prix'=>'required',
        ]);


        Chambre::create($validate);

        return redirect()->route('chambres.create');
    }

    // Affiche la liste des chambres.
    public function index(){
        $chambres = \App\Models\Chambre::all();
        return view('chambres.index', compact('chambres'));
    }

    // Affiche le formulaire d'edition d'un chambre
    public function edit($id){
        $chambre = \App\Models\Chambre::findOrFail($id);
        return view('chambres.edit',  compact('chambre'));
    }

    // Met à jour une chambre existante
    public function update(Request $request, $id){
        $validate = $request->validate([
            'numero'=>'required',
            'type'=>'required',
            'status'=>'required',
            'prix'=>'required',
        ]);

        $chambre = \App\Models\Chambre::findOrFail($id);

        $chambre->update($validate);

        return redirect()->route('chambres.index')->with('success', 'Chambre modifié avec succès');
    }

    // Supprime une chambre
    public function destroy($id){
        $chambre = \App\Models\Chambre::findOrFail($id);
        $chambre->delete();
        return redirect()->route('chambres.index')->with('success', 'Chambre supprimée avec succèss');
    }
}
