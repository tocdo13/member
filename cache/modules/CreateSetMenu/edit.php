<style>
    .multi-input-header{
        height:20px;
    }
</style>
<script>
    var product = <?php echo String::array2js($this->map['products']);?>;
</script>
<span style="display:none">
	<span id="mi_set_menu_sample">
		<div id="input_group_#xxxx#" style="margin-top: 8px;">
			<span class="multi_input">
					<input name="mi_set_menu[#xxxx#][id]" style="width:30px; background: #FFC;" id="id_#xxxx#" readonly="" autocomplete="off" type="hidden"/>
			</span>
            <span class="multi_input">
					<input  name="mi_set_menu[#xxxx#][stt]" style="width:30px; background: #FFC;" type="text"  autocomplete="off" id="stt_#xxxx#" readonly=""/>
			</span>
            <span class="multi_input">
					<input  name="mi_set_menu[#xxxx#][code]" style="width:97px;" type="text" id="code_#xxxx#" autocomplete="off"/>
			</span>
            <span class="multi_input">
					<input  name="mi_set_menu[#xxxx#][product_name]" style="width:250px;" type="text" id="product_name_#xxxx#" autocomplete="off"/>
			</span>
            <span class="multi_input">
					<input  name="mi_set_menu[#xxxx#][quantity]" required="" style="width:70px;" type="text"  autocomplete="off" id="quantity_#xxxx#" onkeypress="if((event.keyCode<48 || event.keyCode>57) && event.keyCode!=46 && event.keyCode!=44)event.returnValue=false;"/>
			</span>
            <span class="multi_input">
					<input  name="mi_set_menu[#xxxx#][unit]" style="width:98px;background: #FFC; text-align: right;" type="text" id="unit_#xxxx#" readonly=""/>
			</span>
			<span class="multi_input"><span style="width:20px;">
				<img src="packages/core/skins/default/images/buttons/delete.gif" onclick="if(confirm('<?php echo Portal::language('Are_you_sure_delete_item');?>')) mi_delete_row($('input_group_#xxxx#'),'mi_set_menu','#xxxx#','');event.returnValue=false; change_total(); checkRestaurant();" style="cursor:hand;"/>
			</span></span><br/>
		</div>
	</span>
