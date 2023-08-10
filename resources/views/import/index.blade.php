<x-app-layout>
<form method="POST" action="{{ route('import.upload') }}" class="w-1/2" enctype="multipart/form-data">
@csrf
<div class="pb-6">
    <div>Start an import by uploading a file</div>
    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">Upload file</label>
    <input class="block w-full text-sm text-gray-900 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="file_input" name="file_input" type="file">    
</div>
<div class="pb-6">
    <div>Start an import by url a Google sheet</div>
    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_input">URL file</label>
    <input class="block w-full text-sm text-gray-900 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="google_sheet_url" name="google_sheet_url" type="text">    
</div>
<x-primary-button name="create_dimensions">
    {{ __('Upload & Import') }}
</x-primary-button>
</form>
</x-app-layout>
