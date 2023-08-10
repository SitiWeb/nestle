<x-app-layout>
    <div class="mb-6 flex justify-end">
        <x-primary-link href="{{ route('units.create') }}" text="Create new unit" />
    </div>
    <x-filters :data="$filter_data"></x-filters>
    <div class="container mx-auto">
        <div class="grid grid-cols-3 gap-4 ">
            @foreach($units as $unit)
            <x-card :unit="$unit"></x-card>
            @endforeach
        </div>
    </div>
 
</x-app-layout>