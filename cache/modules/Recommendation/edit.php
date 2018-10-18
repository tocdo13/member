<style>
.simple-layout-middle{
    width: 100%;
}
</style>
<?php System::set_page_title(HOTEL_NAME);?>
<span style="display:none;">
     <span id="products_sample">
        <div id="input_group_#xxxx#" style="text-align:left;">
            <span class="multi-input" style="width: 25px;">
                 <input name="check_box_#xxxx#" id="check_box_#xxxx#" type="checkbox" class="class_check_box" onclick="choose_items(this,#xxxx#);" />        
            </span>
            <span class="multi-input">
            <input  name="products[#xxxx#][id]" type="hidden" id="id_#xxxx#"/>
            <input  name="products[#xxxx#][index]" type="text" id="index_#xxxx#"  style="width:34px;background:#CCCCCC;text-align: center;" readonly="readonly"/>
            </span>
            <span class="multi-input">
                <input name="products[#xxxx#][product_id]" type="text"  id="product_id_#xxxx#" style="width: 87px;" onfocus="GetProduct(#xxxx#);" autocomplete="off" />
            </span>
            <span class="multi-input">
                <input  name="products[#xxxx#][product_name]" type="text" id="product_name_#xxxx#" style="width:155px;" onfocus="GetProduct(#xxxx#);" autocomplete="off"/>
            </span>
            <span class="multi-input">
                <input  name="products[#xxxx#][unit]" type="text" id="unit_#xxxx#" style="width:105px;background: #CCC;" readonly="readonly" />
            </span>
            
            <span class="multi-input">
                <input  name="products[#xxxx#][quantity]" type="text" id="quantity_#xxxx#" style="width:85px;text-align: right;" />
            </span>
              
            <span class="multi-input">
                <input  name="products[#xxxx#][delivery_date]" type="text" id="delivery_date_#xxxx#" style="width:105px;text-align: center;" />
            </span>
            
            <span class="multi-input">
                <input  name="products[#xxxx#][remain_product]" type="text" id="remain_product_#xxxx#" style="width:100px;text-align: right;background: #CCC;" readonly="readonly" />
            </span>
            <span class="multi-input">
                <input  name="products[#xxxx#][remain_total]" type="text" id="remain_total_#xxxx#" style="width:105px;text-align: right;background: #CCC;" readonly="readonly" />
            </span>

            <span class="multi-input">
                <input  name="products[#xxxx#][note]" type="text" id="note_#xxxx#" style="width:206px;text-align: left;" />
            </span>
            <?php 
				if((User::can_view(false,ANY_CATEGORY)))
				{?>
			<span class="multi-input" style="width: 150px; text-align: center; cursor: pointer;">
                <span onclick="purchase_history(#xxxx#)"><i class="fa fa-history w3-text-indigo" style="font-size: 20px; padding-top: 2px;"></i></span>
            </span>
			
				<?php
				}
				?>
            <span class="multi-input" style="width: 40px;">
                <a href="#" onClick=" mi_delete_row($('input_group_#xxxx#'),'products','#xxxx#','');" style="margin-left: 15px;"><i class="fa fa-times-circle w3-text-red" style="font-size: 20px; padding-top: 2px;"></i></a>
            </span>
            <br clear="all" />
        </div>
     </span>
</span>

