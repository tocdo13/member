<style>
    @media print
{
    #search_display{
        display: none;
    }
    .footer{
        display: none;
    }
    .see{
        display: none;
    }
}
.simple-layout-middle{
    width: 95%;
}
</style>

<form name="repost_revenue" method="post">
<table id="repost_revenue" width="100%" style="border-collapse: collapse;">
    <tr>
        <td>
            <table border="0" cellSpacing=0 cellpadding="5" width="100%" class="header" style="border-collapse: collapse;">
			<tr valign="middle">
			  <td align="left">
			  	<strong><?php echo HOTEL_NAME;?></strong><br />
			<?php echo HOTEL_ADDRESS;?>
			  </td>
			  <td align="right">
                [[.date_print.]]:<?php echo date('d/m/Y H:i');?>
                <br />
                [[.user_print.]]:<?php echo User::id();?>
			  </td>
			</tr>	
             <tr>
                <td style="text-align: center;text-transform: uppercase; " colspan="2">
                    <?php if(Url::get('type')=='date'){ ?><h2>[[.repost_revenue_payment_date.]]</h2><?php }else{ ?><h2>[[.repost_revenue_payment_month.]]</h2><?php } ?>[[.date_from.]]: [[|date_from|]] [[.date_to.]]: [[|date_to|]]
                </td>
            </tr>
            <tr>
                <td>
                    
                </td>
            </tr>
		</table>
        </td>
    </tr>
    <tr id="search_display">
        <td align="middle" width="80%"><fieldset >
            <legend>[[.search.]]</legend>
        <table cellspacing="0" cellpadding="5" width="70%" class="search_date" border="0" style=" margin-left: 200px; border-collapse: collapse; ">
            
            <tr>
                <td>
                        [[.date_from.]]
                        <input name="date_from" type="text" id="date_from" style="width: 80px;" />
                </td>
                <td>
                        [[.date_to.]]
                        <input name="date_to" type="text" id="date_to" style="width: 80px;" />
                </td>
                <td>
                        [[.customer_name.]]
                        <input name="customer_name" type="text" id="customer_name" style="width: 250px;" onkeypress="Autocomplete()" oninput="delete_customer();" autocomplete="off" />
                        <input name="customer_id" type="text" id="customer_id" class="hidden"/>
                </td>
                <td>
                        [[.type.]]
                        <select name="type" title="" id="type">
                            
                        </select>
                </td>
                <td>
                    <input name="sub" type="submit" id="sub" value="[[.search.]]"/>
                </td>
            </tr>
           
            
        </table>
        </fieldset>
        </td>
    </tr>
    <tr>
        <td>
            <table cellspacing="0" cellpadding="5" width="100%" border="1" class="content" style="border-collapse: collapse;">
                <tr style="background: #eeeeee;">
                <?php if(Url::get('type')=='date'){ ?>
                    <td rowspan="2" style="text-align: center;" ><b>[[.date.]]</b></td>
                  <?php }else{?>  
                  <td rowspan="2" style="text-align: center;"><b>[[.month.]]</b></td>
                  <?php } ?>
                    <td colspan="3" style="text-align: center;"><b>FO</b></td>
                    <td rowspan="2" style="text-align: center;"><b>[[.hosekeeping.]]</b></td>
                    <td rowspan="2" style="text-align: center;"><b>[[.restaurant.]]</b></td>
                    <td rowspan="2" style="text-align: center;"><b>[[.shop.]]</b></td>
                    <td rowspan="2" style="text-align: center;"><b>[[.spa.]]</b></td>
                    <?php if([[=isset_karaoke=]]==1){?>
                    <td rowspan="2" style="text-align: center;"><b>[[.karaoke.]]</b></td>
                    <?php }?>
                    <td rowspan="2" style="text-align: center;"><b>[[.drain.]]</b></td>
                    <td rowspan="2" style="text-align: center;"><b>[[.tax.]]</b></td>
                    <td rowspan="2" style="text-align: center;"><b>[[.total_amount.]]</b></td>
                    <td rowspan="2" style="text-align: center;"><b>[[.payment_amount.]]</b></td>
                    <td rowspan="2" style="text-align: center;width:8% "><b>[[.total_not_payment.]]</b></td>
                   <td rowspan="2" style="text-align: center; width:5% " class="see"><b>[[.see.]]</b></td>
                </tr>
                 <tr style="background: #eeeeee;">
                    <td style="text-align: center;"><b>[[.amount_room1.]]<br /></b></td>
                    <td style="text-align: center; width :13% "><b>[[.total_service_room.]]<br/></b></td>
                    <td style="text-align: center;"><b>[[.service_1.]]</b></td>
                    
                </tr>
                <!--LIST:items-->
                <tr>
                    <td style="text-align: center;"><b>[[|items.id|]]</b></td>
                    <td style="text-align: right;"><?php echo System::display_number([[=items.room=]])?></td>
                    <td style="text-align: right;"><?php echo System::display_number([[=items.service_room=]])?></td>
                    <td style="text-align: right;"><?php echo System::display_number([[=items.service_other=]])?></td>
                    <td style="text-align: right;"><?php echo System::display_number([[=items.housekeeping=]])?></td>
                    <td style="text-align: right;"><?php echo System::display_number([[=items.bar=]])?></td>
                    <td style="text-align: right;"><?php echo System::display_number([[=items.vending=]])?></td>
                    <td style="text-align: right;"><?php echo System::display_number([[=items.spa=]])?></td>
                    <?php if([[=isset_karaoke=]]==1){?>
                    <td style="text-align: right;"><?php echo System::display_number([[=items.karaoke=]])?></td>
                    <?php }?>
                    <td style="text-align: right;"><?php echo System::display_number([[=items.total_before_tax=]])?></td>
                    <td style="text-align: right;"><?php echo System::display_number([[=items.tax=]])?></td>
                    <td style="text-align: right;"><?php echo System::display_number(round([[=items.total=]]))?></td>
                    <td style="text-align: right;"><?php echo System::display_number([[=items.payment=]])?></td>
                    <td style="text-align: right;"><?php echo System::display_number(round([[=items.not_payment=]]))?></td>
                    <td style="text-align: center;" class="see"><div onclick="url_revenue('[[|items.start_date|]]','[[|items.end_date|]]');"><img src="packages/core/skins/default/images/buttons/rate_list.gif" /></div></td>
                </tr>
                <!--/LIST:items-->
                <tr style="background: #eeeeee;">
                    <td style="text-align: center;text-transform: uppercase;"><b>[[.total_drain.]]</b></td>
                    <td style="text-align: right;"><b><?php echo System::display_number([[=total_room=]])?></b></td>
                    <td style="text-align: right;"><b><?php echo System::display_number([[=total_service_room=]])?></b></td>
                    <td style="text-align: right;"><b><?php echo System::display_number([[=total_service_other=]])?></b></td>
                    <td style="text-align: right;"><b><?php echo System::display_number([[=total_housekeeping=]])?></b></td>
                    <td style="text-align: right;"><b><?php echo System::display_number([[=total_bar=]])?></b></td>
                    <td style="text-align: right;"><b><?php echo System::display_number([[=total_vending=]])?></b></td>
                    <td style="text-align: right;"><b><?php echo System::display_number([[=total_spa=]])?></b></td>
                    <?php if([[=isset_karaoke=]]==1){?>
                    <td style="text-align: right;"><b><?php echo System::display_number([[=total_karaoke=]])?></b></td>
                    <?php }?>
                    <td style="text-align: right;"><b><?php echo System::display_number([[=total_before_tax=]])?></b></td>
                    <td style="text-align: right;"><b><?php echo System::display_number([[=total_tax=]])?></b></td>
                    <td style="text-align: right;"><b><?php echo System::display_number(round([[=total_total=]]))?></b></td>
                    <td style="text-align: right;"><b><?php echo System::display_number([[=total_payment=]])?></b></td>
                    <td style="text-align: right;"><b><?php echo System::display_number(round([[=total_not_payment=]]))?></b></td>
                    <td style="text-align: right;" class="see"><b></b></td>
                   
                   
                    
                </tr>
            </table>
        </td>
    
    </tr>
    <tr>
        <td>
            <table class="footer" collpadding="0" collspacing="5" width="100%" border="0"  style="border-collapse: collapse;" >
                <tr>
                    <td valign="top" style="padding-left: 2px;" > <b>Ý nghĩa :</b></td>
                </tr>
                <tr >
                    <td style="padding-left: 20px;">-Báo cáo tổng hợp doanh thu theo ngày thống kê doanh thu của tiền phòng và dịch vụ theo từng ngày phát sinh của những phòng sẽ thu tiền</td>
                </tr>
                <tr>
                    <td style="padding-left: 20px;">-Phòng FOC và FOC All sẽ ko tính doanh thu (tiền = 0)</td>
                </tr>
                <tr>
                    <td style="padding-left: 20px;">-Những phòng, dịch vụ Cancel sẽ không được tính doanh thu</td><br />
                </tr>
            </table>
        </td>
    </tr>
</table>
</form>
<script>
    jQuery("#date_from").datepicker();
    jQuery("#date_to").datepicker();
    
    function Autocomplete()
    {
        jQuery('#customer_name').autocomplete({
            url:'get_customer_search_fast.php?customer=1',
            onItemSelect:function(item)
            {
                document.getElementById('customer_id').value = item.data[0];
            }
            
        }); 
    }
    function delete_customer()
    {
        if(jQuery("#customer_name").val()=='')
        {
            jQuery("#customer_id").val('');
        }
    }
    function url_revenue(start_date,end_date)
    {
        var customer_id = jQuery("#customer_id").attr('value');
        var customer_name = jQuery("#customer_name").attr('value');
        window.open('?page=report_revenue_group_of_type_new&date_from='+start_date+'&date_to='+end_date+'&customer_id='+customer_id+'&customer_name='+customer_name)   
    }

</script>