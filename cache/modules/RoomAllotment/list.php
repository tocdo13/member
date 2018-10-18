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
    #ui-datepicker-div{
        z-index: 999999;
    }
    p {
        font-weight: normal;
    }
    .input_data {
        border: none;
        outline: none; 
        width: 60px; height: 32px; 
        margin: 0px!important; 
        text-align: center; cursor: pointer; 
        background: #4BC0C0;
        color: #FFFFFF;
    }
    .input_data:hover {
        border: 3px solid #FF3D67;
    }
    .select_data{
        border: 3px solid #FF3D67;
    }
</style>

<form name="ListRoomAllotmentForm" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="act" id="act" />
    <div class="w3-container w3-margin-bottom" style="max-width: 1200px; margin: 0px auto 10px;">
        <div class="w3-row">
            <h3 class="w3-left w3-margin-left" style="color: #f27173;"><i class="fa fa-braille fa-fw" style="color: #f27173;"></i> ROOM ALLOTMENT</h3>
            
            <table class="w3-right" cellpadding="10">
                <tr style="font-weight: bold;">
                    <td><select  name="view_action" id="view_action" class="w3-input w3-border" style="width: inherit; text-align: center; color: #f27173;" onchange="ChangeAction();"><?php
					if(isset($this->map['view_action_list']))
					{
						foreach($this->map['view_action_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('view_action',isset($this->map['view_action'])?$this->map['view_action']:''))
                    echo "<script>$('view_action').value = \"".addslashes(URL::get('view_action',isset($this->map['view_action'])?$this->map['view_action']:''))."\";</script>";
                    ?>
	</select></td>
                </tr>
            </table>
        </div>
        <div class="w3-row w3-padding"><?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><br clear="all"><?php }?></div>
        <div class="w3-row">
            <table class="w3-left">
                <tr>
                    <td>(15 days default)</td>
                    <td>Start Date</td>
                    <td><input  name="start_date" id="start_date" class="w3-input w3-border" autocomplete="OFF" maxlength="12" placeholder="Start Date" style="width: 100px;" readonly="" onchange="ListRoomAllotmentForm.submit();" / type ="text" value="<?php echo String::html_normalize(URL::get('start_date'));?>"></td>
                    <!--
                    <td>End Date</td>
                    <td><input  name="end_date" id="end_date" class="w3-input w3-border" autocomplete="OFF" maxlength="12" placeholder="End Date" style="width: 100px;" readonly="" / type ="text" value="<?php echo String::html_normalize(URL::get('end_date'));?>"></td>
                    -->
                    <td>Channel</td>
                    <td><select  name="customer_id" id="customer_id" class="w3-input w3-border" style="width: 100px;" onchange="ListRoomAllotmentForm.submit();"><?php
					if(isset($this->map['customer_id_list']))
					{
						foreach($this->map['customer_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('customer_id',isset($this->map['customer_id'])?$this->map['customer_id']:''))
                    echo "<script>$('customer_id').value = \"".addslashes(URL::get('customer_id',isset($this->map['customer_id'])?$this->map['customer_id']:''))."\";</script>";
                    ?>
	</select></td>
                    <td>
                        <div class="w3-button w3-blue w3-hover-blue w3-right" style="font-weight: normal;" onclick="ListRoomAllotmentForm.submit();">
                            <i class="fa fa-fw fa-search"></i> View In Times
                        </div>
                    </td>
                </tr>
            </table>
            <div class="w3-button w3-blue w3-hover-blue w3-right w3-margin-right edit_allotment" style="font-weight: normal;" onclick="SaveAllotment();">
                <i class="fa fa-fw fa-save"></i> Save
            </div>
        </div>
        <div class="w3-row">
            <table class="w3-right booking">
                <tr>
                    <td>Arrival Time</td>
                    <td><input  name="arrival_time" id="arrival_time" class="w3-input w3-border" autocomplete="OFF" maxlength="12" placeholder="Arrival Time" style="width: 100px;" readonly="" / type ="text" value="<?php echo String::html_normalize(URL::get('arrival_time'));?>"></td>
                    <td>Departure Time</td>
                    <td><input  name="departure_time" id="departure_time" class="w3-input w3-border" autocomplete="OFF" maxlength="12" placeholder="Departure Time" style="width: 100px;" readonly="" / type ="text" value="<?php echo String::html_normalize(URL::get('departure_time'));?>"></td>
                    <td>
                        <div class="w3-button w3-blue w3-hover-blue w3-right w3-margin-right booking" style="font-weight: normal;" onclick="CreateReservation();">
                            <i class="fa fa-fw fa-plus"></i> Create Booking
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="w3-row">
            <?php if(isset($this->map['auto_reset']) and is_array($this->map['auto_reset'])){ foreach($this->map['auto_reset'] as $key1=>&$item1){if($key1!='current'){$this->map['auto_reset']['current'] = &$item1;?>
            <p class="w3-text-pink">Hệ Thống đã tư động reset số lượng phòng trống của <?php echo $this->map['auto_reset']['current']['customer_name'];?> thuộc hạng phòng <?php echo $this->map['auto_reset']['current']['room_level_name'];?> ngày <?php echo $this->map['auto_reset']['current']['in_date'];?> từ <?php echo $this->map['auto_reset']['current']['avail'];?> về 0, <span class="w3-text-blue" onclick="RestoreAvail(<?php echo $this->map['auto_reset']['current']['id'];?>);">ấn vào đây để khôi phục</span></p>
            <?php }}unset($this->map['auto_reset']['current']);} ?>
        </div>
    </div>
    <div class="w3-row w3-margin w3-padding" style="min-width: 1250px; margin: 0px auto 10px;">
        <div class="w3-row">
            <div class="w3-button w3-green w3-hover-green w3-margin" style="font-weight: normal;" onclick="CreateAllotmentAll();">
                <i class="fa fa-fw fa-plus"></i> Create Allotments
            </div>
        </div>
        <table class="w3-table-all w3-white" style="margin: 0px auto 50px;">
            <tr>
                <th colspan="3"><b style="color: #f27173;"><i class="fa fa-braille fa-fw" style="color: #f27173;"></i> ROOM ALLOTMENT</b><br /><span>Room Level</span><br /><span style="padding-left: 10px; font-weight: normal;">(Channel) / Dates</span></th>
                <?php if(isset($this->map['timeline']) and is_array($this->map['timeline'])){ foreach($this->map['timeline'] as $key2=>&$item2){if($key2!='current'){$this->map['timeline']['current'] = &$item2;?>
                <td style="width: 60px; cursor: pointer; text-align: center; border-left: 1px solid #CCC; background: <?php echo $this->map['timeline']['current']['background'];?>!important;"><?php echo $this->map['timeline']['current']['weekday'];?><br /><span style="font-size: 15px;"><b><?php echo $this->map['timeline']['current']['mday'];?></b></span><br /><?php echo $this->map['timeline']['current']['month'];?></td>
                <?php }}unset($this->map['timeline']['current']);} ?>
            </tr>
            <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key3=>&$item3){if($key3!='current'){$this->map['items']['current'] = &$item3;?>
                <tr style="background: #FFF;">
                    <td colspan="3">
                        <p><b style="color: #f27173;"><?php echo $this->map['items']['current']['name'];?> (<?php echo $this->map['items']['current']['code'];?>)</b></p>
                        <p><span onclick="CreateAllotment('<?php echo $this->map['items']['current']['id'];?>','<?php echo $this->map['items']['current']['name'];?>','<?php echo $this->map['items']['current']['code'];?>');" class="w3-border" style="font-weight: normal; padding: 3px; margin-left: 7px; border-radius: 5px; cursor: pointer; color: #4BC0C0;">Create Allotment</span></p>
                    </td>
                    <?php if(isset($this->map['items']['current']['timeline']) and is_array($this->map['items']['current']['timeline'])){ foreach($this->map['items']['current']['timeline'] as $key4=>&$item4){if($key4!='current'){$this->map['items']['current']['timeline']['current'] = &$item4;?>
                    <td style="width: 60px; cursor: pointer; text-align: center; border-left: 1px solid #CCC; color: rgb(255, 99, 132);">
                        <?php echo $this->map['items']['current']['timeline']['current']['avail'];?>
                    </td>
                    <?php }}unset($this->map['items']['current']['timeline']['current']);} ?>
                </tr>
                <?php if(isset($this->map['items']['current']['channel_list']) and is_array($this->map['items']['current']['channel_list'])){ foreach($this->map['items']['current']['channel_list'] as $key5=>&$item5){if($key5!='current'){$this->map['items']['current']['channel_list']['current'] = &$item5;?>
                <tr style="background: #FFF;">
                    <td rowspan="3" style="width: 10px; background: rgba(255, 205, 86, 0.3)!important; margin: 0px!important; padding: 0px!important;"></td>
                    <td rowspan="3"><b><?php echo $this->map['items']['current']['channel_list']['current']['name'];?></b></td>
                    <td rowspan="3" style="width: 60px; border-left: 1px solid #CCC; text-align: center;">
                        <input lang="<?php echo $this->map['items']['current']['channel_list']['current']['id'];?>" id="quantity_<?php echo $this->map['items']['current']['id'];?>_<?php echo $this->map['items']['current']['channel_list']['current']['id'];?>" type="text" name="booking_quantity[<?php echo $this->map['items']['current']['id'];?>][channel][<?php echo $this->map['items']['current']['channel_list']['current']['id'];?>][quantity]" class="w3-border input_number booking booking_quantity" title="Booking quantity" autocomplete="off" placeholder="" style="outline: none; width: 50px; height: 50px; font-size: 17px; font-weight: bold; text-align: center;" />
                    </td>
                    <?php if(isset($this->map['items']['current']['channel_list']['current']['timeline']) and is_array($this->map['items']['current']['channel_list']['current']['timeline'])){ foreach($this->map['items']['current']['channel_list']['current']['timeline'] as $key6=>&$item6){if($key6!='current'){$this->map['items']['current']['channel_list']['current']['timeline']['current'] = &$item6;?>
                    <td style="width: 60px; text-align: center; border-left: 1px solid #CCC; padding: 0px!important; <?php 
				if(($this->map['items']['current']['channel_list']['current']['timeline']['current']['is_allotment']!=0))
				{?>background: rgba(75, 192, 192,0.3);
				<?php
				}
				?>">
                        <?php 
				if(($this->map['items']['current']['channel_list']['current']['timeline']['current']['is_allotment']!=0))
				{?>
                        <input name="allotment[<?php echo $this->map['items']['current']['channel_list']['current']['timeline']['current']['is_allotment'];?>][timeline][<?php echo $this->map['items']['current']['channel_list']['current']['timeline']['current']['id'];?>][avail]" lang="<?php echo $this->map['items']['current']['channel_list']['current']['timeline']['current']['is_allotment'];?>,<?php echo $this->map['items']['current']['channel_list']['current']['timeline']['current']['id'];?>,<?php echo $this->map['items']['current']['channel_list']['current']['id'];?>,<?php echo $this->map['items']['current']['id'];?>" onclick="CheckAction(<?php echo $this->map['items']['current']['channel_list']['current']['timeline']['current']['is_allotment'];?>,<?php echo $this->map['items']['current']['channel_list']['current']['timeline']['current']['id'];?>,<?php echo $this->map['items']['current']['channel_list']['current']['id'];?>,<?php echo $this->map['items']['current']['id'];?>);" readonly="" class="input_data input_number" id="allotment_<?php echo $this->map['items']['current']['channel_list']['current']['timeline']['current']['is_allotment'];?>_timeline_<?php echo $this->map['items']['current']['channel_list']['current']['timeline']['current']['id'];?>_avail" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val())));" type="text" value="<?php echo $this->map['items']['current']['channel_list']['current']['timeline']['current']['avail'];?>" title="Availability <?php echo $this->map['items']['current']['channel_list']['current']['name'];?> Date <?php echo $this->map['items']['current']['channel_list']['current']['timeline']['current']['in_date'];?> : <?php echo $this->map['items']['current']['channel_list']['current']['timeline']['current']['avail'];?>" autocomplete="OFF" />
                            <!--name="allotment[<?php echo $this->map['items']['current']['channel_list']['current']['timeline']['current']['is_allotment'];?>][timeline][<?php echo $this->map['items']['current']['channel_list']['current']['timeline']['current']['id'];?>][avail]"-->
                        
				<?php
				}
				?>
                    </td>
                    <?php }}unset($this->map['items']['current']['channel_list']['current']['timeline']['current']);} ?>
                </tr>
                <tr style="background: #FFF;">
                    <?php if(isset($this->map['items']['current']['channel_list']['current']['timeline']) and is_array($this->map['items']['current']['channel_list']['current']['timeline'])){ foreach($this->map['items']['current']['channel_list']['current']['timeline'] as $key7=>&$item7){if($key7!='current'){$this->map['items']['current']['channel_list']['current']['timeline']['current'] = &$item7;?>
                    <td style="width: 60px; cursor: pointer; text-align: center; border-left: 1px solid #CCC; padding: 0px!important; <?php 
				if(($this->map['items']['current']['channel_list']['current']['timeline']['current']['is_allotment']!=0))
				{?>background: rgba(75, 192, 192,0.3);
				<?php
				}
				?>">
                        <?php 
				if(($this->map['items']['current']['channel_list']['current']['timeline']['current']['is_allotment']!=0))
				{?>
                        <input name="allotment[<?php echo $this->map['items']['current']['channel_list']['current']['timeline']['current']['is_allotment'];?>][timeline][<?php echo $this->map['items']['current']['channel_list']['current']['timeline']['current']['id'];?>][rate]" lang="<?php echo $this->map['items']['current']['channel_list']['current']['timeline']['current']['is_allotment'];?>,<?php echo $this->map['items']['current']['channel_list']['current']['timeline']['current']['id'];?>,<?php echo $this->map['items']['current']['channel_list']['current']['id'];?>,<?php echo $this->map['items']['current']['id'];?>" onclick="CheckAction(<?php echo $this->map['items']['current']['channel_list']['current']['timeline']['current']['is_allotment'];?>,<?php echo $this->map['items']['current']['channel_list']['current']['timeline']['current']['id'];?>,<?php echo $this->map['items']['current']['channel_list']['current']['id'];?>,<?php echo $this->map['items']['current']['id'];?>);" readonly="" class="input_data input_number" id="allotment_<?php echo $this->map['items']['current']['channel_list']['current']['timeline']['current']['is_allotment'];?>_timeline_<?php echo $this->map['items']['current']['channel_list']['current']['timeline']['current']['id'];?>_rate" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val())));" type="text" value="<?php echo $this->map['items']['current']['channel_list']['current']['timeline']['current']['rate'];?>" title="Rate <?php echo $this->map['items']['current']['channel_list']['current']['name'];?> Date <?php echo $this->map['items']['current']['channel_list']['current']['timeline']['current']['in_date'];?> : <?php echo $this->map['items']['current']['channel_list']['current']['timeline']['current']['rate'];?>" autocomplete="OFF" />
                        
				<?php
				}
				?>
                    </td>
                    <?php }}unset($this->map['items']['current']['channel_list']['current']['timeline']['current']);} ?>
                </tr>
                <tr style="background: #FFF;">
                    <?php if(isset($this->map['items']['current']['channel_list']['current']['timeline']) and is_array($this->map['items']['current']['channel_list']['current']['timeline'])){ foreach($this->map['items']['current']['channel_list']['current']['timeline'] as $key8=>&$item8){if($key8!='current'){$this->map['items']['current']['channel_list']['current']['timeline']['current'] = &$item8;?>
                    <td style="width: 60px; cursor: pointer; text-align: center; border-left: 1px solid #CCC; padding: 0px!important; <?php 
				if(($this->map['items']['current']['channel_list']['current']['timeline']['current']['is_allotment']!=0))
				{?>background: rgba(75, 192, 192,0.3);
				<?php
				}
				?>">
                        <?php 
				if(($this->map['items']['current']['channel_list']['current']['timeline']['current']['is_allotment']!=0))
				{?>
                        <input name="allotment[<?php echo $this->map['items']['current']['channel_list']['current']['timeline']['current']['is_allotment'];?>][timeline][<?php echo $this->map['items']['current']['channel_list']['current']['timeline']['current']['id'];?>][confirm]" lang="<?php echo $this->map['items']['current']['channel_list']['current']['timeline']['current']['is_allotment'];?>,<?php echo $this->map['items']['current']['channel_list']['current']['timeline']['current']['id'];?>,<?php echo $this->map['items']['current']['channel_list']['current']['id'];?>,<?php echo $this->map['items']['current']['id'];?>" onclick="CheckAction(<?php echo $this->map['items']['current']['channel_list']['current']['timeline']['current']['is_allotment'];?>,<?php echo $this->map['items']['current']['channel_list']['current']['timeline']['current']['id'];?>,<?php echo $this->map['items']['current']['channel_list']['current']['id'];?>,<?php echo $this->map['items']['current']['id'];?>);" id="allotment_<?php echo $this->map['items']['current']['channel_list']['current']['timeline']['current']['is_allotment'];?>_timeline_<?php echo $this->map['items']['current']['channel_list']['current']['timeline']['current']['id'];?>_confirm" type="checkbox" value="<?php echo $this->map['items']['current']['channel_list']['current']['timeline']['current']['confirm'];?>" title="Confirm <?php echo $this->map['items']['current']['channel_list']['current']['name'];?> Date <?php echo $this->map['items']['current']['channel_list']['current']['timeline']['current']['in_date'];?>" <?php 
				if(($this->map['items']['current']['channel_list']['current']['timeline']['current']['confirm']==1))
				{?>checked="checked"
				<?php
				}
				?>/>
                        
				<?php
				}
				?>
                    </td>
                    <?php }}unset($this->map['items']['current']['channel_list']['current']['timeline']['current']);} ?>
                </tr>
                <?php }}unset($this->map['items']['current']['channel_list']['current']);} ?>
            <?php }}unset($this->map['items']['current']);} ?>
        </table>
    </div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<div id="LightBoxCentral" style="width: 100%; height: 100%; display: none; overflow-y: auto; position: fixed; top: 0px; left: 0px; background: rgba(0,0,0,0.5); z-index: 999;">
    <div style="width: 100%; height: 100%; position: absolute; top: 0px; left: 0px; z-index: -1;" onclick="CloseLightBox();"></div>
    <div id="LightBoxCentralContent" style="min-width: 320px; max-width: 960px; min-height: 50px; z-index: 99; margin: 100px auto; background: white;">
        
    </div>
