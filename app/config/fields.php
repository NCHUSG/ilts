<?php

return array(

    'username' => array(
        'db' => 'u_username',
        'en' => 'User Name',
        'zh_TW' => '使用者名稱',
    ),
    'nickname' => array(
        'db' => 'u_nick',
        'en' => 'Nickname',
        'zh_TW' => '暱稱',
    ),
    'email' => array(
        'db' => 'u_email',
        'en' => 'Email',
        'zh_TW' => 'Email',
    ),
    'authority' => array(
        'db' => 'u_authority',
        'en' => 'Authority',
        'zh_TW' => '權限',
    ),
    'last_name' => array(
        'db' => 'u_last_name',
        'en' => 'Last Name',
        'zh_TW' => '姓',
    ),
    'first_name' => array(
        'db' => 'u_first_name',
        'en' => 'First Name',
        'zh_TW' => '名字',
    ),
    'gender' => array(
        'db' => 'u_gender',
        'en' => 'gender',
        'zh_TW' => '性別',
    ),
    'birthday' => array(
        'db' => 'u_birthday',
        'en' => 'Birthday',
        'zh_TW' => '生日',
    ),
    'phone' => array(
        'db' => 'phone',
        'en' => 'Phone',
        'zh_TW' => '電話',
    ),
    'address' => array(
        'db' => 'u_address',
        'en' => 'Address',
        'zh_TW' => '地址',
    ),
    'website' => array(
        'db' => 'u_website',
        'en' => 'Website',
        'zh_TW' => '個人網站',
    ),
    'gravatar' => array(
        'db' => 'u_gravatar',
        'en' => 'Gravatar',
        'zh_TW' => '大頭貼',
    ),
    'description' => array(
        'db' => 'u_description',
        'en' => 'Description',
        'zh_TW' => '敘述',
    ),

    'allow_create_root_group' => array(
        'en' => 'Allow create root group',
        'zh_TW' => '允許創造根組織',
    ),

    'group' => array(
        'name' => array(
            'en' => 'Group name',
            'zh_TW' => '組織/社團名稱',
        ),
        'code' => array(
            'en' => 'Group short name',
            'zh_TW' => '簡稱',
        ),
        'description' => array(
            'en' => 'Description',
            'zh_TW' => '敘述',
        ),
        'email' => array(
            'en' => 'Email',
            'zh_TW' => 'Email',
        ),
        'allow_member_create_child_group' => array(
            'en' => 'Allow member to create child groups',
            'zh_TW' => '允許成員建立子群組',
        ),
        'public' => array(
            'en' => 'Public',
            'zh_TW' => '是否公開',
        ),
        'joinByAllow' => array(
            'en' => 'Joinable by Allow from Admin',
            'zh_TW' => '透過允許的方式加入',
        ),
        'directJoinable_by_StudentEmailValidation' => array(
            'en' => 'Direct Joinable by Student Email Validation',
            'zh_TW' => '使用學生信箱認證之後直接加入',
        ),
        'directJoinable_by_EmailValidation' => array(
            'en' => 'Direct Joinable by Email Validation',
            'zh_TW' => '使用信箱認證之後直接加入',
        ),
        'directJoinable' => array(
            'en' => 'Direct Joinable',
            'zh_TW' => '可直接加入',
        ),

        'allow_guest_see_child_group' => array(
            'en' => 'Allow guest see child groups',
            'zh_TW' => '允許訪客看見子群組',
        ),
        'allow_guest_see_members' => array(
            'en' => 'Allow guest see members',
            'zh_TW' => '允許訪客看見成員',
        ),
        'allow_members_see_child_group' => array(
            'en' => 'Allow members see child groups',
            'zh_TW' => '允許成員看見子群組',
        ),
        'allow_members_see_members' => array(
            'en' => 'Allow members see members',
            'zh_TW' => '允許成員看見其他成員',
        ),
    ),

    'join_method' => array(
        'joinByAllow' => array(
            'en' => 'Joinable by Allow from Admin',
            'zh_TW' => '透過管理員核准',
        ),
        'directJoinable_by_StudentEmailValidation' => array(
            'en' => 'Direct Joinable by Student Email Validation',
            'zh_TW' => '使用學生信箱認證',
        ),
        'directJoinable_by_EmailValidation' => array(
            'en' => 'Direct Joinable by Email Validation',
            'zh_TW' => '使用一般信箱認證',
        ),
        'directJoinable' => array(
            'en' => 'Direct Joinable',
            'zh_TW' => '直接加入',
        ),
    ),

    'identity_status' => array(
        'unreachable' => '尚未加入母組織',
        'guest' => '尚未加入',
        'pending' => '等待加入確認',
        'member' => '群組中的一員',
        'admin' => '具有管理權限',
    ),

    'email_validation_title' => array(
        'en' => 'Identity Confirm',
        'zh_TW' => '身份確認信',
    ),

    'email_validation_error_msg' => '錯誤的信箱驗證！',
    'email_validation_ok_msg' => '信箱認證完成！',
    'email_invitaion_ok_msg' => '邀請認證完成！',

    'oauth_scope' => array(
        'user.login.basic'     => '可以得知是否擁有本系統使用者權限',
        'user.login.student'   => '可以得知是否擁有本系統學生權限',
        'user.login.developer' => '可以得知是否擁有本系統開發者權限',
        'user.info.basic'    => '可以取得使用者基本資料（使用者名稱、信箱）',
        'user.info.internet' => '可以取得使用者網路資料（網站網址、Gravatar頭像位址、自我敘述）',
        'user.info.personal' => '可以取得使用者個人資料（姓名、性別、生日、電話、地址）',
        'user.info.student'  => '可以取得使用者學生資料（學校信箱、學號、科系、年級）',
    ),

    'oauth_scope_id' => array(
        'user.isIn.'  => '可以取得使用者是否為 {group} 的成員',
        'user.isMemberOf.'  => '可以取得使用者是否為 {group} 的一般成員',
        'user.isAdminOf.'  => '可以取得使用者是否為 {group} 的管理員',
        'user.isPendingOf.'  => '可以取得使用者是否為 {group} 的待核准成員',
    ),

    'email_form_message' => array(
        'directJoinable_by_StudentEmailValidation' => '請輸入學生信箱',
        'directJoinable_by_EmailValidation' => '請輸入電子信箱',
    )
);
