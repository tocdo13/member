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
			</tr>	
	</table>
	<table align="center" width="100%">
	<tr>
		<td align="center" style="border:1px dotted #CCCCCC;">
		<p>&nbsp;</p>
		<font class="report_title">[[.debit_overbalance_report.]]</font>
		<br><br>
		<form name="SearchForm" method="post">
		<table><tr><td>
		<fieldset>
		<table border="0" align="center" id="select_time">
			<tr>
			  <td>[[.hotel.]]</td>
			  <td><select name="portal_id" id="portal_id">
			    </select></td>
			  <td nowrap="nowrap">&nbsp;</td>
			  </tr>
			<tr>
			  <td width="1%" nowrap="nowrap"><p>[[.customer.]]</p> </td>
			  <td nowrap="nowrap">
                <p>
				    <input name="customer_id" type="hidden" id="customer_id" />
                    <input name="customer_name" type="text" id="customer_name" onfocus="CustomerAutocomplete();" autocomplete="off"/>
                </p>
              
              </td>
			  <td nowrap="nowrap">&nbsp;</td>
			</tr>
            
            
            <tr>
              <td>[[.date_from.]]</td>
              <td><input name="date_from" type="text" id="date_from" onchange="changevalue();" tabindex="1" /></td>
            </tr>
            <tr>
              <td>[[.date_to.]]</td>
              <td><input name="date_to" type="text" id="date_to" onchange="changefromday();" tabindex="2" /></td>
            </tr>
			
			</table>
			</fieldset>
		  </td></tr></table>
		  <br />
		  <table border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
            
          </table>
		  <p>&nbsp;</p>
          <!-- 
			<table>
			<tr>
				<td align="right">[[.line_per_page.]]</td>
				<td><input name="line_per_page" type="text" id="line_per_page" value="32" size="4" maxlength="2" style="text-align:right"/></td>
			</tr>
			<tr>
				<td align="right">[[.no_of_page.]]</td>
				<td><input name="no_of_page" type="text" id="no_of_page" value="50" size="4" maxlength="2" style="text-align:right"/></td>
			</tr>
			<tr>
				<td align="right">[[.from_page.]]</td>
				<td><input name="start_page" type="text" id="start_page" value="1" size="4" maxlength="4" style="text-align:right"/></td>
			</tr>
			</table>
            -->
			<p>
				<input type="submit" name="do_search" value="[[.report.]]" />
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
	jQuery("#date_from").datepicker();
	jQuery("#date_to").datepicker();
    function changevalue()
    {
        var myfromdate = $('date_from').value.split("/");
        var mytodate = $('date_to').value.split("/");
        if(new Date(myfromdate[2]+'-'+myfromdate[1]+'-'+myfromdate[0])> new Date(mytodate[2]+'-'+mytodate[1]+'-'+mytodate[0]))
        {
            jQuery("#date_to").val(jQuery("#date_from").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('date_from').value.split("/");
        var mytodate = $('date_to').value.split("/");
        if(new Date(myfromdate[2]+'-'+myfromdate[1]+'-'+myfromdate[0])> new Date(mytodate[2]+'-'+mytodate[1]+'-'+mytodate[0]))
        {
            jQuery("#date_from").val(jQuery("#date_to").val());
        }
    }
    function CustomerAutocomplete()
    {
        jQuery("#customer_name").autocomplete({
            url: 'get_customer_search.php?customer=review_debit_customer',
            onItemSelect: function (item) {
                jQuery("#customer_id").val(item.data[0]);
            }
        })
    }
</script>