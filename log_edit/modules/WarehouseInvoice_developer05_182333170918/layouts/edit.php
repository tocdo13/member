<?php //System::debug($_REQUEST); ?>
<script src="packages/core/includes/js/multi_items.js" type="text/javascript"></script>
<script src="packages/core/includes/js/aarray.js" type="text/javascript"></script>
<script type="text/javascript" src="packages/core/includes/js/jquery/jquery.alphanumeric.pack.js"></script>
<script type="text/javascript" src="packages/core/includes/js/jquery/jquery.maskedinput.js"></script>
<script type="text/javascript">
	//var product_arr = <?php //echo String::array2js([[=products=]]);?>;
	var supplier_arr = <?php echo String::array2js([[=suppliers=]]);?>;	
    //console.log(supplier_arr);
	var all_products = <?php echo String::array2js([[=all_products=]]);?>;
    //Kieu hoa don
    var invoice_type = <?php echo '\''.Url::get('type').'\'';?>;
</script>
<span style="display:none">
	<span id="mi_product_sample">
		<div id="input_group_#xxxx#" style="text-align:left;">
			<input  name="mi_product[#xxxx#][id]" type="hidden" id="id_#xxxx#"/>
			
            <input  name="mi_product[#xxxx#][remain_id]" type="hidden" id="remain_id_#xxxx#"/>
            <!--KimTan : ma bi trung khi go day du goi nho-->
            <span class="multi-input">
                <input  name="mi_product[#xxxx#][product_id]" style="width:80px; height: 24px; text-transform: uppercase;" type="text" id="product_id_#xxxx#" tabindex="#xxxx#_6" onblur="myAutocomplete('#xxxx#',invoice_type);" autocomplete="OFF"/>
            </span>
			
            <span class="multi-input">
                <input  name="mi_product[#xxxx#][name]" style="width:150px; height: 24px;" type="text" id="name_#xxxx#" tabindex="#xxxx#_7" onblur="myAutocompleteName('#xxxx#',invoice_type);checkexit();" autocomplete="OFF"/>
            </span>
			<!-- end KimTan : ma bi trung khi go day du goi nho-->
            <span class="multi-input">
                <input  name="mi_product[#xxxx#][unit]" style="width:55px; height: 24px;background-color:#CCC;" type="text" id="unit_#xxxx#" readonly="readonly" class="readonly" />
                <input  name="mi_product[#xxxx#][unit_id]" type="hidden" id="unit_id_#xxxx#"/>
                <select  name="mi_product[#xxxx#][units_id]" id="units_id_#xxxx#" style="display:none;width:60px;" onchange="$('unit_id_#xxxx#').value = this.value;"  tabindex="#xxxx#_8">[[|units_id|]]</select>
            </span>
            
            <span class="multi-input">
                <input  name="mi_product[#xxxx#][category]" type="text"  readonly="readonly" id="category_#xxxx#" style="width:140px;  height: 24px;background:#CCCCCC;"  />
                <input  name="mi_product[#xxxx#][category_id]" type="hidden" id="category_id_#xxxx#"  />
                <select  name="mi_product[#xxxx#][categorys_id]" id="categorys_id_#xxxx#" style="display:none;width:144px;" onchange="$('category_id_#xxxx#').value = this.value;"  tabindex="#xxxx#_9">[[|categorys_id|]]</select>
            </span>
            
            <span class="multi-input price">
                <input  name="mi_product[#xxxx#][type]" style="width:80px; height: 24px;background-color:#CCC;" type="text" id="type_#xxxx#" />
                <select  name="mi_product[#xxxx#][types_id]" id="types_id_#xxxx#" style="display:none;width:83px;" onchange="$('type_#xxxx#').value = this.value;"  tabindex="#xxxx#_10">[[|types_id|]]</select>
            </span>
            
            <span class="multi-input price">
                <input  name="mi_product[#xxxx#][remain_num]" style="width:100px; height: 24px;background-color:#CCC;" type="text" id="remain_num_#xxxx#" readonly="readonly" />
            </span>
            <span class="multi-input price">
                <input  name="mi_product[#xxxx#][remain_money]" style="width:80px; height: 24px;background-color:#CCC;" type="hidden" id="remain_money_#xxxx#" readonly="readonly" />
            </span>
            <!--IF:cond(Url::get('edit_average_price') and Url::get('edit_average_price') == 1)-->
            <span class="multi-input">
                <input  name="mi_product[#xxxx#][num]" style="width:80px; height: 24px;" type="hidden" id="num_#xxxx#" value="0" onblur="updatePaymentPrice('#xxxx#'); check_remain_num(#xxxx#);" onchange="updatePaymentPrice('#xxxx#');" class="format_number input_number"  tabindex="#xxxx#_11"/>
            </span>	
            <!--ELSE-->
            <span class="multi-input">
                <input  name="mi_product[#xxxx#][num]" style="width:80px; height: 24px;" type="text" id="num_#xxxx#" onblur="updatePaymentPrice('#xxxx#'); check_remain_num(#xxxx#);" onchange="updatePaymentPrice('#xxxx#');" class="format_number input_number"  tabindex="#xxxx#_11"/>
            </span>	
            <!--/IF:cond-->	
            <!--IF:cond(Url::get('type') == 'IMPORT')-->
                <span class="multi-input">
                    <input  name="mi_product[#xxxx#][old_price]" style="width:110px; height: 24px;text-align:right;background-color:#CCC;" type="hidden" id="old_price_#xxxx#" readonly="readonly" />
                </span>
                <span class="multi-input">
                    <input  name="mi_product[#xxxx#][lastest_imported_price]" style="width:110px; height: 24px;text-align:right;background-color:#CCC;" type="text" id="lastest_imported_price_#xxxx#" readonly="readonly" />
                </span>
            <!--/IF:cond-->
            <!--IF:cond(Url::get('type') == 'EXPORT')-->
                <span class="multi-input">
                    <input  name="mi_product[#xxxx#][old_price]" style="width:110px; height: 24px;text-align:right;background-color:#CCC;" type="text" id="old_price_#xxxx#" readonly="readonly" />
                </span>
            <!--/IF:cond-->
            <!--IF:cond(!Url::get('move_product'))-->
            <!--IF:condx(Url::get('edit_average_price') and Url::get('edit_average_price') == 1)-->
            <span class="multi-input">
                <input  name="mi_product[#xxxx#][price]" style="width:80px; height: 24px;text-align:right;" type="hidden" id="price_#xxxx#" value="0" onblur="updatePaymentPrice('#xxxx#');" onchange="updatePaymentPrice('#xxxx#');" class="format_number input_number"/>
            </span>
            <!--ELSE-->
            <span class="multi-input">
                <input  name="mi_product[#xxxx#][price]" style="width:80px; height: 24px;text-align:right;" type="text" id="price_#xxxx#" onblur="updatePaymentPrice('#xxxx#');" onchange="updatePaymentPrice('#xxxx#');" class="format_number input_number" tabindex="#xxxx#_12" />
            </span>
            <!--/IF:condx-->
            <!--/IF:cond-->
            <!--IF:condx(Url::get('edit_average_price') and Url::get('edit_average_price') == 1)-->
            <span class="multi-input">
                <input  name="mi_product[#xxxx#][payment_price]" style="width:80px; height: 24px;text-align:right;" type="hidden" id="payment_price_#xxxx#" value="0" oninput="updatePrice('#xxxx#');" onchange="updatePrice('#xxxx#');" />
            </span>
            <!--ELSE-->
            <span class="multi-input">
                <input  name="mi_product[#xxxx#][payment_price]" style="width:80px; height: 24px;text-align:right;" type="text" <?php echo (Url::get('type')=='EXPORT')?'readonly="readonly"':'';?> id="payment_price_#xxxx#" oninput="updatePrice('#xxxx#');" onchange="updatePrice('#xxxx#');" tabindex="#xxxx#_13" />
            </span>
            <!--/IF:condx-->
		    <!--IF:cond(Url::get('edit_average_price') and Url::get('edit_average_price') == 1)-->
            <span class="multi-input">
                <input  name="mi_product[#xxxx#][money_add]" style="width:80px; height: 24px;text-align:right;" type="text" id="money_add_#xxxx#" onblur="//updatePaymentPrice('#xxxx#');" onkeyup="check_add_money('#xxxx#');//updatePaymentPrice('#xxxx#');" tabindex="#xxxx#_14"/>
            </span>
            <!--/IF:cond-->
        	<span class="multi-input">
				<span style="width:40px; height: 24px;">
                <?php if(User::can_delete(false,ANY_CATEGORY)){?>
				<img src="<?php echo Portal::template('core');?>/images/buttons/delete.gif" onclick="mi_delete_row($('input_group_#xxxx#'),'mi_product','#xxxx#','group_');updateTotalPayment();if(document.all)event.returnValue=false; else return false;" style="cursor:pointer;"/>
                <?php }?>
                </span>
            </span>
            <!--IF:cond(Url::get('type') != 'EXPORT')-->
            <span align="center" style="width:80px; height: 24px;">
                <input type="checkbox" name="mi_product[#xxxx#][isset_money]" id="isset_money_#xxxx#" onclick ="check_money(#xxxx#);" />
            </span>
            <!--/IF:cond-->
            <br/>
            <br clear="all" />
        </div>
	</span>
