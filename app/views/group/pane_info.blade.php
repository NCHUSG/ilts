<div id="info" class="row content tab-pane active">
  <div class="col-md-12 col-sm-12">
    <h4>詳細資訊</h4>
    <table class="table table-hover">
      <tr>
          <td>項目</td>
          <td>內容</td>
      </tr>
      @foreach ($info as $key => $value)
        <tr>
            <td>{{ Config::get('fields.group.' . $key . '.zh_TW',$key) }}</td>
            <td>{{ $group->getOption($key ,Config::get('default.group_options.' . $key) ) }}</td>
        </tr>
      @endforeach
    </table>
  </div>
</div>
