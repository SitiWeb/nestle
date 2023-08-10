<?php

namespace App\Http\Controllers;
use PhpOffice\PhpSpreadsheet\IOFactory;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Brand;
use App\Models\Unit;
use App\Models\Location;

class ImportController extends Controller
{
    public function index(){
        return view('import.index');
    }

    public function upload(Request $request){
        $file = $request->file('file_input');
        $googleSheetUrl = $request->input('google_sheet_url');
    
        if ($file) {
            $filePath = $file->store('files/import');
            // Prepend the storage path to the file path
            $absolutePath = storage_path('app/' . $filePath);

            // Check if the file exists
            if (!file_exists($absolutePath)) {
                return redirect()->back()->with('error', 'File not found');
            }
            $this->run_import($absolutePath);
        } 
    }

    public function run_import($absolutePath){

        

        $spreadsheet = IOFactory::load($absolutePath);
        $worksheet = $spreadsheet->getActiveSheet();

        $rows = $worksheet->toArray();
    
        // Skip the first 8 rows
        $rows = array_slice($rows, 8);
    
        // Process the rows starting from row 9
        foreach ($rows as $row) {
            if (!empty($row[0])){
                $data = $this->get_data($row);
            }
            else{
                break;
            }
            
           
            // Insert the row into the database or perform other operations
            // Unit::create([
            //     'column1' => $row[0],
            //     'column2' => $row[1],
            //     // ...
            // ]);
        }
    
        return redirect()->back()->with('success', 'File uploaded successfully');
    }

    public function get_data($row){

        $data = [
            'cf_unit_number' => '',
            'cf_nitr_location_code' => '',
            'cf_nitr_data_source_code' => '',
            'cf_region' => '',
            'cf_nitr_region' => '',
            'cf_nitr_top_50' => '',
            'cf_nitr_top_46' => '',
            'store' => '',
            'retailer' => '',
            'cf_unit_type' => '',
            'cf_ba_present' => '',
            'cf_install_date' => '',
            'cf_audit_date' => '',
            'cf_audit_date' => '',
            'cf_auditing_supplier' => '',
            'cf_auditing_supplier_technician' => '',
            'cf_asset_tag_number' => '',
            'cf_unit_condition' => '',
            'sustainability_feature' => '',
            'dimensions' => '',
            'dimensions_comments' => '',
            'graphic_dimensions' => '',
            'actual_graphic_dimensions' => '',
            'shelf_dimensions_1' => '',
            'shelf_dimensions_2' => '',
            'shelf_dimensions_3' => '',
            'shelf_dimensions_4' => '',
            'shelf_dimensions_5' => '',
            'shelf_dimensions_6' => '',
            'shelf_material' => '',
            'shelf_comments' => '',
            'screen_dimensions_1' => '',
            'screen_dimensions_1' => '',
            'screen_comments' => '',
            'description' => '',
        ];

        if (isset($row[0])){
            $data['cf_unit_number'] = ($row[0]);
        }

        if (isset($row[1])){
            $data['cf_nitr_location_code'] = ($row[1]);
        }

        if (isset($row[2])){
            $data['cf_nitr_data_source_code'] = ($row[2]);
        }

        if (isset($row[5])){
            $data['cf_region'] = ($row[5]);
        }

        if (isset($row[6])){
            $data['cf_nitr_region'] = ($row[6]);
        }

        if (isset($row[7])){
            $data['cf_nitr_top_50'] = ($row[7]);
        }

        if (isset($row[8])){
            $data['cf_nitr_top_46'] = ($row[8]);
        }

        if (isset($row[9])){
            $data['airport_name'] = ($row[9]);
        }

        if (isset($row[10])){
            
            $data['cf_unit_type'] = ($row[10]);
        }

        if (isset($row[11])){
            $data['airport_code'] = ($row[11]);
        }

        if (isset($row[12])){
            $data['terminal'] = ($row[12]);
        }

        if (isset($row[13])){
            $data['store'] = ($row[13]);
        }

        if (isset($row[14])){
            $data['retailer'] = ($row[14]);
        }

        if (isset($row[15])){
            $data['cf_customer_level_4'] = ($row[15]);
        }

        if (isset($row[16])){
            $data['brand'] = ($row[16]);
        }

        
        if (isset($row[26])){
            $data['cf_ba_present'] = ($row[26]);
        }
        if (isset($row[27])){
            if ($row[27] !== 'N/A'){
                $date = \DateTime::createFromFormat('m/d/y', $row[27]);
                $databaseDate = $date->format('Y-m-d');
                $data['cf_install_date'] = $databaseDate;
                
            }
           
       
         
        }
        if (isset($row[28])){
            if ($row[28] !== 'N/A'){
                $date = \DateTime::createFromFormat('m/d/y', $row[28]);
                $databaseDate = $date->format('Y-m-d');
                $data['cf_renovation_date'] = $databaseDate;
            }
           
     
        }
        if (isset($row[29])){
            if ($row[29] !== 'N/A'){
                $date = \DateTime::createFromFormat('m/d/y', $row[29]);
                $databaseDate = $date->format('Y-m-d');
                $data['cf_audit_date'] = $databaseDate;
            }
            
        }




        if (isset($row[30])){
            $data['cf_auditing_supplier'] = ($row[30]);
        }

        if (isset($row[31])){
            $data['cf_auditing_supplier_technician'] = ($row[31]);
        }

        if (isset($row[33])){
            $data['cf_asset_tag_number'] = ($row[33]);
        }

        if (isset($row[34])){
            $data['cf_unit_condition'] = ($row[34]);
        }

        if (isset($row[35])){
            $data['sustainability_feature'] = ($row[35]);
        }

        if (isset($row[36])){
            $data['dimensions'] = ($row[36]);
        }

        if (isset($row[37])){
            $data['dimensions_comments'] = ($row[37]);
        }

        if (isset($row[38])){
            $data['graphic_dimensions'] = ($row[38]);
        }

        if (isset($row[39])){
            $data['actual_graphic_dimensions'] = ($row[39]);
        }

        if (isset($row[40])){
            $data['shelf_dimensions_1'] = ($row[40]);
        }
        
        if (isset($row[41])){
            $data['shelf_dimensions_2'] = ($row[41]);
        }

        if (isset($row[42])){
            $data['shelf_dimensions_3'] = ($row[42]);
        }
        if (isset($row[43])){
            $data['shelf_dimensions_4'] = ($row[43]);
        }
        if (isset($row[44])){
            $data['shelf_dimensions_5'] = ($row[44]);
        }
        if (isset($row[45])){
            $data['shelf_dimensions_6'] = ($row[45]);
        }
        if (isset($row[46])){
            $data['shelf_material'] = ($row[46]);
        }
        if (isset($row[47])){
            $data['shelf_comments'] = ($row[47]);
        }
        if (isset($row[48])){
            $data['screen_dimensions_1'] = ($row[48]);
        }

        if (isset($row[49])){
            $data['screen_dimensions_1'] = ($row[49]);
        }
        if (isset($row[50])){
            $data['screen_dimensions_2'] = ($row[50]);
        }
        if (isset($row[51])){
            $data['screen_comments'] = ($row[51]);
        }
        if (isset($row[52])){
            $data['description'] = ($row[52]);
        }
        $new_data = [];
        foreach($data as $key => $value){
            switch ($value){
                case "N/A":
                    $value = '';
                    break;
            }
            $new_data[$key] = $value;
        }
        $this->import_unit($new_data);
    }

