<script>
  $(document).ready(function(){
    $('span.to_edit').click(function(){
      $(this).parents('.editable_range').toggleClass('editing');
    });

    var submiting;
    $('span.save').click(function(){
      if(!submiting){
        submiting = $(this).parents('.editable_range').find('form');
        var form_data = submiting.serializeArray();
        var action = submiting.attr('action');

        $.ajax({
          type: "POST",
          data: form_data,
          url: action,
          success: function(data){
            if(typeof data == "object"){
              submiting.data('isSuccess',false);
              for(k in data){
                $('input[name='+k+']').next().text(data[k]);
              }
              $('#modify_status').text("您填寫的選項有誤，請檢查，謝謝");
              $('#modifying .modal-footer').removeClass('hidden');
            }
            else
              submiting.data('isSuccess',true);
          },
          error: function(xhr,status_text){
            $('#modify_status').text("資料傳送錯誤："+status_text);
            $('#modifying .modal-footer').removeClass('hidden');
            submiting.data('isSuccess',false);
          },
          complete:function(data){
            if(submiting.data('isSuccess')){
              $('#noTrespassingOuterBarG').slideUp();
              $('#modify_status').text("成功！");
              var complete_interval = setInterval(function(){
                location.reload();
                console.log("reloading...");
                clearInterval(complete_interval);
              },1000);
            }

            submiting=false;
          }
        });
      }
      $('#modifying .modal-footer').addClass('hidden');
      $('#modifying').modal({
        keyboard: false
      });
    });
  });
</script>

<style>
  p.editable{
    height: 24px;
    padding: 6px 12px;
  }
  div.editable_range:not(.editing) .editing_show{
    display: none;
  }
  div.editable_range.editing .editable{
    display: none;
  }

  span.to_edit:hover{
    background-color:rgba(24, 77, 129, 1);
  }

  span.save:hover{
    background-color:rgba(189, 138, 50, 1);
  }

  p.form-control-static.editing_show{
    padding-top: 0;
  }
</style>
<div id="user_info" class="row content tab-pane active">
  <div class="col-md-12 col-sm-12 editable_range">
    <h4>
      基本資料
      <span class="badge to_edit" id="basic_info"><span class="editing_show">取消</span>編輯</span>
      <span class="badge editing_show save">儲存</span>
    </h4>
    {{ Form::open(array('url' => route('update_info','basic'), 'class'=>'form-horizontal', 'role'=>'form')) }}
      @foreach ($user_info as $key => $value)
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
      個人資料
      <span class="badge to_edit" id="basic_info"><span class="editing_show">取消</span>編輯</span>
      <span class="badge editing_show save">儲存</span>
    </h4>
    {{ Form::open(array('url' => route('update_info','option'), 'class'=>'form-horizontal', 'role'=>'form')) }}
      @foreach ($user_option as $key => $value)
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


<div class="modal fade" id="modifying" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">個人資料修改...</h4>
        </div>
        <div class="modal-body">
          <style>
          #noTrespassingOuterBarG{
            margin-left: auto;
            margin-right: auto;
            height:34px;
            width:268px;
            border:2px solid #27B0B0;
            overflow:hidden;
            background-color:#FFFFFF;
          }

          .noTrespassingBarLineG{
          background-color:#27B0B0;
          float:left;
          width:23px;
          height:201px;
          margin-right:40px;
          margin-top:-47px;
          -moz-transform:rotate(45deg);
          -webkit-transform:rotate(45deg);
          -ms-transform:rotate(45deg);
          -o-transform:rotate(45deg);
          transform:rotate(45deg);
          }

          .noTrespassingAnimationG{
          width:395px;
          -moz-animation-name:noTrespassingAnimationG;
          -moz-animation-duration:1s;
          -moz-animation-iteration-count:infinite;
          -moz-animation-timing-function:linear;
          -webkit-animation-name:noTrespassingAnimationG;
          -webkit-animation-duration:1s;
          -webkit-animation-iteration-count:infinite;
          -webkit-animation-timing-function:linear;
          -ms-animation-name:noTrespassingAnimationG;
          -ms-animation-duration:1s;
          -ms-animation-iteration-count:infinite;
          -ms-animation-timing-function:linear;
          -o-animation-name:noTrespassingAnimationG;
          -o-animation-duration:1s;
          -o-animation-iteration-count:infinite;
          -o-animation-timing-function:linear;
          animation-name:noTrespassingAnimationG;
          animation-duration:1s;
          animation-iteration-count:infinite;
          animation-timing-function:linear;
          }

          #noTrespassingFrontBarG{
          }

          @-moz-keyframes noTrespassingAnimationG{
          0%{
          margin-left:0px;
          }

          100%{
          margin-left:-64px;
          }

          }

          @-webkit-keyframes noTrespassingAnimationG{
          0%{
          margin-left:0px;
          }

          100%{
          margin-left:-64px;
          }

          }

          @-ms-keyframes noTrespassingAnimationG{
          0%{
          margin-left:0px;
          }

          100%{
          margin-left:-64px;
          }

          }

          @-o-keyframes noTrespassingAnimationG{
          0%{
          margin-left:0px;
          }

          100%{
          margin-left:-64px;
          }

          }

          @keyframes noTrespassingAnimationG{
          0%{
          margin-left:0px;
          }

          100%{
          margin-left:-64px;
          }

          }

          </style>
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
          <h3 id="modify_status" class="text-center">...</h3>
        </div>
        <div class="modal-footer hidden">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
