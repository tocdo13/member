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

<form name="GolfPriceManagerForm" method="POST" enctype="multipart/form-data">
<input name="act" id="act" type="hidden" />
<div id="InventoryHeader" style="width: 100%; height: auto;background: #F2F2F2; z-index: 99;">
    <div class="w3-container">
        <div class="w3-row" style="min-width: 1200px!important; max-width: 1420px; margin: 0px auto;">
            <div class="w3-row">
                <div class="w3-button w3-margin w3-left" style="font-weight: bold; text-transform: uppercase; background: none!important;">
                    <h3><img src="packages/hotel/packages/golf/includes/img/golf.png" style="height: 40px; width: auto;" /> [[.golf_rate.]]</h3>
                </div>
                <div class="w3-button w3-blue w3-hover-blue w3-margin w3-right" onclick="location.href='?page=golf_price_manager&cmd=add';" style="font-weight: normal;">
                    <i class="fa fa-fw fa-plus"></i> [[.add_price.]]
                </div>
                <div class="w3-button w3-blue w3-hover-blue w3-margin w3-right" onclick="jQuery('#act').val('SAVE'); GolfPriceManagerForm.submit();" style="font-weight: normal;">
                    <i class="fa fa-fw fa-save"></i> [[.save.]]
                </div>
            </div>
            <div class="w3-row">
                <table>
                    <tr>
                        <td><label for="in_date">[[.in_date.]]</label></td>
                        <td><input name="in_date" type="text" id="in_date" onchange="jQuery('#act').val(''); GolfPriceManagerForm.submit();" class="w3-input w3-border" style="width: 120px; text-align: center;" readonly="" /></td>
                    </tr>
                </table>
            </div>
            <hr />
            <div class="w3-row">
                <table style="width: 100%; background: rgba(255,255,255,1); box-shadow: 0px 1px 3px 1px rgba(60,64,67,.15); border: 5px solid #FFF; border-radius: 5px; margin: 15px;" cellspacing="0" cellpadding="5">
                    <tr style="height: 60px;">
                        <td colspan="2" style="font-weight: bold;">[[.golf_hole.]] / [[.group_traveller.]]</td>
                        <td style="width: 40px; border-left: 1px solid #ffc76c; vertical-align: bottom; position: relative;">
                            <span style="font-size: 9px; position: absolute; left: -12px; bottom: 0px; background: #FFF; color: red;">00:00</span>
                            <span style="width: 19px; height: 25px; border-right: 1px solid #ffeacd; position: absolute; top: 0px; left: 0px;"></span>
                        </td>
                        <!--LIST:timeline-->
                        <td style="width: 40px; border-left: 1px solid #ffc76c; vertical-align: bottom; position: relative;">
                            <span style="font-size: 9px; position: absolute; left: -12px; bottom: 0px; background: #FFF; color: red;">[[|timeline.in_house|]]</span>
                            <span style="width: 19px; height: 25px; border-right: 1px solid #ffeacd; position: absolute; top: 0px; left: 0px;"></span>
                        </td>
                        <!--/LIST:timeline-->
                    </tr>
                    <!--LIST:golf_hole-->
                    <tr style="height: 40px; background: #f9f9f9;">
                        <td colspan="<?php echo [[=count_time=]]+2; ?>">[[|golf_hole.name|]]</td>
                    </tr>
                        <!--LIST:group_traveller-->
                        <tr style="height: 40px;">
                            <td style="width: 80px;"></td>
                            <td style="border-bottom: 1px dashed #EEE;" class="w3-text-blue">[[|group_traveller.name|]]</td>
                            <td colspan="<?php echo [[=count_time=]]; ?>" style="position: relative; border-bottom: 1px dashed #EEE;">
                                <!--LIST:items-->
                                    <?php if([[=items.golf_hole_id=]]==[[=golf_hole.id=]] and [[=items.group_traveller_id=]]==[[=group_traveller.id=]]){ ?>
                                    <input id="items_[[|items.id|]]" name="golf_price[[[|items.id|]]][price]" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val())));" value="<?php echo System::display_number([[=items.price=]]); ?>" title="<?php echo date('H:i',[[=items.start_time=]]).' - '.date('H:i',[[=items.end_time=]]); ?>" class="input_number" style="width: <?php echo (([[=items.end_time=]]-[[=items.start_time=]])/3600)*40 - 5; ?>px; height: 30px; border-radius: 15px; padding-right: 5px; border: 1px solid #FFF; background: rgba(100,181,246,0.5); text-align: right; position: absolute; top: 5px; left: <?php echo (([[=items.start_time=]]-[[=in_time=]])/3600)*40; ?>px;" type="text" />
                                    <?php } ?>
                                <!--/LIST:items-->
                            </td>
                        </tr>
                        <!--/LIST:group_traveller-->
                    <!--/LIST:golf_hole-->
                </table>
            </div>
        </div>
    </div>
</div>
<div id="InventoryContent" style="width: 100%; height: auto;">
    <div class="w3-container" style="margin-bottom: 50px;">
        <div class="w3-row" style="min-width: 1200px!important; max-width: 1420px; margin: 10px auto;">
            
        </div>
    </div>
</div>
<div id="LightBoxCentral" style="width: 100%; height: 100%; display: none; overflow-y: auto; position: fixed; top: 0px; left: 0px; background: rgba(0,0,0,0.5); z-index: 999;">
    <div style="width: 100%; height: 100%; position: absolute; top: 0px; left: 0px; z-index: -1;" onclick="CloseLightBox();"></div>
    <div id="LightBoxCentralContent" style="min-width: 320px; max-width: 960px; min-height: 50px; z-index: 99; margin: 100px auto; background: white;">
        
    </div>
</div>
</form>

<script>
    //jQuery(this).val(number_format(to_numeric(jQuery(this).val())));
    var CURRENT_YEAR = <?php echo date('Y')?>;
    var CURRENT_MONTH = <?php echo intval(date('m')) - 1;?>;
    var CURRENT_DAY = <?php echo date('d')?>;
    var $input_count_range = 100;
    var windowscrollTop = 0;
    jQuery("#in_date").datepicker();
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

