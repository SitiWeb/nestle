<x-app-layout>
 
        <div class="flex justify-between">
            <div>
                {{ __('Create Brand') }}
            </div>
            <div>
                <div class="">
                    <x-primary-link href="{{ route('brands.index') }}" text="Back to Brands" />
                </div>
            </div>
        </div>

    @if ($errors->any())
       
            <div class="bg-red-500 text-white font-bold rounded-md p-4">
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
    
    @endif
    <div class="container mx-auto bg-white rounded shadow p-6">
        <form method="POST" action="{{ route('brands.store') }}" class="w-1/2" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="name" class="block">Brand Name:</label>
                <input type="text" name="name" id="name" required class="w-full border border-gray-300 rounded px-2 py-1">
            </div>
            <div class="mb-4">
                <label for="image" class="block">Brand Image:</label>
                <input type="file" name="img" id="img" accept="image/*" onchange="previewImage(event)" class="w-full">
                <img id="preview" class="mt-2 w-32 h-32 object-cover rounded" src="#" alt="Brand Image Preview" style="display: none;">
            </div>
            <!-- Add fields for meta values as needed -->
            <x-primary-button>
                {{ __('Create') }}
            </x-primary-button>
        </form>
    </div>

    <script>
        function previewImage(event) {
            var input = event.target;
            var reader = new FileReader();
            reader.onload = function () {
                var preview = document.getElementById('preview');
                preview.src = reader.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    </script>
</x-app-layout>
