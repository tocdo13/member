<script src="packages/core/includes/js/jquery/datepicker.js"></script>
<link rel="stylesheet" href="packages/hotel/packages/report/includes/css/report.css"/>
<table width="100%" height="100%" bgcolor="#B5AEB5">
<tr><td>
	<link rel="stylesheet" href="skins/default/report.css"/>
	<div style="width:99%;height:100%;text-align:center;vertical-align:middle;border:4px double;background-color:white;">
	<div style="padding:10 40 10 80;background-image:url(skins/default/images/back_of_book.jpg);background-repeat:repeat-y;background-position:left;">
	<p>&nbsp;</p>
	<table cellpadding="0" cellspacing="0" align="center" width="100%">
	<tr><td width="80px">&nbsp;</td>
	<td align="center">
		<p>&nbsp;</p>
        <h2 class="report-title-new">[[.detailed_daily_report.]]</h2>
		<br>
		<form name="SearchForm" method="post">
   		<?php //if(User::can_admin(false,ANY_CATEGORY)){?>
        <div style="margin-top:10px;"><label for="hotel_id">[[.Hotel.]]:</label> <select name="hotel_id" id="hotel_id" onchange="get_bar()"></select></div>
        <?php //}?>
		<table><tr><td>
		<fieldset><legend>[[.time_select.]]</legend>
		<table border="0">
			<tr>
                <td >[[.from_day.]]:</td>
                <td colspan="2"><input name="date_from" type="text" id="date_from" onchange="changevalue();" class="input-long-text" /></td>
            </tr>
            <tr>
                <td >[[.to_day.]]:</td>
                <td colspan="2"><input name="date_to" type="text" id="date_to" onchange="changefromday();" class="input-long-text"/></td>
            </tr>
            <tr>    
                    <td></td>
             		<td style="width: 25px;"> <input name="checked_all" type="checkbox" id="checked_all"/></td> 
                    <td ><b><label for="checked_all">[[.select_all_bar.]]</label></b></td>
             </tr>
			<!--LIST:bars-->
             <tr>
                <td></td>
             	<!--IF:cond(Session::is_set('bar_id') and Session::get('bar_id')==[[=bars.id=]])-->
                	<td ><input name="bar_id_[[|bars.id|]]" type="checkbox" value="[[|bars.id|]]" id="bar_id_[[|bars.id|]]" class="check_box" checked="checked" /></td>
                <!--ELSE-->
                  <td ><input name="bar_id_[[|bars.id|]]" type="checkbox" value="[[|bars.id|]]" id="bar_id_[[|bars.id|]]" class="check_box" /></td>
                <!--/IF:cond-->
                  <td><label for="bar_id_[[|bars.id|]]">[[|bars.name|]]</label></td>
			 </tr>
             <!--/LIST:bars-->
            <!--
            <tr>
			  <td align="left">[[.bar_shift.]]</td>
			  <td><select name="shift_id" id="shift_id" style="width:180px;">[[|shift_list|]]</select></td>
			  </tr>
              -->
            <tr>
			  <td align="left">[[.by_time.]]</td>
			  <td colspan="2">
                <input name="start_time" type="text" id="start_time" class="input-short-text" onblur="validate_time(this,this.value);" />
                [[.to.]]
                <input name="end_time" type="text" id="end_time" class="input-short-text"  onblur="validate_time(this,this.value);" />
              </td>
			 </tr>
             <tr>
			  <td  align="left" nowrap="nowrap">[[.user_status.]]</td>
			  <td colspan="2">
                  <!-- 7211 -->  
                  <select style="" name="user_status" id="user_status" class="brc2" style="width: 150px !important;">
                    <option value="1">Active</option>
                    <option value="0">All</option>
                  </select>
                  <!-- 7211 end--> 
              </td>
		    </tr>
            <tr>
			  <td align="left">[[.user.]]</td>
			  <td colspan="2"><select name="user_id" id="user_id" class="input-long-text"></select></td>
			  </tr>
		  </table>
		  </fieldset>
		  </td></tr></table>
			
			<p>
				<input type="submit" name="do_search" value="  [[.report.]]  " onclick="return check_bar();"/>
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
jQuery("#checked_all").click(function (){
        console.log('1111');
        var check  = this.checked;
        jQuery(".check_box").each(function(){
            this.checked = check;
        });
    });
   function get_bar()
    {
        SearchForm.submit();
    }
	var bars_list = [[|bar_lists|]];
	getBarShift([[|bar_id|]]);
	<!--IF:cond([[=view_all=]]!=1)-->
		jQuery('#from_year').attr('disabled',true);
		jQuery('#from_month').attr('disabled',true);
		jQuery('#to_month').attr('disabled',true);
		jQuery('#from_day').attr('disabled',true);
		jQuery('#to_day').attr('disabled',true);
	<!--/IF:cond-->
