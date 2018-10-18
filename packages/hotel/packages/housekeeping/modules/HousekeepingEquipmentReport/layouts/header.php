<div >
<!--IF:first_page([[=page_no=]]==1)-->
<link rel="stylesheet" href="skins/default/report.css">
<?php Form::$current->error_messages();?><?php $input_count = 0;?>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('title'));?>

<!--/IF:first_page-->
<table cellpadding="10" cellspacing="0" width="100%">
<tr>
	<td align="center">
		<!--IF:first_page([[=page_no=]]==[[=start_page=]] or [[=page_no=]] == 0 )-->
		<table cellSpacing=0 width="100%" style="font-family:'Times New Roman', Times, serif">
			<tr valign="top">
				<td align="left" width="79%">
				<strong><?php echo HOTEL_NAME;?></strong><br />
			[[.address.]]: <?php echo HOTEL_ADDRESS;?><BR>
			Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
			Email: <?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?></td>
		
			  <td width="21%" align="right" nowrap><strong>[[.template_code.]]</strong><br />
              <i>[[.promulgation.]]</i>
              <br />
              [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
            <br />
            [[.user_print.]]:<?php echo ' '.User::id();?>
              </td>
			</tr>	
		</table>
		<div style="line-height:40px;"><font class="report_title">[[.housekeeping_equipment_report.]]</font></div>
		<div style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
		[[.from_date.]]: <?php echo(Url::get('from_date_tan'));?>&nbsp;&nbsp;[[.to_date.]]:<?php echo(Url::get('to_date_tan'));?>
		<br />
		<?php echo URL::get('bar_id')?Portal::language('bar_id').DB::fetch('select name from bar where id=\''.URL::get('bar_id').'\'','name'):'';?></div><br />
		<!--/IF:first_page-->