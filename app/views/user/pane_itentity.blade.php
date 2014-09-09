
<div id="itentity" class="row content tab-pane">
  <div class="col-xs-12">
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
          var trigger_more = more('div#identities-wrapper');
          if(window.location.hash == "#itentity")
            trigger_more();
          $('ul.nav li a[href="#itentity"]').click(trigger_more);
        });
      </script>
    @stop

    <div id="identities-wrapper" class="more-wrapper" href="{{ route('identities'); }}">
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
    @if( filter_var(IltSiteOptions::get('allow_create_root_group','false'), FILTER_VALIDATE_BOOLEAN) )
      <a href="{{ route('createRootGroup') }}" class="btn btn-primary btn-lg btn-block" role="button">建立根群組</a>
    @endif
    @if($isAdmin)
      <a href="{{ route('admin') }}" class="btn btn-primary btn-lg btn-block" role="button">管理員介面</a>
    @endif
    @if($isDev)
      <a href="{{ route('dev') }}" class="btn btn-info btn-lg btn-block" role="button">開發者介面</a>
    @endif
  </div>
</div>
