jQuery(function(e){
  // this
  // define a parameter and save it.. it has to be the value parameter on the user control. And it cannot save to the databae right away. It needs to hang on to the value
  // each time it is clicked..

  jQuery(".hide_this").css("display", "none");

  jQuery("#btn_awp_code").click(function(e){
    var awp_wide = jQuery('.awp_img_select img.layout_selected').parents('.awp_img_select').attr('data-layout'); // parents
    if (awp_wide != 'true'){
      awp_wide = 'false';
    }
  });

  // all of this should apply to both the settings page and the widget itself..

  jQuery('.awp_img_select').click(function(e){  // they will not be clicking it over and over, once it is saved to the database.
    if (jQuery(this).find('img').hasClass('layout_selected')) return;
    jQuery('.awp_settings').find('.awp_img_select img').removeClass('layout_selected');
    jQuery(this).find('img').addClass('layout_selected');
    jQuery('.c_awp_wrapper').toggleClass('c_awp_wide');

    if (jQuery(this).attr("data-layout") != 'true'){
      jQuery('.c_awp_item').css('width','');
    }

    jQuery(window).trigger('resize'); // there is nothing to resize..
  });

  function toggle_layout(layout) {
    jQuery('.cerkl_awp_sample').toggleClass('col-md-8 col-md-12');
    jQuery('.cerkl_awp_preview').toggleClass('col-md-4 col-md-12');
    jQuery(window).trigger('resize');
  }

  function resize_headline() {
    jQuery(".c_awp_headline").each(function(index,elem){
      var text = jQuery(elem).html().replace(/\-\<br\>/g,"").replace(/\<br\>/g," ");
      jQuery(elem).html(trimTitle(text,jQuery(elem)));
    });
  }

  function resize_c_awp_item() {
    if (jQuery('.c_awp_wrapper.c_awp_wide').length > 0 ){
      var width  = jQuery(".c_awp_wide").width();
      if (width <=450) {
        jQuery(".c_awp_wide .c_awp_item").css("width", "100%");
      }else if (width <=800){
        jQuery(".c_awp_wide .c_awp_item").css("width","50%");
      }else if (width <1600){
        jQuery(".c_awp_wide .c_awp_item").css("width","33%");
      }else{
        jQuery(".c_awp_wide .c_awp_item").css("width","25%");
      }
    }
    if (jQuery('.c_awp_wrapper').not('.c_awp_wide').length>0){
      jQuery('.c_awp_item .c_awp_story_img').css("height",function(e){
        return jQuery('.c_awp_wrapper').width() * 0.22;
      });
      jQuery('.c_awp_item').css("width","");
    }else{
      jQuery('.c_awp_item .c_awp_story_img').css("height","");
    }
  }

  function trimTitle(title, element) {
    function len(text){
      var elem = element;
      if(!elem.closest("a").hasClass(".c_awp_hidden")){
        elem = element.closest("a").parent().find("a.c_awp_hidden .c_awp_headline");
      }
      elem.text(text).css({"height": "auto","width": "auto","position": "absolute","visibility": "hidden","white-space": "nowrap", "text-overflow":"none"});
      return elem[0].clientWidth;
    }
    var fitSize = element.width();
    var textSize = len(title);
    if(textSize < fitSize){
      return title;
    } else {
      if(2 * fitSize - 5 > textSize){
        var arr = title.split(" ");
        for(var i = 0; i<arr.length; i++){
          if(len(arr.slice(0,i+1).join(' ')) > fitSize){
            return arr.slice(0,i).join(' ') + "<br/>" + arr.slice(i).join(' ');
          }
        }
        var half = title.length/2;
        return title.slice(0, half) + "-<br/>" + title.slice(half);
      } else {
        var i = 0;
        var line;
        do {
          line = title.split(" ").slice(0,i).join(' ');
          i++;
        } while(len(line) < fitSize);
        if(i - 2 <= 0){
          var doubleLine;
          var j = 0;
          do {
            doubleLine = title.slice(0,j);
            j++;
          } while(len(doubleLine) < 2 * fitSize - 30);
          return title.slice(0,(j/2)-1) + "-<br/>" + title.slice((j/2)-1);
        } else {
          return title.split(" ").slice(0,i - 2).join(' ') + "<br/>" + title.split(" ").slice(i-2).join(' ');
        }
      }
    }
  }
  jQuery(window).resize(function(e) {
    resize_c_awp_item();
    resize_headline();
  });
  jQuery(document).ready(function(e) {  // and document ready...
    resize_c_awp_item();
    resize_headline();
  });
});
