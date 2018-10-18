<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('list_department'));?>
<link href="<?php echo Portal::template('core');?>/css/category.css" rel="stylesheet" type="text/css" />
<div class="form_bound">
<form name="ListManageDepartmentForm" method="post">
    <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr>
            <td class="" width="60%" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;">[[.list_department.]]</td>
            <?php if(User::can_add(false,ANY_CATEGORY)){?>
            <td style="width: 40%; text-align: right; padding-right: 30px;">
                <a class="w3-btn w3-cyan w3-text-white" href="<?php echo URL::build_current(array()+array('cmd'=>'add'));?>" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.Add.]]</a>
            
            <?php }
            if(User::can_delete(false,ANY_CATEGORY)){
            ?>
            
                <a class="w3-btn w3-red" href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};ListManageDepartmentForm.submit();" style="text-transform: uppercase; text-decoration: none; ">[[.Delete.]]</a>
            </td>
            <?php }?>
        </tr>
    </table>
    <div class="form_content">
	<table cellspacing="0" width="100%">
	<tr bgcolor="#EFEFEF" valign="top">
		<td width="100%">
			<table bgcolor="#EFEFEF" cellspacing="0" width="100%">
    			<tr>
    				<td width="100%">
    					<div style="border:2px solid #FFFFFF;">
                            <a name="top_anchor"></a>
                            <table width="99%" cellpadding="2" cellspacing="0" border="1" style="border-collapse:collapse" bordercolor="#CCCCCC">
                                <tr valign="middle" bgcolor="#EFEFEF" style="line-height:20px">
                            		<th width="2%" title="[[.check_all.]]">
                                        <input type="checkbox" value="1" id="ManagePortal_all_checkbox" onclick="select_all_checkbox(this.form, 'ManagePortal',this.checked,'#FFFFEC','white');"<?php if(URL::get('cmd')=='delete') echo ' checked';?>/>
                                    </th>
                                    <th width="14%" align="left" nowrap >[[.code.]]</th>
                                    <!--LIST:languages-->
                            		<th width="13%" align="left" nowrap >[[.name.]]([[|languages.code|]])</th>
                            		<!--/LIST:languages-->
									<?php if(User::is_admin()){?>
                                    <td nowrap align="left" width="1%" nowrap="nowrap">[[.account_revenue_code.]]</td>
                                    <td nowrap align="left" width="1%" nowrap="nowrap">[[.account_deposit_code.]]</td>
                                    <?php }?>                                    
                                    <?php if(User::can_edit(false,ANY_CATEGORY)){?>
                            		<th width="1%" align="center" nowrap>&nbsp;</th>
                            		<?php }?>
                                    <?php if(User::can_delete(false,ANY_CATEGORY)){?>
                            		<th width="1%" align="center" nowrap>&nbsp;</th>
                            		<?php }?>
                            	</tr>
                            	<!--LIST:department-->
                            	<tr bgcolor="white" valign="middle" <?php Draw::hover('#E2F1DF');?> style="cursor:pointer;background-color: #FF9; border-top: 1px solid #FC0; border-bottom: 1px solid #FC0;" >
                            		<td>
                                        <input name="selected_ids[]" type="checkbox" value="[[|department.id|]]" onclick="select_checkbox(this.form,'ManageDepartment',this,'#FFFFEC','white');" id="ManageDepartment_checkbox" <?php if(URL::get('cmd')=='delete') echo 'checked';?>/>
                                        <input name="id" type="hidden" id="id"/>
                                    </td>
                                    <td class="category-group" <?php Draw::hover('#E2F1DF');?> nowrap align="left" onclick="location='<?php echo URL::build_current(array('cmd'=>'edit','id'=>[[=department.id=]]));?>';">[[|department.code|]]</td>
                                    <td nowrap align="left">[[|department.name_1|]]</td>
                                    <td nowrap align="left">[[|department.name_2|]]</td>
                                    <?php if(User::is_admin()){?>
                                    <td nowrap align="left">[[|department.acc_revenue_code|]]</td>
                                    <td nowrap align="left">[[|department.acc_deposit_code|]]</td>
                                    <?php }?>
                                    <?php if(User::can_edit(false,ANY_CATEGORY)){?>
                            		<td align="left" nowrap>
                                        <a href="<?php echo URL::build_current(array('cmd'=>'edit','id'=>[[=department.id=]]));?>">
                                            <img src="packages/core/skins/default/images/buttons/edit.gif" alt="S?a"/>
                                        </a>
                                    </td>
                            		<?php }?>
                                    <?php if(User::can_delete(false,ANY_CATEGORY)){?>
                            		<td align="left" nowrap>
                                        <a onclick="check_department([[|department.id|]],'[[|department.code|]]');">
                                            <img src="packages/core/skins/default/images/buttons/delete.gif" alt="X�a"/>
                                        </a>
                                    </td>
                            		<?php }?>
                            	</tr>
                            	<!--LIST:department.child-->
                            	<tr bgcolor="white" valign="middle" <?php Draw::hover('#E2F1DF');?> style="cursor:pointer;">
                            		<td>
                                        <input name="selected_ids[]" type="checkbox" value="[[|department.child.id|]]" onclick="select_checkbox(this.form,'ManageDepartment',this,'#FFFFEC','white');" id="ManageDepartment_checkbox" <?php if(URL::get('cmd')=='delete') echo 'checked';?>/>
                                        <input name="id" type="hidden" id="id"/>
                                    </td>
                                    <td nowrap align="left" onclick="location='<?php echo URL::build_current(array('cmd'=>'edit','id'=>[[=department.child.id=]]));?>';" style="text-indent: 40px;">[[|department.child.code|]]</td>
                                    <td nowrap align="left" style="text-indent: 40px;">[[|department.child.name_1|]]</td>
                                    <td nowrap align="left" style="text-indent: 40px;">[[|department.child.name_2|]]</td>
                                    <?php if(User::is_admin()){?>
                                    <td nowrap align="left">[[|department.acc_revenue_code|]]</td>
                                    <td nowrap align="left">[[|department.acc_deposit_code|]]</td>
                                    <?php }?>                                    
                                    <?php if(User::can_edit(false,ANY_CATEGORY)){?>
                            		<td align="left" nowrap>
                                        <a href="<?php echo URL::build_current(array('cmd'=>'edit','id'=>[[=department.child.id=]]));?>">
                                            <img src="packages/core/skins/default/images/buttons/edit.gif" alt="S?a"/>
                                        </a>
                                    </td>
                            		<?php }?>
                                    <?php if(User::can_delete(false,ANY_CATEGORY)){?>
                            		<td align="left" nowrap>
                                        <a onclick="check_department([[|department.child.id|]],'[[|department.child.code|]]');" >
                                            <img src="packages/core/skins/default/images/buttons/delete.gif" alt="X�a"/>
                                        </a>
                                    </td>
                            		<?php }?>
                            	</tr>
                            	<!--/LIST:department.child-->
                            	<!--/LIST:department-->
                            </table>
    				    </div>
    				</td>
    			</tr>
			</table>
            <table width="100%">
                <tr>
        			<td width="100%">
        				[[.select.]]:&nbsp;
        				<a href="javascript:void(0)" onClick="select_all_checkbox(document.ListManageDepartmentForm,'ManagePortal',true,'#FFFFEC','white');">[[.select_all.]]</a>&nbsp;
        				<a href="javascript:void(0)" onClick="select_all_checkbox(document.ListManageDepartmentForm,'ManagePortal',false,'#FFFFEC','white');">[[.select_none.]]</a>
        				<a href="javascript:void(0)" onClick="select_all_checkbox(document.ListManageDepartmentForm,'ManagePortal',-1,'#FFFFEC','white');">[[.select_invert.]]</a>
        			</td>
        			<td>
        				<a name="bottom_anchor" href="#top_anchor"><img src="<?php echo Portal::template('core');?>/images/top.gif" title="[[.top.]]" border="0" alt="[[.top.]]"/></a>
        			</td>
    			</tr>
            </table>
		</td>
	</tr>
	</table>
	</div>
</form>
</div>
<script>
function check_department(id,code)
{
    console.log(code);
    if(code=='SPA'||code=='GOLF'||code=='SALES'||code=='HK'||code=='AMENITIES'||code=='MINIBAR'||code=='LAUNDRY'||code=='RES'||code=='REC'||code=='BANQUET'||code=='VENDING'||code=='KARAOKE'||code=='WH'||code=='TICKET')
    {
        alert('[[.khong_the_xoa_cac_ma_mac_dinh.]]');
        return false;
    }
    else
    {
        var cf = confirm('[[.are_you_sure.]]');
        if(cf==true)
            window.location='?page=manage_department&cmd=delete&id='+id;
        else
            return false;
    }
}
function checkbox_department(code)
{
    console.log(code);
}
</script>