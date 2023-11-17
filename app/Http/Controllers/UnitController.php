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

                    $meta[] =  ['label' => $shelf->name.' ' .$row['label']. ' (' . $new_label.')'  , 'key' => $row['key'], 'value' => $str.'<br><small>'.$shelf->comment.'</small>' ];
               
                }
                
               
                
            }
            elseif(strpos($row['key'], 'cf_') === 0){
         

                $lastFiveChars = substr( $row['key'], -5);

                if ($lastFiveChars === "_date") {
                    if (isset($unit->meta[$row['key']])) {
                       

                        try {
                            // Create a Carbon instance by parsing the date
                            $carbonDate = Carbon::createFromFormat('d/m/y', $unit->meta[$row['key']]);
                            
                            // Change the date format
                            $formattedDate = $carbonDate->format('d/m/Y');
                            
                            // Output the formatted date
                            echo $formattedDate;
                        } catch (\Exception $e) {
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
            $checkbox = False;
            foreach ($metaData as $key => $meta) {
                if ($key == 'cf_ba_present'){
                    $checkbox = True;
                }
                if (is_array($meta)) {
                    
                    foreach ($meta as $type => $value) {
                      
                            $unit->meta()->updateOrCreate(
                                ['meta_key' => $key . '_' . $type, 'unit_id' => $unit->id],
                                ['meta_value' => $value]
                            );
                        
                     
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
            if ($checkbox){
                $unit->meta()->updateOrCreate(
                            ['meta_key' => 'cf_ba_present', 'unit_id' => $unit->id],
                            ['meta_value' => '1']
                        );
            }
            else{
                $unit->meta()->updateOrCreate(
                            ['meta_key' => 'cf_ba_present', 'unit_id' => $unit->id],
                            ['meta_value' => '0']
                        );
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

        $units = Unit::applyFilters($request)->with('brands', 'location')->paginate(16);
     
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
            // Original unit data
            'Unit number',
            'Code',
            'Data source',
            'Audit date',
            'Region', 
            'NITR region',
            'NITR top 50 visibility ranking',
            'Airport name',
            'Terminal',
            'Store',
            'Retailer',
            'Ba representitive',
            'Brand',
            // Yellow data in CSV
            'Wallbay',
            'Gondola',
            'KitKat Bus',
            'FSU',
            'Cash Till',
            'Lightbox',
            'Others (Pillars)',
            'Customer Personalisation',
            'MULTIBRANDED',
            // Further unit data
            'BA present at location',
            'Install Date',
            'Renovation Date',
            'Audit date',
            'Auditing supplier',
            'auditing_supplier_technician',
            'Asset tag number',
            'Unit condition',
            // Builds
            'Fixturebuild',
            'Graphicbuild',
            'Shelfstripbuild',
            'Screenbuild'
        ]);


     
        // Insert data rows
        foreach ($units as $row) {
            $brandarray = array();
            $cf_graphic_str = $cf_fixture_str = $cf_screen_str = $cf_shelf_str = $cf_code = $cf_data_source = $brand = $rowlocationretailer = $rowlocationname = $rowlocationterminal = $rowlocationairpot_code = $rowlocationairport_store_name = $cf_region = $cf_audit_date = $cf_nitr_region = $cf_nitr_top_50 = $ba_present = $cf_install_date = $cf_renovation_date = $cf_auditing_supplier = $cf_auditing_supplier_technician = $cf_asset_tag_number = $cf_unit_condition = $cf_fixture_build = $cf_shelfstrip_build  = $cf_screen_build = $cf_graphic_build = '';
            $combinedDimensions = [];
            
           
            foreach($row->shelves as $shelf){
 
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
                if($shelf->type == "fixturebuild"){
                    if($cf_fixture_str == ''){
                        $cf_fixture_str = $shelf->name .': '. implode(' x ', $dimensions);
                    }else{
                        $cf_fixture_str = $shelf->name .': '. implode(' x ', $dimensions) .' | '. $cf_fixture_str;

                    }
                    
                }elseif($shelf->type == "graphics"){
                    if($cf_graphic_str == ''){
                        $cf_graphic_str = $shelf->name .': '. implode(' x ', $dimensions);
                    }else{
                        $cf_graphic_str = $shelf->name .': '. implode(' x ', $dimensions) .' | '. $cf_graphic_str;
                    }
                }elseif($shelf->type == "screen"){
                    if($cf_screen_str == ''){
                        $cf_screen_str = $shelf->name .': '. implode(' x ', $dimensions);
                    }else{
                        $cf_screen_str = $shelf->name .': '. implode(' x ', $dimensions) .' | '. $cf_screen_str;
                    }
                }elseif($shelf->type == "shelf"){
                    if($cf_shelf_str == ''){
                        $cf_shelf_str = $shelf->name .': '. implode(' x ', $dimensions);
                    }else{
                        $cf_shelf_str = $shelf->name .': '. implode(' x ', $dimensions) .' | '. $cf_shelf_str;
                    }
                }
                //dd($str);
            
            }
            
            if (isset($row->meta['cf_nitr_location_code'])){
                $cf_code = $row->meta['cf_nitr_location_code'];
            }
            if (isset($row->meta['cf_nitr_data_source_code'])){
                $cf_data_source = $row->meta['cf_nitr_data_source_code'];
            }
            if (isset($row->meta['cf_region'])){
                $cf_region = $row->meta['cf_region'];
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
            if (isset( $row->location->airport_code)){
                $rowlocationretailer = $row->location->airport_code;
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
            
            if (isset($row->location) ){
                $rowlocationname = $row->location->name;
                $rowlocationairport_store_name = $row->location->airport_store_name;
                $rowlocationterminal = $row->location->terminal;
                $rowlocationname =  $row->location->name;
                $rowlocationretailer =       $row->location->retailer;
            }
            if (isset($row->brands) ){
                
                foreach($brand = $row->brands as $brand){
                    array_push($brandarray, $brand->name);
                }
                $brands = implode(";", $brandarray);
                
            }
            $csv->insertOne([
                // Unit number 
                $row->id, 
                 // Code
                $cf_code,
                 // Data source
                $cf_data_source,
                // Audit date
                $cf_audit_date,
                // Region
                $cf_region,
                // NITR region
                $cf_nitr_region,
                // NITR top 50 visibility ranking
                $cf_nitr_top_50,
                // Airport name
                $rowlocationairport_store_name,
                // Terminal
                $rowlocationterminal,
                // Store
                $rowlocationname,
                // Retailer
                $rowlocationretailer,
                // BA reprensetative
                $ba_present,
                // Brand
                $brands,
                // 'Wallbay',
                'Brand field missing',
                // Gondola
                'Brand field missing',
                // KitKat Bus
                'Brand field missing',
                // FSU
                'Brand field missing',
                // Cash Till
                'Brand field missing',
                // Lightbox
                'Brand field missing',
                // Others (Pillars)
                'Brand field missing',
                // Customer Personalisation
                'Brand field missing',
                // MULTIBRANDED
                'Brand field missing',
                // BA present at location
                'BA present at location field missing',
                // Install date
                $cf_install_date,
                // Renovation date
                $cf_renovation_date,
                // Audit date
                $cf_audit_date,
                // Auditing supplier
                $cf_auditing_supplier,
                // Auditing supplier technician
                $cf_auditing_supplier_technician,
                // Asset tag nyumber
                $cf_asset_tag_number,
                // Unit condition
                $cf_unit_condition,
                // Fixture build
                $cf_fixture_str,
                // Graphic build
                $cf_graphic_str,
                // Shelfstrip build
                $cf_shelf_str,
                // Screen build
                $cf_screen_str 
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
