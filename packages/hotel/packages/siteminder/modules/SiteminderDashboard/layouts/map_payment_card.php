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
    <div class="w3-row w3-padding">
        <p><h5><i class="fa fa-fw fa-tachometer"></i> MAP PAYMENT CARD PROVIDER CODES</h5></p>
    </div>
    <div class="w3-row w3-padding" id="DashboardContent">
        
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
        LoadOTAPaymentCard();
    });
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
</script>


