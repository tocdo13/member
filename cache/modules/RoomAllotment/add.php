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
</style>

<form name="AddRoomAllotmentForm" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="act" id="act" />
    <div class="w3-container w3-margin-bottom" style="max-width: 1200px; margin: 0px auto;">
        <div class="w3-row">
            <h3 class="w3-left w3-margin-left" style="color: #f27173;"><i class="fa fa-braille fa-fw" style="color: #f27173;"></i> <?php echo Portal::language('create_allotment');?></h3>
            <div class="w3-button w3-blue w3-hover-blue w3-right w3-margin-right" style="font-weight: normal;" onclick="CheckSubmit();">
                <i class="fa fa-fw fa-save"></i> Save
            </div>
        </div>
        <div class="w3-row w3-padding"><?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><br clear="all"><?php }?></div>
        <div class="w3-row w3-margin-top">
            <div class="w3-third w3-padding">
                
                <p><b class="w3-text-blue"><?php echo Portal::language('customer');?></b></p>
                <p id="lblCustomercode"><?php echo Portal::language('code');?>: <b style="color: #f27173;"><?php echo $this->map['customer_code'];?></b></p>
                <p id="lblCustomerName"><?php echo Portal::language('name');?>: <b style="color: #f27173;"><?php echo $this->map['customer_name'];?></b></p>
                <input  name="customer_id" id="customer_id" style="display: none;" / type ="hidden" value="<?php echo String::html_normalize(URL::get('customer_id'));?>">
                
                <p><b class="w3-text-blue"><?php echo Portal::language('room_level');?></b></p>
                <p id="lblRoomLevelcode"><?php echo Portal::language('code');?>: <b style="color: #f27173;"><?php echo $this->map['room_level_code'];?></b></p>
                <p id="lblRoomLevelName"><?php echo Portal::language('name');?>: <b style="color: #f27173;"><?php echo $this->map['room_level_name'];?></b></p>
                <input  name="room_level_id" id="room_level_id" style="display: none;" / type ="hidden" value="<?php echo String::html_normalize(URL::get('room_level_id'));?>">
                
                <hr />
                
                <p><b class="w3-text-blue"><?php echo Portal::language('start_date');?></b></p>
                <input name="start_date" type="text" id="start_date" class="w3-input w3-border" autocomplete="OFF" maxlength="12" placeholder="<?php echo Portal::language('start_date');?>" readonly="" />
                
                <p><b class="w3-text-blue"><?php echo Portal::language('end_date');?></b></p>
                <input name="end_date" type="text" id="end_date" class="w3-input w3-border" autocomplete="OFF" maxlength="12" placeholder="<?php echo Portal::language('end_date');?>" readonly="" />
                
                <hr />
                
                <p><b class="w3-text-blue"><?php echo Portal::language('room_availability_default');?></b></p>
                <input name="availability_default" type="text" id="availability_default" class="w3-input w3-border input_number" autocomplete="OFF" maxlength="255" placeholder="<?php echo Portal::language('room_availability_default');?>" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val())));" />
                
                <p><b class="w3-text-blue"><?php echo Portal::language('rate_default');?></b></p>
                <input name="rate_default" type="text" id="rate_default" class="w3-input w3-border input_number" autocomplete="OFF" style="text-align: right;" maxlength="255" placeholder="<?php echo Portal::language('rate_default');?>" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val())));" />
                
                <p><b class="w3-text-blue"><input  name="auto_reset_avail" id="auto_reset_avail" checked="checked" / type ="checkbox" value="<?php echo String::html_normalize(URL::get('auto_reset_avail'));?>"> <?php echo Portal::language('auto_reset_availability');?></b></p>
                <p><b class="w3-text-blue"><?php echo Portal::language('cut_of_date');?> <?php echo Portal::language('auto_reset_availability');?></b></p>
                <input value="2" name="day_reset_avail" type="text" id="day_reset_avail" class="w3-input w3-border input_number" autocomplete="OFF" maxlength="17" placeholder="<?php echo Portal::language('cut_of_date');?> <?php echo Portal::language('auto_reset_availability');?>" />
                
                
                <!--
                <p><b class="w3-text-blue">Deposit</b></p>
                <input  name="deposit" id="deposit" class="input_number" autocomplete="OFF" placeholder="$" style="padding: 7px; width: 70%; border: 1px solid #ccc; text-align: right;" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val())));" / type ="text" value="<?php echo String::html_normalize(URL::get('deposit'));?>">
                <b style="color: #f27173;"><?php echo $this->map['deposit_currency'];?></b>
                -->
            </div>
            <div class="w3-twothird w3-padding">
                <p><b class="w3-text-blue"><?php echo Portal::language('setup_availability');?></b><br /><span><i>(Không áp dụng với ngày quá khứ)</i></span></p>
                <table class="w3-table" id="DateRangeGroupAvail" style="">
                    
                </table>
                <div onclick="AddDateRange('avail');" class="w3-button w3-white w3-hover-white w3-margin w3-border" style="font-weight: normal; color: #f27173!important;">
                    <?php echo Portal::language('add_date_range');?>
                </div>
                
                <hr />
                
                <p><b class="w3-text-blue"><?php echo Portal::language('setup_rate');?></b><br /><span><i>(Không áp dụng với ngày quá khứ)</i></span></p>
                <table class="w3-table" id="DateRangeGroupRate" style="">
                    
                </table>
                <div onclick="AddDateRange('rate');" class="w3-button w3-white w3-hover-white w3-margin w3-border" style="font-weight: normal; color: #f27173!important;">
                    <?php echo Portal::language('add_date_range');?>
                </div>
            </div>
        </div>
    </div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<table id="DateRangeGroupAvailTemp" style="display: none;">
    <tr id="DateRangeGroupAvail_X######X">
        <td><input type="text" name="bulkrangeavail[X######X][availability]" id="bulk_availability_X######X" class="w3-input w3-border" style="width: 100px;" autocomplete="OFF" placeholder="Avail" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val())));" /></td>
        <td><input type="text" name="bulkrangeavail[X######X][from_date]" id="bulk_avail_from_date_X######X" class="w3-input w3-border input_date" style="width: 100px;" readonly="" placeholder="From Date" /></td>
        <td><input type="text" name="bulkrangeavail[X######X][to_date]" id="bulk_avail_to_date_X######X" class="w3-input w3-border input_date" style="width: 100px;" readonly="" placeholder="To Date" /></td>
        <td style="text-align: center;"><input type="checkbox" name="bulkrangeavail[X######X][MON]" id="bulk_avail_mon_X######X" checked="checked" /> Mon</td>
        <td style="text-align: center;"><input type="checkbox" name="bulkrangeavail[X######X][TUE]" id="bulk_avail_tue_X######X" checked="checked" /> Tue</td>
        <td style="text-align: center;"><input type="checkbox" name="bulkrangeavail[X######X][WED]" id="bulk_avail_wed_X######X" checked="checked" /> Wed</td>
        <td style="text-align: center;"><input type="checkbox" name="bulkrangeavail[X######X][THU]" id="bulk_avail_thu_X######X" checked="checked" /> Thu</td>
        <td style="text-align: center;"><input type="checkbox" name="bulkrangeavail[X######X][FRI]" id="bulk_avail_fri_X######X" checked="checked" /> Fri</td>
        <td style="text-align: center;"><input type="checkbox" name="bulkrangeavail[X######X][SAT]" id="bulk_avail_sat_X######X" checked="checked" /> Sat</td>
        <td style="text-align: center;"><input type="checkbox" name="bulkrangeavail[X######X][SUN]" id="bulk_avail_sun_X######X" checked="checked" /> Sun</td>
        <td style="text-align: center;">
            <div onclick="DeleteDateRange('avail',X######X);" class="w3-button w3-white w3-hover-white w3-border" style="font-weight: normal;">
                <i class="fa fa-remove fa-fw" style="color: #f27173!important;"></i>
            </div>
        </td>
    </tr>
