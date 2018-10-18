<link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('vending_reservation_list'));?>
<form method="post" name="ListBarReservationNewForm"> 
<table cellspacing="0" width="100%">
    <tr valign="top">
		<td align="left">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="form-title" width="100%">[[.vending_invoice_list.]]</td>
                    <?php if(User::can_view(false,ANY_CATEGORY)){?><td><a href="<?php echo Url::build('vat_bill',array('department'=>'VEND'));?>"  class="button-medium-add" target="_blank">[[.list_vat_printed.]]</a></td><?php }?>
                    <?php if(User::can_view(false,ANY_CATEGORY)){?><td width="1%" align="right"><a href="<?php echo URL::build_current(array('cmd'=>'list_less'));?>"  class="button-medium">[[.list_vending_reservation_less_than_200,000.]]</a></td><?php }?>
					<!--
                    <?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%" align="right"><a href="<?php echo URL::build_current(array('cmd'=>'add'));?>"  class="button-medium-add">[[.bar_reservation.]]</a></td><?php }?>
                    <?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%"><a href="javascript:void(0)" onclick="if(!confirm('[[.are_you_sure.]]')){return false};BarReservationNewListForm.cmd.value='delete';BarReservationNewListForm.submit();"  class="button-medium-delete">[[.Delete.]]</a></td><?php }?>
                    -->                    
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
                        <td align="right">[[.arrival_time.]]</td>
                        <td>:</td>
                        <td>
                            <input name="from_arrival_time" type="text" id="from_arrival_time" onchange="changevalue()" style="width:80px;" class="date-input"/>
                            &nbsp;[[.to.]]
                            <input name="to_arrival_time" type="text" id="to_arrival_time" onchange="changefromday()" style="width:80px;" class="date-input"/>
                        </td>
                        <td>&nbsp;</td>
                        <!--<td><select name="status" id="status" onchange="window.location='<?php echo Url::build_current()?>&amp;status='+this.value"></select></td>-->
                        <td><input type="submit" value="[[.search.]]" /></td>
                        <td>&nbsp;</td>
                        <!--<td align="right" style="text-align:right;">
                            [[.bar_name.]]: <select name="bars" id="bars" onchange="updateBar();"></select> 
                            <input name="acction" type="hidden" value="0" id="acction" />
                            <script>
                            var bar_id = '<?php //if(Url::get('bar_id')){ echo Url::get('bar_id');} else { echo '';}?>';
                            if(bar_id != ''){
                                $('bars').value = bar_id;	
                            }
                            </script>
                        </td>-->
                    </tr>
                </table>
                </fieldset>
                <div style="border:2px solid #FFFFFF;">
				<table width="100%" cellpadding="2" cellspacing="0" bordercolor="#CECFCE" border="1" style="border-collapse:collapse">
					<tr class="table-header">
						<th align="left" width="150px">[[.time.]]/[[.time_in.]]</th>
						<th align="center" width="100px">[[.code.]]</th>
						<th align="center" width="150px">[[.agent_name.]]</th>
						<th align="center" width="80px">[[.total.]]</th>
						<?php if(User::can_delete(false,ANY_CATEGORY)) {?>
						<th>[[.user.]]</th>
                        <th align="center" width="80px">VAT</th>
						<th width="80px">&nbsp;</th>
                        <?php }?>
                    </tr>
					<!--LIST:items-->
                    <?php
						//if([[=items.status=]]=='CHECKIN')
//						{
//							$bg_color = '#FFFF99';
//						}
//						else if([[=items.status=]]=='CHECKOUT')
//						{
//							$bg_color = '#E2F1DF';
//						}
//						else
//						{
							$bg_color = '#FFFFFF';
						//}						
						?>
					<tr bgcolor="<?php if(URL::get('just_edited_id',0)==[[=items.id=]]){ echo '#EFFFDF';} else {echo $bg_color;}?>" valign="middle" <?php Draw::hover('#EFEEEE');?> style="cursor:pointer;">
                        <td align="left">[[|items.time|]]</td>
                        <td align="center">[[|items.code|]]</td>
                        <td align="left">[[|items.agent_name|]]</td>
                        <td align="right">[[|items.total|]]</td>
                        <td align="left">[[|items.user_id|]]</td>
                        <td align="center">[[|items.vat_bill_code|]]</td>
                        <?php if(User::can_edit(false,ANY_CATEGORY)) {?> <td><a class="button-medium-add" target="_blank" href="<?php echo Url::build('vat_bill',array(  'department'=>'VEND','cmd'=>'entry_vending','v_r_id'=>[[=items.id=]])); ?>">[[.print_vat.]]</a></td> <?php } ?>
                    </tr>
					<!--/LIST:items-->
				</table>
                </div>
                [[|paging|]]
                <input name="cmd" type="hidden" value=""/>
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
		ListBarReservationNewForm.submit();
	}
    function changevalue()
    {
        var myfromdate = $('from_arrival_time').value.split("/");
        var mytodate = $('to_arrival_time').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#to_arrival_time").val(jQuery("#from_arrival_time").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('from_arrival_time').value.split("/");
        var mytodate = $('to_arrival_time').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#from_arrival_time").val(jQuery("#to_arrival_time").val());
        }
    }
</script>