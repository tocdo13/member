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
                    <td><select name="view_action" id="view_action" class="w3-input w3-border" style="width: inherit; text-align: center; color: #f27173;" onchange="ChangeAction();"></select></td>
                </tr>
            </table>
        </div>
        <div class="w3-row w3-padding"><?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><br clear="all"><?php }?></div>
        <div class="w3-row">
            <table class="w3-left">
                <tr>
                    <td>(15 days default)</td>
                    <td>Start Date</td>
                    <td><input name="start_date" type="text" id="start_date" class="w3-input w3-border" autocomplete="OFF" maxlength="12" placeholder="Start Date" style="width: 100px;" readonly="" onchange="ListRoomAllotmentForm.submit();" /></td>
                    <!--
                    <td>End Date</td>
                    <td><input name="end_date" type="text" id="end_date" class="w3-input w3-border" autocomplete="OFF" maxlength="12" placeholder="End Date" style="width: 100px;" readonly="" /></td>
                    -->
                    <td>Channel</td>
                    <td><select name="customer_id" id="customer_id" class="w3-input w3-border" style="width: 100px;" onchange="ListRoomAllotmentForm.submit();"></select></td>
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
                    <td><input name="arrival_time" type="text" id="arrival_time" class="w3-input w3-border" autocomplete="OFF" maxlength="12" placeholder="Arrival Time" style="width: 100px;" readonly="" /></td>
                    <td>Departure Time</td>
                    <td><input name="departure_time" type="text" id="departure_time" class="w3-input w3-border" autocomplete="OFF" maxlength="12" placeholder="Departure Time" style="width: 100px;" readonly="" /></td>
                    <td>
                        <div class="w3-button w3-blue w3-hover-blue w3-right w3-margin-right booking" style="font-weight: normal;" onclick="CreateReservation();">
                            <i class="fa fa-fw fa-plus"></i> Create Booking
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="w3-row">
            <!--LIST:auto_reset-->
            <p class="w3-text-pink">Hệ Thống đã tư động reset số lượng phòng trống của [[|auto_reset.customer_name|]] thuộc hạng phòng [[|auto_reset.room_level_name|]] ngày [[|auto_reset.in_date|]] từ [[|auto_reset.avail|]] về 0, <span class="w3-text-blue" onclick="RestoreAvail([[|auto_reset.id|]]);">ấn vào đây để khôi phục</span></p>
            <!--/LIST:auto_reset-->
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
                <!--LIST:timeline-->
                <td style="width: 60px; cursor: pointer; text-align: center; border-left: 1px solid #CCC; background: [[|timeline.background|]]!important;">[[|timeline.weekday|]]<br /><span style="font-size: 15px;"><b>[[|timeline.mday|]]</b></span><br />[[|timeline.month|]]</td>
                <!--/LIST:timeline-->
            </tr>
            <!--LIST:items-->
                <tr style="background: #FFF;">
                    <td colspan="3">
                        <p><b style="color: #f27173;">[[|items.name|]] ([[|items.code|]])</b></p>
                        <p><span onclick="CreateAllotment('[[|items.id|]]','[[|items.name|]]','[[|items.code|]]');" class="w3-border" style="font-weight: normal; padding: 3px; margin-left: 7px; border-radius: 5px; cursor: pointer; color: #4BC0C0;">Create Allotment</span></p>
                    </td>
                    <!--LIST:items.timeline-->
                    <td style="width: 60px; cursor: pointer; text-align: center; border-left: 1px solid #CCC; color: rgb(255, 99, 132);">
                        [[|items.timeline.avail|]]
                    </td>
                    <!--/LIST:items.timeline-->
                </tr>
                <!--LIST:items.channel_list-->
                <tr style="background: #FFF;">
                    <td rowspan="3" style="width: 10px; background: rgba(255, 205, 86, 0.3)!important; margin: 0px!important; padding: 0px!important;"></td>
                    <td rowspan="3"><b>[[|items.channel_list.name|]]</b></td>
                    <td rowspan="3" style="width: 60px; border-left: 1px solid #CCC; text-align: center;">
                        <input lang="[[|items.channel_list.id|]]" id="quantity_[[|items.id|]]_[[|items.channel_list.id|]]" type="text" name="booking_quantity[[[|items.id|]]][channel][[[|items.channel_list.id|]]][quantity]" class="w3-border input_number booking booking_quantity" title="Booking quantity" autocomplete="off" placeholder="" style="outline: none; width: 50px; height: 50px; font-size: 17px; font-weight: bold; text-align: center;" />
                    </td>
                    <!--LIST:items.channel_list.timeline-->
                    <td style="width: 60px; text-align: center; border-left: 1px solid #CCC; padding: 0px!important; <!--IF:cond([[=items.channel_list.timeline.is_allotment=]]!=0)-->background: rgba(75, 192, 192,0.3);<!--/IF:cond-->">
                        <!--IF:cond([[=items.channel_list.timeline.is_allotment=]]!=0)-->
                        <input name="allotment[[[|items.channel_list.timeline.is_allotment|]]][timeline][[[|items.channel_list.timeline.id|]]][avail]" lang="[[|items.channel_list.timeline.is_allotment|]],[[|items.channel_list.timeline.id|]],[[|items.channel_list.id|]],[[|items.id|]]" onclick="CheckAction([[|items.channel_list.timeline.is_allotment|]],[[|items.channel_list.timeline.id|]],[[|items.channel_list.id|]],[[|items.id|]]);" readonly="" class="input_data input_number" id="allotment_[[|items.channel_list.timeline.is_allotment|]]_timeline_[[|items.channel_list.timeline.id|]]_avail" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val())));" type="text" value="[[|items.channel_list.timeline.avail|]]" title="Availability [[|items.channel_list.name|]] Date [[|items.channel_list.timeline.in_date|]] : [[|items.channel_list.timeline.avail|]]" autocomplete="OFF" />
                            <!--name="allotment[[[|items.channel_list.timeline.is_allotment|]]][timeline][[[|items.channel_list.timeline.id|]]][avail]"-->
                        <!--/IF:cond-->
                    </td>
                    <!--/LIST:items.channel_list.timeline-->
                </tr>
                <tr style="background: #FFF;">
                    <!--LIST:items.channel_list.timeline-->
                    <td style="width: 60px; cursor: pointer; text-align: center; border-left: 1px solid #CCC; padding: 0px!important; <!--IF:cond([[=items.channel_list.timeline.is_allotment=]]!=0)-->background: rgba(75, 192, 192,0.3);<!--/IF:cond-->">
                        <!--IF:cond([[=items.channel_list.timeline.is_allotment=]]!=0)-->
                        <input name="allotment[[[|items.channel_list.timeline.is_allotment|]]][timeline][[[|items.channel_list.timeline.id|]]][rate]" lang="[[|items.channel_list.timeline.is_allotment|]],[[|items.channel_list.timeline.id|]],[[|items.channel_list.id|]],[[|items.id|]]" onclick="CheckAction([[|items.channel_list.timeline.is_allotment|]],[[|items.channel_list.timeline.id|]],[[|items.channel_list.id|]],[[|items.id|]]);" readonly="" class="input_data input_number" id="allotment_[[|items.channel_list.timeline.is_allotment|]]_timeline_[[|items.channel_list.timeline.id|]]_rate" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val())));" type="text" value="[[|items.channel_list.timeline.rate|]]" title="Rate [[|items.channel_list.name|]] Date [[|items.channel_list.timeline.in_date|]] : [[|items.channel_list.timeline.rate|]]" autocomplete="OFF" />
                        <!--/IF:cond-->
                    </td>
                    <!--/LIST:items.channel_list.timeline-->
                </tr>
                <tr style="background: #FFF;">
                    <!--LIST:items.channel_list.timeline-->
                    <td style="width: 60px; cursor: pointer; text-align: center; border-left: 1px solid #CCC; padding: 0px!important; <!--IF:cond([[=items.channel_list.timeline.is_allotment=]]!=0)-->background: rgba(75, 192, 192,0.3);<!--/IF:cond-->">
                        <!--IF:cond([[=items.channel_list.timeline.is_allotment=]]!=0)-->
                        <input name="allotment[[[|items.channel_list.timeline.is_allotment|]]][timeline][[[|items.channel_list.timeline.id|]]][confirm]" lang="[[|items.channel_list.timeline.is_allotment|]],[[|items.channel_list.timeline.id|]],[[|items.channel_list.id|]],[[|items.id|]]" onclick="CheckAction([[|items.channel_list.timeline.is_allotment|]],[[|items.channel_list.timeline.id|]],[[|items.channel_list.id|]],[[|items.id|]]);" id="allotment_[[|items.channel_list.timeline.is_allotment|]]_timeline_[[|items.channel_list.timeline.id|]]_confirm" type="checkbox" value="[[|items.channel_list.timeline.confirm|]]" title="Confirm [[|items.channel_list.name|]] Date [[|items.channel_list.timeline.in_date|]]" <!--IF:cond([[=items.channel_list.timeline.confirm=]]==1)-->checked="checked"<!--/IF:cond-->/>
                        <!--/IF:cond-->
                    </td>
                    <!--/LIST:items.channel_list.timeline-->
                </tr>
                <!--/LIST:items.channel_list-->
            <!--/LIST:items-->
        </table>
    </div>
</form>
<div id="LightBoxCentral" style="width: 100%; height: 100%; display: none; overflow-y: auto; position: fixed; top: 0px; left: 0px; background: rgba(0,0,0,0.5); z-index: 999;">
    <div style="width: 100%; height: 100%; position: absolute; top: 0px; left: 0px; z-index: -1;" onclick="CloseLightBox();"></div>
    <div id="LightBoxCentralContent" style="min-width: 320px; max-width: 960px; min-height: 50px; z-index: 99; margin: 100px auto; background: white;">
        
    </div>
</div>
<script>
    var windowscrollTop = 0;
    var customerjs = [[|customerjs|]];
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