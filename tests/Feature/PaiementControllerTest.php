<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaiementControllerTest extends TestCase
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

    public function test_create_affiche_le_formulaire_de_paiement(){
        $response = $this->get(route('paiements.create'));
        $response->assertStatus(200);
        $response->assertSee('Effectuer un paiement');
    }

    public function test_store_enregistre_un_paiement(){
        $reservation = \App\Models\Reservation::factory()->create();
        $facture = \App\Models\Facture::factory()->create([
            'reservation_id'=> $reservation->id,
            'montant_total' => 758.78,
        ]);

        $data = [
            'date_paiement' => '2020-03-10',
            'mode_paiement' => 'espèces',
            'reservation_id' => $reservation->id,
            'facture_id'=> $facture->id,
        ];
        $response = $this->post(route('paiements.store'), $data);
        $response->assertRedirect(route('paiements.index'));
        $this->assertDatabaseHas('paiements', [
            'date_paiement' => '2020-03-10',
            'montant' => 758.78, 
            'mode_paiement' => 'espèces',
            'reservation_id' => $reservation->id,
            'facture_id'=> $facture->id,
        ]);
    }

    public function test_index_affiche_tous_les_paiements(){
        $reservation = \App\Models\Reservation::factory()->create();
        $facture = \App\Models\Facture::factory()->create();
        \App\Models\Paiement::factory()->create([
            'date_paiement'=> '2024-03-03',
            'montant'=> 100,
            'mode_paiement'=>'espèces',
            'reservation_id'=>$reservation->id,
            'facture_id'=>$facture->id,
        ]);
        $response = $this->get(route('paiements.index'));
        $response->assertStatus(200);
        $response->assertSee('2024-03-03');
        $response->assertSee('100');
        $response->assertSee('espèces');
    }

    public function test_edit_affiche_le_formulaire_de_modification(){
        $reservation = \App\Models\Reservation::factory()->create();
        $facture = \App\Models\Facture::factory()->create();
        $paiement = \App\Models\Paiement::factory()->create([
            'reservation_id' => $reservation->id,
            'facture_id' => $facture->id,
        ]);
        $response = $this->get(route('paiements.edit', $paiement->id));
        $response->assertStatus(200);
        $response->assertSee('Modifier un paiement');
    }

    public function test_update_modifie_un_paiement(){
        $reservation = \App\Models\Reservation::factory()->create();
        $facture = \App\Models\Facture::factory()->create();
        $paiement = \App\Models\Paiement::factory()->create([
            'reservation_id' => $reservation->id,
            'facture_id' => $facture->id,
        ]);

        $nouvelleReservation = \App\Models\Reservation::factory()->create();
        $nouvelleFacture = \App\Models\Facture::factory()->create();
        $this->assertDatabaseHas('factures', ['id' => $nouvelleFacture->id]);

        $data = [
            'date_paiement' => '2024-03-05',
            'montant' => 200,
            'mode_paiement' => 'carte',
            'reservation_id' => $nouvelleReservation->id,
            'facture_id' => $nouvelleFacture->id,
        ];
        $response = $this->put(route('paiements.update', $paiement->id), $data);

        $response->assertRedirect(route('paiements.index'));
        $this->assertDatabaseHas('paiements', [
            'id' => $paiement->id,
            'montant' => 200,
            'mode_paiement' => 'carte',
        ]);
    }
    public function test_destroy_supprime_un_paiement(){
        $paiement = \App\Models\Paiement::factory()->create();

        $response = $this->delete(route('paiements.destroy', $paiement->id));

        $response->assertRedirect(route('paiements.index'));
        $this->assertDatabaseMissing('paiements', [
            'id' => $paiement->id,
        ]);
    }
    
    public function test_store_calcul_automatiquement_le_montant()
    {
        $reservation = \App\Models\Reservation::factory()->create();
        $facture = \App\Models\Facture::factory()->create([
            'reservation_id' => $reservation->id,
            'montant_total' => 500,
        ]);

        $data = [
            'date_paiement' => '2024-03-05',
            'mode_paiement' => 'carte',
            'reservation_id' => $reservation->id,
            'facture_id' => $facture->id,
        ];

        $response = $this->post(route('paiements.store'), $data);

        $response->assertRedirect(route('paiements.index'));
        $this->assertDatabaseHas('paiements', [
            'facture_id' => $facture->id,
            'montant' => 500,
        ]);
    }
}
