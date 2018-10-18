<link href="skins/default/room.css" rel="stylesheet" type="text/css" />
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('title'));?>
	<input  name="action" id="action" type="hidden"/>
	<input  name="group_deleted_ids" id="group_deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>"/>
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;">[[.product_limit.]]</td>
            <td width="60%" nowrap="nowrap" style="padding-right: 30px;">
    	        <input type="button" name="add_to_all" onclick="getUrl(this);" value="[[.add_to_all.]]" class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"/>
	    	    <input type="button" name="remove_all" onclick="if(confirm('Are you sure to delete all?'))getUrl(this);" value="[[.remove_all.]]"  class="w3-btn w3-red w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"/>
        		<?php if(User::can_add(false,ANY_CATEGORY)){?><input type="button" value="[[.import_from_excel.]]" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'import',));?>'" class="w3-btn w3-lime w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"/><?php }?>
                <?php if(User::can_add(false,ANY_CATEGORY)){?><input type="button" value="[[.export_to_excel.]]" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'export_excel',));?>'" class="w3-btn w3-teal w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"/><?php }?>
                <input type="button" onclick="location='<?php echo URL::build('product',array('cmd'=>'add'));?>'" value="[[.add_product.]]" class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"/>
            </td>
        </tr>
    </table>    

<div class="body">
    <div style="border:2px solid #FFFFFF;">
    
    <form name="frmSearch" method="post">
        <fieldset>
        	<legend class="title">[[.search.]]</legend>
            
            <table border="0" cellpadding="3" cellspacing="0">
    				<tr>
    					<td align="right" nowrap style="font-weight:bold">[[.code.]]</td>
    					<td>:</td>
    					<td nowrap>
    						<input name="product_id" type="text" id="product_id" style="width:50px; height: 24px;"/>
    					</td>
                        <td align="right" nowrap style="font-weight:bold">[[.name.]]</td>
    					<td>:</td>
    					<td nowrap>
    						<input name="product_name" type="text" id="product_name" style="width:200px; height: 24px;"/>
    					</td>
    					<td align="right" nowrap style="font-weight:bold">[[.category.]]</td>
    					<td>:</td>
    					<td nowrap>
    						<select name="category_id" id="category_id" style=" height: 24px;"></select>
    					</td>
    					<td align="right" nowrap style="font-weight:bold">[[.type.]]</td>
    					<td>:</td>
    					<td nowrap>
						    <select  name="type" id="type" style="width:80px; height: 24px;">
								<option value="">[[.all.]]</option><option value="PRODUCT">[[.product.]]</option><option value="DRINK">[[.drink.]]</option>
							</select>
							<script>
							$('type').value='<?php echo URL::get('type');?>';
							</script>
    					</td>
    					<td><input name="search" type="submit" value="[[.search.]]" style=" height: 24px;"/></td>
                        <td><input type="submit" name="no_material" value="[[.product_have_not_material.]]" style=" height: 24px;"/></td>
    				</tr>
    		</table>
        </fieldset>
    
    <br />

	<table cellpadding="5" cellspacing="0" border="1" style="border-collapse:collapse" bordercolor="#CECFCE" width="100%">
        <tr valign="middle" bgcolor="#EFEFEF">
            <th align="left" style="width: 30px;" >[[.stt.]]</th>
            <th align="left" >[[.code.]]</th>
            <th align="left" style="width: 200px;">[[.name.]]</th>
            <th align="left" style="width: 50px;">[[.unit_id.]]</th>
            <th>[[.product_limit.]]</th>
            <?php if(User::can_edit(false,ANY_CATEGORY)){?>
            <th width="70px">[[.update.]]</th>
            <?php }?> 
            <th></th> 
            <?php if(User::can_delete(false,ANY_CATEGORY)){?>
            <th style="width: 90px;" >[[.delete_material.]]</th>
            <?php }?>
            <th align="center"><input type="checkbox" id="all_item_check_box"/></th>            
		</tr>
        <!--LIST:items-->
        <tr valign="middle" <?php Draw::hover('#E2F1DF');?>style="cursor:hand;">
            <td align="center">[[|items.stt|]]</td>
            <td align="left">[[|items.id|]]</td>
            <td align="left" id="[[|items.id|]]">[[|items.name|]]</td>
            <td align="center" >[[|items.unit|]]</td>
            <td align="left" >[[|items.product_material|]]</td>
            <?php if(User::can_edit(false,ANY_CATEGORY)){?>
            <td align="center">
                <!--IF:cond([[=items.product_material=]]=='')-->
                <input type="button" onclick="location='<?php echo Url::build_current(array('cmd'=>'add','product_id'=>[[=items.id=]],'product_name'=>[[=items.name=]],'protal_id'=>PORTAL_ID));?>'" value="[[.add_material.]]"  class="button-medium-add" />
                <!--ELSE-->
                <a href="<?php echo Url::build_current(array('cmd'=>'edit','product_id'=>[[=items.id=]],'product_name'=>[[=items.name=]],'protal_id'=>PORTAL_ID,'category_id','type'));?>"><img src="packages/core/skins/default/images/buttons/edit.gif" /></a>
                <!--/IF:cond-->
            </td>
            <?php }?> 
            <td align="center">
                <?php if(User::can_view(false,ANY_CATEGORY)){?><a title="[[.export_to_excel.]]" onclick="" href="<?php echo Url::build_current(array('export'=>'excel','id'=>[[=items.id=]],'protal_id'=>PORTAL_ID)); ?>"><img src="packages/core/skins/default/images/buttons/gotopage.jpg" alt="[[.export.]]"/></a></td><?php }?>
            </td>
            <td align="center">
                <?php if(User::can_delete(false,ANY_CATEGORY)){?><a onclick="if(!confirm('Are you sure to delete?')) return false;" href="<?php echo Url::build_current(array('cmd'=>'delete','product_id'=>[[=items.id=]],'protal_id'=>PORTAL_ID)); ?>"><img src="packages/core/skins/default/images/buttons/delete.gif" alt="[[.delete_material.]]"/></a></td><?php }?>
            </td>
            <td align="center">
                <input name="selected_ids[]" type="checkbox" class="item-check-box" value="[[|items.id|]]"/>
            </td>
        </tr>
        <!--/LIST:items-->
	</table>
    </div>
    <div>[[|paging|]]</div>
</div>
</form>

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
            alert('[[.you_must_choose_product.]]');
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
