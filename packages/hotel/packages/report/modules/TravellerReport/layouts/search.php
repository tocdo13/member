<script src="packages/core/includes/js/jquery/datepicker.js"></script>
<table width="100%" height="100%" bgcolor="#B5AEB5">
<tr><td>
	<link rel="stylesheet" href="skins/default/report.css">
	<div style="width:100%;height:100%;text-align:center;vertical-align:middle;border:4px double;background-color:white;">
	<div style="padding:10 40 10 80;background-image:url(skins/default/images/back_of_book.jpg);background-repeat:repeat-y;background-position:left;">
	<p>&nbsp;</p>
	<table cellSpacing=0 width="100%" style="font-family:'Times New Roman', Times, serif">
		<tr valign="top">
			<td align="left" width="100%">
			<strong><?php echo Portal::get_setting('company_name');?></strong><br /><?php echo Portal::get_setting('company_address');?></td>
			<td align="center" nowrap>
			<strong>[[.template_code.]]</strong><br />
			<i>[[.promulgation.]]</i>
			</td>
		</tr>	
	</table>
	<table align="center" width="100%">
	<tr>
		<td align="center" style="border:1px dotted #CCCCCC;">
		<p>&nbsp;</p>
		<font class="report_title">[[.traveller_report.]]</font>
		<br><br />
		<form name="SearchForm" method="post">
		<table><tr><td>
		<fieldset><legend>[[.time_select.]]</legend>
		
		 <br>
		<table border="0" align="center" id="select_time">
            <tr>
            <!--Start Luu Nguyen Giap add portal -->
            <?php //if(User::can_admin(false,ANY_CATEGORY)) {?>
            <td width="1%" nowrap="nowrap">[[.hotel.]]</td>
            <td style="margin: 0;" colspan="4"><select name="portal_id" id="portal_id"></select></td>
            <?php //}?>
            <!--End Luu Nguyen Giap add portal -->
            </tr>
			<tr>
                <td>[[.from_day.]]</td>
                <td><input name="date_from" type="text" id="date_from" onchange="changevalue();" /></td>
            </tr>
            <tr>
                <td class="to-date">[[.to_day.]]</td>
                <td class="to-date"><input name="date_to" type="text" id="date_to" onchange="changefromday();" /></td>
            </tr>
			</table><br />
			<table width="100%">
			<tr>
			  <td align="center" nowrap="nowrap"	>[[.status.]]
			    <select  name="status" id="status" style="width:180px;" onchange="if(this.value == 'IN_HOUSE'){jQuery('.to-date').hide();}else{jQuery('.to-date').show();};check_date();">
			      <option selected="selected" value="BOOKED">BOOKED</option>
				  <option value="IN_HOUSE">[[.in_house.]]</option>
			      <option value="CHECKIN">CHECK IN</option>				
			      <option value="CHECKIN_BOOKED">CHECKIN AND BOOKED</option>
				  <option value="CHECKIN_CHECKOUT">CHECKIN AND CHECKOUT</option>
			      <option value="CHECKOUT">CHECK OUT</option>
			      <option value="CANCEL">CANCEL</option>	
			        </select></td>
			  </tr>
		  </table>
		  </fieldset>
		  </td></tr></table>
			<table>
			<tr>
				<td>[[.line_per_page.]]</td>
				<td><input name="line_per_page" type="text" id="line_per_page" value="32" size="4" maxlength="2" style="text-align:right"/></td>
			</tr>
			<tr>
				<td>[[.no_of_page.]]</td>
				<td><input name="no_of_page" type="text" id="no_of_page" value="50" size="4" maxlength="2" style="text-align:right"/></td>
			</tr>
			<tr>
				<td>[[.from_page.]]</td>
				<td><input name="start_page" type="text" id="start_page" value="1" size="4" maxlength="4" style="text-align:right"/></td>
			</tr>
			</table>
			<p>
				<input type="submit" name="do_search" value="  [[.report.]]  "/>
				<input type="button" value="  [[.cancel.]]  " onclick="location='<?php echo Url::build('home');?>';"/>
			</p>
			</form>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
		</td></tr></table>
	</div>
	</div>
</td>
</tr></table>
<script>
    console.log(jQuery('#status').val());
    if(jQuery('#status').val() == 'IN_HOUSE')
    {
        jQuery('.to-date').hide();
    }
    else
    {
        jQuery('.to-date').show();
    }
    function changevalue()
    {
        var myfromdate = $('date_from').value.split("/");
        var mytodate = $('date_to').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#date_to").val(jQuery("#date_from").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('date_from').value.split("/");
        var mytodate = $('date_to').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#date_from").val(jQuery("#date_to").val());
        }
    }
    
	jQuery("#date_from").datepicker();
    jQuery("#date_to").datepicker();

</script>