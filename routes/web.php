<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

Auth::routes([
    'register' => false
]);

Route::middleware('auth')->group(function () {

    if (Schema::hasTable('navigations')){
        $navigation = DB::table('navigations')->get();

        foreach ($navigation as $key => $nav) {
            if (empty($nav->nav_controller)){
                continue;
            } 

            $controller = 'App\Http\Controllers\\'.$nav->nav_controller.'Controller'::class;

            // FOR IMPORT
            Route::post($nav->nav_route.'/import', [$controller, 'import'])->name($nav->nav_route.'.import');
            // ENDS HERE

            Route::resource($nav->nav_route, $controller);
        }
    }
    
    // My Account
    $match = ['PUT', 'POST'];
    Route::get('/account_settings/password', [App\Http\Controllers\MyAccountController::class, 'password'])
        ->name('account_settings.password');
    Route::put('/account_settings/password_update', [App\Http\Controllers\MyAccountController::class, 'passwordUpdate'])
        ->name('account_settings.password_update');
    Route::get('/account_settings/email', [App\Http\Controllers\MyAccountController::class, 'email'])
        ->name('account_settings.email');
    Route::match($match, '/account_settings/email_update', [App\Http\Controllers\MyAccountController::class, 'emailUpdate'])->name('account_settings.email_update');
    Route::get('/account_settings/delete_account', [App\Http\Controllers\MyAccountController::class, 'deleteAccount'])
        ->name('account_settings.delete_account');
    Route::match($match, '/account_settings/change_profile', [App\Http\Controllers\MyAccountController::class, 'changeProfile'])->name('account_settings.change_profile');
    Route::resource('/account_settings', App\Http\Controllers\MyAccountController::class);
    // Ends here
});