</table>
<table id="DateRangeGroupRateTemp" style="display: none;">
    <tr id="DateRangeGroupRate_X######X">
        <td><input type="text" name="bulkrangerate[X######X][rate]" id="bulk_rate_X######X" class="w3-input w3-border" style="width: 100px; text-align: right;" autocomplete="OFF" placeholder="$" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val())));" /></td>
        <td><input type="text" name="bulkrangerate[X######X][from_date]" id="bulk_rate_from_date_X######X" class="w3-input w3-border input_date" style="width: 100px;" readonly="" placeholder="From Date" /></td>
        <td><input type="text" name="bulkrangerate[X######X][to_date]" id="bulk_rate_to_date_X######X" class="w3-input w3-border input_date" style="width: 100px;" readonly="" placeholder="To Date" /></td>
        <td style="text-align: center;"><input type="checkbox" name="bulkrangerate[X######X][MON]" id="bulk_rate_mon_X######X" checked="checked" /> Mon</td>
        <td style="text-align: center;"><input type="checkbox" name="bulkrangerate[X######X][TUE]" id="bulk_rate_tue_X######X" checked="checked" /> Tue</td>
        <td style="text-align: center;"><input type="checkbox" name="bulkrangerate[X######X][WED]" id="bulk_rate_wed_X######X" checked="checked" /> Wed</td>
        <td style="text-align: center;"><input type="checkbox" name="bulkrangerate[X######X][THU]" id="bulk_rate_thu_X######X" checked="checked" /> Thu</td>
        <td style="text-align: center;"><input type="checkbox" name="bulkrangerate[X######X][FRI]" id="bulk_rate_fri_X######X" checked="checked" /> Fri</td>
        <td style="text-align: center;"><input type="checkbox" name="bulkrangerate[X######X][SAT]" id="bulk_rate_sat_X######X" checked="checked" /> Sat</td>
        <td style="text-align: center;"><input type="checkbox" name="bulkrangerate[X######X][SUN]" id="bulk_rate_sun_X######X" checked="checked" /> Sun</td>
        <td style="text-align: center;">
            <div onclick="DeleteDateRange('rate',X######X);" class="w3-button w3-white w3-hover-white w3-border" style="font-weight: normal;">
                <i class="fa fa-remove fa-fw" style="color: #f27173!important;"></i>
            </div>
        </td>
    </tr>
