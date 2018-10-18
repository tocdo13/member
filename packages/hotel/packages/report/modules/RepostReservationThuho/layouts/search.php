<script src="packages/core/includes/js/jquery/datepicker.js"></script>
<div id="header_search" style="margin: 5px auto; width: 900px;">
    <table style="width: 100%;">
        <tr>
            <td style="text-align: left; width: 150px;"><div style="border-radius: 50%; width: 150px; height: 150px; overflow: hidden; background: #eee; margin: 10px; border:2px solid #eee; box-shadow: 0px 0px 5px #000;"><img src="<?php echo HOTEL_LOGO; ?>" alt="logo" style="width: 150px; height: auto;" /></div></td>
            <td style="text-align: left;">
                <h3 style="text-transform: uppercase;"><?php echo HOTEL_NAME; ?></h3>
                <p><b>ADD:</b><i> <?php echo HOTEL_ADDRESS; ?></i><br />
                <b>TEL:</b> <i></i><?php echo HOTEL_PHONE; ?></i> | <b>FAX:</b> <i><?php echo HOTEL_FAX; ?></i><br />
                <b>EMAIL:</b> <i><?php echo HOTEL_EMAIL; ?></i><br />
                 <b>WEBSITE:</b> <i><?php echo HOTEL_WEBSITE; ?></i></p>
            </td>
            <td style="text-align: right;">
                <strong>[[.template_code.]]</strong><br />
                <i>[[.promulgation.]]</i>
            </td>
        </tr>
    </table>
</div>
<div id="content_search" style="width: 100%;">
    <h1 style="text-transform: uppercase; width: 100%; text-align: center; margin: 10px auto;">[[.restaurant_revenue_transfer_to_room.]]</h1>
    <div id="search" style="width: 500px; margin: 0px auto; border: 1px solid #171717; border-radius: 10px;">
        <form name="SearchForm" id="SearchForm" method="post">
            <fieldset style="width: 350px; margin: 5px auto;">
                <legend>Step 1</legend>
                <table border="0" align="center" id="select_time">
                    <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
                    <tr>
                        <td>[[.hotel.]]:</td>
                        <td><select name="portal_id" id="portal_id" style="width: 250px;" onchange="get_bar(this);"></select></td>
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
                    <?php }?>
                </table>
            </fieldset>
            <fieldset style="width: 300px; margin: 0px auto;">
                <legend>Step 2</legend>
                <table style="text-align: center;">
                    <tr>
                        <td align="right">[[.date_from.]]</td>
                        <td><input name="date_from" type="text" id="date_from"  tabindex="1" onchange="changevalue()" /></td>
                    </tr>
                    
                    <tr>
                        <td align="right">[[.date_to.]]</td>
                        <td><input name="date_to" type="text" id="date_to" tabindex="2" onchange="changefromday()"  /></td>
                    </tr>
                    
                </table>
            </fieldset>
            <fieldset style="width: 150px; margin: 5px auto;">
                <legend>Step 3</legend>
                <table>
                	<tr>
                		<td align="right">[[.line_per_page.]]</td>
                		<td><input name="line_per_page" type="text" id="line_per_page" value="99" size="4" maxlength="3" style="text-align:right"/></td>
                	</tr>
                	<tr>
                		<td align="right">[[.no_of_page.]]</td>
                		<td><input  name="no_of_page" type="text" id="no_of_page" value="50" size="4" maxlength="2" style="text-align:right"/></td>
                	</tr>
                	<tr>
                		<td align="right">[[.from_page.]]</td>
                		<td><input name="start_page" type="text" id="start_page" value="1" size="4" maxlength="4" style="text-align:right"/></td>
                	</tr>
            	</table>
            </fieldset>
            <div style="width: 265px; margin: 0px auto;">
                <input type="submit" name="do_search" value="  [[.report.]]  " onclick="return check_bar();" style="width: 120px; height: 40px; line-height: 40px; text-align: center; background: #0161ba; border: none; border-radius:5px; box-shadow:0px 0px 3px #999; margin: 5px; font-size: 13px; font-weight: bold; text-transform: uppercase; cursor: pointer; color: #fff;"/>
                <input type="button" value="  [[.cancel.]]  " onclick="location='<?php echo Url::build('home');?>';" style="width: 120px; height: 40px; line-height: 40px; text-align: center; background: #85c4ff; border: none; border-radius:5px; box-shadow:0px 0px 3px #999; margin: 5px; font-size: 13px; font-weight: bold; text-transform: uppercase; cursor: pointer; color: #fff;"/>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
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
    function test_checked()
    {
        var check  = false;
        jQuery(".check_box").each(function (){

            if(this.checked)
                check = true;
    	});
        return check;
    } 
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
	jQuery("#date_from").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>,1 - 1, 1)});
    jQuery("#date_to").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1)});
    
    $('date_from').value='<?php if(Url::get('date_from')){echo Url::get('date_from');}else{ echo ('1/'.date('m').'/'.date('Y'));}?>';
    $('date_to').value='<?php if(Url::get('date_to')){echo Url::get('date_to');}else{ echo (date('t').'/'.date('m').'/'.date('Y'));}?>';
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
</script>