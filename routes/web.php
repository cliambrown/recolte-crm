<?php

use App\Http\Controllers\OrgController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\OrgRelationshipController;
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
    
    Route::resource('orgs', OrgController::class);
    Route::resource('org_relationships', OrgRelationshipController::class)->except(['index','show']);
    
    Route::resource('positions', PositionController::class)->except(['index','show']);
    Route::get('/people/{person}/confirm-current-position', [PositionController::class, 'confirm_current'])->name('positions.confirm_current');
    Route::put('/people/{person}/confirm-current-position', [PositionController::class, 'update_current'])->name('positions.update_current');
    
});

require __DIR__.'/auth.php';
