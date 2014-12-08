@extends('portal.master')

@section('content')
  <link rel="stylesheet" href="{{ asset('assets/css/form.css'); }}">
  <script src="{{ asset('assets/js/form.js'); }}"></script>

  <div class="row">
    <div class="col-md-12 col-sm-12">
      <h1 class="text-center">伊爾特會員系統</h1>
      <h2 class="text-center">填寫組織註冊資料</h2>
    </div>
  </div>

{{ Form::open(array('url' => $action, 'class'=>'form-horizontal', 'role'=>'form', 'id' => 'ilt_form')) }}
  <div class="container block">
    <h3 class="text-center">建立組織</h3>
    <div class="row">
      <div class="col-md-12 col-sm-12">
        <!-- <form class="form-horizontal" role="form" action="" method="post"> -->
          @foreach($default as $key => $value)
          <div class="form-group">
            <label for="input-{{ $key }}" class="col-sm-3 control-label">{{ Config::get('fields.group.' . $key . '.zh_TW',$key) }}</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="input-{{ $key }}" name="{{ $key }}" value="{{ $value }}" >
              <p class="text-danger form-control-static">{{$errors->first($key);}}</p>
            </div>
          </div>
          @endforeach
          <div class="form-group">
            <div class="col-xs-12">
              <!-- <input type="hidden" name="value" value="" /> -->
              <button type="submit" class="progress-button" data-style="rotate-back" data-perspective="" data-horizontal=""><span class="progress-wrap"><span class="content">建立組織</span><span class="progress"><span style="width: 0%; opacity: 1;" class="progress-inner"></span></span></span></button>
              <!--<button type="submit" class="btn btn-default pull-right" scope="simple">略過選填資料，註冊（Register）</button> -->
            </div>
          </div>
        <!-- </form> -->
      </div>
    </div>
  </div>
{{ Form::close() }}
@stop
