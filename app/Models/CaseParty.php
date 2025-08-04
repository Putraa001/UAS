<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseParty extends Model
{
    protected $fillable = [
        'legal_case_id',
        'party_type',
        'name',
        'identity_type',
        'identity_number',
        'gender',
        'place_of_birth',
        'date_of_birth',
        'marital_status',
        'occupation',
        'education',
        'address',
        'village',
        'district',
        'city',
        'province',
        'postal_code',
        'phone',
        'email',
        'emergency_contact',
        'emergency_phone',
        'monthly_income',
        'debt_amount',
        'collateral_description',
        'collateral_value',
        'status',
        'notes',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'monthly_income' => 'decimal:2',
        'debt_amount' => 'decimal:2',
        'collateral_value' => 'decimal:2'
    ];

    const PARTY_TYPES = [
        'debitor' => 'Debitor',
        'penjamin' => 'Penjamin',
        'saksi' => 'Saksi',
        'ahli_waris' => 'Ahli Waris',
        'kuasa_hukum' => 'Kuasa Hukum',
        'pihak_ketiga' => 'Pihak Ketiga',
        'lainnya' => 'Lainnya'
    ];

    const IDENTITY_TYPES = [
        'ktp' => 'KTP',
        'sim' => 'SIM',
        'passport' => 'Passport',
        'npwp' => 'NPWP',
        'lainnya' => 'Lainnya'
    ];

    const GENDER_OPTIONS = [
        'L' => 'Laki-laki',
        'P' => 'Perempuan'
    ];

    const MARITAL_STATUS_OPTIONS = [
        'belum_menikah' => 'Belum Menikah',
        'menikah' => 'Menikah',
        'cerai' => 'Cerai',
        'janda_duda' => 'Janda/Duda'
    ];

    const STATUS_OPTIONS = [
        'aktif' => 'Aktif',
        'non_aktif' => 'Non Aktif',
        'meninggal' => 'Meninggal',
        'pindah' => 'Pindah'
    ];

    // Relationships
    public function legalCase(): BelongsTo
    {
        return $this->belongsTo(LegalCase::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Accessors
    public function getPartyTypeNameAttribute(): string
    {
        return self::PARTY_TYPES[$this->party_type] ?? $this->party_type;
    }

    public function getIdentityTypeNameAttribute(): string
    {
        return self::IDENTITY_TYPES[$this->identity_type] ?? $this->identity_type;
    }

    public function getGenderNameAttribute(): string
    {
        return self::GENDER_OPTIONS[$this->gender] ?? $this->gender;
    }

    public function getMaritalStatusNameAttribute(): string
    {
        return self::MARITAL_STATUS_OPTIONS[$this->marital_status] ?? $this->marital_status;
    }

    public function getStatusNameAttribute(): string
    {
        return self::STATUS_OPTIONS[$this->status] ?? $this->status;
    }

    public function getFormattedMonthlyIncomeAttribute(): string
    {
        if (!$this->monthly_income) return '-';
        return 'Rp ' . number_format($this->monthly_income, 0, ',', '.');
    }

    public function getFormattedDebtAmountAttribute(): string
    {
        if (!$this->debt_amount) return '-';
        return 'Rp ' . number_format($this->debt_amount, 0, ',', '.');
    }

    public function getFormattedCollateralValueAttribute(): string
    {
        if (!$this->collateral_value) return '-';
        return 'Rp ' . number_format($this->collateral_value, 0, ',', '.');
    }

    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address,
            $this->village,
            $this->district,
            $this->city,
            $this->province,
            $this->postal_code
        ]);
        
        return implode(', ', $parts);
    }

    public function getAgeAttribute(): ?int
    {
        if (!$this->date_of_birth) return null;
        return now()->diffInYears($this->date_of_birth);
    }
}
