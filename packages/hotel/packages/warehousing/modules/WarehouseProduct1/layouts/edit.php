<?php System::set_page_title(HOTEL_NAME);?>
<div class="wh_product_type-bound">
<form name="EditWarehouseProductForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td width="55%" class="form-title">[[|title|]]</td>
            <td width="20%" align="right" nowrap="nowrap">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><input name="save" type="submit" value="[[.Save.]]" class="button-medium-save"><?php }?>
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="<?php echo Url::build_current(array('action'));?>"  class="button-medium-delete">[[.cancel.]]</a><?php }?>
            </td>
        </tr>
    </table>
	<div class="content">
		<?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><?php }?>
        <br />
		<fieldset>
			<table border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td class="label">[[.category.]]:</td>
					<td><select name="category_id" id="category_id"></select></td>
				</tr>
                <tr>
                  <td class="label">[[.code.]](*):</td>
                  <td><input name="id" type="text" id="id" style="width:200px;" /></td>
                </tr>
                <tr>
					<td class="label">[[.vietnamese_name.]](*):</td>
					<td><input name="name_1" type="text" id="name_1" style="width:200px;"></td>
				</tr>
				<tr>
                  <td class="label">[[.english_name.]]:</td>
				  <td><input name="name_2" type="text" id="name_2" style="width:200px;"></td>
			  </tr>
				<tr>
                  <td class="label">[[.type.]]:</td>
				  <td><select name="type" id="type">
				    </select></td>
			  </tr>
				<tr>
                  <td class="label">[[.unit.]]:</td>
				  <td><select name="unit_id" id="unit_id">
				    </select></td>
			  </tr>
				<tr>
				  <td class="label">[[.price.]]:</td>
				  <td><input name="price" type="text" id="price" style="width:200px;"></td>
			  </tr>
				<tr>
                  <td class="label">[[.start_term_quantity.]]:</td>
				  <td><input name="start_term_quantity" type="text" id="start_term_quantity" style="width:200px;" /></td>
			  </tr>
			</table>
	  </fieldset>	
	</div>
</form>	
</div>
<script type="text/javascript">
	jQuery("#arrival_time").datepicker();
	jQuery("#departure_time").datepicker();
</script>