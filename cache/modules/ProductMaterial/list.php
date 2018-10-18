<link href="skins/default/room.css" rel="stylesheet" type="text/css" />
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('title'));?>
	<input  name="action" id="action" type="hidden"/>
	<input  name="group_deleted_ids" id="group_deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>"/>
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><?php echo Portal::language('product_limit');?></td>
            <td width="60%" nowrap="nowrap" style="padding-right: 30px;">
    	        <input type="button" name="add_to_all" onclick="getUrl(this);" value="<?php echo Portal::language('add_to_all');?>" class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"/>
	    	    <input type="button" name="remove_all" onclick="if(confirm('Are you sure to delete all?'))getUrl(this);" value="<?php echo Portal::language('remove_all');?>"  class="w3-btn w3-red w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"/>
        		<?php if(User::can_add(false,ANY_CATEGORY)){?><input type="button" value="<?php echo Portal::language('import_from_excel');?>" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'import',));?>'" class="w3-btn w3-lime w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"/><?php }?>
                <?php if(User::can_add(false,ANY_CATEGORY)){?><input type="button" value="<?php echo Portal::language('export_to_excel');?>" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'export_excel',));?>'" class="w3-btn w3-teal w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"/><?php }?>
                <input type="button" onclick="location='<?php echo URL::build('product',array('cmd'=>'add'));?>'" value="<?php echo Portal::language('add_product');?>" class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"/>
            </td>
        </tr>
    </table>    

