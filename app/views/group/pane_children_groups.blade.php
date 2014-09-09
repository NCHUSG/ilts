<div id="children_groups" class="row content tab-pane">
  <div class="col-md-12 col-sm-12">
    <h4>子群組</h4>

    @section('head_css')
      @parent

      <link href="{{ asset('assets/css/more.css'); }}" rel="stylesheet"/>
    @stop

    @section('footer_scripts')
      @parent
      <script src="{{ asset('assets/js/jquery.appear.js'); }}"></script>
      <script src="{{ asset('assets/js/more.js'); }}"></script>
      <script>
        $(document).ready(function(){
          var trigger_more = more('div#subGroups-wrapper');
          if(window.location.hash == "#children_groups")
            trigger_more();
          $('ul.nav li a[href="#children_groups"]').click(trigger_more);
        });
      </script>
    @stop

    <div id="subGroups-wrapper" class="more-wrapper" href="{{ route('subGroup',$code); }}">
      <div class="more-sample">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><span class="group_name"></span><span class="badge statusText"></span></h3>
          </div>
          <div class="panel-body">
            
          </div>
        </div>
        <a href="#" class="btn btn-default groupPanelBtn info" role="button">群組頁面</a>
        <a href="#" class="btn btn-info groupPanelBtn ctrl" role="button">群組管理介面</a>
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
      <a href="{{ route('createGroup',$group->g_code) }}" class="btn btn-primary btn-lg btn-block" role="button">建立子群組</a>
    @endif
  </div>
</div>
