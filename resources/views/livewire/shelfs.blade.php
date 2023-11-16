<div class="container mx-auto bg-white rounded shadow p-6">
<form wire:submit.prevent="save">
<div>
    <x-primary-link href="{{ (route('units.edit',['unit' => $unit]));}}" text="Back to unit" >
    </x-primary-link>
@foreach($shelves as $index => $shelf)
    <div class="mb-4 my-4">
 
        <div class="flex flex-wrap -mx-3  w-full">
        
                <div class=" w-1/4 px-3 md:mb-0">
            <div>
                <label for="{{ $shelf['name'] }}[{{$index}}][type]" class="block text-sm font-medium block text-sm font-medium text-gray-700 mt-1">Type Dimension</label>
                @php
                $data = [
                    ['name'=>'Graphics','id'=>'graphics'],
                    ['name'=>'Screen','id'=>'screen'],
                    ['name'=>'Backlight','id'=>'backlight'],
                    ['name'=>'Shelf','id'=>'shelf'],
                    ['name'=>'Fixturebuild','id'=>'fixturebuild'],
                ];
                @endphp
               
                <select name="{{ $shelf['name'] }}[{{$index}}][type]" id="{{ $shelf['name'] }}[{{$index}}][type]" wire:model="shelves.{{$index}}.type" class="block  shadow-sm sm:text-sm border-gray-300 rounded-md w-full ">
                    <option value=""></option>
                    @foreach($data as $row)
                    
                    @if ($row['id'] == $shelf['type'])
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
        </div>
        <div class=" w-1/4 px-3  md:mb-0">
            <div class="mb-4">
                <label for="{{$shelf['name']}}[{{$index}}][name]" class="block text-sm font-medium text-gray-700 ">{{ 'name'}}</label>
                <input type="text" name="{{$shelf['name']}}[{{$index}}][name]" id="{{$shelf['name']}}[{{$index}}][name]" wire:model.defer="shelves.{{$index}}.name"  class="bg-white  text-gray-800  border-gray-300  block w-full shadow-sm sm:text-sm rounded-md">
            </div>
        </div>
        <div class=" w-1/2 px-3  md:mb-0">
            <div class="mb-4">
                <label for="{{$shelf['name']}}[{{$index}}][comment]" class="block text-sm font-medium text-gray-700 ">{{ 'Comment'}}</label>
                <input  type="text" name="{{$shelf['name']}}[{{$index}}][comment]" id="{{$shelf['name']}}[{{$index}}][comment]" wire:model.defer="shelves.{{$index}}.comment"  class="bg-white  text-gray-800  border-gray-300  block w-full shadow-sm sm:text-sm rounded-md">
            </div>
        </div>
        
        

        </div>    
        <div class="flex flex-wrap -mx-3 mb-6 w-full">
        
            <div class=" w-1/4 px-3 mb-6 md:mb-0">
            
                <label for="{{ $shelf['name'] }}[{{$index}}][width]" class="block text-sm font-medium text-gray-700">Width</label>
                <input type="number" wire:model.defer="shelves.{{$index}}.width" name="{{ $shelf['name'] }}[{{$index}}][width]" id="{{ $shelf['name'] }}[{{$index}}][width]" placeholder="mm" class="block w-full px-4 py-2 mt-1 text-gray-800 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500">
            </div>
            <div class=" w-1/4 px-3 mb-6 md:mb-0">
                <label for="{{ $shelf['name'] }}[{{$index}}][height]" class="block text-sm font-medium text-gray-700">Height</label>
                <input type="number" wire:model.defer="shelves.{{$index}}.height" name="{{ $shelf['name'] }}[{{$index}}][height]" id="{{ $shelf['name'] }}[{{$index}}][height]" placeholder="mm" class="block w-full px-4 py-2 mt-1 text-gray-800 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500">
            </div>
            <div class=" w-1/4 px-3 mb-6 md:mb-0">
                <label for="{{ $shelf['name'] }}[{{$index}}][length]" class="block text-sm font-medium text-gray-700">Depth</label>
                <input type="number" wire:model.defer="shelves.{{$index}}.length" name="{{ $shelf['name'] }}[{{$index}}][length]" id="{{ $shelf['name'] }}[{{$index}}][length]" placeholder="mm" class="block w-full px-4 py-2 mt-1 text-gray-800 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500">
            </div>
            
            <div class="w-1/4 px-3  md:mb-0">
                <div></div>
            </div>
            <div class="w-1/4 px-3  md:mb-0">
                <div><a href="#" wire:click.prevent="removeShelf({{$index}})">Delete</a></div>
            </div>
        </div>
        
    </div>
    <input type="hidden" name="unit_idd" value="{{$unit}}" />
    <hr>

@endforeach
<div class="flex w-full justify-between mt-4">
<button wire:click="addShelf" class="flex items-center justify-center px-4 py-2 bg-black border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 active:bg-gray-900  focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">add dimension</button>
<button type="submit" class="flex items-center justify-center px-4 py-2 bg-black border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 active:bg-gray-900  focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
    Save Shelves
</button>
</div>
</div>
</form>
</div>