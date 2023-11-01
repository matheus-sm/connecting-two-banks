<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;


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


Route::get('/test-connection', function () {
    try {
        DB::connection()->getPdo();
        return "Conexão bem-sucedida!";
    } catch (\Exception $e) {
        return "Erro na conexão: " . $e->getMessage();
    }
})->middleware('auth');

Route::get('/', function () {
    return view('welcome');
})->middleware('auth');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
