<style>
    #no_avata {
        width: 200px;
        height: auto;
        border: 3px solid #FFFFFF;
        box-shadow: 0px 0px 5px #555555;
    }
    #avatar {
        width: 200px;
        height: auto;
        border: 3px solid #FFFFFF;
        box-shadow: 0px 0px 5px #555555;
    }
    .simple-layout-middle {width:100%;}
    .simple-layout-content {
         /*background: #22272b;*/
         background: #f9f9f9;
         padding: 0px; 
         min-height: calc( 100% - 100px )!important;
         margin: 0px;
         border: none;
    }
    .simple-layout-bound{
        background: none!important;
        background: #f9f9f9!important;
    }
    body{
        background: #f9f9f9!important;
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
</style>
<form name="AddGolfCaddieSchedulerForm" method="post" enctype="multipart/form-data">
    <div class="w3-row" style="min-width: 1200px!important; max-width: 1420px; margin: 0px auto;">
        <div class="w3-row">
            <div class="w3-button w3-margin w3-left w3-text-pink" style="font-weight: bold; text-transform: uppercase; background: none!important;">
                <i class="fa fa-fw fa-calendar w3-text-pink"></i> [[.add_caddie_schduler.]]
            </div>
            <div class="w3-button w3-margin w3-right w3-text-grey" onclick="location.href='?page=golf_caddie_scheduler'" style="font-weight: bold; text-transform: uppercase; background: none!important;">
                <i class="fa fa-fw fa-mail-reply"></i> [[.back.]]
            </div>
            <div class="w3-button w3-blue w3-hover-blue w3-margin w3-right" onclick="AddGolfCaddieSchedulerForm.submit();" style="font-weight: normal;">
                <i class="fa fa-fw fa-save"></i> [[.save.]]
            </div>
        </div>
        <div class="w3-row">
            <table style="width: 100%;" cellspacing="0" cellpadding="10">
                <!--LIST:caddie-->
                <tr style="height: 50px;">
                    <td style="width: 50px;"><img src="packages/hotel/packages/golf/modules/GolfCaddie/avata/[[|caddie.image_profile|]]" style="width: 45px; height: 45px; border-radius: 50%;" /></td>
                    <td style="">[[|caddie.full_name|]]</td>
                </tr>
                <tr style="border-bottom: 1px solid #F2f2f2;">
                    <td>
                        <input id="scheduler_caddie_id_[[|caddie.id|]]" name="scheduler_caddie[[[|caddie.id|]]][id]" type="hidden" value="[[|caddie.id|]]" />
                        <input id="scheduler_caddie_name_[[|caddie.id|]]" name="scheduler_caddie[[[|caddie.id|]]][name]" type="hidden" value="[[|caddie.full_name|]]" />
                    </td>
                    <td>
                        <table id="BulkDateRange_[[|caddie.id|]]" style="" cellspacing="5" cellpadding="5">
                            
                        </table>
                        <div onclick="AddDateRange('[[|caddie.id|]]');" class="w3-button w3-white w3-hover-white w3-margin w3-border w3-text-blue" style="font-weight: normal;">
                            [[.add_schduler.]]
                        </div>
                    </td>
                </tr>
                <!--/LIST:caddie-->
            </table>
        </div>
    </div>
</form>
<table id="BulkDateRangeTemplate" style="display: none;">
    <tr id="DateRangeGroup_X######X_Y######Y">
        <td><input type="text" name="scheduler_caddie[X######X][timeline][Y######Y][from_time]" id="scheduler_caddie_from_time_X######X_Y######Y" class="w3-input w3-border" style="width: 80px; text-align: center;" placeholder="HH:ii" maxlength="5" /></td>
        <td><input type="text" name="scheduler_caddie[X######X][timeline][Y######Y][to_time]" id="scheduler_caddie_to_time_X######X_Y######Y" class="w3-input w3-border" style="width: 80px; text-align: center;" placeholder="HH:ii" maxlength="5" /></td>
        <td><input type="text" name="scheduler_caddie[X######X][timeline][Y######Y][from_date]" id="scheduler_caddie_from_date_X######X_Y######Y" onchange="if(jQuery('#scheduler_caddie_to_date_X######X_Y######Y').val()==''){ jQuery('#scheduler_caddie_to_date_X######X_Y######Y').val(jQuery('#scheduler_caddie_from_date_X######X_Y######Y').val()); }" class="w3-input w3-border" style="width: 120px; text-align: center;" readonly="" placeholder="DD/MM/YYYY" maxlength="10" /></td>
        <td><input type="text" name="scheduler_caddie[X######X][timeline][Y######Y][to_date]" id="scheduler_caddie_to_date_X######X_Y######Y" onchange="if(jQuery('#scheduler_caddie_from_date_X######X_Y######Y').val()==''){ jQuery('#scheduler_caddie_from_date_X######X_Y######Y').val(jQuery('#scheduler_caddie_to_date_X######X_Y######Y').val()); }" class="w3-input w3-border" style="width: 120px; text-align: center;" readonly="" placeholder="DD/MM/YYYY" maxlength="10" /></td>
        <td><input type="checkbox" name="scheduler_caddie[X######X][timeline][Y######Y][MON]" checked="checked" /> Mon</td>
        <td><input type="checkbox" name="scheduler_caddie[X######X][timeline][Y######Y][TUE]" checked="checked" /> Tue</td>
        <td><input type="checkbox" name="scheduler_caddie[X######X][timeline][Y######Y][WED]" checked="checked" /> Wed</td>
        <td><input type="checkbox" name="scheduler_caddie[X######X][timeline][Y######Y][THU]" checked="checked" /> Thu</td>
        <td><input type="checkbox" name="scheduler_caddie[X######X][timeline][Y######Y][FRI]" checked="checked" /> Fri</td>
        <td><input type="checkbox" name="scheduler_caddie[X######X][timeline][Y######Y][SAT]" checked="checked" /> Sat</td>
        <td><input type="checkbox" name="scheduler_caddie[X######X][timeline][Y######Y][SUN]" checked="checked" /> Sun</td>
        <td><i class="fa fa-remove fa-fw w3-text-pink" onclick="DeleteDateRange(X######X,Y######Y);"></i></td>
    </tr>
</table>
<script>
    var CURRENT_YEAR = <?php echo date('Y')?>;
    var CURRENT_MONTH = <?php echo intval(date('m')) - 1;?>;
    var CURRENT_DAY = <?php echo date('d')?>;
    var $input_count_range = 100;
    var windowscrollTop = 0;
    jQuery(document).ready(function(){
          
    });
    function AddDateRange($caddie_id){
        var input_count = $input_count_range++;
        var content = jQuery("#BulkDateRangeTemplate").html().replace(/X######X/g,$caddie_id).replace(/Y######Y/g,input_count);
        jQuery("#BulkDateRange_"+$caddie_id).append(content);
        jQuery("#scheduler_caddie_from_date_"+$caddie_id+"_"+input_count).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
        jQuery("#scheduler_caddie_to_date_"+$caddie_id+"_"+input_count).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
        jQuery("#scheduler_caddie_from_time_"+$caddie_id+"_"+input_count).mask('99:99');
        jQuery("#scheduler_caddie_to_time_"+$caddie_id+"_"+input_count).mask('99:99');
    }
    function DeleteDateRange($caddie_id,input_count){
        jQuery("#DateRangeGroup_"+$caddie_id+"_"+input_count).remove();
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
