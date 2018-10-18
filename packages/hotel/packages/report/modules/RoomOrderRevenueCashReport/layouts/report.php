<style>
	.simple-layout-middle{
		width:100%;	
	}
</style>
<!---------REPORT----------->
<?php $i=0;$j=0;$k=0;?>
<!--LIST:payment_types--><?php $i++;?><!--/LIST:payment_types-->
<!--LIST:currencies--><?php $j++;?><!--/LIST:currencies-->
<!--LIST:credit_card--><?php $k++;?><!--/LIST:credit_card-->
<table cellpadding="2" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px;">
	<tr valign="middle" bgcolor="#EFEFEF">
	  <th width="2%" rowspan="4" align="left" nowrap="nowrap" class="report-table-header">[[.stt.]]</th>
	  <th width="70px" rowspan="4" class="report-table-header">[[.folio_id.]]</th>
      <th width="100px" rowspan="4" class="report-table-header">[[.vat_code.]]</th>
	  <th width="250px" rowspan="4" class="report-table-header">[[.room.]]</th>
	  <th rowspan="4" class="report-table-header" width="80px">[[.date.]]</th>
	  <th rowspan="4" class="report-table-header" width="120px">[[.deposit.]]</th>
	  <th colspan="<?php echo $j;//($i*$j + $j + 2 - 3*$j);?>" class="report-table-header">[[.payment.]]</th>
	  <th rowspan="4" class="report-table-header">[[.user.]]</th>
      <th rowspan="4" class="report-table-header">[[.note.]]</th>
  </tr>
	<tr valign="middle" bgcolor="#EFEFEF">
    <!--LIST:payment_types-->
        <?php if([[=payment_types.def_code=]]=='CREDIT_CARD'){?>
        	<th class="report-table-header" colspan="<?php echo $j;?>">[[|payment_types.name|]]</th>
        <?php }elseif([[=payment_types.def_code=]]!='DEBIT' && [[=payment_types.def_code=]]!='FOC'){?>
        	<th class="report-table-header" colspan="<?php echo $j;?>">[[|payment_types.name|]]</th>
         <?php }else{?>
         	<th class="report-table-header">[[|payment_types.name|]]</th>
         <?php }?>
    <!--/LIST:payment_types-->
    </tr>
    <tr valign="middle" bgcolor="#EFEFEF">
     <!--LIST:payment_types-->
      	<?php if([[=payment_types.def_code=]]=='CREDIT_CARD'){?>
             <!----LIST:credit_card---->
                <th class="report-table-header">&nbsp;</th>
             <!----/LIST:credit_card----> 
        <?php }else if([[=payment_types.def_code=]]!='DEBIT' && [[=payment_types.def_code=]]!='FOC'){?>
        	 <!--LIST:currencies-->
				<th class="report-table-header" rowspan="2">[[|currencies.id|]]</th>
   			 <!--/LIST:currencies-->
         <?php }else{?>
         		<th class="report-table-header" rowspan="2"></th>
         <?php }?>
      <!--/LIST:payment_types-->    
     </tr>
    <tr valign="middle" bgcolor="#EFEFEF">
        <!----LIST:credit_card---->
             <!--LIST:currencies-->
                <th class="report-table-header" style="display:none;">[[|currencies.id|]]</th>
            <!--/LIST:currencies-->
        <!----/LIST:credit_card----> 
    </tr>   
    <?php $total_credit_total = 0; ?> 
    <!--LIST:items-->
