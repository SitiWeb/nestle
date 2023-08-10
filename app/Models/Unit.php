<?php

namespace App\Models;
use App\Models\UnitMeta;
use App\Models\Location;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'name',
        'description',
        'location_id',
        'brand_id'
    ];

    public function meta()
    {
        return $this->hasMany(UnitMeta::class);
    }
    public function images()
    {
        return $this->hasMany(Image::class);
    }
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'unit_brand');
    }
    public function shelves()
    {
        return $this->hasMany(Shelf::class);
    }
    public function files()
    {
        return $this->hasMany(File::class);
    }
    public function scopeApplyFilters($query, $request)


    {



        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($searchQuery) use ($search) {
                $searchQuery->where('name', 'LIKE', "%$search%")
                    ->orWhere('description', 'LIKE', "%$search%")
                    ->orWhereHas('meta', function ($metaQuery) use ($search) {
                        $metaQuery->where(function ($metaSearchQuery) use ($search) {
                            $metaSearchQuery->where('meta_key', 'cf_asset_tag_number')
                                ->where('meta_value', 'LIKE', "%$search%");
                        })->orWhere(function ($metaSearchQuery) use ($search) {
                            $metaSearchQuery->where('meta_key', 'cf_nitr_location_code')
                                ->where('meta_value', 'LIKE', "%$search%");
                        })->orWhere(function ($metaSearchQuery) use ($search) {
                            $metaSearchQuery->where('meta_key', 'cf_auditing_supplier')
                                ->where('meta_value', 'LIKE', "%$search%");
                        })->orWhere(function ($metaSearchQuery) use ($search) {
                            $metaSearchQuery->where('meta_key', 'cf_unit_type')
                                ->where('meta_value', 'LIKE', "%$search%");
                        })->orWhere(function ($metaSearchQuery) use ($search) {
                            $metaSearchQuery->where('meta_key', 'cf_auditing_supplier_technician')
                                ->where('meta_value', 'LIKE', "%$search%");
                        });
                    })
                    ->orWhereHas('location', function ($locationQuery) use ($search) {
                        $locationQuery->where(function ($locationSearchQuery) use ($search) {
                            $locationSearchQuery->where('name', 'LIKE', "%$search%")
                                ->orWhere('airport_store_name', 'LIKE', "%$search%")
                                ->orWhere('airport_code', 'LIKE', "%$search%")
                                ->orWhere('terminal', 'LIKE', "%$search%")
                                ->orWhere('country', 'LIKE', "%$search%")
                                ->orWhere('retailer', 'LIKE', "%$search%");
                        });
                    })
                    ->orWhereHas('brand', function ($brandQuery) use ($search) {
                        $brandQuery->where(function ($brandSearchQuery) use ($search) {
                            $brandSearchQuery->where('name', 'LIKE', "%$search%");
                        });
                    });
            });
        }
        
       

        
        if ($request->filled('filter.brands')) {
            $brands = $request->input('filter.brands');
            
            if (!is_array($brands)) {
                $brands = [$brands];
            }
            
            $query->whereHas('brands', function ($query) use ($brands) {
                $query->whereIn('brands.id', $brands);
            });
        }
        


       
        if ($request->filled('filter.condition')) {
            $condition = $request->input('filter.condition');
            $query->whereHas('meta', function ($metaQuery) use ($condition) {
                $metaQuery->where('meta_key', 'cf_unit_condition')->where('meta_value', $condition);
            });
        }

        if ($request->filled('filter.airport_code')) {
            $condition = $request->input('filter.airport_code');
            $query->whereHas('location', function ($metaQuery) use ($condition) {
                $metaQuery->where('airport_code', $condition);
            });
        }

        if ($request->filled('filter.country')) {
            $condition = $request->input('filter.country');
            $query->whereHas('location', function ($metaQuery) use ($condition) {
                $metaQuery->where('country', $condition);
            });
        }

        if ($request->filled('filter.airport_store_name')) {
            $condition = $request->input('filter.airport_store_name');
            $query->whereHas('location', function ($metaQuery) use ($condition) {
                $metaQuery->where('airport_store_name', $condition);
            });
        }

        if ($request->filled('filter.retailer')) {
            $condition = $request->input('filter.retailer');
            $query->whereHas('location', function ($metaQuery) use ($condition) {
                $metaQuery->where('retailer', $condition);
            });
        }

        if ($request->filled('filter.location_name')) {
            $condition = $request->input('filter.location_name');
            $query->whereHas('location', function ($metaQuery) use ($condition) {
                $metaQuery->where('name', $condition);
            });
        }

        if ($request->filled('filter.cf_nitr_location_code')) {
            $condition = $request->input('filter.cf_nitr_location_code');
            $query->whereHas('meta', function ($metaQuery) use ($condition) {
                $metaQuery->where('meta_key', 'cf_nitr_location_code')->where('meta_value', $condition);
            });
        }

        if ($request->filled('filter.cf_unit_type')) {
            $condition = $request->input('filter.cf_unit_type');
            $query->whereHas('meta', function ($metaQuery) use ($condition) {
                $metaQuery->where('meta_key', 'cf_unit_type')->where('meta_value', $condition);
            });
        }

        if ($request->filled('filter.date_cf_install_date.date_start')) {
            $dateOperator = $request->input('filter.date_cf_install_date.type');
            $dateStart = $request->input('filter.date_cf_install_date.date_start');
            $dateEnd = $request->input('filter.date_cf_install_date.date_end');
    
            $query->whereHas('meta', function ($metaQuery) use ($dateOperator, $dateStart, $dateEnd) {
                $metaQuery->where('meta_key', 'cf_install_date');
        
                if ($dateOperator === 'before') {
                    $metaQuery->whereDate('meta_value', '<', $dateStart);
                } elseif ($dateOperator === 'after') {
                    $metaQuery->whereDate('meta_value', '>', $dateStart);
                } elseif ($dateOperator === 'between') {
                    $metaQuery->whereDate('meta_value', '>=', $dateStart)->whereDate('meta_value', '<=', $dateEnd);
                }
            }); 
        }
     
        if ($request->filled('filter.date_cf_audit_date.date_start')) {
            $dateOperator = $request->input('filter.date_cf_audit_date.type');
            $dateStart = $request->input('filter.date_cf_audit_date.date_start');
            $dateEnd = $request->input('filter.date_cf_audit_date.date_end');
                
            $query->whereHas('meta', function ($metaQuery) use ($dateOperator, $dateStart, $dateEnd) {
                $metaQuery->where('meta_key', 'cf_audit_date');
        
                if ($dateOperator === 'before' && $dateStart)  {
                    $metaQuery->whereDate('meta_value', '<', $dateStart);
                } elseif ($dateOperator === 'after' && $dateStart) {
                    $metaQuery->whereDate('meta_value', '>', $dateStart);
                } elseif ($dateOperator === 'between' && $dateStart && $dateEnd) {
                    $metaQuery->whereDate('meta_value', '>=', $dateStart)->whereDate('meta_value', '<=', $dateEnd);
                }
            }); 
        }
   
        if ($request->filled('filter.date_cf_renovation_date.date_start')) {
            $dateOperator = $request->input('filter.date_cf_renovation_date.type');
            $dateStart = $request->input('filter.date_cf_renovation_date.date_start');
            $dateEnd = $request->input('filter.date_cf_renovation_date.date_end');

            $query->whereHas('meta', function ($metaQuery) use ($dateOperator, $dateStart, $dateEnd) {
                $metaQuery->where('meta_key', 'cf_renovation_date');
        
                if ($dateOperator === 'before' && $dateStart) {
                    $metaQuery->whereDate('meta_value', '<', $dateStart);
                } elseif ($dateOperator === 'after' && $dateStart) {
                    $metaQuery->whereDate('meta_value', '>', $dateStart);
                } elseif ($dateOperator === 'between' && $dateStart && $dateEnd) {
                    $metaQuery->whereDate('meta_value', '>=', $dateStart)->whereDate('meta_value', '<=', $dateEnd);
                }
            }); 
        }
        
        /* dimensions cf_dimensions_fixturebuild */
        if ($request->filled('filter.cf_dimensions_fixturebuild.length_operator')) {
            $lengthOperator = $request->input('filter.cf_dimensions_fixturebuild.length_operator');
            $lengthValue1 = $request->input('filter.cf_dimensions_fixturebuild.length_value_1');
            $lengthValue2 = $request->input('filter.cf_dimensions_fixturebuild.length_value_2');
           
            $query->whereHas('meta', function ($metaQuery) use ($lengthOperator, $lengthValue1, $lengthValue2) {
                $metaQuery->where('meta_key', 'cf_dimensions_fixturebuild_length');
              
                if ($lengthOperator === 'smaller') {
                    $metaQuery->where('meta_value', '<', $lengthValue1);
                  
                } elseif ($lengthOperator === 'bigger') {
                    
                    $metaQuery->where('meta_value', '>', $lengthValue1);
      
                } elseif ($lengthOperator === 'between') {
                    $metaQuery->whereBetween('meta_value', [$lengthValue1, $lengthValue2]);
                }
                
            });
     
        }
        
        
        if ($request->filled('filter.cf_dimensions_fixturebuild.width_operator')) {
            $widthOperator = $request->input('filter.cf_dimensions_fixturebuild.width_operator');
            $widthValue1 = $request->input('filter.cf_dimensions_fixturebuild.width_value_1');
            $widthValue2 = $request->input('filter.cf_dimensions_fixturebuild.width_value_2');
            
            $query->whereHas('meta', function ($metaQuery) use ($widthOperator, $widthValue1, $widthValue2) {
                $metaQuery->where('meta_key', 'cf_dimensions_fixturebuild_width');
        
                if ($widthOperator === 'smaller') {
                    $metaQuery->where('meta_value', '<', $widthValue1);
                } elseif ($widthOperator === 'bigger') {
                    $metaQuery->where('meta_value', '>', $widthValue1);
                } elseif ($widthOperator === 'between') {
                    $metaQuery->whereBetween('meta_value', [$widthValue1, $widthValue2]);
                }
            });
        }
        
        if ($request->filled('filter.cf_dimensions_fixturebuild.height_operator')) {
            $heightOperator = $request->input('filter.cf_dimensions_fixturebuild.height_operator');
            $heightValue1 = $request->input('filter.cf_dimensions_fixturebuild.height_value_1');
            $heightValue2 = $request->input('filter.cf_dimensions_fixturebuild.height_value_2');
        
            $query->whereHas('meta', function ($metaQuery) use ($heightOperator, $heightValue1, $heightValue2) {
                $metaQuery->where('meta_key', 'cf_dimensions_fixturebuild_height');
        
                if ($heightOperator === 'smaller') {
                    $metaQuery->where('meta_value', '<', $heightValue1);
                } elseif ($heightOperator === 'bigger') {
                    $metaQuery->where('meta_value', '>', $heightValue1);
                } elseif ($heightOperator === 'between') {
                    $metaQuery->whereBetween('meta_value', [$heightValue1, $heightValue2]);
                }
            });
        }


        /* dimensions cf_dimensions_graphic */
        if ($request->filled('filter.cf_dimensions_graphic.length_operator')) {
            $lengthOperator = $request->input('filter.cf_dimensions_graphic.length_operator');
            $lengthValue1 = $request->input('filter.cf_dimensions_graphic.length_value_1');
            $lengthValue2 = $request->input('filter.cf_dimensions_graphic.length_value_2');
        
            $query->whereHas('meta', function ($metaQuery) use ($lengthOperator, $lengthValue1, $lengthValue2) {
                $metaQuery->where('meta_key', 'cf_dimensions_graphic_length');
        
                if ($lengthOperator === 'smaller') {
                    $metaQuery->where('meta_value', '<', $lengthValue1);
                } elseif ($lengthOperator === 'bigger') {
                    $metaQuery->where('meta_value', '>', $lengthValue1);
                } elseif ($lengthOperator === 'between') {
                    $metaQuery->whereBetween('meta_value', [$lengthValue1, $lengthValue2]);
                }
            });
     
        }
        
        if ($request->filled('filter.cf_dimensions_graphic.width_operator')) {
            $widthOperator = $request->input('filter.cf_dimensions_graphic.width_operator');
            $widthValue1 = $request->input('filter.cf_dimensions_graphic.width_value_1');
            $widthValue2 = $request->input('filter.cf_dimensions_graphic.width_value_2');
        
            $query->whereHas('meta', function ($metaQuery) use ($widthOperator, $widthValue1, $widthValue2) {
                $metaQuery->where('meta_key', 'cf_dimensions_graphic_width');
        
                if ($widthOperator === 'smaller') {
                    $metaQuery->where('meta_value', '<', $widthValue1);
                } elseif ($widthOperator === 'bigger') {
                    $metaQuery->where('meta_value', '>', $widthValue1);
                } elseif ($widthOperator === 'between') {
                    $metaQuery->whereBetween('meta_value', [$widthValue1, $widthValue2]);
                }
            });
        }
        
        if ($request->filled('filter.cf_dimensions_graphic.height_operator')) {
            $heightOperator = $request->input('filter.cf_dimensions_graphic.height_operator');
            $heightValue1 = $request->input('filter.cf_dimensions_graphic.height_value_1');
            $heightValue2 = $request->input('filter.cf_dimensions_graphic.height_value_2');
        
            $query->whereHas('meta', function ($metaQuery) use ($heightOperator, $heightValue1, $heightValue2) {
                $metaQuery->where('meta_key', 'cf_dimensions_graphic_height');
        
                if ($heightOperator === 'smaller') {
                    $metaQuery->where('meta_value', '<', $heightValue1);
                } elseif ($heightOperator === 'bigger') {
                    $metaQuery->where('meta_value', '>', $heightValue1);
                } elseif ($heightOperator === 'between') {
                    $metaQuery->whereBetween('meta_value', [$heightValue1, $heightValue2]);
                }
            });
        }


        /* dimensions cf_dimensions_backpanel */
        if ($request->filled('filter.cf_dimensions_backpanel.length_operator')) {
            $lengthOperator = $request->input('filter.cf_dimensions_backpanel.length_operator');
            $lengthValue1 = $request->input('filter.cf_dimensions_backpanel.length_value_1');
            $lengthValue2 = $request->input('filter.cf_dimensions_backpanel.length_value_2');
        
            $query->whereHas('meta', function ($metaQuery) use ($lengthOperator, $lengthValue1, $lengthValue2) {
                $metaQuery->where('meta_key', 'cf_dimensions_backpanel_length');
        
                if ($lengthOperator === 'smaller') {
                    $metaQuery->where('meta_value', '<', $lengthValue1);
                } elseif ($lengthOperator === 'bigger') {
                    $metaQuery->where('meta_value', '>', $lengthValue1);
                } elseif ($lengthOperator === 'between') {
                    $metaQuery->whereBetween('meta_value', [$lengthValue1, $lengthValue2]);
                }
            });
     
        }
        
        if ($request->filled('filter.cf_dimensions_backpanel.width_operator')) {
            $widthOperator = $request->input('filter.cf_dimensions_backpanel.width_operator');
            $widthValue1 = $request->input('filter.cf_dimensions_backpanel.width_value_1');
            $widthValue2 = $request->input('filter.cf_dimensions_backpanel.width_value_2');
        
            $query->whereHas('meta', function ($metaQuery) use ($widthOperator, $widthValue1, $widthValue2) {
                $metaQuery->where('meta_key', 'cf_dimensions_backpanel_width');
        
                if ($widthOperator === 'smaller') {
                    $metaQuery->where('meta_value', '<', $widthValue1);
                } elseif ($widthOperator === 'bigger') {
                    $metaQuery->where('meta_value', '>', $widthValue1);
                } elseif ($widthOperator === 'between') {
                    $metaQuery->whereBetween('meta_value', [$widthValue1, $widthValue2]);
                }
            });
        }
        
        if ($request->filled('filter.cf_dimensions_backpanel.height_operator')) {
            $heightOperator = $request->input('filter.cf_dimensions_backpanel.height_operator');
            $heightValue1 = $request->input('filter.cf_dimensions_backpanel.height_value_1');
            $heightValue2 = $request->input('filter.cf_dimensions_backpanel.height_value_2');
        
            $query->whereHas('meta', function ($metaQuery) use ($heightOperator, $heightValue1, $heightValue2) {
                $metaQuery->where('meta_key', 'cf_dimensions_backpanel_height');
        
                if ($heightOperator === 'smaller') {
                    $metaQuery->where('meta_value', '<', $heightValue1);
                } elseif ($heightOperator === 'bigger') {
                    $metaQuery->where('meta_value', '>', $heightValue1);
                } elseif ($heightOperator === 'between') {
                    $metaQuery->whereBetween('meta_value', [$heightValue1, $heightValue2]);
                }
            });
        }

        
        /* dimensions cf_dimensions_shelfstrip */
        if ($request->filled('filter.cf_dimensions_shelfstrip.length_operator')) {
            $lengthOperator = $request->input('filter.cf_dimensions_shelfstrip.length_operator');
            $lengthValue1 = $request->input('filter.cf_dimensions_shelfstrip.length_value_1');
            $lengthValue2 = $request->input('filter.cf_dimensions_shelfstrip.length_value_2');
        
            $query->whereHas('meta', function ($metaQuery) use ($lengthOperator, $lengthValue1, $lengthValue2) {
                $metaQuery->where('meta_key', 'cf_dimensions_shelfstrip_length');
        
                if ($lengthOperator === 'smaller') {
                    $metaQuery->where('meta_value', '<', $lengthValue1);
                } elseif ($lengthOperator === 'bigger') {
                    $metaQuery->where('meta_value', '>', $lengthValue1);
                } elseif ($lengthOperator === 'between') {
                    $metaQuery->whereBetween('meta_value', [$lengthValue1, $lengthValue2]);
                }
            });
     
        }
        
        if ($request->filled('filter.cf_dimensions_shelfstrip.width_operator')) {
            $widthOperator = $request->input('filter.cf_dimensions_shelfstrip.width_operator');
            $widthValue1 = $request->input('filter.cf_dimensions_shelfstrip.width_value_1');
            $widthValue2 = $request->input('filter.cf_dimensions_shelfstrip.width_value_2');
        
            $query->whereHas('meta', function ($metaQuery) use ($widthOperator, $widthValue1, $widthValue2) {
                $metaQuery->where('meta_key', 'cf_dimensions_shelfstrip_width');
        
                if ($widthOperator === 'smaller') {
                    $metaQuery->where('meta_value', '<', $widthValue1);
                } elseif ($widthOperator === 'bigger') {
                    $metaQuery->where('meta_value', '>', $widthValue1);
                } elseif ($widthOperator === 'between') {
                    $metaQuery->whereBetween('meta_value', [$widthValue1, $widthValue2]);
                }
            });
        }
        
        if ($request->filled('filter.cf_dimensions_screen.height_operator')) {
            $heightOperator = $request->input('filter.cf_dimensions_screen.height_operator');
            $heightValue1 = $request->input('filter.cf_dimensions_screen.height_value_1');
            $heightValue2 = $request->input('filter.cf_dimensions_screen.height_value_2');
        
            $query->whereHas('meta', function ($metaQuery) use ($heightOperator, $heightValue1, $heightValue2) {
                $metaQuery->where('meta_key', 'cf_dimensions_screen_height');
        
                if ($heightOperator === 'smaller') {
                    $metaQuery->where('meta_value', '<', $heightValue1);
                } elseif ($heightOperator === 'bigger') {
                    $metaQuery->where('meta_value', '>', $heightValue1);
                } elseif ($heightOperator === 'between') {
                    $metaQuery->whereBetween('meta_value', [$heightValue1, $heightValue2]);
                }
            });
        }

         /* dimensions cf_dimensions_screen */
         if ($request->filled('filter.cf_dimensions_screen.length_operator')) {
            $lengthOperator = $request->input('filter.cf_dimensions_screen.length_operator');
            $lengthValue1 = $request->input('filter.cf_dimensions_screen.length_value_1');
            $lengthValue2 = $request->input('filter.cf_dimensions_screen.length_value_2');
        
            $query->whereHas('meta', function ($metaQuery) use ($lengthOperator, $lengthValue1, $lengthValue2) {
                $metaQuery->where('meta_key', 'cf_dimensions_screen_length');
        
                if ($lengthOperator === 'smaller') {
                    $metaQuery->where('meta_value', '<', $lengthValue1);
                } elseif ($lengthOperator === 'bigger') {
                    $metaQuery->where('meta_value', '>', $lengthValue1);
                } elseif ($lengthOperator === 'between') {
                    $metaQuery->whereBetween('meta_value', [$lengthValue1, $lengthValue2]);
                }
            });
     
        }
        
        if ($request->filled('filter.cf_dimensions_screen.width_operator')) {
            $widthOperator = $request->input('filter.cf_dimensions_screen.width_operator');
            $widthValue1 = $request->input('filter.cf_dimensions_screen.width_value_1');
            $widthValue2 = $request->input('filter.cf_dimensions_screen.width_value_2');
        
            $query->whereHas('meta', function ($metaQuery) use ($widthOperator, $widthValue1, $widthValue2) {
                $metaQuery->where('meta_key', 'cf_dimensions_screen_width');
        
                if ($widthOperator === 'smaller') {
                    $metaQuery->where('meta_value', '<', $widthValue1);
                } elseif ($widthOperator === 'bigger') {
                    $metaQuery->where('meta_value', '>', $widthValue1);
                } elseif ($widthOperator === 'between') {
                    $metaQuery->whereBetween('meta_value', [$widthValue1, $widthValue2]);
                }
            });
        }
        
        if ($request->filled('filter.cf_dimensions_screen.height_operator')) {
            $heightOperator = $request->input('filter.cf_dimensions_screen.height_operator');
            $heightValue1 = $request->input('filter.cf_dimensions_screen.height_value_1');
            $heightValue2 = $request->input('filter.cf_dimensions_screen.height_value_2');
        
            $query->whereHas('meta', function ($metaQuery) use ($heightOperator, $heightValue1, $heightValue2) {
                $metaQuery->where('meta_key', 'cf_dimensions_screen_height');
        
                if ($heightOperator === 'smaller') {
                    $metaQuery->where('meta_value', '<', $heightValue1);
                } elseif ($heightOperator === 'bigger') {
                    $metaQuery->where('meta_value', '>', $heightValue1);
                } elseif ($heightOperator === 'between') {
                    $metaQuery->whereBetween('meta_value', [$heightValue1, $heightValue2]);
                }
            });
        }



   
        

        return $query;
    }
}