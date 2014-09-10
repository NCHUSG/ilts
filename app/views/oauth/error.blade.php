@extends('master')

@section('content')
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

    .title {
        padding-top: 30px;
        padding-left: 12px;
        margin-bottom: 16px;

        font-size: 18px;
        font-weight: bold;
        color: #262626;
        cursor: pointer;
    }

    .need {

        font-size: 16px;
        padding-left: 12px;
        padding-top: 10px;
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

<h1 class="text-center">伊爾特系統</h1>

<div id="block" class="container">

    <div class="row title">
        <span>應用程式辨識錯誤</span>
    </div>
    <div class="clear">
    </div>
    <div class="row need">
        <span>你所使用的應用程式可能發生以下錯誤：</span>
    </div>

    <div class="row need-list">
        @foreach ($error as $key => $value)
            <p><span class="glyphicon glyphicon-remove"></span>{{ $value }}</p>
        @endforeach
    </div>

    <div class="row confirm">
        <a class="btn btn-warning pull-right" href="javascript:history.back()">回上頁</a>
    </div>
</div>

@stop
