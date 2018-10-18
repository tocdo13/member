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
			<td align="center" nowrap>
			<strong>[[.template_code.]]</strong><br />
			<i>[[.promulgation.]]</i>
			</td>
		</tr>	
	</table>
	<table align="center" width="100%">
	<tr>
		<td align="center" style="border:1px dotted #CCCCCC;">
		<p>&nbsp;</p>
		<font class="report_title">[[.group_tour_report.]]</font>
		<br>
		<form name="SearchForm" method="post">
		<table><tr><td>
		<fieldset><legend>[[.time_select.]]</legend>
		<table border="0" align="center">
		<tr>
			  <td nowrap="nowrap">[[.today.]]</td>
			  <td nowrap="nowrap"><label>
			    <input type="checkbox" name="today" value="1" onclick="if(this.checked==true){$('select_time').style.display='none';}else{$('select_time').style.display='';}">
			  </label></td>
			  <td nowrap="nowrap">&nbsp;</td>
			  <td nowrap="nowrap">&nbsp;</td>
			  <td nowrap="nowrap">&nbsp;</td>
		  </tr>
		 </table>
		 <br>
		<table border="0" align="center" id="select_time">
              <tr>
                <!--Start Luu Nguyen Giap add portal -->
                <?php //if(User::can_admin(false,ANY_CATEGORY)) {?>
                <td nowrap="nowrap">[[.hotel.]]</td>
                <td style="margin: 0;" colspan="4"><select name="portal_id" id="portal_id"></select></td>
                <?php //}?>
                <!--End Luu Nguyen Giap add portal -->
            </tr>
			<tr>
			  <td width="1%" nowrap="nowrap"><p>[[.from_year.]]</p>			  </td>
			  <td nowrap="nowrap"><p>
				<select name="from_year" id="from_year" style="width:100px;" onchange="check_date();" >
				<?php
					for($i=date('Y')+5;$i>=1990;$i--)
					{
						echo '<option value="'.$i.'"'.(($i==URL::get('year',date('Y')))?' selected':'').'>'.$i.'</option>';
					}
				?></select></p>			  </td>
			  <td width="30" nowrap="nowrap">&nbsp;</td>
			  <td width="1%" nowrap="nowrap"><p>[[.to_year.]]</p></td>
			  <td nowrap="nowrap"><p>
                  <select name="to_year" id="to_year" style="width:100px;" onchange="check_date();" >
                    <?php
					for($i=date('Y')+5;$i>=1990;$i--)
					{
						echo '<option value="'.$i.'"'.(($i==URL::get('year',date('Y')))?' selected':'').'>'.$i.'</option>';
					}
				?></select>
			    </p></td>
			</tr>
			<tr>
			  <td width="1%" nowrap="nowrap">[[.from_month.]]</td>
			  <td nowrap="nowrap"><select  name="from_month" id="from_month" style="width:100px;" onchange="check_date();">
				<option value="01">[[.january.]]</option>
				<option value="02">[[.february.]]</option>
				<option value="03">[[.march.]]</option>
				<option value="04">[[.april.]]</option>
				<option value="05">[[.may.]]</option>
				<option value="06">[[.june.]]</option>
				<option value="07">[[.july.]]</option>
				<option value="08">[[.august.]]</option>
				<option value="09">[[.september.]]</option>
				<option value="10">[[.october.]]</option>
				<option value="11">[[.november.]]</option>
				<option value="12">[[.december.]]</option>
			  </select>
			  <script>
			  document.getElementById('from_month').value='<?php echo date('m',time());?>';
			  </script>			  </td>
			  <td width="30" nowrap="nowrap">&nbsp;</td>
			  <td width="1%" nowrap="nowrap">[[.to_month.]]</td>
			  <td nowrap="nowrap"><select  name="to_month" id="to_month" style="width:100px;" onchange="check_date();">
                  <option value="01">[[.january.]]</option>
                  <option value="02">[[.february.]]</option>
                  <option value="03">[[.march.]]</option>
                  <option value="04">[[.april.]]</option>
                  <option value="05">[[.may.]]</option>
                  <option value="06">[[.june.]]</option>
                  <option value="07">[[.july.]]</option>
                  <option value="08">[[.august.]]</option>
                  <option value="09">[[.september.]]</option>
                  <option value="10">[[.october.]]</option>
                  <option value="11">[[.november.]]</option>
                  <option value="12">[[.december.]]</option>
                </select>
                  <script>
			   document.getElementById('to_month').value='<?php echo date('m',time());?>';
			    </script></td>
			</tr>
			<tr>
			  <td width="1%" nowrap="nowrap">[[.from_day.]]</td>
			  <td nowrap="nowrap"><select  name="from_day" id="from_day" style="width:100px;" onchange="check_date();">
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
              </select></td>
			  <td width="30" nowrap="nowrap">&nbsp;</td>
			  <td width="1%" nowrap="nowrap">[[.to_day.]]</td>
			  <td nowrap="nowrap"><select  name="to_day" id="to_day" style="width:100px;" onchange="check_date();">
                  <option value="01">1</option>
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
              </select>
			  <script>
			  	$('to_day').value='<?php echo date('d')?>';</script>			  </td>
			</tr>
			</table>
			<table width="100%">
			<tr>
			  <td width="176" nowrap="nowrap" align="right"	>&nbsp;</td>
			  <td width="258" nowrap="nowrap">&nbsp;</td>
			  </tr>
		  </table>
		  </fieldset>
		  </td></tr></table>
			<table>
			<tr>
				<td>[[.line_per_page.]]</td>
				<td><input name="line_per_page" type="text" id="line_per_page" value="999" size="4" maxlength="3" style="text-align:right"/></td>
			</tr>
			<tr>
				<td>[[.no_of_page.]]</td>
				<td><input name="no_of_page" type="text" id="no_of_page" value="50" size="4" maxlength="2" style="text-align:right"/></td>
			</tr>
			<tr>
				<td>[[.from_page.]]</td>
				<td><input name="start_page" type="text" id="start_page" value="1" size="4" maxlength="4" style="text-align:right"/></td>
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
<script>
    function check_date(){
        var from_year = jQuery('#from_year').val();
        var to_year = jQuery('#to_year').val();
        //console.log(year);
        var from_month = jQuery('#from_month').val();
        var to_month = jQuery('#to_month').val();
        var from_day = jQuery('#from_day').val();
        var to_day = jQuery('#to_day').val();
        var from_date = new Date(from_year,from_month,from_day);
        
        var to_date = new Date(to_year,to_month,to_day);
        if(from_date>to_date){
            alert('Ngày bắt đầu lớn hơn ngày kết thúc');
            jQuery('#from_year').css('border','1px solid red');
            jQuery('#from_month').css('border','1px solid red');
            jQuery('#from_day').css('border','1px solid red'); 
        }else{
            jQuery('#from_year').css('border','1px solid #000');
            jQuery('#from_month').css('border','1px solid #000');
            jQuery('#from_day').css('border','1px solid #000');
        }
    }
</script>