</span>
<div class="row">
    <div class="container">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 class="panel-title"><?php echo Portal::language('set_menu_list');?></h4>
                </div>
                <form name="setMenu" method="POST" onsubmit="return checkDuplicate();">
                    <div class="panel-body">
                        <div class="col-md-3 pull-right" style="margin-bottom:20px;">
                            <div style="pull-right">
                                <input class="btn btn-sm btn-info" style="margin-right: 50px;" value="<?php echo Portal::language('Save');?>" name="submit" type="submit"/>
                            </div>
                        </div>
                        <div>
                            <?php if(Form::$current->is_error()){?><strong>B&#225;o l&#7895;i</strong><br>
                        	<?php echo Form::$current->error_messages();?><br>
                        	<?php }?>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-12" style="margin-bottom: 20px;">
                                <div class="col-md-2 form-group">
                                    <label>Mã : </label> 
                                    <input name="menu_code" type="text" id="menu_code" value="<?php echo isset($_GET['id'])?$this->map['menu_code']:''; ?>" class="form-control" autocomplete="off" <?php if(isset($_GET['id'])){ ?> readonly="" <?php } ?> />
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Tên : </label>
                                    <input name="menu_name" type="text" id="menu_name" value="<?php echo isset($_GET['id'])?$this->map['menu_name']:''; ?>" autocomplete="off" class="form-control" required="" />
                                </div>
                                <div class="col-md-2 form-group">
                                    <label>Nhà hàng : </label>
                                    <select  name="department" id="department" class="form-control" required=""><?php
					if(isset($this->map['department_list']))
					{
						foreach($this->map['department_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('department',isset($this->map['department'])?$this->map['department']:''))
                    echo "<script>$('department').value = \"".addslashes(URL::get('department',isset($this->map['department'])?$this->map['department']:''))."\";</script>";
                    ?>
	</select>
                                </div>
                                <div class="col-md-2 form-group">
                                    <label>Ngày bắt đầu : </label>
                                    <input name="start_date" type="text" id="start_date" class="form-control" value="<?php if(isset($this->map['start_date'])) echo $this->map['start_date']; ?>" />
                                </div>
                                <div class="col-md-2 form-group">
                                    <label>Ngày kết thúc : </label>
                                    <input name="end_date" type="text" id="end_date" class="form-control" value="<?php if(isset($this->map['end_date'])) echo $this->map['end_date']; ?>"/>
                                </div>
                            </div>
                            <div class="col-md-12" style="margin-bottom: 20px; margin-left: 20px;">
                                <p><span style="text-decoration: underline; font-style: italic;">(<span style="color: red;">*</span>)Chú ý</span><span style="color: blue;"> : Phải chọn nhà hàng trước khi cài đặt món. Nếu muốn chọn lại nhà hàng, xin vui lòng xóa hết các món đã cài đặt ở dưới.</span></p>
                            </div>
                            <div class="col-md-12" style="padding-left: 30px;">
                                 <span id="mi_set_menu_all_elems" style="margin-bottom: 10px; display:block;">
                					<span>
                						<span class="multi-input-header" style="width:30px;"><?php echo Portal::language('stt');?></span>
                						<span class="multi-input-header" style="width:100px;"><?php echo Portal::language('code');?></span>
                						<span class="multi-input-header" style="width:253px;"><?php echo Portal::language('product_name');?></span>
                					    <span class="multi-input-header" style="width:70px;"><?php echo Portal::language('quantity');?></span>
                                        <span class="multi-input-header" style="width:100px;"><?php echo Portal::language('unit');?></span>
                						<span class="multi-input-header" style="width:20px;"><img src="skins/default/images/spacer.gif"/></span>
                						<br/>
                					</span>
                				 </span>
                                 <input type="button" value="<?php echo Portal::language('add');?>" onclick="mi_add_new_row('mi_set_menu');myAutocomplete(input_count);myAutocomplete_name(input_count);changeSttValue();"/>
                                 <input type="hidden" value="<?php echo isset($_GET['id'])?$_GET['id']:""; ?>" name="bar_set_menu_id" />   
                            </div>
                            <hr class="col-md-6" />
                            <div class="col-md-12" style="margin-top: 30px; margin-left: 340px;">
                                <div><span style="font-weight: bold;">Tổng : </span><input name="total_hidden" type="text" id="total_hidden" value="<?php echo isset($_GET['id'])?System::display_number($this->map['total']):'0'; ?>" style="text-align: right;" oninput="jQuery(this).val(number_format(this.value));" required=""/> </div>
                                   
                            </div>
                        </div>                        
                    </div>
                <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
            </div>
        </div>
    </div>
</div>
<script>
<?php
if(isset($_REQUEST['mi_set_menu']))
{
    echo 'var mi_set_menu = '.String::array2js($_REQUEST['mi_set_menu']).';';
?>
mi_init_rows('mi_set_menu',mi_set_menu);
<?php
}
?>
jQuery(document).ready(function(){
    //myAutocompleteSetMenuCode(jQuery("#menu_code"));
    //myAutocompleteSetMenuName(jQuery("#menu_name"));
    jQuery("#start_date").datepicker();
    jQuery("#end_date").datepicker();
    <?php
    if(isset($_GET['id'])){
    ?>
        //checkRestaurant(); 
        jQuery("div[id^=input_group_]").each(function(){
            var id = jQuery(this).attr("id");
            var arr_temp = id.split("_");
            id = arr_temp[2];
            myAutocomplete(id);
            myAutocomplete_name(id);
        });   
    <?php    
    }
    ?>
});
function callAutocomplete(){
    <?php
       if(!isset($_GET['copy'])){
    ?>
    //myAutocompleteSetMenuCode(jQuery("#menu_code"));
    //myAutocompleteSetMenuName(jQuery("#menu_name"));
    jQuery("#menu_code").val('');
    jQuery("#menu_name").val('');
    jQuery("#total").html('');
    jQuery("#total_hidden").val(0);
    <?php
     }
    ?>
}
function delete_set_menu(obj){
    if(confirm('Bạn có chắc không?')){
        jQuery(obj).parent().parent().remove();
        checkRestaurant();
    }
}
/**
function myAutocompleteSetMenuCode(obj)
{	   
    var department = jQuery("#department").val(); 
    jQuery(obj).autocomplete({
            url: 'get_product.php?product=1&set_menu=1&type=set_menu&searchByCode=1&search_type=set_menu_code&department='+department,
            onItemSelect: function(item){               
                getProductSetMenuFromCode(obj,jQuery(obj).val());             
	       }
    });
}
function getProductSetMenuFromCode(obj,value){
    if(obj)
    {
		var department = jQuery("#department").val(); 
        for(var key in product){
                if(product[key]['product_id']==value && product[key]['department_code']==department){ 
                    $(obj).value = product[key]['product_id'];
        			$('menu_name').value = product[key]['product_name'];
                    //$('total').innerHTML = product[key]['price'];
                    $('total_hidden').value = product[key]['price'];
                }
            }
    }
}
function myAutocompleteSetMenuName(obj)
{	    
    var department = jQuery("#department").val(); 
    jQuery(obj).autocomplete({
            url: 'get_product.php?product=1&set_menu=1&type=set_menu&searchByName=1&search_type=set_menu_name&department='+department,
            onItemSelect: function(item){
                getProductSetMenuFromName(obj,jQuery(obj).val());         
	       }
    });
}
function getProductSetMenuFromName(obj,value){
    if(obj)
    {
		var department = jQuery("#department").val(); 
        for(var key in product){
                if(product[key]['product_name']==value && product[key]['department_code']==department){
                    $(obj).value = product[key]['product_name'];
        			$('menu_code').value = product[key]['product_id'];
                    $('total').innerHTML = product[key]['price'];
                    $('total_hidden').value = product[key]['price'];
                }
            }
    }
}
**/
function myAutocomplete(index)
{	   
	var department = jQuery("#department").val(); 
    jQuery("#code_"+index).autocomplete({
            url: 'get_product.php?product=1&type=set_menu&searchByCode=1&set_menu=1&child=1&department='+department,
            onItemSelect: function(item){
                getProductFromCode(index,jQuery("#code_"+index).val());
               
	       }
    });
    checkRestaurant();
}
function myAutocomplete_name(index)
{	
	var department = jQuery("#department").val(); 
    jQuery("#product_name_"+index).autocomplete({
            url: 'get_product.php?product=1&type=set_menu&searchByName=1&set_menu=1&child=1&department='+department,
            onItemSelect: function(item){
                getProductFromName(index,jQuery("#product_name_"+index).val(),'name');
                
	       }
    });
    checkRestaurant();
}
function getProductFromName(id,value,type='')
{
    var department = jQuery("#department").val();   
    if($('code_'+id))
    {
            for(var key in product){
                if(product[key]['product_name']==value && product[key]['department_code']==department){
                    $('code_'+id).value = product[key]['product_id'];
        			$('product_name_'+id).value = product[key]['product_name'];
                    //$('quantity_'+id).value = 1;
                    $('unit_'+id).value = product[key]['unit'];
                    //$('price_'+id).value = product[key]['price'];
                }
            }
            //change_total();
    } 
}
function getProductFromCode(id,value)
{
    var department = jQuery("#department").val();  
    value = value+"_"+department;
    if($('code_'+id))
    {
		if(typeof(product[value])=='object')
        {
			$('product_name_'+id).value = product[value]['product_name'];
            $('unit_'+id).value = product[value]['unit'];
        }
        else
        {
			$('product_name_'+id).value = '';
            $('unit_'+id).value = product[value]['unit'];
		}
	    //change_total();
    } 
}
function changeSttValue(){
    var max_stt = jQuery("#mi_set_menu_all_elems span:last-child").prev().find(("div span:nth-child(2) input")).val();
    var max_stt = parseInt(max_stt)+1;
    if(isNaN((max_stt))){
        max_stt = 1;
    }
        jQuery("#mi_set_menu_all_elems span:last-child div span:nth-child(2) input").val(max_stt);
}
function change_total(){
    var total = 0;
    jQuery("div[id^=input_group_]").each(function(){
        var quantity = jQuery(this).find("input[id^=quantity]").val();
        var price = jQuery(this).find("input[id^=price]").val();
        total += (quantity*price);
    });
    jQuery("#total").html(total);
    
}
function checkDuplicate(){
    if(confirm('<?php echo Portal::language('Are_you_sure_Save');?>?')){
        var j = 0;
        var condition = true;  
          jQuery("input[id^=code_]").each(function(){
                if(j!=0 && condition){
                    var code = jQuery(this).val();
                    jQuery("input[id^=code_]").not(this).each(function(){
                        var code_temp = jQuery(this).val();
                        if(code==code_temp){
                            alert("Mã "+code+" đã bị trùng! Xin vui lòng kiểm tra lại!");
                            condition=false;
                        }
                    });
                }
                j++;
          });
         jQuery("#department").removeAttr('disabled');  
        return condition;
    }
    else{
        return false;
    }  
}
function checkRestaurant(){
    <?php
      if(!isset($_GET['copy'])){
    ?>
    //var i=0;
//    jQuery("div[id^=input_group_").each(function(){
//        if(i!=0){
//            jQuery("#department").attr('disabled','');
//        }
//        else{
//            jQuery("#department").removeAttr('disabled');
//            i++;
//        }
//    });
    <?php
     }
    ?>
}
</script>