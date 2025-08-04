<?php

namespace Database\Factories;

use App\Models\LegalCase;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LegalCase>
 */
class LegalCaseFactory extends Factory
{
    protected $model = LegalCase::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $caseType = $this->faker->randomElement(['KB', 'SN', 'CP', 'FR', 'LN']);
        $progressLevel = $this->faker->numberBetween(1, 4);
        
        return [
            'case_type' => $caseType,
            'title' => $this->generateCaseTitle($caseType),
            'description' => $this->generateCaseDescription($caseType),
            'progress_level' => $progressLevel,
            'progress_percentage' => LegalCase::PROGRESS_LEVELS[$progressLevel]['percentage'],
            'status' => $this->faker->randomElement(['aktif', 'ditutup', 'ditangguhkan', 'menunggu_dokumen']),
            'priority' => $this->faker->randomElement(['rendah', 'sedang', 'tinggi', 'kritis']),
            'assigned_to' => User::factory(),
            'created_by' => User::factory(),
            'due_date' => $this->faker->optional(0.7)->dateTimeBetween('now', '+6 months'),
            'notes' => $this->faker->optional(0.6)->paragraph(),
            'estimated_resolution_days' => $this->faker->optional(0.8)->numberBetween(7, 180),
        ];
    }

    /**
     * Generate judul kasus berdasarkan jenis
     */
    private function generateCaseTitle(string $caseType): string
    {
        $titles = [
            'KB' => [
                'Penunggakan Kredit Modal Kerja PT {}',
                'Kredit Macet Nasabah {} - Restrukturisasi',
                'Penyelesaian NPL Sektor {} Tahun {}',
                'Kredit Bermasalah {} - Eksekusi Jaminan',
            ],
            'SN' => [
                'Komplain Nasabah {} - Layanan ATM',
                'Sengketa Biaya Administrasi {}',
                'Keluhan Nasabah {} - Transfer Gagal',
                'Sengketa {} - Blokir Rekening',
            ],
            'CP' => [
                'Audit Compliance {} - Laporan OJK',
                'Pelanggaran POJK {} oleh Cabang {}',
                'Review Kepatuhan {} - SOP Baru',
                'Compliance Check {} - KYC Nasabah',
            ],
            'FR' => [
                'Investigasi Fraud {} - Kartu Kredit',
                'Kasus Pemalsuan {} - Dokumen Kredit',
                'Fraud Internal {} - Teller {}',
                'Penipuan Online {} - Internet Banking',
            ],
            'LN' => [
                'Kasus Hukum {} - Gugatan Perdata',
                'Sengketa {} dengan Vendor {}',
                'Masalah Legal {} - Kontrak Kerjasama',
                'Kasus {} - Pelanggaran Kontrak',
            ]
        ];

        $template = $this->faker->randomElement($titles[$caseType]);
        
        return str_replace(
            ['{}'],
            [
                $this->faker->randomElement([
                    $this->faker->company,
                    $this->faker->name,
                    $this->faker->city,
                    $this->faker->year
                ])
            ],
            $template
        );
    }

    /**
     * Generate deskripsi kasus berdasarkan jenis
     */
    private function generateCaseDescription(string $caseType): string
    {
        $descriptions = [
            'KB' => 'Kasus kredit bermasalah yang memerlukan penanganan khusus untuk restrukturisasi atau eksekusi jaminan. Nasabah mengalami kesulitan pembayaran dan perlu dilakukan negosiasi ulang.',
            'SN' => 'Sengketa dengan nasabah terkait layanan perbankan yang memerlukan penyelesaian sesuai prosedur penanganan keluhan. Perlu koordinasi dengan unit terkait.',
            'CP' => 'Masalah kepatuhan terhadap regulasi yang berlaku dan memerlukan tindakan perbaikan. Harus diselesaikan sesuai timeline yang ditetapkan regulator.',
            'FR' => 'Kasus dugaan fraud yang memerlukan investigasi mendalam dan koordinasi dengan pihak berwenang. Perlu pengumpulan bukti dan analisis forensik.',
            'LN' => 'Kasus hukum lainnya yang memerlukan penanganan khusus dari tim legal. Perlu koordinasi dengan advokat eksternal jika diperlukan.'
        ];

        return $descriptions[$caseType] . ' ' . $this->faker->paragraph();
    }

    /**
     * State untuk kasus yang sudah selesai
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'progress_level' => 4,
            'progress_percentage' => 100.00,
            'status' => 'ditutup',
        ]);
    }

    /**
     * State untuk kasus dengan prioritas tinggi
     */
    public function highPriority(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'tinggi',
            'due_date' => $this->faker->dateTimeBetween('now', '+1 month'),
        ]);
    }

    /**
     * State untuk kasus yang terlambat
     */
    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'due_date' => $this->faker->dateTimeBetween('-1 month', 'yesterday'),
            'progress_level' => $this->faker->numberBetween(1, 3),
            'status' => 'aktif',
        ]);
    }

    /**
     * State untuk kasus kredit bermasalah
     */
    public function kreditBermasalah(): static
    {
        return $this->state(fn (array $attributes) => [
            'case_type' => 'KB',
            'title' => $this->generateCaseTitle('KB'),
            'description' => $this->generateCaseDescription('KB'),
            'priority' => 'tinggi',
        ]);
    }

    /**
     * State untuk kasus fraud
     */
    public function fraud(): static
    {
        return $this->state(fn (array $attributes) => [
            'case_type' => 'FR',
            'title' => $this->generateCaseTitle('FR'),
            'description' => $this->generateCaseDescription('FR'),
            'priority' => 'kritis',
        ]);
    }
}
