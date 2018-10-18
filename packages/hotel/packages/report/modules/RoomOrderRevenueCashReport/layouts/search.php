<table width="100%" height="100%" bgcolor="#B5AEB5">
<tr><td>
	<link rel="stylesheet" href="skins/default/report.css">
	<div style="width:99%;height:100%;text-align:center;vertical-align:middle;border:4px double;background-color:white;">
	<div style="padding:10 40 10 80;background-image:url(skins/default/images/back_of_book.jpg);background-repeat:repeat-y;background-position:left;">
	<p>&nbsp;</p>
	<table cellSpacing=0 width="100%">
		<tr valign="top">
			<td align="left" width="65%" style="padding-left:80px;">
			<strong><?php echo HOTEL_NAME;?></strong><br />
			<?php echo HOTEL_ADDRESS;?></td>
			<td align="right" nowrap width="35%" style="padding-right:20px;">
			<strong>[[.template_code.]]</strong></td>
		</tr>	
	</table>
	<table cellpadding="0" cellspacing="0" align="center" width="100%">
	<tr><td width="80px">&nbsp;</td>
	<td align="center">
		<p>&nbsp;</p>
		<font class="report_title">[[.cash_revenue_report.]]</font>
		<br>
		<form name="SearchForm" method="post">
		<table><tr><td>
		<fieldset><legend></legend>
		  <table border="0">
			<tr>
			  <td align="right" nowrap="nowrap">[[.hotel.]]</td>
			  <td align="left"><select name="portal_id" id="portal_id"></select>
              </td>
			  </tr>
              <tr>
              	<td>[[.today.]]</td>
                <td><input  name="today" type="checkbox" id="today" value="1" onClick="if(this.checked==true){jQuery('#from_month').val('<?php echo date('m');?>');jQuery('#to_month').val('<?php echo date('m');?>');jQuery('#from_day').val('<?php echo date('d');?>');jQuery('#to_day').val('<?php echo date('d');?>');}"></td>
              </tr>
              <tr>
                  <td align="right">[[.vat_code.]]: </td>
                  <td align="left"><input name="from_code" type="text" id="from_code" style="width:75px;"> [[.to.]]: <input name="to_code" type="text" id="to_code" style="width:75px;"></td>
              </tr>
              </table>
			<table border="0">
			<tr>
			  <td width="100px"><p>[[.by_year.]]</p>			  </td>
			  <td><p>
				<select name="from_year" id="from_year">
				<?php
					for($i=date('Y');$i>=1990;$i--)
					{
						echo '<option value="'.$i.'"'.(($i==URL::get('year',date('Y')))?' selected':'').'>'.$i.'</option>';
					}
				?><option value="" <?php if(isset($_REQUEST['year']) and $_REQUEST['year']==''){echo ' selected';}?>>[[.all_time.]]</option>
				</select></p>			  </td>
			</tr>
			<tr>
			  <td>[[.by_month.]]</td>
			  <td nowrap><select  name="from_month" id="from_month">
				<option value=""></option>
				<option value="01">01</option>
				<option value="02">02</option>
				<option value="03">03</option>
				<option value="04">04</option>
				<option value="05">05</option>
				<option value="06">06</option>
				<option value="07">07</option>
				<option value="08">08</option>
				<option value="09">09</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>				
			  </select>
			    <script>
			  $('from_month').value='<?php echo date('m');?>';
			  $('to_month').value='<?php echo date('m');?>';
			  </script>			  </td>
			</tr>
			<tr>
			  <td nowrap="nowrap">[[.by_day.]]</td>
			  <td>
			  	<select name="from_day" id="from_day"></select>
			  	[[.to.]]
			  	<select name="to_day" id="to_day"></select>
			  <script> $('to_day').value='<?php echo date('d');?>';</script>			  </td>
			</tr>
            <tr>
			  <td align="left">[[.reception_shift.]]</td>
			  <td><select name="shift_id" id="shift_id" style="width:180px;">[[|shift_lists|]]</select></td>
			</tr>
			<tr>
			  <td nowrap="nowrap">[[.by_user.]]</td>
			  <td><select name="user_id" id="user_id"></select></td>
			  </tr>
		  </table>
			</form>     
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
	</div>
	</div>
</td>
</tr></table>
<script>
	<!--IF:cond([[=view_all=]]!=1)-->
		jQuery('#from_year').attr('disabled',true);
		jQuery('#from_month').attr('disabled',true);
		jQuery('#to_month').attr('disabled',true);
		jQuery('#from_day').attr('disabled',true);
		jQuery('#to_day').attr('disabled',true);
	<!--/IF:cond-->
		$('portal_id').value = '<?php echo PORTAL_ID;?>';
</script>