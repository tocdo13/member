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
    .RoomLevelInfo {
        opacity: 0;
        transition: all 0.3s ease-out;
    }
    h5:hover .RoomLevelInfo{
        opacity: 1;
    }
</style>
<form name="MapRoomRateSiteminderForm" method="POST" enctype="multipart/form-data">
<input name="act" id="act" type="hidden" />
<div class="w3-row">
    <div class="w3-row w3-padding">
        <p class=" w3-left"><h5><i class="fa fa-fw fa-tachometer"></i> MAP ROOM TYPE & ROOM RATE</h5></p>
    </div>
    <div class="w3-row w3-padding" id="DashboardContent">
        <div class="w3-round w3-light-grey w3-padding">
            <i class="fa fa-warning fa-fw"></i> Chú ý:
            <p> - Mọi khai báo về Room Rate giữa PMS và Siteminder phải là đồng nhất</p>
            <p> - Mỗi RoomType khai báo trên siteminder chỉ tương ứng với 1 loại RoomLevel trong PMS</p>
        </div>
        <hr />
        <div class="w3-button w3-white w3-hover-white w3-margin w3-border w3-right" onclick="AddNewRoomType();">
            <h6><i class="fa fa-plus fa-fw"></i> Add New Room Type</h6>
        </div>
        <div class="w3-round w3-padding w3-margin">
            <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
            <table class="w3-table-all w3-margin">
                    <tr class="w3-light-grey">
                        <td colspan="2" style="cursor: pointer;" onclick="EditRoomType(<?php echo $this->map['items']['current']['id'];?>);"><h5><span><i class="fa fa-fw fa-hotel"></i></span> <b><?php echo $this->map['items']['current']['type_name'];?></b> <?php echo $this->map['items']['current']['description'];?></h5></td>
                        <td style="text-align: right; width: 10%;">
                            <div class="w3-button  w3-white w3-hover-white w3-border w3-right" onclick="AddNewRoomRate('<?php echo $this->map['items']['current']['type_name'];?>',<?php echo $this->map['items']['current']['id'];?>);">
                                <i class="fa fa-plus fa-fw"></i> New Room Rate
                            </div>
                        </td>
                    </tr>
                    <?php if(isset($this->map['items']['current']['child']) and is_array($this->map['items']['current']['child'])){ foreach($this->map['items']['current']['child'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current']['child']['current'] = &$item2;?>
                    <tr class="w3-white">
                        <td style="padding-left: 50px; width: 30%;">
                            <span style="cursor: pointer;" onclick="EditRoomRate('<?php echo $this->map['items']['current']['type_name'];?>',<?php echo $this->map['items']['current']['id'];?>,'<?php echo $this->map['items']['current']['child']['current']['rate_plan_code'];?>','<?php echo $this->map['items']['current']['child']['current']['rate_name'];?>','<?php echo $this->map['items']['current']['child']['current']['availability'];?>','<?php echo $this->map['items']['current']['child']['current']['default_min_stay'];?>','<?php echo $this->map['items']['current']['child']['current']['default_stop_sell'];?>','<?php echo $this->map['items']['current']['child']['current']['rate_config_manual'];?>','<?php echo $this->map['items']['current']['child']['current']['manual_rack_rate'];?>','<?php echo $this->map['items']['current']['child']['current']['rate_config_derive'];?>','<?php echo $this->map['items']['current']['child']['current']['derive_from_rate_id'];?>','<?php echo $this->map['items']['current']['child']['current']['daily_rate'];?>','<?php echo $this->map['items']['current']['child']['current']['amount_inc'];?>','<?php echo $this->map['items']['current']['child']['current']['amount_dec'];?>','<?php echo $this->map['items']['current']['child']['current']['amount_adjust'];?>','<?php echo $this->map['items']['current']['child']['current']['percent_inc'];?>','<?php echo $this->map['items']['current']['child']['current']['percent_dec'];?>','<?php echo $this->map['items']['current']['child']['current']['percent_adjust'];?>','<?php echo $this->map['items']['current']['child']['current']['id'];?>','<?php echo $this->map['items']['current']['child']['current']['auto_set_avail'];?>','<?php echo $this->map['items']['current']['child']['current']['overbook_quantity'];?>');"><?php echo $this->map['items']['current']['child']['current']['rate_plan_code'];?>: <?php echo $this->map['items']['current']['child']['current']['rate_name'];?></span> 
                            <span style="cursor: pointer;" class="w3-text-teal" onclick="GetChannel(<?php echo $this->map['items']['current']['child']['current']['id'];?>);"> <i class="fa fa-plug fa-fw"></i> Map to Channel <?php echo sizeof($this->map['items']['current']['child']['current']['child'])!=0?'<span class="w3-tag w3-round-large w3-green w3-center">'.sizeof($this->map['items']['current']['child']['current']['child']).'</span>':''; ?></span>
                        </td>
                        <td colspan="2" style="border-left: 1px solid #CCC;">
                            <span style="cursor: pointer;" onclick="EditRoomRate('<?php echo $this->map['items']['current']['type_name'];?>',<?php echo $this->map['items']['current']['id'];?>,'<?php echo $this->map['items']['current']['child']['current']['rate_plan_code'];?>','<?php echo $this->map['items']['current']['child']['current']['rate_name'];?>','<?php echo $this->map['items']['current']['child']['current']['availability'];?>','<?php echo $this->map['items']['current']['child']['current']['default_min_stay'];?>','<?php echo $this->map['items']['current']['child']['current']['default_stop_sell'];?>','<?php echo $this->map['items']['current']['child']['current']['rate_config_manual'];?>','<?php echo $this->map['items']['current']['child']['current']['manual_rack_rate'];?>','<?php echo $this->map['items']['current']['child']['current']['rate_config_derive'];?>','<?php echo $this->map['items']['current']['child']['current']['derive_from_rate_id'];?>','<?php echo $this->map['items']['current']['child']['current']['daily_rate'];?>','<?php echo $this->map['items']['current']['child']['current']['amount_inc'];?>','<?php echo $this->map['items']['current']['child']['current']['amount_dec'];?>','<?php echo $this->map['items']['current']['child']['current']['amount_adjust'];?>','<?php echo $this->map['items']['current']['child']['current']['percent_inc'];?>','<?php echo $this->map['items']['current']['child']['current']['percent_dec'];?>','<?php echo $this->map['items']['current']['child']['current']['percent_adjust'];?>','<?php echo $this->map['items']['current']['child']['current']['id'];?>','<?php echo $this->map['items']['current']['child']['current']['auto_set_avail'];?>','<?php echo $this->map['items']['current']['child']['current']['overbook_quantity'];?>');"><?php 
				if(($this->map['items']['current']['child']['current']['description']!=''))
				{?><i class="fa fa-link fa-fw"></i><?php echo $this->map['items']['current']['child']['current']['description'];?>
				<?php
				}
				?></span>
                            <?php 
				if(($this->map['items']['current']['child']['current']['rate_config_derive']==1))
				{?> 
                                <span style="cursor: pointer;" class="w3-text-pink" onclick="RateOverride(<?php echo $this->map['items']['current']['child']['current']['id'];?>);"><i class="fa fa-fw fa-plus"></i>Add Rate Override</span>
                            
				<?php
				}
				?>
                        </td>
                    </tr>
                        <?php if(isset($this->map['items']['current']['child']['current']['child']) and is_array($this->map['items']['current']['child']['current']['child'])){ foreach($this->map['items']['current']['child']['current']['child'] as $key3=>&$item3){if($key3!='current'){$this->map['items']['current']['child']['current']['child']['current'] = &$item3;?>
                        <tr class="w3-pale-green  RoomRateItems_<?php echo $this->map['items']['current']['child']['current']['id'];?>">
                            <td style="padding-left: 100px; width: 30%;">
                                <span style="cursor: pointer;" onclick="EditRoomRateOta(<?php echo $this->map['items']['current']['child']['current']['child']['current']['id'];?>);" class="w3-text-teal"><b> - <?php echo $this->map['items']['current']['child']['current']['child']['current']['ota_name'];?></b></span> 
                            </td>
                            <td colspan="2" style="padding-left: 50px; border-left: 1px solid #CCC;">
                                <span style="cursor: pointer;" onclick="EditRoomRateOta(<?php echo $this->map['items']['current']['child']['current']['child']['current']['id'];?>);"><?php 
				if(($this->map['items']['current']['child']['current']['child']['current']['description']!=''))
				{?><i class="fa fa-link fa-fw"></i><?php echo $this->map['items']['current']['child']['current']['child']['current']['description'];?>
				<?php
				}
				?></span>
                                <?php 
				if(($this->map['items']['current']['child']['current']['child']['current']['manual_derive']=='DERIVE'))
				{?> 
                                    <span style="cursor: pointer;" class="w3-text-pink" onclick="ChannelOverride(<?php echo $this->map['items']['current']['child']['current']['id'];?>);"><i class="fa fa-fw fa-plus"></i>Add Rate Override</span>
                                
				<?php
				}
				?>
                            </td>
                        </tr>
                        <?php }}unset($this->map['items']['current']['child']['current']['child']['current']);} ?>
                    <?php }}unset($this->map['items']['current']['child']['current']);} ?>
            </table>
            <?php }}unset($this->map['items']['current']);} ?>
        </div>
    </div>
</div>
<div id="LightBoxCentral" style="width: 100%; height: 100%; display: none; overflow-y: auto; position: fixed; top: 0px; left: 0px; background: rgba(0,0,0,0.5); z-index: 999;">
    <div style="width: 100%; height: 100%; position: absolute; top: 0px; left: 0px; z-index: -1;" onclick="CloseLightBox();"></div>
    <div id="LightBoxCentralContent" class="w3-padding" style="min-width: 320px; max-width: 720px; min-height: 50px; z-index: 99; margin: 100px auto; background: white;">
    </div>
</div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<div id="LoadingCentral" style="width: 100%; height: 100%; position: fixed; top: 0px; left: 0px; background: rgba(0,0,0,0.5); display: none; z-index: 9999;">
    <div class="loader"></div>
