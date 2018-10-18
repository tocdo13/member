<?php  System::set_page_title(HOTEL_NAME.' - '.Portal::language('title')); ?>
<span style="display:none">
	<span id="mi_group_sample">
		<div id="input_group_#xxxx#">
            <input name="mi_group[#xxxx#][id]" type="hidden" readonly="" id="id_#xxxx#"  tabindex="-1" style="width:30px; height: 24px; background:#CCC"/>
			<span class="multi-input"><input  name="mi_group[#xxxx#][product_id]" type="text" id="product_id_#xxxx#"  tabindex="-1" style="width:100px; height: 24px;" onfocus="ProductAutocomplete([#xxxx#]);" autocomplete="off"/></span>
            <span class="multi-input"><input  name="mi_group[#xxxx#][product_name]" type="text" id="product_name_#xxxx#" onfocus="ProductAutocomplete2([#xxxx#]);" style="width:200px; height: 24px;" /></span>
            <span class="multi-input"><input  name="mi_group[#xxxx#][product_unit]" type="text" id="product_unit_#xxxx#" style="width:100px; height: 24px; background-color: #CCC;" readonly="readonly" /></span>
            <span class="multi-input"><input  name="mi_group[#xxxx#][price]" type="text" id="price_#xxxx#" oninput="jQuery('#price_#xxxx#').ForceNumericOnly().FormatNumber(); CountPrice(this);" style="width:80px; height: 24px;text-align: right;" /></span>
            <span class="multi-input"><input  name="mi_group[#xxxx#][tax]" type="text" id="tax_#xxxx#" oninput="jQuery('#tax_#xxxx#').ForceNumericOnly(); CountPrice(this);"  style="width:80px; height: 24px;text-align: right;" /></span>
            <span class="multi-input"><input  name="mi_group[#xxxx#][price_after_tax]" type="text" id="price_after_tax_#xxxx#" oninput="jQuery('#price_after_tax_#xxxx#').ForceNumericOnly().FormatNumber(); CountChangePrice(this);" style="width:80px; height: 24px; text-align: right;" /></span>
            <span class="multi-input"><input  name="mi_group[#xxxx#][start_date]" type="text" id="start_date_#xxxx#" class="start_date_class" style="width:100px; height: 24px;" /></span>
            <span class="multi-input"><input  name="mi_group[#xxxx#][end_date]" type="text" id="end_date_#xxxx#" class="end_date_class" style="width:100px; height: 24px;" /></span>
            <span class="multi-input">
                <select  name="mi_group[#xxxx#][supplier]" style="width:150px; height: 24px;"  id="supplier_#xxxx#">
					[[|option_suppliers|]]
				</select>
            </span>
            <!--IF:delete(User::can_delete(false,ANY_CATEGORY))-->
			<span class="multi-input" style="width:30px; text-align:center;"><a href="#" tabindex="-1" onClick="if(Confirm('#xxxx#')){ mi_delete_row($('input_group_#xxxx#'),'mi_group','#xxxx#','');}" style="cursor:pointer;"><i class="fa fa-times-circle w3-text-red" style="font-size: 20px; padding-top: 2px;"></i></a></span>   
			<!--/IF:delete-->
		</div>
        <br clear="all" />
	</span>
</span>

