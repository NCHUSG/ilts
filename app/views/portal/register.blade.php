@extends('portal.master')

@section('content')
  <link rel="stylesheet" href="{{ asset('assets/css/form.css'); }}">
  <script src="{{ asset('assets/js/form.js'); }}"></script>

  <div class="row">
    <div class="col-md-12 col-sm-12">
      <h1 class="text-center">伊爾特會員系統</h1>
      <h2 class="text-center">填寫使用者註冊資料</h2>
    </div>
  </div>

{{ Form::open(array('url' => $action, 'class'=>'form-horizontal', 'role'=>'form', 'id' => 'ilt_form')) }}
  <div class="container block">
    <h3 class="text-center">必填資料</h3>
    <div class="row">
      <div class="col-md-12 col-sm-12">
        <!-- <form class="form-horizontal" role="form" action="" method="post"> -->
          <div class="form-group">
            <label for="input-oauth-provider" class="col-sm-3 control-label">OAuth Provider</label>
            <div class="col-sm-9">
              <p class="form-control-static" id="input-oauth-provider">{{$default['provider']}}</p>
            </div>
          </div>
          <div class="form-group">
            <label for="input-oauth-identifier" class="col-sm-3 control-label">OAuth Identifier</label>
            <div class="col-sm-9">
              <p class="form-control-static" id="input-oauth-identifier">{{$default['identifier']}}</p>
            </div>
          </div>
          <div class="form-group">
            <label for="input-username" class="col-sm-3 control-label">Username</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="input-username" name="username" value="{{$default['username']}}" placeholder="使用者名稱（帳號）">
              <p class="text-danger form-control-static">{{$errors->first('username');}}</p>
            </div>
          </div>
          <div class="form-group">
            <label for="input-nickname" class="col-sm-3 control-label">Nickname</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="input-nickname" name="nickname" value="{{$default['nickname']}}" placeholder="暱稱">
              <p class="text-danger form-control-static">{{$errors->first('nickname');}}</p>
            </div>
          </div>
          <div class="form-group">
            <label for="input-email" class="col-sm-3 control-label">Email</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="input-email" name="email" value="{{$default['email']}}" placeholder="電子郵件">
              <p class="text-danger form-control-static">{{$errors->first('email');}}</p>
            </div>
          </div>
          <div class="form-group">
            <div class="col-xs-12">
              <!-- <input type="hidden" name="value" value="" /> -->
              <button type="submit" scope="simple" class="progress-button" data-style="rotate-back" data-perspective="" data-horizontal=""><span class="progress-wrap"><span class="content">我要略過選填資料，直接註冊</span><span class="progress"><span style="width: 0%; opacity: 1;" class="progress-inner"></span></span></span></button>
              <!--<button type="submit" class="btn btn-default pull-right" scope="simple">略過選填資料，註冊（Register）</button> -->
            </div>
          </div>
        <!-- </form> -->
      </div>
    </div>
  </div>

  <div style="margin-top:30px">
  </div>
  <div class="container block" id="optional_field">
    <h3 class="text-center">選填資料</h3>
    <div class="row">
      <div class="col-md-12 col-sm-12">
          <div class="form-group">
            <label for="input-first-name" class="col-sm-3 control-label">First Name</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="input-first-name" value="{{$default['first_name']}}" name="first_name" placeholder="名字">
              <p class="text-danger form-control-static">{{$errors->first('first_name');}}</p>
            </div>
          </div>
          <div class="form-group">
            <label for="input-last-name" class="col-sm-3 control-label">Last Name</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="input-last-name" name="last_name" value="{{$default['last_name']}}" placeholder="姓氏">
              <p class="text-danger form-control-static">{{$errors->first('last_name');}}</p>
            </div>
          </div>
          <div class="form-group">
            <label for="input-gender" class="col-sm-3 control-label">Gender</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="input-gender" name="gender" value="{{$default['gender']}}" placeholder="性別">
              <p class="text-danger form-control-static">{{$errors->first('gender');}}</p>
            </div>
          </div>
          <div class="form-group">
            <label for="input-birthday" class="col-sm-3 control-label">Birthdday</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="input-birthday" name="birthday" value="{{$default['birthday']}}" placeholder="生日">
              <p class="text-danger form-control-static">{{$errors->first('birthday');}}</p>
            </div>
          </div>
          <div class="form-group">
            <label for="input-phone" class="col-sm-3 control-label">Phone</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="input-phone" name="phone" value="{{$default['phone']}}" placeholder="電話號碼">
              <p class="text-danger form-control-static">{{$errors->first('phone');}}</p>
            </div>
          </div>
          <div class="form-group">
            <label for="input-address" class="col-sm-3 control-label">Address</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="input-address" name="address" value="{{$default['address']}}" placeholder="地址">
              <p class="text-danger form-control-static">{{$errors->first('address');}}</p>
            </div>
          </div>
          <div class="form-group">
            <label for="input-website" class="col-sm-3 control-label">Website</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="input-website" name="website" value="{{$default['website']}}" placeholder="個人網站">
              <p class="text-danger form-control-static">{{$errors->first('website');}}</p>
            </div>
          </div>
          <div class="form-group">
            <label for="input-gravater" class="col-sm-3 control-label">Gravatar</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="input-gravater" name="gravater" value="" placeholder="Gravatar 大頭照">
              <p class="text-danger form-control-static">{{$errors->first('gravater');}}</p>
            </div>
          </div>
          <div class="form-group">
            <label for="input-description" class="col-sm-3 control-label">Description</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="input-description" name="description" value="{{$default['description']}}" placeholder="自我敘述">
              <p class="text-danger form-control-static">{{$errors->first('description');}}</p>
            </div>
          </div>
          <div class="form-group">
            <div class="col-xs-12">
              <button type="submit" class="progress-button" data-style="shrink" data-horizontal=""><span class="content">註冊（Register）</span><span class="progress"><span style="width: 0%; opacity: 1;" class="progress-inner"></span></span></button>
              <!--<button type="submit" class="btn btn-default pull-right" scope="full">註冊（Register）</button>-->
            </div>
          </div>

      </div>
    </div>
  </div>
{{ Form::close() }}
@stop
