<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Location') }}
        </h2>
    </x-slot>
<div class="container mx-auto bg-white rounded shadow p-6">
        <h1 class="text-3xl font-bold mb-4">Edit {{$location->name}}</h1>
        <form method="POST" action="{{ route('locations.update',$location) }}" class="w-1/2">
            @csrf
            @method('PUT')
            <x-field type="country" name="country" label="Country" :oldValue="$location->country ?? null" :data="$countries" />
            <x-field type="text" name="name" label="Store name" :oldValue="$location->name ?? null" required/>
            <x-field type="text" name="airport_store_name" label="Airport/Downtown Store Name" :oldValue="$location->airport_store_name ?? null" required/>
            <x-field type="text" name="airport_code" label="Airport code" :oldValue="$location->airport_code ?? null" />
            <x-field type="text" name="terminal" label="Terminal" :oldValue="$location->terminal ?? null" />
            <x-field type="text" name="retailer" label="Retailer" :oldValue="$location->retailer ?? null" />
            
               
            <x-primary-button>
                {{ __('Edit') }}
            </x-primary-button>
        </form>
    </div>
    <script>
        $(document).ready(function() {
            $('.js-basic-single').select2({
                width: '100%', // need to override the changed default
                height: '100px' 
            });
        });
    </script>
</x-app-layout>