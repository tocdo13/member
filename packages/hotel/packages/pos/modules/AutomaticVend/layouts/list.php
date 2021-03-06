<?php //System::debug([[=product_list_js=]]);?>
<script>
    var product_array=[[|product_array|]];
</script>
<style>
#banner_url{display:none;}
input[type=text]{ 
	height:22px !important;
}
</style>
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
        </div>
        
        <div style="float:left;padding-left:10px;width:150px;display: none;" id="restaurant_other">
            <div align="right">[[.menu.]]: <input name="bar_name" type="text" id="bar_name" style="width:200px; border:none; font-size:14px; font-weight:bold;" readonly="readonly" /></div>
    	</div>
        
        <input name="acction" type="hidden" value="0" id="acction" />        
		<?php if(User::can_add(false,ANY_CATEGORY)){?>
        <?php if(Url::get('cmd')=='add'){?>
		<input name="checkin" type="button" value="[[.vend.]]" id="checkin" class="button-medium" onclick="checkSubmit(this);" style="min-width:100px;height:45px;text-align:left;color:#000000;margin-left:2px;margin-right:2px;float:right;text-decoration:none;text-indent:20px;cursor:pointer;"/>
        <?php } else {?>
        <input name="checkin" type="button" value="[[.save.]]" id="checkin" class="button-medium" onclick="checkSubmit(this);" style="min-width:100px;height:45px;text-align:left;color:#000000;margin-left:2px;margin-right:2px;float:right;text-decoration:none;text-indent:20px;cursor:pointer;"/>
        <?php }?>
        <input name="back" type="button" value="[[.back.]]" class="button-medium-back" onclick="window.location='<?php echo Url::build('vending_reservation',array());?>'" />
        <input name="act" type="hidden" id="act" value="" />
		<div id="dynamic_save_button_bound"></div>
		<?php }?>
	</div>
    </div>
	<table cellpadding="2" width="100%" border="1" bordercolor="#A6A6A6" id="table_bound">
		<tr>
        	<td class="info-left" id="hidden_expan" style="background:#F5F3F3;">	
				<div id="product_select_expansion">
                    <!--Thanh search-->
                    <div class="select-menu-bound">
                    	<a name="top" ></a>
						
                        <div class="title"><span style="float:left; line-height:30px;">[[.search.]]:</span>
                            <table id="search_div" width="250px;">
                            	<tr>
                                	<td width="70%"><input name="input_product_name" type="text" id="input_product_name" onkeypress="if(event.keyCode==13){searchProduct('product',jQuery('#bar_id_other').val());return false;}" onkeyup="if(event.keyCode==13){searchProduct('product',jQuery('#bar_id_other').val());return false;}else{checkSearchProduct(this,event.keyCode);searchProduct('product',jQuery('#bar_id_other').val());}" maxlength="400"/></td>
                                    <td width="30%"><img src="packages/hotel/skins/default/images/iosstyle/tia.png" class="keyboard" onclick="get_keyboard('input_product_name','');"/><img src="packages/hotel/skins/default/images/iosstyle/delete-smaller.png" onclick="jQuery('#input_product_name').val('');" style="margin-left:5px;"/></td>
                                </tr>
                            </table>
                            
                            
                            <input name="select_bar" type="button" value="[[.select_area.]]" id="select_bar" onclick="jQuery('#div_select_bar').css({'display':'block','top':'250px','left':'600px'});" class="expan"/>
                            <input name="bar_name_this" type="button" value="[[|bar_name|]]" onclick="jQuery('#restaurant_other').css('display','none');jQuery('#bar_id_other').val([[|department_id|]]);jQuery('#bar_name').val('[[|department_name|]]');searchProduct('this_bar',jQuery('#bar_id_other').val());" class="expan"/>
                            
                            <input name="back" type="button" value="[[.Back.]]" id="back" class="expan" />
                        </div>
						<div>
                        	<div id="div_food_category">[[|food_categories|]]</div>
                            <div class="body">[[|products|]]</div>
                        </div>
				    </div>
                    <!--End:Thanh search-->
                    
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
                    </div>
            		<div style="float:right; <?php if([[=status=]]==''){echo 'display:none;';}?>">
                    	<input type="button" name="preview" value=" [[.view_order.]]" tabindex="-1" class="expan" onclick="window.open('<?php echo Url::build('automatic_vend',array('cmd'=>'detail',md5('act')=>md5('print'),md5('preview')=>1,'id'=>Url::get('id')))?>');"/>                           
                    </div>
                </div>
                <div id="menu_extra">
                	<ul id="menu_extra_ul" style="float:left;">
                    	<li class="menu_extra_li" id="li_quantity" lang="delete" onclick="getSelectQuantity('delete');return false;"><img src="packages/hotel/skins/default/images/iosstyle/delete_button.png" /></li>
                    	<li class="menu_extra_li" id="li_quantity" onclick="getSelectQuantity('quantity');return false;"><img src="packages/hotel/skins/default/images/iosstyle/add_items.png" /></li>
                        <li class="menu_extra_li" id="li_percentage" onclick="getSelectQuantity('percentage');return false;"><img src="packages/hotel/skins/default/images/iosstyle/5.png" /></li>
                        <li style="display: none;" class="menu_extra_li_extra" id="li_discount" onclick="getSelectQuantity('discount');return false;"><img src="packages/hotel/skins/default/images/iosstyle/discount_invoice.png" /></li>   
                        <li class="menu_extra_li_order" style="display:none;"><input name="guest_info" type="button" id="guest_info" value="[[.for_sale.]]" class="summary" onclick="getResevationRoom();"/> </li>
                        <li class="menu_extra_li_extra" id="li_summary" onclick="getSummary();" > <img src="packages/hotel/skins/default/images/iosstyle/9.png" /></li>
                    </ul>              
                </div>
				<div id="foot_drink_bound" style="width:84%; float:right;" class="selected-foot-and-drink-table">
					<table cellpadding="5" width="100%" id="foot_drink" cellspacing="5">
						<tr class="foot-drink-tr header">
							<td width="100px">[[.name_product.]]</td>
							<td width="25px" align="center">SL</td>
							<td width="50px" align="center">[[.price.]]</td>
                            <td width="25">&nbsp;</td>
						</tr>
					</table>
                    <div id="show_detail"></div>
                </div>
                
                <!---->
				<input name="items_id" type="hidden" id="items_id"/>
				<script>
					jQuery("items_id").value = '<?php if(Url::get('items_id')){ echo Url::get('items_id');}?>';
				</script>
                <!---->
                
                <div id="total_mini" style="margin-bottom:5px; margin-top:5px;clear: both;" align="right">
                	<font style="font-size:12px; "><b>[[.total_amount.]] (<?php echo HOTEL_CURRENCY;?>)</b></font>: &nbsp;<span id="amount_mini"><b>[[|remain|]]</b></span>
                </div>
                <div id="show_table"></div>
            </td>
        </tr>
        </table>
        <div id="info_summary"> [[|categories|]]</div>
        <div id="div_bound_summary">
        <div id="bound_summary" style="display:block;">
            <div class="info">
                <span class="title_bound">[[.summary.]]</span>
                <img width="30" height="25" id="btnClose" src="packages/core/skins/default/images/buttons/delete.gif" onclick="HideDialog('bound_summary');" style="float:right;margin:5px;cursor: pointer;"/>
            </div>
            <table cellpadding="0" width="100%">
                <tr>
                <td>
                    <fieldset style="text-align:left; margin-bottom:10px;margin-top:10px;">
                        <legend class="title">[[.guest_info.]]</legend>
            			<table cellpadding="3" width="100%">
                            <tr>
                                <td>[[.guest_name.]]:<input name="receiver_name" type="text" id="receiver_name" value="[[|receiver_name|]]" style="width:150px;"  /></td>
                                <td  colspan="2">&nbsp;</td>
                          	</tr>
                            <tr>
                                <td width="200px">
                                    [[.company.]]:
                                    <input name="customer_name" type="text" id="customer_name" value="[[|customer_name|]]" class="input-text-max" />
                                    <a href="#" onclick="window.open('?page=vending_customer&amp;action=select_customer','customer')"> 
                                        <img width="20" src="skins/default/images/cmd_Tim.gif" />
                                    </a>
                                    <img width="20" src="packages/core/skins/default/images/buttons/delete.gif" onclick="$('customer_name').value='';$('customer_address').value='';$('tax_code').value='';$('customer_id').value=0;" style="cursor:pointer;"/>
                                    <input name="customer_id" type="text" id="customer_id" value="[[|customer_id|]]"  class="hidden" />
                                </td> 
                                <td>
                                    [[.customer_address.]]:
                                    <input name="customer_address" type="text" id="customer_address" value="[[|customer_address|]]" class="input-text-max" style="width: 220px;"  />
                                </td>
                                <td>   
                                    [[.tax_code.]]:
                                    <input name="tax_code" type="text" id="tax_code" value="[[|tax_code|]]" class="input-text-max" style="width: 170px;"   />
                                </td>
                            </tr>
                        </table>
            		</fieldset>
                </td>
                <td></td>
                </tr>
            </table> 
            <fieldset>
                <legend class="title">[[.Order_info.]]</legend>
                <table>
                	<tr>
                        <td>
                            [[.create_date.]]<br />
                            <input name="arrival_date" type="text" id="arrival_date"  class="input-text" value="[[|arrival_date|]]" onclick="jQuery('#ui-datepicker-div').css('z-index',103);" />
                            <input name="arrival_time_in_hour" type="text" id="arrival_time_in_hour" class="input_number" style="width:30px;" maxlength="2" />h
                            <input name="arrival_time_in_munite" type="text" id="arrival_time_in_munite" class="input_number" style="width:30px;" maxlength="2"  />
                        </td>
                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>
                            [[.note.]]<br /><input name="note" type="text" id="note" value="[[|note|]]" class="input-text-max" style="width:380px;"/>
                        </td>
                    </tr>                
                </table>
            </fieldset>
            <fieldset>
                <legend class="title">[[.billing_info.]]</legend>
            	<table class="general-info" align="center" border="1" cellpadding="3" cellspacing="3" width="100%" >
                    <!--Tong tien truoc thue, full charge, rate-->
                    <tr>
                        <td>[[.amount.]]:</td>
                        <td><input name="total_amount" type="text" id="total_amount" value="[[|total_amount|]]" readonly="readonly"  maxlength="200" class="general"/>&nbsp; &nbsp;<label><?php echo HOTEL_CURRENCY;?></label></td>
                        <td>Full charge:<input name="full_charge" type="checkbox" id="full_charge" value="1" <?php if([[=full_charge=]]==1){echo 'checked="checked"';}?> disabled="disabled"/><input name="input_full_charge" type="text" id="input_full_charge" value="[[|full_charge|]]" style="display:none;" /> </td>
                        <td>Full rate: <input name="full_rate" type="checkbox" id="full_rate" value="1" <?php if([[=full_rate=]]==1){echo 'checked="checked"';}?> disabled="disabled"/><input name="input_full_rate" type="text" id="input_full_rate" value="[[|full_rate|]]" style="display:none;"/></td>
                    </tr>
                    <tr>
                        <!--Thue va phi-->
                        <td>[[.service_charge.]]/[[.tax_rate.]]:</td>
                        <td>
                            <input name="service_charge" type="text" id="service_charge" value="[[|service_charge|]]" class="input_number"  maxlength="3" style="width:30px !important;text-align:right;margin-right:15px;" onkeyup="if(to_numeric(this.value)<=100){GetTotalPayment();}else{this.value=((this.value).substr(0,((this.value).length-1)));}" />
                            <input name="tax_rate" type="text" id="tax_rate" value="[[|tax_rate|]]" class="input_number"  maxlength="3" style="width:30px !important;text-align:right;" onkeyup="this.value=number_format(this.value);GetTotalPayment();" onblur="GetTotalPayment();" />
                        </td>
                        <td style="display: none;">[[.discount.]](%/VND):</td>
                        <td style="display: none;">
                            <input name="discount_percent" type="text" id="discount_percent" value="[[|discount_percent|]]" class="input_number"  maxlength="200" style="width:30px !important;text-align:right;margin-right:5px;" onkeyup="if(to_numeric(this.value)<=100){GetTotalPayment();}else{this.value=((this.value).substr(0,((this.value).length-1)));}" />
                            <input name="discount" type="text" id="discount" value="[[|discount|]]" class="input_number"  maxlength="200" style="width:125px !important;text-align:right;" onkeyup="this.value=number_format(this.value);GetTotalPayment();" onblur="GetTotalPayment();" placeholder="[[.discount_cash.]]" />
                        </td>
                    </tr>
                    
                    <tr>
                        <td>[[.total_amount.]]:</td>
                        <td><input name="total_payment" type="text" id="total_payment" value="[[|total_payment|]]" readonly="readonly" class="general"  maxlength="200">&nbsp; &nbsp;<label><?php echo HOTEL_CURRENCY;?></label></td>
                        <td>[[.deposit.]]:</td>
                        <td><input name="deposit" type="text" id="deposit" value="[[|deposit|]]" onkeyup="this.value=number_format(this.value); GetTotalPayment();" class="input_number"  maxlength="200" style="width:80px !important;text-align:right; border:none" readonly="readonly" />&nbsp; &nbsp;<label><?php echo HOTEL_CURRENCY;?></label></td>
                    </tr>
                    <tr>
                        <td>[[.remain.]]:</td>
                        <td><input name="remain" type="text" id="remain" value="[[|remain|]]" class="general" readonly="readonly"  maxlength="200" style="font-weight:bold;"/>&nbsp; &nbsp;<label><?php echo HOTEL_CURRENCY;?></label></td>
                        <td style="display:none">[[.is_debit.]]:</td>
                        <td><input style="display:none" name="is_debit" type="checkbox" id="is_debit" value="1" <?php if([[=is_debit=]]==1){echo 'checked="checked"';}?>/></td>
                    </tr>
                    <tr style="display: none;">
                        <td>[[.FOC.]]:</td>
                        <td><input name="foc" type="checkbox" id="foc" value="1" <?php if([[=foc=]]==1){echo 'checked="checked"';}?>/></td>
                    </tr>
                </table>
            </fieldset>
            
            <div id="button_total">
                <input name="button_ok" type="button" id="button_ok" value="[[.ok.]]" onclick="HideDialog('bound_summary');" style="height:35px; width:80px;float:right;"/>
                <input name="button_reset" type="button" id="button_reset" value="[[.reset.]]" onclick="jQuery('#receiver_name').val('');jQuery('#discount_percent').val('');jQuery('#service_charge').val(0);jQuery('#tax_rate').val(0);jQuery('#deposit').val(0);HideDialog('bound_summary');" style="height:35px; width:80px;float:right;"/>              
            </div>
        </div>
        </div>
    <div id="div_bound_note" style="display:none;">
        <div id="bound_note"><div class="info"><span class="title_bound">[[.note_product.]]</span><img width="20" height="20" id="btnClose" src="packages/core/skins/default/images/buttons/delete.gif" onclick="submitNote(jQuery('#note_product').val(),jQuery('#id_note').val());HideDialog('div_bound_note');" style="float:right;"/></div>
            <input name="id_note" type="hidden" id="id_note" value=""  />
            <textarea id="note_product" cols="82" rows="3"></textarea>
        </div>
    </div>
    
    <div id="dialog" class="web-dialog">
        <div class="info">
            <span style="text-align:center; font-size:14px; color:#6D84B4;line-height:35px;" id="title_select_quantity"><b>[[.edit_quantity_for_menu.]]</b></span>
            <img width="30" height="25" id="btnClose" src="packages/core/skins/default/images/buttons/delete.gif" onclick="HideDialog('dialog');" style="float:right;"/>
        </div>
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
        	<tr>
                <td valign="top">
					<table id="caculator" cellpadding="2" cellspacing="0">
                    	<tr><td><input name="name_1" type="button" value="1" class="button" onclick="GetNumber(this.value,jQuery('#prd_key').val(),jQuery('#pass').val());"></td>
							<td><input name="name_2" type="button" value="2" class="button" onclick="GetNumber(this.value,jQuery('#prd_key').val(),jQuery('#pass').val());"></td>
							<td><input name="name_3" type="button" value="3" class="button" onclick="GetNumber(this.value,jQuery('#prd_key').val(),jQuery('#pass').val());"></td>
						</tr>
						<tr><td><input name="name_4" type="button" value="4" class="button" onclick="GetNumber(this.value,jQuery('#prd_key').val(),jQuery('#pass').val());"></td>
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
						<tr id="tr_percentage">
                        	<td>
                                <input  name="radio_number" type="radio" id="radio_percentage" value="radio_percentage" class="radio_number" lang="percentage"/>
                                <label for="radio_percentage">[[.rate_discount.]](%)</label>:
                            </td>
                            <td>
                                <input  name="percentage" type="text" id="input_radio_percentage" onclick="$('radio_percentage').checked=true;" class="text-right" style="width:100px; display: ;"/>
                            </td>
                        </tr>
                        <tr id="tr_promotion">
                        	<td>
                                <input  name="radio_number" type="radio" id="radio_promotion" value="radio_promotion" class="radio_number" lang="promotion"/>
                                <label for="radio_percentage">[[.quantity_discount.]](%)</label>:
                            </td>
                            <td>
                                <input  name="promotion" type="text" id="input_radio_promotion" onclick="$('radio_promotion').checked=true;" class="text-right" style="width:55px;" title="Discount for product" value="" />
    							<input name="discount_quantity" type="text" id="discount_quantity" value="0" onkeyup="checkLimited(this.value);" onblur="checkLimited(this.value);" style="width:38px;" class="text-right" title="Discount by quantity" />
                            </td>
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
    
    <div id="div_select_bar" style="display:none;">
    	<div class="info"><span class="title_bound">[[.select_area.]]</span><img width="30" height="25" id="btnClose" src="packages/core/skins/default/images/buttons/delete.gif" onclick="HideDialog('div_select_bar');" style="float:right;"/></div>
        <input  name="bar_id_other" type="hidden" value="[[|department_id|]]" id="bar_id_other" />
        <table border="0" cellpadding="2" cellspacing="0" width="350" bordercolor="#CCCCCC" id="bar_select">
        	<?php $i=0;?>
        	<!--LIST:bars-->
            <?php if($i%2==0)
            {
				echo '<tr>';
				echo '<td><input name="'.[[=bars.name=]].'" type="button" id="'.[[=bars.name=]].'" value="'.[[=bars.name=]].'" lang="'.[[=bars.bar_id_from=]].'" onclick="jQuery(\'#bar_id_other\').val(this.lang);jQuery(\'#div_select_bar\').css(\'display\',\'none\');jQuery(\'#bar_name\').val(this.name);searchProduct(\'bar\',jQuery(\'#bar_id_other\').val());" style="width:125px; height:30px;" /></td>';	
			}
            else
            {
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
	var count = 0;
	units=[[|units|]];
	var check_selected = 0;
	bar_id = <?php echo (Session::get('bar_id')?Session::get('bar_id'):'0');?>;
	paging(25);
	SetCss();
	//GetStatus();
	product_list = [[|product_list_js|]];
    var flag2 = 0;
	var flag = 0;
	var full_rate = to_numeric(jQuery('#input_full_rate').val());
	var full_charge = to_numeric(jQuery('#input_full_charge').val());
	for(var j in product_list)
    {
		drawItems(product_list[j]);	
	}
	GetTotalPayment();
    jQuery('#deposit_date').datepicker();
	jQuery("#arrival_date").datepicker({minDate:0});
	jQuery(document).click(function() 
    {
		if(jQuery('#select_number').css('display')=='block')
        {
			var box = jQuery('#select_number');
			box.hide();
		}
		if(jQuery('#select_outside').css('display')=='block')
        {
			var box = jQuery('#select_outside');
			box.hide();
		}
	});
    function check(obj)
    {
    	var bar_id = jQuery('#bar_id_other').val();
		jQuery.ajax({
			url:"form.php?block_id=<?php echo Module::block_id();?>&bar_id="+bar_id,
			type:"POST",
            data:{category_id:obj.attr('lang'),bar_id_other:bar_id,act:'product',cmd:'draw_products',type:'CATEGORY'},
			success:function(html){		
			    
				if(jQuery('#alert').html() == null){
					flag = 1;
					jQuery(".body").html(html);
				}else{
					jQuery("#post_show").css('display','block');
				}
			}
		});
    }
	jQuery(document).ready(function()
    {
        GetTotalPayment();	       
		jQuery('#testRibbon').css('display','none');
		jQuery(".jcarousel-clip-horizontal").width(jQuery('.full_screen').width()-100);
		if(jQuery('#bound_product_list').css('display')=='none')
        {
			jQuery('#bound_product_list').css('display','block');
		}
		jQuery('#button_sum').css('display','block');
		jQuery('.menu_extra_li, .menu_extra_li_extra').click(function(){
			if(jQuery(this).attr('lang')=='delete' && jQuery('#number_selected').val()==1){// TH SL mon deu=1, ko hien thi ma tru luon
			}else{
				var box = jQuery('#select_number');
				box.show(); return false;
			}
		}); 
		jQuery('#li_extra').click(function(){
			var box = jQuery('#select_outside');
			box.show(); return false;
		});
		SetCss();			
	});
    
	jQuery("#expan").click(function(){
		flag = 1;
		ShowExpan();
	});
    
	jQuery(".category-list-item,.category-list-item-parent").each(function(){
	   	jQuery(this).click(function(){	
		  check(jQuery(this))
		})
	});
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
		wrap: 'last',
        //itemFallbackDimension: 300
    });
	jQuery('#ul_food_category').jcarousel({
        visible: 7,
		scroll: 6,
		wrap: 'last',
		vertical:true,
		animation:'fast'
    });
	

	function searchProduct(act,bar_id){
	        //alert(bar_id);
			jQuery('.body').html('');
            jQuery.ajax({
				url:"form.php?block_id=<?php echo Module::block_id();?>&client=1",
				type:"POST",			
                data:{product_name:jQuery("#input_product_name").val(),act:act,bar_id_other:bar_id,cmd:'draw_products',type:'PRODUCT'},
				success:function(html){
					if(jQuery('#alert').html() == null || jQuery('#alert').html() == ''){
						flag = 1;
						jQuery(".body").html(html);
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
                    jQuery('.category-list-item-parent').bind('click',function(){
                        check(jQuery(this));
                    })
				}
			});
	}
	function SelectedItems(key,pass)
    {
		var categories_discount = [[|categories_discount_js|]];
		var discount_pct = 0;
		price_id = key;
        
		for(var j in product_array)
        {
			if( product_array[j]['id'] == price_id )
            {
                //alert(product_array[j]['product_id']);
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
                
				if(pass == 1 || pass == 2)
                {
					if(pass==1)
                    {
						jQuery('#title_select_quantity').html('[[.edit_quantity_for_menu.]]');
					}
                    else
                    {
						jQuery('#title_select_quantity').html('[[.add_quantity_for_menu.]]');
					}
					<?php if(User::can_admin(false,ANY_CATEGORY)){?>
						jQuery('.can_admin').css('display','');
					<?php } ?>
					jQuery('#pass').val(pass);
					jQuery('#prd_key').val(key);
					if(jQuery('#name_'+key).val() != undefined)
                    {
						jQuery('#name').val(jQuery('#name_'+key).val());
					}
                    else 
                        jQuery('#name').val(product_array[j]['name']);
					if(jQuery('#note_'+key).val() != undefined)
                    {
						jQuery('#note-dialog').val(jQuery('#note_'+key).val());
					}
                    else 
                        jQuery('#name').val(product_array[j]['note']);
					if(jQuery('#amount_'+key).val() != undefined && pass == 1)
                    {
						jQuery('#amount').val(number_format(jQuery('#amount_'+key).val()));
					}
                    else
                    {
                        jQuery('#amount').val(number_format(net_price));
                    }
					if(jQuery('#unit_'+key).val() != undefined)
                    {
						jQuery('#unit_abc').val(jQuery('#unit_'+key).val());
					}
                    else 
                    if(product_array[j]['unit'] != '')
                    {
						jQuery('#unit_abc').val(product_array[j]['unit']);
					}
					if(jQuery('#unit_id_'+key).val() != undefined)
                    {
						jQuery('#unit_id').val(jQuery('#unit_id_'+key).val());
					}
                    else
                    {
						jQuery('#unit_id').val(product_array[j]['unit_id']);
					}
					if(jQuery('#note_'+key).val() != undefined)
                    {
						jQuery('#note-dialog').val(jQuery('#note_'+key).val());
					}
					if(jQuery('#price_'+key).val() != undefined && pass==1)
                    {
						jQuery('#input_radio_price').val(to_numeric(jQuery('#price_'+key).val()));
					}
                    else
                    {
                        jQuery('#input_radio_price').val(price);	
                    }						
					if(jQuery('#quantity_'+key).val() != undefined && pass==1)
                    {
						jQuery('#input_radio_quantity').val(to_numeric(jQuery('#quantity_'+key).val()));
					}
                    else
                    {
                        jQuery('#input_radio_quantity').val('');	
                    }
					if(jQuery('#percentage_'+key).val() != undefined && pass==1)
                    {
						jQuery('#input_radio_percentage').val(jQuery('#percentage_'+key).val());
					}
                    else
                    { 
                        jQuery('#input_radio_percentage').val(''); 
                    }
                    if(jQuery('#promotion_'+key).val() != undefined && pass==1)
                    {
						jQuery('#input_radio_promotion').val(jQuery('#promotion_'+key).val());
					}
                    else
                    { 
                        jQuery('#input_radio_promotion').val(''); 
                    }
                    if(jQuery('#quantity_discount_'+key).val() != undefined && pass==1)
                    {
						jQuery('#discount_quantity').val(jQuery('#quantity_discount_'+key).val());
					}
                    else
                    { 
                        jQuery('#discount_quantity').val(''); 
                    }
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
					//jQuery('#input_radio_quantity').ForceNumericOnly();
                    //jQuery('#input_radio_promotion').ForceNumericOnly();
					jQuery('#input_radio_percentage').ForceNumericOnly();
					if(product_array[j]['product_id'] == 'DOUTSIDE' || product_array[j]['product_id'] == 'FOUTSIDE' || product_array[j]['product_id'] == 'SOUTSIDE')
                    {
						jQuery('#name').removeAttr('readonly');
						jQuery('#input_radio_price').removeAttr('readonly');	
						jQuery('#name,#input_radio_price').css('background','#FFF');
						jQuery('#name').focus();
						//jQuery('#tr_cancel').css('display','none');
                        //jQuery('#tr_promotion').css('display','');
						jQuery('#tr_percentage').css('display','none');
						jQuery('#tr_note_outside').css('display','block');
					}
                    else
                    {
						jQuery('#input_radio_price').removeAttr('readonly');
						jQuery('#tr_note_outside').css('display','none');
						jQuery('#name').attr('readonly','readonly');
						<?php if(!User::can_admin(false,ANY_CATEGORY)){?>
						jQuery('#input_radio_price').attr('readonly','readonly');
						<?php }?>
						//jQuery('#tr_cancel').css('display','');
                        //jQuery('#tr_promotion').css('display','');
						jQuery('#tr_percentage').css('display','');
					}
						
				}
                else
                {
					count_product = 0;
					existsCheck = false;
					if( $("quantity_"+key) )
                    {
						existsCheck = true;
					}
					if(existsCheck)
                    {
						var quantity = to_numeric(jQuery("#quantity_"+key).val());
							jQuery("#quantity_"+key).val(1+quantity);
							jQuery("#discount_category_"+key).val(discount_pct);
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
                        arr_product['quantity_discount'] = 0;
						arr_product['percentage'] = 0;
						arr_product['discount_category'] = discount_pct;
						arr_product['unit'] = product_array[j]['unit'];
						arr_product['unit_id'] = product_array[j]['unit_id'];
						arr_product['price'] = price;
						arr_product['quantity'] = 1;
						arr_product['amount'] = number_format(net_price);
						arr_product['printed'] = 0;
						arr_product['note'] = '';
                        arr_product['department_id'] = jQuery('#bar_id_other').val();
                        
						drawItems(arr_product);
						amount = net_price;
						jQuery("#total_amount").val(number_format(to_numeric(jQuery("#total_amount").val()) + amount));
						//jQuery('#loading-layer').fadeOut(200);
					}
					GetTotalPayment();
				}
			}
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
						oldPrice = 0; 
                        oldQuantity=0; 
                        oldAmount =0;
					}
				}
                else
                {
					oldAmount = to_numeric(jQuery("#amount_"+key).val());				
					oldPrice = to_numeric(jQuery("#price_"+key).val());
					oldQuantity = to_numeric(jQuery("#quantity_"+key).val());
				}
				if(act=='edit')
                {
					if($('unit_abc') && $('unit_id') && jQuery('#unit_abc').val()!='')
                    {
						jQuery("#unit_"+key).val(jQuery('#unit_abc').val());
						jQuery("#unit_id_"+key).val(jQuery('#unit_id').val());
					}
					newPercentage = to_numeric(jQuery('#input_radio_percentage').val());
					newPrice = to_numeric(jQuery('#input_radio_price').val());
					newQuantity = to_numeric(jQuery('#input_radio_quantity').val());						
					submitNote(jQuery('#note-dialog').val(),key);
					jQuery("#price_"+key).val(newPrice); 			
					jQuery("#quantity_"+key).val(newQuantity);
					jQuery("#percentage_"+key).val(newPercentage);
                    newPromotion = to_numeric(jQuery('#input_radio_promotion').val());
                    jQuery("#promotion_"+key).val(newPromotion);
                    newQuanityDiscount = to_numeric(jQuery('#discount_quantity').val());
                    jQuery("#quantity_discount_"+key).val(newQuanityDiscount);
				}
                else if(act=='add')
                {
					newQuantity = $('radio_quantity').checked?to_numeric(jQuery('#input_radio_quantity').val()):0;
					newPercentage = $('radio_percentage').checked?to_numeric(jQuery('#input_radio_percentage').val()):0;
                    submitNote(jQuery('#note-dialog').val(),key);
					jQuery("#quantity_"+key).val(newQuantity + oldQuantity);
					jQuery("#percentage_"+key).val(newPercentage);
                    newPromotion = to_numeric(jQuery('#input_radio_promotion').val());
                    jQuery("#promotion_"+key).val(newPromotion);
				}
				if( product_code!='DOUTSIDE' && product_code!='FOUTSIDE' && product_code!='SOUTSIDE' && (newQuantity + oldQuantity)==(to_numeric(jQuery("#remain_"+key).val())+to_numeric(jQuery("#quantity_cancel_"+key).val())))
                {
					jQuery("#table_"+key+" tr td").css('color','#D6D6D6');
					jQuery("#table_"+key+" tr td input").css('color','#D6D6D6');
					jQuery("#table_"+key+" tr td").css('cursor','default');
					jQuery("#table_"+key+" tr td input").css('cursor','default');	
				}

			}
			GetTotalPayment();
		}
	}
	function submitNote(valu,id)
    {
		if(valu != '')
        {
			jQuery('#note_'+id).val(valu);
			jQuery('#note_pr_'+id).html('<img src="packages/hotel/skins/default/images/iosstyle/icon-note-1.png" title="'+valu+'" >');
		}
	}
    
	function drawItems(arr)
    {
		var can_delete = '<?php echo User::can_delete(false,ANY_CATEGORY)?>';
		var can_add = '<?php echo User::can_add(false,ANY_CATEGORY)?>';
		var can_edit = '<?php echo User::can_edit(false,ANY_CATEGORY)?>';
		var items = '<table cellpadding="0" cellspacing=0 width="100%" id="table_'+arr['id']+'" class="selected-foot-and-drink-table" title="'+arr['note']+'" lang="'+arr['id']+'">';
		items += '<tr class="foot-drink-tr" id="item_detail_'+arr['id']+'" onclick="if(jQuery(\'#selected_'+arr['id']+'\').attr(\'checked\')==\'checked\'){jQuery(\'#selected_'+arr['id']+'\').attr(\'checked\',false);}else{jQuery(\'#selected_'+arr['id']+'\').attr(\'checked\',\'checked\'); }CssSelected();">';
		items += '<td width="135px" class="foot-drink-td"><input name="product_list['+arr['id']+'][name]" type="text" value="'+arr['name']+'" id="name_'+arr['id']+'" style=" border:none; width:124px;" readonly="readonly" maxlength="200"></td>';
		items += '<td width="40px" class="foot-drink-td"><input name="product_list['+arr['id']+'][quantity]" type="text" value="'+number_format(arr['quantity'],2)+'" id="quantity_'+arr['id']+'" style=" border:none; width:100%;text-align:right;" readonly="readonly" maxlength="200"></td>';
		items += '<td width="25" style="display:none;"><input name="product_list['+arr['id']+'][promotion]" type="text" value="'+arr['promotion']+'" id="promotion_'+arr['id']+'"  style=" border:none; width:25px;" readonly="readonly" maxlength="200"></td>';
        items += '<td width="25" style="display:none;"><input name="product_list['+arr['id']+'][quantity_discount]" type="text" value="'+arr['quantity_discount']+'" id="quantity_discount_'+arr['id']+'"  style=" border:none; width:25px;" readonly="readonly" maxlength="200"></td>';
		items += '<td width="25" style="display:none;"><input name="product_list['+arr['id']+'][percentage]" type="text" value="'+arr['percentage']+'" id="percentage_'+arr['id']+'" style=" border:none; width:20px;" readonly="readonly" maxlength="200"></td>';
		items += '<td width="25" style="display:none;"><input name="product_list['+arr['id']+'][discount_category]" type="text" value="'+arr['discount_category']+'" id="discount_category_'+arr['id']+'" style=" border:none; width:20px;" readonly="readonly" maxlength="200"></td>';
        items += '<td width="65" style="display:none;" align="right"><input name="product_list['+arr['id']+'][price]" type="text" value="'+number_format(arr['price'])+'" id="price_'+arr['id']+'" style=" border:none; width:60px;text-align:right;" readonly="readonly" maxlength="200"></td>';
        items += '<td width="75" align="right"><input name="product_list['+arr['id']+'][amount]" type="text" value="'+number_format(arr['amount'])+'" id="amount_'+arr['id']+'" style=" border:none; width:100%;text-align:right;" readonly="readonly" maxlength="200"></td>';
		items += '<td width="20" align="center" style="display:none;"><input name="product_list['+arr['id']+'][printed]" type="text" value="'+arr['printed']+'" id="printed_'+arr['id']+'" style=" border:none; width:20px;" readonly="readonly" maxlength="200"></td>';
		if(can_edit){
			items += '<td width="18" id="add_'+arr['id']+'" class="foot-drink-td" align="center" title="edit_product" onclick="jQuery(\'#prd_key\').val(\''+arr['id']+'\');SelectedItems(\''+arr['id']+'\',1);"><img width="15" src="packages/hotel/skins/default/images/iosstyle/edit.png" style="cursor:pointer;"></td>';	
		}
        /*
		if(arr['note'] !=''){
			items += '<td width="20" id="note_pr_'+arr['id']+'" class="foot-drink-td keyboard" onclick="jQuery(\'#id_note\').val(\''+arr['id']+'\');get_keyboard(\'note_product\',\''+arr['id']+'\');getNote(\''+arr['id']+'\');" align="center"><img src="packages/hotel/skins/default/images/iosstyle/icon-note-1.png" class="keyboard" ></td>';
		}else{
			items += '<td width="18" id="note_pr_'+arr['id']+'" class="foot-drink-td keyboard" onclick="jQuery(\'#id_note\').val(\''+arr['id']+'\');get_keyboard(\'note_product\',\''+arr['id']+'\');getNote(\''+arr['id']+'\');" align="center"><img src="packages/hotel/skins/default/images/iosstyle/icon-note-no.png"  class="keyboard" ></td>';	
		}
        */
		items += '<td align="center" style="display:none;"><input name="product_list['+arr['id']+'][note]" type="text" value="'+arr['note']+'" id="note_'+arr['id']+'" style=" border:none; width:25px;" readonly="readonly" maxlength="200"></td>';
		items += '<td align="center" style="display:none;"><input name="product_list['+arr['id']+'][unit]" type="text" value="'+arr['unit']+'" id="unit_'+arr['id']+'" style=" border:none; width:25px;" readonly="readonly" maxlength="200"></td>';
		items += '<td align="center" style="display:none;"><input name="product_list['+arr['id']+'][unit_id]" type="text" value="'+arr['unit_id']+'" id="unit_id_'+arr['id']+'" style=" border:none; width:25px;" readonly="readonly" maxlength="200"></td>';
		items += '<td align="center" style="display:none;"><input name="product_list['+arr['id']+'][price_id]" type="text" value="'+arr['price_id']+'" id="price_id_'+arr['id']+'" style=" border:none; width:25px;" readonly="readonly" maxlength="200"></td>';
		items += '<td align="center" style="display:none;"><input name="product_list['+arr['id']+'][product_id]" type="text" value="'+arr['product_id']+'" id="product_id_'+arr['id']+'" style=" border:none; width:25px;" readonly="readonly" maxlength="200"></td>';	
		items += '<td align="center" style="display:none;"><input name="product_list['+arr['id']+'][brp_id]" type="text" value="'+arr['brp_id']+'" id="brp_id_'+arr['id']+'" style=" border:none; width:25px;" readonly="readonly" maxlength="200"></td>';	
        items += '<td align="center" style="display:none;"><input name="product_list['+arr['id']+'][department_id]" type="text" value="'+arr['department_id']+'" id="department_id_'+arr['id']+'" style=" border:none; width:25px;" readonly="readonly" maxlength="200"></td>';
		//items += '<td align="center" style="display:none;"><input name="product_list['+arr['id']+'][bar_id]" type="text" value="'+arr['bar_id']+'" id="bar_id_'+arr['id']+'" style=" border:none; width:25px;" readonly="readonly" maxlength="200"></td>';	
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
	
	function checkSubmit(obj)
    {
		var id = obj.id;
		var name = obj.name;
		//var status = '[[|status|]]';

		if(id=='checkin'){
			jQuery('#act').val('checkin');
			TouchBarRestaurantForm.submit();
		}else{
			var answ = confirm('[[.do_you_want_to_save.]]');
			if(answ == true){
				jQuery('#act').val(name);
				if(id == 'save_stay'){
					jQuery('#acction').val(1);	
				}
				TouchBarRestaurantForm.submit();
			}else{
				jQuery('#act').val('');
				return false;	
			}
		}
	}
    //gioi han so luong khuyen mai <= quantity
    function checkLimited(original_value)
    {
        var discount_quantity = to_numeric(jQuery('#discount_quantity').val());
        var check = discount_quantity/4;
        var quantity = to_numeric(jQuery('#input_radio_quantity').val())
        if(check == 'NaN' || discount_quantity >= quantity)
        {
            alert('[[.discount_quantity_must_be_smaller_than_quantity.]]');
            jQuery('#discount_quantity').val(0);
            return false;
        }
    }
    //function check
</script> 