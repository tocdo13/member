<style>
	.room_service_detail,.extra_service_detail,.service_detail{
		display:none;	
	}
	input[type=text]{
		width:100px;
		text-align:right;
		border:none;
	}
	input[type=checkbox]{
		display:none;
	}
	#travellers_id,#order_ids{
		width:120px;
		height:30px;	
	}
	#save,#add_payment{
		height:28px !important;
		width:65px !important;	
	}
	#title{
		background:url(/../../packages/hotel/skins/default/images/iosstyle/item_list_bg.png) repeat-x;	
		height:30px;
	}
	.invoice tr{
		height:30px;	
	}
	.payment{
		border:1px solid silver !important;	
		height:25px;
		width:85px;
	}
	.checked{
		display:inline-block !important;	
	}
	#table_split{
		vertical-align:top;	
	}
	.post_show{
		border:5px solid #E0DFE0;
		position:fixed;
		min-height:400px;
		background:#FFFFFF;
		overflow:auto;
}
	
	#check_add{
		width:300px;
		height:50px;	
	}
	#button_add{
		width:50px;
		height:30px;	
	}
	div{
		
	}
	.date{
		width:22%;float:left;	
	}
	.description{
		width:50%;
		float:left;		
	}
	.amount{
		width:25%;
		float:left;		
	}
	#mask{
		display:none;
		width:100%;
		height:1000px;
		position:absolute;
		top:0px;
		left:0px;
		float:left;
		background:#999999;
		padding:50px 0px;
		font-weight:bold;
		font-size:14px;
	}	
