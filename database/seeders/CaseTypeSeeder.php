<?php

namespace Database\Seeders;

use App\Models\CaseType;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CaseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first admin user or create one if doesn't exist
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::factory()->create([
                'name' => 'Administrator',
                'email' => 'admin@bkk.com',
                'role' => 'admin',
            ]);
        }

        $caseTypes = [
            [
                'code' => 'KB',
                'name' => 'Kredit Bermasalah',
                'description' => 'Kasus terkait kredit yang mengalami masalah pembayaran, macet, atau sengketa.',
                'sort_order' => 1,
            ],
            [
                'code' => 'KL',
                'name' => 'Kredit Lancar',
                'description' => 'Kasus terkait kredit yang berjalan lancar namun memerlukan perhatian hukum.',
                'sort_order' => 2,
            ],
            [
                'code' => 'SP',
                'name' => 'Sengketa Perdata',
                'description' => 'Sengketa perdata yang melibatkan bank dengan pihak ketiga.',
                'sort_order' => 3,
            ],
            [
                'code' => 'KP',
                'name' => 'Kasus Pidana',
                'description' => 'Kasus pidana yang berkaitan dengan operasional bank.',
                'sort_order' => 4,
            ],
            [
                'code' => 'JM',
                'name' => 'Jaminan',
                'description' => 'Kasus terkait pengelolaan, eksekusi, atau sengketa jaminan kredit.',
                'sort_order' => 5,
            ],
            [
                'code' => 'LG',
                'name' => 'Legal Opinion',
                'description' => 'Permintaan pendapat hukum dan konsultasi legal.',
                'sort_order' => 6,
            ],
            [
                'code' => 'KT',
                'name' => 'Kontrak',
                'description' => 'Review, pembuatan, dan sengketa kontrak atau perjanjian.',
                'sort_order' => 7,
            ],
            [
                'code' => 'LL',
                'name' => 'Lain-lain',
                'description' => 'Kasus hukum lainnya yang tidak termasuk dalam kategori di atas.',
                'sort_order' => 99,
            ],
        ];

        foreach ($caseTypes as $caseTypeData) {
            CaseType::create([
                'code' => $caseTypeData['code'],
                'name' => $caseTypeData['name'],
                'description' => $caseTypeData['description'],
                'sort_order' => $caseTypeData['sort_order'],
                'is_active' => true,
                'created_by' => $admin->id,
            ]);
        }
    }
}