</div>
<script>
    var OVER_BOOK = <?php echo (!OVER_BOOK)?'false':'true'; ?>;
    var room_level_js = <?php echo $this->map['room_level_js'];?>;
    var items_js = <?php echo $this->map['items_js'];?>;
    //OpenLightBox();
    function OpenLightBox(){
        windowscrollTop = jQuery(window).scrollTop();
        jQuery("body").addClass('over_hidden');
        jQuery("#LightBoxCentral").css('display','');
    }
    function CloseLightBox(){
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
        
    });
    function DeleteRateOta($rate_ota_id){
        OpenLoading();
        jQuery.ajax({
			url:"form.php?block_id="+<?php echo Module::block_id();?>,
			type:"POST",
			data:{status:'DELETERATEOTA',id:$rate_ota_id},
			success:function(html)
            {
                alert(html);
                CloseLoading();
                CloseLightBox();
                location.reload();
			}
          });
    }
    function SaveRateOta($rate_ota_id){
        $check = true;
        $messenge = '';
        if(jQuery("#Manual_derive").val()=='DERIVE'){
            if(to_numeric(jQuery("#daily_rate").val())==2){
                if(jQuery("#percent_adjust").val()==''){
                    $check = false;
                    $messenge += 'Percentage adjustment is required \n';
                }
            }else if(to_numeric(jQuery("#daily_rate").val())==3){
                if(jQuery("#amount_adjust").val()==''){
                    $check = false;
                    $messenge += 'Amount adjustment is required \n';
                }
            }else if(to_numeric(jQuery("#daily_rate").val())==4){
                if(jQuery("#amount_adjust").val()==''){
                    $check = false;
                    $messenge += 'Amount adjustment is required \n';
                }
                if(jQuery("#percent_adjust").val()==''){
                    $check = false;
                    $messenge += 'Percentage adjustment is required \n';
                }
            }else if(to_numeric(jQuery("#daily_rate").val())==5){
                if(jQuery("#percent_adjust").val()==''){
                    $check = false;
                    $messenge += 'Percentage adjustment is required \n';
                }
                if(jQuery("#amount_adjust").val()==''){
                    $check = false;
                    $messenge += 'Amount adjustment is required \n';
                }
            }
        }
        
        if(!$check){
            alert($messenge);
        }else{
            OpenLoading();
            var form_data = new FormData();
            $amount_adjust = '';
            $amount_inc = 1;
            $amount_dec = 0;
            $percent_adjust = '';
            $percent_inc = 1;
            $percent_dec = 0;
            if(to_numeric(jQuery("#daily_rate").val())==2){
                $percent_adjust = jQuery("#percent_adjust").val();
                $percent_inc = document.getElementById('percent_inc').checked==true?1:0;
                $percent_dec = document.getElementById('percent_dec').checked==true?1:0;
            }else if(to_numeric(jQuery("#daily_rate").val())==3){
                $amount_adjust = jQuery("#amount_adjust").val();
                $amount_inc = document.getElementById('amount_inc').checked==true?1:0;
                $amount_dec = document.getElementById('amount_dec').checked==true?1:0;
            }else if(to_numeric(jQuery("#daily_rate").val())==4 || to_numeric(jQuery("#daily_rate").val())==5){
                $amount_adjust = jQuery("#amount_adjust").val();
                $amount_inc = document.getElementById('amount_inc').checked==true?1:0;
                $amount_dec = document.getElementById('amount_dec').checked==true?1:0;
                $percent_adjust = jQuery("#percent_adjust").val();
                $percent_inc = document.getElementById('percent_inc').checked==true?1:0;
                $percent_dec = document.getElementById('percent_dec').checked==true?1:0;
            }
            form_data.append('status', 'SAVERATEOTA');
            form_data.append('Manual_derive', jQuery("#Manual_derive").val());
            form_data.append('daily_rate', jQuery("#daily_rate").val());
            form_data.append('amount_adjust', $amount_adjust);
            form_data.append('amount_inc', $amount_inc);
            form_data.append('amount_dec', $amount_dec);
            form_data.append('percent_adjust', $percent_adjust);
            form_data.append('percent_inc', $percent_inc);
            form_data.append('percent_dec', $percent_dec);
            form_data.append('rate_ota_id', $rate_ota_id);
            form_data.append('sent_rates', document.getElementById('sent_rates').checked==true?1:0);
            form_data.append('sent_stop_sell', document.getElementById('sent_stop_sell').checked==true?1:0);
            form_data.append('sent_cta', document.getElementById('sent_cta').checked==true?1:0);
            form_data.append('sent_ctd', document.getElementById('sent_ctd').checked==true?1:0);
            form_data.append('sent_min_stay', document.getElementById('sent_min_stay').checked==true?1:0);
            form_data.append('sent_max_stay', document.getElementById('sent_max_stay').checked==true?1:0);
            jQuery.ajax({
    			url:"form.php?block_id="+<?php echo Module::block_id();?>,
                cache: false,
                contentType: false,
                processData: false,
    			type:"POST",
    			data:form_data,
    			success:function(html)
                {
                    alert(html);
                    //console.log(html);
                    CloseLoading();
                    CloseLightBox();
                    location.reload();                    
    			}
              });
        }
    }
    function EditRoomRateOta($rate_ota_id){
        OpenLoading();
        jQuery.ajax({
			url:"form.php?block_id="+<?php echo Module::block_id();?>,
			type:"POST",
			data:{status:'EDITRATEOTA',rate_ota_id:$rate_ota_id},
			success:function(html)
            {
                $data = jQuery.parseJSON(html);
                $content = '';
                $content += '<div class="w3-container">';
                    $content += '<div class="w3-row w3-padding w3-text-cyan"><h3>Configure Channel '+$data['booking_channel_name']+'</h3><p> '+$data['type_name']+' /  '+$data['rate_name']+'</p></div>';
                    $content += '<hr />';
                    $content += '<div class="w3-round w3-padding">';
                        $content += '<table id="ConfigRateOtaContent" style="width: 100%;" cellpadding="5" cellspacing="5">';
                            $content += '<tr>';
                                $content += '<td style="text-align: right; width: 50%;"><b>Sent</b></td>';
                                $content += '<td>';
                                    $content += '<input type="checkbox" id="sent_rates" '+($data['sent_rates']==1?'checked="checked"':'')+' /> Rates <br/>';
                                    $content += '<input type="checkbox" id="sent_stop_sell" '+($data['sent_stop_sell']==1?'checked="checked"':'')+' /> Stop Sell <br/>';
                                    $content += '<input type="checkbox" id="sent_cta" '+($data['sent_cta']==1?'checked="checked"':'')+' /> CTA <br/>';
                                    $content += '<input type="checkbox" id="sent_ctd" '+($data['sent_ctd']==1?'checked="checked"':'')+' /> CTD <br/>';
                                    $content += '<input type="checkbox" id="sent_min_stay" '+($data['sent_min_stay']==1?'checked="checked"':'')+' /> Min Stay <br/>';
                                    $content += '<input type="checkbox" id="sent_max_stay" '+($data['sent_max_stay']==1?'checked="checked"':'')+' /> Max Stay <br/>';
                                $content += '</td>';
                            $content += '</tr>';
                            $content += '<tr>';
                                $content += '<td style="text-align: right; width: 50%;"><b>Manual/Derived *</b></td>';
                                $content += '<td>';
                                    $content += '<select id="Manual_derive" class="w3-input w3-border" onchange="ManuallyDeriveConfigOta(this.value);">';
                                        $content += '<option value="MANUALLY" '+($data['manual_derive']!='DERIVE'?'selected="selected"':'')+'>Manually input daily rates</option>';
                                        $content += '<option value="DERIVE" '+($data['manual_derive']=='DERIVE'?'selected="selected"':'')+'>Derive rates from '+$data['type_name']+' /  '+$data['rate_name']+'</option>';
                                    $content += '</select>';
                                $content += '</td>';
                            $content += '</tr>';
                            $content += '<tr class="AdjustDailyRatesBy" '+($data['manual_derive']!='DERIVE'?'style="display: none;"':'')+'>';
                                $content += '<td style="text-align: right;"><b>Adjust daily rates by *</b></td>';
                                $content += '<td>';
                                    $content += '<select  name="daily_rate" id="daily_rate" onchange="SelectDailyRate(\'ConfigRateOtaContent\');" class="w3-input w3-border">';
                                        $content += '<option value="1" '+($data['daily_rate']==1?'selected="selected"':'')+'>Keep rates the same</option>';
                                        $content += '<option value="2" '+($data['daily_rate']==2?'selected="selected"':'')+'>Percentage</option>';
                                        $content += '<option value="3" '+($data['daily_rate']==3?'selected="selected"':'')+'>Amount</option>';
                                        $content += '<option value="4" '+($data['daily_rate']==4?'selected="selected"':'')+'>Amount then percentage</option>';
                                        $content += '<option value="5" '+($data['daily_rate']==5?'selected="selected"':'')+'>Percentage then amount</option>';
                                    $content += '</select>';
                                $content += '</td>';
                            $content += '</tr>';
                            if(to_numeric($data['daily_rate'])==2){
                                $content += CreateElementPercentageAdjust($data['percent_inc'],$data['percent_dec'],$data['percent_adjust']);
                            }else if(to_numeric($data['daily_rate'])==3){
                                $content += CreateElementAmountAdjust($data['amount_inc'],$data['amount_dec'],$data['amount_adjust']);
                            }else if(to_numeric($data['daily_rate'])==4){
                                $content += CreateElementAmountAdjust($data['amount_inc'],$data['amount_dec'],$data['amount_adjust']);
                                $content += CreateElementPercentageAdjust($data['percent_inc'],$data['percent_dec'],$data['percent_adjust']);
                            }else if(to_numeric($data['daily_rate'])==5){
                                $content += CreateElementPercentageAdjust($data['percent_inc'],$data['percent_dec'],$data['percent_adjust']);
                                $content += CreateElementAmountAdjust($data['amount_inc'],$data['amount_dec'],$data['amount_adjust']);
                            }
                        $content += '</table>';
                    $content += '</div>';
                    $content += '<hr />';
                    $content += '<div class="w3-round w3-padding">';
                        $content += '<div class="w3-button w3-teal w3-margin w3-round w3-right" onclick="SaveRateOta('+$rate_ota_id+');">';
                            $content += '<i class="fa fa-save fa-fw"></i> Save';
                        $content += '</div>';
                            $content += '<div class="w3-button w3-white w3-hover-white w3-text-pink w3-margin w3-right" onclick="if(confirm(\' Are You Sure? \')){ DeleteRateOta('+$rate_ota_id+'); }">';
                                $content += '<i class="fa fa-remove fa-fw"></i> Delete';
                            $content += '</div>';
                        $content += '<div class="w3-button w3-white w3-hover-white w3-margin w3-round w3-right" onclick="CloseLightBox();">';
                            $content += 'Cancel';
                        $content += '</div>';
                    $content += '</div>';
                $content += '</div>';
                document.getElementById('LightBoxCentralContent').innerHTML = $content;
                OpenLightBox();
                CloseLoading();
			}
          });
    }
    function ConnectChannel($rate_id,$ota_code){
        OpenLoading();
        jQuery.ajax({
			url:"form.php?block_id="+<?php echo Module::block_id();?>,
			type:"POST",
			data:{status:'CONNECTCHANNEL',rate_id:$rate_id,ota_code:$ota_code},
			success:function(html)
            {
                $data = jQuery.parseJSON(html);
                CloseLoading();
                location.reload();  
			}
          });
    }
    function GetChannel($rate_id){
        OpenLoading();
        jQuery.ajax({
			url:"form.php?block_id="+<?php echo Module::block_id();?>,
			type:"POST",
			data:{status:'GETCHANNEL',rate_id:$rate_id},
			success:function(html)
            {
                $data = jQuery.parseJSON(html);
                if(to_numeric($data['count_channel'])>0){
                    $content = '<div class="w3-container">';
                        $content += '<div class="w3-row w3-padding w3-text-cyan"><h3>Map To Channel</p></div>';
                        $content += '<hr />';
                        $content += '<div class="w3-round w3-padding">';
                            $content += '<label>Choose A Room Type *</label>';
                            $content += '<ul class="w3-ul w3-hoverable w3-border">';
                              for(var i in $data['value']){
                                $content += '<li style="cursor: pointer;" onclick="ConnectChannel('+$rate_id+',\''+$data['value'][i]['booking_channel_code']+'\');"><i class="fa fa-fw fa-plug"></i> <span class="w3-text-green">'+$data['value'][i]['booking_channel_name']+'</span></li>';
                            }
                            $content += '</ul>';
                        $content += '</div>';
                        $content += '<hr />';
                        $content += '<div class="w3-row w3-padding">';
                            $content += '<div class="w3-button w3-white w3-hover-white w3-margin w3-round w3-right" onclick="CloseLightBox();">';
                                $content += 'Cancel';
                            $content += '</div>';
                        $content += '</div>';
                    $content += '</div>';
                    document.getElementById('LightBoxCentralContent').innerHTML = $content;
                    OpenLightBox();
                }else{
                    alert('nonthing!');
                }
                CloseLoading();
			}
          });
    }
    function SaveMoveRoomRate($rate_id){
        if(jQuery("#RoomTypeMoveId").val()!=''){
            $room_type_id = jQuery("#RoomTypeMoveId").val();
            OpenLoading();
            jQuery.ajax({
    			url:"form.php?block_id="+<?php echo Module::block_id();?>,
    			type:"POST",
    			data:{status:'SAVEMOVEROOMRATE',room_rate_id:$rate_id,room_type_id:$room_type_id},
    			success:function(html)
                {
                    alert(html);
                    CloseLoading();
                    CloseLightBox();
                    location.reload();
    			}
              });
        }else{
            alert('Choose A Room Type');
        }
    }
    function MoveRoomRate($rate_id){
        OpenLoading();
        jQuery.ajax({
			url:"form.php?block_id="+<?php echo Module::block_id();?>,
			type:"POST",
			data:{status:'MOVEROOMRATE'},
			success:function(html)
            {
                $data = jQuery.parseJSON(html);
                if(to_numeric($data['count_room_type'])>0){
                    $content = '<div class="w3-container">';
                        $content += '<div class="w3-row w3-padding w3-text-cyan"><h3>Move to Room Type</p></div>';
                        $content += '<hr />';
                        $content += '<div class="w3-round w3-pale-yellow w3-padding">';
                            $content += '<p class="w3-text-amber"><i class="fa fa-warning fa-fw w3-text-amber"></i> Room Rate configuration will be adjusted to match that of new Room Type, including availability.</p>';
                        $content += '</div>';
                        $content += '<div class="w3-round w3-padding">';
                            $content += '<label>Choose A Room Type *</label>';
                            $content += '<select id="RoomTypeMoveId" class="w3-input w3-border">';
                                $content += '<option value="">Please select a value</option>';
                                for(var i in $data['value']){
                                    $content += '<option value="'+$data['value'][i]['id']+'">'+$data['value'][i]['type_name']+'</option>';
                                }
                            $content += '</select>';
                        $content += '</div>';
                        $content += '<hr />';
                        $content += '<div class="w3-row w3-padding">';
                            $content += '<div class="w3-button w3-white w3-hover-white w3-margin w3-round w3-right" onclick="if(confirm(\'Are you sure?\')){ SaveMoveRoomRate('+$rate_id+'); }">';
                                $content += 'Save';
                            $content += '</div>';
                            $content += '<div class="w3-button w3-white w3-hover-white w3-margin w3-round w3-right" onclick="CloseLightBox();">';
                                $content += 'Cancel';
                            $content += '</div>';
                        $content += '</div>';
                    $content += '</div>';
                    document.getElementById('LightBoxCentralContent').innerHTML = $content;
                    OpenLightBox();
                }else{
                    alert('nonthing!');
                }
                CloseLoading();
			}
          });
    }
    function SaveRateOverrides(){
        $check = true;
        $messenge = '';
        if(jQuery("#from_date").val()==''){
            $check = false;
            $messenge += 'From Date is required \n';
        }
        if(jQuery("#to_date").val()==''){
            $check = false;
            $messenge += 'To Date is required \n';
        }
        if(jQuery("#daily_rate").val()==''){
            $check = false;
            $messenge += 'Adjust daily rates by is required \n';
        }else{
            if(to_numeric(jQuery("#daily_rate").val())==2){
                if(jQuery("#percent_adjust").val()==''){
                    $check = false;
                    $messenge += 'Percentage adjustment is required \n';
                }
            }else if(to_numeric(jQuery("#daily_rate").val())==3){
                if(jQuery("#amount_adjust").val()==''){
                    $check = false;
                    $messenge += 'Amount adjustment is required \n';
                }
            }else if(to_numeric(jQuery("#daily_rate").val())==4){
                if(jQuery("#amount_adjust").val()==''){
                    $check = false;
                    $messenge += 'Amount adjustment is required \n';
                }
                if(jQuery("#percent_adjust").val()==''){
                    $check = false;
                    $messenge += 'Percentage adjustment is required \n';
                }
            }else if(to_numeric(jQuery("#daily_rate").val())==5){
                if(jQuery("#percent_adjust").val()==''){
                    $check = false;
                    $messenge += 'Percentage adjustment is required \n';
                }
                if(jQuery("#amount_adjust").val()==''){
                    $check = false;
                    $messenge += 'Amount adjustment is required \n';
                }
            }
        }
        
        if(!$check){
            alert($messenge);
        }else{
            OpenLoading();
            var form_data = new FormData();
            $amount_adjust = '';
            $amount_inc = 1;
            $amount_dec = 0;
            $percent_adjust = '';
            $percent_inc = 1;
            $percent_dec = 0;
            if(to_numeric(jQuery("#daily_rate").val())==2){
                $percent_adjust = jQuery("#percent_adjust").val();
                $percent_inc = document.getElementById('percent_inc').checked==true?1:0;
                $percent_dec = document.getElementById('percent_dec').checked==true?1:0;
            }else if(to_numeric(jQuery("#daily_rate").val())==3){
                $amount_adjust = jQuery("#amount_adjust").val();
                $amount_inc = document.getElementById('amount_inc').checked==true?1:0;
                $amount_dec = document.getElementById('amount_dec').checked==true?1:0;
            }else if(to_numeric(jQuery("#daily_rate").val())==4 || to_numeric(jQuery("#daily_rate").val())==5){
                $amount_adjust = jQuery("#amount_adjust").val();
                $amount_inc = document.getElementById('amount_inc').checked==true?1:0;
                $amount_dec = document.getElementById('amount_dec').checked==true?1:0;
                $percent_adjust = jQuery("#percent_adjust").val();
                $percent_inc = document.getElementById('percent_inc').checked==true?1:0;
                $percent_dec = document.getElementById('percent_dec').checked==true?1:0;
            }
            $RateOverrideId = jQuery("#RateOverrideId").val();
            $type = jQuery("#RateOverrideId").val()!=''?'EDIT':'ADD';
            $rate_id = jQuery("#RateId").val();
            
            form_data.append('status', 'SAVERATEOVER');
            form_data.append('act', $type);
            form_data.append('RateOverrideId', $RateOverrideId);
            form_data.append('from_date', jQuery("#from_date").val());
            form_data.append('to_date', jQuery("#to_date").val());
            form_data.append('daily_rate', jQuery("#daily_rate").val());
            form_data.append('amount_adjust', $amount_adjust);
            form_data.append('amount_inc', $amount_inc);
            form_data.append('amount_dec', $amount_dec);
            form_data.append('percent_adjust', $percent_adjust);
            form_data.append('percent_inc', $percent_inc);
            form_data.append('percent_dec', $percent_dec);
            form_data.append('rate_id', $rate_id);
            jQuery.ajax({
    			url:"form.php?block_id="+<?php echo Module::block_id();?>,
                cache: false,
                contentType: false,
                processData: false,
    			type:"POST",
    			data:form_data,
    			success:function(html)
                {
                    alert(html);
                    CloseLoading();
                    if(html=='Success!'){
                        RateOverride($rate_id);
                    }
    			}
              });
        }
    }
    function SaveRateOtaOverrides(){
        $check = true;
        $messenge = '';
        if(jQuery("#from_date").val()==''){
            $check = false;
            $messenge += 'From Date is required \n';
        }
        if(jQuery("#to_date").val()==''){
            $check = false;
            $messenge += 'To Date is required \n';
        }
        if(jQuery("#daily_rate").val()==''){
            $check = false;
            $messenge += 'Adjust daily rates by is required \n';
        }else{
            if(to_numeric(jQuery("#daily_rate").val())==2){
                if(jQuery("#percent_adjust").val()==''){
                    $check = false;
                    $messenge += 'Percentage adjustment is required \n';
                }
            }else if(to_numeric(jQuery("#daily_rate").val())==3){
                if(jQuery("#amount_adjust").val()==''){
                    $check = false;
                    $messenge += 'Amount adjustment is required \n';
                }
            }else if(to_numeric(jQuery("#daily_rate").val())==4){
                if(jQuery("#amount_adjust").val()==''){
                    $check = false;
                    $messenge += 'Amount adjustment is required \n';
                }
                if(jQuery("#percent_adjust").val()==''){
                    $check = false;
                    $messenge += 'Percentage adjustment is required \n';
                }
            }else if(to_numeric(jQuery("#daily_rate").val())==5){
                if(jQuery("#percent_adjust").val()==''){
                    $check = false;
                    $messenge += 'Percentage adjustment is required \n';
                }
                if(jQuery("#amount_adjust").val()==''){
                    $check = false;
                    $messenge += 'Amount adjustment is required \n';
                }
            }
        }
        
        if(!$check){
            alert($messenge);
        }else{
            OpenLoading();
            var form_data = new FormData();
            $amount_adjust = '';
            $amount_inc = 1;
            $amount_dec = 0;
            $percent_adjust = '';
            $percent_inc = 1;
            $percent_dec = 0;
            if(to_numeric(jQuery("#daily_rate").val())==2){
                $percent_adjust = jQuery("#percent_adjust").val();
                $percent_inc = document.getElementById('percent_inc').checked==true?1:0;
                $percent_dec = document.getElementById('percent_dec').checked==true?1:0;
            }else if(to_numeric(jQuery("#daily_rate").val())==3){
                $amount_adjust = jQuery("#amount_adjust").val();
                $amount_inc = document.getElementById('amount_inc').checked==true?1:0;
                $amount_dec = document.getElementById('amount_dec').checked==true?1:0;
            }else if(to_numeric(jQuery("#daily_rate").val())==4 || to_numeric(jQuery("#daily_rate").val())==5){
                $amount_adjust = jQuery("#amount_adjust").val();
                $amount_inc = document.getElementById('amount_inc').checked==true?1:0;
                $amount_dec = document.getElementById('amount_dec').checked==true?1:0;
                $percent_adjust = jQuery("#percent_adjust").val();
                $percent_inc = document.getElementById('percent_inc').checked==true?1:0;
                $percent_dec = document.getElementById('percent_dec').checked==true?1:0;
            }
            $RateOverrideId = jQuery("#RateOverrideId").val();
            $type = jQuery("#RateOverrideId").val()!=''?'EDIT':'ADD';
            $rate_ota_id = jQuery("#RateOtaId").val();
            
            form_data.append('status', 'SAVERATEOTAOVER');
            form_data.append('act', $type);
            form_data.append('RateOverrideId', $RateOverrideId);
            form_data.append('from_date', jQuery("#from_date").val());
            form_data.append('to_date', jQuery("#to_date").val());
            form_data.append('daily_rate', jQuery("#daily_rate").val());
            form_data.append('amount_adjust', $amount_adjust);
            form_data.append('amount_inc', $amount_inc);
            form_data.append('amount_dec', $amount_dec);
            form_data.append('percent_adjust', $percent_adjust);
            form_data.append('percent_inc', $percent_inc);
            form_data.append('percent_dec', $percent_dec);
            form_data.append('rate_ota_id', $rate_ota_id);
            jQuery.ajax({
        			url:"form.php?block_id="+<?php echo Module::block_id();?>,
                    cache: false,
                    contentType: false,
                    processData: false,
        			type:"POST",
        			data:form_data,
        			success:function(html)
                    {
                        alert(html);
                        CloseLoading();
                        if(html=='Success!'){
                            ChannelOverride($rate_ota_id);
                        }
        			}
              });
        }
    }
    function GetRateOverrides($type,$from_date,$to_date,$daily_rate,$amount_inc,$amount_dec,$amount_adjust,$percent_inc,$percent_dec,$percent_adjust,$rate_over_id){
        jQuery("#RateOverrideId").val('');
        jQuery("#from_date").val('');
        jQuery("#to_date").val('');
        jQuery("#daily_rate").val('');
        SelectDailyRate('DerivedRateOverrides');
        if($type=='EDIT'){
            jQuery("#RateOverrideId").val($rate_over_id);
            jQuery("#from_date").val($from_date);
            jQuery("#to_date").val($to_date);
            jQuery("#daily_rate").val($daily_rate);
            $content = '';
            if($daily_rate!=''){
                if(to_numeric($daily_rate)==2){
                    $content += CreateElementPercentageAdjust($percent_inc,$percent_dec,$percent_adjust);
                }else if(to_numeric($daily_rate)==3){
                    $content += CreateElementAmountAdjust($amount_inc,$amount_dec,$amount_adjust);
                }else if(to_numeric($daily_rate)==4){
                    $content += CreateElementAmountAdjust($amount_inc,$amount_dec,$amount_adjust);
                    $content += CreateElementPercentageAdjust($percent_inc,$percent_dec,$percent_adjust);
                }else if(to_numeric($daily_rate)==5){
                    $content += CreateElementPercentageAdjust($percent_inc,$percent_dec,$percent_adjust);
                    $content += CreateElementAmountAdjust($amount_inc,$amount_dec,$amount_adjust);
                }
            }
            jQuery("#DerivedRateOverrides").append($content);
        }
    }
    function ChannelOverride($rate_ota_id){
        OpenLoading();
        jQuery.ajax({
			url:"form.php?block_id="+<?php echo Module::block_id();?>,
			type:"POST",
			data:{status:'GETRATEOTAOVER',id:$rate_ota_id},
			success:function(html)
            {
                $data = jQuery.parseJSON(html);
                $content = '<div class="w3-container">';
                    $content += '<div class="w3-row w3-padding w3-text-cyan"><h3>Derived Rate Overrides '+$data['name']+'</h3> <p>'+$data['type_name']+' / '+$data['rate_name']+'</p></div>';
                    $content += '<hr />';
                    $content += '<div class="w3-round w3-padding">';
                        $content += '<table style="width: 100%;" cellspacing="5" cellpadding="5" border="1" bordercolor="#CCC">';
                            $content += '<tr>';
                                $content += '<th style="width: 80px;">From Date</th>';
                                $content += '<th style="width: 80px;">To Date</th>';
                                $content += '<th style="width: 200px;">Override rates by</th>';
                                $content += '<th></th>';
                            $content += '</tr>';
                            for(var i in $data['rate_over']){
                                $content += '<tr>';
                                    $content += '<td>'+$data['rate_over'][i]['from_date']+'</td>';
                                    $content += '<td>'+$data['rate_over'][i]['to_date']+'</td>';
                                    $content += '<td>'+$data['rate_over'][i]['description']+'</td>';
                                    $content += '<td style="text-align: center;">';
                                        $content += '<div class="w3-button w3-blue w3-round" onclick="GetRateOverrides(\'EDIT\',\''+$data['rate_over'][i]['from_date']+'\',\''+$data['rate_over'][i]['to_date']+'\','+$data['rate_over'][i]['daily_rate']+','+$data['rate_over'][i]['amount_inc']+','+$data['rate_over'][i]['amount_dec']+','+$data['rate_over'][i]['amount_adjust']+','+$data['rate_over'][i]['percent_inc']+','+$data['rate_over'][i]['percent_dec']+','+$data['rate_over'][i]['percent_adjust']+','+$data['rate_over'][i]['id']+');">';
                                            $content += 'Edit';
                                        $content += '</div>';
                                        $content += '<div class="w3-button w3-pink w3-round" onclick="if(confirm(\' Are You Sure? \')){ DeleteRateOtaOverrides('+$data['rate_over'][i]['id']+'); }">';
                                            $content += 'Delete';
                                        $content += '</div>';
                                    $content += '</td>';
                                $content += '</tr>';
                            }
                        $content += '</table>';
                        $content += '<input id="RateOverrideId" type="hidden" />';
                        $content += '<input id="RateOtaId" type="hidden" value="'+$rate_ota_id+'" />';
                        $content += '<hr />';
                        $content += '<table id="DerivedRateOverrides" style="width: 100%;" cellspacing="5" cellpadding="5" >';
                            $content += '<tr>';
                                $content += '<td>';
                                    $content += '<label for="from_date">From date *</label>';
                                    $content += '<input id="from_date" class="w3-input w3-border" readonly="" />';
                                $content += '</td>';
                                $content += '<td>';
                                    $content += '<label for="to_date">To date *</label>';
                                    $content += '<input id="to_date" class="w3-input w3-border" readonly="" />';
                                $content += '</td>';
                            $content += '</tr>';
                            $content += '<tr>';
                                $content += '<td colspan="2">';
                                    $content += '<label>Override daily rates by *</label>';
                                    $content += '<select id="daily_rate" onchange="SelectDailyRate(\'DerivedRateOverrides\');" class="w3-input w3-border">';
                                        $content += '<option value="">Please select a value</option>';
                                        $content += '<option value="2">Percentage</option>';
                                        $content += '<option value="3">Amount</option>';
                                        $content += '<option value="4">Amount then percentage</option>';
                                        $content += '<option value="5">Percentage then amount</option>';
                                    $content += '</select>';
                                $content += '</td>';
                            $content += '</tr>';
                        $content += '</table>';
                        $content += '<table id="DerivedRateOverrides" style="width: 100%;" cellspacing="5" cellpadding="5" >';
                            $content += '<tr>';
                                $content += '<td style="text-align: right;">';
                                    $content += '<div class="w3-button w3-teal w3-margin w3-round w3-right" onclick="SaveRateOtaOverrides();">';
                                        $content += 'Save';
                                    $content += '</div>';
                                $content += '</td>';
                            $content += '</tr>';
                        $content += '</table>';
                    $content += '</div>';
                    $content += '<hr />';
                    $content += '<div class="w3-row w3-padding">';
                        $content += '<div class="w3-button w3-white w3-hover-white w3-margin w3-border w3-round w3-right" onclick="GetRateOverrides(\'ADD\',\'\',\'\',\'\',\'\',\'\',\'\',\'\',\'\',\'\',\'\');">';
                            $content += 'Add Rate Overrides';
                        $content += '</div>';
                        $content += '<div class="w3-button w3-white w3-hover-white w3-margin w3-round w3-right" onclick="CloseLightBox();">';
                            $content += 'Cancel';
                        $content += '</div>';
                    $content += '</div>';
                $content += '</div>';
                document.getElementById('LightBoxCentralContent').innerHTML = $content;
                jQuery("#from_date").datepicker();
                jQuery("#to_date").datepicker();
                OpenLightBox();
                CloseLoading();
			}
          });
    }
    function RateOverride($rate_id){
        OpenLoading();
        jQuery.ajax({
			url:"form.php?block_id="+<?php echo Module::block_id();?>,
			type:"POST",
			data:{status:'GETROOMRATEOVER',id:$rate_id},
			success:function(html)
            {
                $data = jQuery.parseJSON(html);
                $content = '<div class="w3-container">';
                    $content += '<div class="w3-row w3-padding w3-text-cyan"><h3>Derived Rate Overrides '+$data['type_name']+'</h3> <p>'+$data['type_name']+' / '+$data['rate_name']+'</p></div>';
                    $content += '<hr />';
                    $content += '<div class="w3-round w3-padding">';
                        $content += '<table style="width: 100%;" cellspacing="5" cellpadding="5" border="1" bordercolor="#CCC">';
                            $content += '<tr>';
                                $content += '<th style="width: 80px;">From Date</th>';
                                $content += '<th style="width: 80px;">To Date</th>';
                                $content += '<th style="width: 200px;">Override rates by</th>';
                                $content += '<th></th>';
                            $content += '</tr>';
                            for(var i in $data['rate_over']){
                                $content += '<tr>';
                                    $content += '<td>'+$data['rate_over'][i]['from_date']+'</td>';
                                    $content += '<td>'+$data['rate_over'][i]['to_date']+'</td>';
                                    $content += '<td>'+$data['rate_over'][i]['description']+'</td>';
                                    $content += '<td style="text-align: center;">';
                                        $content += '<div class="w3-button w3-blue w3-round" onclick="GetRateOverrides(\'EDIT\',\''+$data['rate_over'][i]['from_date']+'\',\''+$data['rate_over'][i]['to_date']+'\','+$data['rate_over'][i]['daily_rate']+','+$data['rate_over'][i]['amount_inc']+','+$data['rate_over'][i]['amount_dec']+','+$data['rate_over'][i]['amount_adjust']+','+$data['rate_over'][i]['percent_inc']+','+$data['rate_over'][i]['percent_dec']+','+$data['rate_over'][i]['percent_adjust']+','+$data['rate_over'][i]['id']+');">';
                                            $content += 'Edit';
                                        $content += '</div>';
                                        $content += '<div class="w3-button w3-pink w3-round" onclick="if(confirm(\' Are You Sure? \')){ DeleteRateOverrides('+$data['rate_over'][i]['id']+'); }">';
                                            $content += 'Delete';
                                        $content += '</div>';
                                    $content += '</td>';
                                $content += '</tr>';
                            }
                        $content += '</table>';
                        $content += '<input id="RateOverrideId" type="hidden" />';
                        $content += '<input id="RateId" type="hidden" value="'+$rate_id+'" />';
                        $content += '<hr />';
                        $content += '<table id="DerivedRateOverrides" style="width: 100%;" cellspacing="5" cellpadding="5" >';
                            $content += '<tr>';
                                $content += '<td>';
                                    $content += '<label for="from_date">From date *</label>';
                                    $content += '<input id="from_date" class="w3-input w3-border" readonly="" />';
                                $content += '</td>';
                                $content += '<td>';
                                    $content += '<label for="to_date">To date *</label>';
                                    $content += '<input id="to_date" class="w3-input w3-border" readonly="" />';
                                $content += '</td>';
                            $content += '</tr>';
                            $content += '<tr>';
                                $content += '<td colspan="2">';
                                    $content += '<label>Override daily rates by *</label>';
                                    $content += '<select id="daily_rate" onchange="SelectDailyRate(\'DerivedRateOverrides\');" class="w3-input w3-border">';
                                        $content += '<option value="">Please select a value</option>';
                                        $content += '<option value="2">Percentage</option>';
                                        $content += '<option value="3">Amount</option>';
                                        $content += '<option value="4">Amount then percentage</option>';
                                        $content += '<option value="5">Percentage then amount</option>';
                                    $content += '</select>';
                                $content += '</td>';
                            $content += '</tr>';
                        $content += '</table>';
                        $content += '<table id="DerivedRateOverrides" style="width: 100%;" cellspacing="5" cellpadding="5" >';
                            $content += '<tr>';
                                $content += '<td style="text-align: right;">';
                                    $content += '<div class="w3-button w3-teal w3-margin w3-round w3-right" onclick="SaveRateOverrides();">';
                                        $content += 'Save';
                                    $content += '</div>';
                                $content += '</td>';
                            $content += '</tr>';
                        $content += '</table>';
                    $content += '</div>';
                    $content += '<hr />';
                    $content += '<div class="w3-row w3-padding">';
                        $content += '<div class="w3-button w3-white w3-hover-white w3-margin w3-border w3-round w3-right" onclick="GetRateOverrides(\'ADD\',\'\',\'\',\'\',\'\',\'\',\'\',\'\',\'\',\'\',\'\');">';
                            $content += 'Add Rate Overrides';
                        $content += '</div>';
                        $content += '<div class="w3-button w3-white w3-hover-white w3-margin w3-round w3-right" onclick="CloseLightBox();">';
                            $content += 'Cancel';
                        $content += '</div>';
                    $content += '</div>';
                $content += '</div>';
                document.getElementById('LightBoxCentralContent').innerHTML = $content;
                jQuery("#from_date").datepicker();
                jQuery("#to_date").datepicker();
                OpenLightBox();
                CloseLoading();
			}
          });
    }
    function DeleteRateOtaOverrides($id){
        OpenLoading();
        jQuery.ajax({
			url:"form.php?block_id="+<?php echo Module::block_id();?>,
			type:"POST",
			data:{status:'DELETERATEOTAOVER',id:$id},
			success:function(html)
            {
                alert(html);
                CloseLoading();
                CloseLightBox();
                location.reload();
			}
          });
    }
    function DeleteRateOverrides($id){
        OpenLoading();
        jQuery.ajax({
			url:"form.php?block_id="+<?php echo Module::block_id();?>,
			type:"POST",
			data:{status:'DELETERATEOVER',id:$id},
			success:function(html)
            {
                alert(html);
                CloseLoading();
                CloseLightBox();
                location.reload();
			}
          });
    }
    function DeleteRoomRate($id){
        OpenLoading();
        jQuery.ajax({
			url:"form.php?block_id="+<?php echo Module::block_id();?>,
			type:"POST",
			data:{status:'DELETEROOMRATE',id:$id},
			success:function(html)
            {
                alert(html);
                CloseLoading();
                CloseLightBox();
                location.reload();
			}
          });
    }
    function SaveRoomRate($type,$type_id,$rate_id){
        $check = true;
        $messenge = '';
        if(jQuery("#rate_plan_code").val()==''){
            $check = false;
            $messenge += 'Rate Plan Code is required \n';
        }
        if(jQuery("#rate_name").val()==''){
            $check = false;
            $messenge += 'Room Rate Name is required \n';
        }
        if(document.getElementById('rate_config_manual').checked==true){
            if(jQuery("#manual_rack_rate").val()==''){
                $check = false;
                $messenge += 'Rack Rate is required \n';
            }
        }else{
            if(jQuery("#derive_from_rate_id").val()==''){
                $check = false;
                $messenge += 'Derive from is required \n';
            }else{
                if(jQuery("#daily_rate").val()==''){
                    $check = false;
                    $messenge += 'Adjust daily rates by is required \n';
                }else{
                    if(to_numeric(jQuery("#daily_rate").val())==2){
                        if(jQuery("#percent_adjust").val()==''){
                            $check = false;
                            $messenge += 'Percentage adjustment is required \n';
                        }
                    }else if(to_numeric(jQuery("#daily_rate").val())==3){
                        if(jQuery("#amount_adjust").val()==''){
                            $check = false;
                            $messenge += 'Amount adjustment is required \n';
                        }
                    }else if(to_numeric(jQuery("#daily_rate").val())==4){
                        if(jQuery("#amount_adjust").val()==''){
                            $check = false;
                            $messenge += 'Amount adjustment is required \n';
                        }
                        if(jQuery("#percent_adjust").val()==''){
                            $check = false;
                            $messenge += 'Percentage adjustment is required \n';
                        }
                    }else if(to_numeric(jQuery("#daily_rate").val())==5){
                        if(jQuery("#percent_adjust").val()==''){
                            $check = false;
                            $messenge += 'Percentage adjustment is required \n';
                        }
                        if(jQuery("#amount_adjust").val()==''){
                            $check = false;
                            $messenge += 'Amount adjustment is required \n';
                        }
                    }
                }
            }
        }
        
        if(!$check){
            alert($messenge);
        }else{
            OpenLoading();
            var form_data = new FormData();
            $amount_adjust = '';
            $amount_inc = 1;
            $amount_dec = 0;
            $percent_adjust = '';
            $percent_inc = 1;
            $percent_dec = 0;
            if(document.getElementById('rate_config_derive').checked==true){
                if(to_numeric(jQuery("#daily_rate").val())==2){
                    $percent_adjust = jQuery("#percent_adjust").val();
                    $percent_inc = document.getElementById('percent_inc').checked==true?1:0;
                    $percent_dec = document.getElementById('percent_dec').checked==true?1:0;
                }else if(to_numeric(jQuery("#daily_rate").val())==3){
                    $amount_adjust = jQuery("#amount_adjust").val();
                    $amount_inc = document.getElementById('amount_inc').checked==true?1:0;
                    $amount_dec = document.getElementById('amount_dec').checked==true?1:0;
                }else if(to_numeric(jQuery("#daily_rate").val())==4 || to_numeric(jQuery("#daily_rate").val())==5){
                    $amount_adjust = jQuery("#amount_adjust").val();
                    $amount_inc = document.getElementById('amount_inc').checked==true?1:0;
                    $amount_dec = document.getElementById('amount_dec').checked==true?1:0;
                    $percent_adjust = jQuery("#percent_adjust").val();
                    $percent_inc = document.getElementById('percent_inc').checked==true?1:0;
                    $percent_dec = document.getElementById('percent_dec').checked==true?1:0;
                }
            }
            $availability = 'LINKED';
            if(document.getElementById('availability_linked').checked==true)
                $availability = 'LINKED';
            else{
                if(document.getElementById('availability_managed').checked==true)
                    $availability = 'MANAGED';
            }
            form_data.append('status', 'SAVEROOMRATE');
            form_data.append('act', $type);
            form_data.append('room_type_id', $type_id);
            form_data.append('rate_plan_code', jQuery("#rate_plan_code").val());
            form_data.append('rate_name', jQuery("#rate_name").val());
            form_data.append('availability', $availability);
            form_data.append('auto_set_avail', (document.getElementById('auto_set_avail').checked==true?1:0));
            form_data.append('overbook_quantity', jQuery("#overbook_quantity").val());
            form_data.append('default_min_stay', jQuery("#default_min_stay").val());
            form_data.append('default_stop_sell', (document.getElementById('default_stop_sell').checked==true?1:0));
            form_data.append('rate_config_manual', (document.getElementById('rate_config_manual').checked==true?1:0));
            form_data.append('manual_rack_rate', (document.getElementById('rate_config_manual').checked==true?jQuery("#manual_rack_rate").val():''));
            form_data.append('rate_config_derive', (document.getElementById('rate_config_derive').checked==true?1:0));
            form_data.append('derive_from_rate_id', (document.getElementById('rate_config_derive').checked==true?jQuery("#derive_from_rate_id").val():''));
            form_data.append('daily_rate', (document.getElementById('rate_config_derive').checked==true?jQuery("#daily_rate").val():''));
            form_data.append('amount_adjust', $amount_adjust);
            form_data.append('amount_inc', $amount_inc);
            form_data.append('amount_dec', $amount_dec);
            form_data.append('percent_adjust', $percent_adjust);
            form_data.append('percent_inc', $percent_inc);
            form_data.append('percent_dec', $percent_dec);
            form_data.append('rate_id', ($type=='ADD'?'':$rate_id));
            if($type=='ADD'){
                $start_date = jQuery('#start_date').val();
                $end_date = jQuery('#end_date').val();
            }else{
                $start_date = '';
                $end_date = '';
            }
            form_data.append('start_date', $start_date);
            form_data.append('end_date', $end_date);
            jQuery.ajax({
    			url:"form.php?block_id="+<?php echo Module::block_id();?>,
                cache: false,
                contentType: false,
                processData: false,
    			type:"POST",
    			data:form_data,
    			success:function(html)
                {
                    alert(html);
                    CloseLoading();
                    CloseLightBox();
                    location.reload();
    			}
              });
        }
        
    }
    function EditRoomRate($type_name,$type_id,$rate_plan_code,$rate_name,$availability,$default_min_stay,$default_stop_sell,$rate_config_manual,$manual_rack_rate,$rate_config_derive,$derive_from_rate_id,$daily_rate,$amount_inc,$amount_dec,$amount_adjust,$percent_inc,$percent_dec,$percent_adjust,$rate_id,$auto_set_avail,$overbook_quantity){
        OpenLoading();
        jQuery.ajax({
			url:"form.php?block_id="+<?php echo Module::block_id();?>,
			type:"POST",
			data:{status:'GETITEMSRATE',rate_id:$rate_id},
			success:function(html)
            {
                $data = jQuery.parseJSON(html);
                $content = CreateElementRoomRate($type_name,$type_id,$rate_plan_code,$rate_name,$availability,$default_min_stay,$default_stop_sell,$rate_config_manual,$manual_rack_rate,$rate_config_derive,$derive_from_rate_id,$daily_rate,$amount_inc,$amount_dec,$amount_adjust,$percent_inc,$percent_dec,$percent_adjust,$rate_id,$data,$auto_set_avail,$overbook_quantity);
                document.getElementById('LightBoxCentralContent').innerHTML = $content;
                OpenLightBox();
                CloseLoading();
			}
          });
    }
    function AddNewRoomRate($type_name,$type_id){
        OpenLoading();
        jQuery.ajax({
    			url:"form.php?block_id="+<?php echo Module::block_id();?>,
    			type:"POST",
    			data:{status:'GETITEMSRATE',rate_id:''},
    			success:function(html)
                {
                    $data = jQuery.parseJSON(html);
                    $content = CreateElementRoomRate($type_name,$type_id,'','','LINKED','',0,1,'',0,'','',1,0,'',1,0,'',false,$data,0,'');
                    document.getElementById('LightBoxCentralContent').innerHTML = $content;
                    jQuery("#start_date").datepicker();
                    jQuery("#end_date").datepicker();
                    OpenLightBox();
                    CloseLoading();
    			}
          });
    }
    function SelectDailyRate($parent_id){
        jQuery(".PercentageAdjust").remove();
        jQuery(".AmountAdjust").remove();
        $content = '';
        if(jQuery("#daily_rate").val()!=''){
            if(to_numeric(jQuery("#daily_rate").val())==2){
                $content += CreateElementPercentageAdjust(true,false,'');
            }else if(to_numeric(jQuery("#daily_rate").val())==3){
                $content += CreateElementAmountAdjust(true,false,'');
            }else if(to_numeric(jQuery("#daily_rate").val())==4){
                $content += CreateElementAmountAdjust(true,false,'');
                $content += CreateElementPercentageAdjust(true,false,'');
            }else if(to_numeric(jQuery("#daily_rate").val())==5){
                $content += CreateElementPercentageAdjust(true,false,'');
                $content += CreateElementAmountAdjust(true,false,'');
            }
        }
        jQuery("#"+$parent_id).append($content);
    }
    function ChooseRease($type,$code){
        document.getElementById($type+'_'+$code).checked = true;
        if($code=='inc'){
            document.getElementById($type+'_dec').checked = false;
        }else{
            document.getElementById($type+'_inc').checked = false;
        }
    }
    function CreateElementRoomRate($type_name,$type_id,$rate_plan_code,$rate_name,$availability,$default_min_stay,$default_stop_sell,$rate_config_manual,$manual_rack_rate,$rate_config_derive,$derive_from_rate_id,$daily_rate,$amount_inc,$amount_dec,$amount_adjust,$percent_inc,$percent_dec,$percent_adjust,$rate_id,$data,$auto_set_avail,$overbook_quantity){
        
        $content = '<div class="w3-container">';
        $content += '<div class="w3-row w3-padding w3-text-cyan"><h3>Configure Room Rate to '+$type_name+'</h3></div>';
        $content += '<hr />';
        $content += '<div class="w3-round w3-padding">';
            $content += '<table id="ConfigRoomRateContent" style="width: 100%;" cellpadding="5" cellspacing="5">';
                $content += '<tr>';
                    $content += '<td colspan="2"><b>General</b></td>';
                $content += '</tr>';
                $content += '<tr>';
                    $content += '<td style="text-align: right; width: 50%;"><b>Room Type</b></td>';
                    $content += '<td>'+$type_name+'</td>';
                $content += '</tr>';
                $content += '<tr>';
                    $content += '<td style="text-align: right;"><b>Rate Plan Code *</b></td>';
                    $content += '<td><input id="rate_plan_code" name="rate_plan_code" type="text" value="'+$rate_plan_code+'" class="w3-input w3-border" style="width: 200px;" /></td>';
                $content += '</tr>';
                $content += '<tr>';
                    $content += '<td style="text-align: right;"><b>Room Rate Name *</b></td>';
                    $content += '<td><input id="rate_name" name="rate_name" type="text" value="'+$rate_name+'" class="w3-input w3-border" /></td>';
                $content += '</tr>';
                $content += '<tr>';
                    $content += '<td style="text-align: right;"><b>Availability</b></td>';
                    $content += '<td><input onclick="jQuery(\'.RateAvailBox\').css(\'display\',\'none\');" type="radio" id="availability_linked" name="vailability" value="LINKED" '+($availability!='MANAGED'?'checked="checked"':'')+'/> Linked to Room Type ';
                    $content += '<input onclick="jQuery(\'.RateAvailBox\').css(\'display\',\'\');" type="radio" id="availability_managed" name="vailability" value="MANAGED" '+($availability!='MANAGED'?'':'checked="checked"')+'/> Managed Independently </td>';
                $content += '</tr>';
                $content += '<tr class="RateAvailBox" '+($availability!='MANAGED'?'style="display: none;"':'style="display: none;"')+'>';
                    $content += '<td style="text-align: right;"><b>Auto Set Avail:</b><br/><span><i>Luôn cập nhập lại số lượng phòng trống ban đầu</i></span></td>';
                    $content += '<td><input type="checkbox" id="auto_set_avail" '+($auto_set_avail==1?'':'')+'/></td>';
                $content += '</tr>';
                $content += '<tr class="RateAvailBox" '+($availability!='MANAGED'?'style="display: none;"':'style="display: none;"')+'>';
                    $content += '<td style="text-align: right;"><b>Overbook Quantity:</b><br/><span><i>Số lượng phòng trống đảm bảo tính khả dụng</i></span></td>';
                    $content += '<td><input id="overbook_quantity" value="'+$overbook_quantity+'" type="number" class="w3-input w3-border" style="width: 80px;" /></td>';
                $content += '</tr>';
                $content += '<tr>';
                    $content += '<td style="text-align: right;"><b>Default Min Stay</b></td>';
                    $content += '<td><input id="default_min_stay" name="default_min_stay" type="text" value="'+$default_min_stay+'" class="w3-input w3-border input_number" style="width: 100px;" /></td>';
                $content += '</tr>';
                $content += '<tr>';
                    $content += '<td style="text-align: right;"><b>Default Stop Sell</b></td>';
                    $content += '<td><input id="default_stop_sell" name="default_stop_sell" type="checkbox" '+($default_stop_sell==1?'checked="checked"':'')+' /></td>';
                $content += '</tr>';
                $content += '<tr>';
                    $content += '<td colspan="2"><b>Rate Setup</b></td>';
                $content += '</tr>';
                $content += '<tr>';
                    $content += '<td style="text-align: right;"><b>Manually input daily rates</b></td>';
                    $content += '<td><input id="rate_config_manual" name="rate_config_manual" type="checkbox" '+($rate_config_derive==0?'checked="checked"':'')+' onclick="ManuallyDeriveConfig(\'MANUALLY\');" /></td>';
                $content += '</tr>';
                $content += '<tr class="ManuallyInputDailyRates" '+($rate_config_derive==1?'style="display: none;"':'')+'>';
                    $content += '<td style="text-align: right;"><b>Rack Rate *</b></td>';
                    $content += '<td><input id="manual_rack_rate" name="manual_rack_rate" type="text" value="'+$manual_rack_rate+'" class="w3-input w3-border input_number" style="width: 100px;" /></td>';
                $content += '</tr>';
                $content += '<tr>';
                    $content += '<td style="text-align: right;"><b>Derive daily rates from another Room Rate</b></td>';
                    $content += '<td><input id="rate_config_derive" name="rate_config_derive" type="checkbox" '+($rate_config_derive==1?'checked="checked"':'')+' onclick="ManuallyDeriveConfig(\'DERIVE\');" /></td>';
                $content += '</tr>';
                $content += '<tr class="DeriveDailyRatesFromAnotherRoomRate" '+($rate_config_derive==0?'style="display: none;"':'')+'>';
                    $content += '<td style="text-align: right;"><b>Derive from *</b></td>';
                    $content += '<td>';
                        $content += '<select  name="derive_from_rate_id" id="derive_from_rate_id" onchange="SelectDeriveFrom();" class="w3-input w3-border">';
                            $content += '<option value="">Please select a value</option>';
                            for(var i in $data){
                                $content += '<optgroup label="'+$data[i]['type_name']+'">';
                                    for(var j in $data[i]['child']){
                                        if(!$rate_id || ($rate_id && $rate_id!=$data[i]['child'][j]['id']))
                                            $content += '<option '+($derive_from_rate_id==$data[i]['child'][j]['id']?'selected="selected"':'')+' label="'+$data[i]['type_name']+' / '+$data[i]['child'][j]['rate_name']+'" value="'+$data[i]['child'][j]['id']+'">'+$data[i]['type_name']+' / '+$data[i]['child'][j]['rate_name']+'</option>';
                                    }
                                $content += '</optgroup>';
                            }
                        $content += '</select>';
                    $content += '</td>';
                $content += '</tr>';
                $content += '<tr class="AdjustDailyRatesBy" '+($rate_config_derive==0?'style="display: none;"':'')+'>';
                    $content += '<td style="text-align: right;"><b>Adjust daily rates by *</b></td>';
                    $content += '<td>';
                        $content += '<select  name="daily_rate" id="daily_rate" onchange="SelectDailyRate(\'ConfigRoomRateContent\');" class="w3-input w3-border">';
                            $content += '<option value="">Please select a value</option>';
                            $content += '<option value="1" '+($daily_rate==1?'selected="selcted"':'')+'>Keep rates the same</option>';
                            $content += '<option value="2" '+($daily_rate==2?'selected="selcted"':'')+'>Percentage</option>';
                            $content += '<option value="3" '+($daily_rate==3?'selected="selcted"':'')+'>Amount</option>';
                            $content += '<option value="4" '+($daily_rate==4?'selected="selcted"':'')+'>Amount then percentage</option>';
                            $content += '<option value="5" '+($daily_rate==5?'selected="selcted"':'')+'>Percentage then amount</option>';
                        $content += '</select>';
                    $content += '</td>';
                $content += '</tr>';
                if($rate_config_derive==1){
                    if($daily_rate==2){
                        $content += CreateElementPercentageAdjust($percent_inc,$percent_dec,$percent_adjust);
                    }else if($daily_rate==3){
                        $content += CreateElementAmountAdjust($amount_inc,$amount_dec,$amount_adjust);
                    }else if($daily_rate==4){
                        $content += CreateElementAmountAdjust($amount_inc,$amount_dec,$amount_adjust);
                        $content += CreateElementPercentageAdjust($percent_inc,$percent_dec,$percent_adjust);
                    }else if($daily_rate==5){
                        $content += CreateElementPercentageAdjust($percent_inc,$percent_dec,$percent_adjust);
                        $content += CreateElementAmountAdjust($amount_inc,$amount_dec,$amount_adjust);
                    }
                }
            $content += '</table>';
        $content += '</div>';
        if(!$rate_id){
            //$content += '<hr />';
            $content += '<div class="w3-round w3-padding" style="display: none;">';
                $content += '<table style="width: 100%;" cellpadding="5" cellspacing="5">';
                    $content += '<tr>';
                        $content += '<td colspan="2"><b>First Setting Rates to Default</b></td>';
                    $content += '</tr>';
                    $content += '<tr>';
                        $content += '<td style="text-align: right;">Start date</td>';
                        $content += '<td><input id="start_date" type="text" class="w3-input w3-border" style="width: 120px;" /></td>';
                    $content += '</tr>';
                    $content += '<tr>';
                        $content += '<td style="text-align: right;">End date</td>';
                        $content += '<td><input id="end_date" type="text" class="w3-input w3-border" style="width: 120px;" /></td>';
                    $content += '</tr>';
                $content += '</table>';
            $content += '</div>';
        }
        $content += '<hr />';
        $content += '<div class="w3-round w3-padding">';
            $content += '<div class="w3-button w3-teal w3-margin w3-round w3-right" onclick="SaveRoomRate(\''+(!$rate_id?'ADD':'EDIT')+'\','+$type_id+','+$rate_id+');">';
                $content += '<i class="fa fa-save fa-fw"></i> Save';
            $content += '</div>';
            if($rate_id){
                $content += '<div class="w3-button w3-white w3-hover-white w3-margin w3-border w3-round w3-right" onclick="MoveRoomRate('+$rate_id+');">';
                    $content += 'Move To Another Room Type';
                $content += '</div>';
                $content += '<div class="w3-button w3-white w3-hover-white w3-text-pink w3-margin w3-right" onclick="if(confirm(\' Are You Sure? \')){ DeleteRoomRate('+$rate_id+'); }">';
                    $content += '<i class="fa fa-remove fa-fw"></i> Delete';
                $content += '</div>';
            }
            $content += '<div class="w3-button w3-white w3-hover-white w3-margin w3-round w3-right" onclick="CloseLightBox();">';
                $content += 'Cancel';
            $content += '</div>';
        $content += '</div>';
    $content += '</div>';
    return $content;  
        
    }
    function CreateElementPercentageAdjust($percent_inc,$percent_dec,$percent_adjust){
        $content = '';
        $content += '<tr class="PercentageAdjust">';
            $content += '<td style="text-align: right;"><b>Percentage adjustment *</b></td>';
            $content += '<td>';
                $content += '<input name="percent_inc" id="percent_inc" onclick="ChooseRease(\'percent\',\'inc\');" type="checkbox" '+($percent_dec==false?'checked="checked"':'')+' />';
                $content += '<label for="percent_inc" style="margin-right: 10px;">Increase</label>';
                $content += '<input name="percent_dec" id="percent_dec" onclick="ChooseRease(\'percent\',\'dec\');" type="checkbox" '+($percent_dec==true?'checked="checked"':'')+' />';
                $content += '<label for="percent_dec" style="margin-right: 10px;">Decrease</label>';
                $content += '<input id="percent_adjust" name="percent_adjust" type="text" class="w3-border input_number" maxlength="3" style="width: 80px; padding: 8px;" placeholder="%" value="'+$percent_adjust+'" />';
            $content += '</td>';
        $content += '</tr>';
        return $content;
    }
    function CreateElementAmountAdjust($amount_inc,$amount_dec,$amount_adjust){
        $content = '';
        $content += '<tr class="AmountAdjust">';
            $content += '<td style="text-align: right;"><b>Amount adjustment *</b></td>';
            $content += '<td>';
                $content += '<input name="amount_inc" id="amount_inc" onclick="ChooseRease(\'amount\',\'inc\');" type="checkbox" '+($amount_dec==false?'checked="checked"':'')+' />';
                $content += '<label for="amount_inc" style="margin-right: 10px;">Increase</label>';
                $content += '<input name="amount_dec" id="amount_dec" onclick="ChooseRease(\'amount\',\'dec\');" type="checkbox" '+($amount_dec==true?'checked="checked"':'')+' />';
                $content += '<label for="amount_dec" style="margin-right: 10px;">Decrease</label>';
                $content += '<input id="amount_adjust" name="amount_adjust" type="text" class="w3-border input_number" style="width: 80px; padding: 8px;" placeholder="$" value="'+$amount_adjust+'" />';
            $content += '</td>';
        $content += '</tr>';
        return $content;
    }
    function SelectDeriveFrom(){
        if(jQuery("#derive_from_rate_id").val()!=''){
            jQuery(".AdjustDailyRatesBy").css('display','');
        }else{
            jQuery(".AdjustDailyRatesBy").css('display','none');
        }
        jQuery(".PercentageAdjust").remove();
        jQuery(".AmountAdjust").remove();
        jQuery("#daily_rate").val('');
    }
    function ManuallyDeriveConfigOta($value){
        if($value == 'DERIVE'){
            jQuery(".AdjustDailyRatesBy").css('display','');
        }else{
            jQuery(".AdjustDailyRatesBy").css('display','none');
        }
        jQuery("#daily_rate").val('');
        jQuery(".PercentageAdjust").remove();
        jQuery(".AmountAdjust").remove();
    }
    function ManuallyDeriveConfig($value){
        if($value == 'DERIVE'){
            document.getElementById('rate_config_manual').checked = false;
            document.getElementById('rate_config_derive').checked = true;
            jQuery(".ManuallyInputDailyRates").css('display','none');
            jQuery(".DeriveDailyRatesFromAnotherRoomRate").css('display','');
        }else{
            document.getElementById('rate_config_manual').checked = true;
            document.getElementById('rate_config_derive').checked = false;
            jQuery(".ManuallyInputDailyRates").css('display','');
            jQuery(".DeriveDailyRatesFromAnotherRoomRate").css('display','none');
        }
        jQuery("#manual_rack_rate").val('');
        jQuery("#derive_from_rate_id").val('');
        jQuery(".AdjustDailyRatesBy").css('display','none');
        jQuery("#daily_rate").val('');
        jQuery(".PercentageAdjust").remove();
        jQuery(".AmountAdjust").remove();
    }
    function EditRoomType($id){
        OpenLoading();
        jQuery.ajax({
			url:"form.php?block_id="+<?php echo Module::block_id();?>,
			type:"POST",
			data:{status:'GETROOMTYPE',id:$id},
			success:function(html)
            {
                $data = jQuery.parseJSON(html);
                $content = '<div class="w3-container">';
                    $content += '<div class="w3-row w3-padding w3-text-cyan"><h3>Configure Room Type</h3></div>';
                    $content += '<hr />';
                    $content += '<div class="w3-round w3-padding">';
                        $content += '<p>';
                            $content += '<label>Name *:</label>';
                            $content += '<input id="type_name" name="type_name" value="'+$data['type_name']+'" type="text" class="w3-input w3-border" />';
                        $content += '</p>';
                        $content += '<p>';
                            $content += '<label>Map To Room Level</label>';
                            $content += '<select id="room_level_id" name="room_level_id" class="w3-input w3-border">';
                                $content += '<option value="">Chọn</option>';
                                for(var i in room_level_js){
                                    $content += '<option value="'+room_level_js[i]['id']+'" '+(room_level_js[i]['id']==$data['room_level_id']?'selected="selected"':'')+'>'+room_level_js[i]['brief_name']+' - '+room_level_js[i]['name']+'</option>';
                                }
                            $content += '</select>';   
                        $content += '</p>';
                        <?php if(SITEMINDER_TWO_WAY){ ?>
                        $content += '<p style="display: none;">';
                            $content += '<label>Auto Set Avail:</label><br/>';
                            $content += '<i style="font-weight: normal;">Luôn cập nhập lại số lượng phòng trống ban đầu</i><br/>';
                            $content += '<input id="auto_set_avail" name="auto_set_avail" value="'+$data['auto_set_avail']+'" type="checkbox" '+($data['auto_set_avail']==1?'checked="checked"':'')+' />';
                        $content += '</p>';
                        $content += '<p style="display: none;">';
                            $content += '<label>Overbook Quantity:</label><br/>';
                            $content += '<i style="font-weight: normal;">Số lượng phòng trống đảm bảo tính khả dụng</i>';
                            $content += '<input id="overbook_quantity" name="overbook_quantity" value="'+$data['overbook_quantity']+'" type="number" class="w3-input w3-border" style="width: 80px;" />';
                        $content += '</p>';
                        <?php } ?>
                    $content += '</div>';
                    $content += '<hr />';
                    $content += '<div class="w3-row w3-padding">';
                        $content += '<div class="w3-button w3-teal w3-margin w3-round w3-right" onclick="SaveRoomType(\'EDIT\','+$id+');">';
                            $content += '<i class="fa fa-save fa-fw"></i> Save';
                        $content += '</div>';
                        $content += '<div class="w3-button w3-pink w3-margin w3-round w3-right" onclick="if(confirm(\' Are You Sure? \')){ DeleteRoomType('+$id+'); }">';
                            $content += '<i class="fa fa-remove fa-fw"></i> Delete';
                        $content += '</div>';
                        $content += '<div class="w3-button w3-white w3-hover-white w3-margin w3-round w3-right" onclick="CloseLightBox();">';
                            $content += 'Cancel';
                        $content += '</div>';
                    $content += '</div>';
                $content += '</div>';
                document.getElementById('LightBoxCentralContent').innerHTML = $content;
                OpenLightBox();
                CloseLoading();
			}
          });
    }
    function AddNewRoomType(){
        $content = '<div class="w3-container">';
            $content += '<div class="w3-row w3-padding w3-text-cyan"><h3>Create New Room Type</h3></div>';
            $content += '<hr />';
            $content += '<div class="w3-round w3-padding">';
                $content += '<p>';
                    $content += '<label>Name *:</label>';
                    $content += '<input id="type_name" name="type_name" type="text" class="w3-input w3-border" />';
                $content += '</p>';
                $content += '<p>';
                    $content += '<label>Map To Room Level</label>';
                    $content += '<select id="room_level_id" name="room_level_id" class="w3-input w3-border">';
                        $content += '<option value="">Chọn</option>';
                        for(var i in room_level_js){
                            $content += '<option value="'+room_level_js[i]['id']+'">'+room_level_js[i]['brief_name']+' - '+room_level_js[i]['name']+'</option>';
                        }
                    $content += '</select>';   
                $content += '</p>';
                <?php if(SITEMINDER_TWO_WAY){ ?>
                $content += '<p style="display: none;">';
                    $content += '<label>Auto Set Avail:</label><br/>';
                    $content += '<i style="font-weight: normal;">Luôn cập nhập lại số lượng phòng trống ban đầu</i><br/>';
                    $content += '<input id="auto_set_avail" name="auto_set_avail" type="checkbox" />';
                $content += '</p>';
                $content += '<p style="display: none;">';
                    $content += '<label>Overbook Quantity:</label><br/>';
                    $content += '<i style="font-weight: normal;">Số lượng phòng trống đảm bảo tính khả dụng</i>';
                    $content += '<input id="overbook_quantity" name="overbook_quantity" type="number" class="w3-input w3-border" style="width: 80px;" />';
                $content += '</p>';
                //$content += '<hr />';
                $content += '<p style="display: none;">';
                    $content += '<label>Availability the first setting:</label><br/>';
                    $content += '<i style="font-weight: normal;">Số lượng cài đặt lần đầu</i>';
                    $content += '<input id="availability_default" name="availability_default" type="number" class="w3-input w3-border" style="width: 80px;" />';
                $content += '</p>';
                $content += '<p style="display: none;">';
                    $content += '<label>Start Date:</label><br/>';
                    $content += '<input id="start_date" name="start_date" type="text" class="w3-input w3-border" style="width: 120px;" />';
                $content += '</p>';
                $content += '<p style="display: none;">';
                    $content += '<label>End Date:</label><br/>';
                    $content += '<input id="end_date" name="end_date" type="text" class="w3-input w3-border" style="width: 120px;" />';
                $content += '</p>';
                <?php } ?>
            $content += '</div>';
            $content += '<hr />';
            $content += '<div class="w3-row w3-padding">';
                $content += '<div class="w3-button w3-teal w3-margin w3-round w3-right" onclick="SaveRoomType(\'ADD\');">';
                    $content += '<i class="fa fa-save fa-fw"></i> Save';
                $content += '</div>';
                $content += '<div class="w3-button w3-white w3-margin w3-hover-white w3-round w3-right" onclick="CloseLightBox();">';
                    $content += 'Cancel';
                $content += '</div>';
            $content += '</div>';
        $content += '</div>';
        document.getElementById('LightBoxCentralContent').innerHTML = $content;
        jQuery("#start_date").datepicker();
        jQuery("#end_date").datepicker();
        OpenLightBox();
    }
    function SaveRoomType($cmd,$id){
        $type_name = jQuery("#type_name").val();
        $room_level_id = jQuery("#room_level_id").val();
        $overbook_quantity = to_numeric(jQuery("#overbook_quantity").val());
        $auto_set_avail = document.getElementById('auto_set_avail').checked==true?1:0;
        if($cmd=='ADD'){
            $availability_default = jQuery("#availability_default").val();
            $start_date = jQuery("#start_date").val();
            $end_date = jQuery("#end_date").val();
        }else{
            $availability_default = '';
            $start_date = '';
            $end_date = '';
        }
        if(!$id){
            $id = 0;
        }
        OpenLoading();
        jQuery.ajax({
			url:"form.php?block_id="+<?php echo Module::block_id();?>,
			type:"POST",
			data:{status:'SAVEROOMTYPE',act:$cmd,end_date:$end_date,start_date:$start_date,availability_default:$availability_default,type_name:$type_name,room_level_id:$room_level_id,id:$id,overbook_quantity:$overbook_quantity,auto_set_avail:$auto_set_avail},
			success:function(html)
            {
                alert(html);
                CloseLoading();
                CloseLightBox();
                location.reload();
			}
          });
    }
    function DeleteRoomType($id){
        OpenLoading();
        jQuery.ajax({
			url:"form.php?block_id="+<?php echo Module::block_id();?>,
			type:"POST",
			data:{status:'DELETEROOMTYPE',id:$id},
			success:function(html)
            {
                alert(html);
                CloseLoading();
                CloseLightBox();
                location.reload();
			}
          });
    }
</script>


