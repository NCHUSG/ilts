var submiting;
var statusInfo = $('div#infobox h3.text-center');
var processBar = $('div#infobox #noTrespassingOuterBarG');
var alert = $('div#infobox div.alert');
var controls = $('div#infobox div.modal-footer');

function init_info_tabs(){
  var hash = window.location.hash;

  $('.nav a[data-toggle=tab]').click(function (e) {
    $(this).tab('show');
    var scrollmem = $('body').scrollTop();
    window.location.hash = this.hash;
    $('html,body').scrollTop(scrollmem);
  });

  if (hash != "")
    $('ul.nav a[href="' + hash + '"]').eq(0).trigger('click');
  else
    $('ul.nav li.active a').eq(0).trigger('click');
}

function editable_form(){
  $('span.to_edit').click(function(){
    $(this).parents('.editable_range').toggleClass('editing');
  });

  $('span.save').click(function(){
    if(!submiting){
      var editable_range = $(this).parents('.editable_range');

      submiting = editable_range.find('form');

      var form_data = submiting.serializeArray();
      var action = submiting.attr('action');

      $('div#infobox h4.modal-title').text("修改" + editable_range.find('span.form-title').text() + "...");
      statusInfo.show().text("處理中...");
      alert.hide();
      controls.hide();
      processBar.show();
      $('div#infobox').modal({
        keyboard: false
      });

      $.ajax({
        type: "POST",
        data: form_data,
        url: action,
        success: function(r){
          var isSuccess = !!r.success;
          console.log(r);
          if(r.errors){
            $('p.form-control-static').each(function(){
              $(this).text('');
            });
            for(k in r.errors)
              $('input[name='+k+']').next().text(r.errors[k]);
          }

          if(r.url)
            submiting.data('redirectUrl',r.url);

          if(r.error){
            alert.text(r.error).attr('class','alert alert-danger').slideDown();
          }

          if(r.message)
            statusInfo.text(r.message);

          submiting.data('isSuccess',isSuccess);
        },
        error: function(xhr,status_text){
          statusInfo.text("連線錯誤：status_text:"+status_text);
          console.log(xhr);
          submiting.data('isSuccess',false);
        },
        complete:function(data){
          processBar.slideUp();
          controls.slideDown();

          setTimeout(function(){
            if(submiting.data('redirectUrl')){
              if(window.location.href == submiting.data('redirectUrl'))
                location.reload();
              else
                window.location.href = submiting.data('redirectUrl');
            }
            submiting=false;
          },1000);
        }
      });
    }
  });
}

$(document).ready(function(){
  init_info_tabs();
  editable_form();
});
