<link href="skins/default/stylesheet.css" rel="stylesheet" type="text/css">
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('delete_product'));?>
<form name="DeleteProductForm" method="post">
<table cellspacing="0" width="100%">
	<tr valign="top" bgcolor="#FFFFFF">
		<td align="left">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="form-title" width="70%">[[.delete_product.]]</td>
					<td width="30%">
					<?php Draw::button(Portal::language('delete_complate'),'confirm',false,true,'DeleteProductForm');?>
					<?php Draw::button(Portal::language('back_to_list'),URL::build_current(array('type')));?>
					</td>
                </tr>
            </table>
		</td>
	</tr>
	<tr bgcolor="#EFEFEF" valign="top">
	<td bgcolor="#EFEFEF" width="100%">&nbsp;</td>
	</tr>
	<?php if(Form::$current->is_error())
	{
	?><tr bgcolor="#EFEFEF" valign="top">
	<td><?php echo Form::$current->error_messages();?></td>
	</tr>
	<?php
	}
	?>
    
    
	
	<tr><td>
    <input type="hidden" name="id" value="[[|id|]]"/><input type="hidden" name="cmd" value="delete"/>
	<table>
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
		</tr>
	<tr bgcolor="#EFEFEF" valign="top">
		<td nowrap align="right">&nbsp;</td>
		<td width="10"><span style="width:10px;line-height:24px;">&nbsp;</span></td>
		<td width="496">
			<b>[[.confirm_question.]]</b>
		</td>
	</tr>
	</table>
	</td></tr>
	
	</table>
    </form>