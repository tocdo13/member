<!---------REPORT----------->
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px; font-family:Arial, Helvetica, sans-serif; border-collapse:collapse;">
	<tr valign="middle" bgcolor="#EFEFEF">
        <th width="10px"  class="report-table-header"><?php echo Portal::language('stt');?></th>
        <th width="100px"  class="report-table-header"><?php echo Portal::language('product_name');?></th>
        <th width="20px" class="report-table-header">DVT</th>
        <th width="20px"  class="report-table-header">SL</th>  
        <th width="20px"  class="report-table-header">DG</th>
        <th width="100px"  class="report-table-header"><?php echo Portal::language('total');?></th>
        <th width="100px"  class="report-table-header"><?php echo Portal::language('tax_rate');?></th>     
        <th width="100px"  class="report-table-header"><?php echo Portal::language('tax_fee');?></th>
        <th width="100px"  class="report-table-header"><?php echo Portal::language('amount');?></th>
        <th width="100px" class="report-table-header"><?php echo Portal::language('note');?></th>
	</tr>

	<?php 
				if(($this->map['page_no']!=1))
				{?>
<!---------LAST GROUP VALUE----------->	
		<tr>
            <td colspan="5" class="report_sub_title" align="right"><b><?php echo Portal::language('last_page_summary');?></b></td>
        	<td align="right" class="report_table_column"><strong><?php echo (!($this->map['last_group_function_params']['total_after_fee'])?'':System::display_number($this->map['last_group_function_params']['total_after_fee']));?></strong></td>
        	<td align="right"  class="report_table_column" >&nbsp;</td>
            <td align="right" class="report_table_column"><strong><?php echo (!($this->map['last_group_function_params']['tax_fee'])?'':System::display_number($this->map['last_group_function_params']['tax_fee']));?></strong></td>
            <td align="right" class="report_table_column"><strong><?php echo (!($this->map['last_group_function_params']['total'])?'':System::display_number($this->map['last_group_function_params']['total']));?></strong></td>
            <td align="right"  class="report_table_column" >&nbsp;</td>
		</tr>
	
				<?php
				}
				?>
	<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
<!---------GROUP----------->
<!---------CELLS----------->
	<tr <?php Draw::hover('#FFFF33')?> bgcolor="white">
		<td align="center" class="report_table_column"><?php echo $this->map['items']['current']['stt'];?></td>
        <td align="center" class="report_table_column"><?php echo $this->map['items']['current']['description'];?></td>
        <td align="center" class="report_table_column"></td>
        <td align="center" class="report_table_column"></td>
        <td align="center" class="report_table_column"></td>
        <td align="right" class="report_table_column"><?php echo $this->map['items']['current']['total_after_fee']?System::display_number($this->map['items']['current']['total_after_fee']):'';?></td>
        <td align="center" class="report_table_column"><?php echo $this->map['items']['current']['tax_rate'];?>%</td>
        <td align="right" class="report_table_column"><?php echo $this->map['items']['current']['tax_fee']?System::display_number($this->map['items']['current']['tax_fee']):'';?></td>
        <td align="right" class="report_table_column"><?php echo $this->map['items']['current']['total']?System::display_number($this->map['items']['current']['total']):'';?></td>
        <td align="center" class="report_table_column" onclick="window.open('<?php echo $this->map['items']['current']['link'];?>');"><a><?php echo $this->map['items']['current']['code'];?></a></td>  
	</tr>
	<?php }}unset($this->map['items']['current']);} ?>
	<!---------TOTAL GROUP FUNCTION----------->	
    <tr>
		<td colspan="5" class="report_sub_title" align="right"><b><?php if($this->map['page_no']==$this->map['total_page'])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
        <td align="right" class="report_table_column"><strong><?php echo (!($this->map['group_function_params']['total_after_fee'])?'':System::display_number($this->map['group_function_params']['total_after_fee']));?></strong></td>
        <td align="right"  class="report_table_column" >&nbsp;</td>
        <td align="right" class="report_table_column"><strong><?php echo (!($this->map['group_function_params']['tax_fee'])?'':System::display_number($this->map['group_function_params']['tax_fee']));?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo (!($this->map['group_function_params']['total'])?'':System::display_number($this->map['group_function_params']['total']));?></strong></td>
        <td align="right"  class="report_table_column" >&nbsp;</td>
    </tr>
</table>
</div>
</div>
