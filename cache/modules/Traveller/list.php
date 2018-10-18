<script>
	traveller_array_items = {
		'length':'<?php echo sizeof($this->map['items']);?>'
<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
,'<?php echo $this->map['items']['current']['i'];?>':'<?php echo $this->map['items']['current']['id'];?>'
<?php }}unset($this->map['items']['current']);} ?>
	}
</script>
<table cellpadding="15" cellspacing="0" width="100%" border="0" class="table-bound">
		<tr>
        	<td width="90%" class="form-title"><?php echo Portal::language('traveller_list');?></td>
        </tr>
</table> 
<table bgcolor="#FFFFFF" cellspacing="0" width="100%">
<tr>
	<td width="100%" align="center" > 
		<fieldset>
			<legend class="title"><?php echo Portal::language('search');?></legend>
			<form name="SearchTravellerForm" method="post">
			<table border="0" cellspacing="0" cellpadding="2">
				<tr><td align="right" nowrap><?php echo Portal::language('full_name');?></td>
				<td>:</td>
				<td nowrap>
						<input  name="full_name" id="full_name" style="width:100px" / type ="text" value="<?php echo String::html_normalize(URL::get('full_name'));?>"></td><td align="right" nowrap><?php echo Portal::language('passport');?></td>
				<td>:</td>
				<td nowrap>
						<input  name="passport" id="passport" style="width:100px" / type ="text" value="<?php echo String::html_normalize(URL::get('passport'));?>"></td>
				<td align="right" nowrap="nowrap"><?php echo Portal::language('nationality');?></td>
				<td>&nbsp;</td>
				<td nowrap="nowrap"><select  name="nationality" id="nationality" style="width:100px" onchange="SearchTravellerForm.submit();"><?php
					if(isset($this->map['nationality_list']))
					{
						foreach($this->map['nationality_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('nationality',isset($this->map['nationality'])?$this->map['nationality']:''))
                    echo "<script>$('nationality').value = \"".addslashes(URL::get('nationality',isset($this->map['nationality'])?$this->map['nationality']:''))."\";</script>";
                    ?>
	</select></td>
				<td align="right" nowrap="nowrap"><input type="submit" value="<?php echo Portal::language('search');?>" style="width:100px;" /></td>
				<td>&nbsp;</td>
				<td nowrap="nowrap">&nbsp;</td>
				<td nowrap="nowrap" style="display:none;"><input type="button" value="<?php echo Portal::language('add_travller');?>" /></td>
				</tr><tr>
				  <td align="right" nowrap><?php echo Portal::language('arrival_date');?></td>
				  <td>:</td>
				<td nowrap>
						<input  name="arrival_time" id="arrival_time" style="width:100px" / type ="text" value="<?php echo String::html_normalize(URL::get('arrival_time'));?>"></td>
				<td align="right" nowrap="nowrap"><?php echo Portal::language('group');?></td>
				<td>:</td>
				<td nowrap="nowrap"><select  name="reservation_id" id="reservation_id" style="width:100px" onchange="SearchTravellerForm.submit();"><?php
					if(isset($this->map['reservation_id_list']))
					{
						foreach($this->map['reservation_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('reservation_id',isset($this->map['reservation_id'])?$this->map['reservation_id']:''))
                    echo "<script>$('reservation_id').value = \"".addslashes(URL::get('reservation_id',isset($this->map['reservation_id'])?$this->map['reservation_id']:''))."\";</script>";
                    ?>
	</select>                </td>
				<td align="right" nowrap="nowrap"><?php echo Portal::language('arrival_room_today');?></td>
				<td>:</td>
				<td nowrap="nowrap"><select  name="reservation_room_id" id="reservation_room_id" style="width:100px" onchange="SearchTravellerForm.submit();"><?php
					if(isset($this->map['reservation_room_id_list']))
					{
						foreach($this->map['reservation_room_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('reservation_room_id',isset($this->map['reservation_room_id'])?$this->map['reservation_room_id']:''))
                    echo "<script>$('reservation_room_id').value = \"".addslashes(URL::get('reservation_room_id',isset($this->map['reservation_room_id'])?$this->map['reservation_room_id']:''))."\";</script>";
                    ?>
	</select>
                </td>
				<td nowrap><input  name="update_to_pa18" type="submit" value="<?php echo Portal::language('update_to_pa18');?>"  style="width:100px;"/></td>
				<td nowrap><input name="export_file_excel" type="submit" value="<?php echo Portal::language('export_file_excel');?>" style="white-100px;"/></td>
				<td nowrap>&nbsp;</td>
				
			  </tr>
			</table>
             <input  name="id_check_box" id="id_check_box" / type ="hidden" value="<?php echo String::html_normalize(URL::get('id_check_box'));?>">
		</fieldset><br />
		<div class="content">
		<table width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#C6E2FF">
		<tr class="table-header">
			  <th width="1%" title="<?php echo Portal::language('check_all');?>"><?php echo Portal::language('no');?></th>
				<th align="left" nowrap ><a href="<?php echo URL::build_current(((URL::get('order_by')=='traveller.last_name' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'traveller.last_name'));?>" title="<?php echo Portal::language('sort');?>">
				  <?php if(URL::get('order_by')=='traveller.last_name') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
			    <?php echo Portal::language('full_name');?> </a></th>
                <th align="left" nowrap="nowrap" ><?php echo Portal::language('gender');?></th>
                <th align="left" nowrap="nowrap" ><?php echo Portal::language('birth_date');?></th>
                <th align="left" nowrap="nowrap" >XDNS</th>
				<th align="left" nowrap="nowrap" ><?php echo Portal::language('nationality');?></th>
				<th align="left" nowrap ><?php echo Portal::language('address');?></th>
                <th align="left" nowrap ><?php echo Portal::language('occupation');?></th>
                <th align="left" nowrap ><?php echo Portal::language('room');?></th>
                <th align="left" nowrap="nowrap" > <a href="<?php echo URL::build_current(((URL::get('order_by')=='time_in' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'time_in'));?>" title="<?php echo Portal::language('sort');?>">
                  <?php if(URL::get('order_by')=='time_in') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
				  <?php echo Portal::language('date_in');?></a></th>
				<th align="left" nowrap >
					<a href="<?php echo URL::build_current(((URL::get('order_by')=='time_out' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'time_out'));?>" title="<?php echo Portal::language('sort');?>">
					<?php if(URL::get('order_by')=='time_out') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?><?php echo Portal::language('date_out');?></a></th>
				<th align="left" nowrap="nowrap" ><?php echo Portal::language('hour_in');?></th>
                <th align="left" nowrap="nowrap" ><?php echo Portal::language('hour_out');?></th>
                <th align="left" nowrap="nowrap" ><?php echo Portal::language('is_vietnamese');?></th>
                <th align="left" nowrap="nowrap" ><a href="<?php echo URL::build_current(((URL::get('order_by')=='traveller.passport' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'traveller.passport'));?>" title="<?php echo Portal::language('sort');?>">
                  <?php if(URL::get('order_by')=='traveller.passport') echo '<img src="skins/default/images/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
				  <?php echo Portal::language('passport');?></a></th>
				<th width="1%"><input type="checkbox" id="all_item_check_box"></th>
				<th align="left" nowrap >
					<?php echo Portal::language('note');?></th>
				<?php if(User::can_edit(false,ANY_CATEGORY)){?><th><?php echo Portal::language('edit');?></th><?php }
				if(User::can_delete(false,ANY_CATEGORY)){?><th><?php echo Portal::language('delete');?></th><?php }?></tr>
			<?php $i=1;?><?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current'] = &$item2;?>
			<tr <?php if($i%2==0){echo 'class="row-even"';}else{echo 'class="row-odd"';}?> id="Traveller_tr_<?php echo $this->map['items']['current']['id'];?>">
				<td align="right" nowrap="nowrap" onclick="location='<?php echo URL::build_current();?>&amp;id=<?php echo $this->map['items']['current']['id'];?>';"><?php echo $this->map['items']['current']['i'];?></td>
				<td align="left" nowrap onclick="location='<?php echo URL::build_current();?>&id=<?php echo $this->map['items']['current']['id'];?>';"> <?php echo $this->map['items']['current']['first_name'];?> <?php echo $this->map['items']['current']['last_name'];?></td>
                <td nowrap align="left"><?php echo $this->map['items']['current']['gender'];?></td>
                <td nowrap align="left"><?php echo $this->map['items']['current']['birth_date'];?></td>
                <td nowrap align="center"><?php echo $this->map['items']['current']['birth_date_correct'];?></td>
				<td align="left" onclick="location='<?php echo URL::build_current();?>&amp;id=<?php echo $this->map['items']['current']['id'];?>';"><span style="cursor:text"><?php echo $this->map['items']['current']['nationality'];?></span></td>
				<td nowrap align="left"><?php echo $this->map['items']['current']['address'];?></td>
                <td nowrap align="left"><?php echo $this->map['items']['current']['occupation'];?></td>
                <td nowrap align="center" onclick="location='<?php echo URL::build_current();?>&id=<?php echo $this->map['items']['current']['id'];?>';"><?php echo $this->map['items']['current']['room_name'];?>(<?php echo $this->map['items']['current']['status'];?>)</td>
                <td align="left" nowrap="nowrap" onclick="location='<?php echo URL::build_current();?>&amp;id=<?php echo $this->map['items']['current']['id'];?>';"> <?php echo $this->map['items']['current']['time_in'];?></td>
				<td nowrap align="left" onclick="location='<?php echo URL::build_current();?>&id=<?php echo $this->map['items']['current']['id'];?>';"><?php echo $this->map['items']['current']['time_out'];?></td>
                <td align="left" nowrap="nowrap" onclick="location='<?php echo URL::build_current();?>&amp;id=<?php echo $this->map['items']['current']['id'];?>';"> <?php echo $this->map['items']['current']['hour_in'];?></td>
				<td nowrap align="left" onclick="location='<?php echo URL::build_current();?>&id=<?php echo $this->map['items']['current']['id'];?>';"><?php echo $this->map['items']['current']['hour_out'];?></td>
				<td align="center" nowrap="nowrap" onclick="location='<?php echo URL::build_current();?>&amp;id=<?php echo $this->map['items']['current']['id'];?>';"><span style="cursor:text"><?php echo $this->map['items']['current']['is_vn'];?></span></td>
                <td align="left" nowrap="nowrap" style="cursor:text"> <?php echo $this->map['items']['current']['passport'];?></td>
                <td align="right" nowrap="nowrap"><input name="item_check_box[]" type="checkbox" class="item-check-box" value="<?php echo $this->map['items']['current']['id'];?>"></td>
				<td nowrap align="left" onclick="location='<?php echo URL::build_current();?>&id=<?php echo $this->map['items']['current']['id'];?>';">
					<?php echo $this->map['items']['current']['note'];?></td>
				<?php  if(User::can_edit(false,ANY_CATEGORY)){ ?>
                <td nowrap width="15" <?php echo (!$this->map['items']['current']['inputed_pa18_info'])?' bgcolor="#FF0000"':'';?>>
					<a href="<?php echo Url::build_current(array( 'reservation_id','cmd'=>'edit','id'=>$this->map['items']['current']['id'])); ?>"><img src="packages/core/skins/default/images/buttons/edit.gif" alt="<?php echo Portal::language('edit');?>" border="0"></a></td>
				<?php }
				if(User::can_delete(false,ANY_CATEGORY)){ ?>
                <td nowrap width="15">
					<a href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>$this->map['items']['current']['id'])); ?>"><img src="packages/core/skins/default/images/buttons/delete.gif" alt="<?php echo Portal::language('delete');?>" border="0"></a></td>
				<?php }?>
                </tr>
			<?php $i++;?><?php }}unset($this->map['items']['current']);} ?>
	  </table>
	  <input  name="action" id="action" type ="hidden" value="<?php echo String::html_normalize(URL::get('action'));?>">
	  <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
	  </div>
	</td>
</tr>
</table>
<div class="paging"><?php echo $this->map['paging'];?></div>
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
			alert('<?php echo Portal::language('please_select_nationality');?>');
			jQuery('#nationality').focus()
			return false;
		}
	}
	jQuery("#arrival_time").datepicker();
</script>
