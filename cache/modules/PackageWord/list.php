<script type="text/javascript" src="packages/core/includes/js/table_multi_items.js">
</script>
<script src="packages/core/includes/js/ajax.js" type="text/javascript">
</script>
<script src="packages/core/includes/js/resize.js" type="text/javascript">
</script>
<script language="javascript">
table_fields = 
	{'':''
	,'id':''<?php if(isset($this->map['languages']) and is_array($this->map['languages'])){ foreach($this->map['languages'] as $key1=>&$item1){if($key1!='current'){$this->map['languages']['current'] = &$item1;?>
		,'value_<?php echo $this->map['languages']['current']['id'];?>':'text'
		<?php }}unset($this->map['languages']['current']);} ?>,'package_id':'suggest','time':'time'
	};
field_error_messages = {};
define_select_fields = {
'':''
}
define_suggest_fields = {
'':''
,'package_id':{
'':''
<?php if(isset($this->map['packages']) and is_array($this->map['packages'])){ foreach($this->map['packages'] as $key2=>&$item2){if($key2!='current'){$this->map['packages']['current'] = &$item2;?>
,'<?php echo addslashes($this->map['packages']['current']['id']);?>':'<?php echo addslashes($this->map['packages']['current']['name']);?>'
<?php }}unset($this->map['packages']['current']);} ?>
} 
}
define_field_actions = {
'':''
}
function update_row(index)
{
}
</script>
<?php 
$title = (URL::get('cmd')=='delete')?Portal::language('delete_title'):Portal::language('list_title');
$action = (URL::get('cmd')=='delete')?'delete':'list';
System::set_page_title(HOTEL_NAME.' - '.$title);?>
<table cellpadding="2" width="100%">
	<tr><td>
		<img alt="" src="<?php echo Portal::template('core').'/images/buttons/';?><?php echo $action;?>_button.gif" align="absmiddle"/>
	</td>
	<td class="form_title" nowrap="nowrap">
		<?php echo $title;?>
	</td>
	<td width="100%">
		<table width="100%"><tr><td align="center" width="100%" nowrap="nowrap">
		<?php echo $this->map['paging'];?>
		</td></tr></table>
	</td>
	<?php 
	if(User::can_edit()and User::can_add()){?>
	<td class="form_title_button" nowrap="nowrap">
		<a  onclick="ListPackageWordForm.submit();">
			<img alt="" src="<?php echo Portal::template('core').'/images/buttons/';?>save_button.gif"/>
			<br /><?php echo Portal::language('Save');?>
		</a>
	</td>
	<?php }
	if(User::can_delete()){?>
	<td class="form_title_button" nowrap="nowrap">
		<a  onclick="if(confirm('<?php echo Portal::language('delete_confirm_question');?>?')){ListPackageWordForm.cmd.value='delete';ListPackageWordForm.submit();}"><img alt="" src="<?php echo Portal::template('core').'/images/buttons/';?>delete_button.gif" width="25" /><br /><?php echo Portal::language('Delete');?></a></td><?php }
	?>
	</tr>
	</table>
