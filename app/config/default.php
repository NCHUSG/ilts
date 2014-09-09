<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Admin Group Default Value
    |--------------------------------------------------------------------------
    |
    */

    'admin_group' => array(
        'code' => 'admin',
        'name' => '管理員',
    ),


    /*
    |--------------------------------------------------------------------------
    | Dev Group Default Value
    |--------------------------------------------------------------------------
    |
    */

    'dev_group' => array(
        'code' => 'dev',
        'name' => '開發者',
    ),

    /*
    |--------------------------------------------------------------------------
    | Group Default Value
    |--------------------------------------------------------------------------
    |
    */

    'group' => array(
        'name' => '',
        'code' => '',
    ),

    /*
    |--------------------------------------------------------------------------
    | Group Default Value
    |--------------------------------------------------------------------------
    |
    */
   
    'group_public_options' => array(
        'description' => '',
        'email' => '',
    ),

    'group_options' => array(),

    'group_bool_options' => array(
        // 'description' => '',
        // 'email' => '',

        'allow_member_create_child_group' => 'false',

        'public' => 'false',
        'allow_guest_see_child_group' => 'false',
        'allow_guest_see_members' => 'false',
        'allow_members_see_child_group' => 'true',
        'allow_members_see_members' => 'true',

        'joinByAllow' => 'true',
        'directJoinable_by_StudentEmailValidation' => 'true',
        'directJoinable_by_EmailValidation' => 'true',
        'directJoinable' => 'false',
    ),

    /*
    |--------------------------------------------------------------------------
    | Option Default Value
    |--------------------------------------------------------------------------
    |
    */
    
    'options' => array(
        'allow_create_root_group' => 'false',
    ),

);