</div>
<script>
    var windowscrollTop = 0;
    var customerjs = <?php echo $this->map['customerjs'];?>;
    var GroupBooking = {};
    jQuery(document).ready(function(){
        jQuery("#start_date").datepicker();
        jQuery("#end_date").datepicker();
        jQuery("#arrival_time").datepicker();
        jQuery("#departure_time").datepicker();
        ChangeAction();
    });
    function RestoreAvail($auto_reset_avail_id){
        <?php echo 'var block_id = '.Module::block_id().';';?>
        jQuery.ajax({
					url:"form.php?block_id="+block_id,
					type:"POST",
					data:{status:'RESTORE',id:$auto_reset_avail_id},
					success:function(html)
                    {
                        if(html.trim()!=''){
                            alert(html.trim());
                            $start_date = '<?php echo Url::get('start_date')?Url::get('start_date'):''; ?>';
                            if($start_date=='')
                                location.href='?page=room_allotment';
                            else
                                location.href='?page=room_allotment&start_date='+$start_date;
                        }
					}
		});
    }
    function ChangeAction(){
        if(jQuery("#view_action").val()=='VIEW' || jQuery("#view_action").val()=='EDIT'){
            jQuery('.booking').css('display','none');
            jQuery("#arrival_time").val('');
            jQuery("#departure_time").val('');
            if(jQuery("#view_action").val()=='EDIT'){
                jQuery('.edit_allotment').css('display','');
            }else{
                jQuery('.edit_allotment').css('display','none');
            }
        }else{
            jQuery('.booking').css('display','');
            jQuery('.edit_allotment').css('display','none');
        }
    }
    function CheckAction($allotment_id,$time,$customer_id,$room_level_id){
        if(jQuery("#view_action").val()=='VIEW'){
            window.location.href = '?page=room_allotment&cmd=edit&id='+$allotment_id;
            jQuery(".input_data").attr('readonly','readonly');
        }
        else if(jQuery("#view_action").val()=='EDIT'){
            jQuery(".input_data").removeAttr('readonly');
        }
        else if(jQuery("#view_action").val()=='BOOKING'){
            jQuery(".input_data").attr('readonly','readonly');
            /*
            jQuery(".input_data").attr('readonly','readonly');
            if(!GroupBooking[$customer_id]){
                jQuery(".input_data").removeClass('select_data');
                jQuery(".input_data").removeAttr('name');
                GroupBooking = {};
                GroupBooking[$customer_id] = $customer_id;
            }
            if(jQuery("#allotment_"+$allotment_id+"_timeline_"+$time+"_avail").attr('name')==undefined){
                jQuery("#allotment_"+$allotment_id+"_timeline_"+$time+"_avail").attr("name","allotment["+$allotment_id+"][timeline]["+$time+"][avail]");
                jQuery("#allotment_"+$allotment_id+"_timeline_"+$time+"_avail").addClass('select_data');
                jQuery("#allotment_"+$allotment_id+"_timeline_"+$time+"_rate").attr("name","allotment["+$allotment_id+"][timeline]["+$time+"][rate]");
                jQuery("#allotment_"+$allotment_id+"_timeline_"+$time+"_rate").addClass('select_data');
            }else{
                jQuery("#allotment_"+$allotment_id+"_timeline_"+$time+"_avail").removeAttr('name');
                jQuery("#allotment_"+$allotment_id+"_timeline_"+$time+"_avail").removeClass('select_data');
                jQuery("#allotment_"+$allotment_id+"_timeline_"+$time+"_rate").removeAttr('name');
                jQuery("#allotment_"+$allotment_id+"_timeline_"+$time+"_rate").removeClass('select_data');
            }
            */
        }
    }
    function CreateReservation(){
        $check = true;
        if(jQuery("#arrival_time").val().trim()==''){
            check = false;
            mess = 'Nhập thiếu dữ liệu';
        }
        if(jQuery("#departure_time").val().trim()==''){
            check = false;
            mess = 'Nhập thiếu dữ liệu';
        }
        if(count_date(jQuery("#arrival_time").val().trim(), jQuery("#departure_time").val().trim())<0){
            check = false;
            mess = 'Ngày CheckIn phải nhỏ hơn hoặc bằng ngày CheckOUT';
        }
        if($check){
            var $timeline = {};
            var $customer = {};
            var $check_customer = 0;
            var std =jQuery("#arrival_time").val().trim().split("/");
    	    var ed =jQuery("#departure_time").val().trim().split("/");
            var arrival_time = Date.parse(std[1]+"/"+std[0]+"/"+std[2]) / 1000;
            var departure_time = Date.parse(ed[1]+"/"+ed[0]+"/"+ed[2]) / 1000;
            if(arrival_time==departure_time)
                $timeline[arrival_time] = arrival_time;
            else{
                for(var $i=arrival_time;$i<departure_time;$i+=86400){
                    $timeline[$i] = $i;
                }
            }
            
            jQuery(".input_data").each(function(){
                $id = this.id;
                $lang = this.lang.split(',');
                $allotment_id = $lang[0];
                $time = $lang[1];
                $customer_id = $lang[2];
                $room_level_id = $lang[3];
                if(to_numeric(jQuery("#quantity_"+$room_level_id+"_"+$customer_id).val())>0 && $timeline[$time]){
                    if($check_customer==0){
                        $customer[$customer_id] = $customer_id;
                        $check_customer = 1;
                    }else{
                        if(!$customer[$customer_id])
                        {
                            $check = false;
                            alert('Không được đặt nhiều nguồn khách trong cùng 1 booking!');
                            return false;
                        }
                    }
                    if(to_numeric(jQuery("#allotment_"+$allotment_id+"_timeline_"+$time+"_avail").val())<to_numeric(jQuery("#quantity_"+$room_level_id+"_"+$customer_id).val())){
                        $check = false;
                        alert('Không được đặt quá số lượng phòng trống trong allotment!');
                        return false;
                    }
                }
            });
        }
        if($check){
            jQuery("#act").val('CREATE_BOOKING');
            ListRoomAllotmentForm.submit();
        }
    }
    function SaveAllotment(){
        jQuery("#act").val('SAVE_ALLOTMENT');
        ListRoomAllotmentForm.submit();
    }
    function count_date(start_day, end_day)
    {
    	var std =start_day.split("/");
    	var ed =end_day.split("/");
    	return (Date.parse(ed[1]+"/"+ed[0]+"/"+ed[2])-Date.parse(std[1]+"/"+std[0]+"/"+std[2]))/86400000;
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
    function CreateAllotment($room_level_id,$room_level_name,$room_level_code){
        $content = '<div class="w3-container w3-padding w3-text-dark-grey">';
            $content += '<div class="w3-row w3-text-green">';
                $content += '<h6 style="text-transform: uppercase;"><i class="fa fa-lg fa-fw fa-bolt"></i> Create Room Allotment in '+$room_level_name+' ('+$room_level_code+')</h6>';
            $content += '</div>';
            $content += '<hr />';
            $content += '<div class="w3-row w3-text-dark-grey">';
                    for(var i in customerjs){
                        $content += '<table class="w3-table">';
                            $content += '<tr style="cursor: pointer;">';
                                $content += '<th style="width: 30px; text-align: center;"><i class="fa fa-link fa-fw w3-text-yellow"></i></th>';
                                $content += '<th><span class="w3-text-blue">'+customerjs[i]['name']+'</span></th>';
                                $content += '<th style="width: 150px;">'+customerjs[i]['code']+'</th>';
                                $content += '<th style="width: 150px;"><i class="fa fa-tags fa-fw"></i>'+customerjs[i]['group_name']+'</th>';
                                $content += '<th style="width: 30px; text-align: center;">';
                                    $content += '<div onclick="window.location.href=\'?page=room_allotment&cmd=add&customer_id='+customerjs[i]['id']+'&room_level_id='+$room_level_id+'\'" class="w3-button w3-white w3-hover-white w3-border" style="font-weight: normal; color: #f27173!important;">';
                                        $content += '<i class="fa fa-arrow-right fa-fw" style="color: #f27173!important;"></i> Create';
                                    $content += '</div>';
                                $content += '</th>';
                            $content += '</tr>';
                         $content += '</table>';
                         $content += '<hr />';
                    }
            $content += '</div>';
            $content += '<div class="w3-row w3-text-dark-grey">';
                $content += '<div onclick="CloseLightBox();" class="w3-button w3-white w3-hover-white w3-margin w3-right" style="font-weight: normal;color: #f27173!important;">';
                    $content += 'Cancel';
                $content += '</div>';
            $content += '</div>';
        $content += '</div>';
        document.getElementById('LightBoxCentralContent').innerHTML = $content;
        OpenLightBox();
    }
    function CreateAllotmentAll(){
        $content = '<div class="w3-container w3-padding w3-text-dark-grey">';
            $content += '<div class="w3-row w3-text-green">';
                $content += '<h6 style="text-transform: uppercase;"><i class="fa fa-lg fa-fw fa-bolt"></i> Create Room Allotment </h6>';
            $content += '</div>';
            $content += '<hr />';
            $content += '<div class="w3-row w3-text-dark-grey">';
                    for(var i in customerjs){
                        $content += '<table class="w3-table">';
                            $content += '<tr style="cursor: pointer;">';
                                $content += '<th style="width: 30px; text-align: center;"><i class="fa fa-link fa-fw w3-text-yellow"></i></th>';
                                $content += '<th><span class="w3-text-blue">'+customerjs[i]['name']+'</span></th>';
                                $content += '<th style="width: 150px;">'+customerjs[i]['code']+'</th>';
                                $content += '<th style="width: 150px;"><i class="fa fa-tags fa-fw"></i>'+customerjs[i]['group_name']+'</th>';
                                $content += '<th style="width: 30px; text-align: center;">';
                                    $content += '<div onclick="window.location.href=\'?page=room_allotment&cmd=add_all&customer_id='+customerjs[i]['id']+'\'" class="w3-button w3-white w3-hover-white w3-border" style="font-weight: normal; color: #f27173!important;">';
                                        $content += '<i class="fa fa-arrow-right fa-fw" style="color: #f27173!important;"></i> Create';
                                    $content += '</div>';
                                $content += '</th>';
                            $content += '</tr>';
                         $content += '</table>';
                         $content += '<hr />';
                    }
            $content += '</div>';
            $content += '<div class="w3-row w3-text-dark-grey">';
                $content += '<div onclick="CloseLightBox();" class="w3-button w3-white w3-hover-white w3-margin w3-right" style="font-weight: normal;color: #f27173!important;">';
                    $content += 'Cancel';
                $content += '</div>';
            $content += '</div>';
        $content += '</div>';
        document.getElementById('LightBoxCentralContent').innerHTML = $content;
        OpenLightBox();
    }
</script>