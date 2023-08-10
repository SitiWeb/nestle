<x-app-layout>
    <div style="display:flex; flex-direction:column; justify-content:space-between;    min-height: calc(100vh - 4em - 80px);">
        
        <div class="mb-6 flex justify-end">
            @role('editor')
            <x-primary-link href="{{ route('brands.create') }}" text="Create new Brand" />
            @endrole
        </div>
        
        <div class="min-h-full flex justify-center flex-row">
            
            <div class="grid grid-cols-3 gap-12">
                @foreach($brands as $brand)
                @php 
                if (isset($brand->img)){
                    $src = url('/').'/'.$brand->img;
                }
                else{
                    $src = 'https://placehold.co/400x400?font=roboto&text='.$brand->name;
                }
                @endphp
                <div class="bg-white">
                    <a href="{{route ('units.overview')}}?filter[brands]={{$brand->id}}">
                        <img src="{{ $src }}" width="200" height="200" style="object-fit: cover; height: 200px;" />
                    </a>
                </div>
                @endforeach
            </div>
        
        </div>
        <div>

        </div>
    </div>

</x-app-layout>