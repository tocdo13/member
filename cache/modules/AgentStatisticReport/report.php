<style>
.hover:hover{
    background: #CCCCCC;
}
</style>
<?php 
				if((($this->map['page_no']==$this->map['start_page']) or $this->map['page_no']==0))
				{?>
<table cellpadding="10" cellspacing="0" width="100%">
<tr>
    <td align="center">
    	<table border="0" cellSpacing="0" cellpadding="5" width="100%">
    		<tr valign="middle">
                <td width="100"><img src="<?php echo HOTEL_LOGO;?>" width="100" /></td>
                <td align="left">
                    <br />
                    <strong><?php echo HOTEL_NAME;?></strong>
                    <br />
                    ADD: <?php echo HOTEL_ADDRESS;?>
                    <br />
                    Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?>
                    <br />
                    Email:<?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?>
                </td>
                <td align="right">
                <?php echo Portal::language('date_print');?>:<?php echo date('d/m/Y H:i');?>
                <br />
                <?php echo Portal::language('user_print');?>:<?php echo User::id();?>
                </td>
    		</tr>
            <tr><td>&nbsp;</td></tr>	
    		<tr>
    			<td colspan="3" style="text-align:center;">
                    <font class="report_title specific" ><?php echo Portal::language('agent_or_company_statistic_report');?><br /><br /></font>
                    <span style="font-family:'Times New Roman', Times, serif;font-weight:bold;">
                        <?php echo Portal::language('date_from');?>&nbsp;<?php echo $this->map['date_from'];?>&nbsp;<?php echo Portal::language('date_to');?>&nbsp;<?php echo $this->map['date_to'];?>
                    </span> 
    			</td>
    		</tr>
    	</table>
    </td>
</tr>
</table>

				<?php
				}
				?>
<?php 
				if((isset($this->map['items'])))
				{?>

<!---------REPORT----------->	
<div align="right"><em><?php echo Portal::language('price_unit');?>: <?php echo HOTEL_CURRENCY;?></em></div>

<table  cellpadding="2" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="border-collapse:collapse; font-size:11px;">
	<tr valign="middle" bgcolor="#EFEFEF">
		<th width="50px" class="report-table-header"><?php echo Portal::language('no');?></th>
		<th width="300px" class="report-table-header"><?php echo Portal::language('company');?></th>
        <th width="100px" class="report-table-header"><?php echo Portal::language('no_of_adult');?></th>
        <th width="100px" class="report-table-header"><?php echo Portal::language('no_of_child');?></th>
		<th width="100px" class="report-table-header"><?php echo Portal::language('no_of_room');?></th>
		<th width="100px" class="report-table-header"><?php echo Portal::language('no_of_night');?></th>
		<th width="100px" class="report-table-header"><?php echo Portal::language('revenue');?></th>
		<th width="100px" class="report-table-header"><?php echo Portal::language('money_per_night');?></th>
    </tr>
 <!--start: KID thêm đoạn này để tính tổng của trang trước chuyển sang nếu số trang khác 1
 <?php 
				if(($this->map['page_no']!=1))
				{?>
<!---------LAST GROUP VALUE----------->	        
    <tr>
        <td colspan="2" class="report_sub_title" align="right"><b><?php echo Portal::language('last_page_summary');?></b></td>
    	<td align="right" class="report_table_column"><strong><?php echo $this->map['last_group_function_params']['total_adult']?System::display_number($this->map['last_group_function_params']['total_adult']):'';?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo $this->map['last_group_function_params']['total_child']?System::display_number($this->map['last_group_function_params']['total_child']):'';?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo $this->map['last_group_function_params']['total_room']?System::display_number($this->map['last_group_function_params']['total_room']):'';?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo $this->map['last_group_function_params']['total_night']?System::display_number($this->map['last_group_function_params']['total_night']):'';?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo $this->map['last_group_function_params']['total_money']?System::display_number($this->map['last_group_function_params']['total_money']):'';?></strong></td>
        <td align="right" class="report_table_column"><strong>&nbsp;</td>
        
    </tr>

				<?php
				}
				?>
<!--end:KID-->   <?php $stt=1; ?>
	<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
	<tr bgcolor="white" class="hover">
		<td align="center" class="report-table-column"><?php echo $stt++; ?></td>
		<td align="left"  class="report-table-column"><?php echo $this->map['items']['current']['customer_name'];?></td>
        <td align="right" class="report-table-column"><?php echo System::display_number($this->map['items']['current']['total_adult']);?></td>
        <td align="right" class="report-table-column"><?php echo System::display_number($this->map['items']['current']['total_child']);?></td>
		<td align="right" class="report-table-column"><?php echo System::display_number($this->map['items']['current']['total_room']);?></td>
		<td align="right" class="report-table-column"><?php echo System::display_number($this->map['items']['current']['total_night']);?></td>
		<td align="right" class="report-table-column"><?php echo System::display_number($this->map['items']['current']['total_money_after']);?></td>
		<td align="right" class="report-table-column"><?php echo System::display_number($this->map['items']['current']['total_per_night']);?></td>
	</tr>
	<?php }}unset($this->map['items']['current']);} ?>
    <tr class="hover">
        <td colspan="2" class="report_sub_title" align="right"><b><?php if($this->map['real_page_no']==$this->map['real_total_page'])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
		<td align="right" class="report_table_column"><strong><?php echo System::display_number($this->map['group_function_params']['total_adult']);?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number($this->map['group_function_params']['total_child']);?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number($this->map['group_function_params']['total_room']);?></strong></td>
		<td align="right" class="report_table_column"><strong><?php echo System::display_number($this->map['group_function_params']['total_night']);?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number($this->map['group_function_params']['total_money_after']);?></strong></td>        
        <td align="right" class="report_table_column"><strong></strong></td>
	</tr>
</table>
<center><?php echo Portal::language('page');?> <?php echo $this->map['page_no'];?>/<?php echo $this->map['total_page'];?></center>


				<?php
				}
				?>


<?php 
				if(($this->map['real_page_no']==$this->map['real_total_page']))
				{?>

<table width="100%" style="font-family:'Times New Roman', Times, serif">
		<tr>
			<td></td>
			<td></td>
			<td align="center"><?php echo Portal::language('day');?> <?php echo date('d');?> <?php echo Portal::language('month');?> <?php echo date('m');?> <?php echo Portal::language('year');?> <?php echo date('Y');?><br /></td>
		</tr>
		<tr valign="top">
			<td width="33%" align="center"><?php echo Portal::language('creator');?></td>
			<td width="33%" align="center"><?php echo Portal::language('general_accountant');?></td>
			<td width="33%" align="center"><?php echo Portal::language('director');?></td>
		</tr>
		</table>
		<p>&nbsp;</p>
		<script>full_screen();</script>

				<?php
				}
				?>
<div style="page-break-before:always;page-break-after:always;"></div>
