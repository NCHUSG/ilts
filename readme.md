伊爾特系統
---
## CH1: 關於

### 簡述

伊爾特系統（Ilt System），是透過使用者清單（List of User）、群組關係樹（Tree of Group Relationship）、辨識標籤（Identity Tag）組成的會員管理系統，取這三元素的頭字母為I-L-T，所以稱為伊爾特（Ilt）。

### 特色

- 整合OAuth登入與Provider的會員系統。
- 將使用者、群組、權限整合在一起的會員系統。
- 低耦合性，以伊爾特為中心，用OAuth Provider整合各項程式。

## CH2: 安裝

### 步驟

- 執行終端指令 `git clone <本專案git位址>` 本專案到指定資料夾
- 將網路伺服器設定根目錄到本專案的public資料夾，或是指定到本專案根目錄的server資料夾。_（註1）_
- 執行終端指令 `composer update` ，更新vendor，詳細請參照 [Laravel Framework][1] 的說明文件。
- 複製設定示範檔案至正式設定檔：

  ```
  cp app/config/app.example.php app/config/app.php
  cp app/config/database.example.php app/config/database.php
  cp app/config/hybridauth.example.php app/config/hybridauth.php
  cp app/config/sites.example.php app/config/sites.php
  ```

- 編輯 `app/config/app.php` ，將 `url` 參數修改為本專案的根目錄，並且更新 `key` 參數的值，詳細請參照 [Laravel Framework][1] 的說明文件。
- 編輯 `app/config/database.php` ，修改資料庫連線參數，詳細請參照 [Laravel Framework][1] 的說明文件。
- 編輯 `app/config/hybridauth.php` ，修改 `base_url` 參數，以及providers的 `id` 與 `secret`，詳細說明請參照下方的 Portal。
- 編輯 `app/config/sites.php` ，修改網站的相關參數，詳細請參照 [Laravel Framework][1] 的說明文件。
- 編輯 `app/config/mail.php` ，修改 Email Sever 的相關參數，詳細請下方的 Mail。
- 執行終端指令 `php artisan migrate` ，建立資料表。
- 連線到網站，測試是否正常：
    - 是否能正常看到頁面。
    - 各項OAuth登入（Ex. Google, Facebook）是否都正常。
    - 到處丟測資，看會不會出現系統錯誤訊息，若有，請檢查是否為伺服器環境的問題。若認為是程式問題，請到本專案的頁面提報Issue。
- 清除各項測資。
- 編輯 `app/config/app.php` ，將 `debug` 參數改為 `false` 。
- 開始運作本網站囉！

### Portal

本系統不儲存密碼，只使用 Google 或者 Facebook 進行登入，但此方式需要在 Google 或是 Facebook 上面設定好 API Key 相關訊息，這些訊息都在 `app/config/hybridauth.php` 內，其檔案本身附有詳細說明，請前往查看並設定。

### Mail

