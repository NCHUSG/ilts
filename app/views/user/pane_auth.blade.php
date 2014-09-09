<div id="auth" class="row content tab-pane">
      <div class="col-md-12 col-sm-12">
        <div>
          <h4>目前狀態</h4>
          <ul class="list-unstyled">
            <li>本次登入從：{{$provider}}</li>
            <li>使用者狀態：{{$user->u_status}}</li>
          </ul>
        </div>
        -
        <div>
          <h4>Portal認證</h4>
          <ul class="list-unstyled">
            {{$providers_info}}
          </ul>
        </div>
<!--         -
        <div>
          <h4>權限認證</h4>
          <ul class="list-unstyled">
            <li>註冊會員：<span class="text-success">已通過</span></li>
            <li>信箱認證：<span class="text-primary">進行中</span></li>
            <li>學生認證：<span class="text-muted">尚未認證</span></li>
          </ul>
        </div> -->
        -
        <div>
          <h4>應用程式認證</h4>
          <ul class="list-unstyled">
            {{$projects_info}}
          </ul>
        </div>
      </div>
    </div>
