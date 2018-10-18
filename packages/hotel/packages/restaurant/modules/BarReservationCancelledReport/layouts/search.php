<table width="100%" height="100%" bgcolor="#B5AEB5">
<tr><td>
	<link rel="stylesheet" href="skins/default/report.css">
	<div style="width:98%;height:100%;text-align:center;vertical-align:middle;border:4px double;background-color:white;padding-right:10px;">
	<div style="padding:10px 40px 10px 80px;background-image:url(skins/default/images/back_of_book.jpg);background-repeat:repeat-y;background-position:left;">
	<p>&nbsp;</p>
	<table cellSpacing=0 width="100%" style="font-family:'Times New Roman', Times, serif">
		<tr valign="top">
			<td align="left" width="100%">
			<strong><?php echo HOTEL_NAME;?></strong><br /><?php echo HOTEL_ADDRESS;?></td>
			<td align="center" nowrap>
			<strong>[[.template_code.]]</strong>
			</td>
		</tr>	
	</table>
	<table width="100%">
	<tr>
	<td align="center">
		<p>&nbsp;</p>
		<font class="report_title">[[.Bar_reservation_cancelled_report.]]</font>
		<br>
		<form name="SearchForm" method="post">
   		<?php if(User::can_admin()){?>
        <div style="margin-top:10px;"><label for="hotel_id">[[.Hotel.]]:</label> <select name="hotel_id" id="hotel_id" onchange="get_bar();"></select></div>
        <?php }?>
		<table><tr><td>
		<fieldset><legend>[[.time_select.]]</legend>
		<table border="0">
             <tr>
                <td>[[.from_date.]]</td>
                <td><input name="from_date_tan" type="text" id="from_date_tan"/></td>
             </tr> 
             <tr>   
                <td>[[.to_date.]]</td>
                <td><input name="to_date_tan" type="text" id="to_date_tan"/></td>
             </tr>  
             <tr>
             		<td  align="right"  > <input name="checked_all" type="checkbox" id="checked_all"/></td> 
                    <td  align="left"><b><label for="checked_all">[[.select_all_bar.]]</label></b></td>
             </tr>
             <!--LIST:bars-->
             <tr>
             	<!--IF:cond(Session::is_set('bar_id') and Session::get('bar_id')==[[=bars.id=]])-->
                	<td  align="right"> <input name="bar_id_[[|bars.id|]]" type="checkbox" value="[[|bars.id|]]" id="bar_id_[[|bars.id|]]" class="check_box" checked="checked"  /></td>
                <!--ELSE-->
                  <td  align="right"><input name="bar_id_[[|bars.id|]]" type="checkbox" value="[[|bars.id|]]" id="bar_id_[[|bars.id|]]" class="check_box" /></td>
                <!--/IF:cond-->
                  <td  ><label for="bar_id_[[|bars.id|]]">[[|bars.name|]]</label></td>
			 </tr>
             
             <!--/LIST:bars-->
            <!--
            <tr>
			  <td align="left">[[.bar_shift.]]</td>
			  <td><select name="shift_id" id="shift_id" style="width:180px;">[[|shift_list|]]</select></td>
			  </tr>
              -->
             
            <tr>
		  </table>
		  </fieldset>
		  </td></tr></table>
			<table>
			<tr>
				<td>[[.line_per_page.]]</td>
				<td><input type="text" name="line_per_page" value="15" size="4" maxlength="2" style="text-align:right"/></td>
			</tr>
			<tr>
				<td>[[.no_of_page.]]</td>
				<td><input type="text" name="no_of_page" value="10" size="4" maxlength="2" style="text-align:right"/></td>
			</tr>
			<tr>
				<td>[[.from_page.]]</td>
				<td><input type="text" name="start_page" value="1" size="4" maxlength="4" style="text-align:right"/></td>
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
    jQuery('#checked_all').click(
    function ()
    {
        var check = this.checked;
        jQuery('.check_box').each(function(){
            this.checked = check;
        })
    }
    );
    function get_bar()
    {
        SearchForm.submit();
    }
    function check_date(){
        var from_year = jQuery('#from_year').val();
        var to_year = jQuery('#to_year').val();
        //console.log(year);
        var from_month = jQuery('#from_month').val();
        var to_month = jQuery('#to_month').val();
        var from_day = jQuery('#from_day').val();
        var to_day = jQuery('#to_day').val();
        var from_date = new Date(from_year,from_month,from_day);
        
        var to_date = new Date(to_year ,to_month,to_day);
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
<script type="text/javascript">
    jQuery("#from_date_tan").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>,1 - 1, 1)});
	jQuery("#to_date_tan").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>,1 - 1, 1)});
    $('from_date_tan').value='<?php if(Url::get('from_date_tan')){echo Url::get('from_date_tan');}else{ echo ('01/'.date('m').'/'.date('Y'));}?>';
    $('to_date_tan').value='<?php if(Url::get('to_date_tan')){echo Url::get('to_date_tan');}else{ echo (date('d/m/Y'));}?>';
</script>