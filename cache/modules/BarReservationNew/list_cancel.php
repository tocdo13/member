<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('list_title'));
    //System::debug($this->map['items']);
?>
<link href="skins/default/category.css" rel="stylesheet" type="text/css" />
<table cellspacing="0" width="100%">
	<tr valign="top">
		<td align="left" colspan="2">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="" style="font-size: 18px; text-transform: uppercase; padding-left: 15px;" width="60%"><i class="fa fa-file-text" style="font-size: 26px;"></i> <?php echo Portal::language('bar_reservation_list_cancel');?>  (<?php echo $this->map['bar_name'];?>)</td>
					<?php
                        if(User::can_delete(false,ANY_CATEGORY))
                        {
                        ?><td style="width: 40%; text-align: right; padding-right: 30px;"><input type="submit" name="delete" value="<?php echo Portal::language('delete');?>" class="w3-btn w3-red" style="text-transform: uppercase; margin-right: 5px;" />
                        <?php
                        }
                        ?>
                    <input type="button" name="list" value="<?php echo Portal::language('back');?>" class="w3-btn w3-green" onclick="window.location='<?php echo Url::build_current();?>'"style="text-transform: uppercase;" /></td>
                </tr>
            </table>
		</td>
	</tr>
	<tr valign="top">
		<td width="100%">
			<table cellspacing="0" width="100%">
			<tr>
				<td width="100%">
					
					<table>
					<form method="post" name="SearchBarReservationNewForm">
						<tr >
							<td align="right" nowrap><?php echo Portal::language('arrival_time');?></td>
							<td nowrap>
								<input  name="from_arrival_time" id="from_arrival_time" size="8" onchange="changevalue();" style="height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('from_arrival_time'));?>">
								<?php echo Portal::language('to');?>
								<input  name="to_arrival_time" id="to_arrival_time" size="8" onchange="changevalue();" style="height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('to_arrival_time'));?>">
                                
						    <td nowrap>&nbsp;</td>
						    <td nowrap><?php echo Portal::language('agent_name');?>
					        <input  name="agent_name" id="agent_name" style="height: 24px;" / type ="text" value="<?php echo String::html_normalize(URL::get('agent_name'));?>"></td>
						    <td nowrap><?php echo Draw::button(Portal::language('search'),false,false,true,'SearchBarReservationNewForm');?></td>
                            <td>&nbsp;</td>
                             <td align="right">
                                <?php echo Portal::language('bar_name');?> <select  name="bars" id="bars" onchange="updateBar();" style="height: 24px;"><?php
					if(isset($this->map['bars_list']))
					{
						foreach($this->map['bars_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('bars',isset($this->map['bars'])?$this->map['bars']:''))
                    echo "<script>$('bars').value = \"".addslashes(URL::get('bars',isset($this->map['bars'])?$this->map['bars']:''))."\";</script>";
                    ?>
	</select> 
                                <input  name="acction" value="0" id="acction" style="height: 24px;" / type ="hidden" value="<?php echo String::html_normalize(URL::get('acction'));?>">
                                <script>
                                    var bar_id = '<?php if(Url::get('bar_id')){ echo Url::get('bar_id');} else { echo '';}?>';
                                    if(bar_id != ''){
                                    	$('bars').value = bar_id;	
                                    }
                                 </script>
                            </td> 
						</tr>
				  <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
				  </table>
					<form name="BarReservationNewListForm" method="post">
                  	<div style="border:2px solid #FFFFFF;">
					<table width="100%" cellpadding="2" cellspacing="0" bordercolor="#CECFCE" border="1" style="border-collapse:collapse">
						<tr style="height: 24px; text-transform: uppercase;" class="w3-light-gray">
						  <th width="1%" align="center"><input type="checkbox" value="1" onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"></th>
							<th nowrap align="center">
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='bar_reservation.time' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'bar_reservation.time'));?>">
								<?php if(URL::get('order_by')=='bar_reservation.time') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('time');?></a>							</th>
							<th align="center" nowrap="nowrap"> <?php echo Portal::language('cancel_time');?> </th>
							<th align="center" nowrap="nowrap"> <a href="<?php echo URL::build_current(((URL::get('order_by')=='bar_reservation.code' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'bar_reservation.code'));?>">
                              <?php if(URL::get('order_by')=='bar_reservation.code') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('code');?> </a> </th>
                            <th align="center" nowrap="nowrap"> <?php echo Portal::language('mice');?></th>  
							<th align="center" nowrap="nowrap"> 
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='bar_reservation.agent_name' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'bar_reservation.agent_name'));?>">
								<?php if(URL::get('order_by')=='bar_reservation.agent_name') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('source_customer');?> / <?php echo Portal::language('guest_name');?></a>							</th>
							<th align="center" nowrap="nowrap"><?php echo Portal::language('user_name');?></th>
							<th align="center" nowrap><?php echo Portal::language('total');?></th>
							<th align="center" nowrap><?php echo Portal::language('delete');?></th>
						</tr>
						<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
						<tr style="height: 24px;" bgcolor="<?php if(URL::get('just_edited_id',0)==$this->map['items']['current']['id']){ echo '#EFFFDF';} else {echo 'white';}?>" valign="middle" <?php Draw::hover('#EFEEEE');?> onclick="location='<?php echo URL::build('touch_bar_restaurant').'&cmd=edit';?>&id=<?php echo $this->map['items']['current']['id'];?>&bar_id=<?php echo $this->map['items']['current']['bar_id'];?>';">
							<td><input name="selected_ids[]" type="checkbox" value="<?php echo $this->map['items']['current']['id'];?>" onclick="just_click=true;"></td>
								<td nowrap align="left"><?php echo $this->map['items']['current']['arrival_date'];?></td>
								<td align="left" nowrap="nowrap"><?php echo $this->map['items']['current']['cancel_date'];?> </td>
								<td align="left" nowrap="nowrap"><?php echo $this->map['items']['current']['code'];?> </td>
                                <td align="left" nowrap="nowrap"><?php if($this->map['items']['current']['mice_reservation_id']!=''){ ?>MICE+<?php echo $this->map['items']['current']['mice_reservation_id'];?><?php } ?></td>
								<td align="left" nowrap="nowrap"><?php echo $this->map['items']['current']['name'];?></td>
								<td align="left" nowrap="nowrap"><?php echo $this->map['items']['current']['user_id'];?></td>
								<td align="right" style="padding-right: 4px;" nowrap><?php echo $this->map['items']['current']['total'];?></td>
								<?php if(User::can_delete(false,ANY_CATEGORY)) {?>
                                <td style="text-align: center;"><a href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>$this->map['items']['current']['id'],'bar_id'=>$this->map['items']['current']['bar_id']));?>"><i class="fa fa-times-circle w3-text-red" style="font-size: 16px;"></i></a></td>
                                <?php }?>
						</tr>
						<?php }}unset($this->map['items']['current']);} ?>
					</table>
                    </div>
                    <?php echo $this->map['paging'];?>
                    <input type="hidden" name="is_cancel" value="1" id="is_cancel" />
                    <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
                </td>
			</tr>
			</table>
		</td>
	</tr>
</table>
<script>
	jQuery('#from_arrival_time').datepicker();
	jQuery('#to_arrival_time').datepicker();
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
	function updateBar(){
		jQuery('#acction').val(1);
		//jQuery('#bar').val(jQuery('#bar').val());
		SearchBarReservationNewForm.submit();
	//jQuery('#acction').val(0);
	}
</script>