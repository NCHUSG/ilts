<?php

class DeveloperController extends BaseController {

    protected $layout = 'master';

    public function index()
    {
        $data = array();

        if (Session::has('message')) {
            $data['message'] = Session::get('message');
            Session::forget('message');
        }
        
        return View::make('developer/info')->with($data);
    }


}
