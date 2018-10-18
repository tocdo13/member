/**
 *  jQuery Windows Engine Plugin
 *  @requires jQuery v1.2.6 or greater
 *  http://hernan.amiune.com/labs
 *
 *  Copyright(c)  Hernan Amiune (hernan.amiune.com)
 *  Licensed under MIT license:
 *  http://www.opensource.org/licenses/mit-license.php
 * 
 *  Version: 1.6
 */
 

var jqWindowsEngineZIndex = 100;

jQuery.extend({

    newWindow: function(options) {

        var lastMouseX = 0;
        var lastMouseY = 0;

        var defaults = {
            id: "",
            title: "",
            width: 480,
            height: 200,
            posx: 50,
            posy: 50,
            content: "",
            onDragBegin: null,
            onDragEnd: null,
            onResizeBegin: null,
            onResizeEnd: null,
            onAjaxContentLoaded: null,
            onWindowClose: null,
            statusBar: true,
            minimizeButton: true,
            maximizeButton: true,
            closeButton: true,
            draggable: true,
            resizeable: true,
            type: "normal",
            modal: false
        };

        var options = jQuery.extend(defaults, options);

        var idAttr = "";
        if (options.id != "") idAttr = 'id="' + options.id + '"';
        $modalBackground = jQuery('<div class="modal-background"></div>');
        $modalBackground.css("z-index","10000");
        $windowContainer = jQuery('<div ' + idAttr + ' class="window-container"></div>');
        $titleBar = jQuery('<div class="window-titleBar"></div>');
        $titleBar.append('<div class="window-titleBar-leftCorner"></div>');
        $titleBarContent = jQuery('<div class="window-titleBar-content">' + options.title + '</div>');
        $titleBar.append($titleBarContent);
        $titleBar.append('<div class="window-titleBar-rightCorner"></div>');
        $windowMinimizeButton = jQuery('<div class="window-minimizeButton"></div>');
        $windowMaximizeButton = jQuery('<div class="window-maximizeButton"></div>');
        $windowCloseButton = jQuery('<div class="window-closeButton"></div>');
        $windowContent = jQuery('<div class="window-content"></div>');
        $windowStatusBar = jQuery('<div class="window-statusBar"></div>');
        $windowResizeIcon = jQuery('<div class="window-resizeIcon"></div>');

        if (options.minimizeButton) $titleBar.append($windowMinimizeButton);
        if (options.maximizeButton) $titleBar.append($windowMaximizeButton);
        if (options.closeButton) $titleBar.append($windowCloseButton);
        if (options.resizeable) $windowStatusBar.append($windowResizeIcon);
        $windowContainer.append($titleBar);
        $windowContent.append(options.content);
        $windowContainer.append($windowContent);
        if (options.statusBar) $windowContainer.append($windowStatusBar);
        
        if(options.type === "iframe"){
            $windowContent.css("overflow","hidden");
        }
        
        var disableSelection = function() {
            document.onselectstart = function() {
                return false;
            };
        }
        
        var enableSelection = function() {
            document.onselectstart = null;
        }
        
        var setFocus = function($obj) {
            if(options.modal){
                $obj.css("z-index", 10001);
            }
            else{
                $obj.css("z-index", jqWindowsEngineZIndex++);
            }
        }

        var resize = function($obj, width, height) {
            width = parseInt(width);
            height = parseInt(height);
            $obj.data("lastWidth", width).data("lastHeight", height);
            $obj.css("width", width + "px").css("height", height + "px");
            if (options.type === "iframe") {
                var dw = 3, dh = 2;
                $obj.find("iframe").css("width", (width-dw) + "px").css("height", (height-dh) + "px");
                
                $obj.find("iframe").each(function(){
                    jQuery("#jquery-window-engine-iframe-cover").css({top:jQuery(this).offset().top, left:jQuery(this).offset().left,
                    width:this.offsetWidth,height:this.offsetHeight,
                    position: "absolute", opacity: "0.0001", zIndex: 10000,
                    background:"#444"});
                });
            }
        }
        
        var move = function($obj, x, y) {
            x = parseInt(x);
            y = parseInt(y);
            $obj.data("lastX", x).data("lastY", y);
            x = x + "px";
            y = y + "px";
            $obj.css("left", x).css("top", y);
            
            if (options.type === "iframe") {
                $obj.find("iframe").each(function(){
                    jQuery("#jquery-window-engine-iframe-cover").css({top:jQuery(this).offset().top, left:jQuery(this).offset().left,
                    width:this.offsetWidth,height:this.offsetHeight,
                    position: "absolute", opacity: "0.0001", zIndex: 10000,
                    background:"#444"});
                });
            }
        }
        
        var dragging = function(e, $obj) {
            if (options.draggable) {
                e = e ? e : window.event;
                var newx = parseInt($obj.css("left")) + (e.clientX - lastMouseX);
                var newy = parseInt($obj.css("top")) + (e.clientY - lastMouseY);
                lastMouseX = e.clientX;
                lastMouseY = e.clientY;

                move($obj, newx, newy);
            }
        };
        
        var resizing = function(e, $obj) {
            e = e ? e : window.event;
            var w = parseInt($obj.css("width"));
            var h = parseInt($obj.css("height"));
            w = w < 100 ? 100 : w;
            h = h < 50 ? 50 : h;
            var neww = w + (e.clientX - lastMouseX);
            var newh = h + (e.clientY - lastMouseY);
            lastMouseX = e.clientX;
            lastMouseY = e.clientY;

            resize($obj, neww, newh);
        };
        
        //Attach a mousedown event to the window's title bar to allow dragging
        $titleBarContent.bind('mousedown', function(e) {
            $obj = jQuery(e.target).parent().parent();
            setFocus($obj);
            disableSelection();
            
            if ($obj.data("state") != "maximized") {
                
                if (options.type === "iframe") {
                    var $tmpDiv = jQuery('<div id="jquery-window-engine-iframe-cover" ></div>');
                    $obj.find("iframe").each(function(){
                        $tmpDiv.css({top:jQuery(this).offset().top, left:jQuery(this).offset().left,
                        width:this.offsetWidth,height:this.offsetHeight,
                        position: "absolute", opacity: "0.0001", zIndex: 10000,
                        background:"#444"});
                        jQuery('body').append($tmpDiv);
                    });
                }
            
                e = e ? e : window.event;
                lastMouseX = e.clientX;
                lastMouseY = e.clientY;

                jQuery(document).bind('mousemove', function(e) {
                    dragging(e, $obj);
                });

                jQuery(document).bind('mouseup', function(e) {
                    if (options.type === "iframe") jQuery("#jquery-window-engine-iframe-cover").remove();
                    if (options.onDragEnd != null) options.onDragEnd();
                    jQuery(document).unbind('mousemove');
                    jQuery(document).unbind('mouseup');
                    enableSelection();
                });

                if (options.onDragBegin != null) options.onDragBegin();
            }else if (options.type === "iframe"){
                jQuery("#jquery-window-engine-iframe-cover").remove();
            }
            
            return false;
        });
        
        //Attach a double click event to the window's title bar to allow maximization
        $titleBarContent.dblclick(function(e) {
            $obj = jQuery(e.target).parent().parent();
            $obj.find(".window-maximizeButton").click();
        });
        
        //Attach a mousedown event to the window's resize icon to allow resizing
        $windowResizeIcon.bind('mousedown', function(e) {
            $obj = jQuery(e.target).parent().parent();
            setFocus($obj);
            disableSelection();
            
            if ($obj.data("state") === "normal") {
            
                if (options.type === "iframe") {
                    var $tmpDiv = jQuery('<div id="jquery-window-engine-iframe-cover" ></div>');
                    $obj.find("iframe").each(function(){
                        $tmpDiv.css({top:jQuery(this).offset().top, left:jQuery(this).offset().left,
                        width:this.offsetWidth,height:this.offsetHeight,
                        position: "absolute", opacity: "0.0001", zIndex: 10000,
                        background:"#444"});
                        jQuery('body').append($tmpDiv);
                    });
                }
            
                e = e ? e : window.event;
                lastMouseX = e.clientX;
                lastMouseY = e.clientY;

                jQuery(document).bind('mousemove', function(e) {
                    resizing(e, $obj);
                });

                jQuery(document).bind('mouseup', function(e) {
                    if (options.type === "iframe") jQuery("#jquery-window-engine-iframe-cover").remove();
                    if (options.onResizeEnd != null) options.onResizeEnd();
                    jQuery(document).unbind('mousemove');
                    jQuery(document).unbind('mouseup');
                    enableSelection();
                });

                if (options.onResizeBegin != null) options.onResizeBegin();
            }
            return false
        });
        
        //Attach a click event to the window's minimize button to allow window minimization
        $windowMinimizeButton.bind('click', function(e) {
            $obj = jQuery(e.target).parent().parent();
            setFocus($obj);
            if ($obj.data("state") === "minimized") {
                $obj.data("state", "normal");
                $obj.css("height", $obj.data("lastHeight"));
                $obj.find(".window-content").slideToggle("slow");
            }
            else if ($obj.data("state") === "normal") {
                $obj.data("state", "minimized");
                $obj.find(".window-content").slideToggle("slow", function() { $obj.css("height", 0); });
            }
            else {
                $obj.find(".window-maximizeButton").click();
            }
        });

        $windowMaximizeButton.bind('click', function(e) {
            $obj = jQuery(e.target).parent().parent();
            setFocus($obj);
            
            ////for iframe browser compatibility
            var dw = 3, dh = 2;
            
            
            if ($obj.data("state") === "minimized") {
                $obj.find(".window-minimizeButton").click();
            }
            else if ($obj.data("state") === "normal") {
                $obj.animate({
                    top: "5px",
                    left: "5px",
                    width: jQuery(window).width() - 15,
                    height: jQuery(window).height() - 45
                }, "slow");
                if (options.type === "iframe") {
                    $obj.find("iframe").animate({
                        top: "5px",
                        left: "5px",
                        width: jQuery(window).width() - 15 - dw,
                        height: jQuery(window).height() - 45 - dh
                    }, "slow");
                }
                $obj.data("state", "maximized")
            }
            else if ($obj.data("state") === "maximized") {
                $obj.animate({
                    top: $obj.data("lastY"),
                    left: $obj.data("lastX"),
                    width: $obj.data("lastWidth"),
                    height: $obj.data("lastHeight")
                }, "slow");
                if (options.type === "iframe") {
                    $obj.find("iframe").animate({
                        top: $obj.data("lastY"),
                        left: $obj.data("lastX"),
                        width: parseInt($obj.data("lastWidth") - dw),
                        height: parseInt($obj.data("lastHeight") - dh)
                    }, "slow");
                }
                $obj.data("state", "normal")
            }

        });
        
        //Attach a click event to the popup's close button to allow closing
        $windowCloseButton.bind('click', function(e) 
        {
            var $window = jQuery(e.target).parent().parent();
            
            $window.fadeOut(function() { 
                /** use-customize **/
                window.FunSearch(1);
                $window.parent().remove();
                //location.reload();
                /** use-customize **/
                });
            
            if (options.onWindowClose != null) 
            {
                options.onWindowClose();
            }
            if(options.modal)
            {
                $modalBackground.remove();
            }
        });
        
        //Attach a click event to the popup's background to allow closing
        $modalBackground.bind('click', function(e) {
            $windowCloseButton.click();
        });

        $windowContent.click(function(e) {
            setFocus(jQuery(e.target).parent());
        });
        $windowStatusBar.click(function(e) {
            setFocus(jQuery(e.target).parent());
        });

        $windowContainer.data("state", "normal");
        //hide the popup to show it later using a visual effect
        $windowContainer.css("display", "none");

        jQuery("#container_"+options.id).append($windowContainer);
        if(options.modal){
            jQuery('body').append($modalBackground);
        }

        $window = $windowContainer;
        if (!options.draggable) $window.children(".window-titleBar").css("cursor", "default");
        setFocus($window);
        
        //move the popup to the initial position
        move($windowContainer, options.posx, options.posy);
        //resize the popup to the initial size
        resize($windowContainer, options.width, options.height);
        
        
        
        //show the popup using the jquery fadeIn visual effect
        $window.fadeIn();


    },

    updateWindowContent: function(id, newContent) {
        jQuery("#" + id + " .window-content").html(newContent);
    },

    updateWindowContentWithAjax: function(id, url, cache) {
        cache = cache === undefined ? true : false;
        jQuery.ajax({
            url: url,
            cache: cache,
            dataType: "html",
            success: function(data) {
                jQuery("#" + id + " .window-content").html(data);
            }
        });
    },

    moveWindow: function(id, x, y) {
        $obj = jQuery("#" + id);
        x = parseInt(x);
        y = parseInt(y);
        $obj.data("lastX", x).data("lastY", y);
        x = x + "px";
        y = y + "px";
        $obj.css("left", x).css("top", y);
    },

    resizeWindow: function(id, width, height) {
        $obj = jQuery("#" + id);
        width = parseInt(width);
        height = parseInt(height);
        $obj.data("lastWidth", width).data("lastHeight", height);
        width = width + "px";
        height = height + "px";
        $obj.css("width", width).css("height", height);
    },

    minimizeWindow: function(id) {
        jQuery("#" + id + " .window-minimizeButton").click();
    },

    maximizeWindow: function(id) {
        jQuery("#" + id + " .window-maximizeButton").click();
    },

    showWindow: function(id) {
        jQuery("#" + id + " .window-closeButton").fadeIn();
    },

    hideWindow: function(id) {
        jQuery("#" + id + " .window-closeButton").fadeOut();
    },

    closeWindow: function(id) {
        jQuery("#" + id + " .window-closeButton").click();
    },

    closeAllWindows: function() {
        jQuery(".window-container .window-closeButton").click();
    }

});
