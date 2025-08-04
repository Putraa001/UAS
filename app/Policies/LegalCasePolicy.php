<?php

namespace App\Policies;

use App\Models\LegalCase;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LegalCasePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Semua authenticated user bisa melihat index
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LegalCase $legalCase): bool
    {
        // Admin dan manager bisa lihat semua
        if (in_array($user->role, ['admin', 'manager'])) {
            return true;
        }
        
        // User biasa hanya bisa lihat kasus yang ditugaskan atau dibuat sendiri
        return $legalCase->assigned_to === $user->id || $legalCase->created_by === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Hanya admin dan manager yang bisa membuat kasus
        return in_array($user->role, ['admin', 'manager']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LegalCase $legalCase): bool
    {
        // Admin dan manager bisa update semua kasus
        return in_array($user->role, ['admin', 'manager']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LegalCase $legalCase): bool
    {
        // Hanya admin yang bisa hapus kasus
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can update progress.
     */
    public function updateProgress(User $user, LegalCase $legalCase): bool
    {
        // Admin dan manager bisa update progress semua kasus
        if (in_array($user->role, ['admin', 'manager'])) {
            return true;
        }
        
        // User biasa hanya bisa update kasus yang ditugaskan kepadanya
        return $legalCase->assigned_to === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, LegalCase $legalCase): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, LegalCase $legalCase): bool
    {
        return $user->role === 'admin';
    }
}