<link href="skins/default/stylesheet.css" rel="stylesheet" type="text/css">
<?php System::set_page_title(HOTEL_NAME.' - '.'[[.detail_title.]]');?>
<table width="100%" cellspacing="0">
		<tr><td nowrap width="100%">
			<font class="form_title"><b>[[.detail_title.]]</b></font>
		</td>
		<td>
			<a target="_blank" href="<?php echo URL::build('help',array('id'=>$GLOBALS['current_block']->data['module_id'],'href'=>'?'.$_SERVER['QUERY_STRING']));?>#detail">
				<img src="skins/default/images/scr_symQuestion.gif"/>
			</a>
		</td>
		<td>&nbsp;</td>
		</tr>
	</table>	
	<table cellspacing="0" width="100%">
		<tr bgcolor="#EFEFEF" valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.code.]]</strong></span></td>
			<td align="center"><div style="width:10px;">:</div></td>
			<td>
				<div class="detail_box">[[|id|]]</div>
			</td>
		</tr><tr bgcolor="#EFEFEF" valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><b>[[.name.]]</b></span></td>
			<td align="center"><div style="width:10px;">:</div></td>
			<td width="90%"><div class="detail_box">&nbsp;[[|name|]]</div></td>
		</tr><tr bgcolor="#EFEFEF" valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.category_id.]]</strong></span></td>
			<td align="center"><div style="width:10px;">:</div></td>
			<td width="90%"><div class="detail_box">&nbsp;[[|category_id|]]</div></td>
		</tr><tr bgcolor="#EFEFEF" valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.type.]]</strong></span></td>
			<td align="center"><div style="width:10px;">:</div></td>
			<td>
				<div class="detail_box">[[|type|]]</div>
			</td>
		</tr><tr bgcolor="#EFEFEF" valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.unit_id.]]</strong></span></td>
			<td align="center"><div style="width:10px;">:</div></td>
			<td width="90%"><div class="detail_box">&nbsp;[[|unit_id|]]</div></td>
		</tr><tr bgcolor="#EFEFEF" valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.price_usd.]]</strong></span></td>
			<td align="center"><div style="width:10px;">:</div></td>
			<td>
				<div class="detail_box">[[|price_usd|]]</div>
			</td>
		</tr><tr bgcolor="#EFEFEF" valign="top">
			<td nowrap align="right"><span style="line-height:24px;"><strong>[[.price.]]</strong></span></td>
			<td align="center"><div style="width:10px;">:</div></td>
			<td>
				<div class="detail_box">[[|price|]]</div>
			</td>
		</tr>
		<!--IF:store(sizeof([[=stores=]])>0)-->
		<tr>
			<td colspan=3 style="padding:5px">
				<b><u>[[.store_product.]]:</u></b>
				<table cellpadding="5" cellspacing="0" bordercolor="#D6D2C2" border="1" style="border-collapse:collapse" bgcolor="white">
				<tr style="line-height:20px" bgcolor="#ECE9D8">
					<th>[[.store_name.]]</th>
					<th>[[.quantity.]]</th>
					<th>[[.import_quantity.]]</th>
					<th>[[.export_quantity.]]</th>
					<th>[[.import_price.]]</th>
					<th>[[.export_price.]]</th>
				</tr>
				<!--LIST:stores-->
				<tr>
					<td><a href="">[[|stores.name|]]</a></td>
					<td>[[|stores.quantity|]]</td>
					<td>[[|stores.import_quantity|]]</td>
					<td>[[|stores.export_quantity|]]</td>
					<td>[[|stores.import_price|]]</td>
					<td>[[|stores.export_price|]]</td>
				</tr>
				<!--/LIST:stores-->
				</table>
			</td>
		</tr>
		<!--/IF:store-->
	<tr bgcolor="#EFEFEF" valign="top">
		<td colspan="3">
			<table cellpadding=5>
			<tr><td>
			<?php Draw::button('[[.list.]]',URL::build_current());?></td>
			<?php if(User::can_edit(false,ANY_CATEGORY))
			{
			?><td>
			<?php Draw::button('[[.edit.]]',URL::build_current(array()+array('cmd'=>'edit','edit_selected'=>1,'selected_ids'=>$_REQUEST['id'])));?></td>
			<?php
			}
			if(User::can_delete(false,ANY_CATEGORY))
			{
			?><td>
			<?php Draw::button('[[.delete.]]',URL::build_current(array()+array('cmd'=>'delete','id'=>$_REQUEST['id'])));?></td>
			<?php
			}
			?></tr>
			</table>
		</td>
	</tr>
	</table>