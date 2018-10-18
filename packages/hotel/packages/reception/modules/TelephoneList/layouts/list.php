<?php System::set_page_title(HOTEL_NAME);?><link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<form name="telephone_list" method="post">
<table cellspacing="0" width="100%">
	<tr valign="top" bgcolor="#FFFFFF">
		<td align="left">
			<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
				<tr>
				  <td width="100%" class="form-title">
					<font class="form_title"><b>[[.list_title_telephone_list.]] [[.month.]]  <?php echo Url::get('date',date('m',time()));?> [[.year.]] <?php echo Url::get('year',date('Y',time()));?></b></font>
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
						<legend><strong>[[.search.]]</strong></legend>
						  [[.phone_number_id.]] : <input name="phone_number_id" type="text" id="phone_number_id" style="width:70px;/>
                          [[.room_id.]]&nbsp;<select name="room_id" id="room_id"></select>
						  [[.from_date.]] : <input name="from_date" type="text" id="from_date" style="width:100px">
						  [[.to_date.]] : <input name="to_date" type="text" id="to_date" style="width:100px;">					  
						&nbsp;&nbsp;<input name="search" type="submit" id="search"  value="[[.search.]]">
						</fieldset>
					</td>
					<td width="40%">
						<fieldset>
						<legend><strong>[[.update.]]</strong></legend>
						[[.month.]]:
						<input name="month" type="text" id="month" maxlength="2" size="2" />
						[[.year.]]:
						<input name="year" type="text" id="year" maxlength="4" size="4" />
						<input name="update" type="submit" id="update"  value="[[.update.]]">
                        <?php if(User::is_admin()){?>
                        <input name="empty" type="submit" id="empty"  value="[[.empty_data.]]">
                        <?php }?>
						</fieldset>
					</td>
				  </tr>
				</table>

			</td>
		</tr>
		<tr valign=top>
		<td>		
		<div align="right">[[|paging|]]	</div>
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td width="100%">
					<table width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="#CCCCCC">
						<tr valign="middle" bgcolor="#EEEEEE" style="line-height:20px">
							<th width="20" title="[[.check_all.]]">[[.stt.]]</th>
							<th width="1%" align="left" nowrap >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='telephone_report_daily.phone_number_id' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'telephone_report_daily.phone_number_id'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='telephone_report_daily.phone_number_id') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.phone_number.]]								</a>							</th>
                            <th width="1%" align="left" nowrap >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='room.name' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'room.name'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='room.name') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.room_name.]]								</a>							</th>
							<th width="154" align="left" nowrap >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='telephone_report_daily.hdate' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'telephone_report_daily.hdate'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='telephone_report_daily.hdate') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.date.]]								</a>							</th>
							<th width="152" align="right" nowrap >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='telephone_report_daily.dial_number' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'telephone_report_daily.dial_number'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='telephone_report_daily.dial_number') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
								[[.dial_number.]]								</a>							</th>
							<th width="1%" align="right" nowrap ><a href="<?php echo URL::build_current(((URL::get('order_by')=='telephone_report_daily.ring_durantion' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'telephone_report_daily.ring_durantion'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='telephone_report_daily.ring_durantion') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.ring_duration.]] ('s)</a> 	</th>
							<th width="300" align="right" nowrap >[[.description.]]</th>
					        <th align="right" nowrap >[[.price_vnd.]]</th>
					  </tr>
					  <?php $i=1;?>
						<!--LIST:items-->
						<tr bgcolor="<?php {echo '#FFFFFF';}?>" <?php Draw::hover(Portal::get_setting('crud_item_hover_bgcolor','#EFEFEF'));?> style="cursor:hand;<?php if($i%2){echo 'background-color:#F9F9F9';}?>"  id="TelephoneList_tr_[[|items.id|]]">
							<td><?php echo $i++;?></td>
							<td nowrap align="center">[[|items.phone_number_id|]]</td>
                            <td nowrap align="center">[[|items.room_name|]]</td>
							<td nowrap align="left"><?php echo date('H:i d/m/Y',[[=items.in_date=]]);?></td>
				            <td align="right" nowrap><?php if([[=items.dial_number=]]!=0){echo [[=items.dial_number=]];}else{echo Portal::language('incoming');}?></td>
							<td align="right" nowrap>[[|items.ring_durantion|]]</td>
							<td align="right" nowrap>[[|items.description|]]</td>
							<td align="right" nowrap><?php echo System::display_number_report([[=items.price_vnd=]]);?></td>
					  </tr>
						<!--/LIST:items-->
					</table>
				</td>
			</tr>
		</table>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr bgcolor="#EFEFEF">
			<td>
				<div style="font-weight:bold;padding-left:10px;height:23px;line-height:23px;">[[.total.]] : [[|total|]] [[.items.]]</div>
			</td>
			<td>
				<div><div align="right">[[|paging|]]</div></div>
			</td>
		  </tr>
		</table>
  </form>
<script type="text/javascript">
	jQuery("#from_date").datepicker();
	jQuery("#to_date").datepicker();	
</script>