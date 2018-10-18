<style>
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
<header style=" width: 100%; padding: 100px 0px; margin-bottom: 30px; background: #2c4762 url(packages/hotel/packages/siteminder/modules/SiteminderApiSecretKey/api_bg.png) repeat center top; background-size: contain;">
    <h1 style="margin: 0; padding: 0; font-size: 38px!important; font: inherit; vertical-align: baseline; text-align: center; color: white;">Get Secret Key</h1>
</header>

<div class="w3-container" style="max-width: 500px; margin: 0px auto;">
    <p>
        <label style="font-weight: bold;">SecretName</label>
        <input type="text" id="secretname" class="w3-input w3-border w3-round-xxlarge" autocomplete="OFF" onkeyup="hightlightchange(this);" />
    </p>
    <p>
        <label style="font-weight: bold;">SecretPass</label>
        <input type="text" id="secretpass" class="w3-input w3-border w3-round-xxlarge" autocomplete="OFF" onkeyup="hightlightchange(this);" />
    </p>
    <p>
        <label style="font-weight: bold;">SecretKey</label>
        <input type="text" id="secretkey" class="w3-input w3-border w3-round-xxlarge" autocomplete="OFF" readonly="" style="background: #DDDDDD;" />
        <div class="w3-button w3-teal" onclick="GetSecretKet();">GetKey</div>
        <div class="w3-button w3-blue-gray" onclick="CopySecretKet();">Copy SecretKey</div>
    </p>
    <p>
        <label style="font-weight: bold;">CreateTime</label>
        <label id="CreateTime">__:__ __/__/____</label>
        <input type="hidden" id="lifetimes" class="w3-input w3-border w3-round-xxlarge" autocomplete="OFF" readonly="" style="background: #DDDDDD;" />
    </p>
    <p>
        <div class="w3-button w3-deep-purple" onclick="SaveSecretKet();">Save</div>
        <input type="hidden" id="apikey_id" class="w3-input w3-border w3-round-xxlarge" autocomplete="OFF" readonly="" style="background: #DDDDDD;" />
    </p>
</div>

<div class="w3-container" style="max-width: 500px; margin: 0px auto;">
    <ul id="SecretKeyBox" style="list-style: none;" class="w3-ul w3-border">
        
    </ul>
</div>
<div id="LoadingCentral" style="width: 100%; height: 100%; position: fixed; top: 0px; left: 0px; background: rgba(0,0,0,0.5); display: none; z-index: 999;">
    <div class="loader"></div>
