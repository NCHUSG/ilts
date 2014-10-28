<div id="members" class="row content tab-pane">
  <div class="col-md-12 col-sm-12">
    <h4>成員</h4>

    @section('head_css')
      @parent

      <link href="{{ asset('assets/css/more.css'); }}" rel="stylesheet"/>
    @stop

    @section('footer_scripts')
      @parent
      <script>
        $(document).ready(function(){
          var trigger_more = more('div#members-wrapper');
          if(window.location.hash == "#members")
            trigger_more();
          $('ul.nav li a[href="#members"]').click(trigger_more);
        });
      </script>
    @stop

    <div id="members-wrapper" class="more-wrapper" href="{{ route('member',$code); }}">
      <div class="more-sample">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><span class="group_name"></span><span class="badge statusText"></span></h3>
          </div>
          <div class="panel-body">
            
          </div>
        </div>
        <div class="info username col-xs-12 col-md-6"><div class="info-content alert alert-success">使用者名稱：</div></div>
        <div class="info email col-xs-12 col-md-6"><div class="info-content alert alert-info">電子信箱：</div></div>
      </div>
      <div class="more-container">
        <div class="more-loadingBar" class="row">
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
        </div>
      </div>
    </div>
    <div class="row">&nbsp;</div>
    @if($display_create)
      <a href="{{ route('invite',$group->g_code) }}" class="btn btn-primary btn-lg btn-block" role="button">邀請</a>
    @endif
  </div>
</div>