    public function import_unit($unit_data){
        $unit_title = $unit_data['cf_unit_number'] . '_' . $unit_data['cf_nitr_data_source_code'];

        $brand_data = $this->process_brand($unit_data);
        $location_data = $this->process_location($unit_data);
        
        $unit = Unit::firstWhere('name', $unit_title);
        if (!$unit){
            $unit = Unit::create([
                'name' => $unit_title,
                'description' =>  $unit_data['description'],
                'location_id' => $location_data->id,
            ]);
        }else{
            $unit->update([
                'name' => $unit_title,
                'description' =>  $unit_data['description'],
                'location_id' => $location_data->id,
            ]);
        }
        
        $unit->brands()->attach($brand_data);

  

        foreach ($unit_data as $key => $meta) {
            if (is_array($meta)) {
                foreach ($meta as $type => $value) {
                    if ($value){
                        $unit->meta()->updateOrCreate([
                            'meta_key' => $key.'_'.$type,
                            'meta_value' => $value,
                        ]);
                    }
                }
            }
            else{
                if ($meta && strpos($key, 'cf_') === 0) {
                    
                    $unit->meta()->updateOrCreate(
                        ['meta_key' => $key, 'unit_id' => $unit->id],
                        ['meta_value' => $meta]
                    );
                    if ($key == 'cf_unit_type'){
                        dd($unit->meta()->get());
                    }
                }
            }
            
        }
        
        $unit->save();

       
    }

    public function process_brand($data)
    {
        $brandsString = $data['brand']; // Your string
        $brands = explode(',', $brandsString);
        $result = [];
        foreach ($brands as $brandName) {
            $brandName = trim($brandName); // Remove any extra whitespace

            // Search for the brand in the database
            $brand = Brand::firstWhere('name', $brandName);

            if ($brand){
                $result[] = $brand->id;
            }
            else{
                $brand = Brand::create(
                    [
                        'name' => $brandName,
                        'slug' => Str::slug($brandName),
                    ]
                );
                $result[] = $brand->id;
            }
            
        }
        return $result;
    }

    public function process_location($data){
        $store = $data['store'];
        $slug =  Str::slug($data['store']);
        $retailer = $data['retailer'];
        $terminal = $data['terminal'];
        $airport_name = $data['airport_name'];
        $airport_code = $data['airport_code'];

        $location = Location::firstWhere('name', $store);
        if (!$location){
            $location = Location::create([
                'name' => $store,
                'slug' => $slug,
                'retailer' => $retailer,
                'terminal' => $terminal,
                'airport_store_name' => $airport_name,
                'airport_code' => $airport_code,
            ]);
        }
        return $location;

    }
}
