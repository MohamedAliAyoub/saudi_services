<?php

namespace Database\Seeders;

use App\Enums\VisitTypeEnum;
use App\Models\Visit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class VisitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientId = 3;

        $visits = [
            [
                'store_id' => 1,
                'date' => now()->subDays(10)->format('Y-m-d'),
                'time' => now()->subDays(10)->format('H:i:s'),
                'status' => VisitTypeEnum::PENDING->value,
                'comment' => 'زيارة قديمة في انتظار',
                'employee_id' => 1,
                'service_id' => 1,
                'client_id' => $clientId,
            ],
            [
                'store_id' => 2,
                'date' => now()->subDays(5)->format('Y-m-d'),
                'time' => now()->subDays(5)->format('H:i:s'),
                'status' => VisitTypeEnum::DONE->value,
                'comment' => 'زيارة قديمة مكتملة',
                'employee_id' => 2,
                'service_id' => 2,
                'client_id' => $clientId,
            ],
            [
                'store_id' => 3,
                'date' => now()->addDays(5)->format('Y-m-d'),
                'time' => now()->addDays(5)->format('H:i:s'),
                'status' => VisitTypeEnum::LATE->value,
                'comment' => 'زيارة مستقبلية متأخرة',
                'employee_id' => 3,
                'service_id' => 3,
                'client_id' => $clientId,
            ],
        ];

        foreach ($visits as $visit) {
            Visit::create($visit);
        }
    }
}
