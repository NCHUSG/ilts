<div id="ctrl" class="row content tab-pane">
  <div class="col-md-12 col-sm-12 editable_range">
    <h4>
      <span class="form-title">基本資料</span>
      <span class="badge to_edit"><span class="editing_show">取消</span>編輯</span>
      <span class="badge editing_show save">儲存</span>
    </h4>
    {{ Form::open(array('url' => route('groupCtrl',array($group->g_code,'basicCtrl')), 'class'=>'form-horizontal', 'role'=>'form')) }}
      @foreach ($basic_info as $key => $value)
        <div class="form-group">
          <label for="input-{{ $key }}" class="col-sm-2 control-label">{{ $fields[$key]['zh_TW'] }}</label>
          <div class="col-sm-10">
            <p class="editable" name="{{ $key }}">{{ $value }}</p>
            <input type="{{ $key }}" name="{{ $key }}" class="form-control editing_show" id="input-{{ $key }}" placeholder="{{ $value }}" value="{{ $value }}">
            <p class="text-danger form-control-static editing_show"></p>
          </div>
        </div>
      @endforeach
    {{ Form::close() }}
  </div>
  <div class="col-md-12 col-sm-12 editable_range">
    <h4>
      <span class="form-title">公開資料</span>
      <span class="badge to_edit"><span class="editing_show">取消</span>編輯</span>
      <span class="badge editing_show save">儲存</span>
    </h4>
    {{ Form::open(array('url' => route('groupCtrl',array($group->g_code,'public')), 'class'=>'form-horizontal', 'role'=>'form')) }}
      @foreach ($info as $key => $value)
        <div class="form-group">
          <label for="input-{{ $key }}" class="col-sm-2 control-label">{{ $fields[$key]['zh_TW'] }}</label>
          <div class="col-sm-10">
            <p class="editable" name="{{ $key }}">{{ $value }}</p>
            <input type="{{ $key }}" name="{{ $key }}" class="form-control editing_show" id="input-{{ $key }}" placeholder="{{ $value }}" value="{{ $value }}">
            <p class="text-danger form-control-static editing_show"></p>
          </div>
        </div>
      @endforeach
    {{ Form::close() }}
  </div>
  <div class="col-md-12 col-sm-12 editable_range">
    <h4>
      <span class="form-title">設定選項</span>
      <span class="badge to_edit"><span class="editing_show">取消</span>編輯</span>
      <span class="badge editing_show save">儲存</span>
    </h4>
    {{ Form::open(array('url' => route('groupCtrl',array($group->g_code,'bool_option')), 'class'=>'form-horizontal', 'role'=>'form')) }}
      @foreach ($option as $key => $value)
        <div class="form-group">
          <label for="input-{{ $key }}" class="col-sm-2 control-label">{{ $fields[$key]['zh_TW'] }}</label>
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