//	$('hotel_id').value = '<?php //echo PORTAL_ID;?>';
	function getBarShift(id){
		for(var i in bars_list){
			if(bars_list[i]['id'] == id){
				jQuery('#shift_id').html('<option value="0">-------</option>');
				for(var j in bars_list[i]['shifts']){
					var shifts = bars_list[i]['shifts'][j];
					jQuery('#shift_id').append('<option value="'+shifts['id']+'">'+shifts['name']+': '+shifts['brief_start_time']+'h - '+shifts['brief_end_time']+'h </option>');
				}
			}
		}
	}
    jQuery('#start_time').mask("99:99");
    jQuery('#end_time').mask("99:99");
  
    function changevalue()
    {
        var myfromdate = $('date_from').value.split("/");
        var mytodate = $('date_to').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#date_to").val(jQuery("#date_from").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('date_from').value.split("/");
        var mytodate = $('date_to').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#date_from").val(jQuery("#date_to").val());
        }
    }
    
	jQuery("#date_from").datepicker();
    jQuery("#date_to").datepicker();
    //start:KID 
    function test_checked()
    {
        var check  = false;
        jQuery(".check_box").each(function (){

            if(this.checked)
                check = true;
        });
        return check;
    }
    
    function check_bar()
    {
        //start:KID them doan nay vao check valid time
        var hour_from = (jQuery("#start_time").val().split(':'));
        var hour_to = (jQuery("#end_time").val().split(':'));
        var date_from_arr = jQuery("#date_from").val();
        var date_to_arr = jQuery("#date_to").val();
        //end:KID them doan nay vao check valid time
        var validate = test_checked();
        if( validate)
        {
            //start:KID them doan nay vao check valid time
            if((date_from_arr == date_to_arr) && (to_numeric(hour_from[0]) > to_numeric(hour_to[0])))
            {
                alert('[[.start_time_longer_than_end_time_try_again.]]');
                return false;
            }
            else
            {
                if(to_numeric(hour_from[0]) >23 || to_numeric(hour_to[0]) >23 || to_numeric(hour_from[1]) >59 || to_numeric(hour_to[1]) >59)
                {
                    alert('[[.the_max_time_is_2359_try_again.]]');
                    return false;
                }
                else
                {  
                    return true;
                }
            }
            //end:KID them doan nay vao check valid time
        }
        else
        {
            alert('[[.you_must_choose_bar.]]');
            return false;

        }
    }
    //end:KID
    // 7211
    var users = <?php echo String::array2js([[=users=]]);?>;
    for (i in users){
       if(users[i]['is_active']!=1){
          jQuery('option[value='+users[i]['id']+']').remove();  
       }
    }
    jQuery('#user_status').change(function(){
        if(jQuery('#user_status').val()!=1){
            for (i in users){
               if(users[i]['is_active']!=1){
                  jQuery('#user_id').append('<option value='+users[i]['id']+'>'+users[i]['id']+'</option>');  
               }
            }
        }else{
            for (i in users){
               if(users[i]['is_active']!=1){
                  jQuery('option[value='+users[i]['id']+']').remove();  
               }
            }
        }
    });
    //7211 end 
</script>
