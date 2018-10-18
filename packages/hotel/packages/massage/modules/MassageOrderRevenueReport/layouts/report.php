<style>
.simple-layout-middle{width:100%;}
</style>
<!---------HEADER----------->
<!--IF:first_page([[=page_no=]] <= 1)-->
<div class="report-bound"> 
    <link rel="stylesheet" href="skins/default/report.css"/>
    <table cellpadding="10" cellspacing="0" width="100%">
        <tr><td >
    		<table cellspacing="0" width="100%" style="font-family:'Times New Roman', Times, serif">
    			<tr style="font-size:11px; font-weight:normal">
                    <td align="left" width="60%">
                        <strong><?php echo HOTEL_NAME;?></strong>
                        <br />
                        <strong><?php echo HOTEL_ADDRESS;?></strong>
                    </td>
                    <td align="right" style="padding-right:10px;" >
                        <strong>[[.template_code.]]</strong>
                        <br />
                        [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
                        <br />
                        [[.user_print.]]:<?php echo ' '.User::id();?>
                    </td>
                </tr>
                <tr>
    				<td colspan="2"> 
                        <div style="width:100%; text-align:center;">
                            <font class="report_title specific" >[[.bao_cao_thu_tien_theo_hoa_don.]]<br /></font>
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
                                	<td><input name="from_date" type="text" id="from_date" style="width: 80px;" onchange="changevalue();"/></td>
                                    <td>[[.hour_from.]]</td>
                                    <td><input name="hour_from" type="text" id="hour_from" style="width: 40px;"/></td>
                                    <td>[[.to.]]</td>
                                	<td><input name="to_date" type="text" id="to_date" style="width: 80px;" onchange="changefromday();"/></td>
                                    <td>[[.hour_to.]]</td>
                                    <td><input name="hour_to" type="text" id="hour_to" style="width: 40px;"/></td>
                                    <td>[[.code.]]</td>
                                	<td><input name="from_code" type="text" id="from_code" style="width: 40px;"/></td>
                                    <td>[[.to.]]</td>
                                    <td><input name="to_code" type="text" id="to_code" style="width: 40px;"/></td>
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
        jQuery("#from_date").datepicker();//({ minDate: new Date(<?php //echo BEGINNING_YEAR;?>,1 - 1, 1)});
    	jQuery("#to_date").datepicker();//({ minDate: new Date(<?php //echo BEGINNING_YEAR;?>, 1 - 1, 1)});
    }
);
</script>
<!--ELSE-->
<br />
<br />
<br />
<!--/IF:first_page-->



