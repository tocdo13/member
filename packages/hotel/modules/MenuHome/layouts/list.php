<div class="home-menu-wrapper">
	<div>
		<table border="1" cellspacing="0" cellpadding="5" width="100%" bordercolor="#CCCCCC">
			<tr class="table-header">
				<td align="center" class="title">[[.select_hotel.]]</td>
			</tr>
			<tr>
			  	<td><div class="hotel-list">
                    
					<!--LIST:hotels-->
						<div><a <?php echo ([[=hotels.id=]]==PORTAL_ID)?'class="selected"':'';?> href="<?php echo Url::build_current(array('selected_portal_id'=>[[=hotels.id=]]));?>">[[|hotels.name|]]</a></div>
                    <!--/LIST:hotels-->					
					</div></td>
		  </tr>
		</table>
	</div>
</div>