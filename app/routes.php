<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});
Route::any('register', 'UserController@create');
Route::any('login', function(){
        $username = Input::get('email');
        $password = Input::get('password');
        $attempt = array('email' => $username, 'password' => $password);
        if (Auth::attempt($attempt)) {
            return Response::make(Auth::user()->id, 200);
        }else{
            return Response::make(0, 200);
        }
    });
    
Route::group(array('before' => 'auth'), function() {
    Route::any('logout', function() {
        Auth::logout();
        return \Illuminate\Http\JsonResponse::create('true');
    });
    Route::any('user/list', 'UserController@index');
    Route::any('user/changePassword', 'UserController@changePassword');
    Route::any('user/{id}', 'UserController@show');
    Route::any('user/edit/{id}', 'UserController@edit');
});
