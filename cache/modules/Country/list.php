<?php 
$title = Portal::language('country_list');
$action = (URL::get('cmd')=='edit')?'edit':'add';
?>
<div id="title_region"></div>
<div class="form_bound">
		<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
			<tr>
				<td width="55%" align="left" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><?php echo Portal::language('country_list');?></td>
				<td width="27%" align="right" nowrap="nowrap">
					<?php if(User::can_add(false,ANY_CATEGORY)){?><a href="<?php echo URL::build_current(array('cmd'=>'add','action'));?>"  class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('Add');?></a><?php }?>
					<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0)" onclick="if(!confirm('<?php echo Portal::language('are_you_sure');?>')){return false};ListCountryForm.cmd.value='delete';ListCountryForm.submit();"  class="w3-btn w3-red" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('Delete');?></a><?php }?>
                    <?php if(User::can_edit(false,ANY_CATEGORY)){?><a href="javascript:void(0)" onclick="if(!confirm('<?php echo Portal::language('are_you_sure');?>')){return false};ListCountryForm.cmd.value='update';ListCountryForm.submit();"  class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('Save');?></a><?php }?>                    
				</td>
			</tr>
		</table>      
		<fieldset>
			<legend class="title"><?php echo Portal::language('search');?></legend>
		<form method="post" name="SearchCountryForm">
			<?php echo Portal::language('name');?>:
			<input  name="name" id="name" style="width:100px" / type ="text" value="<?php echo String::html_normalize(URL::get('name'));?>">&nbsp;
			<input type="hidden" name="page_no" value="1">
			<input type="submit" value="   <?php echo Portal::language('search');?>   "> (<?php echo Portal::language('total');?>: <?php echo $this->map['total'];?>)
		<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
		</fieldset><br />
		<div class="content">
		<?php 
				if((URL::get('selected_ids')))
				{?><div class="notice"><?php echo Portal::language('selected_list_to_delete');?></div><br />
				<?php
				}
				?>
		<form name="ListCountryForm" method="post">
		<table width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#CCCCCC">
			<thead>
			<tr class="w3-light-gray" style="text-transform: uppercase; height: 30px;">
				<td width="1%" title="<?php echo Portal::language('check_all');?>">
					<input type="checkbox" value="1" id="Country_all_checkbox" onclick="select_all_checkbox(this.form,'Country',this.checked,'#FFFFEC','white');"<?php if(URL::get('cmd')=='delete') echo ' checked';?>/></td>
				<td colspan="2" align="center"><?php echo Portal::language('vietnamese');?></td>
				<td colspan="2" align="center"><?php echo Portal::language('english');?></td>
                <td><?php echo Portal::language('select_for_report');?></td>                
				<td>&nbsp;</td>
				<?php if(User::can_edit(false,ANY_CATEGORY))
				{?>
				<?php }?>
			</tr>
			<tr valign="middle" bgcolor="#EFEFEF" style="line-height:20px">
			  <td title="<?php echo Portal::language('check_all');?>">&nbsp;</td />
			  <td width="100" align="center"><?php echo Portal::language('code');?></td>
			  <td align="center"><?php echo Portal::language('name');?></td>
			  <td width="100" align="center"><?php echo Portal::language('code');?></td>
			  <td align="center"><?php echo Portal::language('name');?></td>
			  <td>&nbsp;</td>
              <td>&nbsp;</td>
	        </tr>
			</thead>
			<tbody>
			<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
			<tr bgcolor="<?php if((URL::get('just_edited_id',0)==$this->map['items']['current']['id']) or (is_numeric(array_search($this->map['items']['current']['id'],$this->map['just_edited_ids'])))){ echo '#FFFFFF';} else {echo '#FFFFFF';}?>" valign="middle" <?php Draw::hover('#E2F1DF');?> style="cursor:hand;" id="Country_tr_<?php echo $this->map['items']['current']['id'];?>">
				<td><input name="selected_ids[]" type="checkbox" value="<?php echo $this->map['items']['current']['id'];?>" onclick="select_checkbox(this.form,'Country',this,'#FFFFEC','white');" id="Country_checkbox" <?php if(URL::get('cmd')=='delete') echo 'checked';?> /></td>
				<td><?php echo $this->map['items']['current']['code_1'];?></td>
			    <td style="text-transform: uppercase;"><?php echo $this->map['items']['current']['name_1'];?></td>
			    <td><?php echo $this->map['items']['current']['code_2'];?></td>
			    <td><?php echo $this->map['items']['current']['name_2'];?> </td>
                <td align="center"><input name="selected_report[]" type="checkbox" value="<?php echo $this->map['items']['current']['id'];?>" onclick="select_checkbox(this.form,'Country',this,'#FFFFEC','white');" id="Country_checkbox" <?php if($this->map['items']['current']['selected_report']==1) echo 'checked';?> /></td>                
		        <td width="24" align="center"><a href="<?php echo URL::build_current();?>&cmd=edit&id=<?php echo $this->map['items']['current']['id'];?>"><?php echo Portal::language('edit');?></a></td>
	          </tr>
			<?php }}unset($this->map['items']['current']);} ?>
			</tbody>
		</table>
		<table width="100%"><tr>
		<td width="100%">
			<?php echo Portal::language('select');?>:&nbsp;
			<a href="#a" onclick="select_all_checkbox(document.ListCountryForm,'Country',true,'#FFFFEC','white');"><?php echo Portal::language('select_all');?></a>&nbsp;
			<a href="#a" onclick="select_all_checkbox(document.ListCountryForm,'Country',false,'#FFFFEC','white');"><?php echo Portal::language('select_none');?></a>
			<a href="#a" onclick="select_all_checkbox(document.ListCountryForm,'Country',-1,'#FFFFEC','white');"><?php echo Portal::language('select_invert');?></a>
		</td>
		</tr></table>
		<input type="hidden" name="cmd" value="delete"/>
		<?php 
				if((URL::get('cmd')=='delete'))
				{?>
		<input type="hidden" name="confirm" value="1" />
		
				<?php
				}
				?>
		<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
		</div>
</div>

