<style>
    .simple-layout-middle {width:100%;}
    .simple-layout-content {
         /*background: #22272b;*/
         background: #F2F2F2;
         padding: 0px; 
         min-height: calc( 100% - 100px )!important;
         margin: 0px;
         border: none;
    }
    .simple-layout-bound{
        background: none!important;
        background: #F2F2F2!important;
    }
    body{
        background: #F2F2F2!important;
    }
    .over_hidden{
        overflow: hidden!important;
        position: fixed;
        width: 100%;
        height: 100%;
    }
    #ui-datepicker-div{
        z-index: 999999;
    }
    .position_fixed {
        position: fixed; 
        top: 0px; 
        left: 0px; 
        
    }
    /*.NgClick:hover{
        outline: 3px solid #2494D1;
    }
    */
</style>

<form name="GolfPriceManagerEditForm" method="POST" enctype="multipart/form-data">
<input name="act" id="act" type="hidden" />
<div id="InventoryHeader" style="width: 100%; height: auto;background: #F2F2F2; z-index: 99;">
    <div class="w3-container">
        <div class="w3-row" style="min-width: 1200px!important; max-width: 1420px; margin: 0px auto;">
            <div class="w3-row">
                <div class="w3-button w3-margin w3-left" style="font-weight: bold; text-transform: uppercase; background: none!important;">
                    <h3><img src="packages/hotel/packages/golf/includes/img/golf.png" style="height: 40px; width: auto;" /> [[.golf_rate.]]</h3>
                </div>
                
                <div class="w3-button w3-margin w3-right w3-text-grey" onclick="location.href='?page=golf_price_manager'" style="font-weight: bold; text-transform: uppercase; background: none!important;">
                    <i class="fa fa-fw fa-mail-reply"></i> [[.back.]]
                </div>
                <div class="w3-button w3-blue w3-hover-blue w3-margin w3-right" onclick="CheckSubmit('SAVE');" style="font-weight: normal;">
                    <i class="fa fa-fw fa-save"></i> [[.save.]]
                </div>
            </div>
        </div>
    </div>
</div>
<div id="InventoryContent" style="width: 100%; height: auto;">
    <div class="w3-container" style="margin-bottom: 50px;">
        <div class="w3-row" style="min-width: 1200px!important; max-width: 1420px; margin: 10px auto;">
            <p><b class="w3-text-blue">[[.price.]]</b><br /><span><i>(Không áp dụng với ngày quá khứ)</i></span></p>
            <table id="BulkDateRange" style="" cellspacing="5" cellpadding="5">
                
            </table>
            <div onclick="AddDateRange();" class="w3-button w3-white w3-hover-white w3-margin w3-border w3-text-blue" style="font-weight: normal;">
                [[.add_date_range.]]
            </div>
            <hr />
            <p><b class="w3-text-blue">[[.golf_hole.]] / [[.group_traveller.]]</b> </p>
            <!--LIST:golf_hole-->
            <table cellspacing="5" cellpadding="5" class="w3-left" style="background: rgba(255,255,255,0.7); box-shadow: 0px 1px 3px 1px rgba(60,64,67,.15); border: 5px solid #FFF; border-radius: 5px; margin: 15px;">
                    <tr>
                        <td colspan="2">
                            <input class="w3-check golf_hole" type="checkbox" name="golf_hole[[[|golf_hole.id|]]][id]" id="golf_hole_id_[[|golf_hole.id|]]" lang="[[|golf_hole.id|]]" />
                            <input type="hidden" name="golf_hole[[[|golf_hole.id|]]][name]" value="[[|golf_hole.name|]]" />
                            <label for="golf_hole_id_[[|golf_hole.id|]]" style="font-weight: bold;" class="w3-text-blue">[[.golf_hole.]]: [[|golf_hole.code|]] - [[|golf_hole.name|]]</label>
                        </td>
                    </tr>
                    <!--LIST:group_traveller-->
                    <tr>
                        <td style="width: 80px;"></td>
                        <td>
                            <input class="w3-check hole_item hole_item_[[|golf_hole.id|]]" lang="[[|golf_hole.id|]]" type="checkbox" name="golf_hole[[[|golf_hole.id|]]][group_traveller][[[|group_traveller.id|]]][id]" id="golf_hole_[[|golf_hole.id|]]_group_traveller_[[|group_traveller.id|]]" />
                            <input type="hidden" name="golf_hole[[[|golf_hole.id|]]][group_traveller][[[|group_traveller.id|]]][name]" value="[[|group_traveller.name|]]" />
                            <label for="golf_hole_[[|golf_hole.id|]]_group_traveller_[[|group_traveller.id|]]" style="font-weight: bold;">[[|group_traveller.name|]]</label>
                        </td>
                    </tr>
                    <!--/LIST:group_traveller-->
            </table>
            <!--/LIST:golf_hole-->
        </div>
    </div>
