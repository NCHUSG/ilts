@extends('portal.master')

@section('content')
  <link rel="stylesheet" href="{{ asset('assets/css/submit_loading.css'); }}">
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
    
    .progress-button{
      width: 100%;
      border-radius: 5px;
      overflow: hidden;
    }

  </style>

  <div class="row">
    <div class="col-md-12 col-sm-12">
      <h1 class="text-center">伊爾特會員系統</h1>
      <h2 class="text-center">填寫使用者註冊資料</h2>
    </div>
  </div>

  <script>
    var submit_loading_inteval,submiting;

    function submit_registration(){
      var form_data = $( "form#regis_form" ).serializeArray();
      
      $.ajax({
        type: "POST",
        data: form_data,
        url: '{{ $action }}',
        success: function(data){
          if(typeof data == "object"){
            submiting.data('isSuccess',false);
            for(k in data){
              $('input[name='+k+']').next().text(data[k]);
            }
            alert("您填寫的選項有誤，請檢查，謝謝");
          }
          else
            submiting.data('isSuccess',true);
        },
        error: function(xhr,status_text){
          alert(status_text);
          submiting.data('isSuccess',false);
        },
        complete:function(){
          clearInterval(submit_loading_inteval);
          submiting.find('span.progress-inner').css('width','100%');

          var complete_interval_1 = setInterval(function(){
            submiting.removeClass('state-loading');
            if(submiting.data('isSuccess')){
              submiting.css('background-color','#0E7138');
              submiting.text("註冊成功！");
              submiting.addClass('state-success');
            }
            var complete_interval_2 = setInterval(function(){
              submiting.removeClass('state-success');
              submiting.find('span.progress-inner').css('width','0%');
              clearInterval(complete_interval_2);
              submit_loading_inteval=false;
              if(submiting.data('isSuccess'))
                window.location = '{{ $success_redirect }}';
              else{
                var optional_field = $('div#optional_field');
                optional_field.html(submiting.data('optional_field_tmp'));
                optional_field.slideDown();
              }
                
            },1000);
            clearInterval(complete_interval_1);
          },500);
        }
      });
    }
    $(document).ready(function(){
      $('button[type=submit]').click(function(){
        if(!submit_loading_inteval){
          submiting = $(this);
          submiting.data('percent',0);
          $(this).addClass('state-loading');

          submit_loading_inteval = setInterval(function(){
            
            var percent=submiting.data('percent');
            submiting.find('span.progress-inner').css('width',percent+'%');
            submiting.data('percent',percent+10);
          },500);

          if ($(this).is('[scope=simple]')){
            var optional_field = $('div#optional_field');
            optional_field.slideUp(function(){
              submiting.data('optional_field_tmp',optional_field.html())
              optional_field.empty();
              submit_registration();
            });
          }
          else
            submit_registration();
        }
        return false
      });
      $('form').submit(function(e){
        return false;
      });
    });
  </script>

{{ Form::open(array('url' => $action, 'class'=>'form-horizontal', 'role'=>'form', 'id' => 'regis_form')) }}
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
