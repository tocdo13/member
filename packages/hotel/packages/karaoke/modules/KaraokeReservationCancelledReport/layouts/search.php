<script src="packages/core/includes/js/jquery/datepicker.js"></script>
<table width="100%" height="100%" bgcolor="#B5AEB5">
<tr><td>
	<link rel="stylesheet" href="skins/default/report.css">
	<div style="width:98%;height:100%;text-align:center;vertical-align:middle;border:4px double;background-color:white;padding-right:10px;">
	<div style="padding:10px 40px 10px 80px;background-image:url(skins/default/images/back_of_book.jpg);background-repeat:repeat-y;background-position:left;">
	<p>&nbsp;</p>
	<table cellSpacing=0 width="100%" style="font-family:'Times New Roman', Times, serif">
		<tr valign="top">
			<td align="left" width="60%">
			<strong><?php echo HOTEL_NAME;?></strong><br /><?php echo HOTEL_ADDRESS;?></td>
			<td align="right" nowrap>
			<strong>[[.template_code.]]</strong>
            <br />
            [[.date_print.]]:<?php echo ' '.date('d/m/Y H:i');?>
            <br />
            [[.user_print.]]:<?php echo ' '.User::id();?>
			</td>
		</tr>	
	</table>
	<table width="100%">
	<tr>
	<td align="center">
		<p>&nbsp;</p>
		<font class="report_title">[[.Karaoke_reservation_cancelled_report.]]</font>
		<br>
		<form name="SearchForm" method="post">
   		<?php if(User::can_admin()){?>
        <div style="margin-top:10px;"><label for="hotel_id">[[.Hotel.]]:</label> <select name="hotel_id" id="hotel_id"></select></div>
        <?php }?>
		<table><tr><td>
		<fieldset><legend>[[.time_select.]]</legend>
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
             		<td align="right"  > <input name="checked_all" type="checkbox" id="checked_all" onClick="jQuery('.check_box').attr('checked',jQuery(this).attr('checked'));" ></td> 
                    <td align="left"><b><label for="checked_all">[[.select_all_karaoke.]]</label></b></td>
             </tr>
             <!--LIST:karaokes-->
             <tr>
             	<!--IF:cond(Session::is_set('karaoke_id') and Session::get('karaoke_id')==[[=karaokes.id=]])-->
                	<td align="right"> <input name="karaoke_id_[[|karaokes.id|]]" type="checkbox" value="[[|karaokes.id|]]" id="karaoke_id_[[|karaokes.id|]]" class="check_box" checked="checked"  /></td>
                <!--ELSE-->
                  <td align="right"><input name="karaoke_id_[[|karaokes.id|]]" type="checkbox" value="[[|karaokes.id|]]" id="karaoke_id_[[|karaokes.id|]]" class="check_box" /></td>
                <!--/IF:cond-->
                  <td><label for="karaoke_id_[[|karaokes.id|]]">[[|karaokes.name|]]</label></td>
			 </tr>
             
             <!--/LIST:karaokes-->
            <!--
            <tr>
			  <td align="left">[[.karaoke_shift.]]</td>
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