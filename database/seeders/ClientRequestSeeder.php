<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClientRequest;

class ClientRequestSeeder extends Seeder
{
    public function run()
    {
        $clientRequests = [
            [
                'client_id' => 3,
                'service_id' => 1,
                'store_id' => 1,
                'status' => 'pending',
                'date' => '2021-01-01',
                'time' => '10:00',
                'address' => 'الرياض',
                'comment' => 'نظافة المنزل',
                'visit_id' => null,
            ],
            [
                'client_id' => 3,
                'service_id' => 2,
                'store_id' => 1,
                'status' => 'pending',
                'date' => '2021-01-01',
                'time' => '10:00',
                'address' => 'الرياض',
                'comment' => 'ازالة الحشرات',
                'visit_id' => null,
            ],
            [
                'client_id' => 3,
                'service_id' => 3,
                'store_id' => 1,
                'status' => 'pending',
                'date' => '2021-01-01',
                'time' => '10:00',
                'address' => 'الرياض',
                'comment' => 'صيانة الكهرباء',
                'visit_id' => null,
            ],
            [
                'client_id' => 3,
                'service_id' => 1,
                'store_id' => 1,
                'status' => 'pending',
                'date' => '2021-01-01',
                'time' => '10:00',
                'address' => 'الرياض',
                'comment' => 'نظافة المنزل',
                'visit_id' => null,
            ],
            [
                'client_id' => 3,
                'service_id' => 2,
                'store_id' => 1,
                'status' => 'pending',
                'date' => '2021-01-01',
                'time' => '10:00',
                'address' => 'الرياض',
                'comment' => 'ازالة الحشرات',
                'visit_id' => null,
            ],
            [
                'client_id' => 3,
                'service_id' => 3,
                'store_id' => 1,
                'status' => 'pending',
                'date' => '2021-01-01',
                'time' => '10:00',
                'address' => 'الرياض',
                'comment' => 'صيانة ��لكهرباء',
                'visit_id' => null,
            ],
        ];

        foreach ($clientRequests as $request) {
            ClientRequest::query()->insert($request);
        }
    }
}
