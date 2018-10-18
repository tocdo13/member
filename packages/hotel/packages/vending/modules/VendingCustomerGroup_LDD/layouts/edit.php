<?php System::set_page_title(HOTEL_NAME);?>
<div class="warehouse-bound">
<form name="EditTourForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="55%" class="form-title">[[|title|]]</td>
            <td width="20%" align="right" nowrap="nowrap">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><input name="save" type="submit" value="[[.Save.]]" class="button-medium-save"><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current();?>"  class="button-medium-back">[[.back.]]</a><?php }?>
            </td>
        </tr>
    </table>
	<div class="content">
		<?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><?php }?>
        <br />
			<table border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="label">[[.parent.]]:</td>
					<td><select name="parent_id" id="parent_id"></select></td>
				</tr>
                <tr>
					<td class="label">[[.code.]](*):</td>
					<td>
                    <?php if(Url::get('cmd')=='edit'){?>
                    <input  name="code" type="text" id="code" size="40" value="[[|code|]]" readonly="readonly" style="background-color:#CCC" />
                    <?php }else{?>
                    <input name="code" type="text" id="code" size="40" />
                    <?php }?>
                    </td>
				</tr>
                <tr>
					<td class="label">[[.name.]](*):</td>
					<td><input name="name" type="text" id="name" size="40"></td>
				</tr>
			</table>
	</div>
</form>	
</div>
<?php if(Url::get('cmd')=='edit'){?>
<style type="text/css">
#code{
		
}
</style>
<?php }?>
<script type="text/javascript" src="packages/core/includes/js/jquery/jquery.alphanumeric.pack.js"></script>