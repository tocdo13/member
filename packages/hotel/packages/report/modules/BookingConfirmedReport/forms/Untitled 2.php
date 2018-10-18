<style>
/*full m?n h?nh*/
/*.simple-layout-middle{width:100%;}*/
</style>
<!---------HEADER----------->
<!--IF:first_page([[=page_no=]]<=1)-->
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
                        <br />
                        [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
                        <br />
                        [[.user_print.]]:<?php echo ' '.User::id();?>

                    </td>
                </tr>
                <tr>
    				<td colspan="2"> 
                        <div style="width:100%; text-align:center;">
                            <font class="report_title specific" >[[.vending_revenue_report.]]<br /></font>
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
                                	<td><input name="line_per_page" type="text" id="line_per_page" value="[[|line_per_page|]]" size="4" maxlength="2" style="text-align:right;width: 20px;"/></td>
                                    <td>[[.no_of_page.]]</td>
                                	<td><input name="no_of_page" type="text" id="no_of_page" value="[[|no_of_page|]]" size="4" maxlength="2" style="text-align:right;width: 20px;"/></td>
                                    <!--
                                    <td>[[.from_page.]]</td>
                                	<td><input name="start_page" type="text" id="start_page" value="[[|start_page|]]" size="4" maxlength="4" style="text-align:right"/></td>
                                    -->
                                    <td>|</td>
                                    <td>[[.hotel.]]</td>
                                	<td><select name="portal_id" id="portal_id"></select></td>
                                    <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                                    <td>[[.user.]]</td>
                                	<td><select name="user_id" id="user_id"></select></td>
                                    <?php }?>
                                    <td>[[.area.]]</td>
                                    <td><select name="area_id" id="area_id"></select></td>
                                    <td>[[.from.]]</td>
                                	<td><input name="from_date" type="text" id="from_date" onchange="changevalue();" style="width: 60px;"/></td>
                                    <td>[[.to.]]</td>
                                	<td><input name="to_date" type="text" id="to_date" onchange="changefromday();" style="width: 60px;"/></td>
                                    <td>[[.from_bill_number.]]</td>
                                	<td><input name="from_bill" type="text" id="from_bill" style="width: 20px;" class="input_number"/></td>
                                    <td>[[.to_bill_number.]]</td>
                                	<td><input name="to_bill" type="text" id="to_bill" style="width: 20px;"class="input_number" /></td>
                                    <td>[[.customer_name.]]</td>
                                    <td><input name="customer_name" type="text" id="customer_name" style="width: 70px;" onfocus="Autocomplete();" /></td>
                                    <td><input type="submit" name="do_search" value="[[.search1.]]" style="width: 50px;text-align: center;"/></td>
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
<?php $i=0;$j=0;$k=0;$total_amount = 0;?>
<!--LIST:payment_types--><?php $i++;?><!--/LIST:payment_types-->
<!--LIST:currencies--><?php $j++;?><!--/LIST:currencies-->
<!--LIST:credit_card--><?php $k++;?><!--/LIST:credit_card-->
<table  cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	<tr bgcolor="#EFEFEF">
        <th rowspan="3" class="report_table_header" width="50px">[[.stt.]]</th>
        <th rowspan="3" class="report_table_header" width="100px">[[.code.]]</th>
        <th rowspan="3" class="report_table_header" width="100px">[[.date.]]</th>
        <th rowspan="3" class="report_table_header" width="150px">[[.customer_name.]]</th>
        <th width="40px" rowspan="3" class="report_table_header" >[[.area_name.]]</th>
        <th width="60px" rowspan="3" class="report_table_header">[[.total_before_discount.]]</th>
        <th width="60px" rowspan="3" class="report_table_header">[[.discount.]]</th>
        <th width="60px" rowspan="3" class="report_table_header">[[.service_charge.]]</th>
        <th width="60px" rowspan="3" class="report_table_header">[[.tax.]]</th>
        <th width="60px" rowspan="3" class="report_table_header">[[.deposit.]]</th>
        <th colspan="<?php echo ($i+$j-1);?>" class="report_table_header">[[.payment.]]</th>
        <th rowspan="3" class="report_table_header" width="100px">[[.total.]]</th>
        <th rowspan="3" class="report_table_header" width="150px">[[.note.]]</th>
        <th rowspan="3" class="report_table_header" width="100px">[[.user.]]</th>
    </tr>
     <tr valign="middle" bgcolor="#EFEFEF">
    <!--LIST:payment_types-->
        <?php if([[=payment_types.def_code=]]=='CREDIT_CARD'){?>
        	<th class="report_table_header">[[|payment_types.name|]]</th>
        <?php }elseif([[=payment_types.def_code=]]=='CASH'){?>
        	<th class="report_table_header" colspan="<?php echo $j;?>">[[|payment_types.name|]]</th>
         <?php }else{?>
         	<th class="report_table_header">[[|payment_types.name|]]</th>
         <?php }?>
    <!--/LIST:payment_types-->
    </tr>
    <tr valign="middle" bgcolor="#EFEFEF">
     <!--LIST:payment_types-->
      	<?php if([[=payment_types.def_code=]]=='CREDIT_CARD'){?>
                <th class="report_table_header" >&nbsp;</th>
        <?php }else if([[=payment_types.def_code=]]=='CASH'){?>
        	 <!--LIST:currencies-->
				<th class="report_table_header">[[|currencies.id|]]</th>
   			 <!--/LIST:currencies-->
         <?php }else{?>
         		<th class="report_table_header"></th>
         <?php }?>
      <!--/LIST:payment_types-->    
     </tr>
    <!--IF:first_page([[=page_no=]]!=1)-->
    <?php $card = 0; ?>
