<?php System::set_page_title(HOTEL_NAME);?>

<script type="text/javascript">
   
    function openw(cmd)
    {
        var url = '?page=supplier_price';
		url += '&cmd='+cmd;
        if(cmd=='add')
        {
            url +='&supplier=' + document.getElementById("supplier").value;
        }
        else if(cmd=='edit')
        {
            
        }
        else if(cmd=='delete')
        {
            
        }
        else
        {
            
        }
		//var ids = jQuery('#ids').val();
       // url += '&ids='+ids;
       // url += '&type='+type;
		//window.open(url);
        location.href = url;
        console.log(url);
    }
</script>
<div>
<form name="ListSupplierForm" method="post" enctype="multipart/form-data">
	<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr height="40">
        	<td width="60%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> <?php echo Portal::language('quotation_from_supplier');?></td>
            <td width="40%" align="right" style="padding-right: 30px;">           
                <?php if(User::can_add(false,ANY_CATEGORY)){?><a href="<?php echo URL::build_current(array('cmd'=>'import'));?>"  class="w3-btn w3-lime" style="text-transform: uppercase; margin-right: 5px; text-decoration: none;"><?php echo Portal::language('import_from_excel');?></a><?php }?>
                <a href="<?php echo URL::build_current(array('cmd'=>'add'));?>"  class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; margin-right: 5px; text-decoration: none;"><?php echo Portal::language('Add');?></a>
				<input name="submit_edit" type="submit" id="submit_edit" value="<?php echo Portal::language('edit');?>" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; margin-right: 5px;"/>
                <a href="javascript:void(0)" onclick="if(!confirm('<?php echo Portal::language('are_you_sure');?>')){return false};ListSupplierForm.cmd.value='delete_group';ListSupplierForm.submit();"  class="w3-btn w3-red" style="text-transform: uppercase; text-decoration: none;"><?php echo Portal::language('Delete');?></a>
                <!--<input type="button" onclick="openw('add');" value="<?php echo Portal::language('add');?>" class="button-medium-add"  />
                <input type="button" value="<?php echo Portal::language('edit');?>" onclick="openw('edit');"  class="button-medium-edit" />
                <input type="button" value="<?php echo Portal::language('delete');?>" onclick="openw('delete');"  class="button-medium-delete" />-->
            </td>
        </tr>
    </table>        
	
    <div class="content"  >
        <table style="width: 85%; margin: 0 auto;">
        <tr><td>
		<fieldset>
			<legend class="title"><?php echo Portal::language('search');?></legend>
            <table width="100%"  style="text-align: center; left: 30%;">
                <tr>
                <td >
            <?php echo Portal::language('code_product');?>:
            <input  name="code_product" id="code_product" style="width: 150px; height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('code_product'));?>">
            <?php echo Portal::language('product_name11');?>:
            <input  name="product_name" id="product_name" style="width: 150px;height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('product_name'));?>">
			<?php echo Portal::language('supplier');?>:
           <!-- <select  name="supplier" id="supplier" style="width: 150px;"><?php
					if(isset($this->map['supplier_list']))
					{
						foreach($this->map['supplier_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('supplier',isset($this->map['supplier'])?$this->map['supplier']:''))
                    echo "<script>$('supplier').value = \"".addslashes(URL::get('supplier',isset($this->map['supplier'])?$this->map['supplier']:''))."\";</script>";
                    ?>
	
            <?php echo $this->map['option_suppliers'];?>
            </select> -->
            <input  name="supplier" id="supplier" onfocus="Autocomplete();" style="height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('supplier'));?>">
            <input  name="supplier_id" id="supplier_id" style="display: none;"  / type ="text" value="<?php echo String::html_normalize(URL::get('supplier_id'));?>">
            <input name="search" type="submit" value="<?php echo Portal::language('search');?>" style="height: 24px;" />
                </td></tr>
            </table>
		</fieldset>
        </td></tr>
        </table>
        <br />
        
        <table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#D9ECFF" rules="cols">
            <tr class="w3-light-gray w3-border" style="height: 24px; text-transform: uppercase;">
                <th width="10"><input type="checkbox" id="all_item_check_box"/></th>
                <th width="30"><?php echo Portal::language('order_number');?></th>
                <th width="100" align="center"><?php echo Portal::language('code_product');?></th>
                <th width="300" align="center"><?php echo Portal::language('name_product');?></th>
                <!-- Oanh add -->
                <th width="80" align="center"><?php echo Portal::language('unit');?></th>
                <!-- End oanh -->
                <th width="300" align="center"><?php echo Portal::language('supplier');?></th>
                <th width="80" align="center"><?php echo Portal::language('from_date');?></th>
                <th width="80" align="center"><?php echo Portal::language('to_date');?></th>
                <th width="80" align="center"><?php echo Portal::language('price');?></th>
                <th width="80" align="center"><?php echo Portal::language('tax');?></th>
                <th width="100" align="center"><?php echo Portal::language('price_after_tax');?></th>
                <th width="40" align="center"><?php echo Portal::language('edit');?></th>
                <th width="40" align="center"><?php echo Portal::language('delete');?></th>
            </tr>
            <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
            <tr <?php echo $this->map['items']['current']['index']%2==0?' style="background-color: #D4F8FA;"':''?>>
                <td align="center">
                    <input name="item_check_box[]" type="checkbox" class="item-check-box" value="<?php echo $this->map['items']['current']['id'];?>"/>
                </td>
                <td align="center"><?php echo $this->map['items']['current']['index'];?></td>
                <td align="left"><?php echo $this->map['items']['current']['product_id'];?></td>
                <td><?php echo $this->map['items']['current']['product_name'];?></td>
                <!-- Oanh add -->
                <td><?php echo $this->map['items']['current']['unit_name'];?></td>
                <!-- End oanh -->
                <td><?php echo $this->map['items']['current']['supplier_name'];?></td>
                <td align="center"><?php echo $this->map['items']['current']['starting_date'];?></td>
                <td align="center"><?php echo $this->map['items']['current']['ending_date'];?></td>
                <td align="right"><?php echo $this->map['items']['current']['price'];?></td>
                <td align="right"><?php echo $this->map['items']['current']['tax'];?></td>
                <td align="right"><?php echo $this->map['items']['current']['price_after_tax'];?></td>
                <td align="center"><?php if(User::can_edit(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current(array('cmd'=>'edit','ids'=>$this->map['items']['current']['id']));?>"><i class="fa fa-pencil w3-text-orange" style="font-size: 20px; padding-top 2px;"></i></a><?php }?></td>
                <td align="center"><?php if(User::can_delete(false,ANY_CATEGORY)){?><a class="delete-one-item" href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>$this->map['items']['current']['id']));?>"><i class="fa fa-times-circle w3-text-red" style="font-size: 20px; padding-top 2px;"></i></a><?php }?></td>
            </tr>
          <?php }}unset($this->map['items']['current']);} ?>			
        </table>
        
        <br />
        
		<div class="paging"><?php echo $this->map['paging'];?></div>
	</div>
    <input name="ids" id="ids" type="hidden" value="" />
	<input  name="cmd" value=""/ type ="hidden" value="<?php echo String::html_normalize(URL::get('cmd'));?>">
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
				
</div>
<script type="text/javascript">
  var arr_supplier= <?php echo String::array2js($this->map['suppliers']); ?>;//trung add arr nay de lay customer
    function Autocomplete()
    {
        jQuery("#supplier").autocomplete({
            url:'get_customer1.php?supplier=1',
            onItemSelect:function(item){
                jQuery("#supplier_id").val(arr_supplier[item.data]['supplier_id']);
            }
            
        });
    }
	jQuery("#delete_button").click(function (){
		ListCustomerForm.cmd.value = 'delete';
		ListCustomerForm.submit();
	});
	jQuery(".delete-one-item").click(function (){
		if(!confirm('<?php echo Portal::language('are_you_sure');?>')){
			return false;
		}
	});
	jQuery("#all_item_check_box").click(function (){
		var check  = this.checked;
		jQuery(".item-check-box").each(function(){
			this.checked = check;
		});
	});
      
</script>
