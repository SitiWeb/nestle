<x-app-layout>

    <div class="container mx-auto bg-white rounded shadow p-6">
        <div class="flex justify-between">
           
            <div>
                <div class="">
                    <x-primary-link href="{{ route('units.create') }}" text="Create new unit" />
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
            @foreach($units as $unit)
           
            <tr class="{{ $loop->iteration % 2 === 0 ? 'bg-gray-50' : 'bg-white' }}">
                
                <td class="px-4 py-2">{{ $unit->name }}</td>
                <td class="px-4 py-2 text-right flex justify-end">
                    <x-primary-link href="{{ route('units.edit', $unit) }}" text="Edit" />
                    <form action="{{ route('units.destroy', $unit) }}" method="POST" class="">
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
        {{ $units->links() }}
    </div>
</div>

</x-app-layout>