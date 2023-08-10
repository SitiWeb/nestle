<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <div>
                {{ __('Edit Brand') }}
            </div>
            <div>
                <div class="">
                    <x-primary-link href="{{ route('brands.index') }}" text="Back to Brands" />
                </div>
            </div>
        </div>
    </x-slot>

    <div class="container mx-auto bg-white rounded shadow p-6">
        <h1 class="text-3xl font-bold mb-4">Edit Brand</h1>
        <form method="POST" action="{{ route('brands.update', $brand) }}" class="w-1/2" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <x-field type="text" name="name" label="Brand Name" :oldValue="$brand->name" required />

            <div class="mb-4">
                <label for="img" class="block">Brand Image:</label>
                <input type="file" name="img" id="img" accept="image/*" onchange="previewImage(event)" class="w-full">
                <img id="preview" class="mt-2 w-32 h-32 object-cover rounded" src="{{url('/')}}/{{ $brand->img }}" alt="Brand Image Preview">
            </div>

            <!-- Add fields for meta values as needed -->

            <x-primary-button>
                {{ __('Edit') }}
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
            };
            reader.readAsDataURL(input.files[0]);
        }
    </script>
</x-app-layout>
