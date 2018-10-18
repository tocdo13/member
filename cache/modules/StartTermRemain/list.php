<div class="product-supplier-bound">
<form name="ListStartTermRemainForm" method="post">
	<table cellpadding="15" cellspacing="0" width="70%" border="0" >
		<tr>
        	<td width="70%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> <?php echo $this->map['title'];?></td>
            <td width="30%" align="right" nowrap="nowrap" style="padding-right: 30px;">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><input type="button" value="<?php echo Portal::language('add_new');?>" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'add','type'));?>'" class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; margin-right: 5px;"/><?php }?>
                <?php if(User::can_delete(false,ANY_CATEGORY)){?><input type="button" value="<?php echo Portal::language('delete');?>" id="delete_button" class="w3-btn w3-red" style="text-transform: uppercase;" /><?php }?>
                                
            </td>
        </tr>
    </table>
	<div class="content">
    	<div class="search-box">
        	<fieldset>
            	<legend class="title"><?php echo Portal::language('search');?></legend>
        		<span><?php echo Portal::language('warehouse');?>:</span> 
        		<select  name="warehouse_id" id="warehouse_id" style="height: 24px;"><?php
					if(isset($this->map['warehouse_id_list']))
					{
						foreach($this->map['warehouse_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('warehouse_id',isset($this->map['warehouse_id'])?$this->map['warehouse_id']:''))
                    echo "<script>$('warehouse_id').value = \"".addslashes(URL::get('warehouse_id',isset($this->map['warehouse_id'])?$this->map['warehouse_id']:''))."\";</script>";
                    ?>
	</select>
                <span><?php echo Portal::language('product_id');?>:</span>
                <input  name="product_id" id="product_id" style="height: 24px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('product_id'));?>"> 
                <input type="submit" name="search" value="<?php echo Portal::language('search');?>" style="height: 24px;"/>
            </fieldset>
        </div>
        <br />
		<table width="70%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC">
            <tr class="w3-light-gray" style="text-transform: uppercase; height: 24px;">
                <th width="10"><input type="checkbox" id="all_item_check_box"/></th>
                <th width="30"><?php echo Portal::language('order_number');?></th>
                <th width="100" align="center"><?php echo Portal::language('product_code');?></th>
                <th width="200" align="center"><?php echo Portal::language('product');?></th>
                <th width="80" align="center"><?php echo Portal::language('quantity');?></th>
                <th width="100" align="center"><?php echo Portal::language('total_start_term_price');?></th>
                <th width="40"><?php echo Portal::language('edit');?></th>
                <th width="40"><?php echo Portal::language('delete');?></th>
            </tr>
            <?php $warehouse = '';?>
            <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
            <?php if($warehouse != $this->map['items']['current']['warehouse_id']){ $warehouse = $this->map['items']['current']['warehouse_id'];?>
			<tr>
                <td colspan="8" class="category-group"><?php echo $this->map['items']['current']['warehouse_name'];?></td>
			</tr>  
            <?php }?>
            <tr>  
                <td width="1%"><input name="item_check_box[]" type="checkbox" class="item-check-box" value="<?php echo $this->map['items']['current']['id'];?>"></td>
                <td style="cursor:pointer;"><?php echo $this->map['items']['current']['i'];?></td>
                <td style="cursor:pointer;"><?php echo $this->map['items']['current']['product_id'];?></td>
                <td style="cursor:pointer;"><?php echo $this->map['items']['current']['product_name'];?></td>
                <td style="cursor:pointer;text-align: right;"><?php echo $this->map['items']['current']['quantity'];?></td>
                <td style="cursor:pointer; text-align: right;"><?php echo $this->map['items']['current']['total_start_term_price'];?></td>
                <td style="text-align: center;"><a href="<?php echo Url::build_current(array('cmd'=>'edit','type','id'=>$this->map['items']['current']['id']));?>"><i class="fa fa-pencil w3-text-orange" style="font-size: 20px; padding-top: 2px;"></i></a></td>
                <td style="text-align: center;"><a class="delete-one-item" href="<?php echo Url::build_current(array('cmd'=>'delete','type','id'=>$this->map['items']['current']['id']));?>"><i class="fa fa-times-circle w3-text-red" style="font-size: 20px; padding-top: 2px;"></i></a></td>
			</tr>
            <?php }}unset($this->map['items']['current']);} ?>			
		</table>
		<br />
		<div class="paging"><?php echo $this->map['paging'];?></div>
	</div>
	<input  name="cmd" value=""/ type ="hidden" value="<?php echo String::html_normalize(URL::get('cmd'));?>">
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
				
</div>
<script type="text/javascript" src="packages/core/includes/js/jquery/jquery.maskedinput.js"></script>
<script type="text/javascript">
	jQuery("#create_date").mask("99/99/9999");
	jQuery("#delete_button").click(function (){
        if(confirm('<?php echo Portal::language('are_you_sure');?>')){
            ListStartTermRemainForm.cmd.value = 'delete';
    		ListStartTermRemainForm.submit();
        };
		
	});
	jQuery(".delete-one-item").click(function (){
		if(!confirm('<?php echo Portal::language('are_you_sure');?>')){
			return false;
		}
	});
	jQuery("#all_item_check_box").click(function (){
		var check  = this.checked;
		jQuery(".item-check-box").each(function(){
			this.checked = check;
		});
	});
</script>