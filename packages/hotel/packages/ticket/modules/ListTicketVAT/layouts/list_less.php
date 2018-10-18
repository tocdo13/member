<link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('bar_reservation_list'));?>
<form method="post" name="ListLessBarReservationNewForm"> 
<table cellspacing="0" width="100%">
    <tr valign="top">
		<td align="left">
            <table cellpadding="10" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="form-title" width="100%">[[.bar_invoice_list.]]</td>
                    <?php if(User::can_add(false,ANY_CATEGORY)){?><td><input name="print_all_vat" type="submit" id="print_all_vat" class="button-medium-add" value="[[.print_all_vat.]]"/></td><?php }?>
                    <?php if(User::can_add(false,ANY_CATEGORY)){?><td><input name="print_group_vat" type="submit" id="print_group_vat" class="button-medium-add" value="[[.print_group_vat.]]" onclick="return check_items(this);"/></td><?php }?>
                    <?php if(User::can_view(false,ANY_CATEGORY)){?><td><a href="<?php echo Url::build('vat_bill',array('department'=>'TICKET'));?>"  class="button-medium-add" target="_blank">[[.list_vat_printed.]]</a></td><?php }?>
                    <?php if(User::can_view(false,ANY_CATEGORY)){?><td width="1%" align="right"><a href="<?php echo URL::build_current(array());?>"  class="button-medium">[[.list_bar_reservation_more_than_200,000.]]</a></td><?php }?>                   
                </tr>
            </table>
		</td>
	</tr>
	<tr valign="top">
        <td width="100%">
			<table cellspacing="0" width="100%">
			<tr>
			<td>
				<fieldset>
                <legend class="title">[[.search_options.]]</legend>
                <table>
                    <tr>
                        <td align="right">[[.invoice_number.]] <input name="invoice_number" type="text" id="invoice_number" class="date-input" /></td>
                        <td align="right">[[.date.]]</td>
                        <td>:</td>
                        <td>
                            <input name="from_arrival_time" type="text" id="from_arrival_time" style="width:80px;" class="date-input"/>
                            <!--
                            &nbsp;[[.to.]]
                            <input name="to_arrival_time" type="text" id="to_arrival_time" style="width:80px;" class="date-input"/>
                            -->
                        </td>
                        <td>&nbsp;</td>
                        <!--<td><select name="status" id="status" onchange="window.location='<?php echo Url::build_current()?>&amp;cmd=list_less&amp;status='+this.value"></select></td>-->
                        <td><input type="submit" value="[[.search.]]" /></td>
                        <td>&nbsp;</td>
                        <td align="right" style="text-align:right;">
                            [[.bar_name.]]: <select name="bars" id="bars" onchange="updateBar();"></select> 
                            <input name="acction" type="hidden" value="0" id="acction" />
                            <script>
                            var bar_id = '<?php if(Url::get('bar_id')){ echo Url::get('bar_id');} else { echo '';}?>';
                            if(bar_id != ''){
                                $('bars').value = bar_id;	
                            }
                            </script>
                        </td>
                    </tr>
                </table>
                </fieldset>
                <div style="border:2px solid #FFFFFF;">
				<table width="100%" cellpadding="2" cellspacing="0" bordercolor="#CECFCE" border="1" style="border-collapse:collapse">
					<tr class="table-header">
                        <th width="1%"><input type="checkbox" value="1" onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"/></th>
						<th align="left" width="150px">[[.time.]]/[[.time_in.]]</th>
						<th align="left" width="100px">[[.code.]]</th>
						<th align="center" width="150px">[[.agent_name.]]</th>
						<th align="center" width="100px">[[.room_name.]]</th>
						<th align="center" width="80px">[[.total.]]</th>
						<?php if(User::can_delete(false,ANY_CATEGORY)) {?>
						<th width="80px">[[.user.]]</th>
                        <th align="center" width="60px">VAT</th>
                        <?php }?>
                    </tr>
					<!--LIST:items-->
                    <?php
						//if([[=items.status=]]=='CHECKIN')
//						{
							$bg_color = '#FFFF99';
						//}
						//else if([[=items.status=]]=='CHECKOUT')
						//{
							//$bg_color = '#E2F1DF';
						//}
						//else
						//{
						//	$bg_color = '#FFFFFF';
						//}						
						?>
					<tr bgcolor="<?php if(URL::get('just_edited_id',0)==[[=items.id=]]){ echo '#EFFFDF';} else {echo $bg_color;}?>" valign="middle" <?php Draw::hover('#EFEEEE');?> style="cursor:pointer;">
                        <td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" class="item-check-box" onclick="just_click=true;"/></td>
                        <td align="left">[[|items.time|]]</td>
                        <td align="left">[[|items.code|]]</td>
                        <td align="left">[[|items.agent_name|]]</td>
                        <td align="center">[[|items.room_name|]]</td>
                        <td align="right">[[|items.total|]]</td>
                        <td align="center">[[|items.user_id|]]</td>
                        <td align="center">[[|items.vat_bill_code|]]</td>
                    </tr>
					<!--/LIST:items-->
				</table>
                </div>
                [[|paging|]]
            </td>
            </tr>
            </table>
        </td>
    </tr>
</table>  
</form>  
<script>
	jQuery('#from_arrival_time').datepicker({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
	jQuery('#to_arrival_time').datepicker({ minDate: new Date(BEGINNING_YEAR, 1 - 1, 1) });
	function updateBar(){
		jQuery('#acction').val(1);
		ListLessBarReservationNewForm.submit();
	}
    
    function check_items(obj)
    {
        var all_checked = new Array();
        all_checked  = jQuery(".item-check-box:checked");
        if(all_checked.length < 1)
        {
            alert('[[.you_must_choose_item.]]');
            return false;    
        }
    }
    
</script>