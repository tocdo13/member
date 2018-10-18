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

<form name="AddAllRoomAllotmentForm" method="POST" enctype="multipart/form-data">
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
            <p><b class="w3-text-blue"><?php echo Portal::language('customer');?></b></p>
            <p id="lblCustomercode"><?php echo Portal::language('code');?>: <b style="color: #f27173;"><?php echo $this->map['customer_code'];?></b></p>
            <p id="lblCustomerName"><?php echo Portal::language('name');?>: <b style="color: #f27173;"><?php echo $this->map['customer_name'];?></b></p>
            <input  name="customer_id" id="customer_id" style="display: none;" / type ="hidden" value="<?php echo String::html_normalize(URL::get('customer_id'));?>">
            <p><b class="w3-text-blue"><input  name="auto_reset_avail" id="auto_reset_avail" checked="checked" / type ="checkbox" value="<?php echo String::html_normalize(URL::get('auto_reset_avail'));?>"> <?php echo Portal::language('auto_reset_availability');?></b></p>
            <p><b class="w3-text-blue"><?php echo Portal::language('cut_of_date');?> <?php echo Portal::language('auto_reset_availability');?></b></p>
            <p><input value="2" name="day_reset_avail" type="text" id="day_reset_avail" class="w3-input w3-border input_number" style="width: 50px;" autocomplete="OFF" maxlength="17" placeholder="<?php echo Portal::language('cut_of_date');?> <?php echo Portal::language('auto_reset_availability');?>" /></p>
            <hr />
            
            <p><b class="w3-text-blue"><?php echo Portal::language('setup_allotment');?></b><br /><span><i>(Không áp dụng với ngày quá khứ)</i></span></p>
            <table class="w3-table" id="DateRangeGroupAvail" style="">
                
            </table>
            <div onclick="AddDateRange();" class="w3-button w3-white w3-hover-white w3-margin w3-border" style="font-weight: normal; color: #f27173!important;">
                <?php echo Portal::language('add_allotment');?>
            </div>
        </div>
    </div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<table id="DateRangeGroupAvailTemp" style="display: none;">
    <tr id="DateRangeGroupAvail_X######X">
        <td><select id="bulk_room_level_id_X######X" name="bulkrangeavail[X######X][room_level_id]" class="w3-input w3-border"><?php echo $this->map['room_level_option'];?></select></td>
        <td><input type="text" name="bulkrangeavail[X######X][availability]" id="bulk_availability_X######X" class="w3-input w3-border" style="width: 100px;" autocomplete="OFF" placeholder="Avail" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val())));" /></td>
        <td><input type="text" name="bulkrangeavail[X######X][rate]" id="bulk_rate_X######X" class="w3-input w3-border" style="width: 130px; text-align: right;" autocomplete="OFF" placeholder="Rates" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val())));" /></td>
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
            <div onclick="DeleteDateRange(X######X);" class="w3-button w3-white w3-hover-white w3-border" style="font-weight: normal;">
                <i class="fa fa-remove fa-fw" style="color: #f27173!important;"></i>
            </div>
        </td>
    </tr>
</table>

<script>
    var input_count_avail = 100;
    var CURRENT_YEAR = <?php echo date('Y')?>;
    var CURRENT_MONTH = <?php echo intval(date('m')) - 1;?>;
    var CURRENT_DAY = <?php echo date('d')?>;
    
    
    jQuery(document).ready(function(){
        jQuery("#start_date").datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
        jQuery("#end_date").datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
        var bulkrangeavail = <?php echo isset($_REQUEST['bulkrangeavail'])?String::array2js($_REQUEST['bulkrangeavail']):String::array2js(array()); ?>;
        for(var i in bulkrangeavail){
            AddDateRange(bulkrangeavail[i]);
        }
    });
    function AddDateRange(row){
        var input_count = input_count_avail++;
        var content = jQuery("#DateRangeGroupAvailTemp").html().replace(/X######X/g,input_count);
        jQuery("#DateRangeGroupAvail").append(content);
        jQuery("#bulk_avail_from_date_"+input_count).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
        jQuery("#bulk_avail_to_date_"+input_count).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
        if(row){
                jQuery("#bulk_room_level_id_"+input_count).val(row['room_level_id']);
                jQuery("#bulk_rate_"+input_count).val(row['rate']);
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
    function DeleteDateRange(input_count){
        jQuery("#DateRangeGroupAvail_"+input_count).remove();
    }
    function CheckSubmit(){
        var check = true;
        var mess = '';
        var timeline = {};
        for(var i in input_count_avail){
            if(jQuery('#bulk_room_level_id_'+i).val()!=undefined){
                if(jQuery('#bulk_room_level_id_'+i).val()!='' && jQuery('#bulk_availability_'+i).val()!='' && jQuery('#bulk_rate_'+i).val()!='' && jQuery('#bulk_avail_from_date_'+i).val()!='' && jQuery('#bulk_avail_to_date_'+i).val()!=''){
                    timeline[jQuery('#bulk_room_level_id_'+i).val()] = {};
                    timeline[jQuery('#bulk_room_level_id_'+i).val()]['id'] = jQuery('#bulk_room_level_id_'+i).val();
                    if(!timeline[jQuery('#bulk_room_level_id_'+i).val()]['timeline']){
                        timeline[jQuery('#bulk_room_level_id_'+i).val()]['timeline'] = {};
                    }
                    $time = timeline[jQuery('#bulk_room_level_id_'+i).val()]['timeline'];
                    for(j in $time){
                        if( (jQuery('#bulk_avail_to_date_'+i).val()-$time[j]['from_date'])>=0 && ($time[j]['to_date']-jQuery('#bulk_avail_from_date_'+i).val())>=0){
                            check = false;
                            mess = 'Trùng thời gian allotment';
                        }
                    }
                    timeline[jQuery('#bulk_room_level_id_'+i).val()]['timeline'][i] = {};
                    timeline[jQuery('#bulk_room_level_id_'+i).val()]['timeline'][i]['from_date'] = jQuery('#bulk_avail_from_date_'+i).val();
                    timeline[jQuery('#bulk_room_level_id_'+i).val()]['timeline'][i]['to_date'] = jQuery('#bulk_avail_to_date_'+i).val();
                }else{
                    check = false;
                    mess = 'Bạn điền thiếu thông tin';
                }
            }
        }
        if(!check){
            alert(mess);
            return false;
        }else{
            jQuery("#act").val('SAVE');
            AddAllRoomAllotmentForm.submit();
        }
    }
    function count_date(start_day, end_day)
    {
    	var std =start_day.split("/");
    	var ed =end_day.split("/");
    	return (Date.parse(ed[1]+"/"+ed[0]+"/"+ed[2])-Date.parse(std[1]+"/"+std[0]+"/"+std[2]))/86400000;
    }
</script>