<style><!---------REPORT----------->
<style>
	.report_table_column{
		width:65px;	
	}
	.simple-layout-middle{
		width:100%;	
	}
</style>
<?php $i=0;$j=0;$k=0;?>
<!--LIST:payment_types--><?php $i++;?><!--/LIST:payment_types-->
<!--LIST:currencies--><?php $j++;?><!--/LIST:currencies-->
<!--LIST:credit_card--><?php $k++;?><!--/LIST:credit_card-->
<tr>
<td>
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px; font-family:Arial, Helvetica, sans-serif; border-collapse:collapse;">
	<tr valign="middle" bgcolor="#EFEFEF">
	  <th width="10px" rowspan="3" class="report-table-header">[[.stt.]]</th>
	  <th width="100px" rowspan="3" class="report-table-header">[[.order_id.]]</th>
      <th width="100px" rowspan="3" class="report-table-header">[[.recode.]]</th>
	  <th width="100px" rowspan="3" class="report-table-header">[[.date.]]</th>
      <th width="120px" rowspan="3" class="report-table-header">[[.customer_name.]]</th>
	  <th width="40px" rowspan="3" class="report-table-header" >[[.table_name.]]</th>
      <th width="60px" rowspan="3" class="report-table-header">[[.total_before_discount.]]</th>
      <th width="60px" rowspan="3" class="report-table-header">[[.discount.]]</th>
      <th width="60px" rowspan="3" class="report-table-header">[[.service_charge.]]</th>
      <th width="60px" rowspan="3" class="report-table-header">[[.tax.]]</th>
       <th width="60px" rowspan="3" class="report-table-header">[[.discount_after_tax.]]</th>
      <th width="60px" rowspan="3" class="report-table-header">[[.deposit.]]</th>
	  <th colspan="<?php echo ($i+$j-1);?>" class="report-table-header">[[.payment.]]</th>
	  <th width="60px" rowspan="3" class="report-table-header">[[.total.]]</th>     
	  <th width="60px" rowspan="3" class="report-table-header">[[.user.]]</th>
      <th width="150px" rowspan="3" class="report-table-header">[[.note.]]</th>
	</tr>
    <tr valign="middle" bgcolor="#EFEFEF">
    <!--LIST:payment_types-->
        <?php if([[=payment_types.def_code=]]=='CREDIT_CARD'){?>
        	<th class="report-table-header">[[|payment_types.name|]]</th>
        <?php }elseif([[=payment_types.def_code=]]=='CASH'){?>
        	<th class="report-table-header" colspan="<?php echo $j;?>">[[|payment_types.name|]]</th>
         <?php }else{?>
         	<th class="report-table-header">[[|payment_types.name|]]</th>
         <?php }?>
    <!--/LIST:payment_types-->
    </tr>
    <tr valign="middle" bgcolor="#EFEFEF">
     <!--LIST:payment_types-->
      	<?php if([[=payment_types.def_code=]]=='CREDIT_CARD'){?>
                <th class="report-table-header" >&nbsp;</th>
        <?php }else if([[=payment_types.def_code=]]=='CASH'){?>
        	 <!--LIST:currencies-->
				<th class="report-table-header">[[|currencies.id|]]</th>
   			 <!--/LIST:currencies-->
         <?php }else{?>
         		<th class="report-table-header"></th>
         <?php }?>
      <!--/LIST:payment_types-->    
     </tr>
	<!--IF:first_page([[=page_no=]]!=1)-->
  <?php $card = 0; ?>
