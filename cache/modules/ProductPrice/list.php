<div class="product-supplier-bound">
<form name="ListProductPriceForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td style="width: 40%; text-transform: uppercase; font-size: 18px; padding-left: 15px;" class=""><?php echo Portal::language('product_price_list');?></td>
            <td width="60%" nowrap="nowrap" align="right" style="padding-right: 30px;">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><input type="button" value="<?php echo Portal::language('add_new');?>" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'add','type','portal_id'=>$this->map['portal_id']));?>'" class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"/><?php }?>
                <?php if(User::can_add(false,ANY_CATEGORY)){?><input type="button" value="<?php echo Portal::language('import_from_excel');?>" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'import','type','portal_id'=>$this->map['portal_id']));?>'" class="w3-btn w3-lime w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"/><?php }?>
                <?php if(User::can_add(false,ANY_CATEGORY)){?><input name="export_to_excel" type="submit" value="<?php echo Portal::language('export_to_excel');?>" class="w3-btn w3-teal w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"/><?php }?>
                <?php if(User::can_edit(false,ANY_CATEGORY)){?><input type="button" value="<?php echo Portal::language('export_cache');?>" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'cache','type','portal_id'=>$this->map['portal_id']));?>'" class="w3-btn w3-indigo w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"/><?php }?>
                <?php if(User::can_edit(false,ANY_CATEGORY)){?><a href="javascript:void(0)" onclick="ListProductPriceForm.act.value='edit_selected';ListProductPriceForm.submit();"  class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('Edit');?></a><?php }?>                
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0)" id="delete_button" class="w3-btn w3-red w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('delete');?></a><?php }?>
            </td>
        </tr>
    </table>
    
	<div class="content">
    	<div class="search-box">
            <a name="top_anchor"></a>
        	<fieldset>
            	<legend class="title"><?php echo Portal::language('select');?></legend>
                <?php 
                    if(User::can_admin(false,ANY_CATEGORY))
                    {
                ?>
                <span><?php echo Portal::language('portal');?>:</span> 
        		<select  name="portal_id" id="portal_id" onchange="ListProductPriceForm.act.value='search_portal';ListProductPriceForm.submit();" style="height: 24px;"><?php
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

        		<span><?php echo Portal::language('department');?>:</span> 
        		<select   name="department_code" id="department_code" onchange="ListProductPriceForm.act.value='search_department';ListProductPriceForm.submit();" style="height: 24px;"><?php echo $this->map['department_list'];?></select>
                <script>$('department_code').value = "<?php echo Url::get('department_code');?>";</script>
            </fieldset>
        	
            <fieldset>
            	<legend class="title"><?php echo Portal::language('search');?></legend>
                
                <table border="0" cellpadding="3" cellspacing="0">
						<tr>
    						<td align="right" nowrap style="font-weight:bold"><?php echo Portal::language('code');?></td>
    						<td>:</td>
    						<td nowrap>
    							<input  name="product_id" id="product_id" style="width:50px;height: 24px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('product_id'));?>">
    						</td><td align="right" nowrap style="font-weight:bold"><?php echo Portal::language('name');?></td>
    						<td>:</td>
    						<td nowrap>
    							<input  name="product_name" id="product_name" style="width:100px; height: 24px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('product_name'));?>">
    						</td>
    						<td align="right" nowrap style="font-weight:bold"><?php echo Portal::language('category_id');?></td>
    						<td>:</td>
    						<td nowrap>
    							<select  name="category_id" id="category_id" style="width:150px;height: 24px;"><?php
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
    						</td>
    						<td align="right" nowrap style="font-weight:bold"><?php echo Portal::language('type');?></td>
    						<td>:</td>
    						<td nowrap>
    							<select  name="type" id="type" style="width:80px;height: 24px;">
    								<option value=""><?php echo Portal::language('all');?></option><option value="GOODS"><?php echo Portal::language('goods');?></option><option value="PRODUCT"><?php echo Portal::language('product');?></option><option value="DRINK"><?php echo Portal::language('drink');?></option><option value="MATERIAL"><?php echo Portal::language('material');?></option><option value="EQUIPMENT"><?php echo Portal::language('equipment');?></option><option value="SERVICE"><?php echo Portal::language('service');?></option><option value="TOOL"><?php echo Portal::language('tool');?></option>
    							</select>
    							<script>
    							$('type').value='<?php echo URL::get('type');?>';
    							</script>
    						</td>
    						</td><td align="right" nowrap style="font-weight:bold"><?php echo Portal::language('start_date');?></td>
    						<td>:</td>
    						<td nowrap>
    							<input  name="start_date" id="start_date" onchange="changevalue();" style="width:70px;height: 24px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('start_date'));?>">
    						</td>
    						</td><td align="right" nowrap style="font-weight:bold"><?php echo Portal::language('end_date');?></td>
    						<td>:</td>
    						<td nowrap>
    							<input  name="end_date" id="end_date" onchange="changefromday();" style="width:70px;height: 24px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('end_date'));?>">
    						</td>
    						<td><input name="search" type="submit" value="  <?php echo Portal::language('search');?>  " style="height: 24px;"/></td>
                            <script>
                                        function changevalue(){
                                            var myfromdate = $('start_date').value.split("/");
                                            var mytodate = $('end_date').value.split("/");
                                            if(myfromdate[2] > mytodate[2]){
                                                $('end_date').value =$('start_date').value;
                                            }else{
                                                if(myfromdate[1] > mytodate[1]){
                                                    $('end_date').value =$('start_date').value;
                                                }else{
                                                    if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1])){
                                                        $('end_date').value =$('start_date').value;
                                                    }
                                                }
                                            }
                                        }
                                        function changefromday(){
                                            var myfromdate = $('start_date').value.split("/");
                                            var mytodate = $('end_date').value.split("/");
                                            if(myfromdate[2] > mytodate[2]){
                                                $('start_date').value= $('end_date').value;
                                            }else{
                                                if(myfromdate[1] > mytodate[1]){
                                                    $('start_date').value = $('end_date').value;
                                                }else{
                                                    if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1])){
                                                        $('start_date').value =$('end_date').value;
                                                    }
                                                }
                                            }
                                        }
                                    </script>
						</tr>
				</table>
            </fieldset>
        </div>
        
		<table width="100%" border="1" cellspacing="0" cellpadding="3" bordercolor="#CCCCCC">
            <tr bgcolor="#F1F1F1">
                <th width="10"><input type="checkbox" id="all_item_check_box"/></th>
                <th width="30"><?php echo Portal::language('order_number');?></th>
                <th width="150" align="center"><?php echo Portal::language('product_code');?></th>
                <th width="300" align="center"><?php echo Portal::language('product');?></th>
                <th width="100" align="center"><?php echo Portal::language('unit');?></th>
                <th width="150" align="center"><?php echo Portal::language('type');?></th>
                <th width="100" align="center"><?php echo Portal::language('price');?></th>
                <th width="150" align="center"><?php echo Portal::language('category');?></th> 
                <th width="150" align="right"><?php echo Portal::language('start_date');?></th>
                <th width="150" align="right"><?php echo Portal::language('end_date');?></th>                             
                <th width="50">&nbsp;</th>
                <th width="50">&nbsp;</th>
            </tr>
            <?php $department = '';?>
            <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
            <?php if($department != $this->map['items']['current']['department_code']){ $department = $this->map['items']['current']['department_code'];?>
            <tr>
                <td colspan="10" class="category-group">
                <?php echo $this->map['items']['current']['department_name'];?>
                <input type="button" value="<?php echo Portal::language('copy');?>" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'copy','type','portal_id'=>$this->map['portal_id'],'department_code'=>$this->map['items']['current']['department_code']));?>'"/>
                </td>
                <td colspan="1" style="text-align: right;">
                    <a href="<?php echo Url::build_current(array('cmd'=>'edit','department_code'=>$this->map['items']['current']['department_code'],'portal_id'=>$this->map['portal_id'],'product_id','product_name','category_id','type'));?>">
                        <img src="packages/core/skins/default/images/buttons/edit.gif" />
                    </a>
                </td>
            </tr>  
            <?php }?>
            <tr>  
                <td width="1%">
                    <input name="selected_ids[]" type="checkbox" class="item-check-box" value="<?php echo $this->map['items']['current']['id'];?>"/>
                </td>
                <td style="cursor:pointer;"><?php echo $this->map['items']['current']['i'];?></td>
                <td style="cursor:pointer;"><?php echo $this->map['items']['current']['product_id'];?></td>
                <td style="cursor:pointer;"><?php echo $this->map['items']['current']['product_name'];?></td>
                <td style="cursor:pointer;"><?php echo $this->map['items']['current']['unit'];?></td>  
                <td style="cursor:pointer;"><?php echo $this->map['items']['current']['type'];?></td>                 
                <td style="cursor:pointer;" align="right"><?php echo $this->map['items']['current']['price'];?></td>
                <td style="cursor:pointer;"><?php echo $this->map['items']['current']['category_name'];?></td>
                <td style="cursor:pointer;color:#090;" align="right"><?php echo $this->map['items']['current']['start_date'];?></td>
                <td style="cursor:pointer;color:#F00;" align="right"><?php echo $this->map['items']['current']['end_date'];?></td>
                <td align="right">
                    <a target="_blank" href="<?php echo Url::build_current(array('cmd'=>'edit','department_code'=>$this->map['items']['current']['department_code'],'id'=>$this->map['items']['current']['id'],'portal_id'=>$this->map['portal_id'],'product_id','product_name','category_id','type'));?>">
                        <img src="packages/core/skins/default/images/buttons/edit.gif" />
                    </a>
                </td>
        		<td align="right">
                    <a href="" onclick="if(!confirm('<?php echo Portal::language('are_you_sure');?>')){return false;}else{ return check_delete('<?php echo $this->map['items']['current']['id'];?>')}">
                        <img src="packages/core/skins/default/images/buttons/delete.gif" alt="X�a"/>
                    </a>
                </td>
                
            </tr>
            <?php }}unset($this->map['items']['current']);} ?>			
		</table>
        <div class="paging"><?php echo $this->map['paging'];?></div>
        <table width="100%">
            <tr>
    			<td width="100%">
    				<?php echo Portal::language('select');?>:&nbsp;
    				<a href="javascript:void(0)" onclick="select_all_checkbox(document.ListProductPriceForm,'ManagePortal',true,'#FFFFEC','white');"><?php echo Portal::language('select_all');?></a>&nbsp;
    				<a href="javascript:void(0)" onclick="select_all_checkbox(document.ListProductPriceForm,'ManagePortal',false,'#FFFFEC','white');"><?php echo Portal::language('select_none');?></a>
    				<a href="javascript:void(0)" onclick="select_all_checkbox(document.ListProductPriceForm,'ManagePortal',-1,'#FFFFEC','white');"><?php echo Portal::language('select_invert');?></a>
    			</td>
    			<td>
    				<a name="bottom_anchor" href="#top_anchor"><img src="<?php echo Portal::template('core');?>/images/top.gif" title="<?php echo Portal::language('top');?>" border="0" alt="<?php echo Portal::language('top');?>"/></a>
    			</td>
			</tr>
        </table>
        <br />
        <br />
        <br />
	</div>
	<input  name="act" value=""/ type ="hidden" value="<?php echo String::html_normalize(URL::get('act'));?>">
    <input  name="cmd" value=""/ type ="hidden" value="<?php echo String::html_normalize(URL::get('cmd'));?>">
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
				
