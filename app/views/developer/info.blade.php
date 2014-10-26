@extends('master')


@section('head_css')
  @parent
<style type="text/css">
  .block {
    width: 860px;
    background-color: rgba(244, 248, 240, 1);
    padding: 20px;
    border: 1px solid #e5e5e5;
    -webkit-border-radius: 15px;
    -moz-border-radius: 15px;
    border-radius: 15px;
    -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
    -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
    box-shadow: 0 1px 2px rgba(0,0,0,.05);
  }

  .content {
    padding-left: 5px;
    padding-top: 15px;
  }

  table {
    font-size: 14px;
  }

  textarea.form-control {
    height:100px;
  }

</style>
@stop

@section('footer_scripts')
  @parent
  <script src="{{asset('assets/js/developer.js')}}"></script>
@stop

@section('content')
<div class="container block">
  <div class="row">
    <div class="col-md-12 col-sm-12">
      <h1 class="text-center">伊爾特開發者專區</h1>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 col-sm-12">
      <ul class="nav nav-justified nav-pills">
        <li><a href="#about" data-toggle="tab">關於</a></li>
        <li class="active"><a href="#developer_info" data-toggle="tab">開發者</a></li>
        <li><a href="#projects" data-toggle="tab">專案</a></li>
        <li><a href="{{route('user')}}">使用者介面</a></li>
      </ul>
    </div>
  </div>
  <div class="tab-content">
    @include('developer.pane_about')
    @include('developer.pane_info')
    @include('developer.pane_projects')
  </div>
</div>

@stop
