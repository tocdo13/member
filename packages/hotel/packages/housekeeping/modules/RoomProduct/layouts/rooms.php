<link href="skins/default/room.css" rel="stylesheet" type="text/css" />
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('title'));?><div class="body">
	<table cellspacing="0" width="100%">
		<tr valign="top">
			<td align="left" colspan="2">
				<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
					<tr>
						<td class="form-title" width="100%">[[.add_goods_for_room.]]</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<p><input type="button" onclick="location='<?php echo URL::build_current(array('cmd'=>'add'));?>'" value="[[.add_to_all.]]"/><input type="button" onclick="if(confirm('Are you sure to delete all product from all room? (This operation could not be undone!!!)'))location='<?php echo URL::build_current(array('cmd'=>'remove_all'));?>'" value="[[.remove_all.]]"/></p>
	<table border="0" cellpadding="4" cellspacing="0" width="100%" >
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
			?><!--LIST:floors.rooms-->
			<td> 
				<table class="room_arround" style="width:70px; height:70px; background:#DFDFDF; text-align:center"><tr><td>
				<div class="room-bound">
				<a href="<?php echo URL::build_current(array('room_id'=>[[=floors.rooms.id=]]));?>" class="room_RESOVER">
					<span style="font-size:11px;font-weight:normal;[[|floors.rooms.style_sheet|]]">P[[|floors.rooms.name|]]</span>
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