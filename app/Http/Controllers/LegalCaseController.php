<?php

namespace App\Http\Controllers;

use App\Models\LegalCase;
use App\Models\User;
use App\Models\CaseParty;
use App\Models\CaseType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class LegalCaseController extends Controller
{
    // Removed constructor - middleware handled in routes

    public function index(Request $request)
    {
        $query = LegalCase::with(['assignedUser', 'creator'])
            ->orderBy('created_at', 'desc');

        if (auth()->user()->role === 'user') {
            $query->where(function($q) {
                $q->where('assigned_to', auth()->id())
                  ->orWhere('created_by', auth()->id());
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('case_code', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('case_type')) {
            $query->where('case_type', $request->case_type);
        }

        if ($request->filled('progress_level')) {
            $query->where('progress_level', $request->progress_level);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $cases = $query->paginate(15);

        return view('legal-cases.index', compact('cases'));
    }

    public function create()
    {
        $this->authorize('create', LegalCase::class);
        
        $users = User::whereIn('role', ['admin', 'manager', 'user'])->get();
        $caseTypes = CaseType::active()->ordered()->get();
        return view('legal-cases.create', compact('users', 'caseTypes'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', LegalCase::class);
        
        $activeCaseTypes = CaseType::active()->pluck('code')->toArray();
        $validated = $request->validate([
            'case_type' => ['required', Rule::in($activeCaseTypes)],
            'title' => 'required|string|max:255|min:10',
            'description' => 'required|string|min:20',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string|max:1000',
            'priority' => ['nullable', Rule::in(array_keys(LegalCase::PRIORITY_OPTIONS))],
            'estimated_resolution_days' => 'nullable|integer|min:1|max:365',
            
            // Primary Party Information
            'primary_party_name' => 'required|string|max:255',
            'primary_party_type' => ['required', Rule::in(array_keys(CaseParty::PARTY_TYPES))],
            'primary_identity_type' => ['nullable', Rule::in(array_keys(CaseParty::IDENTITY_TYPES))],
            'primary_identity_number' => 'nullable|string|max:50',
            'primary_phone' => 'nullable|string|max:20',
            'primary_address' => 'nullable|string|max:500',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['priority'] = $validated['priority'] ?? 'sedang';

        // Use database transaction to ensure both case and primary party are created together
        DB::beginTransaction();
        
        try {
            // Create the legal case
            $legalCase = LegalCase::create([
                'case_type' => $validated['case_type'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'assigned_to' => $validated['assigned_to'],
                'due_date' => $validated['due_date'],
                'notes' => $validated['notes'],
                'priority' => $validated['priority'],
                'estimated_resolution_days' => $validated['estimated_resolution_days'],
                'created_by' => $validated['created_by'],
            ]);

            // Create the primary party automatically
            if (!empty($validated['primary_party_name'])) {
                CaseParty::create([
                    'legal_case_id' => $legalCase->id,
                    'party_type' => $validated['primary_party_type'],
                    'name' => $validated['primary_party_name'],
                    'identity_type' => $validated['primary_identity_type'] ?? 'ktp',
                    'identity_number' => $validated['primary_identity_number'],
                    'phone' => $validated['primary_phone'],
                    'address' => $validated['primary_address'],
                    'status' => 'aktif',
                    'created_by' => auth()->id(),
                ]);
            }

            DB::commit();

            return redirect()->route('legal-cases.show', $legalCase)
                ->with('success', 'Kasus hukum berhasil dibuat dengan kode ' . $legalCase->case_code . '. Pihak utama (' . $validated['primary_party_name'] . ') telah ditambahkan.');
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->withErrors(['error' => 'Gagal membuat kasus: ' . $e->getMessage()]);
        }
    }

    public function show(LegalCase $legalCase)
    {
        $this->authorize('view', $legalCase);

        $legalCase->load(['assignedUser', 'creator', 'documents.uploader', 'statusLogs.changer', 'parties.creator']);

        return view('legal-cases.show', compact('legalCase'));
    }

    public function edit(LegalCase $legalCase)
    {
        $this->authorize('update', $legalCase);
        
        $users = User::whereIn('role', ['admin', 'manager', 'user'])->get();
        $caseTypes = CaseType::active()->ordered()->get();
        return view('legal-cases.edit', compact('legalCase', 'users', 'caseTypes'));
    }

    public function update(Request $request, LegalCase $legalCase)
    {
        $this->authorize('update', $legalCase);
        
        $rules = [
            'title' => 'required|string|max:255|min:10',
            'description' => 'required|string|min:20',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
            'priority' => ['nullable', Rule::in(array_keys(LegalCase::PRIORITY_OPTIONS))],
            'estimated_resolution_days' => 'nullable|integer|min:1|max:365'
        ];

        // Only admin can change status
        if (auth()->user()->role === 'admin') {
            $rules['status'] = [Rule::in(array_keys(LegalCase::STATUS_OPTIONS))];
        }

        $validated = $request->validate($rules);

        $legalCase->update($validated);

        return redirect()->route('legal-cases.show', $legalCase)
            ->with('success', 'Kasus hukum berhasil diperbarui');
    }

    public function destroy(LegalCase $legalCase)
    {
        $this->authorize('delete', $legalCase);
        
        $caseCode = $legalCase->case_code;
        $legalCase->delete();

        return redirect()->route('legal-cases.index')
            ->with('success', "Kasus {$caseCode} berhasil dihapus");
    }

    public function updateProgress(Request $request, LegalCase $legalCase)
    {
        $this->authorize('updateProgress', $legalCase);
        
        $validated = $request->validate([
            'progress_level' => 'required|integer|min:1|max:4',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            if ($legalCase->updateProgress($validated['progress_level'], $validated['notes'] ?? null)) {
                return back()->with('success', 'Progress berhasil diperbarui ke ' . 
                    LegalCase::PROGRESS_LEVELS[$validated['progress_level']]['name']);
            }
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return back()->with('error', 'Hanya admin yang dapat menurunkan level progress');
        }

        return back()->with('error', 'Gagal memperbarui progress');
    }
}