</div>
<script>
    LoadSecretKey();
    function OpenLoading(){
        jQuery("#LoadingCentral").css('display','');
    }
    function CloseLoading(){
        jQuery("#LoadingCentral").css('display','none');
    }
    function hightlightchange(obj){
        if(obj.value=='')
            jQuery("#"+obj.id).css('background','#FFDDDD');
        else{
            if(obj.id=='email' && !validateEmail(jQuery("#email").val())){
                jQuery("#"+obj.id).css('background','#FFDDDD');
            }else
                jQuery("#"+obj.id).css('background','white');
        }
    }
    function ResetForm(){
        jQuery('#secretname').val('');
        jQuery('#secretpass').val('');
        jQuery('#secretkey').val('');
        jQuery('#lifetimes').val('');
        jQuery('#apikey_id').val('');
        jQuery('#CreateTime').html('__:__ __/__/____');
    }
    function RemoveSecretKey($apikey_id){
        if(confirm('Are You Sure?')){
            OpenLoading();
            jQuery.ajax({
    					url:"form.php?block_id="+<?php echo Module::block_id();?>,
    					type:"POST",
    					data:{status:'DELETESECRETKEY',apikey_id:$apikey_id},
    					success:function(html)
                        {
                            $data = jQuery.parseJSON(html);
                            LoadSecretKey();
                            if(jQuery("#apikey_id").val()==$apikey_id){
                                ResetForm();
                            }
                            CloseLoading();
    					}
    		          });
        }
    }
    function EditSecretKey($apikey_id){
        OpenLoading();
        jQuery.ajax({
					url:"form.php?block_id="+<?php echo Module::block_id();?>,
					type:"POST",
					data:{status:'EDITSECRETKEY',apikey_id:$apikey_id},
					success:function(html)
                    {
                        $data = jQuery.parseJSON(html);
                        console.log($data);
                        jQuery('#secretname').val($data['secretname']);
                        jQuery('#secretpass').val($data['secretpass']);
                        jQuery('#secretkey').val($data['secretkey']);
                        jQuery('#lifetimes').val($data['lifetimes']);
                        jQuery('#apikey_id').val($data['id']);
                        jQuery('#CreateTime').html($data['create_time']);
                        CloseLoading();
					}
		          });
    }
    function SaveSecretKet(){
        if(jQuery('#secretname').val()!='' && jQuery('#secretpass').val()!='' && jQuery('#secretkey').val()!='' && jQuery("#lifetimes").val()!=''){
        OpenLoading();
        jQuery.ajax({
					url:"form.php?block_id="+<?php echo Module::block_id();?>,
					type:"POST",
					data:{status:'SAVESECRETKEY',secretname:jQuery('#secretname').val(),secretpass:jQuery('#secretpass').val(),secretkey:jQuery('#secretkey').val(),lifetimes:jQuery("#lifetimes").val(),apikey_id:jQuery("#apikey_id").val()},
					success:function(html)
                    {
                        $data = jQuery.parseJSON(html);
                        console.log($data);
                        if($data['status']==1){
                            ResetForm();
                            LoadSecretKey();
                        }
                        alert($data['messenge']);
                        CloseLoading();
					}
		          });
        }else{
            alert('SecretName,SecretPass,SecretKey field can not be empty!');
            jQuery("#secretname").css('background','#FFDDDD');
            jQuery("#secretpass").css('background','#FFDDDD');
        }
    }
    function CopySecretKet(){
        var copyText = document.getElementById("secretkey");
        copyText.select();
        document.execCommand("Copy");
    }
    function GetSecretKet(){
        if(jQuery('#secretname').val()!='' && jQuery('#secretpass').val()!=''){
        OpenLoading();
        jQuery.ajax({
					url:"form.php?block_id="+<?php echo Module::block_id();?>,
					type:"POST",
					data:{status:'GETSECRETKEY',secretname:jQuery('#secretname').val(),secretpass:jQuery('#secretpass').val()},
					success:function(html)
                    {
                        $data = jQuery.parseJSON(html);
                        console.log($data);
                        jQuery("#secretkey").val($data['secretkey']);
                        jQuery("#CreateTime").html($data['create_time']);
                        jQuery("#lifetimes").val($data['lifetimes']);
                        CloseLoading();
					}
		          });
        }else{
            alert('SecretName,SecretPass field can not be empty!');
            jQuery("#secretname").css('background','#FFDDDD');
            jQuery("#secretpass").css('background','#FFDDDD');
        }
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
                        console.log($data);
                        $content = '';
                        
                        for(var i in $data){
                            $content += '<li class="w3-padding w3-margin w3-hover-pale-red" style="position: relative; cursor: pointer;">';
                                $content += '<span style="font-weight: bold;" onclick="EditSecretKey('+$data[i]['id']+');">'+$data[i]['secretkey']+'</span><br />';
                                $content += '<span style="font-size: 10px!important;" onclick="EditSecretKey('+$data[i]['id']+');">'+$data[i]['lifetimes']+' - '+$data[i]['secretname']+' - '+$data[i]['secretpass']+'</span>';
                                $content += '<i class="w3-display-right w3-padding fa fa-2x fa-remove w3-text-pink" onclick="RemoveSecretKey('+$data[i]['id']+');"></i>';
                            $content += '</li>';
                        }
                        document.getElementById('SecretKeyBox').innerHTML = $content;
                        CloseLoading();
					}
		});
    }
</script>

