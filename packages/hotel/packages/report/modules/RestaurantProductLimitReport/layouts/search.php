<table width="100%" height="100%" bgcolor="#B5AEB5">
<tr><td>
	<link rel="stylesheet" href="skins/default/report.css">
	<div style="width:98%;height:100%;text-align:center;vertical-align:middle;border:4px double;background-color:white;padding-right:10px;">
	<div style="padding:10px 40px 10px 80px;background-image:url(skins/default/images/back_of_book.jpg);background-repeat:repeat-y;background-position:left;">
	<p>&nbsp;</p>
	<table cellSpacing=0 width="100%" style="font-family:'Times New Roman', Times, serif">
		<tr valign="top">
			<td align="left" width="100%">
			<strong><?php echo HOTEL_NAME;?></strong><br />
			ADD: <?php echo HOTEL_ADDRESS;?><BR>
			Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
			Email: <?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?></td>
			<td align="right" nowrap>
			<strong>[[.template_code.]]</strong><br />
			<i>[[.promulgation.]]</i>
			</td>
		</tr>	
	</table>
	<table>
	<tr><td width="80px">&nbsp;</td>
	<td align="center">
		<p>&nbsp;</p>
		<font class="report_title">[[.Restaurant_product_limit_report.]]</font>
		<br>
		<form name="SearchForm" method="post">
		<table><tr><td>
		<fieldset><legend>[[.time_select.]]</legend>
		<table border="0">
			<tr>
			  <td width="1%" nowrap="nowrap"><p>[[.from_year.]]</p>			  </td>
			  <td nowrap="nowrap"><p>
				<select  name="from_year" id="from_year" style="width:100px;">
				<?php
					for($i=date('Y');$i>=1990;$i--)
					{
						echo '<option value="'.$i.'"'.(($i==URL::get('year',date('Y')))?' selected':'').'>'.$i.'</option>';
					}
				?></select></p>			  </td>
			  <td width="30" nowrap="nowrap">&nbsp;</td>
			  <td width="1%" nowrap="nowrap"><p>[[.to_year.]]</p></td>
			  <td nowrap="nowrap"><p>
                  <select  name="to_year" id="to_year" style="width:100px;">
                    <?php
					for($i=date('Y');$i>=1990;$i--)
					{
						echo '<option value="'.$i.'"'.(($i==URL::get('year',date('Y')))?' selected':'').'>'.$i.'</option>';
					}
				?></select>
			    </p></td>
			</tr>
			<tr>
			  <td width="1%" nowrap="nowrap">[[.from_month.]]</td>
			  <td nowrap="nowrap"><select  name="from_month" id="from_month" style="width:100px;">
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
			  $('from_month').value='<?php echo date('n');?>';
			  </script>			  </td>
			  <td width="30" nowrap="nowrap">&nbsp;</td>
			  <td width="1%" nowrap="nowrap">[[.to_month.]]</td>
			  <td nowrap="nowrap"><select  name="to_month" id="to_month" style="width:100px;">
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
			  $('to_month').value='<?php echo date('n');?>';
			    </script></td>
			</tr>
			<tr>
			  <td width="1%" nowrap="nowrap">[[.from_day.]]</td>
			  <td nowrap="nowrap"><select  name="from_day" id="from_day" style="width:100px;">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
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
              </select></td>
			  <td width="30" nowrap="nowrap">&nbsp;</td>
			  <td width="1%" nowrap="nowrap">[[.to_day.]]</td>
			  <td nowrap="nowrap"><select  name="to_day" style="width:100px;">
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                  <option value="7">7</option>
                  <option value="8">8</option>
                  <option value="9">9</option>
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
              </select>
			  <script>
			  	$('to_day').value=<?php echo date('d')?></script>			  </td>
			</tr>
		  </table>
		  </fieldset>
		  </td></tr>
          </table>
			<table border="0">
			<tr>
				<td>[[.bar_id.]]</td>
				<td>
				<select name="bar_id" style="width:200">
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
