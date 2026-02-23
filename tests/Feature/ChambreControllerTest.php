<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChambreControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_create_affiche_le_formulaire()
    {
        $response = $this->get(route('chambres.create'));
        $response->assertStatus(200);
        $response->assertSee('Créer une nouvelle chambre');
    }

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
}
