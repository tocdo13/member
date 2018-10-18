<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('delete_title'));?><table cellspacing="0" width="100%">
	<tr valign="top" bgcolor="#FFFFFF">
		<td align="left">
			<table width="100%" cellpadding="15">
				<tr><td nowrap width="100%" class="form-title"><?php echo Portal::language('delete_selected_laundryinvoice_confirm');?>
				</td>
				<td>
					<a target="_blank" href="<?php echo URL::build('help',array('id'=>Module::block_id(),'href'=>'?'.$_SERVER['QUERY_STRING']));?>#delete_selected">
						<img src="skins/default/images/scr_symQuestion.gif"/>
					</a>
				</td>
				<td>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr bgcolor="#EEEEEE" valign="top">
		<td >
			<table bgcolor="#EEEEEE" cellspacing="0" width="100%">
			<tr>
				<td width="100%">
					<table width="100%" cellpadding="5" cellspacing="0" border="1" style="border-collapse:collapse">
					<form name="DeleteSelectedLaundryInvoiceForm" method="post">
						<tr valign="middle">
							<th width="1%"><input type="checkbox" value="1" checked onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"></th>
							<th><?php echo Portal::language('reservation_room_id');?></th>
							<th><?php echo Portal::language('room_name');?></th>
							<th><?php echo Portal::language('total');?></th>
						</tr>
						<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
						<tr bgcolor="white" valign="middle" <?php Draw::hover('#E2F1DF');?>onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current();?>&id=<?php echo $this->map['items']['current']['id'];?>';}else{just_click=false;}" style="cursor:hand;">
							<td><input name="selected_ids[]" type="checkbox" value="<?php echo $this->map['items']['current']['id'];?>" onclick="just_click=true;" checked></td>
							<td><?php echo $this->map['items']['current']['name'];?></td>
							<td><?php echo $this->map['items']['current']['room_name'];?></td>
							<td><?php echo $this->map['items']['current']['total'];?></td>
						</tr>
						<?php }}unset($this->map['items']['current']);} ?>
					</table>
				</td>
			</tr>
			</table>
			<p>
			<table><tr>
				<td><?php Draw::button(Portal::language('delete_selected'),'confirm1',false,true,'DeleteSelectedLaundryInvoiceForm');?></td>
				<td>
					<?php Draw::button(Portal::language('list'),Url::build_current());?></td>
			</tr></table>
			</p>
		</td>
	</tr>
	<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
	</table>