<div class="body">
    <div style="border:2px solid #FFFFFF;">
    
    <form name="frmSearch" method="post">
        <fieldset>
        	<legend class="title"><?php echo Portal::language('search');?></legend>
            
            <table border="0" cellpadding="3" cellspacing="0">
    				<tr>
    					<td align="right" nowrap style="font-weight:bold"><?php echo Portal::language('code');?></td>
    					<td>:</td>
    					<td nowrap>
    						<input  name="product_id" id="product_id" style="width:50px; height: 24px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('product_id'));?>">
    					</td>
                        <td align="right" nowrap style="font-weight:bold"><?php echo Portal::language('name');?></td>
    					<td>:</td>
    					<td nowrap>
    						<input  name="product_name" id="product_name" style="width:200px; height: 24px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('product_name'));?>">
    					</td>
    					<td align="right" nowrap style="font-weight:bold"><?php echo Portal::language('category');?></td>
    					<td>:</td>
    					<td nowrap>
    						<select  name="category_id" id="category_id" style=" height: 24px;"><?php
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
						    <select  name="type" id="type" style="width:80px; height: 24px;">
								<option value=""><?php echo Portal::language('all');?></option><option value="PRODUCT"><?php echo Portal::language('product');?></option><option value="DRINK"><?php echo Portal::language('drink');?></option>
							</select>
							<script>
							$('type').value='<?php echo URL::get('type');?>';
							</script>
    					</td>
    					<td><input name="search" type="submit" value="<?php echo Portal::language('search');?>" style=" height: 24px;"/></td>
                        <td><input type="submit" name="no_material" value="<?php echo Portal::language('product_have_not_material');?>" style=" height: 24px;"/></td>
    				</tr>
    		</table>
        </fieldset>
    
    <br />

	<table cellpadding="5" cellspacing="0" border="1" style="border-collapse:collapse" bordercolor="#CECFCE" width="100%">
        <tr valign="middle" bgcolor="#EFEFEF">
            <th align="left" style="width: 30px;" ><?php echo Portal::language('stt');?></th>
            <th align="left" ><?php echo Portal::language('code');?></th>
            <th align="left" style="width: 200px;"><?php echo Portal::language('name');?></th>
            <th align="left" style="width: 50px;"><?php echo Portal::language('unit_id');?></th>
            <th><?php echo Portal::language('product_limit');?></th>
            <?php if(User::can_edit(false,ANY_CATEGORY)){?>
            <th width="70px"><?php echo Portal::language('update');?></th>
            <?php }?> 
            <th></th> 
            <?php if(User::can_delete(false,ANY_CATEGORY)){?>
            <th style="width: 90px;" ><?php echo Portal::language('delete_material');?></th>
            <?php }?>
            <th align="center"><input type="checkbox" id="all_item_check_box"/></th>            
		</tr>
        <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
        <tr valign="middle" <?php Draw::hover('#E2F1DF');?>style="cursor:hand;">
            <td align="center"><?php echo $this->map['items']['current']['stt'];?></td>
            <td align="left"><?php echo $this->map['items']['current']['id'];?></td>
            <td align="left" id="<?php echo $this->map['items']['current']['id'];?>"><?php echo $this->map['items']['current']['name'];?></td>
            <td align="center" ><?php echo $this->map['items']['current']['unit'];?></td>
            <td align="left" ><?php echo $this->map['items']['current']['product_material'];?></td>
            <?php if(User::can_edit(false,ANY_CATEGORY)){?>
            <td align="center">
                <?php 
				if(($this->map['items']['current']['product_material']==''))
				{?>
                <input type="button" onclick="location='<?php echo Url::build_current(array('cmd'=>'add','product_id'=>$this->map['items']['current']['id'],'product_name'=>$this->map['items']['current']['name'],'protal_id'=>PORTAL_ID));?>'" value="<?php echo Portal::language('add_material');?>"  class="button-medium-add" />
                 <?php }else{ ?>
                <a href="<?php echo Url::build_current(array('cmd'=>'edit','product_id'=>$this->map['items']['current']['id'],'product_name'=>$this->map['items']['current']['name'],'protal_id'=>PORTAL_ID,'category_id','type'));?>"><img src="packages/core/skins/default/images/buttons/edit.gif" /></a>
                
				<?php
				}
				?>
            </td>
            <?php }?> 
            <td align="center">
                <?php if(User::can_view(false,ANY_CATEGORY)){?><a title="<?php echo Portal::language('export_to_excel');?>" onclick="" href="<?php echo Url::build_current(array('export'=>'excel','id'=>$this->map['items']['current']['id'],'protal_id'=>PORTAL_ID)); ?>"><img src="packages/core/skins/default/images/buttons/gotopage.jpg" alt="<?php echo Portal::language('export');?>"/></a></td><?php }?>
            </td>
            <td align="center">
                <?php if(User::can_delete(false,ANY_CATEGORY)){?><a onclick="if(!confirm('Are you sure to delete?')) return false;" href="<?php echo Url::build_current(array('cmd'=>'delete','product_id'=>$this->map['items']['current']['id'],'protal_id'=>PORTAL_ID)); ?>"><img src="packages/core/skins/default/images/buttons/delete.gif" alt="<?php echo Portal::language('delete_material');?>"/></a></td><?php }?>
            </td>
            <td align="center">
                <input name="selected_ids[]" type="checkbox" class="item-check-box" value="<?php echo $this->map['items']['current']['id'];?>"/>
            </td>
        </tr>
        <?php }}unset($this->map['items']['current']);} ?>
	</table>
    </div>
    <div><?php echo $this->map['paging'];?></div>
</div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			

<script type="text/javascript"> 
	jQuery("#all_item_check_box").click(function(){
		var check = this.checked;
		jQuery(".item-check-box").each(function(){
			this.checked = check;
		});
	});
    function getUrl(obj)
    {
        var all_checked = new Array();
        all_checked  = jQuery(".item-check-box:checked");
        var str_id = '';
        var str_name = '';
        for(var i = 0; i<all_checked.length; i++)
        {
            str_id+= jQuery(all_checked[i]).val()+',';
            str_name+= jQuery("#"+jQuery(all_checked[i]).val()).html()+',';
        }
        str_id = str_id.substring(0,str_id.length-1);
        str_name = str_name.substring(0,str_name.length-1);
        if(!str_id)
        {
            alert('<?php echo Portal::language('you_must_choose_product');?>');
            return false;    
        }
		var url = '?page=product_material';
        if(jQuery(obj).attr('name')=='add_to_all')
            url += '&cmd=add';
        else
            url += '&cmd=remove_all';
		url += '&product_id='+str_id+"&product_name="+str_name;
        url += '&portal_id='+<?php echo '\''.PORTAL_ID.'\'';?>;
		window.location=url;
    }
</script>
