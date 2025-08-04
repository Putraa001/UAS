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
        Schema::create('case_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique()->comment('Kode jenis kasus (KB, KL, dll)');
            $table->string('name', 100)->comment('Nama jenis kasus');
            $table->text('description')->nullable()->comment('Deskripsi jenis kasus');
            $table->boolean('is_active')->default(true)->comment('Status aktif');
            $table->integer('sort_order')->default(0)->comment('Urutan tampilan');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            
            $table->index(['is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_types');
    }
};
