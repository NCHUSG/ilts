<div id="join" class="row content tab-pane">
  @section('footer_scripts')
    @parent
  @stop

  <div class="col-md-12 col-sm-12">
    <h4>加入方案：</h4>
    <div id="start_join" class="row">
      @foreach ($join_option as $key => $available)
        @if ($available)
          <a href="{{ route('join',array($code,$key)) }}" class="btn btn-primary btn-lg btn-block interactive" role="button">{{ Config::get('fields.join_method.' . $key . '.zh_TW') }}</a>
        @endif
      @endforeach
    </div>
    @if ($is_pending)
      <div class="row">&nbsp;</div>
      <div class="alert alert-info" role="alert">您已經申請加入此組織！請靜待核准或是去信箱查看確認信件！</div>
    @endif
  </div>
</div>
