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

    $wallet = $user->createWallet([
        'name' => 'Shipu Ahamed',
        'slug' => 'my-wallet-01h67jeez4jw49nmw30x11k0w8',
        'account_id' => '01h67jeez4jw49nmw30x11k0w8',
    ]);

    dd($user->balance); // 0
    dd($user->hasWallet('my-wallet')); // true
    return view('welcome');
});

Route::get('/hub/{tenant}/my-profile', \App\Livewire\MyProfile::class)->name('filament.admin.pages.my-profile');