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
			
			</td>
	</tr>	
</table>
<table width="100%" cellpadding="0" cellspacing="0">
<tr><td width="80px">&nbsp;</td>
<td align="center">
<p>&nbsp;</p>
<font class="report_title"><?php echo Portal::language('housekeeping_daily_report');?></font>
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
<fieldset><legend class="title"><?php echo Portal::language('time_select');?></legend>
  <table border="0">
	<tr>
        <td align="right"><?php echo Portal::language('from_day');?>:</td>
        <td><input  name="date_from" id="date_from" onchange="changevalue();" / type ="text" value="<?php echo String::html_normalize(URL::get('date_from'));?>"></td>
    </tr>
    </table>
    <fieldset>
    <legend><?php echo Portal::language('CHECK_FLOOR');?></legend>
    <table>
    <tr>
        <th><input type="checkbox" value="1" id="ManageUser_all_checkbox" onclick="jQuery('.check_boox').attr('checked',this.checked);" />
        <label><?php echo Portal::language('CHECK_ALL');?></label>
        </th>
    </tr>
	<tr>
	  <td colspan="2">
		<?php 
				if((isset($this->map['floors'])))
				{?>
		<?php if(isset($this->map['floors']) and is_array($this->map['floors'])){ foreach($this->map['floors'] as $key1=>&$item1){if($key1!='current'){$this->map['floors']['current'] = &$item1;?>
		<input  name="floor[]" type="checkbox" id="floor<?php echo $this->map['floors']['current']['id'];?>" value="<?php echo $this->map['floors']['current']['id'];?>" class="check_boox"><label for="floor<?php echo $this->map['floors']['current']['id'];?>">Floor <?php echo $this->map['floors']['current']['id'];?></label><br />
		<?php }}unset($this->map['floors']['current']);} ?>
		
				<?php
				}
				?>
	  </td>
	  </tr>
      </table>
    </fieldset>
    <table>
    <tr>
        <td>Trạng thái</td>
    </tr>
    <tr>
        <td><select name='status_select' id='status_select'>
            <option value="ALL_STATUS">ALL_STATUS</option>
            <option value="READY">READY</option>
            <option value="DIRTY">DIRTY</option>
            <option value="OCCUPIED">OCCUPIED</option>
            <option value="REPAIR">REPAIR</option>
            <option value="HOUSEUSE">HOUSEUSE</option>
        </select></td>
    </tr>
    </table>
  </td></tr></table>
	<p>
		<input type="submit" name="do_search" value="  <?php echo Portal::language('report');?>  "/>
		<input type="button" value="  <?php echo Portal::language('cancel');?>  " onclick="location='<?php echo User::home_page();?>';"/>
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
jQuery("#date_from").datepicker();
function check_all()
{
  jQuery('.check_boox').checked = true;
}
</script>