<!---------LAST GROUP VALUE----------->	        
    <tr>
        <td align="right" colspan="9" class="report_sub_title"><b>[[.last_page_summary.]]</b></td>
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
         <?php }else if([[=payment_types.def_code=]]=='REFUND'){?>
                    <td align="right"  class="report_table_column" ><strong><?php echo (([[=last_group_function_params=]]['refund']==0)?'':System::Display_number([[=last_group_function_params=]]['refund']));?></strong></td>
         <?php }?>
         <!--/LIST:payment_types-->
        <td></td>
        	<td align="right"  class="report_table_column" ><strong><?php echo System::Display_number([[=last_group_function_params=]]['total_amount']);?></strong></td>
            <td align="right"  class="report_table_column" >&nbsp;</td>
            <td align="right"  class="report_table_column" >&nbsp;</td>
		</tr>
	</tr>
	<!--/IF:first_page-->
	<!--LIST:items-->
    <tr bgcolor="white">
        <td align="center" class="report_table_column">[[|items.stt|]]</td>
        <td align="center" class="report_table_column">
        <a href="<?php echo Url::build('automatic_vend',array('cmd'=>'detail',md5('act')=>md5('print'),md5('preview')=>1,'id'=>[[=items.id=]]));?>" target="_blank">[[|items.code|]]</a>
        </td>
        <td align="center" class="report_table_column">[[|items.create_date|]]</td>
        <td align="left" class="report_table_column">[[|items.agent_name|]]</td>
        <td align="left" class="report_table_column">[[|items.area_name|]]</td>
        <td align="right" class="report_table_column" ><?php echo (([[=items.before_discount=]]==0)?'0':System::display_number([[=items.before_discount=]]));?></td>
        <td align="right" class="report_table_column" ><?php echo ((([[=items.total_discount=]]+[[=items.product_discount=]])==0)?'':System::display_number([[=items.total_discount=]]+[[=items.product_discount=]]));?></td>
        <td align="right" class="report_table_column" ><?php echo (([[=items.fee_rate=]]==0)?'':System::display_number([[=items.fee_rate=]]));?></td>
        <td align="right" class="report_table_column" ><?php echo (([[=items.tax=]]==0)?'':System::display_number([[=items.tax=]]));?></td>
        <td align="right" class="report_table_column" ><?php echo (([[=items.deposit=]]==0)?'':System::display_number([[=items.deposit=]]));?></td>
        <!--LIST:payment_types-->
    	 <?php if([[=payment_types.def_code=]]=='CREDIT_CARD'){ $card_item = 0; $count = 0;?>
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
        <?php echo '<td align="right" class="report_table_column" >'.System::Display_number($card_item).'</td>';	
		}else if([[=payment_types.def_code=]]=='CASH'){?>
        		<!--LIST:currencies-->
                <?php $tt = [[=payment_types.def_code=]].'_'.[[=currencies.id=]]; if(isset($this->map['items']['current'][$tt])){
					echo '<td align="right" class="report_table_column" >'.System::Display_number($this->map['items']['current'][$tt]).'</td>';	
				}else{
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
         <?php }else if([[=payment_types.def_code=]]=='REFUND'){?>
                    <td align="right" class="report_table_column" ><?php echo ([[=items.refund=]]==0?'':System::Display_number([[=items.refund=]]));?></td>
         <?php }?>
        <!--/LIST:payment_types-->
        
        <td align="right" class="report_table_column"><?php echo System::Display_number([[=items.total=]]); ?></td>
        <td align="left" class="report_table_column">[[|items.note|]]</td>
        <td align="center" class="report_table_column">[[|items.user_id|]]</td>
	</tr>
	<!--/LIST:items-->
    <?php $total_card = 0;?>
    <tr>
        
        <td align="right" colspan="9" class="report_sub_title" ><b><?php if([[=page_no=]]==[[=total_page=]])echo Portal::language('summary');else echo Portal::language('next_page_summary');?></b></td>
        <td align="right" class="report_table_column"><strong><?php echo (([[=group_function_params=]]['deposit']==0)?'':System::display_number([[=group_function_params=]]['deposit']));?></strong></td>
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
         <?php }else if([[=payment_types.def_code=]]=='REFUND'){?>
                    <td align="right" class="report_table_column" ><strong><?php echo (([[=group_function_params=]]['refund']==0)?'':System::Display_number([[=group_function_params=]]['refund']));?></strong></td>
         <?php }?> 
        <!--/LIST:payment_types-->
        
        <td align="right" class="report_table_column"><strong><?php echo System::display_number([[=group_function_params=]]['total_amount']);?></strong></td>
        <td colspan="2" class="report_sub_title" align="right">&nbsp;</td>
    </tr>
