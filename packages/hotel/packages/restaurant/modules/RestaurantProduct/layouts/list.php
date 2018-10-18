<script>
	RestaurantProduct_array_items = {
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
<link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<table cellspacing="0" width="100%">
	<tr valign="top" bgcolor="#FFFFFF">
		<td align="left">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="form-title" width="100%"><?php echo Portal::language('restaurant_product_list'); ?> ([[.total.]]: [[|total|]])</td>
					<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%" align="right"><a href="<?php echo URL::build('product',array('cmd'=>'syn','function'=>'res_product'));?>"  class="button-medium-add">[[.add.]]</a></td><?php }?>
                    <?php if(User::can_edit(false,ANY_CATEGORY)){?><td width="1%"><a href="javascript:void(0)" onclick="RestaurantProductListForm.cmd.value='edit_selected';RestaurantProductListForm.submit();"  class="button-medium-edit">[[.Edit.]]</a></td><?php }?>
                    <?php if(User::can_edit(false,ANY_CATEGORY)){?><td width="1%"><a href="javascript:void(0)" onclick="RestaurantProductListForm.cmd.value='export_product';RestaurantProductListForm.submit();"  class="button-medium-edit">[[.export_data.]]</a></td><?php }?>
                    <?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%"><a href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};RestaurantProductListForm.cmd.value='delete_selected';RestaurantProductListForm.submit();"  class="button-medium-delete">[[.Delete.]]</a></td><?php }?>
                </tr>
            </table>
		</td>
	</tr>
	<tr valign="top">
		<td width="100%">
			<table cellspacing="0" width="100%">
			<tr>
				<td width="100%">
					<FORM method="get" name="SearchRestaurantProductForm">
					<input type="hidden" name="page" value="<?php echo URL::get('page');?>" />
					<table>
						<tr><td align="right" nowrap style="font-weight:bold">[[.code.]]</td>
						<td>:</td>
						<td nowrap>
							<input name="code" type="text" id="code" style="width:50px;">
						</td><td align="right" nowrap style="font-weight:bold">[[.name.]]</td>
						<td>:</td>
						<td nowrap>
							<input name="name" type="text" id="name" style="width:100px;">
						</td>
						<td align="right" nowrap style="font-weight:bold">[[.category_id.]]</td>
						<td>:</td>
						<td nowrap>
							<select name="category_id" id="category_id" style="width:150px;" onchange="this.form.submit();"></select>
							<input name="action" type="hidden" id="action">
						</td>
						<td align="right" nowrap style="font-weight:bold">[[.type.]]</td>
						<td>:</td>
						<td nowrap>
							<select  name="type" id="type" style="width:100px;" onchange="this.form.submit();">
								<option value=""></option><option value="GOODS">[[.goods.]]</option><option value="PRODUCT">[[.product.]]</option><option value="MATERIAL">[[.material.]]</option><option value="EQUIPMENT">[[.equipment.]]</option><option value="SERVICE">[[.service.]]</option><option value="TOOL">[[.tool.]]</option>
							</select>
							<script>
							$('type').value='<?php echo URL::get('type');?>';
							</script>
						</td>
						<td><input type="submit" value="  [[.search.]]  "/></td>
						</tr>
					</table>
					</FORM>
					<div>
				<form name="RestaurantProductListForm" method="post">
                    <div style="border:2px solid #FFFFFF;">
					<table width="100%" cellpadding="2" cellspacing="0" border="1" style="border-collapse:collapse" bordercolor="#CECFCE">
						<tr class="table-header">
							<th width="1%" title="[[.check_all.]]"><input type="checkbox" value="1" id="RestaurantProduct_check_0" onclick="check_all('RestaurantProduct','RestaurantProduct_array_items','#FFFFEC',this.checked);"></th>
							<th nowrap align="left" >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='res_product'.'.id' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'res_product'.'.id'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='res_product'.'.id') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.code.]]								</a>							</th><th align="left" nowrap>
								<a title="[[.sort.]]" href="<?php echo URL::build_current(((URL::get('order_by')=='res_product'.'.name_'.Portal::language() and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'res_product'.'.name_'.Portal::language()));?>" >
								<?php if(URL::get('order_by')=='res_product'.'.name_'.Portal::language()) echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.name.]]								</a>
							</th>
						    <th nowrap align="right">
								[[.price.]]							</th><th nowrap align="center">
								[[.unit_id.]]
							</th><th nowrap align="center" >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='res_product'.'.type' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'res_product'.'.type'));?>" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='res_product'.'.type') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.type.]]								</a>
							</th>
							<?php if(User::can_edit(false,ANY_CATEGORY))
							{
							?>	<th>&nbsp;</th>
								<th width="22">&nbsp;</th>
						  <?php
							}
							if(User::can_delete(false,ANY_CATEGORY))
							{
							?><th>&nbsp;</th>
							<?php
							}
							?></tr>
						<?php $category = '';?>		
						<!--LIST:items-->
						<?php if($category != [[=items.category_id=]]){$category=[[=items.category_id=]];?>
						<tr>
							<td colspan="9" class="category-group">[[|items.category_id|]]</td>
						</tr>
						<?php }?>
						<?php 
						if(Url::get('action')=='select_product')
						{
							$onclick='pick_value(\''.[[=items.id=]].'\');window.close();';
						}
						else
						{
							$onclick='location=\''.URL::build_current().'&cmd=edit_selected&selected_ids='.[[=items.id=]].'&type='.[[=items.type=]].'\';';
						}
						?><tr bgcolor="<?php if((URL::get('just_edited_id',0)==[[=items.id=]]) or (is_numeric(array_search(MAP['items']['current']['id'],MAP['just_edited_ids'])))){ echo '#FFFFFF';} else {echo 'white';}?>" valign="middle" <?php Draw::hover('#E2F1DF');?>style="cursor:hand;" id="RestaurantProduct_tr_[[|items.id|]]">
							<td><!--IF:cond([[=items.can_delete=]])--><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="tr_color = clickage('RestaurantProduct','[[|items.i|]]','RestaurantProduct_array_items','#FFFFEC');" id="RestaurantProduct_check_[[|items.i|]]"><!--/IF:cond--></td>
							<td nowrap align="left" id="id_[[|items.id|]]" onclick="<?php echo $onclick;?>">
									[[|items.code|]]								</td><td align="left" nowrap id="name_[[|items.id|]]" onclick="<?php echo $onclick;?>">
									[[|items.name|]]
								</td>
							    <td nowrap align="right" id="price_[[|items.id|]]" onclick="<?php echo $onclick;?>">
									[[|items.price|]]								</td><td nowrap align="center" id="unit_[[|items.id|]]" onclick="<?php echo $onclick;?>">
									[[|items.unit_id|]]
								</td><td nowrap align="center"  onclick="<?php echo $onclick;?>">
									[[|items.type|]]
								</td>
							<?php if(User::can_edit(false,ANY_CATEGORY)){?>
                            	<td nowrap width="15" valign="middle"><a href="<?php echo Url::build_current(array()+array('cmd'=>'edit_selected','selected_ids'=>[[=items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/edit.gif" alt="[[.edit.]]" width="12" height="12" border="0"></a></td>
								<td>&nbsp;</td>
				    <?php } if(User::can_delete(false,ANY_CATEGORY) and [[=items.can_delete=]]){?>
                            	<td nowrap width="15" valign="middle"><a href="<?php echo Url::build_current(array('cmd'=>'delete','category_id','code','name','id'=>[[=items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/delete.gif" alt="[[.delete.]]" width="12" height="12" border="0"></a></td>
							<?php }?>
                            </tr>
						<!--/LIST:items-->
					</table>
                  </div>
				[[|paging|]]
            <input name="cmd" type="hidden" value="">
			</form>            
		</td>
		</tr>
	</table>	
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
