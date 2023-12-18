<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApartmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('apartment_types')->insert([
            [
                'name' => 'Apartment',
                'description' => 'Multi-storey apartments with a large number of rooms, '.
                    'often serving families, high prices, long-term stays',
            ],
            [
                'name' => 'Mini Apartment',
                'description' => 'Mini apartments with a small number of floors, '.
                    'built for business purposes and rented in large quantities',
            ],
            [
                'name' => "Motel",
                'description' => 'Small-scale boarding houses, suitable for short-term rentals, '.
                    'low prices, often with few floors',
            ],
            [
                'name' => 'Whole House',
                'description' => 'Whole house but the owner does not use it but rents it out, '.
                    'suitable for families and small groups',
            ],
        ]);
    }
}
