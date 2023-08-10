<x-app-layout>

    <div class="container mx-auto bg-white rounded shadow p-6">
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

        <form method="POST" action="{{ route('units.store') }}" class="w-1/2" enctype="multipart/form-data">
            @csrf
        
            @include('units.form')
            <!-- Add fields for meta values as needed -->
            <div class="flex space-x-4">
                <x-primary-button>
                    {{ __('Create') }}
                </x-primary-button>
                <x-primary-button name="create_dimensions">
                    {{ __('Create and add dimensions') }}
                </x-primary-button>
            </div>
        </form>
    </div>
    <script>
        function previewImages(event, id) {
            var previewContainer = document.getElementById(id + 'Preview-multi');
            console.log(id);
            previewContainer.innerHTML = '';

            var files = event.target.files;
            for (var i = 0; i < files.length; i++) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Preview';
                    img.className = 'h-20 w-20 object-cover rounded-md';
                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(files[i]);
            }
        }
    </script>

    <script>
        document.addEventListener('click', function(event) {
            console.log('click');
            if (event.target.matches('.delete-button')) {
                event.preventDefault();
                const unitId = event.target.getAttribute('data-unit-id');
                const imageId = event.target.getAttribute('data-image-id');
                deleteImage(unitId, imageId);
            }
        });

        function deleteImage(unitId, imageId) {
            fetch(`/units/${unitId}/image-delete/${imageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                })
                .then(response => response.json())
                .then(data => {
                    // Handle the response as needed
                    console.log(data);
                })
                .catch(error => {
                    // Handle the error
                    console.error(error);
                });
        }
        $(".js-basic-search").select2({
            matcher: matchCustom,
            width : "100%"
        });

        function matchCustom(params, data) {
            // If there are no search terms, return all of the data
            if ($.trim(params.term) === '') {
                return data;
            }

            // Do not display the item if there is no 'text' property
            if (typeof data.text === 'undefined') {
                return null;
            }

            // `params.term` should be the term that is used for searching
            // `data.text` is the text that is displayed for the data object
            if (data.text.toUpperCase().indexOf(params.term.toUpperCase()) > -1) {
                return data;
            }

            // custom search using lookup data
            if ($(data.element).data('lookup').toUpperCase().indexOf(params.term.toUpperCase()) > -1) {
                return data;
            }

        // Return `null` if the term should not be displayed
        return null;
        }
    </script>

</x-app-layout>
