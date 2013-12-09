<?php

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class UserController extends \BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {

        //
        $users = User::all();
        $response = Response::make($users->toJson(), 200);

        //$response->header('Content-Type', $value);

        return $response;
    }

    /**
     * 用户注册
     *
     * @return Response
     */
    public function create() {
        //
        $user = new User();
        $user->username = Input::get('username');
        $user->password = Input::get('password');
        $user->email = Input::get('email');

        $validator = $user->getRegisterValidator();
        if ($validator->fails()) {
            $messages = $validator->messages();
            return Response::make($messages, 200);
        }
        if ($user->save()) {
            Auth::login($user);
            return Response::make($user->id, 200);
        } else {
            return Response::make($user->getErrors(), 404);
        }
    }

    /**
     * 用户信息获取
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        //
        $response = Response::make(User::findOrFail($id)->toJson(), 200);
        return $response;
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        //
    }
    
}
