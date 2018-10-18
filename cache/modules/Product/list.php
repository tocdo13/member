<script>
	Product_array_items = {
		'length':'<?php echo sizeof($this->map['items']);?>'
		<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
		,'<?php echo $this->map['items']['current']['i'];?>':'<?php echo $this->map['items']['current']['id'];?>'
		<?php }}unset($this->map['items']['current']);} ?>
	}
</script>
<?php 
$title = Portal::language('product_list');
System::set_page_title(HOTEL_NAME.' - '.Portal::language('product_list'));
?>
<form name="ListProductForm" enctype="multipart/form-data" method="post">
<table width="100%" cellspacing="0" cellpadding="0">
	<tr valign="top">
		<td align="left">
            <table cellpadding="0" cellspacing="0" width="100%" border="0" class="table-bound">
                <tr height="40">
                    <td class="" width="50%" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> <?php echo Portal::language('product_list');?> <?php echo Url::get('type')?Portal::language(Url::get('type')):''; ?> (<?php echo Portal::language('quantity');?>: <?php echo $this->map['total'];?>)</td>
					<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="50%" align="right"><a href="<?php echo URL::build_current(array('cmd'=>'add'));?>"  class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('add');?></a><?php }?>
   					<?php if(User::can_add(false,ANY_CATEGORY)){?><a href="<?php echo URL::build_current(array('cmd'=>'import'));?>"  class="w3-btn w3-lime w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('import_from_excel');?></a><?php }?>
                    <?php if(User::can_edit(false,ANY_CATEGORY)){?><input type="submit" name="export_excel" value="<?php echo Portal::language('export_excel');?>" class="w3-btn w3-teal w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;" /><?php }?>
					<!--<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%" align="right"><a href="<?php echo URL::build_current(array('cmd'=>'syn'));?>"  class="button-medium-add"><?php echo Portal::language('product_synchronize');?></a></td><?php }?>-->                    
                    <?php if(User::can_edit(false,ANY_CATEGORY)){?><a href="javascript:void(0)" onclick="ListProductForm.cmd.value='edit_selected';ListProductForm.submit();"  class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('Edit');?></a><?php }?>
                    <?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0)" onclick="if(!confirm('<?php echo Portal::language('are_you_sure');?>')){return false};ListProductForm.cmd.value='delete_selected';ListProductForm.submit();"  class="w3-btn w3-red w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('Delete');?></a></td><?php }?>
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
                    <legend class="title"><?php echo Portal::language('search_options');?></legend>
                    <table border="0" cellpadding="3" cellspacing="0">
						<tr>
						<td align="right" nowrap style="font-weight:bold"><?php echo Portal::language('portal_name');?></td>
						<td>:</td>
						<td nowrap>
							<select  name="portal_id" id="portal_id" style="width:150px; height: 24px;"><?php
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
						</td>
						<td align="right" nowrap style="font-weight:bold"><?php echo Portal::language('code');?></td>
						<td>:</td>
						<td nowrap>
							<input  name="code" id="code" style="width:50px;  height: 24px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('code'));?>">
						</td><td align="right" nowrap style="font-weight:bold"><?php echo Portal::language('name');?></td>
						<td>:</td>
						<td nowrap>
							<input  name="name" id="name" style="width:100px;  height: 24px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('name'));?>">
						</td>
						<td align="right" nowrap style="font-weight:bold"><?php echo Portal::language('category_id');?></td>
						<td>:</td>
						<td nowrap>
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
						</td>
						<td align="right" nowrap style="font-weight:bold"><?php echo Portal::language('type');?></td>
						<td>:</td>
						<td nowrap>
							<select  name="type" id="type" style="width:100px;  height: 24px;">
								<option value=""><?php echo Portal::language('all');?></option><option value="GOODS"><?php echo Portal::language('goods');?></option><option value="PRODUCT"><?php echo Portal::language('product');?></option><option value="DRINK"><?php echo Portal::language('drink');?></option><option value="MATERIAL"><?php echo Portal::language('material');?></option><option value="EQUIPMENT"><?php echo Portal::language('equipment');?></option><option value="SERVICE"><?php echo Portal::language('service');?></option><option value="TOOL"><?php echo Portal::language('tool');?></option>
							</select>
							<script>
							$('type').value='<?php echo URL::get('type');?>';
							</script>
						</td>
						<td><input name="search" type="submit" value="  <?php echo Portal::language('search');?>  " style=" height: 24px;"/></td>
                        <td><input type="button" value="  <?php echo Portal::language('show_all_product');?>  " style=" height: 24px;" onclick="window.location='<?php echo Url::build_current(); ?>'"/></td>
						</tr>
					</table>
                    </fieldset><br />
					<table width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#C6E2FF">
						<tr class="w3-light-gray" style="text-transform: uppercase; height: 26px;">
                        <th width="1%" title="<?php echo Portal::language('check_all');?>"><input type="checkbox" value="1" id="Product_check_0" onclick="check_all('Product','Product_array_items','#FFFFEC',this.checked);"></th>
                        <th nowrap align="left" >
                            <a href="<?php echo URL::build_current(((URL::get('order_by')=='product.id' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'product.id'));?>" title="<?php echo Portal::language('sort');?>">
                            <?php if(URL::get('order_by')=='product.id') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('code');?></a>							</th>
                            <th nowrap align="left">
                            <a title="<?php echo Portal::language('sort');?>" href="<?php echo URL::build_current(((URL::get('order_by')=='product.name_'.Portal::language() and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'product.name_'.Portal::language()));?>" >
                            <?php if(URL::get('order_by')=='product.name_'.Portal::language()) echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('name');?></a>
                        </th>
                       <th align="center" width="1%" nowrap="nowrap"> <?php echo Portal::language('unit_id');?> </th>
			<th align="center" width="1%" nowrap="nowrap"> <?php echo Portal::language('category');?> </th>
				       <th nowrap align="left" >
							<a href="<?php echo URL::build_current(((URL::get('order_by')=='product.type' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'product.type'));?>" title="<?php echo Portal::language('sort');?>">
							<?php if(URL::get('order_by')=='product.type') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('type');?></a>
							</th>
							<?php if(User::can_edit(false,ANY_CATEGORY)){?>	
                            <th width="1%">&nbsp;</th>
							<?php }
							if(User::can_delete(false,ANY_CATEGORY)){?>
                            <th width="1%">&nbsp;</th>
							<?php }?>
                        </tr>
						<?php $category = '';?>	
						<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current'] = &$item2;?>
						<?php if($category != $this->map['items']['current']['category_id']){$category=$this->map['items']['current']['category_id'];?>
						<tr>
							<td colspan="10" class="category-group"><?php echo $this->map['items']['current']['category_id'];?></td>
						</tr>
						<?php }?>	
						<?php 
						if(Url::get('action')=='select_product')
						{
							$onclick='pick_value(\''.$this->map['items']['current']['id'].'\');window.close();';
						}else{
							$onclick='location=\''.URL::build_current().'&edit_selected=1&selected_ids='.$this->map['items']['current']['id'].'&type='.$this->map['items']['current']['type'].'\';';
						}
						?>
                        <tr <?php echo ($this->map['items']['current']['i']%2==0)?'class="row-odd"':'class="row-even"';?> id="Product_tr_<?php echo $this->map['items']['current']['id'];?>">
							<td><input name="selected_ids[]" type="checkbox" value="<?php echo $this->map['items']['current']['id'];?>" onclick="tr_color = clickage('Product','<?php echo $this->map['items']['current']['i'];?>','Product_array_items','#FFFFEC');" id="Product_check_<?php echo $this->map['items']['current']['i'];?>"></td>
							<td nowrap align="left" id="id_<?php echo $this->map['items']['current']['id'];?>"><?php echo $this->map['items']['current']['id'];?></td>
                            <td nowrap align="left" id="name_<?php echo $this->map['items']['current']['id'];?>"><?php echo $this->map['items']['current']['name'];?></td>
                            <td align="center" nowrap="nowrap" id="unit_<?php echo $this->map['items']['current']['id'];?>"> <?php echo $this->map['items']['current']['unit_id'];?> </td>
                            <td align="center" nowrap="nowrap" id="unit_<?php echo $this->map['items']['current']['id'];?>"> <?php echo $this->map['items']['current']['category_id'];?> </td>
		      			  <td nowrap align="left"><?php echo $this->map['items']['current']['type'];?></td>
                                <?php if(Url::get('page')=='spa_room'){?>
                                <td align="center" nowrap>
                                	<a href="<?php echo Url::build('start_term_remain',array('spa_room_id'=>$this->map['items']['current']['id']));?>"><?php echo Portal::language('declare');?></a></td>
                                <?php }?>
							<?php 
				if((User::can_edit(false,ANY_CATEGORY)))
				{?>
							<td nowrap><a href="<?php echo Url::build_current(array()+array('cmd'=>'edit_selected','selected_ids'=>$this->map['items']['current']['id'],'code','name','category_id','type')); ?>"><img src="packages/core/skins/default/images/buttons/edit.gif" alt="<?php echo Portal::language('edit');?>"></a></td>
							
				<?php
				}
				?>
							<?php 
				if((User::can_delete(false,ANY_CATEGORY)))
				{?>
							<td nowrap><a href="<?php echo Url::build_current(array('cmd'=>'delete','category_id','code','name','id'=>$this->map['items']['current']['id'])); ?>"><img src="packages/core/skins/default/images/buttons/delete.gif" alt="<?php echo Portal::language('delete');?>"></a>							</td>
							
				<?php
				}
				?>
							</tr>
						<?php }}unset($this->map['items']['current']);} ?>
				</table>
                <?php echo $this->map['paging'];?>             
		</td>
		</tr>
	</table>
	<input  name="cmd" value="" / type ="hidden" value="<?php echo String::html_normalize(URL::get('cmd'));?>">
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
				
<script>
		function pick_value(id)
	{
		if (window.opener && !window.opener.closed)
		{
			if(window.opener.document.getElementById('product_id_'+window.opener.document.current_product_id))
			{
				window.opener.document.getElementById('product_id_'+window.opener.document.current_product_id).value=($('id_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
				window.opener.document.getElementById('product_name_'+window.opener.document.current_product_id).value=($('name_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
				//window.opener.document.getElementById('product_price_'+window.opener.document.current_product_id).value=($('price_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
				window.opener.document.getElementById('unit_'+window.opener.document.current_product_id).value=($('unit_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
			}
			else
			if(window.opener.document.getElementById('product_id'))
			{
				window.opener.document.getElementById('product_id').value=($('id_'+id).innerText).replace(/^\s*|\s(?=\s)|\s*$/g, "");
			}
		}
	}
</script>