<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FamilyController;
use App\Http\Controllers\CityController;


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

Route::get('family/create', [FamilyController::class, 'create'])->name('family.create');
Route::post('family/store', [FamilyController::class, 'store'])->name('family.store');
Route::get('family', [FamilyController::class, 'index'])->name('family.index');
Route::get('family/{id}', [FamilyController::class, 'show'])->name('family.show');

Route::get('cities/get', [CityController::class, 'getCities'])->name('cities.get');
Route::post('family/{id}/add-member', [FamilyController::class, 'addMember'])->name('family.addMember');




Route::get('/', function () {
    return view('welcome');
});
