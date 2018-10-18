<style>
	.item-body{
		width:400px;	
	}
	input[type=text]{
		width:100px;
		text-align:right;
		border:none;
		height:14px;
		font-size:11px;
	}
	input[type=checkbox]{
		display:none;
	}
	#travellers_id,#order_ids{
		width:120px;
		height:30px;	
	}
	#save,#add_payment,#back{
		height:28px !important;
		width:65px !important;	
	}
	#title{
		background:url(packages/hotel/skins/default/images/iosstyle/item_list_bg.png) repeat-x;	
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
		width:900px;
}
	.date{
		width:40px !important;	
		float:left;
	}
	.description{
		width:250px !important;
		float:left;
	}
	.amount{
		width:80px !important;
		float:right;
	}
	#check_add{
		width:300px;
		height:50px;	
	}
	#button_add{
		width:50px;
		height:30px;	
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
<?php $herf_folio_room = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'form.php?block_id=429&cmd=create_folio'.(Url::get('fast')?'&fast=1':'');?>
<?php $herf_folio_group = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'form.php?block_id=429&cmd=group_folio&customer_id='.[[=customer_id_group=]].'&id='.[[=reservation_id_group=]].''.(Url::get('fast')?'&fast=1':'');?>
<?php $_REQUEST['check_payment'] = isset($_REQUEST['check_payment'])?$_REQUEST['check_payment']:0;?>
<div id="mask" class="mask" align="center"><img src="packages/core/skins/default/images/ajax-loader-big.gif" align="absmiddle" hspace="5" class="displayIn"><br />Đang tải dữ liệu......</div>
<div id="post_show">
<form method="post" name="CreateTravellerFolioForm">
<table width="99%" style="margin:auto" class="invoice">
    <tr>
        <td>
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
        <td style="text-align: right;">
            Folio [[.group.]]:
            <select onchange="OpenFolioGroup(this.value,0);" style="margin-right: 10px; color: green ; font-weight: bold;  padding: 5px; border: 1px solid green; width: 170px;">
                <option value="" style="color: green ; font-weight: bold; line-height: 25px;">[[.preview_order.]]</option>
                <!--LIST:list_folio_group_in_recode-->
                    <?php if((int)(abs([[=list_folio_group_in_recode.total=]]-[[=list_folio_group_in_recode.amount=]])) >= 1000){ ?>
                        <?php if(isset([[=list_folio_group_in_recode.folio_code=]])){?>
                        <option value="[[|list_folio_group_in_recode.id|]],[[|list_folio_group_in_recode.traveller_id|]]" style="color: green ; line-height: 25px;"> - <?php echo 'No.F'.str_pad([[=list_folio_group_in_recode.folio_code=]],6,"0",STR_PAD_LEFT);?></option>
                        <?php } else{?>
                        <option value="[[|list_folio_group_in_recode.id|]],[[|list_folio_group_in_recode.traveller_id|]]" style="color: green ; line-height: 25px;"> - <?php echo 'Ref'.str_pad([[=list_folio_group_in_recode.id=]],6,"0",STR_PAD_LEFT);?></option>
                        <?php }?>
                    <?php }?> 
                <!--/LIST:list_folio_group_in_recode-->
            </select>
            <select onchange="OpenFolioGroup(this.value,1);" style="margin-right: 10px; color: red ; font-weight: bold; padding: 5px; border: 1px solid red; width: 170px; ">
                <option value="" style="color: red ; font-weight: bold; line-height: 25px;">[[.bill_folio.]]</option>
                <!--LIST:list_folio_group_in_recode-->
                    <?php if((int)(abs([[=list_folio_group_in_recode.total=]]-[[=list_folio_group_in_recode.amount=]])) < 1000){ ?>
                        <?php if(isset([[=list_folio_group_in_recode.folio_code=]])){?>
                        <option value="[[|list_folio_group_in_recode.id|]],[[|list_folio_group_in_recode.traveller_id|]]" style="color: red ; line-height: 25px;"> - <?php echo 'No.F'.str_pad([[=list_folio_group_in_recode.folio_code=]],6,"0",STR_PAD_LEFT);?></option>
                        <?php } else{?>
                        <option value="[[|list_folio_group_in_recode.id|]],[[|list_folio_group_in_recode.traveller_id|]]" style="color: red ; line-height: 25px;"> - <?php echo 'Ref'.str_pad([[=list_folio_group_in_recode.id=]],6,"0",STR_PAD_LEFT);?></option>
                        <?php } ?>
                    <?php }?>
                <!--/LIST:list_folio_group_in_recode-->
            </select>
        </td>
    </tr>
    
    </tr>
</table>
<!---<div style="text-align:center; color:#36F; font-size:16px; font-weight:bold; margin-top:10px; text-transform: uppercase;">[[.create_folio.]] [[.room.]]</div>--->
<div></div>
<div><?php echo Form::$current->error_messages();?></div>
 <?php $herf = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'form.php?block_id=429&cmd=create_folio&traveller_id='.Url::get('traveller_id').'&rr_id='.Url::get('rr_id').'';?>
