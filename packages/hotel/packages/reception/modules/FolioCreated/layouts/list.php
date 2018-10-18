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
				<td align="right" nowrap width="35%"><strong>[[.template_code.]]</strong></td>
			</tr>	
             <tr valign="top">
				<td align="left" width="65%"></td>
				<td align="right" nowrap width="35%"></td>
			</tr>
            <tr valign="top">
				<td align="left" width="65%">[[.print_by.]] : <?php echo Session::get('user_id');?></td>
				<td align="right" nowrap width="35%"></td>
			</tr>
            <tr valign="top">
				<td align="left" width="65%">[[.print_date.]] : <?php echo date('h:i d/M/Y',time());?></td>
				<td align="right" nowrap width="35%"></td>
			</tr>	
		</table>
	<table cellpadding="0" cellspacing="0" align="center" width="100%">
	<tr>
	<td align="center">
		<font class="report_title" style="font-size:20px; text-align:center;"><b>[[.folio_list.]]</b></font>

		<br><br />
        <label id="timehidden">ngày: [[|from_date|]]</label>
		<form name="WeeklyViewFolioForm" method="post">
		<table width="100%" id="hidden"><tr><td>
		<fieldset><legend><b>[[.search.]]</b></legend>
		<table border="0" style="margin:auto;">
        	<tr style="text-align:center;">
            	<td>[[.room_id.]] : <select name="room_id" id="room_id"  style="width:80px;" ></select> </td>
            	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[[.guest_name.]] : <input name="guest_name" type="text" id="guest_name" > </td>
                <td align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[[.date.]]:
            	<input type="text" name="from_date" id="from_date" onchange="check_from_date();" class="date-input" style="float:right;"/>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
            <tr>
            	<td>[[.code.]]:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input name="code" type="text" id="code" style="width:80px;" > </td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[[.customer_name.]] :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input name="customer_name" type="text" id="customer_name" > </td>
                <td align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="do_search" value="  [[.view.]]  " style="height:30px;"></td>
            </tr>
		  </table>
		  </fieldset>
		  </td></tr></table>
			</form>
	</td></tr></table>
</td>
<div><table width="100%" cellpadding="3" style="border:1px solid #DFDFDF; line-height:30px; margin:10px auto;" id="revenue">
	<tr height="30" style="background:#DFDFDF;">
    	<td width="2%" align="center">[[.no.]]</td>
        <td width="4%" align="center">[[.code.]]</td>
        <td width="4%" align="center">[[.room_name.]]</td>
        <td width="7%" align="center">[[.arrival_date.]]</td>
        <td width="7%" align="center">[[.departure_date.]]</td>
        <td width="9%" align="center">[[.price.]]</td>
        <td width="15%" align="center">[[.guest.]]</td>
        <td width="15%" align="center">[[.customer.]]</td>
        <td width="10%" align="center">[[.total.]]</td>
        <td width="5%" align="center">[[.view.]]</td>
        <td width="10%" align="center">[[.create_time.]]</td>
        <td width="10%" align="center">[[.user.]]</td>
        <td width="12%" align="center">[[.remark.]]</td>
        
    </tr>
    <?php $i= 1;$total=0;$night = 0;?>
<?php $customer_name = ''; ?>
    <!--LIST:items-->
    <tr>
    	<td width="2%" align="center" ><?php echo $i; $i++; $night+=[[=items.nights=]];?></td>
         <td width="4%" align="center">[[|items.id|]]</td>
        <td width="4%" align="center">[[|items.room_name|]]</td>
        <td width="7%" align="center"><?php echo date('h:i<br> d/m/Y',[[=items.time_in=]]);?></td>
        <td width="7%" align="center"><?php echo date('d/m/Y',[[=items.time_out=]]);?></td>
        <td width="9%" align="right" ><?php echo System::display_number([[=items.price=]]); ?></td>
        <td width="15%" align="center">[[|items.full_name|]]</td>
        <td width="15%" align="center">[[|items.customer_name|]]</td>
        <td width="4%" align="right"><?php echo System::display_number([[=items.total_amount=]]);$total +=[[=items.total_amount=]]; ?></td>
        <td width="5%" align="center"><?php echo date('h:i<br> d/m/Y',[[=items.time=]]);?></td>
        <td width="5%" align="center">[[|items.user_id|]]</td>
        <td width="12%" align="center">[[|items.note|]]</td>
    </tr>
    <!--/LIST:items-->
     <tr>
    	<td align="right"  ><b><i>[[.total.]]</i></b></td>
        <td align="center"  ></td>
        <td align="center"  ><b><?php echo ($i-1);?> </b></td>
         <td align="center" colspan="5"  ></td>
        <td align="right"  ><b><?php echo System::display_number($total);?></b> </td>
        <td width="6%" align="center" colspan="3">&nbsp;</td>
    </tr>
        </table>
</div>
</tr>
</table>
<script>
		$('from_date').value = '[[|from_date|]]';
		jQuery("#from_date").datepicker({ minDate: new Date(BEGINNING_YEAR,1 - 1, 1)});
	<!--IF:cond([[=view_all=]]!=1)-->
		jQuery('#from_year').attr('disabled',true);
		jQuery('#from_month').attr('disabled',true);
		jQuery('#from_day').attr('disabled',true);
		jQuery('#to_day').attr('disabled',true);
	<!--/IF:cond-->
</script>