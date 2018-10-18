<?php 
//$title = Portal::language('warehouse_list');
System::set_page_title(HOTEL_NAME);?>
<div>
<form method="post" name="SearchWarehouseForm" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>">
<table cellpadding="15" cellspacing="0" width="60%" border="0" bordercolor="#CCCCCC" class="table-bound">
    <tr>
        <td width="60%"  class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> [[.warehouse_list.]]</td>
        <td width="40%" align="right" nowrap="nowrap" style="padding-right: 30px;">
        <?php if(User::can_add(false,ANY_CATEGORY)){?>
            <a href="<?php echo URL::build_current(array('cmd'=>'add'));?>"  class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.Add.]]</a>
        <?php }?>
        <?php if(User::can_delete(false,ANY_CATEGORY)){?>
            <a href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};ListWarehouseForm.cmd.value='delete';ListWarehouseForm.submit();"  class="w3-btn w3-red" style="text-transform: uppercase; text-decoration: none;">[[.Delete.]]</a>
        <?php }?>
        </td>
    </tr>
</table>
</form>
	
<form name="ListWarehouseForm" method="post" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>">
    <table cellpadding="2" cellspacing="0" width="60%" border="1" bordercolor="<?php echo Portal::get_setting('crud_list_item_frame_color','#C3C3C3');?>">
        <thead>
            <tr class="w3-light-gray" style="height: 24px; text-transform: uppercase;">
				<th width="1%" title="[[.check_all.]]">
				    <input type="checkbox" value="1" id="Warehouse_all_checkbox" onclick="select_all_checkbox(this.form,'Warehouse',this.checked,'<?php echo Portal::get_setting('crud_list_item_selected_bgcolor','#FFFFEC');?>','<?php echo Portal::get_setting('crud_item_bgcolor','white');?>');"<?php if(URL::get('cmd')=='delete') echo 'checked';?>/>
                </th>
				<th nowrap align="left">
    				<a title="[[.sort.]]" href="<?php echo URL::build_current(((URL::get('order_by')=='warehouse.name_'.Portal::language() and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'warehouse.name_'.Portal::language()));?>" >
    				<?php if(URL::get('order_by')=='warehouse.name_'.Portal::language()) echo '<img alt="" src="'.Portal::template('core').'/images/buttons/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
    				[[.name.]]</a>				
                </th>
                <?php if(User::can_edit(false,ANY_CATEGORY)){?>
                <th  width="200" nowrap="nowrap">[[.code.]]</th>
                <th  width="200" nowrap="nowrap">[[.privilege.]]</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<?php }?>
			</tr>
		</thead>
		
        <tbody>
			<?php $i=1;?>
			<!--LIST:items-->
			<?php $onclick = 'location=\''.URL::build_current().'&cmd=edit&id='.urlencode([[=items.id=]]).'\';"';?>
			<tr <?php echo ($i%2==0)?'':'bgcolor="#FFFFCC"';$i++;?> valign="middle" style="cursor:pointer;" id="Warehouse_tr_[[|items.id|]]">
				<td>
                    <!--IF:cond([[=items.structure_id=]]!=ID_ROOT and [[=items.code=]]<>'REST' and [[=items.code=]]<>'HSKP' and [[=items.code=]]<>'MASSA' and [[=items.code=]]<>'REPT')-->
                    <input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="select_checkbox(this.form,'Warehouse',this,'<?php echo Portal::get_setting('crud_list_item_selected_bgcolor','#FFFFEC');?>','<?php echo Portal::get_setting('crud_item_bgcolor','white');?>');" id="Warehouse_checkbox" <?php if(URL::get('cmd')=='delete') echo 'checked';?>/>
                    <!--/IF:cond-->
                </td>
				<td nowrap align="left" onclick="window.location='<?php echo Url::build_current().'&cmd=edit&id='.[[=items.id=]];?>'">
    				[[|items.indent|]]
    				[[|items.indent_image|]]
    				<span class="page_indent">&nbsp;</span>
    				[[|items.name|]]
                </td>
                <td>[[|items.code|]]</td>
                <td>[[|items.module_name|]]</td>
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
    			<a href="javascript:void(0)" onclick="select_all_checkbox(document.ListWarehouseForm,'Warehouse',true,'<?php echo Portal::get_setting('crud_list_item_selected_bgcolor','#FFFFEC');?>','<?php echo Portal::get_setting('crud_item_bgcolor','white');?>');">[[.select_all.]]</a>&nbsp;
    			<a href="javascript:void(0)" onclick="select_all_checkbox(document.ListWarehouseForm,'Warehouse',false,'<?php echo Portal::get_setting('crud_list_item_selected_bgcolor','#FFFFEC');?>','<?php echo Portal::get_setting('crud_item_bgcolor','white');?>');">[[.select_none.]]</a>
    			<a href="javascript:void(0)" onclick="select_all_checkbox(document.ListWarehouseForm,'Warehouse',-1,'<?php echo Portal::get_setting('crud_list_item_selected_bgcolor','#FFFFEC');?>','<?php echo Portal::get_setting('crud_item_bgcolor','white');?>');">[[.select_invert.]]</a>
			</td>
			<td>&nbsp;</td>
		</tr>
	</table>		
	<input type="hidden" name="cmd" value="" id="cmd"/>
	<!--IF:delete(URL::get('cmd')=='delete')-->
	<input type="hidden" name="confirm" value="1" />
	<!--/IF:delete-->
</form>
</div>
