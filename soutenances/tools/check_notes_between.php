<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;

$debut = $argv[1] ?? '2023-01-01';
$fin = $argv[2] ?? '2026-07-06';

try {
    $count = DB::table('soutenances')
        ->whereBetween('date_soutenance', [$debut, $fin])
        ->count();

    echo "Count between $debut and $fin: $count\n\n";

    $rows = DB::table('soutenances')
        ->whereBetween('date_soutenance', [$debut, $fin])
        ->orderBy('date_soutenance', 'asc')
        ->limit(20)
        ->get();

    foreach ($rows as $r) {
        echo sprintf("%s | %s | %s | %s | %s\n", $r->date_soutenance, $r->matricule, $r->annee_univ, $r->note, $r->created_at);
    }

} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
