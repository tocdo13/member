<style>
#banner_url{display:none;}
input[type=text]{ 
    height:22px !important;
}
</style>
<script>
    var index = 1;
    var room_array={
        '':''
    <!--LIST:reservation_traveller_list-->
    <!--IF:cond_extra1(isset([[=reservation_traveller_list.reservation_room_id=]]))-->
        ,'[[|reservation_traveller_list.id|]]':{
            'id':'[[|reservation_traveller_list.reservation_room_id|]]',
            'name':'<?php echo addslashes([[=reservation_traveller_list.name=]])?>'
        }
    <!--/IF:cond_extra1-->
    <!--/LIST:reservation_traveller_list-->
    }
    /*=================== phan cua vip_card ====================*/
    var vip_card={
        '':''
    <!--LIST:vip_cards-->
        ,'[[|vip_cards.code|]]':{
            'code':'[[|vip_cards.code|]]',
            'name':'<?php echo addslashes([[=vip_cards.name=]])?>',
            'discount_percent':'[[|vip_cards.discount_percent|]]'
        }
    <!--/LIST:vip_cards-->
    }    
    /*=================== /phan cua vip_card ====================*/
</script>
<div id="order_full_screen">
<div><input value="[[.full_screen.]]" name="full_screen_button" type="button" id="full_screen_button" onclick="switchFullScreen();" class="button-large fullscreen" style="float:right; display:none;"/></div>
<br clear="all" />
<form name="TouchBarRestaurantForm" method="post">
<div id="mask"></div>
<div style="padding:0px;">
    <div><?php echo Form::$current->error_messages();?></div>
    <div>
    
    <div style="float:right; width:100%;margin-right:0px;text-align:right;white-space:nowrap;margin-bottom:5px;">
        <div style="float:left;">
        <span style="font-size:20px;text-transform:uppercase;padding-left:5px;" class="title">[[|bar_name|]]</span>
        <br />
        <span style="font-size:14px;text-transform:uppercase;padding-left:5px;float:left; color:#666;" class="title">[[|table_name|]]</span>
        </div>
        <div style="float:left;padding-left:10px;width:150px;display:none;" id="restaurant_other">
        <div align="right">[[.restaurant_menu.]]: <input name="bar_name" type="text" id="bar_name" style="width:200px; border:none; font-size:14px; font-weight:bold;" readonly="readonly" /></div>
        <div id="div_add_charge" style="text-align:left;">[[.add_charge.]]: <span id="add_charge"></span>%</div>
        </div>

                                    <input name="acction" type="hidden" value="0" id="acction" />
                              
        <?php if(User::can_add(false,ANY_CATEGORY)){?>
        <input name="checkin" type="button" value="CHECKIN" id="checkin" class="button-medium" onclick="checkSubmit(this);" style="min-width:100px;height:45px;text-align:left;color:#000000;margin-left:2px;margin-right:2px;float:right;text-decoration:none;text-indent:20px;cursor:pointer;"/>
        <input name="save" type="button" value="SAVE" id="save" class="button-bar-save1" onclick="checkSubmit(this);" <?php if(!isset([[=status=]]) || [[=status=]]==''){echo 'style="display:none;"';}?> /><input name="act" type="hidden" id="act" value="" />
        <input name="save_stay" type="button" value="SAVE & STAY" id="save_stay" class="button-bar-save1" onclick="checkSubmit(this);" <?php if(!isset([[=status=]]) || [[=status=]]==''){echo 'style="display:none;"';}?> />
        <input name="booked" type="button" value="BOOKED" id="booked" class="button-medium" onclick="checkSubmit(this);" style="min-width:100px;height:45px;text-align:left;color:#000000;margin-left:2px;margin-right:2px;float:right;text-decoration:none;text-indent:20px;cursor:pointer;"/>
        <input name="back" type="button" value="[[.back.]]" class="button-medium-back" onclick="window.location='<?php echo Url::build('table_map',array('bar_id'=>Session::get('bar_id'),'group'=>Session::get('group')));?>'" />
         <div id="password_box" style="display:none;position:absolute;top:auto;left:auto;border:1px solid #000000;padding:10px;text-align:center;background:#FFFF99;">
                                    [[.password.]]: <input  name="password" type="password" id="password">
                                    <input type="submit" name="save" value="[[.OK.]] " tabindex="-1" style="color:#000066;font-weight:bold;">
                                    <a class="close" onclick="jQuery('#password_box').hide();$('password').value='';">[[.close.]]</a>
         </div>
        <div id="dynamic_save_button_bound"></div>
        <div>
            <!--<input type="checkbox" class="checkblock" id="checkblock" onclick="blockcss();" />Block-->
        </div>
        <?php }?>
    </div>
    </div>
    <table cellpadding="2" width="100%" border="1" bordercolor="#A6A6A6" id="table_bound">
        <tr>
            <td class="info-left" id="hidden_expan" style="background:#F5F3F3;">    
                    <div id="product_select_expansion">
                    <div class="select-menu-bound">
                        <a name="top" ></a>
                        <div class="title"><span style="float:left; line-height:30px;">[[.search.]]:</span>
                        <table id="search_div" width="250px;">
                            <tr>
                                <td width="70%"><input name="input_product_name" type="text" id="input_product_name" onkeypress="if(event.keyCode==13){searchProduct('product',jQuery('#bar_id_other').val());return false;}" onkeyup="if(event.keyCode==13){searchProduct('product',jQuery('#bar_id_other').val());return false;}else{checkSearchProduct(this,event.keyCode);searchProduct('product',jQuery('#bar_id_other').val());}" maxlength="400"/>
                                </td>
                                <td width="30%"><img src="packages/hotel/skins/default/images/iosstyle/tia.png" class="keyboard" onclick="get_keyboard('input_product_name','');"/><img src="packages/hotel/skins/default/images/iosstyle/delete-smaller.png" onclick="jQuery('#input_product_name').val('');" style="margin-left:5px;"/> 
                                </td>
                            </tr>
                        </table>
                        <input name="bar_name_this" type="button" value="[[|bar_name|]]" onclick="jQuery('#restaurant_other').css('display','none');jQuery('#bar_id_other').val('<?php echo Session::get('bar_id');?>');searchProduct('this_bar',jQuery('#bar_id_other').val());" class="expan"/><input name="select_bar" type="button" value="[[.select_bar.]]" id="select_bar" onclick="jQuery('#div_select_bar').css({'display':'block','top':'250px','left':'600px'});" class="expan"/><input name="search_product" type="button" value="[[.search.]]" id="search_product" onclick="searchProduct('product',jQuery('#bar_id_other').val());" class="expan"/><input name="product_select_expansion" type="button" value="[[.expan.]]" id="expan" class="expan" onclick="search_product();" style="float:right;display:none;"/><input name="back" type="button" value="[[.Back.]]" id="back" class="expan" /></div>
                        <div>
                            <div id="div_food_category">[[|food_categories|]]
                            </div>
                            <div class="body">[[|products|]]</div>
                        </div>
                    </div>
                    <div id="post_show"></div>
                    <div id="show_food_drink"></div>
                    <div id="div_discount_percent" style="width:220px !important; display:none;">
                        <table width="200">
                            <tr>
                                <td width="120">[[.category_discount.]]</td>
                                <td width="80">[[.percent.]]</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </td>
            <td class="info-right" id="expan_display">
                <div class="info">
                    <div style="float:left;">
                    <span style="font-size:12px; font-weight:500;" >[[.create_date.]]: &nbsp;[[|date|]]</span><br />
                    <!--IF:cond_bill(isset([[=code=]]) and [[=code=]])--><b>[[.Bill_ID.]]: [[|code|]]</b><!--/IF:cond_bill-->
                    
                      <input  name="status" type="text" id="status" value="[[|status|]]" readonly="readonly" style="border:none; font-weight:bold;background:#F7F6F6; width:80px; " />
                    
                    </div>
                        <div style="float:right;">
                            <input type="button" name="preview" value=" [[.view_order.]]" tabindex="-1" class="expan" onclick="if(jQuery('#status').val()!=''){window.open('<?php echo Url::build('touch_bar_restaurant',array('cmd'=>'detail',md5('act')=>md5('print_bill'),md5('preview')=>1,'id'=>Url::get('id'),'bar_id'))?>');}else{jQuery('#act').val('submit_invoice');TouchBarRestaurantForm.submit();}"/>                            
                            <input type="button" name="preview" value=" [[.food_order.]] " onclick="sentToPrint('kitchen','<?php echo Url::get('id');?>');" tabindex="-1" class="expan" style="display:none;">
                            <input type="button" name="preview" value=" [[.drink_order.]] " onclick="sentToPrint('bar','<?php echo Url::get('id');?>');" tabindex="-1" class="expan" style="display:none;">
                            <input type="button" value="[[.print_banquet_event_order.]]" onClick="window.open('<?php echo Url::build('touch_bar_restaurant',array('cmd'=>'detail','act'=>'print_b_e_order','id'=>Url::get('id')));?>');" tabindex="-1" class="expan" style="display:none;">
                        </div>
              </div>
              <div id="menu_extra">
                    <ul id="menu_extra_ul" style="float:left;">
                        <li class="menu_extra_li" id="li_quantity" lang="delete" onclick="getSelectQuantity('delete');return false;"><img src="packages/hotel/skins/default/images/iosstyle/1.png" /></li>
                        <li class="menu_extra_li" id="li_quantity" onclick="getSelectQuantity('quantity');return false;"><img src="packages/hotel/skins/default/images/iosstyle/quantity.png" /></li>
                        <li class="menu_extra_li" id="li_percentage" onclick="getSelectQuantity('percentage');return false;"><img src="packages/hotel/skins/default/images/iosstyle/4.png" /></li>
                        <li class="menu_extra_li" id="li_edit" onclick="items_checked();return false;"><img src="packages/hotel/skins/default/images/iosstyle/3.png" /></li>
                        <li class="menu_extra_li_extra" id="li_discount" onclick="getSelectQuantity('discount');return false;"><img src="packages/hotel/skins/default/images/iosstyle/5.png" /></li>
                        <li class="menu_extra_li_extra" id="li_extra" onclick="getSelectOutSide('extra');return false;"><img src="packages/hotel/skins/default/images/iosstyle/6.png" /></li>
                        <li class="menu_extra_li_order" id="li_deposit" onclick="openWindowUrl('form.php?block_id=<?php echo BLOCK_PAYMENT;?>&cmd=deposit&id=<?php if(Url::get('id')){echo Url::get('id');}else{echo '';}?>&type=BAR',Array('payment','payment_for',80,210,950,500));"><img src="packages/hotel/skins/default/images/iosstyle/7.png" /></li>
                        <li class="menu_extra_li_order" id="li_payment" onclick="SubmitForm('payment');"><img src="packages/hotel/skins/default/images/iosstyle/8.png" /></li>
                        <li class="menu_extra_li_order" id="li_vat" style="background:#ffffc0; display:none;" onclick="window.location='?page=vat_bill&department=RESTAURANT&cmd=entry_restaurant&b_r_id=<?php echo Url::get('id');?>'">
                            <img src="packages/hotel/skins/default/images/iosstyle/icon-vat.png" />
                        </li>    
                        <li class="menu_extra_li_order" style="display:none;"><input name="guest_info" type="button" id="guest_info" value="[[.for_sale.]]" class="summary" onclick="getResevationRoom();"/> </li>
                        <li class="menu_extra_li_extra" id="li_summary" onclick="getSummary();" > <img src="packages/hotel/skins/default/images/iosstyle/9.png" /></li>
                    </ul>              
              </div>
                <div id="foot_drink_bound" style="width:84%; float:right;" class="selected-foot-and-drink-table">
                    <table cellpadding="5" width="100%" id="foot_drink" cellspacing="5">
                        <tr class="foot-drink-tr header">
                            <td width="215">[[.name_product.]]</td>
                            <td width="26" align="center">SL</td>
                            <td width="100" align="right">[[.price.]]</td>
                            <td width="20">&nbsp;</td>
                            <td width="25">[[.note.]]</td>
                            <td width="25">&nbsp;</td>
                            <td width="25">&nbsp;</td>
                        </tr>
                    </table>
                    <div id="show_detail"><!--IF:cond(isset([[=items=]]) AND !empty([[=items=]]))-->[[|items|]]<!--/IF:cond--></div>
                </div>
                <input name="items_id" type="hidden" id="items_id"/>
                <script>
                    jQuery("items_id").value = '<?php if(Url::get('items_id')){ echo Url::get('items_id');}?>';
                </script>
                <div style="margin-bottom:5px; margin-top:5px;" align="right">
                	<font style="font-size:12px; "><b>[[.total_payment_traveller.]] (<?php echo HOTEL_CURRENCY;?>)</b></font>: &nbsp;<input name="total_payment_traveller" id="total_payment_traveller" type="text" onkeyup="format_price();" value="<?php if(isset([[=total_payment_traveller=]])){echo System::display_number([[=total_payment_traveller=]]);}else{echo 0;} ?>" style="text-align: right;" />
                </div>
                <script>
                    function format_price(){
                        var for_mat = to_numeric(jQuery('#total_payment_traveller').val());
                        var total = to_numeric(jQuery("#amount_mini").html());
                        if(for_mat<total){
                            jQuery('#total_payment_traveller').css('background','#ffaaaa');
                        }else{
                            jQuery('#total_payment_traveller').css('background','#ffffff');
                        }
                        to_remain = for_mat-total; to_remain = number_format(to_remain);
                        jQuery('#total_remain_traveller').html(to_remain);
                        for_mat = number_format(for_mat);
                        jQuery('#total_payment_traveller').val(for_mat);
                        
                    }
                </script>
                <div id="total_mini" style="margin-bottom:5px; margin-top:5px;" align="right">
                	<font style="font-size:15px; "><b>[[.total_amount.]] (<?php echo HOTEL_CURRENCY;?>)</b></font>: &nbsp;<span id="amount_mini" style="font-size: 15px; font-weight: bold;"><b>[[|remain|]]</b></span>
                </div>
                <div style="margin-bottom:5px; margin-top:5px;" align="right">
                	<font style="font-size:12px; "><b>[[.total_remain_traveller.]] (<?php echo HOTEL_CURRENCY;?>)</b></font>: &nbsp;<label id="total_remain_traveller">0</label>
                </div>
                <!--<div id="show_table">
                    <table cellpadding="0" cellspacing="0" width="84%" style="float:right;">     
                        <tr class="foot-drink-tr header">
                            <td width="70">[[.table_name.]]</td>
                            <td width="30">[[.num_people.]]</td>
                            <td width="70">[[.order_person.]]</td>
                            <td width="40">[[.select.]]</td>
                            <td width="20"></td>
                        </tr>
                    </table>
                </div>-->
                <div id="show_table">
                    <table cellpadding="0" cellspacing="0" width="75%" style="float:right;">     
                        <tr class="foot-drink-tr header">
                            <td width="60">[[.table_name.]]</td>
                            <td width="60">[[.split_table_button.]]</td>
                            <td width="60">[[.coupling_table.]]</td>
                            <td width="40">[[.select.]]</td>
                            <td width="20"></td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
      </table>
      <div id="info_summary"> [[|categories|]]  </div> 
                <div id="div_bound_summary">
                <div id="bound_summary" class="dragclass" style="display:block; position: absolute; top:100px; left:500px;">
                <div class="info"><span class="title_bound">[[.summary.]]</span><img width="30" height="25" id="btnClose" src="packages/core/skins/default/images/buttons/delete.gif" onclick="HideDialog('bound_summary');" style="float:right;margin:5px;"/></div>
                <table cellpadding="0" width="100%">
                <tr>
                <td>
                <fieldset style="text-align:left; margin-bottom:10px;margin-top:10px;">
                <legend class="title">[[.guest_retail.]]</legend>
                    <table cellpadding="3" width="55%">
                        <tr>
                            <td nowrap>[[.guest_name.]]:</td>
                            <td><input name="receiver_name" type="text" id="receiver_name" value="[[|receiver_name|]]" style="width:150px;"  /></td>
                          </tr>
                        <tr>
                            <td>[[.company.]]:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            <td nowrap="nowrap"><input name="customer_name" type="text" id="customer_name" value="[[|customer_name|]]" class="input-text-max" style="float:left;" />
                              <input name="customer_id" type="text" id="customer_id" value="<?php if([[=customer_id=]]){echo [[=customer_id=]];}else{ echo '';}?>" class="hidden" />
                              <a href="#" onclick="window.open('?page=customer&amp;action=select_customer','customer')"> <img width="30" src="skins/default/images/cmd_Tim.gif" style="float:left;margin-right:10px;" /></a> <img width="30" src="packages/core/skins/default/images/buttons/delete.gif" onClick="$('customer_name').value='';$('customer_id').value=0;" style="cursor:pointer;float:left;"></td>
                            
                        </tr>
                   </table>
                </fieldset>
                </td>
                <td valign="top">
                <fieldset style="text-align:left; margin-bottom:10px;margin-top:10px;height:89px;" id="guest_room_info">
                <legend class="title"><img src="packages/core/skins/default/images/customer_icon.jpg" width="15" height="20" align="top"/>&nbsp;[[.guest_room.]]</legend>
                    <table cellpadding="3" width="55%">
                        <tr>
                            <td nowrap>&nbsp;</td>
                            <th align="left">[[.select_guest_room.]] <br /><select name="reservation_traveller_id" id="reservation_traveller_id" onChange="update_room(this);if(this.value!=0){jQuery('#pay_with_room').attr('checked',true);}"  style="width:170px;height:35px;"></select></th>
                            <td nowrap width="50%">[[.room_name.]] <br /><select name="reservation_room_id" id="reservation_room_id"  onChange="update_traveller(this);if(this.value!=0){ jQuery('#pay_with_room').attr('checked',true);}" style="width:100px;height:35px;"></select></td>
                            <td align="right" valign="bottom">
                            <input name="pay_with_room" type="checkbox" class="checkbox" id="pay_with_room" <?php if([[=pay_with_room=]]==1){ echo 'checked="checked"';}else{echo 'style="display:none;"';}?> />
                            <label for="pay_with_room"><span class="checkbox-span" title="[[.pay_with_room.]]">✓</span></label>
                            </td>
                          </tr>
                   </table>
                </fieldset> 
                </td>
                </tr>
                </table>               
                <fieldset>
                <legend class="title">[[.Order_info.]]</legend>
                <table>
                        <tr>
                                <td nowrap="nowrap" width="100">[[.arrival_time.]]<br />
                                <input name="arrival_date" type="text" id="arrival_date"  class="input-text" value="[[|arrival_date|]]" onclick="jQuery('#ui-datepicker-div').css('z-index',103);" /></td>
                             <td nowrap="nowrap"  width="90">[[.arrival_time_in.]]<br />
                                 <input name="arrival_time_in_hour" type="text" id="arrival_time_in_hour" class="input_number" style="width:30px;" maxlength="2" />h
                                 <input name="arrival_time_in_munite" type="text" id="arrival_time_in_munite" class="input_number" style="width:30px;" maxlength="2"  /></td>
                             <td nowrap="nowrap"  width="90">[[.arrival_time_out.]]<br />
                                 <input name="arrival_time_out_hour" type="text" id="arrival_time_out_hour" class="input_number" style="width:30px;" maxlength="2"  />h
                                <input name="arrival_time_out_munite" type="text" id="arrival_time_out_munite" class="input_number" style="width:30px;" maxlength="2"  />
                              </td>
                             <td>[[.banquet_order_type.]]<br />
                                 <input name="banquet_order_type" type="text" id="banquet_order_type" value="[[|banquet_order_type|]]" class="input-text" />
                             </td>
                        </tr>
                        <tr>
                            <td width="22%" nowrap="nowrap">[[.num_people.]]<br />
                             <input name="num_people" type="text" id="num_people" value="[[|num_people|]]" class="input-text"></td>
                             <td width="22%" nowrap="nowrap">[[.order_person.]]<br /><input name="order_person" type="text" value="[[|order_person|]]" id="order_person" class="input-text" /></td>
                             <td width="22%" nowrap="nowrap" colspan="2">[[.note.]]<br /><input name="note" type="text" id="note" value="[[|note|]]" class="input-text-max" style="width:235px;"/></td>
                        </tr>                
                </table>
                </fieldset>
                <fieldset>
                <legend class="title">[[.billing_info.]]</legend>
                <table class="general-info" align="center" border="1" cellpadding="3" cellspacing="3" width="100%" >
                   <tr>
                        <td width="1%" nowrap="nowrap">[[.amount.]]:</td>
                        <td><input name="total_amount" type="text" id="total_amount" value="[[|total_amount|]]" readonly="readonly"  maxlength="200" class="general">&nbsp; &nbsp;<label><?php echo HOTEL_CURRENCY;?></label></td>
                        <td width="1%" nowrap="nowrap">Full charge:<input name="full_charge" type="checkbox" id="full_charge" value="1" <?php if([[=full_charge=]]==1){echo 'checked="checked"';}?> disabled="disabled"/><input name="input_full_charge" type="text" id="input_full_charge" value="[[|full_charge|]]" style="display:none;" /> </td>
                        <td>Full rate: <input name="full_rate" type="checkbox" id="full_rate" value="1" <?php if([[=full_rate=]]==1){echo 'checked="checked"';}?> disabled="disabled"/><input name="input_full_rate" type="text" id="input_full_rate" value="[[|full_rate|]]" style="display:none;"/></td>
                   </tr>
                   <tr>
                        <td width="1%" nowrap="nowrap">[[.service_charge.]]/[[.tax_rate.]]:</td>
                        <td><input name="service_charge" type="text" id="service_charge" value="[[|service_charge|]]" class="input_number"  maxlength="3" style="width:30px !important;text-align:right;margin-right:15px;" onkeyup="if(to_numeric(this.value)<=100){GetTotalPayment();}else{this.value=((this.value).substr(0,((this.value).length-1)));}" /><input name="tax_rate" type="text" id="tax_rate" value="[[|tax_rate|]]" class="input_number"  maxlength="3" style="width:30px !important;text-align:right;" onkeyup="this.value=number_format(this.value);GetTotalPayment();" onblur="GetTotalPayment();" /></td>
                        <td width="1%" nowrap="nowrap">[[.discount.]](%/VND):<input name="discount_after_tax" type="checkbox" id="discount_after_tax" title="[[.discount_after_tax.]]" style="display:none;" /></td>
                        <td><input name="discount_percent" type="text" id="discount_percent" value="[[|discount_percent|]]" class="input_number"  maxlength="200" style="width:30px !important;text-align:right;margin-right:5px;" onkeyup="if(to_numeric(this.value)<=100){GetTotalPayment();}else{this.value=((this.value).substr(0,((this.value).length-1)));}" /><input name="discount" type="text" id="discount" value="[[|discount|]]" class="input_number"  maxlength="200" style="width:125px !important;text-align:right;" onkeyup="this.value=number_format(this.value);GetTotalPayment();" onblur="GetTotalPayment();" placeholder="[[.discount_cash.]]" /></td>
                    </tr>
                     <tr>
                        <td width="1%" nowrap="nowrap">[[.total_amount.]]:</td>
                        <td><input name="total_payment" type="text" id="total_payment" value="[[|total_payment|]]" readonly="readonly" class="general"  maxlength="200">&nbsp; &nbsp;<label><?php echo HOTEL_CURRENCY;?></label></td>
                        <td width="1%" nowrap="nowrap">[[.deposit.]]:</td>
                        <td><input name="deposit" type="text" id="deposit" value="[[|deposit|]]" onkeyup="this.value=number_format(this.value); GetTotalPayment();" class="input_number"  maxlength="200" style="width:80px !important;text-align:right; border:none" readonly="readonly" />&nbsp; &nbsp;<label><?php echo HOTEL_CURRENCY;?></label></td>
                    </tr>
                     <tr style="display:none;">
                        <td>[[.deposit_date.]]:</td>
                        <td><input name="deposit_date" type="text" id="deposit_date" value="[[|deposit_date|]]" class="general"  maxlength="200"/>&nbsp; &nbsp;</td>
                    </tr>
                     <tr>
                        <td width="1%" nowrap="nowrap">[[.remain.]]:</td>
                        <td><input name="remain" type="text" id="remain" value="[[|remain|]]" class="general" readonly="readonly"  maxlength="200" style="font-weight:bold;"/>&nbsp; &nbsp;<label><?php echo HOTEL_CURRENCY;?></label></td>
                    </tr>
                </table>
                </fieldset>
                    <div id="button_total">
                        <input name="button_ok" type="button" id="button_ok" value="[[.ok.]]" onClick="HideDialog('bound_summary');" style="height:35px; width:80px;float:right;">
                        <input name="button_reset" type="button" id="button_reset" value="[[.reset.]]" onClick="jQuery('#receiver_name').val('');jQuery('#discount_percent').val('');jQuery('#deposit').val(0);jQuery('#deposit_date').val('');HideDialog('bound_summary');" style="height:35px; width:80px;float:right;" >              
                    </div>
                </div>
                </div>
    <div id="div_bound_note" style="display:none;"><div id="bound_note"><div class="info"><span class="title_bound">[[.note_product.]]</span><img width="20" height="20" id="btnClose" src="packages/core/skins/default/images/buttons/delete.gif" onclick="submitNote(jQuery('#note_product').val(),jQuery('#id_note').val());HideDialog('div_bound_note');" style="float:right;"/></div>
    <input name="id_note" type="hidden" id="id_note" value=""  />
    <textarea id="note_product" cols="82" rows="3"></textarea>
    </div>
    </div>
    <div id="dialog" class="web-dialog"><div class="info"><span style="text-align:center; font-size:14px; color:#6D84B4;line-height:35px;" id="title_select_quantity"><b>[[.edit_quantity_for_menu.]]</b></span>
    <img width="30" height="25" id="btnClose" src="packages/core/skins/default/images/buttons/delete.gif" onclick="HideDialog('dialog');" style="float:right;"/></div>
        <table style="margin-left:10px;">
            <tr>
                <td>[[.name.]]:&nbsp;<input  name="name" type="text" border="0" id="name" readonly="readonly" style="width:300px;background:#fff;margin-left:10px;"></td>
                <td></td>
            </tr>
            <tr id="tr_note_outside" style="display:none;">
                <td>[[.unit.]]:&nbsp;<input name="unit_abc" type="text" id="unit_abc" value="" style="width:285px;" />
                <input  name="unit_id" id="unit_id" value="" size="10" class="text-right" style="display:none;"> </td>
               <td></td>
            </tr>
            <tr><td>[[.note.]]:&nbsp;<input  name="note-dialog" type="text" border="0" id="note-dialog" style="width:285px;background:#fff;" value="" /></td>
                <td></td>
            </tr>
        </table>
        <input name="pass" type="text" id="pass" value="1" style="display:none;"/>
        <input name="prd_key" type="text" id="prd_key" value=""  style="display:none;"/>
            <table id="select_item" align="center" style="margin-left:10px;">
                <tr><td valign="top">
                        <table id="caculator" cellpadding="2" cellspacing="0">
                            <tr><td><input name="name_1" type="button" value="1" class="button" onclick="GetNumber(this.value,jQuery('#prd_key').val(),jQuery('#pass').val());"></td>
                                <td><input name="name_2" type="button" value="2" class="button" onclick="GetNumber(this.value,jQuery('#prd_key').val(),jQuery('#pass').val());"></td>
                                <td><input name="name_3" type="button" value="3" class="button" onclick="GetNumber(this.value,jQuery('#prd_key').val(),jQuery('#pass').val());"></td>
                            </tr>
                            <tr><td><input  name="name_4" type="button" value="4" class="button" onclick="GetNumber(this.value,jQuery('#prd_key').val(),jQuery('#pass').val());"></td>
                                <td><input name="name_5" type="button" value="5" class="button" onclick="GetNumber(this.value,jQuery('#prd_key').val(),jQuery('#pass').val());"></td>
                                <td><input name="name_6" type="button" value="6" class="button" onclick="GetNumber(this.value,jQuery('#prd_key').val(),jQuery('#pass').val());"></td>
                            </tr>
                            <tr><td><input name="name_7" type="button" value="7" class="button" onclick="GetNumber(this.value,jQuery('#prd_key').val(),jQuery('#pass').val());"></td>
                                <td><input name="name_8" type="button" value="8" class="button" onclick="GetNumber(this.value,jQuery('#prd_key').val(),jQuery('#pass').val());"></td>
                                <td><input name="name_9" type="button" value="9" class="button" onclick="GetNumber(this.value,jQuery('#prd_key').val(),jQuery('#pass').val());"></td>
                            </tr>
                            <tr>
                                <td><input name="." type="button" value="." class="button" onclick="GetNumber(this.value,jQuery('#prd_key').val(),jQuery('#pass').val());"></td>
                                <td><input name="name_0" type="button" value="0" class="button" onclick="GetNumber(this.value,jQuery('#prd_key').val(),jQuery('#pass').val());"></td>
                                <td><input name="backspace" type="button" value="<-" class="button" onclick="GetNumber(this.value,jQuery('#prd_key').val(),jQuery('#pass').val());"></td>
                            </tr>
                  </table>
                    </td>    
                    <td valign="top">   
                        <table style="margin-top:20px;">
                            
                            <tr class="can_admin" style="display:none;">
                                <td><input  name="radio_number" type="radio" id="radio_price" value="radio_price" checked="checked" class="radio_number" lang=""/><label for="radio_price">[[.price.]]</label>:</td>
                                <td><input  name="price" type="text" id="input_radio_price" onclick="$('radio_price').checked=true;" onKeyUp="jQuery('#input_radio_price').FormatNumber();update_amount(true,jQuery('#prd_key').val());GetTotalPayment();" onblur="update_amount(true,jQuery('#prd_key').val());GetTotalPayment()" style="width:100px;text-align:right;" border="0" class="input_number" /></td>
                            </tr>
                            <tr>
                                <td><input  name="radio_number" type="radio" id="radio_quantity" value="radio_quantity" checked="checked" class="radio_number" lang=""/><label for="radio_quantity">[[.quantity.]]</label>:</td>
                                <td><input  name="quantity" type="text" id="input_radio_quantity" onclick="$('radio_quantity').checked=true;" onkeyup="jQuery('#input_radio_quantity').FormatNumber();update_amount(true,jQuery('#prd_key').val());GetTotalPayment();" onblur="update_amount(true,jQuery('#prd_key').val());GetTotalPayment();" value="1" class="input_number" style="width:100px;text-align:right;"  /></td>
                            </tr>
                            <tr class="can_admin" style="display:none;">
                                <td>[[.total_amount.]]:</td>
                                <td><input  name="amount" type="text" id="amount" border="0" style="width:100px;background:#fff;" class="text-right readonly" value="" readonly="readonly"  /></td>
                            </tr>
                            <tr id="tr_promotion">
                                <td><input  name="radio_number" type="radio" id="radio_promotion" value="radio_promotion" class="radio_number" lang="quantity"/><label for="radio_promotion">[[.promotion.]]</label>:</td>
                                <td> <input name="promotion_quantity" type="text" id="input_radio_promotion" onclick="$('radio_promotion').checked=true;" class="text-right" style="width:100px;" /></td>
                            </tr>
                            <tr id="tr_return">
                                <td><input  name="radio_number" type="radio" id="radio_return" value="radio_return" class="radio_number"  lang="quantity"/><label for="radio_return">[[.return.]]</label></td>
                                <td> <input  name="return" type="text" id="input_radio_return" onclick="$('radio_return').checked=true;" value="" style="width:100px;" class="text-right" /></td>
                            </tr>
                            <tr id="tr_cancel" style="display:none;">
                                <td><input  name="radio_number" type="radio" id="radio_cancel" value="radio_cancel" class="radio_number" lang="quantity"/><label for="radio_cancel">[[.cancel.]]</label>:</td>
                                <td><input  name="quantity_cancel" type="text" id="input_radio_cancel" border="0" style="width:100px;" onclick="$('radio_cancel').checked=true;" class="text-right" ></td>
                            </tr>                        
                            <tr id="tr_percentage">
                                <td><input  name="radio_number" type="radio" id="radio_percentage" value="radio_percentage" class="radio_number" lang="percentage"><label for="radio_percentage">[[.discount.]](%)</label>:</td>
                                <td><input  name="percentage" type="text" id="input_radio_percentage" onclick="$('radio_percentage').checked=true;" class="text-right" style="width:70px;" title="Discount for product" value="" />
                                <input name="discount_category" type="text" id="discount_category" value="0" readonly="readonly" style="width:25px;" class="text-right" title="Discount by category" /></td>
                            </tr>
                            <tr style="height:50px;" id="tr_selected">
                            </tr>
                        </table>
                    </td>
               </tr>
        </table>
      </div>
    <div id="view_summary"></div>
    <div id="view_r_room"></div>
    <div id="view_paymen"></div>
    <div id="view_keyboard"></div>
    <div id="dialog_number"></div>
    <div id="table_map" style="display:none;">
        <div class="info"><span class="title_bound">[[.select_table.]]</span><img width="30" height="25" id="btnClose" src="packages/core/skins/default/images/buttons/delete.gif" onclick="HideDialog('table_map');" style="float:right;"/></div>
        <table border="0" cellpadding="2" cellspacing="0" width="100%" bordercolor="#CCCCCC" rules="rows" frame="hsides" id="table_table_map">
            <!--LIST:floors-->        
            <tr>
                <td width="80px" nowrap valign="top"><b>[[|floors.name|]]</b></td>
                <td width="99%"> 
                    <input name="table_change" type="hidden" id="table_change" />  
                    <!--LIST:floors.bar_tables-->
                    <div class="room-bound" style="width:58px; height:60px;">
                    <input name="table_change_num_people" type="hidden" id="table_change_num_people" value="[[|floors.bar_tables.num_people|]]" />  
                     <a href="#" style="width:50px;height:50px;" <?php if([[=floors.bar_tables.status=]]=='AVAILABLE' || ([[=floors.bar_tables.status=]]=='OCCUPIED' && [[=status=]]=='CHECKIN') || ([[=floors.bar_tables.status=]]=='BOOKED' && ([[=status=]]=='RESERVATION' || [[=status=]]=='BOOKED'))){ echo 'style="width:50px;height:50px;"'; echo 'onclick="jQuery(\'#table_id_\'+jQuery(\'#table_change\').val()).val(this.id);jQuery(\'#table_name_\'+jQuery(\'#table_change\').val()).val(this.lang);jQuery(\'#num_people_\'+jQuery(\'#table_change\').val()).val(jQuery(\'#table_change_num_people\').val());HideDialog(\'table_map\');"';}else{ echo 'style="width:50px; height:50px;cursor:default;"';}?> class="room [[|floors.bar_tables.class|]]" lang="[[|floors.bar_tables.name|]]" id="[[|floors.bar_tables.id|]]"  >
                        <span style="font-size:9px;font-weight:bold;color:#039" >[[|floors.bar_tables.name|]]</span><br />
                    </a>
                    <div class="extra-info-bound" style="display:;">
                    <!--LIST:floors.bar_tables.status_tables_others-->
                    <a target="_blank" title="[[.code.]]: [[|floors.bar_tables.status_tables_others.id|]]" href="#"></a>
                    <!--/LIST:floors.bar_tables.status_tables_others-->
                    </div>
                    </div>
                <!--/LIST:floors.bar_tables-->
                </td>
            </tr>        
            <!--/LIST:floors-->    
        </table>
    </div>
    <div id="div_select_bar" style="display:none;">
        <div class="info"><span class="title_bound">[[.select_bar.]]</span><img width="30" height="25" id="btnClose" src="packages/core/skins/default/images/buttons/delete.gif" onclick="HideDialog('div_select_bar');" style="float:right;"/></div>
        <input  name="bar_id_other" type="hidden" value="<?php echo Session::get('bar_id');?>" id="bar_id_other" />
        <table border="0" cellpadding="2" cellspacing="0" width="350" bordercolor="#CCCCCC" id="bar_select">
            <?php $i=0;?>
            <!--LIST:bars-->
            <?php if($i%2==0){
                echo '<tr>';
                echo '<td><input name="'.[[=bars.name=]].'" type="button" id="'.[[=bars.name=]].'" value="'.[[=bars.name=]].'" lang="'.[[=bars.bar_id_from=]].'" onclick="jQuery(\'#bar_id_other\').val(this.lang);jQuery(\'#div_select_bar\').css(\'display\',\'none\');jQuery(\'#bar_name\').val(this.name);searchProduct(\'bar\',jQuery(\'#bar_id_other\').val());" style="width:125px; height:30px;" /></td>';    
            }else{
                echo '<td><input name="'.[[=bars.name=]].'" type="button" id="'.[[=bars.name=]].'" value="'.[[=bars.name=]].'" lang="'.[[=bars.bar_id_from=]].'" onclick="jQuery(\'#bar_id_other\').val(this.lang);jQuery(\'#div_select_bar\').css(\'display\',\'none\');jQuery(\'#bar_name\').val(this.name);searchProduct(\'bar\',jQuery(\'#bar_id_other\').val());"  style="width:125px; height:30px;"/></td>';        
                echo '</tr>';
            } $i++;?>
            <!--/LIST:bars-->
        </table> 
    </div>

