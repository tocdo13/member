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
		<font class="report_title">[[.receipt_by_employee_report.]]</font>
		<br>
		<form name="SearchForm" id="SearchForm" method="post">
   		<?php if(User::can_admin(false,ANY_CATEGORY)){?>
        <div style="margin-top:10px;"><label for="hotel_id">[[.Hotel.]]:</label> <select name="hotel_id" id="hotel_id" onchange="get_bar(this);"></select></div>
        <table style="margin: 0 auto;">
            <tr>
             		<td align="right"> <input name="checked_all" type="checkbox" id="checked_all" /></td> 
                    <td align="left"><b><label for="checked_all">[[.select_all_bar.]]</label></b></td>
             </tr>
             <!--LIST:bars-->
             <tr >
             	<!--IF:cond(Session::is_set('bar_id') and Session::get('bar_id')==[[=bars.id=]])-->
                	<td align="right"> <input name="bar_id_[[|bars.id|]]" type="checkbox" value="[[|bars.id|]]" id="bar_id_[[|bars.id|]]" class="check_box" checked="checked"  /></td>
                <!--ELSE-->
                  <td align="right"><input  name="bar_id_[[|bars.id|]]"  type="checkbox" value="[[|bars.id|]]" id="bar_id_[[|bars.id|]]" <?php if(isset($_REQUEST['bar_id_'.[[=bars.id=]]])) echo 'checked="checked"' ?> class="check_box"  /></td>
                <!--/IF:cond-->
                  <td><label for="bar_id_[[|bars.id|]]">[[|bars.name|]]</label></td>
    		 </tr>
             <!--/LIST:bars-->
		  </table>
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
            
	    
	    
	    
            <!--
            <tr>
                <td align="left">[[.bar_shift.]]</td>
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
            <tr>
			  <td align="left">[[.user.]]</td>
			  <td><select name="user_id" id="user_id" style="width:180px;"></select></td>
			  </tr>
			<tr>
			  <td colspan="2" nowrap="nowrap">&nbsp;</td>
			  </tr>
		  </table>
		  </fieldset>
		  </td></tr></table>
			
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
    jQuery("#checked_all").click(function ()
    {
        var check  = this.checked;
        jQuery(".check_box").each(function()
        {
            this.checked = check;
        });
    });
	var bars_list = [[|bar_lists|]];
	getBarShift([[|bar_id|]]);
	<!--IF:cond([[=view_all=]]!=1)-->
		jQuery('#from_year').attr('disabled',true);
		jQuery('#from_month').attr('disabled',true);
		jQuery('#to_month').attr('disabled',true);
		jQuery('#from_day').attr('disabled',true);
		jQuery('#to_day').attr('disabled',true);
	<!--/IF:cond-->
	//$('hotel_id').value = '<?php echo PORTAL_ID;?>';
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
    
    function validate_time(obj,value)
    {
        if(value != "__:__")
        {
            var arr = value.split(":")
            var h = arr[0];
            var m = arr[1];
            if(is_numeric(h.toString()))
            {
                if(h>23)
                {
                    alert('[[.invalid_time.]]');
                    jQuery(obj).val('');
                    return false;    
                }
            }
            if(is_numeric(m.toString()))
            {
                if(m>59)
                {
                    alert('[[.invalid_time.]]');
                    jQuery(obj).val('');
                    return false;    
                }
            }  
        }
    }
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
    //luu nguyen giap add search bars with portal_id
    function get_bar(object)
    {
        document.getElementById('SearchForm').submit();
    }
    //end giap
</script>