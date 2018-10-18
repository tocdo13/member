<style>
	.input_style
	{
		width:100%;
		border:0;
		background-color:#EFEFEE;
		font-weight:bold;	
		border-bottom:1 dashed; 	
	}
</style>
<?php System::set_page_title(HOTEL_NAME);?><table cellspacing="0" width="100%">
	<tr valign="top" bgcolor="#FFFFFF">
		<td align="left">
			<table width="100%" cellspacing="0">
				<tr><td nowrap width="100%">
					<font class="form_title"><b>[[.detail_title.]]</b></font>
				</td>
				<td>
					<a target="_blank" href="<?php echo URL::build('help',array('id'=>Module::block_id(),'href'=>'?'.$_SERVER['QUERY_STRING']));?>#add">
						<img src="skins/default/images/scr_symQuestion.gif"/>
					</a>
				</td>
				<td>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr bgcolor="#EEEEEE" valign="top">
	<td bgcolor="#EEEEEE">
	<table width="100%">
	<?php if(Form::$current->is_error())
		{
			echo Form::$current->error_messages();
		}?>
	<tr><td>
		<table>
		<tr bgcolor="#EEEEEE">
			<td nowrap><strong>[[.room_id.]]</strong></td>
			<td align="center"><span style="line-height:24px;">:</span></td>
			<td >[[|room_name|]]
			</td>
		</tr>
		</table>
        
        <table>
            <tr>
                <td><span class="multi-input-header"><span style="width:100px;"><strong>[[.code.]]</strong></span></span></td>
                <td><span class="multi-input-header"><span style="width:200px;"><strong>[[.name.]]</strong></span></span></td>
                <td><span class="multi-input-header"><span style="width:100px;"><strong>[[.unit_name.]]</strong></span></span></td>
                <td><span class="multi-input-header"><span style="width:100px;"><strong>[[.quantity.]]</strong></span></span></td>
            </tr>
            <tr>
                <td><span><span style="width:80px;">[[|product_id|]]</span></span></td>
                <td><span><span style="width:180px;">[[|name|]]</span></span></td>
                <td><span><span style="width:80px;">[[|unit_name|]]</span></span></td>
                <td><span><span style="width:80px;">[[|quantity|]]</span></span></td>
            </tr>
        </table>

    </td></tr>
	<tr bgcolor="#EEEEEE">
		<td>
			<table>
			<tr>
				<td>
					<?php Draw::button(Portal::language('list'),URL::build_current());?></td><td>
					<?php Draw::button(Portal::language('delete'),URL::build_current(array('cmd'=>'delete','room_id'=>Url::get('room_id'),'product_id'=>Url::get('product_id'))));?></td><td>
					<?php Draw::button(Portal::language('damaged'),URL::build_current(array('cmd'=>'damaged','room_id'=>Url::get('room_id'),'product_id'=>Url::get('product_id'))));?></td>
			</tr>
			</table>
		</td>
	</tr>
	</table>
	</td></tr>
</table>