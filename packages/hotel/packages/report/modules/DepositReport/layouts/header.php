<div class="report-bound">
<div>
<!--IF:first_page([[=page_no=]]==1)-->
<link rel="stylesheet" href="skins/default/report.css">

<!--/IF:first_page-->
<table cellpadding="2" cellspacing="0" width="100%">
<tr>
	<td align="center">
		<!--IF:first_page([[=page_no=]]==1 || [[=page_no=]]==0)-->
		<table cellSpacing=0 width="100%">
		<tr valign="top">
			<td align="left" width="65%">
			<strong><?php echo HOTEL_NAME;?></strong><br />
			Địa chỉ: <?php echo HOTEL_ADDRESS;?><BR>
			Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
			Email: <?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?></td>
			<td align="right" nowrap width="35%">
			<strong>[[.template_code.]]</strong>
            
            <br />
            [[.date_print.]]:<?php echo date('d/m/Y H:i');?>
            <br />
            [[.user_print.]]:<?php echo User::id();?>
			</td>
		</tr>	
	</table>
    
        <br />
        <table id="export">
        <tr>
            <td>
		<div class="report_title" style="text-transform:uppercase;text-align: center;">[[.deposit_report.]]</div>
		<div style="font-weight:bold;margin-top:10px;text-align: center;">
        [[.from.]]&nbsp;[[|start_shift_time|]] - [[|from_date|]]&nbsp;[[.to.]]&nbsp;[[|end_shift_time|]] - [[|to_date|]]		
		<?php if(URL::get('minibar_id')){echo '<br />'.Portal::language('minibar').DB::fetch('select name from minibar where id=\''.URL::get('minibar_id').'\'','name');}?>
		<?php if(URL::get('category_id')){echo '<br />'.Portal::language('category').DB::fetch('select name from product_category where id=\''.URL::get('category_id').'\'','name');}?>
		<?php if(URL::get('product_id')){echo '<br />'.Portal::language('product').DB::fetch('select concat(concat(id,\' - \'),name_'.Portal::language().') as name from product where id=\''.URL::get('category_id').'\'','name');}?>
		<?php if(URL::get('reservation_type_id')){echo '<br />'.Portal::language('reservation_type').': '.DB::fetch('select id,name from reservation_type where id=\''.URL::iget('reservation_type_id').'\'','name');}?>
		</div><br />
        <div style="text-align: center;">
        <input name="export_excel" type="submit" id="export_excel" value="[[.export_excel.]]" style="width: 70px; height: 23px"/>
        </div>
		<!--/IF:first_page-->
                