<!--IF:cond_tt(isset($this->map['items']['current']['CASH_VND']))-->
    <?php if([[=items.group=]]==1){
		$cond = ' id='.[[=items.r_id=]].'&cmd=group_invoice&customer_id='.[[=items.customer_id=]];
	}else{
		$cond = ' traveller_id='.[[=items.traveller_id=]];
	} $total_credit = 0; $count = 0; $count2 = 0;
	?>
	<tr bgcolor="white">
		<td nowrap="nowrap" valign="top" align="center" class="report_table_column">[[|items.stt|]]</td>
        <td nowrap align="center" class="report_table_column" width="70px" onclick="window.open('?page=view_traveller_folio&folio_id=[[|items.id|]]&<?php echo $cond;?>');">[[|items.code|]]</td>
        <td nowrap align="center" class="report_table_column" width="100px" onclick="window.open('?page=view_traveller_folio&folio_id=[[|items.id|]]&<?php echo $cond;?>');">[[|items.vat_code|]]</td>
        <td align="left" class="report_table_column" ><div style="float:left;width:250px;font-size:11px;">
            <!--IF:cond(isset([[=items.booking_code=]]) and trim([[=items.booking_code=]]))-->[[.booking_code.]]: [[|items.booking_code|]]<br><!--/IF:cond-->[[|items.guest_name|]]<br> [[.recode.]]: [[|items.r_id|]]<br>
           <!--IF:cond_r([[=items.room_name=]]!='')--><strong>P:[[|items.room_name|]]</strong> <!--/IF:cond_r-->
         </div></td>
      <td nowrap align="center" class="report_table_column" ><?php echo date('d/m/Y',[[=items.time=]]);?></td>
        <td align="right" nowrap class="report_table_column" >[[|items.deposit|]]</td>
        <!--LIST:payment_types-->
        	 <?php if([[=payment_types.def_code=]]=='CREDIT_CARD'){?>
        	 <!--LIST:credit_card-->
                <!--LIST:currencies-->
                	 <?php $cr = [[=payment_types.def_code=]].'_'.[[=credit_card.id=]].'_'.[[=currencies.id=]]; if(isset($this->map['items']['current'][$cr])){
						//echo '<td align="right" nowrap class="report_table_column" >'.System::Display_number($this->map['items']['current'][$cr]).'</td>';	
						$total_credit += $this->map['items']['current'][$cr];
					}else{
						//echo '<td align="right" nowrap class="report_table_column" >&nbsp;</td>';
					}
					if(($count+1)==$k){
						echo '<td align="right" nowrap class="report_table_column" style="width:150px;" >'.System::Display_number($total_credit).'</td>';		
					}
					$count++;
					?>	
   				<!--/LIST:currencies-->
             <!--/LIST:credit_card-->
        <?php }else if([[=payment_types.def_code=]]!='DEBIT' && [[=payment_types.def_code=]]!='FOC'){?>
        		<!--LIST:currencies-->
                <?php $tt = [[=payment_types.def_code=]].'_'.[[=currencies.id=]]; if(isset($this->map['items']['current'][$tt])){
					echo '<td align="right" nowrap class="report_table_column"  style="width:150px;">'.System::Display_number($this->map['items']['current'][$tt]).'</td>';	
				}else{
					echo '<td align="right" nowrap class="report_table_column" style="width:150px;">&nbsp;</td>';
				}?>
   				<!--/LIST:currencies-->
         <?php }else if([[=payment_types.def_code=]]=='DEBIT'){?>
					<td align="right" nowrap class="report_table_column" style="width:150px;" ><?php echo System::Display_number([[=items.debit=]]);?></td>	
         <?php }else if([[=payment_types.def_code=]]=='FOC'){?>
                    <td align="right" nowrap class="report_table_column" style="width:150px;" ><?php echo System::Display_number([[=items.foc=]]);?></td>
         <?php }?>
        <!--/LIST:payment_types-->
        <td nowrap align="center" style="width:100px;">[[|items.user_id|]]</td>
        <td nowrap align="center">&nbsp;</td>
	</tr>
<!--/IF:cond_tt-->
   	<!--/LIST:items-->
    <tr>
    	<td colspan="5" class="report_sub_title" align="right"><b><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
    	<td align="right" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['deposit']);?></strong></td>
     <!--LIST:payment_types-->
        	 <?php if([[=payment_types.def_code=]]=='CREDIT_CARD'){?>
        	 <!--LIST:credit_card-->
                <!--LIST:currencies-->
                	 <?php $cr = [[=payment_types.def_code=]].'_'.[[=credit_card.id=]].'_'.[[=currencies.id=]]; if(isset([[=group_function_params=]][$cr])){
						//echo '<td align="right" nowrap class="report_table_column" ><strong>'.System::Display_number([[=group_function_params=]][$cr]).'</strong></td>';	
						$total_credit_total += [[=group_function_params=]][$cr];
					}else{
						//echo '<td align="right" nowrap class="report_table_column" >&nbsp;</td>';
					}
					if(($count2+1)==$k){
						echo '<td align="right" nowrap class="report_table_column" style="width:150px;" >'.System::Display_number($total_credit_total).'</td>';
					}
					$count2++;
					?>	
   				<!--/LIST:currencies-->
             <!--/LIST:credit_card-->
        <?php }else if([[=payment_types.def_code=]]!='DEBIT' && [[=payment_types.def_code=]]!='FOC'){?>
        		<!--LIST:currencies-->
                <?php $tt = [[=payment_types.def_code=]].'_'.[[=currencies.id=]]; if(isset([[=group_function_params=]][$tt])){
					echo '<td align="right" nowrap class="report_table_column" style="width:150px;" ><strong>'.System::Display_number([[=group_function_params=]][$tt]).'</strong></td>';	
				}else{
					echo '<td align="right" nowrap class="report_table_column" style="width:150px;" >&nbsp;</td>';
				}?>
   				<!--/LIST:currencies-->
         <?php }else if([[=payment_types.def_code=]]=='DEBIT'){?>
					<td align="right" nowrap class="report_table_column" style="width:150px;" ><strong><?php echo System::Display_number([[=group_function_params=]]['debit']);?></strong></td>	
         <?php }else if([[=payment_types.def_code=]]=='FOC'){?>
                    <td align="right" nowrap class="report_table_column" style="width:150px;" ><strong><?php echo System::Display_number([[=group_function_params=]]['foc']);?></strong></td>
         <?php }?>
        <!--/LIST:payment_types-->
          	<td align="right" nowrap class="report_table_column" style="width:100px;" >&nbsp;</td>
            <td align="right" nowrap class="report_table_column" >&nbsp;</td>
       </tr>
    	<!--/IF:cond_d-->
</table>
</div>
</div>