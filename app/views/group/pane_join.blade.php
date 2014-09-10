<div id="join" class="row content tab-pane">
  @section('footer_scripts')
    @parent

    <script>
      var isSuccess,refresh,redirect,form,postURL;
      var ajaxSuccess,ajaxError,ajaxCompleted,startAjax;

      function ajaxSuccess(data){
        var alertbox = $('div#infobox div.alert');
        alertbox.attr('class','alert alert-' + data.status);
        if(data.error){
          alertbox.text(data.error)
          isSuccess = false;
        }
        else{
          alertbox.text(data.message)
          isSuccess = true;
        }
        alertbox.slideDown();
        if(data.refresh) refresh = data.refresh;
        if(data.redirect) redirect = data.redirect;
        if(data.form) form = data.form;
        if(data.postURL) postURL = data.postURL;
      }

      function ajaxError(xhr,status_text){
        console.log(xhr);
        alert("資料傳送錯誤："+status_text);
        isSuccess = false;
      }

      function ajaxCompleted(data){
        $('div#infobox h3.text-center').slideUp();
        $('div#infobox #noTrespassingOuterBarG').slideUp();
        if (isSuccess)
          $('div#infobox h4.modal-title').text("成功發出請求...");
        else
          $('div#infobox h4.modal-title').text("失敗...");

        if(refresh){
          var complete_interval = setInterval(function(){
            if(redirect)
              window.location = redirect;
            else
              location.reload();
            console.log("reloading...");
            clearInterval(complete_interval);
          },refresh);
        }
        else if(form){
          $('div#infobox h4.modal-title').text("請填寫表單...");
          var formTmp = $('<form id="join_form" action="'+postURL+'"></form>');
          for(type in form){
            for(name in form[type])
              formTmp.append($('<input class="form-control" name="'+name+'" type="'+type+'" value="'+form[type][name]+'">'));
          }
          formTmp.append($('<div class="row">&nbsp;</div>'))
          formTmp.append($('<button type="submit" class="btn btn-primary btn-lg btn-block">送出</button>'))
          formTmp.submit(function(){
            startAjax($(this).attr('action'),$(this).serializeArray());
            return false;
          });
          $('div#infobox div.modal-body').append(formTmp);
          console.log(form);
        }
      }

      function startAjax(url,data){
        $('div#infobox h4.modal-title').text("發出加入請求...");
        $('div#infobox h3.text-center').show().text("請稍等...");
        $('div#infobox div.alert').hide();
        $('div#infobox #noTrespassingOuterBarG').show();
        $('div#infobox').modal({
          keyboard: false
        });

        function ajaxCore(){
          if(data)
            $.ajax({
              type:'POST',
              url: url,
              data: data,
              success: ajaxSuccess,
              error: ajaxError,
              complete: ajaxCompleted,
            });
          else
            $.ajax({
              type:'GET',
              url: url,
              success: ajaxSuccess,
              error: ajaxError,
              complete: ajaxCompleted,
            });
        }

        if($('form#join_form').is('form'))
          $('form#join_form').slideUp(function(){
            $('form#join_form').remove();
            ajaxCore();
          });
        else
          ajaxCore();
      }

      $('#start_join a').click(function(){
        console.log("!!!");

        startAjax($(this).attr('href'));

        return false;
      });
    </script>
  @stop

  <div class="col-md-12 col-sm-12">
    <h4>加入方案：</h4>
    <div id="start_join" class="row">
      @foreach ($join_option as $key => $available)
        @if ($available)
          <a href="{{ route('join',array($code,$key)) }}" class="btn btn-primary btn-lg btn-block" role="button">{{ Config::get('fields.join_method.' . $key . '.zh_TW') }}</a>
        @endif
      @endforeach
    </div>
    @if ($is_pending)
      <div class="row">&nbsp;</div>
      <div class="alert alert-info" role="alert">您已經申請加入此組織！請靜待核准或是去信箱查看確認信件！</div>
    @endif
  </div>
</div>
