<script src="packages/core/includes/js/jquery/datepicker.js"></script>
<style>
#content_search{
    background: #fff;
}
#content_search:hover{
    background: #f6f6f6;
}
</style>
<div id="content_search" style="margin: 100px auto; width: 500px; border: 1px solid #999; border-radius:10px;">
    <h1 style="text-transform: uppercase; width: 100%; text-align: center; margin: 10px auto;">[[.traveller_report.]]</h1>
    <form name="SearchForm" method="post">
    <fieldset style="width: 300px; margin: 10px auto; background: #fff;">
        <legend>STEP 1</legend>
        <table>
            <!--Start Luu Nguyen Giap add portal -->
            <?php //if(User::can_admin(false,ANY_CATEGORY)) {?>
            <tr>
                <td> [[.hotel.]] </td>
                <td><select name="portal_id" id="portal_id" style="width: 200px;"></select></td>
            </tr>
            <?php //}?>
            <!--End Luu Nguyen Giap add portal -->
            <tr>
                <td> [[.nationality.]] </td>
                <td><select name="country_id" id="country_id" style="width: 200px;"></select></td>
            </tr>
        </table>
    </fieldset>
    <fieldset style="width: 200px; margin: 10px auto; background: #fff;">
        <legend>STEP 2</legend>
        <table>
            <tr>
                <td align="right">[[.date_from.]]</td>
                <td><input name="date_from" type="text" id="date_from" tabindex="1" onchange="changevalue()" /></td>
                <script>
                    $('date_from').value='<?php if(Url::get('date_from')){echo Url::get('date_from');}else{ echo ('1/'.date('m').'/'.date('Y'));}?>';
                    function changevalue(){
                        var myfromdate = $('date_from').value.split("/");
                        var mytodate = $('date_to').value.split("/");
                        if(myfromdate[2] > mytodate[2]){
                            $('date_to').value =$('date_from').value;
                        }else{
                            if(myfromdate[1] > mytodate[1]){
                                $('date_to').value =$('date_from').value;
                            }else{
                                if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1])){
                                    $('date_to').value =$('date_from').value;
                                }
                            }
                        }
                    }
                </script>
            </tr>
            <tr>
                <td align="right">[[.date_to.]]</td>
                <td><input name="date_to" type="text" id="date_to" tabindex="2" onchange="changefromday()" /></td>
                <script>
                    $('date_to').value='<?php if(Url::get('date_to')){echo Url::get('date_to');}else{ echo (date('t').'/'.date('m').'/'.date('Y'));}?>';
                    function changefromday(){
                        var myfromdate = $('date_from').value.split("/");
                        var mytodate = $('date_to').value.split("/");
                        if(myfromdate[2] > mytodate[2]){
                            $('date_from').value= $('date_to').value;
                        }else{
                            if(myfromdate[1] > mytodate[1]){
                                $('date_from').value = $('date_to').value;
                            }else{
                                if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1])){
                                    $('date_from').value =$('date_to').value;
                                }
                            }
                        }
                    }
                </script>
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
        <input type="submit" name="do_search" value="  [[.report.]]  " style="width: 120px; height: 40px; line-height: 40px; text-align: center; background: #fff; border: none; border-radius:5px; box-shadow:0px 0px 3px #999; margin: 5px; font-size: 13px; font-weight: bold; text-transform: uppercase; cursor: pointer; color: #171717;"/>
        <input type="button" value="  [[.cancel.]]  " onclick="location='<?php echo Url::build('home');?>';" style="width: 120px; height: 40px; line-height: 40px; text-align: center; background: #fff; border: none; border-radius:5px; box-shadow:0px 0px 3px #999; margin: 5px; font-size: 13px; font-weight: bold; text-transform: uppercase; cursor: pointer; color: #171717;"/>
    </div>
    </form>
</div>
<div style="width: 100%; text-align: center; font-size: 25px; line-height: 30px; margin: 5px auto; text-transform: uppercase;">
<?php if($_REQUEST['no_record']==true){ ?>
    [[.no_record.]]
<?php } ?>
</div>
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<script type="text/javascript">
	jQuery("#date_from").datepicker();//({ minDate: new Date(<?php //echo BEGINNING_YEAR;?>,1 - 1, 1)});
    jQuery("#date_to").datepicker();//({ minDate: new Date(<?php //echo BEGINNING_YEAR;?>, 1 - 1, 1)});
	//$('portal_id').value = '<?php //echo PORTAL_ID;?>';
</script>