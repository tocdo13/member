<DIV ID="calenderdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></DIV>
<link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<script src="packages/core/includes/js/calendar.js">
</script>
<SCRIPT LANGUAGE="JavaScript">
	document.write(getCalendarStyles());
	cal = new CalendarPopup('calenderdiv');
	cal.showNavigationDropdowns();
</SCRIPT>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('shop_invoice_list'));?>
<table cellspacing="0" width="100%">
	<tr valign="top">
		<td align="left">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="form-title" width="100%">[[.shop_invoice_list.]]</td>
					<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%" align="right"><a href="<?php echo URL::build_current(array('cmd'=>'add'));?>"  class="button-medium-add">[[.shop_invoice.]]</a></td><?php }?>
                    <?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%"><a href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};ShopInvoiceListForm.cmd.value='delete';ShopInvoiceListForm.submit();"  class="button-medium-delete">[[.Delete.]]</a></td><?php }?>                    
                </tr>
            </table>
		</td>
	</tr>
	<tr bgcolor="#EFEFEF" valign="top">
		<td width="100%">
			<table bgcolor="#EFEFEF" cellspacing="0" width="100%">
			<tr>
			<td>
				<table width="100%" cellpadding="0" cellspacing="5">
                    <tr>
                        <td width="100%">
                            <b>[[.search.]]</b>
                            <form method="post" name="SearchShopInvoiceForm"> 
                            <table>
                            	<tr>
                                	<td align="right">[[.Shop.]]</td>
                                    <td>:</td>
                                    <td><select name="shop_id" onchange="window.location='<?php echo Url::build_current()?>&shop_id='+this.value"></select></td>
                                </tr>
                                <tr>
                                    <td align="right" nowrap>[[.arrival_time.]]</td>
                                    <td>:</td>
                                    <td nowrap>
                                            <input name="from_arrival_time" type="text" id="from_arrival_time" size="12">
                                            <a href="#" name="arrival_time_start_date_in" onclick="cal.select(this.input,'arrival_time_start_date_in','dd/MM/yyyy'); return false;"><img width="20" src="skins/default/images/calendar.gif" title="[[.select_date.]]"></a>
                                        <script>
                                            var inputs = document.getElementsByTagName("input");
                                            var anchors = document.getElementsByTagName("a");
                                            anchors[anchors.length-1].input = inputs[inputs.length-1];
                                        </script>
                                        &nbsp;&nbsp;[[.to.]]
                                        <input name="to_arrival_time" type="text" id="to_arrival_time" size="12">
                                        <a href="#" name="arrival_time_end_date_in" onclick="cal.select(this.input,'arrival_time_end_date_in','dd/MM/yyyy'); return false;"><img width="20" src="skins/default/images/calendar.gif" title="[[.select_date.]]"></a>
                                        <script>
                                            var inputs = document.getElementsByTagName("input");
                                            var anchors = document.getElementsByTagName("a");
                                            anchors[anchors.length-1].input = inputs[inputs.length-1];
                                        </script>
                                    </td>
                                </tr> 
                                <tr>    
                                    <td align="right" nowrap>[[.agent_name.]]</td>
                                    <td>:</td>
                                    <td nowrap>
                                            <input name="agent_name" type="text" id="agent_name" size="45">
                                    </td>
                                </tr>
                                <tr>
                                	<td align="right">[[.total_from.]]</td>
                                    <td>:</td>
                                    <td>
                                    	<input name="total_from" type="text" id="total_from" size="15" />&nbsp;[[.to.]]:&nbsp;<input name="total_to" type="text" id="total_to" size="15" />&nbsp;<strong><?php echo HOTEL_CURRENCY?></strong>
                                    </td>
                                </tr>
                                <tr>
                                	<td>&nbsp;</td>
                                	<td>&nbsp;</td>
                                    <td>
                                        <?php echo Draw::button(Portal::language('search'),false,false,true,'SearchShopInvoiceForm');?><?php echo Draw::button('Reset','?page=shop_invoice');?></td>
                                </tr>
                            </table>
                            </form>
                        </td>
                    </tr>
				</table>
				<form name="ShopInvoiceListForm" method="post">
                <div style="border:2px solid #FFFFFF;">
				<table width="100%" cellpadding="5" cellspacing="0" bordercolor="#CECFCE" border="1" style="border-collapse:collapse">
					<tr valign="middle" style="line-height:20px;">
						<th width="1%"><input type="checkbox" value="1" onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"></th>
						<th align="left" nowrap="nowrap" width="1%">[[.time.]]</th>
						<th align="left" nowrap="nowrap">[[.customer_name.]]</th>
						<th align="left" nowrap="nowrap">[[.shop_name.]]</th>
						<th align="right" nowrap="nowrap">[[.total.]]</th>
                        <th>&nbsp;</th>
						<?php if(User::can_delete(false,ANY_CATEGORY)) {?><th>&nbsp;</th>
						<?php }?></tr>
					<!--LIST:items-->
					<tr bgcolor="<?php if(URL::get('just_edited_id',0)){ echo '#EFFFDF';}else{echo '#FFFFFF';}?>" valign="middle" <?php Draw::hover('#EFEEEE');?>onclick="if(typeof(just_click)=='undefined' || !just_click){location='<?php echo URL::build_current();?>&id=[[|items.id|]]';}else{just_click=false;}" style="cursor:hand;<?php if([[=items.status=]]=='CHECKIN'){?>font-weight:bold;<?php }?>">
						<td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="just_click=true;"></td>
							<td align="left" nowrap="nowrap"><?php echo date('H:i d/m/Y',[[=items.time=]]);?></td>
							<td align="left" nowrap="nowrap">[[|items.agent_name|]]</td>
							<td align="left" nowrap="nowrap">[[|items.shop_name|]]</td>
							<td align="right" nowrap="nowrap">[[|items.total|]]</td>
                            <td nowrap width="1%"><a href="<?php echo Url::build_current(array(  'shop_invoice_bar_id', 'shop_invoice_receptionist_id')+array('cmd'=>'edit','id'=>[[=items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/edit.gif" alt="[[.edit.]]" width="12" height="12" border="0"></a></td>
							<?php if(User::can_delete(false,ANY_CATEGORY)) {?><td nowrap width="1%"><a href="<?php echo Url::build_current(array(  'shop_invoice_bar_id', 'shop_invoice_receptionist_id')+array('cmd'=>'delete','id'=>[[=items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/delete.gif" alt="[[.delete.]]" width="12" height="12" border="0"></a></td>
							<?php } ?></tr>
					<!--/LIST:items-->
				</table>
                </div>
                [[|paging|]]
			<input name="cmd" type="hidden" value="">                
            </form> 
		</td>
        </tr>
	</table>
    </td>
</tr>
</table>    