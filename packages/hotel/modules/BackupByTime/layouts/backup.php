<?php System::set_page_title(HOTEL_NAME);?><link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<div><?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?></div>
<div class="setting-bound1">
	<form name="TableBackup" method="post">
		<br/>
        <div align="right">
            <div align="center" style="font-size:24px;font-weight:bold">[[.Backup_data_at.]] <?php echo date('H:i d/m/Y',time());?></div>
        </div>
        <br />

    	<fieldset>
        	<legend class="title">[[.select.]]</legend>
            [[.select_department.]]<select name="department" id="department" onchange="this.form.act.value ='select_department';this.form.submit();"></select>
            <input name="act" type="hidden" id="act" />
            [[.from_date.]]<input name="date_from" type="text" id="date_from" style="width: 100px;" />
            [[.to_date.]]<input name="date_to" type="text" id="date_to" style="width: 100px;" />
            <input  name="backup" type="submit" id="backup" value=" [[.backup.]] "/>
            
            <input  name="restore" type="button" id="restore" value=" [[.restore.]] " onclick="window.location='<?php echo Url::build_current(array('cmd'=>'restore'));?>';"/>
        </fieldset>
        <br />
        
		<table width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="#CCCCCC" style="border-collapse:collapse">
            <tr bgcolor="#EEEEEE" style="line-height:20px">
				<th title="[[.check_all.]]" width="10%" align="center">
                    <input type="checkbox" value="1" id="TableBackup_all_checkbox" checked="checked" onclick="select_all_checkbox(this.form, 'TableBackup',this.checked,'#FFFFEC','red');"/>
                </th>
				<th>[[.table.]]</th>
            </tr>
            <?php for($i = 0; $i < count($this->map['table']); $i++) {?>
            <tr style="cursor:hand;">
            	<td align="center">
                    <input name="selected_ids[]" type="checkbox" checked="checked" value="<?php echo  $this->map['table'][$i]  ; ?>" onclick="select_checkbox(this.form,'TableBackup',this,'#FFFFEC','white');"  />
                </td>
            	<td><?php echo strtoupper($this->map['table'][$i]); ?></td>
            </tr>
            <?php }?>
		</table>
    
        [[.select.]]:
    	<a  onclick="select_all_checkbox(document.TableBackup,'TableBackup',true,'#FFFFEC','white');">[[.select_all.]]</a>
    	<a  onclick="select_all_checkbox(document.TableBackup,'TableBackup',false,'#FFFFEC','white');">[[.select_none.]]</a>
    	<a  onclick="select_all_checkbox(document.TableBackup,'TableBackup',-1,'#FFFFEC','white');">[[.select_invert.]]</a>
  </form>				
</div>
<script>
    jQuery("#date_from").datepicker();
    jQuery("#date_to").datepicker();
</script>
    