<script type="text/javascript">

var product_department = <?php echo String::array2js([[=product_department=]]);?>;

</script>

<span style="display:none">
	<span id="mi_group_sample">
		<div id="input_group_#xxxx#">
            <input name="mi_group[#xxxx#][id]" type="hidden" readonly="" id="id_#xxxx#"  tabindex="-1" style="width:30px; background:#CCC"/>
			<span class="multi-input"><input  name="mi_group[#xxxx#][code]" type="text" id="code_#xxxx#"  tabindex="-1" style="width:100px;" onblur="ProductAutocomplete(#xxxx#);" autocomplete="off" /></span>
            <span class="multi-input">
                <span class="multi-input"><input  name="mi_group[#xxxx#][name]" type="text" id="name_#xxxx#"  tabindex="-1" style="width:250px;" onblur="ProductAutocomplete2(#xxxx#);" /></span>
            </span>
            
            <span class="multi-input">
                <span class="multi-input"><input  name="mi_group[#xxxx#][unit]" type="text" id="unit_#xxxx#" class="readonly"  tabindex="-1" style="width:100px;" /></span>
            </span>
            
            
			<span class="multi-input">
                <select  name="mi_group[#xxxx#][department]" style="width:100px;"  id="department_#xxxx#">
					<option value=""></option>[[|department_options|]]<option value="ALL">[[.all.]]</option>
				</select>
            </span>
			
            <!--IF:delete(User::can_delete(false,ANY_CATEGORY))-->
			<span class="multi-input" style="width:20px; text-align:center;"><img tabindex="-1" src="packages/core/skins/default/images/buttons/delete.gif" onClick="if(Confirm('#xxxx#')){ mi_delete_row($('input_group_#xxxx#'),'mi_group','#xxxx#','');event.returnValue=false; }" style="cursor:pointer;"/></span>   
			<!--/IF:delete-->
		</div>
        <br clear="all" />
	</span>
</span>

<form name="EditDepartmentProductForm" id="EditDepartmentProductForm" method="post" >
    <table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
    	<tr height="40">
    		<td width="70%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;">[[|title|]]</td>
    		<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="30%" style="text-align: right;"><input type="submit" name="btnSave" id="btnSave" value="[[.Save.]]" onclick="return Check_submit();" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"/><?php }?>
    		<a class="w3-green w3-btn" onclick="history.go(-1)" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.back.]]</a></td>
        </tr>
        
    </table>
    
    
    <fieldset>
        <legend class="title"></legend>
		<table border="0" cellspacing="0" cellpadding="5">
			<tr>
				<td><strong>[[.department.]]</strong>: 
                    <select  name="department_id" id="department_id" style="width:100px; height: 26px;">
                        <option value=""></option></option>[[|department_options|]]<option value="ALL">[[.all.]]
                    </select>&nbsp;&nbsp;
                    <script type="text/javascript">
			               jQuery('#department_id').val('<?php echo URL::get('department_id');?>');
					</script>
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
                    					<span style="white-space:nowrap; width:auto;">
                    						<span class="multi-input-header" style="float:left;width:100px;">Mã SP</span>
                                            <span class="multi-input-header" style="float:left;width:250px;">Tên Sản phẩm</span>
                                            <span class="multi-input-header" style="float:left;width:100px;">DVT</span>
                                            <span class="multi-input-header" style="float:left;width:100px;">Bộ phận</span>
                                            
                                            <!--IF:delete(User::can_delete(false,ANY_CATEGORY))-->
                    						<span class="multi-input-header" style="float:left;width:30px;text-align:center;">[[.Delete.]]?</span>
                    						<!--/IF:delete-->
                                            <br clear="all">
                    					</span>
                    				</span>
                    			</div>
                    			<div><a href="javascript:void(0);" onclick="mi_add_new_row('mi_group');AddInput(input_count);ProductAutocomplete(input_count);ProductAutocomplete2(input_count);jQuery('#add_charge_'+input_count).ForceNumericOnly();" class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-top: 5px;">[[.Add.]]</a></div>
                            </td>
                       </tr>
                   </table>
                </td>
            </tr>
         </table>	
    </fieldset>
 </form>
