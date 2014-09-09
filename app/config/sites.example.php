<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Sites Setting
    |--------------------------------------------------------------------------
    |
    */

    'name' => '學生自治組織',


    /*
    |--------------------------------------------------------------------------
    | Administrator Setting
    |--------------------------------------------------------------------------
    |
    */

    'maintainer' => '',


    /*
    |--------------------------------------------------------------------------
    | System Setting
    |--------------------------------------------------------------------------
    |
    */

    'authority' => array(
        'EMAIL',
        'DEVELOPER',
        'STUDENT'
    ),

    'oauth_scope' => array(
        'user.login.basic'     => '可以得知是否擁有本系統使用者權限',
        'user.login.student'   => '可以得知是否擁有本系統學生權限',
        'user.login.developer' => '可以得知是否擁有本系統開發者權限',
        'user.info.basic'    => '可以取得使用者基本資料（使用者名稱、信箱）',
        'user.info.internet' => '可以取得使用者網路資料（網站網址、Gravatar頭像位址、自我敘述）',
        'user.info.personal' => '可以取得使用者個人資料（姓名、性別、生日、電話、地址）',
        'user.info.student'  => '可以取得使用者學生資料（學校信箱、學號、科系、年級）'
    ),

    'g_status_admin_value' => 'admin',
    'g_status_dev_value' => 'dev',
    'g_status_public_value' => 'public',

    'i_authority_admin_value' => '11',
    'i_authority_member_value' => '5',
    'i_authority_pending_value' => '3',

    'i_authority_value_to_readable' => array(
        '3' => 'pending',
        '5' => 'member',
        '11' => 'admin',
    ),

);