</div>
</form>
</div>
<script>
    var order_id = '<?php echo (Url::get('id')?Url::get('id'):'')?>';
    var can_delete = '<?php echo User::can_delete(false,ANY_CATEGORY)?>';
    var can_add = '<?php echo User::can_add(false,ANY_CATEGORY)?>';
    var can_edit = '<?php echo User::can_edit(false,ANY_CATEGORY)?>';
    var can_admin_bar = '<?php echo User::can_admin(false,ANY_CATEGORY)?>';
    var count = 0;
    units=[[|units|]];
    var check_selected = 0;
    bar_id = <?php echo (Session::get('bar_id')?Session::get('bar_id'):'0');?>;
    paging(25);
    SetCss();
    GetStatus();
    product_list = [[|product_list_js|]];
    table_list = [[|table_list_js|]];
    var flag2 = 0;
    var flag = 0;
    var full_rate = to_numeric(jQuery('#input_full_rate').val());
    var full_charge = to_numeric(jQuery('#input_full_charge').val());
    for(var j in product_list)
    {
        if(product_list[j]['product_id'] == 'FOUTSIDE' || product_list[j]['product_id'] == 'DOUTSIDE' || product_list[j]['product_id'] == 'SOUTSIDE'){
            if(count<product_list[j]['product_sign']){
                count=product_list[j]['product_sign'];
            }
        }
        drawItems(product_list[j]);    
    }
    for(var p in table_list){
        drawTableList(table_list[p]);    
    }
    GetTotalPayment();
    jQuery('#deposit_date').datepicker();
    jQuery("#arrival_date").datepicker({minDate:0});
    jQuery(document).click(function() {
        if(jQuery('#select_number').css('display')=='block'){
            var box = jQuery('#select_number');
            box.hide();
        }
        if(jQuery('#select_outside').css('display')=='block'){
            var box = jQuery('#select_outside');
            box.hide();
        }
    });
    jQuery(document).ready(function(){
        jQuery('#testRibbon').css('display','none');
        jQuery(".jcarousel-clip-horizontal").width(jQuery('.full_screen').width()-100);
        if(jQuery('#bound_product_list').css('display')=='none'){
            jQuery('#bound_product_list').css('display','block');
        }
        jQuery('#button_sum').css('display','block');
        jQuery('.menu_extra_li, .menu_extra_li_extra').click(function(){
            var can_action = 0;
            jQuery('.ids_selected').each(function(){
                var id_selected=jQuery(this).attr('lang');
                if(jQuery(this).attr('checked')== true || jQuery(this).attr('checked')== 'checked'){
                    can_action = 1;    
                }
            });
            if(jQuery(this).attr('lang')=='delete' && jQuery('#number_selected').val()==1){// TH SL mon deu=1, ko hien thi ma tru luon
            }else{
                if(jQuery(this).attr('id')=='li_discount' || can_action){
                    var box = jQuery('#select_number');
                    box.show(); return false;
                }
            }
        }); 
        jQuery('#li_extra').click(function(){
            var box = jQuery('#select_outside');
            box.show(); return false;
        });
        SetCss();    
        category_action();        
    });
    function category_action(){
        jQuery(".category-list-item,.category-list-item-parent").each(function(){
            jQuery(this).click(function(){        
                var bar_id = jQuery('#bar_id_other').val();
                jQuery.ajax({
                    url:"form.php?block_id=<?php echo Module::block_id();?>&bar_id=<?php echo Session::get('bar_id');?>",
                    type:"POST",
                    data:{category_id:jQuery(this).attr('lang'),bar_id_other:bar_id,act:'product',cmd:'draw_products',type:'CATEGORY'},
                    success:function(html){                    
                        if(jQuery('#alert').html() == null){
                            flag = 1;
                            jQuery(".body").html(html);
                        }else{
                            jQuery("#post_show").css('display','block');
                        }
                        
                    }
                });
            })
        });    
    }
    function GetStatus(){
        var status = '[[|status|]]';    
        if(status == 'CHECKIN'){
            jQuery('#checkin').css("display","none");
            jQuery('#save').css('display','block');
            jQuery('#booked').attr("name","checkout");
            jQuery('#booked').addClass("button-medium");
            jQuery('#booked').val('[[.PRINT_&_CHECKOUT.]]');
        }else if(status == 'BOOKED' || status == 'RESERVATION'){
            jQuery('#dynamic_save_button_bound').html('<input name="cancel" type="submit" value="CANCEL" class="button-bar-delete" id="cancel" >');
            jQuery('#booked').css('display','none');
            jQuery('#save').css('display','block');
        }else if(status == 'CHECKOUT'){
            jQuery('#checkin').css("display","none");
            jQuery('#save').css('display','block');
            jQuery('#booked').css('display','none');    
        }else if(status == ''){
            //jQuery('#guest_info').css("display","none");    
            //jQuery('#summary').css("display","none");    
        }else if(status == 'CANCEL'){
            jQuery('#dynamic_save_button_bound').html('');
            jQuery('#booked').css('display','none');
            jQuery('#save').css('display','none');
            jQuery('#save_stay').css('display','none');
            jQuery('#checkin').css('display','none');
        }
    }
    jQuery("#expan").click(function(){
        flag = 1;
        ShowExpan();
    });
    jQuery("#save,#save_stay").click(function(){
        var status = '[[|status|]]';
        if(status == 'CHECKOUT'){
            jQuery("#act").val('save');
            jQuery("#password_box").show();
            return false;
        }
    });
    jQuery("#cancel").click(function(){
        var status = '[[|status|]]';
        if(status == 'BOOKED' || status == 'RESERVATION'){
            if(confirm('[[.are_you_sure.]]')){
                return true;    
            }else{
                return false;    
            }
        }
    });
    if(to_numeric(jQuery('#input_pmt_ROOM').val())>0){
        jQuery('#guest_room_info').css('display','block');    
    }
    jQuery('#icon_keyboard').click(function() {
        jQuery("#dialog_keyboard").fadeIn(300);
        jQuery('#dialog_keyboard').css('display','block');
        jQuery('#text_keyboard').val(jQuery('#input_product_name').val());
    });
    //jQuery(document).click(function(){
       
    //});
    jQuery("#back").click(function(){
        flag = 0;
        jQuery("#back").css('display','none');
        jQuery("#expan").css('display','block');
        jQuery("#mask").css('display','none');
        jQuery("#product_select_expansion").removeClass('css_product_select_expansion');    
        jQuery("#foot_drink_bound").removeClass('css_foot_drink_bound');
        jQuery("#product_select_expansion,#post_show").removeAttr('width');
        jQuery("#product_select_expansion,#post_show").width(0);
        var width = jQuery('#table_bound').width();
        jQuery("#product_select_expansion").width(width*0.62);
        jQuery("#post_show").width(width*0.62);
        //jQuery(".body").css('height','200px');
        jQuery(".body").width((width*0.62 - 15));
        jQuery("#post_show").css('display','none');
        jQuery("#search_product").css('display','block');
        jQuery('#info_summary').css('display','block');
        jQuery("#show_food_drink").html('');        
    });
    
    jQuery('#dialog_keyboard').css('display','none');
    jQuery('#mycarousel').jcarousel({
        visible: 9,
        wrap: 'last'
    });
    jQuery('#ul_food_category').jcarousel({
        visible: 7,
        scroll: 6,
        wrap: 'last',
        vertical:true,
        animation:'fast'
    });
    
    <?php if(Url::get('act')=='payment'){?>
    jQuery('#act').val(0);
     openWindowUrl('form.php?block_id=<?php echo BLOCK_PAYMENT;?>&cmd=payment&id=<?php echo Url::get('id');?>&type=BAR&total_amount='+to_numeric(jQuery('#total_payment').val())+'&portal_id=<?php echo PORTAL_ID;?>',Array('payment','payment_for','80','210','950','500'));
    <?php
        }?>
    function searchProduct(act,bar_id)
    {
        if(jQuery('#input_product_name').val()=='' && act=='product'){
            //alert('[[.you_have_to_input_at_least_2_letters.]]');
            //alert('[[.insert_text_to_search.]]');
            //return false;
        }else{
            
            jQuery('.body').html('');
            get_bar_id = <?php echo Url::get('bar_id');?>;
            if(bar_id == ''){
                bar_id = jQuery('#bar_id_other').val();    
            }
            //console.log(<?php echo Module::block_id();?>);
            jQuery.ajax({
                url:"form.php?block_id=<?php echo Module::block_id();?>&client=1",
                type:"POST",            
                data:{product_name:jQuery("#input_product_name").val(),act:act,bar_id_other:bar_id,bar_id:<?php echo Url::get('bar_id');?>,cmd:'draw_products',type:'PRODUCT'},
                success:function(html){
                    if(jQuery('#alert').html() == null || jQuery('#alert').html() == ''){
                        flag = 1;
                        jQuery(".body").html(html);
                       console.log(html);
                    }else{

                        jQuery("#post_show").css('display','block');    
                    }
                    jQuery('#ul_food_category').jcarousel({
                        visible: 7,
                        scroll: 6,
                        wrap: 'last',
                        vertical:true,
                        animation:'fast'
                    });
                    jQuery('#mycarousel').jcarousel({
                        visible: 9,
                        wrap: 'last'
                    });
                    category_action();
                }
            });
        }
    }
    function SelectedItems(key,pass)
    {
        var categories_discount = [[|categories_discount_js|]];
        var discount_pct = 0;
        price_id = key;
        if((pass==1 || pass==2) && $('product_id_'+key) && ($('product_id_'+key).value == 'SOUTSIDE' || $('product_id_'+key).value == 'FOUTSIDE' || $('product_id_'+key).value == 'DOUTSIDE')){
            price_id = key.substr(0,key.lastIndexOf('_'));
        }
        for(var j in product_array)
        {
            if((product_array[j]['id'] == price_id) || (product_array[j]['product_id'] == price_id && ( price_id=='FOUTSIDE' || price_id=='DOUTSIDE' || price_id=='SOUTSIDE')))
            {
                price_id = product_array[j]['id'];
                //key = product_array[j]['id'];
                var net_price = to_numeric(product_array[j]['price']);
                for(var k in categories_discount)
                {
                    if(product_array[j]['category_id'] == categories_discount[k]['id'] && categories_discount[k]['discount_percent']>0)
                    {
                        discount_pct = categories_discount[k]['discount_percent'];
                        if(!jQuery('#discount_'+categories_discount[k]['id']).html())
                        {
                            var tt = '<table width="200" id="discount_'+categories_discount[k]['id']+'"><tr><td width="120">'+categories_discount[k]['name']+'</td><td width="80">'+categories_discount[k]['discount_percent']+'</td></tr></table>';
                            jQuery('#div_discount_percent').append(tt);
                        }
                        jQuery('#div_discount_percent').css('display','block');
                    }
                }
                if(jQuery('#service_charge').val()=='')
                {
                    var service_rate = 0;
                }
                else
                {
                    var service_rate = to_numeric(jQuery('#service_charge').val());    
                }
                if(jQuery('#tax_rate').val()=='')
                {
                    var tax_rate = 0;
                }
                else
                {
                    var tax_rate = to_numeric(jQuery('#tax_rate').val());    
                }
                net_price = to_numeric(product_array[j]['price']);
                price = to_numeric(product_array[j]['price']);
                if(jQuery('#bar_id_other').val() != bar_id)
                {
                    var add_charge = to_numeric(jQuery('#add_charge').html());
                    net_price =net_price + (net_price*add_charge*0.01);
                    price =price + (price*add_charge*0.01);
                }
                //KID THEM  || product_array[j]['product_id'] == 'SOUTSIDE' VAO DIEU KIEN DE NHAP DUOC DICH VU NGOAI
                if((pass == 1 || pass == 2 || (product_array[j]['product_id'] == 'DOUTSIDE' || product_array[j]['product_id'] == 'FOUTSIDE' || product_array[j]['product_id'] == 'SOUTSIDE')) && key!='DOUTSIDE' && key!='FOUTSIDE' && key!='SOUTSIDE')
                {
                    //alert('tren');
                        if(pass==1)
                        {
                            jQuery('#title_select_quantity').html('[[.edit_quantity_for_menu.]]');
                        }
                        else
                        {
                            jQuery('#title_select_quantity').html('[[.add_quantity_for_menu.]]');
                        }
                        <?php if(User::can_admin(false,ANY_CATEGORY) || RES_EDIT_PRODUCT_PRICE){?>
                            jQuery('.can_admin').css('display','');
                        <?php } ?>
                        jQuery('#pass').val(pass);
                        jQuery('#prd_key').val(key);
                        if(jQuery('#name_'+key).val() != undefined){
                            jQuery('#name').val(jQuery('#name_'+key).val());
                        }else jQuery('#name').val(product_array[j]['name']);
                        if(jQuery('#note_'+key).val() != undefined){
                            jQuery('#note-dialog').val(jQuery('#note_'+key).val());
                        }else jQuery('#name').val(product_array[j]['note']);                        
                        /*if(jQuery('#price_'+key).val() != undefined){
                            jQuery('#price').val(jQuery('#price_'+key).val());
                        }else jQuery('#price').val(price);*/
                        if(jQuery('#amount_'+key).val() != undefined && pass == 1){
                            jQuery('#amount').val(number_format(jQuery('#amount_'+key).val()));
                        }else{jQuery('#amount').val(number_format(net_price));}
                        if(jQuery('#unit_'+key).val() != undefined){
                            jQuery('#unit_abc').val(jQuery('#unit_'+key).val());
                        }else if(product_array[j]['unit'] != ''){
                            jQuery('#unit_abc').val(product_array[j]['unit']);
                        }
                        if(jQuery('#unit_id_'+key).val() != undefined){
                            jQuery('#unit_id').val(jQuery('#unit_id_'+key).val());
                        }else{
                            jQuery('#unit_id').val(product_array[j]['unit_id']);
                        }
                        if(jQuery('#note_'+key).val() != undefined){
                            jQuery('#note-dialog').val(jQuery('#note_'+key).val());
                        }
                        if(jQuery('#price_'+key).val() != undefined && pass==1){
                            jQuery('#input_radio_price').val(number_format(to_numeric(jQuery('#price_'+key).val())));
                        }else{jQuery('#input_radio_price').val(number_format(price));    }
                        jQuery('#input_radio_price').ForceNumericOnly().FormatNumber();
                                        
                        if(jQuery('#quantity_'+key).val() != undefined && pass==1){
                            jQuery('#input_radio_quantity').val(to_numeric(jQuery('#quantity_'+key).val()));
                        }else{jQuery('#input_radio_quantity').val('');    }
                        
                        if(jQuery('#promotion_'+key).val() != undefined && pass==1)
                        {
                            jQuery('#input_radio_promotion').val(jQuery('#promotion_'+key).val());
                        }
                        else{ jQuery('#input_radio_promotion').val(''); }

                        if(jQuery('#remain_'+key).val() != undefined && pass==1){
                            jQuery('#input_radio_return').val(jQuery('#remain_'+key).val()-jQuery('#remain_'+key).val());
                        }else{ jQuery('#input_radio_return').val(''); }
                        
                        if(jQuery('#quantity_cancel_'+key).val() != undefined && pass==1){
                            jQuery('#input_radio_cancel').val(jQuery('#quantity_cancel_'+key).val()-jQuery('#quantity_cancel_'+key).val());
                        }else{ jQuery('#input_radio_cancel').val(''); }
                        
                        if(jQuery('#percentage_'+key).val() != undefined && pass==1){
                            jQuery('#input_radio_percentage').val(jQuery('#percentage_'+key).val());
                        }else{ jQuery('#input_radio_percentage').val(''); }
                        
                        if(product_array[j]['type']!='DRINK' && product_array[j]['type'] != 'PRODUCT')
                        {
                            //jQuery('#tr_cancel').css('display','none');
                        }
                        else
                        {
                            //jQuery('#tr_cancel').css('display','');
                        }    
                        jQuery('#discount_category').val(discount_pct);
                        if(pass==1)
                        {
                            jQuery('#tr_selected').html('<td></td><td><input  name="selected" type="button" value="OK" id="selected" onclick="NumberSelected(\''+key+'\',\'edit\',\''+product_array[j]['product_id']+'\'); HideDialog(\'dialog\');" class="selection" /><input  name="cancel" type="button" value="Cancel" id="cancel" onclick="HideDialog(\'dialog\');" class="selection"/></td>');
                        }
                        else
                        {
                            jQuery('#tr_selected').html('<td></td><td><input  name="selected" type="button" value="OK" id="selected" onclick="NumberSelected(\''+key+'\',\'add\',\''+product_array[j]['product_id']+'\'); HideDialog(\'dialog\');" class="selection" /><input  name="cancel" type="button" value="Cancel" id="cancel" onclick="HideDialog(\'dialog\');" class="selection"/></td>');
                        }
                        jQuery("#dialog").fadeIn(300);
                        jQuery('#input_radio_return').ForceNumericOnly();
                        //jQuery('#input_radio_quantity').ForceNumericOnly();
                        jQuery('#input_radio_cancel').ForceNumericOnly();
                        jQuery('#input_radio_promotion').ForceNumericOnly();
                        jQuery('#input_radio_percentage').ForceNumericOnly();
                        if(product_array[j]['product_id'] == 'DOUTSIDE' || product_array[j]['product_id'] == 'FOUTSIDE' || product_array[j]['product_id'] == 'SOUTSIDE')
                        {
                            jQuery('#name').removeAttr('readonly');
                            jQuery('#input_radio_price').removeAttr('readonly');    
                            jQuery('#name,#input_radio_price').css('background','#FFF');
                            jQuery('#name').focus();
                            //jQuery('#tr_cancel').css('display','none');
                            jQuery('#tr_return').css('display','');
                            jQuery('#tr_promotion').css('display','');
                            jQuery('#tr_percentage').css('display','');
                            jQuery('#tr_note_outside').css('display','');
                        }
                        else
                        {
                            jQuery('#input_radio_price').removeAttr('readonly');
                            jQuery('#tr_note_outside').css('display','none');
                            jQuery('#name').attr('readonly','readonly');
                            <?php if(!User::can_admin(false,ANY_CATEGORY) && !RES_EDIT_PRODUCT_PRICE){?>
                            jQuery('#input_radio_price').attr('readonly','readonly');
                            <?php }?>
                            //jQuery('#tr_cancel').css('display','');
                            jQuery('#tr_return').css('display','');
                            jQuery('#tr_promotion').css('display','');
                            jQuery('#tr_percentage').css('display','');
                        }
                        
                }
                else
                {
                    count_product = 0;
                    existsCheck = false;
                    if(($("quantity_"+key) || product_array[j]['product_id'] == 'DOUTSIDE' || product_array[j]['product_id'] == 'SOUTSIDE' || product_array[j]['product_id'] == 'FOUTSIDE') && key!='DOUTSIDE' && key!='FOUTSIDE' && key!='SOUTSIDE'){
                        existsCheck = true;
                    }

                    if(existsCheck){
                        var quantity = to_numeric(jQuery("#quantity_"+key).val());
                        //var oldAmount = to_numeric(jQuery("#amount_"+key).val());
                        var quantity_cancel = to_numeric(jQuery("#quantity_cancel_"+key).val());
                        var remain = to_numeric(jQuery("#remain_"+key).val());
                        if((quantity_cancel)<quantity){
                            jQuery("#quantity_"+key).val(1+quantity);
                            jQuery("#discount_category_"+key).val(discount_pct);
                            //update_total_amount(key);
                        }else{
                            alert('[[.this_product_has_canceled.]]');    
                            return false;
                        }
                    }
                    else
                    {
                        count ++;
                        var arr_product={};  
                        net_price = net_price - net_price*discount_pct/100;
                        arr_product['price_id'] = product_array[j]['id'];
                        arr_product['id'] = product_array[j]['id'];
                        arr_product['brp_id'] = '';
                        arr_product['product_id'] = product_array[j]['product_id'];
                        arr_product['name'] = product_array[j]['name'];
                        arr_product['promotion'] = 0;
                        arr_product['percentage'] = 0;
                        arr_product['discount_category'] = discount_pct;
                        arr_product['unit'] = product_array[j]['unit'];
                        arr_product['unit_id'] = product_array[j]['unit_id'];
                        arr_product['code'] = product_array[j]['code'];
                        arr_product['price'] = price;
                        arr_product['quantity'] = 1;
                        arr_product['amount'] = number_format(net_price);
                        arr_product['remain'] = 0;
                        arr_product['printed'] = 0;
                        arr_product['note'] = '';arr_product['quantity_cancel'] = 0;
                        if($('bar_id_other') && jQuery('#bar_id_other').val() != ''){
                            arr_product['bar_id'] = jQuery('#bar_id_other').val();
                        }
                        if(key=='DOUTSIDE' || key=='FOUTSIDE' || key=='SOUTSIDE'){
                            arr_product['id'] = product_array[j]['id']+'_'+product_array[j]['product_id']+'_'+count;
                            arr_product['name'] = jQuery('#name_outside').val();
                            arr_product['price'] = jQuery('#price_outside').val();
                            arr_product['quantity'] = jQuery('#quantity_outside').val();    
                        }
                        drawItems(arr_product);
                        amount = net_price;
                        jQuery("#total_amount").val(number_format(to_numeric(jQuery("#total_amount").val()) + amount));
                        //jQuery('#loading-layer').fadeOut(200);
                    }
                    GetTotalPayment();
                }
                myAutocomplete1();
            }/*else if(price_id=='FOUTSIDE' || price_id=='DOUTSIDE' || price_id=='SOUTSIDE'){
                alert(2);
                if(price_id=='FOUTSIDE'){
                    var name = ' Đồ ăn ngoài thực đơn ';    
                }
                if(price_id=='DOUTSIDE'){
                    var name = ' Đồ uống ngoài thực đơn ';    
                }
                if(price_id=='SOUTSIDE'){
                    var name = ' Dịch vụ ngoài thực đơn ';    
                }
                alert('Bạn chưa cập nhật giá cho sản phẩm '+name);    
                return false;
            }*/
        }
    }
    function NumberSelected(key,act,product_code)
    {  
        flag2 = 0;
        if(jQuery('#name').val() == '' || jQuery('#input_radio_price').val()==0 || jQuery('#input_radio_price').val()=='')
        {
            alert('[[.invalid_name_or_price_or_quantity_product.]]');
            return false;    
        }
        else
        {
            var amount = document.TouchBarRestaurantForm.amount.value;
            if(key)
            {
                if((product_code=='DOUTSIDE' || product_code=='FOUTSIDE' || product_code=='SOUTSIDE') && act=='add')
                {
                    if($('quantity_'+key))
                    {
                        oldAmount = to_numeric(jQuery("#amount_"+key).val());                
                        oldPrice = to_numeric(jQuery("#price_"+key).val());
                        oldQuantity = to_numeric(jQuery("#quantity_"+key).val());    
                    }
                    else
                    {
                        oldPrice = 0; oldQuantity=0; oldAmount =0;
                    }
                }
                else
                {
                    oldAmount = to_numeric(jQuery("#amount_"+key).val());                
                    oldPrice = to_numeric(jQuery("#price_"+key).val());
                    oldQuantity = to_numeric(jQuery("#quantity_"+key).val());
                    oldPromotion = to_numeric(jQuery("#promotion_"+key).val());
                    oldCancel = to_numeric(jQuery("#quantity_cancel_"+key).val());
                    oldRemain = to_numeric(jQuery("#remain_"+key).val());
                }
                if(act=='edit')
                {
                    if($('unit_abc') && $('unit_id') && jQuery('#unit_abc').val()!='')
                    {
                        jQuery("#unit_"+key).val(jQuery('#unit_abc').val());
                        jQuery("#unit_id_"+key).val(jQuery('#unit_id').val());
                    }
                    //if(product_code=='DOUTSIDE' || product_code=='FOUTSIDE' || product_code=='SOUTSIDE')
//                    {
//                        jQuery('#name_'+key).val(jQuery('#name').val());
//                        submitNote(jQuery('#note-dialog').val(),key);
//                        jQuery("#price_"+key).val(jQuery('#input_radio_price').val());
//                        jQuery("#quantity_"+key).val(to_numeric(jQuery('#input_radio_quantity').val()));
//                        jQuery("#amount_"+key).val(number_format(to_numeric(jQuery('#input_radio_price').val())*(to_numeric(jQuery('#input_radio_quantity').val()))));        
//                        var newAmount = to_numeric(jQuery('#input_radio_price').val())*(to_numeric(jQuery('#input_radio_quantity').val()));    
//                        jQuery("#total_amount").val(number_format(to_numeric(jQuery("#total_amount").val()) + newAmount -oldAmount));
//                    }
//                    else
//                    {
                        newPromotion = to_numeric(jQuery('#input_radio_promotion').val());
                        newPercentage = to_numeric(jQuery('#input_radio_percentage').val());
                        newCancel = to_numeric(jQuery('#input_radio_cancel').val());
                        newPrice = to_numeric(jQuery('#input_radio_price').val());
                        newReturn = to_numeric(jQuery('#input_radio_return').val());
                        newQuantity = to_numeric(jQuery('#input_radio_quantity').val())-newReturn-newCancel;                        
                        submitNote(jQuery('#note-dialog').val(),key);
                        jQuery("#price_"+key).val(newPrice);                         
                        jQuery("#remain_"+key).val(oldRemain+newReturn); 
                        jQuery("#quantity_"+key).val(newQuantity);
                        jQuery("#promotion_"+key).val(newPromotion);
                        jQuery("#percentage_"+key).val(newPercentage);
                        jQuery("#quantity_cancel_"+key).val(oldCancel+newCancel);
                    //}
                    //update_total_amount(key);
                }
                else 
                if(act=='add')
                {
                    newQuantity = $('radio_quantity').checked?to_numeric(jQuery('#input_radio_quantity').val()):0;
                    newPromotion = $('radio_promotion').checked?to_numeric(jQuery('#input_radio_promotion').val()):0;
                    newPercentage = $('radio_percentage').checked?to_numeric(jQuery('#input_radio_percentage').val()):0;
                    newCancel = $('radio_cancel').checked?to_numeric(jQuery('#input_radio_cancel').val()):0;
                    newReturn = $('radio_return').checked?to_numeric(jQuery('#input_radio_return').val()):0;
                    if(product_code=='DOUTSIDE' || product_code=='FOUTSIDE' || product_code=='SOUTSIDE'){
                        if($('quantity_'+key))
                        {
                            submitNote(jQuery('#note-dialog').val(),key);
                            jQuery('#name_'+key).val(jQuery('#name').val());
                            jQuery("#price_"+key).val(jQuery('#input_radio_price').val());
                            jQuery("#quantity_"+key).val(oldQuantity + to_numeric(jQuery('#input_radio_quantity').val()));
                            jQuery("#amount_"+key).val(number_format(to_numeric(jQuery('#input_radio_price').val())*(oldQuantity + to_numeric(jQuery('#input_radio_quantity').val()))));
                            var newAmount = to_numeric(jQuery('#input_radio_price').val())*(oldQuantity + to_numeric(jQuery('#input_radio_quantity').val()));                
                            jQuery("#total_amount").val(number_format(to_numeric(jQuery("#total_amount").val()) + newAmount - oldAmount));
                        }
                        else
                        {
                            count ++;
                            var arr_product={};
                            if($('bar_id_other') && jQuery('#bar_id_other').val() != '')
                            {
                                arr_product['bar_id'] = jQuery('#bar_id_other').val();
                            }
                            else
                            {
                                 arr_product['bar_id'] = '<?php echo (Url::get('bar_id')?Url::get('bar_id'):'');?>';    
                            }
                            arr_product['price_id'] = key;
                            arr_product['id'] = key+'_'+product_code+'_'+count;
                            arr_product['product_id'] = product_code;
                            arr_product['name'] = jQuery('#name').val();
                            arr_product['promotion'] = 0;
                            arr_product['percentage'] = 0;
                            arr_product['discount_category'] = 0;
                            arr_product['unit'] = jQuery('#unit_abc').val();
                            arr_product['unit_id'] = jQuery('#unit_id').val();
                            arr_product['price'] = jQuery('#input_radio_price').val();
                            arr_product['quantity'] = jQuery('#input_radio_quantity').val();
                            arr_product['amount'] = (to_numeric(jQuery('#input_radio_price').val()) * to_numeric(jQuery('#input_radio_quantity').val()));
                            arr_product['remain'] = 0;
                            arr_product['printed'] = 0;
                            arr_product['note'] = jQuery('#note-dialog').val();
                            arr_product['quantity_cancel'] = 0;
                            arr_product['brp_id'] = '';
                            drawItems(arr_product);
                            jQuery("#total_amount").val(number_format(to_numeric(jQuery("#total_amount").val()) + arr_product['amount']));
                        }
                    }
                    else
                    {
                        submitNote(jQuery('#note-dialog').val(),key);
                        jQuery("#remain_"+key).val(newReturn + oldRemain); 
                        jQuery("#quantity_"+key).val(newQuantity + oldQuantity-newReturn);
                        jQuery("#promotion_"+key).val(newPromotion + oldPromotion);
                        jQuery("#percentage_"+key).val(newPercentage);
                        jQuery("#quantity_cancel_"+key).val(newCancel + oldCancel);
                        //update_total_amount(key);

                    }
                }
                if( product_code!='DOUTSIDE' && product_code!='FOUTSIDE' && product_code!='SOUTSIDE' && (newQuantity + oldQuantity)==(to_numeric(jQuery("#remain_"+key).val())+to_numeric(jQuery("#quantity_cancel_"+key).val())))
                {
                    jQuery("#table_"+key+" tr td").css('color','#D6D6D6');
                    jQuery("#table_"+key+" tr td input").css('color','#D6D6D6');
                    jQuery("#table_"+key+" tr td").css('cursor','default');
                    jQuery("#table_"+key+" tr td input").css('cursor','default');    
                }

            }
            //HideDialog('dialog');
            GetTotalPayment();
        }
    }
    function submitNote(valu,id){
        if(valu != ''){
            jQuery('#note_'+id).val(valu);
            jQuery('#note_pr_'+id).html('<img src="packages/hotel/skins/default/images/iosstyle/icon-note-1.png" title="'+valu+'" >');
        }
    }
