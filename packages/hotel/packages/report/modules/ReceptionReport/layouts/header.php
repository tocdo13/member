<div class="report-bound">
<div>
<!--IF:first_page([[=page_no=]]==1)-->
<link rel="stylesheet" href="skins/default/report.css">

<!--/IF:first_page-->
<table cellpadding="2" cellspacing="0" width="100%">
<tr>
	<td align="center">
		<!--IF:first_page([[=page_no=]]==1)-->
		<table cellSpacing=0 width="100%">
		<tr valign="top" stype="font-size:11px;">
			<td align="left" width="65%">
			<strong><?php echo HOTEL_NAME;?></strong><br />
			ADD: <?php echo HOTEL_ADDRESS;?><BR>
			<td align="right" nowrap width="35%">
			<strong>[[.template_code.]]</strong></td>
		</tr>	
	</table>
		<div class="report_title" style="text-transform:uppercase">BÁO CÁO DOANH THU</div>
		<div style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
		<?php Report::display_date_params();?>		
		<?php if(URL::get('minibar_id')){echo '<br />'.Portal::language('minibar').DB::fetch('select name from minibar where id=\''.URL::get('minibar_id').'\'','name');}?>
		<?php if(URL::get('category_id')){echo '<br />'.Portal::language('category').DB::fetch('select name from product_category where id=\''.URL::get('category_id').'\'','name');}?>
		<?php if(URL::get('product_id')){echo '<br />'.Portal::language('product').DB::fetch('select concat(concat(id,\' - \'),name_'.Portal::language().') as name from product where id=\''.URL::get('category_id').'\'','name');}?>
		<?php if(URL::get('reservation_type_id')){echo '<br />'.Portal::language('reservation_type').': '.DB::fetch('select id,name from reservation_type where id=\''.URL::iget('reservation_type_id').'\'','name');}?>
		</div><br />
		<!--/IF:first_page-->