<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReservationControllerTest extends TestCase
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

    public function test_create_affiche_le_formulaire_de_reservation()
    {
        $response = $this->get(route('reservations.create'));
        $response->assertStatus(200);
        $response->assertSee('Créer une réservation');
    }

    public function test_store_enregistrer_une_reservation(){

        $chambre = \App\Models\Chambre::factory()->create();
        $client = \App\Models\Client::factory()->create();
        $data = [
            'date_debut'=> '2020-04-13',
            'date_fin'=> '2020-04-20',
            'status' => 'confirmé',
            'chambre_id' => $chambre->id,
            'client_id'=> $client->id
        ];
        $response = $this->post(route('reservations.store'), $data);
        $response->assertRedirect(route('reservations.index'));
        $this->assertDatabaseHas('reservations', $data);
    }

    public function test_update_modifie_une_reservation()
    {
        $chambre = \App\Models\Chambre::factory()->create();
        $client = \App\Models\Client::factory()->create();
        $reservation = \App\Models\Reservation::factory()->create([
            'chambre_id' => $chambre->id,
            'client_id' => $client->id,
        ]);

        $newData = [
            'date_debut' => '2024-03-12',
            'date_fin' => '2024-03-18',
            'status' => 'en attente',
            'chambre_id' => $chambre->id,
            'client_id' => $client->id,
        ];

        $response = $this->put(route('reservations.update', $reservation->id), $newData);

        $response->assertRedirect(route('reservations.index'));
        $this->assertDatabaseHas('reservations', $newData);
    }

    public function test_destroy_annule_une_reservation()
    {
        $reservation = \App\Models\Reservation::factory()->create();

        $response = $this->delete(route('reservations.destroy', $reservation->id));

        $response->assertRedirect(route('reservations.index'));
        $this->assertDatabaseMissing('reservations', ['id' => $reservation->id]);
    }

    public function test_store_refuse_si_chambre_deja_reservee()
    {
        $chambre = \App\Models\Chambre::factory()->create();
        $client = \App\Models\Client::factory()->create();

        // Première réservation
        \App\Models\Reservation::factory()->create([
            'chambre_id' => $chambre->id,
            'client_id' => $client->id,
            'date_debut' => '2024-03-10',
            'date_fin' => '2024-03-15',
            'status' => 'confirmée',
        ]);

        // Tentative de réservation sur la même période
        $data = [
            'date_debut' => '2024-03-12', // chevauchement
            'date_fin' => '2024-03-14',
            'status' => 'confirmée',
            'chambre_id' => $chambre->id,
            'client_id' => $client->id,
        ];

        $response = $this->post(route('reservations.store'), $data);
        
        $response->assertStatus(302);
        $response->assertSessionHasErrors('chambre_id');
    }
}
