@extends('master')


@section('head_css')
  @parent

  <link href="{{ asset('assets/css/info.css'); }}" rel="stylesheet"/>
@stop

@section('footer_scripts')
  @parent

  <script src="{{ asset('assets/js/info.js'); }}"></script>
@stop

@section('content')
<div class="container block">
  <div class="row">
    <div class="col-md-12 col-sm-12">
      <h1 class="text-center">伊爾特使用者專區</h1>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 col-sm-12">
      <ul class="nav nav-justified nav-pills">
        <li><a href="#about" data-toggle="tab">關於</a></li>
        <li class="active"><a href="#user_info" data-toggle="tab">使用者</a></li>
        <!-- <li><a href="#massages" data-toggle="tab">短訊（預計）</a></li> -->
        <li><a href="#itentity" data-toggle="tab">身分、群組</a></li>
        <li><a href="#auth" data-toggle="tab">安全性</a></li>
        <li><a href="{{route('logout')}}">登出</a></li>
      </ul>
    </div>
  </div>
  <div class="tab-content">
    @include('user.pane_about')
    @include('user.pane_itentity')
    @include('user.pane_auth')
    @include('user.pane_info')
  </div>
</div>

@stop
