<script src="packages/core/includes/js/jquery/datepicker.js"></script>
<table width="100%" height="100%" bgcolor="#B5AEB5">
<tr><td>
	<link rel="stylesheet" href="skins/default/report.css">
	<div style="width:99%;height:100%;text-align:center;vertical-align:middle;border:4px double;background-color:white;">
	<div style="padding:10 40 10 80;background-image:url(skins/default/images/back_of_book.jpg);background-repeat:repeat-y;background-position:left;">
	<p>&nbsp;</p>
	<table cellpadding="0" cellspacing="0" align="center" width="100%">
	<tr><td width="80px">&nbsp;</td>
	<td align="center">
		<p>&nbsp;</p>
		<font class="report_title">[[.karaoke_debit_report.]]</font>
		<br>
		<form name="SearchForm" method="post">
   		<?php if(User::can_admin(false,ANY_CATEGORY)){?>
        <div style="margin-top:10px;"><label for="hotel_id">[[.Hotel.]]:</label> <select name="hotel_id" id="hotel_id"></select></div>
        <?php }?>
		<table><tr><td>
		<fieldset><legend>[[.select.]]</legend>
		<table border="0">
			<tr>
                <td align="right">[[.from_day.]]:</td>
                <td><input name="date_from" type="text" id="date_from" onchange="changevalue();" /></td>
            </tr>
            <tr>
                <td align="right">[[.to_day.]]:</td>
                <td><input name="date_to" type="text" id="date_to" onchange="changefromday();" /></td>
            </tr>
		
            <tr>
              <td align="right">[[.customer_code.]]:&nbsp;</td>
              <td><input name="code" type="text" id="code" style="width:180px;" /></td>
            </tr>
            <tr>
              <td align="right">[[.company.]]:&nbsp;</td>
              <td><input name="customer_name" type="text" id="customer_name" style="width:180px;" /></td>
            </tr>
			  
		  </table>
		  </fieldset>
		  </td></tr></table>
			<table>
			<tr>
				<td align="right">[[.line_per_page.]]</td>
				<td align="right">&nbsp;</td>
				<td align="left"><input name="line_per_page" type="text" id="line_per_page" value="15" size="4" maxlength="2" style="text-align:right"/></td>
			</tr>
			<tr>
				<td align="right">[[.no_of_page.]]</td>
				<td align="right">&nbsp;</td>
				<td align="left"><input  name="no_of_page" type="text" id="no_of_page" value="400" size="4" maxlength="2" style="text-align:right"/></td>
			</tr>
			<tr>
				<td align="right">[[.from_page.]]</td>
				<td align="right">&nbsp;</td>
				<td align="left"><input name="start_page" type="text" id="start_page" value="1" size="4" maxlength="4" style="text-align:right"/></td>
			</tr>
			</table>
			
			<p>
				<input type="submit" name="do_search" value="  [[.report.]]  ">
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
    function changevalue()
    {
        var myfromdate = $('date_from').value.split("/");
        var mytodate = $('date_to').value.split("/");
        if(myfromdate[2] > mytodate[2])
            $('date_to').value =$('date_from').value;
        else{
            if(myfromdate[1] > mytodate[1])
                $('date_to').value =$('date_from').value;
            else{
                if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1]))
                    $('date_to').value =$('date_from').value;
            }    
        }    
        
    }
    function changefromday()
    {
        var myfromdate = $('date_from').value.split("/");
        var mytodate = $('date_to').value.split("/");
        if(myfromdate[2] > mytodate[2])
            $('date_from').value= $('date_to').value;
        else
        {
            if(myfromdate[1] > mytodate[1])
                $('date_from').value = $('date_to').value;
            else
            {
                if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1]))
                    $('date_from').value =$('date_to').value;
            }
       } 
    }
    
	jQuery("#date_from").datepicker();
    jQuery("#date_to").datepicker();

</script>