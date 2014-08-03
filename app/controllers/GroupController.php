<?php

class UserController extends BaseController {

    protected $layout = 'master';
    protected $user;

    public function __construct() {
        $this->user = IltUser::find(Session::get('user_being.u_id'));
    }

    public function index()
    {
        if(!$this->user)return Redirect::route('logout');
        $user = $this->user;
    }

    public function update_info($type){
        if(!$this->user)return Redirect::route('logout');
        $user = $this->user;
    }

}
