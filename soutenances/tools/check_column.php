<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\Schema;

$column = $argv[1] ?? 'date_soutenance';
$table = $argv[2] ?? 'soutenances';

$has = Schema::hasColumn($table, $column);

echo "Table $table has column $column? " . ($has ? 'YES' : 'NO') . "\n";
