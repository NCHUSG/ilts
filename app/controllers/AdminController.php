<?php

class AdminController extends BaseController {

    protected $layout = 'master';

    public function index()
    {
        $data = array();

        $site_option = Config::get('default.options');
        $site_option = array_merge($site_option,IltSiteOptions::getAll());
        $data['site_option'] = $site_option;

        $data['fields'] = Config::get('fields');
        return View::make('admin/info')->with($data);
    }

    public function option()
    {
        $inputs = Input::all();
        $rules      = Config::get('validation.CTRL.options.rules');
        $messages   = Config::get('validation.CTRL.options.messages');
        $validator  = Validator::make($inputs, $rules, $messages);

        if ($validator->fails()){
            return $validator->errors();
        }

        $inputs['_token'] = null;

        foreach ($inputs as $key => $value) {
            IltSiteOptions::put($key,$value);
        }
        
        return "OK!";
    }


}