<!---------LAST GROUP VALUE----------->	
		<tr>
            <td colspan="6" class="report_sub_title" align="right"><b>[[.last_page_summary.]]</b></td>
            <td align="right" class="report_table_column"><strong class="change_num"><?php echo (([[=last_group_function_params=]]['before_discount']==0)?'':System::display_number(round([[=last_group_function_params=]]['before_discount'])));?></strong></td>
            <td align="right" class="report_table_column"><strong class="change_num"><?php echo (([[=last_group_function_params=]]['total_discount']==0)?'':System::display_number(round([[=last_group_function_params=]]['total_discount'])));?></strong></td>
            <td align="right" class="report_table_column"><strong class="change_num"><?php echo (([[=last_group_function_params=]]['fee_rate']==0)?'':System::display_number(round([[=last_group_function_params=]]['fee_rate'])));?></strong></td>
            <td align="right" class="report_table_column"><strong class="change_num"><?php echo (([[=last_group_function_params=]]['tax']==0)?'':System::display_number(round([[=last_group_function_params=]]['tax'])));?></strong></td>
            <td align="right" class="report_table_column"><strong class="change_num"><?php echo (([[=last_group_function_params=]]['total_discount_after_tax']==0)?'':System::display_number(round([[=last_group_function_params=]]['total_discount_after_tax'])));?></strong></td>                        
            <td align="right" class="report_table_column"><strong class="change_num"><?php echo (([[=last_group_function_params=]]['deposit'])?'':System::display_number(round([[=last_group_function_params=]]['deposit'])));?></strong></td>
            
         <!--LIST:payment_types-->
            	 <?php if([[=payment_types.def_code=]]=='CREDIT_CARD'){?>
            	 <!--LIST:credit_card-->
                    <!--LIST:currencies-->
                    	 <?php $cr = [[=payment_types.def_code=]].'_'.[[=credit_card.id=]].'_'.[[=currencies.id=]]; if(isset([[=last_group_function_params=]][$cr])){
    						$card += (([[=last_group_function_params=]][$cr]==0)?0:([[=last_group_function_params=]][$cr]));	
    					}?>	
       				<!--/LIST:currencies-->
                 <!--/LIST:credit_card-->
                 
            <?php echo '<td align="right"  class="report_table_column" ><strong class="change_num">'.System::Display_number($card).'</strong></td>';	
    		}else if([[=payment_types.def_code=]]=='CASH'){?>
            		<!--LIST:currencies-->
                    <?php $tt = [[=payment_types.def_code=]].'_'.[[=currencies.id=]]; if(isset([[=group_function_params=]][$tt])){
    					echo '<td align="right"  class="report_table_column" ><strong class="change_num">'.(([[=last_group_function_params=]][$tt]==0)?'':System::Display_number([[=last_group_function_params=]][$tt])).'</strong></td>';	
    				}else{
    					echo '<td align="right"  class="report_table_column" >&nbsp;</td>';
    				}?>
       				<!--/LIST:currencies-->
             <?php }else if([[=payment_types.def_code=]]=='DEBIT'){?>
    					<td align="right"  class="report_table_column" ><strong class="change_num"><?php echo (([[=last_group_function_params=]]['debit']==0)?'':System::Display_number([[=last_group_function_params=]]['debit']));?></strong></td>	
             <?php }else if([[=payment_types.def_code=]]=='FOC'){?>
                        <td align="right"  class="report_table_column" ><strong class="change_num"><?php echo (([[=last_group_function_params=]]['foc']==0)?'':System::Display_number([[=last_group_function_params=]]['foc']));?></strong></td>
             <?php }else if([[=payment_types.def_code=]]=='ROOM CHARGE'){?>
                        <td align="right"  class="report_table_column" ><strong class="change_num"><?php echo (([[=last_group_function_params=]]['room']==0)?'':System::Display_number([[=last_group_function_params=]]['room']));?></strong></td>
             <?php }else if([[=payment_types.def_code=]]=='BANK'){?>
                        <td align="right"  class="report_table_column" ><strong class="change_num"><?php echo (([[=last_group_function_params=]]['bank']==0)?'':System::Display_number([[=last_group_function_params=]]['bank']));?></strong></td>
             <?php }else if([[=payment_types.def_code=]]=='REFUND'){?>
                        <td align="right"  class="report_table_column" ><strong class="change_num"><?php echo (([[=last_group_function_params=]]['refund']==0)?'':System::Display_number([[=last_group_function_params=]]['refund']));?></strong></td>
             <?php }?>
            <!--/LIST:payment_types-->
            
            	<td align="right"  class="report_table_column" ><strong><?php echo System::Display_number([[=last_group_function_params=]]['total']);?></strong></td>
                <td align="right"  class="report_table_column" >&nbsp;</td>
                <td align="right"  class="report_table_column" >&nbsp;</td>
		</tr>
	<!--/IF:first_page-->
	<!--LIST:items-->
