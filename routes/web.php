<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
    return view('admin.welcome');
})->middleware(['auth']);;

require __DIR__.'/auth.php';

Auth::routes(['verify' => true,'register' => true, 'reset' => true]);


/* ************ Admin route ********** */
Route::get('locations', [App\Http\Controllers\LocationController::class, 'index'])->name('locations.index')->middleware(['auth','admin']);
Route::get('/locations/list', [App\Http\Controllers\LocationController::class, 'get'])->name('locations.list')->middleware(['auth','admin']);
Route::post('/locations', [App\Http\Controllers\LocationController::class, 'store'])->name('locations.store')->middleware(['auth','admin']);
Route::put('/locations/{location}', [App\Http\Controllers\LocationController::class, 'update'])->name('locations.update')->middleware(['auth','admin']);
Route::delete('/locations/{location}', [App\Http\Controllers\LocationController::class, 'destroy'])->name('locations.destroy')->middleware(['auth','admin']);
Route::get('/locations/{id}/show', [App\Http\Controllers\LocationController::class, 'show'])->name('locations.show')->middleware(['auth','admin']);


//Route::resource('target-types', App\Http\Controllers\SlotController::class)->only('index', 'show')->middleware(['auth','admin']);
Route::get('slots', [App\Http\Controllers\SlotController::class, 'index'])->name('slots.index')->middleware(['auth','admin']);
Route::get('/slots/list', [App\Http\Controllers\SlotController::class, 'get'])->name('slots.list')->middleware(['auth','admin']);
Route::post('/slots', [App\Http\Controllers\SlotController::class, 'store'])->name('slots.store')->middleware(['auth','admin']);
Route::put('/slots/{slot}', [App\Http\Controllers\SlotController::class, 'update'])->name('slots.update')->middleware(['auth','admin']);
Route::delete('/slots/{slot}', [App\Http\Controllers\SlotController::class, 'destroy'])->name('slots.destroy')->middleware(['auth','admin']);
Route::get('/slots/{id}/show', [App\Http\Controllers\SlotController::class, 'show'])->name('slots.show')->middleware(['auth','admin']);

//Route::resource('target-types', App\Http\Controllers\TargetTypeController::class)->only('index', 'show')->middleware(['auth','admin']);

Route::get('targets', [App\Http\Controllers\TargetTypeController::class, 'index'])->name('targets.index')->middleware(['auth','admin']);
Route::get('/targets/list', [App\Http\Controllers\TargetTypeController::class, 'get'])->name('targets.list')->middleware(['auth','admin']);
Route::post('/targets', [App\Http\Controllers\TargetTypeController::class, 'store'])->name('targets.store')->middleware(['auth','admin']);
Route::put('/targets/{target}', [App\Http\Controllers\TargetTypeController::class, 'update'])->name('targets.update')->middleware(['auth','admin']);
Route::delete('/targets/{target}', [App\Http\Controllers\TargetTypeController::class, 'destroy'])->name('targets.destroy')->middleware(['auth','admin']);
Route::get('/targets/{id}/show', [App\Http\Controllers\TargetTypeController::class, 'show'])->name('targets.show')->middleware(['auth','admin']);




//Route::resource('compaign-objectives', App\Http\Controllers\CompaignObjectiveController::class)->only('index', 'show')->middleware(['auth','admin']);
Route::get('compaign_objectives', [App\Http\Controllers\CompaignObjectiveController::class, 'index'])->name('compaign_objectives.index')->middleware(['auth','admin']);
Route::get('/compaign_objectives/list', [App\Http\Controllers\CompaignObjectiveController::class, 'get'])->name('compaign_objectives.list')->middleware(['auth','admin']);
Route::post('/compaign_objectives', [App\Http\Controllers\CompaignObjectiveController::class, 'store'])->name('compaign_objectives.store')->middleware(['auth','admin']);
Route::put('/compaign_objectives/{compaign_objective}', [App\Http\Controllers\CompaignObjectiveController::class, 'update'])->name('compaign_objectives.update')->middleware(['auth','admin']);
Route::delete('/compaign_objectives/{compaign_objective}', [App\Http\Controllers\CompaignObjectiveController::class, 'destroy'])->name('compaign_objectives.destroy')->middleware(['auth','admin']);
Route::get('/compaign_objectives/{id}/show', [App\Http\Controllers\CompaignObjectiveController::class, 'show'])->name('compaign_categories.show')->middleware(['auth','admin']);


//Route::resource('compaign-categories', App\Http\Controllers\CompaignCategoryController::class)->only('index', 'show')->middleware(['auth','admin']);
Route::get('compaign_categories', [App\Http\Controllers\CompaignCategoryController::class, 'index'])->name('compaign_categories.index')->middleware(['auth','admin']);
Route::get('/compaign_categories/list', [App\Http\Controllers\CompaignCategoryController::class, 'get'])->name('compaign_categories.list')->middleware(['auth','admin']);
Route::post('/compaign_categories', [App\Http\Controllers\CompaignCategoryController::class, 'store'])->name('compaign_categories.store')->middleware(['auth','admin']);
Route::put('/compaign_categories/{compaign_category}', [App\Http\Controllers\CompaignCategoryController::class, 'update'])->name('compaign_categories.update')->middleware(['auth','admin']);
Route::delete('/compaign_categories/{compaign_category}', [App\Http\Controllers\CompaignCategoryController::class, 'destroy'])->name('compaign_categories.destroy')->middleware(['auth','admin']);
Route::get('/compaign_categories/{id}/show', [App\Http\Controllers\CompaignCategoryController::class, 'show'])->name('compaign_categories.show')->middleware(['auth','admin']);


