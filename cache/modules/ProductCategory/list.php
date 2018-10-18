<form name="ListCategoryForm" method="post" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>">
<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
	<tr>
		<td width="55%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><?php echo Portal::language('product_category');?></td>
		<?php 
		if(URL::get('cmd')=='delete'){?>
		<td width="45%" style="text-align: right; padding-right: 30px;"><a onclick="$('cmd').cmd='delete';ListCategoryForm.submit();"  class="w3-btn w3-red" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('Delete');?></a>
		<a href="<?php echo URL::build_current(Module::$current->redirect_parameters);?>"  class="w3-btn w3-green" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('back');?></a></td>
		<?php 
		}else{ 
		if(User::can_edit(false,ANY_CATEGORY)){?>
		<td width="45%" style="text-align: right; padding-right: 30px;"><a href="#"  class="w3-btn w3-orange w3-text-white" onclick="ListCategoryForm.submit();" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('Save_and_close');?></a>
		<?php }
		if(User::can_add(false,ANY_CATEGORY)){?>
		<a href="<?php echo URL::build_current(Module::$current->redirect_parameters+array('cmd'=>'add'));?>"  class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('Add');?></a>
		<?php }?>
		<?php if(User::can_delete(false,ANY_CATEGORY)){?>
		<a href="javascript:void(0)" onclick="if(!confirm('<?php echo Portal::language('are_you_sure');?>')){return false};ListCategoryForm.cmd.value='delete';ListCategoryForm.submit();"  class="w3-btn w3-red w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('Delete');?></a></td>
		<?php }
		}?>
	</tr>
</table>
	<a name="top_anchor"></a>		
	<table cellpadding="2" cellspacing="0" width="100%" border="1" bordercolor="<?php echo Portal::get_setting('crud_list_item_frame_color','#C3C3C3');?>">
		<thead>
			<tr class="w3-light-gray" style="height: 26px; text-transform: uppercase;">
				<th width="1%" title="<?php echo Portal::language('check_all');?>">
				<input type="checkbox" value="1" id="Category_all_checkbox" onclick="select_all_checkbox(this.form,'Category',this.checked,'<?php echo Portal::get_setting('crud_list_item_selected_bgcolor','#FFFFEC');?>','<?php echo Portal::get_setting('crud_item_bgcolor','white');?>');"<?php if(URL::get('cmd')=='delete') echo ' checked';?>></th />
				<th nowrap align="left">
				<a title="<?php echo Portal::language('sort');?>" href="<?php echo URL::build_current(((URL::get('order_by')=='category.name_'.Portal::language() and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'category.name_'.Portal::language()));?>" >
				<?php if(URL::get('order_by')=='category.name_'.Portal::language()) echo '<img alt="" src="'.Portal::template('core').'/images/buttons/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
				<?php echo Portal::language('name');?>					</a>				</th>
				<th nowrap align="left">
				<a href="<?php echo URL::build_current(((URL::get('order_by')=='category.type' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'category.type'));?>" title="<?php echo Portal::language('sort');?>">
				<?php if(URL::get('order_by')=='category.type') echo '<img alt="" src="'.Portal::template('core').'/images/buttons/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
				<?php echo Portal::language('code');?>					</a>
				</th>
				<?php if(User::can_edit(false,ANY_CATEGORY))
				{?>
                <th><?php echo Portal::language('position');?></th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<?php }?>
			</tr>
		</thead>
		<tbody>

			<?php $i=0;?>
			<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
			<?php $onclick = 'location=\''.URL::build_current().'&cmd=edit&id='.urlencode($this->map['items']['current']['id']).'\';"';?>
			<tr valign="middle" style="cursor:pointer;" id="Category_tr_<?php echo $this->map['items']['current']['id'];?>" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
				<td><?php 
				if(($this->map['items']['current']['structure_id']!=ID_ROOT))
				{?>
                <?php if (($this->map['items']['current']['code'] != 'PITCH') && ($this->map['items']['current']['code'] != 'HH') && ($this->map['items']['current']['code'] != 'DA') && ($this->map['items']['current']['code'] != 'DU') && ($this->map['items']['current']['code'] != 'DVNH') && ($this->map['items']['current']['code'] != 'GL') && ($this->map['items']['current']['code'] != 'LD') && ($this->map['items']['current']['code'] != 'GK') && ($this->map['items']['current']['code'] != 'CL')){?>
                <input name="selected_ids[]" type="checkbox" value="<?php echo $this->map['items']['current']['id'];?>" onclick="select_checkbox(this.form,'Category',this,'<?php echo Portal::get_setting('crud_list_item_selected_bgcolor','#FFFFEC');?>','<?php echo Portal::get_setting('crud_item_bgcolor','white');?>');" id="Category_checkbox" <?php if(URL::get('cmd')=='delete') echo 'checked';?>/>
                <?php }?>
                
				<?php
				}
				?>
                </td>
				<td nowrap align="left" onclick="location='<?php echo Url::build_current().'&cmd=edit&id='.$this->map['items']['current']['id'];?>'">
				<?php echo $this->map['items']['current']['indent'];?>
				<?php echo $this->map['items']['current']['indent_image'];?>
				<span class="page_indent">&nbsp;</span>
				<?php echo $this->map['items']['current']['name'];?></td>
				<td nowrap align="left" <?php echo $onclick;?>>
				<?php echo $this->map['items']['current']['code'];?>
				</td>
                <td><?php 
				if(($this->map['items']['current']['structure_id']!=1000000000000000000))
				{?><input  name="position_<?php echo $this->map['items']['current']['id'];?>" id="position_<?php echo $this->map['items']['current']['id'];?>" value="" class="input_number" style="width:60px;" / type ="text" value="<?php echo String::html_normalize(URL::get('position_'.$this->map['items']['current']['id']));?>">
				<?php
				}
				?></td>
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
			<a href="javascript:void(0)" onclick="select_all_checkbox(document.ListCategoryForm,'Category',true,'<?php echo Portal::get_setting('crud_list_item_selected_bgcolor','#FFFFEC');?>','<?php echo Portal::get_setting('crud_item_bgcolor','white');?>');"><?php echo Portal::language('select_all');?></a>&nbsp;
			<a href="javascript:void(0)" onclick="select_all_checkbox(document.ListCategoryForm,'Category',false,'<?php echo Portal::get_setting('crud_list_item_selected_bgcolor','#FFFFEC');?>','<?php echo Portal::get_setting('crud_item_bgcolor','white');?>');"><?php echo Portal::language('select_none');?></a>
			<a href="javascript:void(0)" onclick="select_all_checkbox(document.ListCategoryForm,'Category',-1,'<?php echo Portal::get_setting('crud_list_item_selected_bgcolor','#FFFFEC');?>','<?php echo Portal::get_setting('crud_item_bgcolor','white');?>');"><?php echo Portal::language('select_invert');?></a>
			</td>
			<td>
			<a name="bottom_anchor" href="#top_anchor"><img alt="" src="<?php echo Portal::template('core');?>/images/top.gif" title="<?php echo Portal::language('top');?>" border="0" alt="<?php echo Portal::language('top');?>"></a>
			</td>
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
			
			
