<form method="post" name="EditMinibarImportForm">
<div style="background-color:#FFFFFF;width:100%;">
    <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr>
    		<td align="left" class="" style="text-transform: uppercase; font-size: 18px;"><i class="fa fa-plus-square w3-text-orange" style="font-size: 24px;"></i> <?php echo Portal::language('add_room_cleanup');?></td>
    		<td align="right" style="padding-right: 50px;">
                <input name="update" type="submit" value="<?php echo Portal::language('Save_and_close');?>" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; margin-right: 5px;"/>
                <input name="back" type="button" value="<?php echo Portal::language('back');?>" class="w3-btn w3-green" onclick="window.location='<?php echo Url::build_current(); ?>'"style="text-transform: uppercase;"/>
            </td>        
    	</tr>
    </table>
    
    
    <table border="1" cellspacing="0" cellpadding="5" width="65%"align="left" bordercolor="#C6E2FF" bgcolor="#FFFFFF" style="border-collapse:collapse;">
        <tr class="w3-light-gray w3-border" style="height: 24px; text-transform: uppercase;">  
            <td width="100px"><?php echo Portal::language('room_name');?></td>
            <td width="100px"><?php echo Portal::language('clean');?></td>
            <td><?php echo Portal::language('note');?></td>
        </tr> 
        <?php if(isset($this->map['rooms']) and is_array($this->map['rooms'])){ foreach($this->map['rooms'] as $key1=>&$item1){if($key1!='current'){$this->map['rooms']['current'] = &$item1;?>
        <tr>
            <td>
                Room <?php echo $this->map['rooms']['current']['name'];?>
                <input name="<?php echo $this->map['rooms']['current']['id'];?>" id="<?php echo $this->map['rooms']['current']['id'];?>" type="hidden" value="<?php echo $this->map['rooms']['current']['id'];?>"/>
            </td>
            <td><input name="check_<?php echo $this->map['rooms']['current']['id'];?>" id="check_<?php echo $this->map['rooms']['current']['id'];?>" type="checkbox" value="<?php echo $this->map['rooms']['current']['id'];?>"/></td>
            <td><input name="note_<?php echo $this->map['rooms']['current']['id'];?>" id="note_<?php echo $this->map['rooms']['current']['id'];?>" type="text" style="width: 500px;"/></td>
        </tr>
        <?php }}unset($this->map['rooms']['current']);} ?>
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
</script>