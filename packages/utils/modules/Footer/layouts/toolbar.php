<div id="footer_toolbar">
    <ul>
		<li style="background:url(packages/utils/skins/default/images/bg_arrcordion.png) repeat-x 0% 0%;border:1px solid #FF9900;">
			<a target="_blank" href="<?php echo Url::build('home');?>" <?php if(User::can_admin()){?> onclick="switch_display(document.getElementById('[[|button_name|]]'));"<?php }?>>
			<img src="<?php echo HOTEL_BANNER;?>"  height="23" />
			</a>
		</li>
		<!--IF:login(User::is_login())-->
		<li><img src="packages/core/skins/default/images/user.png" width="15" /><a href="<?php echo Url::build('personal')?>"><?php echo Session::get('user_id'); ?></a></li>
		<li>&raquo; </li>
		<li><a href="<?php echo Url::build('select_portal')?>">[[|current_portal|]]</a></li>
		<!--/IF:login-->
		<!--IF:login(User::is_login())-->
		<li style="float:right;"><a href="<?php echo Url::build('sign_out',array()); ?>">[[.sign_out.]]</a></li>
		<!--/IF:login-->
    </ul>
</div>
