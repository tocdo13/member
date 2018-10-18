<script src="packages/core/includes/js/jquery/datepicker.js"></script>
<table width="100%" height="100%" bgcolor="#B5AEB5">
<tr><td>
	<link rel="stylesheet" href="skins/default/report.css">
	<div style="width:99%;height:100%;text-align:center;vertical-align:middle;border:4px double;background-color:white;">
	<div style="padding:10 40 10 80;background-image:url(skins/default/images/back_of_book.jpg);background-repeat:repeat-y;background-position:left;">
	<p>&nbsp;</p>
	<table cellSpacing=0 width="100%" style="font-family:'Times New Roman', Times, serif">
		<tr valign="top">
			<td align="left" width="65%" style="padding-left:80px;">
			<strong><?php echo HOTEL_NAME;?></strong><br />
			<?php echo HOTEL_ADDRESS;?></td>
			<td align="right" nowrap width="35%" style="padding-right:20px;">
			<strong>[[.template_code.]]</strong></td>
		</tr>	
	</table>
	<table width="100%">
	<tr>
	<td align="center">
		<p>&nbsp;</p>
		<font class="report_title">[[.forgot_object_list_report.]]</font>

		<br>
		<form name="SearchForm" method="post">
		<?php if(User::can_admin()){?>
        <div style="margin-top:10px;"><label for="hotel_id">[[.Hotel.]]:</label> <select name="hotel_id" id="hotel_id"></select></div>
        <?php }?>        
		<table><tr><td>
		<fieldset><legend>[[.time_select.]]</legend>
		<table border="0">
			<tr>
                <td align="right">[[.from_day.]]:</td>
                <td><input name="date_from" type="text" id="date_from" onchange="changevalue();" /></td>
            </tr>
            <tr>
                <td align="right">[[.to_day.]]:</td>
                <td><input name="date_to" type="text" id="date_to" onchange="changefromday();" /></td>
            </tr>
		  </table>
		  </fieldset>
		  </td></tr></table>
			<table>
			<tr>
				<td>[[.line_per_page.]]</td>
				<td><input type="text" name="line_per_page" value="15" size="4" maxlength="2" style="text-align:right"/></td>
			</tr>
			<tr>
				<td>[[.no_of_page.]]</td>
				<td><input type="text" name="no_of_page" value="10" size="4" maxlength="2" style="text-align:right"/></td>
			</tr>
			<tr>
				<td>[[.from_page.]]</td>
				<td><input type="text" name="start_page" value="1" size="4" maxlength="4" style="text-align:right"/></td>
			</tr>
			<tr>
				<td>[[.room_id.]]</td>
				<td>
				<select name="room_id" id="room_id" style="width:115">
					</select>
				</td>
			  </tr> 
			<tr>
			 <td nowrap>[[.name.]]</td>
				<td nowrap>
					<input name="name" type="text" id="name" size="18"></td>
			</tr>
			<tr>	
				<td nowrap>[[.object_type.]]</td>
				<td><input name="type" type="text" id="type" size="18"></td>
			</tr>
			<tr>
				<td>[[.status.]]</td>
				<td>
					<select  name="status">
                                                <option value="">[[.all.]]</option>
						<option value="0">[[.notpay.]]</option>
						<option value="1">[[.pay.]]</option>
                        <option value="2">[[.handle.]]</option>
					</select>
				</td>
			  </tr> 
			<!--
            <tr>
			  <td nowrap>[[.employee.]]</td>
				<td>
					<select name="employee_id"></select>
				</td>
			</tr>
            -->
			</table>
			<p>
				<input type="submit" name="do_search" value="  [[.report.]]  "/>
				<input type="button" value="[[.cancel.]]" onclick="location='<?php echo User::home_page();?>';"/>
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