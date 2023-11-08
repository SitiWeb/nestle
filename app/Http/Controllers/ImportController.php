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
        
        $result = $this->prepare_head($rows);
        $data_head = $result['data'];
        
        // Skip the first 8 rows
        $rows = array_slice($rows, $result['header_row'] + 1);
        
        
        $i = 2;
        
        // Process the rows starting from row 9
        foreach ($rows as $row) {
            
            if (!empty($row[0])){
                if($row[0] == 'EXAMPLE'){
                    continue;
                }
               
                $data = $this->get_data($row, $data_head);
            }
            else{
                break;
            }
            
            $i++;
        }
    }

    public function mapKey($value)
    {
        $parts = explode('|', $value);

        $keyMap = [
            'unit number' => 'cf_unit_number',
            'code' => 'cf_nitr_location_code',
            'data source' => 'cf_nitr_data_source_code',
            'audit date' => 'cf_audit_date',
            'region' => 'cf_region',
            'nitr region' => 'cf_nitr_region',
            'nitr top 50 visibility ranking' => 'cf_nitr_top_50',
            'nitr top 46 ranking' => 'cf_nitr_top_46',
            'airport name' => 'airport_name',
            'airport code' => 'airport_code',
            'terminal' => 'terminal',
            'store' => 'store',
            'retailer' => 'retailer',
            'customer level  4' => 'cf_customer_level_4',
            'brand' => 'brand',
            'wallbay' => 'brand_item',
            'gondola' => 'brand_item',
            'kitkat bus' => 'brand_item',
            'fsu' => 'brand_item',
            'cash till' => 'brand_item',
            'lightbox' => 'brand_item',
            'others' => 'brand_item',
            'customer personalisation' => 'brand_item',
            'multibranded' => 'brand_item',
            'ba present at location' => 'cf_ba_present',
            'install date' => 'cf_install_date',
            'renovation date' => 'cf_renovation_date',
            'supplier carrying out audit' => 'cf_auditing_supplier',
            'person / technician carrying out audit' => 'cf_auditing_supplier_technician',
            'asset tag id number' => 'cf_asset_tag_number',
            'asset tag id' => 'cf_asset_tag_image',
            'unit condition' => 'cf_unit_condition',
            'sustainability feature' => 'cf_sustainability_feature',
            'fixturebuild' => 'dimensions_fixturebuild',
            'graphics/lightbox' => 'dimensions_graphics',
            'graphics/lightbox actual graphic size' => 'dimensions_graphics',
            'shelf strip' => 'dimensions_shelf',
            'screen' => 'dimensions_screen',
            'material / method recommended for update' => 'test',
            'other unit / location specific information - any other useful information to be noted here. feel free to add columns if needed for other measurements or information' => 'description',
            

            '' => 'empty_row',
        ];
        if (count($parts)> 1){
            if ()
            return $keyMap[trim($parts[0])] ?? 'unknown_key';
        }
        else{
            return $keyMap[trim($parts[0])] ?? 'unknown_key';
        }
        
    }

    public function prepare_head($rows){
        $data = [];
        foreach ($rows as $indexrow => $row){
            $skip = [
                '',
                'Good Condition - no obvious defects',
                'Requires Work - Unit is damaged and in need of repair (scratches, dents, peeling paint,)',
                'Requires Replacement - Unit is beyond easy repair and should be scheduled for replacement',
                'Yes',
                'No',
            ];
            if (in_array(trim($row[0]),$skip)){
                continue;
            }



            foreach($row as $index => $column){
                $result = preg_replace('/\([^)]*\)/', '', $column);
                $result = str_replace('?', '', $result);
                $value = trim(strtolower($result));
                $key = $this->mapKey($value);
                if ($key == 'empty_row'){
                    continue;
                }
                $data[$index] = ['key' => $key];
                
                if ($key == 'unknown_key'){
                    dd($value);
                }
                
            }
            break;
        }
        $result = ['header_row' => $indexrow,
                    'data' => $data];
        
        return ($result);
        
    }

    public function get_data($row, $head){
        $data = [];
        foreach($head as $index => $column){
            if ($column['key'] == 'brand_item'){
                continue;
            }
            if (!$row[$index]){
                continue;
            }
            // Define a regular expression pattern for date (mm/dd/yy or mm/dd/yyyy)
            $datePattern = '/^(0?[1-9]|1[0-2])\/(0?[1-9]|[12][0-9]|3[01])\/(\d{2}|\d{4})$/';

            if (preg_match($datePattern, $row[$index])) {
                $data[$column['key']] =  $this->prepare_date($row[$index]);
            } else {
                $data[$column['key']] =  $this->prepare_value($row[$index]);
            }
            
        }
        
        $this->import_unit($data);
    }

    public function prepare_value($data){
        if ($data == 'N/A'){
            return null;
        }
        return $data;
    }

    public function prepare_date($data){
        if ($data !== 'N/A'){
            $date = \DateTime::createFromFormat('m/d/y', $data);
            if ($date){
                $databaseDate = $date->format('Y-m-d');
                return $databaseDate;
            }
        }
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
        if (isset( $unit_data['cf_nitr_data_source_code'])){
            $unit_title = $unit_data['cf_unit_number'] . '_' . $unit_data['cf_nitr_data_source_code'];
        }
        else{
            $unit_title = $unit_data['cf_unit_number'];
        }
        
        if (!isset($unit_data['description'])){
            $unit_data['description'] = '';
        }

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
        dd($unit_data);
        if(isset($unit_data['dimensions'])){          
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
       
    }

    public function process_brand($data)
    {
        if (!isset($data['brand'])){
            dd($data);
        }
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
 
        if (!isset($data['store'])){
            $store = '-';
            $slug =  Str::slug($store);
        }
        else{
            $store = $data['store'];
            $slug =  Str::slug($data['store']);
        }

        if (!isset($data['retailer'])){
            $retailer = '-';
        }
        else{
            $retailer = $data['retailer'];
        }
        if (!isset($data['terminal'])){
            $terminal = '-';
        }
        else{
            $terminal = $data['terminal'];
        }
        
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
