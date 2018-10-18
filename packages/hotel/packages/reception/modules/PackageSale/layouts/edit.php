<style>
div.content fieldset legend.legend-title:hover{
    background: #fff;
}
</style>
<span style="display:none;">
	<span id="mi_product_group_sample">
		<div id="input_group_#xxxx#">
        	<span>
                <input  name="mi_product_group[#xxxx#][id]" type="hidden" id="id_#xxxx#">
                <span class="multi-input"><select  name="mi_product_group[#xxxx#][service_id]" style="width:185px; height: 24px;" id="service_id_#xxxx#" onblur="updateServiceInfo(this,'#xxxx#');" onchange="updateServiceInfo(this,'#xxxx#');">[[|service_options|]]</select></span>
                <span class="multi-input"><input  name="mi_product_group[#xxxx#][quantity]" type="text" id="quantity_#xxxx#" style="width:63px; height: 24px;" onkeyup="updateAllPaymentPrice('#xxxx#');" autocomplete="off"/></span>
                <span class="multi-input"><input  name="mi_product_group[#xxxx#][unit]" style="width:104px; height: 24px;" type="text" id="unit_#xxxx#" readonly="readonly" class="readonly"/></span>
                <span class="multi-input price"><input  name="mi_product_group[#xxxx#][price]" style="width:104px;text-align:right; height: 24px;" type="text" id="price_#xxxx#" onkeyup="updateAllPaymentPrice('#xxxx#');"/></span>
                <span class="multi-input price"><input  name="mi_product_group[#xxxx#][department_name]" style="width:104px;text-align:right; height: 24px;" type="text" id="department_name_#xxxx#" readonly="readonly" class="readonly"/></span>
                <span class="multi-input price"><input  name="mi_product_group[#xxxx#][payment_price]" style="width:104px;text-align:right; height: 24px;" type="text" id="payment_price_#xxxx#" readonly="readonly" class="readonly"  tabindex="-1"/></span>
                <!--<span class="multi-input price"><input name="mi_product_group[#xxxx#][in_date]" type="text" style="width:85px;text-align:right;" id="in_date_#xxxx#"  /></span>-->
                <span class="multi-input"><input  name="mi_product_group[#xxxx#][note]" style="width:154px; height: 24px;" type="text" id="note_#xxxx#"  /></span>
                <!--<span class="multi-input" style="width:60px;text-align:center;"><input  name="mi_product_group[#xxxx#][used]" type="checkbox" id="used_#xxxx#" value="1" checked="checked"></span>-->
               <span class="multi-input" style="width:40px;" id="span_delete">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="<?php echo Portal::template('core');?>/images/buttons/delete.gif" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_product_group','#xxxx#','');updateAllPaymentPrice();if(document.all)event.returnValue=false; else return false;" style="cursor:pointer;"/>
            </span><br clear="all" />
		</div>
	</span> 
</span>
<div class="extra_service_invoice-bound">
<form name="EditExtraServiceInvoiceForm" method="post">
<input  name="deleted_ids" type="hidden"  id="deleted_ids" value="<?php echo URL::get('deleted_ids');?>">
	<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr height="40">
        	<td width="70%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;">[[.add_package.]]</td>
			<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="30%"><input  name="save"  type="submit" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;" class="w3-btn w3-orange w3-text-white" <?php echo (isset([[=status=]]) and [[=status=]]=='CHECKOUT' and !User::is_admin())?'disabled="disabled" title="'.Portal::language('room_checked_out_can_not_edit').'"  value="'.Portal::language('room_checked_out_can_not_edit').'"':' value="'.Portal::language('Save').'"';?> onclick="return check_input_product();"/><?php }?>
            <?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current();?>"  class="w3-btn w3-green" style="text-transform: uppercase; text-decoration: none;">[[.back.]]</a></td><?php }?>
        </tr>
    </table>
	<div class="content">
		<?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><?php }?><br />
		<fieldset>
        <legend class="legend-title">[[.package_information.]]</legend>
			<table border="0" cellspacing="0" cellpadding="2">
				<tr valign="middle">
					<td class="label">[[.package_code.]]</td>
				    <td><input name="code" type="text" id="code" style="width:150px; height: 24px;"/></td>
		         	<td class="label">[[.package_name.]]</td>
				    <td><input name="name" type="text" id="name" style="width:150px; height: 24px;"/></td>
				</tr>
                <tr valign="top">
                  
                  
                </tr>
                
                <tr valign="middle">
					<td class="label">[[.from date.]]</td>
				    <td class="label"><input name="start_date" type="text" id="start_date" style="width:150px; height: 24px;"/></td>
                    <td>[[.to_date.]]</td> 
                    <td><input name="end_date" type="text" id="end_date" style="width:150px; height: 24px;"/></td>
				</tr>-
                
                <tr valign="top">
                  <td class="label">[[.content.]]</td>
				    <td colspan="3"><textarea name="content" id="content" rows="5" style="width: 400px;"></textarea></td>
                  
                </tr>
			</table>
	  </fieldset><br />
      
      <fieldset>
        <legend class="legend-title">[[.detail.]]</legend>
            <span id="mi_product_group_all_elems" style="text-align:left;">
                <span>
                    <span class="multi-input-header" style="width:185px; height: 24px;">[[.name.]]</span>
                    <span class="multi-input-header" style="width:63px; height: 24px;">[[.number.]]</span>
                    <span class="multi-input-header" style="width:104px; height: 24px;">[[.unit.]]</span>
                    <span class="multi-input-header price" style="width:104px; height: 24px;">[[.price.]]</span>
                    <span class="multi-input-header price" style="width:104px; height: 24px;">[[.department.]]</span>
                    <span class="multi-input-header price" style="width:104px; height: 24px;">[[.amount.]]</span>
                    <!--<span class="multi-input-header" style="width:80px;">[[.create_date.]]</span>-->
					<span class="multi-input-header" style="width:154px;height: 24px;">[[.note.]]</span>
                    
                    <!--<span class="multi-input-header" style="width:70px;">[[.used.]]</span>-->
                </span><br clear="all">
            </span>
            <input class="w3-btn w3-cyan w3-text-white" type="button" style="float: left; text-transform: uppercase; margin-top: 5px;" value="[[.add_service.]]" id="add_product" onclick="mi_add_new_row('mi_product_group');jQuery('#service_id_'+input_count).jec();jQuery('#quantity_'+input_count).ForceNumericOnly();jQuery('#price_'+input_count).ForceNumericOnly();jQuery('#price_'+input_count).FormatNumber();jQuery('#in_date_'+input_count).datepicker();"/>
			<div style="width: 100%; margin: 0px auto;">
            
			<div style="float:left;padding-left:390px;">[[.total.]]: <input  name="total_amount" type="text" id="total_amount" readonly="readonly" class="readonly" style="width:104px;font-weight:bold;text-align:right;height: 24px;"/></div>
            </div>
    </fieldset>	
    </div>  
