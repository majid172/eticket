<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$output = "";
$schedules = App\Models\ScheduleBus::with(['schedule.route', 'bus.company'])->get();
foreach ($schedules as $s) {
    $r = $s->schedule->route ?? null;
    $source = $r ? $r->source_city : 'N/A';
    $dest = $r ? $r->destination_city : 'N/A';
    $date = $s->schedule ? $s->schedule->travel_date : 'N/A';
    $schStatus = $s->schedule ? $s->schedule->status : 'N/A';
    $output .= "$source to $dest on $date | Sch status: $schStatus | Bus status: {$s->status} | Seats: {$s->available_seats}\n";
}
file_put_contents('schedules_output.txt', $output);
