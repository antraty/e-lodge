<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Reservation;

$reservation = Reservation::with('client','room')->first();
if (!$reservation) {
    echo "No reservation found\n";
    exit(1);
}

// Generate PDF using the installed DomPDF facade
try {
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reservations.invoice', ['reservation' => $reservation]);
    $storagePath = storage_path('app/invoices');
    if (!is_dir($storagePath)) {
        mkdir($storagePath, 0755, true);
    }
    $filename = $storagePath . DIRECTORY_SEPARATOR . 'facture_reservation_' . $reservation->id . '.pdf';
    file_put_contents($filename, $pdf->output());
    echo "Saved: " . $filename . "\n";
} catch (Throwable $e) {
    echo "Error generating PDF: " . $e->getMessage() . "\n";
    exit(1);
}
