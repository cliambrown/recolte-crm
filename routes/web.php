<?php

use App\Http\Controllers\PersonController;
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
    Route::resource('links', PersonController::class)->except(['index']);
    
});

require __DIR__.'/auth.php';