</style>
<script type="text/javascript" src="packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js"></script>
<?php $herf = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'form.php?block_id=429&cmd=group_folio&customer_id='.Url::get('customer_id').'&id='.Url::get('id').''.(Url::get('fast')?'&fast=1':'');?>
<?php $herf_folio_room = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'form.php?block_id=429&cmd=create_folio'.(Url::get('fast')?'&fast=1':'');?>
<?php $_REQUEST['check_payment'] = isset($_REQUEST['check_payment'])?$_REQUEST['check_payment']:0;?>
<div id="mask" class="mask" align="center">
<img src="packages/core/skins/default/images/ajax-loader-big.gif" align="absmiddle" hspace="5" class="displayIn"><br />Đang tải dữ liệu......</div>
<div id="post_show" style="width:940px; margin:auto;">
<form method="post" name="CreateTravellerFolioForm">
    <table width="99%" style="margin:auto " class="invoice">
        <!---<div style="text-align:center; color:#36F; font-size:16px; font-weight:bold; margin-top:0px; text-transform: uppercase;">[[.create_folio.]] [[.group.]]</div>--->
        <tr>
            <td style="float: left;">
                Folio [[.room.]]:
                <select onchange="OpenFolioRoom(this.value,0);" style="margin-right: 10px; color: green ; font-weight: bold;  padding: 5px; border: 1px solid green; width: 170px;">
                    <option value="" style="color: green ; font-weight: bold; line-height: 25px;">[[.preview_order.]]</option>
                    <!--LIST:list_folio_room_in_recode-->
                        <?php if((int)(abs([[=list_folio_room_in_recode.total=]]-[[=list_folio_room_in_recode.amount=]])) >= 1000){ ?>
                            <?php if(isset([[=list_folio_room_in_recode.folio_code=]])){?>
                            <option value="[[|list_folio_room_in_recode.id|]],[[|list_folio_room_in_recode.reservation_traveller_id|]],[[|list_folio_room_in_recode.reservation_room_id|]]" style="color: green ; line-height: 25px;"> - <?php echo 'No.F'.str_pad([[=list_folio_room_in_recode.folio_code=]],6,"0",STR_PAD_LEFT);?> - [[|list_folio_room_in_recode.name|]] - [[.room.]]: [[|list_folio_room_in_recode.room_name|]]</option>
                            <?php } else{?>
                            <option value="[[|list_folio_room_in_recode.id|]],[[|list_folio_room_in_recode.reservation_traveller_id|]],[[|list_folio_room_in_recode.reservation_room_id|]]" style="color: green ; line-height: 25px;"> - <?php echo 'Ref'.str_pad([[=list_folio_room_in_recode.id=]],6,"0",STR_PAD_LEFT);?> - [[|list_folio_room_in_recode.name|]] - [[.room.]]: [[|list_folio_room_in_recode.room_name|]]</option>
                            <?php }?>
                        <?php }?> 
                    <!--/LIST:list_folio_room_in_recode-->
                </select>
                <select onchange="OpenFolioRoom(this.value,1);" style="margin-right: 10px; color: red ; font-weight: bold; padding: 5px; border: 1px solid red; width: 170px; ">
                    <option value="" style="color: red ; font-weight: bold; line-height: 25px;">[[.bill_folio.]]</option>
                    <!--LIST:list_folio_room_in_recode-->
                        <?php if((int)(abs([[=list_folio_room_in_recode.total=]]-[[=list_folio_room_in_recode.amount=]])) < 1000){ ?>
                            <?php if(isset([[=list_folio_room_in_recode.folio_code=]])){?>
                            <option value="[[|list_folio_room_in_recode.id|]],[[|list_folio_room_in_recode.reservation_traveller_id|]],[[|list_folio_room_in_recode.reservation_room_id|]]" style="color: red ; line-height: 25px;"> - <?php echo 'No.F'.str_pad([[=list_folio_room_in_recode.folio_code=]],6,"0",STR_PAD_LEFT);?> - [[|list_folio_room_in_recode.name|]] - [[.room.]]: [[|list_folio_room_in_recode.room_name|]]</option>
                            <?php } else{?>
                            <option value="[[|list_folio_room_in_recode.id|]],[[|list_folio_room_in_recode.reservation_traveller_id|]],[[|list_folio_room_in_recode.reservation_room_id|]]" style="color: red ; line-height: 25px;"> - <?php echo 'Ref'.str_pad([[=list_folio_room_in_recode.id=]],6,"0",STR_PAD_LEFT);?> - [[|list_folio_room_in_recode.name|]] - [[.room.]]: [[|list_folio_room_in_recode.room_name|]]</option>
                            <?php } ?>
                        <?php }?>
                    <!--/LIST:list_folio_room_in_recode-->
                </select>
            </td>
            <td style="float: right;">
                Folio [[.group.]]:
                <select onchange="OpenFolio(this.value,1);" style="float: right; margin-right: 10px; color: red ; font-weight: bold; padding: 5px; border: 1px solid red; width: 170px; ">
                    <option value="" style="color: red ; font-weight: bold; line-height: 25px;">[[.bill_folio.]]</option>
                    <!--LIST:folio-->
                        <?php if((int)(abs([[=folio.total=]]-[[=folio.amount=]])) < 1000){ ?>
                            <?php if(isset([[=folio.folio_code=]])){?>
                            <option value="[[|folio.id|]],[[|folio.traveller_id|]]" style="color: red ; line-height: 25px;"> - <?php echo 'No.F'.str_pad([[=folio.folio_code=]],6,"0",STR_PAD_LEFT);?></option>
                            <?php } else{?>
                            <option value="[[|folio.id|]],[[|folio.traveller_id|]]" style="color: red ; line-height: 25px;"> - <?php echo 'Ref'.str_pad([[=folio.id=]],6,"0",STR_PAD_LEFT);?></option>
                            <?php } ?>
                        <?php }?>
                    <!--/LIST:folio-->
                </select>
                <select onchange="OpenFolio(this.value,0);" style="float: right; margin-right: 10px; color: green ; font-weight: bold;  padding: 5px; border: 1px solid green; width: 170px;">
                    <option value="" style="color: green ; font-weight: bold; line-height: 25px;">[[.preview_order.]]</option>
                    <!--LIST:folio-->
                        <?php if((int)(abs([[=folio.total=]]-[[=folio.amount=]])) >= 1000){ ?>
                            <?php if(isset([[=folio.folio_code=]])){?>
                            <option value="[[|folio.id|]],[[|folio.traveller_id|]]" style="color: green ; line-height: 25px;"> - <?php echo 'No.F'.str_pad([[=folio.folio_code=]],6,"0",STR_PAD_LEFT);?></option>
                            <?php } else{?>
                            <option value="[[|folio.id|]],[[|folio.traveller_id|]]" style="color: green ; line-height: 25px;"> - <?php echo 'Ref'.str_pad([[=folio.id=]],6,"0",STR_PAD_LEFT);?></option>
                            <?php }?>
                        <?php }?> 
                    <!--/LIST:folio-->
                </select>
            </td>
        </tr>
    </table>
	
	<div><?php echo Form::$current->error_messages();?></div>
	<table width="99%" border="1" style=" border:1px solid silver; margin:auto" class="invoice">
	<tr id="title">
        <td width="50%">
            [[.code.]]:<span id="rr_id" style="text-align:left; color:#0A3680;"> <?php echo (Url::get('add_payment')?Url::get('add_payment'):(Url::get('id')?Url::get('id'):''));?></span>
                    
        </td>
        <td width="50%"><span style="text-align:left; color:#0A3680;">
            <!--LIST:folio--> 
            <?php if([[=folio.folio_code=]]){?>
                <?php if(Url::get('folio_id') && [[=folio.id=]]==Url::get('folio_id')){echo Portal::language('folio').'_'.[[=folio.folio_code=]];}?> 
            <?php } else {?>
                <?php if(Url::get('folio_id') && [[=folio.id=]]==Url::get('folio_id')){echo Portal::language('folio').'_'.[[=folio.id=]];}?> 
            <?php } ?>    	
           <!--/LIST:folio-->
            </span>
                <?php if((User::can_add(false,ANY_CATEGORY) and !isset($_REQUEST['folio_id'])) OR (User::can_edit(false,ANY_CATEGORY) and isset($_REQUEST['folio_id'])) ){ ?>
                <a href="#" class="w3-btn w3-indigo w3-hover-indigo w3-hover-text-white" name="add_payment" type="button" id="add_payment" value="" style="float:right;display:none;margin-right: 5px; text-transform: uppercase; text-decoration: none;" >[[.add.]]</a>
                <a href="#"  style="float:right;margin-right: 5px; text-transform: uppercase; text-decoration: none;"><input name="save" type="button" id="save" value="" class="w3-btn w3-blue w3-hover-blue w3-text-white w3-hover-text-white" onclick="SubmitForm('save');" >[[.Save_and_stay.]]</a>
                <a href="#" class="w3-btn w3-orange w3-hover-orange w3-text-white w3-hover-text-white" name="save_and_print" type="button" id="save_and_print" value="" style="float:right;height:28px; margin-right: 5px; text-transform: uppercase; text-decoration: none;" onclick="SubmitForm('save_and_print');" >[[.save_view.]]</a>
                <?php } ?>
                <a href="<?php echo $herf;?>&act=new&portal_id=<?php echo PORTAL_ID;?>" ><input name="create_folio" type="button" value="[[.create_bill.]]" id="create_folio" class="w3-btn w3-green w3-hover-green w3-hover-text-white" style="float:right; height:28px;margin-right: 5px; text-transform: uppercase; text-decoration: none;" title="[[.create_bill.]]"/></a> 
             <div id="check_add" style="display:none;position:absolute;top:auto;left:auto;border:1px solid #000000;padding:10px;text-align:center;background:#FFFF99;">
				[[.add_order_id.]]: <select name="order_ids" id="order_ids"></select>
				<input type="button" name="add" value="[[.add.]] " id="button_add" tabindex="-1" onclick="var rr_id=jQuery('#rr_id').html();var tr_id=jQuery('#traveller_id').val(); var oder_id = jQuery('#order_ids').val();jQuery('#post_show').remove();openWindowUrl('http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=504&cmd=add_payment&add_payment='+oder_id+'&traveller_id='+tr_id+'&rr_id='+rr_id,Array('','','80','210','950','500'));" style="color:#000066;font-weight:bold;">
				<a class="close" onclick="jQuery('#check_add').hide();$('order_ids').value='';">[[.close.]]</a>
    		 </div>
        </td>
    </tr>
    <tr>
        <td style="vertical-align: bottom;">
            <table style="width: 100%;">
                <tr>
                    <td style="border-right: 1px solid #cdcdcd;">
                        <?php
                            foreach([[=all_service=]] as $val){
                          ?>
                         <input name="all_room_rate" value="<?php echo $val; ?>" type="button"  id="all_room_rate"  onclick="checkSplit('ALL','<?php echo $val; ?>','');" title="[[.pay_all_room_amount.]]" style="margin:2px 0;"/>
                         
                       <?php } ?>
                    </td>
                    <td width="32" style="text-align: center;">
                        <a href="#" name="all" type="image" id="all" onclick="jQuery('#ALL').attr('checked',true);checkSplit('ALL','','');return false;"  title="transfer_all" style=" color:#676E73;"><i class="fa fa-arrow-circle-right w3-hover-text-black" style="font-size: 23px;"></i></a><input type="checkbox" id="ALL"/>
                    </td>
                </tr>
            </table>
        </td>
        <td style="vertical-align: bottom;">
            <div style="width: 100%; margin: 0px auto; float: left;">
                <span>&nbsp;&nbsp;[[.customer.]]: </span>[[|customer_name|]]
                <input name="customer_id" type="hidden" id="customer_id" value="[[|customer_id|]]" />
                <input name="traveller_folio" type="hidden" id="traveller_folio"  />                
            </div>
            <div style="width: 100%; margin: 0px auto; float: left;">
                <span style="float: left; width: 80px;">&nbsp;&nbsp;[[.select_traveller.]]: </span>
                <select name="travellers_id" id="travellers_id" onchange="jQuery('#traveller_id').val(this.value);window.location='<?php echo 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'form.php?block_id=429&cmd=group_folio&id='.Url::get('id');?>&traveller_id='+this.value+'&customer_id='+jQuery('#customer_id').val();" style="width:120px; height: 23px; float: left;"></select>
                <?php if( (!isset($_REQUEST['check_payment']) OR (isset($_REQUEST['check_payment']) and $_REQUEST['check_payment']!=1)) and Url::get('folio_id') ){ ?>
                <a class="w3-button w3-gray w3-hover-gray" href="#" type="button" value="[[.change_traveller.]]" style="padding: 3px; height: 23px; margin-left: 2px; float: left; text-decoration: none;" onclick="jQuery('#change_traveller_folio').css('display','');"><i class="fa fa-refresh" style="font-size: 18px;"></i> [[.change_traveller.]]</a>
                <select name="change_traveller_folio" id="change_traveller_folio" style="width: 120px; height: 25px; margin-left: 2px; float: left; display: none;" onchange="fun_change_traveller_folio(this.value,<?php echo Url::get('folio_id'); ?>);"></select>
                <?php } ?>
            </div>
            <?php if(SETTING_POINT==1){ ?>
            <div style="width: 100%; margin: 5px auto; float: left;">
                <span style="float: left;">
                    <span style="float: left; width: 80px;"> [[.member_code.]]: </span>
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
                </span>
            </div>
            <?php } ?>
            <input name="traveller_id" value="<?php echo Url::get('traveller_id'); ?>" type="hidden" id="traveller_id" />
       </td>
    </tr>
     <tr>
        <td style="vertical-align:top;">
        	<table border="1" width="100%" style="border:1px solid silver;margin:auto;" class="invoice" cellpadding="3">              
                <?php $type=''; $rr = 0;?> 
                <!--LIST:folios-->  
                	<?php if($rr != [[=folios.id=]]){?> 
                	<tr id="title_reservation_room_[[|folios.id|]]" style="background:#f1f1f1;">
                        <td width="400"><span><b><em>[[|folios.room_name|]] </em></b></span></td>
                        <td width="30"><a href="#" name="reservation_room_[[|folios.id|]]" type="image" id="reservation_room_[[|folios.id|]]" onclick="checkSplit('[[|folios.id|]]','ALL_ROOM','');return false;" style="padding-left: 3px;"><i class="fa fa-arrow-circle-right w3-text-gray w3-hover-text-black" style="font-size: 23px;"></i></a>
                        <input type="checkbox" id="reservation_room_[[|folios.id|]]"/></td>
                    </tr>
                    <?php }?>    
                	<!--LIST:folios.items-->	
                <?php if($type != [[=folios.items.type=]] || $rr != [[=folios.id=]]){ $rr = [[=folios.id=]]; $type = [[=folios.items.type=]]; ?>       
                        <tr id="title_[[|folios.items.type|]]_[[|folios.id|]]">
                        	<td width="400">
                                <input name="add_item_[[|folios.items.type|]]_[[|folios.id|]]" type="image" id="add_item_[[|folios.items.type|]]_[[|folios.id|]]" 
                                    src="packages/hotel/skins/default/images/icons/add_item.gif" 
                                    onclick="ItemDetail('[[|folios.items.type|]]_[[|folios.id|]]');return false;" style="float:left;" />
                                    <span> &nbsp; &nbsp; <b><em>[[|folios.items.type|]]</em></b></span>
                            	<input name="total_[[|folios.items.type|]]_[[|folios.id|]]" id="total_[[|folios.items.type|]]_[[|folios.id|]]" value="" style="float:right; text-align:right; border:none;" align="right" />
                            </td>
                            <td width="30">
                                <a href="#" name="all_[[|folios.items.type|]]_[[|folios.id|]]" type="image" id="all_[[|folios.items.type|]]_[[|folios.id|]]" 
                                onclick="jQuery('#All_[[|folios.items.type|]]_[[|folios.id|]]').attr('checked',true);jQuery('.detail_[[|folios.items.type|]]_[[|folios.id|]]').attr('checked',true);checkSplit('[[|folios.id|]]','[[|folios.items.type|]]','');return false;" 
                                style="padding-left: 3px; border:none;" ><i class="fa fa-arrow-circle-right w3-text-gray w3-hover-text-black" style="font-size: 23px;"></i></a>
                            <input type="checkbox" id="ALL_[[|folios.items.type|]]_[[|folios.id|]]"/></td>
                        </tr>
                <?php }?>
                        <tr style="display:none;"><td colspan="2"><input name="foc_[[|folios.id|]]" type="hidden" value="[[|folios.foc|]]" id="foc_[[|folios.id|]]" ><input name="foc_all_[[|folios.id|]]" type="hidden" value="[[|folios.foc_all|]]" id="foc_all_[[|folios.id|]]" ></td></tr>
                        <tr id="tr_[[|folios.items.type|]]_[[|folios.items.id|]]" class="[[|folios.items.type|]]_[[|folios.id|]]" style="display:none;" lang="[[|folios.items.status|]]">  
                        <td width="400">
                            <div class="item-body" style="width:100%;">	
                                <div class="date" id="date_[[|folios.items.type|]]_[[|folios.items.id|]]">[[|folios.items.date|]]</div>
                                <input type="hidden" id="full_date_[[|folios.items.type|]]_[[|folios.items.id|]]" value="[[|folios.items.full_date|]]"/>
                                <div class="description" id="description_[[|folios.items.type|]]_[[|folios.items.id|]]" style="width:150px !important;">[[|folios.items.description|]]</div>
                                <?php if([[=folios.items.type=]]=='LAUNDRY'){
																$her = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=laundry_invoice&cmd=edit&id='.[[=folios.items.id=]];
																echo '<a target="_blank" href="'.$her.'" style="float:left">#LD_'.[[=folios.items.position=]].'</a>';
															}else if([[=folios.items.type=]]=='MINIBAR'){
																$her = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=minibar_invoice&cmd=edit&id='.[[=folios.items.id=]];
																echo '<a target="_blank" href="'.$her.'" style="float:left">#MN_'.[[=folios.items.position=]].'</a>';
															}else if([[=folios.items.type=]]=='EQUIPMENT'){
																$her = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=equipment_invoice&cmd=edit&id='.[[=folios.items.id=]];
																echo '<a target="_blank" href="'.$her.'" style="float:left">#EQ_'.[[=folios.items.position=]].'</a>';
															}else if([[=folios.items.type=]]=='BAR'){
																$her = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=touch_bar_restaurant&cmd=detail&'.md5('act').'='.md5('print_bill').'&'.md5('preview').'=1&id='.[[=folios.items.id=]].[[=folios.items.link=]];
																echo '<a target="_blank" href="'.$her.'" style="float:left">#'.[[=folios.items.code=]].'</a>';
															}else if([[=folios.items.type=]]=='TICKET'){
																$her = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=ticket_invoice_group&cmd=bill&'.md5('act').'='.md5('print').'&id='.[[=folios.items.id=]];
																echo '<a target="_blank" href="'.$her.'" style="float:left">#'.[[=folios.items.id=]].'</a>';
															}else if([[=folios.items.type=]]=='EXTRA_SERVICE'){
																$her = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=extra_service_invoice&cmd=view_receipt&id='.[[=folios.items.ex_id=]];
																echo '<a target="_blank" href="'.$her.'" style="float:left">#'.[[=folios.items.ex_bill=]].'</a>';
															}//else if([[=folios.items.type=]]=='MASSAGE'){
															//	$her = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=massage_daily_summary&cmd=invoice&room_id='.[[=folios.items.room_id=]].'&id='.[[=folios.items.id=]];
															//	echo '<a target="_blank" href="'.$her.'" style="float:left">#'.[[=folios.items.id=]].'</a>';
															//}	?>           
                                <div class="amount" style="float:right;"><?php echo (System::Display_number(System::calculate_number([[=folios.items.net_amount=]])));?><input name="amount_[[|folios.items.type|]]_[[|folios.items.id|]]" type="text" id="amount_[[|folios.items.type|]]_[[|folios.items.id|]]" value="[[|folios.items.net_amount|]]" style="border:none;font-size:11px; text-align:center;float:right; display:none;" readonly="readonly" class="amount" lang="[[|folios.items.service_rate|]]" alt="[[|folios.items.tax_rate|]]" /> </div>
                            </div>
                        </td>
                        <td width="30"><a href="#" name="split_[[|folios.items.type|]]_[[|folios.items.id|]]" type="image" id="split_[[|folios.items.type|]]_[[|folios.items.id|]]" onclick="jQuery('#detail_[[|folios.items.type|]]_[[|folios.items.id|]]').attr('checked',true);checkSplit('[[|folios.id|]]','[[|folios.items.type|]]','[[|folios.items.type|]]_[[|folios.items.id|]]');return false;" style="padding-left: 3px;" lang="[[|folios.items.percent|]]" ><i class="fa fa-arrow-circle-right w3-text-gray w3-hover-text-black" style="font-size: 23px;"></i></a>
                        <input type="checkbox" id="[[|folios.items.type|]]_[[|folios.items.id|]]" lang="[[|folios.items.type|]]" alt="[[|folios.items.id|]]" value="[[|folios.items.amount|]]" name="[[|folios.items.id|]]" class="detail_[[|folios.items.type|]]_[[|folios.id|]]"/></td>
                        </tr>
                <!--/LIST:folios.items-->
             <!--/LIST:folios-->           
            </table> 
        </td>
        <td style="vertical-align:top;">
        	 <input name="all_type" type="hidden" id="all_type" value="ROOM,ROOM_SERVICE,SERVICE,MINIBAR,LAUNDRY,EQUIPMENT,BAR,EXTRA_SERVICE,TELEPHONE,MASSAGE,DISCOUNT,DEPOSIT,DEPOSIT_GROUP,TICKET,KARAOKE,VE,PACKAGE" style="width:300px;"/>
            <input name="action" type="hidden" value="0" id="action" />
            <input name="act" type="hidden" value="" id="act" />
            <input name="arr_ids" type="hidden" value="" id="arr_ids" />
        	<table border="1" width="100%" cellpadding="3" style="border:1px solid silver;margin:auto;" id="table_split">
            
            </table>
            <div id="hidden" style="display:none;">
           <table border="1" width="100%" style="border:1px solid silver;margin:auto;">
            	<tr>
                    <td style="width:70%;"><span style="float:right;"><b><em>[[.total.]]:&nbsp; </em></b></span>&nbsp;</td>   
                    <td style="width:25%;"><input name="total_amount" type="text" id="total_amount" value="0" style="border:none; width:100px; height:25px;" readonly="readonly"/></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="width:70%;"><span style="float:right;"><b><em>[[.service_charge_amount.]]:&nbsp; </em></b></span>&nbsp;</td>
                    <td style="width:25%;"><input name="service_charge_amount" type="text" id="service_charge_amount" value="0" style="border:none; width:100px; height:25px;" readonly="readonly"/></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="width:70%;"><span style="float:right;"><b><em>[[.tax_amount.]]:&nbsp; </em></b></span>&nbsp;</td>
                    <td style="width:25%;"><input name="tax_amount" type="text" id="tax_amount" value="0" style="border:none; width:100px; height:25px;" readonly="readonly" /></td>
                    <td></td>
                </tr>
