<?php System::set_page_title(HOTEL_NAME);?><link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<div><?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?></div>
<div class="setting-bound1">
	<form name="TableBackup" method="post">
		<br><div align="right"><div align="center" style="font-size:24px;font-weight:bold">[[.Backup_data_at.]] <?php echo date('h:i d/m/Y',time());?></div><input  name="backup" type="submit" id="backup" value=" [[.backup.]] "></div>
		<div>
			[[.select.]]:&nbsp;
			<a  onclick="select_all_checkbox(document.TableBackup,'TableBackup',true,'#FFFFEC','white');">[[.select_all.]]</a>&nbsp;
			<a  onclick="select_all_checkbox(document.TableBackup,'TableBackup',false,'#FFFFEC','white');">[[.select_none.]]</a>
			<a  onclick="select_all_checkbox(document.TableBackup,'TableBackup',-1,'#FFFFEC','white');">[[.select_invert.]]</a>
		</div>
		<table width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="#CCCCCC" style="border-collapse:collapse">
			<tr valign="middle" bgcolor="#EEEEEE" style="line-height:20px">
				<th  title="[[.check_all.]]" align="center"><input type="checkbox" value="1" id="TableBackup_all_checkbox" onclick="select_all_checkbox(this.form, 'TableBackup',this.checked,'#FFFFEC','white');"></th>
				<th  title="[[.check_all.]]">[[.table.]]</th>
				<th align="left" nowrap >[[.tablespace_name.]]</th>
				<th  align="left" nowrap >[[.status.]]</th>
				<th  align="left" nowrap >[[.num_rows.]]</th>
				<th  align="left" nowrap >[[.blocks.]]</th>
				<th  align="left" nowrap >[[.avg_row_len.]]</th>
				<th  align="left" nowrap >[[.cache.]]</th>
				<th  align="right" nowrap >[[.sample_size.]]	</th>
				<th  align="right" nowrap >[[.buffer_pool.]]</th>
				<th  align="right" nowrap >[[.min_extents.]]</th>
				<th  align="right" nowrap >[[.max_extents.]]</th>
		  </tr>
		  <?php $i=0;?>
			<!--LIST:tables-->
		  <?php $i++;?>
			<tr bgcolor="<?php {echo 'white';}?>" valign="middle" style="cursor:hand;" id="TelephoneList_tr_[[|tables.id|]]">
				<td align="center"><input name="selected_ids[]" type="checkbox" value="[[|tables.id|]]" onclick="select_checkbox(this.form,'TableBackup',this,'#FFFFEC','white');"  /></td>
				<td>[[|tables.id|]]</td>
				<td nowrap align="left">
						 [[|tables.tablespace_name|]]								</td>
				<td align="left" nowrap>
						[[|tables.status|]]						  </td>
				<td align="left" nowrap>[[|tables.num_rows|]] </td>
				<td align="left" nowrap>[[|tables.blocks|]] </td>
				<td align="left" nowrap>[[|tables.avg_row_len|]] </td>
				<td align="left" nowrap>[[|tables.cache|]] </td>
				<td align="right" nowrap>
						[[|tables.sample_size|]]						  </td>
					<td align="right" nowrap>[[|tables.buffer_pool|]]'s</td>
					<td align="right" nowrap>[[|tables.min_extents|]]</td>
					<td nowrap align="right">
						[[|tables.max_extents|]]								</td>
		  </tr>
			<!--/LIST:tables-->
			<tr>	
				<td colspan="12"><b>[[.Total_table.]] : <?php echo $i;?></b></td>
			</tr>
		</table>
		[[.select.]]:&nbsp;
	<a  onclick="select_all_checkbox(document.TableBackup,'TableBackup',true,'#FFFFEC','white');">[[.select_all.]]</a>&nbsp;
	<a  onclick="select_all_checkbox(document.TableBackup,'TableBackup',false,'#FFFFEC','white');">[[.select_none.]]</a>
	<a  onclick="select_all_checkbox(document.TableBackup,'TableBackup',-1,'#FFFFEC','white');">[[.select_invert.]]</a>
  </form>				
</div>
    