<style>
.simple-layout-middle{width:100%;}
</style>
<!---------HEADER----------->
[[|page_no|]]
<!--IF:first_page([[=page_no=]] <= 1)-->
<div class="report-bound"> 
    <link rel="stylesheet" href="skins/default/report.css"/>
    <table cellpadding="10" cellspacing="0" width="100%">
        <tr><td >
    		<table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
    			<tr style="font-size:11px; font-weight:normal">
                    <td align="left" width="80%">
                        <strong><?php echo HOTEL_NAME;?></strong>
                        <br />
                        <strong><?php echo HOTEL_ADDRESS;?></strong>
                    </td>
                    <td align="right" style="padding-right:10px;" >
                        <strong>[[.template_code.]]</strong>
                    </td>
                </tr>
                <tr>
    				<td colspan="2"> 
                        <div style="width:100%; text-align:center;">
                            <font class="report_title specific" >[[.message_revenue_report.]]<br /></font>
                            <span style="font-family:'Times New Roman', Times, serif;">
                                [[.from.]]&nbsp;[[|from_date|]]&nbsp;[[.to.]]&nbsp;[[|to_date|]]
                            </span> 
                        </div>
                    </td>
    			</tr>	
    		</table>
        </td></tr>
    </table>		
</div>

<style type="text/css">
.specific {font-size: 19px !important;}
</style>


<!---------SEARCH----------->
<table width="100%" height="100%" bgcolor="#B5AEB5" style="margin: 10px  auto 10px;" id="search">
    <tr><td>
    	<div style="width:100%;height:100%;text-align:center;vertical-align:middle;background-color:white;">
        	<div>
            	<table width="100%">
                    <tr><td >
                        <form name="SearchForm" method="post">
                            <table style="margin: 0 auto;">
                                <tr>
                                	<td>[[.line_per_page.]]</td>
                                	<td><input name="line_per_page" type="text" id="line_per_page" value="[[|line_per_page|]]" size="4" maxlength="2" style="text-align:right"/></td>
                                    <td>[[.no_of_page.]]</td>
                                	<td><input name="no_of_page" type="text" id="no_of_page" value="[[|no_of_page|]]" size="4" maxlength="2" style="text-align:right"/></td>
                                    <!--
                                    <td>[[.from_page.]]</td>
                                	<td><input name="start_page" type="text" id="start_page" value="[[|start_page|]]" size="4" maxlength="4" style="text-align:right"/></td>
                                    -->
                                    <td>|</td>
                                    <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                                    <td>[[.hotel.]]</td>
                                	<td><select name="portal_id" id="portal_id"></select></td>
                                    <?php }?>
                                    <td>[[.room.]]</td>
                                	<td><select name="room_id" id="room_id"></select></td>
                                    <td>[[.from.]]</td>
                                	<td><input name="from_date" type="text" id="from_date" style="width: 80px;"/></td>
                                    <td>[[.to.]]</td>
                                	<td><input name="to_date" type="text" id="to_date" style="width: 80px;"/></td>
                                    <td><input type="submit" name="do_search" value="  [[.report.]]  "/></td>
                                </tr>
                            </table>
                        </form>
                    </td></tr>
                </table>
        	</div>
    	</div>
    </td></tr>
</table>

<style type="text/css">
input[type="submit"]{width:100px;}
a:visited{color:#003399}
@media print
{
    #search{display:none}
}
</style>
<script type="text/javascript">
jQuery(document).ready(
    function()
    {
        jQuery("#from_date").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>,1 - 1, 1)});
    	jQuery("#to_date").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1)});
    }
);
</script>
<!--/IF:first_page-->



