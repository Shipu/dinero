<?php

use Filament\Facades\Filament;
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
    dd(\App\Models\Wallet::all()->last()->refreshBalance());
    dd(\App\Models\Wallet::first()->withdraw(20, [
        'account_id' => '01h6s8xtdq50m3pjhg4sndnb1k',
    ]));
    return view('welcome');
});

Route::get('/hub/{tenant}/my-profile', \App\Livewire\MyProfile::class)->name('filament.admin.pages.my-profile');