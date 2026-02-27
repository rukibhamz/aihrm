<?php

namespace Database\Seeders;

use App\Models\EmploymentStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmploymentStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['name' => 'Intern', 'description' => 'Temporary student or trainee'],
            ['name' => 'Probation', 'description' => 'Undergoing probation period'],
            ['name' => 'Confirmed', 'description' => 'Full-time confirmed employee'],
            ['name' => 'Contract', 'description' => 'Fixed-term contract employee'],
        ];

        foreach ($statuses as $status) {
            EmploymentStatus::firstOrCreate(['name' => $status['name']], $status);
        }
    }
}
