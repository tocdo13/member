<!---------REPORT----------->	
<p style="text-align: center;"><input name="export_repost" type="submit" id="export_repost" value="<?php echo Portal::language('export');?>"  /></p>
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px; font-family:Arial, Helvetica, sans-serif; border-collapse:collapse;" >
	<tr valign="middle" bgcolor="#EFEFEF">
		<th width="10px" align="center" nowrap="nowrap" class="report_table_header"><?php echo Portal::language('stt');?></th>
		<th width="20px" align="center" nowrap="nowrap" class="report_table_header"><?php echo Portal::language('invoice_number');?></th>
		<!---<th class="report_table_header" nowrap="nowrap" ><?php echo Portal::language('customer_name');?></th>--->
		<th width="80px;" nowrap="nowrap" class="report_table_header"><?php echo Portal::language('room_level');?></th>
		<th width="70px" nowrap="nowrap" class="report_table_header"><?php echo Portal::language('arrival_date');?></th>
		<th width="70px" nowrap="nowrap" class="report_table_header"><?php echo Portal::language('departure_date');?></th>
		<!---<th width="100px" nowrap="nowrap" class="report_table_header"><?php echo Portal::language('contact');?></th>--->
		<th width="70px" nowrap="nowrap" class="report_table_header"><?php echo Portal::language('status');?></th>
        <th width="50px" nowrap="nowrap" class="report_table_header"><?php echo Portal::language('deposit');?></th>
		<th width="20px" nowrap="nowrap" class="report_table_header"><?php echo Portal::language('confirm');?></th>
	    <th width="300px" nowrap="nowrap" class="report_table_header"><?php echo Portal::language('note');?></th>
	</tr>
    <?php $temp = '';?>    
    <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
    <?php if($temp!=$this->map['items']['current']['reservation_id']){$temp = $this->map['items']['current']['reservation_id'];?>
    <tr>
        <td colspan="11">[<?php echo Portal::language('rcode');?>:  <a target="_blank" href="<?php echo Url::build('reservation',array('cmd'=>'edit','id'=>$this->map['items']['current']['reservation_id']));?>"><?php echo $this->map['items']['current']['reservation_id'];?>]</a>  | <span style="color:#FF0000; font-size:14px"><b><?php echo $this->map['items']['current']['customer_name'];?></b></span> | <span style="color:#0066FF;"> <?php echo $this->map['items']['current']['booking_code'];?>]</span></td>
    </tr>
    <?php }?>
	<tr bgcolor="white">
		<td align="center" valign="middle" class="report_table_column"><?php echo $this->map['items']['current']['stt'];?></td>
		<td align="center" valign="middle" class="report_table_column"><?php echo $this->map['items']['current']['bill_number'];?></td>
		<!---<td nowrap align="left" class="report_table_column" width="150"><?php echo $this->map['items']['current']['customer_name'];?></td>--->
		<td align="center" nowrap="nowrap" class="report_table_column"><?php echo $this->map['items']['current']['room_level'];?></td>
		<td align="center" nowrap="nowrap" class="report_table_column"><?php echo date('H\h:i',$this->map['items']['current']['time_in']);?><br /><?php echo date('d/m/Y',$this->map['items']['current']['time_in']);?></td>
		<td align="center" nowrap="nowrap" class="report_table_column"><?php echo date('H\h:i',$this->map['items']['current']['time_out']);?><br /><?php echo date('d/m/Y',$this->map['items']['current']['time_out']);?></td>
		<!---<td align="left" class="report_table_column">
        <?php if(isset($this->map['items']['current']['contacts']) and is_array($this->map['items']['current']['contacts'])){ foreach($this->map['items']['current']['contacts'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current']['contacts']['current'] = &$item2;?>
        <?php echo $this->map['items']['current']['contacts']['current']['contact_name'];?> -  <?php echo $this->map['items']['current']['contacts']['current']['contact_phone'];?><br />
        <?php }}unset($this->map['items']['current']['contacts']['current']);} ?> </td>--->
		<td align="center" nowrap="nowrap" class="report_table_column"><?php echo $this->map['items']['current']['status'];?></td>
        <td align="center" nowrap="nowrap" class="report_table_column"><?php echo $this->map['items']['current']['deposit'];?></td>
		<td align="center" nowrap="nowrap" class="report_table_column"><?php echo $this->map['items']['current']['confirm'];?></td>		
		<td align="left" class="report_table_column"><?php echo $this->map['items']['current']['note'];?></td>
	</tr>
	<?php }}unset($this->map['items']['current']);} ?>
	<?php 
				if(($this->map['page_no']==$this->map['total_page']))
				{?>
	
				<?php
				}
				?>
</table>
</table>
<script>
    jQuery('#export_repost').click(function(){
         jQuery('.change_numTr').each(function(){
           jQuery(this).html(to_numeric(jQuery(this).html())) ;
        });
        jQuery('#export_repost').remove();
        jQuery('#export').battatech_excelexport({
           containerid:'export',
           datatype:'table'
            
        });
    })
</script>