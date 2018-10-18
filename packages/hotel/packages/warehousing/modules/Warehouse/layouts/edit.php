<?php System::set_page_title(HOTEL_NAME);?>
<div class="warehouse-bound">
<form name="EditTourForm" method="post">
	<table cellpadding="15" cellspacing="0" width="60%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="60%" class="" style="text-transform: uppercase; text-decoration: none; padding-left: 15px; font-size: 18px;"><i class="fa fa-plus-square w3-text-orange" style="font-size: 26px;"></i> [[|title|]]</td>
            <td width="40%" align="right" nowrap="nowrap" style="padding-right: 30px;">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><input name="save" type="submit" value="[[.Save_and_close.]]" class="w3-btn w3-orange" style="text-transform: uppercase; margin-right: 5px;"/><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current();?>"  class="w3-btn w3-green" style="text-transform: uppercase; text-decoration: none;">[[.back.]]</a><?php }?>
            </td>
        </tr>
    </table>
	<div class="content">
		<?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><?php }?>
        <br />
			<table border="0" cellspacing="0" cellpadding="2">

                <tr>
					<td class="label">[[.parent.]]:</td>
					<td><select name="parent_id" id="parent_id" style="height: 24px;"></select></td>
                    <td class="label">[[.code.]] <span style="color: red;">(*)</span>:</td>
					<td>
                    <?php if(Url::get('cmd')=='edit'){?>
                    <input  name="code" type="text" id="code" size="40" value="[[|code|]]" readonly="readonly" style="width: 150px;height: 24px;" />
                    <?php }else{?>
                    <input name="code" type="text" id="code" size="40" style="width: 150px;height: 24px;" />
                    <?php }?>
                    </td>
                    <td class="label">[[.name.]] <span style="color: red;">(*)</span>:</td>
					<td><input name="name" type="text" id="name" size="40" style="width: 200px;height: 24px;"/></td>
				</tr>
                <tr>
					<td class="label">[[.privilege.]]:</td>
					<td><input name="module_name" type="text" id="module_name" disabled="disabled" style="height: 24px;"/></td>
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