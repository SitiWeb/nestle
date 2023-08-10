<x-app-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lightbox2@2.11.3/dist/css/lightbox.min.css">
    <script src="https://cdn.jsdelivr.net/npm/lightbox2@2.11.3/dist/js/lightbox.min.js"></script>
    <div class="mb-4">
        <x-primary-link href="{{ route('units.overview') }}" text="Back to units"></x-primary-link>
    </div>
    <div class="container mx-auto bg-white rounded shadow p-6">
        <div class="grid grid-cols-4 gap-4">
            <div class="col-span-1">

                <!-- Content for the left column (1/4 width) -->
                <div class="gallery">
                  
                    @isset($unit->meta['cf_featured_image_id'])
                        @foreach ($unit->images as $index => $image)
                            @if($image->id != $unit->meta['cf_featured_image_id'])
                                @continue
                            @endif
                            <a href="{{ url('/') }}/{{ $image->path }}" data-lightbox="image-gallery"
                            data-title="{{ $image->path }}">
                            <img src="{{ url('/') }}/{{ $image->path }}"
                                alt="{{ $image->path }}" style="width:100%">
                            <div
                                style="position:relative;
                                        bottom:50px; 
                                        left:10px;
                                        display:inline-block;
                                        padding: 10px;
                                        background: white;
                                        border: 1px solid lightgrey;
                                        border-radius: 50%;
                                        opacity:0.5">
                                <img src="{{ url('/') }}/images/hoekjeszoom.png" height="20px" width="20px">
                            </div>
                            </a>
                        @endforeach
                    @else
                    @if (isset($unit->images[0]))
                        <a href="{{ url('/') }}/{{ $unit->images[0]->path }}" data-lightbox="image-gallery"
                            data-title="{{ $unit->images[0]->path }}">
                            <img src="{{ url('/') }}/{{ $unit->images[0]->path }}"
                                alt="{{ $unit->images[0]->path }}" style="width:100%">
                            <div
                                style="position:relative;
                                        bottom:50px; 
                                        left:10px;
                                        display:inline-block;
                                        padding: 10px;
                                        background: white;
                                        border: 1px solid lightgrey;
                                        border-radius: 50%;
                                        opacity:0.5">
                                <img src="{{ url('/') }}/images/hoekjeszoom.png" height="20px" width="20px">
                            </div>
                        </a>
                    @endif
                    @endisset
                    @foreach ($unit->images as $index => $image)
                        
                            <a href="{{ url('/') }}/{{ $image->path }}" data-lightbox="image-gallery"
                                data-title="{{ $image->path }}" style="display:none;">
                                <img src="{{ url('/') }}/{{ $image->path }}" alt="{{ $image->path }}">
                            </a>
                        
                    @endforeach
                </div>
                <div class="flex flex-col mt-4">
                    
                    <a href="{{route('export.csv')}}?unit_id={{$unit->id}}"
                        class="flex items-center space-x-2 bg-white border border-gray-300 text-gray-700 px-4 my-2  py-2 rounded-md">
               
                        <span>Export CSV</span>
                    </a>
                    <a href="#"
                        class="flex items-center space-x-2 bg-white border border-gray-300 text-gray-700 px-4 my-2  py-2 rounded-md">
                   
                        <span>Share via email</span>
                    </a>
                    @foreach ($unit->files as $item)
                    @php
                        $filePath = $item->path;
                        $files = Storage::url($filePath);

                    @endphp
                    <a href="{{ route('download.file', ['filename' => str_replace('public/files/','',$item->path)]) }}"
                        class="flex items-center space-x-2 bg-white border border-gray-300 text-gray-700 px-4 my-2  py-2 rounded-md">
                        
                        <span>Download {{$item->name}}</span>
                    </a>                   
                    @endforeach
                </div>


            </div>


            <div class="col-span-3">
                <table class="w-full">
                    <tbody>
                        @php
                            $i = 0;
                        @endphp
                        @foreach ($meta as $element)
                            @if ($element['label'] === 'HR')
                                <td colspan="2">
                                    <hr class="my-6">
                                </td>
                                </tr>
                            @else
                                @php
                                    $i++;
                                @endphp
                                @if ($i % 2 === 0)
                                    <tr class="bg-white" id="{{ $element['key'] }}">
                                    @else
                                    <tr class="bg-gray-100" id="{{ $element['key'] }}">
                                @endif

                                <th style="width:50%" class="p-2 text-left">{{ $element['label'] }}</th>
                                <td style="width:50%" class="p-2">{{ $element['value'] }}</td>
                                </tr>
                            @endif
                        @endforeach
                        
                    </tbody>
                </table>
                <div>
                    <h2 class="font-bold">General comments:</h2>
                    <div>{{ $unit->description }}</div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // Initialize Lightbox2
            lightbox.option({
                'resizeDuration': 200,
                'wrapAround': true,
                'alwaysShowNavOnTouchDevices': true,
            });
        });
    </script>
</x-app-layout>
