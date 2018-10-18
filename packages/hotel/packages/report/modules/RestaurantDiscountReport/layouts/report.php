<style>
#timehidden{
		display:none;	
	}
	@media print{
		#hidden{
			display:none;
		}
		#timehidden{
			display:block;	
		}
	}
	
	#revenue{
		border:2px solid #DFDFDF;
		margin:auto; 
		margin-top:50px;
	}
	#revenue tr td{
		border:1px solid silver;
	}
	#revenue tr{
		line-height:20px; 
		border:1px solid silver;	
	}
</style>
<table width="80%" bgcolor="#fff" style="margin:auto;">
<tr>
	<td>
		<table cellSpacing=0 width="100%">
			<tr valign="top">
				<td align="left" width="65%"><strong><?php echo HOTEL_NAME;?></strong><br />Địa chỉ: <?php echo HOTEL_ADDRESS;?><BR></td>
				<td align="right" nowrap width="35%"><strong>[[.template_code.]]</strong>
                <br />
                [[.date_print.]]:<?php echo date('d/m/Y H:i');?>
                <br />
                [[.user_print.]]:<?php echo User::id();?>
                </td>
			</tr>	
		</table>
	<table cellpadding="0" cellspacing="0" align="center" width="100%">
	<tr>
	<td align="center">
		<p>&nbsp;</p>
		<font class="report_title" style="font-size:20px; text-align:center;"><b>[[.restaurant_discount_report.]]</b></font>

		<br><br />
        <label id="timehidden">Từ ngày: [[|from_date|]]  Đến ngày: [[|to_date|]]</label>
		<form name="WeeklyRevenueThuyForm" method="post">
		<table width="100%" id="hidden"><tr><td>
		<fieldset><legend><b>[[.time_select.]]</b></legend>
		<table border="0" style="margin:auto;">
        	<tr>
            	
            	<td>[[.from_date.]]:</td>
            	<td><input type="text" name="from_date" id="from_date" class="date-input" onchange="changevalue();" style="width: 140px;"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td>[[.to_date.]]:</td>
                <td>
                <input type="text" name="to_date" id="to_date" class="date-input" onchange="changefromday();" style="width: 140px;"/></td>
                <!--Start Luu Nguyen Giap add portal -->
                <?php //if(User::can_admin(false,ANY_CATEGORY)) {?>
                <td >[[.hotel.]]:</td>
                <td>
                <select name="portal_id" id="portal_id" style="width: 140px;" onchange="choice_restaurant();"></select></td>
                <?php //}?>
                <!--End Luu Nguyen Giap add portal -->
                <td><input type="submit" name="do_search" value="[[.report.]]" onclick="return check_choose_restaurant();"/></td>
                
			</tr>
            
            <tr>
            	<td>[[.customer.]]:</td>
                <td style="margin: 0;padding:0;"><select name="customer" id="customer" style="width: 140px;">[[|guest|]]</select></td>
                
            	<td>[[.guest_name.]]:</td>
                <td style="margin: 0;padding:0;"><select name="receiver" id="receiver" style="width: 140px;">[[|receiver|]]</select></td>
                
                <td>[[.room.]]:</td>
                <td colspan="2"><select name="room" id="room" style="width: 140px;">[[|rooms|]]</select></td>
			</tr>
            </table>
            <table style="margin: 0 auto;">
            <tr>
             		<td align="right"> <input name="checked_all" type="checkbox" id="checked_all" /></td> 
                    <td align="left"><b><label for="checked_all">[[.select_all_bar.]]</label></b></td>
             </tr>
             <!--LIST:bars-->
             <tr>
                  <td align="right"><input name="bars[]"  type="checkbox" value="[[|bars.id|]]" id="bar_id_[[|bars.id|]]" <?php if([[=bars.check=]]) echo 'checked="checked"' ?> class="check_box"  /></td>
                  <td><label for="bar_id_[[|bars.id|]]">[[|bars.name|]]</label></td>
    		 </tr>
             <!--/LIST:bars-->
		  </table>
			</form>
	</td></tr></table>
