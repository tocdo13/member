<form method="post" name="EditMinibarImportForm">
<div style="background-color:#FFFFFF;width:100%;">
    <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr>
    		<td align="left" class="" style="text-transform: uppercase; font-size: 18px;"><i class="fa fa-pencil w3-text-orange" style="font-size: 24px;"></i> <?php echo Portal::language('edit_room_cleanup');?></td>
    		<td align="right" style="padding-right: 50px;">
                <input name="update" type="submit" value="<?php echo Portal::language('Save_and_close');?>" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; margin-right: 5px;"/>
                <input name="back" type="button" value="<?php echo Portal::language('back');?>" class="w3-btn w3-green" style="text-transform: uppercase;" onclick="window.location='<?php echo Url::build_current(); ?>'"/>
            </td>        
    	</tr>
    </table>

    
    <table border="1" cellspacing="0" cellpadding="5" width="65%"align="left" bordercolor="#C6E2FF" bgcolor="#FFFFFF" style="border-collapse:collapse;">
        <tr class="w3-light-gray" style="height: 24px; text-transform: uppercase;">  
            <td width="100px"><?php echo Portal::language('room_name');?></td>
            <td width="100px"><?php echo Portal::language('status');?></td>
            <td><?php echo Portal::language('note');?></td>
            <td><?php echo Portal::language('complete');?></td>
        </tr> 
        <?php if(isset($this->map['item']) and is_array($this->map['item'])){ foreach($this->map['item'] as $key1=>&$item1){if($key1!='current'){$this->map['item']['current'] = &$item1;?>
        <tr>
            <td>
                Room <?php echo $this->map['item']['current']['room_name'];?>
                <input name="id" id="id" type="hidden" value="<?php echo $this->map['item']['current']['id'];?>"/>
            </td>
            <td><?php echo $this->map['item']['current']['status'];?></td>
            <td><input name="note_<?php echo $this->map['item']['current']['id'];?>" id="note_<?php echo $this->map['item']['current']['id'];?>" type="text" value="<?php echo $this->map['item']['current']['note'];?>" style="width: 500px;"/></td>
            <td><input name="complete_<?php echo $this->map['item']['current']['id'];?>" id="complete_<?php echo $this->map['item']['current']['id'];?>" type="checkbox" value="<?php echo $this->map['item']['current']['id'];?>"/></td>
        </tr>
        <?php }}unset($this->map['item']['current']);} ?>
    </table>
</div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script>
	function check_all()
	{
		 if($('room').checked==true)
		 {
		 	<?php if(isset($this->map['rooms']) and is_array($this->map['rooms'])){ foreach($this->map['rooms'] as $key2=>&$item2){if($key2!='current'){$this->map['rooms']['current'] = &$item2;?>
				$('check_<?php echo $this->map['rooms']['current']['id'];?>').checked=true;
			<?php }}unset($this->map['rooms']['current']);} ?>
		 }
		 else
		 {
		 	<?php if(isset($this->map['rooms']) and is_array($this->map['rooms'])){ foreach($this->map['rooms'] as $key3=>&$item3){if($key3!='current'){$this->map['rooms']['current'] = &$item3;?>
				$('check_<?php echo $this->map['rooms']['current']['id'];?>').checked=false;
			<?php }}unset($this->map['rooms']['current']);} ?>
		 }
	}
    function check_value(obj)
    {
        if(jQuery(obj).val()=='')
            jQuery(obj).val('0');
    }
</script>