var submit_loading_inteval,submiting,submit_registration,submit_action,redirectUrl;

function submit_action(){
  if(!submit_loading_inteval){
    submiting = $(this);
    submiting.data('percent',0);
    $(this).addClass('state-loading');

    submit_loading_inteval = setInterval(function(){
      
      var percent=submiting.data('percent');
      submiting.find('span.progress-inner').css('width',percent+'%');
      submiting.data('percent',percent+10);
    },500);

    if ($(this).is('[scope=simple]')){
      var optional_field = $('div#optional_field');
      optional_field.slideUp(function(){
        submiting.data('optional_field_tmp',optional_field.html())
        optional_field.empty();
        submit_registration();
      });
    }
    else
      submit_registration();
  }
  return false
}

function submit_registration(){
  var form_data = $( "form#ilt_form" ).serializeArray();
  
  $.ajax({
    type: "POST",
    data: form_data,
    url: $( "form#ilt_form" ).attr('action'),
    success: function(r){
      var isSuccess = true;
      console.log(r);
      if(r.errors){
        isSuccess = false;
        $('p.form-control-static').each(function(){
          $(this).text('');
        });
        for(k in r.errors)
          $('input[name='+k+']').next().text(r.errors[k]);
      }

      if(r.url)
        redirectUrl = r.url;
      
      submiting.data('isSuccess',isSuccess);
      if(!isSuccess)
        alert("您填寫的選項有誤，請檢查，謝謝");
    },
    error: function(xhr,status_text){
      alert("連線錯誤："+status_text);
      console.log(xhr);
      submiting.data('isSuccess',false);
    },
    complete:function(){
      clearInterval(submit_loading_inteval);
      submiting.find('span.progress-inner').css('width','100%');

      var complete_interval_1 = setInterval(function(){
        submiting.removeClass('state-loading');
        if(submiting.data('isSuccess')){
          submiting.css('background-color','#0E7138');
          submiting.text("註冊成功！");
          submiting.addClass('state-success');
        }
        var complete_interval_2 = setInterval(function(){
          submiting.removeClass('state-success');
          submiting.find('span.progress-inner').css('width','0%');
          clearInterval(complete_interval_2);
          submit_loading_inteval=false;
          if(submiting.data('isSuccess'))
            window.location = redirectUrl;
          else{
            var optional_field = $('div#optional_field');
            optional_field.html(submiting.data('optional_field_tmp'));
            optional_field.find('button[type=submit]').click(submit_action);
            optional_field.slideDown();
          }
            
        },1000);
        clearInterval(complete_interval_1);
      },500);
    }
  });
}

$(document).ready(function(){
  $('button[type=submit]').click(submit_action);
  $('form#ilt_form').submit(function(e){
    return false;
  });
});
