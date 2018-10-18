<form name="RevenExpenListForm" enctype="multipart/form-data" method="post">
    <table width="100%" cellspacing="0" cellpadding="0">
    	<tr valign="top">
    		<td align="left">
                <table cellpadding="0" cellspacing="0" width="100%" border="0" class="table-bound">
                    <tr height="40">
                        <td class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;">[[.add_product_for_department.]]</td>
                        <?php if(User::can_add(false,ANY_CATEGORY)){?><td width="40%" style="text-align: right;"><input type="button" class="w3-btn w3-lime" value="[[.import_from_excel.]]" onclick="window.location='<?php echo Url::build_current(array('cmd'=>'import'));?>'" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;" /><?php }?>
    					<?php if(User::can_add(false,ANY_CATEGORY)){?><a href="<?php echo URL::build_current(array('cmd'=>'edit'));?>"  class="w3-cyan w3-btn w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.add.]]</a><?php }?>
       					<?php if(User::can_edit(false,ANY_CATEGORY)){?><input type="button" value="[[.edit.]]" onclick="check_edit();"  class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"/><?php }?>
                        <?php if(User::can_delete(false,ANY_CATEGORY)){?><input type="button" value="[[.delete.]]" onclick="check_delete();"  class="w3-btn w3-red" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"/></td><?php }?>
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
                                <legend class="title">[[.search.]]</legend>
                                <table border="0" cellpadding="3" cellspacing="0" width="100%" >
            						<tr width="100%" >
                                        <td style="margin-left: 10px;">
                    						Bộ phận:&nbsp;
                                            <select  name="department_id" id="department_id" style="width:100px; height: 24px;">
                                                <option value="">[[.all.]]</option>[[|department_options|]]
                                            </select>&nbsp;&nbsp;
                                            <script type="text/javascript">
          							               jQuery('#department_id').val('<?php echo URL::get('department_id');?>');
                							</script>
                                            [[.product_code.]]
                                            <input name="product_code" type="text" id="product_code" style=" height: 24px;" />
                                            [[.product_name.]]
                                            <input name="product_name" type="text" id="product_name"style=" height: 24px;" />
                                            
                                            [[.category_id.]] :
                    							<select name="category_id" id="category_id" style="width:150px; height: 24px;"></select>
                    						[[.type.]] :
                    						<select  name="product_type" id="product_type" style="width:100px; height: 24px;">
                								<option value="">[[.all.]]</option><option value="GOODS">[[.goods.]]</option><option value="PRODUCT">[[.product.]]</option><option value="DRINK">[[.drink.]]</option><option value="MATERIAL">[[.material.]]</option><option value="EQUIPMENT">[[.equipment.]]</option><option value="SERVICE">[[.service.]]</option><option value="TOOL">[[.tool.]]</option>
                							</select>
                                            <script type="text/javascript">
                                                 jQuery('#product_type').val('<?php echo URL::get('product_type');?>');
                							</script>               							
                    						<input name="search" type="submit" value="  [[.search.]] " style=" height: 24px;  "/>
                                        </td>
            						</tr>
    					       </table>
                           </fieldset>
                           <br />
                           <table width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#C6E2FF">
        						<tr class="w3-light-gray" style="text-transform: uppercase; height: 30px;">
                                    <th style="text-align: center;" width="22px" title="[[.check_all.]]">
                                        <input type="checkbox" value="" id="Product_check" onclick="checkall(this.checked);"/>
                                    </th>
                                    <th nowrap align="center" width="30px" >[[.stt.]]</th>
                                    <th nowrap align="center" width="60px" >[[.product_code.]]</th>
                                    
                                    <th nowrap align="center" width="300px" >[[.product_name.]]</th>
                                    
                                    <th style="text-align: center;" nowrap align="center" width="80px" >[[.unit.]]</th>
                                    <th style="text-align: center;" nowrap align="center" width="80px" >[[.category.]]</th>
                                    <th style="text-align: center;" nowrap align="center" width="80px" >[[.type.]]</th>
                                    <th style="text-align: center;" nowrap align="center" width="100px" >[[.department.]]</th>
                                    <?php if(User::can_edit(false,ANY_CATEGORY)){?>	
                                    <th style="text-align: center;" width="5%">[[.edit.]]</th>
                                    <?php }
                                    
                                    if(User::can_delete(false,ANY_CATEGORY)){?>
                                    <th style="text-align: center;" width="5%">[[.delete.]]</th>
                                    <?php }?>
                                </tr>
                                
                                <!--LIST:items-->
                                    <tr>
                                        <td style="text-align: center; height: 24px;" ><input type="checkbox" value="1" <?php echo 'id="Product_check_'.[[=items.id=]].'"';?> <?php echo 'name="Product_check_'.[[=items.id=]].'"';?> onclick="get_ids();" /></td>
                                        <td style="text-align: center;" >[[|items.index|]]</td>
                                        <td style="text-align: left;" >[[|items.code|]]</td>
                                        <td style="text-align: left;" >[[|items.name|]]</td>
                                        <td style="text-align: center;" >[[|items.unit|]]</td>
                                        <td style="text-align: center;" >[[|items.category_id|]]</td>
                                        <td style="text-align: center;" >[[|items.type|]]</td>
                                        <td style="text-align: center;" >[[|items.department|]]</td>
                                        <td style="text-align: center;" ><a href="<?php echo Url::build_current(array()+array('cmd'=>'edit','ids'=>[[=items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/edit.gif" alt="[[.edit.]]"/></a></td>
                                        <td style="text-align: center;" ><a href="<?php echo Url::build_current(array('cmd'=>'delete','ids'=>[[=items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/delete.gif" alt="[[.delete.]]"/></a></td>
                                    </tr>
                                <!--/LIST:items-->
    				        </table>
                        </td>
                    </tr>
                </table>
            </td>
   		</tr>
    </table>
    <div class="paging">[[|paging|]]</div>
    <input name="cmd" type="hidden" value="" />
    <input name="ids" id="ids" type="hidden" value="" />
    <input name="type" id="type" type="hidden" value="[[|type|]]" />
</form>
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