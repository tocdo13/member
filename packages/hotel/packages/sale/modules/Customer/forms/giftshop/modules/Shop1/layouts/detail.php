<link href="skins/default/stylesheet.css" rel="stylesheet" type="text/css">
<?php 
System::set_page_title(HOTEL_NAME.' - '.Portal::language('shop_detail'));?>
<table cellspacing="0" width="100%" cellpadding="5px">
	<tr valign="top">
		<td align="left" colspan="3">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="form-title" width="100%">[[.shop_detail.]]</td>
                </tr>
            </table>
		</td>
	</tr>
		<tr bgcolor="#EFEFEF" valign="top">
			<td align="right" width="1%" nowrap="nowrap"><span style="line-height:24px;"><strong>[[.code.]]</strong></span></td>
			<td bgcolor="#EFEFEF" align="center" width="1%" nowrap="nowrap"><div style="width:10px;">:</div></td>
			<td bgcolor="#EFEFEF">
				<div class="detail_box">[[|code|]]</div>
			</td>
		</tr><tr bgcolor="#EFEFEF" valign="top">
			<td nowrap align="right"><strong>[[.name.]]</strong></td>
			<td bgcolor="#EFEFEF" align="center">:</td>
			<td bgcolor="#EFEFEF">
				<div class="detail_box">[[|name|]]</div>
			</td>
		</tr>
	<tr bgcolor="#EFEFEF" valign="top">
		<td>&nbsp;</td>
		<td bgcolor="#EFEFEF">&nbsp;</td>
		<td bgcolor="#EFEFEF">
			<table cellpadding=5>
			<tr><td>
			<?php Draw::button(Portal::language('list'),URL::build_current());?></td>
			<?php if(User::can_edit(false,ANY_CATEGORY))
			{
			?><td>
			<?php Draw::button(Portal::language('edit'),URL::build_current(array()+array('cmd'=>'edit','edit_selected'=>1,'selected_ids'=>$_REQUEST['id'])));?></td>
			<?php
			}
			if(User::can_delete(false,ANY_CATEGORY) and ListShopForm::check_delete(intval(Url::get('id'))))
			{
			?><td>
			<?php Draw::button(Portal::language('delete'),URL::build_current(array()+array('cmd'=>'delete','id'=>$_REQUEST['id'])));?></td>
			<?php
			}
			?></tr>
			</table>
		</p>
		</td>
	</tr>
	</table>