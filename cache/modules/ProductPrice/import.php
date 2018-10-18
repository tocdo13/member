<div class="product-bill-bound">
    <form name="ImportProductPriceForm" method="post" enctype="multipart/form-data">
    	<input  name="action" id="action" type="hidden"/>
    	
        <!--input dung de luu id(product_price_list) khi an nut xoa tung` multi row-->
        <input  name="group_deleted_ids" id="group_deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>"/>
    	
        <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
    		<tr>
            	<td width="70%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><?php echo $this->map['title'];?></td>
                <td width="30%" align="right" nowrap="nowrap" style="padding-right: 30xp;">
                	
                    <?php if(User::can_add(false,ANY_CATEGORY)){?>
                    <input name="save" type="submit" value="<?php echo Portal::language('Save_and_close');?>" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; margin-right: 5px;"/>
                    <?php }?>
    				
                    <?php if(User::can_delete(false,ANY_CATEGORY)){?>
                    <a href="<?php echo Url::build_current(array('type'));?>"  class="w3-btn w3-green" style="text-transform: uppercase; margin-right: 5px; text-decoration: none;"><?php echo Portal::language('back');?></a>
                    <?php }?>
                </td>
            </tr>
        </table>
        
    	<div class="content">
    		<?php if(Form::$current->is_error()){?>
            <div>
            <br/>
            <?php echo Form::$current->error_messages();?>
            </div>
            <?php }?>
            <?php if(Url::get('action')=='success'){?>
			<div style="font-weight:bold;color:#009900;"><?php echo Portal::language('update_sucess');?></div>            
            <?php }?>
            
            <fieldset>
    			<legend class="title"><?php echo Portal::language('department');?></legend>
    			<table border="0" cellspacing="0" cellpadding="2">
    				<tr>
    					<td class="label">&nbsp;</td>
    					<td>
                            <?php 
                                if(User::can_admin(false,ANY_CATEGORY))
                                {
                            ?>
                            <?php echo Portal::language('select_portal');?>
                            <select  name="portal_id" id="portal_id" onchange="$('action').value='search_portal';ImportProductPriceForm.submit();" style="height: 24px;"><?php
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
                            <?php
                                }
                            ?>
                            <?php echo Portal::language('select_department');?>
                            <select  name="department_code" id="department_code" onchange="$('action').value='search_department';" style="height: 24px;"><?php echo $this->map['department_list'];?></select>
                            
                            
                            <?php if(Url::get('department_code')){?>
                            
                                <script>$('department_code').value = "<?php echo Url::get('department_code');?>";</script>
                            
                            <?php }?>
                        </td>
    				</tr>
    			</table>
            </fieldset>	
            
            <br />
            
            <fieldset>
    			<legend class="title"><?php echo Portal::language('upload');?></legend>
    			<table border="0" cellspacing="0" cellpadding="2">
    				<tr>
                        <td><input name="path" id="path" type="file"  /></td>
                        <td><input name="do_upload" type="submit" id="do_upload" value="<?php echo Portal::language('upload_and_preview');?>" style="width: 180px;"/></td>
    				</tr>
    			</table>
            </fieldset>
            
            <br />
            
            <table width="100%" border="1" cellspacing="0" cellpadding="3" bordercolor="#CCCCCC">
                <tr bgcolor="#F1F1F1">
                    <th width="10%" align="left"><?php echo Portal::language('product_id');?></th>
                    <th width="20%" align="left"><?php echo Portal::language('product_name');?>(VN)</th>
                    <th width="20%" align="left"><?php echo Portal::language('product_name');?>(ENG)</th>
                    <th width="10%" align="left"><?php echo Portal::language('unit');?></th>
                    <th width="10%" align="left"><?php echo Portal::language('type');?></th>
                    <th width="10%" align="left"><?php echo Portal::language('category');?></th>
                    <th width="10%" align="right"><?php echo Portal::language('price');?></th>
                    <th width="10%" align="left"><?php echo Portal::language('note');?></th>
                    <?php 
                    if( isset($this->map['change']) && !empty($this->map['change']))
                    {
                    ?>
                    <th width="10%" align="right"><?php echo Portal::language('new_price');?></th>
                    <?php
                    }
                    ?>
                </tr>
                
                <?php 
                    if( isset($this->map['error']) && !empty($this->map['error']))
                    {
                ?>
                    <?php if(isset($this->map['error']) and is_array($this->map['error'])){ foreach($this->map['error'] as $key1=>&$item1){if($key1!='current'){$this->map['error']['current'] = &$item1;?>
                    <tr>  
                        <td style="cursor:pointer;"><?php echo $this->map['error']['current']['product_id'];?></td>
                        <td style="cursor:pointer;"><?php echo $this->map['error']['current']['product_name_1'];?></td>
                        <td style="cursor:pointer;"><?php echo $this->map['error']['current']['product_name_2'];?></td>
                        <td style="cursor:pointer;"><?php echo $this->map['error']['current']['unit'];?></td>  
                        <td style="cursor:pointer;"><?php echo $this->map['error']['current']['type'];?></td>
                        <td style="cursor:pointer;"><?php echo $this->map['error']['current']['category'];?></td>                  
                        <td style="cursor:pointer;" align="right"><?php echo System::display_number($this->map['error']['current']['price']);?></td>
                        <td style="cursor:pointer;"><?php echo $this->map['error']['current']['note'];?></td>   
                    </tr>
                    <?php }}unset($this->map['error']['current']);} ?>
                <?php
                    }
                ?>
                
                
                <?php 
                    if( isset($this->map['preview']) && !empty($this->map['preview']))
                    {
                ?>
                    <?php if(isset($this->map['preview']) and is_array($this->map['preview'])){ foreach($this->map['preview'] as $key2=>&$item2){if($key2!='current'){$this->map['preview']['current'] = &$item2;?>
                    <tr>  
                        <td style="cursor:pointer;"><?php echo $this->map['preview']['current']['product_id'];?></td>
                        <td style="cursor:pointer;"><?php echo $this->map['preview']['current']['product_name_1'];?></td>
                        <td style="cursor:pointer;"><?php echo $this->map['preview']['current']['product_name_2'];?></td>
                        <td style="cursor:pointer;"><?php echo $this->map['preview']['current']['unit'];?></td>  
                        <td style="cursor:pointer;"><?php echo $this->map['preview']['current']['type'];?></td>
                        <td style="cursor:pointer;"><?php echo $this->map['preview']['current']['category'];?></td>                  
                        <td style="cursor:pointer;" align="right"><?php echo System::display_number($this->map['preview']['current']['price']);?></td>
                        <td></td>
                    </tr>
                    <?php }}unset($this->map['preview']['current']);} ?>
                <?php
                    }
                ?>
                
                
                
                <?php 
                    if( isset($this->map['change']) && !empty($this->map['change']))
                    {
                ?>
                    <tr>
                        <th colspan="8" style="text-align: center;"><?php echo Portal::language('product_change_price');?></th>
                    </tr>
                    <?php if(isset($this->map['change']) and is_array($this->map['change'])){ foreach($this->map['change'] as $key3=>&$item3){if($key3!='current'){$this->map['change']['current'] = &$item3;?>
                    <tr>
                        <td style="cursor:pointer;"><?php echo $this->map['change']['current']['product_id'];?></td>
                        <td style="cursor:pointer;"><?php echo $this->map['change']['current']['product_name_1'];?></td>
                        <td style="cursor:pointer;"><?php echo $this->map['change']['current']['product_name_2'];?></td>
                        <td style="cursor:pointer;"><?php echo $this->map['change']['current']['unit'];?></td>  
                        <td style="cursor:pointer;"><?php echo $this->map['change']['current']['type'];?></td>
                        <td style="cursor:pointer;"><?php echo $this->map['change']['current']['category'];?></td>                  
                        <td style="cursor:pointer;" align="right"><?php echo $this->map['change']['current']['price'];?></td> 
                        <td style="cursor:pointer;" align="right"><?php echo $this->map['change']['current']['new_price'];?></td>
                        <td></td>
                    </tr>
                    <?php }}unset($this->map['change']['current']);} ?>
                <?php
                    }
                ?>
    		</table>
   
    	</div>
    <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
				
</div>

<script>
jQuery(document).ready(function(){
    
    jQuery('#do_upload').click(function(){
        if(!jQuery('#path').val())
        {
            alert('<?php echo Portal::language('you_must_choose_file');?>')
            return false;
        }
        
    })
})
</script>