//Route::resource('brands', App\Http\Controllers\BrandController::class)->only('index', 'show')->middleware(['auth','admin']);

Route::get('brands', [App\Http\Controllers\BrandController::class, 'index'])->name('brands.index')->middleware(['auth','admin']);
Route::get('/brands/list', [App\Http\Controllers\BrandController::class, 'get'])->name('brands.list')->middleware(['auth','admin']);
Route::post('/brands', [App\Http\Controllers\BrandController::class, 'store'])->name('brands.store')->middleware(['auth','admin']);
Route::put('/brands/{brand}', [App\Http\Controllers\BrandController::class, 'update'])->name('brands.update')->middleware(['auth','admin']);
Route::delete('/brands/{brand}', [App\Http\Controllers\BrandController::class, 'destroy'])->name('brands.destroy')->middleware(['auth','admin']);
Route::get('/brands/{id}/show', [App\Http\Controllers\BrandController::class, 'show'])->name('brands.show')->middleware(['auth','admin']);


Route::resource('langues', App\Http\Controllers\LangueController::class)->only('index', 'show')->middleware(['auth','admin']);

//Route::resource('hall-types', App\Http\Controllers\HallTypeController::class)->only('index', 'show')->middleware(['auth','admin']);
Route::get('halls', [App\Http\Controllers\HallTypeController::class, 'index'])->name('halls.index')->middleware(['auth','admin']);
Route::get('/halls/list', [App\Http\Controllers\HallTypeController::class, 'get'])->name('halls.list')->middleware(['auth','admin']);
Route::post('/halls', [App\Http\Controllers\HallTypeController::class, 'store'])->name('halls.store')->middleware(['auth','admin']);
Route::put('/halls/{hall}', [App\Http\Controllers\HallTypeController::class, 'update'])->name('halls.update')->middleware(['auth','admin']);
Route::delete('/halls/{hall}', [App\Http\Controllers\HallTypeController::class, 'destroy'])->name('halls.destroy')->middleware(['auth','admin']);
Route::get('/halls/{id}/show', [App\Http\Controllers\HallTypeController::class, 'show'])->name('halls.show')->middleware(['auth','admin']);


//Route::resource('movies', App\Http\Controllers\MovieController::class)->only('index', 'show')->middleware(['auth','admin']);
Route::get('movies', [App\Http\Controllers\MovieController::class, 'index'])->name('movies.index')->middleware(['auth','admin']);
Route::get('/movies/list', [App\Http\Controllers\MovieController::class, 'get'])->name('movies.list')->middleware(['auth','admin']);
Route::post('/movies', [App\Http\Controllers\MovieController::class, 'store'])->name('movies.store')->middleware(['auth','admin']);
Route::put('/movies/{movie}', [App\Http\Controllers\MovieController::class, 'update'])->name('movies.update')->middleware(['auth','admin']);
Route::delete('/movies/{movie}', [App\Http\Controllers\MovieController::class, 'destroy'])->name('movies.destroy')->middleware(['auth','admin']);
Route::get('/movies/{id}/show', [App\Http\Controllers\MovieController::class, 'show'])->name('movies.show')->middleware(['auth','admin']);



//Route::resource('movie-genres', App\Http\Controllers\MovieGenreController::class)->only('index', 'show')->middleware(['auth','admin']);
Route::get('movies_genres', [App\Http\Controllers\MovieGenreController::class, 'index'])->name('movies_genres.index')->middleware(['auth','admin']);
Route::get('/movies_genres/list', [App\Http\Controllers\MovieGenreController::class, 'get'])->name('movies_genres.list')->middleware(['auth','admin']);
Route::post('/movies_genres', [App\Http\Controllers\MovieGenreController::class, 'store'])->name('movies_genres.store')->middleware(['auth','admin']);
Route::put('/movies_genres/{movie_genre}', [App\Http\Controllers\MovieGenreController::class, 'update'])->name('movies_genres.update')->middleware(['auth','admin']);
Route::delete('/movies_genres/{movie_genre}', [App\Http\Controllers\MovieGenreController::class, 'destroy'])->name('movies_genres.destroy')->middleware(['auth','admin']);
Route::get('/movies_genres/{id}/show', [App\Http\Controllers\MovieGenreController::class, 'show'])->name('movies_genres.show')->middleware(['auth','admin']);


Route::resource('genders', App\Http\Controllers\GenderController::class)->only('index', 'show')->middleware(['auth','admin']);
//Route::resource('interests', App\Http\Controllers\InterestController::class)->only('index', 'show')->middleware(['auth','admin']);

