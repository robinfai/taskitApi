<?php

use Illuminate\Support\Facades\Input;

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
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        //
        $user = new User();
        $user->username = Input::get('username');
        $user->password = Input::get('password');
        $user->email = Input::get('email');
        if($user->save()){
            return Response::make($user->id, 200);
        }else{
            return Response::make($user->getErrors(),404);
        }

        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        //
    }

    /**
     * Display the specified resource.
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        //
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        //
    }

}
