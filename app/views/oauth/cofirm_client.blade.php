@extends('master')

@section('head_css')
  @parent
  <style type="text/css">
    #block {
      border-radius: 30px;
      background-color: white;
      padding-left: 50px;
      padding-right: 50px;
      padding-bottom: 30px;
      max-width: 430px;
      position: relative;
      margin-top: 30px;
    }

    .title-block {
      padding-top: 30px;
      padding-left: 12px;
      margin-bottom: 10px;
    }

    .title {
      font-size: 18px;
      font-weight: bold;
      color: #262626;
      cursor: pointer;
    }

    .need {
      font-size: 16px;
      padding-left: 12px;
      padding-top: 5px;
      padding-bottom: 14px;
    }

    .need-list {
      border-top: 1px solid #ebebeb;
      padding-left: 12px;
      padding-top: 22px;
      padding-bottom: 14px;
    }

    .confirm {
      border-top: 1px solid #ebebeb;
      padding-left: 12px;
      padding-top: 22px;
      padding-bottom: 14px;
    }

    .btn {
      margin-left: 10px;
    }
  </style>
@stop

@section('footer_scripts')
  @parent
  <script src="{{asset('assets/js/popover_initial.js')}}" ></script>
@stop

@section('content')
<h1 class="text-center">伊爾特系統</h1>

<div id="block" class="container">

  <div class="row title-block">
    <p>
      <span class="title">{{$app_name}}</span>
      <button type="button" class="btn btn-default btn-xs popover_init" data-toggle="popover" data-placement="right" data-content="{{$app_info}}">?</button>
    </p>
    <p>
      {{$app_describe}}
    </p>
  </div>
  <div class="clear"></div>
  <div class="row need">
    <span>這個應用程式要求您的授權：</span>
  </div>

  <div class="row need-list">
    @foreach ($require as $key => $value)
      <p scope="{{$key}}"><span class="glyphicon glyphicon-check"></span>{{$value}}</p>
    @endforeach
    <p class="text-muted">授權使用期限：{{$expires}}</p>
  </div>

  <div class="row confirm">
    <form action="{{$action}}" method="post" id="form">
      <input type="hidden" name="request_submit" value="true" />
      <input type="hidden" name="request_answer" value="true" />
      <button type="submit" class="btn btn-primary pull-right" >確認</button>
    </form>
    <form action="{{$action}}" method="post" id="form">
      <input type="hidden" name="request_submit" value="true" />
      <input type="hidden" name="request_answer" value="false" />
      <button type="submit" class="btn btn-default pull-right">取消</button>
    </form>
  </div>
</div>
@stop
