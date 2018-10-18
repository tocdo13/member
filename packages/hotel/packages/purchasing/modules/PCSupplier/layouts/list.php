<?php System::set_page_title(HOTEL_NAME);?>
<div class="customer-type-pc_supplier-bound">
<form name="ListSupplierForm" method="post">
	<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr height="40">
        	<td width="65%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-folder-open w3-text-orange" style="font-size: 26px;"></i> [[.supplier_list.]]</td>
            <td width="35%" align="right" nowrap="nowrap" style="padding-right: 30px;">
                <?php if(User::can_add(false,ANY_CATEGORY)){?><a href="<?php echo URL::build_current(array('cmd'=>'import'));?>"  class="w3-btn w3-lime w3-text-white" style="text-transform: uppercase; margin-right: 5px; text-decoration: none;">[[.import_from_excel.]]</a><?php }?>
                <a href="<?php echo URL::build_current(array('cmd'=>'add','group_id','action'));?>"  class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; margin-right: 5px; text-decoration: none;">[[.Add.]]</a>
				<a href="javascript:void(0)" onclick=" get_id_select();if(!confirm('[[.are_you_sure.]]')){return false};ListSupplierForm.cmd.value='delete_group';ListSupplierForm.submit();"  class="w3-btn w3-red" style="text-transform: uppercase; text-decoration: none;">[[.Delete.]]</a>
            </td>
        </tr>
    </table>        
	
    <div class="content">
		<fieldset>
			<legend class="title">[[.search.]]</legend>
			[[.supplier_code.]]: <input name="pc_supplier_code" type="text" id="pc_supplier_code" style="height: 24px;"/>
            [[.supplier_name.]]: <input name="pc_supplier_name" type="text" id="pc_supplier_name" style="height: 24px;" />
            <input name="search" type="submit" value="[[.search.]]" style="height: 24px;"/>
		</fieldset>
        
        <br />
        
        <table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#D9ECFF" rules="cols">
            <tr class="w3-light-gray w3-border" style="height: 24px; text-transform: uppercase;">
                <th width="10"><input type="checkbox" id="all_item_check_box"/></th>
                <th width="30">[[.order_number.]]</th>
                <th width="100" align="left">[[.code.]]</th>
                <th width="250" align="left">[[.name.]]</th>
                <th width="300" align="left">[[.address.]]</th>
                <th width="200" align="left">[[.contact_person_info.]]</th>
                <th width="1%">&nbsp;</th>
                <th width="1%">&nbsp;</th>
            </tr>
            <!--LIST:items-->
            <tr <?php echo [[=items.i=]]%2==0?'class="row-even"':'class="row-odd"'?>>
                <?php if([[=items.pc_order_id=]]==''){ ?>
                <td valign="top">
                    <input name="item_check_box[]" type="checkbox" class="item-check-box" id="[[|items.id|]]" value="[[|items.id|]]"/>
                </td>
                <?php }else{ ?>
                <td></td>
                <?php } ?>
                <td valign="top">[[|items.i|]]</td>
                <td valign="top"><span id="code_[[|items.id|]]">[[|items.code|]]</span></td>
                <td valign="top"><span id="name_[[|items.id|]]"><strong>[[|items.name|]]</strong></span></td>
                <td valign="top"><span id="address_[[|items.id|]]">[[|items.address|]]</span></td>
                <td>
                &loz; <span class="note">[[.contact_person_name.]]:</span> [[|items.contact_person_name|]]<br />
                &loz; <span class="note">[[.contact_person_phone.]]:</span> [[|items.contact_person_phone|]]<br />
                &loz; <span class="note">[[.contact_person_mobile.]]:</span> [[|items.contact_person_mobile|]]<br />
                &loz; <span class="note">[[.contact_person_email.]]:</span> [[|items.contact_person_email|]]                                      
                </td>
                <td valign="top"><?php if(User::can_edit(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current(array('cmd'=>'edit','id'=>[[=items.id=]]));?>"><i class="fa fa-pencil w3-text-orange" style="font-size: 20px; padding-top: 2px;"></i></a><?php }?></td>
                <?php if([[=items.pc_order_id=]]==''){ ?>
                <td valign="top"><?php if(User::can_delete(false,ANY_CATEGORY)){?><a class="delete-one-item" href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>[[=items.id=]]));?>"><i class="fa fa-times-circle w3-text-red" style="font-size: 20px; padding-top: 2px;"></i></a><?php }?></td>
                <?php }else{ ?>
                <td></td>
                <?php } ?>
            </tr>
          <!--/LIST:items-->			
        </table>
        
        <br />
        
		<div class="paging">[[|paging|]]</div>
	</div>
	<input name="cmd" type="hidden" value=""/>
    <input id="id_select" name="id_select" type="hidden" value=""/>
</form>	
</div>
<script type="text/javascript">
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
	//OANH ADD
    function get_id_select()
    {
        var inputs = jQuery('input:checkbox:checked');
        var strids = "0";
        for (var i=0;i<inputs.length;i++)
        { 
            if(inputs[i].id!='all_item_check_box'){
                   strids +=","+inputs[i].id;                
            }
            
        }
        jQuery('#id_select').val(strids);
        
    }
    //END oANH
</script>