<?php

use Illuminate\Support\Facades\Route;
use App\Models\Konten;
use Filament\Http\Controllers\Auth\AuthenticatedSessionController;


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

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', function () {
    $kontens = Konten::all();
    return view('landing', compact('kontens'));
})->name('landing');

// Route::get('admin/login', [AuthenticatedSessionController::class, 'create'])
//     ->name('filament.auth.login');

// Route::get('login', function () {
//     return view('filament..auth.login'); // Jika ini adalah lokasi login
// })->name('filament..auth.login');