<table width="98%" border="1" style="margin:20px auto; border:1px solid silver;" class="invoice">
	<tr id="title">
        <td width="50%">[[.code.]]:<span id="rr_id" style="text-align:left; color:#0A3680;"> <?php echo (Url::get('add_payment')?Url::get('add_payment'):(Url::get('rr_id')?Url::get('rr_id'):''));?></span>_[[.room.]]_[[|room_name|]]
        <span id="reservation_id" style="text-align:left; color:#0A3680;"><?php echo Url::get('r_id');?></span></td>
        <td width="50%">
        <span style="text-align:left; color:#0A3680;">
        <!--LIST:folio--> 
            <?php if(Url::get('folio_id') && [[=folio.id=]]==Url::get('folio_id')){echo Portal::language('folio').'_'.[[=folio.id=]];}?> 	
       <!--/LIST:folio-->
        </span>
        <?php if((User::can_add(false,ANY_CATEGORY) and !isset($_REQUEST['folio_id'])) OR (User::can_edit(false,ANY_CATEGORY) and isset($_REQUEST['folio_id'])) ){ ?>
            <a href="#" name="add_payment" type="button" id="add_payment" value="" style="float:right;height:28px; margin-right: 5px; text-transform: uppercase; text-decoration: none; width: 80px !important;" class="w3-btn w3-indigo w3-hover-indigo w3-hover-text-white" >[[.helf_payment.]]</a>
            <a href="#" class="w3-btn w3-blue w3-hover-blue w3-text-white w3-hover-text-white" name="save" type="button" id="save" value="" style="float:right;margin-right: 5px; text-transform: uppercase; text-decoration: none;" onclick="SubmitForm('save');" >[[.Save_and_stay.]]</a>
            <a href="#" class="w3-btn w3-orange w3-hover-orange w3-text-white w3-hover-text-white" name="save_and_print" type="button" id="save_and_print" value="" style="float:right;height:28px; margin-right: 5px; text-transform: uppercase; text-decoration: none;" onclick="SubmitForm('save_and_print');" >[[.save_view.]]</a>
        <?php } ?>
        <a href="<?php echo $herf;?>&act=new&portal_id=<?php echo PORTAL_ID;?>" ><input name="create_folio" type="button" value="[[.create_bill.]]" id="create_folio" class="w3-btn w3-green w3-hover-green w3-hover-text-white" style="float:right; height:28px;margin-right: 5px; text-transform: uppercase; text-decoration: none;" title="[[.create_bill.]]"/></a>
        <?php if(Url::get('add_payment')){?>
        <a href="#" onclick="window.location='<?php echo $herf;?>&portal_id=<?php echo PORTAL_ID;?>'" >
        <input name="back" type="button" id="back" value="[[.back.]]" style="float:right;height:28px; margin-right: 5px; width: 85px !important; text-transform: uppercase; text-decoration: none;" class="w3-btn w3-black" /></a>
        <?php }?>
         <div id="check_add" style="display:none;position:absolute;top:auto;left:auto;border:1px solid #000000;padding:5px;text-align:center;background:#FFFF99; width: 380px;">
			[[.choose_room.]]: <select name="order_ids" id="order_ids"></select>
            <?php $link_add = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'form.php?block_id=429&cmd=add_payment&traveller_id='.Url::get('traveller_id').'&rr_id='.Url::get('rr_id').'';
			$link_add .= (Url::get('folio_id')?('&folio_id='.Url::get('folio_id')):'');
			?>
            <a href="#" class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none;" onclick="window.location='<?php echo $link_add;?>&add_payment='+jQuery('#order_ids').val()+'&portal_id=<?php echo PORTAL_ID;?>'">[[.add.]]</a>
			<a class="w3-btn w3-red close" onclick="jQuery('#check_add').hide();$('order_ids').value='';" style="text-decoration: none; text-transform: uppercase;">[[.close.]]</a>
		 </div>
        </td>
    </tr>
    <tr>
        <td>[[.folio_created.]]: <?php $k=0; $t=0;?>
        <a href="#" name="all" type="image" id="all" onclick=" jQuery('#ALL').attr('checked',true);checkSplit('','ALL');return false;" style="float: right; padding-right: 7px;color:#676E73;" title="transfer_all"><i class="fa fa-arrow-circle-right w3-text-gray w3-hover-text-black" style="font-size: 23px;"></i><input type="checkbox" id="ALL"/></a>
        <?php if(Url::get('cmd')!='add_payment'){ ?>
       <!--LIST:folio-->
           <?php if((int)([[=folio.total=]] - [[=folio.amount=]]) <1000){ ?>      
             	<a href="<?php echo $herf;?>&folio_id=[[|folio.id|]]&check_payment=1&portal_id=<?php echo PORTAL_ID;?>" style="text-decoration: none; padding-right: 10px;" >
                <?php if(isset([[=folio.folio_code=]]) and [[=folio.folio_code=]] != '' ){?>
                    <i class="fa fa-file-text w3-text-red" style="font-size: 16px;"></i> <i class="w3-text-red"><?php echo 'No.F'.str_pad([[=folio.folio_code=]],6,"0",STR_PAD_LEFT)?></i></a>
                <?php } else{ ?>
                    <i class="fa fa-file-text w3-text-red" style="font-size: 16px;"></i> <i class="w3-text-red"><?php echo 'Ref'.str_pad([[=folio.id=]],6,"0",STR_PAD_LEFT)?></i></a>
                <?php } ?>
           <?php }else{ ?> 
                <a href="<?php echo $herf;?>&folio_id=[[|folio.id|]]&check_payment=0&portal_id=<?php echo PORTAL_ID;?>" style="text-decoration: none; padding-right: 10px;" >
                <?php if(isset([[=folio.folio_code=]]) and [[=folio.folio_code=]] != ''){?>
                    <i class="fa fa-file-text w3-text-green" style="font-size: 16px;"></i> <i class="w3-text-green"><?php echo 'No.F'.str_pad([[=folio.folio_code=]],6,"0",STR_PAD_LEFT)?></i></a>
                <?php } else {?>
                    <i class="fa fa-file-text w3-text-green" style="font-size: 16px;"></i> <i class="w3-text-green"><?php echo 'Ref'.str_pad([[=folio.id=]],6,"0",STR_PAD_LEFT)?></i></a>
                <?php }?>
           <?php } ?>         
       <!--/LIST:folio-->
       <?php }?> </td>   
        <td>
            <div style="width: 100%; margin: 0px auto; float: left;">
                <span id="select_title" style="float: left; line-height: 25px; width: 90px;"> [[.select_traveller.]]: </span>
                <select name="travellers_id" id="travellers_id" onchange="jQuery('#traveller_id').val(this.value);window.location='<?php echo 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'form.php?block_id=429&cmd=create_folio&rr_id='.Url::get('rr_id').''?>&traveller_id='+this.value;" style="width:120px; height: 25px; float: left;"></select>
                <input name="traveller_id" value="<?php echo Url::get('traveller_id'); ?>" type="hidden" id="traveller_id" style="float: left;" />
                <?php if( (!isset($_REQUEST['check_payment']) OR (isset($_REQUEST['check_payment']) and $_REQUEST['check_payment']!=1)) and Url::get('folio_id') ){ ?>
                <input type="button" value="[[.change_traveller.]]" style="padding: 3px; margin-left: 2px; float: left;" onclick="jQuery('#change_traveller_folio').css('display','');" />
                <select name="change_traveller_folio" id="change_traveller_folio" style="width: 120px; height: 25px; margin-left: 2px; float: left; display: none;" onchange="fun_change_traveller_folio(this.value,<?php echo Url::get('folio_id'); ?>);"></select>
                <?php } ?>
            </div>
            <!-- Mạnh thêm ô nhập mã thành viên để thực hiện tích điểm -->
            <?php if(SETTING_POINT==1){ ?>
            <div style="width: 90%; margin: 0px auto; float: left;">
                <span style="float: left; line-height: 25px; width: 90px;"> [[.member_code.]]: </span>
                <input type="text" name="member_code"value="<?php echo isset($_REQUEST['member_code'])?$_REQUEST['member_code']:''; ?>" id="member_code" autocomplete="off" onchange="fun_load_member_code();" style="width: 120px; height: 25px; text-align: center; border: 1px solid #555555; float: left;" />
                <input type="text" name="member_level_id" value="<?php echo isset($_REQUEST['member_level_id'])?$_REQUEST['member_level_id']:''; ?>" id="member_level_id" style="display: none;" />
                <input type="text" name="create_member_date" value="<?php echo isset($_REQUEST['create_member_date'])?$_REQUEST['create_member_date']:''; ?>" id="create_member_date" style="display: none;" />
                <input type="button" name="view_info_member" id="view_info_member" value="info" style="padding: 3px; margin: 0px 2px ; float: left;" onclick="fun_view_info_member();" />
                <div id="div_info" style="width: 100%; height: 100%; display: none; background-color: rgba(0, 0, 0, 0.9); position: fixed; top: 0px; left: 0px;">
                    <div style="width: 600px; height: 400px; margin: 50px auto; background: #ffffff; position: relative;">
                        <div style="width: 20px; height: 20px; border: 2px solid #000000; border-radius: 50%; line-height: 20px; text-align: center; font-size: 17px; position: absolute; top: -10px; right: -10px; background: #fff; cursor: pointer;" onclick="fun_close_info();">X</div>
                        <div id="info_member_discount" style="width: 600px; height: 390px; position: absolute; top: 10px; left: 0px;"></div>
                    </div>
                </div>
            </div>
            <?php } ?>
            <!-- end Mạnh -->
            <!---<a href="<?php echo $herf;?>&act=new&portal_id=<?php echo PORTAL_ID;?>">
                <input name="create_folio" type="button" value="[[.create_folio.]]" id="create_folio" class="view-order-button" style="border:1px solid #FFCF66 !important; float:right; height:20px;" title="[[.create_folio.]]"/>
            </a>--->
             <?php $hef = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=create_traveller_folio&cmd=group_folio&customer_id='.Url::get('customer_id').'&id='.Url::get('id').'';?>
       </td>
    </tr>
     <tr>
        <td style="vertical-align:top;">
        	<table border="1" width="100%" style="border:1px solid silver;margin:auto;" class="invoice" cellpadding="3">
            	<?php $type='';?>   
                <!--LIST:items-->	
                <?php if($type != [[=items.type=]]){ $type = [[=items.type=]]; ?>       
                        <tr id="title_[[|items.type|]]">
                        	<td width="400"><span><b><em>[[|items.type|]]</em></b></span></td>
                            <td width="30"><a href="#" name="all_[[|items.type|]]" type="image" id="all_[[|items.type|]]" onclick="jQuery('#ALL_[[|items.type|]]').attr('checked',true);checkSplit('','[[|items.type|]]');return false;" style="padding-left: 5px;" ><i class="fa fa-arrow-circle-right w3-text-gray w3-hover-text-black" style="font-size: 23px;"></i></a>
                            <input type="checkbox" id="ALL_[[|items.type|]]"/></td>
                        </tr>
                <?php }?>
                        <tr style="display:none;"><td colspan="2"><input name="foc" type="hidden" value="[[|foc|]]" id="foc" ><input name="foc_all" type="hidden" value="[[|foc_all|]]" id="foc_all" ></td></tr>
                        <tr id="tr_[[|items.type|]]_[[|items.id|]]" class="[[|items.type|]]_[[|items.id|]]" lang="[[|items.status|]]">  
                        <td width="400">
                            <div class="item-body" style="width:100%;">	
                                <div class="date" id="date_[[|items.type|]]_[[|items.id|]]">[[|items.date|]]</div>
                                <input type="hidden" id="full_date_[[|items.type|]]_[[|items.id|]]" value="[[|items.full_date|]]"/>
                                <div class="description" id="description_[[|items.type|]]_[[|items.id|]]" style="width:150px !important;">[[|items.description|]]</div>
                                <div>				<?php if([[=items.type=]]=='LAUNDRY'){
																$her = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=laundry_invoice&cmd=edit&id='.[[=items.id=]];
																echo '<a target="_blank" href="'.$her.'" style="float:left">#LD_'.[[=items.position=]].'</a>';
															}else if([[=items.type=]]=='MINIBAR'){
																$her = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=minibar_invoice&cmd=edit&id='.[[=items.id=]];
																echo '<a target="_blank" href="'.$her.'" style="float:left">#MN_'.[[=items.position=]].'</a>';
															}else if([[=items.type=]]=='EQUIPMENT'){
																$her = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=equipment_invoice&cmd=edit&id='.[[=items.id=]];
																echo '<a target="_blank" href="'.$her.'" style="float:left">#EQ_'.[[=items.position=]].'</a>';
															}else if([[=items.type=]]=='BAR'){
																$her = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=touch_bar_restaurant&cmd=detail&'.md5('act').'='.md5('print_bill').'&'.md5('preview').'=1&id='.[[=items.id=]].[[=items.link=]];
																echo '<a target="_blank" href="'.$her.'" style="float:left">#'.[[=items.code=]].'</a>';
															}else if([[=items.type=]]=='EXTRA_SERVICE'){
																$her = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=extra_service_invoice&cmd=view_receipt&id='.[[=items.ex_id=]];
																echo '<a target="_blank" href="'.$her.'" style="float:left">#'.[[=items.ex_bill=]].'</a>';
															}else if([[=items.type=]]=='TICKET'){
																$her = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=ticket_invoice_group&cmd=bill&'.md5('act').'='.md5('print').'&id='.[[=items.id=]];
																echo '<a target="_blank" href="'.$her.'" style="float:left">#'.[[=items.id=]].'</a>';
															}else if([[=items.type=]]=='MASSAGE'){
																$her = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=massage_daily_summary&cmd=invoice&room_id='.[[=items.room_id=]].'&id='.[[=items.id=]];
																echo '<a target="_blank" href="'.$her.'" style="float:left">#'.[[=items.id=]].'</a>';
															}?>
                                </div>              
                                <div class="amount" style="float:right;"><?php echo System::display_number(round(System::calculate_number([[=items.net_amount=]]),2));?><input name="amount_[[|items.type|]]_[[|items.id|]]" type="text" id="amount_[[|items.type|]]_[[|items.id|]]" value="[[|items.net_amount|]]" style="border:none; display:none;" readonly="readonly" class="amount"  lang="[[|items.service_rate|]]" alt="[[|items.tax_rate|]]"/> </div>
                            </div>
                        </td>
                        <td width="30"><a href="#" name="split_[[|items.type|]]_[[|items.id|]]" type="image" id="split_[[|items.type|]]_[[|items.id|]]" onclick="jQuery('#[[|items.type|]]_[[|items.id|]]').attr('checked',true);checkSplit('[[|items.id|]]','[[|items.type|]]');return false;" style="padding-left: 5px;" lang="[[|items.percent|]]"><i class="fa fa-arrow-circle-right w3-text-gray w3-hover-text-black" style="font-size: 23px;"></i></a>
                        <input type="checkbox" id="[[|items.type|]]_[[|items.id|]]" lang="[[|items.type|]]" alt="[[|items.id|]]" value="[[|items.amount|]]" name="[[|items.id|]]" class="[[|items.type|]]"/></td>
                        </tr>
                  <!--/LIST:items-->  
            </table> 
        </td>
        <td style="vertical-align:top;">
        	 <input name="all_type" type="hidden" id="all_type" value="ROOM,MINIBAR,LAUNDRY,EQUIPMENT,BAR,EXTRA_SERVICE,TELEPHONE,MASSAGE,DEPOSIT,DISCOUNT,VEND,PACKAGE" style="width:300px;"/>
            <input name="action" type="hidden" value="0" id="action" />
            <input name="act" type="hidden" value="" id="act" />
        	<table border="1" width="100%" cellpadding="3" style="border:1px solid silver;margin:auto;" id="table_split">
            	<tr style="text-align:center;">
                	<td style="width:50%;"><b>[[.detail.]]</b></td>
                    <td style="width:20%;"><b>[[.percent.]]</b></td>
                    <td style="width:25%;"><b>[[.amount.]]</b></td>
                    <td></td>
                </tr>
            </table>
            <div id="hidden" style="display:none;">
            <table border="1" width="100%" style="border:1px solid silver;margin:auto;">
            <?php $k=0;?>
            <!--LIST:add_payments-->
            	<?php $k++;?>
            	<tr id="tr_add_paid_[[|add_payments.id|]]">
                    <td style="width:70%;"><span style="float:right;"><b><em> <a onclick="window.location='http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>?page=create_traveller_folio&cmd=add_payment&add_payment=[[|add_payments.id|]]&traveller_id=<?php echo Url::get('traveller_id');?>&rr_id=<?php echo Url::get('rr_id');?>&folio_id=<?php echo Url::get('folio_id');?>&portal_id=<?php echo PORTAL_ID;?>'"> [[|add_payments.decription|]]</a>:&nbsp; </em></b></span>&nbsp;</td>
                    <td style="width:25%;"><input name="add_paid[[[|add_payments.id|]]][id]" type="text" id="add_paid_[[|add_payments.id|]]" value="[[|add_payments.add_amount|]]" class="add_paid" style="border:none; width:100px; height:25px;" lang="0" alt="0" readonly="readonly"/></td>
                    <!--start:KID them de check xoa khoan thanh toan-->
                    <td><a href="#" align="left" title="delete" onclick="<?php if([[=add_payments.status=]]=='CHECKOUT') {?>alert('phòng đã checkout không thể xóa thanh toán');return false;<?php }else{?>jQuery('#tr_add_paid_[[|add_payments.id|]]').remove();UpdateTotal();<?php } ?>"><i class="fa fa-times-circle w3-text-red" style="font-size: 18px;"></i></a></td>
                    <!--end:KID them de check xoa khoan thanh toan-->
                </tr>
            <!--/LIST:add_payments-->
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
                <!--IF:con_paid_group([[=paid_group=]] and ([[=paid_group=]] >0))-->
                <tr>
                    <td style="width:70%;"><span style="float:right;"><b><em>[[.paid_group.]]:&nbsp; </em></b></span>&nbsp;</td>
                    <td style="width:25%;"><input name="paid_group" value="<?php echo System::display_number(([[=paid_group=]]+System::calculate_number([[=deposit=]])));?>" type="text" id="paid_group" style="border:none; width:100px; height:25px;" readonly="readonly"/></td>
                    <td></td>
                </tr>
                <!--ELSE-->
                <input name="paid_group" value="0" type="hidden" id="paid_group" style="border:none; width:100px; height:25px;" readonly="readonly"/>
                <!--/IF:con_paid_group-->    
