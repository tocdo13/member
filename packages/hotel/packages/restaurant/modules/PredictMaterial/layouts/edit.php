<script>
    var product = <?php echo String::array2js([[=products=]]);?>;
</script>
<span style="display:none">
	<span id="mi_predict_material_sample">
		<div id="input_group_#xxxx#">
			<input  name="mi_predict_material[#xxxx#][id]" type="hidden" id="id_#xxxx#"/>
            <input  name="mi_predict_material[#xxxx#][type]" type="hidden" id="type_#xxxx#"/>
			<span class="multi_input">
					<input  name="mi_predict_material[#xxxx#][stt]" style="width:30px; height: 24px; background: lightgray;" readonly="readonly" type="text" id="stt_#xxxx#"/>
			</span><span class="multi_input">
					<input  name="mi_predict_material[#xxxx#][code]" style="width:70px; height: 24px;" type="text" id="code_#xxxx#" onblur=""/>
			</span><span class="multi_input">
					<input  name="mi_predict_material[#xxxx#][product_name]" style="width:200px; height: 24px;" type="text" id="product_name_#xxxx#"/>
			</span><span class="multi_input">
					<input  name="mi_predict_material[#xxxx#][quantity]" style="width:70px; height: 24px;" type="text" id="quantity_#xxxx#"  onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44)event.returnValue=false;"/>
			</span>
			<span class="multi_input"><span style="width:30px; height: 24px;">
				<a style="height: 24px;" href="#" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_predict_material','#xxxx#','');event.returnValue=false;" style="cursor:hand;"><i class="fa fa-times-circle w3-text-red" style="font-size: 20px; padding-top: 2px;"></i></a>
			</span></span><br/>
		</div>
	</span>
</span>
<div width="100%">
    <table width="100%" >
        <tr>
            <td class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px; width: 70%;"><i class="fa fa-calendar w3-text-orange" style="font-size: 26px;"></i> [[.predict_material.]]</td>
            <td align='right' style="width: 30%; padding-right: 30px;"><button class="w3-btn w3-lime" id="export" style="text-transform: uppercase;">[[.export.]]</button></td>
        </tr>
    </table>
    <br /><br />
    <form name="ActionPredictMaterialForm" method="post">
    <div width="30%" style="float: left;">
        <table>
            <tr>
                <td width="100%" valign='top'>
                    <table>
                        <tr>
                            <td>
                                <fieldset>
                        			<legend class="title">[[.product_reservation.]]</legend>
                        				<span id="mi_predict_material_all_elems">
                        					<span style="text-transform: uppercase;">
                        						<span class="multi-input-header" style="width:33px; height: 20px; padding-top: 2px;">[[.stt.]]</span>
                        						<span class="multi-input-header" style="width:71px;height: 20px; padding-top: 2px;">[[.code.]]</span>
                        						<span class="multi-input-header" style="width:201px;height: 20px; padding-top: 2px;">[[.product_name.]]</span>
                        						<span class="multi-input-header" style="width:71px;height: 20px; padding-top: 2px;">[[.quantity.]]</span>
                        						<span class="multi-input-header" style="width:30px;height: 20px; padding-top: 2px;"><img src="skins/default/images/spacer.gif"/></span>
                        						<br/>
                        					</span>
                        				</span>
                        			<input class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; margin-top: 5px;" type="button" value="[[.add.]]" onclick="mi_add_new_row('mi_predict_material');myAutocomplete(input_count);"/>
                        		</fieldset>    
                            </td>
                        </tr>
                    </table>
                    <input name="act" type='hidden' id="act" value="0" />  
                </td>
            </tr>
        </table>
    </div>
    </form>
    <div width='680px' style="margin-left: 400px">
        <table>
            <tr>
                <td width="80px" align='center' valign='top'><a href="javascript:void(0)" onClick="submitForm()"><img src="<?php echo Portal::template('core');?>/images/buttons/mui_ten.png" style="text-align:center"/><br /></a></td>
                
                <td width="600px" valign='top'>
                    <div  style="overflow:auto">
                    <fieldset>
             			<legend class="" style="text-transform: uppercase;">[[.material.]]</legend>
                        <table id="tblExport" width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="#CCCCCC" class="table-bound">
                            <tr class="w3-light-gray" style="height: 30px; text-transform: uppercase;">
            					<th width="20px" nowrap align="center">[[.stt.]]</th>
            					<th width="70px" nowrap align="center">[[.code.]]</th>
                                <th width="250px" nowrap align="center">[[.product_name.]]</th>
            					<th width="100px" nowrap align="center">[[.quantity_request.]]</th>
                                <th width="100px" nowrap align="center">[[.in_warehouse.]]</th>
                                <th width="100px" nowrap align="center">[[.shopping.]]</th>
                            </tr>
                            <!--IF:remain(!empty([[=remain_quantity_product=]]))-->
                            <?php $i=1?>
                            <!--LIST:remain_quantity_product-->
                            <tr>
            					<td width="20px" nowrap align="center"><?php echo $i;?></td>
            					<td width="70px" nowrap align="center">[[|remain_quantity_product.id|]]</td>
                                <td width="250px" nowrap align="center">[[|remain_quantity_product.product_name|]]</td>
            					<td width="100px" nowrap align="center">[[|remain_quantity_product.request_quantity|]]</td>
                                <td width="100px" nowrap align="center">[[|remain_quantity_product.remain_quantity|]]</td>
                                <td width="100px" nowrap align="center">[[|remain_quantity_product.shopping|]]</td>
                            </tr>
                            <?php $i++;?>
                            <!--/LIST:remain_quantity_product-->
                            <!--/IF:remain-->
                        </table>
                    </fieldset>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
<script>
mi_init_rows('mi_predict_material',<?php if(isset($_REQUEST['mi_predict_material'])){echo String::array2js($_REQUEST['mi_predict_material']);}else{echo '[]';}?>);
jQuery(document).ready(function(){
    jQuery("#export").click(function (){
        jQuery("#tblExport").battatech_excelexport({
            containerid: "tblExport"
           , datatype: 'table'
        });
    });
});
function myAutocomplete(index)
{	
	jQuery("#code_"+index).autocomplete({
            url: 'get_product.php?product=1',
            onItemSelect: function(item){
                getProductFromCode(index,jQuery("#code_"+index).val());
	       }
    });
}
function getProductFromCode(id,value)
{
	if($('code_'+id))
    {
		if(typeof(product[value])=='object')
        {
            $('id_'+id).value = product[value]['product_id'];
			$('product_name_'+id).value = product[value]['product_name'];
			$('type_'+id).value = product[value]['type'];
        }
        else
        {
            $('id_'+id).value = '';
			$('product_name_'+id).value = '';
			$('type_'+id).value = '';
		}
	
    } 
}
function submitForm(){
    var count=jQuery('input[type=text]').length;
    console.log(count);
    if((count/4-1)>0){
        jQuery('#act').val(1);ActionPredictMaterialForm.submit();
    }else{
       alert('chưa thêm nguyên vật liệu'); 
    }
}
</script>