<!---------REPORT----------->	
<!--IF:check_data(!isset([[=has_no_data=]]))-->
<?php $i=0;$j=0;$k=0;?>
<!--LIST:payment_types--><?php $i++;?><!--/LIST:payment_types-->
<!--LIST:currencies--><?php $j++;?><!--/LIST:currencies-->
<!--LIST:credit_card--><?php $k++;?><!--/LIST:credit_card-->
<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound" style="font-size:11px; border-collapse:collapse;">
	<tr valign="middle" bgcolor="#EFEFEF">
	  <th width="10px" rowspan="3" class="report-table-header">[[.stt.]]</th>
	  <th width="10px" rowspan="3" class="report-table-header">[[.bill_number.]]</th>
      <th width="10px" rowspan="3" class="report-table-header">[[.recode.]]</th>
	  <th width="20px" rowspan="3" class="report-table-header">[[.room_name.]]</th>
      <th width="100px" rowspan="3" class="report-table-header">[[.guest_name.]]</th>
	  <th width="140px" rowspan="3" class="report-table-header" >[[.service_name.]]</th>  
	  <th width="10px" rowspan="3" class="report-table-header">[[.quantity.]]</th>
      <th width="40px" rowspan="3" class="report-table-header">[[.price.]]</th>
      <th width="40px" rowspan="3" class="report-table-header">[[.amount.]]</th>
      <th width="40px" rowspan="3" class="report-table-header">[[.discount_amount.]]</th>
      <th width="40px" rowspan="3" class="report-table-header">[[.discount_persent.]]</th>
	  <th colspan="<?php echo ($i+$j-1);?>" class="report-table-header">[[.payment.]]</th>
	  <th width="60px" rowspan="3" class="report-table-header">[[.total.]]</th>     
	  <th width="60px" rowspan="3" class="report-table-header">[[.user.]]</th>
      <th width="120px" rowspan="3" class="report-table-header">[[.note.]]</th>
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
		<tr><td colspan="6" class="report_sub_title" align="right"><b>[[.last_page_summary.]]</b></td>
    	<td align="right" class="report_table_column"><strong><?php echo System::display_number([[=last_group_function_params=]]['quantity']);?></strong></td>
        <td>&nbsp;</td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=last_group_function_params=]]['amount']);?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=last_group_function_params=]]['discount_amount']);?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number(round([[=last_group_function_params=]]['total_discount_amount_persent']));?></strong></td>
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
					<td align="right"  class="report_table_column" ><strong><?php echo (([[=last_group_function_params=]]['debit']<=0)?'':System::Display_number([[=last_group_function_params=]]['debit']));?></strong></td>	
         <?php }else if([[=payment_types.def_code=]]=='FOC'){?>
                    <td align="right"  class="report_table_column" ><strong><?php echo (([[=last_group_function_params=]]['foc']==0)?'':System::Display_number([[=last_group_function_params=]]['foc']));?></strong></td>
         <?php }else if([[=payment_types.def_code=]]=='ROOM CHARGE'){?>
                    <td align="right"  class="report_table_column" ><strong><?php echo (([[=last_group_function_params=]]['room']==0)?'':System::Display_number([[=last_group_function_params=]]['room']));?></strong></td>
         <?php }else if([[=payment_types.def_code=]]=='BANK'){?>
                    <td align="right"  class="report_table_column" ><strong><?php echo (([[=last_group_function_params=]]['bank']==0)?'':System::Display_number([[=last_group_function_params=]]['bank']));?></strong></td>
         <?php }else if([[=payment_types.def_code=]]=='REFUND'){?>
                    <td align="right"  class="report_table_column" ><strong><?php echo (([[=last_group_function_params=]]['refund']==0)?'':System::Display_number([[=last_group_function_params=]]['refund']));?></strong></td>
         <?php }?>
        <!--/LIST:payment_types-->
        	<td align="right"  class="report_table_column" ><strong><?php echo System::Display_number([[=last_group_function_params=]]['total']);?></strong></td>
            <td align="right"  class="report_table_column" >&nbsp;</td>
            <td align="right"  class="report_table_column" >&nbsp;</td>
		</tr>
	<!--/IF:first_page-->
    <?php
        $i=1;
        $is_rowspan = false; 
    ?>
	<!--LIST:items-->
