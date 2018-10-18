<script>
jQuery(document).ready(function(){
	jQuery('#from_date').datepicker();
 });
</script>
<table cellSpacing=0 width="100%" bgcolor="#FFFFFF">
<tr>
<td>
<p>
<form name="OccupancyHodingReport" method="post">
<table cellSpacing=0 width="100%">
	<tr valign="top">
	  <td align="left"><strong><?php echo HOTEL_NAME;?></strong><br />
		Địa chỉ: <?php echo HOTEL_ADDRESS;?><BR>
		Tel: <?php echo HOTEL_PHONE;?> | Fax:<?php echo HOTEL_FAX;?><br />
		Email: <?php echo HOTEL_EMAIL;?> | Website:<?php echo HOTEL_WEBSITE;?>		</td>
		<td align="right" nowrap width="35%" style="padding-right:20px;">
		<strong><?php echo Portal::language('template_code');?></strong><br />
		<i><?php echo Portal::language('promulgation');?></i>		
        <br />
        <?php echo Portal::language('date_print');?>:<?php echo date('d/m/Y H:i');?>
        <br />
        <?php echo Portal::language('user_print');?>:<?php echo User::id();?>
        </td>
	</tr>	
	<tr>
    
	<td align="center" colspan="2"><p><font class="report_title"><?php echo Portal::language('occupancy_holding_report');?></font><br>
    <!--Start Luu Nguyen Giap add portal-->
    <?php //if(User::can_admin(false,ANY_CATEGORY)) {?>
    <?php echo Portal::language('hotel');?><select  name="portal_id" id="portal_id"><?php
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
	</select>
    <?php //}?>
    <!--End Luu Nguyen Giap add portal-->
	<?php echo Portal::language('from_date');?> : <input name="from_date" value="<?php echo Url::get('from_date',date('d/m/Y'));?>"  type="text" id="from_date" style="width:100px;">
	<input name="view_result"  value="<?php echo Portal::language('view');?>" type="submit" id="view_result">
	</p><br></td>
	</tr>
</table>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
</p>
<?php if(isset($this->map['months']) and is_array($this->map['months'])){ foreach($this->map['months'] as $key1=>&$item1){if($key1!='current'){$this->map['months']['current'] = &$item1;?>
<table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="black" style="border-collapse:collapse">
<tr>
	<th width="25%">
		<?php echo $this->map['months']['current']['name'];?></th>
	<?php if(isset($this->map['months']['current']['days']) and is_array($this->map['months']['current']['days'])){ foreach($this->map['months']['current']['days'] as $key2=>&$item2){if($key2!='current'){$this->map['months']['current']['days']['current'] = &$item2;?>
	<td width="25" align="right" bgcolor="<?php echo $this->map['months']['current']['days']['current']['title_bgcolor'];?>"><?php echo $this->map['months']['current']['days']['current']['week_day'];?></td>
	<?php }}unset($this->map['months']['current']['days']['current']);} ?>
	<th align="right">Total</th>
</tr>
<tr>
	<td>Date</td>
	<?php if(isset($this->map['months']['current']['days']) and is_array($this->map['months']['current']['days'])){ foreach($this->map['months']['current']['days'] as $key3=>&$item3){if($key3!='current'){$this->map['months']['current']['days']['current'] = &$item3;?>
	<td align="right"><?php echo $this->map['months']['current']['days']['current']['id'];?></td>
	<?php }}unset($this->map['months']['current']['days']['current']);} ?>
	<th>&nbsp;</th>
</tr>
<tr>
	<td>Total</td>
	<?php if(isset($this->map['months']['current']['days']) and is_array($this->map['months']['current']['days'])){ foreach($this->map['months']['current']['days'] as $key4=>&$item4){if($key4!='current'){$this->map['months']['current']['days']['current'] = &$item4;?>
	<td align="right"><?php echo $this->map['months']['current']['days']['current']['total'];?></td>
	<?php }}unset($this->map['months']['current']['days']['current']);} ?>
	<th align="right"><?php echo System::display_number($this->map['months']['current']['total']);?></th>
</tr>
<tr style="background-color: #CCCCBB;">
	<td>R.sold</td>
	<?php if(isset($this->map['months']['current']['days']) and is_array($this->map['months']['current']['days'])){ foreach($this->map['months']['current']['days'] as $key5=>&$item5){if($key5!='current'){$this->map['months']['current']['days']['current'] = &$item5;?>
	<td align="right"><?php echo $this->map['months']['current']['days']['current']['rsold'];?></td>
	<?php }}unset($this->map['months']['current']['days']['current']);} ?>
	<th align="right"><?php echo $this->map['months']['current']['rsold'];?></th>
</tr>
<tr>
  <td>Room not used </td>
  <?php if(isset($this->map['months']['current']['days']) and is_array($this->map['months']['current']['days'])){ foreach($this->map['months']['current']['days'] as $key6=>&$item6){if($key6!='current'){$this->map['months']['current']['days']['current'] = &$item6;?>
  <td align="right"><?php echo $this->map['months']['current']['days']['current']['total']-$this->map['months']['current']['days']['current']['rsold'];?></td>
<?php }}unset($this->map['months']['current']['days']['current']);} ?>
  <th align="right"><?php echo System::display_number($this->map['months']['current']['total']-$this->map['months']['current']['rsold']);?></th>
</tr>
<tr>
	<td>Per Used %</td>
	<?php if(isset($this->map['months']['current']['days']) and is_array($this->map['months']['current']['days'])){ foreach($this->map['months']['current']['days'] as $key7=>&$item7){if($key7!='current'){$this->map['months']['current']['days']['current'] = &$item7;?>
	<td align="right" bgcolor="<?php echo $this->map['months']['current']['days']['current']['bgcolor'];?>"><?php echo $this->map['months']['current']['days']['current']['percent'];?></td>
	<?php }}unset($this->map['months']['current']['days']['current']);} ?>
	<th align="right"><?php echo $this->map['months']['current']['percent'];?></th>
</tr>
</table>
<p>&nbsp;</p>
<?php }}unset($this->map['months']['current']);} ?>
</td>
</tr>
</table>
<?php
    if(User::id()=='developer07')
    {
       // System::debug($this->map['months']);
    } 
?>