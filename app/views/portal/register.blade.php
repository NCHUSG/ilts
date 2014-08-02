@extends('portal.master')

@section('content')
  <style type="text/css">
    .block {
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


  </style>

  <div class="row">
    <div class="col-md-12 col-sm-12">
      <h1 class="text-center">伊爾特會員系統</h1>
      <h2 class="text-center">填寫使用者註冊資料</h2>
    </div>
  </div>

  <script src="{{ asset('assets/js/jquery.validate.min.js'); }}"></script>

  <script>
    function submit_registration(){
      var form_data = $( "form" ).serializeArray();

      console.log(form_data);
      
      $.post('{{$action}}',form_data,function(response){
        console.log(response);
      });
    }
    $(document).ready(function(){
      $('button[type=submit]').click(function(){

        if ($(this).is('[scope=simple]')){
          console.log("!!!");
          $('div#optional_field').slideUp(function(){
            $('div#optional_field').empty();
            submit_registration();
          });
        }
        else
          submit_registration();
        return false
      });
      $('form').submit(function(e){
        return false;
      });
    });
  </script>

{{ Form::open(array('url' => $action, 'class'=>'form-horizontal', 'role'=>'form')) }}

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
            <div class="col-sm-offset-2 col-sm-10">
              <!-- <input type="hidden" name="value" value="" /> -->
              <button type="submit" class="btn btn-default pull-right" scope="simple">略過選填資料，註冊（Register）</button>
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
            <div class="col-sm-offset-2 col-sm-10">
              <?php echo Form::token();?>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn btn-default pull-right" scope="full">註冊（Register）</button>
            </div>
          </div>

      </div>
    </div>
  </div>
{{ Form::close() }}
  <div>
  </div>
@stop
