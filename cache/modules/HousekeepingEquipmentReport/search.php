<table width="100%" height="100%" bgcolor="#B5AEB5">
<tr><td align="center">
	<link rel="stylesheet" href="skins/default/report.css">
	<div style="text-align:center;vertical-align:middle;border:4px double;background-color:white;">
	<div style="padding:10 40 10 80;background-image:url(skins/default/images/back_of_book.jpg);background-repeat:repeat-y;background-position:left;">
	<p>&nbsp;</p>
	<table cellSpacing=0 width="100%" style="font-family:'Times New Roman', Times, serif">
		<tr valign="top">
			<td align="left" width="65%" style="padding-left:80px;">
			<strong><?php echo HOTEL_NAME;?></strong><br />
			<?php echo HOTEL_ADDRESS;?></td>
			<td align="right" nowrap width="35%" style="padding-right:20px;">
			<strong><?php echo Portal::language('template_code');?></strong><br />
			<i><?php echo Portal::language('promulgation');?></i>
			</td>
		</tr>	
	</table>
	<table width="100%">
	<tr>
	<td align="center">
		<p>&nbsp;</p>
		<font class="report_title"><?php echo Portal::language('housekeeping_equipment_report');?></font>

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
                <td><?php echo Portal::language('from_date');?></td>
                <td><input  name="from_date_tan" id="from_date_tan" onchange="changevalue();"/ type ="text" value="<?php echo String::html_normalize(URL::get('from_date_tan'));?>"></td>
            </tr>
            <tr>
                <td><?php echo Portal::language('to_date');?></td>
                <td><input  name="to_date_tan" id="to_date_tan" onchange="changefromday();"/ type ="text" value="<?php echo String::html_normalize(URL::get('to_date_tan'));?>"></td>
            </tr>
		  </table>
		  </fieldset>
		  </td></tr></table>
			<table>
			<tr>
				<td align="right"><?php echo Portal::language('line_per_page');?></td>
				<td align="right">&nbsp;</td>
				<td align="left"><input  name="line_per_page" id="line_per_page" value="999" size="4" maxlength="4" style="text-align:right" class="input_number"/ type ="text" value="<?php echo String::html_normalize(URL::get('line_per_page'));?>"></td>
			</tr>
			<tr>
				<td align="right"><?php echo Portal::language('no_of_page');?></td>
				<td align="right">&nbsp;</td>
				<td align="left"><input  name="no_of_page" id="no_of_page" value="400" size="4" maxlength="4" style="text-align:right" class="input_number"/ type ="text" value="<?php echo String::html_normalize(URL::get('no_of_page'));?>"></td>
			</tr>
			<tr>
				<td align="right"><?php echo Portal::language('from_page');?></td>
				<td align="right">&nbsp;</td>
				<td align="left"><input  name="start_page" id="start_page" value="1" size="4" maxlength="4" style="text-align:right" class="input_number"/ type ="text" value="<?php echo String::html_normalize(URL::get('start_page'));?>"></td>
			</tr>
			<tr>
				<td align="right"><?php echo Portal::language('room_id');?></td>
				<td align="right">&nbsp;</td>
				<td align="left">
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
	
					</select>				</td>
			  </tr> 
			</table>
			
			<p>
				<input name="do_search"  type="submit" id="do_search" value="  <?php echo Portal::language('report');?>  "/>
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
    jQuery("#from_date_tan").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>,1 - 1, 1)});
	jQuery("#to_date_tan").datepicker({ minDate: new Date(<?php echo BEGINNING_YEAR;?>,1 - 1, 1)});
    $('from_date_tan').value='<?php if(Url::get('from_date_tan')){echo Url::get('from_date_tan');}else{ echo ('01/'.date('m').'/'.date('Y'));}?>';
    $('to_date_tan').value='<?php if(Url::get('to_date_tan')){echo Url::get('to_date_tan');}else{ echo (date('d/m/Y'));}?>';
    
    function changevalue()
    {
        var myfromdate = $('from_date_tan').value.split("/");
        var mytodate = $('to_date_tan').value.split("/");
        if(new Date(myfromdate[2]+'-'+myfromdate[1]+'-'+myfromdate[0])> new Date(mytodate[2]+'-'+mytodate[1]+'-'+mytodate[0]))
        {
            jQuery("#to_date_tan").val(jQuery("#from_date_tan").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('from_date_tan').value.split("/");
        var mytodate = $('to_date_tan').value.split("/");
        if(new Date(myfromdate[2]+'-'+myfromdate[1]+'-'+myfromdate[0])> new Date(mytodate[2]+'-'+mytodate[1]+'-'+mytodate[0]))
        {
            jQuery("#from_date_tan").val(jQuery("#to_date_tan").val());
        }
    }
</script>       