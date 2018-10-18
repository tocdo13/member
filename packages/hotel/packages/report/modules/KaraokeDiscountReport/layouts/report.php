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
				<td align="left" width="65%"><strong><?php echo HOTEL_NAME;?></strong><br />ADD: <?php echo HOTEL_ADDRESS;?><BR></td>
				<td align="right" nowrap width="35%"><strong>[[.template_code.]]</strong>
                <br />
                [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
                <br />
                [[.user_print.]]:<?php echo ' '.User::id();?>
                </td>
			</tr>	
		</table>
	<table cellpadding="0" cellspacing="0" align="center" width="100%">
	<tr>
	<td align="center">
		<p>&nbsp;</p>
		<font class="report_title" style="font-size:20px; text-align:center;"><b>[[.karaoke_discount_report.]]</b></font>

		<br><br />
        <label id="timehidden">Từ ngày: [[|from_date|]]  Đến ngày: [[|to_date|]]</label>
		<form name="WeeklyRevenueThuyForm" method="post">
		<table width="100%" id="hidden"><tr><td>
		<fieldset><legend><b>[[.time_select.]]</b></legend>
		<table border="0" style="margin:auto;">
        	<tr style="text-align:center;">
            	<td></td>
            	<td>[[.from_date.]]:&nbsp;&nbsp;
            	<input type="text" name="from_date" id="from_date" class="date-input" onchange="check_from_date();"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td>[[.to_date.]]:&nbsp;&nbsp;
                <input type="text" name="to_date" id="to_date" class="date-input" onchange="check_to_date();"/></td>
                <td><input type="submit" name="do_search" value="  [[.report.]]  "></td>
			</tr>
            <tr style="text-align:center;">
            	<td colspan="2">&nbsp;[[.customer.]]:&nbsp;&nbsp;<select name="customer" id="customer" >[[|guest|]]</select></td>
            	<td>[[.guest_name.]]:&nbsp;<select name="receiver" id="receiver" >[[|receiver|]]</select></td>
                <td>&nbsp;[[.room.]]:&nbsp;<select name="room" id="room" >[[|rooms|]]</select></td>
			</tr>
		  </table>
		  </fieldset>
		  </td></tr></table>
			</form>
	</td></tr></table>
</td>
<div>
<table width="100%" style="border:1px solid #DFDFDF; line-height:30px; margin:10px auto;" id="revenue">
	<tr height="30" style="background:#DFDFDF;">
    	<td width="3%" align="center">[[.no.]]</td>
        <td width="3%" align="center">[[.order_id.]]</td>
        <td width="7%" align="center">[[.arrival_time.]]</td>
        <td width="5%" align="center">[[.room.]]</td>
        <td width="7%" align="center">[[.customer_name.]]</td>
        <td width="7%" align="center">[[.guest_name.]]</td>
        <td width="7%" align="center">[[.product_id.]]</td>
        <td width="17%" align="center">[[.product_name.]]</td>
        <td width="7%" align="center">[[.price_standard.]]</td>
        <td width="7%" align="center">[[.discount_rate.]]</td>
         <td width="4%" align="center">[[.quantity.]]</td>
        <td width="10%" align="center">[[.discount.]]</td>
        <td width="10%" align="center" style="display:none;"><b>[[.total.]]</b></td>
        <td width="7%" align="center">[[.creator.]]</td>
    </tr>
    <?php $i= 1;?>
    <!--LIST:items-->
    <tr>
    	<td rowspan="<?php echo [[=items.flag=]];?>"><?php echo $i;?></td>
        <td rowspan="<?php echo [[=items.flag=]];?>"><a href="<?php echo Url::build('karaoke_touch',array(  'karaoke_reservation_karaoke_id', 'karaoke_reservation_receptionist_id')+array('cmd'=>'edit','id'=>[[=items.id=]])); ?>">[[|items.id|]]</a></td>
        <td align="left" rowspan="<?php echo [[=items.flag=]];?>">[[|items.arrival_time|]]</td>
        <td align="left" rowspan="<?php echo [[=items.flag=]];?>">[[|items.room_name|]]</td>
        <td align="left" rowspan="<?php echo [[=items.flag=]];?>">[[|items.customer_name|]]</td>
        <td align="left" rowspan="<?php echo [[=items.flag=]];?>">[[|items.receiver_name|]]</td>
         <!--LIST:item_details-->
        <?php if(([[=item_details.karaoke_reservation_id=]] == [[=items.id=]]) && ([[=item_details.flag=]] == 1)){?>
         <td align="left">[[|item_details.product_id|]]</td>
         <td align="left">[[|item_details.product_name|]]</td>
        <td align="right">[[|item_details.price|]]</td>
        <td align="right">[[|item_details.discount_rate|]]&nbsp;%</td>
        <td align="right">[[|item_details.quantity|]]</td>
        <td align="right">[[|item_details.discount_real|]]</td>
        <td align="right" style="display:none;"><b>[[|item_details.total|]]</b></td>
        <td align="center">[[|item_details.receptionist_id|]]</td>
        <?php }else if(([[=item_details.karaoke_reservation_id=]] == [[=items.id=]]) && ([[=item_details.flag=]] > 1)){?>
        <tr>
         <td align="left">[[|item_details.product_id|]]</td>
         <td align="left">[[|item_details.product_name|]]</td>
        <td align="right">[[|item_details.price|]]</td>
        <td align="right">[[|item_details.discount_rate|]]&nbsp;%</td>
        <td align="right">[[|item_details.quantity|]]</td>
        <td align="right">[[|item_details.discount_real|]]</td>
        <td align="right" style="display:none;"><b>[[|item_details.total|]]</b></td>
        <td align="center">[[|item_details.receptionist_id|]]</td>
        </tr>
        <?php }?>
     <!--/LIST:item_details-->
    </tr>
    <?php $i++;?>
	<!--/LIST:items-->
    
     <tr>
        <td align="right" colspan="6"><b>[[.total.]]:&nbsp;</b></td>
        <td align="right"></td>
        <td align="right"></td>
        <td align="right"></td>
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
		function check_from_date(){
			//alert($('from_date').value);
			var from_date = $('from_date').value.split("/");
			var to_date = $('to_date').value.split("/");
			if((from_date[1] > to_date[1]) || (from_date[2] > to_date[2])){
				$('to_date').value = $('from_date').value;
			}else{
				if((from_date[0] > to_date[0]) && (from_date[1] == to_date[1]) && (from_date[2] == to_date[2]) ){
					$('to_date').value = $('from_date').value;	
				}	
			}
		}
		function check_to_date(){
			var from_date = $('from_date').value.split("/");
			var to_date = $('to_date').value.split("/");
			if((to_date[1] < from_date[1]) || ( to_date[2] < from_date[2])){
				$('from_date').value = $('to_date').value;
			}else{
				if((to_date[0] < from_date[0]) && (from_date[1] == to_date[1]) && (from_date[2] == to_date[2])){
					$('from_date').value = $('to_date').value;
				}
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
</script>