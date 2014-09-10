<?php

class TestController extends BaseController {

    public function main()
    {
        var_dump("Session");
        var_dump(Session::all());
        // var_dump("CurrUser");
        // var_dump(IltUser::get());
        // var_dump("opt");
        // var_dump(strpos("abc","a") === 0);
        // $email_orm = IltEmailVallisations::where('code', '=', '46190f24e59c5bedf00cd37caa334e51')->where('expires', '>', date('Y-m-d'))->first();
        // var_dump($email_orm);
        // var_dump($email_orm->identity()->first());
        var_dump(DB::getQueryLog());
        
        die();
        return "";
    }


}
