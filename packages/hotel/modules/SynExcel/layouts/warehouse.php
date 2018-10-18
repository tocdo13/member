<script>
jQuery(function(){
	jQuery('#date_from').datepicker();
	jQuery('#date_to').datepicker();
});
</script>

<div align="center">
<table cellspacing="0" width="980px" border="1" bordercolor="#CCCCCC" style="text-align:left;margin-top:3px;">
	<tr valign="top" bgcolor="#FFFFFF">
		<td align="left">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="form-title" width="100%">[[.Synchronyze_data_warehouse.]]</td>
					<td width="" align="right"><a class="button-medium-save" style="padding-left:10px;" onclick="jQuery('#act').val(1);synchronize.submit();">[[.save.]]</a></td>           
                </tr>
            </table>
		</td>
	</tr>
</table>
<form name="synchronize" method="post">
<table cellspacing="0" width="980px" border="1" bordercolor="#CCCCCC" style="text-align:left;margin-top:3px;">
	<tr>
    	<td>[[.Date_from.]] : <input name="date_from" type="text" id="date_from" />&nbsp;&nbsp;&nbsp;&nbsp; [[.Date_to.]] :<input name="date_to" type="text" id="date_to" /></td>
    </tr>
</table>
<input type="hidden" name="act" id="act" value="0" />
</form>
</div>