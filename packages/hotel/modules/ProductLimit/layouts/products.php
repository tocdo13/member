<link href="skins/default/room.css" rel="stylesheet" type="text/css" />
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('title'));?>
	<input  name="action" id="action" type="hidden">
	<input  name="group_deleted_ids" id="group_deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td class="form-title">[[.product_limit.]]</td>
            <td width="1%" nowrap="nowrap">
    	        <input type="button" onclick="location='<?php echo URL::build_current(array('cmd'=>'add'));?>'" value="[[.add_to_all.]]" class="button-medium-add"/>
	    	    <input type="button" onclick="if(confirm('Are you sure to delete all?'))location='<?php echo URL::build_current(array('cmd'=>'remove_all'));?>'" value="[[.remove_all.]]"  class="button-medium-delete"/>
        		<input type="button" onclick="location='<?php echo URL::build('restaurant_product',array('cmd'=>'add'));?>'" value="[[.add_product.]]" class="button-medium-add" />
            </td>
        </tr>
    </table>
<div class="body">
    <div style="border:2px solid #FFFFFF;">
	<table cellpadding="5" cellspacing="0" border="1" style="border-collapse:collapse" bordercolor="#CECFCE" width="100%">
        <tr valign="middle" bgcolor="#EFEFEF">
            <th nowrap align="left" >[[.code.]]</th>
            <th nowrap align="left">[[.name.]]</th>
            <th nowrap align="left">[[.unit_id.]]</th>
            <th>[[.product_limit.]]</th>
            <th>[[.add_material.]]</th>
            <th>[[.edit.]]</th>            
		</tr>
        <!--LIST:items-->
        <tr valign="middle" <?php Draw::hover('#E2F1DF');?>style="cursor:hand;" id="Product_tr_[[|items.id|]]">
            <td nowrap align="left" id="id_[[|items.id|]]">[[|items.id|]]</td>
            <td nowrap align="left" id="name_[[|items.id|]]" style="width:300px;">[[|items.name|]]</td>
            <td nowrap align="left" id="unit_[[|items.id|]]">[[|items.unit_id|]]</td><td nowrap align="left">[[|items.product_material|]]</td>
            <td><input type="button" onclick="location='<?php echo URL::build_current(array('cmd'=>'add','product_price_id'=>[[=items.id=]]));?>'" value="[[.add_material.]]"  class="button-medium-add" /></td>
            <td><a href="<?php echo URL::build_current(array('cmd'=>'edit','product_price_id'=>[[=items.id=]]));?>"><img src="packages/core/skins/default/images/buttons/edit.gif" /></a></td>
		</tr>
        <!--/LIST:items-->
	</table>
    </div>
        <div>[[|paging|]]</div>
</div>