<form name="EditSupplierPriceForm" id="EditSupplierPriceForm" method="post" >
    <table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
    	<tr height="40">
    		<td width="60%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-plus-square w3-text-orange" style="font-size: 26px;"></i> [[|title|]]</td>
    		<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="40%" style="text-align: right; padding-right: 30px;"><input type="submit" name="btnSave" id="btnSave" value="[[.Save_and_close.]]" onclick="return CheckSubmit();" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; margin-right: 5px;"/><?php }?>
    		<a href="#" class="w3-btn w3-green" style="text-transform: uppercase; text-decoration: none;" onclick="history.go(-1)">[[.back.]]</a></td>
        </tr>
        
    </table>
    
    
    <fieldset>
        <legend class="title"></legend>
		<table border="0" cellspacing="0" cellpadding="5">
			<tr>
				<td><strong>[[.supplier.]]</strong>: 
                    <select  name="supplier_all" id="supplier_all" style="width:150px; height: 24px;" >
        				[[|option_suppliers|]]
                    </select>
                </td>
                
                <td>[[.start_date.]]:<input name="start_date" type="text" id="start_date" onchange="change_start_date();" style="width: 80px; height: 24px;" />
                [[.end_date.]]: <input name="end_date" type="text" id="end_date" onchange="change_end_date();" style="width: 80px; height: 24px;" /> 
                    
                </td>
			</tr>
		</table>
    </fieldset>
    
    <fieldset>
        <legend class="title">[[.goods.]]</legend>
		<table cellspacing="0">
        	<tr bgcolor="#EEEEEE" valign="top"><td></td></tr>
        	<tr>
                <td style="padding-bottom:30px">
            		<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
            		<table border="0">
                		<?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
                		<tr bgcolor="#EEEEEE" valign="top">
                			<td style="">
                    			<div style="background-color:#EFEFEF;">
                    				<span id="mi_group_all_elems">
                    					<span style="white-space:nowrap; width:auto; text-transform: uppercase; text-align: center;">
                    						<span class="multi-input-header" style="width:100px; height: 24px; padding-top: 3px;">[[.product_code.]] <span style="color: red;">(*)</span> </span>
                                            <span class="multi-input-header" style="width:200px; height: 24px; padding-top: 3px;">[[.name_product.]]</span>
                                            <span class="multi-input-header" style="width:100px; height: 24px; padding-top: 3px;">[[.unit.]]</span>
                                            <span class="multi-input-header" style="width:80px; height: 24px; padding-top: 3px;">[[.price.]]</span>
                                            <span class="multi-input-header" style="width:80px; height: 24px; padding-top: 3px;">[[.tax.]]</span>
                                            <span class="multi-input-header" style="width:80px; height: 24px; padding-top: 3px;">[[.price_after_tax.]]</span>
                                            <span class="multi-input-header" style="width:100px; height: 24px; padding-top: 3px;">[[.start_date.]]</span>
                                            <span class="multi-input-header" style="width:100px; height: 24px; padding-top: 3px;">[[.end_date.]]</span>
                                            <span class="multi-input-header" style="width:150px; height: 24px; padding-top: 3px;">[[.supplier.]] <span style="color: red;">(*)</span></span>
                                            <!--IF:delete(User::can_delete(false,ANY_CATEGORY))-->
                    						<span class="multi-input-header" style="width:30px; height: 24px; padding-top: 3px;text-align:center;">[[.Delete.]]?</span>
                    						<!--/IF:delete-->
                                            <br clear="all"/>
                    					</span>
                    				</span>
                    			</div>
                    			<div><a href="javascript:void(0);" onclick="mi_add_new_row('mi_group');AddInput();" class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-top: 5px;">[[.Add.]]</a></div>
                            </td>
                       </tr>
                   </table>
                </td>
            </tr>
         </table>	
         <p><span style="color: red;">(*)</span>: Là trường bắt buộc phải nhập!</p>
    </fieldset>
    
    
    
 </form>