</td>
<div>
<table width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="#CCCCCC" style="line-height:30px; margin:10px auto;">
	<tr height="30" style="background:#DFDFDF;">
    	<td width="3%" align="center" rowspan="2">[[.no.]]</td>
        <td width="3%" align="center" rowspan="2">[[.order_id.]]</td>
        <td width="7%" align="center" rowspan="2">[[.arrival_time.]]</td>
        <td width="5%" align="center" rowspan="2">[[.room.]]</td>
        <td width="7%" align="center" rowspan="2">[[.customer.]]</td>
        <td width="7%" align="center" rowspan="2">[[.guest_name.]]</td>
        <td width="7%" align="center" rowspan="2">[[.product_id.]]</td>
        <td width="17%" align="center" rowspan="2">[[.product_name.]]</td>
        <td width="7%" align="center" rowspan="2">[[.price_standard.]]</td>
        
        <td width="4%" align="center" rowspan="2">[[.quantity.]]</td>
        
        <td width="24%" align="center" colspan="3">[[.res_discount_percent.]]</td>
        
        
        <td width="10%" align="center" style="display:none;"><b>[[.total.]]</b></td>
        
        <!--<td width="10%" align="center" rowspan="2">[[.discount_money.]]</td> -->
        
        <td width="7%" align="center" rowspan="2">[[.creator.]]</td>
    </tr>
    
    <tr style="background:#DFDFDF;">
        <td width="7%" align="center">[[.res_discount_rate.]]</td>
        <td width="7%" align="center">[[.res_discount_full.]]</td>
        <td width="10%" align="center">[[.res_discount.]]</td>
    </tr>
    <?php $i= 1; $quantiy=0;?>
    <!--LIST:items-->
    <tr>
    	<td rowspan="<?php echo [[=items.flag=]];?>"><?php echo $i;?></td>
        <td rowspan="<?php echo [[=items.flag=]];?>"><a href="<?php echo Url::build('touch_bar_restaurant',array(  'bar_reservation_bar_id', 'bar_reservation_receptionist_id')+array('cmd'=>'edit','id'=>[[=items.id=]])); ?>" target="_blank">[[|items.code|]]</a></td>
        <td align="left" rowspan="<?php echo [[=items.flag=]];?>">[[|items.arrival_time|]] -- [[|items.arrival_time_hour|]]</td>
        <td align="left" rowspan="<?php echo [[=items.flag=]];?>">[[|items.room_name|]]</td>
          
        
        <td align="left" rowspan="<?php echo [[=items.flag=]];?>"><?php if(empty([[=items.agent_name=]])){ ?>[[|items.customer_name|]]<?php }else { ?>[[|items.agent_name|]]<?php } ?></td>
        <td align="left" rowspan="<?php echo [[=items.flag=]];?>">[[|items.receiver_name|]]</td>
         <!--LIST:item_details-->
        <?php if(([[=item_details.bar_reservation_id=]] == [[=items.id=]]) && ([[=item_details.flag=]] == 1)){?>
         <td align="left">[[|item_details.product_id|]]</td>
         <td align="left">[[|item_details.product_name|]]</td>
        <td align="right">[[|item_details.price|]]</td>
        <td align="right">[[|item_details.quantity|]]</td><?php $quantiy+=[[=item_details.quantity=]] ?>
        <td align="right">[[|item_details.discount_rate|]]&nbsp;%</td>
        <td align="center">[[|item_details.full_discount|]]&nbsp;%</td>
        
        <td align="right">[[|item_details.discount_real|]]</td>
        <td align="right" style="display:none;"><b>[[|item_details.total|]]</b></td>
        <!--<td align="left" rowspan="<?php echo [[=items.flag=]];?>"><?php //echo System::display_number([[=items.discount=]]);?></td>-->
        <td align="center" rowspan="<?php echo [[=items.flag=]];?>">[[|item_details.receptionist_id|]]</td>
        
        <?php }else if(([[=item_details.bar_reservation_id=]] == [[=items.id=]]) && ([[=item_details.flag=]] > 1)){?>
        <tr>
         <td align="left">[[|item_details.product_id|]]</td>
         <td align="left">[[|item_details.product_name|]]</td>
        <td align="right">[[|item_details.price|]]</td>
        <td align="right">[[|item_details.quantity|]]</td><?php $quantiy+=[[=item_details.quantity=]] ?>
        <td align="right">[[|item_details.discount_rate|]]&nbsp;%</td>
        <td width="7%" align="center">[[|item_details.full_discount|]]&nbsp;%</td>
        
        <td align="right">[[|item_details.discount_real|]]</td>
        <td align="right" style="display:none;"><b>[[|item_details.total|]]</b></td>
        
        </tr>
        <?php }?>
     <!--/LIST:item_details-->
    </tr>
    <?php $i++;?>
	<!--/LIST:items-->
    
     <tr>
        <td align="right" colspan="9"><b>[[.total.]]:&nbsp;</b></td>
        <td align="right"><?php echo System::display_number($quantiy); ?></td>
        <td align="right"></td>
        <td align="right"></td>
        <td align="right"><b>[[|total_discount|]]</b></td>
        <td align="right" style="display:none;"><b>[[|total|]]</b></td>
        <td align="right"></td>
    </tr>
        </table>
</div>
</tr>
</table>
<script>
		$('from_date').value = '[[|from_date|]]';
		$('to_date').value = '[[|to_date|]]';
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
        function choice_restaurant()
        {
            WeeklyRevenueThuyForm.submit();
        }
        function check_choose_restaurant()
        {
            var check_box_arr = document.getElementsByClassName('check_box');
            var flag = false;
            for(var i=0;i<check_box_arr.length;i++)
            {
                if(check_box_arr[i].checked==true)
                    flag = true;
            }
            if(flag== false)
                alert('Bạn chưa chọn nhà hàng!');
            return flag;
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
		jQuery("#from_date").datepicker();//({ minDate: new Date(BEGINNING_YEAR,1 - 1, 1)});
		jQuery("#to_date").datepicker();//({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1)});
	<!--IF:cond([[=view_all=]]!=1)-->
		jQuery('#from_year').attr('disabled',true);
		jQuery('#from_month').attr('disabled',true);
		jQuery('#from_day').attr('disabled',true);
		jQuery('#to_day').attr('disabled',true);
	<!--/IF:cond-->
    jQuery("#checked_all").click(function (){
       // console.log("!!");
        var check  = this.checked;
        jQuery(".check_box").each(function(){
            this.checked = check;
        });
    });
</script>