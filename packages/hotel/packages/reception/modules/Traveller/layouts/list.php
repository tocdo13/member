<script>
	traveller_array_items = {
		'length':'<?php echo sizeof([[=items=]]);?>'
<!--LIST:items-->
,'[[|items.i|]]':'[[|items.id|]]'
<!--/LIST:items-->
	}
</script>
<table cellpadding="15" cellspacing="0" width="100%" border="0" class="table-bound">
		<tr>
        	<td width="90%" class="form-title">[[.traveller_list.]]</td>
        </tr>
</table> 
<table bgcolor="#FFFFFF" cellspacing="0" width="100%">
<tr>
	<td width="100%" align="center" > 
		<fieldset>
			<legend class="title">[[.search.]]</legend>
			<form name="SearchTravellerForm" method="post">
			<table border="0" cellspacing="0" cellpadding="2">
				<tr><td align="right" nowrap>[[.full_name.]]</td>
				<td>:</td>
				<td nowrap>
						<input name="full_name" type="text" id="full_name" style="width:100px" /></td><td align="right" nowrap>[[.passport.]]</td>
				<td>:</td>
				<td nowrap>
						<input name="passport" type="text" id="passport" style="width:100px" /></td>
				<td align="right" nowrap="nowrap">[[.nationality.]]</td>
				<td>&nbsp;</td>
				<td nowrap="nowrap"><select name="nationality" id="nationality" style="width:100px" onchange="SearchTravellerForm.submit();"></select></td>
				<td align="right" nowrap="nowrap"><input type="submit" value="[[.search.]]" style="width:100px;" /></td>
				<td>&nbsp;</td>
				<td nowrap="nowrap">&nbsp;</td>
				<td nowrap="nowrap" style="display:none;"><input type="button" value="[[.add_travller.]]" /></td>
				</tr><tr>
				  <td align="right" nowrap>[[.arrival_date.]]</td>
				  <td>:</td>
				<td nowrap>
						<input name="arrival_time" type="text" id="arrival_time" style="width:100px" /></td>
				<td align="right" nowrap="nowrap">[[.group.]]</td>
				<td>:</td>
				<td nowrap="nowrap"><select name="reservation_id" id="reservation_id" style="width:100px" onchange="SearchTravellerForm.submit();"></select>                </td>
				<td align="right" nowrap="nowrap">[[.arrival_room_today.]]</td>
				<td>:</td>
				<td nowrap="nowrap"><select name="reservation_room_id" id="reservation_room_id" style="width:100px" onchange="SearchTravellerForm.submit();"></select>
                </td>
				<td nowrap><input  name="update_to_pa18" type="submit" value="[[.update_to_pa18.]]"  style="width:100px;"/></td>
				<td nowrap><input name="export_file_excel" type="submit" value="[[.export_file_excel.]]" style="white-100px;"/></td>
				<td nowrap>&nbsp;</td>
				
			  </tr>
			</table>
             <input name="id_check_box" type="hidden" id="id_check_box" />
		</fieldset><br />
		<div class="content">
		<table width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#C6E2FF">
		<tr class="table-header">
			  <th width="1%" title="[[.check_all.]]">[[.no.]]</th>
				<th align="left" nowrap ><a href="<?php echo URL::build_current(((URL::get('order_by')=='traveller.last_name' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'traveller.last_name'));?>" title="[[.sort.]]">
				  <?php if(URL::get('order_by')=='traveller.last_name') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
			    [[.full_name.]] </a></th>
                <th align="left" nowrap="nowrap" >[[.gender.]]</th>
                <th align="left" nowrap="nowrap" >[[.birth_date.]]</th>
                <th align="left" nowrap="nowrap" >XDNS</th>
				<th align="left" nowrap="nowrap" >[[.nationality.]]</th>
				<th align="left" nowrap >[[.address.]]</th>
                <th align="left" nowrap >[[.occupation.]]</th>
                <th align="left" nowrap >[[.room.]]</th>
                <th align="left" nowrap="nowrap" > <a href="<?php echo URL::build_current(((URL::get('order_by')=='time_in' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'time_in'));?>" title="[[.sort.]]">
                  <?php if(URL::get('order_by')=='time_in') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
				  [[.date_in.]]</a></th>
				<th align="left" nowrap >
					<a href="<?php echo URL::build_current(((URL::get('order_by')=='time_out' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'time_out'));?>" title="[[.sort.]]">
					<?php if(URL::get('order_by')=='time_out') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>[[.date_out.]]</a></th>
				<th align="left" nowrap="nowrap" >[[.hour_in.]]</th>
                <th align="left" nowrap="nowrap" >[[.hour_out.]]</th>
                <th align="left" nowrap="nowrap" >[[.is_vietnamese.]]</th>
                <th align="left" nowrap="nowrap" ><a href="<?php echo URL::build_current(((URL::get('order_by')=='traveller.passport' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'traveller.passport'));?>" title="[[.sort.]]">
                  <?php if(URL::get('order_by')=='traveller.passport') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
				  [[.passport.]]</a></th>
				<th width="1%"><input type="checkbox" id="all_item_check_box"></th>
				<th align="left" nowrap >
					[[.note.]]</th>
				<?php if(User::can_edit(false,ANY_CATEGORY)){?><th>[[.edit.]]</th><?php }
				if(User::can_delete(false,ANY_CATEGORY)){?><th>[[.delete.]]</th><?php }?></tr>
			<?php $i=1;?><!--LIST:items-->
			<tr <?php if($i%2==0){echo 'class="row-even"';}else{echo 'class="row-odd"';}?> id="Traveller_tr_[[|items.id|]]">
				<td align="right" nowrap="nowrap" onclick="location='<?php echo URL::build_current();?>&amp;id=[[|items.id|]]';">[[|items.i|]]</td>
				<td align="left" nowrap onclick="location='<?php echo URL::build_current();?>&id=[[|items.id|]]';"> [[|items.first_name|]] [[|items.last_name|]]</td>
                <td nowrap align="left">[[|items.gender|]]</td>
                <td nowrap align="left">[[|items.birth_date|]]</td>
                <td nowrap align="center">[[|items.birth_date_correct|]]</td>
				<td align="left" onclick="location='<?php echo URL::build_current();?>&amp;id=[[|items.id|]]';"><span style="cursor:text">[[|items.nationality|]]</span></td>
				<td nowrap align="left">[[|items.address|]]</td>
                <td nowrap align="left">[[|items.occupation|]]</td>
                <td nowrap align="center" onclick="location='<?php echo URL::build_current();?>&id=[[|items.id|]]';">[[|items.room_name|]]([[|items.status|]])</td>
                <td align="left" nowrap="nowrap" onclick="location='<?php echo URL::build_current();?>&amp;id=[[|items.id|]]';"> [[|items.time_in|]]</td>
				<td nowrap align="left" onclick="location='<?php echo URL::build_current();?>&id=[[|items.id|]]';">[[|items.time_out|]]</td>
                <td align="left" nowrap="nowrap" onclick="location='<?php echo URL::build_current();?>&amp;id=[[|items.id|]]';"> [[|items.hour_in|]]</td>
				<td nowrap align="left" onclick="location='<?php echo URL::build_current();?>&id=[[|items.id|]]';">[[|items.hour_out|]]</td>
				<td align="center" nowrap="nowrap" onclick="location='<?php echo URL::build_current();?>&amp;id=[[|items.id|]]';"><span style="cursor:text">[[|items.is_vn|]]</span></td>
                <td align="left" nowrap="nowrap" style="cursor:text"> [[|items.passport|]]</td>
                <td align="right" nowrap="nowrap"><input name="item_check_box[]" type="checkbox" class="item-check-box" value="[[|items.id|]]"></td>
				<td nowrap align="left" onclick="location='<?php echo URL::build_current();?>&id=[[|items.id|]]';">
					[[|items.note|]]</td>
				<?php  if(User::can_edit(false,ANY_CATEGORY)){ ?>
                <td nowrap width="15" <?php echo (![[=items.inputed_pa18_info=]])?' bgcolor="#FF0000"':'';?>>
					<a href="<?php echo Url::build_current(array( 'reservation_id','cmd'=>'edit','id'=>[[=items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/edit.gif" alt="[[.edit.]]" border="0"></a></td>
				<?php }
				if(User::can_delete(false,ANY_CATEGORY)){ ?>
                <td nowrap width="15">
					<a href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>[[=items.id=]])); ?>"><img src="packages/core/skins/default/images/buttons/delete.gif" alt="[[.delete.]]" border="0"></a></td>
				<?php }?>
                </tr>
			<?php $i++;?><!--/LIST:items-->
	  </table>
	  <input name="action" type="hidden" id="action">
	  </form>
	  </div>
	</td>
</tr>
</table>
<div class="paging">[[|paging|]]</div>
<script>
	var valu = '';
	jQuery("#all_item_check_box").click(function (){
		var check  = this.checked;
		jQuery(".item-check-box").each(function(){
			this.checked = check;
			valu += this.value+',';
		});
		jQuery("#id_check_box").val(valu.substr(0,valu.length - 1));
		valu = '';
		//alert(jQuery("#id_check_box").val());
	});
	jQuery(".item-check-box").click(function(){	
		jQuery(".item-check-box").each(function(){
			var checkbox = this.checked;
			if(checkbox == true){
				valu += this.value+',';
			}
		});
		jQuery("#id_check_box").val(valu.substr(0,valu.length - 1));
		valu = '';
		//alert(jQuery("#id_check_box").val());
	});	
	function checkNationality(){
		if(!jQuery('#nationality').val()){
			alert('[[.please_select_nationality.]]');
			jQuery('#nationality').focus()
			return false;
		}
	}
	jQuery("#arrival_time").datepicker();
</script>
