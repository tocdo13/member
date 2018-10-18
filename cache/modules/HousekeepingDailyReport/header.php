<div class="report-bound">
<div >
<link rel="stylesheet" href="skins/default/report.css">
<script>full_screen();</script>
<table cellpadding="5" cellspacing="0" width="100%">
<tr>
	<td align="center">
		<table cellSpacing=0 width="100%" style="font-family:'Times New Roman', Times, serif">
			<tr valign="top">
				<td align="left" width="65%"><strong><?php echo HOTEL_NAME;?></strong><br /><?php echo HOTEL_ADDRESS;?></td>
				<td align="right" nowrap width="35%"><strong><?php echo Portal::language('template_code');?></strong><br/>
                <?php echo Portal::language('date_print');?>:<?php echo ' '.date('d/m/Y H:i');?>
                <br />
                <?php echo Portal::language('user_print');?>:<?php echo ' '.User::id();?>
                </td>
			</tr>	
		</table>
		<font class="report_title"><?php echo Portal::language('housekeeping_daily_report');?></font><div><?php echo Portal::language('day');?>: <?php echo Url::get('date_from');?></div><br>
		<div style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
		<?php echo URL::get('room_level_id')?'<br />'.Portal::language('room_level').DB::fetch('select name from room_level where id=\''.URL::get('room_level_id').'\'','name'):'';?>
		<?php echo URL::get('customer_id')?'<br />'.Portal::language('customer').DB::fetch('select name from customer where id=\''.URL::get('customer_id').'\'','name'):'';?></div>
