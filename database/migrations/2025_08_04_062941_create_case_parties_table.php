<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('case_parties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('legal_case_id')->constrained('legal_cases')->onDelete('cascade');
            
            // Tipe pihak: debitor, penjamin, saksi, ahli_waris, dll
            $table->enum('party_type', [
                'debitor', 'penjamin', 'saksi', 'ahli_waris', 
                'kuasa_hukum', 'pihak_ketiga', 'lainnya'
            ])->default('debitor');
            
            // Data Identitas Dasar
            $table->string('name'); // Nama lengkap
            $table->enum('identity_type', ['ktp', 'sim', 'passport', 'npwp', 'lainnya'])->default('ktp');
            $table->string('identity_number')->nullable(); // Nomor identitas
            
            // Data Personal
            $table->enum('gender', ['L', 'P'])->nullable(); // Laki-laki/Perempuan
            $table->string('place_of_birth')->nullable(); // Tempat lahir
            $table->date('date_of_birth')->nullable(); // Tanggal lahir
            $table->enum('marital_status', ['belum_menikah', 'menikah', 'cerai', 'janda_duda'])->nullable();
            $table->string('occupation')->nullable(); // Pekerjaan
            $table->string('education')->nullable(); // Pendidikan
            
            // Alamat
            $table->text('address'); // Alamat lengkap
            $table->string('village')->nullable(); // Desa/Kelurahan
            $table->string('district')->nullable(); // Kecamatan
            $table->string('city')->nullable(); // Kota/Kabupaten
            $table->string('province')->nullable(); // Provinsi
            $table->string('postal_code')->nullable(); // Kode pos
            
            // Kontak
            $table->string('phone')->nullable(); // Nomor telepon
            $table->string('email')->nullable(); // Email
            $table->string('emergency_contact')->nullable(); // Kontak darurat
            $table->string('emergency_phone')->nullable(); // Nomor kontak darurat
            
            // Data Keuangan (untuk debitor)
            $table->decimal('monthly_income', 15, 2)->nullable(); // Penghasilan bulanan
            $table->decimal('debt_amount', 15, 2)->nullable(); // Jumlah hutang
            $table->text('collateral_description')->nullable(); // Deskripsi jaminan
            $table->decimal('collateral_value', 15, 2)->nullable(); // Nilai jaminan
            
            // Status dan catatan
            $table->enum('status', ['aktif', 'non_aktif', 'meninggal', 'pindah'])->default('aktif');
            $table->text('notes')->nullable(); // Catatan tambahan
            
            // Audit trail
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            
            // Indexes
            $table->index(['legal_case_id', 'party_type']);
            $table->index('identity_number');
            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_parties');
    }
};