<tr id="tr_total_vat" style="display:none;">
                    <td style="width:70%;"><span style="float:right;"><b><em>[[.total_vat.]]:&nbsp; </em></b></span>&nbsp;</td>
                    <td style="width:25%;"><input name="total_vat" type="text" id="total_vat" value="0" style="border:none; width:100px; height:25px;" readonly="readonly" /></td>
                    <td></td>
                </tr>
                 <tr>
                    <td style="width:70%;"><span style="float:right;"><b><em>[[.total_payment.]]:&nbsp; </em></b></span>&nbsp;</td>
                    <td style="width:25%;"><input name="total_payment" type="text" id="total_payment" value="0" style="border:none; width:100px; height:25px;" readonly="readonly" /></td>
                    <td></td>
                </tr>
                
            </table>
            </div>
            <div id="payment_bound">
           		<fieldset>
            	<legend><b><i>[[.payment.]]</i></b></legend>
                <div id="payment_method_bound">
                    <b><em>[[.balance.]]:&nbsp; </em></b><input name="balance" type="text" id="balance" value="0" style="border:none; width:100px; height:25px; color: red; text-align: left;;" readonly="readonly" />
                    <?php if((User::can_add(false,ANY_CATEGORY) and !isset($_REQUEST['folio_id'])) OR (User::can_edit(false,ANY_CATEGORY) and isset($_REQUEST['folio_id']) and $_REQUEST['check_payment']==0 ) OR (User::can_delete(false,ANY_CATEGORY) and isset($_REQUEST['folio_id']) and $_REQUEST['check_payment']==1) OR (User::can_admin(false,ANY_CATEGORY) and isset($_REQUEST['folio_id'])) ){ ?>
                        <a href="#" class="w3-btn w3-lime w3-hover-lime" name="payment" type="button" id="payment" value="[[.payment.]]" onclick="SubmitForm('payment');" style="height:34px; border: 1px solid lime; text-transform: uppercase; text-decoration: none;"><i class="fa fa-dollar" style="font-size: 18px;"></i> <span>[[.payment.]]</span></a>
                    <?php }?>    
                    <?php $hef = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=view_traveller_folio&cmd=group_invoice&customer_id='.Url::get('customer_id').'&id='.Url::get('id').'&folio_id='.Url::get('folio_id').'';?>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                
                <?php if(Url::get('folio_id')){?>
                <a target="_blank" href="<?php echo $hef;?>&portal_id=<?php echo PORTAL_ID;?>" class="w3-btn w3-red w3-hover-red" style="text-transform: uppercase; text-decoration: none; height: 34px; float: right;" ><i class="fa fa-eye" style="font-size: 18px;"></i> <!---<input  type="button" id="view_invoice_#xxxx#" title="[[.view_order.]]" style="float:right;"/>--->[[.view_folio.]]</a>
                <?php }?>
                </div>
          		</fieldset>
          </div>  
        </td>
    </tr>
