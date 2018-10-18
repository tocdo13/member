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
	
</style>
<script type="text/javascript" src="packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js"></script>
 <div id="post_show" style="width:920px; margin:auto;">
<form method="post" name="CreateTravellerFolioForm">
	<div style="text-align:center; color:#36F; font-size:16px; font-weight:bold; margin-top:10px;">[[.create_folio.]]</div>
	<div><?php echo Form::$current->error_messages();?></div>
	<table width="90%" border="1" style=" border:1px solid silver; margin:auto" class="invoice">
	<tr id="title">
        <td width="50%">[[.code.]]:<span id="rr_id" style="text-align:left; color:#0A3680;"> <?php echo (Url::get('add_payment')?Url::get('add_payment'):(Url::get('id')?Url::get('id'):''));?></span></td>
        <td width="50%"><span style="text-align:left; color:#0A3680;">
        <!--LIST:folio--> 
            <?php if(Url::get('folio_id') && [[=folio.id=]]==Url::get('folio_id')){echo Portal::language('folio').'_'.[[=folio.id=]];}?> 	
       <!--/LIST:folio-->
        </span><input name="add_payment" type="button" id="add_payment" value="Add" style="float:right;display:none;"  class="button-medium" />
        <input name="save" type="button" id="save" value="Save" style="float:right;" onclick="SubmitForm('save');" class="button-medium-save" />
         <div id="check_add" style="display:none;position:absolute;top:auto;left:auto;border:1px solid #000000;padding:10px;text-align:center;background:#FFFF99;">
									[[.add_order_id.]]: <select name="order_ids" id="order_ids"></select>
									<input type="button" name="add" value="[[.add.]] " id="button_add" tabindex="-1" onclick="var rr_id=jQuery('#rr_id').html();var tr_id=jQuery('#traveller_id').val(); var oder_id = jQuery('#order_ids').val();jQuery('#post_show').remove();openWindowUrl('http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=504&cmd=add_payment&add_payment='+oder_id+'&traveller_id='+tr_id+'&rr_id='+rr_id,Array('','','80','210','950','500'));" style="color:#000066;font-weight:bold;">
									<a class="close" onclick="jQuery('#check_add').hide();$('order_ids').value='';">[[.close.]]</a>
		 </div>
        </td>
    </tr>
    <tr>
        <td>
        <?php $herf = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'form.php?block_id=429&cmd=group_folio&customer_id='.Url::get('customer_id').'&id='.Url::get('id').'';?>
        <input name="all_room_rate" type="button" id="all_room_rate" value="[[.all_room.]]" onclick="checkSplit('ALL','ROOM','');" title="[[.pay_all_room_amount.]]" />
        <input name="all" type="image" src="packages/hotel/skins/default/images/iosstyle/split.png" id="all" onclick=" jQuery('#ALL').attr('checked',true);checkSplit('ALL','',''); return false;" style="float:right;color:#676E73;" title="change_all"/><input type="checkbox" id="ALL"/>
        <!--LIST:folio--> 
             	<a href="#" onclick="window.location.href='<?php echo $herf;?>&folio_id=[[|folio.id|]]&portal_id=<?php echo PORTAL_ID;?>';" ><input name="view_order" type="button" value="[[.folio.]]_[[|folio.id|]]" id="view_order" class="view-order-button" style="border:1px solid #FFCF66 !important; float:right; height:20px;" title="[[|folio.name|]]"/></a>
       <!--/LIST:folio-->
      </td>
        <td><span id="select_title">&nbsp;&nbsp;[[.customer.]]: </span>[[|customer_name|]]<input name="customer_id" type="hidden" id="customer_id" value="[[|customer_id|]]" /><input name="traveller_folio" type="hidden" id="traveller_folio"  />
         <a href="<?php echo $herf;?>&act=new&portal_id=<?php echo PORTAL_ID;?>" ><input name="create_folio" type="button" value="create_folio" id="create_folio" class="view-order-button" style="border:1px solid #FFCF66 !important; float:right; height:20px;" title="[[.create_folio.]]"/></a>
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
                        <td width="30"><input name="reservation_room_[[|folios.id|]]" type="image" src="packages/hotel/skins/default/images/iosstyle/split.png" id="reservation_room_[[|folios.id|]]" onclick="checkSplit('[[|folios.id|]]','ALL_ROOM','');return false;" style="float:right;" />
                        <input type="checkbox" id="reservation_room_[[|folios.id|]]"/></td>
                    </tr>
                    <?php }?>    
                	<!--LIST:folios.items-->	
                <?php if($type != [[=folios.items.type=]] || $rr != [[=folios.id=]]){ $rr = [[=folios.id=]]; $type = [[=folios.items.type=]]; ?>       
                        <tr id="title_[[|folios.items.type|]]_[[|folios.id|]]">
                        	<td width="400"><input name="add_item_[[|folios.items.type|]]_[[|folios.id|]]" type="image" id="add_item_[[|folios.items.type|]]_[[|folios.id|]]" src="packages/hotel/skins/default/images/icons/add_item.gif" onclick="ItemDetail('[[|folios.items.type|]]_[[|folios.id|]]');return false;" style="float:left;" /><span> &nbsp; &nbsp; <b><em>[[|folios.items.type|]]</em></b></span>
                            	<input name="total_[[|folios.items.type|]]_[[|folios.id|]]" id="total_[[|folios.items.type|]]_[[|folios.id|]]" value="" style="float:right; text-align:right; border:none;" align="right" />
                            </td>
                            <td width="30"><input name="all_[[|folios.items.type|]]_[[|folios.id|]]" type="image" src="packages/hotel/skins/default/images/iosstyle/split.png" id="all_[[|folios.items.type|]]_[[|folios.id|]]" onclick="jQuery('#All_[[|folios.items.type|]]_[[|folios.id|]]').attr('checked',true);jQuery('.detail_[[|folios.items.type|]]_[[|folios.id|]]').attr('checked',true);checkSplit('[[|folios.id|]]','[[|folios.items.type|]]','');return false;" style="float:right; text-align:right; border:none;" />
                            <input type="checkbox" id="ALL_[[|folios.items.type|]]_[[|folios.id|]]"/></td>
                        </tr>
                <?php }?>
                        <tr style="display:none;"><td colspan="2"><input name="foc_[[|folios.id|]]" type="hidden" value="[[|folios.foc|]]" id="foc_[[|folios.id|]]" ><input name="foc_all_[[|folios.id|]]" type="hidden" value="[[|folios.foc_all|]]" id="foc_all_[[|folios.id|]]" ></td></tr>
                        <tr id="tr_[[|folios.items.type|]]_[[|folios.items.id|]]" class="[[|folios.items.type|]]_[[|folios.id|]]" style="display:none;" lang="[[|folios.items.status|]]">  
                        <td width="400">
                            <div class="item-body" style="width:100%;">	
                                <div class="date" id="date_[[|folios.items.type|]]_[[|folios.items.id|]]">[[|folios.items.date|]]</div>
                                <div class="description" id="description_[[|folios.items.type|]]_[[|folios.items.id|]]" style="width:150px !important;">[[|folios.items.description|]]</div>
                                <?php if([[=folios.items.type=]]=='LAUNDRY'){
																$her = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=laundry_invoice&id='.[[=folios.items.id=]];
																echo '<a target="_blank" href="'.$her.'" style="float:left">#'.[[=folios.items.id=]].'</a>';
															}else if([[=folios.items.type=]]=='MINIBAR'){
																$her = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=minibar_invoice&id='.[[=folios.items.id=]];
																echo '<a target="_blank" href="'.$her.'" style="float:left">#'.[[=folios.items.id=]].'</a>';
															}else if([[=folios.items.type=]]=='EQUIPMENT'){
																$her = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=equipment_invoice&id='.[[=folios.items.id=]];
																echo '<a target="_blank" href="'.$her.'" style="float:left">#'.[[=folios.items.id=]].'</a>';
															}else if([[=folios.items.type=]]=='BAR'){
																$her = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=touch_bar_restaurant&cmd=detail&'.md5('act').'='.md5('print').'&id='.[[=folios.items.id=]];
																echo '<a target="_blank" href="'.$her.'" style="float:left">#'.[[=folios.items.id=]].'</a>';
															}	?>           
                                <div class="amount" style="float:right;"><?php echo System::display_number(round(System::calculate_number([[=folios.items.net_amount=]]),2));?><input name="amount_[[|folios.items.type|]]_[[|folios.items.id|]]" type="text" id="amount_[[|folios.items.type|]]_[[|folios.items.id|]]" value="[[|folios.items.net_amount|]]" style="border:none;font-size:11px; text-align:center;float:right; display:none;" readonly="readonly" class="amount" lang="[[|folios.items.service_rate|]]" alt="[[|folios.items.tax_rate|]]" /> </div>
                            </div>
                        </td>
                        <td width="30"><input name="split_[[|folios.items.type|]]_[[|folios.items.id|]]" type="image" src="packages/hotel/skins/default/images/iosstyle/split.png" id="split_[[|folios.items.type|]]_[[|folios.items.id|]]" onclick="jQuery('#detail_[[|folios.items.type|]]_[[|folios.items.id|]]').attr('checked',true);checkSplit('[[|folios.id|]]','[[|folios.items.type|]]','[[|folios.items.type|]]_[[|folios.items.id|]]');return false;" style="float:right;" lang="[[|folios.items.percent|]]" />
                        <input type="checkbox" id="[[|folios.items.type|]]_[[|folios.items.id|]]" lang="[[|folios.items.type|]]" alt="[[|folios.items.id|]]" value="[[|folios.items.amount|]]" name="[[|folios.items.id|]]" class="detail_[[|folios.items.type|]]_[[|folios.id|]]"/></td>
                        </tr>
                <!--/LIST:folios.items-->
             <!--/LIST:folios-->           
            </table> 
        </td>
        <td style="vertical-align:top;">
        	 <input name="all_type" type="hidden" id="all_type" value="ROOM,ROOM_SERVICE,SERVICE,MINIBAR,LAUNDRY,EQUIPMENT,BAR,EXTRA_SERVICE,TELEPHONE,DISCOUNT,DEPOSIT,DEPOSIT_GROUP" style="width:300px;"/>
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
            	<legend><b><i>[[.payment_method.]]</i></b></legend>
                <div id="payment_method_bound">
                    <input name="payment" type="button" id="payment" value="[[.payment.]]" onclick="SubmitForm('payment');" style="height:35px;width:90px;">
                    <?php $hef = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=view_traveller_folio&cmd=group_invoice&customer_id='.Url::get('customer_id').'&id='.Url::get('id').'&folio_id='.Url::get('folio_id').'';?>
                </div>
                <?php if(Url::get('folio_id')){?>
                <a target="_blank" href="<?php echo $hef;?>&portal_id=<?php echo PORTAL_ID;?>" ><input  type="button" id="view_invoice_#xxxx#" class="view-order-button" title="[[.view_order.]]" style="float:right;"><span style="font-weight:bold; color:#F63; float:right;">[[.view_folio.]]</span></a>
                <?php }?>
          		</fieldset>
          </div>  
        </td>
    </tr>
