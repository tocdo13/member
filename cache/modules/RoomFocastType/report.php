<script>
jQuery(document).ready(function(){
	jQuery('#from_date').datepicker();
	jQuery('#to_date').datepicker();
 });
</script>
<div style="width:100%;overflow:auto">
<table cellSpacing=0 width="100%">
<tr>
<td>
<p>
<form name="OccupancyHodingReport" method="post">
<table cellSpacing=0 width="100%">
	<tr valign="top">
	  <td align="left"><strong><?php echo HOTEL_NAME;?></strong><br />
		<?php echo Portal::language('address');?>: <?php echo HOTEL_ADDRESS;?><br />
          </td>
		<td align="right" nowrap width="15%" style="padding-right:20px;">
		<strong><?php echo Portal::language('template_code');?></strong>
        <br />
                        Date: <?php echo date('d/m/Y H:i');?>
                        <br />
                        Printer: <?php $user_name =DB::fetch('select name_1 from party where user_id=\''.User::id().'\''); echo $user_name['name_1']  ;?>
        <br />
		<i><?php echo Portal::language('promulgation');?></i>		</td>
	</tr>	
	<tr>
	<td align="center" colspan="2" style="font-size:18px;"><strong><?php echo Portal::language('room_focast_type');?></strong></td>
    </tr>
    <tr>
    <td colspan="2" align="center">
	<?php echo Portal::language('from_date');?> : <input name="from_date" value="<?php echo Url::get('from_date',date('d/m/Y'));?>"  type="text" id="from_date" onchange="changevalue();" style="width:100px;">
	<?php echo Portal::language('to_date');?> : <input name="to_date" value="<?php echo Url::get('to_date',date('d/m/Y'));?>"  type="text" id="to_date" style="width:100px;" onchange="changefromday();" />
	<input name="view_result"  value="<?php echo Portal::language('view');?>" type="submit" id="view_result"/>
	</td>
	</tr>
	<tr>
		<td align="center" colspan="2">(<i><?php echo Portal::language('nen_chon_khoang_thoi_gian_nho_hon_hai_muoi_ngay');?></i>)</td>
	</tr>
       
</table>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
</p>
<table width="100%" cellpadding="3" cellspacing="0" border="1" bordercolor="black" style="border-collapse:collapse" style="font-size:12px;">
    <tr>
    	<td width="100" align="center" style="background-color:#CCCCCC" nowrap="nowrap"><strong><?php echo Portal::language('room_type');?></strong></td>
    	<td align="center" width="50"><strong><?php echo Portal::language('Sum');?></strong></td>
    	<?php if(isset($this->map['days']) and is_array($this->map['days'])){ foreach($this->map['days'] as $key1=>&$item1){if($key1!='current'){$this->map['days']['current'] = &$item1;?>
    	<td width="25" align="center"><strong><?php echo $this->map['days']['current']['id'];?></strong></td>
    	<?php }}unset($this->map['days']['current']);} ?>
    </tr>
    <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current'] = &$item2;?>
    <tr>
        <td width="100" align="center" style="background-color:#CCCCCC" nowrap="nowrap"><?php echo $this->map['items']['current']['name'];?></td>
        <td align="center" width="50"><strong><?php echo $this->map['items']['current']['acount'];?></strong></td>
        <?php if(isset($this->map['items']['current']['child']) and is_array($this->map['items']['current']['child'])){ foreach($this->map['items']['current']['child'] as $key3=>&$item3){if($key3!='current'){$this->map['items']['current']['child']['current'] = &$item3;?>
        <td width="25" align="center"><?php echo $this->map['items']['current']['child']['current']['total'];?>/<?php echo $this->map['items']['current']['child']['current']['acount']-$this->map['items']['current']['child']['current']['total_room']-$this->map['items']['current']['child']['current']['total_repair']; ?></td>
        <?php }}unset($this->map['items']['current']['child']['current']);} ?>
    </tr>
    <?php }}unset($this->map['items']['current']);} ?>
    <tr>
        <td width="100" align="center" style="background-color:#CCCCCC" nowrap="nowrap"><strong><?php echo Portal::language('total');?></strong></td>
        <td align="center" width="50"><strong><?php echo $this->map['total'];?></strong></td>
        <?php if(isset($this->map['days']) and is_array($this->map['days'])){ foreach($this->map['days'] as $key4=>&$item4){if($key4!='current'){$this->map['days']['current'] = &$item4;?>
    	<td width="25" align="center"><strong><?php echo $this->map['days']['current']['total'];?>/<?php echo $this->map['total']-$this->map['days']['current']['total_room']-$this->map['days']['current']['total_repair'] ?><br />(<?php echo round($this->map['days']['current']['total']*100/($this->map['total']-$this->map['days']['current']['total_repair']),2) ?>%)</strong></td>
    	<?php }}unset($this->map['days']['current']);} ?>
    </tr>
</table>
</td>
</tr>
</table>
<br />
</div>
<script>
    function changevalue()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#to_date").val(jQuery("#from_date").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('from_date').value.split("/");
        var mytodate = $('to_date').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#from_date").val(jQuery("#to_date").val());
        }
    }
</script>