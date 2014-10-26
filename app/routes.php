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
    Route::get('/user/info', array( 'uses' => 'UserController@index', 'as' => 'user'));

    Route::get('/user/identities', array( 'uses' => 'UserController@identities', 'as' => 'identities'));

    Route::post('/update_info/{type}', array( 'uses' => 'UserController@update_info', 'as' => 'update_info', 'before' => 'csrf'));

    Route::get('/email_validation/{code}', array( 'uses'=> 'UserController@email_validation', 'as' => 'emailValidation'));

    ## 群組介面
    Route::group(array('before' => 'group'), function()
    {
        Route::get('/group/info/{code}', array( 'uses' => 'GroupController@index', 'as' => 'group'));

        Route::get('/group/under/{code}', array( 'uses' => 'GroupController@subGroups', 'as' => 'subGroup', 'before' => 'groupUnder'));

        Route::get('/group/member/{code}', array( 'uses' => 'GroupController@member', 'as' => 'member', 'before' => 'groupMember'));

        Route::get('/group/join/{code}/{method}', array( 'uses' => 'GroupController@join', 'as' => 'join'));

        Route::post('/group/join/{code}/{method}', array( 'uses' => 'GroupController@join', 'as' => 'join', 'before' => 'csrf'));

        Route::group(array('before' => 'groupCreate'), function()
        {
            Route::get('/group/create/{code}' , array( 'uses' => 'GroupController@create', 'as' => 'createGroup'));

            Route::post('/group/create/{code}' , array( 'uses' => 'GroupController@create_process', 'as' => 'createGroupProcess', 'before' => 'csrf'));
        });

        Route::group(array('before' => 'groupAdmin'), function()
        {
            Route::post('/group/ctrl/{code}/{type}', array( 'uses' => 'GroupController@ctrl', 'as' => 'groupCtrl'));
        });
    });

    ## 創建根群組
    Route::group(array('before' => 'creatRootGroup'), function()
    {
        Route::get('/group/create' , array( 'uses' => 'GroupController@create', 'as' => 'createRootGroup'));

        Route::post('/group/create' , array( 'uses' => 'GroupController@create_process', 'as' => 'createRootGroupProcess', 'before' => 'csrf'));
    });

    ## 管理者專區。只有已登入的管理者可以看的路由，其餘者訪問本區直接轉入使用者頁面，
    Route::group(array('before' => 'admin_only'), function()
    {
        Route::get('/admin' , array( 'uses' => 'AdminController@index', 'as' => 'admin'));

        Route::post('/admin/options' , array( 'uses' => 'AdminController@option', 'as' => 'siteOption', 'before' => 'csrf'));
    });

    ## 開發者專區。只有已登入的管理者可以看的路由，其餘者訪問本區直接轉入使用者頁面，
    Route::group(array('before' => 'dev_only'), function()
    {
        Route::get('/dev' , array( 'uses' => 'DeveloperController@index', 'as' => 'dev'));

        Route::group(array('prefix' => 'v1/res/'), function()
        {
            Route::resource('projects', 'API_ProjectController', array('as' => 'project'));
            Route::resource('clients', 'API_ClientController', array('as' => 'client'));
        });
    });

    // Route::get('user/apply/developer', array( 'uses' => 'UserController@apply_developer'));
    // Route::post('user/apply/developer', array( 'uses' => 'UserController@apply_developer'));

    // Route::get('developer', array( 'uses' => 'DeveloperController@index'));
    // Route::get('admin', array( 'uses' => 'AdminController@index'));

    
});

## 登出程序
Route::get('/logout', array(  'uses' => 'PortalController@logout',
                                    'as'   => 'logout'));

# Filter：OAUTH專區。
Route::group(array(), function()
{
    Route::group(array('before' => 'auth_only'), function()
    {
        Route::get('oauth/header', array( 'uses' => 'OAuthController@header', 'as' => 'oauthHeader'));
        Route::get('oauth/error001', array( 'uses' => 'OAuthController@argument_losing', 'as' => 'oauthErr001'));
        Route::get('oauth/error002', array( 'uses' => 'OAuthController@client_no_exist', 'as' => 'oauthErr002'));

        Route::get('oauth/auth_server/{client_key?}' ,
            array( 'uses' => 'OAuthController@auth_server', 'as' => 'oauthServer'));

        Route::get('oauth/resource_owner' ,
            array( 'uses' => 'OAuthController@resource_owner', 'as' => 'oauthOwner'));

        Route::post('oauth/resource_owner' ,
            array( 'uses' => 'OAuthController@resource_owner', 'as' => 'oauthOwner'));
    });

    Route::get('oauth/resource_server/', array( 'uses' => 'OAuthController@resource_server', 'as' => 'oauthRes'));
});

# API
// Route::group(array('prefix' => 'v1/res/'), function()
// {
//     Route::resource('projects', 'API_ProjectController', array('as' => 'project'));
//     Route::resource('clients', 'API_ClientController', array('as' => 'client'));
// });

Route::get('test', array('uses' => 'TestController@main'));
