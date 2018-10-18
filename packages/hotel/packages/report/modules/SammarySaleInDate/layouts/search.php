<script src="packages/core/includes/js/jquery/datepicker.js"></script>
<style>
#content_search{
    background: #fff;
}
#content_search:hover{
    background: #f6f6f6;
}
</style>
<style>
/*full m�n h?nh
.simple-layout-middle{width:100%;}*/
</style>
<div id="header_search" style="margin: 5px auto; width: 500px;">
    <table>
        <tr>
            <td><div style="border-radius: 50%; width: 150px; height: 150px; overflow: hidden; background: #eee; margin: 10px; border:2px solid #eee; box-shadow: 0px 0px 5px #000;"><img src="<?php echo HOTEL_LOGO; ?>" alt="logo" style="width: 150px; height: auto;" /></div></td>
            <td>
                <h3 style="text-transform: uppercase;"><?php echo HOTEL_NAME; ?></h3>
                <p><b>ADD:</b><i> <?php echo HOTEL_ADDRESS; ?></i><br />
                <b>TEL:</b> <i></i><?php echo HOTEL_PHONE; ?></i> | <b>FAX:</b> <i><?php echo HOTEL_FAX; ?></i><br />
                <b>EMAIL:</b> <i><?php echo HOTEL_EMAIL; ?></i><br />
                 <b>WEBSITE:</b> <i><?php echo HOTEL_WEBSITE; ?></i></p>
            </td>
        </tr>
    </table>
</div>
<div id="content_search" style="margin: 5px auto; width: 500px; border: 1px solid #999; border-radius:10px;">
    <h1 style="text-transform: uppercase; width: 100%; text-align: center; margin: 10px auto;">[[.sammary_sale_in_date.]]</h1>
    <form name="SearchForm" method="post">
    <fieldset style="width: 300px; margin: 10px auto; background: #fff;">
        <legend>STEP 1</legend>
        <table>
            <?php if(User::can_admin(false,ANY_CATEGORY)) {?>
            <tr>
                <td> [[.hotel.]] </td>
                <td><select name="portal_id" id="portal_id" style="width: 200px;"></select></td>
            </tr>
            <?php }?>
            <!-- code sale
            <tr>
                <td width="1%" nowrap="nowrap"><p>[[.sale_code.]]</p></td>
                <td nowrap="nowrap"><p><select name="sale_code" id="sale_code" style="width: 100px;"></select></p></td>
            </tr>
            -->
        </table>
    </fieldset>
    <fieldset style="width: 200px; margin: 10px auto; background: #fff;">
        <legend>STEP 2</legend>
        <table>
            <tr>
                <td align="right">[[.date_from.]]</td>
                <td><input name="date_from" type="text" id="date_from" tabindex="1" onchange="check_date();" /></td>
            </tr>
            <tr>
                <td align="right">[[.date_to.]]</td>
                <td><input name="date_to" type="text" id="date_to" tabindex="2" onchange="check_date();"/></td>
            </tr>
        </table>
    </fieldset>
    <fieldset style="width: 150px; margin: 10px auto; background: #fff;">
        <legend>STEP 3</legend>
        <table>
            <tr>
                <td align="right">[[.line_per_page.]]</td>
                <td><input name="line_per_page" type="text" id="line_per_page" tabindex="1" style="width: 50px;" onchange="check_page();" /></td>
            </tr>
            <tr>
                <td align="right">[[.no_of_page.]]</td>
                <td><input name="total_page" type="text" id="total_page" tabindex="2" style="width: 50px;" onchange="check_page();" /></td>
            </tr>
            <tr>
                <td align="right">[[.from_page.]]</td>
                <td><input name="start_page" type="text" id="start_page" tabindex="2" style="width: 50px;" onchange="check_page();" /></td>
            </tr>
        </table>
    </fieldset>
    <div style="width: 265px; margin: 0px auto;">
        <input type="submit" name="do_search" value="  [[.report.]]  " style="width: 120px; height: 40px; line-height: 40px; text-align: center; background: #0161ba; border: none; border-radius:5px; box-shadow:0px 0px 3px #999; margin: 5px; font-size: 13px; font-weight: bold; text-transform: uppercase; cursor: pointer; color: #fff;"/>
        <input type="button" value="  [[.cancel.]]  " onclick="location='<?php echo Url::build('home');?>';" style="width: 120px; height: 40px; line-height: 40px; text-align: center; background: #85c4ff; border: none; border-radius:5px; box-shadow:0px 0px 3px #999; margin: 5px; font-size: 13px; font-weight: bold; text-transform: uppercase; cursor: pointer; color: #fff;"/>
    </div>
    </form>
</div>
<br />
<br />
<br />
<br />
<br />
<br />
<br />

<script type="text/javascript">
	jQuery("#date_from").datepicker();
	jQuery("#date_to").datepicker();
	$('portal_id').value = '<?php echo PORTAL_ID;?>';
</script>
<script>
/*
function check_date(){
        var to_month = jQuery('#to_month').val();
        console.log(to_month);
        var to_day = jQuery('#to_day').val();
        console.log(to_day);
        var from_date = from_day+'/'+from_month+'/'+from_year;
        var to_date = to_day+'/'+to_month+'/'+to_year;
        var startDate = new Date(from_year,from_month,from_day);
        var endDate = new Date(to_year,to_month,to_day);
        console.log(from_date);
        console.log(to_date);
        if (startDate > endDate){
            alert("ng�y b?t �?u ph?i nh? h�n ng�y �?n");
            jQuery('#from_month').css('background','red');
            jQuery('#from_year').css('background','red');
            jQuery('#from_day').css('background','red');
        }else{
            jQuery('#from_month').css('background','#ffffff');
            jQuery('#from_year').css('background','#ffffff');
            jQuery('#from_day').css('background','#ffffff');
        }

        /*if(from_year > to_year){
            alert("N�m b?t �?u ph?i nh? h�n n�m �?n");
            jQuery('#from_year').val("");
            jQuery('#from_year').css('background','red');
        }else{
            if(from_month)
        }*/
</script>