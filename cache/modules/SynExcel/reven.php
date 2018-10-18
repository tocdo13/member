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
                    <td class="form-title" width="100%"><?php echo Portal::language('Synchronyze_data_reven');?></td>
					<td width="" align="right"><a class="button-medium-save" style="padding-left:10px;" onclick="jQuery('#act').val(1);synchronize.submit();"><?php echo Portal::language('save');?></a></td>           
                </tr>
            </table>
		</td>
	</tr>
</table>
<form name="synchronize" method="post">
<table cellspacing="0" width="980px" border="1" bordercolor="#CCCCCC" style="text-align:left;margin-top:3px;">
	<tr>
    	<td><?php echo Portal::language('Date_from');?> : <input  name="date_from" id="date_from" / type ="text" value="<?php echo String::html_normalize(URL::get('date_from'));?>">&nbsp;&nbsp;&nbsp;&nbsp; <?php echo Portal::language('Date_to');?> :<input  name="date_to" id="date_to" / type ="text" value="<?php echo String::html_normalize(URL::get('date_to'));?>"></td>
    </tr>
</table>
<input type="hidden" name="act" id="act" value="0" />
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
</div>