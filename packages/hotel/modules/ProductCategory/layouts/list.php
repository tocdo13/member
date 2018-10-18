<form name="ListCategoryForm" method="post" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>">
<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
	<tr>
		<td width="55%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;">[[.product_category.]]</td>
		<?php 
		if(URL::get('cmd')=='delete'){?>
		<td width="45%" style="text-align: right; padding-right: 30px;"><a onclick="$('cmd').cmd='delete';ListCategoryForm.submit();"  class="w3-btn w3-red" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.Delete.]]</a>
		<a href="<?php echo URL::build_current(Module::$current->redirect_parameters);?>"  class="w3-btn w3-green" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.back.]]</a></td>
		<?php 
		}else{ 
		if(User::can_edit(false,ANY_CATEGORY)){?>
		<td width="45%" style="text-align: right; padding-right: 30px;"><a href="#"  class="w3-btn w3-orange w3-text-white" onclick="ListCategoryForm.submit();" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.Save_and_close.]]</a>
		<?php }
		if(User::can_add(false,ANY_CATEGORY)){?>
		<a href="<?php echo URL::build_current(Module::$current->redirect_parameters+array('cmd'=>'add'));?>"  class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.Add.]]</a>
		<?php }?>
		<?php if(User::can_delete(false,ANY_CATEGORY)){?>
		<a href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};ListCategoryForm.cmd.value='delete';ListCategoryForm.submit();"  class="w3-btn w3-red w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.Delete.]]</a></td>
		<?php }
		}?>
	</tr>
</table>
	<a name="top_anchor"></a>		
	<table cellpadding="2" cellspacing="0" width="100%" border="1" bordercolor="<?php echo Portal::get_setting('crud_list_item_frame_color','#C3C3C3');?>">
		<thead>
			<tr class="w3-light-gray" style="height: 26px; text-transform: uppercase;">
				<th width="1%" title="[[.check_all.]]">
				<input type="checkbox" value="1" id="Category_all_checkbox" onclick="select_all_checkbox(this.form,'Category',this.checked,'<?php echo Portal::get_setting('crud_list_item_selected_bgcolor','#FFFFEC');?>','<?php echo Portal::get_setting('crud_item_bgcolor','white');?>');"<?php if(URL::get('cmd')=='delete') echo ' checked';?>></th />
				<th nowrap align="left">
				<a title="[[.sort.]]" href="<?php echo URL::build_current(((URL::get('order_by')=='category.name_'.Portal::language() and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'category.name_'.Portal::language()));?>" >
				<?php if(URL::get('order_by')=='category.name_'.Portal::language()) echo '<img alt="" src="'.Portal::template('core').'/images/buttons/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
				[[.name.]]					</a>				</th>
				<th nowrap align="left">
				<a href="<?php echo URL::build_current(((URL::get('order_by')=='category.type' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'category.type'));?>" title="[[.sort.]]">
				<?php if(URL::get('order_by')=='category.type') echo '<img alt="" src="'.Portal::template('core').'/images/buttons/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
				[[.code.]]					</a>
				</th>
				<?php if(User::can_edit(false,ANY_CATEGORY))
				{?>
                <th>[[.position.]]</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<?php }?>
			</tr>
		</thead>
		<tbody>

			<?php $i=0;?>
			<!--LIST:items-->
			<?php $onclick = 'location=\''.URL::build_current().'&cmd=edit&id='.urlencode([[=items.id=]]).'\';"';?>
			<tr valign="middle" style="cursor:pointer;" id="Category_tr_[[|items.id|]]" onmouseover="this.bgColor='#FFCCCC'" onmouseout="this.bgColor='#FFFFFF'">
				<td><!--IF:cond([[=items.structure_id=]]!=ID_ROOT)-->
                <?php if (([[=items.code=]] != 'PITCH') && ([[=items.code=]] != 'HH') && ([[=items.code=]] != 'DA') && ([[=items.code=]] != 'DU') && ([[=items.code=]] != 'DVNH') && ([[=items.code=]] != 'GL') && ([[=items.code=]] != 'LD') && ([[=items.code=]] != 'GK') && ([[=items.code=]] != 'CL')){?>
                <input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="select_checkbox(this.form,'Category',this,'<?php echo Portal::get_setting('crud_list_item_selected_bgcolor','#FFFFEC');?>','<?php echo Portal::get_setting('crud_item_bgcolor','white');?>');" id="Category_checkbox" <?php if(URL::get('cmd')=='delete') echo 'checked';?>/>
                <?php }?>
                <!--/IF:cond-->
                </td>
				<td nowrap align="left" onclick="location='<?php echo Url::build_current().'&cmd=edit&id='.[[=items.id=]];?>'">
				[[|items.indent|]]
				[[|items.indent_image|]]
				<span class="page_indent">&nbsp;</span>
				[[|items.name|]]</td>
				<td nowrap align="left" <?php echo $onclick;?>>
				[[|items.code|]]
				</td>
                <td><!--IF:cond([[=items.structure_id=]]!=1000000000000000000)--><input name="position_[[|items.id|]]" type="text" id="position_[[|items.id|]]" value="" class="input_number" style="width:60px;" /><!--/IF:cond--></td>
				<td width="24px" align="center">[[|items.move_up|]]</td>
				<td width="24px" align="center">[[|items.move_down|]]</td>
			</tr>
			<!--/LIST:items-->
         
		</tbody>
	</table>
	<table width="100%">
		<tr>
			<td width="100%">
			[[.select.]]:&nbsp;
			<a href="javascript:void(0)" onclick="select_all_checkbox(document.ListCategoryForm,'Category',true,'<?php echo Portal::get_setting('crud_list_item_selected_bgcolor','#FFFFEC');?>','<?php echo Portal::get_setting('crud_item_bgcolor','white');?>');">[[.select_all.]]</a>&nbsp;
			<a href="javascript:void(0)" onclick="select_all_checkbox(document.ListCategoryForm,'Category',false,'<?php echo Portal::get_setting('crud_list_item_selected_bgcolor','#FFFFEC');?>','<?php echo Portal::get_setting('crud_item_bgcolor','white');?>');">[[.select_none.]]</a>
			<a href="javascript:void(0)" onclick="select_all_checkbox(document.ListCategoryForm,'Category',-1,'<?php echo Portal::get_setting('crud_list_item_selected_bgcolor','#FFFFEC');?>','<?php echo Portal::get_setting('crud_item_bgcolor','white');?>');">[[.select_invert.]]</a>
			</td>
			<td>
			<a name="bottom_anchor" href="#top_anchor"><img alt="" src="<?php echo Portal::template('core');?>/images/top.gif" title="[[.top.]]" border="0" alt="[[.top.]]"></a>
			</td>
		</tr>
	</table>		
	<input type="hidden" name="cmd" value="" id="cmd"/>
	<!--IF:delete(URL::get('cmd')=='delete')-->
	<input type="hidden" name="confirm" value="1" />
	<!--/IF:delete-->
</form>
