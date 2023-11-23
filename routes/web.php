<?php

use App\Models\Wallet;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Filament\Http\Middleware\IdentifyTenant;

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

Route::get('schedule-run', function(){
    Artisan::call('schedule:run');
})
->withoutMiddleware([
    IdentifyTenant::class,
]);