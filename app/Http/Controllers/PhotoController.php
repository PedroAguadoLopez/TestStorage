<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function index()
    {
        try
        {
            $photos = Photo::latest()->get();

            return view('photos', compact('photos'));
        }
        catch(Exception $e)
        {
            session()->flash('error', $e->getMessage());

            return view('photos', ['photos' => collect()]);
        }
    }

    public function store(Request $request)
    {
        try
        {
            $request->validate([
                'name' => 'required|string|max:256',
                'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048'
            ]);

            $path = $request->file('image')->store('photos', 'public');

            Photo::create([
                'name' => $request->name,
                'image_path' => $path
            ]);

            return back()->with('success', 'Imagen subida correctamente');
        }
        catch(Exception $e)
        {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, Photo $photo)
    {
        try
        {
            $request->validate([
                'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048'
            ]);

            if (Storage::disk('public')->exists($photo->image_path))
            {
                Storage::disk('public')->delete($photo->image_path);
            }

            $newPath = $request->file('image')->store('photos', 'public');

            $photo->update([
                'image_path' => $newPath
            ]);

            return back()->with('success', 'Imagen actualizada correctamente');
        }
        catch(Exception $e)
        {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Photo $photo)
    {
        try
        {
            if (Storage::disk('public')->exists($photo->image_path))
            {
                Storage::disk('public')->delete($photo->image_path);
            }

            $photo->delete();

            return back()->with('success', 'Imagen eliminada correctamente');
        }
        catch(Exception $e)
        {
            return back()->with('error', $e->getMessage());
        }
    }
}
