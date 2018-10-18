<?php $rowspan = count([[=hk_product=]]);?>
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
  <tr valign="middle" bgcolor="#EFEFEF">
	<th width="9%" rowspan="2" nowrap="nowrap" class="report_table_header">[[.room.]]</th>
	<th width="8%" rowspan="2" nowrap="nowrap" class="report_table_header">[[.date_used.]]</th>
	<th width="76%" colspan="<?php echo $rowspan;?>" nowrap="nowrap" class="report_table_header">[[.in_used_equipment.]]</th>
	<th width="7%" rowspan="2" nowrap="nowrap" class="report_table_header">[[.room_note_1.]]</th>
  </tr>
  <tr valign="middle" bgcolor="#EFEFEF">
  <!--LIST:hk_product-->	
    <th nowrap="nowrap" class="report_table_header">[[|hk_product.name|]]</th>
  <!--/LIST:hk_product-->		
  </tr>
<!--LIST:items-->	
 <tr>
	  <td nowrap="nowrap" align="center" width="10%">[[|items.room|]]</td>
  	  <td nowrap="nowrap" align="right" width="10%"><?php echo Date_time::convert_orc_date_to_date([[=items.time=]],'/');?></td>
	  <?php foreach([[=hk_product=]] as $key=>$value){?>	
	  <td nowrap="nowrap" align="center" width="<?php echo 70/$rowspan;?>%"><?php $new_row =[[=items.product=]] ;if(isset($new_row[$key])){echo $new_row[$key]['quantity'].' '.$new_row[$key]['unit_name'];}else{echo '';}?></td>
   	  <?php }?>	
	  <td nowrap="nowrap" class="report_table_header" width="10%">&nbsp;</td>
  </tr>
 <!--/LIST:items-->	
</table>
