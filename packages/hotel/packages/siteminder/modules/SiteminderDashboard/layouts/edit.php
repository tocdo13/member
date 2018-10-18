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
</style>
<div class="w3-row">
    <div class="w3-quarter w3-padding">
        <p><h5><i class="fa fa-fw fa-tachometer"></i> SITEMINDER DASHBOARD</h5></p>
        <hr />
        <ul class="w3-ul">
            <li class="w3-padding-large w3-hover-cyan w3-list-item" id="PmsAccount" style="cursor: pointer;"><i class="fa fa-fw fa-compress"></i> PmsAccount</li>
            <li class="w3-padding-large w3-hover-cyan w3-list-item" id="APISecretKey" style="cursor: pointer;"><i class="fa fa-fw fa-compress"></i> API SecretKey</li>
            <li class="w3-padding-large w3-hover-cyan w3-list-item" id="RoomRateConfiguration" style="cursor: pointer;"><i class="fa fa-fw fa-compress"></i>  Room - Rate Configuration</li>
            <li class="w3-padding-large w3-hover-cyan w3-list-item" id="BookingChannel" style="cursor: pointer;"><i class="fa fa-fw fa-compress"></i> Booking Agent Code</li>
            <!--<li class="w3-padding-large w3-hover-cyan w3-list-item" id="BookingChannelType" style="cursor: pointer;"><i class="fa fa-fw fa-compress"></i> Booking Channel Type (BCT)</li>-->
            <li class="w3-padding-large w3-hover-cyan w3-list-item" id="ServiceAndExtraCharge" style="cursor: pointer;"><i class="fa fa-fw fa-compress"></i> Service and Extra Charge</li>
            <li class="w3-padding-large w3-hover-cyan w3-list-item" id="OTAPaymentCard" style="cursor: pointer;"><i class="fa fa-fw fa-compress"></i> OTA Payment Card Provider Codes</li>
        </ul> 
        <hr />
    </div>
    <div class="w3-threequarter w3-padding" id="DashboardContent">
        
    </div>
</div>
<div id="LightBoxCentral" style="width: 100%; height: 100%; position: fixed; top: 0px; left: 0px; background: rgba(0,0,0,0.5); display: none; z-index: 999;">
    <div style="width: 100%; height: 100%; position: absolute; top: 0px; left: 0px; z-index: -1;" onclick="CloseLightBox();"></div>
    <div id="LightBoxCentralContent" class="w3-padding" style="min-width: 320px; max-width: 720px; min-height: 50px; z-index: 99; margin: 100px auto; background: white;"></div>
</div>
<div id="LoadingCentral" style="width: 100%; height: 100%; position: fixed; top: 0px; left: 0px; background: rgba(0,0,0,0.5); display: none; z-index: 9999;">
    <div class="loader"></div>
