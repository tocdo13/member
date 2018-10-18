<?php 
$title = (URL::get('cmd')=='delete')?Portal::language('Delete_employee'):Portal::language('employee_list');
$action = (URL::get('cmd')=='delete')?'delete_employee':'employee_list';
System::set_page_title(HOTEL_NAME.' - '.$title);?>
<link href="<?php echo Portal::template('core');?>/css/category.css" rel="stylesheet" type="text/css" />
<div class="form_bound">
<div class="calendar-bound">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td width="50%">&nbsp;</td>
			<!--LIST:years-->
			<td nowrap><a class="datetime_button[[|years.selected|]]" href="<?php echo URL::build_current(array('month','day','cmd','object_id'));?>&year=[[|years.year|]]">[[|years.year|]]</a></td>
			<!--/LIST:years-->
			<td width="50%">&nbsp;</td>
		</tr>
	</table>
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td width="50%">&nbsp;</td>
			<!--LIST:months-->
			<td nowrap><a class="month_button[[|months.selected|]]" href="<?php echo URL::build_current(array('year','day','cmd','object_id'));?>&month=[[|months.month|]]" onclick="if(event.shiftKey){select_date_time_range('<?php echo URL::build_current(array('year','cmd','object_id','day'=>[[=day=]]));?>&month=',[[|month|]],[[|months.month|]]); event.returnValue=false;}">[[|months.month|]]</a></td>
			<!--/LIST:months-->
			<td width="50%">&nbsp;</td>
		</tr>
	</table>
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td width="50%">&nbsp;</td>
			<!--LIST:days-->
			<td nowrap><a class="day_button[[|days.selected|]]" href="<?php echo URL::build_current(array('month','year','cmd','object_id'));?>&day=[[|days.day|]]" onclick="if(event.shiftKey){select_date_time_range('<?php echo URL::build_current(array('year','cmd','object_id','month'=>[[=month=]]));?>&day=',[[|day|]],[[|days.day|]]); event.returnValue=false;}">[[|days.day|]]</a></td>
			<!--/LIST:days-->
			<td width="50%">&nbsp;</td>
		</tr>
		<tr height="4">
			<td>
			</td>
		</tr>
	</table>
