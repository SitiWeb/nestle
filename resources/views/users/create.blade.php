<x-app-layout>
    <div class="container mx-auto bg-white rounded shadow p-6">
        <h1 class="text-3xl font-bold mb-6">Edit User</h1>

        <form action="{{ route('users.store') }}" method="POST" class="max-w-md mx-auto">
            @csrf
    
            <div class="mb-4">
                <label for="name" class="block text-gray-700">Name</label>
                <input type="text" name="name" id="name" class="form-input mt-1 block w-full" value="" required>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" name="email" id="email" class="form-input mt-1 block w-full" value="" required>
            </div>

            <div class="mb-4">
                <label for="role" class="block text-gray-700">Role</label>
                <select name="role" id="role" class="form-select mt-1 block w-full" required>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" >{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700">New Password</label>
                <input type="password" name="password" id="password" class="form-input mt-1 block w-full">
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-gray-700">Confirm New Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-input mt-1 block w-full">
            </div>

            <button type="submit" name="create" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Update User</button>
            
        </form>
    </div>
</x-app-layout>
    