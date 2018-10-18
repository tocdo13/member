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
			ADD: <?php echo HOTEL_ADDRESS;?><BR>
			Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
			Email: <?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?></td>
			<td align="right" nowrap width="35%" style="padding-right:20px;">
			<strong>[[.template_code.]]</strong><br />
			<i>[[.promulgation.]]</i>
			</td>
		</tr>	
	</table>
	<table width="100%">
	<tr>
	<td width="60px">&nbsp;</td>
	<td align="center">
		<p>&nbsp;</p>
		<p><font class="report_title" style="text-transform:uppercase">[[.CASH_REVENUE_REPORT.]]</font>		    </p>
		<p>
        	<!--IF:cond(Portal::language()==1)--><!--/IF:cond-->
           <br>
		  </p>
		<form name="SearchForm" method="post">
		<table><tr><td>
		<fieldset><legend>[[.time_select.]]</legend>
		<table border="0">
			<tr>
			  <td width="100px"><p>[[.by_year.]]</p>
			  </td>
			  <td><p>
				<select  name="from_year" id="from_year">
				<?php
					for($i=date('Y');$i>=1990;$i--)
					{
						echo '<option value="'.$i.'"'.(($i==URL::get('year',date('Y')))?' selected':'').'>'.$i.'</option>';
					}
				?><option value="" <?php if(isset($_REQUEST['year']) and $_REQUEST['year']==''){echo ' selected';}?>>[[.all_time.]]</option>
				</select></p>
			  </td>
			</tr>
			<tr>
			  <td>[[.by_month.]]</td>
			  <td>
			  <select  name="from_month" id="from_month">
				<option value="01">[[.january.]]</option>
				<option value="02">[[.february.]]</option>
				<option value="03">[[.march.]]</option>
				<option value="04">[[.april.]]</option>
				<option value="05">[[.may.]]</option>
				<option value="06">[[.june.]]</option>
				<option value="07">[[.july.]]</option>
				<option value="08">[[.august.]]</option>
				<option value="09">[[.september.]]</option>
				<option value="10">[[.november.]]</option>
				<option value="11">[[.october.]]</option>
				<option value="12">[[.december.]]</option>
			  </select><script>document.getElementById('from_month').value="<?php echo date('m',time());?>";</script>
			  </td>
			</tr>
			<tr>
			  <td>[[.by_day.]]</td>
			  <td><select  name="from_day" id="from_day">
				<option value="01" selected="selected">1</option>
				<option value="02">2</option>
				<option value="03">3</option>
				<option value="04">4</option>
				<option value="05">5</option>
				<option value="06">6</option>
				<option value="07">7</option>
				<option value="08">8</option>
				<option value="09">9</option>
				<option value="10">10</option>
				<option value="11">11</option>
				<option value="12">12</option>
				<option value="13">13</option>
				<option value="14">14</option>
				<option value="15">15</option>
				<option value="16">16</option>
				<option value="17">17</option>
				<option value="18">18</option>
				<option value="19">19</option>
				<option value="20">20</option>
				<option value="21">21</option>
				<option value="22">22</option>
				<option value="23">23</option>
				<option value="24">24</option>
				<option value="25">25</option>
				<option value="26">26</option>
				<option value="27">27</option>
				<option value="28">28</option>
				<option value="29">29</option>
				<option value="30">30</option>
				<option value="31">31</option>
			  </select><script>document.getElementById('from_day').value="<?php echo date('d',time());?>";</script>
			  </td>
			</tr>
		  </table>
		  </fieldset>
		  </td></tr></table>
			<table>
			<tr>
				<td align="right">[[.line_per_page.]]</td>
				<td align="right">&nbsp;</td>
				<td align="left"><input  name="line_per_page" type="text" id="line_per_page" value="75" size="4" maxlength="2" style="text-align:right"/></td>
			</tr>
			<tr>
				<td align="right">[[.no_of_page.]]</td>
				<td align="right">&nbsp;</td>
				<td align="left"><input name="no_of_page"  type="text" id="no_of_page" value="500" size="4" maxlength="2" style="text-align:right"/></td>
			</tr>
			<tr>
				<td align="right">[[.from_page.]]</td>
				<td align="right">&nbsp;</td>
				<td align="left"><input  name="start_page" type="text" id="start_page" value="1" size="4" maxlength="4" style="text-align:right"/></td>
			</tr>
			    <tr>
				<td align="right" nowrap>[[.payment_type.]]</td>
				<td align="right" nowrap>&nbsp;</td>
				<td align="left">
				<select  name="payment_type" id="payment_type">
					<option value="CASH" selected="selected">[[.cash.]]</option>
					<option value="CREDIT_CARD">[[.credit.]]</option>
				</select>
				</td>
            </tr>
			    <tr>
				<td align="right" nowrap>[[.group_by_customer.]]</td>
				<td align="right" nowrap>&nbsp;</td>
				<td align="left"><input name="customer" type="checkbox" id="customer" value="1" />
				</td>
            </tr>
			    <tr>
				<td align="right" nowrap>[[.revenue.]]</td>
				<td align="right" nowrap>&nbsp;</td>
				<td align="left">
				<select  name="revenue" id="revenue">
					<option value="CHECKOUT" selected="selected">[[.revenue_real.]]</option>
					<option value="ALL">[[.revenue_expected.]]</option>
				</select>
				</td>
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
