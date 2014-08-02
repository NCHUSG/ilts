<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>伊爾特會員系統</title>

@section('head_css')
  <link href="{{ asset('assets/bootstrap/3.0.3/css/bootstrap.min.css'); }}" rel="stylesheet"/>
  <link href="{{ asset('assets/font-awesome/4.0.3/css/font-awesome.min.css'); }}" rel="stylesheet"/>
  <link href="{{ asset('assets/css/bootstrap-social.css'); }}" rel="stylesheet"/>

  <script src="{{ asset('assets/js/jquery.1.11.0.min.js'); }}"></script>
  <script src="{{ asset('assets/bootstrap/3.0.3/js/bootstrap.min.js'); }}"></script>
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
@show
</body>
</html>
