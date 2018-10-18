<table width="100%" height="100%" bgcolor="#B5AEB5">
<tr><td align="center">
	<link rel="stylesheet" href="skins/default/report.css">
	<div style="width:99%;height:100%;text-align:center;vertical-align:middle;border:4px double;background-color:white;">
	<div style="padding:10 40 10 80;background-image:url(skins/default/images/back_of_book.jpg);background-repeat:repeat-y;background-position:left;">
	<p>&nbsp;</p>
	<table cellSpacing=0 width="100%" style="font-family:'Times New Roman', Times, serif">
		<tr valign="top">
			<td align="left" width="65%" style="padding-left:80px;">
			<strong><?php echo HOTEL_NAME;?></strong><br />
			ADD: <?php echo HOTEL_ADDRESS;?><BR>
			Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
			Email: <?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?></td>
			<td align="right" nowrap width="35%" style="padding-right:20px;">
			<strong>[[.template_code.]]</strong><br /></td>
		</tr>	
	</table>
	<table width="100%">
	<tr>
	<td width="60px">&nbsp;</td>
	<td align="center"><span class="report_title">[[.all_invoices_in_date.]]</span>
		<form name="SearchForm" method="post"><br /><br />
        	<fieldset style="width:300px">
              <table border="0">
                <tr>
                  <td colspan="2"><legend><strong>[[.time_select.]]</strong></legend></td>
                  </tr>
                <tr>
                  <td width="100"><p>[[.date.]]</p>
                    </td>
                  <td><p><select name="date" id="date"></select></p></td>
                  </tr>
              </table>
             </fieldset>
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
