<x-app-layout>
    <div class="container mx-auto bg-white rounded shadow p-6">
        <h1 class="text-3xl font-bold mb-6">Users</h1>

        <a href="{{ route('users.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md mb-4">Create User</a>

        @if ($users->count() > 0)
            <table class="w-full">
                <thead>
                    <tr class="text-left">
                        <th class="py-2">ID</th>
                        <th class="py-2">Name</th>
                        <th class="py-2">Email</th>
                        <th class="py-2">Role</th>
                        <th class="py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td class="py-2">{{ $user->id }}</td>
                            <td class="py-2">{{ $user->name }}</td>
                            <td class="py-2">{{ $user->email }}</td>
                            <td class="py-2">{{ $user->role->name }}</td>
                            <td class="py-2 flex">
                                <x-primary-link href="{{ route('users.edit', $user->id) }}" class="mr-2" text="Edit"></x-primary-link>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <x-primary-button  type="submit" class="text-red-500 hover:text-red-700">Delete</x-primary-button >
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No users found.</p>
        @endif
    </div>
</x-app-layout>