</div>    
	<hr size="3" color="#9DC9FF">
    <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr>
            <td class="form-title" width="100%"><?php echo $title;?></td>
            <?php if(URL::get('cmd')=='delete'){?>
            <td width="1%" nowrap="nowrap"><a class="button-medium-save" href="javascript:void(0)" onclick="ListEmployeeForm.submit();">[[.Delete.]]</a></td>
            <td><a class="button-medium-back" href="<?php echo URL::build_current(array('join_date_start','join_date_end',  'active'=>isset($_GET['active'])?$_GET['active']:'', 'block'=>isset($_GET['block'])?$_GET['block']:'',  'user_id'=>isset($_GET['user_id'])?$_GET['user_id']:''));?>">[[.back.]]</a></td>
            <?php }else{ if(User::can_add(false,ANY_CATEGORY)){?>
            <td><a class="button-medium-add" href="<?php echo URL::build_current(array()+array('cmd'=>'add'));?>">[[.Add.]]</a></td>
            <?php }?>
            <?php if(User::can_delete(false,ANY_CATEGORY)){?>
            <td><a class="button-medium-delete" href="javascript:void(0)" onclick="ListEmployeeForm.cmd.value='delete';ListEmployeeForm.submit();">[[.Delete.]]</a></td>
            <?php }}?>
        </tr>
    </table>
	<div>
	<table cellspacing="0" width="100%">
	<tr bgcolor="#EFEFEF" valign="top">
		<td width="100%">
			<table bgcolor="#EFEFEF" cellspacing="0" width="100%">
			<tr>
				<td width="100%">
					<form method="post" name="SearchEmployeeForm">
					<table>
						<tr>
						  <td align="right" nowrap style="font-weight:bold">[[.full_name.]]</td>
						  <td>:</td>
						<td nowrap>
								<input name="name" type="text" id="name" style="width:200px;">
						</td>
                        <td><input type="hidden" name="page_no" value="1" />
							<input type="submit" value="   [[.search.]]   ">
						</td>
						</tr>
					</table>
					</form>
					<a name="top_anchor"></a>
					<div style="border:2px solid #FFFFFF;">
					<form name="ListEmployeeForm" method="post">					
					<table width="100%" cellpadding="2" cellspacing="0" border="1" style="border-collapse:collapse" bordercolor="#CCCCCC" bgcolor="#FFFFFF">
						<tr class="table-header">
							<th width="1%" title="[[.check_all.]]"><input type="checkbox" value="1" id="Employee_all_checkbox" onclick="select_all_checkbox(this.form, 'Employee',this.checked,'#FFFFEC','white');"<?php if(URL::get('cmd')=='delete') echo ' checked';?>></th>
                            <th width="30" align="left" nowrap >[[.stt.]]</th>
							<th width="50" align="left" nowrap >[[.id.]]</th>
                            <th width="150" align="left" nowrap >[[.full_name.]]</th>
                            <th width="50" align="left" nowrap >[[.gender.]]</th>
                            <th width="50" align="left" nowrap >[[.job_title.]]</th>
                            <th width="50" align="left" nowrap="nowrap" >[[.birthday.]]</th>
                            <th width="60" align="left" nowrap="nowrap" >[[.ophone.]]</th>
                            <th width="60" align="left" nowrap="nowrap" >[[.fphone.]]</th>
                            <th width="50" align="left" nowrap >[[.holiday.]]</th>
                            <th width="80" align="left" nowrap >[[.Check_in_time.]]</th>
                            <th width="80" align="left" nowrap >[[.Check_out_time.]]</th>
                            <th width="30" align="center" nowrap >[[.hour.]]</th>
                            <th width="100" align="left" nowrap >[[.note.]]</th>
                            <th width="20" align="left" nowrap></th>
						</tr>
						<?php $temp = '';$department_name = '';?>
						<!--LIST:items-->
						<?php if($department_name!=[[=items.department_name=]]){$department_name = [[=items.department_name=]];?>
						<tr>
							<td colspan="15" class="category-group"><strong>[[|items.department_name|]]</strong></td>
						</tr>
						<?php }?>
						<tr id="Employee_tr_[[|items.id|]]" <?php echo ([[=items.i=]]%2==0)?'bgcolor="#EFEFEF"':'';?>>
							<td width="1%" align="center"><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="select_checkbox(this.form,'Employee',this,'#FFFFEC','white');" id="Employee_checkbox" <?php if(URL::get('cmd')=='delete') echo 'checked';?>></td>
                            <td nowrap align="left">[[|items.i|]]</td>
							<td nowrap align="left">[[|items.USERID|]]</td>
                            <td nowrap align="left">[[|items.NAME|]]</td>
                            <td nowrap align="left">[[|items.Gender|]]</td>
                            <td nowrap align="left">[[|items.TITLE|]]</td>
                            <td align="left" nowrap="nowrap">[[|items.BIRTHDAY|]]</td>
                            <td align="left" nowrap="nowrap">[[|items.OPHONE|]]</td>
                            <td align="left" nowrap="nowrap">[[|items.FPHONE|]]</td>
                            <td nowrap align="left">[[|items.HOLIDAY|]]</td>
                            <td nowrap align="left" style="color:#03C;">[[|items.checkin_time|]]</td>
                            <td nowrap align="left" style="color:#03C;">[[|items.checkout_time|]]</td>
                            <td nowrap align="center"><span style="color:#03C;font-weight:bold;">[[|items.duration|]]</span></td>
                            <td nowrap align="left">[[|items.Notes|]]</td>
                            <?php if(User::can_edit(false,ANY_CATEGORY)){?>
							<td><a href="<?php echo URL::build_current();?>&cmd=edit&id=[[|items.id|]]">[[.edit.]]</a></td>
							<?php }?>
						</tr>
						<!--/LIST:items-->
					</table>
					<input type="hidden" name="cmd" value="delete"/>
					<input type="hidden" name="page_no" value="1"/>
					<!--IF:delete(URL::get('cmd')=='delete')-->
					<input type="hidden" name="confirm" value="1" />
					<!--/IF:delete-->
					</form>
					</div>
				</td>
			</tr>
			</table>
			</div>
			<table width="100%"><tr>
			<td width="100%">
				[[.select.]]:&nbsp;
				<a href="javascript:void(0)" onclick="select_all_checkbox(document.ListEmployeeForm,'Employee',true,'#FFFFEC','white');">[[.select_all.]]</a>&nbsp;
				<a href="javascript:void(0)" onclick="select_all_checkbox(document.ListEmployeeForm,'Employee',false,'#FFFFEC','white');">[[.select_none.]]</a>
				<a href="javascript:void(0)" onclick="select_all_checkbox(document.ListEmployeeForm,'Employee',-1,'#FFFFEC','white');">[[.select_invert.]]</a>
			</td>
			<td>
				<a name="bottom_anchor" href="#top_anchor"><img src="<?php echo Portal::template('core');?>/images/top.gif" title="[[.top.]]" border="0" alt="[[.top.]]"></a>
			</td>
			</tr></table>
		</td>
</tr>
	</table>	
	</div>
</div>
