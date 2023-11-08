<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Models\Location;
use App\Models\Unit;
use App\Models\Brand;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $locations = Location::paginate(50); // Change 10 to the desired number of items per page
       
        return view('locations.index', compact('locations'));
    }


    /** 
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jsonData = Storage::get('public/countries.json');
        $countries = json_decode($jsonData, true);
        return view('locations.create',compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $location = Location::create([
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name')),
            'airport_store_name' => $request->input('airport_store_name'),
            'airport_code' => $request->input('airport_code'),
            'terminal' => $request->input('terminal'),
            'retailer' => $request->input('retailer'),
            'country' => $request->input('country'),
        ]);

        return redirect()->route('locations.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $location = Location::findOrFail($id);
        $units = Unit::applyFilters($request)->with('brands', 'location')->where('location_id',$id)->paginate(96);
        $brands = Brand::all();
        $query = Unit::query();
        $extra = $this->get_values();
        $filter_data = [
            'brands' => $brands,
            
            'extras' => $extra
        ];
        $totalResults = $units->total();

        // You can customize the view or return a JSON response based on your requirements
        
        return view('locations.show', compact('location','units','filter_data','totalResults'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $location = Location::findOrFail($id);
        $jsonData = Storage::get('public/countries.json');
        $countries = json_decode($jsonData, true);
        // You can customize the view or return a JSON response based on your requirements
        return view('locations.edit', compact('location','countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $location = Location::findOrFail($id);
        
        // Validate and update the location data based on your requirements
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'airport_store_name' => 'required|string|max:255',
            'airport_code' => 'required|string|max:255',
            'terminal' => 'required|string|max:255', 
            'retailer' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            // Add more validation rules for other fields if needed
        ]);
        $location->update($validatedData);
       
        // Redirect or return a response based on your requirements
        return redirect()->route('locations.show', $location->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $location = Location::findOrFail($id);

        $location->units()->delete();
        // Delete the location or mark it as inactive based on your requirements
        $location->delete();

        // Redirect or return a response based on your requirements
        return redirect()->route('locations.index');
    }

    public function get_values(){
        $data = [];
        $cf_unit_type = DB::table('unit_meta')
        ->where('meta_key', '=', 'cf_unit_type')
        ->distinct()
        ->pluck('meta_value');

        $data [] = [
            'label' => 'Unit type',
            'id' => 'cf_unit_type',
            'data' => $cf_unit_type
        ];
        $cf_nitr_location_code = DB::table('unit_meta')
        ->where('meta_key', '=', 'cf_nitr_location_code')
        ->distinct()
        ->pluck('meta_value');
        $data [] = [
            'label' => 'NITR location code',
            'id' => 'cf_nitr_location_code',
            'data' => $cf_nitr_location_code,  
        ];
        $cf_nitr_top_50 = DB::table('unit_meta')
        ->where('meta_key', '=', 'cf_nitr_top_50')
        ->distinct()
        ->pluck('meta_value');

        $distinctname = DB::table('locations')
        ->distinct('name')
        ->pluck('name');
        $data [] = [
            'label' => 'Location name',
            'id' => 'location_name',
            'data' => $distinctname,  
        ];

        $distinctValuesairport_name = DB::table('locations')
        ->distinct('airport_store_name')
        ->pluck('airport_store_name');
        $data [] = [
            'label' => 'Airport name',
            'id' => 'airport_store_name',
            'data' => $distinctValuesairport_name,  
        ];

        $distinctValuesairport_code = DB::table('locations')
        ->distinct('airport_code')
        ->pluck('airport_code');
        $data [] = [
            'label' => 'Airport code',
            'id' => 'airport_code',
            'data' => $distinctValuesairport_code,  
        ];
        
        $distinctValuesretailer = DB::table('locations')
        ->distinct('retailer')
        ->pluck('retailer');
        $data [] = [
            'label' => 'Retailer',
            'id' => 'retailer',
            'data' => $distinctValuesretailer,  
        ];

        $distinctValuescountry = DB::table('locations')
        ->distinct('country')
        ->pluck('country');
        $data [] = [
            'label' => 'country',
            'id' => 'country',
            'data' => $distinctValuescountry,  
        ];

      
        return ($data);
    }
}
