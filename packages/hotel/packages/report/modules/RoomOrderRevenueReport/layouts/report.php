<style>
	.simple-layout-middle{
		width:100%;	
	}
</style>
<!---------REPORT----------->
<?php 
	//Start: giap.ln 
	$i= count([[=payment_types=]]);//mang 6 phan tu: tra lai, tien mat, the, no, chuyen khoan,mien phi
	$j = count([[=currencies=]]);//mang 2 phan tu: USD,VND(cac loai tien te thanh toan)
	$k = count([[=credit_card=]]);//cac loai hinh thanh toan bang the
	//end giap.ln
?>
<table cellpadding="2" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px; border-collapse:collapse;">
	<tr valign="middle" bgcolor="#EFEFEF">
	  <th width="1%" rowspan="3" align="left" nowrap="nowrap" class="report-table-header">[[.stt.]]</th>
	  <th width="30px" rowspan="3" class="report-table-header">[[.order_id.]]</th>
      <th width="30px" rowspan="3" class="report-table-header">[[.order_code_vat.]]</th>
	  <th width="150px" rowspan="3" class="report-table-header">[[.infomation.]]</th>
	  <th rowspan="3" class="report-table-header" width="50px">[[.date.]]</th>
      <th rowspan="3" class="report-table-header" width="65px">[[.deposit.]]</th>
      
      <th colspan="<?php echo ($i*$j - 2*($j-1));?>"class="report-table-header">[[.payment.]]</th>
      
	  <th rowspan="3" align="center" class="report-table-header" width="100px">[[.total.]]</th>     
	  <th rowspan="3" class="report-table-header">[[.user.]]</th>
  </tr>
	<tr valign="middle" bgcolor="#EFEFEF">
    <!--LIST:payment_types-->
        <?php if([[=payment_types.def_code=]]=='CREDIT_CARD'){?>
        	<th colspan="<?php echo $j;?>" class="report-table-header">[[|payment_types.name|]]</th>
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
              <!--LIST:currencies-->
				<th class="report-table-header">[[|currencies.id|]]</th>
   			 <!--/LIST:currencies-->
        <?php }else if([[=payment_types.def_code=]]!='DEBIT' && [[=payment_types.def_code=]]!='FOC'){?>
        	 <!--LIST:currencies-->
				<th class="report-table-header">[[|currencies.id|]]</th>
   			 <!--/LIST:currencies-->
         <?php }else{?>
         		<th class="report-table-header"></th>
         <?php }?>
      <!--/LIST:payment_types-->    
     </tr>
     
    <!--IF:first_page([[=page_no=]]!=1)-->
    <!---------LAST GROUP VALUE----------->		
    <tr>
    	<td colspan="5" class="report_sub_title" align="right"><b>[[.last_page_summary.]]</b></td>
    	
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=last_group_function_params=]]['deposit']);?></strong></td>
     <!--LIST:payment_types-->
        	 <?php if([[=payment_types.def_code=]]=='CREDIT_CARD'){?>
        	 <!--LIST:credit_card-->
                <!--LIST:currencies-->
                	 <?php 
                	 $cr =[[=payment_types.def_code=]].'_'.[[=credit_card.id=]].'_'.[[=currencies.id=]];
                	 if(isset([[=last_group_function_params=]][$cr]))
                     {
						//$total_card += [[=last_group_function_params=]][$cr];
					}?>	
   				<!--/LIST:currencies-->
             <!--/LIST:credit_card-->
        <?php echo '<td align="right" nowrap class="report_table_column" ><strong>'.System::Display_number(0).'</strong></td>';	
			}else if([[=payment_types.def_code=]]!='DEBIT' && [[=payment_types.def_code=]]!='FOC'){?>
        		<!--LIST:currencies-->
                <?php $tt = [[=payment_types.def_code=]].'_'.[[=currencies.id=]]; if(isset([[=last_group_function_params=]][$tt])){
					echo '<td align="right" nowrap class="report_table_column" ><strong>'.System::Display_number([[=last_group_function_params=]][$tt]).'</strong></td>';	
				}else{
					echo '<td align="right" nowrap class="report_table_column" >&nbsp;</td>';
				}?>
   				<!--/LIST:currencies-->
         <?php }else if([[=payment_types.def_code=]]=='DEBIT'){?>
					<td align="right" nowrap class="report_table_column" ><strong><?php echo System::display_number([[=last_group_function_params=]]['debit']);?></strong></td>	
         <?php }else if([[=payment_types.def_code=]]=='FOC'){?>
                    <td align="right" nowrap class="report_table_column" ><strong><?php echo System::display_number([[=last_group_function_params=]]['foc']);?></strong></td>
         <?php }?>
        <!--/LIST:payment_types-->
         	<td align="right" nowrap class="report_table_column" ><strong><?php echo System::display_number([[=last_group_function_params=]]['total']);?></strong></td>
          	<td align="right" nowrap class="report_table_column" >&nbsp;</td>
       </tr>
	<!--/IF:first_page-->
	<?php $total_card_vnd = 0;
        $total_card_usd =0; ?>
    <!--LIST:items-->
    <?php if([[=items.group=]]==1){
		$cond = ' id='.[[=items.r_id=]].'&cmd=group_invoice&customer_id='.[[=items.customer_id=]];
	}else{
		$cond = ' traveller_id='.[[=items.rt_id=]];
	}
	?>
	<tr bgcolor="white">
		<td nowrap="nowrap" valign="top" align="right" class="report_table_column">[[|items.stt|]]</td>
        <td nowrap align="center" class="report_table_column" width="30px"><a target="_blank" href="?page=view_traveller_folio&folio_id=[[|items.id|]]&<?php echo $cond;?>">[[|items.code|]]</a></td>
        <td nowrap="nowrap" valign="top" align="right" class="report_table_column">[[|items.vat_code|]]</td>
        <td align="left" class="report_table_column" ><div style="float:left;width:150px;font-size:11px;">
            <!--IF:cond(isset([[=items.booking_code=]]) and trim([[=items.booking_code=]]))-->[[.booking_code.]]: [[|items.booking_code|]]<br><!--/IF:cond-->[[|items.guest_name|]]<br/> [[.traveller.]]:[[|items.full_name|]] <br/> [[.recode.]]: [[|items.r_id|]]<br>
           <!--IF:cond_r([[=items.room_name=]]!='')--><strong>P:[[|items.room_name|]]</strong> <!--/IF:cond_r-->
         </div></td>
      <td nowrap align="center" class="report_table_column" ><?php echo date('d/m/Y',[[=items.time=]]);?></td>
        <td align="right" nowrap class="report_table_column" >[[|items.deposit|]]</td>
        <!--LIST:payment_types-->
        	 <?php if([[=payment_types.def_code=]]=='CREDIT_CARD')
             { 
                $card_vnd = 0;
                $card_usd =0;?>
        	 <!--LIST:credit_card-->
                <!--LIST:currencies-->
                	 <?php 
                     $cr = [[=payment_types.def_code=]].'_'.[[=credit_card.id=]].'_'.[[=currencies.id=]]; 
                     if(isset($this->map['items']['current'][$cr]))
                     {
                        if([[=currencies.id=]]=='USD')
                        {
                            $card_usd += $this->map['items']['current'][$cr];
                            $total_card_usd +=$card_usd;
                        }
				            
                        if([[=currencies.id=]]=='VND')
                        {
                            $card_vnd += $this->map['items']['current'][$cr];
                            $total_card_vnd += $card_vnd;
                        }
                            
						
                        
                        
					}
                    ?>	
   				<!--/LIST:currencies-->
             <!--/LIST:credit_card-->
             <!--LIST:currencies-->
                <?php 
                    if([[=currencies.id=]]=='VND')
    					echo '<td align="right" nowrap class="report_table_column" >'.System::Display_number($card_vnd).'</td>';
                    if([[=currencies.id=]]=='USD')
                        echo '<td align="right" nowrap class="report_table_column" >'.System::Display_number($card_usd).'</td>';
				?>
				<!--/LIST:currencies-->
        <?php 
			}
            else 
            if([[=payment_types.def_code=]]!='DEBIT' && [[=payment_types.def_code=]]!='FOC'){?>
        		<!--LIST:currencies-->
                <?php 
                    $tt = [[=payment_types.def_code=]].'_'.[[=currencies.id=]]; 
                    if(isset($this->map['items']['current'][$tt]))
                    {
    					echo '<td align="right" nowrap class="report_table_column" >'.System::Display_number($this->map['items']['current'][$tt]).'</td>';	
    				   
                    }
                    else
                    {
    					echo '<td align="right" nowrap class="report_table_column" >&nbsp;</td>';
				}?>
   				<!--/LIST:currencies-->
         <?php }else if([[=payment_types.def_code=]]=='DEBIT'){?>
					<td align="right" nowrap class="report_table_column" ><?php echo System::Display_number([[=items.debit=]]);?></td>	
         <?php }else if([[=payment_types.def_code=]]=='FOC'){?>
                    <td align="right" nowrap class="report_table_column" ><?php echo System::Display_number([[=items.foc=]]);?></td>
         <?php }?>
        <!--/LIST:payment_types-->
        
        <td nowrap align="right"><span class="report_table_column"><?php echo System::display_number([[=items.total=]]);?></span></td>
        <td nowrap align="center">[[|items.user_id|]]</td>
	</tr>
   	<!--/LIST:items-->
    
    <tr>
    	<td colspan="5" class="report_sub_title" align="right"><b><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
    	
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['deposit']);?></strong></td>
       
     <!--LIST:payment_types-->
        	 <?php 
        	 if([[=payment_types.def_code=]]=='CREDIT_CARD')
        	 {
        	   ?>
               <!--LIST:currencies-->
                <?php 
                if([[=currencies.id=]]=='VND')
					echo '<td align="right" nowrap class="report_table_column" ><strong>'.System::Display_number($total_card_vnd).'</strong></td>';
                if([[=currencies.id=]]=='USD')
                    echo '<td align="right" nowrap class="report_table_column" ><strong>'.System::Display_number($total_card_usd).'</strong></td>';
				?>
   				<!--/LIST:currencies-->
               <?php 	
			}
			else if([[=payment_types.def_code=]]!='DEBIT' && [[=payment_types.def_code=]]!='FOC'){?>
        		<!--LIST:currencies-->
                <?php $tt = [[=payment_types.def_code=]].'_'.[[=currencies.id=]]; if(isset([[=group_function_params=]][$tt])){
					echo '<td align="right" nowrap class="report_table_column" ><strong>'.System::Display_number([[=group_function_params=]][$tt]).'</strong></td>';	
				}else{
					echo '<td align="right" nowrap class="report_table_column" >&nbsp;</td>';
				}?>
   				<!--/LIST:currencies-->
         <?php }else if([[=payment_types.def_code=]]=='DEBIT'){?>
					<td align="right" nowrap class="report_table_column" ><strong><?php echo System::Display_number([[=group_function_params=]]['debit']);?></strong></td>	
         <?php }else if([[=payment_types.def_code=]]=='FOC'){?>
                    <td align="right" nowrap class="report_table_column" ><strong><?php echo System::Display_number([[=group_function_params=]]['foc']);?></strong></td>
         <?php }?>
        <!--/LIST:payment_types-->
         	<td align="right" nowrap class="report_table_column" ><strong><?php echo System::Display_number([[=group_function_params=]]['total']);?></strong></td>
          	<td align="right" nowrap class="report_table_column" >&nbsp;</td>
       </tr>
    	<!--/IF:cond_d-->
        
        
</table>
</div>
</div>