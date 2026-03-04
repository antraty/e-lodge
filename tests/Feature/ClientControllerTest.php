<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClientControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_create_ajouter_un_client(){
        $response = $this->get(route('clients.create'));
        $response->assertStatus(200);
        $response->assertSee('Ajouter un nouveau client');
    }

    public function test_store_enregister_un_client(){
        $data = [
            'nom'=>'ANDRIANASOA',
            'prenom'=>'Enzo',
            'telephone'=> '0321907970',
            'adresse'=>'Ambohitrarahaba'
        ];

        $response = $this->post(route('clients.store'), $data);
        $response->assertRedirect(route('clients.create'));
        $this->assertDatabaseHas('clients', $data);
    }
    public function test_index_afficher_la_liste_des_clients(){
        $response = $this->get(route('clients.index'));
        $response->assertStatus(200);
        $response->assertSee('Liste des clients');
    }

    public function test_edit_modifier_un_client(){
        $client = \App\Models\Client::create([
            'nom'=>'Andrianantenaina',
            'prenom'=>'Mamisoa',
            'telephone'=>'000000',
            'adresse'=>'Isoraka'
        ]);
        $response = $this->get(route('clients.edit', $client->id));
        $response->assertStatus(200);
        $response->assertSee('Modifier le client');
    }

    public function test_delete_supprimer_un_client(){
        $client = \App\Models\Client::create([
            'nom'=>'Andrianantenaina',
            'prenom'=>'Mamisoa',
            'telephone'=>'000000',
            'adresse'=>'Isoraka',
        ]);
        $response = $this->delete(route('clients.destroy', $client->id));
        $response->assertRedirect(route('clients.index'));
        $this->assertDatabaseMissing('clients', [
            'id' => $client->id,
        ]);
    }

}
