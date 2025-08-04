<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseDocument extends Model
{
    protected $fillable = [
        'legal_case_id',
        'document_name',
        'filename',
        'original_filename',
        'file_path',
        'mime_type',
        'file_size',
        'document_type',
        'description',
        'uploaded_by',
        'version'
    ];

    protected $casts = [
        'file_size' => 'integer',
        'version' => 'integer'
    ];

    const DOCUMENT_TYPES = [
        'evidence' => 'Bukti',
        'contract' => 'Kontrak',
        'correspondence' => 'Korespondensi',
        'report' => 'Laporan',
        'other' => 'Lainnya'
    ];

    public function legalCase(): BelongsTo
    {
        return $this->belongsTo(LegalCase::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getFormattedFileSizeAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' bytes';
    }
}
