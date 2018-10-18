<form method="post" name="EditMinibarImportForm">
<div style="background-color:#FFFFFF;width:100%;">
    <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr>
    		<td align="left" class="" style="text-transform: uppercase; font-size: 18px;"><i class="fa fa-pencil w3-text-orange" style="font-size: 24px;"></i> [[.edit_room_cleanup.]]</td>
    		<td align="right" style="padding-right: 50px;">
                <input name="update" type="submit" value="[[.Save_and_close.]]" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; margin-right: 5px;"/>
                <input name="back" type="button" value="[[.back.]]" class="w3-btn w3-green" style="text-transform: uppercase;" onclick="window.location='<?php echo Url::build_current(); ?>'"/>
            </td>        
    	</tr>
    </table>

    
    <table border="1" cellspacing="0" cellpadding="5" width="65%"align="left" bordercolor="#C6E2FF" bgcolor="#FFFFFF" style="border-collapse:collapse;">
        <tr class="w3-light-gray" style="height: 24px; text-transform: uppercase;">  
            <td width="100px">[[.room_name.]]</td>
            <td width="100px">[[.status.]]</td>
            <td>[[.note.]]</td>
            <td>[[.complete.]]</td>
        </tr> 
        <!--LIST:item-->
        <tr>
            <td>
                Room [[|item.room_name|]]
                <input name="id" id="id" type="hidden" value="[[|item.id|]]"/>
            </td>
            <td>[[|item.status|]]</td>
            <td><input name="note_[[|item.id|]]" id="note_[[|item.id|]]" type="text" value="[[|item.note|]]" style="width: 500px;"/></td>
            <td><input name="complete_[[|item.id|]]" id="complete_[[|item.id|]]" type="checkbox" value="[[|item.id|]]"/></td>
        </tr>
        <!--/LIST:item-->
    </table>
</div>
</form>
<script>
	function check_all()
	{
		 if($('room').checked==true)
		 {
		 	<!--LIST:rooms-->
				$('check_[[|rooms.id|]]').checked=true;
			<!--/LIST:rooms-->
		 }
		 else
		 {
		 	<!--LIST:rooms-->
				$('check_[[|rooms.id|]]').checked=false;
			<!--/LIST:rooms-->
		 }
	}
    function check_value(obj)
    {
        if(jQuery(obj).val()=='')
            jQuery(obj).val('0');
    }
</script>