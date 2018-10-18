<script src="packages/core/includes/js/jquery/datepicker.js"></script>
<div class="customer-type-supplier-bound" id="style_list" onclick="break_auto_complate();">
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
		<font class="report_title">[[.restaurant_debit_report.]]</font>
		<br>
		<form name="SearchForm" method="post">
   		<?php if(User::can_admin(false,ANY_CATEGORY)){?>
        <div style="margin-top:10px;"><label for="hotel_id">[[.Hotel.]]:</label> <select name="hotel_id" id="hotel_id" onchange="get_bar(this);"></select></div>
        <div style="margin-top:10px;">
                     <table>
                     <tr>
                     		<td align="right"> <input name="checked_all" type="checkbox" id="checked_all" /></td> 
                            <td align="left"><b><label for="checked_all">[[.select_all_bar.]]</label></b></td>
                     </tr>
                     <!--LIST:bars-->
                     <tr>
                     	<!--IF:cond(Session::is_set('bar_id') and Session::get('bar_id')==[[=bars.id=]])-->
                        	<td align="right"> <input name="bar_id_[[|bars.id|]]" type="checkbox" value="[[|bars.id|]]" id="bar_id_[[|bars.id|]]" class="check_box" checked="checked"  /></td>
                        <!--ELSE-->
                          <td align="right"><input name="bar_id_[[|bars.id|]]" type="checkbox" value="[[|bars.id|]]" id="bar_id_[[|bars.id|]]" class="check_box" /></td>
                        <!--/IF:cond-->
                          <td><label for="bar_id_[[|bars.id|]]">[[|bars.name|]]</label></td>
        			 </tr>
                     
                     <!--/LIST:bars-->
                     </table></div>
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
			  <td colspan="2" nowrap="nowrap"><table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="right">[[.company.]]:&nbsp;</td>
                  <td><input name="customer_name" type="text" id="customer_name" style="width:180px;" onfocus="customerAutocomplete();" autocomplete="off" /></td>
                </tr>

              </table></td>
			  </tr>
		  </table>
		  </fieldset>
		  </td></tr></table>
			<table>
			<tr>
				<td align="right">[[.line_per_page.]]</td>
				<td align="right">&nbsp;</td>
				<td align="left"><input name="line_per_page" type="text" id="line_per_page" value="15" size="4" maxlength="2" style="text-align:right"/></td>
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
				<input type="submit" name="do_search" value="  [[.report.]]  " onclick=" return check_bar();">
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
</div>
<script>
    jQuery("#checked_all").click(function ()
    {
        var check  = this.checked;
        jQuery(".check_box").each(function()
        {
            this.checked = check;
        });
    });
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
    
    function customerAutocomplete()
    {
    	jQuery("#customer_name").autocomplete({
             url: 'get_customer_search.php?customer=1',
        onItemSelect: function(item) {
    			//getCustomerFromCode(jQuery("#customer_id").val());
                //console.log('test');
                //document.ListCustomerForm.submit();
    		}
        }) 
    }
    function break_auto_complate(){
        jQuery(".acResults").css('display','none');
    }
    //start:KID them phan check nha hang
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
    //end:KID them phan check nha hang 
</script>
