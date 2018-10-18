<script src="packages/core/includes/js/jquery/datepicker.js"></script>
<table width="100%" height="100%" bgcolor="#B5AEB5">
<tr><td>
	<link rel="stylesheet" href="skins/default/report.css">
	<div style="width:99%;height:100%;text-align:center;vertical-align:middle;border:4px double;background-color:white;">
	<div style="padding:10 40 10 80;background-image:url(skins/default/images/back_of_book.jpg);background-repeat:repeat-y;background-position:left;">
	<p>&nbsp;</p>
	<table cellpadding="0" cellspacing="0" align="center" width="100%">
	<tr><td width="80px">&nbsp;</td>
	<td align="center">
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
		<p>&nbsp;</p>
		<font class="report_title">[[.karaoke_order_revenue_report.]]</font>
		<br>
		<form name="SearchForm" method="post">
   		<?php if(User::can_admin(false,ANY_CATEGORY)){?>
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
              <td align="left">[[.customer_code.]]</td>
			  <td><input name="code" type="text" id="code" style="width:180px;" /></td>
			  </tr>
			<tr>
              <td align="left">[[.revenue.]]</td>
			  <td><select  name="revenue" id="revenue" style="width:180px;" onChange="if(this.value=='min'){jQuery('.check_box').attr('checked',true);jQuery('#checked_all').attr('checked',true);}" >
              	<option value="all">------All------</option>
                <option value="min">-----Dưới 200,000-----</option>
                <option value="max">-----Từ 200,000-----</option>
              </select></td>
			  </tr>
             <tr>
             		<td align="right"> <input name="checked_all" type="checkbox" id="checked_all" onClick="jQuery('.check_box').attr('checked',jQuery(this).attr('checked'));" ></td> 
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
			  <td align="left">[[.by_time.]]</td>
			  <td>
                <input name="start_time" type="text" id="start_time" style="width:40px;" onblur="validate_time(this,this.value);" />
                [[.to.]]
                <input name="end_time" type="text" id="end_time" style="width:40px;" onblur="validate_time(this,this.value);" />
              </td>
			 </tr>
            <tr>
			  <td align="left">[[.user.]]</td>
			  <td><select name="user_id" id="user_id" style="width:180px;"></select></td>
			  </tr>
			<tr>
			  <td colspan="2" nowrap="nowrap">&nbsp;</td>
			  </tr>
            <tr>
                <td colspan="2"><hr noshade size="1" style="padding: 0; margin: 0; text-shadow: none; color: #C6E2FF;" /></td>
            </tr>
            <tr>
                  <td align="left">[[.vat_code.]]: </td>
                  <td align="left"><input name="from_code" type="text" id="from_code" class="input_number" style="width:75px;"/> [[.to.]]: <input name="to_code" type="text" id="to_code" class="input_number" style="width:75px;"/></td>
            </tr>
            			<tr>
			  <td colspan="2" nowrap="nowrap">&nbsp;</td>
			  </tr>
              
		  </table>
		  </fieldset>
		  </td></tr></table>
			<table>
			<tr>
				<td align="right">[[.line_per_page.]]</td>
				<td align="right">&nbsp;</td>
				<td align="left"><input name="line_per_page" type="text" id="line_per_page" value="30" size="4" maxlength="2" style="text-align:right"/></td>
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
				<input type="submit" name="do_search" value="  [[.report.]]  " id="do_search" onclick=" return check_karaoke();"/>
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
	var karaokes_list = [[|karaoke_lists|]];
	getKaraokeShift([[|karaoke_id|]]);
	
	$('hotel_id').value = '<?php echo PORTAL_ID;?>';
	function getKaraokeShift(id){
		for(var i in karaokes_list){
			if(karaokes_list[i]['id'] == id){
				jQuery('#shift_id').html('<option value="0">-------</option>');
				for(var j in karaokes_list[i]['shifts']){
					var shifts = karaokes_list[i]['shifts'][j];
					jQuery('#shift_id').append('<option value="'+shifts['id']+'">'+shifts['name']+': '+shifts['brief_start_time']+'h - '+shifts['brief_end_time']+'h </option>');
				}
			}
		}
	}
    
    jQuery('#start_time').mask("99:99");
    jQuery('#end_time').mask("99:99");
    
    function test_checked()
    {
        var check  = false;
        jQuery(".check_box").each(function (){

            if(this.checked)
                check = true;
    	});
        return check;
    }
    
    function check_karaoke()
    {
        var validate = test_checked();
        if( validate)
        {
            return true;

        }
        else
        {
            alert('[[.you_must_choose_karaoke.]]');
            return false;

        }
    }
  
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