<div class="product-bill-bound">
    <form name="ImportProductPriceForm" method="post" enctype="multipart/form-data">
    	<input  name="action" id="action" type="hidden"/>
    	
        <!--input dung de luu id(product_price_list) khi an nut xoa tung` multi row-->
        <input  name="group_deleted_ids" id="group_deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>"/>
    	
        <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
    		<tr>
            	<td width="70%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;">[[|title|]]</td>
                <td width="30%" align="right" nowrap="nowrap" style="padding-right: 30xp;">
                	
                    <?php if(User::can_add(false,ANY_CATEGORY)){?>
                    <input name="save" type="submit" value="[[.Save_and_close.]]" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; margin-right: 5px;"/>
                    <?php }?>
    				
                    <?php if(User::can_delete(false,ANY_CATEGORY)){?>
                    <a href="<?php echo Url::build_current(array('type'));?>"  class="w3-btn w3-green" style="text-transform: uppercase; margin-right: 5px; text-decoration: none;">[[.back.]]</a>
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
			<div style="font-weight:bold;color:#009900;">[[.update_sucess.]]</div>            
            <?php }?>
            
            <fieldset>
    			<legend class="title">[[.department.]]</legend>
    			<table border="0" cellspacing="0" cellpadding="2">
    				<tr>
    					<td class="label">&nbsp;</td>
    					<td>
                            <?php 
                                if(User::can_admin(false,ANY_CATEGORY))
                                {
                            ?>
                            [[.select_portal.]]
                            <select name="portal_id" id="portal_id" onchange="$('action').value='search_portal';ImportProductPriceForm.submit();" style="height: 24px;"></select>
                            <?php
                                }
                            ?>
                            [[.select_department.]]
                            <select  name="department_code" id="department_code" onchange="$('action').value='search_department';" style="height: 24px;">[[|department_list|]]</select>
                            
                            
                            <?php if(Url::get('department_code')){?>
                            
                                <script>$('department_code').value = "<?php echo Url::get('department_code');?>";</script>
                            
                            <?php }?>
                        </td>
    				</tr>
    			</table>
            </fieldset>	
            
            <br />
            
            <fieldset>
    			<legend class="title">[[.upload.]]</legend>
    			<table border="0" cellspacing="0" cellpadding="2">
    				<tr>
                        <td><input name="path" id="path" type="file"  /></td>
                        <td><input name="do_upload" type="submit" id="do_upload" value="[[.upload_and_preview.]]" style="width: 180px;"/></td>
    				</tr>
    			</table>
            </fieldset>
            
            <br />
            
            <table width="100%" border="1" cellspacing="0" cellpadding="3" bordercolor="#CCCCCC">
                <tr bgcolor="#F1F1F1">
                    <th width="10%" align="left">[[.product_id.]]</th>
                    <th width="20%" align="left">[[.product_name.]](VN)</th>
                    <th width="20%" align="left">[[.product_name.]](ENG)</th>
                    <th width="10%" align="left">[[.unit.]]</th>
                    <th width="10%" align="left">[[.type.]]</th>
                    <th width="10%" align="left">[[.category.]]</th>
                    <th width="10%" align="right">[[.price.]]</th>
                    <th width="10%" align="left">[[.note.]]</th>
                    <?php 
                    if( isset($this->map['change']) && !empty($this->map['change']))
                    {
                    ?>
                    <th width="10%" align="right">[[.new_price.]]</th>
                    <?php
                    }
                    ?>
                </tr>
                
                <?php 
                    if( isset($this->map['error']) && !empty($this->map['error']))
                    {
                ?>
                    <!--LIST:error-->
                    <tr>  
                        <td style="cursor:pointer;">[[|error.product_id|]]</td>
                        <td style="cursor:pointer;">[[|error.product_name_1|]]</td>
                        <td style="cursor:pointer;">[[|error.product_name_2|]]</td>
                        <td style="cursor:pointer;">[[|error.unit|]]</td>  
                        <td style="cursor:pointer;">[[|error.type|]]</td>
                        <td style="cursor:pointer;">[[|error.category|]]</td>                  
                        <td style="cursor:pointer;" align="right"><?php echo System::display_number([[=error.price=]]);?></td>
                        <td style="cursor:pointer;">[[|error.note|]]</td>   
                    </tr>
                    <!--/LIST:error-->
                <?php
                    }
                ?>
                
                
                <?php 
                    if( isset($this->map['preview']) && !empty($this->map['preview']))
                    {
                ?>
                    <!--LIST:preview-->
                    <tr>  
                        <td style="cursor:pointer;">[[|preview.product_id|]]</td>
                        <td style="cursor:pointer;">[[|preview.product_name_1|]]</td>
                        <td style="cursor:pointer;">[[|preview.product_name_2|]]</td>
                        <td style="cursor:pointer;">[[|preview.unit|]]</td>  
                        <td style="cursor:pointer;">[[|preview.type|]]</td>
                        <td style="cursor:pointer;">[[|preview.category|]]</td>                  
                        <td style="cursor:pointer;" align="right"><?php echo System::display_number([[=preview.price=]]);?></td>
                        <td></td>
                    </tr>
                    <!--/LIST:preview-->
                <?php
                    }
                ?>
                
                
                
                <?php 
                    if( isset($this->map['change']) && !empty($this->map['change']))
                    {
                ?>
                    <tr>
                        <th colspan="8" style="text-align: center;">[[.product_change_price.]]</th>
                    </tr>
                    <!--LIST:change-->
                    <tr>
                        <td style="cursor:pointer;">[[|change.product_id|]]</td>
                        <td style="cursor:pointer;">[[|change.product_name_1|]]</td>
                        <td style="cursor:pointer;">[[|change.product_name_2|]]</td>
                        <td style="cursor:pointer;">[[|change.unit|]]</td>  
                        <td style="cursor:pointer;">[[|change.type|]]</td>
                        <td style="cursor:pointer;">[[|change.category|]]</td>                  
                        <td style="cursor:pointer;" align="right">[[|change.price|]]</td> 
                        <td style="cursor:pointer;" align="right">[[|change.new_price|]]</td>
                        <td></td>
                    </tr>
                    <!--/LIST:change-->
                <?php
                    }
                ?>
    		</table>
   
    	</div>
    </form>	
</div>

<script>
jQuery(document).ready(function(){
    
    jQuery('#do_upload').click(function(){
        if(!jQuery('#path').val())
        {
            alert('[[.you_must_choose_file.]]')
            return false;
        }
        
    })
})
</script>





