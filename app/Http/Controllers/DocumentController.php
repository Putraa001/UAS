<?php

namespace App\Http\Controllers;

use App\Models\LegalCase;
use App\Models\CaseDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function upload(Request $request, LegalCase $legalCase)
    {
        $this->authorize('view', $legalCase);
        
        $request->validate([
            'document' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png,txt,xlsx,xls|max:2048', // 2MB max
            'document_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500'
        ], [
            'document.required' => 'File dokumen harus dipilih',
            'document.mimes' => 'Format file yang diizinkan: PDF, DOC, DOCX, JPG, JPEG, PNG, TXT, XLSX, XLS',
            'document.max' => 'Ukuran file maksimal 2MB',
            'document_name.required' => 'Nama dokumen harus diisi'
        ]);

        try {
            $file = $request->file('document');
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            
            // Generate unique filename
            $fileName = $legalCase->case_code . '_' . time() . '_' . Str::random(8) . '.' . $extension;
            
            // Store file in storage/app/documents directory
            $filePath = $file->storeAs('documents', $fileName, 'local');
            
            // Save to database
            $document = CaseDocument::create([
                'legal_case_id' => $legalCase->id,
                'document_name' => $request->document_name,
                'filename' => $fileName, // Add this missing field
                'original_filename' => $originalName,
                'file_path' => $filePath,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'description' => $request->description,
                'uploaded_by' => auth()->id()
            ]);

            return back()->with('success', "Dokumen '{$request->document_name}' berhasil diupload");
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengupload dokumen: ' . $e->getMessage());
        }
    }

    public function download(CaseDocument $document)
    {
        $this->authorize('view', $document->legalCase);
        
        if (!Storage::disk('local')->exists($document->file_path)) {
            return back()->with('error', 'File tidak ditemukan');
        }

        return Storage::disk('local')->download(
            $document->file_path,
            $document->original_filename
        );
    }

    public function destroy(CaseDocument $document)
    {
        $this->authorize('update', $document->legalCase);
        
        try {
            // Delete file from storage
            if (Storage::disk('local')->exists($document->file_path)) {
                Storage::disk('local')->delete($document->file_path);
            }
            
            // Delete from database
            $documentName = $document->document_name;
            $document->delete();
            
            return back()->with('success', "Dokumen '{$documentName}' berhasil dihapus");
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus dokumen: ' . $e->getMessage());
        }
    }

    public function show(CaseDocument $document)
    {
        $this->authorize('view', $document->legalCase);
        
        if (!Storage::disk('local')->exists($document->file_path)) {
            return back()->with('error', 'File tidak ditemukan');
        }

        $fileContent = Storage::disk('local')->get($document->file_path);
        $mimeType = $document->mime_type;

        return response($fileContent)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline; filename="' . $document->original_filename . '"');
    }
}