<style>
    *{
      -webkit-touch-callout: none; /* iOS Safari */
        -webkit-user-select: none; /* Safari */
         -khtml-user-select: none; /* Konqueror HTML */
           -moz-user-select: none; /* Firefox */
            -ms-user-select: none; /* Internet Explorer/Edge */
                user-select: none; /* Non-prefixed version, currently
                                      supported by Chrome and Opera */
    }
    .simple-layout-content {
        border: none!important;
    }
    .over_hidden{
        overflow: hidden!important;
        position: fixed;
        width: 100%;
        height: 100%;
    }
</style>
<form name="ListProjectServerForm" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="act" id="act" />
    <div id="DriveContainer" class="w3-container w3-margin-bottom" style="margin: 0px auto; max-width: 1200px;">
        <div class="w3-row w3-padding"><?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><br clear="all"><?php }?></div>
        <div class="w3-row">
            
        </div>
    </div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<div id="LightBoxCentral" style="width: 100%; height: 100%; display: none; overflow-y: auto; position: fixed; top: 0px; left: 0px; background: rgba(0,0,0,0.5); z-index: 999;">
    <div style="width: 100%; height: 100%; position: absolute; top: 0px; left: 0px; z-index: -1;" onclick="CloseLightBox();" oncontextmenu="CloseLightBox(); return false;"></div>
    <div id="LightBoxCentralContent" style="min-width: 320px; max-width: 480px; min-height: 50px; z-index: 99; margin: 100px auto; background: white; box-shadow: 0px 1px 3px 1px rgba(60,64,67,.15);">
        
    </div>
</div>
<script>
    jQuery(document).ready(function(){
        
    });
    function count_date(start_day, end_day)
    {
    	var std =start_day.split("/");
    	var ed =end_day.split("/");
    	return (Date.parse(ed[1]+"/"+ed[0]+"/"+ed[2])-Date.parse(std[1]+"/"+std[0]+"/"+std[2]))/86400000;
    }
    var windowscrollTop = 0;
    function OpenLightBox(){
        windowscrollTop = jQuery(window).scrollTop();
        jQuery("body").addClass('over_hidden');
        jQuery("#LightBoxCentral").show();
    }
    function CloseLightBox(){
        $input_count_range = 100;
        jQuery("body").removeClass('over_hidden');
        document.getElementById('LightBoxCentralContent').innerHTML = '';
        jQuery("#LightBoxCentral").hide();
        jQuery(window).scrollTop(windowscrollTop);
    }
</script>