</form>	
</div>
<script type="text/javascript">
<?php
   if(Url::get('cmd')=='edit')
   {
        ?>
        $('code').value ='<?php echo [[=code=]];?>';
        $('name').value = '<?php echo [[=name=]];?>';
        $('content').value = '<?php echo [[=content=]];?>';
        
        <?php 
   }
?>
$('start_date').value = '<?php echo [[=start_date=]];?>';
$('end_date').value = '<?php echo [[=end_date=]];?>';
jQuery('#start_date').datepicker();
jQuery('#end_date').datepicker();
var miProductGroup = <?php echo isset($_REQUEST['mi_product_group'])?String::array2js($_REQUEST['mi_product_group']):'{}';?>;
var total_amount = 0;
var total = 0;
for(var i in miProductGroup)
{
    total_amount += to_numeric(miProductGroup[i]['payment_price']);
    miProductGroup[i]['payment_price'] = number_format(miProductGroup[i]['payment_price']);
    miProductGroup[i]['price'] = number_format(miProductGroup[i]['price']);
}
if(total_amount!=0)
{
    $('total_amount').value = number_format(total_amount);   
}
mi_init_rows('mi_product_group',miProductGroup);
var services = [[|services|]];
function updateServiceInfo(obj,index){
		value = obj.value;
	if(typeof(services[value])=='undefined'){
		$('price_'+index).value = '0';
		$('unit_'+index).value = '';
		$('quantity_'+index).value = '0';
		$('payment_price_'+index).value = '0';
	}else{
		$('price_'+index).value = services[value]['price'];
		$('unit_'+index).value = services[value]['unit'];
        $('quantity_'+index).value = '0';
        $('department_name_'+index).value = services[value]['department_name'];
        $('price_'+index).value = number_format($('price_'+index).value);
	}
}

function updateAllPaymentPrice()
{
	total_amount = 0;
    total = 0;
	for(var i=101;i<=input_count;i++)
    {
		if($("price_"+i)){
		 $('payment_price_'+i).value = to_numeric($('price_'+i).value) * to_numeric($('quantity_'+i).value);
		 	
		  total_amount += to_numeric($('payment_price_'+i).value);
         
		 $('payment_price_'+i).value = number_format($('payment_price_'+i).value);
         $('price_'+i).value = number_format($('price_'+i).value);	
		}
	}
	$('total_amount').value = number_format(total_amount);
}
function check_input_product()
{
    var service_id ='';
    var date_input ='';
    
    var quantity =0;
    if(input_count==100)
    {
        alert('Bạn chưa chọn package nào!');
        return false;
    }
    var code =$('code').value;
    code =code.trim(); 
    if(code=='')
    {
        alert('Bạn chưa nhập mã package sale');
        return false;
    }
    var name = $('name').value;
    name = name.trim();
    if(name=='')
    {
        alert('Bạn chưa nhập tên package sale');
        return false;
    }
    for(var i=101;i<=input_count;i++)
    {
        service_id = $('service_id_' + i).value ;
        date_input = $('in_date_' + i).value ;
        quantity = $('quantity_' + i).value ;
        
        if(service_id.trim()=='')
        {
            alert('Bạn chưa nhập package');
            return false;
        }
        
        /*if(date_input.trim()=='')
        {
            alert('Bạn chưa chọn ngày sử dụng package');
            $('in_date_' + i).focus();
            return false;
        }*/
        if(quantity.trim()==0)
        {
            alert('Bạn chưa chọn số lượng dịch vụ');
            return false;
        }
    }
    return true;
}
</script>