<?php 
//$title = Portal::language('warehouse_list');
System::set_page_title(HOTEL_NAME);?>
<div>
<form method="post" name="SearchWarehouseForm" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>">
<table cellpadding="15" cellspacing="0" width="60%" border="0" bordercolor="#CCCCCC" class="table-bound">
    <tr>
        <td width="60%"  class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> <?php echo Portal::language('warehouse_list');?></td>
        <td width="40%" align="right" nowrap="nowrap" style="padding-right: 30px;">
        <?php if(User::can_add(false,ANY_CATEGORY)){?>
            <a href="<?php echo URL::build_current(array('cmd'=>'add'));?>"  class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('Add');?></a>
        <?php }?>
        <?php if(User::can_delete(false,ANY_CATEGORY)){?>
            <a href="javascript:void(0)" onclick="if(!confirm('<?php echo Portal::language('are_you_sure');?>')){return false};ListWarehouseForm.cmd.value='delete';ListWarehouseForm.submit();"  class="w3-btn w3-red" style="text-transform: uppercase; text-decoration: none;"><?php echo Portal::language('Delete');?></a>
        <?php }?>
        </td>
    </tr>
</table>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
	
<form name="ListWarehouseForm" method="post" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>">
    <table cellpadding="2" cellspacing="0" width="60%" border="1" bordercolor="<?php echo Portal::get_setting('crud_list_item_frame_color','#C3C3C3');?>">
        <thead>
            <tr class="w3-light-gray" style="height: 24px; text-transform: uppercase;">
				<th width="1%" title="<?php echo Portal::language('check_all');?>">
				    <input type="checkbox" value="1" id="Warehouse_all_checkbox" onclick="select_all_checkbox(this.form,'Warehouse',this.checked,'<?php echo Portal::get_setting('crud_list_item_selected_bgcolor','#FFFFEC');?>','<?php echo Portal::get_setting('crud_item_bgcolor','white');?>');"<?php if(URL::get('cmd')=='delete') echo 'checked';?>/>
                </th>
				<th nowrap align="left">
    				<a title="<?php echo Portal::language('sort');?>" href="<?php echo URL::build_current(((URL::get('order_by')=='warehouse.name_'.Portal::language() and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'warehouse.name_'.Portal::language()));?>" >
    				<?php if(URL::get('order_by')=='warehouse.name_'.Portal::language()) echo '<img alt="" src="'.Portal::template('core').'/images/buttons/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
    				<?php echo Portal::language('name');?></a>				
                </th>
                <?php if(User::can_edit(false,ANY_CATEGORY)){?>
                <th  width="200" nowrap="nowrap"><?php echo Portal::language('code');?></th>
                <th  width="200" nowrap="nowrap"><?php echo Portal::language('privilege');?></th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<?php }?>
			</tr>
		</thead>
		
        <tbody>
			<?php $i=1;?>
			<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
			<?php $onclick = 'location=\''.URL::build_current().'&cmd=edit&id='.urlencode($this->map['items']['current']['id']).'\';"';?>
			<tr <?php echo ($i%2==0)?'':'bgcolor="#FFFFCC"';$i++;?> valign="middle" style="cursor:pointer;" id="Warehouse_tr_<?php echo $this->map['items']['current']['id'];?>">
				<td>
                    <?php 
				if(($this->map['items']['current']['structure_id']!=ID_ROOT and $this->map['items']['current']['code']<>'REST' and $this->map['items']['current']['code']<>'HSKP' and $this->map['items']['current']['code']<>'MASSA' and $this->map['items']['current']['code']<>'REPT'))
				{?>
                    <input name="selected_ids[]" type="checkbox" value="<?php echo $this->map['items']['current']['id'];?>" onclick="select_checkbox(this.form,'Warehouse',this,'<?php echo Portal::get_setting('crud_list_item_selected_bgcolor','#FFFFEC');?>','<?php echo Portal::get_setting('crud_item_bgcolor','white');?>');" id="Warehouse_checkbox" <?php if(URL::get('cmd')=='delete') echo 'checked';?>/>
                    
				<?php
				}
				?>
                </td>
				<td nowrap align="left" onclick="window.location='<?php echo Url::build_current().'&cmd=edit&id='.$this->map['items']['current']['id'];?>'">
    				<?php echo $this->map['items']['current']['indent'];?>
    				<?php echo $this->map['items']['current']['indent_image'];?>
    				<span class="page_indent">&nbsp;</span>
    				<?php echo $this->map['items']['current']['name'];?>
                </td>
                <td><?php echo $this->map['items']['current']['code'];?></td>
                <td><?php echo $this->map['items']['current']['module_name'];?></td>
				<td width="24px" align="center"><?php echo $this->map['items']['current']['move_up'];?></td>
				<td width="24px" align="center"><?php echo $this->map['items']['current']['move_down'];?></td>
			</tr>
			<?php }}unset($this->map['items']['current']);} ?>
		</tbody>
	</table>
    
	<table width="100%">
		<tr>
			<td width="100%">
    			<?php echo Portal::language('select');?>:&nbsp;
    			<a href="javascript:void(0)" onclick="select_all_checkbox(document.ListWarehouseForm,'Warehouse',true,'<?php echo Portal::get_setting('crud_list_item_selected_bgcolor','#FFFFEC');?>','<?php echo Portal::get_setting('crud_item_bgcolor','white');?>');"><?php echo Portal::language('select_all');?></a>&nbsp;
    			<a href="javascript:void(0)" onclick="select_all_checkbox(document.ListWarehouseForm,'Warehouse',false,'<?php echo Portal::get_setting('crud_list_item_selected_bgcolor','#FFFFEC');?>','<?php echo Portal::get_setting('crud_item_bgcolor','white');?>');"><?php echo Portal::language('select_none');?></a>
    			<a href="javascript:void(0)" onclick="select_all_checkbox(document.ListWarehouseForm,'Warehouse',-1,'<?php echo Portal::get_setting('crud_list_item_selected_bgcolor','#FFFFEC');?>','<?php echo Portal::get_setting('crud_item_bgcolor','white');?>');"><?php echo Portal::language('select_invert');?></a>
			</td>
			<td>&nbsp;</td>
		</tr>
	</table>		
	<input type="hidden" name="cmd" value="" id="cmd"/>
	<?php 
				if((URL::get('cmd')=='delete'))
				{?>
	<input type="hidden" name="confirm" value="1" />
	
				<?php
				}
				?>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
</div>
