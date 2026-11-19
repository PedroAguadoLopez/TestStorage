<?php

namespace App\Http\Controllers;

use App\Models\Doc;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocController extends Controller
{
    public function index()
    {
        try
        {
            $docs = Doc::latest()->get();

            return view('docs', compact('docs'));
        }
        catch(Exception $e)
        {
            session()->flash('error', $e->getMessage());

            return view('docs', ['docs' => collect()]);
        }
    }

    public function store(Request $request)
    {
        try
        {
            $request->validate([
                'name' => 'required|string|max:256',
                'doc' => 'required|file|mimes:pdf|max:5120'
            ]);

            $path = $request->file('doc')->store('docs', 'public');

            Doc::create([
                'name' => $request->name,
                'doc_path' => $path
            ]);

            return back()->with('success', 'Documento subido correctamente');
        }
        catch(Exception $e)
        {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, Doc $doc)
    {
        try
        {
            $request->validate([
                'doc' => 'required|file|mimes:pdf|max:5120'
            ]);

            if (Storage::disk('public')->exists($doc->doc_path))
            {
                Storage::disk('public')->delete($doc->doc_path);
            }

            $newPath = $request->file('doc')->store('docs', 'public');

            $doc->update([
                'doc_path' => $newPath
            ]);

            return back()->with('success', 'Documento actualizado correctamente');
        }
        catch(Exception $e)
        {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Doc $doc)
    {
        try
        {
            if (Storage::disk('public')->exists($doc->doc_path))
            {
                Storage::disk('public')->delete($doc->doc_path);
            }

            $doc->delete();

            return back()->with('success', 'Documento eliminado correctamente');
        }
        catch(Exception $e)
        {
            return back()->with('error', $e->getMessage());
        }
    }
}