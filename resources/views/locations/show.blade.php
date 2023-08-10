<x-app-layout>

    <div class="container mx-auto bg-white rounded shadow p-6">
     
            <h1 class="text-3xl font-bold mb-4">{{ $location->name }}</h1>
            <div class="flex">
                <div class="flex items-center mr-4 text-sm">
                    <span class="font-semibold mr-2">Airport/Downtown Store Name:</span>
                    <span>{{ $location->airport_store_name }}</span>
                </div>
                <div class="flex items-center mr-4 text-sm">
                    <span class="font-semibold mr-2">Airport Code:</span>
                    <span>{{ $location->airport_code }}</span>
                </div>
                <div class="flex items-center mr-4 text-sm">
                    <span class="font-semibold mr-2">Terminal:</span>
                    <span>{{ $location->terminal }}</span>
                </div>
                <div class="flex items-center mr-4 text-sm">
                    <span class="font-semibold mr-2">Retailer:</span>
                    <span>{{ $location->retailer }}</span>
                </div>
                <div class="flex items-center mr-4 text-sm">
                    <span class="font-semibold mr-2">Country:</span>
                    <span>{{ $location->country }}</span>
                </div>
            </div>
    
    </div>
    @if($units)

    <x-filters :data="$filter_data" :totalResults="$totalResults"></x-filters>
    <div class="container mx-auto">
        <div class="grid grid-cols-3 gap-4 ">
            @foreach($units as $unit)
            <x-card :unit="$unit"></x-card>
            @endforeach
        </div>
    </div>
    @endif
</x-app-layout>
