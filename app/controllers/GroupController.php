<?php

class GroupController extends BaseController {

    protected $layout = 'master';
    protected $user;

    private $configDefaultPrefix;
    private $optionBoolOption;
    private function toBool($key)
    {
        if (isset($this->optionBoolOption[$key])) {
            return filter_var($this->optionBoolOption[$key], FILTER_VALIDATE_BOOLEAN);
        }
        else
            return filter_var(Config::get($this->configDefaultPrefix . $key), FILTER_VALIDATE_BOOLEAN);
    }

    public function index($code)
    {
        $group = IltGroup::get($code);
        $user = IltUser::get();
        $id = IltIdentity::get($user,$group);
        $options = $group->options();
        $is_admin = false;

        $data = array();

        $data['group'] = $group;
        $data['code'] = $group->g_code;

        $info = Config::get('default.group_public_options');
        
        foreach ($info as $key => $value) {
            if (isset($options[$key])) {
                $info[$key] = $options[$key];
            }
        }

        $data['info'] = $info;

        $join_option = array();

        $this->configDefaultPrefix = 'default.group_bool_options.';
        $this->optionBoolOption = $options;

        $join_option = array(
            'joinByAllow' => $this->toBool('joinByAllow'),
            'directJoinable_by_StudentEmailValidation' => $this->toBool('directJoinable_by_StudentEmailValidation'),
            'directJoinable_by_EmailValidation' => $this->toBool('directJoinable_by_EmailValidation'),
            'directJoinable' => $this->toBool('directJoinable')
        );
        $data['join_option'] = $join_option;

        if ($id) {
            $data['is_pending'] = $id->is_pending();
            $data['display_join'] = $data['is_pending'];
            $is_admin = $id->is_admin();

            if ($id->member_or_higher()) {
                $data['display_subGroup'] = $this->toBool('allow_members_see_child_group') || $data['is_admin'];
                $data['display_member'] = $this->toBool('allow_members_see_members') || $data['is_admin'];
                $data['display_create'] = $id->is_admin() || ($this->toBool('allow_member_create_child_group') && $id->is_member());
            }
            else{
                $data['display_subGroup'] = $this->toBool('allow_guest_see_child_group');
                $data['display_member'] = $this->toBool('allow_guest_see_members');
                $data['display_create'] = false;
            }
        }
        else{
            $data['is_pending'] = false;
            $data['display_join'] = $join_option['joinByAllow'] || $join_option['directJoinable_by_StudentEmailValidation'] || $join_option['directJoinable_by_EmailValidation'] || $join_option['directJoinable'];

            $data['display_subGroup'] = $this->toBool('allow_guest_see_child_group');
            $data['display_member'] = $this->toBool('allow_guest_see_members');
            $data['display_create'] = false;
        }

        $data['is_admin'] = $is_admin;

        if ($group->g_parent_id)
            $data['parent_group'] = $group->getParent();

        if (Session::has('message')) {
            $data['message'] = Session::get('message');
            Session::forget('message');
        }

        // =====================================
        // Group Admin
        // =====================================

        if ($is_admin) {

            $data['basic_info'] = array('name' => $group->g_name, 'code' => $group->g_code);

            $defaultInfo = Config::get('default.group_options');
            $option = array();
            
            foreach ($defaultInfo as $key => $value) {
                if (isset($options[$key]))
                    $option[$key] = $options[$key];
                else
                    $option[$key] = $value;
            }

            $defaultInfo = Config::get('default.group_bool_options');
            
            foreach ($defaultInfo as $key => $value) {
                if (isset($options[$key]))
                    $option[$key] = $options[$key];
                else
                    $option[$key] = $value;
            }

            $data['option'] = $option;
            $data['fields'] = array_merge(Config::get('fields.group'),Config::get('fields.join_method'));
        }

        // =====================================

        return View::make('group/info', array('name' => 'group'))->with($data);
    }

    public function ctrl($code,$type)
    {
        $availible_type = array('basicCtrl','public','bool_option');
        if(!in_array($type, $availible_type))
            App::abort(405);

        $user = IltUser::get();
        $group = IltGroup::get($code);

        $rules      = Config::get('validation.CTRL.group.'.$type.'.rules');
        $messages   = Config::get('validation.CTRL.group.'.$type.'.messages');
        $validator  = Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails()){
            $result = array(
                'success' => false,
                'message' => '您填的選項有誤，請檢查',
                'errors' => $validator->errors()->toArray()
            );
            return Response::json($result);
        }

        $default = array();

