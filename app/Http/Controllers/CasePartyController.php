<?php

namespace App\Http\Controllers;

use App\Models\CaseParty;
use App\Models\LegalCase;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CasePartyController extends Controller
{
    public function index(LegalCase $legalCase)
    {
        $this->authorize('view', $legalCase);
        
        $parties = $legalCase->parties()->with(['creator', 'updater'])->get();
        
        return view('case-parties.index', compact('legalCase', 'parties'));
    }

    public function create(LegalCase $legalCase)
    {
        $this->authorize('update', $legalCase);
        
        return view('case-parties.create', compact('legalCase'));
    }

    public function store(Request $request, LegalCase $legalCase)
    {
        $this->authorize('update', $legalCase);
        
        $validated = $request->validate([
            'party_type' => ['required', Rule::in(array_keys(CaseParty::PARTY_TYPES))],
            'name' => 'required|string|max:255',
            'identity_type' => ['required', Rule::in(array_keys(CaseParty::IDENTITY_TYPES))],
            'identity_number' => 'nullable|string|max:50',
            'gender' => ['nullable', Rule::in(array_keys(CaseParty::GENDER_OPTIONS))],
            'place_of_birth' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date|before:today',
            'marital_status' => ['nullable', Rule::in(array_keys(CaseParty::MARITAL_STATUS_OPTIONS))],
            'occupation' => 'nullable|string|max:255',
            'education' => 'nullable|string|max:255',
            'address' => 'required|string',
            'village' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            'monthly_income' => 'nullable|numeric|min:0',
            'debt_amount' => 'nullable|numeric|min:0',
            'collateral_description' => 'nullable|string',
            'collateral_value' => 'nullable|numeric|min:0',
            'status' => ['required', Rule::in(array_keys(CaseParty::STATUS_OPTIONS))],
            'notes' => 'nullable|string'
        ], [
            'party_type.required' => 'Tipe pihak harus dipilih',
            'name.required' => 'Nama lengkap harus diisi',
            'identity_type.required' => 'Jenis identitas harus dipilih',
            'address.required' => 'Alamat harus diisi',
            'date_of_birth.before' => 'Tanggal lahir harus sebelum hari ini'
        ]);

        $validated['legal_case_id'] = $legalCase->id;
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        $party = CaseParty::create($validated);

        return redirect()->route('case-parties.show', [$legalCase, $party])
            ->with('success', "Data {$party->party_type_name} '{$party->name}' berhasil ditambahkan");
    }

    public function show(LegalCase $legalCase, CaseParty $party)
    {
        $this->authorize('view', $legalCase);
        
        if ($party->legal_case_id !== $legalCase->id) {
            abort(404);
        }

        $party->load(['creator', 'updater']);
        
        return view('case-parties.show', compact('legalCase', 'party'));
    }

    public function edit(LegalCase $legalCase, CaseParty $party)
    {
        $this->authorize('update', $legalCase);
        
        if ($party->legal_case_id !== $legalCase->id) {
            abort(404);
        }
        
        return view('case-parties.edit', compact('legalCase', 'party'));
    }

    public function update(Request $request, LegalCase $legalCase, CaseParty $party)
    {
        $this->authorize('update', $legalCase);
        
        if ($party->legal_case_id !== $legalCase->id) {
            abort(404);
        }

        $validated = $request->validate([
            'party_type' => ['required', Rule::in(array_keys(CaseParty::PARTY_TYPES))],
            'name' => 'required|string|max:255',
            'identity_type' => ['required', Rule::in(array_keys(CaseParty::IDENTITY_TYPES))],
            'identity_number' => 'nullable|string|max:50',
            'gender' => ['nullable', Rule::in(array_keys(CaseParty::GENDER_OPTIONS))],
            'place_of_birth' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date|before:today',
            'marital_status' => ['nullable', Rule::in(array_keys(CaseParty::MARITAL_STATUS_OPTIONS))],
            'occupation' => 'nullable|string|max:255',
            'education' => 'nullable|string|max:255',
            'address' => 'required|string',
            'village' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            'monthly_income' => 'nullable|numeric|min:0',
            'debt_amount' => 'nullable|numeric|min:0',
            'collateral_description' => 'nullable|string',
            'collateral_value' => 'nullable|numeric|min:0',
            'status' => ['required', Rule::in(array_keys(CaseParty::STATUS_OPTIONS))],
            'notes' => 'nullable|string'
        ]);

        $validated['updated_by'] = auth()->id();
        
        $party->update($validated);

        return redirect()->route('case-parties.show', [$legalCase, $party])
            ->with('success', "Data {$party->party_type_name} '{$party->name}' berhasil diperbarui");
    }

    public function destroy(LegalCase $legalCase, CaseParty $party)
    {
        $this->authorize('update', $legalCase);
        
        if ($party->legal_case_id !== $legalCase->id) {
            abort(404);
        }

        $partyName = $party->name;
        $partyType = $party->party_type_name;
        
        $party->delete();

        return redirect()->route('case-parties.index', $legalCase)
            ->with('success', "Data {$partyType} '{$partyName}' berhasil dihapus");
    }
}