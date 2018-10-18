<div class="product-supplier-bound">
<form name="ListProductPriceForm" method="post">
	<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr>
        	<td style="width: 40%; text-transform: uppercase; font-size: 18px; padding-left: 15px;" class="">[[.product_price_list.]]</td>
            <td width="60%" nowrap="nowrap" align="right" style="padding-right: 30px;">
            	<?php if(User::can_add(false,ANY_CATEGORY)){?><input type="button" value="[[.add_new.]]" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'add','type','portal_id'=>[[=portal_id=]]));?>'" class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"/><?php }?>
                <?php if(User::can_add(false,ANY_CATEGORY)){?><input type="button" value="[[.import_from_excel.]]" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'import','type','portal_id'=>[[=portal_id=]]));?>'" class="w3-btn w3-lime w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"/><?php }?>
                <?php if(User::can_add(false,ANY_CATEGORY)){?><input name="export_to_excel" type="submit" value="[[.export_to_excel.]]" class="w3-btn w3-teal w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"/><?php }?>
                <?php if(User::can_edit(false,ANY_CATEGORY)){?><input type="button" value="[[.export_cache.]]" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'cache','type','portal_id'=>[[=portal_id=]]));?>'" class="w3-btn w3-indigo w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"/><?php }?>
                <?php if(User::can_edit(false,ANY_CATEGORY)){?><a href="javascript:void(0)" onclick="ListProductPriceForm.act.value='edit_selected';ListProductPriceForm.submit();"  class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.Edit.]]</a><?php }?>                
				<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0)" id="delete_button" class="w3-btn w3-red w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.delete.]]</a><?php }?>
            </td>
        </tr>
    </table>
    
	<div class="content">
    	<div class="search-box">
            <a name="top_anchor"></a>
        	<fieldset>
            	<legend class="title">[[.select.]]</legend>
                <?php 
                    if(User::can_admin(false,ANY_CATEGORY))
                    {
                ?>
                <span>[[.portal.]]:</span> 
        		<select name="portal_id" id="portal_id" onchange="ListProductPriceForm.act.value='search_portal';ListProductPriceForm.submit();" style="height: 24px;"></select>
                <?php
                    }
                ?>

        		<span>[[.department.]]:</span> 
        		<select  name="department_code" id="department_code" onchange="ListProductPriceForm.act.value='search_department';ListProductPriceForm.submit();" style="height: 24px;">[[|department_list|]]</select>
                <script>$('department_code').value = "<?php echo Url::get('department_code');?>";</script>
            </fieldset>
        	
            <fieldset>
            	<legend class="title">[[.search.]]</legend>
                
                <table border="0" cellpadding="3" cellspacing="0">
						<tr>
    						<td align="right" nowrap style="font-weight:bold">[[.code.]]</td>
    						<td>:</td>
    						<td nowrap>
    							<input name="product_id" type="text" id="product_id" style="width:50px;height: 24px;"/>
    						</td><td align="right" nowrap style="font-weight:bold">[[.name.]]</td>
    						<td>:</td>
    						<td nowrap>
    							<input name="product_name" type="text" id="product_name" style="width:100px; height: 24px;"/>
    						</td>
    						<td align="right" nowrap style="font-weight:bold">[[.category_id.]]</td>
    						<td>:</td>
    						<td nowrap>
    							<select name="category_id" id="category_id" style="width:150px;height: 24px;"></select>
    						</td>
    						<td align="right" nowrap style="font-weight:bold">[[.type.]]</td>
    						<td>:</td>
    						<td nowrap>
    							<select  name="type" id="type" style="width:80px;height: 24px;">
    								<option value="">[[.all.]]</option><option value="GOODS">[[.goods.]]</option><option value="PRODUCT">[[.product.]]</option><option value="DRINK">[[.drink.]]</option><option value="MATERIAL">[[.material.]]</option><option value="EQUIPMENT">[[.equipment.]]</option><option value="SERVICE">[[.service.]]</option><option value="TOOL">[[.tool.]]</option>
    							</select>
    							<script>
    							$('type').value='<?php echo URL::get('type');?>';
    							</script>
    						</td>
    						</td><td align="right" nowrap style="font-weight:bold">[[.start_date.]]</td>
    						<td>:</td>
    						<td nowrap>
    							<input name="start_date" type="text" id="start_date" onchange="changevalue();" style="width:70px;height: 24px;"/>
    						</td>
    						</td><td align="right" nowrap style="font-weight:bold">[[.end_date.]]</td>
    						<td>:</td>
    						<td nowrap>
    							<input name="end_date" type="text" id="end_date" onchange="changefromday();" style="width:70px;height: 24px;"/>
    						</td>
    						<td><input name="search" type="submit" value="  [[.search.]]  " style="height: 24px;"/></td>
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
                <th width="30">[[.order_number.]]</th>
                <th width="150" align="center">[[.product_code.]]</th>
                <th width="300" align="center">[[.product.]]</th>
                <th width="100" align="center">[[.unit.]]</th>
                <th width="150" align="center">[[.type.]]</th>
                <th width="100" align="center">[[.price.]]</th>
                <th width="150" align="center">[[.category.]]</th> 
                <th width="150" align="right">[[.start_date.]]</th>
                <th width="150" align="right">[[.end_date.]]</th>                             
                <th width="50">&nbsp;</th>
                <th width="50">&nbsp;</th>
            </tr>
            <?php $department = '';?>
            <!--LIST:items-->
            <?php if($department != [[=items.department_code=]]){ $department = [[=items.department_code=]];?>
            <tr>
                <td colspan="10" class="category-group">
                [[|items.department_name|]]
                <input type="button" value="[[.copy.]]" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'copy','type','portal_id'=>[[=portal_id=]],'department_code'=>[[=items.department_code=]]));?>'"/>
                </td>
                <td colspan="1" style="text-align: right;">
                    <a href="<?php echo Url::build_current(array('cmd'=>'edit','department_code'=>[[=items.department_code=]],'portal_id'=>[[=portal_id=]],'product_id','product_name','category_id','type'));?>">
                        <img src="packages/core/skins/default/images/buttons/edit.gif" />
                    </a>
                </td>
            </tr>  
            <?php }?>
            <tr>  
                <td width="1%">
                    <input name="selected_ids[]" type="checkbox" class="item-check-box" value="[[|items.id|]]"/>
                </td>
                <td style="cursor:pointer;">[[|items.i|]]</td>
                <td style="cursor:pointer;">[[|items.product_id|]]</td>
                <td style="cursor:pointer;">[[|items.product_name|]]</td>
                <td style="cursor:pointer;">[[|items.unit|]]</td>  
                <td style="cursor:pointer;">[[|items.type|]]</td>                 
                <td style="cursor:pointer;" align="right">[[|items.price|]]</td>
                <td style="cursor:pointer;">[[|items.category_name|]]</td>
                <td style="cursor:pointer;color:#090;" align="right">[[|items.start_date|]]</td>
                <td style="cursor:pointer;color:#F00;" align="right">[[|items.end_date|]]</td>
                <td align="right">
                    <a target="_blank" href="<?php echo Url::build_current(array('cmd'=>'edit','department_code'=>[[=items.department_code=]],'id'=>[[=items.id=]],'portal_id'=>[[=portal_id=]],'product_id','product_name','category_id','type'));?>">
                        <img src="packages/core/skins/default/images/buttons/edit.gif" />
                    </a>
                </td>
        		<td align="right">
                    <a href="" onclick="if(!confirm('[[.are_you_sure.]]')){return false;}else{ return check_delete('<?php echo [[=items.id=]];?>')}">
                        <img src="packages/core/skins/default/images/buttons/delete.gif" alt="X�a"/>
                    </a>
                </td>
                
            </tr>
            <!--/LIST:items-->			
		</table>
        <div class="paging">[[|paging|]]</div>
        <table width="100%">
            <tr>
    			<td width="100%">
    				[[.select.]]:&nbsp;
    				<a href="javascript:void(0)" onclick="select_all_checkbox(document.ListProductPriceForm,'ManagePortal',true,'#FFFFEC','white');">[[.select_all.]]</a>&nbsp;
    				<a href="javascript:void(0)" onclick="select_all_checkbox(document.ListProductPriceForm,'ManagePortal',false,'#FFFFEC','white');">[[.select_none.]]</a>
    				<a href="javascript:void(0)" onclick="select_all_checkbox(document.ListProductPriceForm,'ManagePortal',-1,'#FFFFEC','white');">[[.select_invert.]]</a>
    			</td>
    			<td>
    				<a name="bottom_anchor" href="#top_anchor"><img src="<?php echo Portal::template('core');?>/images/top.gif" title="[[.top.]]" border="0" alt="[[.top.]]"/></a>
    			</td>
			</tr>
        </table>
        <br />
        <br />
        <br />
	</div>
	<input name="act" type="hidden" value=""/>
    <input name="cmd" type="hidden" value=""/>
</form>	
</div>

<script type="text/javascript" src="packages/core/includes/js/jquery/jquery.maskedinput.js"></script>
<script type="text/javascript">
	//jQuery("#create_date").mask("99/99/9999");
    jQuery("#start_date").datepicker();
    jQuery("#end_date").datepicker();
	jQuery("#delete_button").click(function(){
        if(confirm('[[.are_you_sure.]]'))
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
		if(!confirm('[[.are_you_sure.]]'))
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