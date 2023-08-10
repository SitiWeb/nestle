<x-app-layout>
<div class="container mx-auto bg-white rounded shadow p-6">
        <h1 class="text-3xl font-bold mb-6">Roles</h1>

        <a href="{{ route('roles.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md mb-4">Create Role</a>

        @if ($roles->count() > 0)
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="py-2 text-left">ID</th>
                        <th class="py-2 text-left">Name</th>
                        <!-- Add more table headers as needed -->
                        <th class="py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td class="py-2">{{ $role->id }}</td>
                            <td class="py-2">{{ $role->name }}</td>
                            <!-- Add more table cells as needed -->
                            <td class="py-2 flex">
                              
                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <x-primary-button button>Delete</x-primary-button >
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No roles found.</p>
        @endif
    </div>
</x-app-layout>
