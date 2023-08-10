@props(['unit'])

<a href="{{route('units.show',$unit)}}" class="flex bg-white rounded shadow">
    <div class="w-1/3 p-4">
        @php 
  
        $images = $unit->images;
        
        if ( isset($images[0])){

           $image = url('/').'/'.$images[0]->path;
         
           //$image = 'https://placehold.co/400';
        }
        else{
            $image = 'https://placehold.co/400';
        }
        @endphp
        <div  class="flex  flex-column h-full justify-center">
        <img  class="rounded object-contain" src="{{$image}}" />
        </div>
    </div>


    <div class="w-2/3 p-4 flex flex-col justify-center">
        <table class="w-full text-left text-sm">
            <tbody>
                @isset($unit->brand)
                <tr>
                    <th style="width:100px" colspan="1">Brand</th>
                    <td colspan="2">{{$unit->brand->name}}</td>
                </tr>
                @endisset
                @isset($unit->meta['cf_unit_type'])
          
                <tr>
                    <th style="width:100px">Unit type</th>
                    <td colspan="2">{{$unit->meta['cf_unit_type']}}</td>
                </tr>
                @endisset
                @isset($unit->location)
                <tr>
                    <th style="width:100px">Location</th>
                    <td colspan="2">{{$unit->location->name}}</td>
                </tr>
      
                <tr>
                    <th style="width:100px">Store Name</th>
                    <td colspan="2">{{$unit->location->airport_store_name}}</td>
                </tr>
                @endisset
            </tbody>
        </table>
    </div>
</a>
