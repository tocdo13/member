<DIV ID="calenderdiv" STYLE="position:absolute;visibility:hidden;background-color:white;layer-background-color:white;"></DIV>
<script src="packages/core/includes/js/calendar.js">
</script>
<SCRIPT LANGUAGE="JavaScript">
	document.write(getCalendarStyles());
	cal = new CalendarPopup('calenderdiv');
	cal.showNavigationDropdowns();
</SCRIPT>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('list_title'));?>
<link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<table cellspacing="0" width="100%">
	<tr>
    	<td>
        <div class="bar-id">
            <label for="bar_id">[[.Bar_name.]]: </label>
            <?php if(User::can_admin(MODULE_RESTAURANTPRODUCT,ANY_CATEGORY)){?>
            <select name="bar_id" onchange="window.location='<?php echo Url::build('bar_reservation',array('cmd'))?>'+'&bar_id='+this.value"></select>
            <?php }else{?>
            <span>[[|bar_name|]]</span>
            <?php }?>
        </div>        
        </td>
    </tr>	
	<tr valign="top">
		<td align="left" colspan="2">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="form-title" width="100%">[[.bar_reservation_list_cancel.]]</td>
                </tr>
            </table>
		</td>
	</tr>
	<tr bgcolor="#EFEFEF" valign="top">
		<td width="100%">
			<table bgcolor="#EFEFEF" cellspacing="0" width="100%">
			<tr>
				<td width="100%">
					<b>[[.search.]]</b>
					<table>
					<form method="post" name="SearchBarReservationForm"> 
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
							<td colspan="3">
								<?php echo Draw::button(Portal::language('search'),false,false,true,'SearchBarReservationForm');?>
								<?php echo Draw::button('Reset','?page=bar_reservation');?></td>
						</tr>
				  </form>
				  </table>
					<form name="BarReservationListForm" method="post">
                  	<div style="border:2px solid #FFFFFF;">
					<table width="100%" cellpadding="5" cellspacing="0" bordercolor="#CECFCE" border="1" style="border-collapse:collapse">
						<tr valign="middle" style="line-height:20px">
							<th width="1%"><input type="checkbox" value="1" onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"></th>
							<th nowrap align="left">
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='bar_reservation.time' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'bar_reservation.time'));?>">
								<?php if(URL::get('order_by')=='bar_reservation.time') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.time.]]</a>							</th>
							<th align="center" nowrap="nowrap"> [[.cancel_time.]] </th>
							<th align="left" nowrap="nowrap"> <a href="<?php echo URL::build_current(((URL::get('order_by')=='bar_reservation.code' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'bar_reservation.code'));?>">
                              <?php if(URL::get('order_by')=='bar_reservation.code') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.code.]] </a> </th>
							<th align="center" nowrap="nowrap"> 
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='bar_reservation.agent_name' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'bar_reservation.agent_name'));?>">
								<?php if(URL::get('order_by')=='bar_reservation.agent_name') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.agent_name.]]</a>							</th>
							<th align="center" nowrap="nowrap">[[.bar_name.]]</th>
							<th align="center" nowrap="nowrap">[[.user_name.]]</th>
							<th align="left" nowrap>[[.total.]]</th>
						</tr>
						<!--LIST:items-->
						<tr bgcolor="<?php if(URL::get('just_edited_id',0)==[[=items.id=]]){ echo '#EFFFDF';} else {echo 'white';}?>" valign="middle" <?php Draw::hover('#EFEEEE');?>>
							<td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="just_click=true;"></td>
								<td nowrap align="left">[[|items.arrival_date|]]</td>
								<td align="center" nowrap="nowrap">[[|items.cancel_date|]] </td>
								<td align="left" nowrap="nowrap">[[|items.order_id|]] </td>
								<td align="center" nowrap="nowrap"> [[|items.agent_name|]] </td>
								<td align="center" nowrap="nowrap">[[|items.table_name|]] </td>
								<td align="center" nowrap="nowrap">[[|items.user_name|]]</td>
								<td align="right" style="padding-right: 4px;" nowrap>[[|items.total|]]</td>
								<?php if(User::can_delete(false,ANY_CATEGORY)) {?>
                                <td><a href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>[[=items.id=]]));?>"><img src="packages/core/skins/default/images/buttons/delete.gif" alt="[[.delete.]]" width="12" height="12" border="0"></a></td>
                                <?php }?>
						</tr>
						<!--/LIST:items-->
					</table>
                    </div>
                    [[|paging|]]
                    <p>
                    <table>
                    	<tr>
                        	<td><?php Draw::button(Portal::language('bar_reservation_list'),URL::build_current());?></td>
							<?php
                            if(User::can_delete(false,ANY_CATEGORY))
                            {
                            ?><td><?php Draw::button(Portal::language('delete_selected'),false,false,true,'BarReservationListForm');?></td>
                            <?php
                            }
                            ?>                            
						</tr>
                    </table>
                    </p>
                    <input type="hidden" name="is_cancel" value="1" id="is_cancel" />
                    </form>
                </td>
			</tr>
			</table>
		</td>
	</tr>
</table>