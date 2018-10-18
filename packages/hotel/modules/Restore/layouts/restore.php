<?php System::set_page_title(HOTEL_NAME);?><link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<div><?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?></div>
<div class="setting-bound1">
	<form name="TableRestore" method="post">
		<br><div align="right"><div align="center" style="font-size:24px;font-weight:bold">[[.Recovery_data_at.]]  <?php echo date('h:i d/m/Y',time());?></div><select name="folders" id="folders" onchange="TableRestore.submit();"></select>&nbsp;&nbsp;<input  name="Restore" type="submit" id="Restore" value=" [[.Restore.]] "></div>
		<div>
			[[.select.]]:&nbsp;
			<a  onclick="select_all_checkbox(document.TableRestore,'TableRestore',true,'#FFFFEC','white');">[[.select_all.]]</a>&nbsp;
			<a  onclick="select_all_checkbox(document.TableRestore,'TableRestore',false,'#FFFFEC','white');">[[.select_none.]]</a>
			<a  onclick="select_all_checkbox(document.TableRestore,'TableRestore',-1,'#FFFFEC','white');">[[.select_invert.]]</a>
		</div>
		<table width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="#CCCCCC" style="border-collapse:collapse">
			<tr valign="middle" bgcolor="#EEEEEE" style="line-height:20px">
				<th width="3%" align="center"  title="[[.check_all.]]"><input type="checkbox" value="1" id="TableRestore_all_checkbox" onclick="select_all_checkbox(this.form, 'TableRestore',this.checked,'#FFFFEC','white');"></th>
				<th width="26%"  title="[[.check_all.]]">[[.table.]]</th>
				<th width="35%" align="left" nowrap >[[.folder.]]</th>
				<th width="22%"  align="left" nowrap >[[.last_modified.]]</th>
		        <th width="14%"  align="left" nowrap >[[.file_size.]]</th>
		  </tr>
		  <?php $i=0;?>
			<?php foreach([[=tables=]] as $key=>$value){?>
			  <?php $i++;?>
			<tr bgcolor="<?php {echo 'white';}?>" valign="middle" style="cursor:hand;" id="TelephoneList_tr_[[|items.id|]]">
				<td align="center"><input name="selected_ids[]" type="checkbox" value="<?php echo $key;?>" onclick="select_checkbox(this.form,'TableRestore',this,'#FFFFEC','white');"  /></td>
				<td><a href="<?php echo $key;?>" style="text-transform:uppercase;font-weight:bold" target="_blank"><?php echo str_replace('.sql','',$value);?></a></td>
				<td nowrap align="left">
						 [[|path|]]								</td>
				<td align="left" nowrap> <?php echo date('d/m/Y h:i:s',filemtime($key));?></td>
		        <td align="left" nowrap><?php echo @filesize($key);?></td>
		  </tr>
			<?php }?>
			<tr>
				<td colspan="5"><b>[[.Total_table.]] :	<?php echo $i;?></b></td>
			</tr>
		</table>
		[[.select.]]:&nbsp;
	<a  onclick="select_all_checkbox(document.TableRestore,'TableRestore',true,'#FFFFEC','white');">[[.select_all.]]</a>&nbsp;
	<a  onclick="select_all_checkbox(document.TableRestore,'TableRestore',false,'#FFFFEC','white');">[[.select_none.]]</a>
	<a  onclick="select_all_checkbox(document.TableRestore,'TableRestore',-1,'#FFFFEC','white');">[[.select_invert.]]</a>
  </form>				
</div>
    