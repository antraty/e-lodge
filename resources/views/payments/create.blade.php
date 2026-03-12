@extends('layouts.app')

@section('content')
    <h3>Enregistrer un paiement</h3>

    <form method="POST" action="{{ route('payments.store') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Réservation</label>
            @if($reservation)
                <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">
                <p>#{{ $reservation->id }} - {{ $reservation->client->first_name ?? '' }} {{ $reservation->client->last_name ?? '' }}</p>
                <p>Montant dû: {{ number_format($reservation->total_price,2) }} MGA</p>
                <div class="mb-3">
                    <label class="form-label">Montant</label>
                    <input type="number" step="0.01" name="amount" class="form-control" value="{{ $reservation->total_price }}">
                </div>
            @else
                <select name="reservation_id" class="form-select">
                    @foreach(\App\Models\Reservation::with('client')->limit(100)->get() as $r)
                            <option value="{{ $r->id }}">#{{ $r->id }} - {{ $r->client->first_name ?? '' }} {{ $r->client->last_name ?? '' }} ({{ number_format($r->total_price,2) }} MGA)</option>
                    @endforeach
                </select>
            @endif
        </div>

        <div class="mb-3">
            <label class="form-label">Montant payé</label>
            <input type="number" step="0.01" name="paid_amount" class="form-control" value="0.00" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Méthode</label>
            <select name="method" id="method" class="form-select">
                <option value="cash">Espèce</option>
                <option value="card">Carte</option>
                <option value="mobile_money">Mobile money</option>
                <option value="bank_transfer">Virement</option>
                <option value="check">Chèque</option>
            </select>
        </div>

        <div class="mb-3 d-none" id="card-info">
            <label class="form-label">Numéro de carte</label>
            <input type="text" name="card_number" id="card_number" class="form-control" placeholder="XXXX XXXX XXXX XXXX" inputmode="numeric" maxlength="19">
        </div>

        <div class="mb-3 d-none" id="mobile-info">
            <label class="form-label">Numéro mobile</label>
            <input type="text" name="mobile_number" class="form-control" placeholder="+261 34 12 345 67">
        </div>

        <div class="mb-3">
            <label class="form-label">Référence (optionnel)</label>
            <input type="text" name="reference_number" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control"></textarea>
        </div>

        <button class="btn btn-primary">Enregistrer</button>
    </form>

    <script>
        document.getElementById('method').addEventListener('change', function() {
            var card = document.getElementById('card-info');
            var mobile = document.getElementById('mobile-info');
            card.classList.add('d-none');
            mobile.classList.add('d-none');
            if (this.value === 'card') {
                card.classList.remove('d-none');
            } else if (this.value === 'mobile_money') {
                mobile.classList.remove('d-none');
            }
        });

            // automatically add spaces to card number in groups of 4 digits
            var cardInput = document.getElementById('card_number');
            if (cardInput) {
                cardInput.addEventListener('input', function(e){
                    // preserve cursor position
                    var cursor = this.selectionStart;
                    var value = this.value.replace(/\D/g, ''); // digits only
                    var parts = [];
                    for (var i = 0; i < value.length; i += 4) {
                        parts.push(value.substring(i, i+4));
                    }
                    var formatted = parts.join(' ');
                    this.value = formatted;
                    // attempt to restore cursor near previous position
                    this.selectionStart = this.selectionEnd = cursor;
                });
            }
    </script>
@endsection