</table>
</form>
</div>
<script>
var traveller_id = '<?php echo Url::get('traveller_id')?Url::get('traveller_id'):'';?>';
var cmd = '<?php echo Url::get('cmd')?Url::get('cmd'):'';?>';
traveller_folios = {};
traveller_folio = {};
folio_other={};
total = {};
amount = [];
traveller_folio = [[|traveller_folios_js|]];// Cac ban ghi cua folio nay
folio_other = [[|folio_other_js|]];// Cac ban ghi da dc thanh toasn boi folio khac
	for(var j in traveller_folio){
		traveller_folios[traveller_folio[j]['type']+'_'+traveller_folio[j]['id']] = traveller_folio[j];
	}
	DrawSplitOrder();
	for(j in items_js){
		check = 0;
		for(var k in items_js[j]['items']){
			items = items_js[j]['items'][k];
			if(items['status']==0){
				if(total[''+items['type']+'_'+items['rr_id']+'']){
					total[''+items['type']+'_'+items['rr_id']+''] += to_numeric(items['amount']);
				}else{
					total[''+items['type']+'_'+items['rr_id']+''] = to_numeric(items['amount']);
				}
			}else{
				jQuery('#tr_'+items['type']+'_'+items['id']).css({'background':'#EFEFEF'});
				jQuery('#amount_'+items['type']+'_'+items['id']).css({'background':'#EFEFEF'});
				jQuery('#'+items['type']+'_'+items['id']).attr('checked',true);
				if(total[''+items['type']+'_'+items['rr_id']+'']){
				}else{total[''+items['type']+'_'+items['rr_id']+''] =  0;
				}
			}
			jQuery('#total_'+items['type']+'_'+items['rr_id']).val(number_format(total[''+items['type']+'_'+items['rr_id']+'']));
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
	function checkSplit(rr_id,type,obj){
		if(rr_id=='ALL'){
			if(type=='ROOM'){
				for(var j in items_js){
					for(var k in items_js[j]['items']){
						items = items_js[j]['items'][k];
						if(items['status']==0){
							if(traveller_folios['ROOM_'+items['id']]){
							}else if(items['type']=='ROOM'){
								traveller_folios[items['type']+'_'+items['id']] = items;
							}
						}
					}
				}
			}else{
				for(var j in items_js){
					for(var k in items_js[j]['items']){
						items = items_js[j]['items'][k];
						if(items['status']==0){
							if(traveller_folios[items['type']+'_'+items['id']]){
							}else{
								traveller_folios[items['type']+'_'+items['id']] = items;
							}
						}
					}
				}
			}
		}else {
			if(obj==''){
				if(type=='ALL_ROOM'){
					all_type = jQuery('#all_type').val();
					all_type = all_type.split(',');
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
									itm['rr_id'] = rr_id;
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
								itm['rr_id'] = rr_id;
								traveller_folios[id] = itm;
							}
						}
					});	
				}
			}else{
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
						itm['rr_id'] = rr_id;
						traveller_folios[id] = itm;
					}
				}
			}
		}
		DrawSplitOrder();
	}
