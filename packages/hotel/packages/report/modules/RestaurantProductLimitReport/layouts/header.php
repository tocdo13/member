<!--IF:first_page([[=page_no=]]==1)-->
<div >
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('Restaurant_product_limit_report'));?>
<link rel="stylesheet" href="skins/default/report.css">
<?php echo Form::$current->error_messages();?><?php $input_count = 0;?>
<script>full_screen();</script>
<!--/IF:first_page-->
<table cellpadding="10" cellspacing="0" width="100%">
<tr>
	<td align="center">
		<!--IF:first_page([[=page_no=]]==1)-->
		<table cellSpacing=0 width="100%" style="font-family:'Times New Roman', Times, serif">
			<tr valign="top">
				<td align="left">
				<strong><?php echo HOTEL_NAME;?></strong><br />
			ADD: <?php echo HOTEL_ADDRESS;?><BR>
			Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
			Email: <?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?>
				</td>
				<td align="right" nowrap>
				<strong>[[.template_code.]]</strong><br />
			<i>[[.promulgation.]]</i>
				</td>
			</tr>	
		</table>
		<div style="line-height:40px;"><font class="report_title">[[.Restaurant_product_limit_report.]]</font></div>
		<div style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
		[[.from.]]&nbsp;[[|from_day|]]/[[|from_month|]]/[[|from_year|]]&nbsp;[[.to.]]&nbsp;[[|to_day|]]/[[|to_month|]]/[[|to_year|]]
		<br />
		<?php echo URL::get('bar_id')?Portal::language('bar_id').DB::fetch('select name from bar where id=\''.URL::get('bar_id').'\'','name'):'';?></div><br />
		<!--/IF:first_page-->
