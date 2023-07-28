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
    $user = \App\Models\User::first();

    $wallet = $user->getWallet('cash');
//    $wallet->deposit(100);

    dump($wallet->refreshBalance()); // 0
    dd($wallet->balance); // 0
    dd($user->hasWallet('my-wallet')); // true
    return view('welcome');
});

Route::get('/hub/{tenant}/my-profile', \App\Livewire\MyProfile::class)->name('filament.admin.pages.my-profile');