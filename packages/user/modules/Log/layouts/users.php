<link href="skins/default/room.css" rel="stylesheet" type="text/css" />
<?php System::set_page_title(Portal::get_setting('company_name','').' '.'[[.title.]]');?><div class="body">
	<font class="form_title"><b>[[.select_minibar.]]</b></font>
	<table border="0" cellpadding="4" cellspacing="0" width="100%" >
		<?php $i=0;?><!--LIST:users-->	
		<?php if($i%5==0){echo '<tr>';}?><td> 
				<table class="room_arround"><tr><td>
				<a href="<?php echo URL::build_current(array('user_id'=>[[=user.id=]]));?>">
					<img src="[[|user.image_url|]]" />
				</a><br />
				<a href="<?php echo URL::build_current(array('user_id'=>[[=user.id=]]));?>">
					[[|user.user_name|]]
				</a>
				</td></tr></table>
			</td>
		<?php
		if($i%5==4){echo '</tr>';}
		$i++;
		?><!--/LIST:users-->	
		<?php if($i%5!=0){echo '</tr>';}?></table>
</div>