</span>

<a id="sosanhkq" href="http://google.com"></a>

<table width="1030" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td>
		<form name="EditWarehouseInvoiceForm" method="post">
		<input  name="group_deleted_ids" id="group_deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>"/>
		<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
            <tr>
            	<td width="60%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> [[|title|]]</td>
            	<td  width="40%" align="right" nowrap="nowrap" style="padding-right: 30px;">
            		<?php if(User::is_admin()){ ?><input style="text-transform: uppercase; margin-right: 5px;" name="save" id="save" type="button" onclick="fun_submit('save');" value="[[.save.]]" class="w3-btn w3-blue"/><?php }?>
                    <?php if(User::is_admin()){ ?><input name="save" id="save_and_input" style="text-transform: uppercase; margin-right: 5px;" type="button" onclick="fun_submit('save_and_import');" value="[[.save_and_import.]]"  class="w3-btn w3-lime"/><?php }?>
                    <input name="save_type" id="save_type" style="display: none;" />
                    <?php if(User::can_delete(false,ANY_CATEGORY)){?><input  style="text-transform: uppercase;"  class="w3-btn w3-green" value="[[.back.]]" id="back_page" onclick="window.location='<?php echo Url::build_current(array('type'));?>'"  /><?php }?>
            		
            	</td>
            </tr>
        </table>
		
        
        <div class="product-bill-bound">
            <div class="content" style="background:#FFFFFF;width:100%;padding:0px;">
                <?php if(Form::$current->is_error()){?>
                <div>
                    <br/>
                    <?php echo Form::$current->error_messages();?>
                </div>
                <?php }?>
                
                <fieldset>
                    <legend class="" style="text-transform: uppercase; font-size: 14px;">[[.Contents.]]</legend>
					<table width="100%" border="0" cellspacing="0" cellpadding="2">
						<tr>
                            <td style="padding-left:10px;">
                                <table cellspacing="0" cellpadding="5" width="100%">
                                <tr>
                                    <td nowrap="nowrap" style="width: 100px;">[[.date.]]<em style="color:#F00;">(*)</em>:</td>
                                    <td style="width: 120px;">
                                        <input name="create_date" id="create_date" type="text" value="<?php if(isset($_REQUEST['create_date'])){ $create_date = $_REQUEST['create_date'];}else{ $create_date="";} echo $create_date; ?>" class="date-input" style="width:100%; height: 24px;" tabindex="<?php $i=0; echo '0_100'; $i++; ?>"  />
                                    </td>
                                    <?php $this->map['index']++; ?>
                                    <!--IF:import(Url::get('type')=='IMPORT')-->
                                    <td nowrap="nowrap" style="width: 90px; ">[[.warehouse.]]:</td>
                                    <td style="">
                                        <!--<select name="warehouse_id" id="warehouse_id" style="margin:0px;padding:0px;" onclick="ignore_change();"></select>-->
                                        <input name="warehouse_name" type="text" id="warehouse_name" readonly="readonly" style="width:120px; height: 24px; border: none;" />
                                        <input name="warehouse_id" type="hidden" id="warehouse_id"/>
                                    </td>
                                    <!--/IF:import-->
                                    <!--IF:export(Url::get('type')=='EXPORT')-->
                                    <td nowrap="nowrap" style="width: 90px; display: none;">[[.warehouse.]]:</td>
                                    <td style="display: none;">
                                        <!--<select name="warehouse_id" id="warehouse_id" style="margin:0px;padding:0px;" onclick="ignore_change();"></select>-->
                                        <input name="warehouse_name" type="text" id="warehouse_name" readonly="readonly" style="background: lightgray; width:120px; height: 24px;" />
                                        <input name="warehouse_id" type="hidden" id="warehouse_id"/>
                                    </td>
                                    <!--/IF:export-->
                                    <td nowrap="nowrap" style="width: 90px;">
                                        <span class="label">[[.bill_number.]]: </span></td>
                                    <td>
                                        <input name="bill_number" type="text" id="bill_number" readonly="readonly" style="width:120px; height: 24px; border: none;"  />
                                    </td>
                                    <td style="width: 90px;">
                                        <!--IF:import(Url::get('type')=='IMPORT')-->
                                        <span class="label">[[.invoice_number.]]: </span>
                                    </td>
                                    <td style="width: 120px;">
                                        <input name="invoice_number" id="invoice_number" type="text" value="<?php if(isset($_REQUEST['invoice_number'])){ $invoice_number = $_REQUEST['invoice_number'];}else{ $invoice_number = "";} echo $invoice_number; ?>" style="width:100; height: 24px; " tabindex="<?php echo $i.'_100'; $i++; ?>" />
                                        <!--/IF:import-->
                                    </td>
                                    <!--IF:import(Url::get('type')=='IMPORT')-->
                                    <td nowrap="nowrap" style="">[[.export_to_warehouse.]]:
                                        <input name="direct_export" type="checkbox" id="direct_export" value="1" onclick="toogleToWarehouse(this);" style="margin:0px;padding:0px;" /> 
                                    </td>
                                    <!--/IF:import-->
                                    <!--IF:export(Url::get('type')=='EXPORT')-->
                                    <td nowrap="nowrap" style="display: none;">[[.export_to_warehouse.]]:
                                        <input name="direct_export" type="checkbox" id="direct_export" value="1" onclick="toogleToWarehouse(this);" style="margin:0px;padding:0px;" /> 
                                    </td>
                                    <!--/IF:export-->
    							</tr>
                                <!--Nhap kho-->
                                <!--IF:import(Url::get('type')=='IMPORT')-->
                                
                                <tr id="to_warehouse" style="display:none;">
                                    <td nowrap="nowrap" style="width: 100px;">[[.to_warehouse.]]:
                                    </td>
                                    <td style="width: 100px;">
                                        <select name="to_warehouse_id" id="to_warehouse_id" style="width: 100%;height: 24px;"></select>
                                    </td>
                                    <td colspan="7">&nbsp;</td>
                                </tr>
                                <tr>
    	                            <td nowrap="nowrap" style="width: 100px;">[[.supplier.]]:
                                    </td>
                                    <td colspan="8">
                                        <input name="supplier_id" type="text" id="supplier_id" style="display: none;" /> 
                                        <input name="supplier_code" type="text" id="supplier_code" style="width:110px;height: 24px;text-transform:uppercase;"  onblur="getSupplierFromCode(this.value);" onfocus="supplierAutocomplete();"/>                                        
                                        : 
                                        <input name="supplier_name" type="text" id="supplier_name" readonly="readonly" style=" width: 86%;height: 24px;background-color:#CCC;" onfocus="check(this);" />
                                    </td>
                                    <script>
                                        function check(obj)
                                        {
                                            if(!jQuery(obj).attr('readonly'))
                                                obj.value = '';
                                        }
                                    </script>
    							</tr> 
                                <tr>
                                    <td style="width: 100px;">[[.tax_code.]]:
                                    </td>
                                    <td style="width: 120px;">
                                         <input name="tax_code" type="text" id="tax_code" readonly="readonly" style="width: 100%;height: 24px;background-color:#CCC;" />
                                    </td> 
                                    <td style="width: 90px;">[[.address.]]:</td>
                                    <td colspan="6">
                                         <input name="address" type="text" id="address" readonly="readonly" style="width: 100%;height: 24px;background-color:#CCC;"/>
                                    </td>
                                                  
                                </tr>
                                <!--ELSE-->
                                <!--xuat kho-->
                                    <!--IF:move_product(Url::get('move_product'))--><!--chuyen kho-->
                                    <tr>
                                        <td nowrap="nowrap" style="width: 130px;">[[.from_warehouse.]]:</td>
                                        <td style="width: 120px;">
                                            <!--<select name="warehouse_id" id="warehouse_id" style="margin:0px;padding:0px;" onclick="ignore_change();"></select>-->
                                       	    <input name="warehouse_name" type="text" id="warehouse_name" readonly="readonly" style="height: 24px; width: 100%; border: none;" />
                                            <input name="warehouse_id" type="hidden" id="warehouse_id"/>
                                        </td>
                                        <td nowrap="nowrap" style="width: 100px;">[[.to_warehouse.]]:</td>
                                        <td style="width: 120px;">
                                            <select name="to_warehouse_id" id="to_warehouse_id" style="width: 100%; height: 24px;"></select>
                                        </td>
                                    </tr>
                                    <!--ELSE-->
                                    <!--xuat kho ra ngoai-->
                                    <tr>
                                        <td nowrap="nowrap" style="width: 130px;">[[.from_warehouse.]]:
                                        </td>
                                        <td style="width: 120px;">
                                            <!--<select name="warehouse_id" id="warehouse_id" onclick="ignore_change();"></select>-->
                                            <input name="warehouse_name" type="text" id="warehouse_name" readonly="readonly" style="width: 100%; border: none;" />
                                            <input name="warehouse_id" type="hidden" id="warehouse_id"/>
                                       	
                                           </td>
                                           <td nowrap="nowrap" style="width: 130px;"><span class="label">[[.receiver_name_1.]] :</span></td>
                                           <td>
                                            <select name="wh_receiver_name" id="wh_receiver_name" style=" width: 120px; height: 24px;"></select></td>
                                        
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td nowrap="nowrap" style="width: 130px;">[[.returned_supplier.]]:</td>
                                        <td style="width: 120px;">
                                            <input name="get_back_supplier" type="checkbox" id="get_back_supplier" value="1" onclick="toogleSupplierSelect(this);" style="height: 24px;" width="100%"  />
                                       	    <script>
                                                jQuery(document).ready(function(){
                                                    <?php if(Url::get('get_back_supplier')=='1'){?> 
                                                    jQuery('#get_back_supplier').attr('checked','checked');
                                                    jQuery("#returned_supplier").show();
                                                    <?php }?>
                                                })

                                            </script>
                                        </td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <!--<tr id="returned_supplier" style="display:none;">
        	                            <td nowrap="nowrap">[[.supplier.]]:</td>
                                        <td nowrap="nowrap" colspan="3">
                                            <select name="supplier_id" id="supplier_id" style="margin:0px;padding:0px;"></select>
                                        </td>
        							</tr> -->
                                    <tr id="returned_supplier" style="display:none;">
        	                            <td nowrap="nowrap">[[.supplier.]]:</td>
                                        <td nowrap="nowrap" colspan="3">
                                            <input name="ware_supplier_name" type="text" id="ware_supplier_name" onfocus="Autocomplete()"  />
                                            <input name="ware_supplier_id" type="text" id="ware_supplier_id" style="display:none;"  />
                                        </td>
        							</tr> 
                                    <!--/IF:move_product-->
                                <!--/IF:import-->
                                
                                <tr>
                                    <td valign="top" style="width: 100px;">[[.description.]]:</td>
                                    <?php if(Url::get('type')=='IMPORT'){ ?>
                                    <td colspan="8"><textarea name="note" tabindex="5_100" id="note" rows="3" style="padding:0px;margin:0px;width:100%;"></textarea></td>                              
                                    <?php }else{ ?>
                                    <td colspan="3"><textarea name="note" tabindex="3_100" id="note" rows="3" style="padding:0px;margin:0px;width:100%;"></textarea></td> 
                                    <?php } ?>
                                </tr>
                                <tr>
                                    <!--IF:cond(Url::get('type')=='IMPORT')-->
                                    <td nowrap="nowrap"><span class="label" style="display: none;">[[.receiver_name_1.]] :</span></td>
                                    <td><select name="wh_receiver_name" id="wh_receiver_name" style="margin:0px;padding:0px; display: none;"></select>
                                    <!--<input name="wh_receiver_name" type="text" id="wh_receiver_name" tabindex="+" style="margin:0px;padding:0px;"/>-->
                                    <!--/IF:cond-->
                                    </td> 
                                    <td nowrap="nowrap" style="width: 90px;"><span class="label">[[.receiver.]]:</span></td>
                                    <td><input name="receiver_name" id="receiver_name" type="text" value="<?php if(isset($_REQUEST['receiver_name'])){ $receiver_name = $_REQUEST['receiver_name'];}else{ $receiver_name = "";} echo $receiver_name; ?>" tabindex="<?php echo $i.'_100'; $i++; ?>" style="width: 120px;height: 24px;"/></td>    
                                    <td nowrap="nowrap" style="width: 90px;">[[.deliver.]]:</td>
                                    <td><input name="deliver_name" id="deliver_name" type="text" value="<?php if(isset($_REQUEST['deliver_name'])){ $deliver_name = $_REQUEST['deliver_name'];}else{ $deliver_name = "";} echo $deliver_name; ?>" tabindex="<?php echo $i.'_100'; $i++; ?>" style="width: 120px;height: 24px;"/></td>   
                                </tr>
                                </table>
                            </td>
                        </tr>
					</table>
                </fieldset>
                        
				<fieldset>
                    <legend class="" style="text-transform: uppercase; font-size: 14px; padding-top: 10px; padding-bottom: 10px;">[[.products.]]</legend>
                        <span id="mi_product_all_elems" style="text-align:center; text-transform: uppercase;">
                            <span>
								<span class="multi-input-header" style="width:80px; height: 24px; padding-top: 4px;">[[.code.]]</span>
								<span class="multi-input-header" style="width:150px;height: 24px;padding-top: 4px;">[[.name.]]</span>
                                <span class="multi-input-header" style="width:55px;height: 24px;padding-top: 4px;">[[.unit_at.]]</span>
                                <span class="multi-input-header" style="width:140px;height: 24px;padding-top: 4px;">[[.category.]]</span>
                                <span class="multi-input-header" style="width:80px;height: 24px;padding-top: 4px;">[[.type.]]</span>
                                <span class="multi-input-header" style="width:100px;height: 24px;padding-top: 4px;">[[.remain_quantity.]]</span> 
                                <!--IF:condx(!Url::get('edit_average_price') and Url::get('edit_average_price') != 1)-->
								<span class="multi-input-header" style="width:80px;height: 24px;padding-top: 4px;">[[.quantity.]]</span>
                                <!--/IF:condx-->
                                <span class="multi-input-header" style="width:110px;height: 24px;padding-top: 4px;">
                                <!--IF:cond(Url::get('type')=='IMPORT')-->
                                [[.last_import_price.]]
                                <!--ELSE-->
                                [[.average_price.]]
								<!--/IF:cond-->
                                </span>
                                <!--IF:cond(!Url::get('move_product'))-->
                                <!--IF:condx(!Url::get('edit_average_price') or Url::get('edit_average_price') != 1)-->  
                                <span class="multi-input-header" style="width:80px;height: 24px;padding-top: 4px;">[[.price.]]</span>
								<!--/IF:condx-->
                                <!--/IF:cond-->
                                <!--IF:condx(!Url::get('edit_average_price') or Url::get('edit_average_price') != 1)-->
                                <span class="multi-input-header" style="width:80px;height: 24px;padding-top: 4px;">[[.amount.]]</span>
                                <!--ELSE-->
                                <span class="multi-input-header" style="width:80px;height: 24px;padding-top: 4px;">[[.add_money.]]</span>
                                <!--/IF:condx-->
                                <span class="multi-input-header" style="width:20px;height: 24px;padding-top: 4px;"></span>
                                <!--IF:cond(Url::get('type') != 'EXPORT')-->
                                <span class="multi-input-header" style="width:10px;height: 24px;padding-top: 4px;">&nbsp;</span>
                                <!--/IF:cond-->
							</span>
                       	<br clear="all"/>
						</span>
						<input class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase;" id="add_new" type="button" value="[[.add_product.]]" 
                            onclick="
                                        mi_add_new_row('mi_product');
                                        myAutocomplete(input_count,invoice_type);
                                        myAutocompleteName(input_count,invoice_type);
                                        jQuery('#price_'+input_count).FormatNumber();
                                        //jQuery('#price_'+input_count).ForceNumericOnly();
                                        jQuery('#price_'+input_count).FormatNumber();
                                        jQuery('#num_'+input_count).FormatNumber();
                                        jQuery('#payment_price_'+input_count).FormatNumber();
                                        jQuery('#price_'+input_count).attr('readonly','readonly');
                                        jQuery('#price_'+input_count).addClass('readonly');
                                        allow_hot_key();
                                    "
                        />
				</fieldset>	
                <div style="height: 200px; width: 100%;">
                    <fieldset id="total_payment_bound" style="float:right; margin-right: 20px; padding:20px;">
                        <div style="text-align:right;">
    						<strong>[[.total.]]:</strong> 
                            <input name="total_amount" type="text" id="total_amount" readonly="true" style="width:100px;text-align:right;border:0px;border-bottom:1px solid #CCCCCC;font-weight:bold;color:#000000;"/>
    					</div>
                        <br />
                        <div style="text-align:right;">
    						<strong>[[.tax.]]:</strong> 
                            <input name="tax" type="text" id="tax" class="input_number" oninput="this.value = number_format(to_numeric(this.value)); updateTotalPayment();" style="width:100px;text-align:right;border:0px;border-bottom:1px solid #CCCCCC;font-weight:bold;color:#000000;"/>
    					</div>
                        <br />
                        <div style="text-align:right;">
                            <strong>[[.total_amount.]]:</strong> 
                            <input name="total" type="text" id="total" readonly="true" style="width:100px;text-align:right;border:0px;border-bottom:1px solid #CCCCCC;font-weight:bold;color:#000000;"/>
                        </div>
                        
    				</fieldset>
                </div>
            </div>
        </div>
		</form>	
        </td>
    </tr>
