<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RouteSeeder extends Seeder
{
    public function run(): void
    {
        $routes = [
            ['source_city' => 'Dhaka',     'destination_city' => 'Chittagong', 'distance_km' => 250, 'status' => 1],
            ['source_city' => 'Dhaka',     'destination_city' => 'Sylhet',     'distance_km' => 240, 'status' => 1],
            ['source_city' => 'Dhaka',     'destination_city' => 'Rajshahi',   'distance_km' => 260, 'status' => 1],
            ['source_city' => 'Dhaka',     'destination_city' => 'Khulna',     'distance_km' => 330, 'status' => 1],
            ['source_city' => 'Chittagong','destination_city' => 'Cox\'s Bazar','distance_km' => 150, 'status' => 1],
        ];

        foreach ($routes as &$route) {
            $route['created_at'] = now();
            $route['updated_at'] = now();
        }

        DB::table('routes')->insert($routes);
    }
}
