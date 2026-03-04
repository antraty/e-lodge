<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FactureControllerTest extends TestCase
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

    public function test_generer_cree_une_facture_pour_une_reservation()
    {
        $chambre = \App\Models\Chambre::factory()->create(['prix' => 100]);
        $reservation = \App\Models\Reservation::factory()->create(['chambre_id' => $chambre->id,
        ]);

        $response = $this->post(route('factures.generer', $reservation->id));

        $facture = \App\Models\Facture::where('reservation_id', $reservation->id)->first();
        $response->assertRedirect(route('factures.show', $facture->id));

        $this->assertDatabaseHas('factures', [
            'reservation_id' => $reservation->id,
            'montant_total' => 100, 
        ]);
    }
}