<tr id="tr_total_vat" style="display:none;">
                    <td style="width:70%;"><span style="float:right;"><b><em>[[.total_vat.]]:&nbsp; </em></b></span>&nbsp;</td>
                    <td style="width:25%;"><input name="total_vat" type="text" id="total_vat" value="0" style="border:none; width:100px; height:25px;" readonly="readonly" /></td>
                    <td></td>
                </tr>
                 <tr>
                    <td style="width:70%;"><span style="float:right;"><b><em>[[.total_payment.]]:&nbsp; </em></b></span>&nbsp;</td>
                    <td style="width:25%;"><input name="total_payment" type="text" id="total_payment" value="0" style="border:none; width:100px; height:25px;" readonly="readonly" /></td>
                    <td style="width:25%;"><input name="total_payment_old" type="text" id="total_payment_old" value="0" style="border:none; width:100px; height:25px;display: none;" readonly="readonly" /></td>
                    <td></td>
                </tr>
                
            </table>
            <?php if(!Url::get('add_payment')){;?>
            <div id="payment_bound">
           <fieldset>
            <legend><b><i>[[.summary.]]</i></b></legend>
             <div id="payment_method_bound">
             
            <?php $her = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=payment&id='.Url::get('rr_id').'&type=RESERVATION&act=create_folio&traveller_id='.Url::get('traveller_id').'&total_amount='.Url::get('total_payment').'';?>
            <b><em>[[.balance.]]:&nbsp; </em></b>
            <input name="balance" type="text" id="balance" value="0" style="border:none; width:100px; height:25px; text-align: left;" readonly="readonly" />
            <?php if((User::can_add(false,ANY_CATEGORY) and !isset($_REQUEST['folio_id'])) OR (User::can_edit(false,ANY_CATEGORY) and isset($_REQUEST['folio_id']) and $_REQUEST['check_payment']==0) OR (User::can_delete(false,ANY_CATEGORY) and isset($_REQUEST['folio_id']) and $_REQUEST['check_payment']==1) OR (User::can_admin(false,ANY_CATEGORY) and isset($_REQUEST['folio_id'])) ){  ?>
                <a class="w3-btn w3-lime w3-hover-lime" href="#" name="payment" type="button" id="payment" value="" onclick="SubmitForm('payment');" style="height:32px; text-transform: uppercase; text-decoration: none; "><i class="fa fa-dollar" style="font-size: 18px;"></i> [[.payment.]]</a>
            <?php }?>
            
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <?php $hef = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=view_traveller_folio&cmd=invoice';
				if(Url::get('folio_id')){ 
					$hef_end = $hef.'&traveller_id='.Url::get('traveller_id').'&folio_id='.Url::get('folio_id').'';?>
            <a class="w3-btn w3-red w3-hover-red" target="_blank" href="<?php echo $hef_end;?>&portal_id=<?php echo PORTAL_ID;?>" style="text-transform: uppercase; text-decoration: none;" >[[.view_folio.]]</a>
            <?php } ?>
            </div>
            
            
          </fieldset>
          </div>
          <?php }?>
           </div> 
        </td>
    </tr>     
