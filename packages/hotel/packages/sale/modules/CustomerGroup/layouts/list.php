<?php 
$title = Portal::language('customer_group_list');
$action = (URL::get('cmd')=='edit')?'edit':'add';
?>
<div id="title_region"></div>
<div class="form_bound">
		<table cellpadding="0" cellspacing="0" width="100%" class="table-bound">
			<tr height="40">
				<td width="60%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-book w3-text-orange" style="font-size: 26px;"></i> [[.customer_group_list.]]</td>
				<td align="right" nowrap="nowrap" style="padding-right: 30px;">
					<?php if(User::can_add(false,ANY_CATEGORY)){?><a href="<?php echo URL::build_current(array('cmd'=>'add','action'));?>"  class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.Add.]]</a><?php }?>
					<?php if(User::can_delete(false,ANY_CATEGORY)){?><a href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};ListCustomerGroupForm.cmd.value='delete';ListCustomerGroupForm.submit();"  class="w3-btn w3-red" style="text-transform: uppercase; text-decoration: none;">[[.Delete.]]</a><?php }?>
				</td>
			</tr>
		</table>      
		<fieldset>
		<legend class="title">[[.search.]]</legend>
		<form method="post" name="SearchCustomerGroupForm">
			[[.name.]]:
			<input name="name" type="text" id="name" style="width:100px; height: 24px;" />&nbsp;
			<input type="hidden" name="page_no" value="1"/>
			<input class="w3-btn w3-gray" type="submit" value="[[.search.]]" style="height: 26px; padding-top: 5px;"/>
		</form>
		</fieldset><br />
		<div class="content">
		<!--IF:cond(URL::get('selected_ids'))--><div class="notice">[[.selected_list_to_delete.]]</div><br /><!--/IF:cond-->
		<form name="ListCustomerGroupForm" method="post">
		<table width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#C6E2FF">
			<thead>
			<tr class="w3-light-gray w3-border" style="height: 24px; text-transform: uppercase;">
				<td width="1%" title="[[.check_all.]]">
					<input type="checkbox" value="1" id="CustomerGroup_all_checkbox" onclick="select_all_checkbox(this.form,'CustomerGroup',this.checked,'#FFFFEC','white');"<?php if(URL::get('cmd')=='delete') echo ' checked';?>></td />
				<td nowrap align="left" style="height: 24px;">
					<a href="<?php echo URL::build_current(((URL::get('order_by')=='customer_group.name' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'customer_group.name'));?>" title="[[.sort.]]">
					<?php if(URL::get('order_by')=='customer_group.name') echo '<img alt="" src="skins/default/images/icon/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
					[[.name.]]
					</a>
				</td>
				<td>&nbsp;</td>
				<?php if(User::can_edit(false,ANY_CATEGORY))
				{?>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<?php }?>
			</tr>
			</thead>
			<tbody>
			<?php $i=1;?>
            <?php //System::debug([[=items=]]); ?>
            <!--LIST:items-->
       		 <tr <?php if($i%2==0){echo 'class="row-even"';}else{echo 'class="row-odd"';}$i++;?>>
				<td>
                    
                    <!--IF:cond([[=items.structure_id=]]!=ID_ROOT)-->
                    <?php if(User::can_admin(false,ANY_CATEGORY)){ ?>
                    <input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="select_checkbox(this.form,'CustomerGroup',this,'#FFFFEC','white');" id="CustomerGroup_checkbox" <?php if(URL::get('cmd')=='delete') echo 'checked';?> />
                    <?php } ?>
                    <!--/IF:cond-->
                </td>
				<td nowrap align="left" style="height: 24px;">[[|items.indent|]] <span style="margin-bottom: 10px !important;">[[|items.indent_image|]]</span>&nbsp;[[|items.name|]]</td>
                
				<td width="24px" align="center">
                    <?php if(User::can_admin(false,ANY_CATEGORY)){ ?>
                    <a href="#" onclick="location='<?php echo URL::build_current();?>&cmd=edit&id=[[|items.id|]]';">
                        <i class="fa fa-pencil w3-text-orange" style="font-size: 18px;"></i>
                    </a>
                    <?php } ?>
                </td>
				<td width="24px" align="center">[[|items.move_up|]]</td>
				<td width="24px" align="center">[[|items.move_down|]]</td>
			</tr>
			<!--/LIST:items-->
			</tbody>
		</table>
		<input type="hidden" name="cmd" value="delete"/>
		<!--IF:delete(URL::get('cmd')=='delete')-->
		<input type="hidden" name="confirm" value="1" />
		<!--/IF:delete-->
		</form>
		</div>
</div>

<script>
<?php if(Url::get('status')&&Url::get('status')==1){
    //echo 'alert(\'Nhóm nguồn khách đã có nguồn khách.không thể xóa !\')';
} ?>
</script>