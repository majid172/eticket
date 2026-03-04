<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Update schedule bus status
\App\Models\ScheduleBus::where('status', '!=', 'scheduled')->update(['status' => 'scheduled']);

// Update travel date to today
\App\Models\Schedule::whereNotNull('id')->update(['travel_date' => date('Y-m-d')]);

echo "Updated database mock data to be searchable today.\n";
