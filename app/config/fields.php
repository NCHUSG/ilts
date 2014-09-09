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
    )
);
