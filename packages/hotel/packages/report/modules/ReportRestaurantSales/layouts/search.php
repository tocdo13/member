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
		<font class="report_title">[[.report_restaurant_sales.]]</font>
		<br>
		<form name="SearchForm" id="SearchForm" method="post">
		<div style="margin-top:10px;"><label for="hotel_id">[[.Hotel.]]:</label> <select name="hotel_id" id="hotel_id" onchange="get_bar(this);"></select></div>
        <table><tr><td>
		<fieldset><legend>[[.time_select.]]</legend>
		<table border="0">
            <tr>
                <td style="text-align: left;">[[.from_date.]]</td>
                <td><input name="from_date" type="text" id="from_date" /></td>
            </tr>
            <tr>
                <td style="text-align: left;">[[.to_date.]]</td>
                <td><input name="to_date" type="text" id="to_date" /></td>
            </tr>
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
   	        <tr>
              <td align="left">[[.customer_code.]]</td>
			  <td><input name="code" type="text" id="code" style="width:150px;" onfocus="customerAutocomplete();" autocomplete="off" /></td>
	       </tr>
		  </table>
		  </fieldset>
		  </td></tr></table>
			<table>
			<tr>
				<td align="right">[[.line_per_page.]]</td>
				<td align="right">&nbsp;</td>
				<td align="left"><input name="line_per_page" type="text" id="line_per_page" value="15" size="4" maxlength="3" style="text-align:right"/></td>
			</tr>
			<tr>
				<td align="right">[[.no_of_page.]]</td>
				<td align="right">&nbsp;</td>
				<td align="left"><input  name="no_of_page" type="text" id="no_of_page" value="50" size="4" maxlength="2" style="text-align:right"/></td>
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
<script>
jQuery("#from_date").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>,1 - 1, 1)});
jQuery("#to_date").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1)});
jQuery("#checked_all").click(function (){
       // console.log("!!");
        var check  = this.checked;
        jQuery(".check_box").each(function(){
            this.checked = check;
        });
    });
 function get_bar(object)
    {
        document.getElementById('SearchForm').submit();
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
            alert('[[.you_must_choose_bar.]]');
            return false;

        }
    }
function customerAutocomplete()
    {
    	jQuery("#code").autocomplete({
             url: 'get_customer_search.php?customer=1',
        onItemSelect: function(item) {
    		}
        }) 
    }  
</script>