</div>
<script>
    var customer_js = [[|customer_js|]];
    var extra_service_js = [[|extra_service_js|]];
    var credit_card_js = [[|credit_card_js|]];
    function OpenLightBox(){
        jQuery("#LightBoxCentral").css('display','');
    }
    function CloseLightBox(){
        document.getElementById('LightBoxCentralContent').innerHTML = '';
        jQuery("#LightBoxCentral").css('display','none');
    }
    function OpenLoading(){
        jQuery("#LoadingCentral").css('display','');
    }
    function CloseLoading(){
        jQuery("#LoadingCentral").css('display','none');
    }
    function CallListItems(obj){
        jQuery(".w3-list-item").each(function(){
            jQuery(this).removeClass('w3-cyan');
        });
        jQuery(obj).addClass('w3-cyan');
    }
    jQuery(document).ready(function(){
        $header_top = jQuery("#DashboardContent").offset().top;
        jQuery(window).scroll(function(){
            /** cuon doc **/
            
            if(jQuery(window).scrollTop() > $header_top){
                if(jQuery(".HeaderContent")){
                    jQuery(".HeaderContent").addClass('HeaderFixed');
                }    
            }else{
                jQuery(".HeaderContent").removeClass('HeaderFixed');
            }
            
        });
        jQuery(".w3-list-item").click(function(){ CallListItems(this); document.getElementById('DashboardContent').innerHTML=''; });
        jQuery("#PmsAccount").click(function(){ LoadPmsAccount(); });
        jQuery("#APISecretKey").click(function(){ LoadSecretKey(); });
        jQuery("#RoomRateConfiguration").click(function(){ GetRoomRateConfiguration(); });
        jQuery("#BookingChannel").click(function(){ LoadBookingChannel(); });
        jQuery("#BookingChannelType").click(function(){ LoadBookingChannelType(); });
        jQuery("#ServiceAndExtraCharge").click(function(){ LoadServiceAndExtraCharge(); });
        jQuery("#OTAPaymentCard").click(function(){ LoadOTAPaymentCard(); });
    });
    function GetRoomRateConfiguration(){
        
    }
    function SaveBookingChannel($code){
        $action = 'EDIT';
        if(!$code){
            $code=false;
            $action = 'ADD';
        }
        $codechannel = jQuery("#codechannel").val();
        $namechannel = jQuery("#namechannel").val();
        $customer = jQuery("#customerchannel").val();
        OpenLoading();
        jQuery.ajax({
					url:"form.php?block_id="+<?php echo Module::block_id();?>,
					type:"POST",
					data:{status:'SAVEBOOKINGCHANNEL',action:$action,code:$code,codechannel:$codechannel,namechannel:$namechannel,customer:$customer},
					success:function(html)
                    {
                        alert(html);
                        CloseLoading();
                        CloseLightBox();
                        LoadBookingChannel();
					}
		          });
    }
    function GetBookingChannel($code){
        if(!$code){
            $code=false;
        }
        if($code){
            OpenLoading();
            jQuery.ajax({
    					url:"form.php?block_id="+<?php echo Module::block_id();?>,
    					type:"POST",
    					data:{status:'GETBOOKINGCHANNEL',code:$code},
    					success:function(html)
                        {
                            $data = jQuery.parseJSON(html);
                            $content = '<div class="w3-row"><button class="w3-button w3-cyan w3-right" onclick="SaveBookingChannel(\''+$code+'\');">Save</button></div>';
                            $content += '<hr />';
                            $content += '<div class="w3-row">';
                            $content += '<p>';
                                $content += '<label style="font-weight: bold;">Code</label>';
                                $content += '<input type="text" id="codechannel" value="'+$data['code']+'" class="w3-input w3-border w3-round-xxlarge" readonly="readonly" autocomplete="OFF" />';
                            $content += '</p>';
                            $content += '<p>';
                                $content += '<label style="font-weight: bold;">Name</label>';
                                $content += '<input type="text" id="namechannel" value="'+$data['name']+'" class="w3-input w3-border w3-round-xxlarge" readonly="readonly" autocomplete="OFF" />';
                            $content += '</p>';
                            $content += '<p>';
                                $content += '<label style="font-weight: bold;">Map to Newway</label>';
                                $content += '<select id="customerchannel" class="w3-input w3-border w3-round-xxlarge">';
                                    $content += '<option value=""> Chọn </option>';
                                    for(var i in customer_js){
                                        $content += '<option value="'+customer_js[i]['customer_id']+'" '+(($data['customer_id']!=null && customer_js[i]['customer_id']==$data['customer_id'])?'selected="selected"':'')+' >'+customer_js[i]['code']+' - '+customer_js[i]['name']+'</option>';
                                    }
                                $content += '</select>';
                            $content += '</p>';
                            $content += '</div>';
                            document.getElementById('LightBoxCentralContent').innerHTML = $content;
                            CloseLoading();
                            OpenLightBox();
    					}
    		});
        }else{
            OpenLoading();
            $content = '<div class="w3-row"><button class="w3-button w3-cyan w3-right" onclick="SaveBookingChannel();">Save</button></div>';
            $content += '<hr />';
            $content += '<div class="w3-row">';
            $content += '<p>';
                $content += '<label style="font-weight: bold;">Code</label>';
                $content += '<input type="text" id="codechannel" class="w3-input w3-border w3-round-xxlarge" autocomplete="OFF" />';
            $content += '</p>';
            $content += '<p>';
                $content += '<label style="font-weight: bold;">Name</label>';
                $content += '<input type="text" id="namechannel" class="w3-input w3-border w3-round-xxlarge" autocomplete="OFF" />';
            $content += '</p>';
            $content += '<p>';
                $content += '<label style="font-weight: bold;">Map to Newway</label>';
                $content += '<select id="customerchannel" class="w3-input w3-border w3-round-xxlarge">';
                    $content += '<option value=""> Chọn </option>';
                    for(var i in customer_js){
                        $content += '<option value="'+customer_js[i]['customer_id']+'" >'+customer_js[i]['code']+' - '+customer_js[i]['name']+'</option>';
                    }
                $content += '</select>';
            $content += '</p>';
            $content += '</div>';
            document.getElementById('LightBoxCentralContent').innerHTML = $content;
            CloseLoading();
            OpenLightBox();
        }
    }
    function SearchCode($code){
        $code = $code.toUpperCase();
        jQuery(".searchitems").each(function(){
            jQuery(this).removeClass('w3-green');
            $id = this.id;
            $id = $id.toUpperCase();
            if($id==$code){
                var offset = jQuery('#'+$code).offset();
                jQuery('#'+$code).addClass('w3-green');
                jQuery(window).scrollTop((offset.top-100));
            }
        });
    }
    function SaveServiceAndExtraCharge($code){
        $extra_service = jQuery("#extra_service").val();
        OpenLoading();
        jQuery.ajax({
					url:"form.php?block_id="+<?php echo Module::block_id();?>,
					type:"POST",
					data:{status:'SAVESERVICEANDEXTRA',code:$code,extra_service:$extra_service},
					success:function(html)
                    {
                        alert(html);
                        CloseLoading();
                        CloseLightBox();
                        LoadServiceAndExtraCharge();
					}
		          });
    }
    function SaveOTAPaymentCard($code){
        $credit_card = jQuery("#credit_card").val();
        OpenLoading();
        jQuery.ajax({
					url:"form.php?block_id="+<?php echo Module::block_id();?>,
					type:"POST",
					data:{status:'SAVEOTAPAYMENTCARD',code:$code,credit_card:$credit_card},
					success:function(html)
                    {
                        alert(html);
                        CloseLoading();
                        CloseLightBox();
                        LoadOTAPaymentCard();
					}
		          });
    }
    function GetServiceAndExtraCharge($code){
        OpenLoading();
        jQuery.ajax({
					url:"form.php?block_id="+<?php echo Module::block_id();?>,
					type:"POST",
					data:{status:'GETSERVICEANDEXTRA',code:$code},
					success:function(html)
                    {
                        $data = jQuery.parseJSON(html);
                        $content = '<div class="w3-row"><button class="w3-button w3-cyan w3-right" onclick="SaveServiceAndExtraCharge(\''+$code+'\');">Save</button></div>';
                        $content += '<hr />';
                        $content += '<div class="w3-row">';
                        $content += '<p>';
                            $content += '<label style="font-weight: bold;">Code</label>';
                            $content += '<input type="text" id="codeextra" value="'+$data['code']+'" class="w3-input w3-border w3-round-xxlarge" readonly="readonly" autocomplete="OFF" />';
                        $content += '</p>';
                        $content += '<p>';
                            $content += '<label style="font-weight: bold;">Name</label>';
                            $content += '<input type="text" id="nameextra" value="'+$data['name']+'" class="w3-input w3-border w3-round-xxlarge" readonly="readonly" autocomplete="OFF" />';
                        $content += '</p>';
                        $content += '<p>';
                            $content += '<label style="font-weight: bold;">Map to Newway</label>';
                            $content += '<select id="extra_service" class="w3-input w3-border w3-round-xxlarge">';
                                $content += '<option value=""> Chọn </option>';
                                for(var i in extra_service_js){
                                    $content += '<option value="'+extra_service_js[i]['code']+'" '+(($data['extra_service_code']!=null && extra_service_js[i]['code']==$data['extra_service_code'])?'selected="selected"':'')+' >'+extra_service_js[i]['code']+' - '+extra_service_js[i]['name']+'</option>';
                                }
                            $content += '</select>';
                        $content += '</p>';
                        $content += '</div>';
                        document.getElementById('LightBoxCentralContent').innerHTML = $content;
                        CloseLoading();
                        OpenLightBox();
					}
		});
    }
    function GetOTAPaymentCard($code){
        OpenLoading();
        jQuery.ajax({
					url:"form.php?block_id="+<?php echo Module::block_id();?>,
					type:"POST",
					data:{status:'GETOTAPAYMENTCARD',code:$code},
					success:function(html)
                    {
                        $data = jQuery.parseJSON(html);
                        $content = '<div class="w3-row"><button class="w3-button w3-cyan w3-right" onclick="SaveOTAPaymentCard(\''+$code+'\');">Save</button></div>';
                        $content += '<hr />';
                        $content += '<div class="w3-row">';
                        $content += '<p>';
                            $content += '<label style="font-weight: bold;">Code</label>';
                            $content += '<input type="text" id="codeextra" value="'+$data['code']+'" class="w3-input w3-border w3-round-xxlarge" readonly="readonly" autocomplete="OFF" />';
                        $content += '</p>';
                        $content += '<p>';
                            $content += '<label style="font-weight: bold;">Name</label>';
                            $content += '<input type="text" id="nameextra" value="'+$data['name']+'" class="w3-input w3-border w3-round-xxlarge" readonly="readonly" autocomplete="OFF" />';
                        $content += '</p>';
                        $content += '<p>';
                            $content += '<label style="font-weight: bold;">Map to Newway</label>';
                            $content += '<select id="credit_card" class="w3-input w3-border w3-round-xxlarge">';
                                $content += '<option value=""> Chọn </option>';
                                for(var i in credit_card_js){
                                    $content += '<option value="'+credit_card_js[i]['code']+'" '+(($data['credit_card_code']!=null && credit_card_js[i]['code']==$data['credit_card_code'])?'selected="selected"':'')+' >'+credit_card_js[i]['code']+' - '+credit_card_js[i]['name']+'</option>';
                                }
                            $content += '</select>';
                        $content += '</p>';
                        $content += '</div>';
                        document.getElementById('LightBoxCentralContent').innerHTML = $content;
                        CloseLoading();
                        OpenLightBox();
					}
		});
    }
    function LoadOTAPaymentCard(){
        OpenLoading();
        jQuery.ajax({
					url:"form.php?block_id="+<?php echo Module::block_id();?>,
					type:"POST",
					data:{status:'LOADOTAPAYMENTCARD'},
					success:function(html)
                    {
                        $data = jQuery.parseJSON(html);
                        $content = '<ul style="list-style: none;" class="w3-ul">';
                        for(var i in $data){
                            $content += '<li class="w3-padding w3-margin" style="cursor: pointer;" onclick="GetOTAPaymentCard(\''+$data[i]['code']+'\');">';
                                $content += '<span style="font-weight: bold;">'+$data[i]['code']+'</span> '+($data[i]['credit_card_code']!=null?'<span class="w3-text-green"><i class="fa fa-fw fa-chevron-right"></i> Map To Newway</span> <span class="ww3-text-blue">'+$data[i]['credit_card_code']+': '+$data[i]['credit_card_name']+'</span>':'<span class="w3-text-pink"><i class="fa fa-fw fa-chevron-right"></i> Map To Newway</span>')+' <br />';
                                $content += '<span style="font-size: 10px!important;">'+$data[i]['name']+'</span>';
                            $content += '</li>';
                        }
                        $content += '</ul>';
                        document.getElementById('DashboardContent').innerHTML = $content;
                        jQuery(window).scrollTop(0);
                        CloseLoading();
					}
		});
    }
    function LoadServiceAndExtraCharge(){
        OpenLoading();
        jQuery.ajax({
					url:"form.php?block_id="+<?php echo Module::block_id();?>,
					type:"POST",
					data:{status:'LOADSERVICEANDEXTRA'},
					success:function(html)
                    {
                        $data = jQuery.parseJSON(html);
                        $content = '<ul style="list-style: none;" class="w3-ul">';
                        for(var i in $data){
                            $content += '<li class="w3-padding w3-margin" style="cursor: pointer;" onclick="GetServiceAndExtraCharge(\''+$data[i]['code']+'\');">';
                                $content += '<span style="font-weight: bold;">'+$data[i]['code']+'</span> '+($data[i]['extra_service_code']!=null?'<span class="w3-text-green"><i class="fa fa-fw fa-chevron-right"></i> Map To Newway</span> <span class="ww3-text-blue">'+$data[i]['extra_service_code']+': '+$data[i]['extra_service_name']+'</span>':'<span class="w3-text-pink"><i class="fa fa-fw fa-chevron-right"></i> Map To Newway</span>')+' <br />';
                                $content += '<span style="font-size: 10px!important;">'+$data[i]['name']+'</span>';
                            $content += '</li>';
                        }
                        $content += '</ul>';
                        document.getElementById('DashboardContent').innerHTML = $content;
                        jQuery(window).scrollTop(0);
                        CloseLoading();
					}
		});
    }
    function LoadBookingChannelType(){
        OpenLoading();
        jQuery.ajax({
					url:"form.php?block_id="+<?php echo Module::block_id();?>,
					type:"POST",
					data:{status:'LOADBOOKINGCHANNELTYPE'},
					success:function(html)
                    {
                        $data = jQuery.parseJSON(html);
                        $content = '<ul style="list-style: none;" class="w3-ul">';
                        for(var i in $data){
                            $content += '<li class="w3-padding w3-margin" style="cursor: pointer;">';
                                $content += '<span style="font-weight: bold;">'+$data[i]['id']+'</span><br />';
                                $content += '<span style="font-size: 10px!important;">'+$data[i]['description']+'</span>';
                            $content += '</li>';
                        }
                        $content += '</ul>';
                        document.getElementById('DashboardContent').innerHTML = $content;
                        jQuery(window).scrollTop(0);
                        CloseLoading();
					}
		});
    }
    function LoadBookingChannel(){
        OpenLoading();
        jQuery.ajax({
					url:"form.php?block_id="+<?php echo Module::block_id();?>,
					type:"POST",
					data:{status:'LOADBOOKINGCHANNEL'},
					success:function(html)
                    {
                        $data = jQuery.parseJSON(html);
                        $content = '<div class="w3-row w3-white w3-padding HeaderContent"><input type="text" id="searchchannel" placeholder="search code" onkeyup="SearchCode(\'CHANNEL_\'+this.value);" class="w3-input w3-border w3-left" style="width: 40%;" /><button class="w3-button w3-cyan w3-right" onclick="GetBookingChannel();">ADD Booking Channel</button></div>';
                        $content += '<hr />';
                        $content += '<ul style="list-style: none;" class="w3-ul">';
                        for(var i in $data){
                            $code = $data[i]['code'].toUpperCase();
                            $content += '<li id="CHANNEL_'+$code+'" class="w3-padding w3-margin searchitems" style="cursor: pointer;" onclick="GetBookingChannel(\''+$data[i]['code']+'\');">';
                                $content += '<span style="font-weight: bold;">'+$data[i]['code']+'</span> '+($data[i]['customer_code']!=null?'<span class="w3-text-green"><i class="fa fa-fw fa-chevron-right"></i> Map To Newway</span> <span class="ww3-text-blue">'+$data[i]['customer_code']+': '+$data[i]['customer_name']+'</span>':'<span class="w3-text-pink"><i class="fa fa-fw fa-chevron-right"></i> Map To Newway</span>')+' <br />';
                                $content += '<span style="font-size: 10px!important;">'+$data[i]['name']+'</span>';
                            $content += '</li>';
                        }
                        $content += '</ul>';
                        document.getElementById('DashboardContent').innerHTML = $content;
                        jQuery(window).scrollTop(0);
                        CloseLoading();
					}
		});
    }
    function LoadPmsAccount(){
        OpenLoading();
        $content = '<div class="w3-row w3-white w3-padding HeaderContent"><button class="w3-button w3-cyan w3-right" onclick="window.open(\'?page=setting#siteminder\');">Change Setting</button></div>';
        $content += '<hr />';
        $content += '<ul style="list-style: none;" class="w3-ul">';
            $content += '<li class="w3-padding w3-margin">';
                $content += '<span style="font-weight: bold;">pmsXchange Service Url: <?php echo SITEMINDER_URI; ?></span><br />';
                $content += '<span style="font-weight: bold;">Requestor ID: <?php echo SITEMINDER_REQUESTOR_ID; ?></span><br />';
                $content += '<span style="font-weight: bold;">UserName: <?php echo SITEMINDER_USERNAME; ?></span><br />';
                $content += '<span style="font-weight: bold;">PassWord: <?php echo SITEMINDER_PASSWORD; ?></span><br />';
                $content += '<span style="font-weight: bold;">HotelCode: <?php echo SITEMINDER_HOTELCODE; ?></span><br />';
            $content += '</li>';
        $content += '</ul>';
        document.getElementById('DashboardContent').innerHTML = $content;
        jQuery(window).scrollTop(0);
        CloseLoading();
    }
    function LoadSecretKey(){
        OpenLoading();
        jQuery.ajax({
					url:"form.php?block_id="+<?php echo Module::block_id();?>,
					type:"POST",
					data:{status:'LOADSECRETKEY'},
					success:function(html)
                    {
                        $data = jQuery.parseJSON(html);
                        $content = '<div class="w3-row w3-white w3-padding HeaderContent"><button class="w3-button w3-cyan w3-right" onclick="window.open(\'?page=siteminder_api_secret_key\');">ADD SecretKey</button></div>';
                        $content += '<hr />';
                        $content += '<ul style="list-style: none;" class="w3-ul">';
                        for(var i in $data){
                            $content += '<li class="w3-padding w3-margin">';
                                $content += '<span style="font-weight: bold;">'+$data[i]['secretkey']+'</span><br />';
                                $content += '<span style="font-size: 10px!important;">'+$data[i]['lifetimes']+' - '+$data[i]['secretname']+' - '+$data[i]['secretpass']+'</span>';
                            $content += '</li>';
                        }
                        $content += '</ul>';
                        document.getElementById('DashboardContent').innerHTML = $content;
                        jQuery(window).scrollTop(0);
                        CloseLoading();
					}
		});
    }
</script>


