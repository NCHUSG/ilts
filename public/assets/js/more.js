
function more(wrapperSelector,callback){
  var root = $(wrapperSelector);
  var sample = root.find('div.more-sample');
  var trigger_get_being = false;

  callback = typeof callback === "undefined" ? function(){} : callback;

  trigger_more = function(){
    if(trigger_get_being)
      return;
    trigger_get_being = true;
    var noMore = false;
    var nothing = true;

    $.ajax({
      url: root.attr('href'),
      success: function(r){
        var panel,attr,section,panel_body;
        var g = r.data;

        if(r.refresh){
          var complete_interval = setInterval(function(){
            if(r.redirect)
              window.location = r.redirect;
            else
              location.reload();
            console.log("reloading...");
            clearInterval(complete_interval);
          },r.refresh);
        }

        for(var k in g){
          nothing = false;
          panel = sample.find('div.panel').clone();
          panel.addClass(g[k].status);
          panel.find('h3.panel-title span.group_name').text(g[k].name);
          panel.find('h3.panel-title span.statusText').text(g[k].statusText);
          panel_body = panel.find('div.panel-body');

          section = $("<div class='row'><div class='col-xs-12 panel-content'></div></div>");
          for(var j in g[k].url){
            attr = sample.find('.btn.'+j).clone();
            attr.attr('href',g[k].url[j]);
            section.find(".panel-content").append(attr);
          }
          panel_body.append(section);
          if(g[k].info){
            panel_body.append("<div class='row'>&nbsp;</div>");
            section = $("<div class='row'><div class='col-xs-12 panel-content'></div></div>");
            for(var j in g[k].info){
              attr = sample.find('div.info.'+j).clone();
              attr.find('.info-content').append(g[k].info[j]);
              section.find(".panel-content").append(attr);
            }
            panel_body.append(section);
          }
          root.find('div.more-container').prepend(panel);
        }

        callback();

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
            if(nothing){
              $('div.more-loadingBar').append("<h4 class='text-center'>沒東西。</h4>");
              $('div.more-loadingBar').slideDown();
            }
          });
          trigger_get_being = true;
        }
        else
          trigger_get_being = false;


      }
    });
  };

  root.find('div.more-loadingBar').appear();
  $(document.body).on('appear', wrapperSelector + ' div.more-loadingBar', trigger_more);
  root.find('div.more-loadingBar').hover(trigger_more);
  return trigger_more;
}
