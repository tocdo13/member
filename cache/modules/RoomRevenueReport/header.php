<style>
a:visited{color:#003399}
</style>
<div class="report-bound">
<div>
<?php 
				if(($this->map['page_no']==$this->map['start_page'] or $this->map['page_no'] == 0 ))
				{?>
<link rel="stylesheet" href="skins/default/report.css" type="text/css">
<link rel="stylesheet" href="packages/core/skins/default/css/global.css" type="text/css">

<table id="tblExport" cellpadding="2" cellspacing="0" width="100%">
<tr>
	<td align="center">
		<!--IF:first_page($this->map['page_no']==1)-->
		<table cellSpacing=0 width="100%">
		<tr valign="top">
			<td align="left" width="60%">
			<?php echo HOTEL_NAME;?>: 
			<?php echo HOTEL_ADDRESS;?></td>
			<td align="right" nowrap width="40%">
            <?php echo Portal::language('date_print');?>:<?php echo date('d/m/Y H:i');?>
            <br />
            <?php echo Portal::language('user_print');?>:<?php echo User::id();?>
            </td>
			<!--<em>&#272;&#417;n v&#7883; t&iacute;nh: <?php //echo HOTEL_CURRENCY;?></em></td>-->
		</tr>	
	</table>
		<div class="report_title" style="text-transform:uppercase"><?php if(Url::get('type')==1){echo Portal::language('hotel_revenue_report');}elseif(Url::get('type')==2){echo Portal::language('room_revenue_report');}else{echo Portal::language('reception_report'); }?></div>
		<div style="font-weight:bold;">
		<?php echo Portal::language('from');?>&nbsp;<?php echo $this->map['from_date'];?>&nbsp;<?php echo Portal::language('to');?>&nbsp;<?php echo $this->map['to_date'];?>		
		<?php if(URL::get('minibar_id')){echo '<br />'.Portal::language('minibar').DB::fetch('select name from minibar where id=\''.URL::get('minibar_id').'\'','name');}?>
		<?php if(URL::get('category_id')){echo '<br />'.Portal::language('category').DB::fetch('select name from product_category where id=\''.URL::get('category_id').'\'','name');}?>
		<?php if(URL::get('product_id')){echo '<br />'.Portal::language('product').DB::fetch('select concat(concat(id,\' - \'),name_'.Portal::language().') as name from product where id=\''.URL::get('category_id').'\'','name');}?>
		<?php if(URL::get('reservation_type_id')){echo '<br />'.Portal::language('reservation_type').': '.DB::fetch('select id,name from reservation_type where id=\''.URL::iget('reservation_type_id').'\'','name');}?>
		</div>
        <div style="font-weight:bold;">
        <?php if(isset($this->map['portal_name']) and is_array($this->map['portal_name'])){ foreach($this->map['portal_name'] as $key1=>&$item1){if($key1!='current'){$this->map['portal_name']['current'] = &$item1;?>
        <?php echo $this->map['portal_name']['current']['name_1'];?>&nbsp;
        <?php }}unset($this->map['portal_name']['current']);} ?>
        </div>
        <br />
		
				<?php
				}
				?>
        <button id="export" style="margin: 10px 0px;"><?php echo Portal::language('export_excel');?></button>