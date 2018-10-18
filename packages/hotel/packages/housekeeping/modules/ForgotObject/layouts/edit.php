
<script>
object_type_list = <?php echo String::array2js([[=object_type_list=]]);?>;
</script>
<DIV ID="calenderdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></DIV>
</script>
<SCRIPT LANGUAGE="JavaScript">
	document.write(getCalendarStyles());
	cal = new CalendarPopup('calenderdiv');
	cal.showNavigationDropdowns();
</SCRIPT>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('edit_title'));?><span style="display:none">
</span>
<form name="EditForgotObjectForm" method="post">
<table cellpadding="15" cellspacing="0" width="80%" border="0" bordercolor="#CCCCCC" class="table-bound">
	<tr>
		<td width="60%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-edit w3-text-orange" style="font-size: 26px;"></i> [[.edit_infor_forgot_object.]]</td>
		<td style="width: 40%; text-align: left;">
			<input type="submit" value="[[.Save_and_close.]]" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; margin-right: 5px;" />
		<input type="button" value="[[.List.]]" class="w3-btn w3-lime" style="text-transform: uppercase;" onclick="window.location='<?php echo URL::build_current(array('forgot_object_time_start','forgot_object_time_end')); ?>'" />
		</td>
	</tr>
</table>
<table cellspacing="0" width="100%">
	<?php 
		if(Form::$current->is_error())
		{
			echo Form::$current->error_messages();
		}
	?>
	<tr  valign="middle">
	<td align="right">&nbsp;</td>
	<td ><div style="width:10px;line-height:24px">&nbsp;</div></td>
	<td >&nbsp;</td>
	</tr>
	<tr>
	<td colspan="3">
    <table style="margin-bottom: 20px;">
        <tr  valign="middle">
            <td align="right;" nowrap>[[.forgot_time.]]</td>
            <td>
				<select name="hour">
        		<?php
        			for ($i=0;$i<=23;$i++)
        			{
        				if ($i==Url::get('hour'))
        				{
        					echo '<option value='.$i.' selected="selected">'.$i.'</option>';
        				}else
        				{
        					echo '<option value='.$i.'>'.$i.'</option>';
        				}
        			}
        		?></select>&nbsp;<strong>[[.hour.]]</strong>
        	  	<select  name="minute">
        		<?php
        			for ($i=0;$i<=59;$i++)
        			{
        				if ($i==Url::get('minute'))
        				{
        					echo '<option value='.$i.' selected="selected">'.$i.'</option>';
        				}else
        				{
        					echo '<option value='.$i.'>'.$i.'</option>';
        				}
        			}
        		?></select>&nbsp;<strong>[[.minute.]]</strong> &nbsp;<strong>[[.date.]]</strong><input name="time" type="text" id="time" style="width: 70px; "/>
            </td>
			<td align="right" nowrap><strong>[[.status.]]</strong></td>
			
			<td >
			<select  name="status">
			<?php if(Url::get('status')==0) { ?>
				<option value="0" selected="selected">[[.notpay.]]</option>
                <option value="1">[[.pay.]]</option>
                <option value="2">[[.handled.]]</option>
			<?php }else if(Url::get('status')==1){ ?>
				<option value="0">[[.notpay.]]</option>
                <option value="1" selected="selected">[[.pay.]]</option>
                <option value="2">[[.handled.]]</option>
			<?php } else { ?>
				<option value="0">[[.notpay.]]</option>
                <option value="1">[[.pay.]]</option>
                <option value="2" selected="selected">[[.handled.]]</option>
            <?php } ?>
            </select>			
            </td>	
            <td align="right"><strong>[[.date_paid.]]</strong></td>
                <script>var inputs = document.getElementsByTagName('input');
                var anchors = document.getElementsByTagName('a');
                anchors[anchors.length-1].input = inputs[inputs.length-1];
                </script>            
            <td><input name="date_paid" type="text" id="date_paid" style="width: 80px;"/></td>
			<td align="right"><strong>[[.room_id.]]</strong></td>
			<td align="center"><span style="line-height:24px;">:</span></td>
            <td style="color: red; font-size: 13px;">
                <!--<select name="room_id" id="room_id"></select>-->
                [[|room_info|]]
            </td>
			<td align="right"><strong>[[.employee_id.]]</strong></td>
			<td align="center"><span style="line-height:24px;">:</span></td>
            <td >
				<input name="employee_name" type="text" id="employee_name" value="<?php if(isset($_REQUEST['employee_name'])){echo $_REQUEST['employee_name'] ;} ?>" size="18"/>
				<a href="<?php echo Url::build('employee_profile',array('cmd'=>'add','href'=>$_SERVER['QUERY_STRING']));?>" target="_blank"></a>
            </td>
		</tr>
	</table>
	<table cellpadding="4" cellspacing="0">
	<input type="hidden" name="add_items" value="" id="add_items"/>
		<tr  valign="middle" class="w3-light-gray" style="text-transform: uppercase;">
			<td style="width: 200px;">[[.name.]]</td>
			<td >[[.object_type.]]</td>
            <td style="width: 70px;" >[[.quantity.]]</td>
			<td style="width: 70px;">[[.unit.]]</td>
            <td style="width: 70px;">[[.object_code.]]</td>
            <td >[[.reason.]]</td>
            <td >[[.position.]]</td>
            <td >[[.guest_name.]]</td>
            <td >[[.company_name.]]</td>   
		</tr>
		<tr valign="middle">
            <td ><input name="name" type="text" id="name"style="width: 200px; height: 24px;" value="<?php if(isset($_REQUEST['name'])){echo $_REQUEST['name'] ;} ?>" /></td>
			<td ><input name="object_type" width="70px" style=" height: 24px;" type="text" id="object_type" value="<?php if(isset($_REQUEST['object_type'])){echo $_REQUEST['object_type'] ;} ?>" onkeyup="update_suggest_box(this,object_type_list);" onkeydown="select_suggest(event,object_type_list)" onblur="$('suggest_box').style.display='none';"/></td>	
            <td ><input name="quantity" type="text" onKeyPress="return isNumberKey(event)" id="quantity" style="width: 70px; height: 24px;" /></td>
            <td ><input name="unit" type="text" id="unit" style="width: 70px; height: 24px;" value="<?php if(isset($_REQUEST['unit'])){echo $_REQUEST['unit'] ;} ?>"/></td>
            <td ><input name="object_code" type="text" id="object_code" style="width: 70px; height: 24px;"/></td>
            <td ><input name="reason" type="text" id="reason"  style=" height: 24px;"/></td>
            <td ><input name="position" type="text" id="position" style=" height: 24px;" /></td>
            <td ><input name="guest_name" type="text" id="guest_name" style=" height: 24px;" /></td>
            <td ><input name="company_name" type="text" id="company_name" style=" height: 24px;" /></td>           
        </tr>
		</table>        
	  </td>
	</tr>
	</table>
	</form>
<div id="suggest_box" style="position:absolute; border:1px solid black;background-color:white;display:none;"></div>
<script type="text/javascript">
	jQuery('#time').datepicker({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
	jQuery('#date_paid').datepicker({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
    function isNumberKey(evt)
 {
 var charCode = (evt.which) ? evt.which : event.keyCode
 if (charCode > 31 && (charCode < 48 || charCode > 57))
 return false;
 return true;
 }
</script>