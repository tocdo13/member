<form name="choose_customer" method="POST">
    
    <table style="width: 100%; padding-top:20px;">
        <tr>
            <td class="form-title" style="text-align:left; height: 40px; width:50%;"><?php echo Portal::language('group_event');?></td>
            <td style="text-align:right;"><input name="save" class="button-medium-save"  type="submit"  value="<?php echo Portal::language('save');?>" />
            <input type="button" onclick="window.close();" class="button-medium-back" value="<?php echo Portal::language('close');?>" /></td>
        </tr>
    </table>    
    <br />
    
    <div>
        <label style="color: red;"><?php echo Portal::language('customer');?>:</label>
        <select  name="group_customer" id="group_customer" style="width: 200px;"><?php
					if(isset($this->map['group_customer_list']))
					{
						foreach($this->map['group_customer_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('group_customer',isset($this->map['group_customer'])?$this->map['group_customer']:''))
                    echo "<script>$('group_customer').value = \"".addslashes(URL::get('group_customer',isset($this->map['group_customer'])?$this->map['group_customer']:''))."\";</script>";
                    ?>
	</select>
        <label style="color: #0066CC"><?php echo Portal::language('gender');?>: </label>
        <select  name="gender" id="gender"><?php
					if(isset($this->map['gender_list']))
					{
						foreach($this->map['gender_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('gender',isset($this->map['gender'])?$this->map['gender']:''))
                    echo "<script>$('gender').value = \"".addslashes(URL::get('gender',isset($this->map['gender'])?$this->map['gender']:''))."\";</script>";
                    ?>
	</select>
        <label style="color: #0066CC"><?php echo Portal::language('guest_type');?></label>
        <select  name="traveller_level" id="traveller_level" style="width: 200px;"><?php
					if(isset($this->map['traveller_level_list']))
					{
						foreach($this->map['traveller_level_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('traveller_level',isset($this->map['traveller_level'])?$this->map['traveller_level']:''))
                    echo "<script>$('traveller_level').value = \"".addslashes(URL::get('traveller_level',isset($this->map['traveller_level'])?$this->map['traveller_level']:''))."\";</script>";
                    ?>
	
        </select>
        <label style="color: #0066CC"><?php echo Portal::language('country');?></label>
        <select  name="country_option" id="country_option" style="width: 200px;"><?php
					if(isset($this->map['country_option_list']))
					{
						foreach($this->map['country_option_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('country_option',isset($this->map['country_option'])?$this->map['country_option']:''))
                    echo "<script>$('country_option').value = \"".addslashes(URL::get('country_option',isset($this->map['country_option'])?$this->map['country_option']:''))."\";</script>";
                    ?>
		
        </select>
        <input  name="search" value="search"  / type ="submit" value="<?php echo String::html_normalize(URL::get('search'));?>">
     </div>
    
    <div class="content-top" style="width: 100%; clear: both; overflow: hidden;">
        <div class="content-left" style="width: 50%;float: left;">
            <h3 class="title"><?php echo Portal::language('customer');?></h3>
            <table border="1" cellspacing="0" cellpadding="2" bordercolor="#D9ECFF" rules="cols">
                <tr class="table-header">
                    <td><?php echo Portal::language('stt');?></td>
                    <td>
                        <input type="checkbox" name="check_all_customer" id="check_all_customer" />
                    </td>
                    
                    <td><?php echo Portal::language('customer_name');?></td>
                    <td><?php echo Portal::language('customer_email');?></td>
                </tr>
                <?php $k=1; ?>
                <?php if(isset($this->map['list_customer']) and is_array($this->map['list_customer'])){ foreach($this->map['list_customer'] as $key1=>&$item1){if($key1!='current'){$this->map['list_customer']['current'] = &$item1;?>
                <tr>
                    <td><?php echo $k++; ?></td>
                    <?php if($this->map['list_customer']['current']['check']!=1){ ?>
                    <td><input type="checkbox" name="customer_<?php echo $this->map['list_customer']['current']['id'];?>" value="<?php echo $this->map['list_customer']['current']['id'];?>" class="check_items_customer" /></td>
                    <?php }else{ ?>
                    <td><input type="checkbox" name="customer_<?php echo $this->map['list_customer']['current']['id'];?>" value="<?php echo $this->map['list_customer']['current']['id'];?>"  class="check_items_customer" checked="checked" /></td>
                    <?php } ?>
                    <td><?php echo $this->map['list_customer']['current']['name'];?></td>
                    <td><?php echo $this->map['list_customer']['current']['email'];?></td>
                </tr>
                <?php }}unset($this->map['list_customer']['current']);} ?>
            </table>
        </div>
        
        
        <div class="content-left" style="width: 50%;float: left;">
            <h3 class="title"><?php echo Portal::language('traveller');?></h3>
            <table border="1" cellspacing="0" cellpadding="2" bordercolor="#D9ECFF" rules="cols">
                <tr class="table-header">
                    <td><?php echo Portal::language('stt');?></td>
                    <td>
                        <input type="checkbox" name="check_all_traveller" id="check_all_traveller" />
                    </td>
                    
                    <td><?php echo Portal::language('customer_name');?></td>
                    <td><?php echo Portal::language('customer_email');?></td>
                </tr>
                <?php $k=1; ?>
                <?php if(isset($this->map['list_traveller']) and is_array($this->map['list_traveller'])){ foreach($this->map['list_traveller'] as $key2=>&$item2){if($key2!='current'){$this->map['list_traveller']['current'] = &$item2;?>
                <tr>
                    <td><?php echo $k++; ?></td>
                    <?php if($this->map['list_traveller']['current']['check']!=1){ ?>
                    <td><input type="checkbox" name="traveller_<?php echo $this->map['list_traveller']['current']['id'];?>" value="<?php echo $this->map['list_traveller']['current']['id'];?>" class="check_items_traveller" /></td>
                    <?php }else{ ?>
                    <td><input type="checkbox" name="traveller_<?php echo $this->map['list_traveller']['current']['id'];?>" value="<?php echo $this->map['list_traveller']['current']['id'];?>"  class="check_items_traveller" checked="checked" /></td>
                    <?php } ?>
                    <td><?php echo $this->map['list_traveller']['current']['fullname'];?></td>
                    <td><?php echo $this->map['list_traveller']['current']['email'];?></td>
                </tr>
                <?php }}unset($this->map['list_traveller']['current']);} ?>
            </table>
        </div>
    
   </div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script>
    
    jQuery("#check_all_customer").click(function (){
		var check  = this.checked;
		jQuery(".check_items_customer").each(function(){
			this.checked = check;
		});
	});
    jQuery("#check_all_traveller").click(function (){
		var check  = this.checked;
		jQuery(".check_items_traveller").each(function(){
			this.checked = check;
		});
	});
</script>