<!---------GROUP----------->
<!---------CELLS----------->
	<tr <?php echo Draw::hover('#FFFF66');?> bgcolor="white">
		<td align="center" class="report_table_column">[[|items.stt|]]</td>
        <td align="center" class="report_table_column"><a target="_blank" href="<?php echo Url::build('touch_bar_restaurant',array('cmd'=>'detail',md5('act')=>md5('print_bill'),md5('preview')=>1,'id'=>[[=items.id=]],'bar_id')); //echo Url::build('touch_bar_restaurant',array('bar_reservation_bar_id', 'bar_reservation_receptionist_id')+array('cmd'=>'edit','id'=>[[=items.id=]],'bar_id'=>[[=items.bar_id=]])); ?>">[[|items.code|]]</a></td>
        <td align="center" class="report_table_column"><a target="_blank" href="?page=reservation&cmd=edit&id=<?php echo [[=items.reservation_id=]]; ?>">[[|items.reservation_id|]]</a></td>
        <td align="center" class="report_table_column" ><?php echo date('H:i d/m/Y',[[=items.time_out=]]);?></td>
        
        <td align="center" class="report_table_column">[[|items.customer_name|]] <br />[[|items.traveller_name|]]</td>
        <td align="center" class="report_table_column" >[[|items.table_name|]]</td>
        <td align="right" class="report_table_column change_num " ><?php echo (([[=items.before_discount=]]==0)?'0':System::display_number(round([[=items.before_discount=]])));?></td>
        <td align="right" class="report_table_column change_num " ><?php echo ((([[=items.total_discount=]]+[[=items.product_discount=]])==0)?'':System::display_number(round([[=items.total_discount=]]+[[=items.product_discount=]])));?></td>
        <td align="right" class="report_table_column change_num " ><?php echo (([[=items.fee_rate=]]==0)?'':System::display_number(round([[=items.fee_rate=]])));?></td>        
        <td align="right" class="report_table_column change_num " ><?php echo (([[=items.tax=]]==0)?'':System::display_number(round([[=items.tax=]])));?></td>
        <td align="right" class="report_table_column change_num " ><?php echo (([[=items.total_discount_after_tax=]]==0)?'':System::display_number(round([[=items.total_discount_after_tax=]])));?></td>
        <td align="right" class="report_table_column change_num " ><?php echo (([[=items.deposit=]]==0)?'':System::display_number([[=items.deposit=]]));?></td>
       <!--LIST:payment_types-->
       
        	 <?php if([[=payment_types.def_code=]]=='CREDIT_CARD'){ $card_item = 0; $count = 0;?>
        	 <!--LIST:credit_card-->
             	<?php $count ++;?>
                <!--LIST:currencies-->
                	 <?php $cr = [[=payment_types.def_code=]].'_'.[[=credit_card.id=]].'_'.[[=currencies.id=]]; 
                     if(isset($this->map['items']['current'][$cr]))
                     {					
						$card_item += $this->map['items']['current'][$cr];
					 }
                     ?>	
   				<!--/LIST:currencies-->
             <!--/LIST:credit_card-->
        <?php echo '<td align="right" class="report_table_column change_num" >'.System::Display_number($card_item).'</td>';	
		}else if([[=payment_types.def_code=]]=='CASH'){?>
        		<!--LIST:currencies-->
                <?php $tt = [[=payment_types.def_code=]].'_'.[[=currencies.id=]]; 
                if(isset($this->map['items']['current'][$tt]))
                {
					echo '<td align="right" class="report_table_column change_num" >'.System::Display_number($this->map['items']['current'][$tt]).'</td>';	
				}else{
					echo '<td align="right" class="report_table_column" >&nbsp;</td>';
				}?>
   				<!--/LIST:currencies-->
         <?php }else if([[=payment_types.def_code=]]=='DEBIT'){?>
					<td align="right" class="report_table_column change_num" ><?php echo ([[=items.debit=]]==0?'':System::Display_number([[=items.debit=]]));?></td>	
         <?php }else if([[=payment_types.def_code=]]=='FOC'){?>
                    <td align="right" class="report_table_column change_num" ><?php echo ([[=items.foc=]]==0?'':System::Display_number([[=items.foc=]]));?></td>
         <?php }else if([[=payment_types.def_code=]]=='ROOM CHARGE'){?>
                    <td align="right" class="report_table_column change_num" ><?php echo ([[=items.room=]]==0?'':System::Display_number([[=items.room=]]));?></td>
         <?php }else if([[=payment_types.def_code=]]=='BANK'){?>
                    <td align="right" class="report_table_column change_num" ><?php echo ([[=items.bank=]]==0?'':System::Display_number([[=items.bank=]]));?></td>
         <?php }else if([[=payment_types.def_code=]]=='REFUND'){?>
                    <td align="right" class="report_table_column change_num" ><?php echo ([[=items.refund=]]==0?'':System::Display_number([[=items.refund=]]));?></td>
         <?php }?>
        <!--/LIST:payment_types-->
        
        <td align="right" class="report_table_column change_num" ><?php echo [[=items.total=]]?System::display_number([[=items.total=]]):'0';?></td>
        <td align="center" class="report_table_column" >[[|items.receptionist_id|]]</td>
        <td align="left" class="report_table_column" >[[|items.note|]]</td>
	</tr>
	<!--/LIST:items-->
    <?php $total_card = 0;?>
	<!---------TOTAL GROUP FUNCTION----------->	
    <tr>
		<td colspan="6" class="report_sub_title" align="right"><b><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
    	<td align="right" class="report_table_column"><strong class="change_num"><?php echo (([[=group_function_params=]]['before_discount']==0)?'':System::display_number(round([[=group_function_params=]]['before_discount'])));?></strong></td>
        <td align="right" class="report_table_column"><strong class="change_num"><?php echo (([[=group_function_params=]]['total_discount']==0)?'':System::display_number(round([[=group_function_params=]]['total_discount'])));?></strong></td>
        <td align="right" class="report_table_column"><strong class="change_num"><?php echo (([[=group_function_params=]]['fee_rate']==0)?'':System::display_number(round([[=group_function_params=]]['fee_rate'])));?></strong></td>
        <td align="right" class="report_table_column"><strong class="change_num"><?php echo (([[=group_function_params=]]['tax']==0)?'':System::display_number(round([[=group_function_params=]]['tax'])));?></strong></td>
        <td align="right" class="report_table_column"><strong class="change_num"><?php echo (([[=group_function_params=]]['total_discount_after_tax']==0)?'':System::display_number(round([[=group_function_params=]]['total_discount_after_tax'])));?></strong></td>
        <td align="right" class="report_table_column"><strong class="change_num"><?php echo (([[=group_function_params=]]['deposit']==0)?'':System::display_number(round([[=group_function_params=]]['deposit'])));?></strong></td>
    
     <!--LIST:payment_types-->
        	 <?php if([[=payment_types.def_code=]]=='CREDIT_CARD'){?>
        	 <!--LIST:credit_card-->
                <!--LIST:currencies-->
                	 <?php $cr = [[=payment_types.def_code=]].'_'.[[=credit_card.id=]].'_'.[[=currencies.id=]]; if(isset([[=group_function_params=]][$cr])){
						//echo '<td align="right" class="report_table_column" ><strong>'.(([[=group_function_params=]][$cr]==0)?'':System::Display_number([[=group_function_params=]][$cr])).'</strong></td>';	
						$total_card += (([[=group_function_params=]][$cr]==0)?0:([[=group_function_params=]][$cr]));
					}else{
						//echo '<td align="right" class="report_table_column" >&nbsp;</td>';
					}?>	
   				<!--/LIST:currencies-->
             <!--/LIST:credit_card-->
        <?php echo '<td align="right" class="report_table_column"><strong class="change_num">'.System::Display_number($total_card).'</strong></td>';	
		}else if([[=payment_types.def_code=]]=='CASH'){?>
        		<!--LIST:currencies-->
                <?php $tt = [[=payment_types.def_code=]].'_'.[[=currencies.id=]]; if(isset([[=group_function_params=]][$tt])){
					echo '<td align="right" class="report_table_column" ><strong class="change_num">'.(([[=group_function_params=]][$tt]==0)?'':System::Display_number([[=group_function_params=]][$tt])).'</strong></td>';	
				}else{
					echo '<td align="right" class="report_table_column" >&nbsp;</td>';
				}?>
   				<!--/LIST:currencies-->
         <?php }else if([[=payment_types.def_code=]]=='DEBIT'){?>
					<td align="right" class="report_table_column" ><strong class="change_num"><?php echo (([[=group_function_params=]]['debit']==0)?'':System::Display_number([[=group_function_params=]]['debit']));?></strong></td>	
         <?php }else if([[=payment_types.def_code=]]=='FOC'){?>
                    <td align="right" class="report_table_column" ><strong class="change_num"><?php echo (([[=group_function_params=]]['foc']==0)?'':System::Display_number([[=group_function_params=]]['foc']));?></strong></td>
         <?php }else if([[=payment_types.def_code=]]=='ROOM CHARGE'){?>
                    <td align="right" class="report_table_column" ><strong class="change_num"><?php echo (([[=group_function_params=]]['room']==0)?'':System::Display_number([[=group_function_params=]]['room']));?></strong></td>
         <?php }else if([[=payment_types.def_code=]]=='BANK'){?>
                    <td align="right" class="report_table_column" ><strong class="change_num"><?php echo (([[=group_function_params=]]['bank']==0)?'':System::Display_number([[=group_function_params=]]['bank']));?></strong></td>
         <?php }else if([[=payment_types.def_code=]]=='REFUND'){?>
                    <td align="right" class="report_table_column" ><strong class="change_num"><?php echo (([[=group_function_params=]]['refund']==0)?'':System::Display_number([[=group_function_params=]]['refund']));?></strong></td>
         <?php }?> 
        <!--/LIST:payment_types-->
        
			 <td align="right" class="report_table_column" ><strong class="change_num"><?php echo System::Display_number([[=group_function_params=]]['total']);?></strong></td>
            <td align="right" class="report_table_column">&nbsp;</td>
            <td align="right" class="report_table_column">&nbsp;</td>
            
		</tr>
</table>
</td>
</tr>