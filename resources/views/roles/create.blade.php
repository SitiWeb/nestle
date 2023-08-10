<x-app-layout>
<div class="container mx-auto bg-white rounded shadow p-6">
        <h1 class="text-3xl font-bold mb-6">Create Role</h1>

        <form action="{{ route('roles.store') }}" method="POST" class="max-w-md mx-auto">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-gray-700">Role Name</label>
                <input type="text" name="name" id="name" class="form-input mt-1 block w-full" required>
            </div>

            <!-- Add more form fields as needed -->

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Create Role</button>
        </form>
    </div>
</x-app-layout>