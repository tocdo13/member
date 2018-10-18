<script>
	Product_array_items = {
		'length':'<?php echo sizeof(MAP['items']);?>'
		<!--LIST:items-->
		,'[[|items.i|]]':'[[|items.id|]]'
		<!--/LIST:items-->
	}
</script>
<?php 
$title = Portal::language('product_list');
System::set_page_title(HOTEL_NAME.' - '.Portal::language('product_list'));
?>
<form name="ListProductForm" enctype="multipart/form-data" method="post">
<table width="100%" cellspacing="0" cellpadding="0">
	<tr valign="top">
		<td align="left">
            <table cellpadding="0" cellspacing="0" width="100%" border="0" class="table-bound">
                <tr height="40">
                    <td class="" width="50%" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> [[.product_list.]] <?php echo Url::get('type')?Portal::language(Url::get('type')):''; ?> ([[.quantity.]]: [[|total|]])</td>
					<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="50%" align="right"><a href="<?php echo URL::build_current(array('cmd'=>'add'));?>"  class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.add.]]</a><?php }?>
   					<?php if(User::can_add(false,ANY_CATEGORY)){?><a href="<?php echo URL::build_current(array('cmd'=>'import'));?>"  class="w3-btn w3-lime w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.import_from_excel.]]</a><?php }?>
                    <?php if(User::can_edit(false,ANY_CATEGORY)){?><input type="submit" name="export_excel" value="[[.export_excel.]]" class="w3-btn w3-teal w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;" /><?php }?>
					<!--<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%" align="right"><a href="<?php echo URL::build_current(array('cmd'=>'syn'));?>"  class="button-medium-add">[[.product_synchronize.]]</a></td><?php }?>-->                    
                    <?php if(User::can_edit(false,ANY_CATEGORY)){?><a href="javascript:void(0)" onclick="ListProductForm.cmd.value='edit_selected';ListProductForm.submit();"  class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.Edit.]]</a><?php }?>
                    <?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};ListProductForm.cmd.value='delete_selected';ListProductForm.submit();"  class="w3-btn w3-red w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.Delete.]]</a></td><?php }?>
                </tr>
            </table>
		</td>
	</tr>
	<tr valign="top">
		<td width="100%">
			<table border="0" cellspacing="0" width="100%">
			<tr>
				<td width="100%">
					<fieldset>
                    <legend class="title">[[.search_options.]]</legend>
                    <table border="0" cellpadding="3" cellspacing="0">
						<tr>
						<td align="right" nowrap style="font-weight:bold">[[.portal_name.]]</td>
						<td>:</td>
						<td nowrap>
							<select name="portal_id" id="portal_id" style="width:150px; height: 24px;"></select>
						</td>
						<td align="right" nowrap style="font-weight:bold">[[.code.]]</td>
						<td>:</td>
						<td nowrap>
							<input name="code" type="text" id="code" style="width:50px;  height: 24px;"/>
						</td><td align="right" nowrap style="font-weight:bold">[[.name.]]</td>
						<td>:</td>
						<td nowrap>
							<input name="name" type="text" id="name" style="width:100px;  height: 24px;"/>
						</td>
						<td align="right" nowrap style="font-weight:bold">[[.category_id.]]</td>
						<td>:</td>
						<td nowrap>
							<select name="category_id" id="category_id" style="width:150px; height: 24px;"></select>
						</td>
						<td align="right" nowrap style="font-weight:bold">[[.type.]]</td>
						<td>:</td>
						<td nowrap>
							<select  name="type" id="type" style="width:100px;  height: 24px;">
								<option value="">[[.all.]]</option><option value="GOODS">[[.goods.]]</option><option value="PRODUCT">[[.product.]]</option><option value="DRINK">[[.drink.]]</option><option value="MATERIAL">[[.material.]]</option><option value="EQUIPMENT">[[.equipment.]]</option><option value="SERVICE">[[.service.]]</option><option value="TOOL">[[.tool.]]</option>
							</select>
							<script>
							$('type').value='<?php echo URL::get('type');?>';
							</script>
						</td>
						<td><input name="search" type="submit" value="  [[.search.]]  " style=" height: 24px;"/></td>
                        <td><input type="button" value="  [[.show_all_product.]]  " style=" height: 24px;" onclick="window.location='<?php echo Url::build_current(); ?>'"/></td>
						</tr>
					</table>
                    </fieldset><br />
					<table width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#C6E2FF">
						<tr class="w3-light-gray" style="text-transform: uppercase; height: 26px;">
                        <th width="1%" title="[[.check_all.]]"><input type="checkbox" value="1" id="Product_check_0" onclick="check_all('Product','Product_array_items','#FFFFEC',this.checked);"></th>
                        <th nowrap align="left" >
                            <a href="<?php echo URL::build_current(((URL::get('order_by')=='product.id' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'product.id'));?>" title="[[.sort.]]">
                            <?php if(URL::get('order_by')=='product.id') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.code.]]</a>							</th>
                            <th nowrap align="left">
                            <a title="[[.sort.]]" href="<?php echo URL::build_current(((URL::get('order_by')=='product.name_'.Portal::language() and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'product.name_'.Portal::language()));?>" >
                            <?php if(URL::get('order_by')=='product.name_'.Portal::language()) echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.name.]]</a>
                        </th>
                       <th align="center" width="1%" nowrap="nowrap"> [[.unit_id.]] </th>
			<th align="center" width="1%" nowrap="nowrap"> [[.category.]] </th>
				       <th nowrap align="left" >
							<a href="<?php echo URL::build_current(((URL::get('order_by')=='product.type' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'product.type'));?>" title="[[.sort.]]">
							<?php if(URL::get('order_by')=='product.type') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.type.]]</a>
							</th>
							<?php if(User::can_edit(false,ANY_CATEGORY)){?>	
                            <th width="1%">&nbsp;</th>
							<?php }
							if(User::can_delete(false,ANY_CATEGORY)){?>
                            <th width="1%">&nbsp;</th>
							<?php }?>
                        </tr>
						<?php $category = '';?>	
						<!--LIST:items-->
						<?php if($category != [[=items.category_id=]]){$category=[[=items.category_id=]];?>
						<tr>
							<td colspan="10" class="category-group">[[|items.category_id|]]</td>
						</tr>
						<?php }?>	
						<?php 
						if(Url::get('action')=='select_product')
						{
							$onclick='pick_value(\''.[[=items.id=]].'\');window.close();';
						}else{
							$onclick='location=\''.URL::build_current().'&edit_selected=1&selected_ids='.[[=items.id=]].'&type='.[[=items.type=]].'\';';
						}
						?>
                        <tr <?php echo ([[=items.i=]]%2==0)?'class="row-odd"':'class="row-even"';?> id="Product_tr_[[|items.id|]]">
							<td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="tr_color = clickage('Product','[[|items.i|]]','Product_array_items','#FFFFEC');" id="Product_check_[[|items.i|]]"></td>
							<td nowrap align="left" id="id_[[|items.id|]]">[[|items.id|]]</td>
                            <td nowrap align="left" id="name_[[|items.id|]]">[[|items.name|]]</td>
                            <td align="center" nowrap="nowrap" id="unit_[[|items.id|]]"> [[|items.unit_id|]] </td>
                            <td align="center" nowrap="nowrap" id="unit_[[|items.id|]]"> [[|items.category_id|]] </td>
		      			  <td nowrap align="left">[[|items.type|]]</td>
                                <?php if(Url::get('page')=='spa_room'){?>
                                <td align="center" nowrap>
                                	<a href="<?php echo Url::build('start_term_remain',array('spa_room_id'=>[[=items.id=]]));?>">[[.declare.]]</a></td>
                                <?php }?>
							<!--IF:cond(User::can_edit(false,ANY_CATEGORY))-->
							<td nowrap><a href="<?php echo Url::build_current(array()+array('cmd'=>'edit_selected','selected_ids'=>[[=items.id=]],'code','name','category_id','type')); ?>"><img src="packages/core/skins/default/images/buttons/edit.gif" alt="[[.edit.]]"></a></td>
							<!--/IF:cond-->
							<!--IF:cond(User::can_delete(false,ANY_CATEGORY))-->
							<td nowrap><a href="<?php echo Url::build_current(array('cmd'=>'delete','category_id','code','name','id'=>[[=items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/delete.gif" alt="[[.delete.]]"></a>							</td>
							<!--/IF:cond-->
							</tr>
						<!--/LIST:items-->
				</table>
                [[|paging|]]             
		</td>
		</tr>
	</table>
	<input name="cmd" type="hidden" value="" />
</form>	
<script>
		function pick_value(id)
	{
		if (window.opener && !window.opener.closed)
		{
			if(window.opener.document.getElementById('product_id_'+window.opener.document.current_product_id))
			{
				window.opener.document.getElementById('product_id_'+window.opener.document.current_product_id).value=($('id_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
				window.opener.document.getElementById('product_name_'+window.opener.document.current_product_id).value=($('name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
				//window.opener.document.getElementById('product_price_'+window.opener.document.current_product_id).value=($('price_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
				window.opener.document.getElementById('unit_'+window.opener.document.current_product_id).value=($('unit_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
			}
			else
			if(window.opener.document.getElementById('product_id'))
			{
				window.opener.document.getElementById('product_id').value=($('id_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
			}
		}
	}
</script>