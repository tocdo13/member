<link href="skins/default/room.css" rel="stylesheet" type="text/css" />
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('title'));?><div class="body">
	
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="65%" class="form-title">[[.room_limited.]]</td>
			<td>
				<input type="button" onclick="location='<?php echo URL::build_current(array('cmd'=>'add'));?>'" value="[[.add_to_all.]]"/>
				<input type="button" onclick="if(confirm('Are you sure to delete all product from all room? (This operation could not be undone!!!)'))location='<?php echo URL::build_current(array('cmd'=>'remove_all'));?>'" value="[[.remove_all.]]"/>
			</td>
        </tr>
    </table>
	<table border="0" cellpadding="4" cellspacing="0" width="100%" bgcolor="#ECE9D8" >
		<!--LIST:floors-->		
		<tr>	
			<td width="60px" nowrap><b>[[.floor.]] &nbsp; [[|floors.name|]]</b></td>
			<?php
				$first_room = reset([[=floors.rooms=]]);
				$first_room_name = $first_room['name'];
				if($first_room_name{strlen($first_room_name)-1}!='1')
				{
					echo '<td>&nbsp;</td>';
				}
			?>
			<!--LIST:floors.rooms-->
			<td> 
				<table class="room_arround"><tr><td>
				<div class="room-bound">
				<a href="<?php echo URL::build_current(array('room_id'=>[[=floors.rooms.id=]]));?>" class="room_RESOVER">
					<span style="font-size:9px;font-weight:normal;[[|floors.minibars.style_sheet|]]">Room [[|floors.rooms.room_name|]]</span>
					<br />
					<!--[[| floors.rooms.id |]]-->
					<br>
				</a>
				</div>
				</td></tr></table>
			</td>
			<!--/LIST:floors.rooms-->
			<td>&nbsp;</td>
		</tr>		
		<!--/LIST:floors-->	
	</table>
</div>