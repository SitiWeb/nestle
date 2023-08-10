<x-app-layout>
<div class="">
          
            
            <div class="px-4 bg-white  flex flex-wrap flex-row w-full">
                {{-- Search --}}
                <form action="{{ route('units.overview') }}" method="GET" class="flex flex-wrap flex-row w-full">
                <div class=" my-2 relative rounded-md shadow-sm  mr-2">
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
                        placeholder="Search by brand, Country, Tag...">
                        
                </div>
                <div class="py-2">
                <x-primary-button>Search</x-primary-button>
                </div>
                </form>
            </div>
        </div>
    <div class="container mx-auto bg-white rounded shadow p-6">
        <div class="flex justify-between">
          
            <div>
                <div class="">
                    <x-primary-link href="{{ route('locations.create') }}" text="Create new location" />
                </div>
            </div>
            </div>
    <table class="table w-full">
        <!-- <thead>
            <tr>
                <th scope="col" class="text-left px-4 py-2">Brand Name</th>
                <th scope="col" class="text-left px-4 py-2">Actions</th>
            </tr>
        </thead> -->
        <tbody>
            @foreach($locations as $location)
            <tr class="{{ $loop->iteration % 2 === 0 ? 'bg-gray-50' : 'bg-white' }}">
                <td class="px-4 py-2">{{ $location->name }}</td>
                <td class="px-4 py-2 text-right flex justify-end">
                    <x-primary-link href="{{ route('locations.edit', $location) }}" text="Edit" />
                    <form action="{{ route('locations.destroy', $location) }}" method="POST" class="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded font-semibold text-xs text-white uppercase justify-center tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 mx-3 bg-red-700">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination links -->
    <div class="mt-4">
        {{ $locations->links() }}
    </div>
</div>

</x-app-layout>