<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('list_title'));?>
<link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<table cellspacing="0" width="100%">
	<tr valign="top">
		<td align="left" colspan="2">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="form-title" width="100%">[[.karaoke_reservation_list_cancel.]]  ([[|karaoke_name|]])</td>
					<?php
                        if(User::can_delete(false,ANY_CATEGORY))
                        {
                        ?><td><input type="submit" name="delete" value="[[.delete.]]" class="button-medium-delete" /></td>
                        <?php
                        }
                        ?>
                    <td><input type="button" name="list" value="[[.back.]]" class="button-medium-back" onclick="window.location='<?php echo Url::build_current();?>'" /></td>
                </tr>
            </table>
		</td>
	</tr>
	<tr valign="top">
		<td width="100%">
			<table cellspacing="0" width="100%">
			<tr>
				<td width="100%">
					<b>[[.search.]]</b>
					<table>
					<form method="post" name="SearchKaraokeReservationNewForm">
						<tr>
							<td align="right" nowrap>[[.arrival_time.]]</td>
							<td>:</td>
							<td nowrap>
								<input name="from_arrival_time" type="text" id="from_arrival_time" size="8" onchange="changevalue();" />
								[[.to.]]
								<input name="to_arrival_time" type="text" id="to_arrival_time" size="8" onchange="changefromday();" />
                                <script>
                                        function changevalue(){
                                            var myfromdate = $('from_arrival_time').value.split("/");
                                            var mytodate = $('to_arrival_time').value.split("/");
                                            if(myfromdate[2] > mytodate[2]){
                                                $('to_arrival_time').value =$('from_arrival_time').value;
                                            }else{
                                                if(myfromdate[1] > mytodate[1]){
                                                    $('to_arrival_time').value =$('from_arrival_time').value;
                                                }else{
                                                    if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1])){
                                                        $('to_arrival_time').value =$('from_arrival_time').value;
                                                    }
                                                }
                                            }
                                        }
                                        function changefromday(){
                                            var myfromdate = $('from_arrival_time').value.split("/");
                                            var mytodate = $('to_arrival_time').value.split("/");
                                            if(myfromdate[2] > mytodate[2]){
                                                $('from_arrival_time').value= $('to_arrival_time').value;
                                            }else{
                                                if(myfromdate[1] > mytodate[1]){
                                                    $('from_arrival_time').value = $('to_arrival_time').value;
                                                }else{
                                                    if((myfromdate[0] > mytodate[0])&&(myfromdate[1] == mytodate[1])){
                                                        $('from_arrival_time').value =$('to_arrival_time').value;
                                                    }
                                                }
                                            }
                                        }
                                    </script>
						    <td nowrap>&nbsp;</td>
						    <td nowrap>[[.agent_name.]]
					        <input name="agent_name" type="text" id="agent_name" /></td>
						    <td nowrap><?php echo Draw::button(Portal::language('search'),false,false,true,'SearchKaraokeReservationNewForm');?></td>
                            <td>&nbsp;</td>
                             <td align="right">
                                [[.karaoke_name.]]: <select name="karaokes" id="karaokes" onchange="updateKaraoke();"></select> 
                                <input name="acction" type="hidden" value="0" id="acction" />
                                <script>
                                    var karaoke_id = '<?php if(Url::get('karaoke_id')){ echo Url::get('karaoke_id');} else { echo '';}?>';
                                    if(karaoke_id != ''){
                                    	$('karaokes').value = karaoke_id;	
                                    }
                                 </script>
                            </td> 
						</tr>
				  </form>
				  </table>
					<form name="KaraokeReservationNewListForm" method="post">
                  	<div style="border:2px solid #FFFFFF;">
					<table width="100%" cellpadding="2" cellspacing="0" bordercolor="#CECFCE" border="1" style="border-collapse:collapse">
						<tr class="table-header">
						  <th width="1%" align="center"><input type="checkbox" value="1" onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"></th>
							<th nowrap align="left">
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='karaoke_reservation.time' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'karaoke_reservation.time'));?>">
								<?php if(URL::get('order_by')=='karaoke_reservation.time') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.time.]]</a>							</th>
							<th align="left" nowrap="nowrap"> [[.cancel_time.]] </th>
							<th align="left" nowrap="nowrap"> <a href="<?php echo URL::build_current(((URL::get('order_by')=='karaoke_reservation.code' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'karaoke_reservation.code'));?>">
                              <?php if(URL::get('order_by')=='karaoke_reservation.code') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.code.]] </a> </th>
							<th align="left" nowrap="nowrap"> 
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='karaoke_reservation.agent_name' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'karaoke_reservation.agent_name'));?>">
								<?php if(URL::get('order_by')=='karaoke_reservation.agent_name') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.agent_name.]]</a>							</th>
							<th align="left" nowrap="nowrap">[[.user_name.]]</th>
							<th align="right" nowrap>[[.total.]]</th>
							<th align="left" nowrap></th>
						</tr>
						<!--LIST:items-->
						<tr bgcolor="<?php if(URL::get('just_edited_id',0)==[[=items.id=]]){ echo '#EFFFDF';} else {echo 'white';}?>" valign="middle" <?php Draw::hover('#EFEEEE');?> onclick="location='<?php echo URL::build('karaoke_touch').'&cmd=edit';?>&id=[[|items.id|]]&karaoke_id=[[|items.karaoke_id|]]';">
							<td><input name="selected_ids[]" type="checkbox" value="[[|items.id|]]" onclick="just_click=true;"></td>
								<td nowrap align="left">[[|items.arrival_date|]]</td>
								<td align="left" nowrap="nowrap">[[|items.cancel_date|]] </td>
								<td align="left" nowrap="nowrap">[[|items.order_id|]] </td>
								<td align="left" nowrap="nowrap"> [[|items.agent_name|]] </td>
								<td align="left" nowrap="nowrap">[[|items.user_id|]]</td>
								<td align="right" style="padding-right: 4px;" nowrap>[[|items.total|]]</td>
								<?php if(User::can_delete(false,ANY_CATEGORY)) {?>
                                <td><a href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>[[=items.id=]],'karaoke_id'=>[[=items.karaoke_id=]]));?>"><img src="packages/core/skins/default/images/buttons/delete.gif" alt="[[.delete.]]" width="12" height="12" border="0"></a></td>
                                <?php }?>
						</tr>
						<!--/LIST:items-->
					</table>
                    </div>
                    [[|paging|]]
                    <input type="hidden" name="is_cancel" value="1" id="is_cancel" />
                    </form>
                </td>
			</tr>
			</table>
		</td>
	</tr>
</table>
<script>
	jQuery('#from_arrival_time').datepicker();
	jQuery('#to_arrival_time').datepicker();
	function updateKaraoke(){
		jQuery('#acction').val(1);
		//jQuery('#karaoke').val(jQuery('#karaoke').val());
		SearchKaraokeReservationNewForm.submit();
	//jQuery('#acction').val(0);
	}
</script>