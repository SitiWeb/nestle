<?php

namespace App\Http\Controllers;
use PhpOffice\PhpSpreadsheet\IOFactory;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Brand;
use App\Models\Unit;
use App\Models\Location;
use App\Models\Shelf;

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
            return redirect()->route('units.overview')->with('success', 'File uploaded successfully');

        } 
    }

    public function run_import($absolutePath){

        

        $spreadsheet = IOFactory::load($absolutePath);
        $worksheet = $spreadsheet->getActiveSheet();

        $rows = $worksheet->toArray();
    
        // Skip the first 8 rows
        $rows = array_slice($rows, 8);
        $i = 9;
        // Process the rows starting from row 9
        foreach ($rows as $row) {
      
            if (!empty($row[0])){
                $data = $this->get_data($row);
            }
            else{
                break;
            }
            
            $i++;
        }
    
        

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
            $data['cf_unit_number'] = $this->prepare_value($row[0]);
            
        }

        if (isset($row[1])){
            $data['cf_nitr_location_code'] = $this->prepare_value($row[1]);
        }

        if (isset($row[2])){
            $data['cf_nitr_data_source_code'] = $this->prepare_value($row[2]);
        }

        if (isset($row[5])){
            $data['cf_region'] = $this->prepare_value($row[5]);
        }

        if (isset($row[6])){
            $data['cf_nitr_region'] = $this->prepare_value($row[6]);
        }

        if (isset($row[7])){
            $data['cf_nitr_top_50'] = $this->prepare_value($row[7]);
        }

        if (isset($row[8])){
            $data['cf_nitr_top_46'] = $this->prepare_value($row[8]);
        }

        if (isset($row[9])){
            $data['airport_name'] = $this->prepare_value($row[9]);
        }

        if (isset($row[10])){
            
            $data['cf_unit_type'] = $this->prepare_value($row[10]);
        }

        if (isset($row[11])){
            $data['airport_code'] = $this->prepare_value($row[11]);
        }

        if (isset($row[12])){
            $data['terminal'] = $this->prepare_value($row[12]);
        }

        if (isset($row[13])){
            $data['store'] = $this->prepare_value($row[13]);
        }

        if (isset($row[14])){
            $data['retailer'] = $this->prepare_value($row[14]);
        }

        if (isset($row[15])){
            $data['cf_customer_level_4'] = $this->prepare_value($row[15]);
        }

        if (isset($row[16])){
            $data['brand'] = $this->prepare_value($row[16]);
        }

        
        if (isset($row[26])){
            $data['cf_ba_present'] = $this->prepare_value($row[26]);
        }
        if (isset($row[27])){
            if ($row[27] !== 'N/A'){
                $date = \DateTime::createFromFormat('m/d/y', $row[27]);
                if ($date){
                    $databaseDate = $date->format('Y-m-d');
                    $data['cf_install_date'] = $databaseDate;
                }
                
                
            }
           
       
         
        }
        if (isset($row[28])){
            if ($row[28] !== 'N/A'){
                $date = \DateTime::createFromFormat('m/d/y', $row[28]);
                if ($date){
                    $databaseDate = $date->format('Y-m-d');
                    $data['cf_renovation_date'] = $databaseDate;
                }
                
            }
           
     
        }
        if (isset($row[29])){
            if ($row[29] !== 'N/A'){
                $date = \DateTime::createFromFormat('m/d/y', $row[29]);
                if ($date){
                    $databaseDate = $date->format('Y-m-d');
                    $data['cf_audit_date'] = $databaseDate;
                }
                
            }
            
        }




        if (isset($row[30])){
            $data['cf_auditing_supplier'] = $this->prepare_value($row[30]);
        }

        if (isset($row[31])){
            $data['cf_auditing_supplier_technician'] = $this->prepare_value($row[31]);
        }

        if (isset($row[33])){
            $data['cf_asset_tag_number'] = $this->prepare_value($row[33]);
        }

        if (isset($row[35])){
            $data['cf_unit_condition'] = $this->prepare_value($row[35]);
        }

        if (isset($row[36])){
   
            $data['sustainability_feature'] = $this->prepare_value($row[36]);
        }
        $dimensions = [];
        
        if (isset($row[37])){
            
            $result = $this->prepare_dimensions($row[37] , 'fixturebuild', $row[38], 'General Dimensions');
            if ($result){
                $dimensions[] = $result;
            }
        }
 
        if (isset($row[39])){
            $result = $this->prepare_dimensions($row[39] , 'graphics', $row[47] , 'Graphics visual 1');
            if ($result){
                $dimensions[] = $result;
            }
        }

        if (isset($row[40])){
            $result = $this->prepare_dimensions($row[40] , 'graphics', $row[47],  'Graphics actual 1');
            if ($result){
                $dimensions[] = $result;
            }
        }

        if (isset($row[41])){
            $result = $this->prepare_dimensions($row[41] , 'graphics', $row[47],  'Graphics visual 2');
            if ($result){
                $dimensions[] = $result;
            }
        }

        if (isset($row[42])){
            $result = $this->prepare_dimensions($row[42] , 'graphics', $row[47],  'Graphics actual 2');
            if ($result){
                $dimensions[] = $result;
            }
        }

        if (isset($row[43])){
            $result = $this->prepare_dimensions($row[43] , 'graphics', $row[47],  'Graphics actual 3');
            if ($result){
                $dimensions[] = $result;
            }
        }

        if (isset($row[44])){
            $result = $this->prepare_dimensions($row[44] , 'graphics', $row[47],  'Graphics visual 3');
            if ($result){
                $dimensions[] = $result;
            }
        }

        if (isset($row[45])){
            $result = $this->prepare_dimensions($row[45] , 'graphics', $row[47],  'Graphics actual 4');
            if ($result){
                $dimensions[] = $result;
            }
        }

        if (isset($row[46])){
            $result = $this->prepare_dimensions($row[46] , 'graphics', $row[47],  'Graphics visual 4');
            if ($result){
                $dimensions[] = $result;
            }
        }

        if (isset($row[48])){
            $result = $this->prepare_dimensions($row[48] , 'shelf', $row[55],  'Shelf 1');
            if ($result){
                $dimensions[] = $result;
            }
        }

        if (isset($row[49])){
            $result = $this->prepare_dimensions($row[49] , 'shelf', $row[55],  'Shelf 2');
            if ($result){
                $dimensions[] = $result;
            }
        }

        if (isset($row[50])){
            $result = $this->prepare_dimensions($row[50] , 'shelf', $row[55],  'Shelf 3');
            if ($result){
                $dimensions[] = $result;
            }
        }

        if (isset($row[51])){
            $result = $this->prepare_dimensions($row[51] , 'shelf', $row[55],  'Shelf 4');
            if ($result){
                $dimensions[] = $result;
            }
        }

        if (isset($row[52])){
            $result = $this->prepare_dimensions($row[52] , 'shelf', $row[55],  'Shelf 5');
            if ($result){
                $dimensions[] = $result;
            }
        }

        if (isset($row[53])){
            $result = $this->prepare_dimensions($row[53] , 'shelf', $row[55],  'Shelf 6');
            if ($result){
                $dimensions[] = $result;
            }
        }
   

        if (isset($row[56])){
            $result = $this->prepare_dimensions($row[56] , 'screen', $row[58],  'Screen 1');
            if ($result){
                $dimensions[] = $result;
            }
        }

        if (isset($row[57])){
            $result = $this->prepare_dimensions($row[57] , 'screen', $row[58],  'Screen 2');
            if ($result){
                $dimensions[] = $result;
            }
        }
        
        if (isset($row[59])){
            $data['description'] = $this->prepare_value($row[59]);
        }
        $data['dimensions'] = $dimensions;
        $this->import_unit($data);
    }

    public function prepare_value($data){
        if ($data == 'N/A'){
            return null;
        }
        return $data;
    }
    
    public function prepare_dimensions($data, $type, $comment = false, $name = ''){
        $result = false;
        if ($data && $data != 'N/A'){
            // Split the string by "x" and trim any whitespace
            $values = array_map('trim', explode('x', $data));

            // Remove "mm" from each value and convert them to integers
            $dimensions = array_map(function($value) {
                return (int)str_replace('mm', '', $value);
            }, $values);

            // Map the values to their respective keys
            $dimensionKeys = ['width', 'height', 'length'];
            $result = [];

            foreach ($dimensionKeys as $index => $key) {
                $result[$key] = $dimensions[$index] ?? null;
            }
        }   

        if ($result){
           
            if ($comment && $comment != 'N/A'){
                $result['comment'] = ($comment);
            }
            else{
                $result['comment'] = '';
            }
            if ($type){
                $result['type'] = $type;
            }
            if ($index){
                $result['name'] = $name;
            }
        }   
        
        return $result;
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

  

        DB::transaction(function () use ($unit, $unit_data) {
            $metaToInsertOrUpdate = [];
        
            foreach ($unit_data as $key => $meta) {
             
                if ($meta && strpos($key, 'cf_') === 0) {
                    $metaToInsertOrUpdate[] = [
                        'meta_key' => $key,
                        'meta_value' => $meta,
                        'unit_id' => $unit->id
                    ];
                }
                
            }
        
            // Now perform batch insert or update
            foreach ($metaToInsertOrUpdate as $metaData) {
                $unit->meta()->updateOrCreate(
                    ['meta_key' => $metaData['meta_key'], 'unit_id' => $unit->id],
                    ['meta_value' => $metaData['meta_value']]
                );
            }
        
            $unit->save();
        });
        
        foreach ($unit_data['dimensions'] as $item) {
           
            Shelf::updateOrCreate(
                [
                    'name' => $item['name'],
                    'unit_id' => $unit->id,
                ],  // "name" is the column we're basing our update or create on
                [
                    'width' => $item['width'],
                    'height' => $item['height'],
                    'length' => $item['length'],
                    'type' => $item['type'],
                    'unit_id' => $unit->id,
                    'name' => $item['name'],
                    'comment' => $item['comment'],
                ]
            );
        }
       
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
