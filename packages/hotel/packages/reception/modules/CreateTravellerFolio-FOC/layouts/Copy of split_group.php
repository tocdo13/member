<style>
	.room_service_detail,.extra_service_detail,.service_detail{
		display:none;	
	}
	input[type=text]{
		width:100px;
		text-align:right !important;
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
		width:900px;
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
<div id="post_show">
<form method="post" name="CreateTravellerFolioForm">
<div style="text-align:center; color:#36F; font-size:16px; font-weight:bold; margin-top:10px;">[[.create_folio.]]</div>
<div></div>
<div><?php echo Form::$current->error_messages();?></div>
<table width="90%" border="1" style="margin:20px auto; border:1px solid silver;" class="invoice">
	<tr id="title">
        <td width="50%">[[.code.]]:<span id="rr_id" style="text-align:left; color:#0A3680;"> <?php echo (Url::get('add_payment')?Url::get('add_payment'):(Url::get('id')?Url::get('id'):''));?></span></td>
        <td width="50%"><span style="text-align:left; color:#0A3680;">[[.fo.]]</span><input name="add_payment" type="button" id="add_payment" value="Add" style="float:right;display:none;"  class="button-medium" />
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
        <?php $herf = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=create_traveller_folio&cmd=group_folio&customer_id='.Url::get('customer_id').'&id='.Url::get('id').'';?>
        <input name="all_room_rate" type="button" id="all_room_rate" value="[[.all_room.]]" onclick="CheckedBox('ROOM','');DrawSplitOrder();" title="[[.pay_all_room_amount.]]" />
        <input name="all" type="image" src="packages/hotel/skins/default/images/iosstyle/split.png" id="all" onclick=" jQuery('#ALL').attr('checked',true);CheckedBox('','');DrawSplitOrder(); return false;" style="float:right;color:#676E73;" title="change_all"/><input type="checkbox" id="ALL"/>
        <!--LIST:folio--> 
             	<a href="<?php echo $herf;?>&folio_id=[[|folio.id|]]&portal_id=<?php echo PORTAL_ID;?>" ><input name="view_order" type="button" value="[[.folio.]]_[[|folio.num|]]" id="view_order" class="view-order-button" style="border:1px solid #FFCF66 !important; float:right; height:20px;" title="[[|folio.name|]]"/></a>
       <!--/LIST:folio-->
      </td>
        <td><span id="select_title">&nbsp;&nbsp;[[.customer.]]: </span>[[|customer_name|]]<input name="customer_id" type="hidden" id="customer_id" value="[[|customer_id|]]" /><input name="traveller_folio" type="hidden" id="traveller_folio"  />
         <a href="<?php echo $herf;?>&act=new&portal_id=<?php echo PORTAL_ID;?>" ><input name="create_folio" type="button" value="create_folio" id="create_folio" class="view-order-button" style="border:1px solid #FFCF66 !important; float:right; height:20px;" title="[[.create_folio.]]"/></a>
       </td>
    </tr>
     <tr>
        <td style="vertical-align:top;">
        	<table border="1" width="100%" style="border:1px solid silver;margin:auto;" class="invoice" cellpadding="3">              
                    	 <!--LIST:items-->
                         <tr style="display:none;"><td colspan="2"><input name="foc_[[|items.bill_number|]]" type="hidden" id="foc_[[|items.bill_number|]]" value="[[|items.foc|]]" > <input name="foc_all_[[|items.bill_number|]]" type="hidden" id="foc_all_[[|items.bill_number|]]" value="[[|items.foc_all|]]" > </td></tr>
                        <tr id="title_room_rate">
                        	<td width="94%"><span><b><em>[[.room.]]: [[|items.room_number|]] ( [[.guest.]]: [[|items.traveller_name|]])</em></b></span></td>
                            <td width="5%"><input name="all_room" type="image" src="packages/hotel/skins/default/images/iosstyle/split.png" id="all_room" onclick="jQuery('#ALL_[[|items.bill_number|]]').attr('checked',true);CheckedBox('','[[|items.bill_number|]]');DrawSplitOrder();return false;" style="float:right;" /><input type="checkbox" id="ALL_[[|items.bill_number|]]"/></td>
                        </tr>
                    
                        <tr id="room_[[|items.bill_number|]]">  
                        <td width="94%">
                            <div class="item-body" style="width:100%;">	
                                <div class="date" id="date_ROOM_[[|items.bill_number|]]" style="display:none;">&nbsp;</div>
                                <div class="description" id="description_ROOM_[[|items.bill_number|]]" style="float:left;"><em>[[.total_room_amount.]]_[[|items.room_number|]]<!--IF:condfoc([[=items.foc=]]!='' || [[=items.foc_all=]]==1)--> (FOC)<!--/IF:condfoc--></em></div>              
                                <div class="amount"><input name="amount_ROOM_[[|items.bill_number|]]" value="<?php echo System::display_number(([[=items.room_amount=]]+[[=items.service_rate_amount=]]+[[=items.tax_rate_amount=]]));?>" type="text" id="amount_ROOM_[[|items.bill_number|]]"  style="border:none;width:80px !important;" readonly="readonly" class="amount" /></div>
                            </div>
                        </td>
                        <td width="5%"><input name="split_ROOM_[[|items.bill_number|]]" type="image" src="packages/hotel/skins/default/images/iosstyle/split.png" id="split_ROOM_[[|items.bill_number|]]" onclick="jQuery('#ROOM_[[|items.bill_number|]]').attr('checked',true);DrawSplitOrder(); return false;" style="float:right;" /><input name="[[|items.bill_number|]]" value="<?php echo System::display_number(([[=items.room_amount=]]+[[=items.service_rate_amount=]]+[[=items.tax_rate_amount=]]-[[=items.discount=]]));?>" type="checkbox" id="ROOM_[[|items.bill_number|]]" lang="0" alt="0" class="ROOM"/></td>
                        </tr>
                       
    					<?php //}?>
                         <!--IF:condtotal_ser([[=items.total_amount_room_services=]]>0 and [[=items.total_amount_room_services=]]!="0.00")-->
                         	 <tr id="room_service_[[|items.bill_number|]]">  
                                <td width="94%">
                                 <div class="item-body">			
                                    <div class="date" id="date_ROOM_SERVICE_[[|items.bill_number|]]" style="display:none;">&nbsp;<input name="add_item_room_service_[[|items.bill_number|]]" type="image" id="add_item_room_service_[[|items.bill_number|]]" alt="room_service" src="packages/hotel/skins/default/images/icons/add_item.gif" onclick="ItemDetail(this.alt,'[[|items.bill_number|]]');return false;" /></div>
                                    <div class="description" id="description_ROOM_SERVICE_[[|items.bill_number|]]">[[.room_service.]]_[[|items.room_number|]] &nbsp;<!--IF:condfoc_all([[=items.foc_all=]]==1)--> (FOC)<!--/IF:condfoc_all--></div>
                                    <div class="amount"><input name="amount_ROOM_SERVICE_[[|items.bill_number|]]" value="<?php echo System::display_number([[=items.total_amount_room_services=]])?>" type="text" id="amount_ROOM_SERVICE_[[|items.bill_number|]]" readonly="readonly" class="amount" style="border:none;"></div>
                                     </div>
                                  </td>
                                <td width="5%"><input name="split_room_service_[[|items.bill_number|]]" type="image" src="packages/hotel/skins/default/images/iosstyle/split.png" id="split_room_service_[[|items.bill_number|]]" onclick="jQuery('#ROOM_SERVICE_[[|items.bill_number|]]').attr('checked',true);DrawSplitOrder(); return false;" style="float:right;" /><input name="[[|items.bill_number|]]" type="checkbox" id="ROOM_SERVICE_[[|items.bill_number|]]" lang="0" alt="0" value="[[|items.total_amount_room_services|]]" class="ROOM_SERVICE"/></td>
                        </tr>    
                        <!--/IF:condtotal_ser-->
                        <!--LIST:items.services-->
							<!--IF:cond_roomx([[=items.services.type=]]=='ROOM')-->
                         <tr id="room_service_detail_[[|items.bill_number|]]" class="room_service_detail_[[|items.bill_number|]]" style="display:none;">  
                        <td width="400" colspan="2">
                   		 <div class="item-body">			
                            <div class="date" id="date_ROOM_SERVICE_[[|items.bill_number|]]" style="width:20%;" style="display:none;">&nbsp;</div>
                            <div class="description" id="description_ROOM_SERVICE_[[|items.bill_number|]]" style="width:40%;">[[|items.services.name|]]_[[|items.room_number|]]&nbsp;<!--IF:condfoc_all2([[=items.foc_all=]]==1)--> (FOC)<!--/IF:condfoc_all2--></div>
                            <div class="amount" style="width:38%;"><input name="amount_ROOM_SERVICE_[[|items.bill_number|]]" value="<?php echo System::display_number([[=items.services.amount=]])?>" type="text" id="amount_ROOM_SERVICE_[[|items.bill_number|]]" readonly="readonly" class="amount" style="border:none;width:88px;"></div>
                   			 </div>
                          </td>
                        </tr>    
                        <!--/IF:cond_roomx-->
                        <?php //if(URL::get('other_invoice')){?>
                         <!--IF:condtotal_service([[=items.total_amount_services=]]>0 and [[=items.total_amount_services=]]!="0.00")-->
                         	 <tr id="service_[[|items.bill_number|]]">  
                                <td width="94%">
                                 <div class="item-body">			
                                    <div class="date" id="date_SERVICE_[[|items.bill_number|]]" style="display:none;">&nbsp;</div>
                                    <div class="description" id="description_SERVICE_[[|items.bill_number|]]">[[.room_service.]]_[[|items.room_number|]]&nbsp;<!--IF:condfoc_all3([[=items.foc_all=]]==1)--> (FOC)<!--/IF:condfoc_all3--></div>
                                    <div class="amount"><input name="amount_SERVICE_[[|items.bill_number|]]" value="<?php echo System::display_number([[=items.total_amount_services=]])?>" type="text" id="amount_SERVICE_[[|items.bill_number|]]" readonly="readonly" class="amount" style="border:none;"></div>
                                     </div>
                                  </td>
                                <td width="5%"><input name="split_service_[[|items.bill_number|]]" type="image" src="packages/hotel/skins/default/images/iosstyle/split.png" id="split_service_[[|items.bill_number|]]" onclick="jQuery('#SERVICE_[[|items.bill_number|]]').attr('checked',true);DrawSplitOrder(); return false;" style="float:right;" /><input  name="[[|items.bill_number|]]" type="checkbox" id="SERVICE_[[|items.bill_number|]]" lang="0" alt="0" value="[[|services.amount|]]" class="SERVICE"/></td>
                        </tr>    
                        <!--/IF:condtotal_service-->
                        
                        <!--IF:cond_servicex([[=items.services.type=]]=='SERVICE')-->
                         <tr id="service_[[|items.bill_number|]]" class="service_detail_[[|items.bill_number|]]" style="display:none;">  
                       		<td width="94%">
                            <div class="item-body">	
                            <div class="date" id="date_SERVICE_[[|items.bill_number|]]" style="display:none;">&nbsp;</div>
                            <div class="description" id="description_SERVICE_[[|items.bill_number|]]">[[|items.services.name|]]_[[|items.room_number|]]&nbsp;<!--IF:condfoc_all4([[=items.foc_all=]]==1)--> (FOC)<!--/IF:condfoc_all4--></div>
                            <div class="amount"><input name="amount_SERVICE_[[|items.bill_number|]]" value="<?php echo System::display_number([[=items.services.amount=]])?>" type="text" id="amount_SERVICE_[[|items.bill_number|]]" readonly="readonly" class="amount" style="border:none;"  /></div>
                        </div>
                        </td>
                        <td width="5%"><input name="split_other_service_[[|items.bill_number|]]" type="image" src="packages/hotel/skins/default/images/iosstyle/split.png" id="split_other_service_[[|items.bill_number|]]"  onclick="jQuery('#SERVICE_[[|items.bill_number|]]').attr('checked',true);GetType('SERVICE');DrawSplitOrder(); return false;" style="float:right;" /><input name="[[|items.bill_number|]]" value="<?php echo System::display_number([[=items.services.amount=]])?>" type="checkbox" id="SERVICE_[[|items.bill_number|]]" lang="0" alt="0" class="SERVICE"/></td>
                        </tr> 
                      		<!--/IF:cond_servicex-->
                        <!--/LIST:items.services-->
                        <?php //}?>
                                    
                    
                    <?php //if(URL::get('hk_invoice')){?>
                   <!--IF:cond8m(isset([[=items.minibar=]]) and [[=items.minibar=]]!="0.00" and [[=items.minibar=]]>0)-->
                         <tr id="minibar_[[|items.bill_number|]]">  
                       		<td width="94%">
                                <div class="item-body">	
                                <div class="date" id="date_MINIBAR_[[|items.bill_number|]]" style="display:none;">&nbsp;</div>
                                <div class="description" id="description_MINIBAR_[[|items.bill_number|]]">[[.minibar.]]_[[|items.room_number|]]&nbsp;<!--IF:condfoc_all5([[=items.foc_all=]]==1)--> (FOC)<!--/IF:condfoc_all5--></div>
                                <div class="amount"><input name="amount_MINIBAR_[[|items.bill_number|]]" value="<?php echo System::display_number([[=items.minibar=]])?>" type="text" id="amount_MINIBAR_[[|items.bill_number|]]" readonly="readonly" class="amount" style="border:none;"  /> </div></div>
                            </td>
                             <td width="5%"><input name="split_minibar_[[|items.bill_number|]]" type="image" src="packages/hotel/skins/default/images/iosstyle/split.png" id="split_minibar_[[|items.bill_number|]]"  onclick="jQuery('#MINIBAR_[[|items.bill_number|]]').attr('checked',true);DrawSplitOrder(); return false;" style="float:right;" /><input name="[[|items.bill_number|]]" value="<?php echo System::display_number([[=items.minibar=]]);?>" type="checkbox" id="MINIBAR_[[|items.bill_number|]]" lang="0" alt="0" class="MINIBAR"/></td>
                        </tr> 
                     <!--/IF:cond8m-->        
                     <?php //}?>
                     <?php //if(URL::get('hk_invoice')){?>
                     <!--IF:cond8l(isset([[=items.laundry=]]) and [[=items.laundry=]]!="0.00" and [[=items.laundry=]]>0)-->
                     <tr id="laundry_[[|items.bill_number|]]">  
                      <td width="94%">
                        <div class="item-body">		
                            <div class="date" id="date_LAUNDRY_[[|items.bill_number|]]" style="display:none;">&nbsp;</div>
                            <div class="description" id="description_LAUNDRY_[[|items.bill_number|]]">[[.laundry.]]_[[|items.room_number|]]&nbsp;<!--IF:condfoc_all6([[=items.foc_all=]]==1)--> (FOC)<!--/IF:condfoc_all6--></div>
                            <div class="amount"><input name="amount_LAUNDRY_[[|items.bill_number|]]" value="<?php echo System::display_number([[=items.laundry=]]);?>" type="text" id="amount_LAUNDRY_[[|items.bill_number|]]" readonly="readonly" class="amount" style="border:none;"  /></div></div>
                      </td>
                      <td width="5%"><input name="split_LAUNDRY_[[|items.bill_number|]]" type="image" src="packages/hotel/skins/default/images/iosstyle/split.png" id="split_LAUNDRY_[[|items.bill_number|]]"  onclick="jQuery('#LAUNDRY_[[|items.bill_number|]]').attr('checked',true);DrawSplitOrder(); return false;" style="float:right;" /><input name="[[|items.bill_number|]]" value="<?php echo System::display_number([[=items.laundry=]]);?>" type="checkbox" id="LAUNDRY_[[|items.bill_number|]]" lang="0" alt="0" class="LAUNDRY"/></td>
                        </tr>    
                   <!--/IF:cond8l-->   
                     <?php //}?>
                 
                     <?php //if(URL::get('hk_invoice')){?>
                     <!--IF:condequip(isset([[=items.equipment=]]) and [[=items.equipment=]]!="0.00" and [[=items.equipment=]]>0)-->
                     <tr id="equip_[[|items.bill_number|]]">  
                      <td width="94%">
                        <div class="item-body">		
                            <div class="date" id="date_EQUIP_[[|items.bill_number|]]" style="display:none;">&nbsp;</div>
                            <div class="description" id="description_EQUIP_[[|items.bill_number|]]">[[.compensation.]]_[[|items.room_number|]]&nbsp;<!--IF:condfoc_all6([[=items.foc_all=]]==1)--> (FOC)<!--/IF:condfoc_all6--></div>
                            <div class="amount"><input name="amount_EQUIP_[[|items.bill_number|]]" value="<?php echo System::display_number([[=items.equipment=]]);?>" type="text" id="amount_EQUIP_[[|items.bill_number|]]" readonly="readonly" class="amount" style="border:none;"  /></div></div>
                      </td>
                      <td width="5%"><input name="split_EQUIP_[[|items.bill_number|]]" type="image" src="packages/hotel/skins/default/images/iosstyle/split.png" id="split_EQUIP_[[|items.bill_number|]]"  onclick="jQuery('#EQUIP_[[|items.bill_number|]]').attr('checked',true);DrawSplitOrder(); return false;" style="float:right;" /><input name="[[|items.bill_number|]]" value="<?php echo System::display_number([[=items.equipment=]]);?>" type="checkbox" id="EQUIP_[[|items.bill_number|]]" lang="0" alt="0" class="EQUIP"/></td>
                        </tr>    
                    <!--/IF:condequip-->
                     <?php //}?>
                     
                    <?php //if(URL::get('bar_invoice')){?>
                    <!--IF:cond7(isset([[=items.bar_service=]]) and [[=items.bar_service=]]!="0.00" and [[=items.bar_service=]]>0)-->
                     <tr id="bar_[[|items.bill_number|]]">  
                      <td width="94%">  
                    <div class="item-body">
                        <div class="date" id="date_BAR_[[|items.bill_number|]]" style="display:none;">&nbsp;</div>
                        <div class="description" id="description_BAR_[[|items.bill_number|]]">[[.restaurant.]]_[[|items.room_number|]]&nbsp;<!--IF:condfoc_all7([[=items.foc_all=]]==1)--> (FOC)<!--/IF:condfoc_all7--></div>
                         <div class="amount"><input name="amount_BAR_[[|items.bill_number|]]" value="<?php echo System::display_number([[=items.bar_service=]]);?>" type="text" id="amount_BAR_[[|items.bill_number|]]" readonly="readonly" class="amount" style="border:none;"  /></div></div>
                      </td>
                      <td width="5%"><input name="split_BAR_[[|items.bill_number|]]" type="image" src="packages/hotel/skins/default/images/iosstyle/split.png" id="split_BAR_[[|items.bill_number|]]"  onclick="jQuery('#BAR_[[|items.bill_number|]]').attr('checked',true);DrawSplitOrder(); return false;" style="float:right;" /><input name="[[|items.bill_number|]]" value="<?php echo System::display_number([[=items.bar_service=]]);?>" type="checkbox" id="BAR_[[|items.bill_number|]]" lang="0" alt="0" class="BAR"/></td>
                        </tr>    
                  <!--/IF:cond7-->
                    <?php //}//end bar invoice?>
                     <?php //if(URL::get('extra_service_invoice')){?>
                     
                  <!--IF:condtotal_extra([[=items.total_amount_extra_services=]]>0 and [[=items.total_amount_extra_services=]]!="0.00")-->
                         	 <tr id="extra_service_[[|items.bill_number|]]">  
                                <td width="94%">
                                 <div class="item-body">			
                                    <div class="date" id="date_EXTRA_SERVICE_[[|items.bill_number|]]" style="display:none;">&nbsp;</div>
                                    <div class="description" id="description_EXTRA_SERVICE_[[|items.bill_number|]]">&nbsp;<input name="add_item_extra_service_[[|items.bill_number|]]" type="image" id="add_item_extra_service_[[|items.bill_number|]]" alt="extra_service" src="packages/hotel/skins/default/images/icons/add_item.gif" onclick="ItemDetail(this.alt,'[[|items.bill_number|]]');return false;" />&nbsp;&nbsp;[[.extra_service.]]_[[|items.room_number|]]&nbsp;<!--IF:condfoc_all8([[=items.foc_all=]]==1)--> (FOC)<!--/IF:condfoc_all8--></div>
                                    <div class="amount"><input name="amount_EXTRA_SERVICE_[[|items.bill_number|]]" value="<?php echo System::display_number([[=items.total_amount_extra_services=]])?>" type="text" id="amount_EXTRA_SERVICE_[[|items.bill_number|]]" readonly="readonly" class="amount" style="border:none;"></div>
                                     </div>
                                  </td>
                                <td width="5%"><input name="split_extra_service_[[|items.bill_number|]]" type="image" src="packages/hotel/skins/default/images/iosstyle/split.png" id="split_extra_service_[[|items.bill_number|]]" onclick="jQuery('#EXTRA_SERVICE_[[|items.bill_number|]]').attr('checked',true);DrawSplitOrder(); return false;" style="float:right;" /><input  name="[[|items.bill_number|]]" type="checkbox" id="EXTRA_SERVICE_[[|items.bill_number|]]" lang="0" alt="0" value="[[|items.total_amount_extra_services|]]" class="EXTRA_SERVICE"/></td>
                        </tr>    
                        <!--/IF:condtotal_extra-->    
                 <!--LIST:items.extra_services-->  	
                        <tr id="extra_service_[[|items.bill_number|]]" class="extra_service_detail_[[|items.bill_number|]]" style="display:none;">
                      		<td width="94%">                         
                            <div class="item-body">	
                                <div class="date" id="date_EXTRA_SERVICE_DETAIL_[[|items.bill_number|]]" style="display:none;">&nbsp;</div>
                                <div class="description" id="description_EXTRA_SERVICE_DETAIL_[[|items.bill_number|]]">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[[|items.extra_services.name|]]_[[|items.room_number|]]&nbsp;<!--IF:condfoc_all9([[=items.foc_all=]]==1)--> (FOC)<!--/IF:condfoc_all9--></div>
                                <div class="amount"><input name="amount_EXTRA_SERVICE_DETAIL_[[|items.bill_number|]]" value="<?php echo System::display_number([[=items.extra_services.amount=]]);?>" type="text" id="amount_EXTRA_SERVICE_DETAIL_[[|items.bill_number|]]" readonly="readonly" class="amount" style="border:none;"  /></div></div>
                      </td>
                      <td width="5%"><input name="split_EXTRA_SERVICE_DETAIL_[[|items.bill_number|]]" type="image" src="packages/hotel/skins/default/images/iosstyle/split.png" id="split_EXTRA_SERVICE_DETAIL_[[|items.bill_number|]]"  onclick="jQuery('#EXTRA_SERVICE_DETAIL_[[|items.bill_number|]]').attr('checked',true);DrawSplitOrder(); return false;" style="float:right;" /><input name="[[|items.bill_number|]]" value="<?php echo System::display_number([[=items.extra_services.amount=]]);?>" type="checkbox" id="EXTRA_SERVICE_DETAIL_[[|items.bill_number|]]" lang="0" alt="0" class="EXTRA_SERVICE_DETAIL"/></td>
                        </tr>
                      <!--/LIST:items.extra_services--> 
                       <!--/LIST:items-->
                    <!--IF:condtotal_deposit([[=deposit=]]>0 and [[=deposit=]]!="0.00" and [[=deposit=]]>0)-->
                    <tr id="deposit_RE">  
                      		<td width="300">                         
                            <div class="item-body">	
                                <div class="date" id="date_DEPOSIT_RE" style="display:none;">&nbsp;</div>
                                <div class="description" id="description_DEPOSIT_RE"><b><em>[[.deposit.]]</em></b></div>
                                <div class="amount"><input name="amount_DEPOSIT_RE" value="<?php echo System::display_number([[=deposit=]]);?>" type="text" id="amount_DEPOSIT_RE"  readonly="readonly" class="amount" style="border:none;"  /></div></div>
                      </td>
                      <td width="30"><input name="split_DEPOSIT_RE" type="image" src="packages/hotel/skins/default/images/iosstyle/split.png" id="split_DEPOSIT_RE"  onclick="jQuery('#DEPOSIT_RE').attr('checked',true);DrawSplitOrder(); return false;" style="float:right;" /><input name="RE" type="checkbox" id="DEPOSIT_RE" lang="0" alt="0" value="[[|deposit|]]" class="DEPOSIT"/></td>
                     </tr>  
                 <!--/IF:condtotal_deposit-->
                <?php //}//end extra_service invoice?>         
            </table> 
        </td>
        <td style="vertical-align:top;">
        	 <input name="all_type" type="hidden" id="all_type" value="ROOM,ROOM_SERVICE,SERVICE,MINIBAR,LAUNDRY,EQUIP,BAR,EXTRA_SERVICE,DEPOSIT" style="width:300px;"/>
        	<input name="type" type="hidden" id="type" style="width:300px;"/>
            <input name="item_type" type="hidden" id="item_type" style="width:300px;"/>
            <input name="not_selected" type="hidden" id="not_selected" style="width:300px;"/>
            <input name="action" type="hidden" value="0" id="action" />
            <input name="act" type="hidden" value="" id="act" />
        	<table border="1" width="100%" cellpadding="3" style="border:1px solid silver;margin:auto;" id="table_split">
            
            </table>
            <div id="hidden" style="display:none;">
            <table border="1" width="100%" style="border:1px solid silver;margin:auto;">
            <!--LIST:paid_js-->
            	<tr>
                    <td style="width:70%;"><span style="float:right;"><b><em> <a onclick="var rr_id=jQuery('#rr_id').html();var tr_id=jQuery('#traveller_id').val(); var oder_id = jQuery('#order_ids').val();jQuery('#post_show').remove();openWindowUrl('http://<?php echo $_SERVER['HTTP_HOST'];?>/<?php echo Url::$root;?>form.php?block_id=504&cmd=add_payment&add_payment=[[|add_payments.id|]]&act=group&traveller_id='+tr_id+'&rr_id='+rr_id,Array('','','80','210','950','500'));"> [[|add_payments.decription|]]</a>:&nbsp; </em></b></span>&nbsp;</td>
                    <td style="width:25%;"><input name="paid_[[|paid_js.id|]]" type="text" id="paid_[[|paid_js.id|]]" value="[[|paid_js.total|]]" class="paid" style="border:none; width:100px; height:25px;" readonly="readonly"/></td>
                    <td></td>
                </tr>
            <!--/LIST:paid_js-->
                 <tr>
                    <td style="width:70%;"><span style="float:right;"><b><em>[[.total_payment.]]:&nbsp; </em></b></span>&nbsp;</td>
                    <td style="width:25%;"><input name="total_payment" type="text" id="total_payment" value="0" style="border:none; width:100px; height:25px;" readonly="readonly" /></td>
                    <td></td>
                </tr>
            </table>
            <div id="payment_method_bound">
           <fieldset>
            <legend><b><i>[[.payment_method.]]</i></b></legend>
            <input name="payment" type="button" id="payment" value="[[.payment.]]" onclick="SubmitForm('payment');" style="height:35px;width:90px;">
            <?php $hef = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=view_traveller_folio&cmd=group_invoice&customer_id='.Url::get('customer_id').'&id='.Url::get('id').'&folio_id='.Url::get('folio_id').'';?>
            <?php if(Url::get('folio_id')){?>
            <a target="_blank" href="<?php echo $hef;?>&portal_id=<?php echo PORTAL_ID;?>" ><input  type="button" id="view_invoice_#xxxx#" class="view-order-button" title="[[.view_order.]]" style="float:right;"><span style="font-weight:bold; color:#F63; float:right;">[[.view_folio.]]</span></a>
            <?php }?>
          </fieldset>
         </div>
         </div>  
        </td>
    </tr>
</table>
</form>
</div>
<script>
var traveller_id = '<?php echo Url::get('traveller_id')?Url::get('traveller_id'):'';?>';
var cmd = '<?php echo Url::get('cmd')?Url::get('cmd'):'';?>';
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
	var not_selected = '<?php echo Url::get('not_selected')?Url::get('not_selected'):',';?>';
	var item_type = '<?php echo Url::get('item_type')?Url::get('item_type'):',';?>';
	var y = 0;
	var payment_type = '<?php echo Url::get('payment_type')?Url::get('payment_type'):',';?>';
	/*if(payment_type != ','){
		var payment = jQuery('#payment_type').val();
			payment = payment.split(',');	
			for(var q in payment){
				if(payment[q] != ''){
					var q = payment[q];
					jQuery('#check_'+q).attr('checked',true);
				}
			}	
			GetPayment();
	}else{
		GetPayment();	
	}*/
	if(not_selected == ','){
		jQuery('#not_selected').val(not_selected);
	}
	if(item_type == ','){
		jQuery('#item_type').val(item_type);
	}
	GetEditType();
	function GetEditType(){
		var item_type = jQuery('#item_type').val();
			item_type = item_type.split(',');	
			for(var k in item_type){
				if(item_type[k] != ''){
					var str = item_type[k];
					str = str.substr(0,str.lastIndexOf("_"));
					jQuery('#'+str).attr('checked',true);
				}
			}
			DrawSplitOrder();
	}
	function CheckedBox(type,key){
		if(type != ''){
				jQuery('.'+type).each(function(){
					this.checked = true;
				});
		}else{
			var all_type  = jQuery('#all_type').val();
			all_type = all_type.split(",");
			if(key == ''){
				for(var k in all_type){
					jQuery('.'+all_type[k]).each(function(){
						this.checked = true;
					});		
				}
			}else{
				for(var k in all_type){
					jQuery('#'+all_type[k]+'_'+key).attr('checked',true);
				}	
			}
		}
	}
/*	function GetPayment(){
		jQuery('#payment_type').val('');
			jQuery('.checked').each(function(){
				var name = (this.name).substr((this.name).lastIndexOf("_")+1,(this.name).length -1);
				if(this.checked == false){
					jQuery('#check_'+name).val(0);	
					jQuery('#pay_'+name).val(0);	
				}else{
					var new_name = jQuery('#payment_type').val()+','+name;
					jQuery('#payment_type').val(new_name);	
				}
			});	
	}
*/function DrawSplitOrder(){
	jQuery('#table_split').html('');
	jQuery('#table_split').append('<tr style="text-align:center;"><td style="width:50%;"><b>[[.detail.]]</b></td><td style="width:20%;"><b>[[.percent.]]</b></td><td style="width:25%;"><b>[[.amount.]]</b></td><td>&nbsp;</td></tr>');
	jQuery('#total_payment').val(0);
	var item_type = jQuery('#item_type').val();
	var x='';
	var percent = 100;
	jQuery('#item_type').val('');
	type = jQuery('#all_type').val();
	type = type.split(',');
	for(var i in type){
		if(type[i] != ''){
			//jQuery('#table_split').append('<tr style="text-align:center;"><td colspan="3" style="text-align:left;"><b>'+hihi+'</b></td><td>&nbsp;</td></tr>');
			jQuery('.'+type[i]).each(function(){
				var deleteButton = '<img align="left" src="packages/core/skins/default/images/buttons/delete.gif" title="delete">';
				var key = this.name;
				var not_selected = jQuery('#not_selected').val();			
				not_selected = not_selected.split(",");
				for( var m in not_selected){
					if(not_selected[m] != ''){
						var sel = not_selected[m];
						if((sel.substr(0,sel.lastIndexOf("_")) == type[i]+'_'+key)){
							if(to_numeric(sel.substr(sel.lastIndexOf("_") +1,sel.length -1)) == 100){
								this.checked = false;	
								percent = 100;	
							}else{
								percent = 100 - to_numeric(sel.substr(sel.lastIndexOf("_") +1,sel.length -1));
							}
						}
					}
				}
				if(this.checked == 1){
					y = 1;
					items = item_type.split(",");
						for(var j in items){
							if(items[j] != ''){
								var string = items[j];
								if(string.substr(0,string.lastIndexOf("_")) == type[i]+'_'+key){
									percent =  string.substr(string.lastIndexOf("_")+1,string.length - 1);

								}
							}
						}
					jQuery('#'+type[i].toLowerCase()+'_'+key).css('background','#EFEFEF');
					jQuery('#'+type[i].toLowerCase()+'_'+key).css('color','#ef1e1e');
					jQuery('#amount_'+type[i]+'_'+key).css('background','#EFEFEF');	
					jQuery('#amount_'+type[i]+'_'+key).css('color','#8E8F8F');
					jQuery('#description_'+type[i]+'_'+key).css('color','#8E8F8F');
					jQuery('#date_'+type[i]+'_'+key).css('color','#8E8F8F');
					if(percent == ''){
						percent = 0;	
					}
					jQuery('#table_split').append('<tr id="'+type[i]+'_split_'+key+'"><td id="td_'+type[i]+'_split_'+key+'" style="width:50%;" ></td><td id="percent_'+type[i]+'_'+key+'" style="width:20%;"><input name="percent_'+type[i]+'_'+key+'_input" type="text" id="percent_'+type[i]+'_'+key+'_input"  onkeyup="ChangeAmount(this,\''+type[i]+'\',\''+key+'\',\'percent\');" onfocus="jQuery(\'#percent_'+type[i]+'_'+key+'_input\').css(\'border\',\'1px solid silver\');" class="percent_payment" style="width:50px;"/></td><td style="width:25%;"><input name="amount_'+type[i]+'_'+key+'_input" type="text" id="amount_'+type[i]+'_'+key+'_input" style="width:90px;" onkeyup="ChangeAmount(this,\''+type[i]+'\',\''+key+'\',\'amount\');" onfocus="jQuery(\'#amount_'+type[i]+'_'+key+'_input\').css(\'border\',\'1px solid silver\');"/></td><td onclick="DeleteItem(\''+type[i]+'\',\''+key+'\','+percent+');">'+deleteButton+'</td></tr>');
					jQuery('#td_'+type[i]+'_split_'+key).html(jQuery('#date_'+type[i]+'_'+key).html());
					jQuery('#td_'+type[i]+'_split_'+key).append(' '+jQuery('#description_'+type[i]+'_'+key).html());
					jQuery('#item_type').val(jQuery('#item_type').val() +','+type[i]+'_'+key+'_'+percent);
					jQuery('#percent_'+type[i]+'_'+key+'_input').val(percent);
					jQuery('#amount_'+type[i]+'_'+key+'_input').val(number_format(to_numeric(jQuery('#amount_'+type[i]+'_'+key).val()) * percent/100));
					//alert(jQuery('#amount_'+type[i]+'_'+key+'_input').val());
					percent = 100;
				}else{
					//jQuery('#not_selected').val(jQuery('#not_selected').val() +','+type[i]+'_'+key+'_'+percent);
					var not_select = jQuery('#not_selected').val();	
					var not_select = not_select.split(",");
					for(var k in not_select){
						if(not_select[k] != ''){
							var m = not_select[k];
							var per =  m.substr(m.lastIndexOf("_")+1,m.length -1);
							var str = m.substr(0,m.lastIndexOf("_"));
							var tr = str.substr(0,str.lastIndexOf("_"));
							var key = str.substr(str.lastIndexOf("_")+1,str.length -1);
							if(to_numeric(per) == 100){
								jQuery('#'+tr.toLowerCase()+'_'+key).css('background','#EFEFEF');
								jQuery('#amount_'+tr+'_'+key).css('background','#EFEFEF');
								jQuery('#amount_'+type[i]+'_'+key).css('color','#8E8F8F');
								jQuery('#description_'+type[i]+'_'+key).css('color','#8E8F8F');
								jQuery('#date_'+type[i]+'_'+key).css('color','#8E8F8F');
							}
						}
					}
				}
			});	
		}
		if(y == 1){
			jQuery('#hidden').css('display','block');
		}
	}
	UpdateTotal();
	//CountTraveller();	
}
	function UpdateTotal(){
		jQuery('#total_payment').val(0);
		var type = jQuery('#all_type').val();
		type = type.split(',');
		var tax_amount = 0;
		var charge_amount = 0;
		for(var i in type){
			if(type[i] != ''){
				jQuery('.'+type[i]).each(function(){
					if(this.checked == 1){
						var key = this.name;
						if(type[i] != 'DEPOSIT'){
							if(jQuery('#foc_all_'+key).val()!=0){
								var tt = to_numeric(jQuery('#total_payment').val());
							}else if(jQuery('#foc_'+key).val()!='' && type[i] == 'ROOM'){
								var tt = to_numeric(jQuery('#total_payment').val());		
							}else{
								var tt = to_numeric(to_numeric(jQuery('#total_payment').val()) + to_numeric(jQuery('#percent_'+type[i]+'_'+key+'_input').val()) * to_numeric(jQuery(this).val())/100);
							}
						}else if(type[i] == 'DEPOSIT'){
							var tt = to_numeric(to_numeric(jQuery('#total_payment').val()) - to_numeric(jQuery('#percent_'+type[i]+'_'+key+'_input').val()) * to_numeric(jQuery(this).val())/100);	
						}
					//to_numeric(jQuery('#amount_'+type[i]+'_'+key+'_input').val()));
						jQuery('#total_payment').val(number_format(tt));
					}
				});
			}
		}
		jQuery('.add_paid').each(function(){
			jQuery('#total_payment').val(number_format(to_numeric(this.value) + to_numeric(jQuery('#total_payment').val())));	
		});

		jQuery('#tax_amount').val(number_format(tax_amount));
		jQuery('#service_charge_amount').val(number_format(charge_amount));
		jQuery('#total_payment').val(number_format(to_numeric(jQuery('#total_payment').val()) + tax_amount + charge_amount));
/*		var payment_type = jQuery('#payment_type').val();
		payment_type = payment_type.split(',');
		for(var t in payment_type){
			if(payment_type[t] != ''){
				PaymentType(payment_type[t]);		
			}
		}*/
	}
	function ChangeAmount(obj,items,key,itm){
		var valu = obj.value;
			if(is_numeric(to_numeric(valu))){
				var value = to_numeric(valu);
				if(itm == 'percent'){
					if(value <= 100){				
						var new_value = (value * to_numeric(jQuery('#amount_'+items+'_'+key).val()))/100;
						jQuery('#amount_'+items+'_'+key+'_input').val(number_format(new_value));
						UpdateTotal();
					}else{
						alert('[[.not_allowed_greater_than_100.]]');	
					}
				}else if(itm == 'amount'){
					var new_percent = roundNumber((to_numeric(obj.value)/to_numeric(jQuery('#amount_'+items+'_'+key).val()))*100,4);
					jQuery('#percent_'+items+'_'+key+'_input').val(new_percent);
					UpdateTotal();
				}	
			}else{
				alert('[[.is_not_numeric.]]');	
			}
		
	}
	function DeleteItem(type,key,percent){	
		jQuery('#'+type+'_split_'+key).remove();
		jQuery('#item_type').val(jQuery('#item_type').val().replace(type+'_'+key+'_'+percent,""));
		jQuery('#item_type').val(jQuery('#item_type').val().replace(',,',','));
		jQuery('#'+type+'_'+key).attr('checked',false);
		jQuery('#'+type.toLowerCase()+'_'+key).css('background','#FFF');
		jQuery('#amount_'+type+'_'+key).css('background','#FFF');
		jQuery('#amount_'+type+'_'+key).css('color','#3A0066');
		jQuery('#description_'+type+'_'+key).css('color','#3A0066');
		jQuery('#date_'+type+'_'+key).css('color','#3A0066');	
		DrawSplitOrder();
	}
	function SubmitForm(act){
		var k =0;
		var traveller = '<?php if(Url::get('traveller_id')){echo Url::get('traveller_id');}else{ echo '';}?>';
		if(jQuery('#travellers_id').val() == 0 && traveller == '' && jQuery('#traveller_id').val() == ''){
			alert('[[.you_must_select_traveller_id.]]');
			return false;	

		}
		if(jQuery('#item_type').val() != ','){
			jQuery('.input-payment').each(function(){
				if(to_numeric(this.value) != 0){
					k=1;
				}
			});	
/*			if(k==0 && cmd != 'add_payment'){
				alert('[[.you_have_to_select_payment_type.]]');	
				return false;	
			}else{
*/				jQuery('#action').val(1);
				jQuery('#act').val(act);
				CreateTravellerFolioForm.submit();	
			//}
		}else{
			alert('has_no_item_selected');
			return false;	
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
	function ItemDetail(itm,key){
		if(jQuery('#add_item_'+itm+'_'+key).attr('src')=='packages/hotel/skins/default/images/icons/add_item.gif'){
			jQuery('#add_item_'+itm+'_'+key).attr('src','packages/hotel/skins/default/images/icons/close_item.gif');
			jQuery('.'+itm+'_detail_'+key).show();//slideDown(100);
				
		}else{
			jQuery('#add_item_'+itm+'_'+key).attr('src','packages/hotel/skins/default/images/icons/add_item.gif');
			jQuery('.'+itm+'_detail_'+key).hide();//slideUp(100);
		}
	}
	function setAmountPayment(obj){
	var exchange_rate = '<?php echo [[=exchange_rate=]];?>';
	var value = 0;
	id = obj.getAttribute('lang');
	name = obj.getAttribute('alt');
	jQuery('.input-payment').each(function(){
		if(this.lang != id){
			if(this.alt == 'CASH_USD'){
				value = to_numeric(value)+(to_numeric(this.value.replace(/,/g,''))*to_numeric(exchange_rate));
			}else{
				value = to_numeric(value)+to_numeric(this.value.replace(/,/g,''));
			}
		}
	});
	if(name == 'CASH_USD'){
		jQuery("#input_pmt_"+id).val(number_format((to_numeric(jQuery('#total_payment').val()) - value)/to_numeric(exchange_rate)));
	}else{
		if((to_numeric(jQuery('#total_payment').val()) - value)>10 || (to_numeric(jQuery('#total_payment').val()) - value)<-10){
			jQuery("#input_pmt_"+id).val(number_format(to_numeric(jQuery('#total_payment').val()) - value));
		}else{
			jQuery("#input_pmt_"+id).val(0);	
		}
	}	
}
function checkNumber(obj){
	if(!is_numeric(to_numeric(obj.value))){
		alert('[[.is_not_number.]]');
		obj.value = '';
	}else{
	}
}

</script>