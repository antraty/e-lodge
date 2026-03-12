# Script pour résoudre automatiquement les conflits Git
$files = @(
    "app\Http\Controllers\ClientController.php",
    "app\Http\Controllers\ReservationController.php",
    "app\Models\Reservation.php",
    "app\Models\Client.php",
    "resources\views\dashboard.blade.php",
    "resources\views\layouts\app.blade.php",
    "resources\views\clients\index.blade.php",
    "resources\views\clients\edit.blade.php",
    "resources\views\clients\create.blade.php",
    "resources\views\reservations\index.blade.php",
    "resources\views\reservations\edit.blade.php",
    "resources\views\reservations\create.blade.php"
)

foreach ($file in $files) {
    if (Test-Path $file) {
        Write-Host "Résolution de $file..."
        $content = Get-Content $file -Raw
        
        # Supprimer tout entre <<<<<<< HEAD et ======= (inclus)
        # Garder tout entre ======= et >>>>>>> (exclus)
        $pattern = '<<<<<<< HEAD.*?======='
        $content = $content -replace $pattern, ''
        
        # Supprimer les marqueurs >>>>>>>
        $content = $content -replace '>>>>>>> [^\r\n]+[\r\n]+', ''
        
        # Sauvegarder le fichier corrigé
        Set-Content -Path $file -Value $content -NoNewline
        Write-Host "  -> Corrigé!"
    }
}

Write-Host "`nTous les conflits ont été résolus!"
