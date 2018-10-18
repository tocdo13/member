<?php System::set_page_title(HOTEL_NAME);?>
<div class="ExtraServiceAdmin-type-supplier-bound">
<form name="ListExtraServiceAdminForm" method="post">
	<table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr height="40">
        	<td width="70%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><?php echo $this->map['title'];?></td>
            <?php if(User::can_add(false,ANY_CATEGORY)){?><td width="30%" align="right" style="padding-right: 30px;"><a href="<?php echo URL::build_current(array('cmd'=>'add'));?>"  class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('Add');?></a><?php }?>
			<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0)" onclick="if(!confirm('<?php echo Portal::language('are_you_sure');?>')){return false};ListExtraServiceAdminForm.cmd.value='delete';ListExtraServiceAdminForm.submit();"  class="w3-btn w3-red w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('Delete');?></a></td><?php }?>
        </tr>
    </table>        
	<div class="content">
		<fieldset>
			<legend class="title"><?php echo Portal::language('search');?></legend>
			<?php echo Portal::language('input_keyword');?>: <input  name="keyword" id="keyword" / type ="text" value="<?php echo String::html_normalize(URL::get('keyword'));?>"><input  name="search" value="OK" / type ="submit" value="<?php echo String::html_normalize(URL::get('search'));?>">
             <span style="color: red;"><?php echo Portal::language('service_is_used_then_disable_button_delete');?></span>
		</fieldset><br />
		<table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC">
			<tr class="w3-light-gray" style="height: 24px; text-transform: uppercase;">
			  <th width="10"><input type="checkbox" id="all_item_check_box"/></th>
			  <th width="30"><?php echo Portal::language('order_number');?></th>
              <th width="150" align="left"><?php echo Portal::language('code');?></th>
              <th width="300" align="left"><?php echo Portal::language('name');?></th>
			  <th width="150" align="center"><?php echo Portal::language('price');?></th>
			  <th width="100" align="center"><?php echo Portal::language('unit');?></th>
              <th width="150" align="center"><?php echo Portal::language('type');?></th>
              <th width="150" align="center"><?php echo Portal::language('status');?></th>
			  <th width="50" align="center"><?php echo Portal::language('edit');?></th>
		      <th width="50" align="center"><?php echo Portal::language('delete');?></th>
		  </tr>
		  <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
			<tr class="<?php echo $this->map['items']['current']['row_class'];?>">
			  <td><?php 
				if((($this->map['items']['current']['code']!='EXTRA_BED'  and $this->map['items']['current']['code']!='BABY_COT' and $this->map['items']['current']['code']!='EARLY_CHECKIN' and $this->map['items']['current']['code']!='LATE_CHECKOUT' and $this->map['items']['current']['code']!='LATE_CHECKIN'  and $this->map['items']['current']['code']!='VFD' and $this->map['items']['current']['can_delete']==0)))
				{?>
              <input name="item_check_box[]" type="checkbox" class="item-check-box" value="<?php echo $this->map['items']['current']['id'];?>">
              
				<?php
				}
				?>
              </td>
			  <td><?php echo $this->map['items']['current']['i'];?></td>
				<td><?php echo $this->map['items']['current']['code'];?></td>
				<td><?php echo $this->map['items']['current']['name'];?></td>
			    <td align="right"><?php echo System::display_number($this->map['items']['current']['price']);?></td>
			    <td align="center"><?php echo $this->map['items']['current']['unit'];?></td>
                  <td align="center"><?php echo $this->map['items']['current']['type'];?></td>
                <td align="center"><?php echo $this->map['items']['current']['status'];?></td>
		       <td align="center"><a href="<?php echo Url::build_current(array('cmd'=>'edit','id'=>$this->map['items']['current']['id']));?>">
                        <img src="packages/core/skins/default/images/buttons/edit.gif" />
                    </a>
               </td>
			    <td align="center">
                <?php 
				if((($this->map['items']['current']['code']!='EXTRA_BED' and $this->map['items']['current']['code']!='BABY_COT' and $this->map['items']['current']['code']!='BABY_COT' and $this->map['items']['current']['code']!='EARLY_CHECKIN' and $this->map['items']['current']['code']!='LATE_CHECKOUT' and $this->map['items']['current']['code']!='LATE_CHECKIN' and $this->map['items']['current']['code']!='VFD')))
				{?>
                <?php if(User::can_delete(false,ANY_CATEGORY) and $this->map['items']['current']['can_delete']==0){?>
                    <a class="delete-one-item" href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>$this->map['items']['current']['id']));?>">
                        <img src="packages/core/skins/default/images/buttons/delete.gif">
                    </a>
                <?php }?>
                
				<?php
				}
				?>
                </td>
			</tr>
		  <?php }}unset($this->map['items']['current']);} ?>			
		</table>
  <br />
		<div class="paging"><?php echo $this->map['paging'];?></div>
	</div>
	<input  name="cmd" value="" type ="hidden" value="<?php echo String::html_normalize(URL::get('cmd'));?>">
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
				
</div>
<script type="text/javascript">
	jQuery("#delete_button").click(function (){
		ListExtraServiceAdminForm.cmd.value = 'delete';
		ListExtraServiceAdminForm.submit();
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
