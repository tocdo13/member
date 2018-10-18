<script>
	DataImport_array_items = {
		'length':'<?php echo sizeof(MAP['items']);?>'
		<!--LIST:items-->
		,'[[|items.i|]]':'[[|items.id|]]'
		<!--/LIST:items-->
	}
</script>
<form name="DataImportForm" enctype="multipart/form-data" method="post">
<div>
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
			<td class="form-title" width="50%">[[.data_import.]]</td>
			<td width="20%" align="right"></td>
		</tr>
	</table>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td align="center"><br />
			<table border="1" cellspacing="0" cellpadding="10" bordercolor="#CCCCCC">
			<tr>
			  <td><img src="packages/core/skins/default/images/buttons/save.jpg" /></td>
			  <td>[[.import_from_excel.]]:</td>
			  <td align="center"><input name="file" type="file" /></td>
			  <td>[[.sheet.]]: <input name="sheet" type="text" id="sheet" style="width:50px;" /></td>
			  <td><input name="import" type="submit" value="[[.import_data.]]"></td>
			</tr>
			<tr>
			  <td>&nbsp;</td>
			  <td>[[.type.]]
                <select name="type" id="type">
                </select>
              </td>
			  <td align="center">&nbsp;</td>
			  <td>&nbsp;</td>
			  <td>&nbsp;</td>
			  </tr>
			</table>
		</td>
	  </tr>
	</table>
</div>
</form>