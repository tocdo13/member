<div >
<!--IF:first_page([[=page_no=]]==[[=start_page=]] or [[=page_no=]] == 0 )-->
<?php System::set_page_title(HOTEL_NAME);?><link rel="stylesheet" href="skins/default/report.css">
<?php Form::$current->error_messages();?><?php $input_count = 0;?><?php System::set_page_title(HOTEL_NAME);?>
<script>full_screen();</script>


<table cellpadding="10" cellspacing="0" width="100%" >
<tr>
	<td align="center">
		<!--IF:first_page([[=page_no=]]==1)-->
		<table cellSpacing=0 width="100%" style="font-family:'Times New Roman', Times, serif">
			<tr valign="top">
				<td align="left" width="60%">
				<strong><?php echo HOTEL_NAME;?></strong>
				</td>
				<td align="right" nowrap>
				<strong>[[.template_code.]]</strong>
                <br />
                [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
                <br />
                [[.user_print.]]:<?php echo ' '.User::id();?>
                </td>
			</tr>
		</table>
		<div style="line-height:40px;"><font class="report_title" style="text-transform:uppercase">[[.forgot_object_list.]]</font></div>
		<div style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
		[[.from.]]&nbsp;[[|from_date|]]&nbsp;[[.to.]]&nbsp;[[|to_date|]]
		<br />
		<?php echo URL::get('name')?Portal::language('name').URL::get('name'):'';?><?php echo URL::get('type')?Portal::language('type').URL::get('type'):'';?><?php echo URL::get('room_id')?Portal::language('room').DB::fetch('select name from room where id='.URL::get('room_id').'','name'):'';?><?php echo URL::get('employee_id')?Portal::language('employee').DB::fetch('select concat(last_name," ",first_name) as name from employee_profile where id="'.URL::get('employee_id').'"','name'):'';?><?php echo URL::get('status')?Portal::language('status').URL::get('status'):'';?></div><br />
		<!--/IF:first_page-->     