//    function drawTableList(arr){ 
//        var can_delete = '<?php echo User::can_delete(false,ANY_CATEGORY)?>';
//        var items = '<table cellpadding="0" cellspacing=0 width="85%" id="table_list_'+arr['id']+'" class="selected-table-list" title="'+arr['name']+'" style="float:right;">';
//        items += '<tr id="item_table_'+arr['id']+'">'; //jQuery(target).attr('class')
//        items += '<td width=""><input name="table_list['+arr['id']+'][name]" type="text" value="'+arr['name']+'" id="table_name_'+arr['id']+'" style=" border:none; width:65px;" readonly="readonly" maxlength="200"></td>';
//        items += '<td width="" style="display:none;"><input name="table_list['+arr['id']+'][table_id]" type="text" value="'+arr['id']+'" id="table_id_'+arr['id']+'" style=" border:none; width:55px;" readonly="readonly" maxlength="200"></td>';
//        items += '<td width=""><input name="table_list['+arr['id']+'][num_people]" type="text" value="'+arr['num_people']+'" id="num_people_'+arr['id']+'" style=" border:none; width:45px;" onclick="jQuery(this).css(\'border\',\'1px solid #D6D6D6\');"  maxlength="200"></td>';
//        items += '<td width=""><input name="table_list['+arr['id']+'][order_person]" type="text" value="'+arr['order_person']+'" id="order_person_'+arr['id']+'" style=" border:none; width:80px; padding:0px;" onclick="jQuery(this).css(\'border\',\'1px solid #D6D6D6\');" maxlength="200"></td>';
//        items += '<td width="1%"><input name="select_table" type="button" id="select_table" value="[[.change_table.]]" style="height:40px; width:80px;" onclick="jQuery(\'#table_map\').css(\'display\',\'block\');jQuery(\'#table_change\').val(\''+arr['id']+'\');" /></td>';
//        if(can_delete){
//            items += '<td width="18" id="delete_'+arr['id']+'" align="center" title="delete" onclick="DeleteTable(\''+arr['id']+'\');"><img width="15" src="packages/core/skins/default/images/buttons/delete.gif"></td>';
//        }else{
//            items += '<td width="17" colspan="2"></td>';    
//        }
//        items += '</tr></table>';    
//        jQuery('#show_table').append(items);
//    }
    function drawTableList(arr){ 
        var can_delete = '<?php echo User::can_delete(false,ANY_CATEGORY)?>';
        var items = '<table cellpadding="0" cellspacing=0 width="75%" id="table_list_'+arr['id']+'" class="selected-table-list" title="'+arr['name']+'" style="float:right;">';
        items += '<tr id="item_table_'+arr['id']+'">'; //jQuery(target).attr('class')
        items += '<td width=""><input name="table_list['+arr['id']+'][name]" type="text" value="'+arr['name']+'" id="table_name_'+arr['id']+'" style=" border:none; width:55px;" readonly="readonly" maxlength="200"></td>';
        items += '<td width="" style="display:none;"><input name="table_list['+arr['id']+'][table_id]" type="text" value="'+arr['id']+'" id="table_id_'+arr['id']+'" style=" border:none; width:55px;" readonly="readonly" maxlength="200"></td>';
        items += '<td width=""><input name="split_table" type="button" value="[[.split_table_button.]]" id="split_table" style="height:30px; width:73px;" <?php if(Url::get('cmd')=='edit' and Url::get('id')){?>onClick="window.open(\'?page=split_table&from_code=<?php echo Url::get('id');?>&type=split\')"<?php } ?>></td>';
		//items += '<td width=""><input name="coupling_table" type="button" value="[[.coupling_table.]]" id="coupling_table" style="height:40px; width:73px;" <?php if(Url::get('cmd')=='edit' and Url::get('id')){?>onClick="window.open(\'?page=split_table&from_code=<?php echo Url::get('id');?>\')"<?php } ?>></td>';
		items += '<td width=""><a href="<?php if(Url::get('cmd')=='edit' and Url::get('id')){?>?page=split_table<?php } ?>"><input name="coupling_table" type="button" value="[[.coupling_table.]]" id="coupling_table" style="height:30px; width:73px;" /></a></td>'
        items += '<td width="1%"><input name="select_table" type="button" id="select_table" value="[[.change_table.]]" style="height:30px; width:73px;" onclick="jQuery(\'#table_map\').css(\'display\',\'block\');jQuery(\'#table_change\').val(\''+arr['id']+'\');" /></td>';
        items += '<td width="17" colspan="2"></td>';    
        items += '</tr></table>';    
        jQuery('#show_table').append(items);
    }
    //Kimtan:them text-transform: capitalize;
    function drawItems(arr){
        var items = '<table cellpadding="0" cellspacing=0 width="100%" id="table_'+arr['id']+'" class="selected-foot-and-drink-table" title="'+arr['note']+'" lang="'+arr['id']+'">';
        items += '<tr class="foot-drink-tr" id="item_detail_'+arr['id']+'" onclick="if((jQuery(\'#status\').val()==\'CHECKOUT\' && can_admin_bar) || jQuery(\'#status\').val()!=\'CHECKOUT\'){if(jQuery(\'#selected_'+arr['id']+'\').attr(\'checked\')==true || jQuery(\'#selected_'+arr['id']+'\').attr(\'checked\')==\'checked\'){jQuery(\'#selected_'+arr['id']+'\').attr(\'checked\',false);}else{jQuery(\'#selected_'+arr['id']+'\').attr(\'checked\',true); }CssSelected();}">';
        items += '<td width="130" class="foot-drink-td"><input name="product_list['+arr['id']+'][name]" type="text" value="'+arr['name']+'" id="name_'+arr['id']+'" style=" border:none; width:124px; text-transform: capitalize;" readonly="readonly" maxlength="200" ></td>';
        items += '<td width="25" class="foot-drink-td"><input name="product_list['+arr['id']+'][quantity]" type="text" value="'+number_format(arr['quantity'],2)+'" id="quantity_'+arr['id']+'" style=" border:none; width:20px;text-align:center;" readonly="readonly" maxlength="200"></td>';
        items += '<td width="25" style="display:none;"><input name="product_list['+arr['id']+'][promotion]" type="text" value="'+arr['promotion']+'" id="promotion_'+arr['id']+'"  style=" border:none; width:25px;" readonly="readonly" maxlength="200"></td>';
        items += '<td width="25" style="display:none;"><input name="product_list['+arr['id']+'][percentage]" type="text" value="'+arr['percentage']+'" id="percentage_'+arr['id']+'" style=" border:none; width:20px;" readonly="readonly" maxlength="200"></td>';
        items += '<td width="25" style="display:none;"><input name="product_list['+arr['id']+'][discount_category]" type="text" value="'+arr['discount_category']+'" id="discount_category_'+arr['id']+'" style=" border:none; width:20px;" readonly="readonly" maxlength="200"></td>';
        items += '<td width="65" style="display:none;" align="right"><input name="product_list['+arr['id']+'][price]" type="text" value="'+number_format(arr['price'])+'" id="price_'+arr['id']+'" style=" border:none; width:60px;text-align:right;" readonly="readonly" maxlength="200"></td>';
        items += '<td width="75" align="right"><input name="product_list['+arr['id']+'][amount]" type="text" value="'+number_format(arr['amount'])+'" id="amount_'+arr['id']+'" style=" border:none; width:70px;text-align:right;" readonly="readonly" maxlength="200"></td>';
        items += '<td width="20" align="center" style="display:none;"><input name="product_list['+arr['id']+'][printed]" type="text" value="'+arr['printed']+'" id="printed_'+arr['id']+'" style=" border:none; width:20px;" readonly="readonly" maxlength="200"></td>';
        if(can_edit)
        {
            items += '<td width="18" style="display:none;" id="add_'+arr['id']+'" class="foot-drink-td" align="center" title="edit_product" onclick="jQuery(\'#prd_key\').val(\''+arr['id']+'\');SelectedItems(\''+arr['id']+'\',1);"><img width="15" src="packages/hotel/skins/default/images/iosstyle/edit.png" style="cursor:pointer;"></td>';    
        }
        if(arr['note'] !=''){
            items += '<td width="20" id="note_pr_'+arr['id']+'" class="foot-drink-td keyboard" onclick="jQuery(\'#id_note\').val(\''+arr['id']+'\');get_keyboard(\'note_product\',\''+arr['id']+'\');getNote(\''+arr['id']+'\');" align="center"><img src="packages/hotel/skins/default/images/iosstyle/icon-note-1.png" class="keyboard" ></td>';
        }else{
            items += '<td width="18" id="note_pr_'+arr['id']+'" class="foot-drink-td keyboard" onclick="jQuery(\'#id_note\').val(\''+arr['id']+'\');get_keyboard(\'note_product\',\''+arr['id']+'\');getNote(\''+arr['id']+'\');" align="center"><img src="packages/hotel/skins/default/images/iosstyle/icon-note-no.png"  class="keyboard" ></td>';    
        }
        items += '<td align="center" style="display:none;"><input name="product_list['+arr['id']+'][note]" type="text" value="'+arr['note']+'" id="note_'+arr['id']+'" style=" border:none; width:25px;" readonly="readonly" maxlength="200"></td>';
        items += '<td align="center" style="display:none;"><input name="product_list['+arr['id']+'][unit]" type="text" value="'+arr['unit']+'" id="unit_'+arr['id']+'" style=" border:none; width:25px;" readonly="readonly" maxlength="200"></td>';
        items += '<td align="center" style="display:none;"><input name="product_list['+arr['id']+'][unit_id]" type="text" value="'+arr['unit_id']+'" id="unit_id_'+arr['id']+'" style=" border:none; width:25px;" readonly="readonly" maxlength="200"></td>';
        items += '<td align="center" style="display:none;"><input name="product_list['+arr['id']+'][price_id]" type="text" value="'+arr['price_id']+'" id="price_id_'+arr['id']+'" style=" border:none; width:25px;" readonly="readonly" maxlength="200"></td>';
        items += '<td align="center" style="display:none;"><input name="product_list['+arr['id']+'][remain]" type="text" value="'+arr['remain']+'" id="remain_'+arr['id']+'" style=" border:none; width:25px;" readonly="readonly" maxlength="200"></td>';
        items += '<td align="center" style="display:none;"><input name="product_list['+arr['id']+'][product_id]" type="text" value="'+arr['product_id']+'" id="product_id_'+arr['id']+'" style=" border:none; width:25px;" readonly="readonly" maxlength="200"></td>';
        items += '<td align="center" style="display:none;"><input name="product_list['+arr['id']+'][quantity_cancel]" type="text" value="'+arr['quantity_cancel']+'" id="quantity_cancel_'+arr['id']+'" style=" border:none; width:25px;" readonly="readonly" maxlength="200"></td>';    
        items += '<td align="center" style="display:none;"><input name="product_list['+arr['id']+'][brp_id]" type="text" value="'+arr['brp_id']+'" id="brp_id_'+arr['id']+'" style=" border:none; width:25px;" readonly="readonly" maxlength="200"></td>';    
        items += '<td align="center" style="display:none;"><input name="product_list['+arr['id']+'][bar_id]" type="text" value="'+arr['bar_id']+'" id="bar_id_'+arr['id']+'" style=" border:none; width:25px;" readonly="readonly" maxlength="200"></td>';    
        if(can_delete){
            items += '<td width="18" id="cancel_'+arr['id']+'" style="display:none;" align="center" title="cancel" onclick="CancelProduct(\''+arr['id']+'\');"><img width="15" src="packages/core/skins/default/images/buttons/cancel2.jpg"></td>';
            if(arr['brp_id'] >0){
                items += '<td width="18" align="center"></td>';
            }else{
                items += '<td width="18" id="delete_'+arr['id']+'" align="center" title="delete" onclick="DeleteProduct(\''+arr['id']+'\');"><img width="15" src="packages/core/skins/default/images/buttons/delete.gif" style="cursor:pointer;"></td>';
            }
        }else{
            items += '<td width="17"></td>';
        }
        items += '<td align="center" style="display:none;"><input name="product_list['+arr['id']+'][selected]" type="checkbox" value="1" id="selected_'+arr['id']+'" class="ids_selected" lang="'+arr['id']+'" readonly="readonly" maxlength="200"></td>';    
        items += '</tr></table>';
        //alert(items);    
        jQuery('#show_detail').append(items);
        if(to_numeric(arr['quantity'])==(to_numeric(arr['quantity_cancel']))){
            jQuery("#table_"+arr['id']+" tr td").css('color','#D6D6D6');
            jQuery("#table_"+arr['id']+" tr td input").css('color','#D6D6D6');    
            jQuery('#cancel_'+arr['id']).attr('onclick','');
            jQuery('#note_pr_'+arr['id']).attr('onclick','');
        }else{
            jQuery("#table_"+arr['id']+" tr td").css('cursor','pointer');
            jQuery("#table_"+arr['id']+" tr td input").css('cursor','pointer');
        }
    }
    
    function checkSubmit(obj){
        var id = obj.id;
        var name = obj.name;
        var status = '[[|status|]]';
        if(status != 'CHECKOUT'){
            if(id=='checkin'){
                /*var arrival_date = jQuery('#arrival_date').val();
                var time_in_hour = to_numeric(jQuery('#arrival_time_in_hour').val());
                var time_in_munite = to_numeric(jQuery('#arrival_time_in_munite').val());
                var date_in = '<?php //echo date("d/m/Y");?>';
                var time_in = <?php //echo( date("G",time())*3600 + date("i",time())*60);?>;
                if(arrival_date != date_in || (arrival_date == date_in && (time_in_hour*3600 + time_in_munite*60) > (time_in+300))){
                    alert('[[.greater_time_in_the_current_time.]]');    
                    return false;
                }else{*/
                    jQuery('#act').val('checkin');
                    TouchBarRestaurantForm.submit();
                //}
            }else{
                //var answ = confirm('[[.do_you_want_to_save.]]');
                //if(answ == true){
                    jQuery('#act').val(name);
                    if(id == 'save_stay'){
                        jQuery('#acction').val(1);    
                    }
                    TouchBarRestaurantForm.submit();
                /*}else{
                    jQuery('#act').val('');
                    return false;    
                }*/
            }
        }
    }
    function myAutocomplete1()
    {
        jQuery('#unit_abc').autocomplete({
            url:'r_get_units.php',
            minChars: 0,
            width: 280,
            matchContains: true,
            autoFill: false,
            formatItem: function(row, i, max) {
                return ' <span> ' + row.name + '</span>';
            },
            formatMatch: function(row, i, max) {
                return row.name;
            },
            formatResult: function(row) {
                return row.name;
            },            
            onItemSelect: function(item) {
                updateUnit();
            }
            
        });
    }
    function sentToPrint(act,id)
    {
        jQuery.ajax({
            url:"form.php?block_id=<?php echo Module::block_id();?>",
            type:"POST",
            data:{cmd:'print_kitchen',act:act,id:id,client:'1'},
            success:function(html){                    
                alert(html);
            }
        });
            
    }
   
