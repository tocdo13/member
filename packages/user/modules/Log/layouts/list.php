<form name="LogListForm"  method="post">
<table cellspacing="0" cellpadding="10" width="100%">
	<tr valign="top">
		<td align="center" colspan="2">
			<h2>[[.list_title_log.]]</h2>
		</td>
	</tr>
	<tr valign="top">
		<td>
			<table cellspacing="0" width="100%">
			<tr>
				<td width="100%">
					<fieldset>
					<legend class="title">[[.search.]]</legend>
					<table>
						<tr>
						  <td nowrap>[[.keyword.]]: <input name="keyword" type="text" id="keyword" style="width:120px;" /></td>
						  <td nowrap>[[.date_from.]]: <input name="date_from" type="text" id="date_from" style="width:70px;" onchange="changevalue();" /></td>
						  <td nowrap>[[.date_to.]]: <input name="date_to" type="text" id="date_to" style="width:70px;" onchange="changefromday();" /></td>
                          
                        <td>[[.module_id.]]: </td>
                        <td><select name="module_id" id="module_id" onchange="log_filter();"></select></td>
						<td nowrap>[[.user_id.]]</td>
						<td>:</td>
						<td nowrap>
							<select name="user_id" id="user_id" onchange="log_filter();"></select></td>
                        <td nowrap>[[.room_id.]]</td>
                        <td nowrap>
                            <select name="room_id" id="room_id" onchange="log_filter();"></select>
						  	<input type="submit"  onclick="this.disable=true;" value="[[.searh.]]">
							<?php if(User::is_admin()){ ?><input name="delete_selected" type="submit"  id="delete_selected" onClick="if(!confirm('[[.are_you_sure.]]?')){return false;}" value="[[.delete_all.]]"><?php } ?>
						</td>
						</tr>  
					</table>
					</fieldset>
					<p>[[|paging|]]</p><br/>
					<table width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="#CCCCCC">
						<tr class="table-header">
							<th width="1%"><input type="checkbox" value="1" onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"></th>
							<th nowrap align="left">
								[[.time.]]
							</th>
							<th nowrap align="left">
								[[.module_id.]]
							</th>
							<th nowrap align="left">
								[[.type.]]
							</th>
							<th nowrap align="left">
								[[.user_id.]]
							</th>
							<th align="left">
								[[.title.]]
							</th>
							<th align="left" nowrap="nowrap">
								[[.note.]]
							</th>
							<th>&nbsp;</th>
							<th>&nbsp;</th>
						</tr>
						<?php $last_date = false;?><!--LIST:items-->
						<?php
						if($last_date!=[[=items.in_date=]])
						{
							$last_date=[[=items.in_date=]];
							echo '<tr bgcolor="#FFFF80"><td colspan="9">'.[[=items.in_date=]].'</td></tr>';
						}
						?><tr bgcolor="white" valign="top">
							<td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="just_click=true;"></td>
							<td nowrap align="left">
								<?php echo date('d/m/Y H:i:s',[[=items.time=]]);?>
							</td>
							<td nowrap align="left">
								[[|items.module_name|]]
							</td>
							<td nowrap align="left">
								[[|items.type|]]
							</td>
							<td nowrap align="left">
							  <strong>[[|items.user_id|]]						    </strong></td>
							
							<td align="left" width="100%">
								[[|items.title|]]
							</td>
							<td nowrap align="left">
								[[|items.note|]]
							</td>
							<td nowrap>
								<?php if(User::is_admin()){ ?>&nbsp;<a href="<?php echo Url::build_current(array('cmd'=>'edit','id'=>[[=items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/edit.gif" alt="[[.edit.]]" width="12" height="12" border="0"></a><?php } ?>
							</td>
							<td nowrap>
								<?php if(User::is_admin()){ ?>&nbsp;<a href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>[[=items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/delete.gif" alt="[[.delete.]]" width="12" height="12" border="0"></a> <?php } ?>
							</td>
						</tr>
						<tr bgcolor="#EEEEEE"><td colspan="9">[[|items.description|]]</td></tr>
						<!--/LIST:items-->
					</table>
				</td>
			</tr>
			</table>
            <br />
            <br />
			[[|paging|]]
		</td>
	</tr>
</table>	
</form>
<script>
jQuery('#date_from').datepicker();
jQuery('#date_to').datepicker();
function changevalue()
    {
        var myfromdate = $('date_from').value.split("/");
        var mytodate = $('date_to').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#date_to").val(jQuery("#date_from").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('date_from').value.split("/");
        var mytodate = $('date_to').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#date_from").val(jQuery("#date_to").val());
        }
    }
function log_filter()
{
	location='<?php echo URL::build_current(array('year','month','day'));?>'
		+(($('type').value!='')?'&type='+$('type').value:'')
		+(($('module_id').value!='')?'&module_id='+$('module_id').value:'')
		+(($('user_id').value!='')?'&user_id='+$('user_id').value:'');
}
full_screen();
</script>