</table>
<script type="text/javascript">

jQuery("#create_date").mask("99/99/9999");
//jQuery('#create_date').datepicker();
    var cmd=<?php echo '\''.Url::get('cmd').'\''; ?>;
    var CURRENT_YEAR = <?php echo date('Y')?>;
    var CURRENT_MONTH = <?php echo intval(date('m')) - 1;?>;
    var CURRENT_DAY = <?php echo date('d')?>;
    jQuery("#create_date").datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
//show hide cac tuy chon
function toogleSupplierSelect(obj)
{
	if(obj.checked == true){
		jQuery("#returned_supplier").show();
        //jQuery("#total_payment_bound").show();
	}else{
		jQuery("#returned_supplier").hide();
        //jQuery("#total_payment_bound").hide();
	}
}
function toogleToWarehouse(obj)
{
    //alert(1);
	if(obj.checked == true)
		jQuery("#to_warehouse").show();
    else
		jQuery("#to_warehouse").hide();
}
function allow_hot_key()
{
    jQuery(".input_number").keydown(function(event) 
    {
        //alert(event.keyCode);
		// Allow: backspace, delete, tab, escape, and enter
		if ( 
            event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || 
			 // Allow: Ctrl+A
			(event.keyCode == 65 && event.ctrlKey === true) || 
			 // Allow: home, end, left, right
			(event.keyCode >= 35 && event.keyCode <= 39) ||
            // Allow: . .(del)
            event.keyCode == 190 || event.keyCode == 110
            ) 
        {
				 // let it happen, don't do anything
				 return;
		}
		else 
        {
			// Ensure that it is a number and stop the keypress
			if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) 
            {
				event.preventDefault(); 
			}   
		}
	});   
}
function supplierAutocomplete()
{
	jQuery("#supplier_code").autocomplete({
         url: 'get_customer.php?supplier=1',
    onItemSelect: function(item) {
			getSupplierFromCode(item.value);
            jQuery(".acResults").css('display','none');
		}
    })
}

