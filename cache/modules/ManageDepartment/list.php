<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('list_department'));?>
<link href="<?php echo Portal::template('core');?>/css/category.css" rel="stylesheet" type="text/css" />
<div class="form_bound">
<form name="ListManageDepartmentForm" method="post">
    <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr>
            <td class="" width="60%" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><?php echo Portal::language('list_department');?></td>
            <?php if(User::can_add(false,ANY_CATEGORY)){?>
            <td style="width: 40%; text-align: right; padding-right: 30px;">
                <a class="w3-btn w3-cyan w3-text-white" href="<?php echo URL::build_current(array()+array('cmd'=>'add'));?>" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('Add');?></a>
            
            <?php }
            if(User::can_delete(false,ANY_CATEGORY)){
            ?>
            
                <a class="w3-btn w3-red" href="javascript:void(0)" onclick="if(!confirm('<?php echo Portal::language('are_you_sure');?>')){return false};ListManageDepartmentForm.submit();" style="text-transform: uppercase; text-decoration: none; "><?php echo Portal::language('Delete');?></a>
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
                            		<th width="2%" title="<?php echo Portal::language('check_all');?>">
                                        <input type="checkbox" value="1" id="ManagePortal_all_checkbox" onclick="select_all_checkbox(this.form, 'ManagePortal',this.checked,'#FFFFEC','white');"<?php if(URL::get('cmd')=='delete') echo ' checked';?>/>
                                    </th>
                                    <th width="14%" align="left" nowrap ><?php echo Portal::language('code');?></th>
                                    <?php if(isset($this->map['languages']) and is_array($this->map['languages'])){ foreach($this->map['languages'] as $key1=>&$item1){if($key1!='current'){$this->map['languages']['current'] = &$item1;?>
                            		<th width="13%" align="left" nowrap ><?php echo Portal::language('name');?>(<?php echo $this->map['languages']['current']['code'];?>)</th>
                            		<?php }}unset($this->map['languages']['current']);} ?>
									<?php if(User::is_admin()){?>
                                    <td nowrap align="left" width="1%" nowrap="nowrap"><?php echo Portal::language('account_revenue_code');?></td>
                                    <td nowrap align="left" width="1%" nowrap="nowrap"><?php echo Portal::language('account_deposit_code');?></td>
                                    <?php }?>                                    
                                    <?php if(User::can_edit(false,ANY_CATEGORY)){?>
                            		<th width="1%" align="center" nowrap>&nbsp;</th>
                            		<?php }?>
                                    <?php if(User::can_delete(false,ANY_CATEGORY)){?>
                            		<th width="1%" align="center" nowrap>&nbsp;</th>
                            		<?php }?>
                            	</tr>
                            	<?php if(isset($this->map['department']) and is_array($this->map['department'])){ foreach($this->map['department'] as $key2=>&$item2){if($key2!='current'){$this->map['department']['current'] = &$item2;?>
                            	<tr bgcolor="white" valign="middle" <?php Draw::hover('#E2F1DF');?> style="cursor:pointer;background-color: #FF9; border-top: 1px solid #FC0; border-bottom: 1px solid #FC0;" >
                            		<td>
                                        <input name="selected_ids[]" type="checkbox" value="<?php echo $this->map['department']['current']['id'];?>" onclick="select_checkbox(this.form,'ManageDepartment',this,'#FFFFEC','white');" id="ManageDepartment_checkbox" <?php if(URL::get('cmd')=='delete') echo 'checked';?>/>
                                        <input  name="id" id="id"/ type ="hidden" value="<?php echo String::html_normalize(URL::get('id'));?>">
                                    </td>
                                    <td class="category-group" <?php Draw::hover('#E2F1DF');?> nowrap align="left" onclick="location='<?php echo URL::build_current(array('cmd'=>'edit','id'=>$this->map['department']['current']['id']));?>';"><?php echo $this->map['department']['current']['code'];?></td>
                                    <td nowrap align="left"><?php echo $this->map['department']['current']['name_1'];?></td>
                                    <td nowrap align="left"><?php echo $this->map['department']['current']['name_2'];?></td>
                                    <?php if(User::is_admin()){?>
                                    <td nowrap align="left"><?php echo $this->map['department']['current']['acc_revenue_code'];?></td>
                                    <td nowrap align="left"><?php echo $this->map['department']['current']['acc_deposit_code'];?></td>
                                    <?php }?>
                                    <?php if(User::can_edit(false,ANY_CATEGORY)){?>
                            		<td align="left" nowrap>
                                        <a href="<?php echo URL::build_current(array('cmd'=>'edit','id'=>$this->map['department']['current']['id']));?>">
                                            <img src="packages/core/skins/default/images/buttons/edit.gif" alt="S?a"/>
                                        </a>
                                    </td>
                            		<?php }?>
                                    <?php if(User::can_delete(false,ANY_CATEGORY)){?>
                            		<td align="left" nowrap>
                                        <a onclick="check_department(<?php echo $this->map['department']['current']['id'];?>,'<?php echo $this->map['department']['current']['code'];?>');">
                                            <img src="packages/core/skins/default/images/buttons/delete.gif" alt="X�a"/>
                                        </a>
                                    </td>
                            		<?php }?>
                            	</tr>
                            	<?php if(isset($this->map['department']['current']['child']) and is_array($this->map['department']['current']['child'])){ foreach($this->map['department']['current']['child'] as $key3=>&$item3){if($key3!='current'){$this->map['department']['current']['child']['current'] = &$item3;?>
                            	<tr bgcolor="white" valign="middle" <?php Draw::hover('#E2F1DF');?> style="cursor:pointer;">
                            		<td>
                                        <input name="selected_ids[]" type="checkbox" value="<?php echo $this->map['department']['current']['child']['current']['id'];?>" onclick="select_checkbox(this.form,'ManageDepartment',this,'#FFFFEC','white');" id="ManageDepartment_checkbox" <?php if(URL::get('cmd')=='delete') echo 'checked';?>/>
                                        <input  name="id" id="id"/ type ="hidden" value="<?php echo String::html_normalize(URL::get('id'));?>">
                                    </td>
                                    <td nowrap align="left" onclick="location='<?php echo URL::build_current(array('cmd'=>'edit','id'=>$this->map['department']['current']['child']['current']['id']));?>';" style="text-indent: 40px;"><?php echo $this->map['department']['current']['child']['current']['code'];?></td>
                                    <td nowrap align="left" style="text-indent: 40px;"><?php echo $this->map['department']['current']['child']['current']['name_1'];?></td>
                                    <td nowrap align="left" style="text-indent: 40px;"><?php echo $this->map['department']['current']['child']['current']['name_2'];?></td>
                                    <?php if(User::is_admin()){?>
                                    <td nowrap align="left"><?php echo $this->map['department']['current']['acc_revenue_code'];?></td>
                                    <td nowrap align="left"><?php echo $this->map['department']['current']['acc_deposit_code'];?></td>
                                    <?php }?>                                    
                                    <?php if(User::can_edit(false,ANY_CATEGORY)){?>
                            		<td align="left" nowrap>
                                        <a href="<?php echo URL::build_current(array('cmd'=>'edit','id'=>$this->map['department']['current']['child']['current']['id']));?>">
                                            <img src="packages/core/skins/default/images/buttons/edit.gif" alt="S?a"/>
                                        </a>
                                    </td>
                            		<?php }?>
                                    <?php if(User::can_delete(false,ANY_CATEGORY)){?>
                            		<td align="left" nowrap>
                                        <a onclick="check_department(<?php echo $this->map['department']['current']['child']['current']['id'];?>,'<?php echo $this->map['department']['current']['child']['current']['code'];?>');" >
                                            <img src="packages/core/skins/default/images/buttons/delete.gif" alt="X�a"/>
                                        </a>
                                    </td>
                            		<?php }?>
                            	</tr>
                            	<?php }}unset($this->map['department']['current']['child']['current']);} ?>
                            	<?php }}unset($this->map['department']['current']);} ?>
                            </table>
    				    </div>
    				</td>
    			</tr>
			</table>
            <table width="100%">
                <tr>
        			<td width="100%">
        				<?php echo Portal::language('select');?>:&nbsp;
        				<a href="javascript:void(0)" onClick="select_all_checkbox(document.ListManageDepartmentForm,'ManagePortal',true,'#FFFFEC','white');"><?php echo Portal::language('select_all');?></a>&nbsp;
        				<a href="javascript:void(0)" onClick="select_all_checkbox(document.ListManageDepartmentForm,'ManagePortal',false,'#FFFFEC','white');"><?php echo Portal::language('select_none');?></a>
        				<a href="javascript:void(0)" onClick="select_all_checkbox(document.ListManageDepartmentForm,'ManagePortal',-1,'#FFFFEC','white');"><?php echo Portal::language('select_invert');?></a>
        			</td>
        			<td>
        				<a name="bottom_anchor" href="#top_anchor"><img src="<?php echo Portal::template('core');?>/images/top.gif" title="<?php echo Portal::language('top');?>" border="0" alt="<?php echo Portal::language('top');?>"/></a>
        			</td>
    			</tr>
            </table>
		</td>
	</tr>
	</table>
	</div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
</div>
<script>
function check_department(id,code)
{
    console.log(code);
    if(code=='SPA'||code=='GOLF'||code=='SALES'||code=='HK'||code=='AMENITIES'||code=='MINIBAR'||code=='LAUNDRY'||code=='RES'||code=='REC'||code=='BANQUET'||code=='VENDING'||code=='KARAOKE'||code=='WH'||code=='TICKET')
    {
        alert('<?php echo Portal::language('khong_the_xoa_cac_ma_mac_dinh');?>');
        return false;
    }
    else
    {
        var cf = confirm('<?php echo Portal::language('are_you_sure');?>');
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