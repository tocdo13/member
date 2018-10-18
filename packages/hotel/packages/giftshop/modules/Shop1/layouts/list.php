<script>
	Shop_array_items = {
		'length':'<?php echo sizeof(MAP['items']);?>'
		<!--LIST:items-->
		,'[[|items.i|]]':'[[|items.id|]]'
		<!--/LIST:items-->
	}
</script>
<?php 
$title = Portal::language('shop_list');
System::set_page_title(HOTEL_NAME.' - '.Portal::language('shop_list'));?><link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<table cellspacing="0" width="100%">
	<tr valign="top">
		<td align="left" colspan="2">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="form-title" width="100%">[[.shop_list.]]</td>
                </tr>
            </table>
		</td>
	</tr>
	<tr bgcolor="#EFEFEF" valign="top">
		<td width="100%">
			<table bgcolor="#EFEFEF" cellspacing="0" width="100%">
			<tr>
				<td width="100%">
					<b>[[.search.]]</b>
					<form method="post" name="SearchShopForm">
					<table>
						<tr><td align="right" nowrap style="font-weight:bold">[[.code.]]</td>
						<td>:</td>
						<td nowrap>
								<input name="code" type="text" id="code" style="width=80">
						</td><td align="right" nowrap style="font-weight:bold">[[.name.]]</td>
						<td>:</td>
						<td nowrap>
								<input name="name" type="text" id="name" style="width=150">
						</td>
						<td><?php echo Draw::button(Portal::language('search'),false,false,true,'SearchShopForm');?><input type="submit" style="width:0px;background-color:inherit;border:0 solid white;"></td>
                        </tr>
					</table>
					</form>
					<form name="ShopListForm" method="post">
					<div>
                    <table width="100%">
						<tr>
						<?php if(User::can_add(false,ANY_CATEGORY))
                        {
                            echo '<td align="left"  width="100px">';
                            Draw::button(Portal::language('add_new'),Url::build_current(array('cmd'=>'add')));
                            echo '</td>';
                        }
                        if(User::can_edit(false,ANY_CATEGORY))
                        {
                            echo '<td align="left"  width="100px">';
                            Draw::button(Portal::language('edit'),'edit_selected','Edit Selected Items',true,'ShopListForm');
                            echo '</td>';
                        }
                        if(User::can_delete(false,ANY_CATEGORY))
                        {
                            echo '<td align="left"  width="100px">';
                            Draw::button(Portal::language('delete'),'delete_selected','Delete Selected Items',true,'ShopListForm');
                            echo '</td>';
                        }
                        ?>
                        <td align="right"><a href="#bottom"><img src="skins/default/images/bottom.gif" title="[[.bottom.]]" border="0" alt="[[.bottom.]]"></a></td>
                    </tr>
                    </table>
                    </div>
                    <div style="border:2px solid #FFFFFF;">
					<table width="100%" cellpadding="5" cellspacing="0" bordercolor="#CECFCE" border="1" style="border-collapse:collapse">
						<tr valign="middle" style="line-height:20px">
							<th width="1%" title="[[.check_all.]]"><input type="checkbox" value="1" id="Shop_check_0" onclick="check_all('Shop','Shop_array_items','#FFFFEC',this.checked);"></th>
							<th nowrap align="left" >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='shop.code' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'shop.code'));?>" style="font-weight:700" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='shop.code') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.code.]]
								</a>
							</th><th nowrap align="left" >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='shop.name' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'shop.name'));?>" style="font-weight:700" title="[[.sort.]]">
								<?php if(URL::get('order_by')=='shop.name') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.name.]]
								</a>
							</th>
						</tr>
						<!--LIST:items-->
						<tr bgcolor="<?php if((URL::get('just_edited_id',0)==[[=items.id=]]) or (is_numeric(array_search(MAP['items']['current']['id'],MAP['just_edited_ids'])))){ echo '#EFFFDF';} else {echo 'white';}?>" valign="middle" <?php Draw::hover('#EFEEEE');?>style="cursor:hand;" id="Shop_tr_[[|items.id|]]">
							<td><!--IF:cond([[=items.can_delete=]])--><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="tr_color = clickage('Shop','[[|items.i|]]','Shop_array_items','#FFFFEC');" id="Shop_check_[[|items.i|]]"><!--/IF:cond--></td>
							<td nowrap align="left" onclick="location='<?php echo URL::build_current();?>&id=[[|items.id|]]';">
									[[|items.code|]]
								</td><td nowrap align="left" onclick="location='<?php echo URL::build_current();?>&id=[[|items.id|]]';">
									[[|items.name|]]
								</td>
							<?php if(User::can_edit(false,ANY_CATEGORY)){?>
                            	<td nowrap width="15px">&nbsp;<a href="<?php echo Url::build_current(array()+array('edit_selected'=>true,'selected_ids'=>[[=items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/edit.gif" alt="[[.edit.]]" width="12" height="12" border="0"></a></td>
							<?php } if(User::can_delete(false,ANY_CATEGORY) and [[=items.can_delete=]]){?>
                            	<td nowrap width="15px">&nbsp;<a href="<?php echo Url::build_current(array()+array('cmd'=>'delete','id'=>[[=items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/delete.gif" alt="[[.delete.]]" width="12" height="12" border="0"></a></td>
							<?php }?>
                        </tr>
						<!--/LIST:items-->
					</table>
                    </div>
                    [[|paging|]]
                    <div>
                        <div style="float:left;padding:2px 2px 2px 10px;" ><strong>[[.Select.]]:</strong></div>
                        <div style="float:left;padding:2px 2px 2px 10px;font-weight:400;color:#333333;cursor:hand;" onclick="check_all('Shop','Shop_array_items','#FFFFEC',1);">[[.All.]]</div>
                        <div style="float:left;padding:2px 2px 2px 10px;font-weight:400;color:#333333;cursor:hand;" onclick="check_all('Shop','Shop_array_items','#FFFFEC',0);">[[.None.]]</div>
                        <div style="padding:2px 2px 2px 10px;width:100px;font-weight:400;color:blue;cursor:hand;" onclick="select_invert('Shop','Shop_array_items','#FFFFEC');">[[.select_invert.]]</div>
                    </div>
                    <div style="padding:2px 0 0 10px">
                    <table width="100%">
                        <tr>
                        <?php if(User::can_add(false,ANY_CATEGORY))
                        {
                            echo '<td  align="left" width="100px">';
                            Draw::button(Portal::language('add_new'),Url::build_current(array('cmd'=>'add')));
                            echo '</td>';
                        }
                        if(User::can_edit(false,ANY_CATEGORY))
                        {
                            echo '<td align="left" width="100px">';
                            Draw::button(Portal::language('edit'),'edit_selected','Edit Selected Items',true,'ShopListForm');
                            echo '</td>';
                        }
                        if(User::can_delete(false,ANY_CATEGORY))
                        {
                            echo '<td align="left" width="100px">';
                            Draw::button(Portal::language('delete'),'delete_selected','Delete Selected Items',true,'ShopListForm');
                            echo '</td>';
                        }
                        ?>
                        <td align="right"><a name="bottom" href="#"><img src="skins/default/images/top.gif" title="[[.top.]]" border="0" alt="[[.top.]]"></a></td>
                        </tr>
                    </table>
                    </div>
            </td>
        </tr>
	</table>
</td>
</tr>
</table>    