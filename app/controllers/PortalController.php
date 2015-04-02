<?php

class PortalController extends BaseController {

    protected $layout = 'portal.master';

    public function login(){

        if (Input::has('callback')) {
            Session::put('portal.callback', urldecode(Input::get('callback')));
        }
        else {
            Session::forget('portal.callback');
        }

        $hybridauth_config = Config::get('hybridauth');

        $availible_providers = array();

        foreach ($hybridauth_config['providers'] as $key => $provider) {
            if ($provider['enabled']) {
                $provider_name = strtolower($key);
                $availible_providers[$provider_name] = action('PortalController@oauth',$provider_name);
            }
        }

        $data = array();
        $data['urls'] = $availible_providers;

        return View::make('portal/login', array('name' => 'Login'))->with($data);
    }


    public function oauth($action) {

        // check URL segment
        if ($action == "auth") {
            // process authentication
            try {
                Hybrid_Endpoint::process();
            }
            catch (Exception $e) {
                Log::error($e);
                Session::set("message","登入過程出了點問題，請稍候重試");
                return Redirect::route("logout");
            }
            return;
        }
        try {
            $hybridauth_config = Config::get('hybridauth');

            $provider = strtolower($action);
            $providers = array_map('strtolower',array_keys($hybridauth_config['providers']));

            if ( !in_array($provider, $providers))
                throw new Exception("invalid provider!", 1);

            $hybridauth_config['base_url'] = route("provider","auth") . "/";

            // create a HybridAuth object
            $socialAuth = new Hybrid_Auth($hybridauth_config);
            // authenticate with Google
            $social_provider = $socialAuth->authenticate($provider);
            // fetch user profile
            $userProfile = $provider->getUserProfile();

        }
        catch(Exception $e) {
            Log::error($e);
            Session::set("message","登入過程出了點問題，請稍候重試...");
            if(isset($social_provider)) $social_provider->logout();
            return Redirect::route("logout");
        }

        // access user profile data
        $oauth = array(
                    'status'    => true,
                    'provider'  => $social_provider->id,
                    'user'      => (object) (array) $userProfile);

        Session::put('oauth', $oauth);

        // logout
        $social_provider->logout();
        return Redirect::action('PortalController@login_process');
    }


    public function login_process() {

        $oauth = (object) Session::get('oauth');

        $provider   = strtolower( $oauth->provider );
        $identifier = $oauth->user->identifier;

        $i_u_p = IltUserProvider::firstOrCreate(array('u_p_identifier' => $identifier, 'u_p_type' => $provider));
        $user = $i_u_p->user();

        if (!$user->count()) {
            Session::put('provider',$i_u_p);
            return Redirect::route('register');
        }

        $user = $user->first();

        $session = array(   'status'    => true,
                            'provider'  => $provider,
                            'identifier'=> $identifier,
                            'u_id'      => $user->u_id,
                            'username'  => $user->u_username,
                            'user'      => $user,
                            'authority' => explode(',', $user->u_authority),
                            'level'     => $user->u_status);

        if ( !is_array($session['authority']) ) {
            $session['authority'] = array($session['authority']);
        }

        Session::put('user_being', $session);

        if ( Session::has('portal.callback') ) {

            $callback = Session::get('portal.callback');
            Session::forget('portal.callback');

            // var_dump($callback);
            // die();

            return Redirect::to($callback);
        }

        return Redirect::route('user');
    }

    public function register()
    {
        $oauth = (object) Session::get('oauth');

        $user  = $oauth->user;
        $email = $user->email;
        $username = substr($email, 0, stripos($email, '@'));
        $birthday = $user->birthYear . '/' . $user->birthMonth . '/' . $user->birthDay;

        $session = array(   'provider'  => $oauth->provider,
                            'identifier'=> $oauth->user->identifier,
                            'email'     => $email );

        Session::put('register', $session);

        $default['provider']    = strtolower( $oauth->provider );
        $default['identifier']  = $user->identifier;
        $default['username']    = Input::old('username',$username);
        $default['nickname']    = Input::old('nickname',    $user->displayName);
        $default['email']       = Input::old('email',       $email);
        $default['first_name']  = Input::old('first_name',  $user->firstName);
        $default['last_name']   = Input::old('last_name',   $user->lastName);
        $default['gender']      = Input::old('gender',      $user->gender);
        $default['birthday']    = Input::old('birthday',    $birthday);
        $default['phone']       = Input::old('phone',       $user->phone);
        $default['address']     = Input::old('address',     $user->address);
        $default['website']     = Input::old('website',     $user->webSiteURL);
        $default['description'] = Input::old('description', "");

        $data = array('default' => $default,
                      'action'  => action('register_process'),
                      'success_redirect' => route('user')
        );

        return View::make('portal/register', array('name' => 'register'))->with($data);
    }

