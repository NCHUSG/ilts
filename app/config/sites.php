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
        'user.login.basic',
        'user.login.student',
        'user.login.developer',
        'user.info.basic',
        'user.info.internet',
        'user.info.personal',
        'user.info.student',
    ),

    'oauth_scope_id_prefix' => array(
        'user.isIn.',
        'user.isMemberOf.',
        'user.isAdminOf.',
        'user.isPendingOf.',
        'user.inUnder.',
    ),

    'email_StudentEmailValidation_type_value' => 'directJoinable_by_StudentEmailValidation',
    'email_directJoinable_by_EmailValidation_type_value' => 'directJoinable_by_EmailValidation',
    'email_invitaion_type_prefix' => 'invitation:',

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

    'number_of_user_per_page' => 20,

    'user_shown_column' => array("u_username","u_nick","u_email","i_authority"),

);
