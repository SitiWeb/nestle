<x-app-layout>


    <div>
        <div id="text-filters" class="text-center py-2 bg-white font-bold">Select a region to start your search</div>
        <div class="grid grid-cols-4 bg-white region-filters-headings" style="display:none">
            <div class="bg-white p-2 " >
                <div class=" text-center">Region</div>
            </div>
            <div class="bg-white p-2 ">
                <div class=" text-center">Country</div>
            </div>
            <div class="bg-white  p-2">
                <div class=" text-center">Airport</div>
            </div>
            <div class="bg-white p-2 ">
                <div class="text-center">Shop</div>
            </div>
        </div>




        <div class="grid grid-cols-4 bg-white region-filters mb-4">

            <div class="bg-white p-4 border" >

                <div class="region-continent region-filter">
                  
                    @if (!empty($continents))
                    <ul>
                        @foreach($continents as $continent => $countries)
                            <li class="continent pl-2 py-1 rounded" data-continent="{{$continent}}"><a href="#" >{{ $continent }}</a></li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>

            <div class="bg-white p-4 border">

                <div class="region-country region-filter">
                @if (!empty($continents))
                @foreach($continents as $continent => $list)              
                    <div class="countries-continent" style="display:none;" data-continent="{{($continent)}}">
                        <ul>
                            @foreach($list as $country)
                                <li class="country pl-2 py-1 rounded" data-continent="{{($continent)}}" data-country="{{$country}}" > 
                                    <a href="#" class=" " >{{ $country }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
                @endif
                </div>
            </div>

            <div class="bg-white border p-4">

                <div class="region-airport region-filter">
                    @if (!empty($continents))
                    <div class="airport-countries" data-continent="{{($continent)}}" >
                        <ul>
                            @foreach($locations as $location)
            
                                <li class="airports pl-2 py-1 rounded" data-country="{{$location->country}}" data-airport="{{$location->airport_store_name}}" style="display:none;">
                                    <a href="#" class="">{{ $location->airport_store_name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
          
            </div>

            <div class="bg-white p-4 border">

                <div class="region-shop region-filter">
                @if (!empty($continents))
                    <div class="shops-countries" data-continent="{{($continent)}}" >
                        <ul>
                            @foreach($locations as $location)
            
                                <li class="shops pl-2 py-1 rounded" data-airport="{{$location->airport_store_name}}" data-shop="{{$location->name}}" style="display:none;">
                                    <a class="" href="{{ route('locations.show',$location) }}" >{{ $location->airport_store_name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
                
            </div>
        </div>

        <div id="svgMap"></div>
    </div>
    <script>
        new svgMap(
        @php
        echo $data;
        @endphp
        );
    </script>
    <script>
        $(document).ready(function() {
        $('.continent').click(function(e) {
            e.preventDefault();
            var continent = $(this).data('continent');
            
            // Hide all continent blocks
            $('.countries-continent').hide();
            $('.airports').hide();
            $('.shops').hide();
            $('#text-filters').hide();
            $('.region-filters-headings').show();
            
            // Show the selected continent block
            $('.countries-continent[data-continent="' + continent + '"]').show();
                // Add bullet point and set font weight for the selected item
            $('.continent').removeClass('selected font-bold bg-neutral-200');
            $(this).addClass('selected font-bold bg-neutral-200');
        });
        $('.country').click(function(e) {
            e.preventDefault();
            var country = $(this).data('country');
            
            $('.airports').hide();
            $('.shops').hide();
            
            // Show the selected continent block
            $('.airports[data-country="' + country + '"]').show();
                // Add bullet point and set font weight for the selected item
            $('.country').removeClass('font-bold bg-neutral-200');
            $(this).addClass('font-bold bg-neutral-200');
        });
        $('.airports').click(function(e) {
            e.preventDefault();
            var airport = $(this).data('airport');
            
            $('.shops').hide();
            
            // Show the selected continent block
            $('.shops[data-airport="' + airport + '"]').show();
            $('.airports').removeClass('font-bold bg-neutral-200');
            $(this).addClass('font-bold bg-neutral-200');
        });
        });
    </script>
</x-app-layout>
