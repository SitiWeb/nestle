@props(['type', 'color', 'name', 'label', 'oldValue' => '', 'min' => null, 'max' => null,'data' => null])

@php
$id = $attributes->get('id', $name);
$hasError = $errors->has($name);
@endphp

@php
$labelColor = 'text-gray-700 ';
$inputColor = 'bg-white  text-gray-800  border-gray-300 ';
$checkboxColor = 'text-indigo-600';

if (isset($color)) {
$labelColor = "text-$color";
$inputColor = "bg-$color text-white border-$color";
$checkboxColor = "text-$color";
}

$value = isset($oldValue) ? $oldValue : old($name);

@endphp

@switch($type)
@case('text')
<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 ">{{ $label }}</label>
    <input type="text" name="{{ $name }}" id="{{ $name }}" value="{{ $value }}" class="bg-white  text-gray-800  border-gray-300  block w-full shadow-sm sm:text-sm rounded-md">
</div>
@break

@case('textarea')
<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium {{ $labelColor }}">{{ $label }}</label>
    <textarea name="{{ $name }}" id="{{ $name }}" class="bg-white  text-gray-800  border-gray-300  block w-full shadow-sm sm:text-sm rounded-md">{{ $value }}</textarea>
</div>
@break

@case('checkbox')
<div class="flex items-center mb-4">
    <input type="checkbox" name="{{ $name }}" id="{{ $name }}" {{ $value ? 'checked' : '' }} class="mr-2 {{ $checkboxColor }} h-4 w-4 border-gray-300 rounded-md focus:ring-indigo-500 dark:{{ str_replace('text-indigo-600', 'text-indigo-300', $checkboxColor) }}">
    <label for="{{ $name }}" class="text-sm font-medium {{ $labelColor }}">{{ $label }}</label>
</div>
@break

@case('number')
<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium {{ $labelColor }}">{{ $label }}</label>
    <input type="number" name="{{ $name }}" id="{{ $id }}" value="{{ old($name, $value) }}" @if ($min !==null) min="{{ $min }}" @endif @if ($max !==null) max="{{ $max }}" @endif class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  ">


</div>

@break

@case('color')
<div class="mb-4 mr-4">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 ">{{ $label }}</label>
    <div class="flex space-x-2">
        <input name="{{ $name }}" id="{{ $name }}" type="color" value="{{ old($name, $value) }}" class="bg-white  text-gray-800  border-gray-300  shadow-sm sm:text-sm rounded-md" />
    </div>
</div>

@break
@case('image')
<div class="mb-4 mr-4">
    <label for="{{ $name }}" class="block text-sm font-medium {{ $labelColor }}">{{ $label }}</label>
    <input type="file" name="{{ $name }}" id="{{ $id }}" class=" rounded-md  " onchange="previewImage(event, '{{ $id }}')">
    @if (!empty($value))
    <div class="mt-2">
        <img id="{{ $id }}Preview" src="{{ $value }}" alt="Preview" class="h-20 w-20 object-cover rounded-md">
    </div>
    @endif
</div>
@break
@case('images')
<div class="mb-4 mr-4">
    <label for="{{ $name }}" class="block text-sm font-medium {{ $labelColor }}">{{ $label }}</label>
    <input type="file" name="{{ $name }}[]" id="{{ $id }}" class="rounded-md " multiple onchange="previewImages(event, '{{ $id }}')">
    <div id="{{ $id }}Preview-multi"></div>
    @if (!empty($value->images))
    <div class="mt-2 grid grid-cols-6 gap-2">
    @foreach ($value->images as $image)
        <div class="relative">
            <img src="{{ url('/') }}/{{ $image['path'] }}" alt="Preview" class="h-20 w-20 object-cover rounded-md">
            <a href="{{ route('units.image-delete',['unit' => $value, 'image' => $image]) }}?_method=DELETE" class="absolute top-0 right-0 p-1 bg-red-500 text-white rounded-full">
                x
            </a>
    
            @isset($value->meta['cf_featured_image_id'])
                @if ($image->id == $value->meta['cf_featured_image_id'])
                <input type="radio" name="fields[cf_featured_image_id]" value="{{$image->id}}" checked> 
                @else
            
                <input type="radio" name="fields[cf_featured_image_id]" value="{{$image->id}}"> 
                @endif

            @else
                <input type="radio" name="fields[cf_featured_image_id]" value="{{$image->id}}"> 
            @endisset
            
        </div>
    @endforeach
