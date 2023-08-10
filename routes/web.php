<?php
use App\Http\Controllers\RegionController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Livewire\Shelfs;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return Redirect::to('/dashboard');
});

Route::get('/images/{filename}', 'App\Http\Controllers\ImageController@show')->name('image');
Route::get('/brand-images/{filename}', 'App\Http\Controllers\ImageController@show_brand')->name('brands.image');

Route::middleware(['auth','role:admin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class)->middleware(['auth','role:admin'])->only(['index', 'store', 'destroy','create']);
    Route::get('/map', [RegionController::class, 'index'])->name('map');
    Route::get('/overview', [UnitController::class, 'overview'])->name('units.overview');
    Route::get('/dashboard', [UnitController::class, 'overview'])->name('units.dashboard');
    Route::resource('images',ImageController::class)->only(['index', 'store', 'destroy']);
    Route::resource('units', UnitController::class);
    Route::resource('locations', LocationController::class);
    Route::resource('brands', BrandController::class);
    Route::get('units/{unit}/image-delete/{image}', [UnitController::class, 'deleteImage'])->name('units.image-delete');


});


Route::middleware(['auth','role:editor'])->group(function () {
    Route::get('/import', [ImportController::class, 'index'])->name('import');
    Route::post('/import/upload', [ImportController::class, 'upload'])->name('import.upload');
    Route::get('/map', [RegionController::class, 'index'])->name('map');
    Route::get('/overview', [UnitController::class, 'overview'])->name('units.overview');
    Route::get('/dashboard', [UnitController::class, 'overview'])->name('units.dashboard');
    Route::resource('images',ImageController::class)->only(['index', 'store', 'destroy', 'edit']);
    Route::resource('units', UnitController::class);
    Route::resource('locations', LocationController::class);
    Route::resource('brands', BrandController::class);
    Route::get('units/{unit}/image-delete/{image}', [UnitController::class, 'deleteImage'])->name('units.image-delete');
    Route::get('/units/{unit}/shelves', Shelfs::class)->name('units.shelves');

});
Route::middleware(['auth','role:reader'])->group(function () {
    Route::get('/export', [UnitController::class, 'exportToCsv'])->name('export.csv');

    Route::get('/map', [RegionController::class, 'index'])->name('map');
    Route::get('/overview', [UnitController::class, 'overview'])->name('units.overview');
    Route::get('/dashboard', [UnitController::class, 'overview'])->name('units.dashboard');
    Route::resource('units', UnitController::class)->only(['index', 'show']);
    Route::resource('locations', LocationController::class)->only(['index', 'show']);
    Route::resource('brands', BrandController::class)->only(['index', 'show']);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/search/bylocation', [UnitController::class, 'bylocation'])->name('units.bylocation');
    Route::get('/search/bybrand', [UnitController::class, 'bybrand'])->name('units.bybrand');
    Route::get('/search/byadvanced', [UnitController::class, 'byadvanced'])->name('units.byadvanced');
    Route::get('/download-file/{filename}', [UnitController::class, 'download'])->middleware('auth')->name('download.file');

});

Route::get('/request_admin', [UserController::class, 'request_admin'])->name('user.request_admin');
Route::get('/request_login', [UserController::class, 'request_login'])->name('user.request_login');
Route::post('/request_admin', [UserController::class, 'request_admin_action'])->name('user.request_admin_action');
Route::post('/request_login', [UserController::class, 'request_login_action'])->name('user.request_login_action');


require __DIR__.'/auth.php';
