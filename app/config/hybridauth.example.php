<?php
return array(

    //"base_url"   => "http://localhost/ilts_laravel/public/index.php/portal/o/auth",
    //在原本的 hybridauth 是需要設定 base_url 的，但是本項目會用 laravel 函式自動產生

    "providers"  => array (

        /**
         * Google 登入
         * 前往此處新增 Project:
         * https://console.developers.google.com/project
         * 新增 Project 完成之後，從 左方選單 > APIS & AUTH > Credentials 進入進行 API Key 取得
         * 點選 "Create new Client ID" 申請網站 API key，
         *     "Application type" 選擇 "Web application"
         *     "Authorized JavaScript origins" 輸入 "網站所在的 domain"
         *     "Authorized redirect URI" 輸入: (可透過換行允許多個 redirect URI)
         *       http://...網站所在.../portal/o/auth?hauth.done=Google
         *       https://...網站所在.../portal/o/auth?hauth.done=Google
         *       如果 Nginx 設定之根目錄不為 public 資料夾，則可能需要加入以下 URL
         *       http://...網站所在.../public/index.php/portal/o/auth?hauth.done=Google
         *       https://...網站所在.../public/index.php/portal/o/auth?hauth.done=Google
         *       http://...網站所在.../public/portal/o/auth?hauth.done=Google
         *       https://...網站所在.../public/portal/o/auth?hauth.done=Google
         * 申請完畢之後，右方會多出一個 Client，內附 Client ID 以及 Client secret 等資訊
         *     將 Client ID 取代下方的 ID
         *     將 Client secret 取代下方的 SECRET
         * 接著要去 左方選單 > APIS & AUTH > APIs 將 API 啟用
         *     需要啟用 Contacts API 以及 Google+ API
         * 完成以上設定之後，將下方 enabled 設定成 true 即會在登入頁面顯示登入按鈕
         */
        "Google"     => array (
            "enabled"    => false,
            "keys"       => array ( "id" => "ID", "secret" => "SECRET" ),
            ),

        /**
         * Facebook 登入
         * Facebook API 尚未研究QQ
         */
        "Facebook"   => array (
            "enabled"    => false,
            "keys"       => array ( "id" => "ID", "secret" => "SECRET" ),
            ),

        /**
         * 目前內部程式碼沒有進行實作，所以無法使用
         */
        // "Twitter"    => array (
        //     "enabled"    => false,
        //     "keys"       => array ( "key" => "ID", "secret" => "SECRET" )
        //     )
    ),
);