<script>
    <?php if(isset($_REQUEST['mi_group'])){echo 'var bars = '.String::array2js($_REQUEST['mi_group']).';';}else{echo 'var bars = [];';}?>
    var product_array = <?php echo String::array2js([[=products=]]);?>;
    mi_init_rows('mi_group',bars);
    jQuery(document).ready(function(){
        jQuery("#department_id").change(function(){
            var selected = document.getElementById('department_id').selectedIndex;
             var inputs = document.getElementsByTagName('select');
             
             for (var i=0;i<inputs.length;i++)
             {
                if(inputs[i].id.search('department_') >= 0 && inputs[i].id != 'department_#xxxx#')
                {
                    inputs[i].selectedIndex = selected;
                }
             }   
        }); 
        
    });
    for (var i=101;i<=input_count;i++)
    {
        ProductAutocomplete(i);
    }  
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
    function AddInput(input_count){
        jQuery("#amount_"+input_count).keyup(function()
        {
             processStr(input_count);    
        });
        
        jQuery("#time_"+input_count).val(jQuery.datepicker.formatDate('dd/mm/yy', new Date()));
    	jQuery("#time_"+input_count).datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>, 1 - 1, 1),yearRange: '-100:+4' });
    }
    function ProductAutocomplete(index)
    {
        jQuery("#code_" + index).autocomplete({
             url:'get_product_purcharsing.php?department_product=1',
             onItemSelect: function(item) 
             {
                getProductFromCode(index,jQuery("#code_"+index).val());
                jQuery(".acResults").css('display','none');
                jQuery(".acResults").remove();
                //document.getElementById("name_" + index).value= product_department[item.value]['product_name'];
                //document.getElementById("unit_" + index).value = product_department[item.value]['unit'];
                
             }
        }); 
    }
    function ProductAutocomplete2(index)
    {
        jQuery("#name_" + index).autocomplete({
             url:'get_product_purcharsing.php?department_product=1&name_product=1',
             onItemSelect: function(item) 
             {
                console.log(item.data[0]);
                getProductFromCode(index,item.data[0]);
                jQuery(".acResults").css('display','none');
                jQuery(".acResults").remove();
                //document.getElementById("name_" + index).value= product_department[item.value]['product_name'];
                //document.getElementById("unit_" + index).value = product_department[item.value]['unit'];
                
             }
        }); 
    }
    function getProductFromCode(id,value){
		if(typeof(product_array[value])=='object'){
            $('code_'+id).value = product_array[value]['id'];
            $('name_'+id).value = product_array[value]['name'];
            $('unit_'+id).value = product_array[value]['unit'];
			$('name_'+id).className = 'readonly';
		}else{
		    $('unit_'+id).value = '';
            if(value){
				$('name_'+id).className = 'notice';			
				$('name_'+id).value = '[[.product_does_not_exist.]]';
			}else{
				$('name_'+id).value = '';
			}
		}
	}
    function Confirm(index){
    	//var mi_group_name = $('name_'+ index).value;
    	//return confirm('[[.Are_you_sure_delete_mi_group_name.]] '+ mi_group_name+'?');
        return confirm('[[.Are_you_sure_delete_mi_group_name.]] ?');
    }
    function Check_submit()
    {
        var ale = 1;
        var check = true;
        for (var i=101;i<=input_count;i++)
        {
            if(jQuery("#code_"+i).val() != undefined)
            {
                if(typeof(product_array[jQuery("#code_"+i).val()])=='object'){
            
        		}else{
        		    check = false;
        		}
                if(jQuery("#department_"+i).val()=='')
                {
                    check = false;
                    ale = 2;
                }
                for(var j=101; j<= input_count; j++)
                {
                    if(i != j)
                    {
                        if(jQuery("#code_"+j).val() != undefined)
                        {
                            if(jQuery("#code_"+i).val() == jQuery("#code_"+j).val() && jQuery("#department_"+i).val() == jQuery("#department_"+j).val())
                            {
                                check = false;
                                ale = 3;
                            }
                        }
                    }
                }
            }
        }
        if(check==false)
        {
            if(ale==1)
            {
                alert('[[.product_does_not_exist.]]');                
            }else if(ale==2)
            {
                alert('[[.ban_chua_chon_bo_phan.]]');
            }else if(ale==3)
            {
                alert('[[.ban_co_san_pham_trung_nhau.]]');
            }
        }
        return check;
    }
</script>