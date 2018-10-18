<?php 
$title = Portal::language('country_list');
$action = (URL::get('cmd')=='edit')?'edit':'add';
?>
<div id="title_region"></div>
<div class="form_bound">
		<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
			<tr>
				<td width="55%" align="left" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;">[[.country_list.]]</td>
				<td width="27%" align="right" nowrap="nowrap">
					<?php if(User::can_add(false,ANY_CATEGORY)){?><a href="<?php echo URL::build_current(array('cmd'=>'add','action'));?>"  class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.Add.]]</a><?php }?>
					<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};ListCountryForm.cmd.value='delete';ListCountryForm.submit();"  class="w3-btn w3-red" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.Delete.]]</a><?php }?>
                    <?php if(User::can_edit(false,ANY_CATEGORY)){?><a href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};ListCountryForm.cmd.value='update';ListCountryForm.submit();"  class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.Save.]]</a><?php }?>                    
				</td>
			</tr>
		</table>      
		<fieldset>
			<legend class="title">[[.search.]]</legend>
		<form method="post" name="SearchCountryForm">
			[[.name.]]:
			<input name="name" type="text" id="name" style="width:100px" />&nbsp;
			<input type="hidden" name="page_no" value="1">
			<input type="submit" value="   [[.search.]]   "> ([[.total.]]: [[|total|]])
		</form>
		</fieldset><br />
		<div class="content">
		<!--IF:cond(URL::get('selected_ids'))--><div class="notice">[[.selected_list_to_delete.]]</div><br /><!--/IF:cond-->
		<form name="ListCountryForm" method="post">
		<table width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#CCCCCC">
			<thead>
			<tr class="w3-light-gray" style="text-transform: uppercase; height: 30px;">
				<td width="1%" title="[[.check_all.]]">
					<input type="checkbox" value="1" id="Country_all_checkbox" onclick="select_all_checkbox(this.form,'Country',this.checked,'#FFFFEC','white');"<?php if(URL::get('cmd')=='delete') echo ' checked';?>/></td>
				<td colspan="2" align="center">[[.vietnamese.]]</td>
				<td colspan="2" align="center">[[.english.]]</td>
                <td>[[.select_for_report.]]</td>                
				<td>&nbsp;</td>
				<?php if(User::can_edit(false,ANY_CATEGORY))
				{?>
				<?php }?>
			</tr>
			<tr valign="middle" bgcolor="#EFEFEF" style="line-height:20px">
			  <td title="[[.check_all.]]">&nbsp;</td />
			  <td width="100" align="center">[[.code.]]</td>
			  <td align="center">[[.name.]]</td>
			  <td width="100" align="center">[[.code.]]</td>
			  <td align="center">[[.name.]]</td>
			  <td>&nbsp;</td>
              <td>&nbsp;</td>
	        </tr>
			</thead>
			<tbody>
			<!--LIST:items-->
			<tr bgcolor="<?php if((URL::get('just_edited_id',0)==[[=items.id=]]) or (is_numeric(array_search(MAP['items']['current']['id'],MAP['just_edited_ids'])))){ echo '#FFFFFF';} else {echo '#FFFFFF';}?>" valign="middle" <?php Draw::hover('#E2F1DF');?> style="cursor:hand;" id="Country_tr_[[|items.id|]]">
				<td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="select_checkbox(this.form,'Country',this,'#FFFFEC','white');" id="Country_checkbox" <?php if(URL::get('cmd')=='delete') echo 'checked';?> /></td>
				<td>[[|items.code_1|]]</td>
			    <td style="text-transform: uppercase;">[[|items.name_1|]]</td>
			    <td>[[|items.code_2|]]</td>
			    <td>[[|items.name_2|]] </td>
                <td align="center"><input name="selected_report[]" type="checkbox" value="[[|items.id|]]" onclick="select_checkbox(this.form,'Country',this,'#FFFFEC','white');" id="Country_checkbox" <?php if([[=items.selected_report=]]==1) echo 'checked';?> /></td>                
		        <td width="24" align="center"><a href="<?php echo URL::build_current();?>&cmd=edit&id=[[|items.id|]]">[[.edit.]]</a></td>
	          </tr>
			<!--/LIST:items-->
			</tbody>
		</table>
		<table width="100%"><tr>
		<td width="100%">
			[[.select.]]:&nbsp;
			<a href="#a" onclick="select_all_checkbox(document.ListCountryForm,'Country',true,'#FFFFEC','white');">[[.select_all.]]</a>&nbsp;
			<a href="#a" onclick="select_all_checkbox(document.ListCountryForm,'Country',false,'#FFFFEC','white');">[[.select_none.]]</a>
			<a href="#a" onclick="select_all_checkbox(document.ListCountryForm,'Country',-1,'#FFFFEC','white');">[[.select_invert.]]</a>
		</td>
		</tr></table>
		<input type="hidden" name="cmd" value="delete"/>
		<!--IF:delete(URL::get('cmd')=='delete')-->
		<input type="hidden" name="confirm" value="1" />
		<!--/IF:delete-->
		</form>
		</div>
</div>

