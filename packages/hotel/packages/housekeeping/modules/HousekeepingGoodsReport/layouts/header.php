<div >
<link rel="stylesheet" href="skins/default/report.css">

<script>full_screen();</script>
<table cellpadding="10" cellspacing="0" width="100%">
<tr>
	<td align="center">
		<table cellSpacing=0 width="100%" style="font-family:'Times New Roman', Times, serif">
			<tr valign="top">
			<td align="left" width="65%">
			<strong><?php echo HOTEL_NAME;?></strong><br />
			<?php echo HOTEL_ADDRESS;?></td>
			<td align="right" nowrap width="35%">
			<!--<strong>[[.template_code.]]</strong><br />-->
			<!--<i>[[ .promulgation. ]]</i>-->
			</td>
			</tr>
		</table>
		<p>
		<div style="line-height:40px;"><font class="report_title" style="text-transform:uppercase">[[.housekeeping_used_good_report.]]</font></div>
		<div style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
		
		<?php Report::display_date_params();?><?php echo URL::get('product_id')?'<br />'.Portal::language('product').' : '.DB::fetch('select name_'.Portal::language().' as name from hk_product where id=\''.URL::get('product_id').'\'','name'):'';?></div>
		</p>
