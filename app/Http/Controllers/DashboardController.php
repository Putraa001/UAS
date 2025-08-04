<?php

namespace App\Http\Controllers;

use App\Models\LegalCase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        
        // Base query berdasarkan role
        $baseQuery = LegalCase::query();
        if ($user->role === 'user') {
            $baseQuery->forUser($user);
        }

        // Hitung statistik dengan query terpisah untuk menghindari conflict
        $totalKasus = (clone $baseQuery)->count();
        $kasusAktif = (clone $baseQuery)->where('status', 'aktif')->count();
        $kasusSelesai = (clone $baseQuery)->where('status', 'ditutup')->count();
        
        // Query terpisah untuk kasus terlambat
        $kasusTerlambat = (clone $baseQuery)
            ->where('due_date', '<', now())
            ->where('progress_level', '<', 4)
            ->count();
            
        // Query terpisah untuk kasus bahaya
        $kasusBahaya = (clone $baseQuery)
            ->whereBetween('due_date', [now(), now()->addDays(3)])
            ->where('progress_level', '<', 4)
            ->count();

        // Kasus terbaru
        $recentCases = (clone $baseQuery)
            ->with(['assignedUser', 'creator'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Statistik progress level dengan query sederhana
        $progressStats = [];
        for ($i = 1; $i <= 4; $i++) {
            $progressStats[$i] = (clone $baseQuery)->where('progress_level', $i)->count();
        }

        // Statistik jenis kasus
        $caseTypeStats = [];
        foreach (['KB', 'SN', 'CP', 'FR', 'LN'] as $type) {
            $caseTypeStats[$type] = (clone $baseQuery)->where('case_type', $type)->count();
        }

        // Statistik prioritas
        $priorityStats = [];
        foreach (['rendah', 'sedang', 'tinggi', 'kritis'] as $priority) {
            $priorityStats[$priority] = (clone $baseQuery)->where('priority', $priority)->count();
        }

        $data = [
            'user' => $user,
            'totalKasus' => $totalKasus,
            'kasusAktif' => $kasusAktif,
            'kasusSelesai' => $kasusSelesai,
            'kasusTerlambat' => $kasusTerlambat,
            'kasusBahaya' => $kasusBahaya,
            'recentCases' => $recentCases,
            'progressStats' => $progressStats,
            'caseTypeStats' => $caseTypeStats,
            'priorityStats' => $priorityStats
        ];

        return view('dashboard', $data);
    }
}
