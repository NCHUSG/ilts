<div id="pane_system_manager" class="row content tab-pane">
  <div class="col-md-12 col-sm-12 editable_range">
    <h4>
      <span class="form-title">系統選項</span>
      <span class="badge to_edit"><span class="editing_show">取消</span>編輯</span>
      <span class="badge editing_show save">儲存</span>
    </h4>
    {{ Form::open(array('url' => route('siteOption'), 'class'=>'form-horizontal', 'role'=>'form')) }}
      @foreach ($site_option as $key => $value)
        <div class="form-group">
          <label for="input-{{ $key }}" class="col-sm-2 control-label">{{ Config::get('fields.' . $key . '.zh_TW', $key) }}</label>
          <div class="col-sm-10">
            <p class="editable" name="{{ $key }}">{{ $value }}</p>
            <input type="{{ $key }}" name="{{ $key }}" class="form-control editing_show" id="input-{{ $key }}" placeholder="{{ $value }}" value="{{ $value }}">
            <p class="text-danger form-control-static editing_show"></p>
          </div>
        </div>
      @endforeach
    {{ Form::close() }}
  </div>
</div>
