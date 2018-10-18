/**
 * .disableTextSelect - Disable Text Select Plugin
 *
 * Version: 1.1
 * Updated: 2007-11-28
 *
 * Used to stop users from selecting text
 *
 * Copyright (c) 2007 James Dempster (letssurf@gmail.com, http://www.jdempster.com/category/jquery/disabletextselect/)
 *
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 **/

/**
 * Requirements:
 * - jQuery (John Resig, http://www.jquery.com/)
 **/
(function(jQuery){if(jQuery.browser.mozilla){jQuery.fn.disableTextSelect=function(){return this.each(function(){jQuery(this).css({"MozUserSelect":"none"})})};jQuery.fn.enableTextSelect=function(){return this.each(function(){jQuery(this).css({"MozUserSelect":""})})}}else{if(jQuery.browser.msie){jQuery.fn.disableTextSelect=function(){return this.each(function(){jQuery(this).bind("selectstart.disableTextSelect",function(){return false})})};jQuery.fn.enableTextSelect=function(){return this.each(function(){jQuery(this).unbind("selectstart.disableTextSelect")})}}else{jQuery.fn.disableTextSelect=function(){return this.each(function(){jQuery(this).bind("mousedown.disableTextSelect",function(){return false})})};jQuery.fn.enableTextSelect=function(){return this.each(function(){jQuery(this).unbind("mousedown.disableTextSelect")})}}}})(jQuery)