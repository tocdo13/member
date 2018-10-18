<link href="skins/default/stylesheet.css" rel="stylesheet" type="text/css">
 <form name="DeleteReservationForm" method="post">
	<input type="hidden" name="id" value="[[|id|]]">
    <input type="hidden" name="cmd" value="delete">
<table cellspacing="0" width="100%">
	<tr valign="top" bgcolor="#FFFFFF">
		<td align="left">
			<table width="100%" cellpadding="15" cellspacing="0" class="table-bound">
				<tr><td width="700" nowrap  class="form-title">[[.delete_reservation.]]</td>
                  <td nowrap="nowrap" width="250" align="right">
				  	<?php if(User::can_delete(false,ANY_CATEGORY)){?><input name="delete" type="submit" value="[[.delete.]]" class="button-medium-save"><?php }?>
			<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current();?>"  class="button-medium-delete">[[.cancel.]]</a><?php }?></td>
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
            	N&#7871;u b&#7841;n x&oacute;a th&igrave; m&#7885;i th&ocirc;ng tin li&ecirc;n quan &#273;&#7871;n &#273;&#417;n &#273;&#7863;t ph&ograve;ng n&agrave;y s&#7869; b&#7883; x&oacute;a b&#7887; ho&agrave;n to&agrave;n kh&#7887;i h&#7879; th&#7889;ng &#273;&#7875; &#273;&#7843;m b&#7843;o t&iacute;nh nh&#7845;t qu&aacute;n v&#7873; d&#7919; li&#7879;u. B&#7841;n c&oacute; ch&#7855;c mu&#7889;n x&oacute;a?
                </fieldset>               </td>
        </tr>
        </table>
	</td></tr>
	</table>
	</form>    