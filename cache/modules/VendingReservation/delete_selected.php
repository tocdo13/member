<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('delete_title'));?>
<table cellpadding="15" cellspacing="0" width="980">
	<tr valign="top">
		<td align="left" class="form-title"><?php echo Portal::language('delete_selected_confirm');?></td>
	</tr>
</table>
<table cellspacing="0" width="100%">
	<tr bgcolor="#EEEEEE" valign="top">
		<td width="100%">
			<table bgcolor="#EEEEEE" cellspacing="0" width="100%">
			<tr>
				<td width="100%">
					<table width="100%" cellpadding="5" cellspacing="0" bordercolor="#8BB4A4" border="1" style="border-collapse:collapse">
					<form name="DeleteSelectedBarReservationNewForm" method="post">
						<input type="hidden" name="confirm" value="1"/>
						<tr valign="middle">
							<th width="1%"><input type="checkbox" value="1" checked onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"/></th>
							<th align="left"><?php echo Portal::language('reservation_time');?></th>
							<th align="left"><?php echo Portal::language('code');?></th>
						</tr>
						<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
						<tr bgcolor="white" <?php Draw::hover('#E2F1DF');?>onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current();?>&id=<?php echo $this->map['items']['current']['id'];?>';}else{just_click=false;}" style="cursor:pointer;">
							<td>
                                <input name="selected_ids[]" type="checkbox" value="<?php echo $this->map['items']['current']['id'];?>" onclick="just_click=true;" checked="checked"/>
                            </td>
							<td align="left"><?php echo $this->map['items']['current']['arrival_date'];?></td>
							<td align="left"><?php echo $this->map['items']['current']['code'];?></td>
						</tr>
						<?php }}unset($this->map['items']['current']);} ?>
					</table>
				</td>
			</tr>
			</table>
			<p>
			<table><tr>
				<td><?php Draw::button(Portal::language('delete_selected'),'',false,true,'DeleteSelectedBarReservationNewForm');?></td>
				<td><?php Draw::button(Portal::language('list'),Url::build_current());?></td>
                <input type="hidden" name="confirm" value="1" id="confirm" />
			</tr></table>
			</p>
		</td>
	</tr>
	<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
	</table>	