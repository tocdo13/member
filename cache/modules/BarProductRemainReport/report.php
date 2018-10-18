<div class="report-bound" style=" page:land;">
<form name="BarTableReportForm" method="post">
<table cellpadding="10" cellspacing="0" width="100%">
<tr>
	<td align="center">      
		<!---------REPORT----------->
		<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
			<tr valign="middle" style="background:#DFDFDF !important;">
				<th class="report_table_header"><?php echo Portal::language('stt');?></th>
				<th class="report_table_header"><?php echo Portal::language('order_id');?></th>
				<th class="report_table_header"><?php echo Portal::language('time_in');?></th>
				<th class="report_table_header"><?php echo Portal::language('time_out');?></th>
				<th class="report_table_header"><?php echo Portal::language('date');?></th>
                <th class="report_table_header"><?php echo Portal::language('product_code');?></th>
				<th class="report_table_header"><?php echo Portal::language('product_name');?></th>
                <th class="report_table_header"><?php echo Portal::language('quantity');?></th>
                <th class="report_table_header"><?php echo Portal::language('price');?></th>
                <th class="report_table_header"><?php echo Portal::language('total');?></th>
			</tr>
            
        <?php 
				if(($this->map['page_no']!=1))
				{?>
        <!---------LAST GROUP VALUE----------->	        
            <tr>
                <td colspan="7" class="report_sub_title" align="right"><b><?php echo Portal::language('last_page_summary');?></b></td>
            	<td align="right" class="report_table_column"><strong><?php echo $this->map['last_group_function_params']['quantity']?System::display_number($this->map['last_group_function_params']['quantity']):'';?></strong></td>
                <td align="right" class="report_table_column">&nbsp;</td>
                <td align="right" class="report_table_column"><strong><?php echo $this->map['last_group_function_params']['total']?System::display_number($this->map['last_group_function_params']['total']):'';?></strong></td>
            </tr>
    	
				<?php
				}
				?>
        <?php $total = 0;?>			
		<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
			<tr bgcolor="white">
				<td class="report_table_column" style="width: 30px; text-align: center;"><?php echo $this->map['items']['current']['stt'];?></td>
				<td class="report_table_column" style="width: 60px; text-align: center;"><a href="<?php echo Url::build('touch_bar_restaurant',array(  'bar_reservation_bar_id', 'bar_reservation_receptionist_id')+array('cmd'=>'edit','id'=>$this->map['items']['current']['br_id'])); ?>"><?php echo $this->map['items']['current']['code'];?></a></td>
				<td class="report_table_column" style="width: 60px; text-align: center;"><?php echo date('H:i',$this->map['items']['current']['time_in']);?></td>
                <td class="report_table_column" style="width: 60px; text-align: center;"><?php echo date('H:i',$this->map['items']['current']['time_out']);?></td>
                <td class="report_table_column" style="width: 60px; text-align: center;"><?php echo date('d/m/Y',$this->map['items']['current']['time_out']);?></td>
				<td class="report_table_column" style="width: 100px; text-align: right;"><?php echo $this->map['items']['current']['product_id'];?></td>
				<td class="report_table_column" style="width: 60px; text-align: center;"><?php echo $this->map['items']['current']['name'];?></td>
                <td class="report_table_column" style="width: 60px; text-align: center;"><?php echo $this->map['items']['current']['quantity'];?></td>
                <td class="report_table_column" style="width: 60px; text-align: center;"><?php echo System::display_number($this->map['items']['current']['price']);?></td>
                <td class="report_table_column" style="width: 60px; text-align: center;"><?php $total+= $this->map['items']['current']['price'] * $this->map['items']['current']['quantity'];echo System::display_number($this->map['items']['current']['price'] * $this->map['items']['current']['quantity']);?></td>
			</tr>
		<?php }}unset($this->map['items']['current']);} ?>
		<!---------TOTAL GROUP FUNCTION----------->	
			<tr>
                <td colspan="7" class="report_sub_title" align="right"><b><?php if($this->map['real_page_no']==$this->map['real_total_page'])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
				<td align="center" class="report_table_column"><strong><?php echo $this->map['group_function_params']['quantity']?System::display_number($this->map['group_function_params']['quantity']):'';?></strong></td>
				<td align="right" class="report_table_column">&nbsp;</td>
                <td align="center" class="report_table_column"><strong><?php echo System::display_number($total);?></strong></td>
			</tr>
		</table>
		<br />
        <br />
		<table>
            <tr>
                <td><?php echo Portal::language('page');?></td>
                <td><?php echo $this->map['page_no'];?>/<?php echo $this->map['total_page'];?></td>
            </tr>
        </table>
        <?php 
				if(($this->map['page_no']==$this->map['total_page']))
				{?>
		<table width="100%" style="font-family:'Times New Roman', Times, serif">
    		<tr>
    			<td></td>
    			<td></td>
    			<td align="center"><?php echo Portal::language('day');?> <?php echo date('d');?><?php echo Portal::language('month');?> <?php echo date('m');?> <?php echo Portal::language('year');?> <?php echo date('Y');?><br /></td>
    		</tr>
    		<tr valign="top">
    			<td width="33%" align="center"><?php echo Portal::language('creator');?></td>
    			<td width="33%" align="center"><?php echo Portal::language('general_accountant');?></td>
    			<td width="33%" align="center"><?php echo Portal::language('director');?></td>
    		</tr>
    	</table>
		
        
				<?php
				}
				?>
        <p>&nbsp;</p>
    </td>
</tr>
</table>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<div style="page-break-before:always;page-break-after:always;"></div>
</div>
<style type="text/css">
input[type="submit"]{width:100px;}
a:visited{color:#003399}
</style>