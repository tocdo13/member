<table width="100%" height="100%" bgcolor="#B5AEB5">
<tr><td>
	<link rel="stylesheet" href="skins/default/report.css">
	<div style="width:500px;height:100%;text-align:center;vertical-align:middle;border:4px double;background-color:white;">
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
	<table>
	<tr>
	<td width="60px">&nbsp;</td>
	<td align="center">
		<p>&nbsp;</p>
		<font class="report_title">[[.title.]]</font>
		<br>
		<form name="SearchForm" method="post">
		<table><tr><td>
		<fieldset><legend>[[.time_select.]]</legend>
		<table border="0">
			<tr>
			  <td width="100"><p>[[.by_year.]]</p>			  </td>
			  <td><p>
				<select name="year">
				<?php
					for($i=date('Y');$i>=1990;$i--)
					{
						echo '<option value="'.$i.'"'.(($i==URL::get('year',date('Y')))?' selected':'').'>'.$i.'</option>';
					}
				?></select></p>			  </td>
			</tr>
			<tr>
			  <td>[[.by_month.]]</td>
			  <td><select name="month" id="month">
				<option value=""></option>
				<option value="1">[[.january.]]</option>
				<option value="2">[[.february.]]</option>
				<option value="3">[[.march.]]</option>
				<option value="4">[[.april.]]</option>
				<option value="5">[[.may.]]</option>
				<option value="6">[[.june.]]</option>
				<option value="7">[[.july.]]</option>
				<option value="8">[[.august.]]</option>
				<option value="9">[[.september.]]</option>
				<option value="10">[[.november.]]</option>
				<option value="11">[[.october.]]</option>
				<option value="12">[[.december.]]</option>
			  </select>
			  <script>
			  $('month').value='<?php echo date('n');?>';
			  </script>			  </td>
			</tr>
		  </table>
		  </fieldset>
		  </td></tr></table>
			<p>
				<input type="submit" name="do_search" value="  [[.report.]]  "/>
				<input type="button" value="  [[.cancel.]]  " onclick="location='<?php echo User::home_page();?>';"/>
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
