<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Legal cases created by this user
     */
    public function createdCases()
    {
        return $this->hasMany(LegalCase::class, 'created_by');
    }

    /**
     * Legal cases assigned to this user
     */
    public function assignedCases()
    {
        return $this->hasMany(LegalCase::class, 'assigned_to');
    }

    /**
     * Documents uploaded by this user
     */
    public function uploadedDocuments()
    {
        return $this->hasMany(CaseDocument::class, 'uploaded_by');
    }
}
