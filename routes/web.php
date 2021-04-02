<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Cabinet\HomeController as CabinetHomeController;
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

Route::get('/', [HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/verify/{token}', [RegisterController::class, 'verify'])->name('register.verify');

Route::get('/cabinet', [CabinetHomeController::class, 'index'])->name('cabinet');

Route::group(
    [
        'prefix' => 'admin',
        'as' => 'admin.',
        'namespace' => 'App\\Http\\Controllers\\Admin',
        'middleware' => ['auth', 'can:admin-panel'],
    ],
    function () {
        Route::get('/', 'HomeController@index')->name('home');
        Route::resource('users', 'UsersController');
        Route::post('/users/{user}/verify', 'UsersController@verify')->name('users.verify');
    }
);
