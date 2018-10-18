<?php System::set_page_title(HOTEL_NAME);?><link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<form name="telephone_list" method="post">
<table cellspacing="0" width="100%">
	<tr valign="top" bgcolor="#FFFFFF">
		<td align="left">
			<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
				<tr>
				  <td width="100%" class="form-title">
					<font class="form_title"><b><?php echo Portal::language('list_title_telephone_list');?> <?php echo Portal::language('month');?>  <?php echo Url::get('date',date('m',time()));?> <?php echo Portal::language('year');?> <?php echo Url::get('year',date('Y',time()));?></b></font>
				</td>
				<td>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr valign="top">
		<td width="100%">
		<table cellspacing="0" width="100%">
		<tr>
			<td>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
				  	<td align="center" width="60%">
						<fieldset>
						<legend><strong><?php echo Portal::language('search');?></strong></legend>
						  <?php echo Portal::language('phone_number_id');?> : <input  name="phone_number_id" id="phone_number_id" style="width:70px;/ type ="text" value="<?php echo String::html_normalize(URL::get('phone_number_id'));?>">
                          <?php echo Portal::language('room_id');?>&nbsp;<select  name="room_id" id="room_id"><?php
					if(isset($this->map['room_id_list']))
					{
						foreach($this->map['room_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('room_id',isset($this->map['room_id'])?$this->map['room_id']:''))
                    echo "<script>$('room_id').value = \"".addslashes(URL::get('room_id',isset($this->map['room_id'])?$this->map['room_id']:''))."\";</script>";
                    ?>
	</select>
						  <?php echo Portal::language('from_date');?> : <input  name="from_date" id="from_date" style="width:100px" type ="text" value="<?php echo String::html_normalize(URL::get('from_date'));?>">
						  <?php echo Portal::language('to_date');?> : <input  name="to_date" id="to_date" style="width:100px;" type ="text" value="<?php echo String::html_normalize(URL::get('to_date'));?>">					  
						&nbsp;&nbsp;<input name="search" type="submit" id="search"  value="<?php echo Portal::language('search');?>">
						</fieldset>
					</td>
					<td width="40%">
						<fieldset>
						<legend><strong><?php echo Portal::language('update');?></strong></legend>
						<?php echo Portal::language('month');?>:
						<input  name="month" id="month" maxlength="2" size="2" / type ="text" value="<?php echo String::html_normalize(URL::get('month'));?>">
						<?php echo Portal::language('year');?>:
						<input  name="year" id="year" maxlength="4" size="4" / type ="text" value="<?php echo String::html_normalize(URL::get('year'));?>">
						<input name="update" type="submit" id="update"  value="<?php echo Portal::language('update');?>">
                        <?php if(User::is_admin()){?>
                        <input name="empty" type="submit" id="empty"  value="<?php echo Portal::language('empty_data');?>">
                        <?php }?>
						</fieldset>
					</td>
				  </tr>
				</table>

			</td>
		</tr>
		<tr valign=top>
		<td>		
		<div align="right"><?php echo $this->map['paging'];?>	</div>
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td width="100%">
					<table width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="#CCCCCC">
						<tr valign="middle" bgcolor="#EEEEEE" style="line-height:20px">
							<th width="20" title="<?php echo Portal::language('check_all');?>"><?php echo Portal::language('stt');?></th>
							<th width="1%" align="left" nowrap >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='telephone_report_daily.phone_number_id' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'telephone_report_daily.phone_number_id'));?>" title="<?php echo Portal::language('sort');?>">
								<?php if(URL::get('order_by')=='telephone_report_daily.phone_number_id') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('phone_number');?>								</a>							</th>
                            <th width="1%" align="left" nowrap >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='room.name' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'room.name'));?>" title="<?php echo Portal::language('sort');?>">
								<?php if(URL::get('order_by')=='room.name') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('room_name');?>								</a>							</th>
							<th width="154" align="left" nowrap >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='telephone_report_daily.hdate' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'telephone_report_daily.hdate'));?>" title="<?php echo Portal::language('sort');?>">
								<?php if(URL::get('order_by')=='telephone_report_daily.hdate') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('date');?>								</a>							</th>
							<th width="152" align="right" nowrap >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='telephone_report_daily.dial_number' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'telephone_report_daily.dial_number'));?>" title="<?php echo Portal::language('sort');?>">
								<?php if(URL::get('order_by')=='telephone_report_daily.dial_number') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
								<?php echo Portal::language('dial_number');?>								</a>							</th>
							<th width="1%" align="right" nowrap ><a href="<?php echo URL::build_current(((URL::get('order_by')=='telephone_report_daily.ring_durantion' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'telephone_report_daily.ring_durantion'));?>" title="<?php echo Portal::language('sort');?>">
								<?php if(URL::get('order_by')=='telephone_report_daily.ring_durantion') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('ring_duration');?> ('s)</a> 	</th>
							<th width="300" align="right" nowrap ><?php echo Portal::language('description');?></th>
					        <th align="right" nowrap ><?php echo Portal::language('price_vnd');?></th>
					  </tr>
					  <?php $i=1;?>
						<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
						<tr bgcolor="<?php {echo '#FFFFFF';}?>" <?php Draw::hover(Portal::get_setting('crud_item_hover_bgcolor','#EFEFEF'));?> style="cursor:hand;<?php if($i%2){echo 'background-color:#F9F9F9';}?>"  id="TelephoneList_tr_<?php echo $this->map['items']['current']['id'];?>">
							<td><?php echo $i++;?></td>
							<td nowrap align="center"><?php echo $this->map['items']['current']['phone_number_id'];?></td>
                            <td nowrap align="center"><?php echo $this->map['items']['current']['room_name'];?></td>
							<td nowrap align="left"><?php echo date('H:i d/m/Y',$this->map['items']['current']['in_date']);?></td>
				            <td align="right" nowrap><?php if($this->map['items']['current']['dial_number']!=0){echo $this->map['items']['current']['dial_number'];}else{echo Portal::language('incoming');}?></td>
							<td align="right" nowrap><?php echo $this->map['items']['current']['ring_durantion'];?></td>
							<td align="right" nowrap><?php echo $this->map['items']['current']['description'];?></td>
							<td align="right" nowrap><?php echo System::display_number_report($this->map['items']['current']['price_vnd']);?></td>
					  </tr>
						<?php }}unset($this->map['items']['current']);} ?>
					</table>
				</td>
			</tr>
		</table>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr bgcolor="#EFEFEF">
			<td>
				<div style="font-weight:bold;padding-left:10px;height:23px;line-height:23px;"><?php echo Portal::language('total');?> : <?php echo $this->map['total'];?> <?php echo Portal::language('items');?></div>
			</td>
			<td>
				<div><div align="right"><?php echo $this->map['paging'];?></div></div>
			</td>
		  </tr>
		</table>
  <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script type="text/javascript">
	jQuery("#from_date").datepicker();
	jQuery("#to_date").datepicker();	
</script>