</div>

    @endif
</div>
@break
@case('date')
<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium {{ $labelColor }}">{{ $label }}</label>
    <input type="date" name="{{ $name }}" id="{{ $id }}" value="{{ old($name, $value) }}" class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md  ">
</div>
@break

@case('unit_type')
<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium block text-sm font-medium text-gray-700 {{ $labelColor }}">{{ $label }}</label>
    @php
    $data = [
        ['name'=>'Type 1','id'=>'Type 1'],
        ['name'=>'Type 2','id'=>'Type 2'],
        ['name'=>'Type 3','id'=>'Type 3']
    ];
    @endphp
    <select name="{{ $name }}" id="{{ $id }}"  class="block  shadow-sm sm:text-sm border-gray-300 rounded-md w-full ">
        <option value=""></option>
        @foreach($data as $row)
        @if ($row['id'] == $value)
        <option value="{{$row['id']}}" data-lookup="{{$row['name']}}" selected>
            {{$row['name']}}
        </option>
        @else
        <option value="{{$row['id']}}">
            {{$row['name']}}
        </option>
        @endif
        @endforeach
    </select>
</div>
@break

@case('condition')
<div class="mb-4  mr-2">
    <label for="{{ $name }}" class="block text-sm font-medium block text-sm font-medium text-gray-700 {{ $labelColor }}">{{ $label }}</label>
    @php
    $data = [
        ['name'=>'New','id'=>'New'],
        ['name'=>'Good','id'=>'Good'],
        ['name'=>'Poor','id'=>'Poor']
    ];
    @endphp
 
    <select name="{{ $name }}" id="{{ $id }}"  class="block  shadow-sm sm:text-sm border-gray-300 rounded-md w-full ">
        <option value=""></option>
        @foreach($data as $row)
        @if ($row['id'] == $value)
        <option value="{{$row['id']}}" data-lookup="{{$row['name']}}" selected>
            {{$row['name']}}
        </option>
        @else
        <option value="{{$row['id']}}">
            {{$row['name']}}
        </option>
        @endif
        @endforeach
    </select>
    
</div>
@break

@case('country')
<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium {{ $labelColor }}">{{ $label }}</label>
    <select name="{{ $name }}" id="{{ $id }}" class="js-basic-single">
        @foreach($data as $row)
        @if ($row['country'] == '')
        <option value="{{$row['country']}}" selected>
            {{$row['country']}}
        </option>
        @else
        <option value="{{$row['country']}}">
            {{$row['country']}}
        </option>
        @endif
        @endforeach
    </select>
    
</div>
@break

@case('store')
<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium {{ $labelColor }}">{{ $label }}</label>

    <select name="{{ $name }}" id="{{ $id }}" class="js-basic-search">
        <option value=""></option>
        @foreach($data as $row)
       
        @if ($row['id'] == $value)
        <option value="{{$row['id']}}" data-lookup="{{$row['name']}}{{$row['slug']}}{{$row['airport_store_name']}}{{$row['airport_code']}}{{$row['terminal']}}{{$row['retailer']}}{{$row['country']}}" selected>
            {{$row['name']}}
        </option>
        @else
        <option value="{{$row['id']}}">
            {{$row['name']}}
        </option>
        @endif
        @endforeach
    </select>
    
</div>
@break

@case('brand')
<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium {{ $labelColor }}">{{ $label }}</label>

    <select name="{{ $name }}" id="{{ $id }}" class="js-basic-search">
        <option value=""></option>
        @foreach($data as $row)
       
        @if ($row['id'] == $value)
        <option value="{{$row['id']}}" data-lookup="{{$row['name']}}" selected>
            {{$row['name']}}
        </option>
        @else
        <option value="{{$row['id']}}">
            {{$row['name']}}
        </option>
        @endif
        @endforeach
    </select>
    
</div>
@break

@case('select_filter')
<div class="my-2 mr-2">
   

    <select name="filter[{{ $name }}]" id="{{ $id }}"  class="block  shadow-sm sm:text-sm border-gray-300 rounded-md  ">
        <option value="">Brands</option>
        @foreach($data as $row)
        
        @if (isset(request()->filter[$name]) && $row['id'] == request()->filter[$name])
        <option value="{{$row['id']}}" data-lookup="{{$row['name']}}" selected>
            {{$row['name']}}
        </option>
        @else
        <option value="{{$row['id']}}">
            {{$row['name']}}
        </option>
        @endif
        @endforeach
    </select>
    
