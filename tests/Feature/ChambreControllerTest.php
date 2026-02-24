<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChambreControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

     /** Teste l'affichage du formulaire de création */
    public function test_create_affiche_le_formulaire()
    {
        $response = $this->get(route('chambres.create'));
        $response->assertStatus(200);
        $response->assertSee('Créer une nouvelle chambre');
    }

    /** Teste l'enregistrement d'une chambre */
    public function test_store_enregistre_une_chambre()
    {
        $data = [
            'numero' => '101',
            'type' => 'simple',
            'status' => 'disponible',
            'prix' => 50000,
        ];

        $response = $this->post(route('chambres.store'), $data);

        $response->assertRedirect(route('chambres.create'));
        $this->assertDatabaseHas('chambres', $data);
    }

    /** Teste l'affichage de la liste des chambres */
    public function test_index_afficher_la_liste_des_chambres(){
        $response = $this->get(route('chambres.index'));
        $response->assertStatus(200);
        $response->assertSee('Liste des chambres');
    }

    /** Teste l'affichage du formulaire de modification */
    public function test_edit_modifier_une_chambre(){
        $chambre = \App\Models\Chambre::create([
        'numero' => '101',
        'type' => 'simple',
        'status' => 'disponible',
        'prix' => 50000,
        ]);
        $response = $this->get(route('chambres.edit', $chambre->id));
        $response->assertStatus(200);
        $response->assertSee('Modifier la chambre');
    }

    /** Teste la suppression d'une chambre */
    public function test_delete_supprimer_une_chambre(){
        $chambre = \App\Models\Chambre::create([
            'numero'=>'101',
            'type'=>'simple',
            'status'=>'disponible',
            'prix'=>50000,
        ]);
        $response = $this->delete(route('chambres.destroy', $chambre->id));

        $response->assertRedirect(route('chambres.index'));

        $this->assertDatabaseMissing('chambres', [
            'id' => $chambre->id,
        ]);
    }
}
