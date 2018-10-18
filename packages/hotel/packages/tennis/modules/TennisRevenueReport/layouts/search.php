<script>
jQuery(document).ready(function(){
	jQuery('#date_from').datepicker();
	jQuery('#date_to').datepicker();
 });

</script>
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
			<strong>[[.template_code.]]</strong><br />
			<i>[[.promulgation.]]</i>
			</td>
		</tr>	
	</table>
	<table cellpadding="0" cellspacing="0" align="center" width="100%">
	<tr><td width="80px">&nbsp;</td>
	<td align="center">
		<p>&nbsp;</p>
		<font class="report_title"><?php if(Url::get('type')==1){echo Portal::language('tennis_report_by_date');}else{echo Portal::language('tennis_report_by_court');}?></font>
		<br>
		<form name="SearchForm" method="post">
		<table><tr><td>
		<fieldset><legend>[[.time_select.]]</legend>
		<table width="100%" border="0" cellspacing="10" cellpadding="0">
		  <tr>
			<td>
			<div style="width:80px;float:left">[[.date_from.]]</div>&nbsp;<input name="date_from" type="text" id="date_from" style="width:80px;height:15px;">
			&nbsp;[[.date_to.]]&nbsp;<input name="date_to" type="text" id="date_to" style="width:80px;height:15px;">
			</td>
		  </tr>
		  <?php if(Url::get('type')==2){?>
		  <tr>
			<td>
			<div style="width:80px;float:left">[[.court_id.]]</div>&nbsp;<select name="court_id" id="court_id"></select>
			</td>
		  </tr>			   
		  <?php }?>
		  <tr>
			<td>
			<div style="width:80px;float:left">[[.status.]]</div>&nbsp;<select name="status" id="status"></select>
			</td>
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
				<td align="left"><input  name="no_of_page" type="text" id="no_of_page" value="50" size="4" maxlength="2" style="text-align:right"/></td>
			</tr>
			<tr>
				<td align="right">[[.from_page.]]</td>
				<td align="right">&nbsp;</td>
				<td align="left"><input name="start_page" type="text" id="start_page" value="1" size="4" maxlength="4" style="text-align:right"/></td>
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