function getSupplierFromCode(value)
{
	if(typeof(supplier_arr[value])=='object')
    {
		$('supplier_name').readOnly = true;
        $('supplier_id').value = supplier_arr[value]['supplier_id'];        
		$('supplier_name').value = supplier_arr[value]['name'];
        $('address').value = supplier_arr[value]['address'];
        $('tax_code').value = supplier_arr[value]['tax_code'];                
		$('supplier_name').style.backgroundColor = '#CCC';
        $('supplier_name').className = '';
	}
    else
    {
        if(value)
        {
            $('supplier_name').className = 'notice';
    		$('supplier_name').readOnly = false;
    		$('supplier_name').style.backgroundColor = '#FFF';
            $('supplier_name').value = '[[.New_supplier.]]';
        }
        else
        {
    		$('supplier_name').readOnly = true;
    		$('supplier_name').style.backgroundColor = '#CCC';
            $('supplier_name').className = '';   
            $('supplier_name').value = '';
            $('supplier_id').value = '';            
        }
	}
}

function myAutocomplete(id,invoice_type)
{ var flag_check=1;
	jQuery("#product_id_"+id).autocomplete(
    {
        url: 'get_product.php?wh_invoice=1&type=<?php echo Url::get('type');?>&warehouse_id=<?php echo Url::get('warehouse_id');?>',
        onItemSelect: function(item) 
        {
            getProductFromCode_new(id,jQuery("#product_id_"+id).val(),invoice_type,flag_check);
            check_remain_num(id);
	    }
    });
}
function myAutocompleteName(id,invoice_type)
{  var flag_check=2;
	jQuery("#name_"+id).autocomplete(
    {
        url: 'get_product.php?wh_invoice=2&type=<?php echo Url::get('type');?>&warehouse_id=<?php echo Url::get('warehouse_id');?>',
        onItemSelect: function(item) 
        {
           getProductFromCode_new(id,item.data[0],invoice_type,flag_check,all_products);
           check_remain_num(id);
	    } 
       
    });
    
}

