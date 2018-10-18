/**
 *  jQuery Windows Engine Plugin
 *  @requires jQuery v1.2.6 or greater
 *  http://hernan.amiune.com/labs
 *
 *  Copyright(c)  Hernan Amiune (hernan.amiune.com)
 *  Licensed under MIT license:
 *  http://www.opensource.org/licenses/mit-license.php
 *
 *  Version: 1.1
 */
var jqWindowsEngineZIndex = 10001;
jQuery.extend({
newWindow: function(options){
    var lastMouseX = 0;
    var lastMouseY = 0;
    var defaults = {
        id: "",
        title: "",
        width: 300,
        height: 200,
        posx: 50,
        posy: 50,
        content: "",
		onDragBegin: null,
		onDragEnd: null,
		onResizeBegin: null,
		onResizeEnd: null,
		onWindowClose: null, //Giap comment
		onAjaxContentLoaded: null,
        statusBar: true,
		minimizeButton: true,
		maximizeButton: true,
		closeButton: true, //show close
		draggable: true,
		resizeable: true
    };
    var options = jQuery.extend(defaults, options);
	var idAttr = "";
	if(options.id != "")idAttr = 'id="'+options.id+'"';
    if(options.id != "")idAttr = 'id="'+options.id+'"';
    jQuerywindowContainer = jQuery('<div '+idAttr+' class="window-container"></div>');
    jQuerytitleBar = jQuery('<div class="window-titleBar"></div>');
    jQuerytitleBar.append('<div class="window-titleBar-leftCorner"></div>');
    jQuerytitleBarContent = jQuery('<div class="window-titleBar-content">'+options.title+'</div>');
    jQuerytitleBar.append(jQuerytitleBarContent);
    jQuerytitleBar.append('<div class="window-titleBar-rightCorner"></div>');
    jQuerywindowMinimizeButton = jQuery('<div class="window-minimizeButton"></div>');
	jQuerywindowMaximizeButton = jQuery('<div class="window-maximizeButton"></div>');
	jQuerywindowCloseButton = jQuery('<div class="window-closeButton" id="window-closeButton-test" onclick="close_window_fun();"></div>');
    jQuerywindowContent = jQuery('<div class="window-content"></div>');
    jQuerywindowStatusBar = jQuery('<div class="window-statusBar"></div>');
	jQuerywindowResizeIcon = jQuery('<div class="window-resizeIcon"></div>');
	if(options.minimizeButton) jQuerytitleBar.append(jQuerywindowMinimizeButton);
	if(options.maximizeButton) jQuerytitleBar.append(jQuerywindowMaximizeButton);
	if(options.closeButton) 
        jQuerytitleBar.append(jQuerywindowCloseButton);
	if(options.resizeable) jQuerywindowStatusBar.append(jQuerywindowResizeIcon);
	jQuerywindowContainer.append(jQuerytitleBar);
    jQuerywindowContent.append(options.content);
	jQuerywindowContainer.append(jQuerywindowContent);
	if(options.statusBar) jQuerywindowContainer.append(jQuerywindowStatusBar);
	var setFocus = function(jQueryobj){
	    jQueryobj.css("z-index",jqWindowsEngineZIndex++);
	}
	var resize = function(jQueryobj, width, height){
		width = parseInt(width);
		height = parseInt(height);
		jQueryobj.data("lastWidth",width).data("lastHeight",height);
		width = width+"px";
		height = height+"px";
		jQueryobj.css("width", width).css("height", height);
	}
	var move = function(jQueryobj, x, y){
		x = parseInt(x);
		y = parseInt(y);
		jQueryobj.data("lastX",x).data("lastY",y);
        x = x+"px";
		y = y+"px";
		jQueryobj.css("left", x).css("top", y);
	}
	var dragging = function(e, jQueryobj){
	    if(options.draggable){
            e = e ? e : window.event;
            var newx = parseInt(jQueryobj.css("left")) + (e.clientX - lastMouseX);
            var newy = parseInt(jQueryobj.css("top")) + (e.clientY - lastMouseY);
            lastMouseX = e.clientX;
            lastMouseY = e.clientY;
            move(jQueryobj,newx,newy);
		}
	};
	var resizing = function(e, jQueryobj){
        e = e ? e : window.event;
        var w = parseInt(jQueryobj.css("width"));
        var h = parseInt(jQueryobj.css("height"));
        w = w<100 ? 100 : w;
        h = h<50 ? 50 : h;
        var neww = w + (e.clientX - lastMouseX);
        var newh = h + (e.clientY - lastMouseY);
        lastMouseX = e.clientX;
        lastMouseY = e.clientY;
        resize(jQueryobj, neww, newh);
	};
	jQuerytitleBarContent.bind('mousedown', function(e){
	    jQueryobj = jQuery(e.target).parent().parent();
		setFocus(jQueryobj);
	    if(jQueryobj.data("state") != "maximized"){
	        e = e ? e : window.event;
		    lastMouseX = e.clientX;
		    lastMouseY = e.clientY;
		    jQuery(document).bind('mousemove', function(e){
			    dragging(e, jQueryobj);
		    });
		    jQuery(document).bind('mouseup', function(e){
				if(options.onDragEnd != null)options.onDragEnd();
				jQuery(document).unbind('mousemove');
				jQuery(document).unbind('mouseup');
		    });
			if(options.onDragBegin != null)options.onDragBegin();
	    }
    });
	jQuerywindowResizeIcon.bind('mousedown', function(e){
		jQueryobj = jQuery(e.target).parent().parent();
		setFocus(jQueryobj);
		if(jQueryobj.data("state") === "normal"){
			e = e ? e : window.event;
			lastMouseX = e.clientX;
			lastMouseY = e.clientY;
			jQuery(document).bind('mousemove', function(e){
				resizing(e, jQueryobj);
			});
			jQuery(document).bind('mouseup', function(e){
				if(options.onResizeEnd != null)options.onResizeEnd();
				jQuery(document).unbind('mousemove');
				jQuery(document).unbind('mouseup');
			});
			if(options.onResizeBegin != null)options.onResizeBegin();
		}
    });
	jQuerywindowMinimizeButton.bind('click', function(e){
	    jQueryobj = jQuery(e.target).parent().parent();
		setFocus(jQueryobj);
		if(jQueryobj.data("state") === "minimized"){
            jQueryobj.data("state", "normal");
            jQueryobj.css("height", jQueryobj.data("lastHeight"));
            jQueryobj.find(".window-content").slideToggle("slow");
		}
        else if(jQueryobj.data("state") === "normal"){
            jQueryobj.data("state", "minimized");
            jQueryobj.find(".window-content").slideToggle("slow", function(){jQueryobj.css("height", 0);});
        }
        else{
            jQueryobj.find(".window-maximizeButton").click();
        }
    });
	jQuerywindowMaximizeButton.bind('click', function(e){
        jQueryobj = jQuery(e.target).parent().parent();
        setFocus(jQueryobj);
        if(jQueryobj.data("state") === "minimized"){
            jQueryobj.find(".window-minimizeButton").click();
        }
        else if(jQueryobj.data("state") === "normal"){
            jQueryobj.animate({
                top: "5px",
                left: "5px",
                width: jQuery(window).width()-15,
                height: jQuery(window).height()-45
            },"slow");
            jQueryobj.data("state","maximized")
        }
        else if(jQueryobj.data("state") === "maximized"){
            jQueryobj.animate({
                top: jQueryobj.data("lastY"),
                left: jQueryobj.data("lastX"),
                width: jQueryobj.data("lastWidth"),
                height: jQueryobj.data("lastHeight")
            },"slow");
            jQueryobj.data("state","normal")
        }
    });
    /* M?nh b? event close click
	jQuerywindowCloseButton.bind('click', function(e){
	  var jQuerywindow = jQuery(e.target).parent().parent();
      //Giap comment
	  if(jQuerywindow.parent() && 0)
	  {
	     // alert(location);
		  location.reload();
         // console.log('aaaaa');
          
	  }
      else
      {
           //console.log('bbbb');
      }
      jQuerywindow.fadeOut(function(){ jQuerywindow.remove(); });
	  if(options.onWindowClose != null)
      {
         options.onWindowClose();
         //Giap ð? t?ng ghé qua ð
         console.log('bbbb'); 
      }
    });
    */
	jQuerywindowContent.click(function(e){
      setFocus(jQuery(e.target).parent());
    });
	jQuerywindowStatusBar.click(function(e){
      setFocus(jQuery(e.target).parent());
    });
	move(jQuerywindowContainer,options.posx,options.posy);
	resize(jQuerywindowContainer,options.width,options.height);
	jQuerywindowContainer.data("state","normal");
	jQuerywindowContainer.css("display","none");
    jQuery('body').append(jQuerywindowContainer);
    jQuerywindow = jQuerywindowContainer;
    if(!options.draggable) jQuerywindow.children(".window-titleBar").css("cursor","default");
    setFocus(jQuerywindow);
    jQuerywindow.fadeIn();
},
updateWindowContent: function(id, newContent){
    jQuery("#" + id + " .window-content").html(newContent);
},
updateWindowContentWithAjax: function(id, url, cache){
    cache = cache===undefined ? true : false;
    jQuery.ajax({
        url: url,
        cache: cache,
        dataType: "html",
        success: function(data) {
            jQuery("#" + id + " .window-content").html(data);
        }
    });
},
updateWindowContentWithUrl: function(id, url){
	jQuery("#" + id + " .window-content").html('');
	jQuery("#" + id + " .window-content").html('<iframe src ="'+url+'" width="100%" height="100%" frameborder="0"></iframe>');
},
moveWindow: function(id, x, y){
    jQueryobj = jQuery("#" + id);
    x = parseInt(x);
    y = parseInt(y);
    jQueryobj.data("lastX",x).data("lastY",y);
    x = x+"px";
    y = y+"px";
    jQueryobj.css("left", x).css("top", y);
},
resizeWindow: function(id, width, height){
    jQueryobj = jQuery("#" + id);
    width = parseInt(width);
    height = parseInt(height);
    jQueryobj.data("lastWidth",width).data("lastHeight",height);
    width = width+"px";
    height = height+"px";
    jQueryobj.css("width", width).css("height", height);
},
minimizeWindow: function(id){
    jQuery("#" + id + " .window-minimizeButton").click();
},
maximizeWindow: function(id){
    jQuery("#" + id + " .window-maximizeButton").click();
},
/* M?nh b? event close click
//Giap comment
showWindow: function(id){
    jQuery("#" + id + " .window-closeButton").fadeIn();
},
hideWindow: function(id){
	//alert(2);
    jQuery("#" + id + " .window-closeButton").fadeOut();
},
closeWindow: function(id){
	window.parent.location.reload();
    jQuery("#" + id + " .window-closeButton").click();
}, 
closeAllWindows: function(){
	//alert(3);
    jQuery(".window-container .window-closeButton").click();
} 
*/
//giap.luunguyen add

});
function close_window_fun(){
    var at_path = window.parent.location.toString();
    if(at_path.indexOf('&adddd_guest=yes')>-1)
    {
        var ts_path = at_path.replace('&adddd_guest=yes','');
        window.parent.location.replace(ts_path);
    }
    else
    {
          location.reload();
          jQuery(".window-container").fadeOut();
    }
}
