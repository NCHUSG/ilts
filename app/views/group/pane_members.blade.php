<div id="members" class="row content tab-pane">
  <div class="col-md-12 col-sm-12">
    <h4>成員</h4>

    @section('head_css')
      @parent
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

        function perm_action(button){
          interactive($(button).attr('href'));
          return false;
        }
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
        <button href="#" class="btn btn-success allow" onclick="return perm_action(this)" role="button">准許加入</button>
        <button href="#" class="btn btn-info admin" onclick="return perm_action(this)" role="button">提升為管理員</button>
        <button href="#" class="btn btn-warning lower" onclick="return perm_action(this)" role="button">取消管理員權限</button>
        <button href="#" class="btn btn-danger kick" onclick="return perm_action(this)" role="button">踢出</button>
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
    @if($is_admin)
      <a href="{{ route('invite',$group->g_code) }}" class="btn btn-primary btn-lg btn-block" onclick="return perm_action(this)" role="button">邀請</a>
    @endif
  </div>
</div>
