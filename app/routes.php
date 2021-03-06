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
Route::get('mailTest', function()
{
    Mail::send('emails.welcome', array(), function($message)
    {
        $message->to('robinfai@me.com', 'John Smith')->subject('Welcome!');
    });
    //return View::make('hello');
});
Route::get('password/remind', function()
{
    if(Input::get('email')){
        $credentials = array('email' => Input::get('email'));

        return Password::remind($credentials);
    }
    if(Session::has('error')){
        echo Session::get('error');
    }elseif(Session::has('success')){
        echo 'An e-mail with the password reset has been sent.';
    }
});
Route::get('password/reset/{token}', function($token)
{
    return View::make('auth.reset')->with('token', $token);
});Route::post('password/reset/{token}', function()
{
    $credentials = array(
        'email' => Input::get('email'),
        'password' => Input::get('password'),
        'password_confirmation' => Input::get('password_confirmation')
    );

    return Password::reset($credentials, function($user, $password)
    {
        $user->password = Hash::make($password);

        $user->save();

        return Redirect::to('home');
    });
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

    //用户相关路由
    Route::any('logout', function() {
        Auth::logout();
        return \Illuminate\Http\JsonResponse::create('true');
    });
    Route::any('user/list', 'UserController@index');
    Route::any('user/changePassword', 'UserController@changePassword');
    Route::any('user/{id}', 'UserController@show');
    Route::any('user/edit/{id}', 'UserController@edit');
    //用户相关路由

    //board相关路由
    Route::any('board/create', 'BoardController@create');
    Route::any('board/update/{id}', 'BoardController@update');
    Route::any('board/addMember/{id}', 'BoardController@addMember');
    Route::any('board/removeMember/{id}', 'BoardController@removeMember');
    Route::any('board/addAdmin/{id}', 'BoardController@addAdmin');
    Route::any('board/removeAdmin/{id}', 'BoardController@removeAdmin');
    Route::any('board', 'BoardController@index');

    //cardList相关路由
    Route::any('cardList/getList/{id}', 'CardListController@index');
    Route::any('cardList/create', 'CardListController@create');
    Route::any('cardList/update/{id}', 'CardListController@update');

    //card相关路由
    Route::any('card/getList/{id}', 'CardController@index');
    Route::any('card/create', 'CardController@create');
    Route::any('card/update/{id}', 'CardController@update');

    Route::any('card/addColor/{id}/{color}', 'CardController@addColor');
    Route::any('card/removeColor/{id}/{color}', 'CardController@removeColor');

    Route::any('card/setCompletionTime/{id}', 'CardController@setCompletionTime');
    Route::any('card/addMember/{id}', 'CardController@addMember');
    Route::any('card/removeMember/{id}', 'CardController@removeMember');
});