function getProductFromCode(id,product_id,invoice_type,flag_check)
{
    //neu co trong product
	if(product_id && typeof(product_arr[product_id])=='object')
    {
        //console.log(flag_check);
		jQuery('#units_id_'+id).css('display','none');	
		jQuery('#unit_'+id).css('display','block');
        jQuery('#categorys_id_'+id).css('display','none');	
		jQuery('#category_'+id).css('display','block');		
		//jQuery('#name_'+id).attr('readonly',true);
		//jQuery('#name_'+id).addClass('readonly');
		jQuery('#types_id_'+id).css('display','none');
		jQuery('#type_'+id).css('display','block');
		jQuery('#type_'+id).attr('readonly',true);
		jQuery('#type_'+id).css('background-color','#CCC');
		//jQuery('#name_'+id).css('background-color','#CCC');
        if(flag_check==1){
            //alert(product_arr[product_id]['name']);
            $('name_'+id).value = product_arr[product_id]['name'];
        }else{
            
            $('product_id_'+id).value = product_arr[product_id]['id'];
        }
	   //	$('name_'+id).value = product_arr[product_id]['name'];
       // $('name_'+id).value = product_arr[product_id]['name'];
		$('unit_'+id).value = product_arr[product_id]['unit'];
        $('unit_id_'+id).value = product_arr[product_id]['unit_id'];
        $('category_id_'+id).value = product_arr[product_id]['category_id'];
        $('category_'+id).value = product_arr[product_id]['category'];
		$('type_'+id).value = product_arr[product_id]['type'];
		if($('remain_num_'+id))
		{
			$('remain_num_'+id).value = product_arr[product_id]['remain_num'];
		}
        /**
        * Them so tien ton vao day 
        **/
        if($('remain_money_'+id))
		{
			$('remain_money_'+id).value = product_arr[product_id]['remain_money'];
		}
		if($('old_price_'+id))
		{
		    var old_price = parseFloat(product_arr[product_id]['old_price']).toFixed(4);
			$('old_price_'+id).value = number_format_four(old_price);
            //$('old_price_'+id).value = 99999999;
		}
        if($('lastest_imported_price_'+id))
		{
		    var lastest_price = parseFloat(product_arr[product_id]['lastest_imported_price']).toFixed(4);
			$('lastest_imported_price_'+id).value = PriceFormatCurrency(number_format_four(lastest_price));
            //$('lastest_imported_price_'+id).value =99999999;
		}
        //Neu chua co gia thi dien vao gia nhap lan cuoi
        if($('price_'+id))
        {
            if(!$('price_'+id).value)
    		{
                if(invoice_type=='EXPORT')
    			 $('price_'+id).value = $('old_price_'+id).value;
    		}    
        }
        
		$('name_'+id).className = '';
        check_duplicate_product(id,jQuery('#product_id_'+id).val());
	}
    else//neu khong co
    {
        if(invoice_type=='IMPORT')//neu hoa don nhap => kiem tra co trong all product khong
        {
    		var flag = 0;
    		var product_id = jQuery('#product_id_'+id).val();
			for(j in all_products)
            {
				if(all_products[j]['id'] == product_id.toUpperCase())
                {
					flag = 1;	
					alert('[[.product_code_exists.]]');
					return false;
				}
			}
			if(flag == 0)
            {
				UpdateProduct(id);	
			}
        }
        else//neu hoa don xuat thi bao loi~ luon
        {
            //alert('[[.product_is_not_in_warehouse.]]');
            $('name_'+id).className = 'notice';
            $('name_'+id).value = '[[.product_is_not_in_warehouse.]]';
            $('product_id_'+id).value = '';
        }

	}
}
function getProductFromCode_new(id,product_id,invoice_type,flag_check,all_products_new)
{
    //console.log(all_products_new);
    jQuery.ajax({
    			url:"get_remain_products.php?",
    			type:"POST",
    			data:{data:'xxx',product_id:product_id,warehouse_id:<?php echo Url::get('warehouse_id');?>,type:'<?php echo Url::get('type') ?>'},
    			success:function(html)
                {
                    var product_arr = JSON.parse(html);
                    //console.log(product_arr);
                    if(product_id && typeof(product_arr[product_id])=='object')
                    {
                        jQuery('#units_id_'+id).css('display','none');	
                    	jQuery('#unit_'+id).css('display','block');
                        jQuery('#categorys_id_'+id).css('display','none');	
                    	jQuery('#category_'+id).css('display','block');	
                    	jQuery('#types_id_'+id).css('display','none');
                    	jQuery('#type_'+id).css('display','block');
                    	jQuery('#type_'+id).attr('readonly',true);
                    	jQuery('#type_'+id).css('background-color','#CCC');
                    	
                        if(flag_check==1){
                            
                            $('name_'+id).value = product_arr[product_id]['name'];
                        }else{
                            
                            $('product_id_'+id).value = product_arr[product_id]['id'];
                        }
                       
                    	$('unit_'+id).value = product_arr[product_id]['unit'];
                        $('unit_id_'+id).value = product_arr[product_id]['unit_id'];
                        $('category_id_'+id).value = product_arr[product_id]['category_id'];
                        $('category_'+id).value = product_arr[product_id]['category'];
                    	$('type_'+id).value = product_arr[product_id]['type'];
                    	if($('remain_num_'+id))
                    	{
                    		$('remain_num_'+id).value = product_arr[product_id]['remain_num'];
                    	}
                        /**
                        * Them so tien ton vao day 
                        **/
                        if(to_numeric(product_arr[product_id]['remain_num'])<=0)
                        {
                            product_arr[product_id]['old_price'] = 0;
                        }
                        if($('remain_money_'+id))
                    	{
                    		$('remain_money_'+id).value = product_arr[product_id]['remain_money'];
                    	}
                    	if($('old_price_'+id))
                    	{
                    	    var old_price = parseFloat(product_arr[product_id]['old_price']).toFixed(4);
                    		$('old_price_'+id).value = number_format_four(old_price);
                    	}
                        if($('lastest_imported_price_'+id))
                    	{
                    	    var lastest_price = parseFloat(product_arr[product_id]['lastest_imported_price']).toFixed(4);
                    		$('lastest_imported_price_'+id).value = PriceFormatCurrency(number_format_four(lastest_price));
                            
                    	}
                        //Neu chua co gia thi dien vao gia nhap lan cuoi
                        if($('price_'+id))
                        {
                            if(!$('price_'+id).value)
                    		{
                                if(invoice_type=='EXPORT')
                    			 $('price_'+id).value = $('old_price_'+id).value;
                    		}    
                        }
                        
                    	$('name_'+id).className = '';
                        //check_duplicate_product(id,jQuery('#product_id_'+id).val());
                    }
                    else//neu khong co
                    {
                        if(invoice_type=='IMPORT')//neu hoa don nhap => kiem tra co trong all product khong
                        {
                    		//console.log(all_products_new);
                            var flag = 0;
                    		var product_id_1 = jQuery('#product_id_'+id).val();
                            for(j in all_products_new)
                            {
                    			if(all_products_new[j]['id'] == product_id_1.toUpperCase())
                                {
                    				flag = 1;	
                    				alert('[[.product_code_exists.]]');
                    				return false;
                    			}
                    		}
                    		if(flag == 0)
                            {
                    			UpdateProduct(id);	
                    		}
                        }
                        else//neu hoa don xuat thi bao loi~ luon
                        {
                            //alert('[[.product_is_not_in_warehouse.]]');
                            $('name_'+id).className = 'notice';
                            $('name_'+id).value = '[[.product_is_not_in_warehouse.]]';
                            $('product_id_'+id).value = '';
                        }
                    
                    }
                    
                }
    });
}
function PriceFormatCurrency(input_number)
{
    if(input_number)
    {
        input_number += '';
        x = input_number.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }
    
}
// viet lai ham number_format de lay 4 so sau dau phay

