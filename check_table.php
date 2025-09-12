<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $result = \Illuminate\Support\Facades\DB::select('DESCRIBE users');
    echo "Users table structure:\n";
    foreach ($result as $column) {
        echo "Field: {$column->Field}, Type: {$column->Type}, Null: {$column->Null}, Key: {$column->Key}, Default: {$column->Default}, Extra: {$column->Extra}\n";
    }
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
}
