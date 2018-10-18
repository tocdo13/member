<?php 
System::set_page_title(HOTEL_NAME.' - '.Portal::language('add_department'));?>
<div align="center">
<table cellspacing="0" width="100%" border="0">
	<tr valign="top">
		<td align="left">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="" width="60%" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><?php echo $this->map['title'];?></td>
					<td width="40%" align="right"><a class="w3-orange w3-text-white w3-btn" onClick="AddManageDepartmentForm.submit();" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('save');?></a>
					<a class="w3-btn w3-green" onClick="history.go(-1)" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('back');?></a></td>                    
                </tr>
            </table>
		</td>
	</tr>
	
    <tr valign="top">
    	<td>
        	<form name="AddManageDepartmentForm" method="post" >
            	<div class="search-box">
                	<fieldset>
                    	<legend class="title"><?php echo Portal::language('select');?></legend>
                        <span><?php echo Portal::language('parent_name');?>:</span> 
                		<select  name="parent_id" id="parent_id"><?php
					if(isset($this->map['parent_id_list']))
					{
						foreach($this->map['parent_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('parent_id',isset($this->map['parent_id'])?$this->map['parent_id']:''))
                    echo "<script>$('parent_id').value = \"".addslashes(URL::get('parent_id',isset($this->map['parent_id'])?$this->map['parent_id']:''))."\";</script>";
                    ?>
	</select>
                    </fieldset>
                </div>
                <table width="100%" cellpadding="0">
                    <?php 
                    if(Form::$current->is_error())
                	{
                	?>
                    <tr valign="top">
                        <td bgcolor="#C8E1C3"><?php echo Form::$current->error_messages();?></td>
                	</tr>
                	<?php
                	}
                	?>
                    
                	<tr valign="top">
                		<td>
            				<span id="mi_product_all_elems">
            					<span>
            						<span class="multi-input-header" style="width:51px;"><?php echo Portal::language('code');?></span>
            						<?php if(isset($this->map['languages']) and is_array($this->map['languages'])){ foreach($this->map['languages'] as $key1=>&$item1){if($key1!='current'){$this->map['languages']['current'] = &$item1;?>
            						<span class="multi-input-header" style="width:152px;"><?php echo Portal::language('name');?>(<?php echo $this->map['languages']['current']['code'];?>)</span>
            						<?php }}unset($this->map['languages']['current']);} ?>
                                    <span class="multi-input-header" style="width:82px;"><?php echo Portal::language('area_id');?></span>
                                    <?php if(User::is_admin()){?>
            						<span class="multi-input-header" style="width:81px;"><?php echo Portal::language('account_revenue_code');?></span>
            						<span class="multi-input-header" style="width:80px;"><?php echo Portal::language('account_deposit_code');?></span>
                                    <?php }?>
                                    <span class="multi-input-header" style="width:100px;"><?php echo Portal::language('mice_use');?></span>
            					</span>
            				</span>
                		</td>
                    </tr>
                    <tr>
                        <td>
                			<span class="multi_input">
                				<input  name="code" id="code" style="width:50px;text-transform: uppercase; height: 24px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('code'));?>">
                			</span>
                            <script type="text/javascript">
                                jQuery(document).ready(function(){
                                    <?php if(Url::get('cmd')=='edit') {?>
                                    jQuery('#code').attr('readonly','readonly');
                                    <?php }?>      
                                })
                            </script>
                            <?php if(isset($this->map['languages']) and is_array($this->map['languages'])){ foreach($this->map['languages'] as $key2=>&$item2){if($key2!='current'){$this->map['languages']['current'] = &$item2;?>
                			<span class="multi_input">
                				<input  name="name_<?php echo $this->map['languages']['current']['id'];?>" id="name_<?php echo $this->map['languages']['current']['id'];?>" style="width:148px; height: 24px;"  / type ="text" value="<?php echo String::html_normalize(URL::get('name_'.$this->map['languages']['current']['id']));?>">
                			</span>
                			<?php }}unset($this->map['languages']['current']);} ?>
                            <span class="multi_input">
                				<select  name="area_id" id="area_id" style="width: 80px; height: 24px;"><?php
					if(isset($this->map['area_id_list']))
					{
						foreach($this->map['area_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('area_id',isset($this->map['area_id'])?$this->map['area_id']:''))
                    echo "<script>$('area_id').value = \"".addslashes(URL::get('area_id',isset($this->map['area_id'])?$this->map['area_id']:''))."\";</script>";
                    ?>
	
                					<?php echo $this->map['area_options'];?>
                				</select>
                			</span>
                            <?php if(User::is_admin()){?>
                            <span class="multi_input">
                				<input  name="acc_revenue_code" id="acc_revenue_code" style="width:80px; height: 24px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('acc_revenue_code'));?>">
                			</span>
                            <span class="multi_input">
                				<input  name="acc_deposit_code" id="acc_deposit_code" style="width:80px; height: 24px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('acc_deposit_code'));?>">
                			</span>
                            <?php }?>
                            <span class="multi_input">
                				<select  name="mice_use" id="mice_use" style="width: 100px; height: 24px;"><?php
					if(isset($this->map['mice_use_list']))
					{
						foreach($this->map['mice_use_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('mice_use',isset($this->map['mice_use'])?$this->map['mice_use']:''))
                    echo "<script>$('mice_use').value = \"".addslashes(URL::get('mice_use',isset($this->map['mice_use'])?$this->map['mice_use']:''))."\";</script>";
                    ?>
	
                					<?php echo $this->map['mice_use_option'];?>
                				</select>
                                <script>
                                    jQuery("#mice_use").val('<?php echo $_REQUEST['mice_use'] ?>');
                                </script>
                			</span>
                        </td>
                	</tr>
            	</table>
        	<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
    	</td>
    </tr>
</table>
</div>
