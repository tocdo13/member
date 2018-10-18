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
    .loader  {
        animation: rotate 1s infinite;  
        height: 50px;
        width: 50px;
        margin: 200px auto;
    }
    
    .loader:before,
    .loader:after {   
        border-radius: 50%;
        content: '';
        display: block;
        height: 20px;  
        width: 20px;
    }
    .loader:before {
        animation: ball1 1s infinite;  
        background-color: #cb2025;
        box-shadow: 30px 0 0 #f8b334;
        margin-bottom: 10px;
    }
    .loader:after {
        animation: ball2 1s infinite; 
        background-color: #00a096;
        box-shadow: 30px 0 0 #97bf0d;
    }
    .HeaderFixed {
        position: fixed;
        top: 0px;
        right: 0px;
        box-shadow: 0px 0px 3px #555;
    }
    @keyframes rotate {
        0% { 
            -webkit-transform: rotate(0deg) scale(0.8); 
            -moz-transform: rotate(0deg) scale(0.8);
        }
        50% { 
            -webkit-transform: rotate(360deg) scale(1.2); 
            -moz-transform: rotate(360deg) scale(1.2);
        }
        100% { 
            -webkit-transform: rotate(720deg) scale(0.8); 
            -moz-transform: rotate(720deg) scale(0.8);
        }
    }
    
    @keyframes ball1 {
        0% {
            box-shadow: 30px 0 0 #f8b334;
        }
        50% {
            box-shadow: 0 0 0 #f8b334;
            margin-bottom: 0;
            -webkit-transform: translate(15px,15px);
            -moz-transform: translate(15px, 15px);
        }
        100% {
            box-shadow: 30px 0 0 #f8b334;
            margin-bottom: 10px;
        }
    }
    
    @keyframes ball2 {
        0% {
            box-shadow: 30px 0 0 #97bf0d;
        }
        50% {
            box-shadow: 0 0 0 #97bf0d;
            margin-top: -20px;
            -webkit-transform: translate(15px,15px);
            -moz-transform: translate(15px, 15px);
        }
        100% {
            box-shadow: 30px 0 0 #97bf0d;
            margin-top: 0;
        }
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
<?php 
if(!SITEMINDER_TWO_WAY){
?>
<div class="w3-panel w3-leftbar w3-sand w3-xxlarge w3-serif">
  <p><i>"This feature is only visible when you use a two-way link with siteminder."</i></p>
</div> 
<?php
}else{
?>
<form name="ListSiteminderInventoryForm" method="POST" enctype="multipart/form-data">
<input name="act" id="act" type="hidden" />
<input name="step" type="hidden" id="step" />
<div id="InventoryHeader" style="width: 100%; height: auto; box-shadow: 0px 2px 2px #CCC; background: #F2F2F2; z-index: 99;">
    <div class="w3-container">
        <div class="w3-row" style="min-width: 1200px!important; max-width: 1420px; margin: 0px auto;">
            <div class="w3-row">
                <div class="w3-button w3-green w3-hover-green w3-margin w3-right NODESubmit" onclick="CheckSubmit('SAVE');" style="font-weight: normal; display: none;">
                    <i class="fa fa-fw fa-save"></i> Save
                </div>
                <div class="w3-button w3-margin w3-right w3-text-blue NODESubmit" onclick="CheckSubmit('RESET');" style="font-weight: normal;">
                    <i class="fa fa-fw fa-undo"></i> Reset
                </div>
                <div id="BulkUpdateButton" class="w3-button w3-blue w3-hover-blue w3-margin w3-right" style="font-weight: normal;">
                    <i class="fa fa-fw fa-bolt"></i> Bulk Update
                </div>
            </div>
            <table class="w3-table-all">
                <tr class="w3-white">
                    <td class="w3-text-blue" style="text-align: right; font-size: 20px; line-height: 45px; vertical-align: central;">
                        <i class="fa fa-undo fa-fw NODESubmit" onclick="CheckSubmit('UNDO');" style="margin-right: 5px; cursor: pointer;"></i>
                        <i class="fa fa-angle-double-left fa-fw NODESubmit" onclick="CheckSubmit('LOAD-DOUBLE-LEFT');" style="margin-right: 5px; cursor: pointer;"></i>
                        <i class="fa fa-angle-left fa-fw NODESubmit" onclick="CheckSubmit('LOAD-LEFT');" style="margin-right: 5px; cursor: pointer;"></i>
                        <input name="in_date" type="text" id="in_date" class="w3-text-blue" style="outline: none; border: none; background: none; margin-right: 5px; font-size: 20px; height: 45px; width: 100px;" readonly="" />
                        <i class="fa fa-angle-right fa-fw NODESubmit" onclick="CheckSubmit('LOAD-RIGHT');" style="margin-right: 5px; cursor: pointer;"></i>
                        <i class="fa fa-angle-double-right fa-fw NODESubmit" onclick="CheckSubmit('LOAD-DOUBLE-RIGHT');" style="margin-right: 5px; cursor: pointer;"></i>
                    </td>
                    <!--LIST:timeline-->
                    <td style="width: 60px; cursor: pointer; text-align: center; border-left: 1px solid #CCC; background: [[|timeline.background|]];">[[|timeline.weekday|]]<br /><span style="font-size: 15px;"><b>[[|timeline.mday|]]</b></span><br />[[|timeline.month|]]</td>
                    <!--/LIST:timeline-->
                </tr>
            </table>
        </div>
    </div>
</div>
<div id="InventoryContent" style="width: 100%; height: auto;">
    <div class="w3-container" style="margin-bottom: 50px;">
        <div class="w3-row" style="min-width: 1200px!important; max-width: 1420px; margin: 10px auto;">
            <!--LIST:items-->
            <table class="w3-table-all" style="margin-top: 10px;">
                    <tr class="w3-grey">
                        <td rowspan="2" colspan="2" style="overflow: hidden;" class="w3-light-grey"><span><i class="fa fa-fw fa-hotel"></i></span> <span style="font-size: 17px; line-height: 45px; font-weight: normal;">[[|items.type_name|]]</span></td>
                        <td style="width: 120px; border-left: 1px solid #CCC;"><span style="font-size: 11px;">AVAIL Real</span></td>
                        <!--LIST:items.timeline-->
                            <td class="w3-text-light-grey" style="width: 60px; text-align: center; border-left: 1px solid #CCC;">
                                <input id="RT_AVAILREAL_[[|items.id|]]_[[|items.timeline.time|]]" type="hidden" value="[[|items.timeline.availability_real|]]" /><!--name="room_type[[[|items.id|]]][timeline][[[|items.timeline.time|]]][availability_real]" -->
                                <input id="RT_OVERBOOK_QUANTITY_[[|items.id|]]" type="hidden" value="[[|items.overbook_quantity|]]" />
                                [[|items.timeline.availability_real|]]
                            </td>
                        <!--/LIST:items.timeline-->
                    </tr>
                    <tr class="w3-light-grey">
                        <td style="width: 120px; border-left: 1px solid #CCC;"><span style=" font-size: 11px;">AVAIL</span></td>
                        <!--LIST:items.timeline-->
                            <td class="NgClick" style="width: 60px; cursor: pointer; text-align: center; border-left: 1px solid #CCC; background: <!--IF:cond([[=items.timeline.data_status=]]==0)-->#5CB85C<!--ELSE-->[[|items.timeline.background-top|]]<!--/IF:cond-->!important;">
                                <input readonly="" onchange="UpdateRoomTypeColor(this.id,this.value); UpdateAvail(this.value,this.id,[[|items.overbook_quantity|]],[[|items.timeline.availability_real|]]);" class="InputRoomType input_number" id="RT_AVAIL_[[|items.id|]]_[[|items.timeline.time|]]" type="text" value="[[|items.timeline.availability|]]" style="outline: none; width: 100%; height: 100%; border: none; background: [[|items.timeline.availability_bg|]]; text-align: center;" /> <!--name="room_type[[[|items.id|]]][timeline][[[|items.timeline.time|]]][availability]" -->
                            </td>
                        <!--/LIST:items.timeline-->
                    </tr>
                    <!--LIST:items.child-->
                        <!--IF:condAvail([[=items.child.availability=]]=='MANAGED')-->
                            <tr class="w3-light-grey">
                                <td rowspan="2" style="overflow: hidden;"><span>[[|items.child.rate_name|]]</span> <?php echo (isset([[=items.child.child=]]) and sizeof([[=items.child.child=]])!=0)?'<span class="w3-tag w3-round-large w3-blue w3-center" style="cursor: pointer;" onclick="jQuery(\'.OTADISPLAY_'.[[=items.child.id=]].'\').toggle();">'.sizeof([[=items.child.child=]]).' channels</span>':''; ?></td>
                                <td rowspan="2" style="width: 20px; cursor: pointer;"><!--IF:cond([[=items.child.rate_config_derive=]]==1)--><i class="fa fa-fw fa-link w3-text-grey"></i><!--/IF:cond--></td>
                                <td style="width: 120px; border-left: 1px solid #CCC;"><span style=" font-size: 11px;">AVAIL</span></td>
                                <!--LIST:items.child.timeline-->
                                    <td class="NgClick" style="width: 60px; cursor: pointer; text-align: center; border-left: 1px solid #CCC; background: <!--IF:cond([[=items.child.timeline.data_status=]]==0)-->#5CB85C<!--ELSE-->[[|items.child.timeline.background-top|]]<!--/IF:cond-->!important;">
                                        <input readonly="" class="InputRoomType input_number" type="text" value="[[|items.child.timeline.availability|]]" style="outline: none; width: 100%; height: 100%; border: none; background: [[|items.child.timeline.availability_bg|]]; text-align: center;" />
                                    </td>
                                <!--/LIST:items.child.timeline-->
                            </tr>
                        <!--/IF:condAvail-->
                        <tr class="w3-white">
                            <!--IF:condAvail([[=items.child.availability=]]!='MANAGED')-->
                            <td style="overflow: hidden;"><span>[[|items.child.rate_name|]]</span> <?php echo (isset([[=items.child.child=]]) and sizeof([[=items.child.child=]])!=0)?'<span class="w3-tag w3-round-large w3-blue w3-center" style="cursor: pointer;" onclick="jQuery(\'.OTADISPLAY_'.[[=items.child.id=]].'\').toggle();">'.sizeof([[=items.child.child=]]).' channels</span>':''; ?></td>
                            <td style="width: 20px; cursor: pointer;"><!--IF:cond([[=items.child.rate_config_derive=]]==1)--><i class="fa fa-fw fa-link w3-text-grey"></i><!--/IF:cond--></td>
                            <!--/IF:condAvail-->
                            <td style="width: 120px; border-left: 1px solid #CCC; background: #FFFFFF;"><span style=" font-size: 11px;">RATES</span></td>
                            <!--LIST:items.child.timeline-->
                            <td <!--IF:cond([[=items.child.rate_config_derive=]]!=1)-->class="NgClick"<!--/IF:cond--> style="width: 60px; cursor: pointer; text-align: center; border-left: 1px solid #CCC; background: <!--IF:cond([[=items.child.timeline.data_status=]]==0)-->#5CB85C<!--ELSE-->[[|items.child.timeline.background|]]<!--/IF:cond-->!important;">
                                <input readonly="" onchange="UpdateRoomRateColor(this.id,this.value); UpdateRates(this.value,[[|items.child.id|]],[[|items.child.timeline.time|]]);" class="InputRoomRate input_number" id="RR_RATES_[[|items.child.id|]]_[[|items.child.timeline.time|]]" type="text" value="[[|items.child.timeline.rates|]]" style="outline: none; width: 100%; height: 100%; border: none; background: none; text-align: center; <!--IF:cond([[=items.child.rate_config_derive=]]==1)-->cursor: no-drop; opacity: 0.7;<!--/IF:cond-->" <!--IF:cond([[=items.child.rate_config_derive=]]==1)-->readonly="readonly"<!--/IF:cond--> /> <!--name="room_rate[[[|items.child.id|]]][timeline][[[|items.child.timeline.time|]]][rates]" -->
                            </td>
                            <!--/LIST:items.child.timeline-->
                        </tr>
                            <!--LIST:items.child.child-->
                                <tr class="w3-pale-blue OTADISPLAY_[[|items.child.id|]]" style="display: none;">
                                    <td style="overflow: hidden;"><span class="ShowSearch" style="display: none;">[[|items.child.rate_name|]]- </span><span class="w3-text-blue"><b> - [[|items.child.child.ota_name|]]</b></span></td>
                                    <!--<td style="width: 20px; cursor: pointer;" onclick=""><span class="show-pop-list-copy" style="display: none;"  data-placement="bottom"><i class="fa fa-fw fa-lg fa-copy w3-text-blue"></i></span></td>-->
                                    <td style="width: 20px; cursor: pointer;" onclick=""></td>
                                    <td style="width: 120px; border-left: 1px solid #CCC; background: #FFFFFF;">
                                        <select class="HotelAvailNotifRQ" style="background: none; padding: 0px 5px; border: none; font-size: 11px;">
                                            <option value="RATES">RATES</option>
                                            <option value="STOP_SELL">STOP SELL</option>
                                            <option value="CTA">CTA</option>
                                            <option value="CTD">CTD</option>
                                            <option value="MIN_STAY">MIN STAY</option>
                                            <option value="MAX_STAY">MAX STAY</option>
                                        </select>
                                    </td>
                                    <!--LIST:items.child.child.timeline-->
                                        <td class="NgClick" style="width: 60px; cursor: pointer; text-align: center; border-left: 1px solid #CCC; <!--IF:cond([[=items.child.child.timeline.data_status=]]==0)--> background: #5CB85C!important; <!--ELSE--> <!--IF:cond_stop([[=items.child.child.timeline.stop_sell=]]==1)--> background: #FFAB2D!important;<!--ELSE-->background: [[|items.child.child.timeline.background|]]!important;<!--/IF:cond_stop--> <!--/IF:cond-->">
                                            <input readonly="" onchange="UpdateRoomRateColor(this.id,this.value);" class="InputRoomRate HotelAvailNotifRQStatus OTARATES input_number" id="RRO_RATES_[[|items.child.child.id|]]_[[|items.child.child.timeline.time|]]" type="text" value="[[|items.child.child.timeline.rates|]]" style="outline: none; width: 100%; height: 100%; border: none; background: none; text-align: center; <!--IF:cond([[=items.child.child.manual_derive=]]=='DERIVE')-->cursor: no-drop; opacity: 0.7;<!--/IF:cond-->" <!--IF:cond([[=items.child.child.manual_derive=]]=='DERIVE')-->readonly="readonly"<!--/IF:cond--> />
                                            <!-- name="room_rate_ota[[[|items.child.child.id|]]][timeline][[[|items.child.child.timeline.time|]]][rates]" -->
                                            <input disabled="disabled" onchange="UpdateOTAColor(this.id);" class="CheckboxOTA HotelAvailNotifRQStatus OTASTOP_SELL" id="RRO_STOP_SELL_[[|items.child.child.id|]]_[[|items.child.child.timeline.time|]]" type="checkbox" <!--IF:cond([[=items.child.child.timeline.stop_sell=]]==1)-->checked="checked"<!--/IF:cond--> style="display: none;" />
                                            <!-- name="room_rate_ota[[[|items.child.child.id|]]][timeline][[[|items.child.child.timeline.time|]]][stop_sell]" -->
                                            <input disabled="disabled" onchange="UpdateOTAColor(this.id);" class="CheckboxOTA HotelAvailNotifRQStatus OTACTA" id="RRO_CTA_[[|items.child.child.id|]]_[[|items.child.child.timeline.time|]]" type="checkbox" <!--IF:cond([[=items.child.child.timeline.cta=]]==1)-->checked="checked"<!--/IF:cond--> style="display: none;" />
                                            <!-- name="room_rate_ota[[[|items.child.child.id|]]][timeline][[[|items.child.child.timeline.time|]]][cta]" -->
                                            <input disabled="disabled" onchange="UpdateOTAColor(this.id);" class="CheckboxOTA HotelAvailNotifRQStatus OTACTD" id="RRO_CTD_[[|items.child.child.id|]]_[[|items.child.child.timeline.time|]]" type="checkbox" <!--IF:cond([[=items.child.child.timeline.ctd=]]==1)-->checked="checked"<!--/IF:cond--> style="display: none;" />
                                            <!-- name="room_rate_ota[[[|items.child.child.id|]]][timeline][[[|items.child.child.timeline.time|]]][ctd]" -->
                                            <input readonly="" onchange="UpdateRoomRateColor(this.id,this.value);" class="InputRoomRate HotelAvailNotifRQStatus OTAMIN_STAY input_number" id="RRO_MIN_STAY_[[|items.child.child.id|]]_[[|items.child.child.timeline.time|]]" type="text" value="[[|items.child.child.timeline.min_stay|]]" style="outline: none; width: 100%; height: 100%; border: none; background: none; text-align: center; display: none;" />
                                            <!-- name="room_rate_ota[[[|items.child.child.id|]]][timeline][[[|items.child.child.timeline.time|]]][min_stay]" -->
                                            <input readonly="" onchange="UpdateRoomRateColor(this.id,this.value);" class="InputRoomRate HotelAvailNotifRQStatus OTAMAX_STAY input_number" id="RRO_MAX_STAY_[[|items.child.child.id|]]_[[|items.child.child.timeline.time|]]" type="text" value="[[|items.child.child.timeline.max_stay|]]" style="outline: none; width: 100%; height: 100%; border: none; background: none; text-align: center; display: none;" />
                                            <!-- name="room_rate_ota[[[|items.child.child.id|]]][timeline][[[|items.child.child.timeline.time|]]][max_stay]" -->
                                        </td>
                                    <!--/LIST:items.child.child.timeline-->
                                </tr>
                            <!--/LIST:items.child.child-->
                    <!--/LIST:items.child-->
            </table>
            <!--/LIST:items-->
        </div>
    </div>
</div>
<div id="PopoverContent" style="display:none;">
	
</div>
<div id="LightBoxCentral" style="width: 100%; height: 100%; display: none; overflow-y: auto; position: fixed; top: 0px; left: 0px; background: rgba(0,0,0,0.5); z-index: 999;">
    <div style="width: 100%; height: 100%; position: absolute; top: 0px; left: 0px; z-index: -1;" onclick="CloseLightBox();"></div>
    <div id="LightBoxCentralContent" style="min-width: 320px; max-width: 960px; min-height: 50px; z-index: 99; margin: 100px auto; background: white;">
        
    </div>
</div>
</form>
<div id="QBUTemplate" style="display: none;">
    <div class="w3-container w3-padding w3-text-dark-grey">
        <div class="w3-row w3-text-dark-grey">
            <h6 style="text-transform: uppercase;"><i class="fa fa-lg fa-fw fa-bolt"></i> Bulk Update</h6>
        </div>
        <hr />
        <div class="w3-row w3-text-dark-grey">
            <table cellspacing="5" cellpadding="5">
                <tr>
                    <td>Set</td>
                    <td>
                        <select onchange="jQuery('#bulk_text').val('');jQuery('#bulk_checkbox').removeAttr('checked');SetBulkType();" name="bulk_type" id="bulk_type" class="w3-input w3-border w3-text-dark-grey">
                            <option value="AVAIL">Availability</option>
                            <option value="RATES">Rates</option>
                            <option value="STOPSELL">Stop Sell</option>
                            <option value="CTA">CTA</option>
                            <option value="CTD">CTD</option>
                            <option value="MINSTAY">Min Stay</option>
                            <option value="MAXSTAY">Max Stay</option>
                        </select>
                    </td>
                    <td>to</td>
                    <td id="BulkTypeTo">
                        <input  name="bulk_text" type="number" id="bulk_text" class="w3-input w3-border w3-text-dark-grey input_number" />
                        <input  name="bulk_checkbox" type="checkbox" id="bulk_checkbox" style="display: none;" />
                    </td>
                </tr>
            </table>
        </div>
        <hr />
        <div class="w3-row w3-text-dark-grey">
            <table id="BulkDateRange" cellspacing="5" cellpadding="5"></table>
            <div onclick="AddDateRange();" class="w3-button w3-white w3-hover-white w3-margin w3-text-dark-grey w3-border" style="font-weight: normal;">
                Add Date Range
            </div>
        </div>
        <hr />
        <div class="w3-container w3-text-dark-grey">
            <div class="w3-row w3-margin w3-text-blue">
                <div class="w3-right" style="font-weight: normal;">
                    <span class="w3-text-blue" style="cursor: pointer;" onclick="jQuery('.BulkRoomType').attr('checked','checked');jQuery('.BulkRoomRate').attr('checked','checked');jQuery('.BulkRoomRate_MANAGED').attr('checked','checked');jQuery('.BulkRoomRateOTA').attr('checked','checked');jQuery('.BulkOTA').attr('checked','checked');SetBulkType();">SelectAll</span> |
                    <span class="w3-text-blue" style="cursor: pointer;" onclick="jQuery('.BulkRoomType').removeAttr('checked');jQuery('.BulkRoomRate').removeAttr('checked');jQuery('.BulkRoomRate_MANAGED').removeAttr('checked');jQuery('.BulkRoomRateOTA').removeAttr('checked');jQuery('.BulkOTA').removeAttr('checked');SetBulkType();">Clear</span>
                </div>
            </div>
            <div class="w3-twothird w3-padding">
                <!--LIST:items-->
                    <table class="w3-table-all w3-margin-bottom">
                        <tr class="w3-white w3-text-dark-grey">
                            <td style="width: 20px;"><!--IF:condIsAvail([[=items.is_set_avail=]]==1)--><input class="BulkRoomType BulkRoomType_[[|items.id|]] SetAvail" name="bulkroomtype[[[|items.id|]]][id]" id="bulkroomtypeId_[[|items.id|]]" value="[[|items.id|]]" type="checkbox" /><!--/IF:condIsAvail--></td>
                            <td style="width: 20px;"><i class="fa fa-fw fa-hotel w3-text-dark-grey"></i></td>
                            <td><span class="w3-text-dark-grey" style="font-size: 17px; font-weight: normal;">[[|items.type_name|]]</span></td>
                            <td class="w3-text-blue" style="text-align: right;">
                                <span class="w3-text-blue" style="cursor: pointer;" onclick="jQuery('.BulkRoomType_[[|items.id|]]').attr('checked','checked');SetBulkType();">SelectAll</span> |
                                <span class="w3-text-blue" style="cursor: pointer;" onclick="jQuery('.BulkRoomType_[[|items.id|]]').removeAttr('checked');SetBulkType();">Clear</span>
                            </td>
                        </tr>
                        <!--LIST:items.child-->
                        <tr class="w3-white w3-text-dark-grey">
                            <td></td>
                            <td style="width: 20px;"><input style="display: none;" class="BulkRoomRate BulkRoomType_[[|items.id|]]" name="bulkroomrate[[[|items.child.id|]]][id]" title="[[|items.child.availability|]]" id="bulkroomrateId_[[|items.child.id|]]" value="[[|items.child.id|]]" type="checkbox" lang="[[|items.child.rate_config_derive|]]" /></td>
                            <td colspan="2">[[|items.child.rate_name|]]</td>
                        </tr>
                            <!--LIST:items.child.child-->
                                <!--IF:condManualDerive([[=items.child.child.manual_derive=]]=='MANUALLY')-->
                                <tr class="w3-white w3-text-dark-grey BulkRoomRateOTATR" style="display: none;">
                                    <td></td>
                                    <td style="width: 20px;"></td>
                                    <td colspan="2"><input class="BulkRoomRateOTA BulkRoomType_[[|items.id|]]" name="bulkroomrateOTA[[[|items.child.child.id|]]][id]" id="bulkroomrateOTAId_[[|items.child.child.id|]]" value="[[|items.child.child.id|]]" type="checkbox" lang="[[|items.child.child.manual_derive|]]" /><span style="padding-left: 20px;" class="w3-text-blue">[[|items.child.child.ota_name|]]</span></td>
                                </tr>
                                <!--/IF:condManualDerive-->
                            <!--/LIST:items.child.child-->
                        <!--/LIST:items.child-->
                    </table>
                <!--/LIST:items-->
            </div>
            <div class="w3-third w3-padding">
                <table class="w3-table-all">
                    <tr class="w3-white w3-text-dark-grey">
                        <td colspan="2"><span class="w3-text-dark-grey" style="font-size: 17px; font-weight: normal;">Channels</span></td>
                        <td class="w3-text-blue" style="text-align: right;">
                            <span class="w3-text-blue" style="cursor: pointer;" onclick="jQuery('.BulkOTA').attr('checked','checked');SetBulkType();">SelectAll</span> |
                            <span class="w3-text-blue" style="cursor: pointer;" onclick="jQuery('.BulkOTA').removeAttr('checked');SetBulkType();">Clear</span>
                        </td>
                    </tr>
                    <!--LIST:Channel_OTA-->
                    <tr class="w3-white w3-text-dark-grey">
                        <td style="width: 20px;"><input style="display: none;" class="BulkOTA" name="bulkOTA[[[|Channel_OTA.id|]]][id]" id="bulkOTA_[[|Channel_OTA.id|]]" value="[[|Channel_OTA.id|]]" type="checkbox" /></td>
                        <td colspan="2">[[|Channel_OTA.ota_name|]]</td>
                    </tr>
                    <!--/LIST:Channel_OTA-->
                </table>
            </div>
        </div>
        <hr />
        <div class="w3-row w3-text-dark-grey">
            <div onclick="CheckSubmit('SAVE_BULK');" class="w3-button w3-green w3-hover-green w3-margin w3-right" style="font-weight: normal;">
                Save
            </div>
            <div onclick="CloseLightBox();" class="w3-button w3-white w3-hover-white w3-margin w3-text-blue w3-right" style="font-weight: normal;">
                Cancel
            </div>
        </div>
    </div>
</div>
<table id="BulkDateRangeTemplate" style="display: none;">
    <tr id="DateRangeGroup_X######X">
        <td><input type="text" name="bulkrange[X######X][from_date]" id="bulk_from_date_X######X" onchange="if(jQuery('#bulk_to_date_X######X').val()==''){ jQuery('#bulk_to_date_X######X').val(jQuery('#bulk_from_date_X######X').val()); }" class="w3-input w3-border" style="width: 120px;" readonly="" placeholder="From Date" /></td>
        <td><input type="text" name="bulkrange[X######X][to_date]" id="bulk_to_date_X######X" onchange="if(jQuery('#bulk_from_date_X######X').val()==''){ jQuery('#bulk_from_date_X######X').val(jQuery('#bulk_to_date_X######X').val()); }" class="w3-input w3-border" style="width: 120px;" readonly="" placeholder="To Date" /></td>
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
<div id="LoadingCentral" style="width: 100%; height: 100%; position: fixed; top: 0px; left: 0px; background: rgba(0,0,0,0.5); display: none; z-index: 9999;">
    <div class="loader"></div>
</div>
<script>
    
    var OVER_BOOK = <?php echo (!OVER_BOOK)?'false':'true'; ?>;
    var items_js = [[|items_js|]];
    var CURRENT_YEAR = <?php echo date('Y')?>;
    var CURRENT_MONTH = <?php echo intval(date('m')) - 1;?>;
    var CURRENT_DAY = <?php echo date('d')?>;
    var $input_count_range = 100;
    var windowscrollTop = 0;
    jQuery("#in_date").datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
    //OpenLightBox();
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
    function OpenLoading(){
        jQuery("#LoadingCentral").css('display','');
    }
    function CloseLoading(){
        jQuery("#LoadingCentral").css('display','none');
    }
    jQuery(document).ready(function(){
        jQuery(window).scroll(function(){
            /** cuon doc **/
            if(jQuery(window).scrollTop() > 100){
                jQuery("#InventoryHeader").addClass('position_fixed');
                jQuery("#InventoryContent").css('margin-top','125px');
            }else{
                jQuery("#InventoryHeader").removeClass('position_fixed');
                jQuery("#InventoryContent").css('margin-top','');
            }
        });
        jQuery(".HotelAvailNotifRQ").change(function(){ 
            jQuery(".HotelAvailNotifRQ").val(this.value);
            jQuery(".HotelAvailNotifRQStatus").css('display','none');
            jQuery(".OTA"+this.value).css('display','');
        });
        
        //jQuery(".NODESubmit").click(CheckSubmit(this.lang));
        
        jQuery("#in_date").change(function(){
            jQuery("#act").val('LOAD-DATE');
            jQuery("#step").val(0);
            ListSiteminderInventoryForm.submit();
        });
        //jQuery(".span.show-pop-list-bolt").click(function(){});
        
        //jQuery(".InputRoomType").change(function(){
            //UpdateRoomTypeColor(this.id,this.value);
        //}); //#D9534F,#5CB85C,#FFAB2D
        //jQuery(".InputRoomRate").change(function(){ 
            //UpdateRoomRateColor(this.id,this.value);
        //}); //#D9534F,#5CB85C,#FFAB2D
        //jQuery(".CheckboxOTA").change(function(){ 
            //UpdateOTAColor(this.id);
        //}); //#D9534F,#5CB85C,#FFAB2D
        
        jQuery("#BulkUpdateButton").click(function(){
            OpenLoading();
            $content = jQuery("#QBUTemplate").html();
            document.getElementById('LightBoxCentralContent').innerHTML = $content;
            AddDateRange();
            OpenLightBox();
            SetBulkType();
            CloseLoading();
        });
    });
    function CheckSubmit($act){
        var $check = true;
        var $mess = '';
        var $step = to_numeric(jQuery("#step").val());
        if($act == 'LOAD-DOUBLE-LEFT'){
            jQuery("#step").val($step-2);
        }else if($act == 'LOAD-LEFT'){
            jQuery("#step").val($step-1);
        }else if($act == 'LOAD-RIGHT'){
            jQuery("#step").val($step+1);
        }else if($act == 'LOAD-DOUBLE-RIGHT'){
            jQuery("#step").val($step+2);
        }else if($act == 'UNDO'){
            jQuery("#step").val(0);
            jQuery("#in_date").val(CURRENT_DAY+'/'+(CURRENT_MONTH+1)+'/'+CURRENT_YEAR);
        }else if($act == 'RESET'){
            location.reload();
        }else if($act == 'SAVE_BULK'){
            if(jQuery('#bulk_type').val()=='AVAIL' || jQuery('#bulk_type').val()=='RATES' || jQuery('#bulk_type').val()=='MINSTAY' || jQuery('#bulk_type').val()=='MAXSTAY'){
                if(jQuery("#bulk_text").val()==''){
                    $check = false;
                    $mess += 'Set '+jQuery('#bulk_type').val()+' to is required \n';
                }
            }
            $check_checked = false;
            $check_checked_2 = false;
            if(jQuery('#bulk_type').val()=='AVAIL'){ 
                $check_checked_2 = true;
                jQuery(".BulkRoomType").each(function(){
                    if(document.getElementById(this.id).checked==true){
                        $check_checked = true;
                    } 
                });
                jQuery(".BulkRoomRate").each(function(){
                    if(document.getElementById(this.id).checked==true){
                        $check_checked = true;
                    } 
                });
            }else if(jQuery('#bulk_type').val()=='RATES'){
                jQuery(".BulkRoomRate").each(function(){
                    if(document.getElementById(this.id).checked==true){
                        $check_checked = true;
                        $check_checked_2 = true;
                    }
                });
                jQuery(".BulkRoomRateOTA").each(function(){
                    if(document.getElementById(this.id).checked==true){
                        $check_checked_2 = true;
                        $check_checked = true;
                    }
                });
            }else{
                jQuery(".BulkRoomRate").each(function(){
                    if(document.getElementById(this.id).checked==true){
                        $check_checked = true;
                    }
                });
                jQuery(".BulkOTA").each(function(){
                    if(document.getElementById(this.id).checked==true){
                        $check_checked_2 = true;
                    }
                });
            }
            if(!$check_checked || !$check_checked_2){
                $check = false;
                $mess += 'At least one Room Type/Room Rate must be selected  \n';
            }
            $check_range = 0;
            for(var $i=100;$i<=$input_count_range;$i++){
                console.log(jQuery("#bulk_from_date_"+$i).val());
                if(jQuery("#bulk_from_date_"+$i).val()!=undefined){
                    $check_range++;
                    if(jQuery("#bulk_from_date_"+$i).val()=='' || jQuery("#bulk_to_date_"+$i).val()==''){
                        $check = false;
                        $mess += 'Set From Date And To Date Range are required \n';
                    }
                }
            }
            if($check){
                if($check_range==0){
                    $check = false;
                    $mess += 'Set Date Range is required \n';
                }
            }
        }
        
        if($check){
            jQuery("#act").val($act);
            ListSiteminderInventoryForm.submit();
        }else{
            alert($mess);
        }
    }
    function SetBulkType(){
        if(jQuery('#bulk_type').val()=='AVAIL'){
            jQuery(".BulkRoomType").css('display','');
            //jQuery(".BulkRoomType").removeAttr('checked');
            jQuery(".BulkOTA").css('display','none');
            jQuery(".BulkOTA").removeAttr('checked');
            jQuery(".BulkRoomRateOTATR").css('display','none');
            jQuery(".BulkRoomRateOTA").removeAttr('checked');
            //jQuery(".BulkRoomRate").css('display','none');
            //jQuery(".BulkRoomRate").removeAttr('checked');
            jQuery(".BulkRoomRate").each(function(){
                if(to_numeric(this.title)!='MANAGED'){
                    jQuery(this).css('display','none');
                    jQuery(this).removeAttr('checked');
                }else{
                    jQuery(this).css('display','');
                    //jQuery(".BulkRoomRate").removeAttr('checked');
                }
            });
        }else if(jQuery('#bulk_type').val()=='RATES'){
            jQuery(".BulkRoomType").css('display','none');
            jQuery(".BulkRoomType").removeAttr('checked');
            jQuery(".BulkOTA").css('display','none');
            jQuery(".BulkOTA").removeAttr('checked');
            jQuery(".BulkRoomRate").each(function(){
                if(to_numeric(this.lang)==1){
                    jQuery(this).css('display','none');
                    jQuery(this).removeAttr('checked');
                }else{
                    jQuery(this).css('display','');
                    //jQuery(".BulkRoomRate").removeAttr('checked');
                }
            });
            jQuery(".BulkRoomRateOTATR").css('display','');
            //jQuery(".BulkRoomRateOTA").removeAttr('checked');
        }else{
            jQuery(".BulkRoomRateOTATR").css('display','none');
            jQuery(".BulkRoomRateOTA").removeAttr('checked');
            jQuery(".BulkRoomType").css('display','none');
            jQuery(".BulkRoomType").removeAttr('checked');
            jQuery(".BulkOTA").css('display','');
            //jQuery(".BulkOTA").removeAttr('checked');
            jQuery(".BulkRoomRate").css('display','');
            //jQuery(".BulkRoomRate").removeAttr('checked');
        }
        if(jQuery('#bulk_type').val()=='AVAIL' || jQuery('#bulk_type').val()=='RATES' || jQuery('#bulk_type').val()=='MINSTAY' || jQuery('#bulk_type').val()=='MAXSTAY'){
            jQuery("#bulk_text").css('display','');
            jQuery("#bulk_checkbox").css('display','none');
        }else{
            jQuery("#bulk_text").css('display','none');
            jQuery("#bulk_checkbox").css('display','');
        }
    }
    
    function AddDateRange(){
        var input_count = $input_count_range++;
        var content = jQuery("#BulkDateRangeTemplate").html().replace(/X######X/g,input_count);
        jQuery("#BulkDateRange").append(content);
        jQuery("#bulk_from_date_"+input_count).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
        jQuery("#bulk_to_date_"+input_count).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
    }
    function DeleteDateRange(input_count){
        jQuery("#DateRangeGroup_"+input_count).remove();
    }
    function UpdateOTAColor($element_id){
        jQuery("#"+$element_id).parent().css('background','#5CB85C');
    }
    
    function UpdateRoomRateColor($element_id,$value){
        if($value==''){
            jQuery("#"+$element_id).parent().css('background','#D9534F');
        }else{
            jQuery("#"+$element_id).parent().css('background','#5CB85C');
        }
    }
    
    function UpdateRoomTypeColor($element_id,$room_type_avail){
        if($room_type_avail==''){ 
            jQuery("#"+$element_id).css('background','none'); 
            jQuery("#"+$element_id).parent().css('background','#D9534F');
        }else{
            if($room_type_avail==0)
                jQuery("#"+$element_id).css('background','#FFAB2D'); 
            else
                jQuery("#"+$element_id).css('background','none');
            jQuery("#"+$element_id).parent().css('background','#5CB85C');
        }
    }
    function UpdateRates($rates_value,$room_rate_id,$time){
        OpenLoading();
        jQuery.ajax({
			url:"form.php?block_id="+<?php echo Module::block_id();?>,
			type:"POST",
			data:{status:'UPDATERATES',room_rate_id:$room_rate_id,time:$time,rates_value:$rates_value},
			success:function(html)
            {
                $data = jQuery.parseJSON(html);
                console.log($data);
                for(var $r_r_id in $data['room_rate']){
                    for(var $t in  $data['room_rate'][$r_r_id]['timeline']){
                        jQuery("#RR_RATES_"+$r_r_id+"_"+$t).val($data['room_rate'][$r_r_id]['timeline'][$t]['rates']);
                        UpdateRoomRateColor("RR_RATES_"+$r_r_id+"_"+$t,$data['room_rate'][$r_r_id]['timeline'][$t]['rates']);
                    }
                }
                for(var $ota_id in $data['ota']){
                    for(var $t in  $data['ota'][$ota_id]['timeline']){
                        jQuery("#RRO_RATES_"+$ota_id+"_"+$t).val($data['ota'][$ota_id]['timeline'][$t]['rates']);
                        UpdateOTAColor("RRO_RATES_"+$ota_id+"_"+$t);
                    }
                }
                CloseLoading();
			}
          });
    }
    function UpdateAvail($avail_value,$type_time_id,$overbook_quantity,$availability_real){
        if( (to_numeric($availability_real)-to_numeric($overbook_quantity)) < to_numeric($avail_value) ){
            alert('Room availability is not guaranteed \n So luong phong trong khong dam bao tinh kha dung');
            jQuery("#"+$type_time_id).val( to_numeric($availability_real)-to_numeric($overbook_quantity) );
        }
    }
    /**
    function initPopover($type,$Content,){
        var settings = {
        		trigger:'click',
        		title:'Siteminder Popover',
        		content:'<p>Siteminder Popover.</p><p>Comming Soon !</p>',						
        		multi:false,						
        		closeable:false,
                padding:true,
        		style:'',
        		delay:300,
        		padding:false
        };
       	ContentSettings = {content:$Content,title:'',padding:true};
        var enamashow = 'show.webui.popover'; // hidden / show / shown / hide
        var enamehidden = 'hidden.webui.popover'; // hidden / show / shown / hide
        if($type=='BOLT')
            jQuery('span.show-pop-list-bolt').webuiPopover('destroy').webuiPopover(jQuery.extend({},settings,ContentSettings)).on(enamashow,function(e){  }).on(enamehidden,function(e){});
        
        var asyncSettings = {	width:'auto',
		 						height:'200',
		 						closeable:true,
		 						padding:false,
		 						cache:false,
		 						url:"form.php?block_id="+<?php echo Module::block_id();?>,
		 						type:'POST',
                                data:{status:'GETROOMTYPE',id:$id},
		 						content:function(html){
		 							$data = jQuery.parseJSON(html);
                                    $html_code = '<p></p>';
                                    return $html_code;
		 						}};
        $('a.show-pop-async').webuiPopover('destroy').webuiPopover($.extend({},settings,asyncSettings));
        
    }
    **/
</script>
<?php } ?>

