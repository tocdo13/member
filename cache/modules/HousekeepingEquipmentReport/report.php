<?php $rowspan = count($this->map['hk_product']);?>
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
  <tr valign="middle" bgcolor="#EFEFEF">
	<th width="9%" rowspan="2" nowrap="nowrap" class="report_table_header"><?php echo Portal::language('room');?></th>
	<th width="8%" rowspan="2" nowrap="nowrap" class="report_table_header"><?php echo Portal::language('date_used');?></th>
	<th width="76%" colspan="<?php echo $rowspan;?>" nowrap="nowrap" class="report_table_header"><?php echo Portal::language('in_used_equipment');?></th>
	<th width="7%" rowspan="2" nowrap="nowrap" class="report_table_header"><?php echo Portal::language('room_note_1');?></th>
  </tr>
  <tr valign="middle" bgcolor="#EFEFEF">
  <?php if(isset($this->map['hk_product']) and is_array($this->map['hk_product'])){ foreach($this->map['hk_product'] as $key1=>&$item1){if($key1!='current'){$this->map['hk_product']['current'] = &$item1;?>	
    <th nowrap="nowrap" class="report_table_header"><?php echo $this->map['hk_product']['current']['name'];?></th>
  <?php }}unset($this->map['hk_product']['current']);} ?>		
  </tr>
<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current'] = &$item2;?>	
 <tr>
	  <td nowrap="nowrap" align="center" width="10%"><?php echo $this->map['items']['current']['room'];?></td>
  	  <td nowrap="nowrap" align="right" width="10%"><?php echo Date_time::convert_orc_date_to_date($this->map['items']['current']['time'],'/');?></td>
	  <?php foreach($this->map['hk_product'] as $key=>$value){?>	
	  <td nowrap="nowrap" align="center" width="<?php echo 70/$rowspan;?>%"><?php $new_row =$this->map['items']['current']['product'] ;if(isset($new_row[$key])){echo $new_row[$key]['quantity'].' '.$new_row[$key]['unit_name'];}else{echo '';}?></td>
   	  <?php }?>	
	  <td nowrap="nowrap" class="report_table_header" width="10%">&nbsp;</td>
  </tr>
 <?php }}unset($this->map['items']['current']);} ?>	
</table>
