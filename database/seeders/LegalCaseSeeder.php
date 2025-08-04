<?php

namespace Database\Seeders;

use App\Models\LegalCase;
use App\Models\User;
use Illuminate\Database\Seeder;

class LegalCaseSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada users untuk testing
        $admin = User::firstOrCreate(
            ['email' => 'admin@bank.com'],
            [
                'name' => 'Administrator',
                'role' => 'admin',
                'password' => bcrypt('password123')
            ]
        );

        $manager = User::firstOrCreate(
            ['email' => 'manager@bank.com'],
            [
                'name' => 'Manager Legal',
                'role' => 'manager',
                'password' => bcrypt('password123')
            ]
        );

        $user1 = User::firstOrCreate(
            ['email' => 'legal1@bank.com'],
            [
                'name' => 'Staff Legal 1',
                'role' => 'user',
                'password' => bcrypt('password123')
            ]
        );

        $user2 = User::firstOrCreate(
            ['email' => 'legal2@bank.com'],
            [
                'name' => 'Staff Legal 2', 
                'role' => 'user',
                'password' => bcrypt('password123')
            ]
        );

        // Buat kasus-kasus sample
        $this->createKasusKreditBermasalah($admin, $manager, $user1, $user2);
        $this->createKasusSengketaNasabah($admin, $manager, $user1, $user2);
        $this->createKasusCompliance($admin, $manager, $user1, $user2);
        $this->createKasusFraud($admin, $manager, $user1, $user2);
        $this->createKasusLainnya($admin, $manager, $user1, $user2);

        $this->command->info('Sample legal cases created successfully!');
    }

    private function createKasusKreditBermasalah($admin, $manager, $user1, $user2)
    {
        // Kasus KB dengan berbagai status
        LegalCase::factory()->kreditBermasalah()->create([
            'title' => 'Penunggakan Kredit Modal Kerja PT. Maju Bersama',
            'description' => 'Kredit modal kerja senilai Rp 5.2 miliar mengalami tunggakan selama 4 bulan. Nasabah mengalami kesulitan cash flow akibat penurunan orderan. Perlu dilakukan restrukturisasi kredit dengan perpanjangan jangka waktu dan penyesuaian bunga.',
            'assigned_to' => $user1->id,
            'created_by' => $manager->id,
            'due_date' => now()->addDays(30),
            'progress_level' => 2,
            'progress_percentage' => 50.00,
            'status' => 'aktif',
            'priority' => 'tinggi',
            'estimated_resolution_days' => 45
        ]);

        LegalCase::factory()->kreditBermasalah()->create([
            'title' => 'Eksekusi Jaminan Kredit Investasi CV. Sejahtera',
            'description' => 'Kredit investasi Rp 2.8 miliar kategori macet, nasabah tidak kooperatif. Akan dilakukan eksekusi jaminan berupa tanah dan bangunan. Sedang dalam proses lelang melalui KPKNL.',
            'assigned_to' => $user2->id,
            'created_by' => $admin->id,
            'due_date' => now()->addDays(60),
            'progress_level' => 3,
            'progress_percentage' => 75.00,
            'status' => 'aktif',
            'priority' => 'tinggi'
        ]);

        // Kasus KB yang sudah selesai
        LegalCase::factory()->kreditBermasalah()->completed()->create([
            'title' => 'Penyelesaian NPL Sektor UMKM Batch Q3-2024',
            'description' => 'Program penyelesaian NPL untuk 15 nasabah UMKM dengan total outstanding Rp 1.2 miliar. Berhasil diselesaikan melalui skema restrukturisasi dan sebagian write-off.',
            'assigned_to' => $user1->id,
            'created_by' => $manager->id,
            'due_date' => now()->subDays(10),
            'notes' => 'Berhasil diselesaikan dengan recovery rate 78%'
        ]);
    }

    private function createKasusSengketaNasabah($admin, $manager, $user1, $user2)
    {
        LegalCase::factory()->create([
            'case_type' => 'SN',
            'title' => 'Komplain Nasabah - Transfer Gagal Rp 500 Juta',
            'description' => 'Nasabah melakukan transfer RTGS senilai Rp 500 juta namun dana terpotong di rekening pengirim tetapi tidak masuk ke rekening tujuan. Telah dilakukan investigasi sistem dan koordinasi dengan bank penerima.',
            'assigned_to' => $user1->id,
            'created_by' => $user2->id,
            'due_date' => now()->addDays(7),
            'progress_level' => 2,
            'progress_percentage' => 50.00,
            'status' => 'aktif',
            'priority' => 'kritis'
        ]);

        LegalCase::factory()->create([
            'case_type' => 'SN', 
            'title' => 'Sengketa Biaya Administrasi Kartu Kredit',
            'description' => 'Nasabah memprotes biaya administrasi kartu kredit yang dinilai tidak sesuai dengan kesepakatan awal. Kasus sudah masuk ke OJK dan memerlukan penjelasan detail.',
            'assigned_to' => $user2->id,
            'created_by' => $manager->id,
            'due_date' => now()->addDays(14),
            'progress_level' => 1,
            'progress_percentage' => 25.00,
            'status' => 'menunggu_dokumen',
            'priority' => 'sedang'
        ]);
    }

    private function createKasusCompliance($admin, $manager, $user1, $user2)
    {
        LegalCase::factory()->create([
            'case_type' => 'CP',
            'title' => 'Audit Compliance OJK - Laporan GCG Triwulan II',
            'description' => 'Temuan audit OJK terkait laporan Good Corporate Governance triwulan II. Ada beberapa poin yang perlu diperbaiki dalam implementasi manajemen risiko dan sistem pengendalian internal.',
            'assigned_to' => $manager->id,
            'created_by' => $admin->id,
            'due_date' => now()->addDays(21),
            'progress_level' => 2,
            'progress_percentage' => 50.00,
            'status' => 'aktif',
            'priority' => 'tinggi'
        ]);

        LegalCase::factory()->create([
            'case_type' => 'CP',
            'title' => 'Review Kepatuhan POJK No. 11/2020 - Digital Banking',
            'description' => 'Review kepatuhan implementasi POJK tentang layanan digital banking. Perlu penyesuaian beberapa prosedur operasional dan sistem keamanan.',
            'assigned_to' => $user1->id,
            'created_by' => $manager->id,
            'due_date' => now()->addDays(45),
            'progress_level' => 1,
            'progress_percentage' => 25.00,
            'status' => 'aktif',
            'priority' => 'sedang'
        ]);
    }

    private function createKasusFraud($admin, $manager, $user1, $user2)
    {
        LegalCase::factory()->fraud()->create([
            'title' => 'Investigasi Fraud Kartu Kredit - Transaksi Fiktif',
            'description' => 'Ditemukan indikasi fraud pada transaksi kartu kredit dengan pola tidak wajar. Total kerugian diperkirakan Rp 250 juta. Sedang dilakukan investigasi forensik dan koordinasi dengan kepolisian.',
            'assigned_to' => $user2->id,
            'created_by' => $admin->id,
            'due_date' => now()->addDays(30),
            'progress_level' => 2,
            'progress_percentage' => 50.00,
            'status' => 'aktif',
            'priority' => 'kritis'
        ]);

        LegalCase::factory()->fraud()->create([
            'title' => 'Kasus Pemalsuan Dokumen Kredit - PT. Berkah Jaya',
            'description' => 'Ditemukan dokumen jaminan palsu pada aplikasi kredit PT. Berkah Jaya. Kredit sudah dicairkan Rp 1.5 miliar. Kasus dilaporkan ke polisi dan sedang dalam proses penyidikan.',
            'assigned_to' => $user1->id,
            'created_by' => $admin->id,
            'due_date' => now()->addDays(60),
            'progress_level' => 3,
            'progress_percentage' => 75.00,
            'status' => 'aktif',
            'priority' => 'kritis'
        ]);
    }

    private function createKasusLainnya($admin, $manager, $user1, $user2)
    {
        LegalCase::factory()->create([
            'case_type' => 'LN',
            'title' => 'Sengketa Kontrak dengan Vendor IT - System Integration',
            'description' => 'Sengketa kontrak sistem integrasi dengan vendor IT. Vendor tidak memenuhi milestone sesuai kontrak dan ada claim penalty. Perlu mediasi dan kemungkinan terminasi kontrak.',
            'assigned_to' => $user1->id,
            'created_by' => $manager->id,
            'due_date' => now()->addDays(35),
            'progress_level' => 2,
            'progress_percentage' => 50.00,
            'status' => 'aktif',
            'priority' => 'sedang'
        ]);

        // Kasus yang terlambat
        LegalCase::factory()->overdue()->create([
            'case_type' => 'LN',
            'title' => 'Gugatan Perdata - Wanprestasi Kontrak Sewa Gedung',
            'description' => 'Gugatan perdata dari pemilik gedung terkait wanprestasi kontrak sewa. Bank dianggap melanggar klausul pemeliharaan bangunan. Sedang dalam proses mediasi di pengadilan.',
            'assigned_to' => $user2->id,
            'created_by' => $admin->id,
            'progress_level' => 2,
            'progress_percentage' => 50.00,
            'status' => 'aktif',
            'priority' => 'tinggi'
        ]);

        // Kasus dalam zona bahaya (deadline dekat)
        LegalCase::factory()->create([
            'case_type' => 'LN',
            'title' => 'Review Kontrak Asuransi Kredit 2025',
            'description' => 'Review dan negosiasi ulang kontrak asuransi kredit untuk tahun 2025. Perlu evaluasi coverage dan penyesuaian premi sesuai portfolio risk saat ini.',
            'assigned_to' => $user1->id,
            'created_by' => $manager->id,
            'due_date' => now()->addDays(3),
            'progress_level' => 1,
            'progress_percentage' => 25.00,
            'status' => 'aktif',
            'priority' => 'tinggi'
        ]);
    }
}