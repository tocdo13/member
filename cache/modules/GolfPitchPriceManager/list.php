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
                    <h3><img src="packages/hotel/packages/golf/includes/img/grass.png" style="height: 40px; width: auto;" /> <?php echo Portal::language('golf_pitch_rate');?></h3>
                </div>
                <div class="w3-button w3-green w3-hover-green w3-margin w3-right" onclick="location.href='?page=golf_pitch_price_manager&cmd=add';" style="font-weight: normal;">
                    <i class="fa fa-fw fa-plus"></i> <?php echo Portal::language('add_price');?>
                </div>
                <div class="w3-button w3-blue w3-hover-blue w3-margin w3-right" onclick="jQuery('#act').val('SAVE'); GolfPriceManagerForm.submit();" style="font-weight: normal;">
                    <i class="fa fa-fw fa-save"></i> <?php echo Portal::language('save');?>
                </div>
            </div>
            <div class="w3-row">
                <table>
                    <tr>
                        <td><label for="in_date"><?php echo Portal::language('in_date');?></label></td>
                        <td><input  name="in_date" id="in_date" onchange="jQuery('#act').val(''); GolfPriceManagerForm.submit();" class="w3-input w3-border" style="width: 120px; text-align: center;" readonly="" / type ="text" value="<?php echo String::html_normalize(URL::get('in_date'));?>"></td>
                    </tr>
                </table>
            </div>
            <hr />
            <div class="w3-row">
                <table style="width: 100%; background: rgba(255,255,255,1); box-shadow: 0px 1px 3px 1px rgba(60,64,67,.15); border: 5px solid #FFF; border-radius: 5px; margin: 15px;" cellspacing="0" cellpadding="5">
                    <tr style="height: 60px;">
                        <td colspan="2" style="font-weight: bold;"><?php echo Portal::language('golf_hole');?> / <?php echo Portal::language('group_traveller');?></td>
                        <td style="width: 40px; border-left: 1px solid #ffc76c; vertical-align: bottom; position: relative;">
                            <span style="font-size: 9px; position: absolute; left: -12px; bottom: 0px; background: #FFF; color: red;">00:00</span>
                            <span style="width: 19px; height: 25px; border-right: 1px solid #ffeacd; position: absolute; top: 0px; left: 0px;"></span>
                        </td>
                        <?php if(isset($this->map['timeline']) and is_array($this->map['timeline'])){ foreach($this->map['timeline'] as $key1=>&$item1){if($key1!='current'){$this->map['timeline']['current'] = &$item1;?>
                        <td style="width: 40px; border-left: 1px solid #ffc76c; vertical-align: bottom; position: relative;">
                            <span style="font-size: 9px; position: absolute; left: -12px; bottom: 0px; background: #FFF; color: red;"><?php echo $this->map['timeline']['current']['in_house'];?></span>
                            <span style="width: 19px; height: 25px; border-right: 1px solid #ffeacd; position: absolute; top: 0px; left: 0px;"></span>
                        </td>
                        <?php }}unset($this->map['timeline']['current']);} ?>
                    </tr>
                    <?php if(isset($this->map['golf_hole']) and is_array($this->map['golf_hole'])){ foreach($this->map['golf_hole'] as $key2=>&$item2){if($key2!='current'){$this->map['golf_hole']['current'] = &$item2;?>
                    <tr style="height: 40px; background: #f9f9f9;">
                        <td colspan="<?php echo $this->map['count_time']+2; ?>"><?php echo $this->map['golf_hole']['current']['name'];?></td>
                    </tr>
                        <?php if(isset($this->map['group_traveller']) and is_array($this->map['group_traveller'])){ foreach($this->map['group_traveller'] as $key3=>&$item3){if($key3!='current'){$this->map['group_traveller']['current'] = &$item3;?>
                        <tr style="height: 40px;">
                            <td style="width: 80px;"></td>
                            <td style="border-bottom: 1px dashed #EEE;" class="w3-text-green"><?php echo $this->map['group_traveller']['current']['name'];?></td>
                            <td colspan="<?php echo $this->map['count_time']; ?>" style="position: relative; border-bottom: 1px dashed #EEE;">
                                <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key4=>&$item4){if($key4!='current'){$this->map['items']['current'] = &$item4;?>
                                    <?php if($this->map['items']['current']['golf_hole_id']==$this->map['golf_hole']['current']['id'] and $this->map['items']['current']['group_traveller_id']==$this->map['group_traveller']['current']['id']){ ?>
                                    <input id="items_<?php echo $this->map['items']['current']['id'];?>" name="golf_pitch_price[<?php echo $this->map['items']['current']['id'];?>][price]" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val())));" value="<?php echo System::display_number($this->map['items']['current']['price']); ?>" title="<?php echo date('H:i',$this->map['items']['current']['start_time']).' - '.date('H:i',$this->map['items']['current']['end_time']); ?>" class="input_number" style="width: <?php echo (($this->map['items']['current']['end_time']-$this->map['items']['current']['start_time'])/3600)*40 - 5; ?>px; height: 30px; border-radius: 15px; padding-right: 5px; border: 1px solid #FFF; background: rgba(0,204,153,0.5); text-align: right; position: absolute; top: 5px; left: <?php echo (($this->map['items']['current']['start_time']-$this->map['in_time'])/3600)*40; ?>px;" type="text" />
                                    <?php } ?>
                                <?php }}unset($this->map['items']['current']);} ?>
                            </td>
                        </tr>
                        <?php }}unset($this->map['group_traveller']['current']);} ?>
                    <?php }}unset($this->map['golf_hole']['current']);} ?>
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
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			

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

