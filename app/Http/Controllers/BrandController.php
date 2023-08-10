<?php

namespace App\Http\Controllers;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $brands = Brand::paginate(10);
        return view('brands.index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('brands.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:10000',
        ]);

        // Handle file upload
        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $imagePath = $image->store('brand-images', 'public');
            $validatedData['img'] = $imagePath;
        }
        $validatedData['slug'] = Str::slug($request->input('name'));
        Brand::create($validatedData);

        return redirect()->route('brands.index')->with('success', 'Brand created successfully.');
    }
 

    public function show(string $id)
    {
        $brand = Brand::findOrFail($id);
        // You can customize the view or return a JSON response based on your requirements
        return view('brands.edit', compact('brand'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $brand = Brand::findOrFail($id);
        // You can customize the view or return a JSON response based on your requirements
        return view('brands.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'img' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $imagePath = $image->store('brand-images', 'public');
            $validatedData['img'] = $imagePath;
        }

        $brand->update($validatedData);

        return redirect()->route('brands.index')->with('success', 'Brand updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brand = Brand::findOrFail($id);

        // Delete all associated units
        $brand->units()->delete();

        // Delete the brand
        $brand->delete();

        return redirect()->route('brands.index')->with('success', 'Brand deleted successfully.');
    }
}
