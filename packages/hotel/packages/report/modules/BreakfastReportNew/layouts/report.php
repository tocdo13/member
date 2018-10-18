<style>
	 @media print {
	   fieldset {
	       display: none;
	   }
  }	
</style>
<div id="header">
    <table style="width: 98%; margin: 0px auto;">
        <tr>
            <td style="font-weight: bold;"><?php echo HOTEL_NAME; ?><br /><?php echo HOTEL_ADDRESS; ?></td>
            <td style="text-align: right; font-weight: bold;">[[.template_code.]]<br />[[.date.]]: <?php echo date('H:i d/m/Y'); ?><br />[[.user.]]: <?php echo User::id(); ?></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;"><p style="font-size: 20px; font-weight: bold; text-transform: uppercase;">[[.breakfast_report.]]</p><p>[[.from_date.]]: [[|from_date|]] [[.to_date.]]: [[|to_date|]] </p><p>[[.breakfast_from_time.]]: <?php echo BREAKFAST_FROM_TIME; ?> [[.breakfast_to_time.]]: <?php echo BREAKFAST_TO_TIME; ?></p></td>
        </tr>
    </table>
</div><!-- end header -->

<div id="search" style="width: 98%; margin: 0px auto;">
    <form name="BreakfastReportForm" method="post">
        <fieldset style="border: 1px solid #00b2f9;">
            <legend  style="border: 1px solid #00b2f9; background: #ffffff; border-left: 3px solid #00b2f9; border-right: 3px solid #00b2f9; padding: 5px 20px;">[[.option.]]</legend>
            <table style="width: 100%;">
                <tr>
                    <td>
                        [[.hotel.]]: <select name="portal_id" id="portal_id" style="width: 120px; text-align: center;"></select>
                    </td>
                    <td>
                        [[.from_date.]]: <input name="from_time" type="text" id="from_time" style="width: 35px; text-align: center; display: none;" autocomplete="off" />
                        <input name="from_date" type="text" id="from_date" style="width: 80px; text-align: center;"  autocomplete="off"/>
                    </td>
                    <td>
                        [[.to_date.]]: <input name="to_time" type="text" id="to_time" style="width: 35px; text-align: center; display: none;"  autocomplete="off"/>
                        <input name="to_date" type="text" id="to_date" style="width: 80px; text-align: center;"  autocomplete="off"/>
                    </td>
                    <!--trung add search customer-->
                    <td>
                        [[.customer_name.]] : <input name="customer_name" type="text" id="customer_name" onkeypress="Autocomplete();"  autocomplete="off"/> 
                        <input name="customer_id" type="text" id="customer_id" class="hidden" />
                    </td>
                    <!-- ednd :trung add search customer-->
                    <td>
                        <input name="search" value="[[.view_report.]]" type="submit" style="border: 1px solid #00b2f9; background: #ffffff; padding: 5px; font-weight: bold;" />
                    </td>
                </tr>
            </table>
        </fieldset>
    </form>
</div><!-- end search -->

<div id="report" style="width: 98%; margin: 0px auto;">
    <table style="width: 100%; margin: 10px auto; border-spacing: inherit; border-collapse: 1px;" cellpadding="5" cellspacing="0" border="1" bordercolor="#5d5d5d">
        <tr style="background: #eeeeee;">
            <th style="text-align: center; width: 40px;">[[.stt.]]</th>
            <th style="text-align: center; width: 50px;">[[.recode.]]</th>
            <th style="text-align: center; width: 40px;">[[.barcode.]]</th>
            <th style="text-align: center; width: 15%;">[[.customer1.]]</th>
            <th style="text-align: center;">[[.traveller_name.]]</th>
            <th style="text-align: center;">[[.room_name.]]</th>
            <th style="text-align: center;">[[.is_adult.]]</th> 
            <th style="text-align: center;">[[.is_child.]]</th>
            <th style="text-align: center;">[[.breakfast_indate.]]</th>
            <th style="text-align: center;">[[.breakfast_time.]]</th>
            <th style="text-align: center;">[[.Note.]]</th>
        </tr>
        <?php
            $i = 1;
        ?>
        <!--LIST:items-->
        <tr>
            <td ><?php echo $i; $i++; ?></td>
            <td ><a href="?page=reservation&cmd=edit&id=[[|items.reservation_id|]]&r_r_id=[[|items.reservation_room_id|]]">[[|items.reservation_id|]]</a></td>
            <td >[[|items.barcode|]]</td>    
            <td >[[|items.customer_name|]]</td>  
            <td >[[|items.guest_name|]]</td> 
            <td style="text-align: left;" >[[|items.room_name|]]</td>  
            <td style="text-align:center;" class="is_adult"><?php if([[=items.is_child=]]==0) echo "x"; ?></td> 
            <td style="text-align:center;" class="is_child"><?php if([[=items.is_child=]]==1) echo "x"; ?></td> 
            <td class="tt_voucher" style="text-align: center;" ><?php echo !empty([[=items.in_date=]]) ? [[=items.in_date=]] : ""; ?></td>  
            <td class="tt_use" style="text-align: center;" ><?php echo !empty([[=items.real_use_date=]]) ? date("H:i d/m/Y",[[=items.real_use_date=]]) : ""; ?></td>  
            <td style="text-align: left;" ><?php if(!empty([[=items.reprint=]])) echo "In lại lần ".[[=items.reprint=]]; ?></td>  
        </tr>
        <!--/LIST:items-->
        <tr>
            <td style="text-align: right; font-weight:bold;" colspan="6">[[.Total_final.]]</td>
            <td style="text-align: center; font-weight:bold;" id="total_adult"></td>
            <td style="text-align: center; font-weight:bold;" id="total_child"></td>
            <td style="text-align: center; font-weight:bold;" id="total_voucher"></td>
            <td style="text-align: center; font-weight:bold;" id="total_use"></td>
            <td></td>
        </tr>
        <tr>
            <td style="text-align: right; font-weight:bold;" colspan="8">[[.Sub.]]</td>
            <td colspan="2" style="text-align: center; font-weight:bold;" id="sub_voucher"></td>
            <td></td>
        </tr>
    </table>
</div><!-- end report -->

<div id="footer">

</div><!-- end footer -->
<script>
    jQuery(document).ready(function(){
        jQuery("#total_voucher").html(jQuery(".tt_voucher").length);
        var stt = 0;
        jQuery(".tt_use").each(function(){
            if(jQuery(this).html().trim()!="")
            {
                stt++;
            }
        });
        jQuery("#total_use").html(stt);
        jQuery("#sub_voucher").html(jQuery(".tt_voucher").length - stt);
        
        var count_adult = 0;
        jQuery(".is_adult").each(function(){
            if(jQuery(this).html().trim()!="")
            {
                count_adult++;
            }
        });
        jQuery("#total_adult").html(count_adult);
        
        var count_child = 0;
        jQuery(".is_child").each(function(){
            if(jQuery(this).html().trim()!="")
            {
                count_child++;
            }
        });
        jQuery("#total_child").html(count_child);
    });

    function Autocomplete()
{
    jQuery("#customer_name").autocomplete({
         url: 'get_customer_search_fast.php?customer=1',
         onItemSelect: function(item) {
             //console.log(item);
            document.getElementById('customer_id').value = item.data[0];
           // console.log(document.getElementById('customer_id').value);
        }
    }) ;
}


    jQuery("#from_date").datepicker();
    jQuery("#to_date").datepicker();
</script>