        switch ($type) {
            case 'basicCtrl':
                $group->g_name = Input::get('name');

                if(Input::get('code') != $group->g_code){
                    if(IltGroup::get(Input::get('code') !== false)){
                        $result = array(
                            'success' => false,
                            'message' => '您填的選項有誤，請檢查',
                            'errors' => array("code" => "這個簡稱已經被使用過了...")
                        );
                        return Response::json($result);
                    }
                }
                $group->g_code = Input::get('code');
                $group->save();
                break;
            case 'public':
                $default = Config::get('default.group_public_options');
            case 'bool_option':
                $default = array_merge($default,Config::get('default.group_bool_options'));
                $option = array();
                foreach ($default as $name => $def)
                    $option[$name] = Input::get($name,$def);
                $group->options($option);
                break;
            default:
                App::abort(405);
        }
        
        $result = array(
            'success' => true,
            'message' => '修改成功！',
            'url' => route('group',$group->g_code) . '#ctrl',
        );
        
        return Response::json($result);
    }

    public function subGroups($code)
    {
        $user = IltUser::get();
        $group = IltGroup::get($code);
        $memberOrHigher = IltIdentity::memberOrHigher($user,$group);

        $groups = $group->children()->get()->all();

        $result = array();

        foreach ($groups as $key => $group) {
            $id = IltIdentity::get($user,$group);
            if ($group->getBoolOption('public') || $id) {
                $urlTmp = array();

                if ($memberOrHigher) {
                    $urlTmp['info'] = route('group',$group->g_code);

                    if ($id)
                        $status = Config::get('sites.i_authority_value_to_readable.'.$id->i_authority);
                    else
                        $status = 'guest';
                }
                else
                    $status = 'unreachable';

                $result[$group->g_code] = array(
                    "name" => $group->g_name,
                    'status' => $status,
                    'statusText' => Config::get('fields.identity_status.'.$status),
                    "url" => $urlTmp,
                    'test' => $memberOrHigher,
                );
            }
        }

        $result = array(
            'data' => $result,
            'more' => false,
            'nextUrl' => route('subGroup',$code),
        );

        return Response::json($result);
    }

    public function member($code,$page = null)
    {
        try {
            $user = IltUser::get();
            $group = IltGroup::get($code);
            $id = IltIdentity::get($user,$group);

            $result = array();

            if (!$group->getBoolOption("allow_guest_see_members")) {
                if (!$group->getBoolOption("allow_members_see_members") && !$id->admin_or_higher()) {
                    throw new Exception("You are not allowed to view members in this group!");
                }
            }

            $page = $page ? $page : 0;

            $limit = Config::get('sites.number_of_user_per_page');
            $user_shown_column = Config::get('sites.user_shown_column');
            $i_authority_value_to_readable = Config::get('sites.i_authority_value_to_readable');
            $identity_status = Config::get('fields.identity_status');

            $members_db = $group->users()->skip($limit * ($page))->take($limit)->get($user_shown_column)->all();

            $members = array();

            foreach ($members_db as $key => $m) {
                $member['name'] = $m->u_nick;
                $info = array(
                    'username' => $m->u_username,
                    'email' => $m->u_email,
                );
                $member['info'] = $info;
                $status = $i_authority_value_to_readable[$m->i_authority];
                $member['status'] = $status;
                $member['statusText'] = $identity_status[$status];

                $members[] = $member;
            }

            if(count($members_db) == $limit){
                $more = true;
                $page++;
            }
            else{
                $more = false;
                $page = 0;
            }

            // var_dump($members);
            // //var_dump($queries = DB::getQueryLog());
            // die();

            $result = array(
                'data' => $members,
                'more' => $more,
                'nextUrl' => route('member',array($code,$page)),
            );

        } catch (Exception $e) {
            $result['error'] = $e->getMessage();
        }

        return Response::json($result);
    }

    public function invite($code){

    }

    public function allow($code,$username){
        
    }

    public function create($code = null)
    {
        $default = array();

        $defaultConfig = Config::get('default.group');

        foreach ($defaultConfig as $key => $value)
            $default[$key] = Input::old($key,$value);

        $defaultConfig = Config::get('default.group_public_options');

        foreach ($defaultConfig as $key => $value)
            $default[$key] = Input::old($key,$value);

        $defaultConfig = Config::get('default.group_options');

        foreach ($defaultConfig as $key => $value)
            $default[$key] = Input::old($key,$value);

        $defaultConfig = Config::get('default.group_bool_options');

        foreach ($defaultConfig as $key => $value)
            $default[$key] = Input::old($key,$value);

        $data = array('default' => $default,
                      'action'  => isset($code) ? action('createGroupProcess',$code) : action('createRootGroupProcess'));

        return View::make('group/create', array('name' => 'createGroup'))->with($data);
    }

    public function create_process($code = null)
    {
        $result     = array();
        $rules      = array_merge(
            Config::get('validation.CTRL.group.basic.rules'),
            Config::get('validation.CTRL.group.public.rules'),
            Config::get('validation.CTRL.group.bool_option.rules')
        );
        $messages   = array_merge(
            Config::get('validation.CTRL.group.basic.messages'),
            Config::get('validation.CTRL.group.public.messages'),
            Config::get('validation.CTRL.group.bool_option.messages')
        );
        $validator  = Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails())
        {
            $result = array(
                'success' => false,
                'errors' => $validator->errors()->toArray()
            );
            return Response::json($result);
        }
        
        if (isset($code))
            $g = IltGroup::get($code)->createChild();
        else
            $g = new IltGroup;

        $g->g_code = Input::get('code');
        $g->g_name = Input::get('name');
        $g->save();

        $options = array();

        $optionsConfig = Config::get('default.group_public_options');

        foreach ($optionsConfig as $key => $value)
            $options[$key] = Input::get($key,$value);

        $optionsConfig = Config::get('default.group_bool_options');

        foreach ($optionsConfig as $key => $value)
            $options[$key] = Input::get($key,$value);
        
        $g->options($options);

        $g->recruit(IltUser::get(),true);

        $result = array(
            'success' => true,
            'message' => '建立成功！',
            'url' => route('group',$g->g_code) . '#ctrl',
        );
        
        return Response::json($result);
    }

    public function join($code,$method){
        $result = array();
        $user = IltUser::get();
        $group = IltGroup::get($code);

        try {
            switch ($method) {
                case 'joinByAllow':
                    if (!$group->getBoolOption('joinByAllow'))
                        throw new Exception("method not allowed!");

                    IltIdentity::pending($user,$group);
                    $result['message'] = '已發出請求，請等待管理員核准！';
                    $result['status'] = 'success';
                    $result['refresh'] = 2000;

                    break;
                case 'directJoinable_by_StudentEmailValidation':

                    $result = 
                        $this->process_join_by_email($user,$group,'directJoinable_by_StudentEmailValidation');

                    break;
                case 'directJoinable_by_EmailValidation':

                    $result = 
                        $this->process_join_by_email($user,$group,'directJoinable_by_EmailValidation');

                    break;
                case 'directJoinable':
                    if (!$group->getBoolOption('directJoinable'))
                        throw new Exception("method not allowed!");

                    IltIdentity::member($user,$group);
                    $result['message'] = '成功加入！';
                    $result['status'] = 'success';
                    $result['refresh'] = 2000;

                    break;
                default:
                    throw new Exception("Unknown method.");
                    break;
            }
        } catch (Exception $e) {
            $result['error'] = $e->getMessage();
        }
        return Response::json($result);
    }

    private function process_join_by_email($user,$group,$method)
    {
        if (!$group->getBoolOption($method))
            throw new Exception("method not allowed!");
        $rules      = Config::get('validation.CTRL.process_join_by_email.'.$method.'.rules');
        $messages   = Config::get('validation.CTRL.process_join_by_email.'.$method.'.messages');
        $validator  = Validator::make(Input::all(), $rules, $messages);
        if($validator->fails()){
            $result['form'] = array(
                'hidden' => array(
                    '_token' => csrf_token(),
                ),
                'text' => array(
                    'email' => '',
                ),
            );
            $result['postURL'] = route('join',array($group->g_code,$method));
            if(Request::isMethod('get')){
                $result['message'] = Config::get('fields.email_form_message.' . $method);
                $result['status'] = 'info';
            }
            else{
                $result['message'] = implode(", ", $validator->errors()->toArray()['email']);
                $result['status'] = 'danger';
            }
        }
        else{
            $id = IltIdentity::pending($user,$group);

            $code = md5( time() + $user->u_username );
            while(IltEmailVallisations::where('code','=',$code)->count()){
                $code = md5( $code + $user->u_username );
            }

            $email = new IltEmailVallisations;
            $email->i_id    = $id->getKey();
            $email->type    = $method;
            $email->code    = $code;
            $email->email   = input::get('email');
            $email->expires = date('Y-m-d', time() + 3 * 24 * 3600);
            $email->save();

            $data = array(
                'username'  => $user->u_username,
                'group_name'=> $group->g_name,
                'method'    => Config::get('fields.join_method.' . $method . '.zh_TW'),
                'unit'      => Config::get('sites.name'),
                'link'      => route('emailValidation',$code),
            );

            Mail::send('group.email_vallidation_mail', $data, function($message)
            {
                $message
                ->to( Input::get('email'), IltUser::get()->u_username )
                ->subject('[' . Config::get('sites.name') . '] ' . Config::get('fields.email_validation_title.zh_TW'));
            });

            $result['message'] = '已發出信件，請至信箱內確認！';
            $result['status'] = 'success';
            $result['refresh'] = 2000;
        }

        return $result;
    }

}
