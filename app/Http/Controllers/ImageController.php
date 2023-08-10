<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Image;
class ImageController extends Controller
{
    public function index()
    {
        // Fetch all images from the database
        $images = Image::all();

        // Return the view to display the image library
        return view('images.index', compact('images'));
    }

    public function store(Request $request)
    {
        // Validate the uploaded image
        $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        // Upload and store the image
        $imagePath = $request->file('image')->store('images', 'public');

        // Create a new image record in the database
        $image = Image::create([
            'filename' => $request->file('image')->getClientOriginalName(),
            'path' => $imagePath,
        ]);

        // Redirect back with success message
        return redirect()->back()->with('success', 'Image uploaded successfully.');
    }

    public function destroy(Image $image)
    {
        // Delete the image file from storage
        \Storage::disk('public')->delete($image->path);

        // Delete the image record from the database
        $image->delete();

        // Redirect back with success message
        return redirect()->back()->with('success', 'Image deleted successfully.');
    }
    
    public function show($filename)
    {
        $path = storage_path('app/public/images/' . $filename);
        
        if (!Storage::disk('public')->exists('images/'.$filename)) {
            abort(404);
        }

        return response()->file($path);
    }
    public function show_brand($filename)
    {
        $path = storage_path('app/public/brand-images/' . $filename);
        
        if (!Storage::disk('public')->exists('brand-images/'.$filename)) {
            abort(404);
        }

        return response()->file($path);
    }
}