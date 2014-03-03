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

Route::get('/', function()
{
	return View::make('hello');
});


# Filter���L�͌��^��ֻ���L�Ϳ��Կ���·�ɣ��ѵ����ʹ�����L�����^�tֱ���D��ʹ������档
Route::group(array('before' => 'guest_only'), function()
{

    ## �������
    Route::get('login' ,  function() { return Redirect::route('login');});
    Route::get('portal' , function() { return Redirect::route('login'); });
    Route::get('portal/login', array( 'uses' => 'PortalController@login',
                                      'as'   => 'login'));

    ## Provider OAuth����
    Route::get('portal/o/{provider?}', array( 'uses' => 'PortalController@oauth',
                                              'as'   => 'provider'));


    # Filter��OAuth���^��ֻ���ѽ���Provider�J���ҽ���Seesion��ʹ���߿����L�������
    Route::group(array('before' => 'oauth_only'), function()
    {
        ## �������
        Route::get('portal/login_process', array( 'uses' => 'PortalController@login_process',
                                                  'as'   => 'process'));

        ## �]�����
        Route::get('portal/register', array( 'uses' => 'PortalController@register',
                                             'as'   => 'register'));

        ## �]�Գ���
        Route::post('portal/register', array( 'uses' => 'PortalController@register_process',
                                              'as'   => 'register_process',
                                              'before' => 'csrf'));
    });
});


# Filter��ʹ���ߌ��^��ֻ���L�Ϳ��Կ���·�ɣ��ѵ����ʹ�����L�����^�tֱ���D��ʹ������档
Route::group(array('before' => 'auth_only'), function()
{
    ## ʹ�������
    Route::get('portal/user', array( 'uses' => 'UserController@info', 'as' => 'user', function()
    {
        var_dump(Session::get('user_being'));
    }));

    ## �ǳ�����
    Route::get('portal/logout', array(  'uses' => 'PortalController@logout',
                                        'as'   => 'logout'));


    # Filter�������ߌ��^��ֻ���ѵ���Ĺ����߿��Կ���·�ɣ����N���L�����^ֱ���D��ʹ������棬
    Route::group(array('before' => 'admin_only'), function()
    {
        Route::get('ilt' , function()
        {
            return 'ilt page';
        });
    });

});


