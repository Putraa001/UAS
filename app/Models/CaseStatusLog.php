<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseStatusLog extends Model
{
    protected $fillable = [
        'legal_case_id',
        'from_level',
        'to_level',
        'from_percentage',
        'to_percentage',
        'notes',
        'changed_by'
    ];

    protected $casts = [
        'from_level' => 'integer',
        'to_level' => 'integer',
        'from_percentage' => 'decimal:2',
        'to_percentage' => 'decimal:2'
    ];

    public function legalCase(): BelongsTo
    {
        return $this->belongsTo(LegalCase::class);
    }

    public function changer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    public function getFromLevelNameAttribute(): string
    {
        return $this->from_level ? LegalCase::PROGRESS_LEVELS[$this->from_level]['name'] : '';
    }

    public function getToLevelNameAttribute(): string
    {
        return LegalCase::PROGRESS_LEVELS[$this->to_level]['name'];
    }
}
