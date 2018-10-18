<table id="export" cellpadding="2" cellspacing="0" width="100%">
<tr>
	<td align="center">
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
        <table >
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
</table>
<!---------REPORT----------->
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" >
    <tr valign="middle" bgcolor="#EFEFEF">
        <th align="center" class="report_table_header" width="30px" rowspan="3">[[.stt.]]</th>
        <th align="center" class="report_table_header" width="50px" rowspan="3">[[.re_code.]]</th>
        <th align="center" class="report_table_header" width="200px" rowspan="3">[[.customer.]]</th>
        <th align="center" class="report_table_header" width="80px" rowspan="3">[[.type.]]</th>
        <th align="center" class="report_table_header" width="80px" rowspan="3">[[.room.]]</th>
        <th align="center" class="report_table_header" width="80px" rowspan="3">[[.date.]]</th>
        <th align="center" class="report_table_header" width="240px" colspan="6">[[.deposit.]]</th>
        <th align="center" class="report_table_header" width="100px" rowspan="3">[[.total_dps.]]</th>
        <th align="center" class="report_table_header" width="100px" rowspan="3">[[.used.]]</th>
        <th align="center" class="report_table_header" width="100px" rowspan="3">[[.remain_nd.]]</th>
        <th align="center"class="report_table_header" width="100px" rowspan="3">[[.note.]]</th>
        <th align="center" class="report_table_header" width="100px" rowspan="3">[[.user.]]</th>
    </tr>
    <tr valign="middle" bgcolor="#EFEFEF">
        <th align="center" colspan="2" class="report_table_header" width="80px">[[.cash.]]</th>
        <th align="center" colspan="2"  class="report_table_header" width="80px">[[.credit_card.]]</th>
        <th align="center" colspan="2"  class="report_table_header" width="80px">[[.bank_transfer.]]</th>
    </tr>
    <tr valign="middle" bgcolor="#EFEFEF">
        <th align="center" class="report_table_header" width="80px">VND</th>
        <th align="center" class="report_table_header" width="80px">USD</th>
        <th align="center" class="report_table_header" width="80px">VND</th>
        <th align="center" class="report_table_header" width="80px">USD</th>
        <th align="center" class="report_table_header" width="80px">VND</th>
        <th align="center" class="report_table_header" width="80px">USD</th>
    </tr>
    <?php $stt = 1; $total_dps = 0; $total_use = 0; $total_remain = 0;?>
	<!--LIST:items-->
        <?php $check = 0; ?>
        <!--LIST:items.child-->
        <tr bgcolor="white">
            <td valign="top" align="center" class="report_table_column"><?php echo $stt++; ?></td>
            <td align="center" class="report_table_column" >
                <!--IF:cond( [[=items.child.type_dps=]]=='ROOM' )-->
                    <a href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.child.reservation_id=]],'r_r_id'=>[[=items.child.reservation_room_id=]]));?>" target="_blank">[[|items.child.reservation_id|]]</a>
                <!--ELSE-->
                    <a href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>[[=items.child.reservation_id=]],));?>" target="_blank">[[|items.child.reservation_id|]]</a>
                <!--/IF:cond-->
            </td>
            <td align="center" class="report_table_column" >[[|items.child.customer_name|]]</td>
            <td align="center" class="report_table_column" >[[|items.child.type_dps|]]</td>
            <td align="center" class="report_table_column" >[[|items.child.room_name|]]</td>
            <td align="center" class="report_table_column" >[[|items.child.deposit_date|]]</td>
            <?php foreach([[=items.child.type_payment=]] as $key){ ?>
                <td  align="right"  class="report_table_column change_num" ><?php echo System::display_number($key);?></td>
            <?php }?>
            <!--IF:cond($check == 0)-->
            <td rowspan="[[|items.rowspan|]]" align="right" class="change_num"><?php echo System::display_number([[=items.total_dps=]]); $total_dps += [[=items.total_dps=]]; ?></td>
            <td rowspan="[[|items.rowspan|]]" align="right" class="change_num"><?php echo System::display_number([[=items.total_use=]]); $total_use+= [[=items.total_use=]]; ?></td>
            <td rowspan="[[|items.rowspan|]]" align="right" class="change_num"><?php echo System::display_number([[=items.total_remain=]]); $total_remain+= [[=items.total_remain=]]; ?></td>
            <?php $check++; ?>
            <!--/IF:cond-->
            <td align="left">[[|items.child.note|]]</td>
            <td align="center">[[|items.child.deposit_user_id|]]</td>
        </tr>
        <!--/LIST:items.child-->
	<!--/LIST:items-->
    <tr bgcolor="white">
            <th colspan="6" align="right" class="report_table_column" >[[.total.]]</th>
            <?php foreach([[=total_dps_type=]] as $key){ ?>
                <th  align="right"  class="report_table_column change_num" ><?php echo System::display_number($key);?></th>
            <?php }?>
            <th align="right" class="change_num"><?php echo System::display_number($total_dps); ?></th>
            <th align="right" class="change_num"><?php echo System::display_number($total_use); ?></th>
            <th align="right" class="change_num"><?php echo System::display_number($total_remain); ?></th>
            <th align="center"></th>
            <th align="center"></th>
        </tr>
	</tr>
</table>

</table>
</div>
</div>
<script>
jQuery("#export_excel").click(function () {
        jQuery('.change_num').each(function(){
            jQuery(this).html(to_numeric(jQuery(this).html()));
        });
        jQuery('#export_excel').remove();
        jQuery('#export').battatech_excelexport({
        containerid: "export"
       , datatype: 'table'
    });
    
})
</script>