function DrawSplitOrder(){
	jQuery('#table_split').html('<tr style="text-align:center;"><td style="width:50%;"><b>[[.detail.]]</b></td><td style="width:20%;"><b>[[.percent.]]</b></td><td style="width:25%;"><b>[[.amount.]]</b></td><td>&nbsp;</td></tr>');
	var deleteButton = '<img align="left" src="packages/core/skins/default/images/buttons/delete.gif" title="delete">';
	var type = '';var typ = ''; amount = {};
	travellers = {};
	all_type = jQuery('#all_type').val();
	all_type = all_type.split(',');
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
			str += '<td style="width:25%;"><input name="split_expan_amount_'+folios['type']+'" type="text" id="split_expan_amount_'+folios['type']+'" value="'+number_format(amount[''+type+''])+'" style="width:90px;" readonly="readonly"></td>';
			str += '<td onclick="DeleteItem(\''+folios['type']+'\',\'\',\'\');">'+deleteButton+'</td>';
			str += '<td style="display:none;"></td></tr>';
			var string = '<tr id="'+folios['type']+'_split_'+folios['id']+'" class="'+folios['type']+'" style="display:none;" lang="'+folios['id']+'"><td style="width:50%;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][id]" type="text" id="traveller_folio['+folios['id']+'_'+folios['type']+'][id]" value="'+folios['id']+'" style="display:none;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][type]" type="text" id="type_'+folios['id']+'_'+folios['type']+'" value="'+folios['type']+'" style="display:none;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][type]" type="text" id="type_'+folios['id']+'_'+folios['type']+'" value="'+folios['type']+'" style="display:none;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][date]" type="text" id="date_'+folios['id']+'_'+folios['type']+'" value="'+folios['date']+'" style="width:30%; height:26px; font-size:13px; text-align:left;" readonly="readonly"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][description]" type="text" id="description_'+folios['id']+'_'+folios['type']+'" value="'+folios['description']+'" style="width:70%; height:26px; font-size:13px; text-align:left;" readonly="readonly"></td>';
			string += '<td style="width:20%;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][percent]" type="text" id="percent_'+folios['id']+'_'+folios['type']+'" value="'+folios['percent']+'" onkeyup="ChangeAmount(this,\''+folios['type']+'\','+folios['id']+',\'percent\');" onfocus="jQuery(this).css(\'border\',\'1px solid silver\');" class="percent_payment" style="width:50px;"> </td>';
			string += '<td style="width:25%;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][amount]" type="text" id="amount_'+folios['id']+'_'+folios['type']+'" value="'+number_format(folios['amount'])+'" onkeyup="ChangeAmount(this,\''+folios['type']+'\','+folios['id']+',\'amount\'); this.value=number_format(this.value);" onfocus="jQuery(this).css(\'border\',\'1px solid silver\');" style="width:90px;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][service_rate]" type="text" value="'+folios['service_rate']+'" style="display:none;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][tax_rate]" type="text" value="'+folios['tax_rate']+'" style="display:none;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][rr_id]" type="text" value="'+folios['rr_id']+'" style="display:none;"></td>';
			string += '<td onclick="DeleteItem(\''+folios['type']+'\','+folios['id']+','+folios['percent']+');">'+deleteButton+'</td>';
			string += '<td style="display:none;"></td></tr>';
			jQuery('#table_split').append(str);
			jQuery('#table_split').append(string);
			k++;
		}else{
			var string = '<tr id="'+folios['type']+'_split_'+folios['id']+'" class="'+folios['type']+'" style="display:none;" lang="'+folios['id']+'"><td style="width:50%;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][id]" type="text" id="traveller_folio['+folios['id']+'_'+folios['type']+'][id]" value="'+folios['id']+'" style="display:none;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][type]" type="text" id="type_'+folios['id']+'_'+folios['type']+'" value="'+folios['type']+'" style="display:none;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][date]" type="text" id="date_'+folios['id']+'_'+folios['type']+'" value="'+folios['date']+'" style="width:30%; height:26px; font-size:13px; text-align:left;" readonly="readonly"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][description]" type="text" id="description_'+folios['id']+'_'+folios['type']+'" value="'+folios['description']+'" style="width:70%; height:26px; font-size:13px; text-align:left;" readonly="readonly"></td>';
			string += '<td style="width:20%;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][percent]" type="text" id="percent_'+folios['id']+'_'+folios['type']+'" value="'+folios['percent']+'" onkeyup="ChangeAmount(this,\''+folios['type']+'\','+folios['id']+',\'percent\');" onfocus="jQuery(this).css(\'border\',\'1px solid silver\');" class="percent_payment" style="width:50px;"> </td>';
			string += '<td style="width:25%;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][amount]" type="text" id="amount_'+folios['id']+'_'+folios['type']+'" value="'+number_format(folios['amount'])+'" onkeyup="ChangeAmount(this,\''+folios['type']+'\','+folios['id']+',\'amount\'); this.value=number_format(this.value);" onfocus="jQuery(this).css(\'border\',\'1px solid silver\');" style="width:90px;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][service_rate]" type="text" value="'+folios['service_rate']+'" style="display:none;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][tax_rate]" type="text" value="'+folios['tax_rate']+'" style="display:none;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][rr_id]" type="text" value="'+folios['rr_id']+'" style="display:none;"></td>';
			string += '<td onclick="DeleteItem(\''+folios['type']+'\','+folios['id']+','+folios['percent']+');">'+deleteButton+'</td>';
			string += '<td style="display:none;"></td></tr>';
			jQuery('#table_split').append(string);	
			k++;	
		}
		jQuery('#tr_'+folios['type']+'_'+folios['id']).css('background','#EFEFEF');
		jQuery('#amount_'+folios['type']+'_'+folios['id']).css('background','#EFEFEF');
		jQuery('#'+folios['type']+'_'+folios['id']).attr('checked',true);
		jQuery('#percent_'+folios['id']+'_'+folios['type']).ForceNumericOnly();
		jQuery('#amount_'+folios['id']+'_'+folios['type']).ForceNumericOnly();
	}
	if(k>0){
		jQuery('#hidden').css('display','block');
	}
	UpdateTotal();
}

	function UpdateTotal(){
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
				if(jQuery('#foc_all_'+traveller_folios[i]['rr_id']).val()==0 && jQuery('#foc_'+traveller_folios[i]['rr_id']).val() ==''){
					if(traveller_folios[i]['type'] == 'ROOM'){
						if(total_room_amount[traveller_folios[i]['rr_id']]){
							total_room_amount[traveller_folios[i]['rr_id']] += amount;	
						}else{
							total_room_amount[traveller_folios[i]['rr_id']] = amount;
						}
						service_rate[traveller_folios[i]['rr_id']] = traveller_folios[i]['service_rate'];
						tax_rate[traveller_folios[i]['rr_id']] = traveller_folios[i]['tax_rate']; 
					}else if(traveller_folios[i]['type'] == 'DISCOUNT'){
						discount_amount[traveller_folios[i]['rr_id']] = amount;
					}
					
					//service_room = total_room * service_rate/100;
					//tax_room = (service_room + total_room)*tax_rate/100;
					//jQuery('#total_amount').val(number_format(total_room));
				}
			}else if(traveller_folios[i]['type']!='ROOM' || traveller_folios[i]['type']!='DISCOUNT'){
					var amount = to_numeric(traveller_folios[i]['amount']);
					if(traveller_folios[i]['type'] != 'DEPOSIT' && traveller_folios[i]['type'] != 'DEPOSIT_GROUP'){
						if(jQuery('#foc_all_'+traveller_folios[i]['rr_id']).val()== 1){
							tt = 0;
							tax_amount = 0; charge_amount = 0;
						}else{
							amount_this = amount;		
							tt += amount_this;
							this_charge = amount_this*traveller_folios[i]['service_rate']/(100);
							charge_amount = charge_amount + this_charge;
							tax_amount = tax_amount + (this_charge+amount_this)*to_numeric(traveller_folios[i]['tax_rate'])/(100);
						}
					}else if(traveller_folios[i]['type'] == 'DEPOSIT' || traveller_folios[i]['type'] == 'DEPOSIT_GROUP'){
						total_deposit += amount;	
					}
			}
		}
		//alert(tt+total_room);
		for(var t in total_room_amount){
			if(discount_amount[t] && discount_amount[t]>0){
				total_room_amount[t] = to_numeric(total_room_amount[t]) - to_numeric(discount_amount[t]);	
			}
			total_room = total_room + to_numeric(total_room_amount[t]); 
			service_room =service_room + total_room_amount[t]*service_rate[t]*0.01;
			tax_room =tax_room + (total_room_amount[t] + total_room_amount[t]*service_rate[t]*0.01)* tax_rate[t]*0.01;
		}
		//alert(tt+total_room);
		jQuery('#total_amount').val(number_format(tt+total_room));
		charge_amount = charge_amount +  service_room;
		tax_amount = tax_amount + tax_room;
		jQuery('#tax_amount').val(number_format(tax_amount));
		//tax_amount = roundNumberCeil(tax_amount,0);
		//var total_amount = roundNumberCeil(to_numeric(jQuery('#total_amount').val()),0);
		var total_amount = to_numeric(jQuery('#total_amount').val());
		jQuery('#service_charge_amount').val(number_format(charge_amount));
		//var total_payment = roundNumberFloor(total_amount + to_numeric(tax_amount) + to_numeric(charge_amount),-1);
		var total_payment = roundNumber(total_amount + to_numeric(tax_amount) + to_numeric(charge_amount),0);
		//total_payment = roundNumber(total_payment);
		if(total_deposit>0){
			jQuery('#tr_total_vat').css('display','');
			jQuery('#total_vat').val(number_format(total_payment));
		}
		jQuery('#total_payment').val(number_format(total_payment - total_deposit));
		if(to_numeric(jQuery('#total_payment').val()) <= 0 || !is_numeric(to_numeric(jQuery('#total_payment').val()))){
			jQuery('#payment_method_bound').css('display','none');	
		}else{
			jQuery('#payment_method_bound').css('display','block');	
		}
	}
	function ChangeAmount(obj,type,key,itm){
		var valu = obj.value;
			if(is_numeric(to_numeric(valu))){
				var value = to_numeric(valu);
				if(itm == 'percent'){
					if(value <= 100){				
						var new_value = (value * to_numeric(jQuery('#amount_'+type+'_'+key).val()))/100;
						traveller_folios[type+'_'+key]['percent'] = value;
						traveller_folios[type+'_'+key]['amount'] = new_value;
						if(is_numeric(amount[''+type+''])){
							amount[''+type+''] = amount[''+type+''] - to_numeric(jQuery('#amount_'+key+'_'+type).val()) + new_value;
							jQuery('#split_expan_amount_'+type).val(number_format(amount[''+type+'']));
						}
						jQuery('#amount_'+key+'_'+type).val(number_format(new_value));
						UpdateTotal();
					}else{
						alert('[[.not_allowed_greater_than_100.]]');
						obj.value = obj.value.substr(0,obj.value.length-1);	
					}
				}else if(itm == 'amount'){
					var new_percent = roundNumber((to_numeric(obj.value)/to_numeric(jQuery('#amount_'+type+'_'+key).val()))*100,3);
					if(amount[''+type+'']){
						amount[''+type+''] = amount[''+type+''] - (to_numeric(jQuery('#amount_'+type+'_'+key).val())*to_numeric(jQuery('#percent_'+key+'_'+type).val())*0.01) + to_numeric(valu);
						jQuery('#split_expan_amount_'+type).val(number_format(amount[''+type+'']));
					}
					jQuery('#percent_'+key+'_'+type).val(new_percent);
					traveller_folios[type+'_'+key]['percent'] = new_percent;
					traveller_folios[type+'_'+key]['amount'] = to_numeric(valu);
					UpdateTotal();
				}
			}else{
				alert('[[.is_not_numeric.]]');	
				obj.value = obj.value.substr(0,obj.value.length-1);
			}
		
	}
	function DeleteItem(type,key,percent){
		if(key==''){
			jQuery('.'+type).each(function(){
				jQuery('#split_expan_'+type).remove();
				jQuery('#'+type+'_split_'+this.lang).remove();
				jQuery('#'+type+'_'+this.lang).attr('checked',false);
				jQuery('#tr_'+type+'_'+this.lang).css('background','#FFF');
				jQuery('#amount_'+type+'_'+this.lang).css('background','#FFF');
				if(amount[''+type+'']){
					amount[''+type+''] -= to_numeric(traveller_folios[type+'_'+this.lang]['amount']);
				}

				delete traveller_folios[type+'_'+this.lang];
			});
			if(jQuery.isEmptyObject(traveller_folios)){
				jQuery('#hidden').css('display','none');
			}
			//jQuery('#split_expan_amount_'+type).val(number_format(amount[''+type+'']));
			jQuery('#split_expan_'+type).remove();
		}else{	
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
		UpdateTotal();
	}
	function SubmitForm(act){
		var k =0;
		var traveller = '<?php if(Url::get('traveller_id')){echo Url::get('traveller_id');}else{ echo '';}?>';
		if(jQuery('#travellers_id').val() == 0 && traveller == '' && jQuery('#traveller_id').val() == ''){
			alert('[[.you_must_select_traveller_id.]]');
			return false;	
		}
		if(traveller_folios){			
			jQuery('#act').val(act);
			jQuery('#action').val(1);
			CreateTravellerFolioForm.submit();	
		}
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
</script>