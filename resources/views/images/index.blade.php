<x-app-layout>
<div class="container mx-auto bg-white rounded shadow p-6">
<x-slot name="header">
        <div class="flex justify-between">
            <div>
                {{ __('Index Images') }}
            </div>
            <div>
               
            </div>
        </div>
        
</x-slot>
        @if (session('success'))
            <div class="bg-green-500 text-white py-2 px-4 mb-4">
                {{ session('success') }}
            </div>
        @endif

        <table class="w-full border-collapse">
            <thead>
                <tr>
                    <th class="py-2 px-4 bg-gray-200">ID</th>
                    <th class="py-2 px-4 bg-gray-200">Image</th>
                    <th class="py-2 px-4 bg-gray-200">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($images as $image)
                    <tr>
                        <td class="py-2 px-4">{{ $image->id }}</td>
                        <td class="py-2 px-4">
                            <img src="{{ asset('storage/' . $image->path) }}" alt="Image" class="w-16 h-16 object-cover rounded">
                        </td>
                        <td class="py-2 px-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('images.show', $image) }}" class="py-1 px-2 bg-blue-500 text-white rounded hover:bg-blue-600">Show</a>
                                <form action="{{ route('images.destroy', $image) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="py-1 px-2 bg-red-500 text-white rounded hover:bg-red-600">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>