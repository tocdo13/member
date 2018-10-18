<style type="text/css">
.img{
	border:0px solid #FFFFFF;
    _display:none;
}
.sign-in{
	position:absolute;
	right:0px;
	text-align:center;
	top:0px;
	background:#FFFFFF;
	border:1px solid #0033FF;
	width:160px;
	line-height:18px;
}
.sign-in span,a{	
}
</style>
<img src="<?php echo HOTEL_BANNER;?>">
<div class="banner-logo">	
	<div class="sign-in">
		<!--IF:login(User::is_login())-->
			<span>[[.well_come.]] : </span><b> <?php echo Session::get('user_id'); ?></b> | 
			<a href="<?php echo Url::build('sign_out',array()); ?>">[[.sign_out.]]</a>
		<!--ELSE-->
			<a href="<?php echo Url::build('sign_in',array()); ?>">[[.sign_in.]]</a>
		<!--/IF:login-->
	</div>
</div>
<div style="clear:both; height:0px; font-size:0px;"></div>
<div class="clear-both"></div>
<div class="main-menu-bound">
	<!--IF:cond([[=categories=]])-->
	<ul id="main_menu" class="menu">
	<!--LIST:categories-->
		<li class="menumain">
			<!--IF:cond3([[=categories.icon_url=]])-->
			<span style="width:15px;height:12px;_filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='[[|categories.icon_url|]]',sizingMethod='scale');"><img src="[[|categories.icon_url|]]" style="height:12px; width:15px;" class="img"  /></span>
			<!--/IF:cond3-->
			<!--IF:url([[=categories.url=]])-->
			<a class="main-menu" href="[[|categories.url|]]"><?php echo Portal::language()==1?[[=categories.name_1=]]:[[=categories.name_2=]]; ?></a>
			<!--ELSE-->
			<?php echo Portal::language()==1?[[=categories.name_1=]]:[[=categories.name_2=]]; ?>
			<!--/IF:url-->
			<ul>
				<!--[if lte IE 6]><iframe></iframe><![endif]-->
				<!--IF:cond2([[=categories.child=]])-->
					<?php $group = false;?>
					<!--LIST:categories.child-->
						<?php	if($group != [[=categories.child.group_name=]])
								{
									if($group) echo '<li></li>';
									$group = [[=categories.child.group_name=]];
								}
								if(![[=categories.child.url=]]) [[=categories.child.url=]] = 'javascript:void(0)';
						?>
						<li><a href="[[|categories.child.url|]]">
							<!--IF:cond4([[=categories.child.icon_url=]])-->
							<span style="width:15px;height:12px;display:inline-block;_filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='[[|categories.child.icon_url|]]',sizingMethod='scale');"><img src="[[|categories.child.icon_url|]]" style="height:12px; width:15px;" class="img" /></span>
							<!--ELSE-->
							<span style="width:15px;height:12px;display:inline-block;_filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='packages/core/skins/default/images/folder.png',sizingMethod='scale');"><img src="packages/core/skins/default/images/folder.png" style="height:12px; width:15px;" class="img"  /></span>
							<!--/IF:cond4-->
							<?php echo Portal::language()==1?[[=categories.child.name_1=]]:[[=categories.child.name_2=]]; ?>
							</a>
							<!--IF:cond5([[=categories.child.child=]])-->
							<ul>
								<?php $sub_group = false; ?>
								<!--LIST:categories.child.child-->
								<?php	if($sub_group != [[=categories.child.child.group_name=]])
										{
											if($sub_group!="") echo '<li></li>';
											$sub_group = [[=categories.child.child.group_name=]];
										}
								?>
								<li><a href="[[|categories.child.child.url|]]">
									<!--IF:cond6([[=categories.child.child.icon_url=]])-->
									<span style="width:15px;height:12px;display:inline-block;_filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='[[|categories.child.child.icon_url|]]',sizingMethod='scale');"><img src="[[|categories.child.child.icon_url|]]" style="height:12px; width:15px;" class="img" /></span>
									<!--ELSE-->
									<span style="width:15px;height:12px;display:inline-block;_filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='packages/core/skins/default/images/folder.png',sizingMethod='scale');"><img src="packages/core/skins/default/images/folder.png" style="height:12px; width:15px;" class="img"  /></span>
									<!--/IF:cond6-->
									<?php echo Portal::language()==1?[[=categories.child.child.name_1=]]:[[=categories.child.child.name_2=]]; ?>									
								</a></li>
								<!--/LIST:categories.child.child-->
							</ul>
							<!--/IF:cond5-->						
						</li>
					<!--/LIST:categories.child-->
				<!--/IF:cond2-->
			</ul>
		</li>		
	<!--/LIST:categories-->
		<li class="menumain" style="float:right">
			<a onclick="printWebPart('printer');"><img style="height:20px;" src="packages/cms/skins/default/images/printer.png" /></a>
			<a href="<?php echo Url::build('help'); ?>"><img style="height:20px;" src="skins/default/images/help.gif" /></a>
			<ul></ul>
		</li>
	</ul>
	<!--/IF:cond-->
	<div style="clear:both; height:0px; font-size:0px;" ></div>
</div>
<script>
	var options = {minWidth: 120, arrowSrc: 'packages/cms/skins/default/images/arrow_right.gif', onClick: function(e, menuItem){
 		window.location = jQuery(this).children('div').children('a').attr('href');
	}};
	var logo = new Image();
	logo.src = "<?php echo HOTEL_BANNER; ?>";
	if(logo.width > screen.width-21 || 1)
	{
		jQuery('.banner-logo > img').css('width',screen.width-21);
	}
	jQuery('#main_menu').menu(options);
	/*if (jQuery.browser.msie && jQuery.browser.version.substr(0,1)<7) {
		jQuery('.menumain').hover(
			function()
			{
				jQuery('select').each(function(){
					jQuery(this).css('display','none');
				});
			},
			function()
			{
				jQuery('select').each(function(){
					jQuery(this).css('display','');
				});
				jQuery('#root-menu-div ul').hover(
					function()
					{
						jQuery('select').each(function(){
							jQuery(this).css('display','none');
						});
					},
					function()
					{
						jQuery('select').each(function(){
							jQuery(this).css('display','');
						});
					}
				);
			}
		);
	}*/
</script> 
