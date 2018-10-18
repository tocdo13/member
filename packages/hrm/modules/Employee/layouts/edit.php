<span style="display:none;clear:both;">
	<span id="mi_user_group_sample">
		<span id="input_group_#xxxx#" style="width:100%;text-align:left;">
			<input  name="mi_user_group[#xxxx#][id]" type="hidden" id="id_#xxxx#">
			<span class="multi_input" id="bound_group_id_#xxxx#">
				<select  name="mi_user_group[#xxxx#][parent_id]" style="width:150px;"  id="parent_id_#xxxx#"><option value=""></option>[[|group_options|]]
				</select>
			</span>
			<span class="multi_input">
					<input  name="mi_user_group[#xxxx#][join_date]" style="width:100px;" type="text" id="join_date_#xxxx#" value="<?php echo date('d/m/Y');?>">
			</span>
			<span class="multi_input">
					<input  type="checkbox" value="1" name="mi_user_group[#xxxx#][is_active]" id="is_active_#xxxx#" style="width:50px;" checked>
			</span>
			<span class="multi_input"><span style="width:20;">
				<img src="<?php echo Portal::template('core');?>/images/buttons/delete.gif" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_user_group','#xxxx#','group_');if(document.all)event.returnValue=false; else return false;" style="cursor:hand;"/>
			</span></span><br>
		</span>
	</span> 
	<span id="mi_user_privilege_sample">
		<span id="input_group_#xxxx#" style="width:100%;text-align:left;">
			<input  name="mi_user_privilege[#xxxx#][id]" type="hidden" id="id_#xxxx#">
			<span class="multi_input" id="bound_privilege_id_#xxxx#">
				<select  name="mi_user_privilege[#xxxx#][privilege_id]" style="width:150px;"  id="privilege_id_#xxxx#"><option value=""></option>[[|privilege_options|]]
				</select>
			</span>
			<span class="multi_input"><span style="width:20;">
				<img src="<?php echo Portal::template('core');?>/images/buttons/delete.gif" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_user_privilege','#xxxx#','privilege_');if(document.all)event.returnValue=false; else return false;" style="cursor:hand;"/>
			</span></span><br>
		</span>
	</span>
</span>
<?php 
$title = (URL::get('cmd')=='edit')?Portal::language('Edit_employee'):Portal::language('Add_employee');
$action = (URL::get('cmd')=='edit')?'edit':'add';
System::set_page_title(HOTEL_NAME.' - '.$title);?>
<div class="form_bound" style="padding:5px;width:980px;">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
    	<tr>
        	<td width="100%" class="form-title"><?php echo $title;?></td>
            <td class="form_title_button"><a class="button-medium-save" href="javascript:void(0)" onclick="EditEmployeeForm.submit();">[[.save.]]</a></td>
			<td class="form_title_button"><a class="button-medium-back" href="javascript:void(0)" onclick="location='<?php echo URL::build_current();?>';">[[.back.]]</a></td>
			<?php if($action=='edit'){?>
            <td class="form_title_button"><a class="button-medium-delete" href="javascript:void(0)" onclick="location='<?php echo URL::build_current(array('cmd'=>'delete','id'));?>';">[[.Delete.]]</a></td>
			<?php }?>
        </tr>
	</table>
	<div class="form_content">
		<?php if(Form::$current->is_error()){?><?php echo Form::$current->error_messages();?><?php }?>
		<form  name="EditEmployeeForm" method="post">
		<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
		  <tr>
			<td>AC No:</td>
			<td>
			  <input name="USERID" type="text" id="USERID" style="width:150px" />    </td>
			<td>[[.full_name.]]:</td>
			<td><input name="NAME" type="text" id="NAME" style="width:150px" />    </td>
		  </tr>
		  <tr>
			<td>[[.gender.]] (*):</td>
			<td><input  name="Gender" type="radio" value="Male" <?php echo (URL::get('Gender')?'checked':'');?> />
				<label for="gender">[[.male.]]</label>
				<input  name="Gender" type="radio" value="Female" <?php echo (URL::get('Gender')?'':'checked');?> />
			  <label for="gender1">[[.female.]]</label>    </td>
			<td>No:</td>
			<td><input name="SSN" type="text" id="SSN" style="width:150px" />    </td>
		  </tr>
		  <tr>
		    <td>Office Tel:</td>
		    <td><input name="OPHONE" type="text" id="OPHONE" style="width:150px" />            </td>
			<td>Mobile No: </td>
			<td><input name="PAGER" type="text" id="PAGER" style="width:150px"/>            </td>
		  </tr>
		  <tr>
			<td>Title:</td>
			<td><input name="TITLE" type="text" id="TITLE" style="width:150px" /></td>
			<td>Privilege:</td>
			<td><select name="privilege" id="privilege"></select>    </td>
		  </tr>
		  <tr>
			<td>Date of Birth:</td>
			<td><input name="BIRTHDAY" type="text" id="BIRTHDAY" style="width:150px" />    </td>
			<td>Date of Employment: </td>
			<td>
			  <input name="HIREDDAY" type="text" id="HIREDDAY" style="width:150px" />    </td>
		  </tr>
		  <tr>
			<td>CardNumber:</td>
			<td><input name="CardNo" type="text" id="CardNo" style="width:150px" />    </td>
			<td>Department:</td>
			<td><select name="DEFAULTDEPTID" id="DEFAULTDEPTID">
			  </select>
            </td>
		  </tr>
		  <tr>
			<td>Home Add: </td>
			<td colspan="3"><input name="street" type="text" id="street" style="width:705px" /></td>
			</tr>
		</table>
	</div>
	<input type="hidden" value="1" name="confirm_edit" >
	</form>
	</div>
</div>
<script type="text/javascript">
jQuery(function(){
	jQuery('#BIRTHDAY').datepicker({dateFormat:'yy-mm-dd'});
	jQuery('#HIREDDAY').datepicker({dateFormat:'yy-mm-dd'});
});
</script>
