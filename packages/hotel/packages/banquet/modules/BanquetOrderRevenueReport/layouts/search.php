<script src="packages/core/includes/js/jquery/datepicker.js"></script>
<link rel="stylesheet" href="packages/hotel/packages/report/includes/css/report.css"/>
<table width="100%" height="100%" bgcolor="#B5AEB5">
<tr><td>
	<link rel="stylesheet" href="skins/default/report.css">
	<div style="width:99%;height:100%;text-align:center;vertical-align:middle;border:4px double;background-color:white;">
	<div style="padding:10 40 10 80;background-image:url(skins/default/images/back_of_book.jpg);background-repeat:repeat-y;background-position:left;">
	<p>&nbsp;</p>
	<table cellpadding="0" cellspacing="0" align="center" width="100%">
	<tr><td width="80px">&nbsp;</td>
	<td align="center">
		<p>&nbsp;</p>
        <h2 class="report-title-new">[[.banquet_order_revenue_report.]]</h2>
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
                <td colspan="2"><input name="date_from" type="text" id="date_from" onchange="changevalue();" class="input-long-text" /></td>
            </tr>
            <tr>
                <td align="right">[[.to_day.]]:</td>
                <td colspan="2"><input name="date_to" type="text" id="date_to" onchange="changefromday();" class="input-long-text" /></td>
            </tr>
<!--OANH COMMENT
			<tr>
			  <td nowrap="nowrap">[[.by_day.]]</td>
			  <td>
			  	<select name="from_day" id="from_day" onchange="check_date();"></select>
			  	[[.to.]]
			  	<select name="to_day" id="to_day" onchange="check_date();"></select>
			  <script> $('to_day').value='<?php echo date('d');?>';</script>			  </td>
			</tr>
			<tr>
              <td align="left">[[.full_name.]]</td>
			  <td><input name="customer_name" type="text" id="customer_name" style="width:180px;" /></td>
			  </tr>
			<tr>
-->
              <td align="left">[[.revenue.]]</td>
			  <td colspan="2"><select  name="revenue" id="revenue" class="input-long-text" onChange="if(this.value=='min'){jQuery('.check_box').attr('checked',true);jQuery('#checked_all').attr('checked',true);}" >
              	<option value="all">------All------</option>
                <option value="min">-----Dưới 200,000-----</option>
                <option value="max">-----Từ 200,000-----</option>
              </select></td>
			  </tr>
              <!--oahh comment
             <tr>
             		<td align="right"> <input name="checked_all" type="checkbox" id="checked_all" onClick="jQuery('.check_box').attr('checked',jQuery(this).attr('checked'));" ></td> 
                    <td align="left"><b><label for="checked_all">[[.select_all_banquet.]]</label></b></td>
             </tr>
             -->
             
             <!-- oanh edit chechbox -->
             <tr>
                  <td></td>  
                  <td align="right"> <input name="checked_all" type="checkbox" id="checked_all" /></td> 
                  <td align="left"><b><label for="checked_all">[[.select_all_banquet.]]</label></b></td>
             </tr>
             <!-- end oanh -->
             
             <!--LIST:bars-->
             <tr>
                <td></td>
             	<!--IF:cond(Session::is_set('bar_id') and Session::get('bar_id')==[[=bars.id=]])-->
                	<td align="right"> <input name="bar_id_[[|bars.id|]]" type="checkbox" value="[[|bars.id|]]" id="bar_id_[[|bars.id|]]" class="check_box" checked="checked"  /></td>
                <!--ELSE-->
                  <td align="right"><input name="bar_id_[[|bars.id|]]" type="checkbox" value="[[|bars.id|]]" id="bar_id_[[|bars.id|]]" class="check_box" /></td>
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
				<input type="submit" name="do_search" value="  [[.report.]]  " id="do_search" onclick=" return check_bar();"/>
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
	var bars_list = [[|bar_lists|]];
	getBarShift([[|bar_id|]]);
	
	$('hotel_id').value = '<?php echo PORTAL_ID;?>';
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
    
    function test_checked()
    {
        var check  = false;
        jQuery(".check_box").each(function (){

            if(this.checked)
                check = true;
    	});
        return check;
    }
    //oanh add
    jQuery("#checked_all").click(function (){
       // console.log("!!");
        var check  = this.checked;
        jQuery(".check_box").each(function(){
            this.checked = check;
        });
    });
    //end oanh
    
    function check_bar()
    {
        var validate = test_checked();
        if( validate)
        {
            return true;

        }
        else
        {
            alert('[[.you_must_choose_bar.]]');
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