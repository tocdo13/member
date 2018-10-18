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
    #banner_url{display:none;}
    input[type=text]{ 
        height:22px !important;
    }
    .category-set-menu{
        border:1px solid #6D84B4;
    	border-radius: 5px;
    	background:#00FF80;	
    	float:left;	
    	width:95%;
    	height:44px;
    	line-height:47px;
    	font-weight:bold;
    	font-size:11px;
    	text-align:center;
    	cursor:pointer;
    }
    #div_food_category #ul_food_category li span{line-height: 15px;padding-top: 5px;height: 43px !important;}
    #select_number{width : 260px;}
    #select_outside{width: 254px;}
    #sign-in{display: none;}
    #chang_language a:first-child{display: none;}
    #chang_language a:last-child{background: #FFF; color: red!important; padding: 5px;box-shadow: 0px 1px 3px 1px rgba(60,64,67,.15); text-decoration: none; font-weight: bold;}
    .simple-layout-content{border: 5px solid #FFF!important;box-shadow: 0px 1px 3px 1px rgba(60,64,67,.15); margin: 0px; padding: 0px;}
    .simple-layout-bound, html{background: rgba(232, 240, 254,0.5)!important;}
    .jcarousel-item{overflow: hidden!important;}
    .jcarousel-container{background: none!important;}
    .jcarousel-skin-tango{margin-left: 0px!important;}
    #pagination,#bound_product_list{ margin: 0px!important; padding: 0px!important; position: relative!important; }
    #pagination li img{ width: 30px!important; height: auto!important; border: none; }
    .jcarousel-prev,.jcarousel-next{border: none!important;}
    .category-list-item-parent{line-height: 20px!important;}
    .falseselect {
        background: #FFFFFF;
        color: #555555;
        font-weight: bold;
    }
    .trueselect {
        background: #2f7bff;
        color: #FFFFFF;
        font-weight: bold;
    }
</style>
<?php if(USE_DISPLAY && USE_DISPLAY==1){ ?> <script src='http://<?php echo IP_NODEJS_SERVER; ?>:<?php echo PORT_NODEJS_SERVER; ?>/socket.io/socket.io.js'></script> <?php } ?>
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
</script>
<link rel="stylesheet" href="packages/hotel/skins/default/css/jcarosel.css" type="text/css"/>
<link rel="stylesheet" href="packages/hotel/skins/default/css/restaurant_test.css" type="text/css"/>
<div id="order_full_screen">
    <form name="TouchBarRestaurantForm" method="post">
        <input name="acction" type="hidden" value="0" id="acction" />
        <input name="act" type="hidden" id="act" value="" />
        <div id="mask"></div>
        <div class="w3-row"><?php echo Form::$current->error_messages();?></div>
        <div class="w3-row">
            <div class="w3-quarter">
                <h3>[[|bar_name|]]</h3>
                <p>[[|table_name|]]</p>
                <p style="font-weight: normal;<!--IF:cond(User::id()=='developer05')--> display: none;<!--/IF:cond-->"><input  name="print_automatic_bill" type="checkbox" value="print_auto" id="print_automatic_bill" <!--IF:cond([[=print_automatic_bill=]]!='')--> checked="checked" <!--/IF:cond-->  />[[.print_automatic_bill.]]</p>
                
            </div>
            <div class="w3-threequarter" style="text-align: right;">
                <div style="float:left;padding-left:10px;width:150px;display:none;" id="restaurant_other">
                    <div align="right">[[.restaurant_menu.]]: <input name="bar_name" type="text" id="bar_name" style="width:200px; border:none; font-size:14px; font-weight:bold;" readonly="readonly" /></div>
                    <div id="div_add_charge" style="text-align:left;">[[.add_charge.]]: <span id="add_charge"></span>%</div>
                </div>
                <?php if(User::can_add(false,ANY_CATEGORY)){?>
                    <input name="checkin" type="button" value="[[.table_checkin.]]" id="checkin" class="w3-button w3-border w3-amber w3-hover-amber" onclick="checkSubmit(this);" style="border: 3px solid #FFF!important; box-shadow: 0px 1px 3px 1px rgba(60,64,67,.15); text-transform: uppercase;"/>
                    <input name="save" type="button" value="[[.save.]]" id="save" class="w3-button w3-border w3-blue w3-hover-blue" onclick="checkSubmit(this);" <?php if(!isset([[=status=]]) || [[=status=]]==''){echo 'style="display:none;"';}else{?>style="border: 3px solid #FFF!important; box-shadow: 0px 1px 3px 1px rgba(60,64,67,.15); text-transform: uppercase;"<?php } ?> />
                    <input name="save_stay" type="button" value="[[.save_and_stay.]]" id="save_stay" class="w3-button w3-border w3-blue w3-hover-blue" onclick="checkSubmit(this);" <?php if(!isset([[=status=]]) || [[=status=]]==''){echo 'style="display:none;"';}else{?>style="border: 3px solid #FFF!important; box-shadow: 0px 1px 3px 1px rgba(60,64,67,.15); text-transform: uppercase;"<?php } ?> />
                    <input name="booked" type="button" value="[[.table_booked.]]" id="booked" class="w3-button w3-border w3-blue w3-hover-blue" onclick="checkSubmit(this);" style="border: 3px solid #FFF!important; box-shadow: 0px 1px 3px 1px rgba(60,64,67,.15); text-transform: uppercase;"/>
                    
                    <input name="back" type="button" value="[[.back.]]" class="w3-button w3-border w3-dark-grey w3-hover-dark-grey" onclick="window.location='<?php echo Url::build('table_map',array('bar_id'=>Session::get('bar_id'),'group'=>Url::get('bar_area_id')?Url::get('bar_area_id'):''));?>'" style="border: 3px solid #FFF!important; box-shadow: 0px 1px 3px 1px rgba(60,64,67,.15); text-transform: uppercase;" />
                    <span id="dynamic_save_button_bound" style="margin-left: 10px;"></span>
                    <!--IF:condmice(Url::get('cmd')=='edit')-->
                        <!--IF:issetmice(isset([[=mice_reservation_id=]]) AND [[=mice_reservation_id=]]!=0 AND [[=mice_reservation_id=]]!='')-->
                        <p>
                            <a href="?page=mice_reservation&cmd=edit&id=[[|mice_reservation_id|]]" class="w3-text-pink">[[.mice.]] [[|mice_reservation_id|]]</a>
                            <input value="[[.split.]] [[.mice.]]" type="button" onclick="FunctionSplitMice('[[|mice_reservation_id|]]','RES','[[|id|]]');" class="w3-button w3-border w3-white w3-hover-white w3-text-pink" style="border: 3px solid #FFF!important; box-shadow: 0px 1px 3px 1px rgba(60,64,67,.15); text-transform: uppercase;" />
                        </p>
                        <!--ELSE-->
                            <!--IF:payroom([[=pay_with_room=]]!=1)-->
                            <!--<p>
                                <input value="[[.add_mice.]]" type="button" onclick="FunctionAddMice('RES','[[|id|]]');" class="w3-button w3-border w3-white w3-hover-white w3-text-pink" style="border: 3px solid #FFF!important; box-shadow: 0px 1px 3px 1px rgba(60,64,67,.15); text-transform: uppercase;" />
                                <input value="[[.select.]] [[.mice.]]" type="button" onclick="FunctionSelectMice('RES','[[|id|]]');" class="w3-button w3-border w3-white w3-hover-white w3-text-pink" style="border: 3px solid #FFF!important; box-shadow: 0px 1px 3px 1px rgba(60,64,67,.15); text-transform: uppercase;" />
                            </p>-->
                            <!--/IF:payroom-->
                        <!--/IF:issetmice-->
                    <!--/IF:condmice-->
                    <div id="password_box" style="display:none;position:absolute;top:auto;left:auto;border:1px solid #000000;padding:10px;text-align:center;background:#FFFF99;">
                        [[.password.]]: <input  name="password" type="password" id="password" />
                        <input type="button" value="OK" onclick="SubmitForm('');" tabindex="-1" style="color:#000066;font-weight:bold;"/>
                        <a class="close" onclick="jQuery('#password_box').hide();$('password').value='';">[[.close.]]</a>
                    </div>
                <?php }?>
            </div>
        </div>
        <!--IF:cond(User::id()=='developer05')-->
        <hr style="margin: 5px 0!important;" />
        <div class="w3-row" style="padding: 3px;">
            <div onclick="" class="w3-button w3-white w3-hover-white w3-border" style="font-weight: normal; padding: 3px; margin-right: 3px;">
                <i class="fa fa-fw fa-users"></i> ALL
            </div>
            <div onclick="" class="w3-button w3-white w3-hover-white w3-border" style="font-weight: normal; padding: 3px; margin-right: 3px;">
                <i class="fa fa-fw fa-user-o"></i> A
            </div>
            <div onclick="" class="w3-button w3-white w3-hover-white w3-border" style="font-weight: normal; padding: 3px; margin-right: 3px;">
                <i class="fa fa-fw fa-user-o"></i> B
            </div>
            <div onclick="" class="w3-button w3-blue w3-hover-blue w3-border-blue" style="font-weight: normal; padding: 3px; margin-right: 3px;">
                <i class="fa fa-fw fa-user-plus"></i>
            </div>
        </div>
        <hr style="margin: 5px 0!important;" />
        <!--/IF:cond-->
        <table id="table_bound">
            <tr>
                <td class="info-left" id="hidden_expan" style="border: none!important;">    
                    <div class="info" style="background: none!important; border: none;">
                        <div style="float:left;">
                            <span style="font-size:12px;" >[[.create_date.]]: &nbsp;[[|date|]]</span><br />
                            <!--IF:cond_bill(isset([[=code=]]) and [[=code=]])--><b>[[.Bill_ID.]]: [[|code|]]</b><!--/IF:cond_bill-->
                            <input  name="status" type="text" id="status" value="[[|status|]]" readonly="readonly" style="border: none; font-weight: bold; background: none; width: 60px; " />
                        </div>
                        <div style="float:right;">
                            <input type="button" value="[[.table_select.]]" tabindex="-1" class="w3-button w3-light-grey w3-border" onclick="jQuery('#group_table_select').css('display','');"/>
                            <?php if(Url::get('cmd')!='add'){?>
                                <!--IF:cond(User::id()=='developer05')-->
                                <input type="button" name="preview" value="Tạo HĐ" tabindex="-1" class="w3-button w3-light-grey w3-border" onclick="location.href='?page=touch_bar_restaurant&cmd=invoice&id=<?php echo Url::get('id'); ?>&table_id=<?php echo Url::get('table_id'); ?>&bar_area_id=<?php echo Url::get('bar_area_id'); ?>&bar_id=<?php echo Url::get('bar_id'); ?>';"/>
                                <!--ELSE-->
                                <input type="button" name="preview" value="[[.view_order.]]" tabindex="-1" class="w3-button w3-light-grey w3-border" onclick="if(jQuery('#status').val()!=''){window.open('<?php echo Url::build('touch_bar_restaurant',array('cmd'=>'detail',md5('act')=>md5('print_bill'),md5('preview')=>1,'id'=>Url::get('id'),'bar_id','table_id','bar_area_id','package_id'=>Url::get('package_id')?Url::get('package_id'):''))?>');}else{jQuery('#act').val('submit_invoice');TouchBarRestaurantForm.submit();}"/>
                                <!--/IF:cond-->                           
                            <?php }?>
                            <input type="button" name="preview" value=" [[.food_order.]] " onclick="sentToPrint('kitchen','<?php echo Url::get('id');?>');" tabindex="-1" class="expan" style="display:none;"/>
                            <input type="button" name="preview" value=" [[.drink_order.]] " onclick="sentToPrint('bar','<?php echo Url::get('id');?>');" tabindex="-1" class="expan" style="display:none;"/>
                            <input type="button" value="[[.print_banquet_event_order.]]" onClick="window.open('<?php echo Url::build('touch_bar_restaurant',array('cmd'=>'detail','act'=>'print_b_e_order','id'=>Url::get('id')));?>');" tabindex="-1" class="expan" style="display:none;"/>
                        </div>
                    </div>
                    <div class="w3-row">
                        <div class="w3-left">
                            <div id="foot_drink_bound" style="width:315px; border: none; box-shadow: 0px 0px 1px #CCC;">
                                <table width="100%" id="foot_drink">
                                    <tr style="background: #EEE; height: 35px;">
                                        <td width="75">[[.name_product.]]</td>
                                        <td width="26" align="center">SL</td>
                                        <td width="65" align="right">[[.price.]]</td>
                                        <td width="20" title="[[.detail.]]">CT</td>
                                        <td width="25" title="[[.note.]]">GC</td>
                                        <td width="25">&nbsp;</td>
                                    </tr>
                                </table>
                                <div id="show_detail"><!--IF:cond(isset([[=items=]]) AND !empty([[=items=]]))-->[[|items|]]<!--/IF:cond--></div>
                            </div>
                            <input name="items_id" type="hidden" id="items_id"/><script>jQuery("items_id").value = '<?php if(Url::get('items_id')){ echo Url::get('items_id');}?>';</script>
                            <table style="width:315px;">
                                <tr style="height: 30px;">
                                    <td><b>[[.total_payment_traveller.]] (<?php echo HOTEL_CURRENCY;?>)</b></td>
                                    <td style="text-align: right;">
                                        <input name="total_payment_traveller" id="total_payment_traveller" type="text" class="input_number w3-input w3-border" style="text-align: right;" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val())));GetTotalPayment();" value="<?php if(isset([[=total_payment_traveller=]])){echo System::display_number([[=total_payment_traveller=]]);} ?>" />
                                    </td>
                                </tr>
                                <tr style="height: 30px;">
                                    <td><b>[[.total_amount.]] (<?php echo HOTEL_CURRENCY;?>)</b></td>
                                    <td style="text-align: right;"><span id="amount_mini" style="font-weight: bold;">[[|remain|]]</span></td>
                                </tr>
                                <tr style="height: 30px;">
                                    <td><b>[[.total_remain_traveller.]] (<?php echo HOTEL_CURRENCY;?>)</b></td>
                                    <td style="text-align: right;"><label id="total_remain_traveller"></label></td>
                                </tr>
                            </table>
                        </div>
                        <div class="w3-right">
                            <div id="menu_extra">
                                <ul id="menu_extra_ul" style="float:right;">
                                    <li class="menu_extra_li" id="li_quantity" lang="delete" onclick="getSelectQuantity('delete');return false;" style="overflow: hidden; margin: 0px; padding: 0px; line-height: 40px;" title="[[.delete.]] / [[.reduce_number.]]"><img src="packages/hotel/skins/default/images/iosstyle/1.png" /></li>
                                    <li class="menu_extra_li" id="li_quantity" onclick="getSelectQuantity('quantity');return false;" style="overflow: hidden; margin: 0px; padding: 0px; line-height: 40px; margin-top: 3px;" title="[[.add.]] [[.quantity.]]"><img src="packages/hotel/skins/default/images/iosstyle/quantity.png" /></li>
                                    <li class="menu_extra_li" id="li_percentage" onclick="getSelectQuantity('percentage');return false;" style="overflow: hidden; margin: 0px; padding: 0px; line-height: 40px; margin-top: 3px;" title="[[.discount_percent.]]" title="[[.edit.]]"><img src="packages/hotel/skins/default/images/iosstyle/4.png" /></i></li>
                                    <li class="menu_extra_li" id="li_edit" onclick="items_checked();return false;" style="overflow: hidden; margin: 0px; padding: 0px; line-height: 40px; margin-top: 3px;" title="[[.edit.]]"><img src="packages/hotel/skins/default/images/iosstyle/3.png" /></li>
                                    <!--<li class="menu_extra_li_extra" id="li_discount" onclick="getSelectQuantity('discount');return false;" style="overflow: hidden; margin: 0px; padding: 0px; line-height: 40px;"><img src="packages/hotel/skins/default/images/iosstyle/5.png" /></li>-->
                                    <li class="menu_extra_li_extra" id="li_extra" onclick="getSelectOutSide('extra');return false;" style="overflow: hidden; margin: 0px; padding: 0px; line-height: 40px; margin-top: 3px;" title="[[.add_extra_product.]]"><img src="packages/hotel/skins/default/images/iosstyle/6.png" /></li>
                                    
                                    <li class="menu_extra_li_order1 <?php if(isset([[=mice_reservation_id=]]) AND [[=mice_reservation_id=]]!=''){ ?>css_menu_estra_disable<?php }else{ } ?>" <?php if(isset([[=mice_reservation_id=]]) AND [[=mice_reservation_id=]]!=''){ ?>style="cursor: default; background: rgb(214, 214, 214); overflow: hidden; margin: 0px; padding: 0px; line-height: 40px;"<?php }else{ ?> style="overflow: hidden; margin: 0px; padding: 0px; line-height: 40px; margin-top: 3px;"<?php } ?> id="li_deposit" onclick="<?php if(isset([[=mice_reservation_id=]]) AND [[=mice_reservation_id=]]!=''){ ?>return false;<?php }else{ ?>openWindowUrl('form.php?block_id=<?php echo BLOCK_PAYMENT;?>&cmd=deposit&id=<?php if(Url::get('id')){echo Url::get('id');}else{echo '';}?>&type=BAR',Array('payment','payment_for',80,210,950,500));<?php } ?>" title="[[.deposit.]]"><img src="packages/hotel/skins/default/images/iosstyle/7.png" /></li>
                                    <?php
                                      if([[=status=]] && [[=status=]]!='BOOKED')
                                      {  
                                    ?>
                                    <li class="menu_extra_li_order1 <?php if(isset([[=mice_reservation_id=]]) AND [[=mice_reservation_id=]]!=''){ ?>css_menu_estra_disable<?php }else{ } ?>" <?php if(isset([[=mice_reservation_id=]]) AND [[=mice_reservation_id=]]!=''){ ?>style="cursor: default; background: rgb(214, 214, 214); overflow: hidden; margin: 0px; padding: 0px; line-height: 40px;"<?php }else{ ?> style="overflow: hidden; margin: 0px; padding: 0px; line-height: 40px; margin-top: 3px;"<?php } ?> id="li_payment" onclick="<?php if(isset([[=mice_reservation_id=]]) AND [[=mice_reservation_id=]]!=''){ ?>return false;<?php }else{ ?><?php if([[=status=]]!='CHECKOUT'){ ?>SubmitForm('payment');<?php }else{ ?>jQuery('#act').val('payment');jQuery('#password_box').show();<?php } ?><?php } ?>" title="[[.payment.]]"><img src="packages/hotel/skins/default/images/iosstyle/8.png" /></li>
                                    <?php
                                      }  
                                    ?>
                                    <li class="menu_extra_li_order" id="li_vat" style="background:#ffffc0; display:none; overflow: hidden; margin: 0px; padding: 0px; line-height: 40px;" onclick="window.location='?page=vat_bill&department=RESTAURANT&cmd=entry_restaurant&b_r_id=<?php echo Url::get('id');?>'">
                                        <img src="packages/hotel/skins/default/images/iosstyle/icon-vat.png" />
                                    </li>    
                                    <li class="menu_extra_li_order" style="display:none; overflow: hidden; margin: 0px; padding: 0px; line-height: 40px;"><input name="guest_info" type="button" id="guest_info" value="[[.for_sale.]]" class="summary" onclick="getResevationRoom();"/> </li>
                                    <li class="menu_extra_li_extra" id="li_summary" onclick="getSummary();" style="overflow: hidden; margin: 0px; padding: 0px; line-height: 40px; margin-top: 3px;" title="[[.info.]]"><img src="packages/hotel/skins/default/images/iosstyle/9.png" /></li>
                                </ul>              
                            </div>
                        </div>
                    </div>   
                </td>
                <td class="info-right" id="expan_display" style="border: none;">
                  <div id="product_select_expansion">
                        <div class="select-menu-bound">
                            <a name="top" ></a>
                            <div class="title" style="background: none; border: none; width: 500px; vertical-align: top; margin: 0px; padding: 0px;">
                                <table id="search_div" style="border: none; width: auto; height: 35px; margin-left: 10px;">
                                    <tr>
                                        <td><span style="font-size:12px; text-transform: lowercase;">[[.search.]]</span></td>
                                        <td style="border: 1px solid #EEE; border-right: none;">
                                            <input name="input_product_name" type="text" id="input_product_name" style="width: 150px;" onkeypress="if(event.keyCode==13){searchProduct('product',jQuery('#bar_id_other').val());return false;}" onkeyup="if(event.keyCode==13){searchProduct('product',jQuery('#bar_id_other').val());return false;}else{checkSearchProduct(this,event.keyCode);searchProduct('product',jQuery('#bar_id_other').val());}" maxlength="400"/>
                                        </td>
                                        <td style="border: 1px solid #EEE; border-left: none; border-right: none;">
                                            <i class="keyboard fa fa-fw fa-keyboard-o w3-text-blue" onclick="get_keyboard('input_product_name','');"></i><i class="fa fa-remove fa-fw w3-text-pink" onclick="jQuery('#input_product_name').val(''); searchProduct('product',jQuery('#bar_id_other').val());" style="margin-left: 5px;"></i>
                                        </td>
                                        <td style="border: 1px solid #EEE; border-left: none;">
                                            <i id="search_product" class="fa fa-fw fa-search w3-text-blue" onclick="searchProduct('product',jQuery('#bar_id_other').val());"></i>
                                        </td>
                                        <td>
                                            <input name="select_bar" type="button" value="[[.select_bar.]]" id="select_bar"  class="w3-button w3-light-grey w3-border w3-right" style="height: 35px; line-height: 35px; padding: 0px 8px; margin: 0px;"onclick="jQuery('#div_select_bar').css({'display':'block','top':'250px','left':'600px'});"/>
                                        </td>
                                        <td>
                                            <input name="bar_name_this" type="button" value="[[|bar_name|]]" class="w3-button w3-light-grey w3-border w3-right" style="height: 35px; line-height: 35px; padding: 0px 8px; margin: 0px; max-width: 145px; overflow: hidden;" onclick="jQuery('#restaurant_other').css('display','none');jQuery('#bar_id_other').val('<?php echo Session::get('bar_id');?>');searchProduct('this_bar',jQuery('#bar_id_other').val());"/>
                                        </td>
                                        <td>
                                            <input name="product_select_expansion" type="button" value="[[.expan.]]" id="expan"  class="w3-button w3-light-grey w3-border w3-right" onclick="search_product();" style="height: 35px; line-height: 35px; padding: 0px 8px; margin: 0px; display:none;"/>
                                        </td>
                                        <td>
                                            <input name="back" type="button" value="[[.Back.]]" id="back"  class="w3-button w3-light-grey w3-border w3-right" style="height: 35px; line-height: 35px; padding: 0px 8px; margin: 0px; display: none;" />
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div>
                                <div id="div_food_category">[[|food_categories|]]</div>
                                <div class="body" style="width: 510px;">[[|products|]]</div>
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
            </tr>
          </table>
          <div id="info_summary" style="border: none; background: none;"> 
            [[|categories|]]
          </div> 
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
                        <td nowrap="nowrap"><input name="customer_name" type="text" id="customer_name" value="[[|customer_name|]]" class="input-text-max" style="float:left;" onfocus="customerAutocomplete();" autocomplete="off" />
                          <input name="customer_id" type="text" id="customer_id" value="<?php if([[=customer_id=]]){echo [[=customer_id=]];}else{ echo '';}?>" class="hidden"  />
                          <a href="#" onclick="window.open('?page=customer&amp;action=select_customer','customer')"> <img width="30" src="skins/default/images/cmd_Tim.gif" style="float:left;margin-right:10px;" /></a> <img width="30" src="packages/core/skins/default/images/buttons/delete.gif" onClick="$('customer_name').value='';$('customer_id').value=0;" style="cursor:pointer;float:left;"></td>
                        
                    </tr>
                    <tr>
                        <td nowrap>[[.receiver_phone.]]:</td>
                        <td><input name="receiver_phone" type="text" id="receiver_phone" value="[[|receiver_phone|]]" style="width:150px;"  class="input_number"/></td>
                    </tr>
               </table>
            </fieldset>
            </td>
            <td valign="top">
            <?php
                if([[=status=]] && [[=status=]]!='BOOKED')
                {
            ?>
            <fieldset style="text-align:left; margin-bottom:10px;margin-top:10px;height:120px; width: 350px;" id="guest_room_info">
            <legend class="title"><img src="packages/core/skins/default/images/customer_icon.jpg" width="15" height="20" align="top"/>&nbsp;[[.guest_room.]]</legend>
                <table cellpadding="3" width="55%">
                    <tr style="<?php if(isset([[=mice_reservation_id=]]) AND [[=mice_reservation_id=]]!=0 AND [[=mice_reservation_id=]]!=''){ ?> display: none; <?php } ?>">
                        <?php if([[=reservation_room_status=]]=='CHECKOUT'){ ?>
                            <td colspan="4">
                                [[.guest_room.]]: [[|reservation_name|]] <br /> [[.room.]]: [[|room_name|]]
                                <input name="pay_with_room" type="checkbox" class="checkbox" id="pay_with_room" <?php if([[=pay_with_room=]]==1){ echo 'checked="checked"';}?> style="display:none !important;" />
                                <input name="reservation_traveller_id" id="reservation_traveller_id" type="text" value="[[|reservation_traveller_id|]]" style="display: none;" />
                                <input name="reservation_room_id" id="reservation_room_id" type="text" value="[[|reservation_room_id|]]" style="display: none;" />
                            </td>
                        <?php }else{ ?>
                <td nowrap>&nbsp;</td>
                <th align="left">[[.select_guest_room.]] <br /><select name="reservation_traveller_id" id="reservation_traveller_id" onchange="update_room(this);if(this.value!=0){jQuery('#pay_with_room').attr('checked',true); jQuery('#tr_total_payment_room').css('display',''); jQuery('#img_pay_with_room').css('display','block'); }else{ jQuery('#pay_with_room').attr('checked',false); jQuery('#img_pay_with_room').css('display','none'); jQuery('#tr_total_payment_room').css('display','none'); jQuery('#total_payment_room').val(0); }"  style="width:170px;height:35px;"></select></th>
                <td nowrap width="50%">[[.room_name.]] <br /><select name="reservation_room_id" id="reservation_room_id"  onchange="update_traveller(this);if(this.value!=0){ jQuery('#pay_with_room').attr('checked',true); jQuery('#img_pay_with_room').css('display','block'); jQuery('#total_payment_room').val(jQuery('#amount_mini').html()); jQuery('#tr_total_payment_room').css('display','');}else{ jQuery('#pay_with_room').attr('checked',false); jQuery('#img_pay_with_room').css('display','none');  jQuery('#total_payment_room').val(0); jQuery('#tr_total_payment_room').css('display','none'); jQuery('#total_payment_room').val(0);}" style="width:100px;height:35px;"></select></td>
                <td align="right" valign="bottom">
                <input name="pay_with_room" type="checkbox" class="checkbox" id="pay_with_room" <?php if([[=pay_with_room=]]==1){ echo 'checked="checked"';}?> style="display:none !important;" />
                <label for="pay_with_room"><span class="checkbox-span" style="border: none;" title="[[.pay_with_room.]]"><img id="img_pay_with_room" style="width: 30px; height:30px; <?php if([[=pay_with_room=]]==1){ echo "display:block;"; } else { echo "display:none;"; } ?>" src="packages/hotel/skins/default/images/iosstyle/success.png" /></span></label>
                        </td>
                        <?php } ?>
                      </tr>
                      <tr>
                        <td colspan="4">
                            <?php if(SETTING_POINT==1){ ?>
                            <span style="float: left; line-height: 25px;"> [[.member_code.]]: </span>
                            <input type="text" name="member_code"value="<?php echo isset($this->map['member_code'])?$this->map['member_code']:''; ?>" id="member_code" autocomplete="off" onchange="fun_load_member_code();" style="width: 100px; height: 25px; text-align: center; border: 1px solid #555555; float: left;" />
                            <input type="text" name="member_level_id" value="<?php echo isset($this->map['member_level_id'])?$this->map['member_level_id']:''; ?>" id="member_level_id" style="display: none;" />
                            <input type="text" name="create_member_date" value="<?php echo isset($this->map['create_member_date'])?$this->map['create_member_date']:''; ?>" id="create_member_date" style="display: none;" />
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
                      <tr id="tr_total_payment_room" style="<?php if([[=pay_with_room=]]!=1){ echo 'display: none;';}?>">
                        <td colspan="4">
                            <span style="float: left; line-height: 25px;"> [[.total_payment_room.]]: </span>
                            <input  name="total_payment_room" type="text" value="<?php echo isset($this->map['total_payment_room'])?$this->map['total_payment_room']:0; ?>" id="total_payment_room" onkeyup="jQuery('#total_payment_room').val(number_format(jQuery('#total_payment_room').val()));" />
                        </td>
                      </tr>
               </table>
            </fieldset>
                <?php
                    }
                ?> 
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
                         <td nowrap="nowrap">[[.num_people.]]<br />
                         <input name="num_people" type="text" id="num_people" value="[[|num_people|]]" class="input-text"></td>
                         <td nowrap="nowrap">[[.order_person.]]<br /><input name="order_person" type="text" value="[[|order_person|]]" id="order_person" class="input-text" /></td>
                         <td>[[.banquet_order_type.]]<br />
                             <input name="banquet_order_type" type="text" id="banquet_order_type" value="[[|banquet_order_type|]]" class="input-text" />
                         </td>
                    </tr>
                    <tr>
                    	<td nowrap="nowrap" width="100">[[.departure_time.]]<br />
                            <input name="departure_date" type="text" id="departure_date"  class="input-text" value="[[|departure_date|]]" onclick="jQuery('#ui-datepicker-div').css('z-index',104);" /></td>
                    	<td nowrap="nowrap"  width="90">[[.arrival_time_out.]]<br />
                             <input name="arrival_time_out_hour" type="text" id="arrival_time_out_hour" class="input_number" style="width:30px;" maxlength="2"  />h
                            <input name="arrival_time_out_munite" type="text" id="arrival_time_out_munite" class="input_number" style="width:30px;" maxlength="2"  />
                         </td>
                         <td nowrap="nowrap" colspan="3">[[.note.]]<br /><input name="note" type="text" id="note" value="[[|note|]]" class="input-text-max" style="width:235px;"/></td>
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
                    <td width="1%" nowrap="nowrap">[[.discount.]](%/VND):<input name="discount_after_tax" type="checkbox" id="discount_after_tax" title="[[.discount_after_tax.]]" value="1" <?php if([[=discount_after_tax=]]==1){echo 'checked="checked"';}?> disabled="disabled"/><input name="input_discount_after_tax" type="text" id="input_discount_after_tax" value="[[|discount_after_tax|]]" style="display:none;"/></td>
                    <td>
                        <input name="discount_percent" type="text" id="discount_percent" value="[[|discount_percent|]]" class="input_number"  maxlength="200" style="width:30px !important;text-align:right;margin-right:5px;" onkeyup="if(to_numeric(this.value)<=100){GetTotalPayment();}else{this.value=((this.value).substr(0,((this.value).length-1)));}CheckdDiscountPercent();" />
                        <input name="discount" type="text" id="discount" value="[[|discount|]]" class="input_number"  maxlength="200" style="width:125px !important;text-align:right;" onkeyup="this.value=number_format(this.value);GetTotalPayment(); CheckDiscount();if(to_numeric(this.value)>0 && to_numeric(jQuery('#total_amount').val())<0){this.value='';GetTotalPayment();}" onblur="GetTotalPayment();" placeholder="[[.discount_cash.]]" /></td>
                    </td>
                </tr>
                 <tr>
                    <td width="1%" nowrap="nowrap">[[.total_amount.]]:</td>
                    <td><input name="total_payment" type="text" id="total_payment" value="[[|total_payment|]]" readonly="readonly" class="general"  maxlength="200">&nbsp; &nbsp;<label><?php echo HOTEL_CURRENCY;?></label></td>
                    <td width="1%" nowrap="nowrap">[[.reason_discount.]]:</td>
                    <td><textarea name="reason_discount" id="reason_discount" style="width: 224px; height: 50px; overflow: hidden;">[[|reason_discount|]]</textarea></td>
                </tr>
                 <tr style="display:none;">
                    <td>[[.deposit_date.]]:</td>
                    <td><input name="deposit_date" type="text" id="deposit_date" value="[[|deposit_date|]]" class="general"  maxlength="200"/>&nbsp; &nbsp;</td>
                </tr>
                 <tr>
                    <td width="1%" nowrap="nowrap">[[.remain.]]:</td>
                    <td><input name="remain" type="text" id="remain" value="[[|remain|]]" class="general" readonly="readonly"  maxlength="200" style="font-weight:bold;"/>&nbsp; &nbsp;<label><?php echo HOTEL_CURRENCY;?></label></td>
                    <td width="1%" nowrap="nowrap">[[.deposit.]]:</td>
                    <td><input name="deposit" type="text" id="deposit" value="[[|deposit|]]" onkeyup="this.value=number_format(this.value); GetTotalPayment();" class="input_number"  maxlength="200" style="width:80px !important;text-align:right; border:none" readonly="readonly" />&nbsp; &nbsp;<label><?php echo HOTEL_CURRENCY;?></label></td>
                </tr>
            </table>
            </fieldset>
                <div id="button_total">
                    <input name="button_ok" type="button" id="button_ok" value="[[.ok.]]" onclick="HideDialog('bound_summary');normal_submit()" style="height:35px; width:80px;float:right;"/>
                    <input name="button_reset" type="button" id="button_reset" value="[[.reset.]]" onclick="jQuery('#receiver_name').val('');jQuery('#discount_percent').val('');jQuery('#deposit').val(0);jQuery('#deposit_date').val('');HideDialog('bound_summary');" style="height:35px; width:80px;float:right;" />              
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
                    <td>[[.name.]]:&nbsp;<input  name="name" type="text" border="0" id="name" readonly="readonly" style="width:300px;background:#fff;margin-left:10px;"/></td>
                    <td></td>
                </tr>
                <tr id="tr_note_outside" style="display:none;">
                    <td>[[.unit.]]:&nbsp;<input name="unit_abc" type="text" id="unit_abc" value="" style="width:285px;" />
                    <input  name="unit_id" id="unit_id" value="" size="10" class="text-right" style="display:none;"/> </td>
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
                                <tr><td><input name="name_1" type="button" value="1" class="button" onclick="GetNumber(this.value,jQuery('#prd_key').val(),jQuery('#pass').val());"/></td>
                                    <td><input name="name_2" type="button" value="2" class="button" onclick="GetNumber(this.value,jQuery('#prd_key').val(),jQuery('#pass').val());"/></td>
                                    <td><input name="name_3" type="button" value="3" class="button" onclick="GetNumber(this.value,jQuery('#prd_key').val(),jQuery('#pass').val());"/></td>
                                </tr>
                                <tr><td><input  name="name_4" type="button" value="4" class="button" onclick="GetNumber(this.value,jQuery('#prd_key').val(),jQuery('#pass').val());"/></td>
                                    <td><input name="name_5" type="button" value="5" class="button" onclick="GetNumber(this.value,jQuery('#prd_key').val(),jQuery('#pass').val());"/></td>
                                    <td><input name="name_6" type="button" value="6" class="button" onclick="GetNumber(this.value,jQuery('#prd_key').val(),jQuery('#pass').val());"/></td>
                                </tr>
                                <tr><td><input name="name_7" type="button" value="7" class="button" onclick="GetNumber(this.value,jQuery('#prd_key').val(),jQuery('#pass').val());"/></td>
                                    <td><input name="name_8" type="button" value="8" class="button" onclick="GetNumber(this.value,jQuery('#prd_key').val(),jQuery('#pass').val());"/></td>
                                    <td><input name="name_9" type="button" value="9" class="button" onclick="GetNumber(this.value,jQuery('#prd_key').val(),jQuery('#pass').val());"/></td>
                                </tr>
                                <tr>
                                    <td><input name="." type="button" value="." class="button" onclick="GetNumber(this.value,jQuery('#prd_key').val(),jQuery('#pass').val());"/></td>
                                    <td><input name="name_0" type="button" value="0" class="button" onclick="GetNumber(this.value,jQuery('#prd_key').val(),jQuery('#pass').val());"/></td>
                                    <td><input name="backspace" type="button" value="<-" class="button" onclick="GetNumber(this.value,jQuery('#prd_key').val(),jQuery('#pass').val());"/></td>
                                </tr>
                            </table>
                        </td>    
                        <td valign="top">   
                            <table style="margin-top:20px;">
                                
                                <tr class="can_admin" style="display:none;">
                                    <td><input  name="radio_number" type="radio" id="radio_price" value="radio_price" checked="checked" class="radio_number" lang=""/><label for="radio_price">[[.price.]]</label>:</td>
                                    <td><input  name="price" type="text" id="input_radio_price" onclick="$('radio_price').checked=true;" onkeyup="jQuery('#input_radio_price').FormatNumber();update_amount(true,jQuery('#prd_key').val());GetTotalPayment();" onblur="update_amount(true,jQuery('#prd_key').val());GetTotalPayment()" style="width:100px;text-align:right;" border="0" class="input_number" /></td>
                                </tr>
                                <tr>
                                    <td><input  name="radio_number" type="radio" id="radio_quantity" value="radio_quantity" checked="checked" class="radio_number" lang=""/><label for="radio_quantity">[[.quantity.]]</label>:</td>
                                    <td><input  name="quantity" type="text" id="input_radio_quantity" onclick="$('radio_quantity').checked=true;" onkeyup="check_printed(jQuery('#prd_key').val());jQuery('#input_radio_quantity').FormatNumber();update_amount(true,jQuery('#prd_key').val());GetTotalPayment();" onblur="update_amount(true,jQuery('#prd_key').val());GetTotalPayment(); checkEditQuantity(this);" value="1" class="input_number" style="width:100px;text-align:right;"  /></td>
                                </tr>
                                <tr>
                                    <td><label for="input_radio_complete_quantity">[[.complete.]]</label>:</td>
                                    <td><input  name="complete" type="number" id="input_radio_complete_quantity" onclick="$('radio_complete_quantity').checked=true;" onkeyup="" value="0" style="width:100px;text-align:right;" readonly="" class="readonly"  /></td>
                                </tr>
                                <tr class="can_admin" style="display:none;">
                                    <td>[[.total_amount.]]:</td>
                                    <td><input  name="amount" type="text" id="amount" border="0" style="width:100px;background:#fff;" class="text-right readonly" value="" readonly="readonly"  /></td>
                                </tr>
                                <tr id="tr_promotion">
                                    <td><input  name="radio_number" type="radio" id="radio_promotion" value="radio_promotion" class="radio_number" lang="quantity"/><label for="radio_promotion">[[.promotion.]]</label>:</td>
                                    <td> <input name="promotion_quantity" type="text" id="input_radio_promotion" onclick="$('radio_promotion').checked=true;" class="text-right input_number" style="width:100px;" onchange="if(to_numeric(jQuery('#input_radio_quantity').val()) - to_numeric(jQuery('#input_radio_return').val()) < to_numeric(jQuery('#input_radio_promotion').val())){ alert('[[.so_luong_khuyen_mai_lon_hon_so_luong_thuc_te.]]');jQuery('#input_radio_promotion').val(0);}" /></td>
                                </tr>
                                <tr id="tr_return">
                                    <td><input  name="radio_number" type="radio" id="radio_return" value="radio_return" class="radio_number"  lang="quantity"/><label for="radio_return">[[.return.]]</label></td>
                                <td> <input  name="return" type="text" id="input_radio_return" onclick="$('radio_return').checked=true;<?php if(Url::get('cmd')=='add'){?> alert('Khong the tra lai mon khi da in Order);jQuery('#input_radio_return').blur(); <?php } ?>" onchange="if((to_numeric(jQuery('#input_radio_quantity').val()) - to_numeric(jQuery('#input_radio_promotion').val()) ) < to_numeric(jQuery('#input_radio_return').val())){ alert('So luong tra lai lon hon so luong Order');jQuery('#input_radio_return').val(0); }" value="" style="width:100px;" class="text-right" onblur="checkReturnQuantity(this);"/></td>
                                </tr>
                                <tr id="tr_cancel" style="display:none;">
                                    <td><input  name="radio_number" type="radio" id="radio_cancel" value="radio_cancel" class="radio_number" lang="quantity"/><label for="radio_cancel">[[.cancel.]]</label>:</td>
                                    <td><input  name="quantity_cancel" type="text" id="input_radio_cancel" border="0" style="width:100px;" onclick="$('radio_cancel').checked=true;" class="text-right" /></td>
                                </tr>                        
                                <tr id="tr_percentage">
                                    <td><input  name="radio_number" type="radio" id="radio_percentage" value="radio_percentage" class="radio_number" lang="percentage"/><label for="radio_percentage">[[.discount.]](%)</label>:</td>
                                    <td><input  name="percentage" type="text" id="input_radio_percentage" onclick="$('radio_percentage').checked=true;" class="text-right" style="width:100px;" title="Discount for product" value="" oninput="if(this.value>100) this.value=100;" />
                                    <input name="discount_category" type="text" id="discount_category" value="0"  style="width:25px; display:none;" class="text-right" title="Discount by category" /></td>
                                </tr>
                                <tr style="height:50px;" id="tr_selected">
                                </tr>
                            </table>
                        </td>
                   </tr>
            </table>
        </div>
        <div id="set_menu_content" class="web-dialog" style="width: 550px; padding: 10px;"><div class="info"><span style="text-align:center; font-size:14px; color:#6D84B4;line-height:35px;" id="title_select_quantity"><b>[[.edit_quantity_for_set_menu.]]</b></span>
            <img width="30" height="25" id="btnClose" src="packages/core/skins/default/images/buttons/delete.gif" onclick="HideDialog('set_menu_content');" style="float:right;"/></div>
            <table border="1" cellpadding="5">
                <thead>
                    <th>[[.stt.]]</th>
                    <th>[[.product_id.]]</th>
                    <th>[[.product_name.]]</th>
                    <th style="width: 20px;">[[.SL.]]</th>
                </thead>
                <tbody id="show_product_info">
                </tbody> 
            </table>
            <div id="button_total" style="margin-top: 20px;">
                <button name="button_ok" type="button" id="button_ok" set_menu_id="" onClick="reset_set_menu_quantity(this);" style="height:35px; width:80px;float:right; margin-left: 10px;">RESET</button>
                <button name="button_ok" type="button" id="button_ok" set_menu_id="" onClick="HideDialog('set_menu_content');update_set_menu_quantity(this);" style="height:35px; width:80px;float:right;">OK</button>              
            </div>
        </div>  
        <div id="set_menu_child" class="web-dialog" style="width: 550px; padding: 10px;"><div class="info"><span style="text-align:center; font-size:14px; color:#6D84B4;line-height:35px;" id="title_select_set_menu"><b>[[.edit_quantity_for_set_menu.]]</b></span>
            <img width="30" height="25" id="btnClose" src="packages/core/skins/default/images/buttons/delete.gif" onclick="HideDialog('set_menu_child');" style="float:right;"/></div>
            <div id="show_set_menu_child">
            </div>
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
        <div id="group_table_select" style="display: none; width: 100%; height: 100%; position: fixed; background: rgba(0,0,0,0.9); top: 0px; left: 0px;">
            <div style="width: 960px; height: 550px; z-index: 1; background: #eceff1; margin: 50px auto; border: 1px solid #555555; box-shadow: 0px 0px 3px #000000; border-bottom: 3px solid #34495e; border-top: 3px solid #34495e;">
                <table cellpadding="5" style="width: 100%; border-bottom: 1px solid #EEEEEE;">
                    <tr>
                        <td colspan="2">
                            <h3>[[.order_info.]]</h3>
                        </td>
                        <td style="text-align: right;"><img src="packages/core/skins/default/images/buttons/delete.gif" onclick="jQuery('#group_table_select').css('display','none');" /></td>
                    </tr>
                    <tr>
                        <td><h3>[[.table_action.]]</h3></td>
                        <td colspan="2"><h3>[[.select_table_for_group.]]</h3></td>
                    </tr>
                    <tr>
                        <td>
                            <div id="show_table" style="width: 380px; height: 330px; overflow: auto;">
                            </div> 
                        </td>
                        <td colspan="2">
                            <div style="width: 550px; height: 330px; overflow: auto; border: 1px solid #DDDDDD; box-shadow: inset 0px 0px 3px #000000;">
                                <table style="width: 100%; margin: 10px auto;">
                                    <!--LIST:floor_full-->        
                                    <tr style="border-bottom: 1px solid #DDDDDD;">
                                        <td style="width: 80px;" valign="top"><b>[[|floor_full.name|]]</b></td>
                                        <td> 
                                            <!--LIST:floor_full.bar_tables-->
                                            <?php //if(([[=floor_full.bar_tables.bar_reservation_id=]]=='' OR ([[=floor_full.bar_tables.bar_reservation_id=]]!='' AND [[=floor_full.bar_tables.status=]]=='BOOKED') OR (Url::get('cmd')=='edit' AND [[=floor_full.bar_tables.bar_reservation_id=]]==Url::get('id')))){ ?>
                                            <div <?php if( (Url::get('cmd')=='edit' AND [[=floor_full.bar_tables.bar_reservation_id=]]==Url::get('id')) OR ([[=floor_full.bar_tables.id=]]==Url::get('table_id') AND Url::get('cmd')=='add') ){ ?> class="trueselect" <?php }else{ ?> class="falseselect" <?php } ?> onclick="selectgrouptable('[[|floor_full.bar_tables.id|]]');" style="width: 60px; height: 60px; padding: 5px; cursor: pointer; text-align: center; margin: 5px 5px 5px 5px; float: left; border: 1px solid #EEEEEE;box-shadow: 0 2px 2px 0 rgba(0, 0, 0, .05), 0 1px 4px 0 rgba(0, 0, 0, .08), 0 3px 1px -2px rgba(0, 0, 0, .2);">
                                                <input type="checkbox" name="selectgrouptable[[[|floor_full.bar_tables.id|]]][id]" id="selectgroup_[[|floor_full.bar_tables.id|]]" title="[[|floor_full.bar_tables.name|]]" lang="[[|floor_full.bar_tables.id|]]" value="<?php if(Url::get('cmd')=='edit' AND [[=floor_full.bar_tables.bar_reservation_id=]]==Url::get('id')){ ?>[[|floor_full.bar_tables.bar_reservation_table_id|]]<?php } ?>" class="checkitemsselecttable" <?php if( (Url::get('cmd')=='edit' AND [[=floor_full.bar_tables.bar_reservation_id=]]==Url::get('id')) OR ([[=floor_full.bar_tables.id=]]==Url::get('table_id') AND Url::get('cmd')=='add') ){ ?> checked="checked" <?php } ?> style="display: none;" />
                                                <span style="font-size: 11px;">[[|floor_full.bar_tables.name|]]</span>
                                            </div>
                                            <?php //} ?>
                                            <!--/LIST:floor_full.bar_tables-->
                                        </td>
                                    </tr>        
                                    <!--/LIST:floor_full-->    
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </form>
</div>
<div id="mice_loading" style="width: 100%; height: 100%; position: fixed; top: 0px; left: 0px; text-align: center; background: rgba(255,255,255,0.95); display: none;">
    <img src="packages/hotel/packages/mice/skins/img/loading.gif" style="margin: 100px auto; height: 100px; width: auto;" />
</div>
<div id="mice_light_box" style="width: 100%; height: 100%; position: fixed; top: 0px; left: 0px; text-align: center; background: rgba(0,0,0,0.8); display: none;">
    <div style="width: 720px; height: 450px; background: #FFFFFF; padding: 10px; margin: 50px auto; position: relative; box-shadow: 0px 0px 3px #171717;">
        <div onclick="jQuery('#mice_light_box').css('display','none');" style="width: 20px; height: 20px; border: 2px solid #000000; color: #171717; text-transform: uppercase; line-height: 20px; text-align: center; position: absolute; top: 10px; right: 10px; cursor: pointer;">X</div>
        <div style="width: 500px; height: 22px; color: #171717; text-transform: uppercase; line-height: 22px; position: absolute; text-align: left; top: 10px; left: 10px; cursor: pointer;">Light Box MICE</div>
        <div id="mice_light_box_content" style="width: 700px; height: 400px; overflow: auto; margin: 40px auto 0px; border: 1px solid #EEEEEE;">
            
        </div>
    </div>
</div>
<script> 
    <?php
        if(USE_DISPLAY && USE_DISPLAY==1){
    ?>
        var socket = io.connect('http://<?php echo IP_NODEJS_SERVER; ?>:<?php echo PORT_NODEJS_SERVER; ?>'); // Thanh add -- K?t n?i server Websocket
    <?php        
        }
    ?>
    var amount_package = to_numeric([[|amount_package|]]);
    var count_click = 0;
    var order_id = '<?php echo (Url::get('id')?Url::get('id'):'')?>';
    var can_delete = '<?php echo User::can_delete(false,ANY_CATEGORY)?>';
    var can_add = '<?php echo User::can_add(false,ANY_CATEGORY)?>';
    var can_edit = '<?php echo User::can_edit(false,ANY_CATEGORY)?>';
    var can_admin_bar = '<?php echo User::can_admin(false,ANY_CATEGORY)?>';
    <?php 
    $check_edit_print_invoice = 0;
    if(Url::get('cmd')=='edit')
    {
        $check_edit = DB::fetch('select printed_tmp_bill from bar_reservation where id='.Url::get('id'),'printed_tmp_bill');
        if($check_edit==1 && !User::can_admin(false,ANY_CATEGORY))
            $check_edit_print_invoice=1;
    }
           
    ?>
    var check_print_invoice = '<?php echo $check_edit_print_invoice ?>';
    var count = 0;
    units=[[|units|]];
    var check_selected = 0;
    bar_id = <?php echo (Session::get('bar_id')?Session::get('bar_id'):'0');?>;
    paging(25);
    SetCss();
    GetStatus();
    product_list = [[|product_list_js|]];
    //console.log(product_list);
    table_list = [[|table_list_js|]];
    var flag2 = 0;
    var flag = 0;
    var sf=0;
    var full_rate = to_numeric(jQuery('#input_full_rate').val());
    var full_charge = to_numeric(jQuery('#input_full_charge').val());
    var discount_after_tax = to_numeric(jQuery('#input_discount_after_tax').val());
    for(var j in product_list)
    {
        if(product_list[j]['product_id'] == 'FOUTSIDE' || product_list[j]['product_id'] == 'DOUTSIDE' || product_list[j]['product_id'] == 'SOUTSIDE'){
            if(count<product_list[j]['product_sign']){
                count=product_list[j]['product_sign'];
            }
        }
        if(product_list[j]['stt']!=""){
        sf += 1;
        }
		
	}
    //console.log(sf);
    if(sf!=0){
    sort_product_list = product_list;
    console.log(sort_product_list);
    for(var k=1;k<=sf;k++){
        for(var l in sort_product_list){
            if(sort_product_list[l]['stt']==k){
                //console.log(sort_product_list[l]);
                drawItems(sort_product_list[l],"edit");
            }
        }
    }
    }else{
       for(var j in product_list){
        drawItems(product_list[j],"edit");
       } 
    }
    for(var p in table_list){
        drawTableList(table_list[p]);    
    }
    GetTotalPayment();
    jQuery('#deposit_date').datepicker();
    jQuery("#arrival_date").datepicker({minDate:0});
    jQuery("#departure_date").datepicker({minDate:0});
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
        jQuery('.menu_extra_li, .menu_extra_li_extra').click(function(e){
            e.stopPropagation(); //giap.luunguyen reset event click conflic
            var can_action = 0;
            jQuery('.ids_selected').each(function(){
                var id_selected=jQuery(this).attr('lang');
                if(jQuery(this).attr('checked')== true || jQuery(this).attr('checked')== 'checked'){
                    
                    can_action = 1;    
                }
            });
            if(jQuery(this).attr('lang')=='delete' && jQuery('#number_selected').val()==1){// TH SL mon deu=1, ko hien thi ma tru luon
            }else{
                //giap.luunguyen : choose button li => show 
                jQuery('#li_discount').click(function(){
                    var box = jQuery('#select_number');
                    box.show(); 
                    return false;
                });
                if(can_action && (jQuery(this).attr('id')!='li_edit' && jQuery(this).attr('id')!='li_summary')){ 
                   //giap.luunguyen comment: OLd: jQuery(this).attr('id')=='li_discount' ||   
                    var box = jQuery('#select_number');
                    box.show();
                    jQuery('#dialog').hide(); 
                    jQuery('#bound_summary').hide();
                    return false;
                }
                jQuery('#li_extra').click(function(){
                    var box = jQuery('#select_outside');
                    box.show(); return false;
                });
                
                jQuery('#li_edit').click(function(){
                    var box = jQuery('#bound_summary');
                    box.hide();
                    jQuery('#select_number').hide(); 
                    jQuery('#select_outside').hide();
                    return false;
                });
                
                jQuery('#li_summary').click(function(){
                    var box = jQuery('#dialog');
                    box.hide(); 
                    jQuery('#select_number').hide();
                    jQuery('#select_outside').hide();
                    return false;
                });
               //end giap.luunguyen 
            }
            
        });
         
        
        SetCss();    
        category_action();    
        /** Daund:  thêm nút giảm giá trước & sau thuế phí */
        discount_after_tax = [[|discount_after_tax|]];
        if(to_numeric(discount_after_tax) == 1)
        {
            jQuery('#discount_after_tax').attr('checked', true);
        }    
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
                        //if(jQuery('#alert').html() == null){
                            flag = 1;
                            jQuery(".body").html(html);
                        //}else{
                            //jQuery("#post_show").css('display','block');
                        //}
                        
                    }
                });
            })
        });    
        jQuery(".category-set-menu").click(function(){
                 var bar_id = jQuery('#bar_id_other').val();
                jQuery.ajax({
                    url:"form.php?block_id=<?php echo Module::block_id();?>&bar_id=<?php echo Session::get('bar_id');?>",
                    type:"POST",
                    data:{category_id:'set_menu',bar_id_other:bar_id,act:'product',cmd:'draw_products',type:'set_menu'},
                    success:function(html){       
                        //if(jQuery('#alert').html() == null){
                            flag = 1;
                            jQuery(".body").html(html);
                        //}else{
                        //    jQuery("#post_show").css('display','block');
                        //}
                        
                    }
                });
        });
    }
    function GetStatus(){
        var status = '[[|status|]]';    
        if(status == 'CHECKIN'){
            jQuery('#checkin').css("display","none");
            jQuery('#save').css('display','');
            jQuery('#booked').attr("name","checkout");
            jQuery('#booked').removeClass('w3-blue');
            jQuery('#booked').removeClass('w3-hover-blue');
            jQuery('#booked').addClass('w3-pink');
            jQuery('#booked').addClass('w3-hover-pink');
            jQuery('#booked').val('[[.PRINT_&_CHECKOUT.]]');
        }else if(status == 'BOOKED' || status == 'RESERVATION'){
            jQuery('#dynamic_save_button_bound').html('<input name="cancel" type="button" value="[[.table_cancel.]]" onclick="SubmitForm(\'cancel\');" class="w3-button w3-border w3-dark-grey w3-hover-dark-grey" style="border: 3px solid #FFF!important; box-shadow: 0px 1px 3px 1px rgba(60,64,67,.15); text-transform: uppercase;" id="cancel" >');
            jQuery('#booked').css('display','none');
            jQuery('#save').css('display','');
        }else if(status == 'CHECKOUT'){
            jQuery('#checkin').css("display","none");
            jQuery('#save').css('display','');
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
     //   jQuery("#dialog_keyboard").fadeIn(300);
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
        //if(jQuery('#input_product_name').val()=='' && act=='product')
//        {
//            console.log(111);
//            var product = '<ul id="bound_product_list" style="display:none;">';
//            for(var i in product_array)
//            {
//                product += '<li id="product_'+product_array[i]['id']+'" class="product-list" title="'+product_array[i]['name']+'" onclick="SelectedItems(\''+product_array[i]['id']+'\',0);"><span class="product-name">'+product_array[i]['name']+'</span><br>'+number_format(product_array[i]['price'])+'<input name="items" type="hidden" id="items_'+product_array[i]['id']+'" /></li>';
//            }
//            product += '</ul>';
//            console.log(product);
//            jQuery('.body').html('');
//            jQuery('.body').html(product);
//        }
//        else
//        {
            
            jQuery('.body').html('');
            get_bar_id = <?php echo Url::get('bar_id');?>;
            if(bar_id == ''){
                bar_id = jQuery('#bar_id_other').val();    
            }
            jQuery.ajax({
                url:"form.php?block_id=<?php echo Module::block_id();?>&client=1",
                type:"POST",            
                data:{product_name:jQuery("#input_product_name").val(),act:act,bar_id_other:bar_id,bar_id:<?php echo Url::get('bar_id');?>,cmd:'draw_products',type:'PRODUCT'},
                success:function(html){
                    if(jQuery('#alert').html() == null || jQuery('#alert').html() == ''){
                        flag = 1;
                        jQuery(".body").html(html);
                       //console.log(html);
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
        //}
    }
    function selectgrouptable(table_id)
    {
        if(jQuery("#selectgroup_"+table_id).attr('checked')=='checked')
        {
            jQuery("#selectgroup_"+table_id).removeAttr('checked');
            jQuery("#selectgroup_"+table_id).parent().removeClass('trueselect');
            jQuery("#selectgroup_"+table_id).parent().addClass('falseselect');
        }
        else
        {
            jQuery("#selectgroup_"+table_id).attr('checked','checked');
            jQuery("#selectgroup_"+table_id).parent().removeClass('falseselect');
            jQuery("#selectgroup_"+table_id).parent().addClass('trueselect');
        }
        $ListTable = '';
        $CountTable = 1;
        jQuery(".checkitemsselecttable").each(function(){
            table_id = this.id;
            if(jQuery("#"+table_id).attr('checked')=='checked')
            {
                $CountTable++;
                if($ListTable=='')
                {
                    $ListTable = jQuery("#"+table_id).attr('title');
                }
                else
                {
                    if($CountTable>=15)
                    {
                        $CountTable=1;
                        $ListTable += ',<br/> '+jQuery("#"+table_id).attr('title');
                    }
                    else
                        $ListTable += ', '+jQuery("#"+table_id).attr('title');
                }
            }
        });
        jQuery("#table_name").html($ListTable);
        //console.log($ListTable);
    }
    function check_printed(id){
        var input_radio_quantity = to_numeric(jQuery("#input_radio_quantity").val());
        var quantity = 0;
        var printed = 0;
        var check=true;
        jQuery('.ids_selected').each(function(){
		var id_selected=jQuery(this).attr('lang');
    		if(jQuery(this).attr('checked')== true || jQuery(this).attr('checked')== 'checked'){ 
                quantity = to_numeric(jQuery("#quantity_"+id_selected).val());
                printed = to_numeric(jQuery("#printed_"+id_selected).val());
                if((quantity-printed)==0){
                    check=false;
                }
    		}
    	});
        if(check==false){
            alert("Tất cả sản phẩm đã in, không được sửa!");
            jQuery("#input_radio_quantity").val(printed);
        }else{
            if(input_radio_quantity<printed){
               alert(printed+" Sản phẩm đã in, không được sửa!"); 
               jQuery("#input_radio_quantity").val(quantity); 
            }
        }
    }
    function SelectedItems(key,pass,type)
    {
       if(!type) {
            type = 'default';
       } 
       /** Thanh add phan hien thi set menu **/ 
       if(type=="set")
       {
          get_set_menu_child(key);
       }
       /** END **/
       else{ 
        var categories_discount = [[|categories_discount_js|]];
        var discount_pct = 0;
        price_id = key;
        var id_temp = key.split('-');
        if((pass==1 || pass==2) && $('product_id_'+key) && ($('product_id_'+key).value == 'SOUTSIDE' || $('product_id_'+key).value == 'FOUTSIDE' || $('product_id_'+key).value == 'DOUTSIDE')){
            price_id = key.substr(0,key.lastIndexOf('_'));
            
            
        }
        // Dm mang product_array nay lay trong file cache ( file cache nay dc tao trong quan ly gia sp)
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
                    var service_rate = to_numeric(jQuery('#service_charge').val());//vao day    
                }
                
                if(jQuery('#tax_rate').val()=='')
                {
                    var tax_rate = 0;
                    
                }
                else
                {
                    var tax_rate = to_numeric(jQuery('#tax_rate').val()); //vao day   
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
                        
                        if(jQuery('#complete_'+key).val() != undefined && pass==1){
                            jQuery('#input_radio_complete_quantity').val(to_numeric(jQuery('#complete_'+key).val()));
                        }else{jQuery('#input_radio_complete_quantity').val('0');    }
                        
                        if(jQuery('#promotion_'+key).val() != undefined && pass==1)
                        {
                            //start: KID thay dong (1) thanh dong (2) de hie nthi duoc so thap phan o phan giam gia so luong
                                //jQuery('#input_radio_promotion').val(jQuery('#promotion_'+key).val()); (1)
                                jQuery('#input_radio_promotion').val((to_numeric(jQuery('#promotion_'+key).val())).toPrecision());//(2)
                            //end: KID thay dong (1) thanh dong (2) de hie nthi duoc so thap phan o phan giam gia so luong
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
                        
                        if(jQuery("#quantity_"+key).attr("action_type")=="add")
                        {
                            jQuery("#input_radio_return").attr("readonly","readonly");
                            jQuery("#input_radio_return").addClass("readonly");
                        }
                        else if(jQuery("#quantity_"+key).attr("action_type")=="edit")
                        {
                            if(jQuery("#quantity_"+key).attr("original_quantity")!=jQuery("#quantity_"+key).val())
                            {
                                jQuery("#input_radio_return").attr("readonly","readonly");
                                jQuery("#input_radio_return").addClass("readonly");
                            }
                            else
                            {
                               jQuery("#input_radio_return").removeAttr("readonly");
                               jQuery("#input_radio_return").removeClass("readonly");
                            }
                        }
                        jQuery("#dialog").fadeIn(300);
                        
                        
                        //jQuery('#input_radio_return').ForceNumericOnly();
                        //jQuery('#input_radio_quantity').ForceNumericOnly();
                        jQuery('#input_radio_cancel').ForceNumericOnly();
                        //jQuery('#input_radio_promotion').ForceNumericOnly();
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
                        jQuery("#select_number").fadeOut(300);
                        
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
                        jQuery("input[id^=SET_"+id_temp[0]+"]").each(function(){
                            var this_value = to_numeric(jQuery(this).val());
                            var original_value = to_numeric(jQuery(this).attr('original_value'));
                            var new_value = this_value+original_value;
                            jQuery(this).val(new_value);
                        });
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
                        arr_product['price_id'] = product_array[j]['price_id'];
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
                        arr_product['complete'] = 0;
                        arr_product['amount'] = number_format(net_price);
                        arr_product['remain'] = 0;
                        arr_product['printed'] = 0;
                        arr_product['note'] = '';
                        arr_product['chair_number'] = '';//trung add: neu ko co mang chair_number thi cho bang rong
                        arr_product['quantity_cancel'] = 0;
                        if($('bar_id_other') && jQuery('#bar_id_other').val() != ''){
                            arr_product['bar_id'] = jQuery('#bar_id_other').val();
                        }
                        if(key=='DOUTSIDE' || key=='FOUTSIDE' || key=='SOUTSIDE'){
                            arr_product['id'] = product_array[j]['id']+'_'+product_array[j]['product_id']+'_'+count;
                            arr_product['name'] = jQuery('#name_outside').val();
                            arr_product['price'] = jQuery('#price_outside').val();
                            arr_product['quantity'] = jQuery('#quantity_outside').val();    
                        }
                        drawItems(arr_product,"add");
                        amount = net_price;
                        jQuery("#total_amount").val(number_format(to_numeric(jQuery("#total_amount").val()) + amount));
                        //jQuery('#loading-layer').fadeOut(200);
                    }
                    GetTotalPayment();
                }
                
                myAutocomplete1();
            }
         }
       } 
       
    }
    function NumberSelected(key,act,product_code)
    {  
        flag2 = 0;
        if(jQuery('#name').val() == '' /** || jQuery('#input_radio_price').val()==0 **/ || jQuery('#input_radio_price').val()=='')
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
                    if(jQuery("#input_radio_return").val()!=0 && jQuery("#input_radio_return").val().trim()!="")
                    {
                        if(jQuery("input[id=quantity_"+key+"][haseditedquantity]").length!=0)
                        {
                            alert("Số lượng món đã bị thay đổi so với ban đầu. Bạn phải ghi lại trước khi thực hiện thao tác trả lại món!");
                            return false;
                        }
                    }
                    
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
                        newdiscount = to_numeric(jQuery('#discount_category').val());
                        newCancel = parseFloat(jQuery('#input_radio_cancel').val()*1000);
                        newPrice = to_numeric(jQuery('#input_radio_price').val());
                        newReturn = parseFloat(jQuery('#input_radio_return').val()*1000);
                        newQuantity = parseFloat((parseFloat(jQuery('#input_radio_quantity').val()*1000) -  newReturn - newCancel)/1000);
                        id_temp = key.split('-');
                        jQuery("input[id^=SET_"+id_temp[0]+"]").each(function(){
                            var this_value = to_numeric(jQuery(this).val());
                            var original_value = to_numeric(jQuery(this).attr('original_value'));
                            var new_value = (this_value-(original_value*oldQuantity))+(newQuantity*original_value);
                            jQuery(this).val(new_value);
                        });
                        submitNote(jQuery('#note-dialog').val(),key);
                        jQuery("#price_"+key).val(newPrice);                         
                        jQuery("#remain_"+key).val(oldRemain+newReturn/1000); 
                        jQuery("#quantity_"+key).val(newQuantity);
                        jQuery("#promotion_"+key).val(newPromotion);
                        jQuery("#percentage_"+key).val(newPercentage);
                        jQuery("#quantity_cancel_"+key).val(oldCancel+newCancel/1000);
                       
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
                    if(product_code=='DOUTSIDE' || product_code=='FOUTSIDE' || product_code=='SOUTSIDE')
                    {
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
                            //console.log(drawItems);
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
    function drawTableList(arr){ 
        <?php if([[=close_mice=]]==0){ ?>
        var can_delete = '<?php echo User::can_delete(false,ANY_CATEGORY)?>';
        var items = '<table cellpadding="0" cellspacing=0 width="75%" id="table_list_'+arr['id']+'" class="selected-table-list" title="'+arr['name']+'" style="float:left;">';
        items += '<tr id="item_table_'+arr['id']+'">'; //jQuery(target).attr('class')
        items += '<td width=""><input name="table_list['+arr['id']+'][name]" type="text" value="'+arr['name']+'" id="table_name_'+arr['id']+'" style=" border:none; width:55px;" readonly="readonly" maxlength="200"></td>';
        items += '<td width="" style="display:none;"><input name="table_list['+arr['id']+'][table_id]" type="text" value="'+arr['id']+'" id="table_id_'+arr['id']+'" style=" border:none; width:55px;" readonly="readonly" maxlength="200"></td>';
        items += '<td width=""><input name="split_table" type="button" value="[[.split_table_button.]]" id="split_table" style="height:30px; width:73px;" <?php if(Url::get('cmd')=='edit' and Url::get('id')){?>onClick="window.open(\'?page=split_table&from_code=<?php echo Url::get('id');?>&type=split\')"<?php } ?>></td>';
        items += '<td width=""><input name="tranfer_dish" type="button" value="[[.transfer_dish.]]" id="tranfer_dish" style="height:30px; width:73px;" <?php if(Url::get('cmd')=='edit' and Url::get('id')){?>onclick="openWindowUrl(\'form.php?block_id=915&cmd=deposit&from_code=<?php if(Url::get('id')){echo Url::get('id');}else{echo '';}?>&type=split\',Array(\'split\',\'transfer_for\',80,210,950,500));"<?php } ?>></td>';
        //items += '<td width=""><input name="coupling_table" type="button" value="[[.coupling_table.]]" id="coupling_table" style="height:40px; width:73px;" <?php if(Url::get('cmd')=='edit' and Url::get('id')){?>onClick="window.open(\'?page=split_table&from_code=<?php echo Url::get('id');?>\')"<?php } ?>></td>';
        items += '<td width=""><a href="<?php if(Url::get('cmd')=='edit' and Url::get('id')){?>?page=split_table&id=<?php echo  Url::get('id');?>&table_id=<?php echo Url::get('table_id'); } ?>"><input name="coupling_table" type="button" value="[[.coupling_table.]]" id="coupling_table" style="height:30px; width:73px;" /></a></td>'
        //items += '<td width="1%"><input name="select_table" type="button" id="select_table" value="[[.change_table.]]" style="height:30px; width:73px;" onclick="jQuery(\'#table_map\').css(\'display\',\'block\');jQuery(\'#table_change\').val(\''+arr['id']+'\');" /></td>';
        //items += '<td width="17" colspan="2"></td>';    
        items += '</tr></table>';    
        jQuery('#show_table').append(items);
        <?php } ?>
    }
    //Kimtan:them text-transform: capitalize;
    function drawItems(arr,type){
        if(!type){
            type = "";
        }
        console.log(arr);
        var temp_brp_id = arr['brp_id'].split("-");
        arr['brp_id'] = temp_brp_id[0];
        var id_temp = arr['id'].split('-');
        var items = '<table cellpadding="0" cellspacing=0 width="100%" id="table_'+arr['id']+'" class="selected-foot-and-drink-table" title="'+arr['note']+'" lang="'+arr['id']+'">';
        items += '<tr class="foot-drink-tr" id="item_detail_'+arr['id']+'" onclick="if((jQuery(\'#status\').val()==\'CHECKOUT\' && can_admin_bar) || jQuery(\'#status\').val()!=\'CHECKOUT\'){if(jQuery(\'#selected_'+arr['id']+'\').attr(\'checked\')==true || jQuery(\'#selected_'+arr['id']+'\').attr(\'checked\')==\'checked\'){jQuery(\'#selected_'+arr['id']+'\').attr(\'checked\',false);}else{jQuery(\'#selected_'+arr['id']+'\').attr(\'checked\',true); }CssSelected();}">';
        items += '<td width="70" class="foot-drink-td"><input name="product_list['+arr['id']+'][name]" type="text" value="'+arr['name']+'" id="name_'+arr['id']+'" style=" border:none; width:70px; text-transform: capitalize;" readonly="readonly" maxlength="200" ></td>';
        items += '<td width="25" class="foot-drink-td"><input name="product_list['+arr['id']+'][quantity]" action_type="'+type+'" original_quantity="'+number_format(arr['quantity'],2)+'" type="text" value="'+number_format(arr['quantity'],2)+'" id="quantity_'+arr['id']+'" style=" border:none; width:20px;text-align:center;" readonly="readonly" maxlength="200"></td>';
        items += '<td width="25" style="display:none;"><input name="product_list['+arr['id']+'][promotion]" type="text" value="'+arr['promotion']+'" id="promotion_'+arr['id']+'"  style=" border:none; width:25px;" readonly="readonly" maxlength="200"></td>';
        items += '<td width="25" style="display:none;"><input name="product_list['+arr['id']+'][percentage]" type="text" value="'+arr['percentage']+'" id="percentage_'+arr['id']+'" style=" border:none; width:20px;" readonly="readonly" maxlength="200"></td>';
        items += '<td width="25" style="display:none;"><input name="product_list['+arr['id']+'][discount_category]" type="text" value="'+arr['discount_category']+'" id="discount_category_'+arr['id']+'" style=" border:none; width:20px;" readonly="readonly" maxlength="200"></td>';
        items += '<td width="65" style="display:none;" align="right"><input name="product_list['+arr['id']+'][price]" type="text" value="'+number_format(arr['price'])+'" id="price_'+arr['id']+'" style=" border:none; width:60px;text-align:right;" readonly="readonly" maxlength="200"></td>';
        items += '<td width="60" align="right"><input name="product_list['+arr['id']+'][amount]" type="text" value="'+number_format(arr['amount'])+'" id="amount_'+arr['id']+'" style=" border:none; width:60px;text-align:right;" readonly="readonly" maxlength="200"></td>';
        items += '<td width="20" align="center" style="display:none;"><input name="product_list['+arr['id']+'][printed]" type="text" value="'+arr['printed']+'" id="printed_'+arr['id']+'" style=" border:none; width:20px;" readonly="readonly" maxlength="200"></td>';
        
        
        if(arr['unit']=='set')
        {
            items += '<td width="20" class="foot-drink-td keyboard" onclick="get_set_menu_product('+id_temp[0]+');" align="center"><img src="packages/hotel/skins/default/images/iosstyle/icon-note-1.png" class="keyboard" ></td>';
        }
        else{
            items += '<td width="20" class="foot-drink-td keyboard" align="center"></td>';
        }
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
        items += '<td align="center" style="display:none;"><input name="product_list['+arr['id']+'][complete]" type="text" value="'+arr['complete']+'" id="complete_'+arr['id']+'" style=" border:none; width:25px;" readonly="readonly" maxlength="200"></td>';   
        
        //trung add: truong ten so ghe an vao layout
        //items += '<td width="20" class="foot-drink-td"><input name="product_list['+arr['id']+'][chair_number]" type="text" value="'+arr['chair_number']+'" id="chair_'+arr['id']+'" style=" border:none; width:20px;text-align:center;"  maxlength="200" title="'+arr['chair_number']+'" ></td>'; 
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
        
        if(arr['unit']=='set'){
            var id = id_temp[0];
            var url = "get_product.php";
            var bar_reservation_id = "<?php if(Url::get('id')) echo Url::get('id'); ?>";
            <?php
                if(Url::get('id')){                        
            ?> 
            if(arr['brp_id']>0){
            jQuery.ajax({
                url : url,
                data : {'id':id,'bar_reservation_id':bar_reservation_id,'q':'1','set_menu':'1'},
                dataType:'JSON',
                type:'POST',
                success :function(data){
                    for(var i in data){
                        items += '<td style="display:none;"><input id="SET_'+id+'-'+data[i]["product_id"].trim()+'" original_code="'+data[i]['product_id'].trim()+'" original_name="'+data[i]['product_name']+'" original_value="'+data[i]["original_quantity"]+'" name="set_menu_list['+id+']['+i+'][quantity]" value="'+data[i]["quantity"]+'"/></td>';
                    }
                    items += '</tr></table>'; 
                    jQuery('#show_detail').append(items);   
                    GetTotalPayment();  
                    if(check_reload==1)
                    {
                        var obj = new obj('save','save');
                        checkSubmit(obj);
                    } 
                }
            });
            }
            else{
                    jQuery.ajax({
                        url : url,
                        data : {'id':id,'q':'1','set_menu':'1'},
                        dataType:'JSON',
                        type:'POST',
                        success :function(data){
                            for(var i in data){
                                items += '<td style="display:none;"><input id="SET_'+id+'-'+data[i]["product_id"].trim()+'" original_code="'+data[i]['product_id'].trim()+'" original_name="'+data[i]['product_name']+'" original_value="'+data[i]["original_quantity"]+'" name="set_menu_list['+id+']['+i+'][quantity]" value="'+data[i]["quantity"]+'"/></td>';
                            }
                            items += '</tr></table>'; 
                            jQuery('#show_detail').append(items); 
                            GetTotalPayment();
                            if(check_reload==1)
                            {
                                var obj = new obj('save','save');
                                checkSubmit(obj);
                            }     
                        }
                    });
            }
            <?php
                }
                else
                {
            ?>
               jQuery.ajax({
                url : url,
                data : {'id':id,'q':'1','set_menu':'1'},
                dataType:'JSON',
                type:'POST',
                success :function(data){
                    for(var i in data){
                        items += '<td style="display:none;"><input id="SET_'+id+'-'+data[i]["product_id"].trim()+'" original_code="'+data[i]['product_id'].trim()+'" original_name="'+data[i]['product_name']+'" original_value="'+data[i]["original_quantity"]+'" name="set_menu_list['+id+']['+i+'][quantity]" value="'+data[i]["quantity"]+'"/></td>';
                    }
                    items += '</tr></table>'; 
                    jQuery('#show_detail').append(items); 
                    GetTotalPayment();
                    if(check_reload==1)
                    {
                        var obj = new obj('save','save');
                        checkSubmit(obj);
                    }     
                }
            });             
            <?php                                           
                }                                            
            ?>                        
        }
        else{
            items += '</tr></table>';
            //alert(items);    
            jQuery('#show_detail').append(items);
        }
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
    var last_time = [[|last_time|]];
    //start:KID them submit tu info
    function normal_submit()
    {
        <?php if(Url::get('cmd')=='edit'){ ?>
            if(jQuery('#discount_percent').val().trim() != '' || jQuery('#discount').val().trim() != '')
            {
                if(jQuery('#reason_discount').val().trim() == '')
                {
                    alert('Bạn chưa nhập lý do giảm giá. Xin vui lòng thử lại!');
                    getSummary();
                    jQuery('#reason_discount').css('background','yellow');
                    return false;
                }else
                {
                    jQuery('#reason_discount').css('background','');
                }
            }
            <?php echo 'var id_check = '.Url::get("id").';';?> 
            <?php echo 'var block_id = '.Module::block_id().';';?>
            jQuery.ajax({
        				url:"form.php?block_id="+block_id,
        				type:"POST",
                        dataType: "json",
        				data:{check_last_time:1,id:id_check,last_time:last_time},
        				success:function(html)
                        {
                            if(html['status']=='error')
                            {
                                alert('RealTime:\n Lưu ý, Hóa đơn nhà hàng này đã được tài khoản '+html['user']+' chỉnh sửa trước đó, vào lúc :'+html['time']+' \n Dữ liệu hiện tại của bạn chưa được cập nhập nội dung chỉnh sửa đó \n \n Để tiếp tục thao tác bạn vui lòng tải lại trang !')
                                return false;
                            }
                            else
                            {
                                <?php if([[=close_mice=]]==0){ ?>
                                	var status = '[[|status|]]';
                                	if(status != 'CHECKOUT' && status != '')
                                    {
                                		jQuery('#act').val('save');
                                        jQuery('#acction').val(1);
                                		TouchBarRestaurantForm.submit();
                                	}
                                <?php } ?>
                            }
        				}
       	});
        <?php }else{ ?>
            <?php if([[=close_mice=]]==0){ ?>
            	var status = '[[|status|]]';
            	if(status != 'CHECKOUT' && status != '')
                {
            		jQuery('#act').val('save');
                    jQuery('#acction').val(1);
            		TouchBarRestaurantForm.submit();
            	}
            <?php } ?>
        <?php } ?>
        
    }
    //end: KID them submit tu info
    function SubmitForm(act){
        <?php if(Url::get('cmd')=='edit'){ ?>
            <?php echo 'var id_check = '.Url::get("id").';';?> 
            <?php echo 'var block_id = '.Module::block_id().';';?>
            jQuery.ajax({
        				url:"form.php?block_id="+block_id,
        				type:"POST",
                        dataType: "json",
        				data:{check_last_time:1,id:id_check,last_time:last_time},
        				success:function(html)
                        {
                            if(html['status']=='error')
                            {
                                alert('RealTime:\n Lưu ý, Hóa đơn nhà hàng này đã được tài khoản '+html['user']+' chỉnh sửa trước đó, vào lúc :'+html['time']+' \n Dữ liệu hiện tại của bạn chưa được cập nhập nội dung chỉnh sửa đó \n \n Để tiếp tục thao tác bạn vui lòng tải lại trang !')
                                return false;
                            }
                            else
                            {
                                if(act)
                                    jQuery('#act').val(act);
        	                    TouchBarRestaurantForm.submit();
                            }
        				}
        	});
        <?php }else{ ?>
            if(act)
                jQuery('#act').val(act);
            TouchBarRestaurantForm.submit();
        <?php } ?>
    }
    function checkSubmit(obj){
        <?php if(Url::get('cmd')=='edit'){ ?>
            <?php echo 'var id_check = '.Url::get("id").';';?> 
            <?php echo 'var block_id = '.Module::block_id().';';?>
            jQuery.ajax({
        				url:"form.php?block_id="+block_id,
        				type:"POST",
                        dataType: "json",
        				data:{check_last_time:1,id:id_check,last_time:last_time},
        				success:function(html)
                        {
                            if(html['status']=='error')
                            {
                                alert('RealTime:\n Lưu ý, Hóa đơn nhà hàng này đã được tài khoản '+html['user']+' chỉnh sửa trước đó, vào lúc :'+html['time']+' \n Dữ liệu hiện tại của bạn chưa được cập nhập nội dung chỉnh sửa đó \n \n Để tiếp tục thao tác bạn vui lòng tải lại trang !')
                                return false;
                            }
                            else
                            {
                                count_click = count_click + 1;
                                if(count_click==1){
                                    var id = obj.id;
                                    var name = obj.name;
                                    var status = '[[|status|]]';
                                    if(status != 'CHECKOUT'){
                                        if(id=='checkin'){
                                            jQuery('#act').val('checkin');
                                            TouchBarRestaurantForm.submit();
                                        }
                                        else
                                        {
                                            jQuery('#act').val(name);
                                            if(id == 'save_stay'){
                                                jQuery('#acction').val(1);    
                                            }
                                            //Thanh realtime
                                                <?php if(USE_DISPLAY && USE_DISPLAY==1){
                                                ?>
                                                if(id=='booked'){
                                                    var arr_eating = [];
                                                    var p=0;
                                                    for(var key in product_list){
                                                        arr_eating[p] = {};
                                                        arr_eating[p]['id'] = product_list[key]['brp_id'];
                                                        p++;
                                                    }   
                                                    socket.emit('checkout',arr_eating); 
                                                }
                                                <?php    
                                                } ?>
                                             //end
                                            TouchBarRestaurantForm.submit();
                                        }
                                    }
                                }
                            }
        				}
        	       });
        <?php }else{ ?>
            count_click = count_click + 1;
            if(count_click==1){
                var id = obj.id;
                var name = obj.name;
                var status = '[[|status|]]';
                if(status != 'CHECKOUT'){
                    if(id=='checkin'){
                        jQuery('#act').val('checkin');
                        TouchBarRestaurantForm.submit();
                    }
                    else
                    {
                        jQuery('#act').val(name);
                        if(id == 'save_stay'){
                            jQuery('#acction').val(1);    
                        }
                        //Thanh realtime
                            <?php if(USE_DISPLAY && USE_DISPLAY==1){
                            ?>
                            if(id=='booked'){
                                var arr_eating = [];
                                var p=0;
                                for(var key in product_list){
                                    arr_eating[p] = {};
                                    arr_eating[p]['id'] = product_list[key]['brp_id'];
                                    p++;
                                }   
                                socket.emit('checkout',arr_eating); 
                            }
                            <?php    
                            } ?>
                         //end
                        TouchBarRestaurantForm.submit();
                    }
                }
            }
        <?php } ?>
    }
    function CheckDiscount(){
        if(jQuery("#discount").val() != ''){
            //console.log('1111');
            jQuery("#discount_percent").attr('readonly',true);
            jQuery("#discount_percent").val('');
        }
        else{
            jQuery("#discount_percent").attr('readonly',false);
            jQuery("#discount_percent").val()== '';
        }
    }
    function CheckdDiscountPercent(){
        //console.log('1111');
        if(jQuery("#discount_percent").val() != ''){
            //console.log('1111');
            jQuery("#discount").attr('readonly',true);
            jQuery("#discount").val('');
        }
        else{
            jQuery("#discount").attr('readonly',false);
            jQuery("#discount").val()== '';
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
                        alert("Mã thành viên không tồn tại!");
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
        alert("không có chương trình giảm giá cho thành viên này!");
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
                    alert("không có chương trình giảm giá cho thành viên này!");
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

function checkEditQuantity(obj){
    var complete = jQuery("#input_radio_complete_quantity").val();
    var value = jQuery(obj).val();
    value = to_numeric(value);
    if(value<complete){
      jQuery(obj).val(complete);  
    }
}

function checkReturnQuantity(obj){
    var return_quantity = jQuery(obj).val();
    var quantity = jQuery("input#input_radio_quantity").val();
    var complete = jQuery("input#input_radio_complete_quantity").val();
    if(return_quantity>(quantity-complete)){
        alert("So luong mon tra lai vuot qua so luong mon da che bien!");
        jQuery(obj).val("0");
    }
}



function fun_close_info(){
    jQuery("#div_info").css('display','none');
}
function close_window_fun(){
          location.reload();
          jQuery(".window-container").fadeOut();
    
}
function customerAutocomplete()
{
    jQuery("#customer_name").autocomplete({
             url:'get_customer_search.php?restaurant=1',
             onItemSelect: function(item) {
                document.getElementById("customer_id").value = item.data[0];
            }
        });
}
//End giap
//Kimtan: xu ly ko cho click nut payment trong th ban checkout va tk thuong
    <?php if([[=status=]]==''){?>
    jQuery('.menu_extra_li_order1').attr('onclick','');
	jQuery('.menu_extra_li_order1').css({'background':'#d6d6d6','cursor':'default'});
    <?php }?>
    <?php if([[=status=]]=='CHECKOUT'){
    ?>
    <?php if(!User::can_admin(false,ANY_CATEGORY)){?>
    jQuery('.menu_extra_li_order1').attr('onclick','');
	jQuery('.menu_extra_li_order1').css({'background':'#d6d6d6','cursor':'default'});
    <?php }}?>
    //END Kimtan: xu ly ko cho click nut payment trong th ban checkout va tk thuong
</script>
<script>
    // manh them de reload l?i table_map phuc vu cho viec ghep ban
    <?php if(Url::get('on_load')==1){ ?>
    check_reload = 1;
    <?php }else{ ?>
    check_reload = 0;
    <?php } ?>
    if(check_reload==1)
    {
        //var obj = new obj('save','save');
        //checkSubmit(obj);
    }
    function obj(id,name)
    {
    this.id=id;
    this.name=name;
    }
    function fun_to_numer()
    {
    }
</script>
<!--Thanh add phan ket noi socket-->
<?php 
    if(USE_DISPLAY && USE_DISPLAY==1 && isset([[=arr_product_js=]])){
?>
    <script src='http://<?php echo IP_NODEJS_SERVER; ?>:<?php echo PORT_NODEJS_SERVER; ?>/socket.io/socket.io.js'></script> 		  
    <script>
        var arr_eating = [[|arr_product_js|]];
        //console.log(arr_eating);
        var socket = io.connect('http://<?php echo IP_NODEJS_SERVER; ?>:<?php echo PORT_NODEJS_SERVER; ?>'); // Thanh add -- K?t n?i server Websocket
            socket.emit('call_eating',arr_eating); 
    </script>
<?php
    }
?>
<!--end-->
<!-- THANH add phan set menu -->
<script>
    function get_set_menu_child(price_id)
    {
        var url = "get_product.php";
            jQuery.ajax({
                url : url,
                data : {'q':'1','get_set_menu_child':price_id,'set_menu':1},
                dataType:'JSON',
                type:'POST',
                success :function(data){
                    var str = "<ul id='bound_product_list'>";
                    for(var k in data)
                    {
                       str+= '<li id="product_'+data[k]['id']+'" class="product-list" title="'+data[k]['name']+'" onclick="SelectedItems(\''+data[k]['id']+'\',\'0\');"><span class="product-name">'+data[k]['product_id']+"<br/>"+data[k]['name']+'</span><br>'+number_format(data[k]['total'])+'</li>'; 
                    }
                    str+='</ul>';
                    jQuery("#show_set_menu_child").html();
                    jQuery("#show_set_menu_child").html(str);
                }
            });
          HideDialog('set_menu_content');  
          HideDialog('set_menu_child');  
          jQuery("#set_menu_child").show();
    } 
    function get_set_menu_product(id){
            var url = "get_product.php";
            jQuery.ajax({
                url : url,
                data : {'id':id,'q':'1','set_menu':'1'},
                dataType:'JSON',
                type:'POST',
                success :function(data){
                    var content = "";
                    var j = 1;
                    for(var i in data){
                        var value = jQuery("input#SET_"+id+"-"+data[i]['product_id'].trim()).val();                      
                        content += "<tr id='"+j+"'>"
                                +       "<td>"+j+"</td>"
                                +       "<td>"+data[i]['product_id']+"</td>"
                                +       "<td style='width:358px;'>"+data[i]['product_name']+"</td>"
                                +       "<td style='width:25px;'><input style='text-align:right; width:25px;' set_id='SET_DETAIL_"+id+"-"+data[i]['product_id'].trim()+"' set_menu_id="+id+" set_menu_product_id="+data[i]['product_id'].trim()+" original_value="+data[i]['original_quantity']+" original_code='"+data[i]['product_id'].trim()+"' original_name='"+data[i]['product_name'].trim()+"' value="+value+" /></td>"
                                //+       "<td><img src='packages/core/skins/default/images/Replace-icon.png' style='width:20px; height:20px; cursor:pointer;' onclick='change_set_menu(this);' /></td>" 
                                +  "</tr>";   
                                j++;      
                    }
                    jQuery('#show_product_info').html(content);  
                    jQuery("button[set_menu_id]").each(function(){
                        jQuery(this).attr('set_menu_id',id);   
                    }); 
                }
            });
          HideDialog('set_menu_child');  
          HideDialog('set_menu_content');  
          jQuery("#set_menu_content").show(); 
    }
    function update_set_menu_quantity(obj,set_menu_id){
        if(!set_menu_id){
          var set_menu_id = jQuery(obj).attr('set_menu_id');
        }
        jQuery("input[set_menu_id="+set_menu_id+"]").each(function(){          
            var set_menu_product_id = jQuery(this).attr('set_menu_product_id');
            var set_menu = set_menu_id+"-"+set_menu_product_id;
            var value = jQuery(this).val();
            jQuery("input#"+"SET_"+set_menu).val(value);
        });
    }
    function reset_set_menu_quantity(obj){
        if(confirm('[[.are_you_sure.]]?')){
        var set_menu_id = jQuery(obj).attr('set_menu_id');
        var quantity = jQuery("input[id^=quantity_"+set_menu_id+"]").val();
        jQuery("input[set_menu_id="+set_menu_id+"]").each(function(){
            var original_quantity = jQuery(this).attr('original_value');
            var original_code = jQuery(this).attr('original_code');
            var original_name = jQuery(this).attr('original_name');
            
            //console.log(original_code);
            //console.log(original_name);
            jQuery(this).parent().parent().find("td:nth-child(2)").html(original_code);
            jQuery(this).parent().parent().find("td:nth-child(3)").html(original_name);
            jQuery(this).val(quantity*original_quantity);
        });
        }
    }
    //jQuery('[data-toggle="tooltip"]').tooltip();  
</script>
<!-- END -->
