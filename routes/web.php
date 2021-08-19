<?php

use App\Http\Controllers\OrgController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\PositionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth'])->group(function () {
   
    Route::get('/', [PersonController::class, 'index'])->name('home');
    Route::resource('people', PersonController::class)->except(['index']);
    Route::get('/people/$person/orgs/', [OrgController::class, 'people'])->name('orgs.people');
    
    Route::resource('orgs', OrgController::class);
    Route::get('/orgs/$org/people/', [OrgController::class, 'people'])->name('orgs.people');
    
    Route::resource('positions', PositionController::class)->except(['index','show']);
    
});

require __DIR__.'/auth.php';
