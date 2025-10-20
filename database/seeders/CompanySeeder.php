<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::updateOrCreate(
            ['email' => 'arona@email.com'],
            [
                'company_name' => 'Arona Technologies',
                'phone_number' => '+977 9800000000',
            ]);

        Company::factory(10)->create();
    }
}
