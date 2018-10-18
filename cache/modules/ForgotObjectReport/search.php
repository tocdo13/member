<script src="packages/core/includes/js/jquery/datepicker.js"></script>
<table width="100%" height="100%" bgcolor="#B5AEB5">
<tr><td>
	<link rel="stylesheet" href="skins/default/report.css">
	<div style="width:99%;height:100%;text-align:center;vertical-align:middle;border:4px double;background-color:white;">
	<div style="padding:10 40 10 80;background-image:url(skins/default/images/back_of_book.jpg);background-repeat:repeat-y;background-position:left;">
	<p>&nbsp;</p>
	<table cellSpacing=0 width="100%" style="font-family:'Times New Roman', Times, serif">
		<tr valign="top">
			<td align="left" width="65%" style="padding-left:80px;">
			<strong><?php echo HOTEL_NAME;?></strong><br />
			<?php echo HOTEL_ADDRESS;?></td>
			<td align="right" nowrap width="35%" style="padding-right:20px;">
			<strong><?php echo Portal::language('template_code');?></strong></td>
		</tr>	
	</table>
	<table width="100%">
	<tr>
	<td align="center">
		<p>&nbsp;</p>
		<font class="report_title"><?php echo Portal::language('forgot_object_list_report');?></font>

		<br>
		<form name="SearchForm" method="post">
		<?php if(User::can_admin()){?>
        <div style="margin-top:10px;"><label for="hotel_id"><?php echo Portal::language('Hotel');?>:</label> <select  name="hotel_id" id="hotel_id"><?php
					if(isset($this->map['hotel_id_list']))
					{
						foreach($this->map['hotel_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('hotel_id',isset($this->map['hotel_id'])?$this->map['hotel_id']:''))
                    echo "<script>$('hotel_id').value = \"".addslashes(URL::get('hotel_id',isset($this->map['hotel_id'])?$this->map['hotel_id']:''))."\";</script>";
                    ?>
	</select></div>
        <?php }?>        
		<table><tr><td>
		<fieldset><legend><?php echo Portal::language('time_select');?></legend>
		<table border="0">
			<tr>
                <td align="right"><?php echo Portal::language('from_day');?>:</td>
                <td><input  name="date_from" id="date_from" onchange="changevalue();" / type ="text" value="<?php echo String::html_normalize(URL::get('date_from'));?>"></td>
            </tr>
            <tr>
                <td align="right"><?php echo Portal::language('to_day');?>:</td>
                <td><input  name="date_to" id="date_to" onchange="changefromday();" / type ="text" value="<?php echo String::html_normalize(URL::get('date_to'));?>"></td>
            </tr>
		  </table>
		  </fieldset>
		  </td></tr></table>
			<table>
			<tr>
				<td><?php echo Portal::language('line_per_page');?></td>
				<td><input type="text" name="line_per_page" value="15" size="4" maxlength="2" style="text-align:right"/></td>
			</tr>
			<tr>
				<td><?php echo Portal::language('no_of_page');?></td>
				<td><input type="text" name="no_of_page" value="10" size="4" maxlength="2" style="text-align:right"/></td>
			</tr>
			<tr>
				<td><?php echo Portal::language('from_page');?></td>
				<td><input type="text" name="start_page" value="1" size="4" maxlength="4" style="text-align:right"/></td>
			</tr>
			<tr>
				<td><?php echo Portal::language('room_id');?></td>
				<td>
				<select  name="room_id" id="room_id" style="width:115"><?php
					if(isset($this->map['room_id_list']))
					{
						foreach($this->map['room_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('room_id',isset($this->map['room_id'])?$this->map['room_id']:''))
                    echo "<script>$('room_id').value = \"".addslashes(URL::get('room_id',isset($this->map['room_id'])?$this->map['room_id']:''))."\";</script>";
                    ?>
	
					</select>
				</td>
			  </tr> 
			<tr>
			 <td nowrap><?php echo Portal::language('name');?></td>
				<td nowrap>
					<input  name="name" id="name" size="18" type ="text" value="<?php echo String::html_normalize(URL::get('name'));?>"></td>
			</tr>
			<tr>	
				<td nowrap><?php echo Portal::language('object_type');?></td>
				<td><input  name="type" id="type" size="18" type ="text" value="<?php echo String::html_normalize(URL::get('type'));?>"></td>
			</tr>
			<tr>
				<td><?php echo Portal::language('status');?></td>
				<td>
					<select   name="status">
                                                <option value=""><?php echo Portal::language('all');?></option>
						<option value="0"><?php echo Portal::language('notpay');?></option>
						<option value="1"><?php echo Portal::language('pay');?></option>
                        <option value="2"><?php echo Portal::language('handle');?></option>
					</select>
				</td>
			  </tr> 
			<!--
            <tr>
			  <td nowrap><?php echo Portal::language('employee');?></td>
				<td>
					<select  name="employee_id"><?php
					if(isset($this->map['employee_id_list']))
					{
						foreach($this->map['employee_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('employee_id',isset($this->map['employee_id'])?$this->map['employee_id']:''))
                    echo "<script>$('employee_id').value = \"".addslashes(URL::get('employee_id',isset($this->map['employee_id'])?$this->map['employee_id']:''))."\";</script>";
                    ?>
	</select>
				</td>
			</tr>
            -->
			</table>
			<p>
				<input type="submit" name="do_search" value="  <?php echo Portal::language('report');?>  "/>
				<input type="button" value="<?php echo Portal::language('cancel');?>" onclick="location='<?php echo User::home_page();?>';"/>
			</p>
			<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
			<p>&nbsp;</p>
			<p>&nbsp;</p>
			<p>&nbsp;</p>
		</td></tr></table>
	</div>
	</div>
</td>
</tr></table>
<script>
    function changevalue()
    {
        var myfromdate = $('date_from').value.split("/");
        var mytodate = $('date_to').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#date_to").val(jQuery("#date_from").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('date_from').value.split("/");
        var mytodate = $('date_to').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#date_from").val(jQuery("#date_to").val());
        }
    }
    
	jQuery("#date_from").datepicker();
    jQuery("#date_to").datepicker();
</script>