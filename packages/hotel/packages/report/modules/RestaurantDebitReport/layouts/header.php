<div class="report-bound" style=" page:land;">
<div >
<link rel="stylesheet" href="skins/default/report.css">
<!--IF:first_page(([[=page_no=]]==0) or ([[=page_no=]]==[[=start_page=]]))-->
<?php echo Form::$current->error_messages();?><?php $input_count = 0;?>
<script>full_screen();</script>
<!--/IF:first_page-->
<table cellpadding="10" cellspacing="0" width="100%">
<tr>
	<td align="center">
		<!--IF:first_page(([[=page_no=]]==0) or ([[=page_no=]]==[[=start_page=]]))-->
		<table cellSpacing=0 width="100%">
			<tr valign="top">
				<td align="left" width="65%">
			<strong><?php echo [[=hotel_name=]];?></strong><br />
			<?php echo [[=hotel_address=]];?></td>
			<td align="right" nowrap width="35%">
			<strong>[[.template_code.]]</strong><br />
			<i>[[.promulgation.]]</i>
            <br />
            [[.date_print.]]:<?php echo date('d/m/Y H:i');?>
            <br />
            [[.user_print.]]:<?php echo User::id();?>
			</td>
			</tr>	
		</table>
		<font class="report_title">[[.restaurant_debit_report.]]</font>
		<div style="font-family:'Times New Roman', Times, serif;font-weight:bold;">[[.from_date.]]: <?php echo Url::get('date_from') ?> [[.to_date.]]: <?php echo Url::get('date_to') ?><br />
		<?php //echo URL::get('bar_id')?Portal::language('bar_id').DB::fetch('select name from bar where id=\''.URL::get('bar_id').'\'','name'):'';?>
		<?php echo Url::get('bar_name') ?>
		<!--/IF:first_page-->