</table>


<!---------FOOTER----------->
<center><div style="font-size:11px;">
  [[.page.]] [[|page_no|]]/[[|total_page|]]</div>
</center>
<br/>

<!--IF:end_page(([[=page_no=]]==[[=total_page=]]))-->
<table width="100%" cellpadding="10" style="font-size:11px;">
<tr>
	<td></td>
	<td ></td>
	<td align="center" > [[.day.]] <?php echo date('d');?> [[.month.]] <?php echo date('m');?> [[.year.]] <?php echo date('Y');?><br /></td>
</tr>
<tr>
	<td width="33%" >[[.creator.]]</td>
	<td width="33%" >&nbsp;</td>
	<td width="33%" align="center">[[.general_accountant.]]</td>
</tr>
</table>
<p>&nbsp;</p>
<script>full_screen();</script>
<!--/IF:end_page-->
<div style="page-break-before:always;page-break-after:always;"></div>

<!--ELSE-->
<strong>[[.no_data.]]</strong>
<!--/IF:check_data-->
<script>
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
    function Autocomplete()
    {
        jQuery("#customer_name").autocomplete({
             url: 'get_customer_search_fast.php?customer=1',
             //onItemSelect: function(item){
//                document.getElementById('customer_id').value = item.data[0]; 
//                        var is_rate_code = document.getElementById('is_rate_code');
//                        if(is_rate_code.checked)
//                        {
//                            get_price_rate_code(item.data[0],101);
//                            
//                        }
//            }
        }) ;
    }
</script>






