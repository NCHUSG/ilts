<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>伊爾特會員系統</title>

@section('head_css')
  <link href="{{ asset('assets/bootstrap/3.0.3/css/bootstrap.min.css'); }}" rel="stylesheet"/>
  <link href="{{ asset('assets/font-awesome/4.0.3/css/font-awesome.min.css'); }}" rel="stylesheet"/>
  <link href="{{ asset('assets/css/bootstrap-social.css'); }}" rel="stylesheet"/>

  <style type="text/css">
    body
    {
      background-color: rgba(199, 235, 233, 1);
      padding-top: 50px;
    }

    #copyright
    {
      margin-top: 50px;
      margin-bottom: 30px;
    }
  </style>
@show

</head>
<body>
  <div class="container">
    @yield('content')
    <div class="modal fade" id="infobox" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id="myModalLabel">...</h4>
            </div>
            <div class="modal-body">
              <div id="noTrespassingOuterBarG">
              <div id="noTrespassingFrontBarG" class="noTrespassingAnimationG">
              <div class="noTrespassingBarLineG">
              </div>
              <div class="noTrespassingBarLineG">
              </div>
              <div class="noTrespassingBarLineG">
              </div>
              <div class="noTrespassingBarLineG">
              </div>
              <div class="noTrespassingBarLineG">
              </div>
              <div class="noTrespassingBarLineG">
              </div>
              </div>
              </div>
              <h3 class="text-center">...</h3>
              <div class="alert" role="alert"></div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
  </div>
  <div id="copyright" class="container">
    <div class="row">
      <div class="col-md-12 col-sm-12">
        <p class="text-info text-center">
          Created by Fntsrlike, Maintaining by CHILDISH <br/>
          Copyright © 2014 Fntsrlike. All Rights Reserved
        </p>
      </div>
    </div>
  </div>
@section('footer_scripts')
  <script src="{{ asset('assets/js/jquery.1.11.0.min.js'); }}"></script>
  <script src="{{ asset('assets/bootstrap/3.0.3/js/bootstrap.min.js'); }}"></script>
@show
<?php $from_session = false; ?>
@if( isset($message) || $from_session = Session::has('message') )
<?php
    if($from_session){ $message = Session::get('message'); Session::forget('message'); }
    if(is_string($message)) $message = ["content" => Session::get('message'), "status" => "warn"];
?>
<script>
  $(document).ready(function(){
    var message = "{{ $message['content'] }}";
    var status = "{{ $message['status'] }}";
    $('div#infobox h4.modal-title').text("訊息");
    $('div#infobox h3.text-center').hide();
    $('div#infobox div.alert').show().attr('class','alert alert-' + status).text(message);
    $('div#infobox #noTrespassingOuterBarG').hide();
    $('div#infobox').modal({
      keyboard: false
    });
  });
</script>
@endif
</body>
</html>
