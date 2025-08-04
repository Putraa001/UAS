<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class LegalCase extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'legal_cases';

    protected $fillable = [
        'case_code',
        'case_type',
        'title',
        'description',
        'progress_level',
        'progress_percentage',
        'status',
        'assigned_to',
        'created_by',
        'due_date',
        'notes',
        'priority',
        'estimated_resolution_days'
    ];

    protected $casts = [
        'due_date' => 'date',
        'progress_percentage' => 'decimal:2',
        'estimated_resolution_days' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    protected $attributes = [
        'progress_level' => 1,
        'progress_percentage' => 25.00,
        'status' => 'aktif',
        'priority' => 'sedang'
    ];

    // Konstanta jenis kasus
    const CASE_TYPES = [
        'KB' => 'Kredit Bermasalah',
        'SN' => 'Sengketa Nasabah',
        'CP' => 'Compliance',
        'FR' => 'Fraud',
        'LN' => 'Lainnya'
    ];

    // Konstanta level progress dengan deskripsi Indonesia
    const PROGRESS_LEVELS = [
        1 => [
            'name' => 'Inisiasi', 
            'percentage' => 25.00,
            'description' => 'Kasus baru dilaporkan dan didokumentasikan',
            'color' => 'bg-yellow-500'
        ],
        2 => [
            'name' => 'Investigasi', 
            'percentage' => 50.00,
            'description' => 'Penyelidikan dan pengumpulan bukti sedang berlangsung',
            'color' => 'bg-blue-500'
        ],
        3 => [
            'name' => 'Penanganan', 
            'percentage' => 75.00,
            'description' => 'Tindakan hukum sedang dilaksanakan',
            'color' => 'bg-orange-500'
        ],
        4 => [
            'name' => 'Selesai', 
            'percentage' => 100.00,
            'description' => 'Kasus telah diselesaikan dan ditutup',
            'color' => 'bg-green-500'
        ]
    ];

    // Konstanta status kasus
    const STATUS_OPTIONS = [
        'aktif' => 'Aktif',
        'ditutup' => 'Ditutup',
        'ditangguhkan' => 'Ditangguhkan',
        'menunggu_dokumen' => 'Menunggu Dokumen'
    ];

    // Konstanta prioritas
    const PRIORITY_OPTIONS = [
        'rendah' => 'Rendah',
        'sedang' => 'Sedang',
        'tinggi' => 'Tinggi',
        'kritis' => 'Kritis'
    ];

    /**
     * Generate kode kasus unik dengan format PREFIX-YYYY-MM-XXX
     */
    public function generateCaseCode(): string
    {
        return DB::transaction(function () {
            $tanggal = now();
            $tahun = $tanggal->format('Y');
            $bulan = $tanggal->format('m');
            
            // Cari kasus terakhir dengan jenis yang sama di bulan dan tahun ini
            $kasusTermakhir = static::where('case_type', $this->case_type)
                ->where('case_code', 'like', "{$this->case_type}-{$tahun}-{$bulan}-%")
                ->lockForUpdate()
                ->orderBy('case_code', 'desc')
                ->first();

            if ($kasusTermakhir) {
                $nomorUrutTerakhir = (int) substr($kasusTermakhir->case_code, -3);
                $nomorUrut = $nomorUrutTerakhir + 1;
            } else {
                $nomorUrut = 1;
            }

            return sprintf('%s-%s-%s-%03d', $this->case_type, $tahun, $bulan, $nomorUrut);
        });
    }

    /**
     * Update progress kasus dengan validasi dan logging
     */
    public function updateProgress(int $levelBaru, ?string $catatan = null): bool
    {
        // Validasi level progress
        if (!array_key_exists($levelBaru, self::PROGRESS_LEVELS)) {
            throw new \InvalidArgumentException('Level progress tidak valid');
        }

        // Hanya admin yang bisa mundur level
        if ($levelBaru < $this->progress_level && auth()->user()->role !== 'admin') {
            throw new \UnauthorizedAccessException('Hanya admin yang dapat menurunkan level progress');
        }

        $levelLama = $this->progress_level;
        $persentaseLama = $this->progress_percentage;
        
        $this->progress_level = $levelBaru;
        $this->progress_percentage = self::PROGRESS_LEVELS[$levelBaru]['percentage'];
        
        // Otomatis ubah status jika selesai
        if ($levelBaru == 4) {
            $this->status = 'ditutup';
        }
        
        $berhasil = $this->save();

        if ($berhasil) {
            // Catat perubahan progress
            CaseStatusLog::create([
                'legal_case_id' => $this->id,
                'from_level' => $levelLama,
                'to_level' => $levelBaru,
                'from_percentage' => $persentaseLama,
                'to_percentage' => $this->progress_percentage,
                'notes' => $catatan,
                'changed_by' => auth()->id()
            ]);

            // Kirim notifikasi jika diperlukan
            $this->sendProgressNotification($levelLama, $levelBaru);
        }

        return $berhasil;
    }

    /**
     * Validasi data kasus hukum
     */
    public static function getValidationRules(bool $isUpdate = false): array
    {
        $rules = [
            'case_type' => ['required', Rule::in(array_keys(self::CASE_TYPES))],
            'title' => 'required|string|max:255|min:10',
            'description' => 'required|string|min:20',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string|max:1000',
            'status' => [Rule::in(array_keys(self::STATUS_OPTIONS))],
            'priority' => [Rule::in(array_keys(self::PRIORITY_OPTIONS))],
            'estimated_resolution_days' => 'nullable|integer|min:1|max:365'
        ];

        if ($isUpdate) {
            $rules['case_type'] = ['sometimes', Rule::in(array_keys(self::CASE_TYPES))];
            $rules['title'] = 'sometimes|string|max:255|min:10';
            $rules['description'] = 'sometimes|string|min:20';
        }

        return $rules;
    }

    /**
     * Validasi khusus untuk upload dokumen (max 2MB)
     */
    public static function getDocumentValidationRules(): array
    {
        return [
            'document' => 'required|file|max:2048|mimes:pdf,doc,docx,jpg,jpeg,png',
            'document_type' => ['required', Rule::in(array_keys(CaseDocument::DOCUMENT_TYPES))],
            'description' => 'nullable|string|max:500'
        ];
    }

    /**
     * Cek apakah kasus sudah terlambat
     */
    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && $this->progress_level < 4;
    }

    /**
     * Hitung sisa hari deadline
     */
    public function getDaysUntilDue(): ?int
    {
        if (!$this->due_date) {
            return null;
        }

        return now()->diffInDays($this->due_date, false);
    }

    /**
     * Cek apakah kasus dalam zona bahaya (mendekati deadline)
     */
    public function isInDangerZone(): bool
    {
        $sisaHari = $this->getDaysUntilDue();
        return $sisaHari !== null && $sisaHari <= 3 && $sisaHari >= 0;
    }

    /**
     * Get progress level name
     */
    public function getProgressLevelNameAttribute(): string
    {
        return self::PROGRESS_LEVELS[$this->progress_level]['name'] ?? 'Tidak Diketahui';
    }

    /**
     * Get progress level description
     */
    public function getProgressDescriptionAttribute(): string
    {
        return self::PROGRESS_LEVELS[$this->progress_level]['description'] ?? '';
    }

    /**
     * Get case type name
     */
    public function getCaseTypeNameAttribute(): string
    {
        return $this->caseType ? $this->caseType->name : (self::CASE_TYPES[$this->case_type] ?? 'Tidak Diketahui');
    }

    /**
     * Get status name
     */
    public function getStatusNameAttribute(): string
    {
        return self::STATUS_OPTIONS[$this->status] ?? 'Tidak Diketahui';
    }

    /**
     * Get priority name
     */
    public function getPriorityNameAttribute(): string
    {
        return self::PRIORITY_OPTIONS[$this->priority] ?? 'Tidak Diketahui';
    }

    /**
     * Scope untuk filter berdasarkan role user
     */
    public function scopeForUser($query, $user)
    {
        if ($user->role === 'user') {
            return $query->where(function($q) use ($user) {
                $q->where('assigned_to', $user->id)
                  ->orWhere('created_by', $user->id);
            });
        }

        return $query;
    }

    /**
     * Scope untuk kasus yang terlambat
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->where('progress_level', '<', 4);
    }

    /**
     * Scope untuk kasus dalam zona bahaya
     */
    public function scopeInDangerZone($query)
    {
        return $query->whereBetween('due_date', [now(), now()->addDays(3)])
                    ->where('progress_level', '<', 4);
    }

    /**
     * Kirim notifikasi progress
     */
    private function sendProgressNotification(int $levelLama, int $levelBaru): void
    {
        // Implementasi notifikasi bisa ditambahkan di sini
        // Misalnya kirim email atau push notification
    }

    // Relationships
    public function penugasan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function dokumen(): HasMany
    {
        return $this->hasMany(CaseDocument::class);
    }

    public function logStatus(): HasMany
    {
        return $this->hasMany(CaseStatusLog::class)->orderBy('created_at', 'desc');
    }

    // Alias untuk compatibility
    public function assignedUser(): BelongsTo
    {
        return $this->penugasan();
    }

    public function creator(): BelongsTo
    {
        return $this->pembuat();
    }

    public function documents(): HasMany
    {
        return $this->dokumen();
    }

    public function statusLogs(): HasMany
    {
        return $this->logStatus();
    }

    public function parties(): HasMany
    {
        return $this->hasMany(CaseParty::class);
    }

    /**
     * Relationship dengan case type
     */
    public function caseType(): BelongsTo
    {
        return $this->belongsTo(CaseType::class, 'case_type', 'code');
    }

    /**
     * Boot model events
     */
    protected static function boot()
    {
        parent::boot();

        // Generate kode kasus saat membuat kasus baru
        static::creating(function ($kasusHukum) {
            if (empty($kasusHukum->case_code)) {
                $kasusHukum->case_code = $kasusHukum->generateCaseCode();
            }

            // Set created_by jika belum ada
            if (empty($kasusHukum->created_by) && auth()->check()) {
                $kasusHukum->created_by = auth()->id();
            }
        });

        // Log perubahan saat update
        static::updating(function ($kasusHukum) {
            if ($kasusHukum->isDirty('progress_level')) {
                // Progress level berubah, akan dicatat di updateProgress method
            }
        });
    }
}
