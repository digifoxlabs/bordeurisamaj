<?php

namespace App\Http\Controllers;

use App\Models\AdminDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminDocumentController extends Controller
{
    public function index()
    {
        return view('admin.documents', ['documents' => AdminDocument::latest()->paginate(12)]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:190'],
            'description' => ['nullable', 'string', 'max:2000'],
            'document' => ['required', 'file', 'max:51200'],
        ]);

        $file = $request->file('document');
        $path = $file->store('admin-documents', 'local');
        AdminDocument::create([
            'title' => $data['title'] ?: $file->getClientOriginalName(),
            'description' => $data['description'] ?? null,
            'original_name' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);

        return redirect()->route('admin.documents.index')->with('status', 'Document uploaded successfully.');
    }

    public function download(AdminDocument $document)
    {
        abort_unless(Storage::disk('local')->exists($document->path), 404);
        return Storage::disk('local')->download($document->path, $document->original_name);
    }

    public function destroy(AdminDocument $document)
    {
        Storage::disk('local')->delete($document->path);
        $document->delete();
        return redirect()->route('admin.documents.index')->with('status', 'Document deleted.');
    }
}
