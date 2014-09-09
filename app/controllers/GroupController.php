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

        $data = array();

        $data['group'] = $group;
        $data['code'] = $group->g_code;

        $info = Config::get('default.group_public_options');
        
        foreach ($info as $key => $value) {
            if (isset($options[$key])) {
                $info[$key] = isset($options[$key]);
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
            $data['is_admin'] = $id->is_admin();

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
            $data['is_admin'] = false;

            $data['display_subGroup'] = $this->toBool('allow_guest_see_child_group');
            $data['display_member'] = $this->toBool('allow_guest_see_members');
            $data['display_create'] = false;
        }

        // var_dump($options);
        // var_dump($data);
        // die();

        return View::make('group/info', array('name' => 'group'))->with($data);
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

                    if ($id){
                        if ($id->is_admin())
                            $urlTmp['ctrl'] = route('groupCtrl',$group->g_code);

                        $status = Config::get('sites.i_authority_value_to_readable.'.$id->i_authority);
                    }
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
            'groups' => $result,
            'more' => false,
            'nextUrl' => route('subGroup',$code),
        );

        return Response::json($result);
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
        $rules      = Config::get('validation.CTRL.group.rules');
        $messages   = Config::get('validation.CTRL.group.messages');
        $validator  = Validator::make(Input::all(), $rules, $messages);

        if ($validator->fails())
        {
            $result["errors"] = $validator->errors()->toArray();
            //return Redirect::to('portal/register')->withErrors($validator)->withInput();
            return Response::json($result);
            //return Redirect::route('user');
        }
        else {
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

            $optionsConfig = Config::get('default.group_options');

            foreach ($optionsConfig as $key => $value)
                $options[$key] = Input::get($key,$value);

            $optionsConfig = Config::get('default.group_bool_options');

            foreach ($optionsConfig as $key => $value)
                $options[$key] = Input::get($key,$value);
            
            $g->options($options);

            $g->recruit(IltUser::get(),true);

            $result["url"] = route('group',$g->g_code);
            return Response::json($result);
        }
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
                $result['message'] = '請輸入學生信箱';
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
            $email = new IltEmailVallisations;
            $email->i_id    = $id->getKey();
            $email->type    = $method;
            $email->code    = $code;
            $email->email   = input::get('email');
            $email->expires = date('Y-m-d', time() + 3 * 24 * 3600);
            $email->save();

            $data = array(
                'username'  => $user->u_username,
                'unit'      => Config::get('sites.name'),
                'link'      => action( 'UserController@email_vallidate', array('student', $code) )
            );

            Mail::send('student.email_vallidation_mail', $data, function($message)
            {
                $message
                ->to( Input::get('email'), IltUser::get()->u_username )
                ->subject('伊爾特系統 身份確認信');
            });

            $result['message'] = '已發出信件，請至信箱內確認！';
            $result['status'] = 'success';
            $result['refresh'] = 2000;
        }

        return $result;
    }

    public function update_info($type){
        $user = $this->user;
    }

}
