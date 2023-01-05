<?php

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

Route::group(['prefix' => 'oengine'], function () {
    Route::post('/livewire/component/{slug}', [OEngine\Core\Http\Controllers\OEngineServiceController::class, 'loadComponent']);
    Route::post('/switchSidebar', [OEngine\Core\Http\Controllers\OEngineServiceController::class, 'switchSidebar']);
});
Route::get(apply_filters('route_page_login_url', 'auth/login'), apply_filters('route_page_login_component', OEngine\Core\Http\Livewire\Page\Auth\Login::class))->name('core.login');
Route::get(apply_filters('route_page_register_url', 'auth/register'), apply_filters('route_page_register_component', OEngine\Core\Http\Livewire\Page\Auth\Register::class))->name('core.register');
Route::get(apply_filters('route_page_forgot_password_url', 'auth/forgot-password'), apply_filters('route_page_forgot_password_component', OEngine\Core\Http\Livewire\Page\Auth\ForgotPssword::class))->name('core.forgot_password');
Route::get('/', apply_filters('route_page_home_component', function () {
    return function () {
        return "Hello,";
    };
}));
