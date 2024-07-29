<?php

use App\Http\Controllers\OrderController;
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
    return view('welcome');
});
Route::get('/',[OrderController::class,'create']);

$cruds = [
    // 'categories' => CategoryController::class,
    'orders' => OrderController::class,
];

foreach ($cruds as $obj => $controller) {
    Route::resource($obj, $controller);
}

// Route::prefix('posts')
//     ->name('posts.')
//     ->controller(PostController::class)
//     ->group(function () {
//         Route::get('/',                'index')->name('index');
//         Route::get('/create',          'create')->name('create');
//         Route::post('/create',         'store')->name('store');
//         Route::get('/show/{id}',       'show')->name('show');
//         Route::get('/edit/{id}',       'edit')->name('edit');
//         Route::put('/update/{id}',     'update')->name('update');
//         Route::delete('/delete/{id}',  'destroy')->name('delete');
//     });