使用 laravel 內建之寄信模組，詳細請參照 [Laravel Framework][1] 的說明文件。  
但若要使用 Google 帳號寄信，請參照下方設定

 * `app/config/mail.php` 內之設定如下
   - `driver` => `smtp`
   - `host` => `smtp.gmail.com`
   - `port` => 587
   - `from` => 設定成寄出信件的身分
   - `encryption` => `tls`
   - `username` => 包含 `@gmail.com` 之 Google 帳號
   - `password` => Google 帳號之密碼！
 * 請至對應 Google 帳號的[安全性設定 > 安全性較低的應用程式](https://www.google.com/settings/security/lesssecureapps) 內 `啟用 安全性較低的應用程式存取權限` ，信件才能正常寄出。

## CH3: 架構

### OAuth Login

本系統不儲存密碼，只使用 Google 或者 Facebook 進行登入，但此方式需要在 Google 或是 Facebook 上面設定好 API Key 相關訊息，這些訊息都在 `app/config/hybridauth.php` 內，其檔案本身附有詳細說明，請前往查看並設定。  

在前端部分，本專案是使用基於 [Bootstrap][5] 和 [Font Awesome][6] 的 [Social Buttons for Bootstrap][7] ，作為前端社交按鈕的框架。若要新增首頁OAuth登入的按鈕，請參照該框架的說明文件，編輯 `app/views/portal/login.blade.php` 檔案，新增相對應的按鈕即可。

### OAuth Provider

#### 簡介

本專案的OAuth Provider是Fntsrlike參照網路上的說明自行刻造的小型OAuth Provider架構，相對於完整的規範可能會有許多缺失，會在日後其他部分皆完工後，在強化他的安全性與流程。若是有任何想法也歡迎到本專業網站開Issue或是進行Pull Request，感謝大家。

有關於這部分的程式碼，請檢閱 `app/routes.php` 與 `app/controllers/OAuthController.php` 這兩個檔案，與其相對應的Models分別是 `app/models/OAuthAccessToken.php` 與 `app/models/OAuthClient.php`，資料表結構請參考本架構的Schema `app/database/migrations/` 與 `app/models/OAuthAccessToken.php` 。

#### 流程

1. **Client**向本架構的**Authorization Server**程序提出 `GET Request`，並附上下列資料，其中任一資料若是有誤皆會報錯，並引導使用者到錯誤說明頁面：
    - **`client_key`** 做**Client**辨識。
    - **`scope`** 作為權限要求的範圍。（註1）
    - **`redirect_uri`** ，請求本程式在完成認證程序後，轉移到該URI。（註2）
2. 檢驗 `User` 是否已經登入本專案網站，若沒登入，導向登入頁面引導登入，並在登入後導向回本認證程序。
3. 檢驗 `User` 是否曾認可該 **Client** 的權限需求（包括Scope是否相同），若否則導向認可頁面，詢問使用者是否同意，同意則進入下一階段，若不同意則導向錯誤說明頁面。
4. 若是上述驗證皆正確，則會將 `token` 透過 `GET Request` 發送到 **Client** 指定轉移的URI，使 **Client** 獲取該 `token` 。（註3、註4、註5）
6. **Client** 在得到 `token` 後，即會像本架構的**Resource Server**程序，附上下列資料，透過後端（PHP）的方式，利用 `GET Request` 方法索取資源。
    - **`token`**，即前部分所獲得的token，作為索取資源與範圍的辨識。
    - **`client_key`**，作為 **Client** 的辨識，若是與 `token`當初指定的 **Client** 不同，則會報錯，回饋相關錯誤訊息給 **Client** 。
    - **`client_secret`**，辨識 **Client** 的合法性，此 secret key 絕對不能外留給該 **Client** 管理員與開發者以外的人員，否則會造成資安漏洞。
7. 本程序通過上述資料的檢驗後，將當初 `scope` 指定的資源給予 **Client**，完成程序。

##### 備註

1. Scope的使用說明請參照Scope List。
2. 若是不符合當初Crentential申請所填的URI，則會報錯，引導使用者到說明頁面，表示此URI並不合法。
3. 不清楚透過此方式是否安全，因為可能會導致token被劫取，相關資安問題有待研究。
4. **Client** 獲取 `token` 後，會存在該 **Client**（或是用Session、或是用Cookies 等相關儲存方式，端看 **Client** 的程式設計。
5. **Authorization Server**程序在產 `token` 時，會將以下資料記載資料庫中，以作為**Resource Server**程序的安全檢驗：
    - **Client ID**，使 `token` 對 **Client** 俱有專一性。
    - **User ID**，使 `token` 對 **User** 俱有專一性。
    - **Expires**，本 `token` 的有效期限。若是過期， **Client** 需要另外要求新的 `token` 。
    - **Scope**，作為 **Client** 能索取資源的權限範圍。若是 **Client** 改變 `Scope` ，則會另外產生新的 `token` ，並使原有的token過期、無效。

##### Scopes

```
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
    'user.isIn.'        => '可以取得使用者是否為 {group} 的成員(一般成員或是管理員)',
    'user.isMemberOf.'  => '可以取得使用者是否為 {group} 的成員(一般成員)',
    'user.isAdminOf.'   => '可以取得使用者是否為 {group} 的管理員',
    'user.isPendingOf.' => '可以取得使用者是否為 {group} 的待核准成員',
),
```

#### 應用程式搭配



### Ilt


  [1]: http://laravel.com/
  [2]: http://hybridauth.sourceforge.net/
  [3]: http://www.mrcasual.com/on/coding/laravel4-package-management-with-composer/
  [4]: http://hybridauth.sourceforge.net/
  [5]: http://getbootstrap.com/
  [6]: http://fortawesome.github.io/Font-Awesome/
  [7]: http://lipis.github.io/bootstrap-social/
