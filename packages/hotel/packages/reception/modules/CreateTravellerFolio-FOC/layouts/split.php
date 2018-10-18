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
	}	
</style>
<script type="text/javascript" src="packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js"></script>
<div id="post_show">
<form method="post" name="CreateTravellerFolioForm">
<div style="text-align:center; color:#36F; font-size:16px; font-weight:bold; margin-top:10px;">[[.create_folio.]]</div>
<div></div>
<div><?php echo Form::$current->error_messages();?></div>
 <?php $herf = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'form.php?block_id=429&cmd=create_folio&traveller_id='.Url::get('traveller_id').'&rr_id='.Url::get('rr_id').'';?>
<table width="87%" border="1" style="margin:20px auto; border:1px solid silver;" class="invoice">
	<tr id="title">
        <td width="50%">[[.code.]]:<span id="rr_id" style="text-align:left; color:#0A3680;"> <?php echo (Url::get('add_payment')?Url::get('add_payment'):(Url::get('rr_id')?Url::get('rr_id'):''));?></span>_[[.room.]]_[[|room_name|]]
        <span id="reservation_id" style="text-align:left; color:#0A3680;"><?php echo Url::get('r_id');?></span></td>
        <td width="50%"><span style="text-align:left; color:#0A3680;">
        <!--LIST:folio--> 
            <?php if(Url::get('folio_id') && [[=folio.id=]]==Url::get('folio_id')){echo Portal::language('folio').'_'.[[=folio.id=]];}?> 	
       <!--/LIST:folio-->
        </span><input name="add_payment" type="button" id="add_payment" value="Add" style="float:right;"  class="button-medium" />
        <input name="save" type="button" id="save" value="Save" style="float:right;" onclick="SubmitForm('save');" class="button-medium-save" />
        <?php if(Url::get('add_payment')){?>
        <a href="#" onclick="window.location='<?php echo $herf;?>&portal_id=<?php echo PORTAL_ID;?>'" >
        <input name="back" type="button" id="back" value="Back" style="float:right;" class="button-medium-back" /></a>
        <?php }?>
         <div id="check_add" style="display:none;position:absolute;top:auto;left:auto;border:1px solid #000000;padding:10px;text-align:center;background:#FFFF99;">
									[[.add_order_id.]]: <select name="order_ids" id="order_ids"></select>
                                    <?php $link_add = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'form.php?block_id=429&cmd=add_payment&traveller_id='.Url::get('traveller_id').'&rr_id='.Url::get('rr_id').'';
									$link_add .= (Url::get('folio_id')?('&folio_id='.Url::get('folio_id')):'');
									?>
                                    <a href="#" onclick="window.location='<?php echo $link_add;?>&add_payment='+jQuery('#order_ids').val()+'&portal_id=<?php echo PORTAL_ID;?>'"><input type="button" name="add" value="[[.add.]] " id="button_add" tabindex="-1" style="color:#000066;font-weight:bold;"></a>
									<a class="close" onclick="jQuery('#check_add').hide();$('order_ids').value='';">[[.close.]]</a>
		 </div>
        </td>
    </tr>
    <tr>
        <td>[[.folio_created.]]: <?php $k=0; $t=0;?>
        <input name="all" type="image" src="packages/hotel/skins/default/images/iosstyle/split.png" id="all" onclick=" jQuery('#ALL').attr('checked',true);checkSplit('','ALL');return false;" style="float:right;color:#676E73;" title="change_all"/><input type="checkbox" id="ALL"/>
        <?php if(Url::get('cmd')!='add_payment'){ ?>
       <!--LIST:folio--> 
             	<a href="<?php echo $herf;?>&folio_id=[[|folio.id|]]&portal_id=<?php echo PORTAL_ID;?>" ><input name="view_order" type="button" value="[[.folio.]]_[[|folio.num|]]" id="view_order" class="view-order-button" style="border:1px solid #FFCF66 !important; float:right; height:20px;" title="[[|folio.name|]]"/></a>
       <!--/LIST:folio--><?php }?> </td>   
        <td><span id="select_title">&nbsp;&nbsp;[[.select_traveller.]]: </span><select name="travellers_id" id="travellers_id" onchange="jQuery('#traveller_id').val(this.value);window.location='<?php echo 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'form.php?block_id=429&cmd=create_folio&rr_id='.Url::get('rr_id').''?>&traveller_id='+this.value;" style="width:140px;"></select><input name="traveller_id" value="<?php echo Url::get('traveller_id'); ?>" type="hidden" id="traveller_id" />
        <a href="<?php echo $herf;?>&act=new&portal_id=<?php echo PORTAL_ID;?>" ><input name="create_folio" type="button" value="[[.create_folio.]]" id="create_folio" class="view-order-button" style="border:1px solid #FFCF66 !important; float:right; height:20px;" title="[[.create_folio.]]"/></a>
         <?php $hef = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=create_traveller_folio&customer_id='.Url::get('customer_id').'&id='.Url::get('id').'&cmd=group_folio';?>
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
                            <td width="30"><input name="all_[[|items.type|]]" type="image" src="packages/hotel/skins/default/images/iosstyle/split.png" id="all_[[|items.type|]]" onclick="jQuery('#ALL_[[|items.type|]]').attr('checked',true);checkSplit('','[[|items.type|]]');return false;" style="float:right;" />
                            <input type="checkbox" id="ALL_[[|items.type|]]"/></td>
                        </tr>
                <?php }?>
                        <tr style="display:none;"><td colspan="2"><input name="foc" type="hidden" value="[[|foc|]]" id="foc" ><input name="foc_all" type="hidden" value="[[|foc_all|]]" id="foc_all" ></td></tr>
                        <tr id="tr_[[|items.type|]]_[[|items.id|]]" class="[[|items.type|]]_[[|items.id|]]" lang="[[|items.status|]]">  
                        <td width="400">
                            <div class="item-body" style="width:100%;">	
                                <div class="date" id="date_[[|items.type|]]_[[|items.id|]]">[[|items.date|]]</div>
                                <div class="description" id="description_[[|items.type|]]_[[|items.id|]]" style="width:150px !important;">[[|items.description|]]</div>
                                <div>				<?php if([[=items.type=]]=='LAUNDRY'){
																$her = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=laundry_invoice&id='.[[=items.id=]];
																echo '<a target="_blank" href="'.$her.'" style="float:left">#'.[[=items.id=]].'</a>';
															}else if([[=items.type=]]=='MINIBAR'){
																$her = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=minibar_invoice&id='.[[=items.id=]];
																echo '<a target="_blank" href="'.$her.'" style="float:left">#'.[[=items.id=]].'</a>';
															}else if([[=items.type=]]=='EQUIPMENT'){
																$her = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=equipment_invoice&id='.[[=items.id=]];
																echo '<a target="_blank" href="'.$her.'" style="float:left">#'.[[=items.id=]].'</a>';
															}else if([[=items.type=]]=='BAR'){
																$her = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=touch_bar_restaurant&cmd=detail&'.md5('act').'='.md5('print').'&id='.[[=items.id=]];
																echo '<a target="_blank" href="'.$her.'" style="float:left">#'.[[=items.id=]].'</a>';
															}?>
                                </div>              
                                <div class="amount" style="float:right;"><?php echo System::display_number(round(System::calculate_number([[=items.net_amount=]]),2));?><input name="amount_[[|items.type|]]_[[|items.id|]]" type="text" id="amount_[[|items.type|]]_[[|items.id|]]" value="[[|items.net_amount|]]" style="border:none; display:none;" readonly="readonly" class="amount"  lang="[[|items.service_rate|]]" alt="[[|items.tax_rate|]]"/> </div>
                            </div>
                        </td>
                        <td width="30"><input name="split_[[|items.type|]]_[[|items.id|]]" type="image" src="packages/hotel/skins/default/images/iosstyle/split.png" id="split_[[|items.type|]]_[[|items.id|]]" onclick="jQuery('#[[|items.type|]]_[[|items.id|]]').attr('checked',true);checkSplit('[[|items.id|]]','[[|items.type|]]');return false;" style="float:right;" lang="[[|items.percent|]]" />
                        <input type="checkbox" id="[[|items.type|]]_[[|items.id|]]" lang="[[|items.type|]]" alt="[[|items.id|]]" value="[[|items.amount|]]" name="[[|items.id|]]" class="[[|items.type|]]"/></td>
                        </tr>
                  <!--/LIST:items-->  
            </table> 
        </td>
        <td style="vertical-align:top;">
        	 <input name="all_type" type="hidden" id="all_type" value="ROOM,MINIBAR,LAUNDRY,EQUIPMENT,BAR,EXTRA_SERVICE,TELEPHONE,DEPOSIT,DISCOUNT" style="width:300px;"/>
            <input name="action" type="hidden" value="0" id="action" />
            <input name="act" type="hidden" value="" id="act" />
        	<table border="1" width="100%" cellpadding="3" style="border:1px solid silver;margin:auto;" id="table_split">
            	<tr style="text-align:center;">
                	<td style="width:50%;"><b>[[.detail.]]</b></td>
                    <td style="width:20%;"><b>[[.percent.]]</b></td>
                    <td style="width:25%;"><b>[[.amount.]]</b></td>
                    <td>&nbsp;</td>
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
                    <td><img align="left" src="packages/core/skins/default/images/buttons/delete.gif" title="delete" onclick="jQuery('#tr_add_paid_[[|add_payments.id|]]').remove();UpdateTotal();"></td>
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
                    <td></td>
                </tr>
                
            </table>
            <?php if(!Url::get('add_payment')){?>
            <div id="payment_bound">
           <fieldset>
            <legend><b><i>[[.summary.]]</i></b></legend>
             <div id="payment_method_bound">
            <?php $her = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=payment&id='.Url::get('rr_id').'&type=RESERVATION&act=create_folio&traveller_id='.Url::get('traveller_id').'&total_amount='.Url::get('total_payment').'';?>
            <input name="payment" type="button" id="payment" value="[[.payment.]]" onclick="SubmitForm('payment');" style="height:35px;width:90px;">
            </div>
            <?php if(Url::get('folio_id')){ 
				$hef = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=view_traveller_folio&traveller_id='.Url::get('traveller_id').'&folio_id='.Url::get('folio_id').'';?>
            <a target="_blank" href="<?php echo $hef;?>&portal_id=<?php echo PORTAL_ID;?>" ><input  type="button" id="view_invoice_#xxxx#" class="view-order-button" title="[[.view_order.]]" style="float:right;"><span style="font-weight:bold; color:#F63; float:right;">[[.view_folio.]]</span></a>
            <?php } ?>
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
var cmd = '<?php echo Url::get('cmd')?Url::get('cmd'):'';?>';
traveller_folios = {};
items_js = [[|items_js|]];// Tat ca cac ban ghi can thanh toan
traveller_folio = [[|traveller_folios_js|]];// Cac ban ghi cua folio nay
folio_other = [[|folio_other_js|]];// Cac ban ghi da dc thanh toasn boi folio khac
var add_pmt=[[|add_payment|]];
jQuery('#hidden').css('display','block');
	for(var j in traveller_folio){
		traveller_folios[traveller_folio[j]['type']+'_'+traveller_folio[j]['id']] = traveller_folio[j];
	}
	DrawSplitOrder();
	for(var i in folio_other){
		if(folio_other[i]['percent']==100){
			jQuery('#tr_'+folio_other[i]['type']+'_'+folio_other[i]['id']).css({'background':'#EFEFEF'});
			jQuery('#amount_'+folio_other[i]['type']+'_'+folio_other[i]['id']).css({'background':'#EFEFEF'});
			jQuery('#'+folio_other[i]['type']+'_'+folio_other[i]['id']).attr('checked',true);
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
UpdateTotal();
function checkSplit(id,type){
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
						itm['rr_id'] = rr_id;
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
					traveller_folios[id] = itm;
				}
			}
		}
	}
	DrawSplitOrder();
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
	jQuery('#table_split').html('<tr style="text-align:center;"><td style="width:50%;"><b>[[.detail.]]</b></td><td style="width:20%;"><b>[[.percent.]]</b></td><td style="width:25%;"><b>[[.amount.]]</b></td><td>&nbsp;</td></tr>');
	var deleteButton = '<img align="left" src="packages/core/skins/default/images/buttons/delete.gif" title="delete">';
	for(var i in traveller_folios){
		folios = traveller_folios[i];
		if(folios['date']){
		}else{
			//folios['date'] = '';
		}
		var string = '<tr id="'+folios['type']+'_split_'+folios['id']+'"><td style="width:50%;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][id]" type="text" id="traveller_folio['+folios['id']+'_'+folios['type']+'][id]" value="'+folios['id']+'" style="display:none;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][type]" type="text" id="type_'+folios['id']+'_'+folios['type']+'" value="'+folios['type']+'" style="display:none;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][date]" type="text" id="date_'+folios['id']+'_'+folios['type']+'" value="'+folios['date']+'" style="width:30%; height:26px; font-size:13px; text-align:left;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][description]" type="text" id="description_'+folios['id']+'_'+folios['type']+'" value="'+folios['description']+'" style="width:70%; height:26px; font-size:13px; text-align:left;"></td>';
		string += '<td style="width:20%;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][percent]" type="text" id="percent_'+folios['id']+'_'+folios['type']+'" value="'+folios['percent']+'" onkeyup="ChangeAmount(this,\''+folios['type']+'\',\''+folios['id']+'\',\'percent\');" onfocus="jQuery(this).css(\'border\',\'1px solid silver\');" class="input-number" style="width:50px;"> </td>';
		string += '<td style="width:25%;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][amount]" type="text" id="amount_'+folios['id']+'_'+folios['type']+'" value="'+number_format(folios['amount'])+'" onkeyup="ChangeAmount(this,\''+folios['type']+'\',\''+folios['id']+'\',\'amount\');this.value=number_format(this.value);" onfocus="jQuery(this).css(\'border\',\'1px solid silver\');" style="width:90px;" class="input-number"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][service_rate]" type="text" value="'+folios['service_rate']+'" style="display:none;"><input name="traveller_folio['+folios['id']+'_'+folios['type']+'][tax_rate]" type="text" value="'+folios['tax_rate']+'" style="display:none;"></td>';
		string += '<td onclick="DeleteItem(\''+folios['type']+'\','+folios['id']+','+folios['percent']+');">'+deleteButton+'</td>';
		string += '<td style="display:none;"></td></tr>';
		jQuery('#table_split').append(string);
		jQuery('#tr_'+folios['type']+'_'+folios['id']).css('background','#EFEFEF');
		jQuery('#amount_'+folios['type']+'_'+folios['id']).css('background','#EFEFEF');
		jQuery('#'+folios['type']+'_'+folios['id']).attr('checked',true);
		jQuery('#hidden').css('display','block');
		jQuery('#percent_'+folios['id']+'_'+folios['type']).ForceNumericOnly();
		jQuery('#amount_'+folios['id']+'_'+folios['type']).ForceNumericOnly();
		UpdateTotal();
	}
}
	function UpdateTotal(){
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
				if(jQuery('#foc_all').val()==0 && jQuery('#foc').val()==''){
					if(traveller_folios[i]['type'] == 'ROOM'){
						total_room = total_room + amount;	
						service_rate = traveller_folios[i]['service_rate'];
						tax_rate = traveller_folios[i]['tax_rate'];   
					}else if(traveller_folios[i]['type'] == 'DISCOUNT'){
						total_room = total_room - amount;	
					}
					service_room = total_room * service_rate/100;
					tax_room = (service_room + total_room)*tax_rate/100;
					//jQuery('#total_amount').val(number_format(total_room));
				}
			}else if(traveller_folios[i]['type']!='ROOM' || traveller_folios[i]['type']!='DISCOUNT'){
				var amount = to_numeric(traveller_folios[i]['amount']);
					if(traveller_folios[i]['type'] != 'DEPOSIT'){
						if(jQuery('#foc_all').val()==1){
							tt = 0;
							tax_amount = 0; charge_amount = 0;
						}else{
							amount_this = amount;		
							tt += amount_this;
							this_charge = amount_this*traveller_folios[i]['service_rate']/(100);
							charge_amount = charge_amount + this_charge;
							tax_amount = tax_amount + (this_charge+amount_this)*to_numeric(traveller_folios[i]['tax_rate'])/(100);
						}
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
		//tax_amount = roundNumberCeil(tax_amount,0);
		jQuery('#service_charge_amount').val(number_format(charge_amount));
		//var total_amount = roundNumberCeil(to_numeric(jQuery('#total_amount').val()),0);
		var total_amount = to_numeric(jQuery('#total_amount').val());
		//var total_payment = roundNumberFloor(total_amount + to_numeric(tax_amount) + to_numeric(charge_amount) - to_numeric(jQuery('#paid_group').val()),0);
		var total_payment = roundNumber(total_amount + to_numeric(tax_amount) + to_numeric(charge_amount) - to_numeric(jQuery('#paid_group').val()),0);
		
		if(total_deposit>0){
			jQuery('#tr_total_vat').css('display','');
			jQuery('#total_vat').val(number_format(total_payment));
		}
		jQuery('#total_payment').val(number_format(total_payment-total_deposit));
		if(jQuery('#foc_all').val()!=0){
			jQuery('#total_payment').val('FOC_ALL');
		}
		if(to_numeric(jQuery('#total_payment').val()) <= 0 || !is_numeric(to_numeric(jQuery('#total_payment').val()))){
			jQuery('#payment_method_bound').css('display','none');	
		}else{
			jQuery('#payment_method_bound').css('display','block');	
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
					var new_percent = roundNumber((to_numeric(obj.value)/to_numeric(jQuery('#amount_'+items+'_'+key).val()))*100,3);
					jQuery('#percent_'+key+'_'+items).val(new_percent);
					traveller_folios[items+'_'+key]['percent'] = new_percent;
					traveller_folios[items+'_'+key]['amount'] = to_numeric(valu);
					UpdateTotal();
				}	
			}else{
				alert('[[.is_not_numeric.]]');	
				obj.value = obj.value.substr(0,obj.value.length-1);
			}
		
	}
	function DeleteItem(type,key,percent){	
		jQuery('#'+type+'_split_'+key).remove();
		jQuery('#'+type+'_'+key).attr('checked',false);
		jQuery('#tr_'+type+'_'+key).css('background','#FFF');
		jQuery('#amount_'+type+'_'+key).css('background','#FFF');
		delete traveller_folios[type+'_'+key];
		if(jQuery.isEmptyObject(traveller_folios)){
			//jQuery('#hidden').css('display','none');
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
</script>