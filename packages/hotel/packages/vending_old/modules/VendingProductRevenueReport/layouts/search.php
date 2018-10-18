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
			<strong>[[.template_code.]]</strong><br />
			<i>[[.promulgation.]]</i>
            
			</td>
		</tr>	
	</table>
	<table cellpadding="0" cellspacing="0" align="center" width="100%">
	<tr><td width="80px">&nbsp;</td>
	<td align="center">
		<p>&nbsp;</p>
		<font class="report_title">[[.vending_product_revenue_report.]]</font>
		<br>
		<form name="SearchForm" method="post">
		<table>
        <tr>
        <td>
            <fieldset>
		      <table border="0" align="center" id="select_time">
			 <tr>
			  <td>[[.hotel.]]</td>
			  <td><select name="portal_id" id="portal_id" onchange="get_area(this);">
			    </select></td>
			  <td nowrap="nowrap">&nbsp;</td>
			  </tr>
              <tr>
             		<td align="right"> <input name="checked_all" type="checkbox" id="checked_all" /></td> 
                    <td align="left"><b><label for="checked_all">[[.select_all_area.]]</label></b></td>
             </tr>
              <!--LIST:area-->
             <tr>
            	<!--IF:cond(Session::is_set('area_id') and Session::get('area_id')==[[=area.id=]])-->
            	<td align="right"> <input name="area_id_[[|area.id|]]" type="checkbox" value="[[|area.id|]]" id="area_id_[[|area.id|]]" class="check_box" checked="checked"  /></td>
                <!--ELSE-->
                <td align="right"><input name="area_id_[[|area.id|]]" type="checkbox" value="[[|area.id|]]" id="area_id_[[|area.id|]]" class="check_box" /></td>
                <!--/IF:cond-->
                <td><label for="area_id_[[|area.id|]]">[[|area.name|]]</label></td>
			 </tr>
             <!--/LIST:area-->
			</table>
			</fieldset>
        </td>
        </tr>
        <tr><td>
		<fieldset><legend>[[.time_select.]]</legend>
		<table border="0">
			<tr>
              <td align="right">[[.date_from.]]</td>
              <td><input name="date_from" type="text" id="date_from" onchange="changevalue();" tabindex="1" /></td>
            </tr>
            <tr>
              <td align="right">[[.date_to.]]</td>
              <td><input name="date_to" type="text" id="date_to" onchange="changefromday();" tabindex="2" /></td>
            </tr>
			<tr>
			  <td colspan="2" nowrap="nowrap"><table border="0" cellspacing="0" cellpadding="0">
                <tr style="display:none;">
                  <td align="right">[[.company.]]:&nbsp;</td>
                  <td><input name="customer_name" type="text" id="customer_name" style="width:180px;" /></td>
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
				<td align="left"><input name="line_per_page" type="text" id="line_per_page" value="999" size="4" maxlength="4" style="text-align:right"/></td>
			</tr>
			<tr>
				<td align="right">[[.no_of_page.]]</td>
				<td align="right">&nbsp;</td>
				<td align="left"><input  name="no_of_page" type="text" id="no_of_page" value="50" size="4" maxlength="4" style="text-align:right"/></td>
			</tr>
			<tr>
				<td align="right">[[.from_page.]]</td>
				<td align="right">&nbsp;</td>
				<td align="left"><input name="start_page" type="text" id="start_page" value="1" size="4" maxlength="4" style="text-align:right"/></td>
			</tr>
			</table>
			
			<p>
				<input type="submit" name="do_search" value="  [[.report.]]  " onclick=" return check_bar();"/>
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
<script type="text/javascript">
	function get_area(object)
    {
        console.log('aaa');
        //document.getElementById('SearchForm').submit();\
        SearchForm.submit();
    } 
    jQuery("#date_from").datepicker();
	jQuery("#date_to").datepicker();
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
            alert('[[.you_must_choose_area.]]');
            return false;

        }
    } 
jQuery("#checked_all").click(function (){
       // console.log("!!");
        var check  = this.checked;
        jQuery(".check_box").each(function(){
            this.checked = check;
        });
    }); 
      
</script>
