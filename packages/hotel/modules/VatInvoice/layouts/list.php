<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('list_vat_invoice'));?>
<link href="<?php echo Portal::template('core');?>/css/category.css" rel="stylesheet" type="text/css" />
<div class="form_bound">
<form name="ListVatInvoiceForm" method="post">    
    <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr>
            <td class="form-title" width="100%">[[.list_vat_invoice.]] : <?php echo Url::get('department'); ?></td>
            <?php if(User::can_add(false,ANY_CATEGORY)){?>
            <td>
                <!--<a class="button-medium-add" href="<?php echo URL::build_current(array()+array('cmd'=>'add'));?>">[[.Add.]]</a>-->
            </td>
            <?php }
            if(User::can_delete(false,ANY_CATEGORY)){
            ?>
            <td>
                <a class="button-medium-delete" href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};ListVatInvoiceForm.submit();">[[.Delete.]]</a>
            </td>
            <?php }?>
        </tr>
    </table>
    <div class="form_content">
	<table cellspacing="0" width="100%">
	<tr bgcolor="#EFEFEF" valign="top">
		<td width="100%">
			<div style="border:2px solid #FFFFFF;">
                <a name="top_anchor"></a>
                <table width="99%" cellpadding="2" cellspacing="0" border="1" style="border-collapse:collapse" bordercolor="#CCCCCC">
                	
                    <tr valign="middle" bgcolor="#EFEFEF" style="line-height:20px">
                		<th width="2%" title="[[.check_all.]]">
                            <input type="checkbox" value="1" id="ManageProvince_all_checkbox" onclick="select_all_checkbox(this.form, 'ManageProvince',this.checked,'#FFFFEC','white');"<?php if(URL::get('cmd')=='delete') echo ' checked';?>/>
                        </th>
                        <th width="10px" align="left">[[.stt.]]</th>
                        <th width="20px" align="left">[[.type.]]</th>
                        <th width="20px" align="left">[[.reservation_id.]]</th>
                        <th width="50px" align="left">[[.room_name.]]</th>
                		<th width="150px" align="left">[[.customer_name.]]</th>
                        <!--<th width="100px" align="left">[[.arrival_time.]]</th>
                        <th width="100px" align="left">[[.departure_time.]]</th>-->
                        <th width="100px" align="left">[[.print_user.]]</th>
                        <th width="100px" align="left">[[.print_time.]]</th> 
                        <th width="100px" align="left">[[.last_print_user.]]</th> 
                        <th width="100px" align="left">[[.last_print_time.]]</th>
                        <?php if(User::can_edit(false,ANY_CATEGORY)){?>
                		<th width="1%" align="center" >&nbsp;</th>
                		<?php }?>
                        <?php if(User::can_delete(false,ANY_CATEGORY)){?>
                		<th width="1%" align="center" >&nbsp;</th>
                		<?php }?>
                	</tr>
                	<!--LIST:items-->
                	<tr bgcolor="white" valign="middle" <?php Draw::hover('#E2F1DF');?> style="cursor:pointer;border-top: 1px solid #FC0; border-bottom: 1px solid #FC0;" >
                		<td>
                            <input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="select_checkbox(this.form,'ManageProvince',this,'#FFFFEC','white');" id="ManageProvince_checkbox" <?php if(URL::get('cmd')=='delete') echo 'checked';?>/>
                            <input name="id" type="hidden" id="id"/>
                        </td>
                        <td align="left">[[|items.code|]]</td>
                        <td align="left">[[|items.type|]]</td>
                        <td align="left">
                            <!--IF:cond(isset([[=items.reservation_id=]]))-->
                            <a href="<?php echo Url::build('reservation',array( 'cmd'=>'edit','id'=>[[=items.reservation_id=]] ));?>" target="_blank">[[|items.reservation_id|]]</a></div>
                            <!--ELSE-->
                            <a href="<?php echo Url::build('touch_bar_restaurant',array( 'cmd'=>'edit','id'=>[[=items.bar_reservation_id=]] ));?>" target="_blank">[[|items.bar_reservation_id|]]</a></div>
                            <!--/IF:cond-->
                        </td>
                        <td align="left">[[|items.room_name|]]</td>
                        <td align="left">[[|items.customer_name|]]</td>
                        <!--<td align="left">[[|items.arrival_time|]]</td>
                        <td align="left">[[|items.departure_time|]]</td>-->
                        <td align="left">[[|items.print_user|]]</td>
                        <td align="left">[[|items.print_time|]]</td>
                        <td align="left">[[|items.last_print_user|]]</td>
                        <td align="left">[[|items.last_print_time|]]</td>
                        <?php if(User::can_edit(false,ANY_CATEGORY)){?>
                		<td align="left" >
                            <a href="<?php echo URL::build_current(array('cmd'=>'entry_restaurant','department'=>[[=items.department=]],'id'=>[[=items.id=]]));?>" target="_blank">
                                <img src="packages/core/skins/default/images/buttons/edit.gif" alt="Edit"/>
                            </a>
                        </td>
                		<?php }?>
                        <?php if(User::can_delete(false,ANY_CATEGORY)){?>
                		<td align="left" >
                            <a href="<?php echo URL::build_current(array('cmd'=>'delete','id'=>[[=items.id=]]));?>" onclick="if(!confirm('[[.are_you_sure.]]')){return false}">
                                <img src="packages/core/skins/default/images/buttons/delete.gif" alt="Delete"/>
                            </a>
                        </td>
                		<?php }?>
                	</tr> 
                	<!--/LIST:items-->
                </table>
		    </div>
			<div class="paging">[[|paging|]]</div>
            <table width="100%">
                <tr>
        			<td width="100%">
        				[[.select.]]:&nbsp;
        				<a href="javascript:void(0)" onClick="select_all_checkbox(document.ListVatInvoiceForm,'ManageProvince',true,'#FFFFEC','white');">[[.select_all.]]</a>&nbsp;
        				<a href="javascript:void(0)" onClick="select_all_checkbox(document.ListVatInvoiceForm,'ManageProvince',false,'#FFFFEC','white');">[[.select_none.]]</a>
        				<a href="javascript:void(0)" onClick="select_all_checkbox(document.ListVatInvoiceForm,'ManageProvince',-1,'#FFFFEC','white');">[[.select_invert.]]</a>
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
