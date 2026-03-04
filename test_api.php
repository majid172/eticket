<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$request = \Illuminate\Http\Request::create('/api/schedules', 'GET', [
    'source_city' => 'Dhaka',
    'destination_city' => 'Tangail',
    'travel_date' => date('Y-m-d')
]);
$response = app()->handle($request);
echo $response->getContent();
