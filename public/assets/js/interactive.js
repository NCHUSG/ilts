function interactive(url,data){
  var modalDOM = $('div#infobox');
  var modal_title = $('div#infobox h4.modal-title');
  var modal_header = $('div#infobox h3.text-center');
  var modal_alert = $('div#infobox div.alert');
  var modal_footer = $('div#infobox div.modal-footer');
  var modal_progress = $('div#infobox #noTrespassingOuterBarG');

  var isSuccess,refresh,redirect,form,postURL;

  function ajaxSuccess(data){
    modal_alert.attr('class','alert alert-' + data.status);
    if(data.error){
      modal_alert.text(data.error);
      isSuccess = false;
    }
    else{
      modal_alert.text(data.message);
      isSuccess = true;
    }
    modal_alert.slideDown();
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
    modal_header.slideUp();
    modal_progress.slideUp();
    if (isSuccess)
      modal_title.text("成功發出請求...");
    else
      modal_title.text("失敗...");

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
      modal_title.text("請填寫表單...");
      var formTmp = $('<form id="join_form" action="'+postURL+'"></form>');
      var input_str;
      for(var k in form){
        input_str = '<input class="form-control" name="'+form[k].name+'" type="'+form[k].type+'" placeholder="'+form[k].placeholder+'" value="'+form[k].value+'">';
        if(form[k].text)
          input_str += form[k].text + "</input>";
        formTmp.append($(input_str));
      }
      // for(var type in form){
      //   for(var name in form[type])
      //     formTmp.append($('<input class="form-control" name="'+name+'" type="'+type+'" value="'+form[type][name]+'">'));
      // }
      formTmp.append($('<div class="row">&nbsp;</div>'));
      formTmp.append($('<button type="submit" class="btn btn-primary btn-lg btn-block">送出</button>'));
      formTmp.submit(function(){
        interactive($(this).attr('action'),$(this).serializeArray());
        return false;
      });
      modalDOM.find('div.modal-body').append(formTmp);
      console.log(form);
    }
  }

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

  modal_title.text("發出加入請求...");
  modal_header.show().text("請稍等...");
  modal_alert.hide();
  modal_footer.hide();
  modal_progress.show();
  modalDOM.modal({
    keyboard: false
  });

  if(modalDOM.find('form').is('form'))
    modalDOM.find('form').slideUp(function(){
      modalDOM.find('form').remove();
      ajaxCore();
    });
  else
    ajaxCore();
}

$('a.interactive').click(function(){
  interactive($(this).attr('href'));
  return false;
});
