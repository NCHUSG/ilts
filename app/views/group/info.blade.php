@extends('master')


@section('head_css')
  @parent

  <link href="{{ asset('assets/css/info.css'); }}" rel="stylesheet"/>
  <link href="{{ asset('assets/css/more.css'); }}" rel="stylesheet"/>
@stop

@section('footer_scripts')
  @parent

  <script src="{{ asset('assets/js/info.js'); }}"></script>
  <script src="{{ asset('assets/js/jquery.appear.js'); }}"></script>
  <script src="{{ asset('assets/js/more.js'); }}"></script>
  <script src="{{ asset('assets/js/interactive.js'); }}"></script>
@stop

@section('content')
<div class="container block">
  <div class="row">
    <div class="col-md-12 col-sm-12">
      <h1 class="text-center">群組：{{ $group->g_name }} <span class="badge">{{ $group->g_code }}</span></h1>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 col-sm-12">
      <ul class="nav nav-justified nav-pills">
        <li class="active"><a href="#info" data-toggle="tab">資訊</a></li>
        @if($display_join)
          <li><a href="#join" data-toggle="tab">加入</a></li>
        @endif
        @if($display_subGroup)
          <li><a href="#children_groups" data-toggle="tab">子群組</a></li>
        @endif
        @if($display_member)
          <li><a href="#members" data-toggle="tab">成員</a></li>
        @endif
        @if($is_admin)
          <li><a href="#ctrl" data-toggle="tab">編輯選項</a></li>
        @endif
        @if(isset($parent_group))
          <li><a href="{{route('group',$parent_group->g_code)}}">返回 {{$parent_group->g_name}}</a></li>
        @endif
        <li><a href="{{route('user')}}">使用者介面</a></li>
      </ul>
    </div>
  </div>
  <div class="tab-content">
    @include('group.pane_info')
    @if($display_join)
      @include('group.pane_join')
    @endif
    @if($display_subGroup)
      @include('group.pane_children_groups')
    @endif
    @if($display_member)
      @include('group.pane_members')
    @endif
    @if($is_admin)
      @include('group.pane_ctrl')
    @endif
  </div>
</div>

@stop
