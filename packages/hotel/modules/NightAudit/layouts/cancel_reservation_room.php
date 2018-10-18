<link href="skins/default/stylesheet.css" rel="stylesheet" type="text/css">
 <form name="DeleteReservationForm" method="post">
	<input type="hidden" name="id" value="[[|id|]]">
    <input type="hidden" name="cmd" value="cancel">
<table cellspacing="0" width="100%">
	<tr valign="top" bgcolor="#FFFFFF">
		<td align="left">
			<table width="100%" cellpadding="0" cellspacing="0" class="table-bound">
				<tr height="40">
                	<td width="90%" nowrap  class="form-title">[[.cancel_reservation.]]</td>
				  	<?php if(User::can_edit(false,ANY_CATEGORY)){?><td width="1%"><input name="cancel" type="submit" value="[[.cancel.]]" class="button-medium-delete"></td><?php }?>
				</tr>
		  </table>
		</td>
	</tr>
	<tr bgcolor="#EEEEEE" valign="top">
	</tr>
	<?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><?php }?>
	<tr><td align="center">
        <table align="center" cellpadding="10">
            <tr bgcolor="#EEEEEE" valign="top">
              <td align="left">[[.code.]]: [[|id|]]</td>
            </tr>
        <tr bgcolor="#EEEEEE" valign="top">
            <td width="600">
            	<fieldset>
                <legend class="notice">[[.warning.]]</legend>
                    Bạn có chắc muốn hủy đặt phòng này không?
                </fieldset>               </td>
        </tr>
        </table>
	</td></tr>
	</table>
	</form>
    <style type="text/css">
    @media print{
        
        input[type=submit]{
            display:none;
        }
    }
    </style> 