<div class="form_bound">
	<?php if(URL::get('module_id')){
	?>
	<p>
	<a javascript:void(0)><font size="+1"><b><?php echo Portal::language('module_words_of');?> <?php echo $this->map['module_name'];?></b></font></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo URL::build_current();?>"><font size="+1"><?php echo Portal::language('all_words');?></font></a>
	</p>
	<?php
	}?>
	<div class="form_content">
		<a name="top_anchor"></a>
		<div style="border:2px solid #FFFFFF;">
		<form name="ListPackageWordForm" method="post" action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>">
		<table width="99%" cellpadding="1" cellspacing="0" border="1" bordercolor="#CCCCCC" id="main_table">
			<thead>
			<tr valign="middle" bgcolor="#EFEFEF" style="line-height:20px">
				<td width="1%" title="<?php echo Portal::language('check_all');?>">
					<input type="checkbox" value="1" id="PackageWord_all_checkbox" onclick="select_all_checkbox(this.form,'PackageWord',this.checked,'#FFFFEC','white');"<?php if(URL::get('cmd')=='delete') echo ' checked';?>></td>
				<td nowrap width="200">
					<?php Draw::title_label('id',Portal::language('id'));?>
				das</td>
				<?php if(isset($this->map['languages']) and is_array($this->map['languages'])){ foreach($this->map['languages'] as $key3=>&$item3){if($key3!='current'){$this->map['languages']['current'] = &$item3;?>
				<td nowrap>
					<?php Draw::title_label('value_'.$this->map['languages']['current']['id'],Portal::language('value').'('.$this->map['languages']['current']['code'].')');?>
				</td>
				<?php }}unset($this->map['languages']['current']);} ?><td>
					<?php Draw::title_label('package_id',Portal::language('package_id'));?>
				</td><td nowrap width="50">
					<?php Draw::title_label('time',Portal::language('time'));?>
				</td>
			</tr>
			</thead>
			<tbody>
			<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key4=>&$item4){if($key4!='current'){$this->map['items']['current'] = &$item4;?>
			<tr valign="middle" id="PackageWord_tr_<?php echo $this->map['items']['current']['id'];?>" onclick="edit_row(this,'<?php echo $this->map['items']['current']['id'];?>');">
				<td><input name="selected_ids[]" type="checkbox" value="<?php echo $this->map['items']['current']['id'];?>" title="<?php echo $this->map['items']['current']['i'];?>" onclick="select_checkbox(this.form,'PackageWord',this,'#FFFFEC','white');" <?php if(URL::get('cmd')=='delete') echo 'checked';?>></td>
				<td>
					<div id="id_<?php echo $this->map['items']['current']['id'];?>"><?php echo $this->map['items']['current']['id'];?></div></td>
					<?php if(isset($this->map['languages']) and is_array($this->map['languages'])){ foreach($this->map['languages'] as $key5=>&$item5){if($key5!='current'){$this->map['languages']['current'] = &$item5;?>
					<td>
						<div class="normal_input_text" id="value_<?php echo $this->map['languages']['current']['id'];?>_<?php echo $this->map['items']['current']['id'];?>"><?php echo $this->map['items']['current']['value_'.$this->map['languages']['current']['id']];?></div>
					</td>
					<?php }}unset($this->map['languages']['current']);} ?><td align="left"><div class="normal_input_text" id="package_id_<?php echo $this->map['items']['current']['id'];?>"><?php echo $this->map['items']['current']['package_id'];?></div></td><td nowrap ><div class="normal_input_text" id="time_<?php echo $this->map['items']['current']['id'];?>"><?php echo date('m-d-Y',$this->map['items']['current']['time']);?></div></td>
			</tr>
			<?php }}unset($this->map['items']['current']);} ?>
			</tbody>
		</table>
		<input type="hidden" name="edit_ids" value="0<?php foreach($this->map['items'] as $id=>$item)echo ','.$id;?>"/>
		<script language="javascript">
			init_search_row();
			$('search_by_id').value = '<?php echo String::string2js(URL::get('search_by_id'));?>';
			<?php if(isset($this->map['languages']) and is_array($this->map['languages'])){ foreach($this->map['languages'] as $key6=>&$item6){if($key6!='current'){$this->map['languages']['current'] = &$item6;?>
			$('search_by_value_<?php echo $this->map['languages']['current']['id'];?>').value = '<?php echo String::string2js(URL::get('search_by_value_'.$this->map['languages']['current']['id']));?>';
			<?php }}unset($this->map['languages']['current']);} ?>
			$('search_by_package_id').value = '<?php echo String::string2js(URL::get('search_by_package_id'));?>';
			$('search_by_time').value = '<?php echo String::string2js(URL::get('search_by_time'));?>';
		</script>
		<input type="button" value="  <?php echo Portal::language('Add');?>  " onclick="add_row();">
		</div>
		<table width="100%"><tr>
		<td width="100%">
			<?php echo Portal::language('select');?>:&nbsp;
			<a  onclick="select_all_checkbox(document.ListPackageWordForm,'PackageWord',true,'#FFFFEC','white');"><?php echo Portal::language('select_all');?></a>&nbsp;
			<a  onclick="select_all_checkbox(document.ListPackageWordForm,'PackageWord',false,'#FFFFEC','white');"><?php echo Portal::language('select_none');?></a>
			<a  onclick="select_all_checkbox(document.ListPackageWordForm,'PackageWord',-1,'#FFFFEC','white');"><?php echo Portal::language('select_invert');?></a>
		</td>
		<td>
			<a name="bottom_anchor" javascript:void(0)><img src="<?php echo Portal::template('core');?>/images/top.gif" title="<?php echo Portal::language('top');?>" border="0" alt="<?php echo Portal::language('top');?>"></a>
		</td>
		</tr></table>
		<input type="hidden" name="cmd" value=""/>
		<input type="hidden" name="page_no" value="<?php echo URL::get('page_no');?>"/>
		<?php 
				if((URL::get('cmd')=='delete'))
				{?>
		<input type="hidden" name="confirm" value="1" />
		
				<?php
				}
				?>
		<input type="hidden" name="page_no" value="1"/>
		<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
	</div>
</div>
<div id="suggest_box" style="position:absolute; border:1px solid black;background-color:white;display:none;"></div>
<script type="text/javascript">
document.body.onkeydown = function(evt){
	if(!evt)evt=event;
	if(default_onkeydown(evt))
	{
		if(document.all)evt.returnValue=false;
		else return false;
	}
};
function check_error()
{
	var tr = $('main_table').firstChild.nextSibling.firstChild;
	while(tr)
	{
		var div = tr.childNodes[1];
		if(div.firstChild.firstChild&&div.firstChild.firstChild.tagname)
		{
			for(var i in table_fields)
			{
				if(i)
				{
					var value = div.firstChild.firstChild.value;
					if(value!='')
					{
						if(!field_check_error(i,value,table_fields[i]))
						{
							div.firstChild.firstChild.focus();
							if(field_error_messages[i])
							{
								alert(field_error_messages[i]);
							}
							else
							{
								alert('Invalid '+i);
							}
							return false;
						}
					}
					div = div.nextSibling;
				}
			}
		}
		tr = tr.nextSibling;
	}
	return true;
}
<?php 
foreach($this->map['new_items'] as $item)
{
echo 'add_row('.String::array2js(array_values($item)).');
';
}
?>
</script>