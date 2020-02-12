<?php

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

Auth::routes([
  'register' => false, // Registration Routes...
  'reset' => false, // Password Reset Routes...
  'verify' => false, // Email Verification Routes...
]);
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::prefix('admin')->middleware(['auth'])->group(function () {
	Route::get('/', 'AdminController@home');
	
	Route::middleware(['isAdmin'])->group(function () {

      // Queue
      Route::get('/queue', 'AdminController@listQueue')->name('admin.queue.list');
      Route::post('/queue', 'AdminController@createQueue')->name('admin.queue.create');
      Route::get('/queue/{id}/delete', 'AdminController@deleteQueue')->name('admin.queue.delete');
      Route::get('/queue/{id}', 'AdminController@showQueue')->name('admin.queue.show');
      Route::put('/queue/{id}', 'AdminController@setOperator')->name('admin.queue.set-operator');

      // Operators
      Route::get('/operator', 'AdminController@listOperator')->name('admin.operator.list');
      Route::post('/operator', 'AdminController@createOperator')->name('admin.operator.create');
      Route::get('/operator/{id}/delete', 'AdminController@deleteOperator')->name('admin.operator.delete');
      Route::get('/operator/{id}', 'AdminController@showOperator')->name('admin.operator.show');

      // statistic
      Route::post('/stat', 'AdminStatController@operatorStat');
  	});
    Route::middleware(['isOperator'])->prefix('operator-level')->group(function () {

      // Queue
      Route::get('/queue', 'OperatorController@listQueue')->name('operator.queue.list');
      Route::get('/queue/{id}', 'OperatorController@showQueue')->name('operator.queue.show');
      Route::post('/queue/{id}', 'OperatorController@nextButton')->name('operator.queue.next-button');
      Route::post('/queue/{id}/operatorStatus', 'OperatorController@operatorStatus')->name('operator.queue.operator-status');

    });
});

Route::get('/', 'GuestController@listQueue')->name('queue.list');
Route::get('/{id}', 'GuestController@showQueue')->name('queue.show');
Route::post('/{id}', 'GuestController@standIn')->name('queue.standIn');
Route::delete('/{id}/{object}', 'GuestController@standOut')->name('queue.standOut');
Route::get('/{id}/resetCookie/{key}', 'GuestController@resetCookie')->name('queue.resetCookie');
