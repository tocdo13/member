<a name="top_anchor"></a>
<?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>	
	<form name="ListModeratorForm" method="post" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>">
    <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="55%" class="form-title">[[.granted_user_list.]]</td>
            <?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%" align="right"><a href="<?php echo URL::build_current(array('cmd'=>'add'));?>"  class="button-medium-add">[[.Add.]]</a></td><?php }?>
			<?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%"><a href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};ListModeratorForm.cmd.value='delete';ListModeratorForm.submit();"  class="button-medium-delete">[[.Delete.]]</a></td><?php }?>
        </tr>
    </table> <br />  
	<table cellpadding="2" cellspacing="0" width="100%"  border="1" bordercolor="#CCCCCC" align="center">
		<tr>
			<td colspan="6">
					<?php if(User::can_view(false,ANY_CATEGORY)){?>
					[[.user.]] <input name="user_id" type="text" id="user_id" size="30"/>&nbsp;<input type="submit" value="[[.go.]]" />
					<?php }?>			</td>
		</tr>
		<tr bgcolor="#EFEFEF" valign="middle">		
			<th width="2%" title="[[.check_all.]]">
					<input type="checkbox" value="1" id="Moderator_all_checkbox" onclick="select_all_checkbox(this.form,'Moderator',this.checked,'#EFEFEF','#CCCCCC');"<?php if(URL::get('cmd')=='delete') echo ' checked';?>>				</th>				
				<th width="29%" title="[[.check_all.]]" align="left"><a>[[.account_id.]]</a></th>
				<th width="28%" align="left" nowrap><a>[[.full_name.]]</a></th>
		        <th width="23%" align="left" nowrap>&nbsp;</th>
          <th width="16%" align="left" nowrap>&nbsp;</th>
			    <!--IF:cond1(User::can_admin(false,ANY_CATEGORY))--><th width="2%" title="[[.check_all.]]"></th><!--/IF:cond1-->
			</tr>
			<?php $i = 0;?>
			<!--LIST:items-->
				<tr id="Moderator_tr_[[|items.id|]]">
					<td width="2%"><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="select_checkbox(this.form,'Moderator',this,'#EFEFEF','#CCCCCC');" id="Moderator_checkbox" <?php if(URL::get('cmd')=='delete') echo 'checked';?>></td />
				   <td width="29%">[[|items.id|]]</td />
					<td width="28%" align="left"  nowrap>[[|items.full_name|]]</td>
		            <td width="23%" align="left"  nowrap>&nbsp;</td>
                  <td width="16%" align="left"  nowrap>&nbsp;</td>
				    <!--IF:cond1(User::can_admin(false,ANY_CATEGORY))--><td width="2%"><a href="<?php echo Url::build_current(array('cmd'=>'edit','account_id'=>[[=items.id=]]));?>"><img src="packages/core/skins/default/images/buttons/edit.jpg"></a></td /><!--/IF:cond1-->
				</tr>
			<!--/LIST:items-->
		</table>		
  <table width="100%" cellpadding="6" cellspacing="0" style="background-color:#F0F0F0;border:1px solid #999999;border-top:0px;height:8px;#width:99%" align="center">
		<tr>
			<td>
				[[.select.]]:&nbsp;
				<a onclick="select_all_checkbox(document.ListModeratorForm,'ListModeratorForm',true,'#EFEFEF','#CCCCCC');">[[.select_all.]]</a>&nbsp;
				<a onclick="select_all_checkbox(document.ListModeratorForm,'ListModeratorForm',false,'#EFEFEF','#CCCCCC');">[[.select_none.]]</a>
				<a onclick="select_all_checkbox(document.ListModeratorForm,'ListModeratorForm',-1,'#EFEFEF','#CCCCCC');">[[.select_invert.]]</a>
			</td>
			<td>[[|paging|]]</td>
			<td align="right">
				<a name="bottom_anchor" href="#top_anchor"><img alt="" src="<?php echo Portal::template('core');?>/images/top.gif" title="[[.top.]]" border="0" alt="[[.top.]]"></a>
			</td>			
		</tr>
		</table>
		<input type="hidden" name="cmd" value="" id="cmd"/>
	</form>
	<div style="#height:8px"></div>
<style>
	.quick-menu-item.list,
	.quick-menu-item.save,
	.quick-menu-item.update,
	.quick-menu-item.check_in,
	.quick-menu-item.check_out,
	.quick-menu-item.print,
	.quick-menu-item.move
	{
		display:none;
	}
</style>