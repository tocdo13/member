<script src="packages/core/includes/js/jquery/datepicker.js"></script>
<table width="100%" height="100%" bgcolor="#B5AEB5">
<tr><td>
	<link rel="stylesheet" href="skins/default/report.css">
	<div style="width:100%;height:100%;text-align:center;vertical-align:middle;border:4px double;background-color:white;">
	<div style="padding:10 40 10 80;background-image:url(skins/default/images/back_of_book.jpg);background-repeat:repeat-y;background-position:left;">
	<p>&nbsp;</p>
	<table cellSpacing=0 width="100%" style="font-family:'Times New Roman', Times, serif">
		<tr valign="top">
			<td align="left" width="100%">
			<strong><?php echo Portal::get_setting('company_name');?></strong><br /><?php echo Portal::get_setting('company_address');?></td>
			</tr>	
	</table>
	<table align="center" width="100%">
	<tr>
		<td align="center" style="border:1px dotted #CCCCCC;">
		<p>&nbsp;</p>
		<font class="report_title"><?php echo Portal::language('commission_report');?></font>
		<br><br>
		<form name="SearchForm" method="post">
		<table><tr><td>
		<fieldset>
		<table border="0" align="center" id="select_time">
			<tr>
			  <td><?php echo Portal::language('hotel');?></td>
			  <td><select  name="portal_id" id="portal_id"><?php
					if(isset($this->map['portal_id_list']))
					{
						foreach($this->map['portal_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))
                    echo "<script>$('portal_id').value = \"".addslashes(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))."\";</script>";
                    ?>
	
			    </select></td>
			  <td nowrap="nowrap">&nbsp;</td>
	        </tr>
            <tr>
			  <td><?php echo Portal::language('status');?></td>
			  <td><select  name="status" id="status"><?php
					if(isset($this->map['status_list']))
					{
						foreach($this->map['status_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('status',isset($this->map['status'])?$this->map['status']:''))
                    echo "<script>$('status').value = \"".addslashes(URL::get('status',isset($this->map['status'])?$this->map['status']:''))."\";</script>";
                    ?>
	</select></td>
			  <td nowrap="nowrap">&nbsp;</td>
	        </tr>  
			<tr>
			  <td width="1%" nowrap="nowrap"><p><?php echo Portal::language('customer');?></p>			  </td>
			  <td nowrap="nowrap"><p>
				<select  name="customer_id" id="customer_id"><?php
					if(isset($this->map['customer_id_list']))
					{
						foreach($this->map['customer_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('customer_id',isset($this->map['customer_id'])?$this->map['customer_id']:''))
                    echo "<script>$('customer_id').value = \"".addslashes(URL::get('customer_id',isset($this->map['customer_id'])?$this->map['customer_id']:''))."\";</script>";
                    ?>
	</select></p>			  </td>
			  <td nowrap="nowrap">&nbsp;</td>
			</tr>
			<tr>
			  <td nowrap="nowrap">Booking code </td>
			  <td nowrap="nowrap"><input  name="booking_code" id="booking_code" / type ="text" value="<?php echo String::html_normalize(URL::get('booking_code'));?>"></td>
			  <td nowrap="nowrap">&nbsp;</td>
			  </tr>
			</table>
			</fieldset>
		  </td></tr></table>
		  <br />
		  <table border="1" cellspacing="0" cellpadding="5" bordercolor="#CCCCCC">
            <tr>
              <td colspan="2" align="left" bgcolor="#EFEFEF"><strong><?php echo Portal::language('select_time');?></strong></td>
            </tr>
            <tr>
              <td align="right"><?php echo Portal::language('date_from');?></td>
              <td><input  name="date_from" id="date_from" onchange="changevalue();" tabindex="1" / type ="text" value="<?php echo String::html_normalize(URL::get('date_from'));?>"></td>
            </tr>
            <tr>
              <td align="right"><?php echo Portal::language('date_to');?></td>
              <td><input  name="date_to" id="date_to" onchange="changefromday();" tabindex="2" / type ="text" value="<?php echo String::html_normalize(URL::get('date_to'));?>"></td>
            </tr>
          </table>
		  <p>&nbsp;</p>
			<table>
			<tr>
				<td align="right"><?php echo Portal::language('line_per_page');?></td>
				<td><input  name="line_per_page" id="line_per_page" value="32" size="4" maxlength="2" style="text-align:right"/ type ="text" value="<?php echo String::html_normalize(URL::get('line_per_page'));?>"></td>
			</tr>
			<tr>
				<td align="right"><?php echo Portal::language('no_of_page');?></td>
				<td><input  name="no_of_page" id="no_of_page" value="50" size="4" maxlength="2" style="text-align:right"/ type ="text" value="<?php echo String::html_normalize(URL::get('no_of_page'));?>"></td>
			</tr>
			<tr>
				<td align="right"><?php echo Portal::language('from_page');?></td>
				<td><input  name="start_page" id="start_page" value="1" size="4" maxlength="4" style="text-align:right"/ type ="text" value="<?php echo String::html_normalize(URL::get('start_page'));?>"></td>
			</tr>
			</table>
			<p>
				<input type="submit" name="do_search" value="  <?php echo Portal::language('report');?>  "/>
				<input type="button" value="  <?php echo Portal::language('cancel');?>  " onclick="location='<?php echo Url::build('home');?>';"/>
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
<script type="text/javascript">
	jQuery("#date_from").datepicker();
	jQuery("#date_to").datepicker();

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
</script>