<!---------REPORT----------->	
<!--IF:check_data(!isset([[=has_no_data=]]))-->
<?php $i=0;$j=0;$k=0;?>
<!--LIST:payment_types--><?php $i++;?><!--/LIST:payment_types-->
<!--LIST:currencies--><?php $j++;?><!--/LIST:currencies-->
<!--LIST:credit_card--><?php $k++;?><!--/LIST:credit_card-->
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr valign="middle" bgcolor="#EFEFEF">
	  <th width="10px" rowspan="3" class="report-table-header">[[.stt.]]</th>
	  <th width="50px" rowspan="3" class="report-table-header">[[.bill_number.]]</th>
	  <th width="100px" rowspan="3" class="report-table-header">[[.room_name.]]</th>
      <th width="120px" rowspan="3" class="report-table-header">[[.guest_name.]]</th>
	  <th width="40px" rowspan="3" class="report-table-header" >[[.service_name.]]</th>  
	  <th width="60px" rowspan="3" class="report-table-header">[[.quantity.]]</th>
      <th width="60px" rowspan="3" class="report-table-header">[[.price.]]</th>
      <th width="60px" rowspan="3" class="report-table-header">[[.amount.]]</th>
	  <th colspan="<?php echo ($i+1);?>" class="report-table-header">[[.payment.]]</th>
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
		<tr><td colspan="4" class="report_sub_title" align="right"><b>[[.last_page_summary.]]</b></td>
        <td>&nbsp;</td>
    	<td align="right" class="report_table_column"><strong><?php echo (([[=last_group_function_params=]]['deposit'])?'':System::display_number([[=last_group_function_params=]]['deposit']));?></strong></td>
     <!--LIST:payment_types-->
        	 <?php if([[=payment_types.def_code=]]=='CREDIT_CARD'){?>
        	 <!--LIST:credit_card-->
                <!--LIST:currencies-->
                	 <?php $cr = [[=payment_types.def_code=]].'_'.[[=credit_card.id=]].'_'.[[=currencies.id=]]; if(isset([[=last_group_function_params=]][$cr])){
						$card += (([[=last_group_function_params=]][$cr]==0)?0:([[=last_group_function_params=]][$cr]));	
					}?>	
   				<!--/LIST:currencies-->
             <!--/LIST:credit_card-->
             
        <?php echo '<td align="right"  class="report_table_column" ><strong>'.System::Display_number($card).'</strong></td>';	
		}else if([[=payment_types.def_code=]]=='CASH'){?>
        		<!--LIST:currencies-->
                <?php $tt = [[=payment_types.def_code=]].'_'.[[=currencies.id=]]; if(isset([[=group_function_params=]][$tt])){
					echo '<td align="right"  class="report_table_column" ><strong>'.(([[=last_group_function_params=]][$tt]==0)?'':System::Display_number([[=last_group_function_params=]][$tt])).'</strong></td>';	
				}else{
					echo '<td align="right"  class="report_table_column" >&nbsp;</td>';
				}?>
   				<!--/LIST:currencies-->
         <?php }else if([[=payment_types.def_code=]]=='DEBIT'){?>
					<td align="right"  class="report_table_column" ><strong><?php echo (([[=last_group_function_params=]]['debit']==0)?'':System::Display_number([[=last_group_function_params=]]['debit']));?></strong></td>	
         <?php }else if([[=payment_types.def_code=]]=='FOC'){?>
                    <td align="right"  class="report_table_column" ><strong><?php echo (([[=last_group_function_params=]]['foc']==0)?'':System::Display_number([[=last_group_function_params=]]['foc']));?></strong></td>
         <?php }else if([[=payment_types.def_code=]]=='ROOM CHARGE'){?>
                    <td align="right"  class="report_table_column" ><strong><?php echo (([[=last_group_function_params=]]['room']==0)?'':System::Display_number([[=last_group_function_params=]]['room']));?></strong></td>
         <?php }else if([[=payment_types.def_code=]]=='BANK'){?>
                    <td align="right"  class="report_table_column" ><strong><?php echo (([[=last_group_function_params=]]['bank']==0)?'':System::Display_number([[=last_group_function_params=]]['bank']));?></strong></td>
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
	<tr bgcolor="white">
		<td align="center" class="report_table_column">[[|items.stt|]]</td>
        <td align="center" class="report_table_column"><a href="<?php echo Url::build('massage_daily_summary',array('cmd'=>'edit','id'=>[[=items.reservation_room_id=]])); ?>">[[|items.reservation_room_id|]]</a></td>
        <td align="center" class="report_table_column" >[[|items.hotel_room_name|]]</td>
        <td align="center" class="report_table_column">[[|items.traveller_name|]]</td>
        <td align="center" class="report_table_column" >[[|items.product_name|]]</td>
        <td align="right" class="report_table_column" >[[|items.quantity|]]</td>
        <td align="right" class="report_table_column" >[[|items.price|]]</td>
        <td align="right" class="report_table_column" >[[|items.amount|]]</td>
       <!--LIST:payment_types-->
       <!--IF:pay_room([[=items.hotel_room_name=]]=='')-->
        	 <?php 
             if([[=payment_types.def_code=]]=='CREDIT_CARD')
             { 
                $card_item = 0; 
                $count = 0;
             ?>
        	 <!--LIST:credit_card-->
             	<?php $count ++;?>
                <!--LIST:currencies-->
                	 <?php $cr = [[=payment_types.def_code=]].'_'.[[=credit_card.id=]].'_'.[[=currencies.id=]]; if(isset($this->map['items']['current'][$cr]))
                     {	
						$card_item += $this->map['items']['current'][$cr];
				     }
                     else
                     {
					 }
                     ?>	
   				<!--/LIST:currencies-->
             <!--/LIST:credit_card-->
            <?php echo '<td align="right" class="report_table_column" >'.System::Display_number($card_item).'</td>';	
    		}
            else if([[=payment_types.def_code=]]=='CASH')
            {
            ?>
        		<!--LIST:currencies-->
                <?php 
                $tt = [[=payment_types.def_code=]].'_'.[[=currencies.id=]]; 
                if(isset($this->map['items']['current'][$tt]))
                {
					echo '<td align="right" class="report_table_column" >'.System::Display_number($this->map['items']['current'][$tt]).'</td>';	
				}
                else
                {
					echo '<td align="right" class="report_table_column" >&nbsp;</td>';
				}?>
   				<!--/LIST:currencies-->
         <?php }else if([[=payment_types.def_code=]]=='DEBIT'){?>
					<td align="right" class="report_table_column" ><?php echo ([[=items.debit=]]==0?'':System::Display_number([[=items.debit=]]));?></td>	
         <?php }else if([[=payment_types.def_code=]]=='FOC'){?>
                    <td align="right" class="report_table_column" ><?php echo ([[=items.foc=]]==0?'':System::Display_number([[=items.foc=]]));?></td>
         <?php }else if([[=payment_types.def_code=]]=='ROOM CHARGE'){?>
                    <td align="right" class="report_table_column" ><?php echo ([[=items.room=]]==0?'':System::Display_number([[=items.room=]]));?></td>
         <?php }else if([[=payment_types.def_code=]]=='BANK'){?>
                    <td align="right" class="report_table_column" ><?php echo ([[=items.bank=]]==0?'':System::Display_number([[=items.bank=]]));?></td>
         <?php }?>
        <!--ELSE-->
            <?php if([[=payment_types.def_code=]]=='CREDIT_CARD')
            { 
                $card_item = 0; $count = 0;
            ?>
        	 <!--LIST:credit_card-->
             	<?php $count ++;?>
                <!--LIST:currencies-->
                	 <?php $cr = [[=payment_types.def_code=]].'_'.[[=credit_card.id=]].'_'.[[=currencies.id=]]; if(isset($this->map['items']['current'][$cr])){
						//echo '<td align="right" nowrap class="report_table_column" >'.System::Display_number($this->map['items']['current'][$cr]).'</td>';	
						$card_item += $this->map['items']['current'][$cr];
					}else{
						//echo '<td align="right" class="report_table_column" >&nbsp;</td>';
					}?>	
   				<!--/LIST:currencies-->
             <!--/LIST:credit_card-->
        <?php echo '<td align="right" class="report_table_column" ></td>';	
		}else if([[=payment_types.def_code=]]=='CASH'){?>
        		<!--LIST:currencies-->
                <?php $tt = [[=payment_types.def_code=]].'_'.[[=currencies.id=]]; if(isset($this->map['items']['current'][$tt])){
					echo '<td align="right" class="report_table_column" ></td>';	
				}else{
					echo '<td align="right" class="report_table_column" ></td>';
				}?>
   				<!--/LIST:currencies-->
         <?php }else if([[=payment_types.def_code=]]=='DEBIT'){?>
					<td align="right" class="report_table_column" ></td>	
         <?php }else if([[=payment_types.def_code=]]=='FOC'){?>
                    <td align="right" class="report_table_column" ></td>
         <?php }else if([[=payment_types.def_code=]]=='ROOM CHARGE'){?>
                    <td align="right" class="report_table_column" ><?php echo ([[=items.room=]]==0?'':System::Display_number([[=items.room=]]));?></td>
         <?php }else if([[=payment_types.def_code=]]=='BANK'){?>
                    <td align="right" class="report_table_column" ></td>
         <?php }?>   
        <!--/IF:pay_room-->
        <!--/LIST:payment_types-->
        <td align="right" class="report_table_column" ><?php echo [[=items.total=]]?System::display_number([[=items.total=]]):'';?></td>
        <td align="center" class="report_table_column" >[[|items.user_id|]]</td>
        <td align="left" class="report_table_column" >[[|items.note|]]</td>
	</tr>
	<!--/LIST:items-->
    <?php $total_card = 0;?>
	<!---------TOTAL GROUP FUNCTION----------->	
    <tr>
		<td colspan="5" class="report_sub_title" align="right"><b><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['quantity']);?></strong></td>
        <td></td>
    	<td align="right" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['amount']);?></strong></td>
    
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
        <?php echo '<td align="right" class="report_table_column" ><strong>'.System::Display_number($total_card).'</strong></td>';	
		}else if([[=payment_types.def_code=]]=='CASH'){?>
        		<!--LIST:currencies-->
                <?php $tt = [[=payment_types.def_code=]].'_'.[[=currencies.id=]]; if(isset([[=group_function_params=]][$tt])){
					echo '<td align="right" class="report_table_column" ><strong>'.(([[=group_function_params=]][$tt]==0)?'':System::Display_number([[=group_function_params=]][$tt])).'</strong></td>';	
				}else{
					echo '<td align="right" class="report_table_column" >&nbsp;</td>';
				}?>
   				<!--/LIST:currencies-->
         <?php }else if([[=payment_types.def_code=]]=='DEBIT'){?>
					<td align="right" class="report_table_column" ><strong><?php echo (([[=group_function_params=]]['debit']==0)?'':System::Display_number([[=group_function_params=]]['debit']));?></strong></td>	
         <?php }else if([[=payment_types.def_code=]]=='FOC'){?>
                    <td align="right" class="report_table_column" ><strong><?php echo (([[=group_function_params=]]['foc']==0)?'':System::Display_number([[=group_function_params=]]['foc']));?></strong></td>
         <?php }else if([[=payment_types.def_code=]]=='ROOM CHARGE'){?>
                    <td align="right" class="report_table_column" ><strong><?php echo (([[=group_function_params=]]['room']==0)?'':System::Display_number([[=group_function_params=]]['room']));?></strong></td>
         <?php }else if([[=payment_types.def_code=]]=='BANK'){?>
                    <td align="right" class="report_table_column" ><strong><?php echo (([[=group_function_params=]]['bank']==0)?'':System::Display_number([[=group_function_params=]]['bank']));?></strong></td>
         <?php }?> 
        <!--/LIST:payment_types-->
        
			 <td align="right" class="report_table_column" ><strong><?php echo System::Display_number([[=group_function_params=]]['total']);?></strong></td>
            <td align="right" class="report_table_column">&nbsp;</td>
            <td align="right" class="report_table_column">&nbsp;</td>
		</tr>
</table>
<!--ELSE-->
<strong>[[.no_data.]]</strong>
<!--/IF:check_data-->