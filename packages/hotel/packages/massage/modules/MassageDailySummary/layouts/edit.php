<style>
.ui-datepicker-clear{
    display:none;
}
.paywithroom{
    background: #888;
    z-index: 2000;
}
</style>
<script type="text/javascript">
	var staff_arr = <?php echo String::array2js([[=staffs=]]);?>;
    var product_array = <?php echo String::array2js([[=products=]]);?>;
	var travellers = [[|travellers|]];
</script>
<span style="display:none">
	<span id="mi_staff_group_sample">
		<div id="mi_staff_group_#xxxx#" style="width:100%;text-align:left;">
			<span class="multi-input">
                <input  name="mi_staff_group[#xxxx#][id]" type="hidden" id="id_#xxxx#"/>
            </span>
			<span class="multi-input">
                <select  name="mi_staff_group[#xxxx#][staff_id]" id="staff_id_#xxxx#">[[|staff_options|]]</select>
            </span>
			<span class="multi-input">
                <select  name="mi_staff_group[#xxxx#][room_id]" id="room_id_#xxxx#" onchange="check_room(this,this.value);" class="staff" index="#xxxx#" >[[|room_options_staff|]]</select>
            </span>
			<span class="multi-input">
                Tip: <input  name="mi_staff_group[#xxxx#][tip]" style="width:80px;text-align: right;" type="text" id="tip_#xxxx#" class="input_number format_number" />
            </span>
			<span class="multi-input">
                <img src="<?php echo Portal::template('core');?>/images/buttons/delete.gif" onclick="mi_delete_row($('mi_staff_group_#xxxx#'),'mi_staff_group','#xxxx#','staff_');updateTotalPayment();if(document.all)event.returnValue=false; else return false;" style="cursor:pointer;"/>
            </span>
            <br clear="all" />
		</div>
	</span> 
</span>
<span style="display:none">
	<span id="mi_product_group_sample">
		<div id="mi_product_group_#xxxx#" style="width:100%;text-align:left;" >
			<span class="multi-input">
                <input  name="mi_product_group[#xxxx#][id]" type="hidden" id="id_#xxxx#"/>
            </span>
			<span class="multi-input">
                <input  name="mi_product_group[#xxxx#][product_id]" type="hidden" id="product_id_#xxxx#"/>
            </span>
			<span class="multi-input">
                <select  name="mi_product_group[#xxxx#][room_id]" id="room_id_#xxxx#" style="width:105px;" class="product" onchange="add_room_staff(this.value);">[[|room_options|]]</select>
            </span>
			<span class="multi-input">
                <input  name="mi_product_group[#xxxx#][code]" style="width:122px;" type="text" id="code_#xxxx#" onblur="getProductFromCode('#xxxx#',this.value);productAutoComplete();" autocomplete="OFF"/>
            </span>
			<span class="multi-input">
                <input  name="mi_product_group[#xxxx#][name]" style="width:120px;" type="text" readonly="readonly" class="readonly" id="name_#xxxx#" tabindex="-1"/>
            </span>
			<span class="multi-input">
                <input  name="mi_product_group[#xxxx#][price]" style="width:90px;" type="text" id="price_#xxxx#" onchange="countProductAmount('#xxxx#');updateTotalPayment();"/>
            </span>
            <span class="multi-input">
                <input  name="mi_product_group[#xxxx#][use_time]" style="width:110px;" type="text" id="use_time_#xxxx#" readonly="readonly" class="readonly"/>
            </span>
            <span class="multi-input" style="display: none;"><!--style="display: none;"-->
                <input  name="mi_product_group[#xxxx#][minute]" style="width:110px;" type="text" id="minute_#xxxx#" readonly="readonly" class="readonly"/>
            </span>
			<span class="multi-input">
                <input  name="mi_product_group[#xxxx#][quantity]" style="width:50px;" type="text" value="" id="quantity_#xxxx#" onchange="countProductAmount('#xxxx#');updateTotalPayment();"/>
            </span>
			<span class="multi-input">
                <input  name="mi_product_group[#xxxx#][amount]" style="width:80px;" type="text" readonly="readonly" class="readonly" id="amount_#xxxx#" tabindex="-1"/>
            </span>
			<span class="multi-input">
                <select  name="mi_product_group[#xxxx#][status]" id="status_#xxxx#" style="width:85px;font-size:11px;height:22px;" onchange="updateTime('#xxxx#');">[[|status_options|]]</select>
            </span>
			<span class="multi-input">
                <input  name="mi_product_group[#xxxx#][time_in_hour]" style="width:40px;" type="text" id="time_in_hour_#xxxx#" value="<?php echo date('H:i',time());?>" onchange="check_validate_time(#xxxx#);" />
                <input  name="mi_product_group[#xxxx#][time_in_date]" style="width:81px;" type="text" id="time_in_date_#xxxx#"  value="<?php echo Url::get('date')?Url::get('date'):date('d/m/Y',time());?>" onchange="check_validate_time(#xxxx#);"/>
            </span>
			<span class="multi-input">
                <input  name="mi_product_group[#xxxx#][time_out_hour]" style="width:40px;" type="text" id="time_out_hour_#xxxx#" value="<?php echo date('H:i',time()+3600);?>" onchange="check_validate_time(#xxxx#);"/>
                <input  name="mi_product_group[#xxxx#][time_out_date]" style="width:81px;" type="text" id="time_out_date_#xxxx#"  value="<?php echo Url::get('date')?Url::get('date'):date('d/m/Y',time());?>" onchange="check_validate_time(#xxxx#);"/>
            </span>
			<span class="multi-input DeleteInput_#xxxx#">
                <img src="<?php echo Portal::template('core');?>/images/buttons/delete.gif" onclick="if(!confirm('[[.are_you_sure.]]')){return false};mi_delete_row($('mi_product_group_'+#xxxx#),'mi_product_group','#xxxx#','product_');delete_staff(#xxxx#);DeleteInput(#xxxx#);if(document.all)event.returnValue=false; else return false;" style="cursor:pointer;"/>
            </span>
            <br clear="all" /><!---->
		</div>
	</span> 