</script> 

<script type="text/javascript">
//Drag and Drop script - 
//luu nguyen giap add

if  (document.getElementById){

(function(){

//Stop Opera selecting anything whilst dragging
if (window.opera){
document.write("<input type='hidden' id='Q' value=' '>");
}
var n = 500;
var dragok = false;
var y,x,d,dy,dx;

function move(e){
if (!e) e = window.event;
 if (dragok){
  d.style.left = dx + e.clientX - x + "px";
  d.style.top  = dy + e.clientY - y + "px";
  return false;
 }
}

function down(e)
{
    
    if (!e) e = window.event;
    var temp = (typeof e.target != "undefined")?e.target:e.srcElement;
    if (temp.tagName != "HTML"|"BODY" && temp.className != "dragclass")
    {
        temp = (typeof temp.parentNode != "undefined")?temp.parentNode:temp.parentElement;
    }
    if (temp.className == "dragclass"){
    if (window.opera){
        document.getElementById("Q").focus();
    }
 dragok = true;
 temp.style.zIndex = n++;
 d = temp;
 dx = parseInt(temp.style.left+500);
 dy = parseInt(temp.style.top+100);
 x = e.clientX;
 y = e.clientY;
 document.onmousemove = move;
 return false;
 }
}

function up(){
dragok = false;
document.onmousemove = null;
}

document.onmousedown = down;
document.onmouseup = up;

})();
}
function close_window_fun(){
    location.reload();
    jQuery(".window-container").fadeOut();
}
//End giap
</script>
<script>
    var total_traveller = to_numeric(jQuery("#total_payment_traveller").val());
    if(total_traveller>=1){
        var total = to_numeric(jQuery("#amount_mini").html());
        var remain = total_traveller-total;
        remain = number_format(remain);
        jQuery("#total_remain_traveller").html(remain);
    }
    
</script>