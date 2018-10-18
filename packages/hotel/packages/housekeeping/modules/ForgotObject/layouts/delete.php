<link href="skins/default/stylesheet.css" rel="stylesheet" type="text/css">
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('delete_title'));?><table cellspacing="0" width="100%">
<table border="0">
	<tr valign="top">
		<td align="left" colspan="4">
			<table cellpadding="15" cellspacing="0" width="99%" border="0" bordercolor="#CCCCCC" class="table-bound">
				<tr>
					<td class="form-title" width="100%">[[.delete_title.]]</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr  valign="top">	
	<td colspan="4"><span style="width:10px;line-height:24px;">&nbsp;</span></td>
	</tr>
	<?php if(Form::$current->is_error())
		{
			echo Form::$current->error_messages();
		}?>
	<form name="DeleteForgotObjectForm" method="post">
	<input type="hidden" name="id" value="[[|id|]]"><input type="hidden" name="cmd" value="delete">
	<tr  valign="middle">		
		<td nowrap align="right"><span style="line-height:24px;"><strong>[[.name.]]</strong></span></td>
			<td  align="center">:</td>
			<td >
				<div class="detail_box">[[|name|]]</div>			</td>
		</tr><tr  valign="middle">
			
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.object_type.]]</strong></span></td>
			<td  align="center">:</td>
			<td >
				<div class="detail_box">[[|object_type|]]</div>			</td>
		</tr>
		<tr  valign="middle">
		  
		  <td nowrap align="right"><strong>[[.quantity.]]</strong></td>
		  <td  align="center">:</td>
		  <td ><div class="detail_box">[[|quantity|]]</div></td>
	  </tr>
		<tr  valign="middle">
		 
		  <td nowrap align="right"><strong>[[.unit.]]</strong></td>
		  <td  align="center">:</td>
		  <td ><div class="detail_box">[[|unit|]]</div></td>
	  </tr>
		<tr  valign="middle">
			
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.time.]]</strong></span></td>
			<td  align="center">:</td>
			<td >
				<div class="detail_box">[[|time|]]</div>			</td>
		</tr><tr  valign="middle">
			
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.last_name.]]</strong></span></td>
			<td  align="center">:</td>
			<td >
				<div class="detail_box">[[|full_name|]]</div>			</td>
		</tr><tr  valign="middle">
			
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.check_in_date.]]</strong></span></td>
			<td  align="center">:</td>
			<td >
				<div class="detail_box">[[|check_in_date|]]</div>			</td>
		</tr><tr  valign="middle">
			
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.check_out_date.]]</strong></span></td>
			<td  align="center">:</td>
			<td >
				<div class="detail_box">[[|check_out_date|]]</div>			</td>
		</tr><tr  valign="middle">
			
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.status.]]</strong></span></td>
			<td  align="center">:</td>
			<td >
				<div class="detail_box">
					<!--IF:check(MAP['status']==0)-->
						[[.notpay.]]
					<!--ELSE-->
						[[.pay.]]
					<!--/IF:check-->
				</div>			</td>
		</tr><tr  valign="middle">
			
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.room_id.]]</strong></span></td>
			<td  align="center">:</td>
			<td  ><div class="detail_box">&nbsp;[[|room_id|]]</div></td></tr>
	<tr><td colspan="3"></td></tr>
	<tr  valign="middle">
		
		<td width="40%" colspan="3" align="center" >
			<b style="color:#FF0000">[[.confirm_question.]]</b>
		</td>
	</tr>
	<tr><td colspan="3"></td></tr>
	<tr >
		<td  colspan="3" style="text-align:center">
				<?php Draw::button(Portal::language('delete'),false,false,true,'DeleteForgotObjectForm');?>
				<?php Draw::button(Portal::language('return_list'),URL::build_current(array(
	      'forgot_object_time_start','forgot_object_time_end',
	)));?>
		</td>
	</tr>
	</form>
	</table>