</table>
</form>
</div>
<script>
    var traveller_id = '<?php echo Url::get('traveller_id')?Url::get('traveller_id'):'';?>';
    //console.log(traveller_id);
    var member_list = [[|member_list|]];
    //console.log(member_list);
    var folio_ids = '<?php echo Url::get('folio_id')?Url::get('folio_id'):''; ?>';
    //console.log("***"+folio_ids+"***");
    var cmd = '<?php echo Url::get('cmd')?Url::get('cmd'):'';?>';
    var check_payment = <?php echo Url::get('check_payment')?Url::get('check_payment'):0; ?>;
    traveller_folios = {};
    items_js = [[|items_js|]];// Tat ca cac ban ghi can thanh toan
    traveller_folio = [[|traveller_folios_js|]];// Cac ban ghi cua folio nay
    folio_other = [[|folio_other_js|]];// Cac ban ghi da dc thanh toasn boi folio khac
    var add_pmt=[[|add_payment|]];
    var balance=<?php echo [[=balance=]] ?>;
    jQuery('#hidden').css('display','block');
	for(var j in traveller_folio){
		traveller_folios[traveller_folio[j]['type']+'_'+traveller_folio[j]['id']] = traveller_folio[j];
	}
	DrawSplitOrder();
	for(var i in folio_other){
		if(folio_other[i]['percent']==100 && typeof(items_js[i]) != 'undefined' && items_js[i]['status'] == '1'){
            var description = jQuery('#description_'+folio_other[i]['type']+'_'+folio_other[i]['id']).html();
			jQuery('#description_'+folio_other[i]['type']+'_'+folio_other[i]['id']).html(description+' '+folio_other[i]['href']); 
			jQuery('#tr_'+folio_other[i]['type']+'_'+folio_other[i]['id']).css({'background':'#c7c7c7'});
			jQuery('#amount_'+folio_other[i]['type']+'_'+folio_other[i]['id']).css({'background':'#c7c7c7'});
			jQuery('#'+folio_other[i]['type']+'_'+folio_other[i]['id']).attr('checked',true);
		}
	}
	if(traveller_id != '' && cmd == 'create_folio'){
		jQuery('#travellers_id').val(traveller_id);
		jQuery('#traveller_id').val(traveller_id);
        <?php if(SETTING_POINT==1){ ?>
        if(folio_ids==''){
            jQuery("#member_code").val(member_list[traveller_id]['member_code']);
            jQuery("#member_level_id").val(member_list[traveller_id]['member_level_id']);
            jQuery("#create_member_date").val(member_list[traveller_id]['create_member_date']);
        }
        <?php } ?>
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
    jQuery(document).ready(function(){
        jQuery('#total_payment_old').val(jQuery('#total_payment').val());
    });
    UpdateTotal();
    function checkSplit(id,type){
        var traveller_id = jQuery("#travellers_id").val();
        <?php if(isset($_REQUEST['traveller_id'])){ ?>
        traveller_id = <?php echo $_REQUEST['traveller_id']; ?>;
        <?php } ?>
        //if(traveller_id!=0)
        //{
        	var check = true;
        	if(type=='ALL'){
        		for(var k in items_js){
        			items = items_js[k];
        			if(items['status']==0){
        				if(traveller_folios[items['type']+'_'+items['id']]){
        				}else{
        					traveller_folios[items['type']+'_'+items['id']] = items;
        				}
        			}
        		}
        	}else{
        		if(id==''){
        			jQuery('.'+type).each(function(){
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
                                /** START - DAT bo sung list cac khoan cua chang truoc trong truong hop doi phong **/
        						//itm['rr_id'] = rr_id;
                                itm['rr_id'] = items_js[id]['rr_id'];
                                /** END - DAT bo sung list cac khoan cua chang truoc trong truong hop doi phong **/
        						traveller_folios[id] = itm;
        					}
        				}
        			});
        		}else{
        			var id = jQuery('#'+type+'_'+id).attr('id');
        			var item_id = jQuery('#'+id).attr('alt');
        			if(jQuery('#tr_'+id).attr('lang')==0){
        				if(traveller_folios[id]){
        				}else{
        					itm=[];
        					itm['id'] = item_id;
        					itm['type'] = jQuery('#'+id).attr('lang');
        					itm['percent'] = to_numeric(jQuery('#split_'+id).attr('lang'));
        					itm['amount'] = to_numeric(jQuery('#'+id).val());
        					itm['service_rate'] = to_numeric(jQuery('#amount_'+id).attr('lang'));
        					itm['tax_rate'] = to_numeric(jQuery('#amount_'+id).attr('alt'));
        					itm['description'] = jQuery('#description_'+id).html();
        					itm['date'] = jQuery('#date_'+id).html();
                            itm['full_date'] = jQuery('#full_date_'+id).val();
                            /** START - DAT bo sung list cac khoan cua chang truoc trong truong hop doi phong **/
                            itm['rr_id'] = items_js[id]['rr_id'];
                            /** END - DAT bo sung list cac khoan cua chang truoc trong truong hop doi phong **/
        					traveller_folios[id] = itm;
        				}
        			}
        		}
        	}
        	DrawSplitOrder();
        //}
        //else{
            //alert("Bạn cần chọn tên khách thanh toán trước khi tạo hóa đơn !");
        //}
    }
    function CheckedBox(type){
    	if(type != ''){
    		var check  = jQuery('#ALL_'+type).attr('checked');
    		jQuery('.'+type).each(function(){
    			this.checked = check;
    			checkSplit(id,type);
    		});
    	}else{
    		var all_type  = jQuery('#all_type').val();
    		all_type = all_type.split(",");
    		for(var k in all_type){
    			jQuery('.'+all_type[k]).each(function(){
    				this.checked = true;
    			});		
    		}
    	}
    }
    function DrawSplitOrder(){
    	jQuery('#table_split').html('<tr style="text-align:center;"><td style="width:50%;"><b>[[.detail.]]</b></td><td style="width:96px;"><b>[[.percent.]]</b></td><td style="width:25%;"><b>[[.amount.]]</b></td><td>&nbsp;</td></tr>');
    	var deleteButton = '<i class="fa fa-times-circle w3-text-red" style="font-size: 18px; padding-left: 3px;"></i>';
    	for(var i in traveller_folios){
    		folios = traveller_folios[i];
    		if(folios['date']){
    		}else{
    			//folios['date'] = '';
    		}
    		var string = '<tr id="'+folios['type']+'_split_'+folios['id']+'"><td style="width:50%;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][id]" type="text" id="traveller_folio['+folios['id']+'_'+folios['type']+'][id]" value="'+folios['id']+'" style="display:none;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][type]" type="text" id="type_'+folios['id']+'_'+folios['type']+'" value="'+folios['type']+'" style="display:none;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][full_date]" type="hidden" id="full_date_'+folios['id']+'_'+folios['type']+'" value="'+folios['full_date']+'" style="width:30%; height:26px; font-size:13px; text-align:left;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][date]" type="text" id="date_'+folios['id']+'_'+folios['type']+'" value="'+folios['date']+'" style="width:30%; height:26px; font-size:13px; text-align:left;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][description]" type="text" id="description_'+folios['id']+'_'+folios['type']+'" value="'+folios['description']+'" style="width:70%; height:26px; font-size:13px; text-align:left;"></td>';
    		string += '<td style="width:96px;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][percent]" type="text" id="percent_'+folios['id']+'_'+folios['type']+'" value="'+folios['percent']+'" onkeyup="ChangeAmount(this,\''+folios['type']+'\',\''+folios['id']+'\',\'percent\');" onfocus="jQuery(this).css(\'border\',\'1px solid silver\');" class="input-number" style="width:50px;" <?php if((User::can_add(false,ANY_CATEGORY) and !isset($_REQUEST['folio_id'])) OR (User::can_edit(false,ANY_CATEGORY) and isset($_REQUEST['folio_id'])) ){ ?> <?php }else{ echo 'readonly="readonly"'; } ?> > </td>';
    		/** START - DAT bo sung list cac khoan cua chang truoc trong truong hop doi phong **/
            //start:KID them input co id = amount2_... de lam chuc nang khong cho nhap so lon hon hien tai
            string += '<td style="width:25%;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][amount]" type="text" id="amount_'+folios['id']+'_'+folios['type']+'" value="'+number_format(folios['amount'])+'" onkeyup="ChangeAmount(this,\''+folios['type']+'\',\''+folios['id']+'\',\'amount\');this.value=number_format(this.value);" onfocus="jQuery(this).css(\'border\',\'1px solid silver\');" style="width:100px; padding-right: 2px;" class="input-number"><input type="hidden" id="amount2_'+folios['id']+'_'+folios['type']+'" value="'+number_format(folios['amount'])+'"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][service_rate]" type="text" value="'+folios['service_rate']+'" style="display:none;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][tax_rate]" type="text" value="'+folios['tax_rate']+'" style="display:none;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][rr_id]" type="text" value="'+folios['rr_id']+'" style="display:none;"></td>';
    		//end:KID them input co id = amount2_... de lam chuc nang khong cho nhap so lon hon hien tai
            /** START - DAT bo sung list cac khoan cua chang truoc trong truong hop doi phong **/
            <?php if((User::can_add(false,ANY_CATEGORY) and !isset($_REQUEST['folio_id'])) OR (User::can_edit(false,ANY_CATEGORY) and isset($_REQUEST['folio_id']) and $_REQUEST['check_payment']==0) OR (User::can_delete(false,ANY_CATEGORY) and isset($_REQUEST['folio_id']) and $_REQUEST['check_payment']==1) ){ ?>
                string += '<td onclick="DeleteItem(\''+folios['type']+'\','+folios['id']+','+folios['percent']+','+folios['rr_id']+');">'+deleteButton+'</td>';
            <?php }else{ ?>
                string += '<td></td>';
            <?php } ?>
    		string += '<td style="display:none;"></td></tr>';
    		jQuery('#table_split').append(string);
    		jQuery('#tr_'+folios['type']+'_'+folios['id']).css('background','#c7c7c7');
    		jQuery('#amount_'+folios['type']+'_'+folios['id']).css('background','#c7c7c7');
    		jQuery('#'+folios['type']+'_'+folios['id']).attr('checked',true);
    		jQuery('#hidden').css('display','block');
    		jQuery('#percent_'+folios['id']+'_'+folios['type']).ForceNumericOnly();
    		jQuery('#amount_'+folios['id']+'_'+folios['type']).ForceNumericOnly();
    		UpdateTotal();
    	}
    }
	function UpdateTotal(){
		var hotel_currency = '<?php echo HOTEL_CURRENCY;?>';
        
		jQuery('#total_amount').val(0);
		jQuery('#total_payment').val(0);
		var type = jQuery('#all_type').val();
		type = type.split(',');
		var tax_amount = 0;
		var charge_amount = 0;
		var total_room = 0; 
		var service_rate =0; var tax_rate=0;
		var service_room = 0; var tax_room = 0;
		var tt = 0;
		var total_deposit = 0;
		for(var i in traveller_folios){
			if(traveller_folios[i]['type']=='ROOM' || traveller_folios[i]['type']=='DISCOUNT'){
				var amount = to_numeric(traveller_folios[i]['amount']);
					if(traveller_folios[i]['type'] == 'ROOM'){
						total_room += amount;	
					}else if(traveller_folios[i]['type'] == 'DISCOUNT'){
						total_room = total_room - amount;	
					}
                    //THL
                    service_rate = to_numeric(traveller_folios[i]['service_rate']);
					tax_rate = to_numeric(traveller_folios[i]['tax_rate']); 
                    
                    var total_with_service = total_room/(1+tax_rate/100);
                    tax_room = total_room - total_with_service;
					service_room = total_with_service-total_with_service/(1+service_rate/100);
                    //THL
			}else if(traveller_folios[i]['type']!='ROOM' || traveller_folios[i]['type']!='DISCOUNT'){
				var amount = to_numeric(traveller_folios[i]['amount']);
                if(traveller_folios[i]['type'] != 'DEPOSIT'){
				        amount_this = amount;		
				        tt += amount_this;
                        //THL
                        var total_with_charge = amount_this/(1+to_numeric(traveller_folios[i]['tax_rate'])/100);
                        tax_amount += amount_this - total_with_charge;
       					charge_amount += total_with_charge-total_with_charge/(1+to_numeric(traveller_folios[i]['service_rate'])/100);
                        //THL
				}else if(traveller_folios[i]['type'] == 'DEPOSIT'){
				    total_deposit += amount;	
				}
			}
		}
		//alert(tt+total_room);
		charge_amount = charge_amount +  service_room;
		tax_amount = tax_amount + tax_room;
		jQuery('.add_paid').each(function(){
			tt += to_numeric(this.value);	
			//charge_amount += to_numeric(this.lang);	
			//tax_amount += to_numeric(this.alt);	
		});
		jQuery('#total_amount').val(number_format(tt+total_room));
		jQuery('#tax_amount').val(number_format(tax_amount));
		if(hotel_currency=='USD'){
			tax_amount = roundNumberCeil(tax_amount,2);		
		}
		jQuery('#service_charge_amount').val(number_format(charge_amount));
		//var total_amount = roundNumberCeil(to_numeric(jQuery('#total_amount').val()),0);
		var total_amount = to_numeric(jQuery('#total_amount').val());
		/** THL**/
		if(hotel_currency=='USD'){
			var total_payment = roundNumberFloor(total_amount - to_numeric(jQuery('#paid_group').val()),2);
		}else{
			var total_payment = roundNumber(total_amount - to_numeric(jQuery('#paid_group').val()),0);
		}
		/** THL**/
		if(total_deposit>0){
			jQuery('#tr_total_vat').css('display','');
			jQuery('#total_vat').val(number_format(total_payment));
		}
		jQuery('#total_payment').val(number_format(total_payment-total_deposit));
        /*if(jQuery('#foc_all').val()!=0){
			jQuery('#total_payment').val('FOC_ALL');
		}*/
        //start: KID them tinh balance
        jQuery('#balance').val(number_format(total_payment-total_deposit-balance));
        if(total_payment-total_deposit-balance<=1000)
        {
            jQuery('#balance').val(0);
        }
        //end: KID them tinh balance	
        if(to_numeric(jQuery('#total_payment').val()) == 0 || !is_numeric(to_numeric(jQuery('#total_payment').val()))){
            jQuery('#payment_method_bound').css('display','none');
        }
        else{
            jQuery('#payment_method_bound').css('display','block');
            if(to_numeric(jQuery('#total_payment').val()) < 0){
                jQuery('#payment').val('[[.refun.]]');
    		}else{
    			jQuery('#payment').val('[[.payment.]]');
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
	function ChangeAmount(obj,items,key,itm){
		var valu = obj.value;
			if(is_numeric(to_numeric(valu))){
				var value = to_numeric(valu);
				if(itm == 'percent'){
					if(value <= 100){				
						var new_value = (value * to_numeric(jQuery('#amount_'+items+'_'+key).val()))/100;
						jQuery('#amount_'+key+'_'+items).val(number_format(new_value));
						traveller_folios[items+'_'+key]['percent'] = to_numeric(valu);
						traveller_folios[items+'_'+key]['amount'] = to_numeric(new_value);
						UpdateTotal();
					}else{
						alert('[[.not_allowed_greater_than_100.]]');	
						obj.value = obj.value.substr(0,obj.value.length-1);
					}
				}else if(itm == 'amount'){
				    //start:KID them input co id = amount2_... de lam chuc nang khong cho nhap so lon hon hien tai
				    if(value <= to_numeric(jQuery('#amount2_'+key+'_'+items).val()))
                    {
    					var new_percent = roundNumber((to_numeric(obj.value)/to_numeric(jQuery('#amount_'+items+'_'+key).val()))*100,3);
    					jQuery('#percent_'+key+'_'+items).val(new_percent);
    					traveller_folios[items+'_'+key]['percent'] = new_percent;
    					traveller_folios[items+'_'+key]['amount'] = to_numeric(valu);
    					UpdateTotal();
                    }
                    else
                    {
                        alert('[[.not_allowed_greater_than_simple_amount.]]');
                        obj.value = 0;
                        jQuery('#percent_'+key+'_'+items).val(0);
						traveller_folios[items+'_'+key]['percent'] = 0;
    					traveller_folios[items+'_'+key]['amount'] = 0;
    					UpdateTotal();
                    }
                    //end:KID them input co id = amount2_... de lam chuc nang khong cho nhap so lon hon hien tai
				}	
			}else{
				alert('[[.is_not_numeric.]]');	
				obj.value = obj.value.substr(0,obj.value.length-1);
			}
		
	}
	function DeleteItem(type,key,percent,$r_r_id){	
	    <?php echo 'var block_id = '.Module::block_id().';';?>
            jQuery.ajax({
					url:"form.php?block_id="+block_id,
					type:"POST",
					data:{delete_items_split_folio:1,reservation_room_id:$r_r_id},
					success:function(html)
                    {
                        console.log($r_r_id);
                        if(to_numeric(html)!=1){
                            jQuery('#'+type+'_split_'+key).remove();
                    		jQuery('#'+type+'_'+key).attr('checked',false);
                    		jQuery('#tr_'+type+'_'+key).css('background','#FFF');
                    		jQuery('#amount_'+type+'_'+key).css('background','#FFF');
                    		delete traveller_folios[type+'_'+key];
                    		if(jQuery.isEmptyObject(traveller_folios)){
                    			//jQuery('#hidden').css('display','none');
                    		}
                    		UpdateTotal();
                        }else{
                            alert('Phòng đã CheckOUT, không được xóa hóa đơn!');
                        }
					}
		});
		
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
                        		if(jQuery('#travellers_id').val() == 0 && traveller == '' && jQuery('#traveller_id').val() == ''){
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
    		if(jQuery('#travellers_id').val() == 0 && traveller == '' && jQuery('#traveller_id').val() == ''){
    			//alert('[[.you_must_select_traveller_id.]]');
    			//return false;	
    		} 
    		if(traveller_folios){			
    			jQuery('#act').val(act);
    			jQuery('#action').val(1);
    			CreateTravellerFolioForm.submit();	
    		}
        <?php } ?>
	}
	jQuery("#add_payment").click(function(){
		if(to_numeric(jQuery('#travellers_id').val()) != 0){
			jQuery("#check_add").show();
			return false;
		}else{
			alert('[[.you_must_select_traveller_id.]]');	
			return false;
		}
	});
    function checkNumber(obj){
    	if(!is_numeric(to_numeric(obj.value))){
    		alert('[[.is_not_number.]]');
    		obj.value = '';
    	}else{	}
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
    function OpenFolioGroup($value,$is_payment){
        if($value!=''){
            $arr = $value.split(',');
            $folio_id=$arr[0]; $traveller_id=$arr[1];
            window.location.href='<?php echo $herf_folio_group;?>&folio_id='+$folio_id+'&traveller_id='+$traveller_id+'&check_payment='+$is_payment+'&portal_id=<?php echo PORTAL_ID;?>';
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
                            alert('RealTime:\n Lưu ý, Folio đã được tài khoản '+html['user']+' chỉnh sửa trước đó, vào lúc :'+html['time']+' \n Dữ liệu hiện tại của bạn chưa được cập nhập nội dung chỉnh sửa đó \n \n Để tiếp tục thao tác bạn vui lòng ấn tải lại trang !')
                            return false;
                        }
                        else
                        {
                            if(confirm('[[.are_you_change_traveller.]] ?'))
                            {
                                    <?php echo 'var block_id = '.Module::block_id().';';?>
                                    jQuery.ajax({
                        					url:"form.php?block_id="+block_id,
                        					type:"POST",
                        					data:{change_traveller_folio:$r_r_traveller_folio,folio_id:$folio_id_check},
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
                            }
                            else
                            {
                                return false;
                            }
                        }
    				}
    	});
    }
</script>