</table>

<script>
    var input_count_avail = 100;
    var input_count_rate = 100;
    var CURRENT_YEAR = <?php echo date('Y')?>;
    var CURRENT_MONTH = <?php echo intval(date('m')) - 1;?>;
    var CURRENT_DAY = <?php echo date('d')?>;
    
    jQuery(document).ready(function(){
        jQuery("#start_date").datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
        jQuery("#end_date").datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
        var bulkrangeavail = <?php echo isset($_REQUEST['bulkrangeavail'])?String::array2js($_REQUEST['bulkrangeavail']):String::array2js(array()); ?>;
        var bulkrangerate = <?php echo isset($_REQUEST['bulkrangerate'])?String::array2js($_REQUEST['bulkrangerate']):String::array2js(array()); ?>;
        for(var i in bulkrangeavail){
            AddDateRange('avail',bulkrangeavail[i]);
        }
        for(var j in bulkrangerate){
            AddDateRange('rate',bulkrangerate[j]);
        }
    });
    function AddDateRange(type,row){
        if(type=='avail'){
            var input_count = input_count_avail++;
            var content = jQuery("#DateRangeGroupAvailTemp").html().replace(/X######X/g,input_count);
            jQuery("#DateRangeGroupAvail").append(content);
            jQuery("#bulk_avail_from_date_"+input_count).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
            jQuery("#bulk_avail_to_date_"+input_count).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
            if(row){
                jQuery("#bulk_availability_"+input_count).val(row['availability']);
                jQuery("#bulk_avail_from_date_"+input_count).val(row['from_date']);
                jQuery("#bulk_avail_to_date_"+input_count).val(row['to_date']);
                if(!row['MON'])
                    jQuery("#bulk_avail_mon_"+input_count).removeAttr('checked');
                if(!row['TUE'])
                    jQuery("#bulk_avail_tue_"+input_count).removeAttr('checked');
                if(!row['WED'])
                    jQuery("#bulk_avail_wed_"+input_count).removeAttr('checked');
                if(!row['THU'])
                    jQuery("#bulk_avail_thu_"+input_count).removeAttr('checked');
                if(!row['FRI'])
                    jQuery("#bulk_avail_fri_"+input_count).removeAttr('checked');
                if(!row['SAT'])
                    jQuery("#bulk_avail_sat_"+input_count).removeAttr('checked');
                if(!row['SUN'])
                    jQuery("#bulk_avail_sun_"+input_count).removeAttr('checked');
            }
        }
        else if(type=='rate'){
            var input_count = input_count_rate++;
            var content = jQuery("#DateRangeGroupRateTemp").html().replace(/X######X/g,input_count);
            jQuery("#DateRangeGroupRate").append(content);
            jQuery("#bulk_rate_from_date_"+input_count).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
            jQuery("#bulk_rate_to_date_"+input_count).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
            if(row){
                jQuery("#bulk_rate_"+input_count).val(row['rate']);
                jQuery("#bulk_rate_from_date_"+input_count).val(row['from_date']);
                jQuery("#bulk_rate_to_date_"+input_count).val(row['to_date']);
                if(!row['MON'])
                    jQuery("#bulk_rate_mon_"+input_count).removeAttr('checked');
                if(!row['TUE'])
                    jQuery("#bulk_rate_tue_"+input_count).removeAttr('checked');
                if(!row['WED'])
                    jQuery("#bulk_rate_wed_"+input_count).removeAttr('checked');
                if(!row['THU'])
                    jQuery("#bulk_rate_thu_"+input_count).removeAttr('checked');
                if(!row['FRI'])
                    jQuery("#bulk_rate_fri_"+input_count).removeAttr('checked');
                if(!row['SAT'])
                    jQuery("#bulk_rate_sat_"+input_count).removeAttr('checked');
                if(!row['SUN'])
                    jQuery("#bulk_rate_sun_"+input_count).removeAttr('checked');
            }
        }
    }
    function DeleteDateRange(type,input_count){
        if(type=='avail'){
            jQuery("#DateRangeGroupAvail_"+input_count).remove();
        }
        else if(type=='rate'){
            jQuery("#DateRangeGroupRate_"+input_count).remove();
        }
    }
    function CheckSubmit(){
        var check = true;
        var mess = '';
        if(jQuery("#start_date").val().trim()==''){
            check = false;
            mess = 'Nhập thiếu dữ liệu';
        }
        if(jQuery("#end_date").val().trim()==''){
            check = false;
            mess = 'Nhập thiếu dữ liệu';
        }
        if(count_date(jQuery("#start_date").val().trim(), jQuery("#end_date").val().trim())<0){
            check = false;
            mess = 'Ngày bắt đầu phải nhỏ hơn hoặc bằng ngày kết thúc';
        }
        if(document.getElementById('auto_reset_avail').checked && to_numeric(jQuery('#day_reset_avail').val())<=0){
            check = false;
            mess = 'Chưa Detup ngày tự động nhả phòng Allotment đã giữ';
        }
        if(!check){
            alert(mess);
            return false;
        }else{
            jQuery("#act").val('SAVE');
            AddRoomAllotmentForm.submit();
        }
        
        
    }
    function count_date(start_day, end_day)
    {
    	var std =start_day.split("/");
    	var ed =end_day.split("/");
    	return (Date.parse(ed[1]+"/"+ed[0]+"/"+ed[2])-Date.parse(std[1]+"/"+std[0]+"/"+std[2]))/86400000;
    }
</script>