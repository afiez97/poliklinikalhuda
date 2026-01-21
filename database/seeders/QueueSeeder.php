<?php

namespace Database\Seeders;

use App\Models\QueueCounter;
use App\Models\QueueKiosk;
use App\Models\QueueType;
use Illuminate\Database\Seeder;

class QueueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createQueueTypes();
        $this->createQueueCounters();
        $this->createQueueKiosks();
    }

    /**
     * Create queue types.
     */
    private function createQueueTypes(): void
    {
        $queueTypes = [
            [
                'code' => 'R',
                'name' => 'Pendaftaran',
                'name_en' => 'Registration',
                'name_zh' => '登记',
                'avg_service_time' => 3,
                'max_queue_size' => 200,
                'priority_ratio' => 3,
                'operating_start' => '08:00',
                'operating_end' => '17:00',
                'display_order' => 1,
            ],
            [
                'code' => 'D1',
                'name' => 'Doktor 1',
                'name_en' => 'Doctor 1',
                'name_zh' => '医生1',
                'avg_service_time' => 10,
                'max_queue_size' => 50,
                'priority_ratio' => 3,
                'operating_start' => '08:30',
                'operating_end' => '17:00',
                'display_order' => 2,
            ],
            [
                'code' => 'D2',
                'name' => 'Doktor 2',
                'name_en' => 'Doctor 2',
                'name_zh' => '医生2',
                'avg_service_time' => 10,
                'max_queue_size' => 50,
                'priority_ratio' => 3,
                'operating_start' => '08:30',
                'operating_end' => '17:00',
                'display_order' => 3,
            ],
            [
                'code' => 'F',
                'name' => 'Farmasi',
                'name_en' => 'Pharmacy',
                'name_zh' => '药房',
                'avg_service_time' => 5,
                'max_queue_size' => 150,
                'priority_ratio' => 3,
                'operating_start' => '08:00',
                'operating_end' => '17:30',
                'display_order' => 4,
            ],
            [
                'code' => 'P',
                'name' => 'Pembayaran',
                'name_en' => 'Payment',
                'name_zh' => '付款',
                'avg_service_time' => 3,
                'max_queue_size' => 150,
                'priority_ratio' => 3,
                'operating_start' => '08:00',
                'operating_end' => '17:30',
                'display_order' => 5,
            ],
            [
                'code' => 'L',
                'name' => 'Makmal',
                'name_en' => 'Laboratory',
                'name_zh' => '化验室',
                'avg_service_time' => 5,
                'max_queue_size' => 50,
                'priority_ratio' => 3,
                'operating_start' => '08:00',
                'operating_end' => '16:30',
                'display_order' => 6,
            ],
        ];

        // Create queue types
        $createdTypes = [];
        foreach ($queueTypes as $type) {
            $createdTypes[$type['code']] = QueueType::firstOrCreate(
                ['code' => $type['code']],
                $type
            );
        }

        // Setup auto-transfer (Doktor -> Farmasi -> Pembayaran)
        if (isset($createdTypes['D1'], $createdTypes['F'])) {
            $createdTypes['D1']->update(['auto_transfer_to' => $createdTypes['F']->id]);
        }
        if (isset($createdTypes['D2'], $createdTypes['F'])) {
            $createdTypes['D2']->update(['auto_transfer_to' => $createdTypes['F']->id]);
        }
        if (isset($createdTypes['F'], $createdTypes['P'])) {
            $createdTypes['F']->update(['auto_transfer_to' => $createdTypes['P']->id]);
        }
    }

    /**
     * Create queue counters.
     */
    private function createQueueCounters(): void
    {
        $counters = [
            // Pendaftaran
            ['queue_type_code' => 'R', 'code' => 'K1', 'name' => 'Kaunter 1', 'name_en' => 'Counter 1', 'location' => 'Lobi Utama'],
            ['queue_type_code' => 'R', 'code' => 'K2', 'name' => 'Kaunter 2', 'name_en' => 'Counter 2', 'location' => 'Lobi Utama'],

            // Doktor 1
            ['queue_type_code' => 'D1', 'code' => 'BD1', 'name' => 'Bilik Doktor 1', 'name_en' => 'Doctor Room 1', 'location' => 'Tingkat 1'],

            // Doktor 2
            ['queue_type_code' => 'D2', 'code' => 'BD2', 'name' => 'Bilik Doktor 2', 'name_en' => 'Doctor Room 2', 'location' => 'Tingkat 1'],

            // Farmasi
            ['queue_type_code' => 'F', 'code' => 'F1', 'name' => 'Farmasi 1', 'name_en' => 'Pharmacy 1', 'location' => 'Tingkat Bawah'],
            ['queue_type_code' => 'F', 'code' => 'F2', 'name' => 'Farmasi 2', 'name_en' => 'Pharmacy 2', 'location' => 'Tingkat Bawah'],

            // Pembayaran
            ['queue_type_code' => 'P', 'code' => 'PB1', 'name' => 'Pembayaran 1', 'name_en' => 'Payment 1', 'location' => 'Lobi Utama'],

            // Makmal
            ['queue_type_code' => 'L', 'code' => 'LAB', 'name' => 'Makmal', 'name_en' => 'Laboratory', 'location' => 'Tingkat Bawah'],
        ];

        foreach ($counters as $counter) {
            $queueType = QueueType::where('code', $counter['queue_type_code'])->first();

            if ($queueType) {
                QueueCounter::firstOrCreate(
                    [
                        'queue_type_id' => $queueType->id,
                        'code' => $counter['code'],
                    ],
                    [
                        'name' => $counter['name'],
                        'name_en' => $counter['name_en'] ?? null,
                        'location' => $counter['location'] ?? null,
                        'is_active' => true,
                    ]
                );
            }
        }
    }

    /**
     * Create queue kiosks.
     */
    private function createQueueKiosks(): void
    {
        $queueTypeIds = QueueType::whereIn('code', ['R', 'D1', 'D2', 'L'])
            ->pluck('id')
            ->toArray();

        $kiosks = [
            [
                'kiosk_id' => 'KSK-001',
                'name' => 'Kiosk Lobi Utama',
                'location' => 'Lobi Utama - Pintu Masuk',
                'status' => 'offline',
                'is_active' => true,
                'available_queue_types' => $queueTypeIds,
            ],
            [
                'kiosk_id' => 'KSK-002',
                'name' => 'Kiosk Tingkat 1',
                'location' => 'Tingkat 1 - Area Tunggu',
                'status' => 'offline',
                'is_active' => true,
                'available_queue_types' => $queueTypeIds,
            ],
        ];

        foreach ($kiosks as $kiosk) {
            QueueKiosk::firstOrCreate(
                ['kiosk_id' => $kiosk['kiosk_id']],
                $kiosk
            );
        }
    }
}
