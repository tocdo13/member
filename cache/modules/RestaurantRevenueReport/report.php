<!---------REPORT----------->
<button id="export"><?php echo Portal::language('export');?></button>
<table  id="tblExport" cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px; font-family:Arial, Helvetica, sans-serif; border-collapse:collapse;">
	<tr valign="middle" bgcolor="#EFEFEF">
		<th nowrap="nowrap" class="report_table_header" align="left" width="10"><?php echo Portal::language('stt');?></th>
		<th class="report_table_header" width="50" align="left"><?php echo Portal::language('product_code');?></th>
        <th class="report_table_header" width="50" align="left"><?php echo Portal::language('unit');?></th>
		<th class="report_table_header" width="300" align="left"><?php echo Portal::language('product_name');?></th>
        <th class="report_table_header" width="70"><?php echo Portal::language('price');?></th>
		<th class="report_table_header" width="30"><?php echo Portal::language('quantity');?></th>
        <th class="report_table_header" width="30"><?php echo Portal::language('promotion');?></th>
        <th class="report_table_header" width="70"><?php echo Portal::language('discount');?></th>
        <th class="report_table_header" width="100"><?php echo Portal::language('total_before_tax');?></th>
	</tr>
    
	<?php 
				if(($this->map['page_no']!=1))
				{?>
<!---------LAST GROUP VALUE----------->	
		<tr><td colspan="5" class="report_sub_title" align="right"><b><?php echo Portal::language('last_page_summary');?></b></td>
			<td align="center" class="report_table_column"><?php echo System::display_number($this->map['last_group_function_params']['quantity']);?></td>
            <td align="center" class="report_table_column"><?php echo System::display_number($this->map['last_group_function_params']['promotion']);?></td>
            <td align="right" class="report_table_column"><?php echo System::display_number($this->map['last_group_function_params']['discount']);?></td>
            <td align="right" class="report_table_column"><?php echo System::display_number(round($this->map['last_group_function_params']['total']));?></td>
		</tr>
	
				<?php
				}
				?>
    <?php $category_name = '';
    $category_parent_name = false;
     ?>
	<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
<!---------GROUP----------->
<!---------CELLS----------->
    <?php
        if($category_parent_name !=$this->map['items']['current']['category_parent_name'])
        {
            $category_parent_name = $this->map['items']['current']['category_parent_name'];
            ?>
            <tr style="background-color: #6AA84F;">
		      <td align="left"  colspan="8" style="text-indent: 10px;"><em><strong><?php echo mb_strtoupper($this->map['items']['current']['category_parent_name'],'UTF-8');?></strong></em></td>
              <td nowrap align="right" class="report_table_column" ><em><strong><?php echo System::display_number(round($this->map['items_commons'][$category_parent_name]['total_parent']));?></strong></em></td>  
	       </tr>
            <?php 
        }
        
        if($category_name != $this->map['items']['current']['category_name'] ) 
        {
            $category_name = $this->map['items']['current']['category_name'];
    ?>
	<tr bgcolor="white">
		<td align="left"  colspan="5" style="text-indent: 10px;"><em><strong><?php echo Portal::language('category');?>: <?php echo $this->map['items']['current']['category_name'];?></strong></em></td>  
        <td align="center" class="report_table_column" ><em><strong><?php echo System::display_number(round($this->map['items_commons'][$category_name]['quantity']));?></strong></em></td>
        <td align="center" class="report_table_column" ></td>
        <td align="right" class="report_table_column" ></td>
        <td nowrap align="right" class="report_table_column" ><em><strong><?php echo System::display_number(round($this->map['items_commons'][$category_name]['total']));?></strong></em></td>        
            
	</tr>
    <?php
        }
    ?>
	<tr bgcolor="white">
		<td nowrap="nowrap" valign="top" align="right" class="report_table_column"><?php echo $this->map['items']['current']['stt'];?></td>
        <td align="left" class="report_table_column" ><?php echo $this->map['items']['current']['product_id'];?></td>
        <td align="left" class="report_table_column" ><?php echo $this->map['items']['current']['product_unit'];?></td>
        <td align="left" class="report_table_column" ><?php echo $this->map['items']['current']['product_name'];?></td>
        <td nowrap align="right" class="report_table_column" ><?php echo System::display_number(round($this->map['items']['current']['price']));?></td> 
        <td align="center" class="report_table_column" ><?php echo $this->map['items']['current']['quantity'];?></td>
        <td align="center" class="report_table_column" ><?php echo $this->map['items']['current']['promotion'];?></td>
        <td align="right" class="report_table_column" ><?php 
				if(($this->map['items']['current']['discount']>0))
				{?>
        												<?php echo System::display_number(round($this->map['items']['current']['discount']));?>
                                                        
				<?php
				}
				?></td>
         <td nowrap align="right" class="report_table_column" ><?php echo System::display_number(round($this->map['items']['current']['total']));?></td>        
        
	</tr>
	<?php }}unset($this->map['items']['current']);} ?>
	<!---------TOTAL GROUP FUNCTION----------->	
		<tr><td colspan="5" class="report_sub_title" align="right"><b><?php if($this->map['page_no']==$this->map['total_page'])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
        <td align="center" class="report_table_column"><strong><?php echo System::display_number(round($this->map['group_function_params']['quantity']));?></strong></td>
			<td align="center" class="report_table_column"><strong><?php echo System::display_number(round($this->map['group_function_params']['promotion']));?></strong></td>
            <td align="right" class="report_table_column"><strong><?php echo System::display_number(round($this->map['group_function_params']['discount']));?></strong></td>
            <td align="right" class="report_table_column"><strong><?php echo System::display_number(round($this->map['group_function_params']['total']));?></strong></td>
			
		</tr>
        <!--/** START : DAT-1343 **/-->
        <tr>
            <td colspan="9" align="right" class="report_table_column"><strong><?php echo Portal::language('total_discount_amount_invoice');?> : <?php echo System::display_number_report($this->map['total_discount']); ?> <?php echo Portal::language('change_to_after_tax');?> : <?php echo System::display_number(round($this->map['total_discount_after_tax'])); ?></strong></td>
        </tr>
        <tr>
            <td colspan="9" align="right" class="report_table_column"><strong><?php echo Portal::language('total');?> : <?php echo System::display_number(round($this->map['group_function_params']['total'] - $this->map['total_discount_after_tax'])); ?></strong></td>
        </tr>
        <!--/** END : DAT-1343 **/-->
</table>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery("#export").click(function () {
            jQuery("#tblExport").battatech_excelexport({
                containerid: "tblExport"
               , datatype: 'table'
            });
        });
    });
</script>