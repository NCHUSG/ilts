<?php

class OAuthController extends BaseController {

    // protected $layout = 'master';

    public function __construct()
    {
        header('X-XRDS-Location: ' . route('oauthHeader'));
    }

    public function index()
    {
        return 'oauth index';
    }

    public function auth_server()
    {
        $client_key = Input::get('client_key');

        if ( true === empty($client_key) ) {

            if( true !== Session::has('auth_srv.client_key') ) {
                // return Redirect::route('oauthErr001');
                return $this->error('argument_losing');
            }

            $client_key = Session::get('auth_srv.client_key');
            Session::forget('auth_srv');
        }

        $client_query = OAuthClient::where('client_key', '=', $client_key);

        // Client 存在檢查
        if ( 0 == $client_query->count() ) {
            // return Redirect::route('oauthErr002');
            return $this->error('client_no_exist');
        }

        $client          = $client_query->first();
        $access_form     = $client->from_uri;
        $access_redirect = $client->redirect_uri;
        $host_uri        = urldecode(Input::get('host_uri'));
        $redirect_uri    = urldecode(Input::get('redirect_uri'));

        // 強化安全性，增加來Client要求轉換的網址是否正常的判斷。
        if ( false === stripos($access_redirect, $redirect_uri) ) {
            // exit('redirect_uri not valid');
            return $this->error('redirect_uri_not_valid',"this redirect_uri is '{$redirect_uri}'");
        }

        // 把奇怪的 scope 濾掉
        $scope_set = explode(' ', Input::get('scope'));

        var_dump(Input::get('scope'));
        var_dump($scope_set);
        // die();

        $available_scope = Config::get('sites.oauth_scope');
        $scope  = array();

        foreach ($scope_set as $key => $scope_e) {
            if (in_array($scope_e, $available_scope)) {
                $scope[] = $scope_e;
                unset($scope_set[$key]);
            }
        }

        $available_scope = Config::get('sites.oauth_scope_id_prefix');

        $scope_identity  = array();

        foreach ($scope_set as $i => $scope_e) {
            foreach ($available_scope as $j => $value) {
                if (strpos($scope_e,$value) === 0) {
                    $scope_identity[] = $scope_e;
                    unset($scope_set[$key]);
                }
            }
        }

        if (!(count($scope) + count($scope_identity)))
            return $this->error('no_available_scope');

        $expires     = Input::has('expires') ? Input::get('expires') : 180;
        $u_id        = IltUser::get()->getKey();
        $token_query = OAuthAccessToken::where('client_id', '=', $client->client_id)->where('user_id', '=', $u_id);

        if ( $token_query->count() ) {
            $token_obj = $token_query->first();
            $token = $token_obj->access_token;

            # TODO: token放在網址有其風險性，未來需要強化安全性。   // 其實好像還好？

            if (implode(' ', array_merge($scope,$scope_identity)) == $token_obj->scope) {
                $delimiter = (false === stripos($redirect_uri, '?')) ? '?' : '&' ;
                return Redirect::to($redirect_uri . $delimiter . 'token=' . $token);
            }
        }

        // 用Session記錄client_key，讓轉移來回時仍能保存。會在後段使用後消除。
        Session::put('iltOAuth.client_key', $client_key);
        Session::put('iltOAuth.callback', 'http://' . $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
        Session::put('iltOAuth.scope', $scope);
        Session::put('iltOAuth.scope_identity', $scope_identity);
        Session::put('iltOAuth.expires', $expires);
        Session::put('iltOAuth.redirect_uri', $redirect_uri);
        Session::put('iltOAuth.auth2res_own', true);


        # TODO: 以client id取代client key。     // 忘記為啥要這樣做了

        // 轉移到授權頁面，並附上client_key，讓頁面知道是哪個Client。
        return Redirect::route('oauthOwner');
    }

    # TODO: 增加Scope到參數裡
    // Resource Owner
    public function resource_owner()
    {
        // 此設定為檢查是否正確從auth_sever跳轉，如此一來就可以不用重複檢查auth_server檢查過的事項
        if ( !Session::has('iltOAuth.auth2res_own') ) {
            return 'unnormal redirect!';
        }

        $u_id            = Session::get('user_being.u_id');
        $iltOAuth        = Session::get('iltOAuth') and Session::forget('iltOAuth');

        $client_key      = $iltOAuth['client_key'];
        $callback_uri    = $iltOAuth['callback'];
        $scope           = $iltOAuth['scope'];
        $scope_identity  = $iltOAuth['scope_identity'];
        $expires         = $iltOAuth['expires'];
        $redirect_uri    = $iltOAuth['redirect_uri'];

        $client_query    = OAuthClient::where('client_key', '=', $client_key);

        if ( 0 == $client_query->count() )
            return $this->error('client_no_exist');

        $client      = $client_query->first();
        $project     = OAuthProject::find($client->project_id);

        // 檢查是否收到表單Post信息，若無則轉成確認頁面，若有則開始處理。
        if ( false == Input::has('request_submit') ) {

            $require = array();
            $scope_hash = Config::get('fields.oauth_scope');

            foreach ($scope as $value)
                $require[$value] = $scope_hash[$value];

            $scope_hash = Config::get('fields.oauth_scope_id');
            $oauth_scope_id_prefix = Config::get('sites.oauth_scope_id_prefix');

            foreach ($scope_identity as $value) {
                $extracted = $this->extractIdScope($value);
                if ($extracted[0])
                    $require[$value] = str_replace('{group}',$extracted[0]->g_name , $scope_hash[$extracted[1]]);
                else
                    $require[$value] = str_replace('{group}',"[Unknown]" , $scope_hash[$extracted[1]]);
            }

            $manager = IltUser::find($project->developer_id);

            $data['app_info']       = "{$manager->u_nick} ({$manager->u_username}) [{$manager->u_email}]";
            $data['app_describe']   = $project->describe;
            $data['action']     = route('oauthOwner');
            $data['app_name']   = $project->name;
            $data['require']    = $require;
            $data['expires']    = date('Y-m-d', time() + $expires * 24 * 3600);

            Session::put('iltOAuth', $iltOAuth);
            return View::make('oauth/cofirm_client', $data);
        }
        else {
            // 使用者是否同意授權，若同意及產生Token回傳callback，若反對則傳回原應用程式位置
            if ( 'true' == Input::get('request_answer' )) {
                $origin_token = OAuthAccessToken::where('user_id', '=', $u_id)->where('client_id', '=', $client->getKey())->first();
                if ( $origin_token != null ) {
                    $origin_token->delete();
                }

                $token = $this->generateKey(true);
                $scope = array_merge($scope,$scope_identity);
                $scope = implode(' ', $scope);

                $access_token = new OAuthAccessToken;
                $access_token->access_token = $token;
                $access_token->client_id    = $client->client_id;
                $access_token->user_id      = $u_id;
                $access_token->scope        = $scope;
                $access_token->expires      = date('Y-m-d', time() + $expires * 24 * 3600);
                $access_token->save();

                Session::put('iltOAuth', $iltOAuth);
                Session::put('auth_srv.client_key', $client_key);
                // var_dump("01");
                // die();
                return Redirect::to($callback_uri);
            }
            else {
                // var_dump("02");
                // die();
                return Redirect::to($redirect_uri);
            }
        }

    }

    // Resource Server
    public function resource_server()
    {
        // status: 1 => success
        // status: 2 => token expired
        // status: 3 => arguments losing
        // status: 4 => client identify failed

        $result = array('status' => '', 'data' => '', 'msg' => '');

        // 確認是否有收到Token
        if ((true !== Input::has('token')) ||
            (true !== Input::has('client_key')) ||
            (true !== Input::has('client_secret')) ) {

            $result['status'] = 3;
            $result['msg']    = '您給予的參數有失，請重新發送要求。';
            exit(json_encode($result));
        }

        $token          = Input::get('token');
        $client_key     = Input::get('client_key');
        $client_secret  = Input::get('client_secret');
        $client         = OAuthClient::where('client_key', '=', $client_key)->first();

        // 判定是否為該client
        if ($client->client_secret !== $client_secret) {
            $result['status'] = 4;
            $result['msg']   = 'Client辨識失敗，請重新發送要求。';
            exit(json_encode($result));
        }

        $access_token = OAuthAccessToken::where('access_token', '=', $token)->first();

        // 確認Token是否過期，若過期僅回報狀態碼，不在這裡處理。那是Authorization Server 專司之事。
        $is_token_expired = ( strtotime($access_token->expires) < time() );
        if ($is_token_expired) {
            $result['status'] = 2;
            $result['msg']   = 'Token已經過期，請重新向Authorization Server索取。';
            exit(json_encode($result));
        }

        $scope    = explode(' ', $access_token->scope);
        $user     = IltUser::find($access_token->user_id);
        $user_opt = IltUserOptions::find($access_token->user_id);
        $user_stu = IltUserStudent::where('u_id', '=', $access_token->user_id)->first();
        $data  = array();

        foreach ($scope as $value) {
            switch ($value) {

                # 可以得知是否擁有本系統使用者權限
                case 'user.login.basic':
                    $data['login']['basic'] = true;
                    break;

                # 可以得知是否擁有本系統學生權限
                case 'user.login.student':
                    $data['login']['student'] = (false !== stripos($user->u_authority, 'STUDENT' ));
                    break;

                # 可以得知是否擁有本系統開發者權限
                case 'user.login.developer':
                    $data['login']['developer'] = (false !== stripos($user->u_authority, 'DEVELOPER' ));
                    break;

                # 可以取得使用者基本資料（使用者名稱、信箱）
                case 'user.info.basic':
                    $data['info']['username'] = $user->u_username;
                    $data['info']['email']    = $user->u_email;
                    break;

                # 可以取得使用者網路資料（網站網址、Gravatar頭像位址、自我敘述）
                case 'user.info.internet':
                    $data['info']['username']    = $user_opt->u_website;
                    $data['info']['email']       = $user_opt->u_gravatar;
                    $data['info']['description'] = $user_opt->u_description;
                    break;

                # 可以取得使用者個人資料（姓名、性別、生日、電話、地址）
                case 'user.info.personal':
                    $data['info']['first_name'] = $user_opt->u_first_name;
                    $data['info']['last_name']  = $user_opt->u_last_name;
                    $data['info']['gender']     = $user_opt->u_gender;
                    $data['info']['birthday']   = $user_opt->u_birthday;
                    $data['info']['phone']      = $user_opt->u_phone;
                    $data['info']['address']    = $user_opt->u_address;
                    break;

                # 可以取得使用者學生資料（學校信箱、學號、科系、年級）
                case 'user.info.student':
                    $is_stu = (false !== stripos($user->u_authority, 'STUDENT' ));
                    $depart_hash = Config::get('nchu.departments');
                    $data['student']['email']       = $is_stu ? $user_stu->email : null;
                    $data['student']['number']      = $is_stu ? $user_stu->number : null;
                    $data['student']['department']  = $is_stu ? $depart_hash[$user_stu->department] : null;
                    $data['student']['grade']       = $is_stu ? $user_stu->grade : null;
                    break;

                # 取得學生擁有身分與否
                default:
                    $extracted = $this->extractIdScope($value);
                    // $data = $extracted;

                    if (!$extracted)
                        throw new Exception("Invalid Scope");

                    $group = $extracted[0];
                    $code = $group->g_code;

                    // $data['test'] = $group->g_name;
                    // $data['test1'] = $user->u_username;
                    // $data['test2'] = IltIdentity::isMember($user,$group);
                    // $data['test3'] = $group ? IltIdentity::isMember($user,$group) : false;
                        
                    switch ($extracted[1]) {
                        case 'user.isIn.':
                            $data['isIn'][$code] = $group ? IltIdentity::memberOrHigher($user,$group) : false;
                            break;
                        case 'user.isMemberOf.':
                            $data['isMemberOf'][$code] = $group ? IltIdentity::isMember($user,$group) : false;
                            break;
                        case 'user.isAdminOf.':
                            $data['isAdminOf'][$code] = $group ? IltIdentity::isAdmin($user,$group) : false;
                            break;
                        case 'user.isPendingOf.':
                            $data['isPendingOf'][$code] = $group ? IltIdentity::isPending($user,$group) : false;
                            break;
                        case 'user.inUnder.':
                            if ($group) {
                                $groups = $group->children()->get()->all();

                                $result_groups = array();

                                foreach ($groups as $key => $g) {
                                    if (IltIdentity::memberOrHigher($user,$g)) {
                                          $result_groups[] = $g->g_code;
                                      }
                                }
                                $data['inUnder'][$code] = $result_groups;
                            }
                            else{
                                $data['inUnder'][$code] = false;
                            }
                            break;
                        default:
                            throw new Exception("Invalid Scope");
                            break;
                    }
                    break;
            }
        }

        # TODO: 根據scope給予相對應資料，這邊也需要Model協助處理。

        // 審查全部合格，給予使用者資料
        $result['status'] = 1;
        $result['msg']    = '您已經通過審合，資料已經附上。';
        $result['data']   = $data;

        exit(json_encode($result));
    }

    public function header()
    {

    }

    private function error($e,$more_info = false)
    {
        $error = array();
        switch ($e) {
            case 'argument_losing':
                $error[] = "參數有缺失";
                $error[] = "參數無效";
                break;
            case 'client_no_exist':
                $error[] = "尚未註冊完成";
                $error[] = "辨識代碼錯誤";
                $error[] = "已經被停權";
                break;
            case 'redirect_uri_not_valid':
                $error[] = "錯誤或是無效的 redirect_uri";
                break;
            case 'no_available_scope':
                $error[] = "沒有可用的 scope";
                break;
            default:
                $error[] = "未知的錯誤...";
                break;
        }

        if ($more_info)
            $error[] = $more_info;

        $data = array("error" => $error);
        return View::make('oauth/error', $data);
    }

    public function argument_losing() // 001
    {
        return View::make('oauth/argument_losing');
    }

    public function client_no_exist() // 002
    {
        return View::make('oauth/client_no_exist');
    }

    private function generateKey($unique = false)
    {
        $key = md5(rand());
        if ($unique)
            while (OAuthAccessToken::get($key))
                $key = md5($key + rand());
        return $key;
    }

    private function extractIdScope($scope)
    {
        $oauth_scope_id_prefix = Config::get('sites.oauth_scope_id_prefix');
        foreach ($oauth_scope_id_prefix as $key => $prefix)
            if (strpos($scope,$prefix) === 0)
                return array(IltGroup::get(str_replace($prefix, '', $scope)),$prefix);
        return false;
    }

}
