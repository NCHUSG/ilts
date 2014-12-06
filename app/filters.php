<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::route('login');
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});

/*
|--------------------------------------------------------------------------
| Custom
|--------------------------------------------------------------------------
|
|
*/

Route::filter('guest_only', function()
{
    // 如果已經登入，會被轉移到使用者主頁
    $is_login = (Session::has('user_being') || Session::get('user_being.status') == true);
    if ( $is_login ) {
        return Redirect::route('user');
    }
});

Route::filter('oauth_only', function()
{
    // 如果沒有provider的資料，會被轉移到登入頁面

    $is_oauth = (Session::has('oauth'));
    if ( !$is_oauth ) {
        return Redirect::route('login');
    }
});

Route::filter('auth_only', function()
{
    // 如果還沒登入，會轉移到登入頁面。
    try {
        if (!IltUser::get()) {
            $current_uri = urlencode(Request::fullUrl());
            return Redirect::to( action('PortalController@login') . '?callback=' . $current_uri);
        }
    } catch (Exception $e) {
        return Redirect::route('logout');
    }
});

Route::filter('group', function($route, $request)
{
    try {
        $user = IltUser::get(true);
        $group = IltGroup::get($route->parameter('code'),true);

        if ($group->g_level_sort) {
            if (!IltIdentity::memberOrHigher($user,$group->getParent()))
                throw new Exception("您尚未加入上層組織...");
        }

        if (!$group->getBoolOption('public')) {
            if (!IltIdentity::memberOrHigher($user,$group))
                throw new Exception("你尚未加入此組織！");
        }
    } catch (Exception $e) {
        Session::put('message',array('status' => 'danger', 'content' => $e->getMessage()));
        return Redirect::route('user');
    }
});

Route::filter('groupUnder', function($route, $request)
{
    try {
        $user = IltUser::get(true);
        $group = IltGroup::get($route->parameter('code'),true);
        $id = IltIdentity::get($user,$group);

        if ($id) {
            if (!$group->getBoolOption('allow_guest_see_child_group') && $id->is_pending())
                throw new Exception("不允許訪客取得子群組！");
            if (!$group->getBoolOption('allow_members_see_child_group') && $id->is_member())
                throw new Exception("不允許成員取得子群組！");
        }
        else if (!$group->getBoolOption('allow_guest_see_child_group'))
            throw new Exception("不允許訪客取得子群組！");
    } catch (Exception $e) {
        Session::put('message',array('status' => 'danger', 'content' => $e->getMessage()));
        return Redirect::route('user');
    }
});

Route::filter('groupMember', function($route, $request)
{
    try {
        $user = IltUser::get(true);
        $group = IltGroup::get($route->parameter('code'),true);
        $id = IltIdentity::get($user,$group);

        if ($id) {
            if (!$group->getBoolOption('allow_guest_see_members') && $id->is_pending())
                throw new Exception("不允許訪客取得成員！");
            if (!$group->getBoolOption('allow_members_see_members') && $id->is_member())
                throw new Exception("不允許成員取得成員！");
        }
        else if (!$group->getBoolOption('allow_guest_see_members'))
            throw new Exception("不允許訪客取得成員！");
    } catch (Exception $e) {
        Session::put('message',array('status' => 'danger', 'content' => $e->getMessage()));
        return Redirect::route('user');
    }
});

Route::filter('groupCreate', function($route, $request)
{
    try {
        $user = IltUser::get(true);
        $group = IltGroup::get($route->parameter('code'),true);
        $id = IltIdentity::get($user,$group);

        if ($id) {
            if ($group->getBoolOption('allow_member_create_child_group')) {
                if (!$id->member_or_higher())
                    throw new Exception("你沒有使用本功能的權限！");
            }
            else{
                if (!$id->admin_or_higher())
                    throw new Exception("你沒有使用本功能的權限！");
            }
        }
        else
            throw new Exception("你沒有使用本功能的權限！");
    } catch (Exception $e) {
        Session::put('message',array('status' => 'danger', 'content' => $e->getMessage()));
        return Redirect::route('user');
    }
});

Route::filter('groupAdmin', function($route, $request)
{
    try {
        $user = IltUser::get(true);
        $group = IltGroup::get($route->parameter('code'),true);
        $id = IltIdentity::get($user,$group,true);

        if (!IltIdentity::isAdmin($user,$group))
            throw new Exception("你沒有使用本功能的權限！");
    } catch (Exception $e) {
        Session::put('message',array('status' => 'danger', 'content' => $e->getMessage()));
        return Redirect::route('group',$route->parameter('code'));
    }
});

Route::filter('creatRootGroup', function()
{
    try {
        if (!(IltUser::get()->isAdmin() || filter_var(IltSiteOptions::get('allow_create_root_group'), FILTER_VALIDATE_BOOLEAN))) {
            Session::put('message',array('status' => 'danger', 'content' => '你沒有使用本功能的權限！'));
            return Redirect::route('user');
        }
    } catch (Exception $e) {
        return Redirect::route('logout');
    }
});

Route::filter('admin_only', function()
{
    try {
        if (!IltUser::get()->isAdmin()) {
            Session::put('message',array('status' => 'danger', 'content' => '你沒有使用本功能的權限！'));
            return Redirect::route('user');
        }
    } catch (Exception $e) {
        return Redirect::route('logout');
    }
});

Route::filter('dev_only', function()
{
    try {
        if (!IltUser::get()->isDev()) {
            Session::put('message',array('status' => 'danger', 'content' => '你沒有使用本功能的權限！'));
            return Redirect::route('user');
        }
    } catch (Exception $e) {
        return Redirect::route('logout');
    }
});

Route::filter('apply_student', function()
{
    $user    = IltUser::find(Session::get('user_being.u_id'));
    $student = IltUserStudent::where('u_id', '=', $user->u_id)->first();

    if ( false !== stripos($user->u_authority, 'STUDENT' )) {
        return Redirect::action('UserController@index');
    }
    elseif ( null !== $student && null !== $student->number ) {
        return View::make('student/apply_form_already');
    }
});

Route::filter('apply_student_files', function()
{
    $student_orm = IltUserStudent::where('u_id', '=', Session::get('user_being.u_id'))->first();

    if ( $student_orm === null ) {
        return Redirect::action('StudentController@apply_email');
    }
});


Route::filter('reload_authority', function()
{
    $user    = IltUser::find(Session::get('user_being.u_id'));
    $session['authority'] = explode(',', $user->u_authority);

    if ( !is_array($session['authority']) ) {
        $session['authority'] = array($session['authority']);
    }

    Session::put('user_being.authority', $session['authority']);
});



