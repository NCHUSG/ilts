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
      <h1 class="text-center">伊爾特管理者專區</h1>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 col-sm-12">
      <ul class="nav nav-justified nav-pills">
        <li class="active"><a href="#info" data-toggle="tab">資訊</a></li>
        <li><a href="#pane_system_manager" data-toggle="tab">系統</a></li>
        <li><a href="#pane_user_manager" data-toggle="tab">使用者</a></li>
        <li><a href="#pane_developer_manager" data-toggle="tab">開發者</a></li>
        <li><a href="#pane_admin_manager" data-toggle="tab">管理者</a></li>
        <li><a href="#pane_group_manager" data-toggle="tab">群組</a></li>
        <li><a href="#pane_identity_manager" data-toggle="tab">權限</a></li>
        <li><a href="{{route('user')}}">使用者介面</a></li>
      </ul>
    </div>
  </div>
  <div class="tab-content">
    @include('admin.pane_about')
    @include('admin.pane_info')
    @include('admin.pane_system_manager')
    @include('admin.pane_user_manager')
    @include('admin.pane_developer_manager')
    @include('admin.pane_admin_manager')
    @include('admin.pane_group_manager')
    @include('admin.pane_identity_manager')
  </div>
</div>

@stop
