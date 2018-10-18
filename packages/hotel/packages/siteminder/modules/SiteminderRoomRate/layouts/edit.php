<style>
    .simple-layout-middle {width:100%;}
    .simple-layout-content {
         /*background: #22272b;*/
         background: #FFFFFF;
         padding: 0px; 
         min-height: calc( 100% - 100px )!important;
         margin: 0px;
         border: none;
    }
    .simple-layout-bound{
        background: none!important;
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
</style>
<form name="EditSiteminderRoomRateForm" method="POST" enctype="multipart/form-data">
<input name="act" id="act" type="hidden" />
<div class="w3-row">
    <div class="w3-row w3-padding">
        <p class=" w3-left"><h5><i class="fa fa-fw fa-tachometer"></i> <!--IF:cond(Url::get('cmd')=='add')--> ADD <!--ELSE--> EDIT <!--/IF:cond--> ROOM RATE</h5></p>
    </div>
    <div class="w3-row w3-padding" id="DashboardContent">
        
    </div>
</div>
<div id="LightBoxCentral" style="width: 100%; height: 100%; display: none; overflow-y: auto; position: fixed; top: 0px; left: 0px; background: rgba(0,0,0,0.5); z-index: 999;">
    <div style="width: 100%; height: 100%; position: absolute; top: 0px; left: 0px; z-index: -1;" onclick="CloseLightBox();"></div>
    <div id="LightBoxCentralContent" class="w3-padding" style="min-width: 320px; max-width: 720px; min-height: 50px; z-index: 99; margin: 100px auto; background: white;">
        
    </div>
</div>
</form>
<div id="LoadingCentral" style="width: 100%; height: 100%; position: fixed; top: 0px; left: 0px; background: rgba(0,0,0,0.5); display: none; z-index: 9999;">
    <div class="loader"></div>
</div>
<script>
    function OpenLightBox(){
        jQuery("body").addClass('over_hidden');
        jQuery("#LightBoxCentral").css('display','');
    }
    function CloseLightBox(){
        jQuery("body").removeClass('over_hidden');
        document.getElementById('LightBoxCentralContent').innerHTML = '';
        jQuery("#LightBoxCentral").css('display','none');
    }
    function OpenLoading(){
        jQuery("#LoadingCentral").css('display','');
    }
    function CloseLoading(){
        jQuery("#LoadingCentral").css('display','none');
    }
    jQuery(document).ready(function(){
        
    });
</script>


