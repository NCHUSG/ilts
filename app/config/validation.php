<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Name Rules
    |--------------------------------------------------------------------------
    |
    | API, CTRL
    |   Controller name without 'Controller' and lower case
    |       method name
    |           rules, messages
    |
    */

    /*
    |--------------------------------------------------------------------------
    | API Controller
    |--------------------------------------------------------------------------
    |
    */
    'API' => array(
        'user' => array(
            'store' => array(
                'rules' => array(
                        'username'    => 'required|alpha_dash',
                        'nickname'    => 'required',
                        'email'       => 'required|email',
                        'first_name'  => '',
                        'last_name'   => '',
                        'gender'      => '',
                        'birthday'    => 'date|date_format:Y/m/d',
                        'phone'       => 'numeric',
                        'address'     => '',
                        'website'     => 'url',
                        'gravater'    => 'email',
                        'description' => ''
                ),
                'messages' => array(
                    'required'      => '本欄位選項是必填的！',
                    'alpha_dash'    => '本欄位選項必需為大小寫英文字母（A-Z, a-z）、底線（_）、減號（-）組成。',
                    'email'         => '本欄位選項請符合email格式（Ex. foo@bar.com）。',
                    'url'           => '本欄位選項請符合網址(url)格式（Ex. http://www.foo.com ）。',
                    'numeric'       => '本欄位選項請符合純數字格式。',
                    'date'          => '本欄位選項請輸入有效的日期範圍。',
                    'date_format'   => '本欄位選項請符合純日期（yyyy/mm/dd）格式。'
                ),
            ),
        ),
        'project' => array(
            'store' => array(
                'rules' => array(
                    'name'          => 'required|unique:oauth_projects,name',
                    'describe'      => 'required',
                    'email'         => 'required',
                ),
                'messages' => array(
                    'name.required'    => '「專案名稱」是必填欄位！',
                    'name.unique'      => '「專案名稱」已經被申請過了！',
                    'describe.required'=> '「專案敘述」是必填欄位！',
                    'email.unique'     => '「電子郵件」是必填欄位！',
                ),
            ),
            'update' => array(
                'rules' => array(
                    'name'          => "required|unique:oauth_projects,name,project_id",
                    'describe'      => 'required',
                    'email'         => 'required',
                ),
                'messages' => array(
                    'name.required'    => '「專案名稱」是必填欄位！',
                    'name.unique'      => '「專案名稱」已經被申請過了！',
                    'describe.required'=> '「專案敘述」是必填欄位！',
                    'email.unique'     => '「電子郵件」是必填欄位！',
                ),
            ),
        ),
        'client' => array(
            'store' => array(
                'rules' => array(
                    'project_id'        => 'required',
                    'input_from_uri'    => 'required',
                    'input_redirect_uri'=> 'required'
                ),
                'messages' => array(
                    'project_id'                    => '專案代碼有誤！',
                    'input_from_uri.required'       => '「應用程式來源白名單」是必填欄位！',
                    'input_redirect_uri.required'   => '「應用程式轉向白名單」是必填欄位！',
                ),
            ),
            'update' => array(
                'rules' => array(
                    'from_uri'    => 'required',
                    'redirect_uri'=> 'required',
                ),
                'messages' => array(
                    'from_uri.required'       => '「應用程式來源白名單」是必填欄位！',
                    'redirect_uri.required'   => '「應用程式轉向白名單」是必填欄位！',
                ),
            ),
        ),
    ),

    /*
    |--------------------------------------------------------------------------
    | Basic Controller
    |--------------------------------------------------------------------------
    |
    */
    'CTRL' => array(
        'portal' => array(
            'register_process' => array(
                'rules' => array(
                    'username'    => 'required|alpha_dash',
                    'nickname'    => 'required',
                    'email'       => 'required|email',
                    'first_name'  => '',
                    'last_name'   => '',
                    'gender'      => '',
                    'birthday'    => 'date|date_format:Y/m/d',
                    'phone'       => 'numeric',
                    'address'     => '',
                    'website'     => 'url',
                    'gravater'    => 'email',
                    'description' => ''
                ),
                'messages' => array(
                    'required'      => '本欄位選項是必填的！',
                    'alpha_dash'    => '本欄位選項必需為大小寫英文字母（A-Z, a-z）、底線（_）、減號（-）組成。',
                    'email'         => '本欄位選項請符合email格式（Ex. foo@bar.com）。',
                    'url'           => '本欄位選項請符合網址(url)格式（Ex. http://www.foo.com ）。',
                    'numeric'       => '本欄位選項請符合純數字格式。',
                    'date'          => '本欄位選項請輸入有效的日期範圍。',
                    'date_format'   => '本欄位選項請符合純日期（yyyy/mm/dd）格式。'
                )
            )
        ),
        'user' => array(
            'apply_developer' => array(
                'rules' => array(
                    'agree' => 'accepted',
                ),
                'messages' => array(
                    'accepted'  => '您必須同意條款才能成為開發者！',
                )
            )
        ),
        'student' => array(
            'apply_email_process' => array(
                'rules' => array(
                    'email' => array('required', 'email',
                                     'regex:/^[a-zA-Z0-9._-]+@mail\.[a-zA-Z0-9.-]+\.edu\.tw$/',
                                     'unique:ilt_user_students,email'),
                ),
                'messages' => array(
                    'email.required'        => '學校信箱欄位必須填寫！',
                    'email.email'           => '學校信箱欄位必須是正常的email格式',
                    'email.regex'           => '學校信箱欄位必須以@mail.xxx.edu.tw為位址。',
                    'email.unique'          => '本學校信箱已經認證過了！',
                ),
            ),
            'apply_files_process' => array(
                'rules' => array(
                    'number'        => 'required|numeric|min:1000000000|max:9999999999',
                    'department'    => array('required', 'regex:/^[A-Z][0-9]{2}[A-C]?$/'),
                    'grade'         => 'required|numeric|between:0,6',
                ),
                'messages' => array(
                    'number.required'       => '學號欄位必須填寫！',
                    'number.numeric'        => '學號欄位只能填寫數字！',
                    'number.min'            => '學號欄位必須填寫十碼！',
                    'number.max'            => '學號欄位必須填寫十碼！',
                    'department.required'   => '科系欄位必須填寫！',
                    'department.regex'      => '科系欄位代號有誤！',
                    'grade.required'        => '年級欄位必須填寫！',
                    'grade.numeric'         => '年級欄位只能填寫數字！',
                    'grade.between'         => '年級欄位的範圍有誤！',
                ),
            )
        ),
    )
);