</span>
<div class="massage-daily-summary-bound">
<form  name="MassageEditForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="60%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;">[[|title|]]</td>
            <td align="right" nowrap="nowrap" style="width: 40%; padding-right: 30px;">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><input name="save" type="submit" value="[[.Save_and_close.]]" class="w3-btn w3-orange w3-text-white" onclick="check_select_room();jQuery('.button-medium-save').css('display','none');" style="text-transform: uppercase; margin-right: 5px;"/><?php }?>
                <?php if(User::can_add(false,ANY_CATEGORY)){?><input name="save_stay" type="submit" value="[[.Save_stay.]]" class="w3-btn w3-blue w3-text-white" onclick="check_select_room();jQuery('.button-medium-save').css('display','none');" style="text-transform: uppercase; margin-right: 5px;"/><?php }?>
				<a href="<?php echo Url::build_current();?>" class="w3-btn w3-green w3-text-white" style="text-transform: uppercase; margin-right: 5px; text-decoration: none;">[[.back.]]</a>
                <?php if(Url::get('cmd')!='add' and User::can_delete(false,ANY_CATEGORY)){?><a onclick="return check_delete();" href="<?php echo Url::build_current(array('cmd'=>'delete','id'));?>" class="w3-btn w3-red w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.delete.]]</a><?php }?>
            </td>
        </tr>
    </table>
	<div class="content">
		<?php if(Form::$current->is_error()){?><div><br/><?php echo Form::$current->error_messages();?></div><?php }?>
		<fieldset>
			<legend class="title">[[.used_products_services.]]</legend>
			<span id="mi_product_group_all_elems" style="text-align:left;">
				<span>
					<span class="multi-input-header" style="width:105px;float:left;">[[.room.]]</span>
					<span class="multi-input-header" style="width:122px;float:left;">[[.code.]]</span>
					<span class="multi-input-header" style="width:120px;float:left;">[[.name.]]</span>
					<span class="multi-input-header" style="width:90px;float:left;">[[.price.]]</span>
                    <span class="multi-input-header" style="width:110px;float:left;">[[.use_time.]]</span>
					<span class="multi-input-header" style="width:50px;float:left;">[[.no_of_people.]]</span>
					<span class="multi-input-header" style="width:80px;float:left;">[[.amount.]]</span>
					<span class="multi-input-header" style="width:85px;float:left;">[[.status.]]</span>
					<span class="multi-input-header" style="width:126px;float:left;">[[.time_in.]]</span>
					<span class="multi-input-header" style="width:126px;float:left;">[[.time_out.]]</span>
				</span>
                <br clear="all" />
			</span>
			<input class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; margin-top: 5px;" type="button" value="[[.add_service.]]" onclick="mi_add_new_row('mi_product_group');productAutoComplete();jQuery('#time_in_hour_'+input_count).mask('99:99');jQuery('#time_out_hour_'+input_count).mask('99:99');show_datepicker(input_count);jQuery('#code_'+input_count).ForceCodeOnly();"/>
		</fieldset>
        
		<fieldset>
			<legend class="title">[[.staffs.]]</legend>
            <legend class="title">[[.only_choose_staff_in_room_selected.]]</legend>
				<span id="mi_staff_group_all_elems">
				</span>
                <br clear="all" />
				<input class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; margin-top: 5px;" type="button" value="[[.add_staff.]]" onclick="mi_add_new_row('mi_staff_group');jQuery('#tip_'+input_count).FormatNumber();jQuery('#tip_'+input_count).ForceNumericOnly();"/>
		</fieldset>
        
        <fieldset>
        	<legend class="title">[[.general_information.]]</legend>
        	<table style="width: 65%;" border="0" cellspacing="0" cellpadding="2">
                <tr>
                    <td align="right" class="label">
                        <label>[[.customer.]]</label>
                    </td>
                    <td>
                        <input name="customer_name" type="text" id="customer_name" onkeypress="Autocomplete();"  autocomplete="off" style="width:170px;margin-bottom: 5px;" /><!--Giap comment readonly="readonly"  class="readonly"-->
                        <input name="customer_id" type="text" id="customer_id" value="[[|customer_id|]]" class="hidden" />
                        <!--IF:pointer(User::can_edit(false,ANY_CATEGORY))--><a class="w3-text-blue" href="#" onclick="window.open('?page=customer&action=select_customer&site=spa','customer')" style="text-decoration: none;"> <i class="fa fa-plus-square" style="font-size: 18px;"></i> [[.add_now.]]</a><!--/IF:pointer-->
                    </td>
                </tr>
                <tr valign="middle">
                    <td align="right" class="label">[[.guest_code.]]</td>
                    <td>
                        <input name="guest_id" type="text" id="guest_id" style="display:none;"/>
                        <input name="code" type="text" id="code" onclick="window"/>
                        <a href="#" onclick="window.open('?page=massage_guest&amp;action=select_guest','guest')"><img src="skins/default/images/cmd_Tim.gif" /></a>
                        <img width="15" src="packages/core/skins/default/images/buttons/delete.gif" onclick="$('full_name').value='';$('guest_id').value=0;$('code').value=''" style="cursor:pointer;"/>
                    </td>
                    <td align="right">[[.guest_name.]]</td>
                    <td align="left"><input name="full_name" type="text" id="full_name"/></td>
                    <td colspan="2">
                        <?php if(SETTING_POINT==1){ ?>
                        <span style="float: left; line-height: 25px;"> [[.member_code.]]: </span>
                        <input type="text" name="member_code"value="<?php echo isset($_REQUEST['member_code'])?$_REQUEST['member_code']:''; ?>" id="member_code" autocomplete="off" onchange="fun_load_member_code();" style="width: 100px; height: 25px; text-align: center; border: 1px solid #555555; float: left;" />
                        <input type="text" name="member_level_id" value="<?php echo isset($_REQUEST['member_level_id'])?$_REQUEST['member_level_id']:''; ?>" id="member_level_id" style="display: none;" />
                        <input type="text" name="create_member_date" value="<?php echo isset($_REQUEST['create_member_date'])?$_REQUEST['create_member_date']:''; ?>" id="create_member_date" style="display: none;" />
                        <input type="button" name="view_info_member" id="view_info_member" value="info" style="padding: 3px; margin: 0px 2px ; float: left;" onclick="fun_view_info_member();" />
                        <div id="div_info" style="width: 100%; height: 100%; display: none; background-color: rgba(0, 0, 0, 0.9); position: fixed; top: 0px; left: 0px;">
                            <div style="width: 600px; height: 400px; margin: 50px auto; background: #ffffff; position: relative;">
                                <div style="width: 20px; height: 20px; border: 2px solid #000000; border-radius: 50%; line-height: 20px; text-align: center; font-size: 17px; position: absolute; top: -10px; right: -10px; background: #fff; cursor: pointer;" onclick="fun_close_info();">X</div>
                                <div id="info_member_discount" style="width: 600px; height: 390px; position: absolute; top: 10px; left: 0px;"></div>
                            </div>
                        </div>
                        <?php } ?>
                    </td>
                </tr>
                <tr valign="middle">
                    <td align="right" class="label">[[.hotel_room.]]</td>
                    <td>
                        <select name="hotel_reservation_room_id" id="hotel_reservation_room_id" style="display: block; float:left;" ></select>
                        &nbsp;<span id="room_traveller_name" style="display: block; float:left; margin-left: 5px;" >[[|room_traveller_name|]]</span><span style="margin-left: 7px;display: block; float:left;"><input name="pay_with_room" type="checkbox" id="pay_with_room" onclick="check_pay_with_room();" style="display:block; padding:10px;" <?php if([[=pay_with_room=]]==1){ echo 'checked';}?> title="Trả tiền về phòng" value="1" /></span>
                        <span id="payment_note" style="display:none ;">[[.da_thanh_toan_ve_phong.]]</span>
                        <input name="hotel_reservation_room_check" type="hidden" id="hotel_reservation_room_check" value="0" />
                        <input name="hotel_reservation_room_id_old" type="hidden" id="hotel_reservation_room_id_old" value="" />
                    </td>
                    <td align="right"><span class="label">[[.discount.]]</span></td>
                    <td align="left">
                        <input name="discount_amount" type="text" id="discount_amount" onchange="updateTotalPayment();" onkeyup="check_discount_amount();" style="width:80px;" class="input_number"/>
                        <input name="discount" type="text" id="discount" onchange="updateTotalPayment();" onkeyup="check_discount1();" style="width:40px;" class="input_number"/>% 
                        <span>[[.tax.]] <input name="tax" type="text" id="tax"  value ="[[|tax|]]" onchange="updateTotalPayment();" style="width:40px;"/>%</span>
                        <span>[[.service_rate.]] <input name="service_rate" type="text" id="service_rate"  value ="[[|service_rate|]]" onchange="updateTotalPayment();" style="width:40px;"/>%</span>
                        <!--IF:cond(Url::get('cmd')=='add')-->
                        <span>[[.net_price.]]<input  name="net_price" value="1" type="checkbox" id="net_price" <?php if(NET_PRICE_SPA==1){ echo 'checked';}?> onclick="change_net_price()"/></span>
                        <!--ELSE-->
                        <span>[[.net_price.]]<input  name="net_price" value="1" type="checkbox" id="net_price" onclick="change_net_price()"/></span>
                        <!--/IF:cond-->
                    
                    </td>
                </tr>
                <tr valign="middle">
                    <td align="right" class="label">[[.note.]]</td>
                    <td colspan="3"><textarea name="note" id="note" style="width:98%"></textarea></td>
                </tr>
        	</table>
        </fieldset>
        
        <br />
        
        <fieldset>
            <div style="float: right; padding-right: 100px;">
                <strong>[[.total_payment.]]:</strong> : <input name="total_amount" type="text" id="total_amount" readonly="true" style="width:100px;text-align:right;border:0px;border-bottom:1px solid #CCCCCC;font-weight:bold;color:#000000;"/> <?php echo HOTEL_CURRENCY; ?>
		        <input name="total_amount_old" type="hidden" id="total_amount_old" readonly="" value="" />
                <input name="total_amount_old_2" type="hidden" id="total_amount_old_2" readonly="" value="[[|total_amount|]]" />
                <?php if([[=pay_with_room=]]!=1){ ?>
                <input class="w3-btn w3-lime" style="text-transform: uppercase;" name="payment" type="button" id="payment" value="[[.payment.]]" onclick="openPayment();"/>
                <?php
                    }
                ?>
            </div>
        </fieldset>
        	
        <fieldset style="display:none;">
            <legend class="title">[[.Pay_by.]]</legend>
            <div class="currency-list-bound">
            <div class="currency-list"><span>&nbsp;</span>
                <?php $i=0;?>
                <table border="0" cellspacing="0" cellpadding="5">
                <tr>
                    <!--LIST:currencies-->
                    <td>
                    	<span><br /><?php echo ($i>0)?'+':'';$i++;?>&nbsp;</span>
                    	<span>
                            [[|currencies.name|]]<br /><input name="currency_[[|currencies.id|]]" type="text" id="currency_[[|currencies.id|]]" />
                    	</span>
                    </td>
                    <!--/LIST:currencies-->
                </tr>
                </table>
             </div>
             </div>
        </fieldset>
	</div>
 
    <input  name="staff_deleted_ids" id="staff_deleted_ids" type="hidden" value="<?php echo URL::get('staff_deleted_ids');?>"/>
    <input  name="product_deleted_ids" id="product_deleted_ids" type="hidden" value="<?php echo URL::get('product_deleted_ids');?>"/>	
    <input name="extra_charge" type="text" id="extra_charge" onchange="updateTotalPayment();" style="display:none;"/>
</form>	
</div>
<script type="text/javascript">
   <?php echo 'var block_id = '.Module::block_id().';';?>
    var net_price = <?php echo ((Url::get('net_price')==1)?1:0);?>;
    if(net_price==1)
    {
        jQuery('#net_price').attr('checked',true);
    }
    hotel_reservation_room_id = '[[|hotel_reservation_room_id|]]';
    jQuery('#hotel_reservation_room_id_old').val(hotel_reservation_room_id);

    var check_discount = [[|check_discount|]];
    if(check_discount == 1)
    {
        jQuery('#discount_amount').attr('readonly', true);
        jQuery('#discount').attr('readonly', true);
        jQuery('#tax').attr('readonly', true);
        jQuery('#service_rate').attr('readonly', true);
        jQuery('#net_price').attr('disabled', true);
        jQuery("#payment").css("display","none");
        jQuery("#payment_note").css("display","");
        jQuery("#pay_with_room").css("display","none");
        jQuery("#hotel_reservation_room_id").each(function(){
            for(var id = 0; id < jQuery(this).context.childNodes.length; id ++)
            {
                if(to_numeric(jQuery(this).context.childNodes[id].value) != hotel_reservation_room_id)
                {
                    jQuery("#hotel_reservation_room_id").find("option[value="+jQuery(this).context.childNodes[id].value+"]").attr("hidden","");
                    //jQuery('#pay_with_room')attr("hidden","");
                }                                      
            }
        });
    }
    var cmd = '<?php echo Url::get('cmd'); ?>';
    if(cmd == 'edit')
    {
        var total_amount = [[|total_amount|]];
        var amount_pay_with_room = [[|amount_pay_with_room|]];
        jQuery('#total_amount_old').val(amount_pay_with_room);
        jQuery('#total_amount').val(number_format(total_amount));
    }
//Neu chon khach ngoai thi bo khach phong
    jQuery(document).ready(function(){  
        //if( $('code').value )
//        {
//            $('hotel_reservation_room_id').value = '';
//        }
        for(var i = 101; i <= input_count; i++) 
        {            
            status_default =jQuery("#status_"+i).val();
            if(jQuery("#status_"+i).val() != undefined)
            {
                //console.log(131);
                jQuery("#status_"+i).each(function(){
                    for(var id = 0; id < jQuery(this).context.childNodes.length; id ++)
                    {
                        if(status_default == 'CHECKOUT')
                        {
                            if(to_numeric(jQuery(this).context.childNodes[id].value) != status_default)
                            {
                                <?php if(User::is_admin()){?>
                                    jQuery("#status_"+i).find("option[value="+jQuery(this).context.childNodes[id].value+"]").attr("hidden","");
                                <?php }?>
                            } 
                            room =jQuery("#room_id_"+i).val();
                            jQuery("#room_id_"+i).each(function(){
                                for(var id_room = 0; id_room < jQuery(this).context.childNodes.length; id_room ++)
                                {
                                    if(to_numeric(jQuery(this).context.childNodes[id_room].value) != room)
                                    {
                                        <?php if(!User::is_admin()){?>
                                            jQuery("#room_id_"+i).find("option[value="+jQuery(this).context.childNodes[id_room].value+"]").attr("hidden","");
                                        <?php }?>
                                    } 
                                }
                            });               
                            <?php if(!User::is_admin()){?>
                                jQuery('.DeleteInput_'+i).css('display', 'none');
                                jQuery('#time_out_hour_'+i).attr('readonly',true);
                                jQuery('#time_out_hour_'+i).addClass('readonly');                                                              
                                jQuery('#time_in_hour_'+i).attr('readonly',true);
                                jQuery('#time_in_hour_'+i).addClass('readonly'); 
                                jQuery('#code_'+i).addClass('readonly'); 
                                jQuery('#code_'+i).attr('readonly',true);
                                jQuery('#price_'+i).addClass('readonly'); 
                                jQuery('#price_'+i).attr('readonly',true);
                                jQuery('#quantity_'+i).addClass('readonly'); 
                                jQuery('#quantity_'+i).attr('readonly',true);
                                jQuery('#time_in_date_'+i).addClass('readonly'); 
                                jQuery('#time_in_date_'+i).attr('readonly',true);
                                jQuery('#time_in_date_'+i).datepicker('destroy');
                                jQuery('#time_out_date_'+i).addClass('readonly'); 
                                jQuery('#time_out_date_'+i).attr('readonly',true);
                                jQuery('#time_out_date_'+i).datepicker('destroy');
                            <?php }?>
                        }                            
                    }
                });
            }                                                 
        }     
    });
	function updateTime(index){
		for(var i=101;i<=input_count;i++){
			if(i==index){
			     if($('status_'+i).value=='BOOKED'){
			         var str_in_hour = $('time_in_hour_'+i).value;
                     hours = cal_time(str_in_hour);
                     date = jQuery('#time_in_date_'+i).val();
                     date_arr = date.split('/');
                     new_date = new Date(date_arr[2], date_arr[1]-1, date_arr[0]).getTime() / 1000 + hours;
                     //console.log(new_date);
                     to_date = new Date(new_date*1000);
                     $('time_out_hour_'+i).value = ((to_date.getHours()<10)?'0':'')+to_date.getHours()+':'+((to_date.getMinutes()<10)?'0':'')+to_date.getMinutes();
                     $('time_out_date_'+i).value = ((to_date.getDate()<10)?'0':'')+to_date.getDate()+'/'+(((to_date.getMonth()+1)<10)?'0':'')+(to_date.getMonth()+1)+'/'+to_date.getFullYear();
			     }
				if($('status_'+i).value=='CHECKIN'){
					$('time_in_hour_'+i).value = '<?php echo date('H:i',time());?>';
					$('time_out_hour_'+i).value = '<?php echo date('H:i',time()+3600);?>';
                	$('time_in_date_'+i).value = '<?php echo date('d/m/Y',time());?>';
					$('time_out_date_'+i).value = '<?php echo date('d/m/Y',time()+3600);?>';
				}
				if($('status_'+i).value=='CHECKOUT'){
				    //console.log($('time_in_hour_'+i).value);
                    //return false;
                    if($('time_in_hour_'+i).value > $('time_out_hour_'+i).value){
                        $('time_in_hour_'+i).value = '<?php echo date('H:i',time()-3600);?>';
                    }
					$('time_out_hour_'+i).value = '<?php echo date('H:i',time());?>';
                    $('time_out_date_'+i).value = '<?php echo date('d/m/Y',time());?>';
				}
			}
		}
	}	
	<!--IF:cond(isset([[=hotel_reservation_room_id=]]) and [[=hotel_reservation_room_id=]])-->
	//$('room_traveller_name').innerHTML = travellers[[[|hotel_reservation_room_id|]]]['full_name'];
	<!--/IF:cond-->
	function updateRoomTraveller(value)
    {
    	if(value!=''){
		  jQuery("#pay_with_room").css('visibility','visible');
          //jQuery("#pay_with_room").prop('checked',true);  
          check_pay_with_room(jQuery("#pay_with_room"));
		}
        else{
          jQuery("#pay_with_room").prop('checked',false);  
          jQuery("#pay_with_room").css('visibility','hidden');
          check_pay_with_room(jQuery("#pay_with_room"));  
        }
        if(value == '')
        {
            jQuery('#payment').css('display','');
        }else
        {
            jQuery('#payment').css('display','none');
        }
		if(value && typeof(travellers[value])=='object'){
		  
			$('room_traveller_name').innerHTML = travellers[value]['full_name'];
		}
        else
        {
			$('room_traveller_name').innerHTML = '';
		}
        if(value)
        {
            $('code').value = '';
            $('full_name').value = '';
        }
	}
	function updateTotalPayment(){
		jQuery('#total_amount_vnd').html('');
		var total_payment = 0;
		var total_payment_vnd = 0;
		//update product quantity and amount
		for(var i=101;i<=input_count;i++){
			if(typeof(jQuery("#amount_"+i).val())!='undefined'){
				total_payment += stringToNumber(jQuery("#amount_"+i).val());	
			}
			if(typeof(jQuery("#price_vnd_"+i).val())!='undefined'){
				total_payment_vnd += jQuery("#price_vnd_"+i).val()*jQuery("#quantity_"+i).val();
			}
		}
        var discount_amount =  jQuery('#discount_amount').val();
        //console.log(discount_amount);
        if(discount_amount > total_payment){
            alert('[[.so_tien_khuyen_mai_lon_hon_so_tien_dich_vu.]]');
            jQuery('#discount_amount').val('0');
            //console.log('11111111');
        }
		var price = 0;
		if(jQuery("#extra_charge").val()){
			price += stringToNumber(jQuery("#extra_charge").val());
		}
        if(jQuery('#discount').val() >100 || jQuery('#discount').val() <0)
        {
            alert('[[.invalid_percent.]]');
            jQuery('#discount').val(0);
        }
		total_payment += price;
		//var discount_before_tax = <?php //echo Url::get('discount_before_tax')?Url::get('discount_before_tax'):DISCOUNT_BEFORE_TAX;?>
        //console.log(discount_before_tax);//neu thay doi setting thi can check bang cai nay
        if(jQuery('#net_price').is(':checked'))//gia da co thue phi
        {
           <?php if(DISCOUNT_BEFORE_TAX == 1){?>//giam gia truoc thue phi
                
                var service_rate = 0;
        		if(jQuery("#service_rate").val()){
        			service_rate = jQuery("#service_rate").val();
        		}
        		total_payment = (total_payment*100)/(stringToNumber(service_rate) + 100);
        		total_payment_vnd = (total_payment*100)/(stringToNumber(service_rate) + 100);
                
                var tax = 0;
        		if(jQuery("#tax").val()){
        			tax = jQuery("#tax").val();
        		}
                total_payment = (total_payment*100)/(stringToNumber(tax) + 100);
        		total_payment_vnd = (total_payment*100)/(stringToNumber(tax) + 100);
               
                var discount = 0;
        		if(jQuery("#discount").val()){
        			discount = jQuery("#discount").val();
        		}
                discount = 100 - stringToNumber(discount);
                total_payment = total_payment*discount/100;
        		total_payment_vnd = total_payment*discount/100;
                
                var discount_amount = 0;
                if(jQuery("#discount_amount").val()){
        			discount_amount = jQuery("#discount_amount").val();
        		}
                total_payment = total_payment-discount_amount;
                total_payment_vnd = total_payment-discount_amount;
                console.log(total_payment);
                
                total_payment = total_payment + (total_payment*service_rate/100) + (total_payment + (total_payment*service_rate/100))*tax/100 ;
        		total_payment_vnd = total_payment + (total_payment*service_rate/100) + (total_payment + (total_payment*service_rate/100))*tax/100 ; 
        		
                jQuery("#total_amount").val((total_payment!='NaN')?number_format(roundNumber(total_payment,2)):'0');
        		jQuery('#total_amount_vnd').html(number_format(total_payment_vnd)+' &#273;');
        		$('currency_VND').value = number_format(total_payment_vnd); 
            <?php } else { ?>//giam gia sau thue phi
                var discount = 0;
        		if(jQuery("#discount").val()){
        			discount = jQuery("#discount").val();
        		}
                discount = 100 - stringToNumber(discount);
                total_payment = total_payment*discount/100;
        		total_payment_vnd = total_payment*discount/100;
                
                var discount_amount = 0;
                if(jQuery("#discount_amount").val()){
        			discount_amount = jQuery("#discount_amount").val();
        		}
                total_payment = total_payment-discount_amount;
                total_payment_vnd = total_payment-discount_amount;
                
                //total_payment = total_payment + (total_payment*service_rate/100) + (total_payment + (total_payment*service_rate/100))*tax/100 ;
        		//total_payment_vnd = total_payment + (total_payment*service_rate/100) + (total_payment + (total_payment*service_rate/100))*tax/100 ; 
        		//console.log(total_payment);
                //console.log(total_payment_vnd);
                total_amount = total_payment_vnd;
                var price_package =<?php echo [[=price_package=]]?>;
                
                total_payment -=price_package;
                if(total_payment<0)
                    total_payment =0;
                
                jQuery("#total_amount_hide").val((total_payment!='NaN')?roundNumber(total_payment,2):'0');

                jQuery("#total_amount").val((total_payment!='NaN')?number_format(roundNumber(total_payment,2)):'0');
        		jQuery('#total_amount_vnd').html(number_format(total_payment_vnd)+' &#273;');
        		$('currency_VND').value = number_format(total_payment_vnd); 
          <?php }?>
        }
        else//gia chua co thue phi
        {
            ;
            <?php if(DISCOUNT_BEFORE_TAX == 1){?>//giam gia truoc thue phi
                var discount = 0;
        		if(jQuery("#discount").val()){
        			discount = jQuery("#discount").val();
        		}
        		total_payment = total_payment - total_payment*(discount/100);
        		total_payment_vnd = total_payment_vnd - total_payment_vnd*(discount/100);
                
                var discount_amount = 0;
                if(jQuery("#discount_amount").val()){
        			discount_amount = jQuery("#discount_amount").val();
        		}
                total_payment = total_payment-discount_amount;
                total_payment_vnd = total_payment-discount_amount;
        		
                var tax = 0;
        		if(jQuery("#tax").val()){
        			tax = jQuery("#tax").val();
        		}
                var service_rate = 0;
        		if(jQuery("#service_rate").val()){
        			service_rate = jQuery("#service_rate").val();
        		}
        		total_payment = total_payment + (total_payment*service_rate/100) + (total_payment + (total_payment*service_rate/100))*tax/100 ;
        		total_payment_vnd = total_payment + (total_payment*service_rate/100) + (total_payment + (total_payment*service_rate/100))*tax/100 ;
        		jQuery("#total_amount").val((total_payment!='NaN')?number_format(roundNumber(total_payment,2)):'0');
        		jQuery('#total_amount_vnd').html(number_format(total_payment_vnd)+' &#273;');
        		$('currency_VND').value = number_format(total_payment_vnd);
            <?php } else { ?>//giam gia sau thue phi
                var tax = 0;
        		if(jQuery("#tax").val()){
        			tax = jQuery("#tax").val();
        		}
                var service_rate = 0;
        		if(jQuery("#service_rate").val()){
        			service_rate = jQuery("#service_rate").val();
        		}
        		total_payment = total_payment + (total_payment*service_rate/100) + (total_payment + (total_payment*service_rate/100))*tax/100 ;
        		total_payment_vnd = total_payment + (total_payment*service_rate/100) + (total_payment + (total_payment*service_rate/100))*tax/100 ;
                
                var discount = 0;
        		if(jQuery("#discount").val()){
        			discount = jQuery("#discount").val();
        		}
        		total_payment = total_payment - total_payment*(discount/100);
        		total_payment_vnd = total_payment_vnd - total_payment_vnd*(discount/100);
                
                var discount_amount = 0;
                if(jQuery("#discount_amount").val()){
        			discount_amount = jQuery("#discount_amount").val();
        		}
                total_payment = total_payment-discount_amount;
                total_payment_vnd = total_payment-discount_amount;
                
                var price_package =<?php echo [[=price_package=]]?>;
                
                total_payment -=price_package;
                if(total_payment<0)
                    total_payment =0;
                console.log(total_payment);
                jQuery("#total_amount").val((total_payment!='NaN')?number_format(roundNumber(total_payment,2)):'0');
        		jQuery('#total_amount_vnd').html(number_format(total_payment_vnd)+' &#273;');
        		$('currency_VND').value = number_format(total_payment_vnd);
            <?php }?>
        }
        check_room_checkout();
	}
    function check_discount_amount(){
        if(jQuery("#discount_amount").val() != ''){
            jQuery("#discount").attr('readonly',true);
            jQuery("#discount").val('');
        }
        else{
            jQuery("#discount").attr('readonly',false);
            jQuery("#discount").val()== '';
        }
    }
    function check_discount1(){
        if(jQuery("#discount").val() != ''){
            jQuery("#discount_amount").attr('readonly',true);
            jQuery("#discount_amount").val('');
        }
        else{
            jQuery("#discount_amount").attr('readonly',false);
            jQuery("#discount_amount").val()== '';
        }
    }
	function countProductAmount(id){
		$('amount_'+id).value = number_format(stringToNumber($('price_'+id).value)*$('quantity_'+id).value);
        $('use_time_'+id).value = $('minute_'+id).value*$('quantity_'+id).value;
	}
	function getStaffFromCode(id,value){
		if(typeof(staff_arr[value])=='object'){
			$('full_name_'+id).value = staff_arr[value]['full_name'];
			$('full_name_'+id).className = '';
		}else{
			if(value){
                $('full_name_'+id).className = 'notice';
				$('full_name_'+id).value = '[[.staffs_does_not_exist.]]';
			}else{
				$('full_name_'+id).value = '';
			}
		}
	}    
    function check_room_checkout()
    {
        if(jQuery('#hotel_reservation_room_id').val() != '')
        {
            if(to_numeric(jQuery('#total_amount_old').val()) < to_numeric(jQuery('#total_amount').val()))
            {
                hotel_reservation_room_id = jQuery('#hotel_reservation_room_id').val();
                jQuery.ajax({
                    url:"form.php?block_id="+block_id,
                    type:"POST",
                    data:{action:'CheckRoomCheckOut', hotel_reservation_room_id:hotel_reservation_room_id},
                    success:function(res){
                        res = jQuery.parseJSON(res);
                        if(res.status == 'CHECKOUT')
                        {
                            jQuery('#payment').css('display', '');
                        }
                    }
                });                
            }
        }
    }    
	function getProductFromCode(id,value){	 	   
	    if(typeof(product_array[value])=='object'){
            $('code_'+id).value = product_array[value]['product_id'];
			$('product_id_'+id).value = product_array[value]['product_id'];
			$('name_'+id).value = product_array[value]['name'];
			$('price_'+id).value = product_array[value]['price'];
            $('minute_'+id).value = product_array[value]['use_time'];
			$('name_'+id).className = '';
            $('quantity_'+id).value = 1;
            $('amount_'+id).value = number_format(stringToNumber($('price_'+id).value)*$('quantity_'+id).value);
            $('use_time_'+id).value = number_format(stringToNumber($('minute_'+id).value)*$('quantity_'+id).value);
            updateTotalPayment();
		}else{
			$('product_id_'+id).value = '';
			$('price_'+id).value = '';
			$('quantity_'+id).value = '';
			$('amount_'+id).value = '';
			if(value){
				$('name_'+id).className = 'notice';			
				$('name_'+id).value = '[[.product_does_not_exist.]]';
			}else{
				$('name_'+id).value = '';
			}
		}
	}
	function productAutoComplete()
	{
		jQuery("#code_"+input_count).autocomplete({
                url: 'get_product.php?massage=1',
                onItemSelect: function(item){
                    console.log(item);
                    getProductFromCode(input_count,jQuery("#code_"+input_count).val());
                    jQuery(".acResults").css('display','none');
                    jQuery(".acResults").remove();
    	       }
        });
	}
	mi_init_rows('mi_staff_group',<?php echo isset($_REQUEST['mi_staff_group'])?String::array2js($_REQUEST['mi_staff_group']):'{}';?>);
	mi_init_rows('mi_product_group',<?php echo isset($_REQUEST['mi_product_group'])?String::array2js($_REQUEST['mi_product_group']):'{}';?>);
	
    var check_status = false;
    for(var i=101;i<=input_count;i++)
    {
		if(jQuery("#code_"+i)){
			jQuery("#code_"+i).autocomplete({
					url: 'get_product.php?massage=1'
			});
		}
		if(jQuery('#time_in_hour_'+i)){
			jQuery('#time_in_hour_'+i).mask("99:99");
		}
		if(jQuery('#time_out_hour_'+i)){
			jQuery('#time_out_hour_'+i).mask("99:99");
		}
        if(jQuery('#status_'+i).val()=='CHECKIN' || jQuery('#status_'+i).val()=='BOOKED')
        {
            check_status=true;
        }
	}
    <?php if(Url::get('cmd')=='edit'){?>
    if(check_status==false)
    {
        <?php if(!User::is_admin()){?>
        //jQuery('.w3-btn').css('display','none');
//        jQuery('#payment').css('display','none');
        <?php }?>
    }
    var payment = [[|payment|]];
    if(payment>0)
    {
        jQuery("#hotel_reservation_room_id").each(function(){
            jQuery("#hotel_reservation_room_id option").not(":selected").attr("disabled", "disabled");
            jQuery('#hotel_reservation_room_id').css({"background-color":"#CCCCCC"});
            jQuery("#pay_with_room").attr("disabled", "disabled");
        });
    }
    <?php }?>
    <?php if(isset($_REQUEST['mi_product_group'])){?>
	for(var i=101; i<=input_count; i++)
	{
		//show_datepicker(i);
        productAutoComplete();
	}
	<?php }?>
    
	function show_datepicker(id)
	{		
        var CURRENT_YEAR = <?php echo date('Y')?>;
        var CURRENT_MONTH = <?php echo intval(date('m')) - 1;?>;
        var CURRENT_DAY = <?php echo date('d')?>;
    	$('time_in_date_'+i).value = '<?php echo date('d/m/Y',time());?>';
		$('time_out_date_'+i).value = '<?php echo date('d/m/Y',time()+3600);?>';                
        jQuery("#time_in_date_"+id).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
        jQuery("#time_out_date_"+id).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
	}
    //Chi dc chon nv trong cac phong da dc chon
    function check_room(obj,value)
    {
        if(value!= '')
        {
            var check = false;
            jQuery(".product").each(function(){
                if(this.value!='')
                {
                    if(value == this.value )
                        check = true;
                }
            });
            if(!check)
                jQuery(obj).val('');
        }   
    }
    
    //Delete cac staff trong room vua bi xoa
    function delete_staff(id)
    {
        //lay ra room bi xoa
        var room = jQuery("#room_id_"+id).val();
        if(room!='')
        {
            //duyet qua cac mi staff
            jQuery(".staff").each(function(){
                var index = jQuery(this).attr('index');
                //Neu cac room trong mi staff = room bi xoa thi xoa cac mi nay
                if(jQuery("#room_id_"+index).val()==room)
                    mi_delete_row($('mi_staff_group_'+index),'mi_staff_group',index,'staff_')
                    
            });
            
        }
    }
    function openPayment()
    {
        var check_edit = <?php if (Url::get('cmd') == 'edit') echo '\'ok\''; else echo '\'no\''; ?>;
        if (check_edit === 'no')
        {
            alert('[[.please_save_first.]]');
            return false;
        }
        if(to_numeric(jQuery('#total_amount').val()) == to_numeric(jQuery('#total_amount_old_2').val()))
        {
            var pay_with_room = jQuery('#hotel_reservation_room_id').val();
            var id = <?php if (Url::get('cmd') == 'edit') echo Url::get('id'); else echo 0; ?>;
            id = to_numeric(id);
            var total_amount = to_numeric(jQuery('#total_amount').val());
            var member_code = jQuery('#member_code').val();
            if(1==1)//pay_with_room === ''
            {
                if(member_code){
                    openWindowUrl('form.php?block_id=428&id='+id+'&type=SPA&member_code='+member_code+'&total_amount='+total_amount,Array('payment','payment_for',80,210,950,500)); 
                }else{
                    openWindowUrl('form.php?block_id=428&id='+id+'&type=SPA&total_amount='+total_amount,Array('payment','payment_for',80,210,950,500));
                } 
            }
            else
            {
                
            }
        }else
        {
            alert('[[.please_save_first.]]');
            return false;
        }
    }
    function change_net_price()
    {
        updateTotalPayment();
    }
     

    function check_validate_time(id){
        var today= new Date();                
        var day;
        (today.getDate()<10)?day = '0'+today.getDate():day = today.getDate;                
        var today_ = day+'/'+(today.getMonth()+1)+'/'+today.getFullYear();
        var tomorrow = (today.getDate()+1)+'/'+(today.getMonth()+1)+'/'+today.getFullYear();
        var time_in_date = jQuery('#time_in_date_'+id).val().replace(/\//g,'');
        var time_out_date = jQuery('#time_out_date_'+id).val().replace(/\//g,'');        
        var time_in_hour = jQuery('#time_in_hour_'+id).val().replace(/:/,'');
        var time_out_hour = jQuery('#time_out_hour_'+id).val().replace(/:/,'');                               

        var time_in_hour = jQuery('#time_in_hour_'+id).val();
        var time_out_hour = jQuery('#time_out_hour_'+id).val();                        
        var noti ="Ngày đến phải nhỏ hơn hoặc bằng ngày đi";  
        var noti1 ="Ngày không được để trống! Vui lòng điền ngày.";                                                                         
        if(time_in_hour !=''){
            if(time_in_date > time_out_date){
                alert(noti);
                jQuery('#time_in_date_'+id).val(today_);
                return false;
            }else if(time_in_date == time_out_date){            
                if((time_in_hour > time_out_hour)){
                    alert ('Giờ đi phải lớn hơn giờ đến');
                    jQuery('#time_out_hour_'+id).val('');
                    return false;
                }
            }
        }                  
    }
    function fun_load_member_code(){
    var member_code = jQuery("#member_code").val();
    if(member_code!=''){
        if (window.XMLHttpRequest){
            xmlhttp=new XMLHttpRequest();
        }
        else{
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var text_reponse = xmlhttp.responseText;
                var otbjs = jQuery.parseJSON(text_reponse);
                for(obj in otbjs){
                    if(otbjs[obj]['no_member']==0){
                        alert("mã thành viên không tồn tại, vui lòng nhập lại mã!");
                        jQuery("#member_code").val('');
                        jQuery("#member_level_id").val('');
                        jQuery("#create_member_date").val('');
                        return;
                    }else{
                        jQuery("#member_level_id").val(otbjs[obj]['MEMBER_LEVEL_ID']);
                        jQuery("#create_member_date").val(otbjs[obj]['create_member_date']);
                    }
                }
            }
        }
        xmlhttp.open("GET","get_member.php?data=get_member_discount&member="+member_code,true);
        xmlhttp.send();
    }
}
function fun_view_info_member(){
    var member_level_id = jQuery("#member_level_id").val();
    var create_member_date = jQuery("#create_member_date").val();
    if(member_level_id == ''){
        alert("không có chương trình giảm giá giảm giá! kiểm tra lại mã thành viên!");
    }else{
    if (window.XMLHttpRequest){
            xmlhttp=new XMLHttpRequest();
        }
        else{
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var text_reponse = xmlhttp.responseText;
                var otbjs = jQuery.parseJSON(text_reponse);
                //console.log(otbjs);
                var info = '';
                if(otbjs['info_member']['no_discount']==0){
                    alert("Không có chương trình giảm giá cho hạng thành viên này!");
                    return;
                }else{
                    info += '<table style="width: 100%; border: 1px solid #999999" >';
                            info += '<tr>';
                                info += '<th>[[.code_discount.]]</th>'
                                info += '<th>[[.title_discount.]]</th>'
                                info += '<th>[[.description_discount.]]</th>'
                                info += '<th>[[.start_date_discount.]]</th>'
                                info += '<th>[[.end_date_discount.]]</th>'
                            info +='</tr>';
                        for(var otbj in otbjs){
                            if(otbj!='info_member'){
                                info += '<tr>';
                                    info += '<th>'+otbjs[otbj]['MEMBER_DISCOUNT_CODE']+'</th>'
                                    info += '<th>'+otbjs[otbj]['TITLE']+'</th>'
                                    info += '<th>'+otbjs[otbj]['DESCRIPTION']+'</th>'
                                    info += '<th>'+otbjs[otbj]['START_DATE']+'</th>'
                                    info += '<th>'+otbjs[otbj]['END_DATE']+'</th>'
                                info +='</tr>';
                                
                            }
                        }
                        info += '</table>';
                        document.getElementById("info_member_discount").innerHTML = info;
                        jQuery("#div_info").css('display','');
                }
            }
        }
        xmlhttp.open("GET","get_member.php?data=get_member_info&member_level_id="+member_level_id+"&date="+create_member_date,true);
        xmlhttp.send();
        }
    }
    function fun_close_info(){
        jQuery("#div_info").css('display','none');
    }
    function add_room_staff(obj)
    {
        jQuery('.staff_'+obj).css('display','');        
    }
    
    
    jQuery('.product').each(function(){
        jQuery('.staff_'+jQuery(this).val()).css('display','');
    });
    function Autocomplete()
    {
        jQuery("#customer_name").autocomplete({
             url: 'get_customer_search_fast.php?customer=1',
             onItemSelect: function(item){
                document.getElementById('customer_id').value = item.data[0];
            }
        }) ;
    }
function DeleteInput(xxxx)
{
    updateTotalPayment();
}
function check_delete()
{
    var cf = confirm('[[.are_you_sure.]]');
    if(cf)
    {
        var check = false;
        var notice = '';
        <?php if(DB::fetch('SELECT * FROM traveller_folio WHERE invoice_id =\''.Url::get('id').'\' AND type =\'MASSAGE\'')){?>
            check = true;
            notice = notice+'[[.da_tao_hoa_don_tai_phong_khach_san.]]';
        <?php }?>
        <?php if(DB::fetch('SELECT * FROM payment WHERE bill_id =\''.Url::get('id').'\' AND type =\'SPA\'')){?>
            check = true;
            notice = notice+'[[.da_thanh_toan.]]';
        <?php }?>
        if(check==true)
        {
            notice = notice+' '+'[[.khong_duoc_xoa.]]';
            alert(notice);
            return false;
        }
        else
        {
            return true;
        }
    }
    else
    {
        return false
    }
}
function check_pay_with_room(obj)
{
    if(jQuery(obj).is(":checked"))
    {
        jQuery("#payment").css("display","none");
    }
    else
    {
        jQuery("#payment").css("display","");
    }
}
</script>
