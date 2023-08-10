<x-app-layout>
    <x-slot name="header">
        <div id="text-filters" class="text-center py-2 bg-white font-bold border-y">Advanced search</div>
        <div class="">
            <form action="{{ route('units.overview') }}" method="GET">
            <div class="px-4 bg-white  flex flex-wrap">
                


                @foreach ($filter_data['extras'] as $filter)
                    <x-field type="select_filter2" name="{{ $filter['id'] }}" label="{{ $filter['label'] }}"
                        :data="$filter['data']" :oldValue="null" />
                @endforeach
                <x-field type="select_filter" name="brands" label="Brands" :oldValue="null" :data="$filter_data['brands']" />
                <x-field type="filter_condition" name="condition" label="Condition" :oldValue="null" />
                <div class=" my-2 relative rounded-md shadow-sm  mr-2" style="width:calc(100% - 100px)">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3"
                        style="height: 35px">
                        <svg fill="gray" height="20px" xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6 fill-gray-900 hover:fill-gray-900">
                            <path
                                d="M20.47 21.53a.75.75 0 1 0 1.06-1.06l-1.06 1.06Zm-9.97-4.28a6.75 6.75 0 0 1-6.75-6.75h-1.5a8.25 8.25 0 0 0 8.25 8.25v-1.5ZM3.75 10.5a6.75 6.75 0 0 1 6.75-6.75v-1.5a8.25 8.25 0 0 0-8.25 8.25h1.5Zm6.75-6.75a6.75 6.75 0 0 1 6.75 6.75h1.5a8.25 8.25 0 0 0-8.25-8.25v1.5Zm11.03 16.72-5.196-5.197-1.061 1.06 5.197 5.197 1.06-1.06Zm-4.28-9.97c0 1.864-.755 3.55-1.977 4.773l1.06 1.06A8.226 8.226 0 0 0 18.75 10.5h-1.5Zm-1.977 4.773A6.727 6.727 0 0 1 10.5 17.25v1.5a8.226 8.226 0 0 0 5.834-2.416l-1.061-1.061Z">
                            </path>
                        </svg>
                        
                    </div>
                    <input type="text" name="search" id="search"
                            class="block w-full rounded-md border-0 py-1.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            placeholder="Search by brand, Country, Tag..."
                            value="{{ request('search') }}">
                        
                </div>
                <div class="py-2"><x-primary-button>Apply</x-primary-button></div>
                
            </div>
                
            

            </form>
<!--             
            <div class="px-4 bg-white  flex flex-wrap flex-row w-full">
                {{-- Search --}}
                <form action="" method="GET" class="flex flex-wrap flex-row w-full">
               
                <div class="py-2">
                <x-primary-button>Search</x-primary-button>
                </div>
                </form>
            </div> -->
        </div>
    </x-slot>
    <x-filters :data="$filter_data" :totalResults="$totalResults"></x-filters>
    <div class="container mx-auto">
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        @if(!$units->isEmpty())
        
        <div class="grid grid-cols-3 gap-4 ">
     
     
            @foreach ($units as $unit)
          
                <x-card :unit="$unit"></x-card>
            @endforeach
            @role('editor')
            <div>
            </div>
            @endrole
            </div>
            @else
        <div class=" w-100">No results found for the selected filters. @role('editor')<a class="underline" href="{{ route('units.create') }}">Create new unit</a>@endrole</div>
        
        
        @endif
    </div>

{{ $units->links() }}

</x-app-layout>