function number_format_four(nStr)
{
	nStr = to_numeric(nStr);
	nStr += '';
	x = nStr.split('.');
	x1 = x[0];
	//x2 = x.length > 1 ? '.' + x[1] : '';
	
	//duc them
	var decimals = 4; 
	if(x.length > 1){
		var x2 = new String(x[1]);		
		x2 = String(Math.round(parseFloat(x[1])/Math.pow(10,(x2.length - decimals))));
		while(x2.length < decimals) { x2 = '0'+x2; }
		x2 = '.'+x2;
	} else{
		var x2 = '';
		//x2 += '.';
		//while(x2.length <= decimals) { x2 += '0'; }
	}
	//end edit
	
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(x1)) {
		x1 = x1.replace(rgx, '$1' + ',' + '$2');
	}
	return x1 + x2;
}
function UpdateProduct(id)
{
	$('name_'+id).value = '';
	$('unit_'+id).value = '';
    //khi submit form ma bi loi thi se lay cac gia tri cu~
    $('unit_id_'+id).value = jQuery('#units_id_'+id).val()?jQuery('#units_id_'+id).val():'';
    $('category_'+id).value = '';
    $('category_id_'+id).value = jQuery('#category_id_'+id).val()?jQuery('#category_id_'+id).val():'';;
    $('type_'+id).value = jQuery('#type_'+id).val()?jQuery('#type_'+id).val():'';;
	if($('remain_num_'+id))
	{
		$('remain_num_'+id).value = '';
	}
	if($('old_price_'+id))
	{
		$('old_price_'+id).value = '';
	}
	if($('price_'+id))
	{
		$('price_'+id).value = '';
	}
    if($('product_id_'+id).value != '')
    {
    	jQuery('#name_'+id).attr('readonly',false);
    	jQuery('#name_'+id).removeClass('readonly');
    	jQuery('#name_'+id).css('background-color','#FFF');
    	jQuery('#name_'+id).focus();
    	jQuery('#units_id_'+id).css('display','block');	
    	jQuery('#unit_'+id).css('display','none');
        jQuery('#categorys_id_'+id).css('display','block');	
    	jQuery('#category_'+id).css('display','none');		
    	jQuery('#types_id_'+id).css('display','block');
    	jQuery('#type_'+id).css('display','none');
    }
    else
    {
    	jQuery('#name_'+id).attr('readonly',true);
    	jQuery('#name_'+id).addClass('readonly');
        jQuery('#name_'+id).css('background-color','#CCC');
    	jQuery('#name_'+id).focus();
    	jQuery('#units_id_'+id).css('display','none');	
    	jQuery('#unit_'+id).css('display','block');
        jQuery('#categorys_id_'+id).css('display','none');	
    	jQuery('#category_'+id).css('display','block');		
    	jQuery('#types_id_'+id).css('display','none');
    	jQuery('#type_'+id).css('display','block');  
    }

}

