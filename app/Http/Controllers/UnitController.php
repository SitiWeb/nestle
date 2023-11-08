<?php

namespace App\Http\Controllers;
use Carbon\Carbon;


use Illuminate\Support\Facades\Storage;
use App\Models\Unit;
use App\Models\Image;
use App\Models\Brand;
use App\Models\Location;
use Illuminate\Http\Request;
use PgSql\Lob;
use League\Csv\Writer;
use Illuminate\Http\Response;
use DB;


class UnitController extends Controller
{
    public function index()
    {
        // Retrieve paginated units
        $units = Unit::paginate(50); // Change 10 to the desired number of units per page

        return view('units.index', compact('units'));
    } 

    public function create()
    {
        $locations = Location::all();
        $brands = Brand::all();
        return view('units.create', compact('locations','brands'));
    }

    public function store(Request $request)
    {
       
        $validatedData = $request->validate([
            'name' => 'required|string',
            'file_input' => 'file|max:2048', // Adjust the validation rules as needed
        ]);
        $selectedBrandIds = $request->input('brand_id');
        
        $unit = Unit::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'location_id' => $request->input('location_id'),
            // 'brand_id' => serialize($request->input('brand_id')),
        ]);
        $unit->brands()->attach($selectedBrandIds);

        // Handle uploaded images
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            foreach ($images as $image) {
                // Store the image and get its path
                $imagePath = $image->store('images', 'public'); // Update with your desired storage path

                // Create an Image model instance and associate it with the unit
                $unit->images()->create([
                    
                    'path' => $imagePath,
                    'filename' => basename($imagePath),
                ]);
            }
        }
        
       
    
        $file = $request->file('file_input');
        if ($file){
            $filePath = $file->store('public/files');
    
    
            $unit->files()->create([
                'name' => $request->input('file_name'),
                'filename' => $file->getClientOriginalName(),
                'path' => $filePath,
            ]);
        }
        
    
        // Other actions or redirects after successful file upload
    
        


        // Store meta values
        $metaData = $request->input('fields');
        if ($metaData && is_array($metaData)) {

            foreach ($metaData as $key => $meta) {
                if (is_array($meta)) {
                    foreach ($meta as $type => $value) {
                        if ($value){
                            $unit->meta()->create([
                                'meta_key' => $key.'_'.$type,
                                'meta_value' => $value,
                            ]);
                        }
                    }
                }
                else{
                  if ($meta){
                      $unit->meta()->create([
                          'meta_key' => $key,
                          'meta_value' => $meta,
                      ]);
                  }
                }
                
            }
        }
        if ($request->has('create_dimensions')){
            return redirect()->route('units.shelves',['unit'=>$unit->id]);
        }
        return redirect()->route('units.index');
    }

    public function show($id)
    {

        // Retrieve a specific unit
        $unit = Unit::with(['brands','shelves'])->findOrFail($id);
     
        $settings = $unit->meta()->get()->pluck('meta_value', 'meta_key');
        
        $base = [
            ['label' => 'Unit number',                  'key' => 'cf_unit_number'],
            ['label' => 'Asset Tag Number',             'key' => 'cf_asset_tag_number'],
            ['label' => 'NITR Location Code',           'key' => 'cf_nitr_location_code'],
            ['label' => 'NITR Data Source Code',        'key' => 'cf_nitr_data_source_code'],
            ['label' => 'Install Date',                 'key' => 'cf_install_date'],
            ['label' => 'Audit Date',                   'key' => 'cf_audit_date'],
            ['label' => 'Renovation Date',              'key' => 'cf_renovation_date'],
            ['label' => 'Region',                       'key' => 'cf_region'],
            ['label' => 'NITR Region',                  'key' => 'cf_nitr_region'],
            ['label' => 'NITR Top 50 Ranking',          'key' => 'cf_nitr_top_50'],
            ['label' => 'NITR Top 46 Ranking',          'key' => 'cf_nitr_top_46'],
            ['label' => 'Customer level 4',          'key' => 'cf_customer_level_4'],
            ['label' => 'Airport/Downtown Store Name',  'key' => 'airport_store_name'],
            ['label' => 'Airport Code',                 'key' => 'cf_nitr_location_code'],
            ['label' => 'Terminal',                     'key' => 'terminal'],
            ['label' => 'Store Name',                   'key' => 'store_name'],
            ['label' => 'Retailer',                     'key' => 'retailer'],
            ['label' => 'Brand',                        'key' => 'brand'],
            ['label' => 'HR',                           'key' => ''],

            ['label' => 'Unit Type',                    'key' => 'cf_unit_type'],
            ['label' => 'BA Present',                   'key' => 'cf_ba_present'],
            ['label' => 'Auditing Supplier',            'key' => 'cf_auditing_supplier'],
            ['label' => 'Auditing Supplier Technician', 'key' => 'cf_auditing_supplier_technician'],
            ['label' => 'Unit Condition at Time of Audit', 'key' => 'cf_unit_condition'],
            ['label' => '', 'key' => 'dimensions_fixturebuild'],
            ['label' => '', 'key' => 'dimensions_graphics'],
            ['label' => '', 'key' => 'dimensions_backpanel'],
            ['label' => '', 'key' => 'dimensions_shelf'],
            ['label' => '', 'key' => 'dimensions_screen'],
            ['label' => 'HR',                           'key' => ''],


        ];
        $meta = [];
        $unit->meta = $settings;

        foreach ($base as $row) {
           
            if (strpos($row['key'], 'dimensions_') === 0) {
                $dimensionKey = substr(($row['key']), strlen('dimensions_'));
                foreach($unit->shelves as $shelf){
                    if ($shelf->type != $dimensionKey){
                        continue;
                    }
                    $dimensions = [];
                    $label = [];
                    if ($shelf->width) {
                        $dimensions[] = (int)$shelf->width . 'mm';
                        $label[] = 'L';
                    }

                    if ($shelf->height) {
                        $dimensions[] = (int)$shelf->height . 'mm';
                        $label[] = 'H';
                    }

                    if ($shelf->length) {
                        $dimensions[] = (int)$shelf->length . 'mm';
                        $label[] = 'D';
                    }

                    $str = implode(' x ', $dimensions);
                    $new_label = implode('x', $label);

                    $meta[] =  ['label' => $shelf->name.' ' .$row['label']. ' (' . $new_label.')'  , 'key' => $row['key'], 'value' => $str ];
               
                }
                
               
                
            }
            elseif(strpos($row['key'], 'cf_') === 0){
                $string = "example_date";

                $lastFiveChars = substr( $row['key'], -5);

                if ($lastFiveChars === "_date") {
                    if (isset($unit->meta[$row['key']])) {
                       

                        // Convert the date to a Carbon instance
                        $carbonDate = Carbon::parse($unit->meta[$row['key']]);

                        // Change the date format
                        $formattedDate = $carbonDate->format('d/m/Y');

                        if (!$formattedDate){
                            $formattedDate = '-';
                        }

                
                        $meta[] = ['label' => $row['label'], 'key' => $row['key'], 'value' => $formattedDate];
                    } else {
                        $formattedDate = '-';
                        $meta[] = ['label' => $row['label'], 'key' => $row['key'], 'value' => $formattedDate];
                    }
                    continue;
                } 
              
                if (isset($unit->meta[$row['key']])) {

                
                    $meta[] = ['label' => $row['label'], 'key' => $row['key'], 'value' => $unit->meta[$row['key']]];
                } else {
                    $meta[] = ['label' => $row['label'], 'key' => $row['key'], 'value' => '-'];
                }
                
            }
            else{
                switch($row['key']){
                    case ('terminal'):
                        if (isset($unit->location->terminal)){                    
                            $meta[] = ['label' => $row['label'], 'key' => $row['key'], 'value' => $unit->location->terminal];
                        }
                        break;
                    case ('retailer'):
                        if (isset($unit->location->retailer)){     
                        $meta[] = ['label' => $row['label'], 'key' => $row['key'], 'value' => $unit->location->retailer];
                    }
                        break;
                    case ('store_name'):
                        if (isset($unit->location->name)){     
                        $meta[] = ['label' => $row['label'], 'key' => $row['key'], 'value' => $unit->location->name];
                    }
                        break;
                    case ('airport_store_name'):
                        if (isset($unit->location->airport_store_name)){   
                        $meta[] = ['label' => $row['label'], 'key' => $row['key'], 'value' => $unit->location->airport_store_name];
                    }
                        break;
                    case ('brand'):
                        if (isset($unit->brand->name)){   
                        $meta[] = ['label' => $row['label'], 'key' => $row['key'], 'value' => $unit->brand->name];
                    }
                        break;
                    default:
                        $meta[] = ['label' => $row['label'], 'key' => $row['key'], 'value' => ''];
                        break;

                }
            }
            
            
        }
        
        return view('units.show', compact('unit', 'meta'));
    }

    public function edit($id)
    {
        
        // Retrieve a specific unit for editing
        $locations = Location::all();
        $brands = Brand::all();
        $unit = Unit::with('meta','files', 'shelves','brands')->find($id);
        $settings = $unit->meta()->get()->pluck('meta_value', 'meta_key');
        $combinedDimensions = [];

        foreach ($settings as $key => $value) {
            $combinedDimensions[$key] = $value;
        }
        
        $unit->meta = $combinedDimensions;

        //dd($unit->meta['cf_asset_tag_number']);
        //dd($unit->get_meta());
        return view('units.edit', compact('unit','locations','brands'));
    }

    public function update(Request $request, $id)
    {
   
        $validatedData = $request->validate([
            'name' => 'required|string',
            'file_input' => 'file|max:2048', // Adjust the validation rules as needed
        ]);

        $unit = Unit::findOrFail($id);

        $unit->update([
            'name' => $request->input('name'),
            'location_id' => $request->input('location_id'),
          //  'brand_id' => $request->input('brand_id'),
            'description' => $request->input('description'),
        ]);

        $selectedBrandIds = $request->input('brand_id');
        $unit->brands()->attach($selectedBrandIds);

        // Update meta values
        // Store meta values
        // Handle uploaded images
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            foreach ($images as $image) {
                // Store the image and get its path
                $imagePath = $image->store('images', 'public'); // Update with your desired storage path

                // Create an Image model instance and associate it with the unit
                $unit->images()->create([
                    'path' => $imagePath,
                    'filename' => basename($imagePath),
                ]);
            }
        }


        // Store meta values
        $metaData = $request->input('fields');
        if ($metaData && is_array($metaData)) {

            foreach ($metaData as $key => $meta) {
                if (is_array($meta)) {
                    foreach ($meta as $type => $value) {
                        if ($value){
                            $unit->meta()->updateOrCreate(
                                ['meta_key' => $key . '_' . $type, 'unit_id' => $unit->id],
                                ['meta_value' => $value]
                            );
                        }
                     
                    }
                } else {
                    if ($meta){
                        $unit->meta()->updateOrCreate(
                            ['meta_key' => $key, 'unit_id' => $unit->id],
                            ['meta_value' => $meta]
                        );
                    }
                }
            }
        }
       
        $file = $request->file('file_input');
        if ($file){
            $filePath = $file->store('public/files');

    
            $unit->files()->create([
                'name' => $request->input('file_name'),
                'filename' => $file->getClientOriginalName(),
                'path' => $filePath,
            ]);
        }
        

        return redirect()->route('units.index');
    }

    public function destroy($id)
    {
        // Delete a specific unit
        $unit = Unit::findOrFail($id);
        $unit->delete();

        return redirect()->route('units.index');
    }

    public function deleteImage(Unit $unit, $image)
    {
        // Find the image by its ID or any other identifier and delete it
        // You can customize this logic based on your image storage and model structure

        // Example: Assuming the image is stored in the 'images' directory

        $image = Image::findOrFail($image);
        $imagePath = $image->path;

        // Delete the image file from storage
        if (Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }

        // Delete the image row from the database
        $image->delete();

        // Delete the image data from the unit or any other related model
        // Example: If the unit has an 'images' relationship, you can remove the image from there
        // $unit->images()->where('id', $image)->delete();

        // Perform any other necessary actions related to image deletion

        return redirect()->back()->with('success', 'Image deleted successfully.');
    }
    public function overview(Request $request)
    {

        $extra = $this->get_values();
        $locations = Location::all();
        $brands = Brand::all();
        $query = Unit::query();
        $filter_data = [
            'brands' => $brands,
            'locations' => $locations,
            'extras' => $extra
        ];

        $units = Unit::applyFilters($request)->with('brands', 'location')->paginate(18);
     
        $units->each(function ($unit) {
            $settings = $unit->meta()->get()->pluck('meta_value', 'meta_key');
            $unit->meta = $settings;
            
        });
        $totalResults = $units->total();
        return view('units.overview', compact('units','filter_data','totalResults'));
    }

    public function bybrand()
    {
        $brands = Brand::all(); // Assuming you have a Brand model
        
        return view('brands', compact('brands'));
    }

    public function download($filename)
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login'); // Redirect to the login page
        }

        // Retrieve the file path based on the filename
        $filePath = 'public/files/' . $filename;
        if ($filePath){
            // Generate the download response
            return Storage::download($filePath);
        }
       
    }

    public function get_values(){
        $data = [];
       
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
        ->orderBy('country', 'asc')
        ->pluck('country');
        $data [] = [
            'label' => 'country',
            
            'id' => 'country',
            'data' => $distinctValuescountry,  
        ];

      
        return ($data);
    }
    public function exportToCsv(Request $request, $id = null)
    {
        if ($request->unit_id){
            $units= Unit::where('id', $request->unit_id)->get();
        }
        else{
            $units = Unit::applyFilters($request)->with('brands', 'location', 'meta')->get();
        }
        // Create a new CSV writer instance
        
        $csv = Writer::createFromFileObject(new \SplTempFileObject());
        $units->each(function ($unit) {
            $settings = $unit->meta()->get()->pluck('meta_value', 'meta_key');
            $unit->meta = $settings;
        });
        
        // Insert the header row
        $csv->insertOne([
            'ID', 
            'Name', 
            'Data Source',
            'Audit date',
            'Region', 
            'NITR region',
            'NITR top 50',
            'Location name',
            'Airport',
            'Airport store',
            'Airport code',
            'Terminal',
            'Store',
            'Brand',
            'Ba representitive',
            'Install Date',
            'Renovation Date',
            'Auditing supplier',
            'auditing_supplier_technician',
            'Asset tag number',
            'Unit condition',
            'Fixturebuild',
            'Graphicbuild',
            'Shelfstripbuild',
            'Screenbuild'
        ]);


     
        // Insert data rows
        foreach ($units as $row) {
            $brand = $rowlocationretailer = $rowlocationname = $rowlocationterminal = $rowlocationairpot_code = $rowlocationairport_store_name = $region = $cf_audit_date = $cf_nitr_region = $cf_nitr_top_50 = $ba_present = $cf_install_date = $cf_renovation_date = $cf_auditing_supplier = $cf_auditing_supplier_technician = $cf_asset_tag_number = $cf_unit_condition = $cf_fixture_build = $cf_shelfstrip_build  = $cf_screen_build = $cf_graphic_build = '';
            $combinedDimensions = [];
            foreach ($row->meta as $key => $value) {
                if (strpos($key, 'cf_dimensions_') === 0) {
                    $dimensionKey = substr($key, strlen('cf_dimensions_'));
                    [$dimension, $property] = explode('_', $dimensionKey, 2);
                    $combinedDimensions[$dimension][$property] = $value;
                }
            }
      
            if (isset($row->meta['cf_region'])){
                $region = $row->meta['cf_region'];
            }
            if (isset($row->meta['cf_audit_date'])){
                $cf_audit_date = $row->meta['cf_audit_date'];
            }
            if (isset($row->meta['cf_nitr_region'])){
                $cf_nitr_region = $row->meta['cf_nitr_region'];
            }
            if (isset($row->meta['cf_nitr_top_50'])){
                $cf_nitr_top_50 = $row->meta['cf_nitr_top_50'];
            }
            if (isset($row->meta['cf_ba_present'])){
                $ba_present = $row->meta['cf_ba_present'];
            }
            if (isset($row->meta['cf_install_date'])){
                $cf_install_date = $row->meta['cf_install_date'];
            }
            if (isset($row->meta['cf_renovation_date'])){
                $cf_renovation_date = $row->meta['cf_renovation_date'];
            }
            if (isset($row->meta['cf_auditing_supplier'])){
                $cf_auditing_supplier = $row->meta['cf_auditing_supplier'];
            }
            if (isset($row->meta['cf_auditing_supplier_technician'])){
                $cf_auditing_supplier_technician = $row->meta['cf_auditing_supplier_technician'];
            }
            if (isset($row->meta['cf_asset_tag_number'])){
                $cf_asset_tag_number = $row->meta['cf_asset_tag_number'];
            }
            if (isset($row->meta['cf_unit_condition'])){
                $cf_unit_condition = $row->meta['cf_unit_condition'];
            }
            if (isset($combinedDimensions['fixturebuild']['length']) && isset($combinedDimensions['width']) && isset($combinedDimensions['height'])){
                dd($combinedDimensions['fixturebuild']);
                $cf_fixture_build = $combinedDimensions['fixturebuild']['length'] .'L x ' . $combinedDimensions['fixturebuild']['width'] .'W x '. $combinedDimensions['fixturebuild']['height'] .'H' ;
            }
            if (isset($combinedDimensions['graphicbuild']['length']) && isset($combinedDimensions['graphicbuild']['width']) && isset($combinedDimensions['graphicbuild']['height'])){
                $cf_graphic_build = $combinedDimensions['graphicbuild']['length'] .'L x ' . $combinedDimensions['graphicbuild']['width'] .'W x '. $combinedDimensions['graphicbuild']['height'] .'H' ;
            }
            if (isset($combinedDimensions['shelfstrip']['length']) && isset($combinedDimensions['shelfstrip']['width']) && isset($combinedDimensions['shelfstrip']['height'])){
                $cf_shelfstrip_build = $combinedDimensions['shelfstrip']['length'] .'L x ' . $combinedDimensions['shelfstrip']['width'] .'W x '. $combinedDimensions['shelfstrip']['height'] .'H' ;
            }
            if (isset($combinedDimensions['screen']['length']) && isset($combinedDimensions['screen']['width']) && isset($combinedDimensions['screen']['height']) ){
                $cf_screen_build = $combinedDimensions['screen']['length'] .'L x ' . $combinedDimensions['screen']['width'] .'W x '. $combinedDimensions['screen']['height'] .'H' ;
            }
            if (isset($row->location) ){
                $rowlocationname = $row->location->name;
                $rowlocationairport_store_name = $row->location->airport_store_name;
                $rowlocationairpot_code = $row->location->airpot_code;
                $rowlocationterminal = $row->location->terminal;
                $rowlocationname =  $row->location->name;
                $rowlocationretailer =       $row->location->retailer;
            }
            if (isset($row->brand) ){
                $brand = $row->brand->name;
            }
            
          
         
            $csv->insertOne([
                $row->id, 
                $row->name, 
                'Data source fiels missing',
                $cf_audit_date,
                $region,
                $cf_nitr_region,
                $cf_nitr_top_50,
                $rowlocationname,
                $rowlocationairport_store_name,
                $rowlocationairpot_code,
                $rowlocationterminal,
                $rowlocationname,
                $rowlocationretailer,
                $brand,
                $ba_present,
                $cf_install_date,
                $cf_renovation_date,
                $cf_auditing_supplier,
                $cf_auditing_supplier_technician,
                $cf_asset_tag_number,
                $cf_unit_condition,
                $cf_fixture_build,
                $cf_graphic_build,
                $cf_shelfstrip_build,
                $cf_screen_build 


                


            ]);
        } 
        
        $filename = 'units_' . Carbon::now()->format('Ymd_His') . '.csv';

        // Set the response headers
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        // Generate the response
        $response = new Response($csv->getContent(), 200, $headers);

        return $response;
    }
}
