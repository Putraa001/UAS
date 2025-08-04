<?php

namespace Tests\Unit;

use App\Models\LegalCase;
use App\Models\User;
use App\Models\CaseStatusLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LegalCaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Buat user untuk testing
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->manager = User::factory()->create(['role' => 'manager']);
        $this->user = User::factory()->create(['role' => 'user']);
    }

    /** @test */
    public function dapat_membuat_kasus_hukum_dengan_kode_otomatis()
    {
        $this->actingAs($this->admin);

        $kasus = LegalCase::create([
            'case_type' => 'KB',
            'title' => 'Test Kasus Kredit Bermasalah',
            'description' => 'Deskripsi test kasus yang cukup panjang untuk memenuhi validasi minimum',
            'created_by' => $this->admin->id,
        ]);

        $this->assertNotNull($kasus->case_code);
        $this->assertStringStartsWith('KB-', $kasus->case_code);
        $this->assertEquals(1, $kasus->progress_level);
        $this->assertEquals(25.00, $kasus->progress_percentage);
    }

    /** @test */
    public function kode_kasus_berformat_prefix_tahun_bulan_nomor_urut()
    {
        $this->actingAs($this->admin);

        $kasus1 = LegalCase::factory()->create(['case_type' => 'KB']);
        $kasus2 = LegalCase::factory()->create(['case_type' => 'KB']);
        $kasus3 = LegalCase::factory()->create(['case_type' => 'SN']);

        $tanggal = now();
        $expectedPrefix = 'KB-' . $tanggal->format('Y-m');
        
        $this->assertStringStartsWith($expectedPrefix, $kasus1->case_code);
        $this->assertStringStartsWith($expectedPrefix, $kasus2->case_code);
        $this->assertStringStartsWith('SN-' . $tanggal->format('Y-m'), $kasus3->case_code);

        // Pastikan nomor urut berbeda
        $this->assertNotEquals($kasus1->case_code, $kasus2->case_code);
    }

    /** @test */
    public function dapat_memperbarui_progress_kasus()
    {
        $this->actingAs($this->admin);

        $kasus = LegalCase::factory()->create([
            'progress_level' => 1,
            'created_by' => $this->admin->id
        ]);

        $berhasil = $kasus->updateProgress(2, 'Progress ke investigasi');

        $this->assertTrue($berhasil);
        $this->assertEquals(2, $kasus->fresh()->progress_level);
        $this->assertEquals(50.00, $kasus->fresh()->progress_percentage);

        // Pastikan log status tersimpan
        $this->assertDatabaseHas('case_status_logs', [
            'legal_case_id' => $kasus->id,
            'from_level' => 1,
            'to_level' => 2,
            'notes' => 'Progress ke investigasi'
        ]);
    }

    /** @test */
    public function hanya_admin_yang_dapat_menurunkan_level_progress()
    {
        $this->actingAs($this->user);

        $kasus = LegalCase::factory()->create([
            'progress_level' => 3,
            'created_by' => $this->user->id
        ]);

        $this->expectException(\UnauthorizedAccessException::class);
        $kasus->updateProgress(2, 'Mundur ke investigasi');
    }

    /** @test */
    public function admin_dapat_menurunkan_level_progress()
    {
        $this->actingAs($this->admin);

        $kasus = LegalCase::factory()->create([
            'progress_level' => 3,
            'created_by' => $this->admin->id
        ]);

        $berhasil = $kasus->updateProgress(2, 'Admin mundurkan progress');

        $this->assertTrue($berhasil);
        $this->assertEquals(2, $kasus->fresh()->progress_level);
    }

    /** @test */
    public function progress_level_4_otomatis_mengubah_status_menjadi_ditutup()
    {
        $this->actingAs($this->admin);

        $kasus = LegalCase::factory()->create([
            'progress_level' => 3,
            'status' => 'aktif',
            'created_by' => $this->admin->id
        ]);

        $kasus->updateProgress(4, 'Kasus selesai');

        $this->assertEquals('ditutup', $kasus->fresh()->status);
    }

    /** @test */
    public function dapat_mengecek_kasus_terlambat()
    {
        $kasusNormal = LegalCase::factory()->create([
            'due_date' => now()->addDays(5),
            'progress_level' => 2
        ]);

        $kasusTerlambat = LegalCase::factory()->create([
            'due_date' => now()->subDays(3),
            'progress_level' => 2
        ]);

        $kasusSelesai = LegalCase::factory()->create([
            'due_date' => now()->subDays(3),
            'progress_level' => 4
        ]);

        $this->assertFalse($kasusNormal->isOverdue());
        $this->assertTrue($kasusTerlambat->isOverdue());
        $this->assertFalse($kasusSelesai->isOverdue());
    }

    /** @test */
    public function dapat_mengecek_kasus_dalam_zona_bahaya()
    {
        $kasusAman = LegalCase::factory()->create([
            'due_date' => now()->addDays(10)
        ]);

        $kasusBahaya = LegalCase::factory()->create([
            'due_date' => now()->addDays(2)
        ]);

        $this->assertFalse($kasusAman->isInDangerZone());
        $this->assertTrue($kasusBahaya->isInDangerZone());
    }

    /** @test */
    public function dapat_menghitung_sisa_hari_deadline()
    {
        $kasus = LegalCase::factory()->create([
            'due_date' => now()->addDays(7)
        ]);

        $kasusNullDeadline = LegalCase::factory()->create([
            'due_date' => null
        ]);

        $this->assertEquals(7, $kasus->getDaysUntilDue());
        $this->assertNull($kasusNullDeadline->getDaysUntilDue());
    }

    /** @test */
    public function validasi_data_kasus_berfungsi_dengan_benar()
    {
        $rules = LegalCase::getValidationRules();

        $this->assertArrayHasKey('case_type', $rules);
        $this->assertArrayHasKey('title', $rules);
        $this->assertArrayHasKey('description', $rules);

        // Test validasi document
        $docRules = LegalCase::getDocumentValidationRules();
        $this->assertArrayHasKey('document', $docRules);
        $this->assertStringContains('max:2048', $docRules['document']);
    }

    /** @test */
    public function accessor_methods_berfungsi_dengan_benar()
    {
        $kasus = LegalCase::factory()->create([
            'case_type' => 'KB',
            'progress_level' => 2,
            'status' => 'aktif',
            'priority' => 'tinggi'
        ]);

        $this->assertEquals('Kredit Bermasalah', $kasus->case_type_name);
        $this->assertEquals('Investigasi', $kasus->progress_level_name);
        $this->assertEquals('Aktif', $kasus->status_name);
        $this->assertEquals('Tinggi', $kasus->priority_name);
        $this->assertStringContains('Penyelidikan', $kasus->progress_description);
    }

    /** @test */
    public function scope_for_user_bekerja_dengan_benar()
    {
        $userBiasa = User::factory()->create(['role' => 'user']);
        $admin = User::factory()->create(['role' => 'admin']);

        // Kasus yang dimiliki user biasa
        $kasusUser = LegalCase::factory()->create([
            'assigned_to' => $userBiasa->id,
            'created_by' => $admin->id
        ]);

        // Kasus yang dibuat user biasa
        $kasusCreated = LegalCase::factory()->create([
            'assigned_to' => null,
            'created_by' => $userBiasa->id
        ]);

        // Kasus lain
        $kasusLain = LegalCase::factory()->create([
            'assigned_to' => $admin->id,
            'created_by' => $admin->id
        ]);

        // Test scope untuk user biasa
        $hasilUser = LegalCase::forUser($userBiasa)->get();
        $this->assertCount(2, $hasilUser);
        $this->assertTrue($hasilUser->contains($kasusUser));
        $this->assertTrue($hasilUser->contains($kasusCreated));
        $this->assertFalse($hasilUser->contains($kasusLain));

        // Test scope untuk admin (melihat semua)
        $hasilAdmin = LegalCase::forUser($admin)->get();
        $this->assertCount(3, $hasilAdmin);
    }

    /** @test */
    public function scope_overdue_dan_danger_zone_bekerja()
    {
        // Kasus terlambat
        $kasusTerlambat = LegalCase::factory()->create([
            'due_date' => now()->subDays(5),
            'progress_level' => 2
        ]);

        // Kasus dalam zona bahaya
        $kasusBahaya = LegalCase::factory()->create([
            'due_date' => now()->addDays(2),
            'progress_level' => 1
        ]);

        // Kasus normal
        $kasusNormal = LegalCase::factory()->create([
            'due_date' => now()->addDays(10),
            'progress_level' => 2
        ]);

        $overdueResults = LegalCase::overdue()->get();
        $dangerResults = LegalCase::inDangerZone()->get();

        $this->assertTrue($overdueResults->contains($kasusTerlambat));
        $this->assertFalse($overdueResults->contains($kasusBahaya));
        $this->assertFalse($overdueResults->contains($kasusNormal));

        $this->assertTrue($dangerResults->contains($kasusBahaya));
        $this->assertFalse($dangerResults->contains($kasusTerlambat));
        $this->assertFalse($dangerResults->contains($kasusNormal));
    }

    /** @test */
    public function relationships_berfungsi_dengan_benar()
    {
        $pembuat = User::factory()->create();
        $penugasan = User::factory()->create();

        $kasus = LegalCase::factory()->create([
            'created_by' => $pembuat->id,
            'assigned_to' => $penugasan->id
        ]);

        $this->assertEquals($pembuat->id, $kasus->pembuat->id);
        $this->assertEquals($penugasan->id, $kasus->penugasan->id);

        // Test alias relationships
        $this->assertEquals($pembuat->id, $kasus->creator->id);
        $this->assertEquals($penugasan->id, $kasus->assignedUser->id);
    }

    /** @test */
    public function soft_delete_berfungsi()
    {
        $kasus = LegalCase::factory()->create();
        $kasusId = $kasus->id;

        $kasus->delete();

        // Pastikan soft deleted
        $this->assertSoftDeleted('legal_cases', ['id' => $kasusId]);

        // Pastikan bisa di-restore
        $kasus->restore();
        $this->assertDatabaseHas('legal_cases', ['id' => $kasusId, 'deleted_at' => null]);
    }
}