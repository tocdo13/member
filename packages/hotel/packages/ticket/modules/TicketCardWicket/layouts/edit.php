﻿<?php 
    $loop = 0;
    $loop = rand(1,10);
    $_pol = "str_rand";
    $_SESSION['loop_2'] = $loop;
    for($i=1;$i<=$loop; $i++)
    {
        $_pol = md5($_pol);
    }
?>
<style>
    .shadow{
        -webkit-box-shadow: -12px 10px 19px -5px rgba(0,0,0,0.34);
        -moz-box-shadow: -12px 10px 19px -5px rgba(0,0,0,0.34);
        box-shadow: -12px 10px 19px -5px rgba(0,0,0,0.34);
        background: #F0F0F0;
    }
    .simple-layout-middle{
        width:100%;
    }
</style>
<script src="packages\hotel\modules\CardPaymentRecharge\CryptoJS v3.1.2\components\core-min.js"></script>
<script src="packages\hotel\modules\CardPaymentRecharge\CryptoJS v3.1.2\components\md5-min.js"></script>
<div class="row" style="margin: -15px;">
    <div class="container-fluid">
        <div class="panel panel-primary">
            <div class="panel-body" style="background-color: #FFFFDD;">
                <form name="EditTicketCardWicketForm" method="POST" id="EditTicketCardWicketForm_2" onsubmit="return checkForm();">
                    <div class="col-md-12">    
                        <?php
                           if(isset($_REQUEST['code'])){
                        ?>
                        <div class="col-md-1" style="border: red thin solid; height: 30px; padding:2px; text-align: center; background: red; border-radius: 5px;">
                             <p style="line-height: 24px; font-weight: bold;"><?php echo $_REQUEST['code']; ?></p> 
                             <div style="border: green 1px solid; border-radius: 5px; padding: 5px; background: green; color: white;">
                               [[|sales_name|]]
                             </div>
                             <div style="margin-top: 10px;">
                                <button type="button" title="Quay lại" class="btn btn-sm btn-primary" style="position:absolute; z-index:99999;border: green 1px solid; border-radius: 5px; padding: 5px; width:100px; left:3px;" onclick="if(confirm('Bạn có chắc chắn muốn quay lại không?')) location.href='?page=ticket_card_wicket';"><span class="glyphicon glyphicon-arrow-left" style="color: white; font-size:15px;"></span></button>
                             </div>
                        </div>    
                        <?php 
                            }else{
                        ?>
                            <div class="col-md-1" style="height: 30px; padding:2px;border-radius: 5px;">
                              <div style="border: green 1px solid; border-radius: 5px; padding: 5px; background: green; color: white;">
                               [[|sales_name|]]
                              </div>
                              <div style="margin-top: 10px;">
                                <button type="button" title="Quay lại" class="btn btn-sm btn-primary" style="position:absolute; z-index:99999;border: green 1px solid; border-radius: 5px; padding: 5px; width:100px; left:3px;" onclick="if(confirm('Bạn có chắc chắn muốn quay lại không?')) location.href='?page=ticket_card_wicket';"><span class="glyphicon glyphicon-arrow-left" style="color: white; font-size:15px;"></span></button>
                              </div>
                            </div>
                        <?php        
                            } 
                        ?>           
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label" style="float: left; padding-top: 10px; margin-right: 5px; width: 100px; text-align: right;">[[.customer.]]  </label>
                                <input name="customer_name" type="text" id="customer_name" class="form-control" style="float: left; width: 50%;" autocomplete="off" />
                                <input name="customer_id" type="hidden" id="customer_id" />
                                <button class="btn btn-sm btn-success" id="search_customer" style="margin-left: 10px; margin-top: 3px;" type="button" onclick="window.open('?page=customer&action=select_customer&type=ticket','customer');" ><span class="glyphicon glyphicon-search"></span></button>
                                <button class="btn btn-sm btn-danger" id="remove_customer" style="margin-left: 10px; margin-top: 3px;" type="button" onclick="removeCustomer();" ><span class="glyphicon glyphicon-remove"></span></button>
                            </div>
                        </div>
                        <div class="col-md-3" style="margin-left: 40px;"> 
                            <div class="form-group">
                                <label class="form-label" style="float: left; padding-top: 10px; margin-right: 5px;">[[.booker.]]  </label>
                                <input name="booker" type="text" id="booker" class="form-control" style="float: left; width: 70%;"  autocomplete="off"  />                           
                            </div>
                        </div>
                        <div class="col-md-3" style="margin-left: 40px;">
                            <div class="form-group">
                                <label class="form-label" style="float: left; padding-top: 10px; margin-right: 5px; width: 60px; text-align: right; padding-right: 12px;">[[.phone_tick.]]  </label>
                                <input name="phone" type="text" id="phone" class="form-control" style="float: left; width: 70%;"  autocomplete="off"  />                           
                            </div>                            
                        </div>
                        <div class="col-md-12" style="margin-top: 20px;">
                            <div class="col-md-8 col-md-offset-1">
                                <div class="form-group">
                                    <label class="form-label" style="float: left; padding-top: 10px; margin-right: 5px; width: 87px; text-align: right;">[[.note.]]  </label>
                                    <textarea name="note" id="note" class="form-control" style="float: left; width: 78.5%; resize: none;"></textarea>
                                </div>
                            </div>
                            <div class="col-md-3" style="padding-left: 0px;">
                                <div class="form-group">
                                    <label class="form-label" style="float: left; padding-top: 10px; margin-right: 5px; width: 57px;">[[.check_ticket_online.]]  </label>
                                    <input name="ticket_online" type="text" id="ticket_online" class="form-control" style="float: left; width: 50%;" />
                                    <button class="btn btn-sm btn-primary" style="margin-left: 10px; margin-top: 2px;" type="button" onclick="getTicketOnline();" id="search" >[[.Search.]]</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-12" style="margin-top: 20px; padding-right: 0px;">
                        <div class="col-md-8 shadow" style="min-height: 300px; border: grey 3px solid; border-radius: 5px; background: #FFFFF0;">
                            <div id="card_type" class="col-md-6" style="border-right: grey 2px solid; margin-top: 10px; min-height: 250px; padding-bottom: 20px;">
                                <?php
                                    $i = 50; 
                                    $j = 1;
                                ?>
                                <!--LIST:ticket_card_types-->                                                        
                                    <div class="col-md-4 card_type" button="<?php echo $i; ?>" ticket_card_type_id="[[|ticket_card_types.id|]]" card_type_name="[[|ticket_card_types.name|]]" price="[[|ticket_card_types.price|]]" <?php if(!isset($_SESSION['ticket_card_lock_'.$j]) || (isset($_SESSION['ticket_card_lock_'.$j]) && $_SESSION['ticket_card_lock_'.$j]==1)){ ?> onclick="showModal('[[|ticket_card_types.id|]]','[[|ticket_card_types.name|]]','[[|ticket_card_types.price|]]');" <?php } ?> style="height: 60px; background: <?php  echo (isset($_SESSION['ticket_card_lock_'.$j])?($_SESSION['ticket_card_lock_'.$j]==1?"green":"silver"):"green"); ?>; margin-top: 10px; border-radius: 10px;cursor: pointer; width:30%; margin-right: 3%;">
                                        <span style="color: white; font-size: 10px; height: 60px;display: flex;justify-content: center;align-items: center; text-align: center;">[[|ticket_card_types.name|]]</span>
                                    </div>
                                    <div class="col-md-2" style="margin-top: 10px;">
                                        <span class="glyphicon glyphicon-lock" status="<?php echo (isset($_SESSION['ticket_card_lock_'.$j])?($_SESSION['ticket_card_lock_'.$j]==1?"on":"off"):"on"); ?>" style="margin-left: -10px; cursor: pointer;" onclick="disableCard(this,'[[|ticket_card_types.id|]]','[[|ticket_card_types.name|]]','[[|ticket_card_types.price|]]');"></span>
                                        <br />
                                        <span style="margin-top: 2px; display: block; float: left; margin: 9px 0px 0px -8px;"><?php echo $j<10?$j:""; ?></span>
                                    </div>
                                    <?php
                                        $i++; 
                                        $j++;
                                    ?>
                                <!--/LIST:ticket_card_types-->
                            </div>
                            <div class="col-md-6" style="padding: 5px; padding-right: 0px;">
                                <table class="table table-bordered table-hover" style="margin-top: 10px;">
                                    <thead>
                                        <tr>
                                            <th style="width: 50%;">[[.ticket_name.]]</th>
                                            <th style="width: 5%;">[[.SL.]]</th>
                                            <th style="width: 15%;">[[.Price.]]</th>
                                            <th style="width: 10%;">[[.Discount.]]</th>
                                            <th style="width: 15%;">[[.Total.]]</th>
                                            <th style="width: 5%;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="fillUpDt" style="overflow-y: auto;">
                                        <?php
                                            if(isset([[=ticket_card_wicket_detail=]]) && !empty([[=ticket_card_wicket_detail=]])){
                                        ?>
                                            <!--LIST:ticket_card_wicket_detail-->
                                                <tr id='items_[[|ticket_card_wicket_detail.ticket_card_types_id|]]' style='cursor:pointer;' select='off' > 
                                                    <td style="display: none;">[[|ticket_card_wicket_detail.ticket_card_types_id|]]</td>
                                                    <td <!--IF:cond_payment(([[=check_payment=]]==0) OR ([[=check_payment=]]==1 AND User::can_admin(false,ANY_CATEGORY)) )--> onclick="showModal('[[|ticket_card_wicket_detail.ticket_card_types_id|]]','[[|ticket_card_wicket_detail.name|]]','[[|ticket_card_wicket_detail.price|]]','[[|ticket_card_wicket_detail.quantity|]]','[[|ticket_card_wicket_detail.discount_percent|]]');" style='font-size:11px; color:blue; font-weight:bold;' <!--/IF:cond_payment--> >[[|ticket_card_wicket_detail.name|]]</td>
                                                    <td onclick="showModal('[[|ticket_card_wicket_detail.ticket_card_types_id|]]','[[|ticket_card_wicket_detail.name|]]','[[|ticket_card_wicket_detail.price|]]','[[|ticket_card_wicket_detail.quantity|]]','[[|ticket_card_wicket_detail.discount_percent|]]');" style='font-size:11px'>[[|ticket_card_wicket_detail.quantity|]]</td>
                                                    <td onclick="showModal('[[|ticket_card_wicket_detail.ticket_card_types_id|]]','[[|ticket_card_wicket_detail.name|]]','[[|ticket_card_wicket_detail.price|]]','[[|ticket_card_wicket_detail.quantity|]]','[[|ticket_card_wicket_detail.discount_percent|]]');" style='font-size:11px; text-align: right;'><?php echo System::display_number([[=ticket_card_wicket_detail.price=]]); ?></td>
                                                    <td onclick="showModal('[[|ticket_card_wicket_detail.ticket_card_types_id|]]','[[|ticket_card_wicket_detail.name|]]','[[|ticket_card_wicket_detail.price|]]','[[|ticket_card_wicket_detail.quantity|]]','[[|ticket_card_wicket_detail.discount_percent|]]');" style='font-size:11px; text-align: right;'><?php echo System::display_number([[=ticket_card_wicket_detail.discount_percent=]]); ?></td>
                                                    <td onclick="showModal('[[|ticket_card_wicket_detail.ticket_card_types_id|]]','[[|ticket_card_wicket_detail.name|]]','[[|ticket_card_wicket_detail.price|]]','[[|ticket_card_wicket_detail.quantity|]]','[[|ticket_card_wicket_detail.discount_percent|]]');" style='font-size:11px; text-align: right;'><?php echo System::display_number([[=ticket_card_wicket_detail.total=]]); ?></td>
                                                    <td><button class='btn btn-xs' onclick='removeItem(this);' type='button'><span style='color: red;' class='glyphicon glyphicon-remove'></span></button></td>
                                                    <td style='display:none;'><input name='ticket_list[[[|ticket_card_wicket_detail.ticket_card_types_id|]]][id]' type='hidden' id='ticket_list[[[|ticket_card_wicket_detail.ticket_card_types_id|]]][id]' value='[[|ticket_card_wicket_detail.ticket_card_types_id|]]'/></td>
                                                    <td style='display:none;'><input name='ticket_list[[[|ticket_card_wicket_detail.ticket_card_types_id|]]][quantity]' type='hidden' id='ticket_list[[[|ticket_card_wicket_detail.ticket_card_types_id|]]][quantity]' value='[[|ticket_card_wicket_detail.quantity|]]'/></td>                                                    
                                                    <td style='display:none;'><input name='ticket_list[[[|ticket_card_wicket_detail.ticket_card_types_id|]]][price]' type='hidden' id='ticket_list[[[|ticket_card_wicket_detail.ticket_card_types_id|]]][price]' value='[[|ticket_card_wicket_detail.price|]]'/></td>
                                                    <td style='display:none;'><input name='ticket_list[[[|ticket_card_wicket_detail.ticket_card_types_id|]]][detail_id]' type='hidden' id='ticket_list[[[|ticket_card_wicket_detail.ticket_card_types_id|]]][detail_id]' value='[[|ticket_card_wicket_detail.id|]]'/></td>
                                                    <td style='display:none;'><input name='ticket_list[[[|ticket_card_wicket_detail.ticket_card_types_id|]]][discount_percent]' type='hidden' id='ticket_list[[[|ticket_card_wicket_detail.ticket_card_types_id|]]][discount_percent]' value='[[|ticket_card_wicket_detail.discount_percent|]]'/></td>
                                                </tr>
                                            <!--/LIST:ticket_card_wicket_detail-->
                                        <?php        
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-4" style=" padding-right: 0px;">
                            <div class="col-md-12"  id="display_card" style="height: 100px;border: green 2px solid; border-radius: 5px; margin-bottom: 10px; display: none;">
                                <div class="col-md-12" style="height: 30px; padding: 10px 0px;">
                                    <div class="col-md-5">
                                        [[.Card_information.]] : 
                                    </div>
                                    <div class="col-md-7" id="card_info">                                        
                                    </div>
                                </div>
                                <div class="col-md-12" style="height: 30px; padding: 10px 0px;">
                                    <div class="col-md-5">
                                        [[.Using_starting_date.]] : 
                                    </div>
                                    <div class="col-md-7" id="start_date">                                        
                                    </div>
                                </div>
                                <div class="col-md-12" style="height: 30px; padding: 10px 0px;">
                                    <div class="col-md-5">
                                        [[.Current_money.]] : 
                                    </div>
                                    <div class="col-md-7" id="current_money">                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 shadow" id="payment_info" style="min-height: 140px; border: green 2px solid; border-radius: 5px;">
                                <div class="col-md-6" style="padding: 0px; margin-top: 30px;">
                                    <div style="width: 100%; margin: 5px 0px; height: 25px; float:;">
                                        <span style="width: 50%; line-height:25px; text-align: right; display: block; float: left; margin-right: 5px;">[[.cash.]] </span>
                                        <span style="width: 49%;line-height:25px;">
                                            <input name="cash" type="text" id="cash" format_num="" oninput="format_num(this,this.value);" style="width: 90px; padding: 0px; height: 23px;  text-align: right;" autocomplete="off" />
                                        </span>
                                    </div>
                                    <div style="width: 100%; margin: 5px 0px; height: 25px;">
                                        <span style="width: 50%; line-height:25px; text-align: right; display: block; float: left; margin-right: 5px;">[[.bank_card.]] </span>
                                        <span style="width: 49%;line-height:25px;">
                                            <input name="bank_card" type="text" id="bank_card" format_num="" oninput="format_num(this,this.value);" style="width: 90px; padding: 0px; height: 23px; text-align: right; "  autocomplete="off" />
                                        </span>
                                    </div>
                                    <div style="width: 100%; margin: 5px 0px; height: 25px;">
                                        <span style="width: 50%; line-height:25px; text-align: right; display: block; float: left; margin-right: 5px;">[[.prepaid_card.]] </span>
                                        <span style="width: 49%;line-height:25px;" id="prepaid">
                                            <span id="old_prepaid_card" style="width: 90px; padding: 0px; height: 25px; line-height: 25px;  text-align: right; padding-right: 2px; float: left; display: block;">
                                            <?php
                                                if(isset($_REQUEST['prepaid_card'])){
                                                    echo $_REQUEST['prepaid_card'];
                                            ?>
                                            </span>
                                            <?php        
                                                }
                                            ?>
                                            
                                        </span>
                                    </div>
                                    <div style="width: 100%; margin: 5px 0px; height: 25px;">
                                        <hr style="margin: 0px; border-top:green thin solid;" />
                                        <span style="width: 50%; line-height:25px; text-align: right; display: block; float: left; margin-right: 5px;">[[.Total.]]</span>
                                        <span style="width: 45.5%;line-height:25px; text-align: right; display: block; float: left;" id="total_money">
                                        </span>
                                    </div>
                                </div>  
                                <div class="col-md-6"  style="padding: 0px; margin-top: 30px;">
                                    <div style="width: 100%; margin: 5px 0px; height: 25px; float:;">
                                        <span style="width: 60%; line-height:25px; text-align: right; display: block; float: left;">[[.total.]] : </span>
                                        <span style="width: 39%;line-height:25px; display: block; float: left; text-align: right;" id="total"></span>
                                    </div>
                                    <div style="width: 100%; margin: 5px 0px; height: 25px;">
                                        <span style="width: 60%; line-height:25px; text-align: right; display: block; float: left;">[[.customer_money.]] : </span>
                                        <span style="width: 39%;line-height:25px;  display: block; float: left; text-align: right;"><input name="customer_money" type="text" id="customer_money" oninput="jQuery(this).ForceNumericOnly().FormatNumber(); updateTotal();" style="width: 74px; margin-left: 3px; padding: 0px; height: 23px; text-align: right; "  autocomplete="off" /></span>
                                    </div>
                                    <div style="width: 100%; margin: 5px 0px; height: 25px;">
                                        <span style="width: 60%; line-height:25px; text-align: right; display: block; float: left;">[[.refund.]] : </span>
                                        <span style="width: 39%;line-height:25px;  display: block; float: left; text-align: right;" id="refund"></span>
                                    </div>
                                </div>   
                            </div>
                            <div class="col-md-12" style="min-height: 140px; margin-top: 20px;">
                                <div class="col-md-12" style="height: 30px; margin-top: 15px;">
                                   <div class="col-md-6" style="height: inherit; text-align: center;"><button class="btn btn-warning btn-sm" style="width: 120px;" type="button" onclick="refresh_form();" name="refresh" value="refresh">[[.Refresh.]]</button></div>
                                    <div class="col-md-6" style="height: inherit; text-align: center;"><button class="btn btn-primary btn-sm" style="width: 120px;" type="submit" onclick="setAction('save');" name="save" value="save">[[.save.]]</button></div>
                                </div>
                                <div class="col-md-12" style="height: 60px; margin-top: 15px;">
                                    <div class="col-md-6" style="height: 80px; text-align: center;">
                                        <button class="btn btn-danger btn-lg" style="width: 120px; padding: 26px 16px 25px 16px;" type="submit" name="print_card" value="print_card" onclick="setAction('print_card');" >[[.print_card.]]</button>
                                    </div>
                                    <div class="col-md-6" style="height: inherit; text-align: center;">
                                        <div class="col-md-12">
                                            <button class="btn btn-success btn-sm" style="width: 120px;" type="button" name="member_card" value="member_card" >[[.member_card.]]</button>
                                        </div>
                                        <div class="col-md-12" style="margin-top: 15px;">
                                            <button class="btn btn-info btn-sm" style="width: 120px;" type="button" name="export_card" value="export_card" onclick="setAction('export_card');export_card_fn();" >[[.export.]]</button>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="action" id="action" value="" />
                                <input type="hidden" name="barcode" id="barcode" value="" />
                                <input type="hidden" name="token" value="[[|_token|]]"/>
                            </div>                     
                        </div>
                    </div>
                </form>
                <script>
                    var _pol = '<?php echo $_pol ?>';
                </script>
                <div class="modal fade bs-example-modal-sm in" id="myModal" role="dialog" tabindex="-1" aria-labelledby="mySmallModalLabel"> 
                    <div class="modal-dialog modal-sm" role="document"> 
                        <div class="modal-content"> 
                            <div class="modal-header"> 
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true"> x </span>
                                    </button> 
                                    <h4 class="modal-title" id="mySmallModalLabel">Nhập số lượng vé</h4> 
                            </div> 
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="form-label" style="display: block; float: left; width:30%; margin-top: 7px;">[[.Quantity.]]</label>
                                    <input id="quantity" class="form-control" type="number" style="width: 60%;" autofocus="autofocus" step="1" min="0" max="100" autocomplete="off" />
                                </div>
                                <div class="input-group" style="width: 90%;">
                                    <label class="form-label" style="display: block; float: left; width:40%; margin-top: 7px;">[[.Discount_percent.]]</label>
                                    <input id="discount_percent" class="form-control" type="number" style="width: 60%;" autofocus="autofocus" step="1" min="0" max="100" />
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div> 
                            <div class="modal-footer">
                                  <button type="button" class="btn btn-primary" card_name="" price="" card_id="" discount_percent="" action="add" id="parse_data" onclick="fillUpData(this);">[[.Ok.]]</button>
                                  <button type="button" class="btn btn-default" data-dismiss="modal">[[.Close.]]</button>
                            </div>
                        </div> 
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var ticket_price = {};
    jQuery(document).ready(function(){
        
        jQuery('#testRibbon').css('display','none');
		jQuery(".jcarousel-clip-horizontal").width(jQuery('.full_screen').width()-100);
		if(jQuery('#bound_product_list').css('display')=='none')
        {
			jQuery('#bound_product_list').css('display','block');
		}
        jQuery("#chang_language").css("display","none");
         
        
        enterKey();
        //call_keycode_out();
        //call_keycode_in();
        updateTotal();
        inputBarcode(this);
        jQuery("#customer_name").focus();
        jQuery("input,textarea").on("focus",function(){
            jQuery(document).off("keydown");
            jQuery(window).off("keypress");
            call_keycode_out();
            inputBarcode(this);
        });
        jQuery("input,textarea").on("blur",function(){
            call_key_num();
            enterKey();
            call_keycode_in();
        });
        jQuery("button").keypress(function(e){
            if(e.which==13){
                e.preventDefault();
                return;
            }
        }); 
        jQuery(".card_type").each(function(){
            var status_ticket_lock = jQuery(this).next().find("span:first-child").attr("status");
            if(status_ticket_lock=="off")
            {
                jQuery(this).unbind('mouseenter mouseleave');
                jQuery(this).unbind('click');
                jQuery(this).next().find("span:first-child").css("color","silver");
            }
            else{
                jQuery(this).hover(function(){
                    jQuery(this).css("background-color", "maroon");
                    }, function(){
                    jQuery(this).css("background-color", "green");
                });  
            }
        }); 
        
        <?php
            if([[=is_ticket_online=]]==1)
            {
        ?>
               //jQuery("#payment_info").css("display","none");
               jQuery("button[name=refresh]").css("display","none");
               jQuery("button[name=save]").css("display","none");
               jQuery("button[name=print_card]").css("display","none");
               jQuery("button[name=member_card]").css("display","none");
               jQuery("button[name=export_card]").css("display","none");
               jQuery("#search").css("display","none");
               jQuery("#card_type div").each(function(){
                 jQuery(this).css("visibility","hidden");
               }); 
               
               jQuery("#search_customer").css("display","none");
               jQuery("#remove_customer").css("display","none");
               
               jQuery("tr[id^=items_] td").each(function(){
                  jQuery(this).removeAttr("onclick");
                  jQuery(this).find("button").removeAttr("onclick");
               });
               
        <?php        
            }
        ?>     
    });
    
    var barcode_temp = "";
    var prepaid = 0;
    var current_prepaid_cash = 0;
    function inputBarcode(obj){
        var pressed = false; 
        var chars = []; 
        var stt = 0;
        var import_valid = [];
        
        jQuery(window).keypress(function(e) {
            if (e.which >= 48 && e.which <= 57) {
                chars.push(String.fromCharCode(e.which));
            }
            if (pressed == false) {
                jQuery("tr[id^=items]").each(function(){
                                    var status = jQuery(this).attr("select");
                                    if(status=="on"){
                                        jQuery(this).attr("select","off");
                                        jQuery(this).css("background","white");
                                    }
                        });
                setTimeout(function(){
                    if (chars.length == 10) {
                        
                        //jQuery(window).off("keypress");
                        //jQuery(document).off("keypress");
                        barcode = chars.join("");
                        if(obj){
                            var current_value = jQuery(obj).val().replace(/,/g,"");
                            if(number_format(barcode)==number_format(current_value)){
                                jQuery(obj).val(0);
                            }
                            else{
                            var re = new RegExp(barcode,"g");
                            current_value = current_value.replace(re,"");
                            jQuery(obj).val(number_format(current_value));
                            }
                        }
                        updateTotal();
                        barcode_encode = CryptoJS.MD5(barcode).toString();
                        
                        import_valid.push(barcode);
                        var url = "check_payment_card.php";
                        var super_pl = CryptoJS.MD5(barcode_encode+_pol).toString();
                        jQuery.ajax({
                            url : url,
                            dataType : "JSON",
                            type : "POST",
                            data : {"card_id":barcode,"super_pl":super_pl,"action":"2"},
                            success : function(data){
                                barcode_temp = barcode;
                                jQuery("#barcode").val(barcode);
                                var str = data['color'];
                                jQuery("#card_id").val(barcode);
                                jQuery("#card_info").html("[ "+data['info']['card_id']+" ] " + data['message']);
                                jQuery("#card_info").css("color",str);
                                jQuery("#start_date").html(data['info']['start_time']);
                                jQuery("#start_date").css("color",str);
                                prepaid = data['info']['current_money']?data['info']['current_money']:0;
                                jQuery("#current_money").html(data['info']['current_money']?number_format(data['info']['current_money']):0);
                                jQuery("#current_money").css("color",str);
                                //jQuery("#total_charging_money").html(data['info']['total_charging']?number_format(data['info']['total_charging']):0);
                               // jQuery("#charging_money").html(number_format(data['total_charging']));
                                //jQuery("#amount_after_charging").html(data['info']['current_money']?number_format(data['info']['current_money']):0);
                                //jQuery("#first_encode").val(CryptoJS.MD5(barcode).toString());
                                //jQuery("#second_encode").val(CryptoJS.MD5(jQuery("#first_encode").val()).toString());
                                //jQuery("#third_encode").val(CryptoJS.MD5(jQuery("#second_encode").val()).toString());
                                
                                jQuery("#display_card").fadeIn(500);
                                //enterKey();
                                //inputBarcode("");
                                //
                                var old_prepaid_card = jQuery("#old_prepaid_card").html();
                                    if(old_prepaid_card){
                                        old_prepaid_card = old_prepaid_card.trim();
                                        if(old_prepaid_card==""){
                                            old_prepaid_card=0;
                                        }
                                    }   
                                var str = ' <input name="prepaid_card" type="text" id="prepaid_card" format_num="" oninput=" check_prepaid(this,this.value);format_num(this,this.value);"  onblur="returnPrepaidCard(this,this.value);" style="width: 90px; padding: 0px; height: 23px;  text-align: right; "  autocomplete="off" />';
                                if(jQuery("#prepaid_card").length==0){
                                    jQuery("#prepaid").html(str);
                                    current_prepaid_cash = old_prepaid_card;
                                }                               
                            }
                        });               
                    }
                    chars = [];
                    pressed = false;
                },200);
            }
            pressed = true;
        });
    }
    
    
    function setAction(action){
        jQuery("#action").val(action);
    }
    function checkForm(){ 
            if(!jQuery("tr[id^=items]").length){
                alert("Bạn chưa nhập thông tin vé!");
                return false;
            }
                
            var cash = jQuery("#cash").val();
                cash = Number(cash.replace(/,/g,""));
            var bank_card = jQuery("#bank_card").val();
                bank_card = Number(bank_card.replace(/,/g,""));
                
            var prepaid_card = 0;
            if(jQuery("#prepaid_card").length!=0)
            {
                prepaid_card = jQuery("#prepaid_card").val();
                prepaid_card = Number(prepaid_card.replace(/,/g,""));    
            }    
            var total = 0;
            
            jQuery("tr[id^=items_]").each(function(){
                var quantity = jQuery(this).find("td:nth-child(9) input").val();
                var price = jQuery(this).find("td:nth-child(10) input").val(); 
                var discount_percent = jQuery(this).find("td:nth-child(12) input").val(); 
                if(!discount_percent)
                {
                    discount_percent = 0;
                }
                    total += quantity*price*(100-discount_percent)/100;
            });
            if(total>(cash+bank_card+prepaid_card)){
                alert("Khách chưa trả đủ số tiền.");
                return false;
            }
            else if(total<(cash+bank_card+prepaid_card)){
                alert("Số lượng vé đã bị thay đổi. Xin vui lòng tạo lại thanh toán!");
                return false;
            }
            
            if(cash == 0 && bank_card==0 && prepaid_card==0 && total!=0){
                alert("Chưa nhập khoản thanh toán của khách nên không thể in vé!");
                return false;
            }
            
            if(jQuery("#barcode").val()!=barcode_temp){
                alert("Thẻ không hợp lệ!");
                return false;
            }
            if(confirm("[[.Are_you_sure_perform_the_operation.]]?")){
                return true;     
             }
             else{
                return false;
             }   
    }
    function showModal(id,name,price,quantity="",discount_percent=""){
        //return false;
        var discount_percent = jQuery("tr#items_"+id+" >td:last-child input").val();
        jQuery("#mySmallModalLabel").html(name);
        jQuery("#myModal").modal('show');
        jQuery('#myModal').on('shown.bs.modal', function() {         
          jQuery('#quantity').focus();
        })
        jQuery("#parse_data").attr({"card_id":id,"card_name":name,"price":price,"discount_percent":discount_percent});    
        if(quantity!=""){
           jQuery('#quantity').val(quantity);  
           jQuery('#discount_percent').val(discount_percent);  
           jQuery('#quantity').select();
           jQuery("#parse_data").attr("action","edit");
           jQuery("tr[id^=items_]").not("tr#items_"+id).each(function(){
                if(jQuery(this).attr("select")=="on"){
                    jQuery(this).css("background","white");
                    jQuery(this).attr("select","off");
                }
           });
           jQuery("tr#items_"+id).attr("select","on");
           jQuery("tr#items_"+id).css("background","#CAFFCA");
           if(jQuery("#customer_name").val().trim()=="")
           { 
               jQuery('#discount_percent').removeAttr("readonly"); 
               jQuery('#discount_percent').removeClass("readonly");
           }
           else{
               jQuery('#discount_percent').attr("readonly","");
               jQuery('#discount_percent').addClass("readonly"); 
           }             
        }
        else{ 
            if(jQuery("tr#items_"+id).length!=0)
            {
               jQuery('#discount_percent').val(jQuery("tr#items_"+id+" td:nth-child(12) input").val()); 
               jQuery('#discount_percent').attr("readonly","");
               jQuery('#discount_percent').addClass("readonly");
            }
            else{
               if(jQuery("#customer_name").val().trim()!="")
               {         
                    if(Object.keys(ticket_price).length!=0)
                    {
                         for(var i in ticket_price)
                         {
                            jQuery('#discount_percent').attr("readonly","");
                            jQuery('#discount_percent').addClass("readonly"); 
                            jQuery('#discount_percent').val(ticket_price[i]['price']); 
                         }   
                    }
                    else
                    {
                        jQuery('#discount_percent').removeAttr("readonly"); 
                        jQuery('#discount_percent').removeClass("readonly"); 
                        jQuery('#discount_percent').val(''); 
                    }   
               }
               else
               { 
                   jQuery('#discount_percent').removeAttr("readonly"); 
                   jQuery('#discount_percent').removeClass("readonly"); 
                   jQuery('#discount_percent').val(''); 
               } 
            }
            jQuery('#quantity').val('');
        }
    } 
    
    /** Su dung de click 1 lan 
    function fillUpDataClick(id,name,price){
        if(!jQuery("#items_"+id).length){
            var total = number_format(parseInt(price*1));
            var str = "";
                str += "<tr id='items_"+id+"' style='cursor:pointer;' select='off' onclick=\"showModal('"+id+"','"+name+"','"+price+"','1');\">"
                            +"<td style='display:none;'>"+id+"</td>"
                            +"<td style='font-size:11px; color:blue; font-weight:bold;'>"+name+"</td>"
                            +"<td style='font-size:11px'>1</td>"
                            +"<td style='text-align:right;font-size:11px'>"+number_format(price)+"</td>"
                            +"<td style='text-align:right;font-size:11px'>"+total+"</td>"
                            +"<td><button class='btn btn-xs' onclick='removeItem(this);'><span style='color: red;' class='glyphicon glyphicon-remove'></span></button></td>"
                            +"<td style='display:none;'><input name='ticket_list["+id+"][id]' type='hidden' value='"+id+"'/></td>"
                            +"<td style='display:none;'><input name='ticket_list["+id+"][quantity]' type='hidden' value='1'/></td>"
                            +"<td style='display:none;'><input name='ticket_list["+id+"][price]' type='hidden' value='"+price+"'/></td>"
                    +  "</tr>";
                jQuery("#fillUpDt").append(str); 
        }
        else{
            var current_quantity = parseInt(jQuery("#items_"+id).find("td:nth-child(3)").html());
            var new_quantity = current_quantity+1;         
            var price = parseInt(jQuery("#items_"+id).find("td:nth-child(4)").html().replace(/,/g,""));
            var total = number_format(parseInt(price*new_quantity));
            jQuery("#items_"+id).find("td:nth-child(3)").html(new_quantity);
            jQuery("#items_"+id).find("td:nth-child(5)").html(total);
            jQuery("#items_"+id).removeAttr("onclick");
            jQuery("#items_"+id).attr("onclick","showModal('"+id+"','"+name+"','"+price+"','"+new_quantity+"');");
        }
        updateTotal();
    }
    **/
    function fillUpData(obj){
        var id = jQuery(obj).attr("card_id");
        var name = jQuery(obj).attr("card_name");
        var price = jQuery(obj).attr("price");
        var quantity = jQuery("#quantity").val();
        var discount_percent = jQuery("#discount_percent").val();
        if(!discount_percent)
        {
            discount_percent = 0;
        }
        var total = number_format(parseInt(price*quantity*(100-discount_percent)/100));
        var action = jQuery(obj).attr("action");
        if(quantity>0 && quantity == parseInt(quantity, 10)){
            var check = true;
            jQuery("#fillUpDt tr").each(function(){
                var current_id = jQuery(this).find("td:first-child").html();
                if(current_id==id && action=="add"){
                    var current_quantity = jQuery(this).find("td:nth-child(3)").html();
                    var new_quantity = parseInt(current_quantity)+parseInt(quantity);
                    total = number_format(parseInt(price*new_quantity*(100-discount_percent)/100));
                    jQuery(this).find("td:nth-child(3)").html(new_quantity);
                    jQuery(this).find("td:nth-child(6)").html(total);
                    jQuery(this).find("td:nth-child(9) input").val(new_quantity);
                    check = false;
                    jQuery("#items_"+id).removeAttr("onclick");
                    jQuery("#items_"+id+" td:nth-child(2),#items_"+id+" td:nth-child(3),#items_"+id+" td:nth-child(4),#items_"+id+" td:nth-child(5),#items_"+id+" td:nth-child(6)").attr("onclick","showModal('"+id+"','"+name+"','"+price+"','"+new_quantity+"','"+discount_percent+"');");
                }
                else if(current_id==id && action=="edit"){
                    jQuery(this).find("td:nth-child(3)").html(quantity);
                    jQuery(this).find("td:nth-child(5)").html(discount_percent+"%");
                    jQuery(this).find("td:nth-child(6)").html(total);
                    jQuery(this).find("td:nth-child(9) input").val(quantity);
                    jQuery(this).find("td:nth-child(12) input").val(discount_percent);
                    jQuery("#parse_data").attr("action","add");
                    jQuery("#items_"+id).removeAttr("onclick");
                   jQuery("#items_"+id+" td:nth-child(2),#items_"+id+" td:nth-child(3),#items_"+id+" td:nth-child(4),#items_"+id+" td:nth-child(5),#items_"+id+" td:nth-child(6)").attr("onclick","showModal('"+id+"','"+name+"','"+price+"','"+quantity+"','"+discount_percent+"');");
                    check = false;
                }
            });
            if(check == true){
                var str = "";
                str += "<tr id='items_"+id+"' style='cursor:pointer;' select='off'>"
                            +"<td style='display:none;'>"+id+"</td>"
                            +"<td onclick=\"showModal('"+id+"','"+name+"','"+price+"','"+quantity+"','"+discount_percent+"');\" style='font-size:11px; color:blue; font-weight:bold;'>"+name+"</td>"
                            +"<td onclick=\"showModal('"+id+"','"+name+"','"+price+"','"+quantity+"','"+discount_percent+"');\" style='font-size:11px'>"+quantity+"</td>"
                            +"<td onclick=\"showModal('"+id+"','"+name+"','"+price+"','"+quantity+"','"+discount_percent+"');\" style='text-align:right;font-size:11px'>"+number_format(price)+"</td>"
                            +"<td onclick=\"showModal('"+id+"','"+name+"','"+price+"','"+quantity+"','"+discount_percent+"');\" style='text-align:right;font-size:11px'>"+number_format(discount_percent)+"%</td>"
                            +"<td onclick=\"showModal('"+id+"','"+name+"','"+price+"','"+quantity+"','"+discount_percent+"');\" style='text-align:right;font-size:11px'>"+total+"</td>"
                            +"<td><button class='btn btn-xs' type='button' onclick='removeItem(this);'><span style='color: red;' class='glyphicon glyphicon-remove'></span></button></td>"
                            +"<td style='display:none;'><input name='ticket_list["+id+"][id]' type='hidden' id='ticket_list["+id+"][id]' value='"+id+"'/></td>"
                            +"<td style='display:none;'><input name='ticket_list["+id+"][quantity]' type='hidden' id='ticket_list["+id+"][quantity]' value='"+quantity+"'/></td>"
                            +"<td style='display:none;'><input name='ticket_list["+id+"][price]' type='hidden' id='ticket_list["+id+"][price]' value='"+price+"'/></td>"
                            +"<td style='display:none;'><input name='ticket_list["+id+"][detail_id]' type='hidden' id='ticket_list["+id+"][detail_id]' value=''/></td>"
                            +"<td style='display:none;'><input name='ticket_list["+id+"][discount_percent]' type='hidden' id='ticket_list["+id+"][discount_percent]' value='"+discount_percent+"'/></td>"
                    +  "</tr>";
                jQuery("#fillUpDt").append(str);  
            }
            jQuery('#quantity').val('');  
            jQuery("#myModal").modal('hide');
            updateTotal();
            //enterKey();
        }
        else{
            alert("Số lượng vé phải là số nguyên và lớn hơn 0.");
            jQuery("#quantity").focus();
        }
        
    }
    
    function removeItem(obj){
        if(confirm("[[.Do_you_want_remove_this_items.]]")){
            jQuery(obj).parent().parent().remove();
            updateTotal();
            
        }
    }
    jQuery(document).keyup(hideModal);
    
    function hideModal(e) {
        if (e.keyCode == 27) {
            window.location.hash = "#";
        } else if (e.type === 'click') {
            window.location.hash = "#";
        }
    }
    function format_num(obj,value){
        
        var total = 0;
        jQuery("#fillUpDt tr").each(function(){
            var current_total = jQuery(this).find("td:nth-child(6)").html();
             current_total = Number(current_total.replace(/,/g,""));
             total+=current_total;
        });
        var total_current = 0;
        jQuery("input[format_num]").not(obj).each(function(){
            var current = jQuery(this).val();
                current = Number(current.replace(/,/g,""));
            total_current+=current;
        });
        
        value = Number(value.replace(/,/g,""));
//        var cash = jQuery("#cash").val();
//            cash = Number(cash.replace(/,/g,""));
//        var bank_card = jQuery("#bank_card").val();
//            bank_card = Number(bank_card.replace(/,/g,""));
//        var prepaid_card = 0;
//            if(jQuery("#prepaid_card").length!=0){
//                prepaid_card = jQuery("#prepaid_card").val();
//                prepaid_card = Number(prepaid_card.replace(/,/g,""));
//            }
        if(value<=(total-(total_current))){
            jQuery(obj).val(number_format(value));
        }
        else{
            jQuery(obj).val(number_format(total-(total_current)));
        }    
        updateTotal();
    }

    function check_prepaid(obj,value){
        //var new_value = parseInt(value.replace(/,/g,""));
//        if(new_value>prepaid){
//            jQuery(obj).val(number_format(prepaid));
//        }
        var new_value = to_numeric(value);
        current_prepaid_cash = to_numeric(current_prepaid_cash);
        prepaid = parseInt(prepaid);;
        if((new_value-current_prepaid_cash)>prepaid){
            jQuery(obj).val(number_format(parseInt(current_prepaid_cash)+parseInt(prepaid)));
        }
    }
    function returnPrepaidCard(obj,value){
        var new_value = to_numeric(value);
        if(new_value<current_prepaid_cash){
            jQuery(obj).val(number_format(current_prepaid_cash));
        }
        updateTotal("",0);
    }
    function disableCard(obj,id,name,price){
        var status = jQuery(obj).attr("status");
        var position = jQuery(obj).next().next().html();
        if(status=="off"){
            jQuery(obj).attr("status","on");
            jQuery(obj).css("color","black");
            jQuery(obj).parent().prev().css("background","green");
            jQuery(obj).parent().prev().unbind('mouseenter mouseleave');
            jQuery(obj).parent().prev().hover(function(){
                jQuery(this).css("background-color", "maroon");
                }, function(){
                jQuery(this).css("background-color", "green");
            });
            jQuery(obj).parent().prev().click(function(){
                showModal(id,name,price);
            });
        }
        else{
            jQuery(obj).attr("status","off");
            jQuery(obj).css("color","silver");
            jQuery(obj).parent().prev().css("background","silver");
            jQuery(obj).parent().prev().unbind('mouseenter mouseleave');
            jQuery(obj).parent().prev().unbind('click');
            jQuery(obj).parent().prev().removeAttr("onclick");
        }
        var stt = '';
        stt = (status=='on')?0:1;
        jQuery.ajax({
            url : "change_session_ticket.php",
            data : {'position':position,'status':stt},
            dataType : "JSON",
            type : "POST"
        });
    }
    function updateTotal(){
        var total = 0;
        jQuery("#fillUpDt tr").each(function(){
            var current_total = jQuery(this).find("td:nth-child(6)").html();
             current_total = Number(current_total.replace(/,/g,""));
             total+=current_total;
        });
        jQuery("#total").html(number_format(total));
        
        var cash = jQuery("#cash").val();
            cash = Number(cash.replace(/,/g,""));
        var bank_card = jQuery("#bank_card").val();
            bank_card = Number(bank_card.replace(/,/g,""));
        var prepaid_card = 0;
        if(jQuery("#prepaid_card").length!=0){
            prepaid_card = jQuery("#prepaid_card").val();
            prepaid_card = Number(prepaid_card.replace(/,/g,""));
        }
        else if(jQuery("span#old_prepaid_card").html()!="" && jQuery("#prepaid_card").length==0){
           var prepaid_card = jQuery("span#old_prepaid_card").html().replace(/ /g,"").replace(/,/g,"");
               prepaid_card = parseInt(prepaid_card);
        }
        if(isNaN(prepaid_card))
        {
            prepaid_card = 0;
        }
        var total_money = cash + bank_card + prepaid_card;
        jQuery("#total_money").html(number_format(total_money));
          var customer_money =  jQuery("#customer_money").val(); 
          if(isNaN(customer_money) && isNaN(customer_money.replace(/,/g,"")))
          {
            customer_money = 0;
            jQuery("#customer_money").val(0);
          }
          if(customer_money){
             customer_money = customer_money.replace(/,/g,"");
             if(parseInt(customer_money)>1000000000)
             {
                customer_money = 1000000000;                
             }
             jQuery("#customer_money").val(number_format(customer_money)); 
          }
          else{
            customer_money = 0;
          }
        if(customer_money!=0){
            //jQuery("#customer_money").html(number_format(customer_money));
            var refund = customer_money - total;
            jQuery("#refund").html(number_format(refund));    
        }
        else{
            jQuery("#customer_money").html("0");
            jQuery("#refund").html("0"); 
        }
    }
    jQuery("#myModal").keypress(function(e) {
        if(e.which == 13) {
            fillUpData(jQuery("#parse_data"));
            jQuery(document).off("keypress");
            //enterKey();
        }
    });
    function enterKey(){
        jQuery(document).keypress(function(e) {
            if(e.which == 13) {
                    if(jQuery("tr[id^=items]").length){
                                var check = false;
                                var index = 0;
                                jQuery("tr[id^=items]").each(function(){
                                    var status = jQuery(this).attr("select");
                                    if(status=="on"){
                                        index = jQuery(this).get();
                                        check = true;
                                        return;
                                    }
                                });
                                if(index!=0 && check==true){ 
                                    var id = jQuery(index).find("td:nth-child(1)").html();
                                    var name = jQuery(index).find("td:nth-child(2)").html();
                                    var price = parseInt(jQuery(index).find("td:nth-child(4)").html().replace(/,/g,""));
                                    var quantity = parseInt(jQuery(index).find("td:nth-child(3)").html());
                                    var discount_percent = parseInt(jQuery(index).find("td:nth-child(12) input").val());
                                    if(!jQuery("#myModal").is(":visible")){
                                    showModal(id,name,price,quantity,discount_percent);
                                    }
                                }
                            }    
                        }
        });
    }
    
    
    
        
    
    // Nhung su kien keycode khong bi anh huong trong o input
    function call_keycode_out(){
            jQuery(document).keydown(function(e) {
                switch(e.which) {
                    case 112:  // F1
                                e.preventDefault();
                                jQuery("#customer_name").focus();
                                break;
                    case 113:  // F2  
                                e.preventDefault();
                                jQuery("#cash").focus();
                                break;   
                    case 115:  // F4     
                                e.preventDefault();
                                        jQuery("#action").val("print_card");  
                                        jQuery("#EditTicketCardWicketForm_2").submit();                                                                                                   
                              break;                     
                    case 116:  // F5
                                e.preventDefault();
                                refresh();
                                break;                                           
                    default: return; // exit this handler for other keys
                }
            //e.preventDefault(); // prevent the default action (scroll / move caret)
            
        });
    }
    
    
    
    // Nhung su kien keycode bi anh huong trong o input
    
    function call_key_num()
    {
        jQuery(document).keydown(function(e) {
            if(e.ctrlKey){
                if(e.keyCode>=49 && e.keyCode<=57){ 
                    e.preventDefault();
                    var button = e.keyCode;
                    var obj = jQuery("div[button="+button+"]");
                    if(obj.length){
                        var status = jQuery(obj).next().find("span").attr("status");
                        if(status=="on"){
                            var ticket_card_type_id = jQuery(obj).attr("ticket_card_type_id");    
                            var card_name = jQuery(obj).attr("card_type_name");   
                            var price = jQuery(obj).attr("price");
                            showModal(ticket_card_type_id,card_name,price,"");
                        }
                    }     
                }
            }
        });
    }
    
    
    function call_keycode_in(){
        jQuery(document).keydown(function(e) {
            switch(e.which) {                       
                
                case 37: // left
                break;
        
                case 38: // up
                            if(jQuery("tr[id^=items]").length){
                                var check = false;
                                var index = 0;
                                jQuery("tr[id^=items]").each(function(){
                                    var status = jQuery(this).attr("select");
                                    if(status=="on"){
                                        index = jQuery(this).get();
                                        check = true;
                                        return;
                                    }
                                });
                                if(index!=0 && check==true){
                                    if(jQuery(index).prev().length){
                                        jQuery(index).attr("select","off");
                                        jQuery(index).css("background","white");
                                        jQuery(index).prev().css("background","#CAFFCA");
                                        jQuery(index).prev().attr("select","on");
                                    }
                                }
                                else{
                                    jQuery("tr[id^=items]:last-child").css("background","#CAFFCA");
                                    jQuery("tr[id^=items]:last-child").attr("select","on");
                                }
                            }    
                            break;
        
                case 39: // right
                break;
        
                case 40: // down
                        
                        if(jQuery("tr[id^=items]").length){
                                var check = false;
                                var index = 0;
                                jQuery("tr[id^=items]").each(function(){
                                    var status = jQuery(this).attr("select");
                                    if(status=="on"){
                                        index = jQuery(this).get();
                                        check = true;
                                        return;
                                    }
                                });
                                if(index!=0 && check==true){
                                    if(jQuery(index).next().length){
                                        jQuery(index).attr("select","off");
                                        jQuery(index).css("background","white");
                                        jQuery(index).next().css("background","#CAFFCA");
                                        jQuery(index).next().attr("select","on");
                                        
                                    }
                                }
                                else{
                                    jQuery("tr[id^=items]:first-child").css("background","#CAFFCA");
                                    jQuery("tr[id^=items]:first-child").attr("select","on");
                                }
                            }    
                            break;
                case 46:  // delete                      
                        if(jQuery("tr[id^=items]").length){
                                var check = false;
                                var index = 0;
                                jQuery("tr[id^=items]").each(function(){
                                    var status = jQuery(this).attr("select");
                                    if(status=="on"){
                                        index = jQuery(this).get();
                                        check = true;
                                        return;
                                    }
                                });
                                if(index!=0 && check==true){
                                    if(confirm("[[.Do_you_want_remove_this_items.]]"))
                                    jQuery(index).remove();
                                    updateTotal();
                                }
                            }
                            break;                          
                default: return; // exit this handler for other keys
            }
            //e.preventDefault(); // prevent the default action (scroll / move caret)
            
        });
    } 
    function refresh_form()
    {
        if(confirm('[[.Do_you_want_refresh_this_form.]]?')){
              window.location = '?page=ticket_card_wicket&cmd=edit&sales_id=<?php echo Url::get('sales_id'); ?>';  
        }
    }
    function removeCustomer(){
        if(confirm('[[.Do_you_want_remove_this_customer.]]?')){
            jQuery("#customer_name").val("");
            jQuery("#customer_id").val("");
            ticket_price = {};
            update_total_discount();
        }
    }
    
    
    function get_price_rate_code(customer_id){
        var url = "get_ticket_rate_code_price.php";
        jQuery.ajax({
            url : url,
            dataType : "JSON",
            type : "POST",
            data : {"customer_id":customer_id,"area":"CVN"},
            success : function(data){
               ticket_price = data;  
               update_total_discount();
            }
        }); 
    }
    
    function update_total_discount()
    {
           if(jQuery("tbody#fillUpDt").html().trim()!="")
           {
              jQuery("tbody#fillUpDt tr").each(function(){
                 var ticket_id = jQuery(this).find("> td:first-child").html().trim();
                 if(jQuery.isEmptyObject(ticket_price))
                 {    
                    jQuery("input#ticket_list["+ticket_id+"][discount_percent]").val(0);
                    jQuery(this).find("> td:last-child input").val(0);  // input discount_percent                  
                    jQuery(this).find("> td:nth-child(5)").html("0%");   
                    var new_price = to_numeric(jQuery(this).find("> td:nth-child(4)").html());
                    var total_price = new_price*to_numeric(jQuery(this).find("> td:nth-child(3)").html());
                    jQuery(this).find("> td:nth-child(6)").html(number_format(total_price));                               
                    updateTotal();
                 }
                  else{
                    for(var i in ticket_price)
                     {
                        jQuery("input#ticket_list["+ticket_id+"][discount_percent]").val(ticket_price[i]['price']);
                        jQuery(this).find("> td:last-child input").val(ticket_price[i]['price']);  // input discount_percent                  
                        jQuery(this).find("> td:nth-child(5)").html(ticket_price[i]['price']+"%");   
                        var new_price = to_numeric(jQuery(this).find("> td:nth-child(4)").html()) - to_numeric(ticket_price[i]['price'])*to_numeric(jQuery(this).find("> td:nth-child(4)").html())/100;
                        var total_price = new_price*to_numeric(jQuery(this).find("> td:nth-child(3)").html());
                        jQuery(this).find("> td:nth-child(6)").html(number_format(total_price));                               
                        updateTotal();
                     } 
                  }   
              });
           }
    }
    
    function getTicketOnline()
    {
        var ticket_online = jQuery("#ticket_online").val().trim();
        var url = "getTicketOnline.php";
            jQuery.ajax({
                url : url,
                dataType : "JSON",
                type : "POST",
                data : {"ticket_online":ticket_online},
                success : function(data){
                    console.log(Object.keys(data).length);
                   if(Object.keys(data).length===0)
                   {
                       alert("Mã vé "+ticket_online+" không tồn tại hoặc đã được xuất! Xin vui lòng kiểm tra lại.");
                       jQuery("#ticket_online").val("");
                   }
                   else
                   {
                       jQuery("#customer_name").val(data['customer_name']);
                       jQuery("#customer_id").val(data['customer_id']);
                       jQuery("#booker").val(data['booker']);
                       jQuery("#phone").val(data['phone_booker']);
                       jQuery("#note").val(data['note']);
                       
                       jQuery("#payment_info").css("display","none");
                       jQuery("button[name=refresh]").css("display","none");
                       jQuery("button[name=save]").css("display","none");
                       jQuery("button[name=print_card]").css("display","none");
                       jQuery("button[name=member_card]").css("display","none");
                       var str = "";
                       for(var i in data['details'])
                       {
                            
                            var ticket_card_types_id = data['details'][i]['ticket_card_types_id'];
                            var name = data['details'][i]['name'];
                            var price = data['details'][i]['price'];
                            var quantity = data['details'][i]['quantity'];
                            var discount_percent = data['details'][i]['discount_percent'];
                            var total = number_format(price*quantity*(1-discount_percent/100));
                            str += "<tr id='items_"+ticket_card_types_id+"' style='cursor:pointer;' select='off'>"
                                        +"<td style='display:none;'>"+ticket_card_types_id+"</td>"
                                        +"<td style='font-size:11px; color:blue; font-weight:bold;'>"+name+"</td>"
                                        +"<td style='font-size:11px'>"+quantity+"</td>"
                                        +"<td style='text-align:right;font-size:11px'>"+number_format(price)+"</td>"
                                        +"<td style='text-align:right;font-size:11px'>"+number_format(discount_percent)+"%</td>"
                                        +"<td style='text-align:right;font-size:11px'>"+total+"</td>"
                                        +"<td><button class='btn btn-xs' type='button'><span style='color: red;' class='glyphicon glyphicon-remove'></span></button></td>"
                                        +"<td style='display:none;'><input name='ticket_list["+ticket_card_types_id+"][id]' type='hidden' id='ticket_list["+ticket_card_types_id+"][id]' value='"+ticket_card_types_id+"'/></td>"
                                        +"<td style='display:none;'><input name='ticket_list["+ticket_card_types_id+"][quantity]' type='hidden' id='ticket_list["+ticket_card_types_id+"][quantity]' value='"+quantity+"'/></td>"
                                        +"<td style='display:none;'><input name='ticket_list["+ticket_card_types_id+"][price]' type='hidden' id='ticket_list["+ticket_card_types_id+"][price]' value='"+price+"'/></td>"
                                        +"<td style='display:none;'><input name='ticket_list["+ticket_card_types_id+"][detail_id]' type='hidden' id='ticket_list["+ticket_card_types_id+"][detail_id]' value=''/></td>"
                                        +"<td style='display:none;'><input name='ticket_list["+ticket_card_types_id+"][discount_percent]' type='hidden' id='ticket_list["+ticket_card_types_id+"][discount_percent]' value='"+discount_percent+"'/></td>"
                                +  "</tr>";
                             
                       }
                       jQuery("#fillUpDt").html(str);
                       
                       jQuery("#card_type div").each(function(){
                         jQuery(this).css("visibility","hidden");
                       }); 
                       
                       jQuery("#search_customer").css("display","none");
                       jQuery("#remove_customer").css("display","none");
                   }
                }
            });
    }
    
    function export_card_fn()
    {
        if(jQuery("#ticket_online").val().trim()!="")
        {
            if(confirm('Bạn có muốn xuất vé không?'))
            {
                jQuery("#EditTicketCardWicketForm_2").removeAttr("onsubmit");
                jQuery("#EditTicketCardWicketForm_2").submit();
            }
        }
    }
    
</script>