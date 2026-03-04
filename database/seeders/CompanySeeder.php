<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        // user_id 2 = Green Express Operator, user_id 3 = City Lines Operator
        DB::table('companies')->insert([
            [
                'user_id'       => 2,
                'company_name'  => 'Green Express',
                'trade_license' => 'TL-2024-001',
                'contact_phone' => '01711111111',
                'contact_email' => 'info@greenexpress.com',
                'address'       => 'Motijheel, Dhaka',
                'status'        => 'approved',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'user_id'       => 3,
                'company_name'  => 'City Lines',
                'trade_license' => 'TL-2024-002',
                'contact_phone' => '01722222222',
                'contact_email' => 'info@citylines.com',
                'address'       => 'Mirpur, Dhaka',
                'status'        => 'approved',
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ]);
    }
}
