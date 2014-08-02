@extends('portal.master')

@section('content')
  <style type="text/css">
    .login-block {
      width: 700px;
      background-color: rgba(244, 248, 240, 1);
      padding: 15px;
      border: 1px solid #e5e5e5;
      -webkit-border-radius: 15px;
      -moz-border-radius: 15px;
      border-radius: 15px;
      -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
      -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
      box-shadow: 0 1px 2px rgba(0,0,0,.05);
    }

    .copyright {
      padding: 15px;
    }

    .content {
      padding-top: 15px;
    }

    .content-left {
      border-right: 1px solid rgb(177, 177, 177);
    }

    .content-descibe {
      margin-bottom: 15px;
    }

  </style>

@if (Input::has('callback'))
  <div class="alert alert-info" style="width: 700px; margin:auto; margin-bottom: 20px;">
    <a class="close" data-dismiss="alert" href="#" aria-hidden="true">&times;</a>
    您現在所需的功能需要先登入後才能使用，在您登入後，我們即會為您轉回到原先的頁面。
  </div>
@endif

  <div class="container login-block">
    <div class="row">
      <div class="col-md-12 col-sm-12">
        <h1 class="text-center">{{Config::get('sites.name')}}登入頁面</h1>
        <h4 class="text-center text-info">伊爾特系統，一個為了組織生態系所創建的會員系統</h4>
      </div>
    </div>

    <div class="row content">
      <div class="col-md-8 col-sm-8 content-left">
        <div class="content-descibe">
          <h4>OAuth Login</h4>
          <p>
            伊爾特系統是一個支援OAuth登入的會員系統，我們不使用密碼登入，既提高安全性，也讓使用者更加方便。請在右方按鈕中，選擇你想要使用的登入方式進行本系統登入。
          </p>
        </div>
        <div>
          <h4>OAuth Provider</h4>
          <p>
            亦身為OAuth Provider的伊爾特系統，也是整合應用程式服務的一個平台。將同一個生態圈的應用程式服務與伊爾特的會員系統建立聯結，使應用程式不再需要另外建立會員，而根據需求索取使用者的相關資訊，讓伊爾特系統協助你資料的保管與應用程式的溝通。
          </p>
        </div>
      </div>
      <div class="col-md-4 col-sm-4">
        @if( isset($urls['google']) )
          <a href="{{ $urls['google'] }}" class="btn btn-block btn-social btn-google-plus">
            <i class="fa fa-google-plus"></i> Sign in with Google
          </a>
        @endif
        @if( isset($urls['facebook']) )
          <a href="{{ $urls['facebook'] }}" class="btn btn-block btn-social btn-facebook">
            <i class="fa fa-facebook"></i> Sign in with Facebook
          </a>
        @endif
      </div>
    </div>
  </div>
@stop
