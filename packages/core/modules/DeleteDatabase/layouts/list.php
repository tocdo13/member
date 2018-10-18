<script>
	DatabaseList_array_items = {
		'length':'<?php echo sizeof(MAP['items']);?>'
<!--LIST:items-->
,'[[|items.i|]]':'[[|items.id|]]'
<!--/LIST:items-->
	}
</script>
<link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<form name="DatabaseListForm" method="post">   
<div class="body">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr>
        	<td width="75%" class="form-title">[[.Database.]]</td>
    		<td align="left"  width="100px">
                <a class="button-medium-delete" onclick="if(confirm('[[.are_you_sure.]]?')){DatabaseListForm.submit();}else{return false;}">[[.delete.]]</a>
    		</td>
        </tr>
    </table>
    <br />
    <fieldset style="width:84%;">
        <legend class="title"></legend>
    	<table>
    		<tr>
                <td nowrap="nowrap">[[.hotel.]]</td>
                <td style="margin: 0;"><select name="portal_id" id="portal_id"></select></td>
    			<td align="right" >[[.from_day.]]</td>
                <td>:</td>
                <td>
                    <input name="from_date" type="text" id="from_date" size="12"/>
                    [[.to_date.]]
                    <input name="to_date" type="text" id="to_date" size="12"/>
                </td>
    		</tr>
    	</table>
    </fieldset>
    <br />
	<table width="85%" cellpadding="2" cellspacing="0" border="1" bordercolor="#CCCCCC">	
		<tr class="table-header">
			<th width="10px" title="[[.check_all.]]">
                <input type="checkbox" value="1" id="DatabaseList_check_0" onclick="check_all('DatabaseList','DatabaseList_array_items','#FFFFEC',this.checked);"/>
            </th> 
			<th width="10px">[[.code.]]</th>
			<th width="350px">[[.name.]]</th>
        </tr>
		<!--LIST:items-->
		<tr bgcolor="white" valign="middle" <?php Draw::hover('#E2F1DF');?>style="cursor:pointer;" id="DatabaseList_tr_[[|items.id|]]">
			<td width="10px" align='center'>
                <input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="tr_color = clickage('DatabaseList','[[|items.i|]]','DatabaseList_array_items','#FFFFEC');" id="DatabaseList_check_[[|items.i|]]"/>
            </td>
			<td width="10px">[[|items.code|]]</td>
            <td width="350px">[[|items.name|]]</td>
        </tr>
		<!--/LIST:items-->
        
	</table>
	<div>
		<div style="float:left;padding:2px 2px 2px 10px;" ><strong>[[.Select.]]:</strong></div>
		<div style="float:left;padding:2px 2px 2px 10px;font-weight:400;color:blue;cursor:pointer;" onclick="check_all('DatabaseList','DatabaseList_array_items','#FFFFEC',1);">[[.All.]]</div>
		<div style="float:left;padding:2px 2px 2px 10px;font-weight:400;color:blue;cursor:pointer;" onclick="check_all('DatabaseList','DatabaseList_array_items','#FFFFEC',0);">[[.None.]]</div>
		<div style="float:left;padding:2px 2px 2px 10px;font-weight:400;color:blue;cursor:pointer;" onclick="select_invert('DatabaseList','DatabaseList_array_items','#FFFFEC');">[[.select_invert.]]</div>
	</div>	
</div>
</form>
<script>
	jQuery('#from_date').datepicker();//({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });;
	jQuery('#to_date').datepicker();//({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });;
</script>