<style>
    *{
      -webkit-touch-callout: none; /* iOS Safari */
        -webkit-user-select: none; /* Safari */
         -khtml-user-select: none; /* Konqueror HTML */
           -moz-user-select: none; /* Firefox */
            -ms-user-select: none; /* Internet Explorer/Edge */
                user-select: none; /* Non-prefixed version, currently supported by Chrome and Opera */
    }
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
    .itemday:hover{
        border: 1px solid #6c98ff!important;
        box-shadow: 0px 1px 3px 1px rgba(60,64,67,.15);
        background: #F2F2F2;
    }
    .drive_items_select{
        border: 1px solid #6c98ff!important;
        box-shadow: 0px 1px 3px 1px rgba(60,64,67,.15);
        background: #F2F2F2;
    }
</style>

<form name="EditgolfWorkShiftForm" method="POST" enctype="multipart/form-data">
<input name="act" id="act" type="hidden" />
<div id="InventoryContent" oncontextmenu="return false;" style="width: 100%; height: auto;">
    <div class="w3-container" style="margin-bottom: 50px;">
        <div class="w3-row w3-padding" style="min-width: 600px!important; max-width: 1420px; margin: 0px auto; background: #FFFFFF;">
            <div class="w3-row">
                <div class="w3-button w3-margin w3-left" style="font-weight: bold; text-transform: uppercase; background: none!important;">
                    <i class="fa fa-fw fa-calendar"></i> <?php echo Portal::language('golf_work_shift');?>
                </div>
            </div>
            <div class="w3-row">
                <table id="Calendar" style="margin: 0px auto;">
                    <tr>
                        <th colspan="7">
                            <table class="w3-right">
                                <tr>
                                    <th>
                                        <div class="w3-button w3-left w3-hover-white" onclick="PrevMonth();" style="font-weight: bold; text-transform: uppercase;color: #555;">
                                            <i class="fa fa-fw fa-caret-left fa-2x"></i>
                                        </div>
                                    </th>
                                    <th>
                                        <select  name="month" id="month" class="w3-select" style="background: none; border: none; width: 80px;color: #555;"><?php
					if(isset($this->map['month_list']))
					{
						foreach($this->map['month_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('month',isset($this->map['month'])?$this->map['month']:''))
                    echo "<script>$('month').value = \"".addslashes(URL::get('month',isset($this->map['month'])?$this->map['month']:''))."\";</script>";
                    ?>
	</select>
                                    </th>
                                    <th>
                                        <select  name="year" id="year" class="w3-select" style="background: none; border: none; width: 90px;color: #555;"><?php
					if(isset($this->map['year_list']))
					{
						foreach($this->map['year_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('year',isset($this->map['year'])?$this->map['year']:''))
                    echo "<script>$('year').value = \"".addslashes(URL::get('year',isset($this->map['year'])?$this->map['year']:''))."\";</script>";
                    ?>
	</select>
                                    </th>
                                    <th>
                                        <div class="w3-button w3-left w3-hover-white" onclick="NextMonth();" style="font-weight: bold; text-transform: uppercase;color: #555;">
                                            <i class="fa fa-fw fa-caret-right fa-2x"></i>
                                        </div>
                                    </th>
                                </tr>
                            </table>
                        </th>
                    </tr>
                    <tr style="text-align: center; height: 40px; text-transform: uppercase; color: #555;">
                        <th>Mon</th>
                        <th>Tue</th>
                        <th>Wed</th>
                        <th>Thu</th>
                        <th>Fri</th>
                        <th>Sat</th>
                        <th>Sun</th>
                    </tr>
                    <?php if(isset($this->map['timeline']) and is_array($this->map['timeline'])){ foreach($this->map['timeline'] as $key1=>&$item1){if($key1!='current'){$this->map['timeline']['current'] = &$item1;?>
                    <tr style="text-align: center;">
                        <?php if(isset($this->map['timeline']['current']['week']) and is_array($this->map['timeline']['current']['week'])){ foreach($this->map['timeline']['current']['week'] as $key2=>&$item2){if($key2!='current'){$this->map['timeline']['current']['week']['current'] = &$item2;?>
                            <td>
                                <?php 
				if((isset($this->map['timeline']['current']['week']['current']['mday'])))
				{?>
                                    <div onclick="SelectDay(<?php echo $this->map['timeline']['current']['week']['current']['mday'];?>);" id="items_<?php echo $this->map['timeline']['current']['week']['current']['mday'];?>" class="itemday" lang="<?php echo $this->map['timeline']['current']['week']['current']['id'];?>" style="margin: 5px; padding: 5px;<?php 
				if(($this->map['timeline']['current']['week']['current']['id']==Date_Time::to_time(date('d/m/Y'))))
				{?> border: 1px solid pink; background: pink;  <?php }else{ ?> border: 1px solid #f2f2f2; 
				<?php
				}
				?> overflow: hidden; border-radius: 10px; font-weight: bold;">
                                        <?php echo $this->map['timeline']['current']['week']['current']['mday'];?>
                                    </div>
                                
				<?php
				}
				?>
                            </td>
                        <?php }}unset($this->map['timeline']['current']['week']['current']);} ?>
                    </tr>
                    <?php }}unset($this->map['timeline']['current']);} ?>
                </table>
            </div>
            <hr />
            <div class="w3-row w3-margin w3-padding" style="background: #FAFAFA;">
                <p><i class="fa fa-fw fa-info-circle"></i><?php echo Portal::language('info');?></p>
                <hr />
                
                <div id="CalendarInfo" class="w3-row w3-padding">
                    
                </div>
                
            </div>
        </div>
    </div>
</div>
<div id="LightBoxCentral" style="width: 100%; height: 100%; display: ; overflow-y: auto; position: fixed; top: 0px; left: 0px; background: rgba(0,0,0,0.5); z-index: 999;">
    <div style="width: 100%; height: 100%; position: absolute; top: 0px; left: 0px; z-index: -1;" onclick="CloseLightBox();"></div>
    <div id="LightBoxCentralContent" style="min-width: 320px; max-width: 640px; min-height: 50px; z-index: 99; margin: 100px auto; background: white;">
        <div class="w3-row w3-padding">
            <h3 class="w3-text-gray"><i class="fa fa-fw fa-cog"></i> Setup TeeTime</h3>
            <input id="in_date" type="hidden" value="" />
            <p>Ngày: </p>
        </div>
        <hr />
        <div class="w3-row w3-padding">
            <table class="w3-table">
                <tr>
                    <tr>
                        <td style="width: 50%;"><?php echo Portal::language('start_time_open');?></td>
                        <td><?php echo Portal::language('end_time_open');?></td>
                    </tr>
                    <tr>
                        <td><input type="text" id="start_time" id="start_time" class="w3-input w3-border" /></td>
                        <td><input type="text" id="end_time" id="end_time" class="w3-input w3-border" /></td>
                    </tr>
                    <tr>
                        <td><?php echo Portal::language('minute_in_one_ball');?></td>
                        <td><?php echo Portal::language('num_people_of_group');?></td>
                    </tr>
                    <tr>
                        <td><input type="text" id="minute" id="minute" class="w3-input w3-border" /></td>
                        <td><input type="text" id="num_people" id="num_people" class="w3-input w3-border" /></td>
                    </tr>
                </tr>
            </table>
        </div>
        <div class="w3-row w3-padding">
            <input type="button" value="Setup" onclick="" class="w3-button w3-blue w3-hover-blue w3-right w3-margin" />
        </div>
    </div>
</div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<div id="LoaddingPage" style="width: 100%; height: 100%; display: none; position: fixed; top: 0px; left: 0px; background: rgba(255,255,255,0.5); z-index: 999;">
    <img src="packages/hotel/packages/golf/includes/img/loadding.gif" style="margin: 100px auto; display: block;" />
</div>
<script>
    //jQuery(this).val(number_format(to_numeric(jQuery(this).val())));
    //jQuery('#lbl_deposit_note').html(jQuery('#deposit_note').val().replace(/\r?\n/g, '<br />'));
    var DayListSelect = {};
    var LastDay = 0;
    var CURRENT_YEAR = <?php echo date('Y')?>;
    var CURRENT_MONTH = <?php echo intval(date('m')) - 1;?>;
    var CURRENT_DAY = <?php echo date('d')?>;
    var $input_count_range = 100;
    var windowscrollTop = 0;
    var CountWeek = <?php echo $this->map['count_week'];?>;
    jQuery(document).ready(function(){
        set_layout();
        jQuery("#InventoryContent").touch().on('tapAndHold',function(e, o) { OpenLightBox(); });
    });
    jQuery(window).resize(function(){
        set_layout();
    });
    /**xet keycode */
    jQuery(document).keydown(function(event){
        if(event.which=="17")
            cntrlIsPressed = true;
        if(event.which=="16")
            shiftIsPressed = true;
    });
    jQuery(document).keyup(function(){
        cntrlIsPressed = false;
        shiftIsPressed = false;
    });
    var cntrlIsPressed = false;
    var shiftIsPressed = false;
    /**xet keycode */
    jQuery(document).click(function(event) { 
        if(!jQuery(event.target).closest('.itemday').length) {
            if(jQuery('.itemday').is(":visible")) {
                jQuery('.itemday').removeClass('drive_items_select');
                DayListSelect = {};
                LastDay = 0;
            }
        }        
    });
    function SelectDay($day){
        //if(!cntrlIsPressed && !shiftIsPressed){
            jQuery('.itemday').removeClass('drive_items_select');
            DayListSelect = {};
            jQuery('#items_'+$day).addClass('drive_items_select');
            DayListSelect[$day] = jQuery('#items_'+$day).attr('lang');
            LastDay = $day;
        /*
        }else if(cntrlIsPressed && !shiftIsPressed){
            jQuery('#items_'+$day).addClass('drive_items_select');
            DayListSelect[$day] = jQuery('#items_'+$day).attr('lang');
            LastDay = $day;
        }else{
            if(LastDay == 0){
                jQuery('#items_'+$day).addClass('drive_items_select');
                DayListSelect[$day] = jQuery('#items_'+$day).attr('lang');
                LastDay = $day;
            }else{
                if($day>LastDay){
                    for($i=LastDay;$i<=$day;$i++){
                        jQuery('#items_'+$i).addClass('drive_items_select');
                        DayListSelect[$i] = jQuery('#items_'+$i).attr('lang');
                    }
                }else{
                    for($i=$day;$i<=LastDay;$i++){
                        jQuery('#items_'+$i).addClass('drive_items_select');
                        DayListSelect[$i] = jQuery('#items_'+$i).attr('lang');
                    }
                }
                LastDay = $day;
            }
        }
        */
        GetInfo();
    }
    function GetInfo(){
        $time_list = '';
        for(var $i in DayListSelect){
            $time_list += $time_list==''?DayListSelect[$i]:','+DayListSelect[$i];
        }
        if($time_list!=''){
            OpenLoaddingPage();
            <?php echo 'var block_id = '.Module::block_id().';';?>
            jQuery.ajax({
    					url:"form.php?block_id="+block_id,
    					type:"POST",
    					data:{status:'GETINFO',time:$time_list},
    					success:function(html)
                        {
                            var obj = jQuery.parseJSON(html);
                            $content = '';
                            for(var i in obj){
                                if(to_numeric(obj[i]['status'])==1){
                                    $content += '<p>Ngày '+obj[i]['in_date']+'</p>';
                                    $content += '<p>start time: '+obj[i]['data']['start_time']+' end time: '+obj[i]['data']['end_time']+'</p>';
                                    $content += '<p>Số phút trên 1 lỗ: '+obj[i]['data']['minute']+' phút</p>';
                                    $content += '<p>Số người chơi trên 1 lỗ: '+obj[i]['data']['num_people']+' người</p>';
                                    $content += '<p>ghi chú: '+obj[i]['data']['start_time'].replace(/\r?\n/g, '<br />')+'</p>';
                                    $content += '<input type="button" value="<?php echo Portal::language('edit');?>" class="w3-button w3-blue w3-hover-blue" />';
                                }else{
                                    $content += '<p>Ngày '+obj[i]['in_date']+' Chưa Setup <input type="button" value="Setup" onclick="Setup(\''+obj[i]['in_date']+'\');" class="w3-button w3-blue w3-hover-blue" /></p>';
                                }
                            }
                            jQuery("#CalendarInfo").html($content);
                            CloseLoaddingPage();
    					}
    		});
        }
    }
    function Setup(){
        
    }
    function set_layout(){
        $width = 560;
        $itemswidth = Math.floor($width/7);
        $itemsheight = ($itemswidth*1.61803) - $itemswidth; // ti le vang
        jQuery(".itemday").css('width',($itemswidth-20)+'px');
        jQuery(".itemday").css('height',($itemsheight-20)+'px');
    }
    function PrevMonth(){
        $month = jQuery('#month').val();
        $year = jQuery('#year').val();
        if(to_numeric($month)==1){
            $month = 12;
            $year = to_numeric($year)-1;
        }else{
            $month = to_numeric($month)-1;
        }
        jQuery('#month').val($month);
        jQuery('#year').val($year);
        EditgolfWorkShiftForm.submit();
    }
    function NextMonth(){
        $month = jQuery('#month').val();
        $year = jQuery('#year').val();
        if(to_numeric($month)==12){
            $month = 1;
            $year = to_numeric($year)+1;
        }else{
            $month = to_numeric($month)+1;
        }
        jQuery('#month').val($month);
        jQuery('#year').val($year);
        EditgolfWorkShiftForm.submit();
    }
    function CheckSubmit($act){
        jQuery('#act').val($act);
        EditgolfWorkShiftForm.submit();
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
    function OpenLoaddingPage(){
        jQuery("#LoaddingPage").css('display','');
    }
    function CloseLoaddingPage(){
        jQuery("#LoaddingPage").css('display','none');
    }
</script>

