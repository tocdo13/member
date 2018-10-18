<?php //System::debug($this->map['check']); ?>
<form name="choose_customer" method="POST">
    
    <table style="width: 100%; padding-top:20px;">
        <tr>
            <td class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><?php echo Portal::language('group_event');?></td>
            <td style="text-align:right; padding-right: 30px;"><input name="save" class="w3-btn w3-orange w3-text-white"  type="submit"  value="<?php echo Portal::language('Save_and_close');?>" style="text-transform: uppercase; margin-right: 5px;" />
            <input type="button" onclick="window.close();" class="w3-green w3-btn" value="<?php echo Portal::language('back');?>" style="text-transform: uppercase; margin-right: 5px;"/></td>
        </tr>
    </table>    
    <br />
    <div>
                <label style="color: red;"><?php echo Portal::language('customer');?>:</label>
                <select  name="group_customer" id="group_customer" style="width: 200px; height: 24px;"><?php
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
                <select  name="gender" id="gender" style="height: 24px;"><?php
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
                <select  name="traveller_level" id="traveller_level" style="width: 200px; height: 24px;"><?php
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
                <select  name="country_option" id="country_option" style="width: 200px; height: 24px;"><?php
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
                <input  name="search" value="search" style=" height: 24px;" / type ="submit" value="<?php echo String::html_normalize(URL::get('search'));?>">
     </div>
    <div class="content-top" style="width: 100%; clear: both; overflow:hidden">
        <div class="content-left" style="width: 50%;float: left;">
            <h3 class="title" style="color: red; text-transform: uppercase"><?php echo Portal::language('customer');?></h3>
            
            <table border="1" cellspacing="0" cellpadding="2" bordercolor="#D9ECFF" rules="cols">
                <tr class="w3-gray" style="text-transform: uppercase; height: 26px;">
                    <td><?php echo Portal::language('stt');?></td>
                    <td>
                        <input type="checkbox" name="check_all" id="check_all" />
                    </td>
                    
                    <td><?php echo Portal::language('customer_name');?></td>
                    <td><?php echo Portal::language('customer_email');?></td>
                </tr>
                <?php $k=1; ?>
                <?php if(isset($this->map['list_customer']) and is_array($this->map['list_customer'])){ foreach($this->map['list_customer'] as $key1=>&$item1){if($key1!='current'){$this->map['list_customer']['current'] = &$item1;?>
                <tr>
                    <td><?php echo $k++; ?></td>
                    <?php if($this->map['list_customer']['current']['check_customer']==0){ ?>
                    <td><input type="checkbox" name="customer_<?php echo $this->map['list_customer']['current']['id'];?>" value="<?php echo $this->map['list_customer']['current']['id'];?>" class="check_items" /></td>
                    <?php }else{ ?>
                    <td><input type="checkbox" name="customer_<?php echo $this->map['list_customer']['current']['id'];?>" class="check_items" value="<?php echo $this->map['list_customer']['current']['id'];?>" checked="checked" /></td>
                    <?php } ?>
                    <td><?php echo $this->map['list_customer']['current']['name'];?></td>
                    <td><?php echo $this->map['list_customer']['current']['email'];?></td>
                </tr>
                <?php }}unset($this->map['list_customer']['current']);} ?>
            </table>
        </div>
       
        
        <div class="content-left" style="width: 50%;float: left;">
            <h3 class="title" style="text-transform: uppercase"><?php echo Portal::language('traveller');?></h3>
            <div class="search">
                
            </div>
            <table border="1" cellspacing="0" cellpadding="2" bordercolor="#D9ECFF" rules="cols">
                <tr class="w3-gray" style="text-transform: uppercase; height: 26px;">
                    <td><?php echo Portal::language('stt');?></td>
                    <td>
                        <input type="checkbox" name="check_all" id="check_all_traveller" />
                    </td>
                    
                    <td><?php echo Portal::language('customer_name');?></td>
                    <td><?php echo Portal::language('customer_email');?></td>
                </tr>
                <?php $k=1; ?>
                <?php if(isset($this->map['list_traveller']) and is_array($this->map['list_traveller'])){ foreach($this->map['list_traveller'] as $key2=>&$item2){if($key2!='current'){$this->map['list_traveller']['current'] = &$item2;?>
                <tr>
                    <td><?php echo $k++; ?></td>
                    <?php if($this->map['list_traveller']['current']['check_traveller']==0){ ?>
                    <td><input type="checkbox" name="traveller_<?php echo $this->map['list_traveller']['current']['id'];?>" value="<?php echo $this->map['list_traveller']['current']['id'];?>" class="check_items_traveller" /></td>
                    <?php }else{ ?>
                    <td><input type="checkbox" name="traveller_<?php echo $this->map['list_traveller']['current']['id'];?>" value="<?php echo $this->map['list_traveller']['current']['id'];?>" class="check_items_traveller" checked="checked" /></td>
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
    jQuery("#check_all").click(function (){
		var check  = this.checked;
		jQuery(".check_items").each(function(){
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