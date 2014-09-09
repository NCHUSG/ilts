<?php

class UserController extends BaseController {

    protected $layout = 'master';

    public function index()
    {
        $user = IltUser::get();
        $u_id = $user->u_id;
        $user_option = IltUserOptions::find($u_id);
        $user_providers = IltUserProvider::where('u_id', '=', $u_id)->get();
        $user_providers_arr = array();
        
        //$providers = Config::get('sites.providers');

        $hybridauth_config = Config::get('hybridauth');

        $providers = array();

        foreach ($hybridauth_config['providers'] as $key => $provider) {
            if ($provider['enabled']) {
                $providers[] = $key;
            }
        }

        $providers_info = '';
        $access_clients = OAuthAccessToken::where('user_id', '=', $u_id)->get();
        $project = array();
        $projects_info = '';

        foreach ($user_providers as $user_provider) {
            $user_providers_arr[] = $user_provider->u_p_type;
        }

        foreach ($providers as $provider) {
            if ( in_array($provider, $user_providers_arr) ) {
                $providers_info .= '<li>' . $provider . '：' . '<span class="text-success">已通過</span></li>';
            }
            else {
                $providers_info .= '<li>' . $provider . '：' . '<span class="text-muted">尚未認證</span></li>';
            }
        }

        foreach ($access_clients as $access_client) {
            $client = OAuthClient::where('client_id', '=', $access_client->client_id)->first();
            $project = OAuthProject::find($client->project_id);

            if ( $access_client->expires < time() ) {
                $projects_info .= '<li>' . $project->name . '：' . '<span class="text-success">已通過</span></li>';
            }
            else {
                $projects_info .= '<li>' . $project->name . '：' . '<span class="text-muted">已過期</span></li>';
            }


        }

        $user_info = array(
            'username' => $user->u_username,
            'nickname' => $user->u_nick,
            'email' => $user->u_email,
        );

        $user_option_info = array(
            'first_name' => $user_option->u_first_name,
            'first_name' => $user_option->u_first_name,
            'gender' => $user_option->u_gender,
            'birthday' => $user_option->u_birthday,
            'phone' => $user_option->u_phone,
            'address' => $user_option->u_address,
            'website' => $user_option->u_website,
            'gravatar' => $user_option->u_gravatar,
            'description' => $user_option->u_description,
        );

        $data['provider']       = Session::get('user_being.provider');
        $data['providers_info'] = $providers_info;
        $data['projects_info']  = $projects_info;
        $data['user']           = $user;
        $data['user_info']      = $user_info;
        $data['user_option']    = $user_option_info;

        $data['isAdmin']        = $user->isAdmin();
        $data['isDev']          = $user->isDev();

        $data['fields']         = Config::get('fields');

        if (Session::has('message')) {
            $data['message'] = Session::get('message');
            Session::forget('message');
        }

        return View::make('user/info', array('name' => 'user'))->with($data);
    }

    public function identities()
    {
        $user = IltUser::get();

        $result = array();

        $groups = IltGroup::where('g_level_sort','=',0)->get()->all();

        foreach ($groups as $key => $group) {
            if (filter_var($group->getOption('public','false'), FILTER_VALIDATE_BOOLEAN)) {
                $result[$group->g_code] = array(
                    "name" => $group->g_name,
                    'status' => 'guest',
                    'statusText' => Config::get('fields.identity_status.guest'),
                    "url" => array('info' => route('group',$group->g_code))
                );
            }
        }

        $ids = $user->identities()->get()->all();

        foreach ($ids as $key => $id) {
            $authority = $id->i_authority;
            $group = $id->group()->first();
            $urlTmp = array('info' => route('group',$group->g_code));

            if ($authority == Config::get('sites.i_authority_admin_value'))
                $urlTmp['ctrl'] = route('groupCtrl',$group->g_code);

            $status = Config::get('sites.i_authority_value_to_readable.'.$authority);

            $statusText = Config::get('fields.identity_status.' . $status);

            $result[$group->g_code] = array(
                "name" => $group->g_name,
                'status' => $status,
                'statusText' => $statusText,
                "url" => $urlTmp
            );
        }

        $result = array(
            'groups' => $result,
            'more' => false,
            'nextUrl' => route('identities'),
        );

        return Response::json($result);
    }

    public function apply_developer()
    {
        $user = IltUser::get();

        if ( false !== stripos($user->u_authority, 'DEVELOPER' )) {
            return Redirect::action('DeveloperController@index');
        }

        if(Input::has('agree')) {
            $this->beforeFilter('csrf', array('on' => 'post'));

            $rules      = Config::get('validation.CTRL.user.apply_developer.rules');
            $messages   = Config::get('validation.CTRL.user.apply_developer.messages');
            $validator  = Validator::make(Input::all(), $rules, $messages);

            if ($validator->fails()) {
                return Redirect::action('UserController@apply_developer')->withErrors($validator)->withInput();
            }
            else {
                $user = IltUser::find(Session::get('user_being.u_id'));

                if( empty($user->u_authority) ) {
                    $user->u_authority = 'DEVELOPER';
                }
                else {
                    $user->u_authority .= ',DEVELOPER';
                }

                $user->save();

                $session['authority'] = explode(',', $user->u_authority);

                if ( !is_array($session['authority']) ) {
                    $session['authority'] = array($session['authority']);
                }

                Session::put('user_being.authority', $session['authority']);

                return Redirect::action('DeveloperController@index');
            }
        }
        else {
            return View::make('developer/terms');
        }
    }

    public function email_vallidate($type, $code) {
        $user = IltUser::get();
        $type = strtoupper($type);
        $email_orm = IltEmailVallisations::where('type', '=', $type)
                                     ->where('code', '=', $code)
                                     ->where('expires', '>', date('Y-m-d'))
                                     ->first();

        if ( false !== stripos($user->u_authority, $type )) {
            return View::make('user.email_vallidate_result', array('status' => 'already'));
        }
        elseif ( $email_orm == null ) {
            return View::make('user.email_vallidate_result', array('status' => 'not_found'));
        }
        elseif ( $email_orm->u_id != Session::get('user_being.u_id') ) {
            return View::make('user.email_vallidate_result', array('status' => 'not_match'));
        }

        $email = $email_orm->email;
        $email_orm->delete();

        switch ($type) {
            case 'STUDENT':
                $student = new IltUserStudent;
                $student->u_id       = Session::get('user_being.u_id');
                $student->email      = $email;
                $student->save();
                return Redirect::action('StudentController@apply_files_process');

            default:
                return View::make('user.email_vallidate_result', array('status' => 'success'));
        }

    }

    public function update_info($type){
        $user = IltUser::get();

        $availible_type = array('basic','option');
        if(!in_array($type, $availible_type))
            return "submit_type_not_availible!";

        $rules      = Config::get('validation.CTRL.user.update_info.'.$type.'.rules');
        $messages   = Config::get('validation.CTRL.user.update_info.'.$type.'.messages');
        $validator  = Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails()){
            return $validator->errors();
        }

        if($type == "basic"){
            $user->u_username = Input::get('username');
            $user->u_nick     = Input::get('nickname');
            $user->u_email    = Input::get('email');
            $user->save();
        }
        else{
            $user_opt = IltUserOptions::find($user->u_id);
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
        }
        
        return "OK!";
    }

}
