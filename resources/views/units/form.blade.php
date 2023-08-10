<div class="container mx-auto bg-white rounded shadow p-6 my-4">
<div class="border-b border-gray-900/10 pb-12">
    <h2 class="text-base font-semibold leading-7 text-gray-900">Store</h2>
    <x-field type="store" name="location_id" label="Store" :oldValue="$unit->location_id ?? null" :data="$locations" />
</div>

<div class="border-b border-gray-900/10 pb-12">
    <h2 class="text-base font-semibold leading-7 text-gray-900">Brand</h2>
    

    <div class="space-y-4" name="" id="multibrand" >
    @foreach($brands as $brand)
    <label class="flex items-center">
            <input type="checkbox" name="brand_id[]" value="{{ $brand->id }}" class="form-checkbox"
                @isset($unit)
                {{ $unit->brands->contains('id', $brand->id) ? 'checked' : '' }}>
                @endisset
            <span class="ml-2">{{ $brand->name }}</span>
        </label>
    @endforeach

    </div>


</div>
</div>
<div class="container mx-auto bg-white rounded shadow p-6 my-4">
<div class="border-b border-gray-900/10 pb-12">
    <h2 class="text-base font-semibold leading-7 text-gray-900">Unit info</h2>
    <p class="mt-1 text-sm leading-6 text-gray-600">Info about this unit.</p>

    <x-field type="text" name="name" label="Name" :oldValue="$unit->name ?? null" required />
    <x-field type="text" name="fields[cf_unit_type]" label="Unit type" :oldValue="$unit->meta['cf_unit_type'] ?? null" />
    <x-field type="text" name="fields[cf_nitr_data_source_code]" label="NITS data source" :oldValue="$unit->meta['cf_nitr_data_source_code'] ?? null" />
    <x-field type="text" name="fields[cf_asset_tag_number]" label="Asset tag number" :oldValue="$unit->meta['cf_asset_tag_number'] ?? null" />
    <x-field type="text" name="fields[cf_nitr_location_code]" label="NITR Location Code"
        :oldValue="$unit->meta['cf_nitr_location_code'] ?? null" />
    <x-field type="text" name="fields[cf_region]" label="Region" :oldValue="$unit->meta['cf_region'] ?? null" />
    <x-field type="date" name="fields[cf_install_date]" label="NITR install date" :oldValue="$unit->meta['cf_install_date'] ?? null" />
    <x-field type="date" name="fields[cf_audit_date]" label="Audit date" :oldValue="$unit->meta['cf_audit_date'] ?? null" />
    <x-field type="date" name="fields[cf_renovation_date]" label="Renovation date" :oldValue="$unit->meta['cf_renovation_date'] ?? null" />
    <x-field type="condition" name="fields[cf_unit_condition]" label="Unit Condition at Time of Audit"
        :oldValue="$unit->meta['cf_unit_condition'] ?? null" />
    <x-field type="textarea" name="description" label="General Comments" :oldValue="$unit->description ?? null" required />

</div>
</div>

<div class="container mx-auto bg-white rounded shadow p-6 my-4">
<div class="border-b border-gray-900/10 pb-12">
    <h2 class="text-base font-semibold leading-7 text-gray-900">Images</h2>
    <x-field type="images" name="images" label="Images" :oldValue="$unit ?? null" required />


    
</div>
</div>
<div class="container mx-auto bg-white rounded shadow p-6 my-4">
<div class="border-b border-gray-900/10 pb-12">
    <h2 class="text-base font-semibold leading-7 text-gray-900">NITR</h2>
    <x-field type="text" name="fields[cf_nitr_region]" label="NITR Region" :oldValue="$unit->meta['cf_nitr_region'] ?? null" />
    <x-field type="text" name="fields[cf_nitr_top_50]" label="NITR Top 50 Ranking" :oldValue="$unit->meta['cf_nitr_top_50'] ?? null" />
</div>

<div class="border-b border-gray-900/10 pb-12">
    <h2 class="text-base font-semibold leading-7 text-gray-900">Other</h2>
    <x-field type="text" name="fields[cf_ba_present]" label="BA present" :oldValue="$unit->meta['cf_unit_type'] ?? null" />
    <x-field type="text" name="fields[cf_auditing_supplier]" label="Auditing Supplier"
        :oldValue="$unit->meta['cf_auditing_supplier'] ?? null" />
    <x-field type="text" name="fields[cf_auditing_supplier_technician]"
        label="Auditing Supplier Technician" :oldValue="$unit->meta['cf_auditing_supplier_technician'] ?? null" />
</div>
</div>
<div class="container mx-auto bg-white rounded shadow p-6 my-4">
<div class="border-b border-gray-900/10 pb-12">
    <x-field type="text" name="file_name" label="File name" :oldValue="null" />
    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Upload file</label>
    <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="file_input" name="file_input" type="file">    
    
    @isset($unit)
    Uploaded files: <br>
    <table>
    @foreach($unit->files as $file)


@php
    $filePath = $file->path;
    $files = Storage::url($filePath);

@endphp
        <tr>
            <td>{{$file->id}}</td>
            <td>{{$file->name}}</td>
            <td><a href="{{ route('download.file', ['filename' => $file->path]) }}">Download File</a></td>
            <td>{{$file->created_at}}</td>
            <td>Delete</td>
        </tr>

    @endforeach
    </table>
    @endisset
</div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {

    // Get the <select> element using Select2
    var brandSelect = $("#brand_id");

    // Listen for the "select2:select" event
    brandSelect.on("select2:select", function(e) {
    // Get the selected option
    var selectedOption = e.params.data;

    // Check if the selected option is "Multi Brand"
    if (selectedOption.id === "28") {
        // Perform your desired action here
        jQuery('#multibrand').show();
        // Additional code...
    }
    else{
        jQuery('#multibrand').hide();
    }
    });
});
</script>