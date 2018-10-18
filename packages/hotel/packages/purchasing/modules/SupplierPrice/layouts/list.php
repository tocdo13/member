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
        	<td width="60%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> [[.quotation_from_supplier.]]</td>
            <td width="40%" align="right" style="padding-right: 30px;">           
                <?php if(User::can_add(false,ANY_CATEGORY)){?><a href="<?php echo URL::build_current(array('cmd'=>'import'));?>"  class="w3-btn w3-lime" style="text-transform: uppercase; margin-right: 5px; text-decoration: none;">[[.import_from_excel.]]</a><?php }?>
                <a href="<?php echo URL::build_current(array('cmd'=>'add'));?>"  class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; margin-right: 5px; text-decoration: none;">[[.Add.]]</a>
				<input name="submit_edit" type="submit" id="submit_edit" value="[[.edit.]]" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; margin-right: 5px;"/>
                <a href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};ListSupplierForm.cmd.value='delete_group';ListSupplierForm.submit();"  class="w3-btn w3-red" style="text-transform: uppercase; text-decoration: none;">[[.Delete.]]</a>
                <!--<input type="button" onclick="openw('add');" value="[[.add.]]" class="button-medium-add"  />
                <input type="button" value="[[.edit.]]" onclick="openw('edit');"  class="button-medium-edit" />
                <input type="button" value="[[.delete.]]" onclick="openw('delete');"  class="button-medium-delete" />-->
            </td>
        </tr>
    </table>        
	
    <div class="content"  >
        <table style="width: 85%; margin: 0 auto;">
        <tr><td>
		<fieldset>
			<legend class="title">[[.search.]]</legend>
            <table width="100%"  style="text-align: center; left: 30%;">
                <tr>
                <td >
            [[.code_product.]]:
            <input name="code_product" type="text" id="code_product" style="width: 150px; height: 24px;" />
            [[.product_name11.]]:
            <input name="product_name" type="text" id="product_name" style="width: 150px;height: 24px;" />
			[[.supplier.]]:
           <!-- <select name="supplier" id="supplier" style="width: 150px;">
            [[|option_suppliers|]]
            </select> -->
            <input name="supplier" type="text" id="supplier" onfocus="Autocomplete();" style="height: 24px;" />
            <input name="supplier_id" type="text" id="supplier_id" style="display: none;"  />
            <input name="search" type="submit" value="[[.search.]]" style="height: 24px;" />
                </td></tr>
            </table>
		</fieldset>
        </td></tr>
        </table>
        <br />
        
        <table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#D9ECFF" rules="cols">
            <tr class="w3-light-gray w3-border" style="height: 24px; text-transform: uppercase;">
                <th width="10"><input type="checkbox" id="all_item_check_box"/></th>
                <th width="30">[[.order_number.]]</th>
                <th width="100" align="center">[[.code_product.]]</th>
                <th width="300" align="center">[[.name_product.]]</th>
                <!-- Oanh add -->
                <th width="80" align="center">[[.unit.]]</th>
                <!-- End oanh -->
                <th width="300" align="center">[[.supplier.]]</th>
                <th width="80" align="center">[[.from_date.]]</th>
                <th width="80" align="center">[[.to_date.]]</th>
                <th width="80" align="center">[[.price.]]</th>
                <th width="80" align="center">[[.tax.]]</th>
                <th width="100" align="center">[[.price_after_tax.]]</th>
                <th width="40" align="center">[[.edit.]]</th>
                <th width="40" align="center">[[.delete.]]</th>
            </tr>
            <!--LIST:items-->
            <tr <?php echo [[=items.index=]]%2==0?' style="background-color: #D4F8FA;"':''?>>
                <td align="center">
                    <input name="item_check_box[]" type="checkbox" class="item-check-box" value="[[|items.id|]]"/>
                </td>
                <td align="center">[[|items.index|]]</td>
                <td align="left">[[|items.product_id|]]</td>
                <td>[[|items.product_name|]]</td>
                <!-- Oanh add -->
                <td>[[|items.unit_name|]]</td>
                <!-- End oanh -->
                <td>[[|items.supplier_name|]]</td>
                <td align="center">[[|items.starting_date|]]</td>
                <td align="center">[[|items.ending_date|]]</td>
                <td align="right">[[|items.price|]]</td>
                <td align="right">[[|items.tax|]]</td>
                <td align="right">[[|items.price_after_tax|]]</td>
                <td align="center"><?php if(User::can_edit(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current(array('cmd'=>'edit','ids'=>[[=items.id=]]));?>"><i class="fa fa-pencil w3-text-orange" style="font-size: 20px; padding-top 2px;"></i></a><?php }?></td>
                <td align="center"><?php if(User::can_delete(false,ANY_CATEGORY)){?><a class="delete-one-item" href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>[[=items.id=]]));?>"><i class="fa fa-times-circle w3-text-red" style="font-size: 20px; padding-top 2px;"></i></a><?php }?></td>
            </tr>
          <!--/LIST:items-->			
        </table>
        
        <br />
        
		<div class="paging">[[|paging|]]</div>
	</div>
    <input name="ids" id="ids" type="hidden" value="" />
	<input name="cmd" type="hidden" value=""/>
</form>	
</div>
<script type="text/javascript">
  var arr_supplier= <?php echo String::array2js([[=suppliers=]]); ?>;//trung add arr nay de lay customer
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
		if(!confirm('[[.are_you_sure.]]')){
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
