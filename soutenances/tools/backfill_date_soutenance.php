<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    DB::statement("UPDATE soutenances SET date_soutenance = DATE(created_at) WHERE date_soutenance IS NULL");
    echo "Backfill completed: date_soutenance updated from created_at\n";
} catch (Throwable $e) {
    echo "Backfill failed: " . $e->getMessage() . "\n";
    exit(1);
}
