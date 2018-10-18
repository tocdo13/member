<div class="hotel-banner">
<div class="main-menu-bound">
	<!--IF:cond([[=categories=]])-->
    <ul id="main_menu" class="menu">
    	<!--LIST:categories-->
        <li class="menumain">
        	<img src="[[|categories.icon_url|]]" class="img" onerror="this.src='packages/core/skins/default/images/folder.png'">
			<?php echo Portal::language()==1?[[=categories.name_1=]]:[[=categories.name_2=]]; ?>
          	<ul>
            <!--[if lte IE 6]><iframe></iframe><![endif]-->	<!--IF:cond2([[=categories.child=]])--><?php $group = false;?>
            <!--LIST:categories.child-->
			<?php	// if($group != [[=categories.child.group_name=]]){if($group) echo '<li></li>';$group = [[=categories.child.group_name=]];}
			 //if(![[=categories.child.url=]]) [[=categories.child.url=]] = 'javascript:void(0)';?>
            	<li>
                	<a href="[[|categories.child.url|]]"><img src="[[|categories.child.icon_url|]]" class="img" onerror="this.src='packages/core/skins/default/images/folder.png'"><?php echo Portal::language()==1?[[=categories.child.name_1=]]:[[=categories.child.name_2=]]; ?></a>
                    <!--IF:cond5([[=categories.child.child=]])-->
                    <ul><?php //$sub_group = false; ?><!--LIST:categories.child.child-->
					<?php	//if($sub_group != [[=categories.child.child.group_name=]]){if($sub_group!="")/* echo '<li></li>'*/;$sub_group = [[=categories.child.child.group_name=]];}?>	
                    	<li>
                         	<a href="[[|categories.child.child.url|]]">
                            	<img src="packages/core/skins/default/images/folder.png" class="img">
								<?php echo Portal::language()==1?[[=categories.child.child.name_1=]]:[[=categories.child.child.name_2=]]; ?></a>
                        </li>
            <!--/LIST:categories.child.child-->
            </ul><!--/IF:cond5-->	
       </li><!--/LIST:categories.child-->	<!--/IF:cond2--></ul></li>	<!--/LIST:categories-->
       <li class="menumain" style="float:right">
       <a href="<?php echo Url::build('change_language',array('href','language_id'=>((Portal::language()==1)?2:1)));?>" title="[[.change_language.]]" style="font-size:11px;">
       <!--IF:l_cond(Portal::language()==1)-->EN<!--ELSE-->VN<!--/IF:l_cond--> |</a> 
       <a onclick="printWebPart('printer');" title="[[.print.]]"><img src="packages/core/skins/default/images/printer.png" height="20"></a>
       <ul></ul></li></ul><!--/IF:cond--><div style="clear:both; height:0px; font-size:0px;" ></div></div></div>
	   <script>var options = {minWidth: 120, arrowSrc: 'packages/cms/skins/default/images/arrow_right.gif', onClick: function(e, menuItem){ window.location = jQuery(this).children('div').children('a').attr('href');}};var logo = new Image();logo.src = "<?php echo HOTEL_BANNER; ?>";if(logo.width > screen.width-21 || 1){jQuery('.banner-logo > img').css('width',screen.width-21);}jQuery('#main_menu').menu(options);/*if (jQuery.browser.msie && jQuery.browser.version.substr(0,1)<7) {jQuery('.menumain').hover(function(){	jQuery('select').each(function(){jQuery(this).css('display','none');	});},function(){	jQuery('select').each(function(){jQuery(this).css('display','');	});	jQuery('#root-menu-div ul').hover(function(){	jQuery('select').each(function(){jQuery(this).css('display','none');	});},function(){	jQuery('select').each(function(){jQuery(this).css('display','');	});}	);});}*/</script>