<script>
    var product = <?php echo String::array2js([[=products=]]);?>;
    mi_init_rows('mi_group',<?php echo isset($_REQUEST['mi_group'])?String::array2js($_REQUEST['mi_group']):'{}';?>);
   jQuery(document).ready(function()
    {
        jQuery("#start_date").datepicker();
        jQuery("#end_date").datepicker();
        
        for(var i=101;i<=input_count;i++)
        {
  		    jQuery("#start_date_"+i).datepicker();
            jQuery("#end_date_"+i).datepicker(); 
        }
       
        jQuery("#supplier_all").change(function(){
            var selected = document.getElementById('supplier_all').selectedIndex;
             var inputs = document.getElementsByTagName('select');
             
             for (var i=0;i<inputs.length;i++)
             {
                if(inputs[i].id.search('supplier') >= 0 && inputs[i].id != 'supplier_#xxxx#')
                {
                    inputs[i].selectedIndex = selected;
                }
             }
        });  
       
        
        /*jQuery("#type_all").change(function(){
            var selected = document.getElementById('type_all').selectedIndex;
             var inputs = document.getElementsByTagName('select');
             //console.log(inputs);
             for (var i=0;i<inputs.length;i++)
             {
                if(inputs[i].id.search('type') >= 0 && inputs[i].id != 'type_#xxxx#')
                {
                    inputs[i].selectedIndex = selected;
                }
             }
             
             //Luu Nguyen Giap add 
            // getValue();
            document.getElementById('RevenExpenAddForm').submit();
            
            //window.location=<?php //echo "Url::build_current(array('cmd'=>'add','type'=>Url::get('type_all')))"; ?>;
            
            
        });*/
    });
    function getValue()
    {
        var type_id = jQuery("#type_all").val().replace('#',"");
        //console.log(type_id);
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                //document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
                var text_reponse = xmlhttp.responseText;
                var objs = jQuery.parseJSON(text_reponse);
                var txt=document.getElementById("item_all").innerHTML;
                var new_content = '';
                //console.log(text_reponse);                        
                for(var obj in objs)
                {
                    new_content +='<option value="'+obj+'" >'+objs[obj]+'</option>';
                }
                document.getElementById("item_all").innerHTML = new_content;
            }
        }
        xmlhttp.open("GET","packages/hotel/packages/reven_expen/modules/RevenExpen/forms/db.php?data=get_value&type="+type_id,true);
        xmlhttp.send(); 
    }
    function AddInput()
    {
    	jQuery("#start_date_"+input_count).datepicker();
        jQuery("#end_date_"+input_count).datepicker();
    }
    
    function ProductAutocomplete(id)
    {
        jQuery("#product_id_"+id).autocomplete({
             url:'get_product_purcharsing.php?product_purchasing=1',
             onItemSelect: function(item) 
             {
                //console.log(item);
                getProductSetMenuFromCode(id,jQuery("#product_id_"+id).val());             
                //$('version_id').value = versions[jQuery("#version").val()]['version_id'];
             }
        }); 
    }
    function getProductSetMenuFromCode(id,value){
        if($('product_id_'+id))
        {
            //console.log(value);
            //console.log(product[value]);
    		if(typeof(product[value])=='object')
            {
    			$('product_name_'+id).value = product[value]['product_name'];
                $('product_unit_'+id).value = product[value]['unit'];
            }
            else
            {
    			$('product_name_'+id).value = '';
                $('product_unit_'+id).value = product[value]['unit'];
    		}
    	    //change_total();
        } 
    }
    function ProductAutocomplete2(id)
    {
        jQuery("#product_name_"+id).autocomplete({
             url:'get_product_purcharsing.php?product_purchasing=1&name_product=1',
             onItemSelect: function(item) 
             {
                //console.log(item);
                document.getElementById("product_id_"+id).value= item.data;
                //$('version_id').value = versions[jQuery("#version").val()]['version_id'];
             }
        }); 
    }
    
    function getProductFromName(id,value,type='')
        {
            if($('product_id_'+id))
            {
                    for(var key in product){
                            if(product[key]['product_name']==value){
                                $('product_id_'+id).value = product[key]['id'];
                    			$('product_name_'+id).value = product[key]['product_name'];
                                $('product_unit_'+id).value = product[key]['unit'];
                            }
                    }
            } 
        }
    
    function Confirm(index){
    	//var mi_group_name = $('name_'+ index).value;
    	//return confirm('[[.Are_you_sure_delete_mi_group_name.]] '+ mi_group_name+'?');
        return confirm('[[.Are_you_sure_delete_mi_group_name.]] ?');
    }
    function change_start_date()
    {
        var va = document.getElementById('start_date');
        jQuery(".start_date_class").val(va.value);
    }
    function change_end_date()
    {
        var va_time_out = document.getElementById('end_date');
        jQuery(".end_date_class").val(va_time_out.value);
    }
    function CheckSubmit()
    {
        $check = true;
        $check_conflix = true;
        $cond_confix = '';
        for(var i=101;i<=input_count;i++)
        {
  		    if(jQuery("#product_id_"+i).val()!=undefined)
            {
                if(jQuery("#product_id_"+i).val()=='' || jQuery("#supplier_"+i).val()==0 || jQuery("#supplier_"+i).val()=='')
                {
                    $check = false;
                }
                else
                {
                    for(var j=i+1;j<=input_count+1;j++)
                    {
                        if(jQuery("#product_id_"+j).val()!=undefined && jQuery("#product_id_"+i).val()==jQuery("#product_id_"+j).val() && jQuery("#supplier_"+i).val()==jQuery("#supplier_"+j).val() && i!=j)
                        {
                            if(jQuery("#start_date_"+i).val()=='' 
                            || jQuery("#end_date_"+i).val()=='' 
                            || jQuery("#start_date_"+j).val()=='' 
                            || jQuery("#end_date_"+j).val()=='' 
                            || ( count_date(jQuery("#start_date_"+j).val(),jQuery("#end_date_"+i).val())>=0 && count_date(jQuery("#start_date_"+i).val(),jQuery("#end_date_"+j).val())>=0 ) 
                            )
                            {
                                $check_conflix = false;
                                if($cond_confix=='')
                                    $cond_confix = jQuery("#product_id_"+j).val();
                                else
                                    $cond_confix += ','+jQuery("#product_id_"+j).val();
                            }
                        }
                    }
                }
                
            }
            
        }
        if(!$check)
        {
            alert('Bạn chưa nhập đủ Thông Tin bắt buộc!');
        }
        else if(!$check_conflix)
        {
            $check = false;
            alert('Sản phẩm không được trùng nhau trong cùng một nhà cung cấp! \n Kiểm tra lại các mã: \n '+$cond_confix);
        }
        
        return $check;
    }
    function count_date(start_day, end_day)
    {
    	var std =start_day.split("/");
    	var std_day=std[0];
    	var std_month=std[1];
    	var std_year=std[2];
    //----------------------------
    	var ed =end_day.split("/");
    	var ed_day=ed[0];
    	var ed_month=ed[1];
    	var ed_year=ed[2];
    //----------------------------
    	var startDAY=std_month+"/"+std_day+"/"+std_year;
    	var endDAY=ed_month+"/"+ed_day+"/"+ed_year;
    	var std_second=Date.parse(startDAY);
    	var ed_second=Date.parse(endDAY);
    	return (ed_second-std_second)/86400000;
    }
    
    /*function Before_tax(id)
    {
        if(jQuery("#tax_"+id).val()==''){
           jQuery("#price_before_tax_"+id).val(jQuery("#price_"+id).val()); 
        }else
        {
           var bbb = to_numeric(jQuery('#price_'+id).val());
            jQuery("#price_before_tax_"+id).val(bbb * jQuery('#tax_'+id).val()); 
        }
        
        
    }
    function Before_price(id)
    {
        if(jQuery('#price_'+id).val()!='' && jQuery('#tax_'+id).val() !='' )
        {
            var bbb = to_numeric(jQuery('#price_'+id).val());
            jQuery("#price_before_tax_"+id).val(bbb * jQuery('#tax_'+id).val()); 
        }
        else 
        {
            jQuery("#price_before_tax_"+id).val(jQuery("#price_"+id).val());
        }
    }*/
function CountPrice(obj)
{
    var id_arr = (obj.id).split('_');
    var xxxx = id_arr[1];
    var price = jQuery('#price_'+xxxx).val();
    var tax = jQuery('#tax_'+xxxx).val();
    if(price == '')
    {
        price = 0;
        jQuery('#price_'+xxxx).val(0);        
    }
    if(tax == '')
    {
        tax = 0;
        jQuery('#tax_'+xxxx).val(0)
    }
    price_after_tax = to_numeric(price)*(1+tax/100);
    jQuery('#price_after_tax_'+xxxx).val(number_format(Math.round(price_after_tax)));
}
function CountChangePrice(obj)
{
    var id_arr = (obj.id).split('_');
    var xxxx = id_arr[3];
    var price_after_tax = jQuery('#price_after_tax_'+xxxx).val();
    var tax = jQuery('#tax_'+xxxx).val();
    price_before_tax = to_numeric(price_after_tax)/(1+tax/100);
    jQuery('#price_'+xxxx).val(price_before_tax);    
}
</script>