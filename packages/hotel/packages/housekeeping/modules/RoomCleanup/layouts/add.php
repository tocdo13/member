<form method="post" name="EditMinibarImportForm">
<div style="background-color:#FFFFFF;width:100%;">
    <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr>
    		<td align="left" class="" style="text-transform: uppercase; font-size: 18px;"><i class="fa fa-plus-square w3-text-orange" style="font-size: 24px;"></i> [[.add_room_cleanup.]]</td>
    		<td align="right" style="padding-right: 50px;">
                <input name="update" type="submit" value="[[.Save_and_close.]]" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; margin-right: 5px;"/>
                <input name="back" type="button" value="[[.back.]]" class="w3-btn w3-green" onclick="window.location='<?php echo Url::build_current(); ?>'"style="text-transform: uppercase;"/>
            </td>        
    	</tr>
    </table>
    
    
    <table border="1" cellspacing="0" cellpadding="5" width="65%"align="left" bordercolor="#C6E2FF" bgcolor="#FFFFFF" style="border-collapse:collapse;">
        <tr class="w3-light-gray w3-border" style="height: 24px; text-transform: uppercase;">  
            <td width="100px">[[.room_name.]]</td>
            <td width="100px">[[.clean.]]</td>
            <td>[[.note.]]</td>
        </tr> 
        <!--LIST:rooms-->
        <tr>
            <td>
                Room [[|rooms.name|]]
                <input name="[[|rooms.id|]]" id="[[|rooms.id|]]" type="hidden" value="[[|rooms.id|]]"/>
            </td>
            <td><input name="check_[[|rooms.id|]]" id="check_[[|rooms.id|]]" type="checkbox" value="[[|rooms.id|]]"/></td>
            <td><input name="note_[[|rooms.id|]]" id="note_[[|rooms.id|]]" type="text" style="width: 500px;"/></td>
        </tr>
        <!--/LIST:rooms-->
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
</script>