Route::get('interests', [App\Http\Controllers\InterestController::class, 'index'])->name('interests.index')->middleware(['auth','admin']);
Route::get('/interests/list', [App\Http\Controllers\InterestController::class, 'get'])->name('interests.list')->middleware(['auth','admin']);
Route::post('/interests', [App\Http\Controllers\InterestController::class, 'store'])->name('interests.store')->middleware(['auth','admin']);
Route::put('/interests/{interest}', [App\Http\Controllers\InterestController::class, 'update'])->name('interests.update')->middleware(['auth','admin']);
Route::delete('/interests/{interest}', [App\Http\Controllers\InterestController::class, 'destroy'])->name('interests.destroy')->middleware(['auth','admin']);
Route::get('/interests/{id}/show', [App\Http\Controllers\InterestController::class, 'show'])->name('interests.show')->middleware(['auth','admin']);



Route::get('users', [App\Http\Controllers\UserController::class , 'index'])->name('users.index')->middleware(['auth','admin']);
Route::get('/users/list', [App\Http\Controllers\UserController::class, 'get'])->name('users.list')->middleware(['auth','admin']);
Route::post('/users', [App\Http\Controllers\UserController::class, 'store'])->name('users.store')->middleware(['auth','admin']);
Route::delete('/users/{user}', [App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy')->middleware(['auth','admin']);
Route::get('/users/{id}/show', [App\Http\Controllers\UserController::class, 'show'])->name('users.show')->middleware(['auth','admin']);
Route::put('/users/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update')->middleware(['auth','admin']);
Route::put('/users/{user}/update_password', [App\Http\Controllers\UserController::class, 'update_password'])->name('users.update_password')->middleware(['auth','admin']);
Route::post('users/check_username', [App\Http\Controllers\UserController::class , 'check_username'])->name('users.check_username')->middleware(['auth','admin']);





//Route::resource('compaigns', App\Http\Controllers\CompaignController::class)->only('index', 'show')->middleware(['auth','admin']);

Route::get('compaigns', [App\Http\Controllers\CompaignController::class, 'index'])->name('compaigns.index')->middleware(['auth','admin']);
Route::get('/compaigns/list', [App\Http\Controllers\CompaignController::class, 'get'])->name('compaigns.list')->middleware(['auth','admin']);
Route::post('/compaigns', [App\Http\Controllers\CompaignController::class, 'store'])->name('compaigns.store')->middleware(['auth','admin']);
Route::put('/compaigns/{compaign}', [App\Http\Controllers\CompaignController::class, 'update'])->name('compaigns.update')->middleware(['auth','admin']);
Route::delete('/compaigns/{compaign}', [App\Http\Controllers\CompaignController::class, 'destroy'])->name('compaigns.destroy')->middleware(['auth','admin']);
//Route::get('/compaigns/{compaign}', [App\Http\Controllers\CompaignController::class, 'show'])->name('compaigns.destroy')->middleware(['auth','admin']);
Route::get('/compaigns/{id}/show', [App\Http\Controllers\CompaignController::class, 'show'])->name('compaigns.show')->middleware(['auth','admin']);


/* ************ End Admin route ********** */

/* ************ advertiser  route ********** */
    Route::middleware(['auth', 'advertiser'])->prefix('advertiser')->name('advertiser.')->group(function () {
    Route::get('compaigns', [App\Http\Controllers\CompaignController::class, 'advertiser_index'])->name('compaigns.index');
    Route::get('compaigns/list', [App\Http\Controllers\CompaignController::class, 'my_compaigns'])->name('compaigns.my_compaigns');
    Route::get('/compaigns/{id}/show', [App\Http\Controllers\CompaignController::class, 'show'])->name('compaigns.show');
    Route::post('/compaigns', [App\Http\Controllers\CompaignController::class, 'store'])->name('compaigns.store');



    Route::get('dcp_creatives', [App\Http\Controllers\DcpCreativeController::class, 'index'])->name('dcp_creatives.index');
    Route::get('dcp_creatives/list', [App\Http\Controllers\DcpCreativeController::class, 'get'])->name('dcp_creatives.list');
    Route::post('dcp_creatives', [App\Http\Controllers\DcpCreativeController::class, 'store'])->name('dcp_creatives.store');
    Route::put('dcp_creatives/{interest}', [App\Http\Controllers\DcpCreativeController::class, 'update'])->name('dcp_creatives.update');
    Route::delete('dcp_creatives', [App\Http\Controllers\DcpCreativeController::class, 'destroy'])->name('dcp_creatives.destroy');
    Route::get('dcp_creatives/{id}/show', [App\Http\Controllers\DcpCreativeController::class, 'show'])->name('dcp_creatives.show');




    Route::post('/zip-upload/init', [App\Http\Controllers\DcpCreativeController::class, 'init'])->name('zip.upload.init');
    Route::post('/zip-upload/chunk', [App\Http\Controllers\DcpCreativeController::class, 'chunk'])->name('zip.upload.chunk');
    Route::post('/zip-upload/complete', [App\Http\Controllers\DcpCreativeController::class, 'complete'])->name('zip.upload.complete');



});
