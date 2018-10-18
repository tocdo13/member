<?php System::set_page_title(HOTEL_NAME);?>
<div class="customer-type-pc_supplier-bound">
<form name="ListSupplierForm" method="post">
	<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr height="40">
        	<td width="65%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-folder-open w3-text-orange" style="font-size: 26px;"></i> <?php echo Portal::language('supplier_list');?></td>
            <td width="35%" align="right" nowrap="nowrap" style="padding-right: 30px;">
                <?php if(User::can_add(false,ANY_CATEGORY)){?><a href="<?php echo URL::build_current(array('cmd'=>'import'));?>"  class="w3-btn w3-lime w3-text-white" style="text-transform: uppercase; margin-right: 5px; text-decoration: none;"><?php echo Portal::language('import_from_excel');?></a><?php }?>
                <a href="<?php echo URL::build_current(array('cmd'=>'add','group_id','action'));?>"  class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; margin-right: 5px; text-decoration: none;"><?php echo Portal::language('Add');?></a>
				<a href="javascript:void(0)" onclick=" get_id_select();if(!confirm('<?php echo Portal::language('are_you_sure');?>')){return false};ListSupplierForm.cmd.value='delete_group';ListSupplierForm.submit();"  class="w3-btn w3-red" style="text-transform: uppercase; text-decoration: none;"><?php echo Portal::language('Delete');?></a>
            </td>
        </tr>
    </table>        
	
    <div class="content">
		<fieldset>
			<legend class="title"><?php echo Portal::language('search');?></legend>
			<?php echo Portal::language('supplier_code');?>: <input  name="pc_supplier_code" id="pc_supplier_code" style="height: 24px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('pc_supplier_code'));?>">
            <?php echo Portal::language('supplier_name');?>: <input  name="pc_supplier_name" id="pc_supplier_name" style="height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('pc_supplier_name'));?>">
            <input name="search" type="submit" value="<?php echo Portal::language('search');?>" style="height: 24px;"/>
		</fieldset>
        
        <br />
        
        <table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#D9ECFF" rules="cols">
            <tr class="w3-light-gray w3-border" style="height: 24px; text-transform: uppercase;">
                <th width="10"><input type="checkbox" id="all_item_check_box"/></th>
                <th width="30"><?php echo Portal::language('order_number');?></th>
                <th width="100" align="left"><?php echo Portal::language('code');?></th>
                <th width="250" align="left"><?php echo Portal::language('name');?></th>
                <th width="300" align="left"><?php echo Portal::language('address');?></th>
                <th width="200" align="left"><?php echo Portal::language('contact_person_info');?></th>
                <th width="1%">&nbsp;</th>
                <th width="1%">&nbsp;</th>
            </tr>
            <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
            <tr <?php echo $this->map['items']['current']['i']%2==0?'class="row-even"':'class="row-odd"'?>>
                <?php if($this->map['items']['current']['pc_order_id']==''){ ?>
                <td valign="top">
                    <input name="item_check_box[]" type="checkbox" class="item-check-box" id="<?php echo $this->map['items']['current']['id'];?>" value="<?php echo $this->map['items']['current']['id'];?>"/>
                </td>
                <?php }else{ ?>
                <td></td>
                <?php } ?>
                <td valign="top"><?php echo $this->map['items']['current']['i'];?></td>
                <td valign="top"><span id="code_<?php echo $this->map['items']['current']['id'];?>"><?php echo $this->map['items']['current']['code'];?></span></td>
                <td valign="top"><span id="name_<?php echo $this->map['items']['current']['id'];?>"><strong><?php echo $this->map['items']['current']['name'];?></strong></span></td>
                <td valign="top"><span id="address_<?php echo $this->map['items']['current']['id'];?>"><?php echo $this->map['items']['current']['address'];?></span></td>
                <td>
                &loz; <span class="note"><?php echo Portal::language('contact_person_name');?>:</span> <?php echo $this->map['items']['current']['contact_person_name'];?><br />
                &loz; <span class="note"><?php echo Portal::language('contact_person_phone');?>:</span> <?php echo $this->map['items']['current']['contact_person_phone'];?><br />
                &loz; <span class="note"><?php echo Portal::language('contact_person_mobile');?>:</span> <?php echo $this->map['items']['current']['contact_person_mobile'];?><br />
                &loz; <span class="note"><?php echo Portal::language('contact_person_email');?>:</span> <?php echo $this->map['items']['current']['contact_person_email'];?>                                      
                </td>
                <td valign="top"><?php if(User::can_edit(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current(array('cmd'=>'edit','id'=>$this->map['items']['current']['id']));?>"><i class="fa fa-pencil w3-text-orange" style="font-size: 20px; padding-top: 2px;"></i></a><?php }?></td>
                <?php if($this->map['items']['current']['pc_order_id']==''){ ?>
                <td valign="top"><?php if(User::can_delete(false,ANY_CATEGORY)){?><a class="delete-one-item" href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>$this->map['items']['current']['id']));?>"><i class="fa fa-times-circle w3-text-red" style="font-size: 20px; padding-top: 2px;"></i></a><?php }?></td>
                <?php }else{ ?>
                <td></td>
                <?php } ?>
            </tr>
          <?php }}unset($this->map['items']['current']);} ?>			
        </table>
        
        <br />
        
		<div class="paging"><?php echo $this->map['paging'];?></div>
	</div>
	<input  name="cmd" value=""/ type ="hidden" value="<?php echo String::html_normalize(URL::get('cmd'));?>">
    <input id="id_select" name="id_select" type="hidden" value=""/>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
				
</div>
<script type="text/javascript">
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