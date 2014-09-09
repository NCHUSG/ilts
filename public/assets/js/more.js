
function more(wrapperSelector){
  var root = $(wrapperSelector);
  var sample = root.find('div.more-sample');
  var trigger_get_being = false;

  trigger_more = function(){
    if(trigger_get_being)
      return;
    trigger_get_being = true;
    var noMore = false;

    $.ajax({
      url: root.attr('href'),
      success: function(r){
        var panel,button;
        var g = r.groups;

        for(k in g){
          panel = sample.find('div.panel').clone();
          panel.addClass(g[k].status);
          panel.find('h3.panel-title span.group_name').text(g[k].name)
          panel.find('h3.panel-title span.statusText').text(g[k].statusText)
          for(j in g[k].url){
            button = sample.find('a.btn.'+j).clone();
            button.attr('href',g[k].url[j]);
            panel.find('div.panel-body').append(button);
          }
          root.find('div.more-container').prepend(panel);
        }

        if(!r.more)
          noMore = true;

        root.attr('href',r.nextUrl);
      },
      error: function(xhr,status_text){
        console.log(xhr);
        alert("很抱歉！ 連線出了點問題...\n" +status_text);
      },
      complete:function(){
        if (noMore){
          $('div.more-loadingBar').slideUp(function(){
            $('div.more-loadingBar').empty();
            $('div.more-loadingBar').append("<h4 class='text-center'>沒有更多了...</h4>");
            $('div.more-loadingBar').slideDown();
          });
          trigger_get_being = true;
        }
        else
          trigger_get_being = false;


      }
    });
  }

  root.find('div.more-loadingBar').appear();
  $(document.body).on('appear', wrapperSelector + ' div.more-loadingBar', trigger_more);
  root.find('div.more-loadingBar').hover(trigger_more);
  return trigger_more;
}