//1 phieu khong the co 2 sp giong nhau
function check_duplicate_product(id,value)
{
    /*
    var duplicate = 0;
    for(var i = 101; i<= input_count; i++)
    {
        if(jQuery('#product_id_'+i).val())
        {
            if(value==jQuery('#product_id_'+i).val())
                duplicate++;
            if(duplicate>=2)
            {
                alert('[[.product_exist.]]')
                mi_delete_row($('input_group_'+id),'mi_product',id,'group_');
                
                //$('product_id_'+id).value =null;
                //$('name_'+id).value =null;
                //$('unit_'+id).value = null;
                //$('category_'+id).value =null;
                //$('type_'+id).value =null;
                
                return false;
            }    
        }
    }
    */
}
	
mi_init_rows('mi_product',<?php echo isset($_REQUEST['mi_product'])?String::array2js($_REQUEST['mi_product']):'{}';?>);

<!--IF:type_cond(Url::get('type')=='EXPORT')-->
    //jQuery("#total_payment_bound").hide();
    <!--IF:get_back_supplier(Url::get('get_back_supplier') && Url::get('get_back_supplier')!='')-->
        $('get_back_supplier').checked = true;
        <!--ELSE-->
    <!--/IF:get_back_supplier-->
    <!--ELSE-->
    jQuery("#supplier_select_bound").show();
<!--/IF:type_cond-->


function updatePaymentPrice(prefix)
{
	$('num_'+prefix).value = to_numeric($('num_'+prefix).value);
    //console.log($(to_numeric($('num_'+prefix).value));
	//gia se lay la gia nhap, neu khong co gia nhap (khi chuyen kho) thi lay gia nhap lan cuoi
    var price = $('price_'+prefix)?$('price_'+prefix).value:$('old_price_'+prefix).value;
    //price = number_format_four(price.replace(',',''));
    price = to_numeric(price);
    //$('price_'+prefix).value = number_format($('price_'+prefix).value.replace(',',''));
	var discount =  0;
	$('payment_price_'+prefix).value =  to_numeric(price)*to_numeric($('num_'+prefix).value);
	$('payment_price_'+prefix).value = number_format_four(parseFloat($('payment_price_'+prefix).value).toFixed(4));
	//Chuyen 0 thanh ""
    if($('payment_price_'+prefix).value == 'NaN' || $('payment_price_'+prefix).value == '0' )
    {
		$('payment_price_'+prefix).value = "";
	}
	if($('num_'+prefix).value == 'NaN' || $('num_'+prefix).value == '0' )
    {
		$('num_'+prefix).value = "";
	}
    
    //alert($('num_'+prefix).value);
    //alert($('payment_price_'+prefix).value);
	updateTotalPayment();
}
function updatePrice(prefix)
{
    //start: KID them if($('num_'+prefix).value !='') de chan khi so luong rong van update gia		
    if($('num_'+prefix).value !='')
    {
        var num = to_numeric($('num_'+prefix).value);
        var payment_price = to_numeric($('payment_price_'+prefix).value);
        var price =  (to_numeric(payment_price)/to_numeric(num)).toFixed(4);
    	//Chuyen 0 thanh ""  va || payment_price ==0
        if(price  == 'NaN' || to_numeric(num)  == "0" || payment_price ==0)
        {
    		$('price_'+prefix).value = "";
    	}
        else
        {
            $('price_'+prefix).value = number_format_four(price);
        }
    	updateTotalPayment();
    }
    //end: KID them if($('num_'+prefix).value !='') de chan khi so luong rong van update gia			
}
	
function updateTotalPayment()
{
	var total_payment = 0;
	for(var i=101;i<=input_count;i++)
    {
		if(typeof(jQuery("#payment_price_"+i).val())!='undefined')
        {
            var ty = to_numeric(jQuery("#payment_price_"+i).val());
            var price = to_numeric(jQuery("#price_"+i).val());
            var num = to_numeric(jQuery("#num_"+i).val());
            if (price == 0 && num != 0)
            {
                jQuery("#price_"+i).val(ty/num);
            }
            if (ty == 0)
            {
                ty = price * num;
            }
			total_payment += ty;
		}
	}
    var tax = to_numeric(jQuery("input#tax").val());
     jQuery("#total_amount").val((total_payment!='NaN' && total_payment)?number_format_four(total_payment):'0');
        total_payment+=tax;
    jQuery("#total").val((total_payment!='NaN' && total_payment)?number_format_four(total_payment):'0');
}

/* dua vao common
function formatCurrency(id,num) 
{
	num = num.toString().replace(/\$|\,/g,'');
    
    var dot_position =num.indexOf('.');
    var last = '';
    if(dot_position!= -1)
    {
        last = num.substring(dot_position,num.length);
        num = num.substring(0,dot_position);
    }
    var length = num.length;
    var result = '';
    if(length>3)
    {
        var start = 0;
        if(length%3 != 0)
            start = length%3;
        if(start!=0)
            result+=num.substring(0,start)+','; 
        for (var i = 1; i <= Math.floor(length/3); i++)
        {
        	result+= num.substring(start,start+3)+',';
            start = start+3;
        }
        //cat bo dau phay sau cung
        result = result.substring(0,result.length-1)+last;
    }
    else
        result += num+last;
    jQuery('#'+id+'').val(result);
}
*/

function check_remain_num(id)
{
    <!--IF:type_cond(ALLOW_OVER_EXPORT==0&&Url::get('type')=='EXPORT')-->
        //alert(1);
        var remain = $('remain_num_'+id).value?$('remain_num_'+id).value:'0';
        //dat bang 1 de luk nao cung nhay vao dk duoi
        var num = $('num_'+id).value?$('num_'+id).value:'1';
        if(to_numeric(remain) < to_numeric(num))
        {
            if(to_numeric(remain)==0)
            {
                alert('[[.product_has_not_in_warehouse.]]');
                mi_delete_row($('input_group_'+id),'mi_product',id,'group_');
            }
            else
            {
                alert('[[.remain_is_0.]]');
                $('num_'+id).value = '';
            } 
        }
    <!--/IF:type_cond-->    
    <!--IF:type_cond(ALLOW_OVER_EXPORT == 1 && Url::get('type') == 'EXPORT')-->
        var remain = to_numeric(jQuery('#remain_num_' + id).val());
        var quantity = to_numeric(jQuery('#num_' + id).val());
        if (quantity > remain)
        {
            var total = to_numeric(jQuery('#total_amount').val());
            var increse_total = to_numeric(jQuery('#payment_price_' + id).val());
            jQuery('#total_amount').val(total - increse_total);
            jQuery('#price_' + id).val(0);
            jQuery('#payment_price_' + id).val(0);
            jQuery('#price_' + id).attr('readonly', 'readonly');
        }
        else
        {
            if (remain > 0 && quantity > 0 && quantity < remain && jQuery('#get_back_supplier').is(':checked'))
            {
                jQuery('#price_' + id).removeAttr('readonly');
                jQuery('#payment_price_' + id).removeAttr('readonly');
            }
            else
            {
                jQuery('#price_' + id).attr('readonly', 'readonly');
                $('price_'+id).value = $('old_price_'+id).value;
                updatePaymentPrice(id);
                jQuery('#payment_price_' + id).attr('readonly', 'readonly');
            }
        }
    <!--/IF:type_cond-->
}

