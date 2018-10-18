<div class="product-supplier-bound">
<form name="ListStartTermRemainForm" method="post">
	<table cellpadding="15" cellspacing="0" width="70%" border="0" >
		<tr>
        	<td width="70%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> [[|title|]]</td>
            <td width="30%" align="right" nowrap="nowrap" style="padding-right: 30px;">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><input type="button" value="[[.add_new.]]" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'add','type'));?>'" class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; margin-right: 5px;"/><?php }?>
                <?php if(User::can_delete(false,ANY_CATEGORY)){?><input type="button" value="[[.delete.]]" id="delete_button" class="w3-btn w3-red" style="text-transform: uppercase;" /><?php }?>
                                
            </td>
        </tr>
    </table>
	<div class="content">
    	<div class="search-box">
        	<fieldset>
            	<legend class="title">[[.search.]]</legend>
        		<span>[[.warehouse.]]:</span> 
        		<select name="warehouse_id" id="warehouse_id" style="height: 24px;"></select>
                <span>[[.product_id.]]:</span>
                <input name="product_id" type="text" id="product_id" style="height: 24px;"/> 
                <input type="submit" name="search" value="[[.search.]]" style="height: 24px;"/>
            </fieldset>
        </div>
        <br />
		<table width="70%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC">
            <tr class="w3-light-gray" style="text-transform: uppercase; height: 24px;">
                <th width="10"><input type="checkbox" id="all_item_check_box"/></th>
                <th width="30">[[.order_number.]]</th>
                <th width="100" align="center">[[.product_code.]]</th>
                <th width="200" align="center">[[.product.]]</th>
                <th width="80" align="center">[[.quantity.]]</th>
                <th width="100" align="center">[[.total_start_term_price.]]</th>
                <th width="40">[[.edit.]]</th>
                <th width="40">[[.delete.]]</th>
            </tr>
            <?php $warehouse = '';?>
            <!--LIST:items-->
            <?php if($warehouse != [[=items.warehouse_id=]]){ $warehouse = [[=items.warehouse_id=]];?>
			<tr>
                <td colspan="8" class="category-group">[[|items.warehouse_name|]]</td>
			</tr>  
            <?php }?>
            <tr>  
                <td width="1%"><input name="item_check_box[]" type="checkbox" class="item-check-box" value="[[|items.id|]]"></td>
                <td style="cursor:pointer;">[[|items.i|]]</td>
                <td style="cursor:pointer;">[[|items.product_id|]]</td>
                <td style="cursor:pointer;">[[|items.product_name|]]</td>
                <td style="cursor:pointer;text-align: right;">[[|items.quantity|]]</td>
                <td style="cursor:pointer; text-align: right;">[[|items.total_start_term_price|]]</td>
                <td style="text-align: center;"><a href="<?php echo Url::build_current(array('cmd'=>'edit','type','id'=>[[=items.id=]]));?>"><i class="fa fa-pencil w3-text-orange" style="font-size: 20px; padding-top: 2px;"></i></a></td>
                <td style="text-align: center;"><a class="delete-one-item" href="<?php echo Url::build_current(array('cmd'=>'delete','type','id'=>[[=items.id=]]));?>"><i class="fa fa-times-circle w3-text-red" style="font-size: 20px; padding-top: 2px;"></i></a></td>
			</tr>
            <!--/LIST:items-->			
		</table>
		<br />
		<div class="paging">[[|paging|]]</div>
	</div>
	<input name="cmd" type="hidden" value=""/>
</form>	
</div>
<script type="text/javascript" src="packages/core/includes/js/jquery/jquery.maskedinput.js"></script>
<script type="text/javascript">
	jQuery("#create_date").mask("99/99/9999");
	jQuery("#delete_button").click(function (){
        if(confirm('[[.are_you_sure.]]')){
            ListStartTermRemainForm.cmd.value = 'delete';
    		ListStartTermRemainForm.submit();
        };
		
	});
	jQuery(".delete-one-item").click(function (){
		if(!confirm('[[.are_you_sure.]]')){
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