</div>
<div id="LightBoxCentral" style="width: 100%; height: 100%; display: none; overflow-y: auto; position: fixed; top: 0px; left: 0px; background: rgba(0,0,0,0.5); z-index: 999;">
    <div style="width: 100%; height: 100%; position: absolute; top: 0px; left: 0px; z-index: -1;" onclick="CloseLightBox();"></div>
    <div id="LightBoxCentralContent" style="min-width: 320px; max-width: 960px; min-height: 50px; z-index: 99; margin: 100px auto; background: white;">
        
    </div>
</div>
</form>
<table id="BulkDateRangeTemplate" style="display: none;">
    <tr id="DateRangeGroup_X######X">
        <td><input type="text" name="bulkrange[X######X][price]" id="bulk_price_X######X" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val())));" class="w3-input w3-border" style="width: 150px; text-align: right;" placeholder="$" /></td>
        <td><input type="text" name="bulkrange[X######X][from_time]" id="bulk_from_time_X######X" class="w3-input w3-border" style="width: 80px; text-align: center;" placeholder="HH:ii" maxlength="5" /></td>
        <td><input type="text" name="bulkrange[X######X][to_time]" id="bulk_to_time_X######X" class="w3-input w3-border" style="width: 80px; text-align: center;" placeholder="HH:ii" maxlength="5" /></td>
        <td><input type="text" name="bulkrange[X######X][from_date]" id="bulk_from_date_X######X" onchange="if(jQuery('#bulk_to_date_X######X').val()==''){ jQuery('#bulk_to_date_X######X').val(jQuery('#bulk_from_date_X######X').val()); }" class="w3-input w3-border" style="width: 120px; text-align: center;" readonly="" placeholder="DD/MM/YYYY" maxlength="10" /></td>
        <td><input type="text" name="bulkrange[X######X][to_date]" id="bulk_to_date_X######X" onchange="if(jQuery('#bulk_from_date_X######X').val()==''){ jQuery('#bulk_from_date_X######X').val(jQuery('#bulk_to_date_X######X').val()); }" class="w3-input w3-border" style="width: 120px; text-align: center;" readonly="" placeholder="DD/MM/YYYY" maxlength="10" /></td>
        <td><input type="checkbox" name="bulkrange[X######X][MON]" id="bulk_mon_X######X" checked="checked" /> Mon</td>
        <td><input type="checkbox" name="bulkrange[X######X][TUE]" id="bulk_tue_X######X" checked="checked" /> Tue</td>
        <td><input type="checkbox" name="bulkrange[X######X][WED]" id="bulk_wed_X######X" checked="checked" /> Wed</td>
        <td><input type="checkbox" name="bulkrange[X######X][THU]" id="bulk_thu_X######X" checked="checked" /> Thu</td>
        <td><input type="checkbox" name="bulkrange[X######X][FRI]" id="bulk_fri_X######X" checked="checked" /> Fri</td>
        <td><input type="checkbox" name="bulkrange[X######X][SAT]" id="bulk_sat_X######X" checked="checked" /> Sat</td>
        <td><input type="checkbox" name="bulkrange[X######X][SUN]" id="bulk_sun_X######X" checked="checked" /> Sun</td>
        <td><i class="fa fa-remove fa-fw w3-text-pink" onclick="DeleteDateRange(X######X);"></i></td>
    </tr>
</table>

<script>
    //jQuery(this).val(number_format(to_numeric(jQuery(this).val())));
    var CURRENT_YEAR = <?php echo date('Y')?>;
    var CURRENT_MONTH = <?php echo intval(date('m')) - 1;?>;
    var CURRENT_DAY = <?php echo date('d')?>;
    var $input_count_range = 100;
    var windowscrollTop = 0;
    AddDateRange();
    jQuery(document).ready(function(){
        jQuery('.golf_hole').click(function(){
            $key = this.lang;
            if(document.getElementById('golf_hole_id_'+$key).checked==true){
                jQuery(".hole_item_"+$key).attr('checked','checked');
            }else{
                jQuery(".hole_item_"+$key).removeAttr('checked');
            }
        });     
    });
    function AddDateRange(){
        var input_count = $input_count_range++;
        var content = jQuery("#BulkDateRangeTemplate").html().replace(/X######X/g,input_count);
        jQuery("#BulkDateRange").append(content);
        jQuery("#bulk_from_date_"+input_count).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
        jQuery("#bulk_to_date_"+input_count).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
        jQuery("#bulk_from_time_"+input_count).mask('99:99');
        jQuery("#bulk_to_time_"+input_count).mask('99:99');
    }
    function DeleteDateRange(input_count){
        jQuery("#DateRangeGroup_"+input_count).remove();
    }
    function CheckSubmit($act){
        jQuery('#act').val($act);
        GolfPriceManagerEditForm.submit();
    }
    function OpenLightBox(){
        windowscrollTop = jQuery(window).scrollTop();
        jQuery("body").addClass('over_hidden');
        jQuery("#LightBoxCentral").css('display','');
    }
    function CloseLightBox(){
        $input_count_range = 100;
        jQuery("body").removeClass('over_hidden');
        document.getElementById('LightBoxCentralContent').innerHTML = '';
        jQuery("#LightBoxCentral").css('display','none');
        jQuery(window).scrollTop(windowscrollTop);
    }
</script>