<div class="customer_type-bound">
<form name="EditSupplierPriceForm" method="post" enctype="multipart/form-data">
<input  name="group_deleted_ids" id="group_deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
    <input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>"/>
    
    <table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr height="40">
            <td width="60%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-plus-square w3-text-orange" style="font-size: 26px;"></i> <?php echo $this->map['title'];?></td>
            <td width="40%" align="right" nowrap="nowrap" style="padding-right: 30px;">
                <input name="save" type="submit" value="<?php echo Portal::language('Save_and_close');?>" class="w3-btn w3-orange w3-text-white" onclick="return check_input();" style="text-transform: uppercase; margin-right: 5px;"/>
                <input name="move" type="submit" value="<?php echo Portal::language('pc_move');?>" class="w3-btn w3-lime" onclick="return check_input();"style="text-transform: uppercase; margin-right: 5px;"/>
                <a href="<?php echo Url::build_current(array('group_id','act'));?>"  class="w3-btn w3-green"style="text-transform: uppercase; margin-right: 5px; text-decoration: none;"><?php echo Portal::language('back');?></a>
                <input name="delete" type="submit" onclick="return check_delete();" value="<?php echo Portal::language('delete');?>"  class="w3-btn w3-red"style="text-transform: uppercase; "/>
            </td>
        </tr>
    </table>
    
    <div class="content">
        <?php if(Form::$current->is_error()){?><div><br/><?php echo Form::$current->error_messages();?></div><?php }?>
        <br />
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr valign="top">
                <td>
                    <fieldset>
                    <legend class="title"><?php echo Portal::language('info');?></legend>
                    <table border="0" cellspacing="0" cellpadding="2">
                        <tr>
                            <td class="label"><?php echo Portal::language('recomment_date');?><span style="color: red;">(*)</span>:</td>
                            <td><input name="recommend_date" type="text" id="recommend_date" style="width: 80px; height: 24px;" value="<?php echo $this->map['recommend_date']?>" />
                            <input name="recommend_time" type="text" id="recommend_time" style="width: 50px; height: 24px;" value="<?php echo $this->map['recommend_time']?>"/></td>
                            <td class="label"><?php echo Portal::language('person_recomment');?><span style="color: red;">(*)</span>:</td>
                            <td><input name="person_recommend" type="text" id="person_recommend" style="width: 150px; height: 24px; background-color: #CCC;" readonly="readonly" value="<?php echo $this->map['person_recommend']?>"/></td>
                            <td class="label"><?php echo Portal::language('department');?><span style="color: red;">(*)</span>:</td>
                            <td >
                            <select  name="department_id" id="department_id" style="width: 150px; height: 24px;background-color: #CCC;" onchange="Check_Product(); Check_Warehouse(); ChangeStyle();"><?php
					if(isset($this->map['department_id_list']))
					{
						foreach($this->map['department_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('department_id',isset($this->map['department_id'])?$this->map['department_id']:''))
                    echo "<script>$('department_id').value = \"".addslashes(URL::get('department_id',isset($this->map['department_id'])?$this->map['department_id']:''))."\";</script>";
                    ?>
	
                            <?php echo $this->map['department_list'];?>
                            </select>
                            <input type="hidden" name="portal_department_id" id="portal_department_id" value="<?php echo $this->map['department_id'];?>" style=" height: 24px;" />
                            <script type="text/javascript">
                            document.getElementById("department_id").value ='<?php  echo isset($this->map['department_id'])? $this->map['department_id']:'';?>';
                            </script>
                            <input  name="warehouse_name" id="warehouse_name" readonly="readonly" style="background-color: #CCC; height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('warehouse_name'));?>">
                            <input  name="warehouse_id" id="warehouse_id" value=""/ type ="hidden" value="<?php echo String::html_normalize(URL::get('warehouse_id'));?>">
                            </td>
                        </tr>
                        
                        <tr>
                            <td class="label"><?php echo Portal::language('description');?>:</td>
                            <td colspan="5">
                            <input name="description" type="text" id="description" style="width: 770px;  height: 24px;" value="<?php echo isset($this->map['description'])?$this->map['description']:'';?>" />

                            </td>
                        </tr>

                        <tr>
                            <td class="label"><?php echo Portal::language('delivery_date');?>:</td>
                            <td colspan="5">
                            <input name="delivery_date" type="text" id="delivery_date" value="<?php echo isset($_REQUEST['delivery_date'])?$_REQUEST['delivery_date']:'' ?>" style="width: 80px; height: 24px;" onchange="change_delivery_date(this);" />

                            </td>
                        </tr>
                    </table>
                    <?php 
                    if(User::can_admin(2062,'1042000000000000000'))
                    {
                        ?>
                        <div style="text-align: right; padding-right: 20px;">
                        <?php
                            if(Url::get('cmd')=='edit')
                            {
                                if(empty($this->map['confirm']))
                                {
                                    ?>
                                    <input class="w3-btn w3-indigo" type="submit" name="confirm"  id="confirm" value="Trưởng BP xác nhận" style="width: 150px;" />
                                    <?php 
                                }
                            } 
                        ?>
                            
                        </div>
                        <?php 
                    }
                    ?>
                    
                    
                    </fieldset>
                    <fieldset class="check_select_department upload" style="display: none;">
                        <legend class="title">Nhập từ file excel</legend>
                        <table width="1100" border="1" cellpadding="2" cellspacing="0" bordercolor="#CCCCCC">
                            <tr>
                                <td style="width: 80px;"><input  name="template" id="template" onclick="window.open('packages/hotel/packages/purchasing/modules/Recommendation/recommendation.rar');" value="Tải file mẫu" / type ="button" value="<?php echo String::html_normalize(URL::get('template'));?>"></td>
                                <td style="width: 120px;"><input  name="data" id="data" / type ="file" value="<?php echo String::html_normalize(URL::get('data'));?>"></td>
                                <td><input  name="check_file" id="check_file"  value="Duyệt file" / type ="submit" value="<?php echo String::html_normalize(URL::get('check_file'));?>"></td>
                            </tr>
                        </table>
                    </fieldset>
                    
                    <fieldset>
                        <legend class="title">Danh sách sản phẩm</legend>
                        <div>
                            <span id="products_all_elems" style="text-transform: uppercase;">
                                <span class="multi-input-header" style="width:25px; height: 24px; padding-top: 4px;"><input name="check_box_all" id="check_box_all" type="checkbox" onclick="fun_check_box_all();" /></span>
                                <span class="multi-input-header" style="width:35px;text-align: center; height: 24px; padding-top: 4px;"><?php echo Portal::language('stt');?></span>
                                <span class="multi-input-header" style="width:87px;text-align: center; height: 24px; padding-top: 4px;"><?php echo Portal::language('product_code');?></span>
                                <span class="multi-input-header" style="width:155px;text-align: center; height: 24px; padding-top: 4px;"><?php echo Portal::language('name_product');?></span>
                                <span class="multi-input-header" style="width:105px;text-align: center; height: 24px; padding-top: 4px;"><?php echo Portal::language('unit');?></span>
                                <span class="multi-input-header" style="width:85px;text-align: center; height: 24px; padding-top: 4px;"><?php echo Portal::language('quantity');?></span>

                                <span class="multi-input-header" style="width:105px;text-align: center; height: 24px; padding-top: 4px;"><?php echo Portal::language('delivery_date');?></span>
                                <span class="multi-input-header" style="width:100px;text-align: center; height: 24px; padding-top: 4px;"><?php echo Portal::language('remain_product');?></span>
                                <span class="multi-input-header" style="width:105px;text-align: center; height: 24px; padding-top: 4px;"><?php echo Portal::language('remain_total_other');?></span>
        
                                <span class="multi-input-header" style="width:205px;text-align: center; height: 24px; padding-top: 4px;"><?php echo Portal::language('note');?></span>
                                <span class="multi-input-header" style="width:150px;text-align: center; height: 24px; padding-top: 4px;"><?php echo Portal::language('purchase_history');?></span>
                                <span class="multi-input-header" style="width:40px;text-align: center; height: 24px; padding-top: 4px;"><?php echo Portal::language('Delete');?></span>
                                <br clear="all"/>
                            </span>
                        </div>
                        <div class="check_select_department after_upload" style="display: none;"><a href="javascript:void(0);" onclick="if(jQuery('#department_id').val()==0){ alert('Bạn chưa chọn bộ phận đề xuất!');document.getElementById('department_id').focus();document.getElementById('department_id').style.backgroundColor = 'yellow'; }else{mi_add_new_row('products');} change_index();refresh_remain_product();" class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-top: 5px;"><?php echo Portal::language('Add');?></a></div>     
                        
                    </fieldset>
                </td>
            </tr>
        </table>
    </div>
    <input name="person_edit" type="hidden" id="person_edit" value="<?php echo $this->map['person_edit'];?>" />
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			 
</div>

<script type="text/javascript">
<?php   
    if(isset($_REQUEST['products']))
    {
        echo 'var products = '.String::array2js($_REQUEST['products']).';';
        ?>
        mi_init_rows('products',products);
        <?php
    }
?>
for(var i=101;i<=input_count;i++)
{
    jQuery("#delivery_date_"+i).datepicker();
}

jQuery('#department_id').val(<?php echo isset($_REQUEST['department_id'])?$_REQUEST['department_id']:''; ?>);

var check_upload = '<?php echo $this->map['upload_file'];?>';
if(check_upload == 1)
{
    jQuery('.upload').css('display', 'none');
    jQuery('.after_upload').css('display', '');
}

function ChangeStyle()
{
    if(jQuery('#department_id').val() == 0)
    {
        jQuery('.check_select_department').css('display', 'none');
    }else
    {
        jQuery('.check_select_department').css('display', '');
    }  
}

jQuery('#recommend_time').mask('99:99');
/** Daund viet lai cho auto ra san pham theo tung bo phan */
function GetProduct(xxxx)
{
    var department_id = jQuery('#department_id').val();
    jQuery("#product_id_" + xxxx).autocomplete({
        url:'get_purchasing_new.php?department=1&name_product=0&department_id=' + department_id,
        onItemSelect: function(item) 
        {
            document.getElementById("product_id_" + xxxx).value = item.value;
            document.getElementById("product_name_" + xxxx).value = item.data[0];
            document.getElementById("unit_"+ xxxx).value = item.data[1];
            jQuery(".acResults ").css({'display':'none'});
            check_remain_product(xxxx);
        }
    });
    jQuery("#product_name_" + xxxx).autocomplete({
        url:'get_purchasing_new.php?department=1&name_product=1&department_id=' + department_id,
        onItemSelect: function(item) 
        {
            document.getElementById("product_name_" + xxxx).value = item.value;
            document.getElementById("product_id_" + xxxx).value = item.data[0];
            document.getElementById("unit_"+ xxxx).value = item.data[1];
            jQuery(".acResults ").css({'display':'none'});
            check_remain_product(xxxx);
        }
    });
    
    jQuery(".acResults ").css({'max-height':'300px'});  
}

function AddNewRow()
{
    var department_id = document.getElementById('department_id').value;
    if(department_id==0)
    {
        alert("Bạn chưa chọn bộ phận đề xuất!");
        document.getElementById('department_id').focus();
        document.getElementById('department_id').style.backgroundColor = "yellow";
    }
}
/** Daund viet lai cho auto ra san pham theo tung bo phan */

function ProductAutocomplete(index)
{
    //jQuery(".acResults").remove();
    //1. lay ra gia tri la bo phan id 
    var department_id = document.getElementById('department_id').value;
    if(department_id==0)
    {
        alert("Bạn chưa chọn bộ phận đề xuất!");
        document.getElementById('department_id').focus();
        document.getElementById('department_id').style.backgroundColor = "yellow";
    }
    /*else
    {
        jQuery("#product_id_" + index).autocomplete({
         url:'get_product_purcharsing.php?department=1&department_id=' + department_id,
         onItemSelect: function(item) 
         {

            document.getElementById("product_name_" + index).value = elements[item.data[1]]['product_name'];
            document.getElementById("unit_"+ index).value = elements[item.data[1]]['unit'];
            jQuery(".acResults ").css({'display':'none'});
            check_remain_product(index);
            //jQuery(".acResults ul").remove();
         }
        }); 

        jQuery(".acResults ").css({'max-height':'300px'});   
    }*/
    
}
function nameProductAutocomplete(index)
{
    jQuery(".acResults").remove();
    //1. lay ra gia tri la bo phan id 
    var department_id = document.getElementById('department_id').value;

    if(department_id==0)
    {
        alert("Bạn chưa chọn bộ phận đề xuất!");
        document.getElementById('department_id').focus();
        document.getElementById('department_id').style.backgroundColor = "yellow";
    }
    /*else
    {
        //console.log(department_id);
        jQuery("#product_name_" + index).autocomplete({
             url:'get_product_purcharsing.php?department=1&name_product=1&department_id=' + department_id,
             onItemSelect: function(item) 
             {
                document.getElementById("product_id_" + index).value = elements[item.data[1]]['product_id'];
                document.getElementById("product_name_" + index).value = elements[item.data[1]]['product_name'];
                document.getElementById("unit_"+ index).value = elements[item.data[1]]['unit'];
                check_remain_product(index);
             }
        });
    }*/
}
jQuery("#recommend_date").datepicker();

jQuery("#delivery_date").datepicker();
function check_input()
{
    var date_recommend  = document.getElementById("recommend_date");
    var department = document.getElementById("department_id");
    var peson_recommed = document.getElementById("person_recommend");
    /**var department_old = document.getElementById("portal_department_id");
    if(department_old.value!=0)
    {
        if(department_old.value!=department.value)
        {
            alert("Bạn không được sửa bộ phận");
            department.focus();
            department.style.backgroundColor = "yellow";
            return false;
        }
    }**/
    if(date_recommend.value=="")
    {
        alert("Bạn chưa nhập ngày đề xuất");
        date_recommend.focus();
        date_recommend.style.backgroundColor = "yellow";
        return false;
    }
    if(department.value==0)
    {
        alert("Bạn chưa chọn Bộ phận đề xuất");
        department.focus();
        department.style.backgroundColor = "yellow";
        return false;
    }
    if(peson_recommed.value=="")
    {
        alert("Bạn chưa nhập người đề xuất");
        peson_recommed.focus();
        peson_recommed.style.backgroundColor = "yellow";
        return false;
    }
    for(var i=101;i<=input_count;i++)
    {
        var quantity = document.getElementById("quantity_" + i);
        var delivery_date = document.getElementById("delivery_date_" + i);

        if(quantity.value=='')
        {
            alert("Bạn chưa nhập số lượng!");
            quantity.focus();
            quantity.style.backgroundColor = "yellow";
            return false;
        }
        if(quantity.value == '0')
        {
            alert("Số lượng không thể = 0");
            quantity.focus();
            quantity.style.backgroundColor = "yellow";
            return false;            
        }
        if(delivery_date.value=='')
        {
            alert("Bạn chưa nhập ngày giao!");
            delivery_date.focus();
            delivery_date.style.backgroundColor = "yellow";
            return false;
        }
    }
    return true;
}

function change_index()
{
    document.getElementById("index_" + input_count).value = (input_count - 100);
    
    for(var i=101;i<=input_count;i++)
    {
        jQuery("#delivery_date_" + i).datepicker();
    }
}

function fun_check_box_all()
{
    if(document.getElementById("check_box_all").checked==true)
    {
        jQuery(".class_check_box").attr("checked","checked");
        //voi moi dong lay ra id va gan vao deletes 
        var str_id = "";
        for(var i=101;i<=input_count;i++)
        {
            if(jQuery("#id_" + i).val()!='')
            {
                str_id += jQuery("#id_" + i).val() + ",";
            }
        }
        if(str_id!="")
            str_id = str_id.substr(0,str_id.length-1);
        jQuery("#deleted_ids").val(str_id);
    }
    else
    {
        jQuery(".class_check_box").removeAttr("checked");
        jQuery("#deleted_ids").val("");
    }
}
function check_delete()
{
    //neu co chon 1 checkbox 
    var flag = false;
    for(var i=101;i<=input_count;i++)
    {
        var obj =document.getElementById("check_box_" + i);

        if(obj.checked==true)
        {
            flag = true;
            break;
        }
    }
    if(flag)
    {
        if(confirm('Bạn có chắc chắn xóa các sản phẩm đã chọn!'))
        {
            EditSupplierPriceForm.submit();
        }
        else
            return false;
    }
    else
    {
        alert('Bạn chưa chọn sản phẩm để xóa!');
        return false;
    }
    
}
function choose_items(obj,index)
{
    if(obj.checked==true)
    {
        var id = jQuery("#id_" + index).val();
        if(id!='')
        {
            var str_id = jQuery("#deleted_ids").val();
            if(str_id!="")
                str_id +="," + id;
            else
                str_id = id;
            
            jQuery("#deleted_ids").val(str_id);
        }
        
    }
    else
    {
        var id = jQuery("#id_" + index).val();
        //console.log(id);
        if(id!='')
        {
            var str_id = jQuery("#deleted_ids").val();
            str_id = str_id.replace(id, "0");
            jQuery("#deleted_ids").val(str_id);
        }
    }
    
}
function change_delivery_date(obj)
{
    $check = false;
    for(var i=101;i<=input_count;i++)
    {
        if(jQuery("#check_box_"+i).val()!=undefined && document.getElementById("check_box_" + i).checked==true)
        {
            $check=true;
            if(document.getElementById("check_box_" + i).checked)
                document.getElementById("delivery_date_" + i).value = obj.value;
        }
    }
    if(!$check)
    {
        alert('Bạn chưa chọn sản phẩm');
        obj.value = '';
    }
    jQuery("#deleted_ids").val('');
}

function check_remain_product(index)
{
    var department = document.getElementById("department_id");
    if(department.value==0)
    {
        department.focus();
        department.style.backgroundColor = "yellow";
    }
    else
    {
        if(index==false)
        {
            if(input_count>=101)
            {
                send(department,101,false);
            }
        }
        else
        {
            send(department,index,true);
        }
        
    }
}
//ham de quy gui nhieu san pham hoac gui 1 san pham co index hien tai flag = true the hien index hien tai 
function send(department,index,flag)
{
    //kiem tra so luong ton cua san pham thuoc 1 bo phan 
    
    if(jQuery("#product_id_"+index).val()!=undefined && jQuery("#product_id_"+index).val()!='')
    {
        //console.log(index);
        var product_id = document.getElementById("product_id_" + index).value;
        var department_id = department.value;
        //console.log(department_id);
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
                var text_reponse = xmlhttp.responseText;
                //console.log(text_reponse);
                //dinh dang: NLS01_12_0:NLS01_ALL_1([product_id]_[warehouse_id]_[so luong ton])
                var str_product_warehouse = text_reponse.split(':');
                var remain_product = str_product_warehouse[0].split('_');
                var remain_total = str_product_warehouse[1].split('_');
                document.getElementById("remain_product_" + index).value = remain_product[2];
                document.getElementById("remain_total_" + index).value = remain_total[2] - remain_product[2];
                if(flag==false)
                {
                    if(index<input_count)
                    {
                        index++;
                        send(department,index,false);
                    }
                }
            }
        }
        xmlhttp.open("GET","get_product_purcharsing.php?remain_product=1&product_id="+ product_id + "&department_id=" + department_id,true);
        xmlhttp.send();
    }
    
    
}
function refresh_remain_product()
{
    document.getElementById("remain_product_" + input_count).style.backgroundColor = "yellow";
    document.getElementById("remain_product_" + input_count).readonly ="readonly";
    document.getElementById("remain_total_" + input_count).style.backgroundColor = "yellow";
    document.getElementById("remain_total_" + input_count).readonly ="readonly";
}
function Check_Product()
{
    if(jQuery("#department_id").val()==0)
    {
        for(var i=101;i<=input_count;i++)
        {
            if(jQuery("#product_id_"+i).val()!=undefined && jQuery("#product_id_"+i).val()!='')
            {
                mi_delete_row($('input_group_'+i),'products',i,'');
            }
        }
    }
    else
    {
        $list_product = '';
        $list_product_arr = new Array();
        for(var i=101;i<=input_count;i++)
        {
            if(jQuery("#product_id_"+i).val()!=undefined && jQuery("#product_id_"+i).val()!='')
            {
                if($list_product=='')
                    $list_product = '\''+jQuery("#product_id_"+i).val()+'\'';
                else
                    $list_product += ','+'\''+jQuery("#product_id_"+i).val()+'\'';
                $list_product_arr[i] = new Array();
                $list_product_arr[i]['id'] = jQuery("#product_id_"+i).val();
                $list_product_arr[i]['index'] = i;
            }
        }
        if($list_product!='')
        {
            jQuery.ajax({
    					url:"get_purchasing.php?",
    					type:"POST",
    					data:{data:'check_product_department',list_product:$list_product,department_id:jQuery("#department_id").val()},
    					success:function(html)
                        {
                            var obj = jQuery.parseJSON(html);
                            $check = false;
                            for(j in $list_product_arr)
                            {
                                if(obj[$list_product_arr[j]['id']]==undefined)
                                {
                                    mi_delete_row($('input_group_'+$list_product_arr[j]['index']),'products',$list_product_arr[j]['index'],'');
                                }
                                else
                                    $check=true;
                            }
                            if($check)
                            {
                                check_remain_product(false);
                            }
    					}
    		});
        }
    }
}
var get_warehouse_list =<?php echo $this->map['get_warehouse_list'];?>;
    function Check_Warehouse()
    {
        var department_id = jQuery("#department_id").val();
        //console.log(department_id);
        for(var j in get_warehouse_list)
        {
            if(department_id == get_warehouse_list[j]['id'] )
            {
                jQuery("#warehouse_name").val(get_warehouse_list[j]['warehouse_name']);
            }
        
        }
    } 

function purchase_history(xxxx)
{
    product_id = jQuery('#product_id_'+xxxx).val();
    if(product_id == '')
    {
        alert('Chưa có sản phẩm không thể xem lịch sử!');
        jQuery('#product_id_'+xxxx).css('background-color','yellow');
    }else
    {
        url = '?page=purchase_history&from_date=&to_date=&supplier_id=&product_id='+product_id+'';  
        window.open(url);       
    }  
}
</script>