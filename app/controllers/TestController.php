<?php

class TestController extends BaseController {

    public function main()
    {
        // var_dump("Session");
        // var_dump(Session::all());
        // var_dump("CurrUser");
        // var_dump(IltUser::get());
        // var_dump("opt");
        var_dump(IltGroup::get('test')->options());

        var_dump( "-3" >= "2");
        var_dump(DB::getQueryLog());
        
        die();
        return "";
    }


}
