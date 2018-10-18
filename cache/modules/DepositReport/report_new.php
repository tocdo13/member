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
			<strong><?php echo Portal::language('template_code');?></strong>
            
            <br />
            <?php echo Portal::language('date_print');?>:<?php echo date('d/m/Y H:i');?>
            <br />
            <?php echo Portal::language('user_print');?>:<?php echo User::id();?>
			</td>
		</tr>	
	</table>
    
        <br />
        <table >
        <tr>
            <td>
		<div class="report_title" style="text-transform:uppercase;text-align: center;"><?php echo Portal::language('deposit_report');?></div>
		<div style="font-weight:bold;margin-top:10px;text-align: center;">
        <?php echo Portal::language('from');?>&nbsp;<?php echo $this->map['start_shift_time'];?> - <?php echo $this->map['from_date'];?>&nbsp;<?php echo Portal::language('to');?>&nbsp;<?php echo $this->map['end_shift_time'];?> - <?php echo $this->map['to_date'];?>		
		<?php if(URL::get('minibar_id')){echo '<br />'.Portal::language('minibar').DB::fetch('select name from minibar where id=\''.URL::get('minibar_id').'\'','name');}?>
		<?php if(URL::get('category_id')){echo '<br />'.Portal::language('category').DB::fetch('select name from product_category where id=\''.URL::get('category_id').'\'','name');}?>
		<?php if(URL::get('product_id')){echo '<br />'.Portal::language('product').DB::fetch('select concat(concat(id,\' - \'),name_'.Portal::language().') as name from product where id=\''.URL::get('category_id').'\'','name');}?>
		<?php if(URL::get('reservation_type_id')){echo '<br />'.Portal::language('reservation_type').': '.DB::fetch('select id,name from reservation_type where id=\''.URL::iget('reservation_type_id').'\'','name');}?>
		</div><br />
        <div style="text-align: center;">
        <input name="export_excel" type="submit" id="export_excel" value="<?php echo Portal::language('export_excel');?>" style="width: 70px; height: 23px"/>
        </div>
</table>
<!---------REPORT----------->
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" >
    <tr valign="middle" bgcolor="#EFEFEF">
        <th align="center" class="report_table_header" width="30px" rowspan="3"><?php echo Portal::language('stt');?></th>
        <th align="center" class="report_table_header" width="50px" rowspan="3"><?php echo Portal::language('re_code');?></th>
        <th align="center" class="report_table_header" width="200px" rowspan="3"><?php echo Portal::language('customer');?></th>
        <th align="center" class="report_table_header" width="80px" rowspan="3"><?php echo Portal::language('type');?></th>
        <th align="center" class="report_table_header" width="80px" rowspan="3"><?php echo Portal::language('room');?></th>
        <th align="center" class="report_table_header" width="80px" rowspan="3"><?php echo Portal::language('date');?></th>
        <th align="center" class="report_table_header" width="240px" colspan="6"><?php echo Portal::language('deposit');?></th>
        <th align="center" class="report_table_header" width="100px" rowspan="3"><?php echo Portal::language('total_dps');?></th>
        <th align="center" class="report_table_header" width="100px" rowspan="3"><?php echo Portal::language('used');?></th>
        <th align="center" class="report_table_header" width="100px" rowspan="3"><?php echo Portal::language('remain_nd');?></th>
        <th align="center"class="report_table_header" width="100px" rowspan="3"><?php echo Portal::language('note');?></th>
        <th align="center" class="report_table_header" width="100px" rowspan="3"><?php echo Portal::language('user');?></th>
    </tr>
    <tr valign="middle" bgcolor="#EFEFEF">
        <th align="center" colspan="2" class="report_table_header" width="80px"><?php echo Portal::language('cash');?></th>
        <th align="center" colspan="2"  class="report_table_header" width="80px"><?php echo Portal::language('credit_card');?></th>
        <th align="center" colspan="2"  class="report_table_header" width="80px"><?php echo Portal::language('bank_transfer');?></th>
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
	<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
        <?php $check = 0; ?>
        <?php if(isset($this->map['items']['current']['child']) and is_array($this->map['items']['current']['child'])){ foreach($this->map['items']['current']['child'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current']['child']['current'] = &$item2;?>
        <tr bgcolor="white">
            <td valign="top" align="center" class="report_table_column"><?php echo $stt++; ?></td>
            <td align="center" class="report_table_column" >
                <?php 
				if(( $this->map['items']['current']['child']['current']['type_dps']=='ROOM' ))
				{?>
                    <a href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>$this->map['items']['current']['child']['current']['reservation_id'],'r_r_id'=>$this->map['items']['current']['child']['current']['reservation_room_id']));?>" target="_blank"><?php echo $this->map['items']['current']['child']['current']['reservation_id'];?></a>
                 <?php }else{ ?>
                    <a href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>$this->map['items']['current']['child']['current']['reservation_id'],));?>" target="_blank"><?php echo $this->map['items']['current']['child']['current']['reservation_id'];?></a>
                
				<?php
				}
				?>
            </td>
            <td align="center" class="report_table_column" ><?php echo $this->map['items']['current']['child']['current']['customer_name'];?></td>
            <td align="center" class="report_table_column" ><?php echo $this->map['items']['current']['child']['current']['type_dps'];?></td>
            <td align="center" class="report_table_column" ><?php echo $this->map['items']['current']['child']['current']['room_name'];?></td>
            <td align="center" class="report_table_column" ><?php echo $this->map['items']['current']['child']['current']['deposit_date'];?></td>
            <?php foreach($this->map['items']['current']['child']['current']['type_payment'] as $key){ ?>
                <td  align="right"  class="report_table_column change_num" ><?php echo System::display_number($key);?></td>
            <?php }?>
            <?php 
				if(($check == 0))
				{?>
            <td rowspan="<?php echo $this->map['items']['current']['rowspan'];?>" align="right" class="change_num"><?php echo System::display_number($this->map['items']['current']['total_dps']); $total_dps += $this->map['items']['current']['total_dps']; ?></td>
            <td rowspan="<?php echo $this->map['items']['current']['rowspan'];?>" align="right" class="change_num"><?php echo System::display_number($this->map['items']['current']['total_use']); $total_use+= $this->map['items']['current']['total_use']; ?></td>
            <td rowspan="<?php echo $this->map['items']['current']['rowspan'];?>" align="right" class="change_num"><?php echo System::display_number($this->map['items']['current']['total_remain']); $total_remain+= $this->map['items']['current']['total_remain']; ?></td>
            <?php $check++; ?>
            
				<?php
				}
				?>
            <td align="left"><?php echo $this->map['items']['current']['child']['current']['note'];?></td>
            <td align="center"><?php echo $this->map['items']['current']['child']['current']['deposit_user_id'];?></td>
        </tr>
        <?php }}unset($this->map['items']['current']['child']['current']);} ?>
	<?php }}unset($this->map['items']['current']);} ?>
    <tr bgcolor="white">
            <th colspan="6" align="right" class="report_table_column" ><?php echo Portal::language('total');?></th>
            <?php foreach($this->map['total_dps_type'] as $key){ ?>
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