    public function register_process()
    {
        $result     = array();
        $rules      = Config::get('validation.CTRL.portal.register_process.rules');
        $messages   = Config::get('validation.CTRL.portal.register_process.messages');
        $validator  = Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails())
        {
            $result["error"] = "您填寫的選項有誤，請檢查，謝謝";
            $result["errors"] = $validator->errors()->toArray();
            $result["success"] = false;

            return Response::json($result);
        }
        else {
            $register = (object) Session::get('register');
            $provider = Session::get('provider');

            $user = new IltUser;
            $user->u_username = Input::get('username');
            $user->u_nick     = Input::get('nickname');
            $user->u_email    = Input::get('email');
            $user->u_status   = 'Guest';
            $user->save();

            $user_opt = new IltUserOptions;
            $user_opt->u_id             = $user->u_id;
            $user_opt->u_first_name     = Input::get('first_name');
            $user_opt->u_last_name      = Input::get('last_name');
            $user_opt->u_gender         = Input::get('gender');
            $user_opt->u_birthday       = Input::get('birthday');
            $user_opt->u_phone          = Input::get('phone');
            $user_opt->u_address        = Input::get('address');
            $user_opt->u_website        = Input::get('website');
            $user_opt->u_gravatar       = Input::get('gravater');
            $user_opt->u_description    = Input::get('description');
            $user_opt->save();

            $provider->u_p_type         = $register->provider;
            $provider->u_p_identifier   = $register->identifier;
            $provider->u_p_email        = $register->email;
            $provider->u_id             = $user->u_id;
            $provider->save();

            Session::forget('register');
            Session::forget('provider');
            Session::forget('oauth');

            $session = array(   'status'    => true,
                                'provider'  => $register->provider,
                                'identifier'=> $register->identifier,
                                'u_id'      => $user->u_id,
                                'username'  => $user->u_username,
                                'authority' => explode(',', $user->u_authority),
                                'level'     => $user->u_status);

            if ( !is_array($session['authority']) ) {
                $session['authority'] = array($session['authority']);
            }

            Session::put('user_being', $session);

            if ( Session::has('portal.callback') ) {

                $callback = Session::get('portal.callback');
                Session::forget('portal.callback');

                $result["url"] = $callback;
                $result["message"] = "註冊成功，將轉回您之前要求的畫面...";
            }
            else{
                $result["url"] = route('user');
                $result["message"] = "註冊成功!";
            }

            if(!IltGroup::where("g_status","like","%admin%")->count()){
                $this->init_ilt($user);
                $result["url"] = route('admin');
                $result["message"] = "您是第一個使用者，網站初始化完成！已預設您為管理員！";
            }

            $result["success"] = true;

            return Response::json($result);
        }
    }

    public function logout() {
        Session::forget('user_being');
        return Redirect::route('login');
    }

    private function init_ilt($user){
        $default_value = Config::get('default');

        $g_admin = new IltGroup();
        $g_admin->g_code = $default_value['admin_group']['code'];
        $g_admin->g_name = $default_value['admin_group']['name'];
        $g_admin->setAdmin();
        $g_admin->save();

        $g_dev = new IltGroup();
        $g_dev->g_code = $default_value['dev_group']['code'];
        $g_dev->g_name = $default_value['dev_group']['name'];
        $g_dev->setDev();
        $g_dev->save();

        $user->join($g_admin,true);
        $user->join($g_dev,true);
    }


}
