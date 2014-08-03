<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

# Filter：訪客專區。只有訪客可以看的路由，已登入的使用者訪問本區則直接轉到使用者頁面。
Route::group(array('before' => 'guest_only'), function()
{

    ## 登入頁面
    Route::get('/', function()  { return Redirect::route('login'); });
    Route::get('login' ,  function() { return Redirect::route('login'); });
    Route::get('portal' , function() { return Redirect::route('login'); });
    Route::get('portal/login', array( 'uses' => 'PortalController@login',
                                      'as'   => 'login'));

    ## Provider OAuth程序
    Route::get('portal/o/{provider?}', array( 'uses' => 'PortalController@oauth',
                                              'as'   => 'provider'));

    # Filter：OAuth專區。只有已經被Provider認可且建立Seesion的使用者可以訪問的頁面
    Route::group(array('before' => 'oauth_only'), function()
    {
        ## 登入程序
        Route::get('portal/login_process', array( 'uses' => 'PortalController@login_process',
                                                  'as'   => 'process'));

        ## 註冊頁面
        Route::get('portal/register', array( 'uses' => 'PortalController@register',
                                             'as'   => 'register'));

        ## 註冊程序
        Route::post('portal/register', array( 'uses' => 'PortalController@register_process',
                                              'as'   => 'register_process',
                                              'before' => 'csrf'));
    });
});


# Filter：使用者專區。只有訪客可以看的路由，已登入的使用者訪問本區則直接轉到使用者頁面。
Route::group(array('before' => 'auth_only'), function()
{
    ## 使用者頁面
    Route::get('user/info', array( 'uses' => 'UserController@index', 'as' => 'user'));

    Route::get('email_vallidation/{type}/{code}', array( 'uses'=> 'UserController@email_vallidate'));

    Route::post('update_info/{type}', array( 'uses' => 'UserController@update_info', 'as' => 'update_info'));

    Route::group(array('before' => 'apply_student'), function()
    {
        Route::get('user/apply/student/email', array( 'uses' => 'StudentController@apply_email'));
        Route::post('user/apply/student/email', array( 'uses' => 'StudentController@apply_email_process'));

        Route::group(array('before' => 'apply_student_files'), function()
        {
            Route::get('user/apply/student/files', array( 'uses' => 'StudentController@apply_files'));
            Route::post('user/apply/student/files', array( 'uses' => 'StudentController@apply_files_process', 'after' => 'reload_authority'));
        });
    });

    Route::get('user/apply/developer', array( 'uses' => 'UserController@apply_developer'));
    Route::post('user/apply/developer', array( 'uses' => 'UserController@apply_developer'));

    Route::get('developer', array( 'uses' => 'DeveloperController@index'));
    Route::get('admin', array( 'uses' => 'AdminController@index'));

    ## 登出程序
    Route::get('portal/logout', array(  'uses' => 'PortalController@logout',
                                        'as'   => 'logout'));
});

# Filter：管理者專區。只有已登入的管理者可以看的路由，其餘者訪問本區直接轉入使用者頁面，
Route::group(array('before' => 'auth_only|admin_only'), function()
{
    Route::get('ilt' , function()
    {
        return 'ilt page';
    });
});

# Filter：OAUTH專區。
Route::group(array(), function()
{
    Route::group(array('before' => 'auth_only'), function()
    {
        Route::get('oauth/header', array( 'uses' => 'OAuthController@header'));
        Route::get('oauth/error001', array( 'uses' => 'OAuthController@argument_losing'));
        Route::get('oauth/error002', array( 'uses' => 'OAuthController@client_no_exist'));

        Route::get('oauth/auth_server/{client_key?}' ,
            array( 'uses' => 'OAuthController@auth_server'));

        Route::get('oauth/resource_owner' ,
            array( 'uses' => 'OAuthController@resource_owner'));

        Route::post('oauth/resource_owner' ,
            array( 'uses' => 'OAuthController@resource_owner'));
    });

    Route::get('oauth/resource_server/', array( 'uses' => 'OAuthController@resource_server'));
});

# API
Route::group(array('prefix' => 'v1/res/'), function()
{
    Route::resource('projects', 'API_ProjectController', array('as' => 'project'));
    Route::resource('clients', 'API_ClientController', array('as' => 'client'));
});