</table>
</form>
</div>
<script>
    var traveller_folio_check =  [[|traveller_folio_check|]] ;
    var traveller_id = '<?php echo Url::get('traveller_id')?Url::get('traveller_id'):'';?>';
    var member_list = [[|member_list|]];
    var folio_ids = '<?php echo Url::get('folio_id')?Url::get('folio_id'):'00'; ?>';
    var cmd = '<?php echo Url::get('cmd')?Url::get('cmd'):'';?>';
    var check_payment = <?php echo Url::get('check_payment')?Url::get('check_payment'):0; ?>;
    traveller_folios = {};
    traveller_folio = {};
    folio_other={};
    total = {};
    amount = [];
    traveller_folio = [[|traveller_folios_js|]];// Cac ban ghi cua folio nay
    folio_other = [[|folio_other_js|]];// Cac ban ghi da dc thanh toasn boi folio khac
    var balance=<?php echo [[=balance=]] ?>;
    <?php if(SETTING_POINT==1){ ?>
    if(folio_ids=='00' && traveller_id != ''){
                
            jQuery("#member_code").val(member_list[traveller_id]['member_code']);
            jQuery("#member_level_id").val(member_list[traveller_id]['member_level_id']);
            jQuery("#create_member_date").val(member_list[traveller_id]['create_member_date']);
        }
    <?php } ?>
	for(var j in traveller_folio){
		traveller_folios[traveller_folio[j]['type']+'_'+traveller_folio[j]['id']] = traveller_folio[j];
	}    
	DrawSplitOrder();
	for(j in items_js){
		check = 0;
		for(var k in items_js[j]['items']){
			items = items_js[j]['items'][k];
            
            //new
            if(items['status']==0){
				if(total[''+items['type']+'_'+j+'']){
					total[''+items['type']+'_'+j+''] += to_numeric(items['amount']);
				}else{
					total[''+items['type']+'_'+j+''] = to_numeric(items['amount']);
				}
			}else{
				var description = jQuery('#description_'+items['type']+'_'+items['id']).html();
				jQuery('#description_'+items['type']+'_'+items['id']).html(description+'  '+items['href']);
				jQuery('#tr_'+items['type']+'_'+items['id']).css({'background':'#c7c7c7'});
				jQuery('#amount_'+items['type']+'_'+items['id']).css({'background':'#c7c7c7'});
				jQuery('#'+items['type']+'_'+items['id']).attr('checked',true);
				if(total[''+items['type']+'_'+j+'']){
				}else{total[''+items['type']+'_'+j+''] =  0;
				}
			}
			jQuery('#total_'+items['type']+'_'+j).val(number_format(total[''+items['type']+'_'+j+'']));
            /** START DOI PHONG Dat gom cac khoan cua cac phong chang cu sang phong moi**/
		}
	}
	if(traveller_id != '' && cmd == 'create_folio'){
		jQuery('#travellers_id').val(traveller_id);
		jQuery('#traveller_id').val(traveller_id);
	}
	if(traveller_id != '' && cmd == 'add_payment'){
		jQuery('#traveller_id').val(traveller_id);
	}
	if(cmd == 'add_payment'){
		jQuery('#travellers_id').css('display','none');
		jQuery('#table_payment').css('display','none');	
		jQuery('#add_payment').css('display','none');	
		jQuery('#select_title').css('display','none');
	}
	function checkSplit(rr_id,type,obj)
    {
		var traveller_id = jQuery("#travellers_id").val();
     //if(traveller_id!=0){
        if(rr_id=='ALL')
        {            
			if(type=='ROOM' || type=='MINIBAR' || type=='LAUNDRY' || type=='EXTRA_SERVICE' || type=='TICKET' || type=='EQUIPMENT'
            || type=='VE' || type=='BAR' || type=='KARAOKE' || type=='SERVICE' || type=='ROOM_SERVICE' || type=='MASSAGE'
           || type=='TENNIS' || type=='SWIMMING_POOL' || type=='SHOP' || type=='TELEPHONE' || type=='DISCOUNT' || type=='DEPOSIT' || type=='DEPOSIT_GROUP' || type=='PACKAGE'
            )
            {
				for(var j in items_js)
                {
					for(var k in items_js[j]['items'])
                    {
						items = items_js[j]['items'][k];
						if(items['status']==0)
                        {
							if(traveller_folios[type+'_'+items['id']])
                            {
							}
                            else if(items['type']==type)
                            {
                                if(type=='MASSAGE'){
                                    traveller_folios[items['type']+'_'+items['description']] = items;
                                }
                                else{
								traveller_folios[items['type']+'_'+items['id']] = items;
							    } 
                            }
						}
					}
				}
			}
            else
            {
				for(var j in items_js)
                {
					for(var k in items_js[j]['items']){
						items = items_js[j]['items'][k];
						if(items['status']==0)
                        {
							if(traveller_folios[items['type']+'_'+items['id']])
                            {
							}
                            else
                            {
							    if(type=='MASSAGE'){
                                    traveller_folios[items['type']+'_'+items['description']] = items;
                                }
                                else 	
                                traveller_folios[items['type']+'_'+items['id']] = items;
							}
						}
					}
				}
			}
		}
        else 
        {
			if(obj==''){
				if(type=='ALL_ROOM')
                {
					all_type = jQuery('#all_type').val();
					all_type = all_type.split(',');
                    console.log(all_type);
					for(var j in all_type){
						var type = all_type[j];
						jQuery('.detail_'+type+'_'+rr_id).each(function(){
							var id = this.id;
							if(jQuery('#tr_'+id).attr('lang')==0){
								if(traveller_folios[id]){
									
								}else{
									itm=[];
									itm['id'] = this.alt;
									itm['type'] = this.lang;
									itm['percent'] = to_numeric(jQuery('#split_'+id).attr('lang'));
									itm['amount'] = to_numeric(jQuery('#'+id).val());
									itm['service_rate'] = to_numeric(jQuery('#amount_'+id).attr('lang'));
									itm['tax_rate'] = to_numeric(jQuery('#amount_'+id).attr('alt'));
									itm['description'] = jQuery('#description_'+id).html();
									itm['date'] = jQuery('#date_'+id).html();
                                    itm['full_date'] = jQuery('#full_date_'+id).val();
									itm['rr_id'] = (typeof items_js[rr_id] != 'undefined' && typeof items_js[rr_id]['items'][id] != 'undefined')?items_js[rr_id]['items'][id]['rr_id']:rr_id;
									traveller_folios[id] = itm;
								}
							}
						});	
					}
				}else{
					jQuery('.detail_'+type+'_'+rr_id).each(function(){
						var id = this.id;
						if(jQuery('#tr_'+id).attr('lang')==0){
							if(traveller_folios[id]){
								
							}else{
								itm=[];
								itm['id'] = this.alt;
								itm['type'] = this.lang;
								itm['percent'] = to_numeric(jQuery('#split_'+id).attr('lang'));
								itm['amount'] = to_numeric(jQuery('#'+id).val());
								itm['service_rate'] = to_numeric(jQuery('#amount_'+id).attr('lang'));
								itm['tax_rate'] = to_numeric(jQuery('#amount_'+id).attr('alt'));
								itm['description'] = jQuery('#description_'+id).html();
								itm['date'] = jQuery('#date_'+id).html();
                                itm['full_date'] = jQuery('#full_date_'+id).val();
								itm['rr_id'] = (typeof items_js[rr_id] != 'undefined' && typeof items_js[rr_id]['items'][id] != 'undefined')?items_js[rr_id]['items'][id]['rr_id']:rr_id;
								traveller_folios[id] = itm;
							}
						}
					});	
				}
			}
            else
            {
				var id = jQuery('#'+obj).attr('id');
				var item_id = jQuery('#'+obj).attr('alt');
				if(jQuery('#tr_'+id).attr('lang')==0){
					if(traveller_folios[id]){
						
					}else{
						itm=[];
						itm['id'] = item_id;
						itm['type'] = jQuery('#'+obj).attr('lang');
						itm['percent'] = to_numeric(jQuery('#split_'+id).attr('lang'));
						itm['amount'] = to_numeric(jQuery('#'+id).val());
						itm['service_rate'] = to_numeric(jQuery('#amount_'+id).attr('lang'));
						itm['tax_rate'] = to_numeric(jQuery('#amount_'+id).attr('alt'));
						itm['description'] = jQuery('#description_'+id).html();
						itm['date'] = jQuery('#date_'+id).html();
                        itm['full_date'] = jQuery('#full_date_'+id).val();
						itm['rr_id'] = rr_id;
						traveller_folios[id] = itm;
					}
				}
			}
		}
		DrawSplitOrder();
      // }
       //else{
       //  alert("Bạn cần chọn tên khách thanh toán trước khi tạo hóa đơn !");
       //} 
	}
    function DrawSplitOrder(){
    	jQuery('#table_split').html('<tr style="text-align:center;"><td style="width:50%;"><b>[[.detail.]]</b></td><td style="width:20%;"><b>[[.percent.]]</b></td><td style="width:25%;"><b>[[.amount.]]</b></td><td>&nbsp;</td></tr>');
    	var deleteButton = '<img align="left" src="packages/core/skins/default/images/buttons/delete.gif" title="delete">';
    	var type = '';var typ = ''; amount = {};
    	travellers = {};
    	all_type = jQuery('#all_type').val();
    	all_type = all_type.split(',');
        //console.log(traveller_folios);
    	for(var j in all_type){
    		for(var i in traveller_folios){
    			if(all_type[j] == traveller_folios[i]['type']){
    				travellers[i] = traveller_folios[i];
    				if(amount[''+all_type[j]+'']){
    					amount[''+all_type[j]+''] += to_numeric(traveller_folios[i]['amount']);
    				}else{
    					amount[''+all_type[j]+''] = to_numeric(traveller_folios[i]['amount']);
    				}
    			}
    		}
    	}
    	traveller_folios = travellers;
    	percent = 100;
    	for(var i in traveller_folios){
    		folios = traveller_folios[i];
    		/*if(folios['date']){
    		}else{
    			folios['date'] = '';
    		}*/
    		var k = 0;
    		if(type!=folios['type']){
    			type=folios['type'];
    			var str = '<tr id="split_expan_'+folios['type']+'"><td style="width:50%;" colspan="2"><input name="add_item_'+folios['type']+'" type="image" id="add_item_'+folios['type']+'" src="packages/hotel/skins/default/images/icons/add_item.gif" onclick="ItemDetail(\''+folios['type']+'\');return false;" style="float:left;line-height:30px;" /><input name="title_expan_'+folios['type']+'" type="text" value="'+folios['type']+'" style="width:150px;text-align:left; float:left;margin-left:30px;" readonly="readonly"></td>';
    			str += '<td style="width:25%; text-align: right; padding-right: 13px;"><input name="split_expan_amount_'+folios['type']+'" type="text" id="split_expan_amount_'+folios['type']+'" value="'+number_format(amount[''+type+''])+'" style="width:90px;" readonly="readonly"></td>';
    			
                    <?php if((User::can_add(false,ANY_CATEGORY) and !isset($_REQUEST['folio_id'])) OR (User::can_edit(false,ANY_CATEGORY) and isset($_REQUEST['folio_id']) and $_REQUEST['check_payment']==0) OR (User::can_delete(false,ANY_CATEGORY) and isset($_REQUEST['folio_id']) and $_REQUEST['check_payment']==1) ){ ?>
                        str += '<td onclick="DeleteItem(\''+folios['type']+'\',\'\',\'\');">'+deleteButton+'</td>';
                    <?php }else{ ?>
                        str += '<td></td>';
                    <?php } ?>
    			str += '<td style="display:none;"></td></tr>';
    			var string = '<tr id="'+folios['type']+'_split_'+folios['id']+'" class="'+folios['type']+'" style="display:none;" lang="'+folios['id']+'"><td style="width:50%;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][id]" type="text" id="traveller_folio['+folios['id']+'_'+folios['type']+'][id]" value="'+folios['id']+'" style="display:none;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][type]" type="text" id="type_'+folios['id']+'_'+folios['type']+'" value="'+folios['type']+'" style="display:none;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][type]" type="text" id="type_'+folios['id']+'_'+folios['type']+'" value="'+folios['type']+'" style="display:none;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][date]" type="text" id="date_'+folios['id']+'_'+folios['type']+'" value="'+folios['date']+'" style="width:30%; height:26px; font-size:13px; text-align:left;" readonly="readonly"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][full_date]" type="hidden" id="full_date_'+folios['id']+'_'+folios['type']+'" value="'+folios['full_date']+'" readonly="readonly"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][description]" type="text" id="description_'+folios['id']+'_'+folios['type']+'" value="'+folios['description']+'" style="width:70%; height:26px; font-size:13px; text-align:left;" readonly="readonly"></td>';
    			string += '<td style="width:20%;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][percent]" type="text" id="percent_'+folios['id']+'_'+folios['type']+'" value="'+folios['percent']+'" onkeyup="ChangeAmount(this,\''+folios['type']+'\','+folios['id']+',\'percent\');" onfocus="jQuery(this).css(\'border\',\'1px solid silver\');" class="percent_payment" style="width:50px;" <?php if((User::can_add(false,ANY_CATEGORY) and !isset($_REQUEST['folio_id'])) OR (User::can_edit(false,ANY_CATEGORY) and isset($_REQUEST['folio_id'])) ){ ?> <?php }else{ echo 'readonly="readonly"'; } ?> /> </td>';
    			//start:KID them input co id = amount2_... de lam chuc nang khong cho nhap so lon hon hien tai
                string += '<td style="width:25%;text-align: right; padding-right: 13px;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][amount]" type="text" id="amount_'+folios['id']+'_'+folios['type']+'" value="'+number_format(folios['amount'])+'" onkeyup="ChangeAmount(this,\''+folios['type']+'\','+folios['id']+',\'amount\'); this.value=number_format(this.value);" onfocus="jQuery(this).css(\'border\',\'1px solid silver\');" style="width:90px;"><input type="hidden" id="amount2_'+folios['id']+'_'+folios['type']+'" value="'+number_format(folios['amount'])+'"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][service_rate]" type="text" value="'+folios['service_rate']+'" style="display:none;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][tax_rate]" type="text" value="'+folios['tax_rate']+'" style="display:none;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][rr_id]" type="text" value="'+folios['rr_id']+'" style="display:none;"></td>';
    			//end:KID them input co id = amount2_... de lam chuc nang khong cho nhap so lon hon hien tai
                    <?php if((User::can_add(false,ANY_CATEGORY) and !isset($_REQUEST['folio_id'])) OR (User::can_edit(false,ANY_CATEGORY) and isset($_REQUEST['folio_id']) and $_REQUEST['check_payment']==0) OR (User::can_delete(false,ANY_CATEGORY) and isset($_REQUEST['folio_id']) and $_REQUEST['check_payment']==1) ){ ?>
                    string += '<td onclick="DeleteItem(\''+folios['type']+'\','+folios['id']+','+folios['percent']+');">'+deleteButton+'</td>';
                    <?php }else{ ?>
                    string += '<td></td>';
                    <?php } ?>
                string += '<td style="display:none;"></td></tr>';
    			jQuery('#table_split').append(str);
    			jQuery('#table_split').append(string);
    			k++;
    		}
            else
            {
    			var string = '<tr id="'+folios['type']+'_split_'+folios['id']+'" class="'+folios['type']+'" style="display:none;" lang="'+folios['id']+'"><td style="width:50%;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][id]" type="text" id="traveller_folio['+folios['id']+'_'+folios['type']+'][id]" value="'+folios['id']+'" style="display:none;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][type]" type="text" id="type_'+folios['id']+'_'+folios['type']+'" value="'+folios['type']+'" style="display:none;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][date]" type="text" id="date_'+folios['id']+'_'+folios['type']+'" value="'+folios['date']+'" style="width:30%; height:26px; font-size:13px; text-align:left;" readonly="readonly"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][full_date]" type="hidden" id="full_date_'+folios['id']+'_'+folios['type']+'" value="'+folios['full_date']+'" readonly="readonly"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][description]" type="text" id="description_'+folios['id']+'_'+folios['type']+'" value="'+folios['description']+'" style="width:70%; height:26px; font-size:13px; text-align:left;" readonly="readonly"></td>';
    			string += '<td style="width:20%;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][percent]" type="text" id="percent_'+folios['id']+'_'+folios['type']+'" value="'+folios['percent']+'" onkeyup="ChangeAmount(this,\''+folios['type']+'\','+folios['id']+',\'percent\');" onfocus="jQuery(this).css(\'border\',\'1px solid silver\');" class="percent_payment" style="width:50px;" <?php if((User::can_add(false,ANY_CATEGORY) and !isset($_REQUEST['folio_id'])) OR (User::can_edit(false,ANY_CATEGORY) and isset($_REQUEST['folio_id'])) ){ ?> <?php }else{ echo 'readonly="readonly"'; } ?>> </td>';
    			//start:KID them input co id = amount2_... de lam chuc nang khong cho nhap so lon hon hien tai
                string += '<td style="width:25%; text-align: right; padding-right: 13px;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][amount]" type="text" id="amount_'+folios['id']+'_'+folios['type']+'" value="'+number_format(folios['amount'])+'" onkeyup="ChangeAmount(this,\''+folios['type']+'\','+folios['id']+',\'amount\'); this.value=number_format(this.value);" onfocus="jQuery(this).css(\'border\',\'1px solid silver\');" style="width:90px;"><input type="hidden" id="amount2_'+folios['id']+'_'+folios['type']+'" value="'+number_format(folios['amount'])+'"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][service_rate]" type="text" value="'+folios['service_rate']+'" style="display:none;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][tax_rate]" type="text" value="'+folios['tax_rate']+'" style="display:none;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][rr_id]" type="text" value="'+folios['rr_id']+'" style="display:none;"></td>';
                //end:KID them input co id = amount2_... de lam chuc nang khong cho nhap so lon hon hien tai
                <?php if((User::can_add(false,ANY_CATEGORY) and !isset($_REQUEST['folio_id'])) OR (User::can_edit(false,ANY_CATEGORY) and isset($_REQUEST['folio_id']) and $_REQUEST['check_payment']==0) OR (User::can_delete(false,ANY_CATEGORY) and isset($_REQUEST['folio_id']) and $_REQUEST['check_payment']==1) ){ ?>
                string += '<td onclick="DeleteItem(\''+folios['type']+'\','+folios['id']+','+folios['percent']+');">'+deleteButton+'</td>';
                <?php }else{ ?>
                string += '<td></td>'; 
                <?php } ?>
                string += '<td style="display:none;"></td></tr>';
    			jQuery('#table_split').append(string);	
    			k++;	
    		}
    		jQuery('#tr_'+folios['type']+'_'+folios['id']).css('background','#c7c7c7');
    		jQuery('#amount_'+folios['type']+'_'+folios['id']).css('background','#c7c7c7');
    		jQuery('#'+folios['type']+'_'+folios['id']).attr('checked',true);
    		jQuery('#percent_'+folios['id']+'_'+folios['type']).ForceNumericOnly();
    		jQuery('#amount_'+folios['id']+'_'+folios['type']).ForceNumericOnly();
    	}
    	if(k>0){
    		jQuery('#hidden').css('display','block');
            jQuery("#payment").css('display','unset');
    	}
    	UpdateTotal();
    }

	function UpdateTotal(){
		var hotel_currency = '<?php echo HOTEL_CURRENCY;?>';
		jQuery('#total_amount').val(0);
		jQuery('#total_payment').val(0);
		var type = jQuery('#all_type').val();
		type = type.split(',');
		var tax_amount = 0;
		var charge_amount = 0;
		var total_room_amount = {}; 
		var total_room = 0; 
		var service_rate = {}; var tax_rate={}; var discount_amount = {};
		var service_room = 0; var tax_room = 0;
		var tt = 0; var total_deposit = 0;
		for(var i in traveller_folios){
			if(traveller_folios[i]['type']=='ROOM' || traveller_folios[i]['type']=='DISCOUNT'){
				var amount = to_numeric(traveller_folios[i]['amount']);
					if(traveller_folios[i]['type'] == 'ROOM'){
						if(total_room_amount[traveller_folios[i]['rr_id']]){
							total_room_amount[traveller_folios[i]['rr_id']] += amount;	
						}else{
							total_room_amount[traveller_folios[i]['rr_id']] = amount;
						}
					}else if(traveller_folios[i]['type'] == 'DISCOUNT'){
						discount_amount[traveller_folios[i]['rr_id']] = amount;
					}
					//THL
                    service_rate[traveller_folios[i]['rr_id']] = traveller_folios[i]['service_rate'];
					tax_rate[traveller_folios[i]['rr_id']] = traveller_folios[i]['tax_rate']; 
                    //THL
					//service_room = total_room * service_rate/100;
					//tax_room = (service_room + total_room)*tax_rate/100;
					//jQuery('#total_amount').val(number_format(total_room));

			}else if(traveller_folios[i]['type']!='ROOM' || traveller_folios[i]['type']!='DISCOUNT'){
                var amount = to_numeric(traveller_folios[i]['amount']);
                if(traveller_folios[i]['type'] != 'DEPOSIT' && traveller_folios[i]['type'] != 'DEPOSIT_GROUP'){
                    tt += 0;
                    tax_amount += 0; charge_amount += 0;
				
                    amount_this = amount;
                    tt += amount_this;
                    //THL
                    console.log(amount_this);
                    var total_with_charge = amount_this/(1+to_numeric(traveller_folios[i]['tax_rate'])/100);
                    tax_amount += amount_this - total_with_charge;
                    charge_amount += total_with_charge-total_with_charge/(1+to_numeric(traveller_folios[i]['service_rate'])/100);
                    //THL
				}
                else if(traveller_folios[i]['type'] == 'DEPOSIT' || traveller_folios[i]['type'] == 'DEPOSIT_GROUP'){
					total_deposit += amount;	
				}
			}
		}
		for(var t in total_room_amount){
			if(discount_amount[t] && discount_amount[t]>0){
				total_room_amount[t] = to_numeric(total_room_amount[t]) - to_numeric(discount_amount[t]);	
			}
			total_room += to_numeric(total_room_amount[t]); 
            //THL
            var total_with_charge = (total_room_amount[t])/(1+to_numeric(tax_rate[t])/100);
            tax_room += total_room_amount[t] - total_with_charge;
			charge_amount += total_with_charge-total_with_charge/(1+to_numeric(service_rate[t])/100);
            //THL
		}
		jQuery('#total_amount').val(number_format(tt+total_room));
		charge_amount = charge_amount +  service_room;
		tax_amount = tax_amount + tax_room;
		jQuery('#tax_amount').val(number_format(tax_amount));
		if(hotel_currency=='USD'){
			tax_amount = roundNumberCeil(tax_amount,2);		
		}
		var total_amount = to_numeric(jQuery('#total_amount').val());
		jQuery('#service_charge_amount').val(number_format(charge_amount));
		/** THL**/
		if(hotel_currency=='USD'){
			var total_payment = roundNumberFloor(total_amount,2);
		}else{
			var total_payment = roundNumber(total_amount,0);
		}
		/** THL**/
		if(total_deposit>0){
			jQuery('#tr_total_vat').css('display','');
			jQuery('#total_vat').val(number_format(total_payment));
		}
		jQuery('#total_payment').val(number_format(total_payment - total_deposit));
        //start: KID them tinh balance
        jQuery('#balance').val(number_format(total_payment-total_deposit-balance));
        if(total_payment-total_deposit-balance<=1000)
        {
            jQuery('#balance').val(0);
        }
        //end: KID them tinh balance
        if(to_numeric(jQuery('#total_payment').val()) == 0 || !is_numeric(to_numeric(jQuery('#total_payment').val()))){
            
            jQuery('#payment').css('display','none');
        }
        else
        {
            jQuery('#payment').css('display','unset');
            jQuery('#payment_method_bound').css('display','block');
            if(to_numeric(jQuery('#total_payment').val()) < 0){
                jQuery('#payment span').html('[[.refun.]]');
    		}else{
    			jQuery('#payment span').html('[[.payment.]]');
    		}
        }
        var balance_check = jQuery('#balance').val();
        if(to_numeric(balance_check)>0 && to_numeric(check_payment)==1)
        {
            //alert("[[.are_you_not_input_folio.]]");
            jQuery("#save").css('display','none');
        }
        else
        {
            jQuery("#save").css('display','');
                        
        }
	}
    
    function UpdateTypeTotal(type)
    {
        var amount = 0;
		for(var i in traveller_folios)
        {
			if(traveller_folios[i]['type']==type)
            {
                amount += to_numeric(traveller_folios[i]['amount']);
			}
		}
        jQuery('#split_expan_amount_'+type).val(number_format(amount));
	}
    
	function ChangeAmount(obj,type,key,itm){
        var valu = obj.value;
			if(is_numeric(to_numeric(valu))){
				var value = to_numeric(valu);
				if(itm == 'percent'){
					if(value <= 100){				
						var new_value = (value * to_numeric(jQuery('#amount_'+type+'_'+key).val()))/100;
                        /** START - DAT_1773 thay đổi cách thức cập nhật tổng theo loại **/
                        /*
						if(is_numeric(amount[''+type+''])){
							amount[''+type+''] = amount[''+type+''] - to_numeric(jQuery('#amount_'+key+'_'+type).val()) + new_value;
							jQuery('#split_expan_amount_'+type).val(number_format(amount[''+type+'']));
						}
                        */
						jQuery('#amount_'+key+'_'+type).val(number_format(new_value));
                        traveller_folios[type+'_'+key]['percent'] = value;
						traveller_folios[type+'_'+key]['amount'] = new_value;
                        UpdateTypeTotal(type);
                        /** END - DAT_1773 thay đổi cách thức cập nhật tổng theo loại **/
						UpdateTotal();
					}else{
						alert('[[.not_allowed_greater_than_100.]]');
						obj.value = obj.value.substr(0,obj.value.length-1);	
					}
				}else if(itm == 'amount'){
				    //start:KID them input co id = amount2_... de lam chuc nang khong cho nhap so lon hon hien tai
                    if(value <= to_numeric(jQuery('#amount2_'+key+'_'+type).val()))
                    {
    					var new_percent = roundNumber((to_numeric(obj.value)/to_numeric(jQuery('#amount_'+type+'_'+key).val()))*100,3);
    					/** START - DAT_1773 thay đổi cách thức cập nhật tổng theo loại **/
                        /*
                        if(amount[''+type+'']){
    						amount[''+type+''] = amount[''+type+''] - (to_numeric(jQuery('#amount_'+type+'_'+key).val())*to_numeric(jQuery('#percent_'+key+'_'+type).val())*0.01) + to_numeric(valu);
                            jQuery('#split_expan_amount_'+type).val(number_format(amount[''+type+'']));
    					}
                        */
    					jQuery('#percent_'+key+'_'+type).val(new_percent);
    					traveller_folios[type+'_'+key]['percent'] = new_percent;
    					traveller_folios[type+'_'+key]['amount'] = to_numeric(valu);
                        UpdateTypeTotal(type);
                        /** END - DAT_1773 thay đổi cách thức cập nhật tổng theo loại **/
    					UpdateTotal();
                    }
                    else
                    {
                        alert('[[.not_allowed_greater_than_simple_amount.]]');	
						obj.value = 0;

                        jQuery('#percent_'+key+'_'+type).val(0);
						traveller_folios[type+'_'+key]['percent'] = 0;
    					traveller_folios[type+'_'+key]['amount'] = 0;
    					UpdateTotal();
                    }
                    //end:KID them input co id = amount2_... de lam chuc nang khong cho nhap so lon hon hien tai
				}
			}else{
				alert('[[.is_not_numeric.]]');	
				obj.value = obj.value.substr(0,obj.value.length-1);
			}
		
	}
	function DeleteItem(type,key,percent){
		if(key==''){
			jQuery('.'+type).each(function(){
			    //start: KID them de check cac truong hop khong duoc xoa 
			    if(jQuery.isEmptyObject(traveller_folio_check))
                {
    				jQuery('#'+type+'_split_'+this.lang).remove();
    				jQuery('#'+type+'_'+this.lang).attr('checked',false);
    				jQuery('#tr_'+type+'_'+this.lang).css('background','#FFF');
    				jQuery('#amount_'+type+'_'+this.lang).css('background','#FFF');
    				if(amount[''+type+'']){
    					amount[''+type+''] -= to_numeric(traveller_folios[type+'_'+this.lang]['amount']);
    				}
                    jQuery('#split_expan_amount_'+type).val(number_format(amount[''+type+'']));
    				delete traveller_folios[type+'_'+this.lang];
                }
                else
                {
                    if(traveller_folio_check[this.lang]['status']=='CHECKOUT')
                    {
                        alert('Phòng đã checkout không thể xóa khoản thanh toán')
                        return false;
                    }
                    else
                    {
                        jQuery('#'+type+'_split_'+this.lang).remove();
        				jQuery('#'+type+'_'+this.lang).attr('checked',false);
        				jQuery('#tr_'+type+'_'+this.lang).css('background','#FFF');
        				jQuery('#amount_'+type+'_'+this.lang).css('background','#FFF');
        				if(amount[''+type+'']){
        					amount[''+type+''] -= to_numeric(traveller_folios[type+'_'+this.lang]['amount']);
        				}
                        jQuery('#split_expan_amount_'+type).val(number_format(amount[''+type+'']));
        				delete traveller_folios[type+'_'+this.lang];
                    }
                } 
				
			});
			if(jQuery.isEmptyObject(traveller_folios)){
				jQuery('#hidden').css('display','none');
                jQuery('#split_expan_'+type).remove();
			}
            //end: KID them de check cac truong hop khong duoc xoa
			//jQuery('#split_expan_amount_'+type).val(number_format(amount[''+type+'']));
			
		}else{
		      //start: KID them de check cac truong hop khong duoc xoa
    		  if(jQuery.isEmptyObject(traveller_folio_check))
              {
                jQuery('#'+type+'_split_'+key).remove();
    			jQuery('#'+type+'_'+key).attr('checked',false);
    			jQuery('#tr_'+type+'_'+key).css('background','#FFF');
    			jQuery('#amount_'+type+'_'+key).css('background','#FFF');
    			if(amount[''+type+'']){
    				amount[''+type+''] -= to_numeric(traveller_folios[type+'_'+key]['amount']);
    			}
    			var kt = 0;
    			jQuery('.'+type).each(function(){
    				kt = 1;
    			});
    			if(kt==0){
    				jQuery('#split_expan_'+type).remove();
    			}else{
    				jQuery('#split_expan_amount_'+type).val(number_format(amount[''+type+'']));
    			}
    			delete traveller_folios[type+'_'+key];
    			if(jQuery.isEmptyObject(traveller_folios)){
    				jQuery('#hidden').css('display','none');
    			}
              }
              else
              {
                    if(traveller_folio_check[key]['status']=='CHECKOUT')
                    {
                        alert('Phòng đã checkout không thể xóa khoản thanh toán')
                        return false;   
                    }
                    else
                    {
                        jQuery('#'+type+'_split_'+key).remove();
            			jQuery('#'+type+'_'+key).attr('checked',false);
            			jQuery('#tr_'+type+'_'+key).css('background','#FFF');
            			jQuery('#amount_'+type+'_'+key).css('background','#FFF');
            			if(amount[''+type+'']){
            				amount[''+type+''] -= to_numeric(traveller_folios[type+'_'+key]['amount']);
            			}
            			var kt = 0;
            			jQuery('.'+type).each(function(){
            				kt = 1;
            			});
            			if(kt==0){
            				jQuery('#split_expan_'+type).remove();
            			}else{
            				jQuery('#split_expan_amount_'+type).val(number_format(amount[''+type+'']));
            			}
            			delete traveller_folios[type+'_'+key];
            			if(jQuery.isEmptyObject(traveller_folios)){
            				jQuery('#hidden').css('display','none');
            			}
                    }	
             }
             //end: KID them de check cac truong hop khong duoc xoa
		}
		UpdateTotal();
	}
    var last_time = [[|last_time|]];
	function SubmitForm(act){
	    <?php if(Url::get('folio_id')){ ?>
            <?php echo 'var folio_id = '.Url::get("folio_id").';';?> 
            <?php echo 'var block_id = '.Module::block_id().';';?>
            jQuery.ajax({
        				url:"form.php?block_id="+block_id,
        				type:"POST",
                        dataType: "json",
        				data:{check_last_time:1,folio_id:folio_id,last_time:last_time},
        				success:function(html)
                        {
                            console.log(html);
                            if(html['status']=='error')
                            {
                                alert('RealTime:\n Lưu ý, Folio đã được tài khoản '+html['user']+' chỉnh sửa trước đó, vào lúc :'+html['time']+' \n Dữ liệu hiện tại của bạn chưa được cập nhập nội dung chỉnh sửa đó \n \n Để tiếp tục thao tác bạn vui lòng tải lại trang !')
                                return false;
                            }
                            else
                            {
                                jQuery('#mask').show();
                        		var k =0;
                        		var traveller = '<?php if(Url::get('traveller_id')){echo Url::get('traveller_id');}else{ echo '';}?>';
                        		if(jQuery('#travellers_id').val() == 0 && traveller == '' && jQuery('#traveller_id').val() == '')
                                {
                        			//alert('[[.you_must_select_traveller_id.]]');
                        			//return false;	
                        		}
                        		if(traveller_folios){			
                        			jQuery('#act').val(act);
                        			jQuery('#action').val(1);
                        			CreateTravellerFolioForm.submit();	
                        		}
                            }
        				}
        	});
         <?php }else{ ?>   
    		jQuery('#mask').show();
    		var k =0;
    		var traveller = '<?php if(Url::get('traveller_id')){echo Url::get('traveller_id');}else{ echo '';}?>';
    		if(jQuery('#travellers_id').val() == 0 && traveller == '' && jQuery('#traveller_id').val() == '')
            {
    			//alert('[[.you_must_select_traveller_id.]]');
    			//return false;	
    		}
    		if(traveller_folios){			
    			jQuery('#act').val(act);
    			jQuery('#action').val(1);
    			CreateTravellerFolioForm.submit();	
    		}
        <?php 
        } ?>
	}
	function ItemDetail(itm){
		if(jQuery('#add_item_'+itm).attr('src')=='packages/hotel/skins/default/images/icons/add_item.gif'){
			jQuery('#add_item_'+itm).attr('src','packages/hotel/skins/default/images/icons/close_item.gif');
			jQuery('.'+itm).show();//slideDown(100);
				
		}else{
			jQuery('#add_item_'+itm).attr('src','packages/hotel/skins/default/images/icons/add_item.gif');
			jQuery('.'+itm).hide();//slideUp(100);
		}
	}
    function checkNumber(obj){
    	if(!is_numeric(to_numeric(obj.value))){
    		alert('[[.is_not_number.]]');
    		obj.value = '';
    	}else{
    	}
    }
    function roundNumberCeil(num, dec) {
    	var result = Math.ceil(num*Math.pow(10,dec))/Math.pow(10,dec);
    	return result;
    }
    function roundNumberFloor(num, dec) {
    	var result = Math.floor(num*Math.pow(10,dec))/Math.pow(10,dec);
    	return result;
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
    function OpenFolioRoom($value,$is_payment){
        if($value!=''){
            $arr = $value.split(',');
            $folio_id=$arr[0]; $traveller_id=$arr[1]; $rr_id_folio = $arr[2];
            window.location.href='<?php echo $herf_folio_room;?>&folio_id='+$folio_id+'&traveller_id='+$traveller_id+'&rr_id='+$rr_id_folio+'&check_payment='+$is_payment+'&portal_id=<?php echo PORTAL_ID;?>';
        }
    }
    function OpenFolio($value,$is_payment){
        if($value!=''){
            $arr = $value.split(',');
            $folio_id=$arr[0]; $traveller_id=$arr[1];
            window.location.href='<?php echo $herf;?>&folio_id='+$folio_id+'&traveller_id='+$traveller_id+'&check_payment='+$is_payment+'&portal_id=<?php echo PORTAL_ID;?>';
        }
    }
    function fun_change_traveller_folio($r_r_traveller_folio,$folio_id_check){
        <?php echo 'var block_id = '.Module::block_id().';';?>
        jQuery.ajax({
    				url:"form.php?block_id="+block_id,
    				type:"POST",
                    dataType: "json",
    				data:{check_last_time:1,folio_id:$folio_id_check,last_time:last_time},
    				success:function(html)
                    {
                        if(html['status']=='error')
                        {
                            alert('RealTime:\n Lưu ý, Folio đã được tài khoản '+html['user']+' chỉnh sửa trước đó, vào lúc :'+html['time']+' \n Dữ liệu hiện tại của bạn chưa được cập nhập nội dung chỉnh sửa đó \n \n Để tiếp tục thao tác bạn vui lòng tải lại trang !')
                            return false;
                        }
                        else
                        {
                            if(confirm('[[.are_you_change_traveller.]] ?')){
                                    <?php echo 'var block_id = '.Module::block_id().';';?>
                                    jQuery.ajax({
                        					url:"form.php?block_id="+block_id,
                        					type:"POST",
                        					data:{change_traveller_folio:$r_r_traveller_folio,folio_id:$folio_id_check,is_group_folio:1},
                        					success:function(html)
                                            {
                                                if(to_numeric(html)==2){
                                                    alert('[[.change_traveller.]] [[.success.]] !');
                                                    
                                                }else if(to_numeric(html)==1){
                                                    alert('[[.change_traveller.]] [[.un_success.]] - [[.is_not_traveller_or_traveller_is_checkout.]] !');
                                                }else if(to_numeric(html)==3){
                                                    alert('[[.change_traveller.]] [[.un_success.]] - [[.folio_is_payment.]] !');
                                                }else{
                                                    alert('[[.change_traveller.]] [[.un_success.]] !');
                                                }
                                                window.location.href='<?php echo $herf;?>&act=new&portal_id=<?php echo PORTAL_ID;?>';
                        					}
                        		});
                            }else{
                                return false;
                            }
                        }
    				}
    	});
    }
</script>
