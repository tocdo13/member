<link href="skins/default/stylesheet.css" rel="stylesheet" type="text/css">
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('detail_title'));?><table cellspacing="0" width="100%">
	<tr valign="top">
		<td>&nbsp;</td>
		<td align="left" colspan="2"><font class="form_title">
			<table width="100%" cellspacing="0">
				<tr><td nowrap width="100%">
					<font class="form_title"><b>[[.detail_title.]]</b></font>
				</td>
				<td>
					<a target="_blank" href="<?php echo URL::build('help',array('id'=>Module::block_id(),'href'=>'?'.$_SERVER['QUERY_STRING']));?>#detail">
						<img src="skins/default/images/scr_symQuestion.gif"/>					</a>				</td>
				<td>&nbsp;</td>
				</tr>
			</table>		</td>
	</tr>
		<tr  valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.name.]]</strong></span></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;">:</div></td>
			<td >
				<div class="detail_box">[[|name|]]</div>			</td>
		</tr><tr  valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.object_type.]]</strong></span></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;">:</div></td>
			<td >
				<div class="detail_box">[[|object_type|]]</div>			</td>
		</tr>
		<tr  valign="top">
		  <td nowrap align="right"><strong>[[.quantity.]]</strong></td>
		  <td bgcolor="#ECE9D8" align="center">&nbsp;</td>
		  <td ><div class="detail_box">[[|quantity|]]</div>	</td>
	  </tr>
		<tr  valign="top">
		  <td nowrap align="right"><strong>[[.unit.]]</strong></td>
		  <td bgcolor="#ECE9D8" align="center">&nbsp;</td>
		  <td ><div class="detail_box">[[|unit|]]</div>	</td>
	  </tr>
		<tr  valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.time.]]</strong></span></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;">:</div></td>
			<td >
				<div class="detail_box">[[|time|]]</div>			</td>
		</tr><tr  valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.last_name.]]</strong></span></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;">:</div></td>
			<td >
				<div class="detail_box">[[|full_name|]]</div>			</td>
		</tr><tr  valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.check_in_date.]]</strong></span></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;">:</div></td>
			<td >
				<div class="detail_box">[[|check_in_date|]]</div>			</td>
		</tr><tr  valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.check_out_date.]]</strong></span></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;">:</div></td>
			<td >
				<div class="detail_box">[[|check_out_date|]]</div>			</td>
		</tr><tr  valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.status.]]</strong></span></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;">:</div></td>
			<td >
				<div class="detail_box">
					<!--IF:check(MAP['status']==0)-->
						[[.notpay.]]
					<!--ELSE-->
						[[.pay.]]
					<!--/IF:check-->
				</div>			</td>
		</tr><tr  valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.country_id.]]</strong></span></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;">:</div></td>
			<td bgcolor="#EFEFEE" width="100%"><div class="detail_box">&nbsp;[[|country|]]</div></td>
		</tr><tr  valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.room_id.]]</strong></span></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;">:</div></td>
			<td bgcolor="#EFEFEE" width="100%"><div class="detail_box">&nbsp;[[|room_id|]]</div></td>
		</tr><tr  valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.employee_id.]]</strong></span></td>
			<td bgcolor="#ECE9D8" align="center"><div style="width:10px;">:</div></td>
			<td bgcolor="#EFEFEE" width="100%"><div class="detail_box">&nbsp;[[|employee_id|]]</div></td>
		</tr>
	<tr  valign="top">
		<td>&nbsp;</td>
		<td >&nbsp;</td>
		<td >
			<table cellpadding=5>
			<tr><td>
			<?php Draw::button(Portal::language('list'),URL::build_current(array(   
	      'forgot_object_time_start','forgot_object_time_end',  
	)));?></td>
			<?php if(User::can_edit(false,ANY_CATEGORY))
			{
			?><td>
			<?php Draw::button(Portal::language('edit'),URL::build_current(array(   
	      'forgot_object_time_start','forgot_object_time_end',  
	)+array('cmd'=>'edit','id'=>$_REQUEST['id'])));?></td>
			<?php
			}
			if(User::can_delete(false,ANY_CATEGORY))
			{
			?><td>
			<?php Draw::button(Portal::language('delete'),URL::build_current(array(   
	      'forgot_object_time_start','forgot_object_time_end',  
	)+array('cmd'=>'delete','id'=>$_REQUEST['id'])));?></td>
			<?php
			}
			?></tr>
			</table>
		</p>		</td>
	</tr>
	</table>