</div>
@break
@case('select_filter2')
<div class="my-2 mr-2">
    

    <select name="filter[{{ $name }}]" id="{{ $id }}"  class="block  shadow-sm sm:text-sm border-gray-300 rounded-md  ">
        <option value="">{{ $label }}</option>
        @foreach($data as $row)
        
        @if (isset(request()->filter[$name]) && $row == request()->filter[$name])
        <option value="{{$row}}" data-lookup="{{$row}}" selected>
            {{$row}}
        </option>
        @else
        <option value="{{$row}}">
            {{$row}}
        </option>
        @endif
        @endforeach
    </select>
    
</div>
@break
@case('filter_condition')
<div class=" my-2 mr-2">
    
    @php
    $data = [
        ['name'=>'New','id'=>'New'],
        ['name'=>'Good','id'=>'Good'],
        ['name'=>'Poor','id'=>'Poor']
    ];
    @endphp
    <select name="filter[{{ $name }}]" id="{{ $id }}"  class="block  shadow-sm sm:text-sm border-gray-300 rounded-md  ">
        <option value="">Condition</option>
        @foreach($data as $row)
        
        @if (isset(request()->filter[$name]) && $row['id'] == request()->filter[$name])
        <option value="{{$row['id']}}" data-lookup="{{$row['name']}}" selected>
            {{$row['name']}}
        </option>
        @else
        <option value="{{$row['id']}}">
            {{$row['name']}}
        </option>
        @endif
        @endforeach
    </select>
    
</div>
@break

@case('filter_dates')
<div class="mb-4  mr-6 block">
    <!-- Date Filter -->
    <label for="filter[date_{{$name}}]" class="block text-sm font-medium text-gray-700">{{$label}}</label>
    <div class="flex flex-column  space-x-4">
        <select id="filter-date-operator_{{$name}}" name="filter[date_{{$name}}][type]" class="rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300">
            <option value=""> - </option>
            <option value="before">Before</option>
            <option value="after">After</option>
            <option value="between">Between</option>
        </select>
        <input id="filter-date-start_{{$name}}" name="filter[date_{{$name}}][date_start]" type="date" class="rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300">
        <input id="filter-date-end_{{$name}}" name="filter[date_{{$name}}][date_end]" type="date" class="rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 hidden">
 
    </div>
    <script>
    $(document).ready(function() {
    // Show/hide second date field based on the selected operator
        $('#filter-date-operator_{{$name}}').on('change', function() {
            var operator = $(this).val();
            if (operator === 'between') {
                $('#filter-date-end_{{$name}}').removeClass('hidden');
            } else {
                $('#filter-date-end_{{$name}}').addClass('hidden');
            }
        });
    });
    </script>
</div>
@break

@case('dimensions')
<div class="mb-4 my-4">
    <label for="{{ $name }}" class="block text-sm font-medium {{ $labelColor }}">{{ $label }}</label>
    <div class="grid grid-cols-3 gap-4 ">
        
        <div>
            @isset($value['width'])
                <input type="number" value="{{$value['width']}}" name="{{ $name }}[width]" id="{{ $name }}[width]" placeholder="mm" class="block w-full px-4 py-2 mt-1 text-gray-800 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500">
            @else
                <input type="number" value="" name="{{ $name }}[width]" id="{{ $name }}[width]" placeholder="mm" class="block w-full px-4 py-2 mt-1 text-gray-800 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500">
            @endisset
            <label for="{{ $name }}[height]" class="block text-sm font-medium text-gray-700">Width</label>
        </div>
        <div>
    
            @isset($value['height'])
                <input type="number" value="{{$value['height']}}" name="{{ $name }}[height]" id="{{ $name }}[height]" placeholder="mm" class="block w-full px-4 py-2 mt-1 text-gray-800 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500">
            @else
                <input type="number" value="" name="{{ $name }}[height]" id="{{ $name }}[height]" placeholder="mm" class="block w-full px-4 py-2 mt-1 text-gray-800 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500">
            @endisset
            <label for="{{ $name }}[height]" class="block text-sm font-medium text-gray-700">Height</label>
        </div>
        <div>
            @isset($value['length'])
                <input type="number" value="{{$value['length']}}" name="{{ $name }}[length]" id="{{ $name }}[length]" placeholder="mm" class="block w-full px-4 py-2 mt-1 text-gray-800 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500">
            @else
                <input type="number" value="" name="{{ $name }}[length]" id="{{ $name }}[length]" placeholder="mm" class="block w-full px-4 py-2 mt-1 text-gray-800 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500">
            @endisset
            <label for="{{ $name }}[height]" class="block text-sm font-medium text-gray-700">Depth</label>
        </div>
    </div>    
