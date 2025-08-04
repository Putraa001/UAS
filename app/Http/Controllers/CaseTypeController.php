<?php

namespace App\Http\Controllers;

use App\Models\CaseType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CaseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', CaseType::class);

        $query = CaseType::with(['creator', 'updater'])->ordered();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $caseTypes = $query->paginate(15);

        return view('case-types.index', compact('caseTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', CaseType::class);
        
        return view('case-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', CaseType::class);

        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:case_types,code|regex:/^[A-Z]{1,10}$/',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ], [
            'code.regex' => 'Kode harus berupa huruf kapital tanpa spasi atau karakter khusus.',
            'code.unique' => 'Kode jenis kasus sudah digunakan.',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $caseType = CaseType::create($validated);

        return redirect()->route('case-types.index')
            ->with('success', 'Jenis kasus "' . $caseType->name . '" berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CaseType $caseType)
    {
        $this->authorize('view', $caseType);

        $caseType->load(['creator', 'updater', 'legalCases' => function($query) {
            $query->latest()->take(10);
        }]);

        return view('case-types.show', compact('caseType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CaseType $caseType)
    {
        $this->authorize('update', $caseType);

        return view('case-types.edit', compact('caseType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CaseType $caseType)
    {
        $this->authorize('update', $caseType);

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:10', 'regex:/^[A-Z]{1,10}$/', Rule::unique('case_types', 'code')->ignore($caseType)],
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ], [
            'code.regex' => 'Kode harus berupa huruf kapital tanpa spasi atau karakter khusus.',
            'code.unique' => 'Kode jenis kasus sudah digunakan.',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $caseType->update($validated);

        return redirect()->route('case-types.index')
            ->with('success', 'Jenis kasus "' . $caseType->name . '" berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CaseType $caseType)
    {
        $this->authorize('delete', $caseType);

        // Check if case type is used by any legal cases
        if ($caseType->legalCases()->exists()) {
            return back()->withErrors(['error' => 'Jenis kasus tidak dapat dihapus karena masih digunakan oleh kasus hukum.']);
        }

        $name = $caseType->name;
        $caseType->delete();

        return redirect()->route('case-types.index')
            ->with('success', 'Jenis kasus "' . $name . '" berhasil dihapus.');
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(CaseType $caseType)
    {
        $this->authorize('update', $caseType);

        $caseType->is_active = !$caseType->is_active;
        $caseType->save();

        $status = $caseType->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', 'Jenis kasus "' . $caseType->name . '" berhasil ' . $status . '.');
    }
}
