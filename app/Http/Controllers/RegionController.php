<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Location;
class RegionController extends Controller
{
    public function index()
{



    $locations = location::all();
    $jsonData = Storage::get('public/countries.json');
    $countries = json_decode($jsonData, true);
    $countries_present = location::distinct()
                    ->pluck('country');

    $continents = [];
    
    $mapdata = [];
    foreach ($countries as $country) {
        
        $continent = $country['continent'];
        $countryName = $country['country'];
        $countryCode = $country['country_code'];
        if (! $countries_present->contains($countryName)) {
            continue;
        }
        
        if (!isset($continents[$continent])) {
            $continents[$continent] = [];
        }
        if (in_array($countryCode,$mapdata)){
            $count = $mapdata[$countryCode]['count']++;
            $mapdata_row = [
                'country' => $countryName,
                'continent' => $continent,
                'code' => $countryCode,
                'shops' => $count,
            ];
        }
        else{
            $mapdata_row = [
                'country' => $countryName,
                'continent' => $continent,
                'code' => $countryCode,
                'shops' => 1,
                'link' => route('units.overview').'?filter[country]='.$countryName,
            ];
        }
        $mapdata[$countryCode] = $mapdata_row;
        

        $continents[$continent][] = $countryName;
  
    }
    $data_points = ['data' => [
        'shops' => [
            'name' => 'Shops:',
            'format' => '{0}',
            'thresholdMax' => 100,
            'thresholdMax' => 0,
        ]
        ],
    
    'applyData' => 'shops',
    'values' =>  $mapdata,
    ];
    $data = [
        'targetElementID' => 'svgMap',
        'colorNoData' => '#E2E2E2',
        'colorMin' => '#000000',
        'colorMax' => '#000000',
        'data' => $data_points,

        
    ];
    $data = json_encode($data);

 

    // // Example usage
    // $africaCountries = $continents['Africa'];
    // $europeCountries = $continents['Europe'];

    // // Iterate over continents and countries
    // foreach ($continents as $continent => $countries) {
    //     echo "Continent: $continent\n";
    //     echo "Countries: " . implode(', ', $countries) . "\n\n";
    // }

        return view('map', compact('continents','locations','data'));
    }
}


// {
//     targetElementID: 'svgMap',
//     data: {
//         data: {
//         gdp: {
//             name: 'GDP per capita',
//             format: '{0} USD',
//             thousandSeparator: ',',
//             thresholdMax: 50000,
//             thresholdMin: 1000
//         },
//         change: {
//             name: 'Change to year before',
//             format: '{0} %'
//         }
//         },
//         applyData: 'gdp',
//         values: {
//         AF: {gdp: 587, change: 4.73},
//         AL: {gdp: 4583, change: 11.09},
//         DZ: {gdp: 4293, change: 10.01}
//         // ...
//         }
//     }
//     }