function ignore_change()
{
    alert('You can not change this');
    return false;
}
<!--IF:cond(Url::get('type') == 'IMPORT')-->
function check_money(id)
{
      if(!jQuery('#isset_money_'+id).is(':checked'))
      {
        jQuery("#price_"+id).attr('readonly','readonly');
        jQuery('#price_'+id).addClass('readonly');
        jQuery("#payment_price_"+id).attr('readonly',false);
        jQuery('#payment_price_'+id).removeClass('readonly');
        jQuery('#price_'+id).attr('onchange','');
        jQuery('#price_'+id).attr('onblur','');
      }
      else
      {
        jQuery("#payment_price_"+id).attr('readonly','readonly');
        jQuery('#payment_price_'+id).addClass('readonly');
        jQuery("#price_"+id).attr('readonly',false);
        jQuery('#price_'+id).removeClass('readonly');
        jQuery('#price_'+id).attr('onchange','updatePaymentPrice('+id+');');
        jQuery('#price_'+id).attr('onblur','updatePaymentPrice('+id+');');
      }
}	
<!--/IF:cond-->
    //autocomplete('product_id_'+input_count);
</script>

<script type="text/javascript">
	jQuery(document).ready(function()
    {
        var test = 100;
        <!--IF:cond(Url::get('type') == 'EXPORT')-->
        jQuery('#create_date').focus();
        <!--/IF-->
        jQuery(document).keypress(function(e)
        {
            if(e.keyCode== '13')
            {                              
                var current_idex = jQuery(":focus").attr("tabindex");
                if(current_idex >= 6){
                    if(current_idex == 6){
                        test = test + 1;
                    }
                    current_idex = current_idex +"_"+ test;
                }else{
                    current_idex = current_idex + "_100";
                }
                var stt = current_idex.substring(0,current_idex.indexOf('_'));
                var row = current_idex.substring((current_idex.indexOf('_')+1));
                remove_css(stt,row);
                stt++;
                auto_focus(stt,row);
                jQuery(":focus").css("background-color","#FFC");
                return false;
            }
        });   
	});
    jQuery(function()
    {
        checkCtrl=false;
        jQuery(window).keydown(function(e)
        {
            if(e.keyCode=='17')
            {
                checkCtrl=true;
            }
        }).keyup(function(ev)
        {
            if(ev.keyCode=='17')
            {
                checkCtrl=false;
            }
        }).keydown(function(event)
        {
            if(checkCtrl)
            {
                if(event.keyCode=='76')
                {
                    updateTotalPayment();
                    jQuery('#save').click();
                    checkCtrl=false;     
                }
                if(event.keyCode=='75')
                {    
                    updateTotalPayment();
                    jQuery('#save_and_input').click();
                    checkCtrl=false;        
                }
                if(event.keyCode=='77')
                {                    
                    jQuery('#back_page').click();
                    checkCtrl=false;
                }
            }
        })  

    });
    function remove_css(stt,row)
    {
        var obj = jQuery("[tabindex='"+(stt+'_'+row)+"']");
        //alert(obj.attr("tabindex"));
        if(obj.attr("tabindex"))
        {
            //alert(obj.attr("readonly"));
            if(obj.attr("readonly")==false || typeof(obj.attr("readonly"))=="undefined")
            {
                if(!obj.hasClass("readonly"))
                {
                    jQuery("[tabindex='"+(stt+'_'+row)+"']").css("background-color","white");
                    return false;  
                }
            }
            if(obj.css("display")!="none")
            {
                if(!obj.hasClass("readonly"))
                {
                    jQuery("[tabindex='"+(stt+'_'+row)+"']").css("background-color","white");
                    return false;  
                }
            }
        }
        return false;   
    }
    function auto_focus(stt,row)
    {
        var obj = jQuery("[tabindex='"+(stt+'_'+row)+"']");
        //alert(obj.attr("tabindex"));
        if(obj.attr("tabindex"))
        {
            //alert(obj.attr("readonly"));
            if(obj.attr("readonly")==false || typeof(obj.attr("readonly"))=="undefined")
            {
                //alert(obj.css("display"));
                if(obj.css("display")!="none")
                {
                    obj.focus();
                }
                else
                {
                   stt++;
                   auto_focus(stt,row);
                   return false; 
                }
            }            
            else
            {
                stt++;
                auto_focus(stt,row);
                return false;
            }   
        } 
        else
        {
            jQuery("#add_new").click();
        }       
    }
    function check_add_money(id)
    {
       var add_money = jQuery("#money_add_"+id).val();
        if(add_money.length > 0 )
        {
          var first_char =add_money.charAt(0);
          if ( first_char == '-')
          {
            add_money = add_money.substr(1,add_money.length - 1);
          }
          if(isNaN(add_money))
            {
                jQuery("#money_add_"+id).val('');
            }
        }
    }
    function check_duplicate(){
          var condition = true;  
          var j = 0;   
          jQuery("input[id^=product_id_]").each(function(){
                if(j!=0 && condition){
                    var code = jQuery(this).val();
                    jQuery("input[id^=product_id_]").not(this).each(function(){
                        var code_temp = jQuery(this).val();
                        if(code==code_temp){
                            alert("Mã "+code+" đã bị trùng! Xin vui lòng kiểm tra lại!");
                            condition=false;
                        }
                    });
                }
                j++;
          });
        return condition;  
    }
    var click = 0;
    function fun_submit($type)
    {
        var creat_date = jQuery('#create_date').val();
        var check_date = creat_date.split("/");
        if(check_date[1] < CURRENT_MONTH || check_date[0] < CURRENT_DAY || check_date[2] < CURRENT_YEAR){
            alert('Không được nhập ngày nhỏ hơn ngày hiện tại !!');
            return;
        }
        var condition = true;  
          var j = 0;   
          jQuery("input[id^=product_id_]").each(function(){
                if(j!=0 && condition){
                    var code = jQuery(this).val();
                    jQuery("input[id^=product_id_]").not(this).each(function(){
                        var code_temp = jQuery(this).val();
                        if(code==code_temp){
                            alert("Mã "+code+" đã bị trùng! Xin vui lòng kiểm tra lại!");
                            condition=false;
                        }
                    });
                }
                j++;
          });
        if(condition==true)
        {
            click++;
            if(click==1)
            {
                jQuery('#save_type').val($type);
                EditWarehouseInvoiceForm.submit();
            }
        }
        
    }
    function Autocomplete()
    {
        jQuery('#ware_supplier_name').autocomplete({
            url:'get_customer1.php?get_back_supplier=1',
            onItemSelect:function(item){
                console.log(item);
                jQuery('#ware_supplier_id').val(item.data);
            }
            
        })
    }
</script>