</div>

<script type="text/javascript" src="packages/core/includes/js/jquery/jquery.maskedinput.js"></script>
<script type="text/javascript">
	//jQuery("#create_date").mask("99/99/9999");
    jQuery("#start_date").datepicker();
    jQuery("#end_date").datepicker();
	jQuery("#delete_button").click(function(){
        if(confirm('<?php echo Portal::language('are_you_sure');?>'))
        {
    		ListProductPriceForm.cmd.value = 'delete_selected';
    		ListProductPriceForm.submit();
        }
        else
        {
            return false;
        }
	});
    
	jQuery(".delete-one-item").click(function(){
		if(!confirm('<?php echo Portal::language('are_you_sure');?>'))
        {
			return false;
		}
	});
    
	jQuery("#all_item_check_box").click(function(){
		var check = this.checked;
		jQuery(".item-check-box").each(function(){
			this.checked = check;
		});
	});
    function check_delete(id)
    {
        <?php echo 'var block_id = '.Module::block_id().';';?>
        jQuery.ajax({
					url:"form.php?block_id="+block_id,
					type:"POST",
					data:{check_delete:'check_delete',id_check:id},
					success:function(check)
                    {
                        if(check==1)
                        {
                            alert("Đã tồn tại dữ liệu trong hóa đơn. Không thể xóa!");
                            return false;
                        }
                        else
                        {
                            window.location='?page=product_price&cmd=delete&id='+id;
                        }
					}
		});  
    }
    
</script>