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

  var submiting;
  $('span.save').click(function(){
    if(!submiting){
      editable_range = $(this).parents('.editable_range');
      submiting = editable_range.find('form');
      var form_data = submiting.serializeArray();
      var action = submiting.attr('action');
      var statusInfo = $('div#infobox h3.text-center');
      var processBar = $('div#infobox #noTrespassingOuterBarG');

      $.ajax({
        type: "POST",
        data: form_data,
        url: action,
        success: function(data){
          if(typeof data == "object"){
            submiting.data('isSuccess',false);
            for(k in data){
              submiting.find('input[name='+k+']').next().text(data[k]);
            }
            statusInfo.text("您填寫的選項有誤，請檢查，謝謝");
          }
          else
            submiting.data('isSuccess',true);
        },
        error: function(xhr,status_text){
          statusInfo.text("資料傳送錯誤："+status_text);
          submiting.data('isSuccess',false);
        },
        complete:function(data){
          if(submiting.data('isSuccess')){
            processBar.slideUp();
            statusInfo.text("成功！");
            var complete_interval = setInterval(function(){
              location.reload();
              console.log("reloading...");
              clearInterval(complete_interval);
            },1000);
          }
          else
            $('div#infobox div.alert').show().attr('class','alert alert-danger').text("點一下旁邊關閉此視窗");

          submiting=false;
        }
      });
    }
    $('div#infobox h4.modal-title').text("修改" + editable_range.find('span.form-title').text() + "...");
    statusInfo.show().text("處理中...");
    $('div#infobox div.alert').hide();
    processBar.show();
    $('div#infobox').modal({
      keyboard: false
    });
  });
}

$(document).ready(function(){
  init_info_tabs();
  editable_form();
});
