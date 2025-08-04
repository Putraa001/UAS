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
        Schema::create('legal_cases', function (Blueprint $table) {
            $table->id();
            $table->string('case_code')->unique()->comment('Kode kasus format PREFIX-YYYY-MM-XXX');
            $table->enum('case_type', ['KB', 'SN', 'CP', 'FR', 'LN'])->comment('Jenis kasus: KB=Kredit Bermasalah, SN=Sengketa Nasabah, CP=Compliance, FR=Fraud, LN=Lainnya');
            $table->string('title')->comment('Judul kasus');
            $table->text('description')->comment('Deskripsi detail kasus');
            $table->enum('progress_level', [1, 2, 3, 4])->default(1)->comment('Level progress: 1=Inisiasi, 2=Investigasi, 3=Penanganan, 4=Selesai');
            $table->decimal('progress_percentage', 5, 2)->default(25.00)->comment('Persentase progress');
            $table->enum('status', ['aktif', 'ditutup', 'ditangguhkan', 'menunggu_dokumen'])->default('aktif')->comment('Status kasus');
            $table->enum('priority', ['rendah', 'sedang', 'tinggi', 'kritis'])->default('sedang')->comment('Prioritas kasus');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null')->comment('User yang ditugaskan');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade')->comment('User pembuat kasus');
            $table->date('due_date')->nullable()->comment('Tanggal deadline');
            $table->text('notes')->nullable()->comment('Catatan tambahan');
            $table->integer('estimated_resolution_days')->nullable()->comment('Estimasi hari penyelesaian');
            $table->timestamps();
            $table->softDeletes()->comment('Tanggal penghapusan soft delete');
            
            // Indexes untuk optimasi query
            $table->index(['case_type', 'created_at']);
            $table->index(['status', 'progress_level']);
            $table->index(['assigned_to', 'status']);
            $table->index(['due_date', 'progress_level']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legal_cases');
    }
};
