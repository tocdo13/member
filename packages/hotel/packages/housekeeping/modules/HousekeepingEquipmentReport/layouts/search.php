<table width="100%" height="100%" bgcolor="#B5AEB5">
<tr><td align="center">
	<link rel="stylesheet" href="skins/default/report.css">
	<div style="text-align:center;vertical-align:middle;border:4px double;background-color:white;">
	<div style="padding:10 40 10 80;background-image:url(skins/default/images/back_of_book.jpg);background-repeat:repeat-y;background-position:left;">
	<p>&nbsp;</p>
	<table cellSpacing=0 width="100%" style="font-family:'Times New Roman', Times, serif">
		<tr valign="top">
			<td align="left" width="65%" style="padding-left:80px;">
			<strong><?php echo HOTEL_NAME;?></strong><br />
			<?php echo HOTEL_ADDRESS;?></td>
			<td align="right" nowrap width="35%" style="padding-right:20px;">
			<strong>[[.template_code.]]</strong><br />
			<i>[[.promulgation.]]</i>
			</td>
		</tr>	
	</table>
	<table width="100%">
	<tr>
	<td align="center">
		<p>&nbsp;</p>
		<font class="report_title">[[.housekeeping_equipment_report.]]</font>

		<br>
		<form name="SearchForm" method="post">
		<?php if(User::can_admin()){?>
        <div style="margin-top:10px;"><label for="hotel_id">[[.Hotel.]]:</label> <select name="hotel_id" id="hotel_id"></select></div>
        <?php }?>        
		<table><tr><td>
		<fieldset><legend>[[.time_select.]]</legend>
		<table border="0">
            <tr>
                <td>[[.from_date.]]</td>
                <td><input name="from_date_tan" type="text" id="from_date_tan" onchange="changevalue();"/></td>
            </tr>
            <tr>
                <td>[[.to_date.]]</td>
                <td><input name="to_date_tan" type="text" id="to_date_tan" onchange="changefromday();"/></td>
            </tr>
		  </table>
		  </fieldset>
		  </td></tr></table>
			<table>
			<tr>
				<td align="right">[[.line_per_page.]]</td>
				<td align="right">&nbsp;</td>
				<td align="left"><input name="line_per_page" type="text" id="line_per_page" value="999" size="4" maxlength="4" style="text-align:right" class="input_number"/></td>
			</tr>
			<tr>
				<td align="right">[[.no_of_page.]]</td>
				<td align="right">&nbsp;</td>
				<td align="left"><input name="no_of_page" type="text" id="no_of_page" value="400" size="4" maxlength="4" style="text-align:right" class="input_number"/></td>
			</tr>
			<tr>
				<td align="right">[[.from_page.]]</td>
				<td align="right">&nbsp;</td>
				<td align="left"><input name="start_page" type="text" id="start_page" value="1" size="4" maxlength="4" style="text-align:right" class="input_number"/></td>
			</tr>
			<tr>
				<td align="right">[[.room_id.]]</td>
				<td align="right">&nbsp;</td>
				<td align="left">
				<select name="room_id" id="room_id" style="width:115">
					</select>				</td>
			  </tr> 
			</table>
			
			<p>
				<input name="do_search"  type="submit" id="do_search" value="  [[.report.]]  "/>
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

<script type="text/javascript">
    jQuery("#from_date_tan").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>,1 - 1, 1)});
	jQuery("#to_date_tan").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>,1 - 1, 1)});
    $('from_date_tan').value='<?php if(Url::get('from_date_tan')){echo Url::get('from_date_tan');}else{ echo ('01/'.date('m').'/'.date('Y'));}?>';
    $('to_date_tan').value='<?php if(Url::get('to_date_tan')){echo Url::get('to_date_tan');}else{ echo (date('d/m/Y'));}?>';
    
    function changevalue()
    {
        var myfromdate = $('from_date_tan').value.split("/");
        var mytodate = $('to_date_tan').value.split("/");
        if(new Date(myfromdate[2]+'-'+myfromdate[1]+'-'+myfromdate[0])> new Date(mytodate[2]+'-'+mytodate[1]+'-'+mytodate[0]))
        {
            jQuery("#to_date_tan").val(jQuery("#from_date_tan").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('from_date_tan').value.split("/");
        var mytodate = $('to_date_tan').value.split("/");
        if(new Date(myfromdate[2]+'-'+myfromdate[1]+'-'+myfromdate[0])> new Date(mytodate[2]+'-'+mytodate[1]+'-'+mytodate[0]))
        {
            jQuery("#from_date_tan").val(jQuery("#to_date_tan").val());
        }
    }
</script>       