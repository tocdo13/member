<script src="packages/core/includes/js/jquery/datepicker.js"></script>
<link rel="stylesheet" href="packages/hotel/packages/report/includes/css/report.css"/>
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
			<strong>[[.template_code.]]</strong><br /></td>
		</tr>	
	</table>
	<table cellpadding="0" cellspacing="0" align="center" width="100%">
	<tr><td width="80px">&nbsp;</td>
	<td align="center">
		<p>&nbsp;</p>
		<h2 class="report-title-new">[[.deposit_report.]]</h2>
		<br/><br/>
		<form name="SearchForm" method="post">
		<table><tr><td>
		<fieldset><legend></legend>
		  <table border="0" class="tablespace">
            <tr>
                <td align="left" width="100px">[[.from_day.]]:</td>
                <td>
                <input name="date_from" type="text" id="date_from" onchange="changevalue();" class="input-long-text" />
                </td>
                <td><label>[[.b_start.]]</label></td>
                <td><input name="hour_from" type="text" id="hour_from"   class="input-short-text"  /></td>
            </tr>
            <tr>
                <td align="left" >[[.to_day.]]:</td>
                <td><input name="date_to" type="text" id="date_to" onchange="changefromday();" class="input-long-text"/>    
                </td>
                <td><label >[[.to.]]</label></td>
                <td><input name="hour_to" type="text" id="hour_to" class="input-short-text"/></td>
            </tr>
            
            <tr>
			  <td  align="left">[[.hotel.]]</td>
			  <td><select name="portal_id" id="portal_id" class="input-long-text">
			    </select></td>
		    </tr>
            <tr>
			  <td  align="left" nowrap="nowrap">[[.user_status.]]</td>
			  <td>
                  <!-- 7211 -->  
                  <select style="" name="user_status" id="user_status" class="brc2" style="width: 150px !important;">
                    <option value="1">Active</option>
                    <option value="0">All</option>
                  </select>
                  <!-- 7211 end--> 
              </td>
		    </tr>
			<tr>
			  <td  align="left" nowrap="nowrap">[[.by_user.]]</td>
			  <td><select name="user_id" id="user_id" class="input-long-text"></select></td>
			  </tr>
		  </table>
		  </fieldset>
		  </td>
          </tr></table>
			<table>
			
			</table>
			
			<p>
				<input type="submit" name="do_search" value="[[.report.]]"  onclick="return check_search();"/>
				<input type="button" value="  [[.cancel.]]  " onclick="location='<?php echo Url::build('home');?>';"/>
			</p>
			</form>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
		</td></tr>
        </table>
	</div>
	</div>
</td>
</tr>
</table>

<script>
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
	jQuery('#hour_from').mask("99:99");
    jQuery('#hour_to').mask("99:99");
    $('hour_from').value='<?php if(Url::get('hour_from')){echo Url::get('hour_from');}else{ echo ('00:00');}?>';
    $('hour_to').value='<?php if(Url::get('hour_to')){echo Url::get('hour_to');}else{ echo (date('H').':'.date('i'));}?>';
	
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
    function check_search()
    {
        var hour_from = (jQuery("#hour_from").val().split(':'));
        var hour_to = (jQuery("#hour_to").val().split(':'));

            if(to_numeric(hour_from[0]) >23 || to_numeric(hour_to[0]) >23 || to_numeric(hour_from[1]) >59 || to_numeric(hour_to[1]) >59)
            {
                alert('[[.hour_invalid.]]');
                return false;
            }
    }
    jQuery("#date_from").datepicker();
    jQuery("#date_to").datepicker();
 

</script>