<!---------GROUP----------->
<!---------CELLS----------->
	<tr bgcolor="white">
    <?php 
        $k = [[=count_items=]][[[=items.reservation_room_id=]]]['num'];
        //echo $k.'-'.[[=items.reservation_room_code=]].'-'.[[=items.reservation_id=]];
        if($is_rowspan == false)
        {
    ?>
		<td align="center" class="report_table_column" rowspan="<?php echo $k; ?>">[[|items.stt|]]</td>
        <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>"><a href="<?php echo Url::build('massage_daily_summary',array('cmd'=>'invoice','id'=>[[=items.reservation_room_id=]]));?>" target="_blank">[[|items.reservation_room_id|]]</a></td>
        <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>"><a target="_blank" href="?page=reservation&cmd=edit&id=[[|items.mrr_id|]]">[[|items.mrr_id|]]</a></td>
        <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>">[[|items.hotel_room_name|]]</td>
        <td align="left" class="report_table_column" rowspan="<?php echo $k; ?>">[[|items.traveller_name|]]</td>
    <?php
        } 
    ?>
    
    <?php 
        if($k ==0 || $k ==1 || $i<=$k)
        {
    ?>   
        <td align="left" class="report_table_column" >[[|items.product_name|]]</td>
        <td align="center" class="report_table_column" ><?php echo System::display_number([[=items.quantity=]]); ?></td>
        <td align="right" class="report_table_column" ><?php echo System::display_number([[=items.price=]]); ?></td>
        <td align="right" class="report_table_column" ><?php echo System::display_number([[=items.amount=]]); ?></td>
    <?php
        $i++;
        }
    ?>
    
    <?php 
        if($is_rowspan == false)
        {
    ?>
       <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo System::display_number([[=items.discount_amount=]]);?></td>
       <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo System::display_number(round([[=items.total_discount_amount_persent=]]));?></td>
       <!--LIST:payment_types-->
       
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
					echo '<td align="right" class="report_table_column" rowspan="'.$k.'">'.System::Display_number($this->map['items']['current'][$tt]).'</td>';	
				}
                else
                {
					echo '<td align="right" class="report_table_column" rowspan="'.$k.'">&nbsp;</td>';
				}?>
   				<!--/LIST:currencies-->
         <?php }else if([[=payment_types.def_code=]]=='DEBIT'){?>
					<td align="right" class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo ([[=items.debit=]]<=0?'':System::Display_number([[=items.debit=]]));?></td>	
         <?php }else if([[=payment_types.def_code=]]=='FOC'){?>
                    <td align="right" class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo ([[=items.foc=]]==0?'':System::Display_number([[=items.foc=]]));?></td>
         <?php }else if([[=payment_types.def_code=]]=='ROOM CHARGE'){?>
                    <td align="right" class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo ([[=items.room=]]==0?'':System::Display_number([[=items.room=]]));?></td>
         <?php }else if([[=payment_types.def_code=]]=='BANK'){?>
                    <td align="right" class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo ([[=items.bank=]]==0?'':System::Display_number([[=items.bank=]]));?></td>
         <?php }else if([[=payment_types.def_code=]]=='REFUND'){?>
                    <td align="right" class="report_table_column" rowspan="<?php echo $k; ?>"><?php echo ([[=items.refund=]]==0?'':System::Display_number([[=items.refund=]]));?></td>
         <?php }?>
        
        <!--/LIST:payment_types-->
        <td align="right" class="report_table_column" rowspan="<?php echo $k;?>"><?php echo [[=items.total=]]?System::display_number([[=items.total=]]):'';?></td>
        <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>">[[|items.user_id|]]</td>
        <td align="center" class="report_table_column" rowspan="<?php echo $k; ?>">[[|items.note|]]</td>
    <?php 
        }
        if($is_rowspan == false)
        {
    ?>

    <?php
        $is_rowspan = true;
        } 
    ?>
    <?php
        if($k ==0 || $k ==1 || $i>$k)
        {
            $i = 1;
            $is_rowspan = false;
        } 
    ?>
	</tr>
	<!--/LIST:items-->
    <?php $total_card = 0;?>
	<!---------TOTAL GROUP FUNCTION----------->	
    <tr>
		<td colspan="6" class="report_sub_title" align="right"><b><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
        <td align="center" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['quantity']);?></strong></td>
        <td></td>
    	<td align="right" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['amount']);?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['discount_amount']);?></strong></td>
        <td align="right" class="report_table_column"><strong><?php echo System::display_number(round([[=group_function_params=]]['total_discount_amount_persent']));?></strong></td>
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
					<td align="right" class="report_table_column" ><strong><?php echo (([[=group_function_params=]]['debit']<=0)?'':System::Display_number([[=group_function_params=]]['debit']));?></strong></td>	
         <?php }else if([[=payment_types.def_code=]]=='FOC'){?>
                    <td align="right" class="report_table_column" ><strong><?php echo (([[=group_function_params=]]['foc']==0)?'':System::Display_number([[=group_function_params=]]['foc']));?></strong></td>
         <?php }else if([[=payment_types.def_code=]]=='ROOM CHARGE'){?>
                    <td align="right" class="report_table_column" ><strong><?php echo (([[=group_function_params=]]['room']==0)?'':System::Display_number([[=group_function_params=]]['room']));?></strong></td>
         <?php }else if([[=payment_types.def_code=]]=='BANK'){?>
                    <td align="right" class="report_table_column" ><strong><?php echo (([[=group_function_params=]]['bank']==0)?'':System::Display_number([[=group_function_params=]]['bank']));?></strong></td>
         <?php }else if([[=payment_types.def_code=]]=='REFUND'){?>
                    <td align="right" class="report_table_column" ><strong><?php echo (([[=group_function_params=]]['refund']==0)?'':System::Display_number([[=group_function_params=]]['refund']));?></strong></td>
         <?php }?> 
        <!--/LIST:payment_types-->
        
			 <td align="right" class="report_table_column" ><strong><?php echo System::Display_number([[=group_function_params=]]['total']);?></strong></td>
            <td align="right" class="report_table_column">&nbsp;</td>
            <td align="right" class="report_table_column">&nbsp;</td>
		</tr>
</table>
<br />
<br />
<!---<div style="page-break-before:always;page-break-after:always;"></div>--->
<!--ELSE-->
<strong>[[.no_data.]]</strong>
<!--/IF:check_data-->
<script>
    jQuery('#hour_from').mask("99:99");
    jQuery('#hour_to').mask("99:99");
    function changevalue()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#to_date").val(jQuery("#from_date").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#from_date").val(jQuery("#to_date").val());
        }
    }
</script>