</div>
@break
@case('filter_dimensions')
<!-- Length Filter -->
<div class="flex flex-col">
    <h3>{{$label}}</h3>
    <div class="flex mb-4 flex-col">


<!-- Width Filter -->
<div class="mr-4">
    <label for="filter-width" class="block text-sm font-medium text-gray-700">Width Filter</label>
    <div class="flex space-x-4">
        <select id="filter-width-operator_{{$name}}" name="filter[{{$name}}][width_operator]" class="rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300">
            <option value=""></option>
            <option value="smaller"><</option>
            <option value="bigger">></option>
            <option value="between"><></option>
        </select>
        <input id="filter-width-value-1_{{$name}}" name="filter[{{$name}}][width_value_1]" type="number" style="width:75px;" class="rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300">
        <input id="filter-width-value-2_{{$name}}" name="filter[{{$name}}][width_value_2]" type="number" style="width:75px;" class="rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 hidden">
    </div>
</div>

<!-- Height Filter -->
<div class="mr-4">
    <label for="filter-height" class="block text-sm font-medium text-gray-700">Height Filter</label>
    <div class="flex space-x-4">
        <select id="filter-height-operator_{{$name}}" name="filter[{{$name}}][height_operator]" class="rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300">
        <option value=""></option>
            <option value="smaller"><</option>
            <option value="bigger">></option>
            <option value="between"><></option>
        </select>
        <input id="filter-height-value-1_{{$name}}" name="filter[{{$name}}][height_value_1]" type="number" style="width:75px;" class="rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300">
        <input id="filter-height-value-2_{{$name}}" name="filter[{{$name}}][height_value_2]" type="number" style="width:75px;" class="rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 hidden">
    </div>
</div>

<div class="mr-4">
    <label for="filter-length" class="block text-sm font-medium text-gray-700">Depth Filter</label>
    <div class="flex space-x-4">
        <select width="40px"  id="filter-length-operator_{{$name}}" name="filter[{{$name}}][length_operator]" class="rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300">
            <option value=""></option>
            <option value="smaller"><</option>
            <option value="bigger">></option>
            <option value="between"><></option>
        </select>
        <input id="filter-length-value-1_{{$name}}" name="filter[{{$name}}][length_value_1]" type="number" style="width:75px;" class="rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300">
        <input id="filter-length-value-2_{{$name}}" name="filter[{{$name}}][length_value_2]" type="number" style="width:75px;" class="rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 hidden">
    </div>
</div>


</div>
</div>
<script>
    $(document).ready(function() {
    // Show/hide second input field based on the selected operator for length
    $('#filter-length-operator_{{$name}}').on('change', function() {
        var operator = $(this).val();
        if (operator === 'between') {
            $('#filter-length-value-2_{{$name}}').removeClass('hidden');
        } else {
            $('#filter-length-value-2_{{$name}}').addClass('hidden');
        }
    });

    // Show/hide second input field based on the selected operator for width
    $('#filter-width-operator_{{$name}}').on('change', function() {
        var operator = $(this).val();
        if (operator === 'between') {
            $('#filter-width-value-2_{{$name}}').removeClass('hidden');
        } else {
            $('#filter-width-value-2_{{$name}}').addClass('hidden');
        }
    });

    // Show/hide second input field based on the selected operator for height
    $('#filter-height-operator_{{$name}}').on('change', function() {
        var operator = $(this).val();

        if (operator === 'between') {
            $('#filter-height-value-2_{{$name}}').removeClass('hidden');
        } else {
            $('#filter-height-value-2_{{$name}}').addClass('hidden');
        }
    });
});
</script>
@break

@default
<div class="mb-4 mr-4">
    <label for="{{ $name }}" class="block text-sm font-medium {{ $labelColor }}">{{ $label }}</label>
    <input type="text" name="{{ $name }}" id="{{ $name }}" value="{{ $value }}" class="bg-white  text-gray-800  border-gray-300  block w-full shadow-sm sm:text-sm rounded-md">
</div>
@endswitch