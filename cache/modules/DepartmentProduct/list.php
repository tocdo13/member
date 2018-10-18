<form name="RevenExpenListForm" enctype="multipart/form-data" method="post">
    <table width="100%" cellspacing="0" cellpadding="0">
    	<tr valign="top">
    		<td align="left">
                <table cellpadding="0" cellspacing="0" width="100%" border="0" class="table-bound">
                    <tr height="40">
                        <td class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><?php echo Portal::language('add_product_for_department');?></td>
                        <?php if(User::can_add(false,ANY_CATEGORY)){?><td width="40%" style="text-align: right;"><input type="button" class="w3-btn w3-lime" value="<?php echo Portal::language('import_from_excel');?>" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'import'));?>'" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;" /><?php }?>
    					<?php if(User::can_add(false,ANY_CATEGORY)){?><a href="<?php echo URL::build_current(array('cmd'=>'edit'));?>"  class="w3-cyan w3-btn w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('add');?></a><?php }?>
       					<?php if(User::can_edit(false,ANY_CATEGORY)){?><input type="button" value="<?php echo Portal::language('edit');?>" onclick="check_edit();"  class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"/><?php }?>
                        <?php if(User::can_delete(false,ANY_CATEGORY)){?><input type="button" value="<?php echo Portal::language('delete');?>" onclick="check_delete();"  class="w3-btn w3-red" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"/></td><?php }?>
                    </tr>
                </table>
    		</td>
    	</tr>
        <tr valign="top">
    		<td width="100%">
    			<table border="0" cellspacing="0" width="100%">
        			<tr>
        				<td width="100%">
        					<fieldset>
                                <legend class="title"><?php echo Portal::language('search');?></legend>
                                <table border="0" cellpadding="3" cellspacing="0" width="100%" >
            						<tr width="100%" >
                                        <td style="margin-left: 10px;">
                    						Bộ phận:&nbsp;
                                            <select   name="department_id" id="department_id" style="width:100px; height: 24px;">
                                                <option value=""><?php echo Portal::language('all');?></option><?php echo $this->map['department_options'];?>
                                            </select>&nbsp;&nbsp;
                                            <script type="text/javascript">
          							               jQuery('#department_id').val('<?php echo URL::get('department_id');?>');
                							</script>
                                            <?php echo Portal::language('product_code');?>
                                            <input  name="product_code" id="product_code" style=" height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('product_code'));?>">
                                            <?php echo Portal::language('product_name');?>
                                            <input  name="product_name" id="product_name"style=" height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('product_name'));?>">
                                            
                                            <?php echo Portal::language('category_id');?> :
                    							<select  name="category_id" id="category_id" style="width:150px; height: 24px;"><?php
					if(isset($this->map['category_id_list']))
					{
						foreach($this->map['category_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('category_id',isset($this->map['category_id'])?$this->map['category_id']:''))
                    echo "<script>$('category_id').value = \"".addslashes(URL::get('category_id',isset($this->map['category_id'])?$this->map['category_id']:''))."\";</script>";
                    ?>
	</select>
                    						<?php echo Portal::language('type');?> :
                    						<select  name="product_type" id="product_type" style="width:100px; height: 24px;">
                								<option value=""><?php echo Portal::language('all');?></option><option value="GOODS"><?php echo Portal::language('goods');?></option><option value="PRODUCT"><?php echo Portal::language('product');?></option><option value="DRINK"><?php echo Portal::language('drink');?></option><option value="MATERIAL"><?php echo Portal::language('material');?></option><option value="EQUIPMENT"><?php echo Portal::language('equipment');?></option><option value="SERVICE"><?php echo Portal::language('service');?></option><option value="TOOL"><?php echo Portal::language('tool');?></option>
                							</select>
                                            <script type="text/javascript">
                                                 jQuery('#product_type').val('<?php echo URL::get('product_type');?>');
                							</script>               							
                    						<input name="search" type="submit" value="  <?php echo Portal::language('search');?> " style=" height: 24px;  "/>
                                        </td>
            						</tr>
    					       </table>
                           </fieldset>
                           <br />
                           <table width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#C6E2FF">
        						<tr class="w3-light-gray" style="text-transform: uppercase; height: 30px;">
                                    <th style="text-align: center;" width="22px" title="<?php echo Portal::language('check_all');?>">
                                        <input type="checkbox" value="" id="Product_check" onclick="checkall(this.checked);"/>
                                    </th>
                                    <th nowrap align="center" width="30px" ><?php echo Portal::language('stt');?></th>
                                    <th nowrap align="center" width="60px" ><?php echo Portal::language('product_code');?></th>
                                    
                                    <th nowrap align="center" width="300px" ><?php echo Portal::language('product_name');?></th>
                                    
                                    <th style="text-align: center;" nowrap align="center" width="80px" ><?php echo Portal::language('unit');?></th>
                                    <th style="text-align: center;" nowrap align="center" width="80px" ><?php echo Portal::language('category');?></th>
                                    <th style="text-align: center;" nowrap align="center" width="80px" ><?php echo Portal::language('type');?></th>
                                    <th style="text-align: center;" nowrap align="center" width="100px" ><?php echo Portal::language('department');?></th>
                                    <?php if(User::can_edit(false,ANY_CATEGORY)){?>	
                                    <th style="text-align: center;" width="5%"><?php echo Portal::language('edit');?></th>
                                    <?php }
                                    
                                    if(User::can_delete(false,ANY_CATEGORY)){?>
                                    <th style="text-align: center;" width="5%"><?php echo Portal::language('delete');?></th>
                                    <?php }?>
                                </tr>
                                
                                <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
                                    <tr>
                                        <td style="text-align: center; height: 24px;" ><input type="checkbox" value="1" <?php echo 'id="Product_check_'.$this->map['items']['current']['id'].'"';?> <?php echo 'name="Product_check_'.$this->map['items']['current']['id'].'"';?> onclick="get_ids();" /></td>
                                        <td style="text-align: center;" ><?php echo $this->map['items']['current']['index'];?></td>
                                        <td style="text-align: left;" ><?php echo $this->map['items']['current']['code'];?></td>
                                        <td style="text-align: left;" ><?php echo $this->map['items']['current']['name'];?></td>
                                        <td style="text-align: center;" ><?php echo $this->map['items']['current']['unit'];?></td>
                                        <td style="text-align: center;" ><?php echo $this->map['items']['current']['category_id'];?></td>
                                        <td style="text-align: center;" ><?php echo $this->map['items']['current']['type'];?></td>
                                        <td style="text-align: center;" ><?php echo $this->map['items']['current']['department'];?></td>
                                        <td style="text-align: center;" ><a href="<?php echo Url::build_current(array()+array('cmd'=>'edit','ids'=>$this->map['items']['current']['id'])); ?>"><img src="packages/core/skins/default/images/buttons/edit.gif" alt="<?php echo Portal::language('edit');?>"/></a></td>
                                        <td style="text-align: center;" ><a href="<?php echo Url::build_current(array('cmd'=>'delete','ids'=>$this->map['items']['current']['id'])); ?>"><img src="packages/core/skins/default/images/buttons/delete.gif" alt="<?php echo Portal::language('delete');?>"/></a></td>
                                    </tr>
                                <?php }}unset($this->map['items']['current']);} ?>
    				        </table>
                        </td>
                    </tr>
                </table>
            </td>
   		</tr>
    </table>
    <div class="paging"><?php echo $this->map['paging'];?></div>
    <input  name="cmd" value="" / type ="hidden" value="<?php echo String::html_normalize(URL::get('cmd'));?>">
    <input name="ids" id="ids" type="hidden" value="" />
    <input name="type" id="type" type="hidden" value="<?php echo $this->map['type'];?>" />
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script type="text/javascript">
    check_edit_delete = 0;
    function checkall(val)
    {
        check_edit_delete = 1;
        var inputs = jQuery('input:checkbox');
        for (var i=0;i<inputs.length;i++)
        { 
            if(inputs[i].id.indexOf('Product_check_')==0)
            {
                if(val)
                {
                    inputs[i].checked = 1;
                }
                else
                {
                    inputs[i].checked = 0;
                }
            }
        }
        
        get_ids();
    }
    
    function get_ids()
    {
        check_edit_delete = 2;
        var inputs = jQuery('input:checkbox:checked');
        var strids = "";
        for (var i=0;i<inputs.length;i++)
        { 
            if(inputs[i].id.indexOf('Product_check_')==0)
            {
                strids +=","+inputs[i].id.replace("Product_check_","");
            }
        }
        strids = strids.replace(",","");
        jQuery('#ids').val(strids);
        
    }
    function check_edit(){
        if(check_edit_delete == 0){
            alert('Bạn chưa chọn sản phẩm để sửa');
            return false;
        }
        else{
            openw('edit');
        }
    }
    function check_delete(){
        if(check_edit_delete == 0){
            alert('Bạn chưa chọn sản phẩm để xóa');
            return false;
        }
        else{
            openw('delete')
        }
    }
    function openw(cmd)
    {
        var url = '?page=department_product';
		url += '&cmd='+cmd;
		var ids = jQuery('#ids').val();
        url += '&ids='+ids;
        location.href = url;
    }
</script>