<!-- filters.blade.php -->
@props(['data','totalResults'])
<div class="flex justify-between my-4">
<!-- Button to trigger the modal -->
<div class="flex space-x-4 ">

<button id="openModalBtn" class='flex items-center justify-center px-4 py-2 bg-black border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 active:bg-gray-900  focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150' >
Open filters
</button>
<form action="{{ route('units.overview') }}" class="" method="GET">
                <x-primary-button>Clear</x-primary-button>
            </form>
           <!-- Display total results -->
        <p>Total Results: {{ $totalResults }}</p>
        </div>
        
        <x-primary-link href="{{ route('export.csv', request()->all()) }}" class="btn btn-primary" text="Export CSV"></x-primary-link>


    </div>


<!-- Modal container -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div class="flex space-x-4 mb-4">
            <form action="{{ route('units.overview') }}" method="GET">
                <!-- Category filter -->
                <div class="flex flex-col">
                    <div class="flex">
                    
                    </div>
                    <div class="flex">
                    <x-field type="filter_dates" name="cf_install_date" label="Install date" :oldValue="null" />
                    <x-field type="filter_dates" name="cf_audit_date" label="Audit date" :oldValue="null" />
                    <x-field type="filter_dates" name="cf_renovation_date" label="Renovation date" :oldValue="null" />
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-4 w-full">
                        <x-field type="filter_dimensions" name="cf_dimensions_fixturebuild" label="Fixturebuild" :oldValue="null" />
                        <x-field type="filter_dimensions" name="cf_dimensions_graphic" label="Graphic" :oldValue="null" />
                        <x-field type="filter_dimensions" name="cf_dimensions_backpanel" label="Backpanel" :oldValue="null" />
                        <x-field type="filter_dimensions" name="cf_dimensions_screen" label="Screen" :oldValue="null" />
                        <x-field type="filter_dimensions" name="cf_dimensions_shelfstrip" label="Shelfstrip" :oldValue="null" />
                    </div>
                 
                    
              
                       <x-primary-button>Apply</x-primary-button>
                    </div>
                    </form>
                    
                    <!-- Clear filters button -->
                    
        </div>
    </div>
</div>
<style>
/* Modal styles */
.modal {
    display: none; /* Hide the modal by default */
    position: fixed; /* Position the modal */
    z-index: 1; /* Set the modal's stacking order */
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto; /* Enable scrolling within the modal */
    background-color: rgba(0, 0, 0, 0.5); /* Add a semi-transparent background overlay */
}

.modal-content {
    background-color: #fefefe;
    margin: 10% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

/* Close button */
.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}
</style>


<script>
    $(document).ready(function() {
    // Open the modal when the button is clicked
    $('#openModalBtn').on('click', function() {
        $('#myModal').show();
    });

    // Close the modal when the close button is clicked
    $('.close').on('click', function() {
        $('#myModal').hide();
    });
});
</script>