<span style="display:none;">
	<span id="mi_traveller_sample">	
		<span id="input_group_#xxxx#">
			<input name="mi_traveller[#xxxx#][id]" type="hidden" id="id_#xxxx#" />
			<input name="mi_traveller[#xxxx#][traveller_id_]" type="hidden" id="traveller_id__#xxxx#" />
			<input name="mi_traveller[#xxxx#][traveller_room_id]" type="hidden" id="traveller_room_id_#xxxx#" />
            <input name="mi_traveller[#xxxx#][reservation_room_id]" type="hidden" id="reservation_room_id_#xxxx#" />
            <input name="mi_traveller[#xxxx#][reservation_traveller_id]" type="hidden" id="reservation_traveller_id_#xxxx#" />
            <input name="mi_traveller[#xxxx#][status]" type="hidden" id="status_#xxxx#" />
            <input name="mi_traveller[#xxxx#][check_out]" type="hidden" id="check_out_#xxxx#" />
			<table width="100%" border="0" cellspacing="0" cellpadding="2" bgcolor="#FFFFFF" id="table_#xxxx#">
			  <tr id="tr_#xxxx#">
			  	<td class="multi-input"><span class="multi-input" id="index_#xxxx#" style="width:33px;font-size:10px;color:#F90;">&nbsp;</span></td>
				<td class="multi-input"><input  name="mi_traveller[#xxxx#][room_level]" type="text" style="width:56px;font-size:10px;" class="select-room" id="room_level_#xxxx#" readonly="readonly"/></td>
                <td class="multi-input"><input  name="mi_traveller[#xxxx#][mi_traveller_room_name]" type="text" id="mi_traveller_room_name_#xxxx#" readonly="readonly" class="select-room" style="width:50px;" onclick="if(checkRoomOut($('traveller_room_id_#xxxx#').value) && $('id_#xxxx#').value){alert('Phong da check out. Ban khong duoc quyen sua');}else{<?php if(!Url::get('rr_id')){?>display_room_table(this,'#xxxx#');<?php }?>}"></td>
				<td class="multi-input"><input  name="mi_traveller[#xxxx#][first_name]" style="width:100px; text-transform:uppercase;" type="text" id="first_name_#xxxx#"/></td>
				<td class="multi-input"><input  name="mi_traveller[#xxxx#][last_name]" style="width:80px; text-transform:uppercase;" type="text" id="last_name_#xxxx#"/></td>
				<td class="multi-input"><input  name="mi_traveller[#xxxx#][passport]" style="width:80px; text-transform:uppercase;" type="text" id="passport_#xxxx#" onchange="get_traveller('#xxxx#');"/></td>
                <td class="multi-input"><input  name="mi_traveller[#xxxx#][transit]" style="width:15px;" type="checkbox" id="transit_#xxxx#" onClick="if(jQuery('#transit_#xxxx#').attr('checked')==true){jQuery('#passport_#xxxx#').attr('readonly','readonly');jQuery('#passport_#xxxx#').css('background','#f1f1f1');}else{jQuery('#passport_#xxxx#').removeAttr('readonly');jQuery('#passport_#xxxx#').css('background','#FFFFFF');}"></td>
                <td class="multi-input"><input  name="mi_traveller[#xxxx#][visa]" style="width:80px; text-transform:uppercase;" type="text" id="visa_#xxxx#" /></td>
                <td class="multi-input"><input  name="mi_traveller[#xxxx#][expire_date_of_visa]" style="width:80px;" type="text" id="expire_date_of_visa_#xxxx#" /></td>                
				<td class="multi-input"><select   name="mi_traveller[#xxxx#][gender]" id="gender_#xxxx#" style="width:67px; text-transform:uppercase;" class="gender" >
                    <option value="2" <?php (isset($_REQUEST['mi_traveller']['#xxxx#']['gender']) and $_REQUEST['mi_traveller']['#xxxx#']['gender']==2)?'selected="selected"':''?>><?php echo Portal::language('male');?>/<?php echo Portal::language('female');?></option>
                    <option value="1" <?php (isset($_REQUEST['mi_traveller']['#xxxx#']['gender']) and $_REQUEST['mi_traveller']['#xxxx#']['gender']==1)?'selected="selected"':''?>><?php echo Portal::language('male');?></option>
                    <option value="0" <?php (isset($_REQUEST['mi_traveller']['#xxxx#']['gender']) and $_REQUEST['mi_traveller']['#xxxx#']['gender']==0)?'selected="selected"':''?>><?php echo Portal::language('female');?></option>
                </select></td>
				<td class="multi-input"><input  name="mi_traveller[#xxxx#][birth_date]" style="width:60px;" type="text" id="birth_date_#xxxx#" /></td>
				<td class="multi-input"><input  name="mi_traveller[#xxxx#][nationality_id]" style="width:30px; text-transform:uppercase;"  id="nationality_id_#xxxx#" class="nationality_id" AUTOCOMPLETE=OFF onchange="updateNationality('#xxxx#');" onblur="updateNationality('#xxxx#');"><input  name="mi_traveller[#xxxx#][nationality_name]" style="width:95px; text-transform:uppercase;" readonly="readonly" class="readonly nationality_name"  id="nationality_name_#xxxx#" AUTOCOMPLETE=OFF></td>
                <td class="multi-input"><select   name="mi_traveller[#xxxx#][traveller_level_id]" style="width:84px; text-transform:uppercase;" id="traveller_level_id_#xxxx#" class="reservation_type" ><?php echo $this->map['traveller_level_options'];?></select></td>
                <td class="multi-input"><input name="mi_traveller[#xxxx#][is_child]" type="checkbox" id="is_child_#xxxx#" style="width:15px;" /></td>
                <td class="multi-input"><?php 
				if((User::can_delete(false,ANY_CATEGORY)))
				{?><a href="#" onclick="if(confirm('<?php echo Portal::language('are_you_sure');?>')){ check_is_folio(#xxxx#);}" style="cursor:pointer;"><i class="fa fa-times-circle w3-text-red" style="font-size: 18px;"></i></a>
				
				<?php
				}
				?></td >
                <td class="multi-input">
					<span style="display:none;" id="expand_guest_#xxxx#"></span>
                    <img id="expand_guest_img_#xxxx#" style="cursor:pointer;" src="packages/core/skins/default/images/buttons/node_close.gif" width="20" onclick="expandShortenTraveller('#xxxx#');">
				</td>
			  </tr>
			   <tr>
                <td class="multi-input" colspan="12" style="padding-left:0px;" id="extra_guest_#xxxx#">
                    <?php $herf = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=update_traveller&cmd=checkout_guest&id='.Url::get('id').'&rr_id='.Url::get('r_r_id').'';?>                
                	<table cellpadding="3" cellspacing="0" width="100%">
                    	<tr>
                        	<td style="width: 100px;"><?php echo Portal::language('telephone_number');?>: </td>
                        	<td><input  name="mi_traveller[#xxxx#][phone]" style="width:115px;" type="text" id="phone_#xxxx#" /></td>
                        	<td style="width: 100px;"><?php echo Portal::language('email');?>: </td>
                        	<td><input  name="mi_traveller[#xxxx#][email]" style="width:115px;"  id="email_#xxxx#" /></td>
                        	<td style="width: 100px;"><?php echo Portal::language('address');?>: </td>
                            <td colspan="2"><input  name="mi_traveller[#xxxx#][address]" style="width:250px;"  id="address_#xxxx#" /></td>
                            <td><a href="#" onclick="var conf = confirm('<?php echo Portal::language('are_you_ok');?>'); if(conf){window.location='<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/'.Url::$root.'form.php?block_id='.Module::block_id().'&cmd=checkout_guest';?>&r_id=<?php echo Url::get('r_id');?>&rr_id='+jQuery('#reservation_room_id_#xxxx#').val()+'&traveller_id='+jQuery('#reservation_traveller_id_#xxxx#').val()+'<?php echo PORTAL_ID;?>';}"><input  name="mi_traveller[#xxxx#][checkout_guest]" type="button" id="checkout_guest_#xxxx#" value="<?php echo Portal::language('checkout_guest');?>" style="width:90px; height:23px;"/></a> </td>
                        </tr>
                    	<tr>  
                        	<td style="width: 100px;"><?php echo Portal::language('arrival_time');?></td>
                            <td><input name="mi_traveller[#xxxx#][arrival_hour]" type="text" id="arrival_hour_#xxxx#" style="width:35px;" /> - <input name="mi_traveller[#xxxx#][traveller_arrival_date]" type="text" id="traveller_arrival_date_#xxxx#" style="width:70px;" readonly/></td>
                        	<td style="width: 100px;"><?php echo Portal::language('departure_time');?></td>
                            <td><input name="mi_traveller[#xxxx#][departure_hour]" type="text" id="departure_hour_#xxxx#" style="width:35px;" /> - <input name="mi_traveller[#xxxx#][traveller_departure_date]" type="text" id="traveller_departure_date_#xxxx#" style="width:70px;" readonly/></td>
                        	<!--Oanh comment
                            <td></td>
                            <td></td>-->
                            
                            <!-- End Oanh -->
                            <!-- Start : Ninh thêm bỏ phần đổi phòng với những phòng chưa gán số phòng -->
                            <td><?php echo Portal::language('target_of_entry');?>:</td>
                            <td><select  name="mi_traveller[#xxxx#][entry_target]" id="entry_target_#xxxx#" style="width:250px" ><?php
					if(isset($this->map['mi_traveller[#xxxx#][entry_target]_list']))
					{
						foreach($this->map['mi_traveller[#xxxx#][entry_target]_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('mi_traveller[#xxxx#][entry_target]',isset($this->map['mi_traveller[#xxxx#][entry_target]'])?$this->map['mi_traveller[#xxxx#][entry_target]']:''))
                    echo "<script>$('mi_traveller[#xxxx#][entry_target]').value = \"".addslashes(URL::get('mi_traveller[#xxxx#][entry_target]',isset($this->map['mi_traveller[#xxxx#][entry_target]'])?$this->map['mi_traveller[#xxxx#][entry_target]']:''))."\";</script>";
                    ?>
	<?php echo $this->map['entry_target_options'];?></select></td>
                            <?php 
                                if(isset($_REQUEST['room_empty']) && $_REQUEST['room_empty'] == 'true')
                                {
                                    
                                }elseif(isset($_REQUEST['room_empty']) && $_REQUEST['room_empty'] == ''){
                            ?>
                                <td><input  name="mi_traveller[#xxxx#][change_guest]" type="button" id="change_guest_#xxxx#" value="<?php echo Portal::language('change_room');?>" style="width:90px; height:23px;" onclick="window.location='<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/'.Url::$root.'form.php?block_id='.Module::block_id().'&cmd=change_guest';?>&rr_id='+jQuery('#reservation_room_id_#xxxx#').val()+'&traveller_id='+jQuery('#reservation_traveller_id_#xxxx#').val()+'&portal_id=<?php echo PORTAL_ID;?>';" /> </td>                            
                            <?php } ?>
                            <!-- End : Ninh thêm bỏ phần đổi phòng với những phòng chưa gán số phòng -->
                        </tr>
                        <tr>
                            <td style="width: 100px;"><?php echo Portal::language('is_vn');?></td>
                            <!--<td><select  name="is_vn" id="is_vn_#xxxx#" style="width: 140px; "><?php
					if(isset($this->map['is_vn_list']))
					{
						foreach($this->map['is_vn_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('is_vn',isset($this->map['is_vn'])?$this->map['is_vn']:''))
                    echo "<script>$('is_vn').value = \"".addslashes(URL::get('is_vn',isset($this->map['is_vn'])?$this->map['is_vn']:''))."\";</script>";
                    ?>
	</select></td>-->
                            <td >
                                <select  name="mi_traveller[#xxxx#][is_vn]" id="is_vn_#xxxx#" style="float: left;width:115px;"><?php
					if(isset($this->map['mi_traveller[#xxxx#][is_vn]_list']))
					{
						foreach($this->map['mi_traveller[#xxxx#][is_vn]_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('mi_traveller[#xxxx#][is_vn]',isset($this->map['mi_traveller[#xxxx#][is_vn]'])?$this->map['mi_traveller[#xxxx#][is_vn]']:''))
                    echo "<script>$('mi_traveller[#xxxx#][is_vn]').value = \"".addslashes(URL::get('mi_traveller[#xxxx#][is_vn]',isset($this->map['mi_traveller[#xxxx#][is_vn]'])?$this->map['mi_traveller[#xxxx#][is_vn]']:''))."\";</script>";
                    ?>
	
                                <option id="0"></option>
                                <!--
                                <option value="0" <?php (isset($_REQUEST['mi_traveller']['#xxxx#']['is_vn']) and $_REQUEST['mi_traveller']['#xxxx#']['is_vn']==0)?'selected="selected"':''?>><?php echo Portal::language('Alien');?></option>
                                <option value="1" <?php (isset($_REQUEST['mi_traveller']['#xxxx#']['is_vn']) and $_REQUEST['mi_traveller']['#xxxx#']['is_vn']==1)?'selected="selected"':''?>><?php echo Portal::language('Overseas_Vietnamese');?></option>
                                -->
                                <option value="2" <?php (isset($_REQUEST['mi_traveller']['#xxxx#']['is_vn']) and $_REQUEST['mi_traveller']['#xxxx#']['is_vn']==2)?'selected="selected"':''?>><?php echo Portal::language('Viet_nam');?></option>
                                <option value="3" <?php (isset($_REQUEST['mi_traveller']['#xxxx#']['is_vn']) and $_REQUEST['mi_traveller']['#xxxx#']['is_vn']==3)?'selected="selected"':''?>><?php echo Portal::language('Viet_nam_in_foreign');?></option>
                                 </select>
                            </td>
                            <td style="width: 100px;"><!--<?php echo Portal::language('note');?>: --></td>
                            <td colspan="6"><!--<textarea  name="mi_traveller[#xxxx#][note]" style="width:489px; text-transform:uppercase;"  id="note_#xxxx#"></textarea>--></td>
                        </tr>
                        <tr>
                            <td style="width: 100px;"><?php echo Portal::language('to_judge');?></td>
                            <td>
                                <select  name="mi_traveller[#xxxx#][to_judge]" id="to_judge_#xxxx#" style="float: left;"><?php
					if(isset($this->map['mi_traveller[#xxxx#][to_judge]_list']))
					{
						foreach($this->map['mi_traveller[#xxxx#][to_judge]_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('mi_traveller[#xxxx#][to_judge]',isset($this->map['mi_traveller[#xxxx#][to_judge]'])?$this->map['mi_traveller[#xxxx#][to_judge]']:''))
                    echo "<script>$('mi_traveller[#xxxx#][to_judge]').value = \"".addslashes(URL::get('mi_traveller[#xxxx#][to_judge]',isset($this->map['mi_traveller[#xxxx#][to_judge]'])?$this->map['mi_traveller[#xxxx#][to_judge]']:''))."\";</script>";
                    ?>
	
                                <option id="0"></option>
                                <option value="1" <?php (isset($_REQUEST['mi_traveller']['#xxxx#']['to_judge']) and $_REQUEST['mi_traveller']['#xxxx#']['to_judge']==1)?'selected="selected"':''?>>1</option>
                                <option value="2" <?php (isset($_REQUEST['mi_traveller']['#xxxx#']['to_judge']) and $_REQUEST['mi_traveller']['#xxxx#']['to_judge']==2)?'selected="selected"':''?>>2</option>
                                <option value="3" <?php (isset($_REQUEST['mi_traveller']['#xxxx#']['to_judge']) and $_REQUEST['mi_traveller']['#xxxx#']['to_judge']==3)?'selected="selected"':''?>>3</option>
                                <option value="4" <?php (isset($_REQUEST['mi_traveller']['#xxxx#']['to_judge']) and $_REQUEST['mi_traveller']['#xxxx#']['to_judge']==4)?'selected="selected"':''?>>4</option>
                                <option value="5" <?php (isset($_REQUEST['mi_traveller']['#xxxx#']['to_judge']) and $_REQUEST['mi_traveller']['#xxxx#']['to_judge']==5)?'selected="selected"':''?>>5</option>
                                </select>
                                <img style="height: 21px; width: auto; float: left;" src="resources\default\sao.png" />
                            </td>
                            <td style="width: 100px;"><?php echo Portal::language('note');?>: </td>
                            <td colspan="6"><textarea  name="mi_traveller[#xxxx#][note]" style="width:489px; text-transform:uppercase;"  id="note_#xxxx#"></textarea></td>
                        </tr>
                    	<tr>
                        	<td style="width: 100px;"><?php echo Portal::language('flight_code');?>: </td>
                        	<td><input  name="mi_traveller[#xxxx#][flight_code]" style="width:115px; text-transform:uppercase;" type="text" id="flight_code_#xxxx#" /></td>
                        	<td style="width: 100px;"><?php echo Portal::language('flight_arrival_time');?>: </td>
                        	<td><input name="mi_traveller[#xxxx#][flight_arrival_hour]" type="text" id="flight_arrival_hour_#xxxx#" style="width:35px;" /> - <input  name="mi_traveller[#xxxx#][flight_arrival_date]" style="width:70px;" type="text" id="flight_arrival_date_#xxxx#" /></td>
							<td style="width: 100px;"><?php echo Portal::language('car_note_arrival');?>: </td>
                            <td ><input  name="mi_traveller[#xxxx#][car_note_arrival]" style="width:250px; text-transform:uppercase;"  id="car_note_arrival_#xxxx#" /></td>
                            <td>
                                <div>
							         <input  name="mi_traveller[#xxxx#][pickup]" type="checkbox" id="pickup_#xxxx#" value="1" /><label for="pickup_#xxxx#"><?php echo Portal::language('pick_up');?></label>
							    </div>
							    <div>
							         <input  name="mi_traveller[#xxxx#][pickup_foc]" type="checkbox" id="pickup_foc_#xxxx#" value="1" /><label for="pickup_foc_#xxxx#"><?php echo Portal::language('pickup_foc');?></label>
	          					</div>
							</td>
                        </tr>
                    	<tr>
                        	<td style="width: 100px;"><?php echo Portal::language('flight_code_departure');?>: </td>
                        	<td><input  name="mi_traveller[#xxxx#][flight_code_departure]" style="width:115px; text-transform:uppercase;" type="text" id="flight_code_departure_#xxxx#" /></td>
                        	<td style="width: 100px;"><?php echo Portal::language('flight_departure_time');?>: </td>
                        	<td><input name="mi_traveller[#xxxx#][flight_departure_hour]" type="text" id="flight_departure_hour_#xxxx#" style="width:35px;" /> - <input  name="mi_traveller[#xxxx#][flight_departure_date]" style="width:70px;" type="text" id="flight_departure_date_#xxxx#" /></td>
                        	<td style="width: 100px;"><?php echo Portal::language('car_note_departure');?>: </td>
                            <td><input  name="mi_traveller[#xxxx#][car_note_departure]" style="width:250px; text-transform:uppercase;"  id="car_note_departure_#xxxx#" /></td>
                            <td>
							<div>
							<input  name="mi_traveller[#xxxx#][see_off]" type="checkbox" id="see_off_#xxxx#" value="1" /><label for="see_off_#xxxx#"><?php echo Portal::language('see_off');?></label>
							</div>
							<div>
							<input  name="mi_traveller[#xxxx#][see_off_foc]" type="checkbox" id="see_off_foc_#xxxx#" value="1" /><label for="see_off_foc_#xxxx#"><?php echo Portal::language('see_off_foc');?></label>
							</div>
							</td>
                        </tr>
                        <tr>
                        	<td style="width: 100px;"><?php echo Portal::language('province');?>: </td>
                        	<td class="multi-input" style="width:116px;">
                                <select  name="mi_traveller[#xxxx#][province_id]" style="width:100%; margin-left: 2px;" id="province_id_#xxxx#"><?php
					if(isset($this->map['mi_traveller[#xxxx#][province_id]_list']))
					{
						foreach($this->map['mi_traveller[#xxxx#][province_id]_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('mi_traveller[#xxxx#][province_id]',isset($this->map['mi_traveller[#xxxx#][province_id]'])?$this->map['mi_traveller[#xxxx#][province_id]']:''))
                    echo "<script>$('mi_traveller[#xxxx#][province_id]').value = \"".addslashes(URL::get('mi_traveller[#xxxx#][province_id]',isset($this->map['mi_traveller[#xxxx#][province_id]'])?$this->map['mi_traveller[#xxxx#][province_id]']:''))."\";</script>";
                    ?>
	
                                    <option value=""></option>
                                    <?php echo $this->map['province_options'];?>
                                </select>
                            </td>
                            <td><?php echo Portal::language('competence');?></td>
                            <td ><input  name="mi_traveller[#xxxx#][competence]" style="width:115px;" type="text" id="competence_#xxxx#" /></td>
                            <td style="width: 100px;"><?php echo Portal::language('member_code');?>:</td>
                            <td class="multi-input"><input  name="mi_traveller[#xxxx#][member_code]" style="width:250px; margin-left: 2px;" type="text" id="member_code_#xxxx#" /></td>
                            <td><input  name="create_member_#xxxx#" id="create_member_#xxxx#" onchange="fun_check_create_member(this);" onclick="fun_check_create_member(this);" / type ="checkbox" value="<?php echo String::html_normalize(URL::get('create_member_#xxxx#'));?>"> <?php echo Portal::language('create_member');?></td>
                            
                        </tr>
                    </table>
                </td>
			  </tr>
			</table>
            <div style="padding: 5px;">
			<span id="detail_link_#xxxx#" class="w3-text-indigo" style="font-size:10px;"></span>
            <span id="history_link_#xxxx#" class="w3-text-red w3-hover-text-red" style="font-size:10px;"></span>
			</div>
        </span>
    </span>
</span>
<form name="UpdateTravellerForm" id="UpdateTravellerForm" onsubmit="return checkDoubleClick();" method="post">
<div style="text-align:center">  
<div style="width:1060px;margin-right:auto;margin-left:auto;">
<table cellspacing="0" cellpadding="5" width="100%" style="border:1px solid #4799FF;margin-top:10px;background:url(packages/hotel/skins/default/images/reservation_bg.jpg) repeat-x 0% 0%;" align="center">

	<tr valign="top">
		<td align="left">
			<table width="100%" cellpadding="0" cellspacing="0">
				<tr height="40">
                <form name="ExportForm" method="post"  enctype="multipart/form-data">
                <div>
					<td width="95%" nowrap style="text-transform: uppercase; font-size: 16px;" class="w3-text-indigo"><i class="fa fa-group w3-text-orange" style="font-size: 20px;"></i> <?php echo Portal::language('update_traveller');?></td>
                    <td width="1%" nowrap="nowrap">
                     <?php $hef = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=reservation&cmd=import_traveller&r_id='.Url::get('r_id');?>
                   <a target="_blank" class="w3-btn w3-gray w3-hover-green" href="<?php echo $hef;?>" style="text-decoration: none; margin-right: 5px;" ><i class="fa fa-file-excel-o"></i> <?php echo Portal::language('import_from_excel');?></a>
                    </td>
                    <!--Oanhbtk them export file excel -->                         
                    <td><input name="export_file_excel" type="submit" onclick="exportExcel()" value="<?php echo Portal::language('export_file_excel');?>" class="w3-btn w3-gray w3-hover-green" style="margin-right: 5px;"/></td>
                    <!-- End Oanhbtk -->
				  	<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%" nowrap="nowrap"><input name="save" type="submit" value="<?php echo Portal::language('Save_and_close');?>" class="w3-btn w3-orange" style="margin-right: 5px;"/></td><?php }?>
					<?php if(User::can_edit(false,ANY_CATEGORY)){?><td width="1%" nowrap="nowrap"><input name="update" type="submit" value="<?php echo Portal::language('Save_and_stay');?>" class="w3-btn w3-blue" style="margin-right: 5px;"/></td><?php }?>
					<?php if(User::can_delete(false,ANY_CATEGORY)){?><td width="1%" nowrap="nowrap"><a href="#" onclick="onBackEvent();"  class="w3-btn w3-green"><?php echo Portal::language('back');?></a></td><?php }?>
				</div>
                <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
                </tr>
		  </table>
		</td>
	</tr>
   <table id="myTable" class="tablesorter" width="100%" cellpadding="2" cellspacing="0" border="1">
	<tr valign="top">
	<td><?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><br clear="all"><?php }?>
    	<fieldset style="background:white;margin-bottom:5px;">
			<legend class="legend-title w3-text-indigo" style="width: 250px; font-weight: normal;"><?php echo Portal::language('guest_in_house');?>&nbsp;(<span id="count_number_of_guest"></span> <?php echo Portal::language('guest_per_room');?>)</legend>
            <div style="padding:3px 0px;"><?php echo Portal::language('Change_all_traveller_level');?>: <select id="change_all_traveller_id" onchange="change_all_traveller_level();">
            	<?php echo $this->map['traveller_level_options'];?>
            </select>
             <?php echo Portal::language('change_all_national');?> <input  name="changeAllNational" id="changeAllNational" onchange="updateAllNationality();" onblur="updateAllNationality();" style="width:60px; text-transform:uppercase;" type ="text" value="<?php echo String::html_normalize(URL::get('changeAllNational'));?>">
           	 </div>
			<span id="mi_traveller_all_elems">
                    <span class="multi-input-header" style="width:35px;"><?php echo Portal::language('index');?></span>
                    <span class="multi-input-header" style="width:55px;"><?php echo Portal::language('room_type');?></span>
                    <span class="multi-input-header" style="width:50px;"><?php echo Portal::language('room');?></span>
                    <span class="multi-input-header" style="width:100px;"><?php echo Portal::language('first_name');?> (*)</span>  
                    <span class="multi-input-header" style="width:80px;"><?php echo Portal::language('last_name');?> (*)</span>
                    <span class="multi-input-header" style="width:79px;"><?php echo Portal::language('passport');?> </span>
                    <span class="multi-input-header" style="width:17px;" title="<?php echo Portal::language('transit');?>">&nbsp; </span>
                    <span class="multi-input-header" style="width:79px;"><?php echo Portal::language('visa');?></span>
                    <span class="multi-input-header" style="width:80px;"><?php echo Portal::language('visa_expired');?></span>
                    <span class="multi-input-header" style="width:67px;"><?php echo Portal::language('gender');?></span>
                    <span class="multi-input-header" style="width:60px;"><?php echo Portal::language('birth_date');?></span>
                    <span class="multi-input-header" style="width:126px;"><?php echo Portal::language('nationality');?></span>
                    <span class="multi-input-header" style="width:83px;"><?php echo Portal::language('traveller_level');?> (*)</span>
                    <span class="multi-input-header" style="width:15px;" title="is_child"><i class="fa fa-child" style="font-size: 14px;"></i></span>
                    <span class="multi-input-header" style="border:0px;background:none;"></span>
                    <br clear="all"/>
			  </span>
              <input  name="traveller_delete" id="traveller_delete" / type ="hidden" value="<?php echo String::html_normalize(URL::get('traveller_delete'));?>">
              <input type="button" value="<?php echo Portal::language('add_traveller');?>" class="w3-btn w3-gray w3-hover-cyan" onclick="addnew_traveller();">
            <?php echo Portal::language('copy_traveller');?> <input  name="traveller_indexs" id="traveller_indexs" value="index1,index2,index3" onclick="if(this.value=='index1,index2,index3'){this.value='';}" onblur="if(this.value==''){this.value='index1,index2,index3';}" type ="text" value="<?php echo String::html_normalize(URL::get('traveller_indexs'));?>"> <?php echo Portal::language('quantity');?> <input  name="traveller_quantity" id="traveller_quantity" style="width:30px;" size="100" maxlength="3" type ="text"><input class="w3-btn w3-gray w3-hover-indigo" type="button" value="<?php echo Portal::language('copy');?>" onclick="copyTraveller($('traveller_indexs').value,$('traveller_quantity').value)" style="margin-left: 5px;">
         </fieldset>
	<table width="100%">
	<tr><td align="left">
		</td></tr>
		<tr><td>   
		<fieldset style="background:#DFEFFF;">
			<?php 
				if((Portal::language()==1))
				{?>	
			<div class="notice">B&#7841;n nh&#7853;p s&#7889; h&#7897; chi&#7871;u/CMTND &#273;&#7875; bi&#7871;t kh&aacute;ch &#273;&atilde; &#7903; hay ch&#432;a. N&#7871;u kh&aacute;ch &#273;&atilde; t&#7915;ng &#7903; th&igrave; khi nh&#7853;p xong s&#7889; h&#7897; chi&#7871;u/CMTND th&ocirc;ng tin c&#7911;a kh&aacute;ch &#7903; s&#7869; tự &#273;&#7897;ng c&#7853;p nh&#7853;t. </div>
			 <?php }else{ ?>
			<div class="notice">Enter the passport / ID card number to check the guest history. If he is a return guest, his information will be updated automatically on screen.</div>
			
				<?php
				}
				?>
            
		</fieldset> 
	</td>
	</tr>
	</table>
	</td></tr>
    </table>
</table>
<p>&nbsp;</p>
</div>
</div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<div id="selected_room" onmouseover="$('selected_room').style.display='';" onmouseout="$('selected_room').style.display='none';" style="display:none;float:left;position:absolute;top:0px;left:0px;width:180px;background-color:#FFCC00;border:2px solid #0099CC;vertical-align:top;">
    <div id="rooms" style="width:99%;background-color:#FFFFFF;float:left;"></div>
</div>



<script>
var CURRENT_YEAR = <?php echo date('Y')?>;
var CURRENT_MONTH = <?php echo intval(date('m')) - 1;?>;
var CURRENT_DAY = <?php echo date('d')?>;
var readOnly = 'readonly="readonly"';
<?php 
				if((User::can_edit(false, ANY_CATEGORY)))
				{?>
	readOnly = '';

				<?php
				}
				?>
var traveller_delete = {};
var nationalities = <?php echo String::array2js($this->map['nationalities'])?>;
var currentHour = '<?php echo date('H:i');?>';
var currentDate = '<?php echo date('d/m/Y');?>';
<?php if(User::can_admin(false,ANY_CATEGORY)){
	echo 'var can_admin = true;'; }else{ echo 'var can_admin = false;';} ?>
<?php if(User::can_delete(false,ANY_CATEGORY)){
	echo 'var can_delete = true;'; }else{ echo 'var can_delete = false;';} ?>
	function handleKeyPress(evt) {  
		var nbr;  
		var nbr = (window.event)?event.keyCode:evt.which;
		if(nbr==13){
			if(!confirm('<?php echo Portal::language('Are_you_sure_to_update_reservation');?>?')){
				return false;
			}
		}
		if(nbr==116){
			if(!confirm('<?php echo Portal::language('Are_you_sure_to_refresh');?>?')){
				return false;
			}
		}
		return true;
	}
	document.onkeydown= handleKeyPress;

vip_card_list = <?php echo String::array2js($this->map['vip_card_list']);?>;

var currency_arr = {};
var mi_traveller_arr='';
<?php if(isset($_REQUEST['mi_reservation_room']))
{
	echo 'var mi_reservation_room_arr = '.String::array2js($_REQUEST['mi_reservation_room']).';';
	//echo 'mi_init_rows(\'mi_reservation_room\',mi_reservation_room_arr);';
}
?>


<?php if(isset($_REQUEST['mi_traveller']))
{
	
	echo 'var mi_traveller_arr = '.String::array2js($_REQUEST['mi_traveller']).';';
	echo 'mi_init_rows(\'mi_traveller\',mi_traveller_arr);';
}
else
{
	echo 'var mi_traveller_arr = \'\';';
	echo 'mi_add_new_row(\'mi_traveller\',true);';
}
?> 
count_guest = <?php echo $this->map['count_guest'];?>;
/*if(mi_traveller_arr.length==0 || mi_traveller_arr.length == undefined){
	jQuery('.folio_invoice').css('display','none');		
}*/
var total_guest =0;
	/*for(i in mi_traveller_arr)
	{
		for(j=101;j<=input_count;j++){
			if(mi_traveller_arr[i]['check_out']==1){
				if(jQuery('#id_'+j).val()==mi_traveller_arr[i]['reservation_traveller_id'] && jQuery('#traveller_room_id_'+j).val()!= 'undefined'){
					jQuery('#table_'+j).css('background','#C3C3C3');
					jQuery('#table_'+j+' input').attr('readonly','true');	
					jQuery('#table_'+j+' input[type=button]').attr('onClick','');		
					jQuery('#table_'+j+' input').css('background','#EFEFEF');	
					jQuery('#mi_traveller_room_name_'+j).css('color','#0066ff');
					jQuery('#mi_traveller_room_name_'+j).attr('onclick','');
					if(mi_traveller_arr[i]['status']=='CHANGE'){
						jQuery('#table_'+j+' input[type=button]').val('<?php echo Portal::language('has_changed_room');?>');
						jQuery('#checkout_guest_'+j).css('display','none');							
					}else if(mi_traveller_arr[i]['status']=='CHECKOUT'){
						jQuery('#table_'+j+' input[type=button]').val('<?php echo Portal::language('checkout');?>');						
						jQuery('#checkout_guest_'+j).css('display','none');	   
					}
				}
			}else{
				for(var k in count_guest){
					if(count_guest[k]['count']<=1 && count_guest[k]['id']== mi_traveller_arr[i]['reservation_room_id'] && jQuery('#id_'+j).val()==mi_traveller_arr[i]['reservation_traveller_id'] && jQuery('#traveller_room_id_'+j).val()!= 'undefined'){	
						jQuery('#checkout_guest_'+j).css('display','none');
						jQuery('#change_guest_'+j).css('display','none');
					}
				}
				if(jQuery('#id_'+j).val()==mi_traveller_arr[i]['reservation_room_id'] && jQuery('#room_level_id_'+j).val() != 'undefined'){
					jQuery('#split_invoice_'+j).css('display','block');	
					if(mi_traveller_arr[i]['status']=='CHECKOUT'){
						jQuery('#change_guest_'+j).css('display','none');						
					}	
				}
				total_guest ++;
			}
		}
	}*/
//contruct_currency();
function expandShortenTraveller(id){
	if($('expand_guest_'+id)){
		if($('expand_guest_'+id).innHTML=='')
		{
			$('expand_guest_'+id).innHTML='+';
			jQuery('#extra_guest_'+id).hide();
			$('expand_guest_img_'+id).src='packages/core/skins/default/images/buttons/node_close.gif';
		}
		else
		{
			$('expand_guest_'+id).innHTML='';
			jQuery('#extra_guest_'+id).slideDown(100);
			$('expand_guest_img_'+id).src='packages/core/skins/default/images/buttons/node_open.gif';
		}
	}
}

<?php 
				if((Url::get('r_r_id')))
				{?>
var r_r_id = <?php echo Url::get('r_r_id');?>;
 <?php }else{ ?>
var r_r_id = 0;

				<?php
				}
				?>
var can_checkin = false;
<?php if(User::can_add($this->get_module_id('CheckIn'),ANY_CATEGORY)){?>
	can_checkin = true;
<?php }?>
function updateAll(){
	for(var i=101;i<=input_count;i++){
		if($('index_'+i)){
			$('index_'+i).innerHTML = i;
		}
		if(jQuery('#birth_date_'+i)){
			jQuery('#birth_date_'+i).mask('99/99/9999');
		}
		if($('expand_guest_img_'+i)){
			$('expand_guest_img_'+i).src='packages/core/skins/default/images/buttons/node_close.gif';	
			jQuery('#extra_guest_'+i).hide();
		}
		var datePickerForArrival = true;
		var datePickerForDeparture = true;
		<?php if(!User::can_admin(false,ANY_CATEGORY)){?>
		if($('id_'+i) && $('id_'+i).value){
			//updateRoomForTraveller(i);
		}
		<?php }else{?>
		if(jQuery("#deposit_date_"+i)){
			jQuery("#deposit_date_"+i).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) ,yearRange: '-100:+4'});
		}
		<?php }?>
		if($("traveller_id__"+i)){
			init_traveller_action(i);
			if(datePickerForArrival) jQuery("#arrival_date_"+i).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) ,yearRange: '-100:+4'});
			if(datePickerForDeparture) jQuery("#departure_date_"+i).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY),yearRange: '-100:+4' });
			var viewDetailLink = 'Xem th&#244;ng tin kh&#225;ch/ View this guest\'s info';
			$('detail_link_'+i).innerHTML = '<i class="fa fa-address-book w3-text-indigo" style="font-size: 14px;"></i>&nbsp;<a target="blank" style="font-size:11px;" href="<?php echo 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root;?>?page=traveller&cmd=edit&id='+jQuery('#traveller_id__'+i).val()+'">'+viewDetailLink+'#'+jQuery('#traveller_id__'+i).val()+'</a>';
		
            var viewHistoryLink = '<?php echo Portal::language("view_history"); ?>';
			$('history_link_'+i).innerHTML = '<i class="fa fa-history w3-text-red" style="font-size: 14px; margin-left:30px;"></i>&nbsp;<a target="blank" style="font-size:11px;" href="<?php echo 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root;?>?page=traveller&id='+jQuery('#traveller_id__'+i).val()+'">'+viewHistoryLink+'#'+jQuery('#traveller_id__'+i).val()+'</a>';
        }
		if($('traveller_room_id_'+i) && $('traveller_room_id_'+i).value && $('mi_traveller_room_name_'+i)){
			var roomIndex = getRoomIndexById($('traveller_room_id_'+i).value);
			if($('status_'+roomIndex) && $('status_'+roomIndex).value == 'CHECKOUT'){
				jQuery('#mi_traveller_room_name_'+i).css('background','#FF80FF');
			}
		}
		// Phan check khach checkout or change
		if(jQuery('#check_out_'+i).val()==1){
				jQuery('#table_'+i).css('background','#C3C3C3');
				jQuery('#table_'+i+' input').attr('readonly','true');	
				jQuery('#table_'+i+' input[type=button]').attr('onClick','');		
				jQuery('#table_'+i+' input').css('background','#EFEFEF');	
				jQuery('#mi_traveller_room_name_'+i).css('color','#0066ff');
				jQuery('#mi_traveller_room_name_'+i).attr('onclick','');
				if(jQuery('#status_'+i).val()=='CHANGE'){
					jQuery('#table_'+i+' input[type=button]').val('<?php echo Portal::language('has_changed_room');?>');
					jQuery('#checkout_guest_'+i).css('display','none');							
				}else if(jQuery('#status_'+i).val()=='CHECKOUT'){
					jQuery('#table_'+i+' input[type=button]').val('<?php echo Portal::language('checkout');?>');						
					jQuery('#checkout_guest_'+i).css('display','none');	   
				}
		}else{
			for(var k in count_guest){
				if(count_guest[k]['count']<=1 && count_guest[k]['id']== jQuery('#reservation_room_id_'+i).val()){	
					jQuery('#checkout_guest_'+i).css('display','none');
					jQuery('#change_guest_'+i).css('display','none');
				}
			}
			jQuery('#split_invoice_'+i).css('display','block');	
			if(jQuery('#status_'+i).val()=='CHECKOUT'){
				jQuery('#change_guest_'+i).css('display','none');						
			}	
			total_guest ++;
		}
	}
}

updateAll();
jQuery('#count_number_of_guest').html(total_guest);
function myAutocomplete(index)
{
	if($("nationality_id_"+index)!=null){
		jQuery("#nationality_id_"+index).autocomplete({
			url:'r_get_countries.php',
				onItemSelect: function(item) {
				updateNationality(index);
			},
			formatItem: function(row, i, max) {
				return row.id + ' [<span style="color:#993300"> ' + row.name + '</span> ]';
			},
			formatMatch: function(row, i, max) {
				return row.id + ' ' + row.name;
			},
			formatResult: function(row) {			
				return row.id;
			}
		});
	}
}
jQuery('#changeAllNational').autocomplete({
	url:'r_get_countries.php',
			onItemSelect: function(item) {
			updateAllNationality();
		},
		formatItem: function(row, i, max) {
			return row.id + ' [<span style="color:#993300"> ' + row.name + '</span> ]';
		},
		formatMatch: function(row, i, max) {
			return row.id + ' ' + row.name;
		},
		formatResult: function(row) {			
			return row.id;
		}	
});
function updateAllNationality(){
	if(typeof(nationalities[jQuery('#changeAllNational').val()])=='undefined'){
	}else{
		jQuery('.nationality_name').val(nationalities[jQuery('#changeAllNational').val()]['name']);
	}
	jQuery('.nationality_id').val(jQuery('#changeAllNational').val());
}
function init_traveller_action(traveller_input_count)
{
	
                
    myAutocomplete(traveller_input_count);
	get_traveler_sugges(traveller_input_count);
	get_traveler_name_sugges(traveller_input_count);
    get_member_code_sugges(traveller_input_count);
	//jQuery('#birth_date_'+(traveller_input_count)).mask('99/99/9999');
	if(jQuery('#expire_date_of_visa_'+traveller_input_count)){
		jQuery("#expire_date_of_visa_"+traveller_input_count).datepicker();
	}
	if(jQuery('#arrival_hour_'+traveller_input_count)){
		jQuery("#arrival_hour_"+traveller_input_count).mask("99:99");
	}
	if(jQuery('#departure_hour_'+traveller_input_count)){
		jQuery("#departure_hour_"+traveller_input_count).mask("99:99");
	}
    //KimTan: xu ly khong cho chon ngay den ngay di cua khach vuot qua ngay den ngay di cua phong
    if(jQuery('#traveller_arrival_date_'+traveller_input_count)){
        for(var j in mi_reservation_room_arr){
            jQuery("#traveller_arrival_date_"+traveller_input_count).datepicker({ minDate: new Date(mi_reservation_room_arr[j]['year_arrival_time'], mi_reservation_room_arr[j]['month_arrival_time']-1,mi_reservation_room_arr[j]['day_arrival_time']),
            maxDate: new Date(mi_reservation_room_arr[j]['year_departure_time'], mi_reservation_room_arr[j]['month_departure_time']-1,mi_reservation_room_arr[j]['day_departure_time']) ,yearRange: '-100:+4'});      
        }
	}	
	if(jQuery('#traveller_departure_date_'+traveller_input_count)){
		for(var j in mi_reservation_room_arr){
		  //
          /*
            var parsed_date = new Date(mi_reservation_room_arr[j]['year_departure_time'], mi_reservation_room_arr[j]['month_departure_time'],mi_reservation_room_arr[j]['day_departure_time']);
    
            var maxdate = new Date(parsed_date);
            maxdate.setDate(parsed_date.getDate() + 5);
    
            jQuery("#traveller_departure_date_"+traveller_input_count).datepicker({
                changeMonth: true,
                changeYear: true,
                minDate: parsed_date,
                maxDate: maxdate
            });
            */
          //
            jQuery("#traveller_departure_date_"+traveller_input_count).datepicker({ maxDate: new Date(mi_reservation_room_arr[j]['year_departure_time'], mi_reservation_room_arr[j]['month_departure_time']-1,mi_reservation_room_arr[j]['day_departure_time']) 
            ,minDate: new Date(mi_reservation_room_arr[j]['year_arrival_time'], mi_reservation_room_arr[j]['month_arrival_time']-1,mi_reservation_room_arr[j]['day_arrival_time'])
            ,yearRange: '-100:+4'});
	   }
    }
    //end KimTan: xu ly khong cho chon ngay den ngay di cua khach vuot qua ngay den ngay di cua phong	
	if(jQuery('#flight_arrival_hour_'+traveller_input_count)){
		jQuery("#flight_arrival_hour_"+traveller_input_count).mask("99:99");
	}	
	if(jQuery('#flight_arrival_date_'+traveller_input_count)){
		jQuery("#flight_arrival_date_"+traveller_input_count).datepicker();
	}
	if(jQuery('#flight_departure_hour_'+traveller_input_count)){
		jQuery("#flight_departure_hour_"+traveller_input_count).mask("99:99");
	}	
	if(jQuery('#flight_departure_date_'+traveller_input_count)){
		jQuery("#flight_departure_date_"+traveller_input_count).datepicker();
	}
	if(jQuery('#checkout_guest_'+traveller_input_count) && !jQuery('#reservation_traveller_id_'+traveller_input_count).val())
	{
		jQuery('#checkout_guest_'+traveller_input_count).css('display','none');
		jQuery('#change_guest_'+traveller_input_count).css('display','none');
	}
}
function get_traveler_sugges(traveller_input_count){
	jQuery("#passport_"+traveller_input_count).autocomplete({
		url:'get_traveller.php?passport=1',
		minChars: 0,
		width: 280,
		matchContains: true,
		autoFill: false,
		formatItem: function(row, i, max) {
			return ' <span> ' + row.name + '</span>';
		},
		formatMatch: function(row, i, max) {
			return row.name;
		},
		formatResult: function(row) {			
			return row.name;
		},
		onItemSelect: function(item) {
			get_traveller(traveller_input_count);
		}
	});
}
function get_traveler_name_sugges(traveller_input_count){
	jQuery("#first_name_"+traveller_input_count).autocomplete({
		url:'get_traveller.php',
		minChars: 0,
		width: 280,
		matchContains: true,
		autoFill: false,
		formatItem: function(row, i, max) {
			return row.id + ' [<span style="color:#993300"> ' + row.name + '</span> ]';
		},
		formatMatch: function(row, i, max) {
			return row.name;
		},
		formatResult: function(row) {			
			return row.id;
		},
		onItemSelect: function(item) {
			var newStr = item.value;
			var arr = newStr.split(' - ');
			jQuery('#first_name_'+traveller_input_count).val(arr[0]);
			jQuery('#last_name_'+traveller_input_count).val(arr[1]);
			jQuery('#traveller_id__'+traveller_input_count).val(arr[2]);
			jQuery('#passport_'+traveller_input_count).val(item.data);
			get_traveller(traveller_input_count);
		}
	});
}
function get_member_code_sugges(traveller_input_count){
    jQuery("#member_code_"+traveller_input_count).autocomplete({
		url:'get_traveller.php?member_code=11',
		minChars: 0,
		width: 280,
		matchContains: true,
		autoFill: false,
		formatItem: function(row, i, max) {
			return ' <span style="color:#00b2f9"> ' + row.name + '</span>';
		},
		formatMatch: function(row, i, max) {
			return row.name;
		},
		formatResult: function(row) {			
			return row.name;
		},
		onItemSelect: function(item) {
		    var newvalue = item.value;
            var arr_value = newvalue.split('-');
            jQuery("#member_code_"+traveller_input_count).val(arr_value[0]);
            fun_check_member_code(traveller_input_count);
			//get_traveller(traveller_input_count);
		}
	});
}
function fun_check_create_member(obj){
    var arr = obj.id.split("_");
    var member_code = jQuery("#member_code_"+arr[2]).val();
    if(document.getElementById(obj.id).checked==true){
        if(member_code!='')
        {
            if(member_code==0)
            {
                jQuery("#member_code_"+arr[2]).val("");
            }else{
                document.getElementById(obj.id).checked=false;
                alert("thành viên đã có mã!");
            }
        }
    }
}
function fun_check_member_code(index){
    var member_code = jQuery("#member_code_"+index).val();
    traveller_input_count = index;
    if(member_code!=''){
        if (window.XMLHttpRequest){
            xmlhttp=new XMLHttpRequest();
        }
        else{
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var text_reponse = xmlhttp.responseText;
                var otbjs = jQuery.parseJSON(text_reponse);
                for(obj in otbjs){
                    if(otbjs[obj]['no_member']==0){
                        alert("Mã thành viên không đúng, vui lòng nhập lại!");
                        jQuery("#member_code_"+traveller_input_count).val("");
                        jQuery("#first_name_"+traveller_input_count).val("");
                        jQuery("#last_name_"+traveller_input_count).val("");
                        jQuery("#gender_"+traveller_input_count).val("");
                        jQuery("#birth_date_"+traveller_input_count).val("");
                        jQuery("#nationality_id_"+traveller_input_count).val("");
                        jQuery("#nationality_name_"+traveller_input_count).val("");
                        jQuery("#phone_"+traveller_input_count).val("");
                        jQuery("#note_"+traveller_input_count).val("");
                        jQuery("#traveller_level_id_"+traveller_input_count).val("");
                        jQuery("#expire_date_of_visa_"+traveller_input_count).val("");
                        jQuery("#visa_"+traveller_input_count).val("");
                        jQuery("#traveller_id__"+traveller_input_count).val("");
                        jQuery("#address_"+traveller_input_count).val("");
                        jQuery("#passport_"+traveller_input_count).val("");
                        jQuery("#email_"+traveller_input_count).val("");
                        $('detail_link_'+traveller_input_count).innerHTML = "";
                        return;
                    }else{
                        //console.log(otbjs[obj]['detail']);
                        jQuery("#member_code_"+traveller_input_count).val(otbjs[obj]['detail']['member_code']);
                        jQuery("#first_name_"+traveller_input_count).val(otbjs[obj]['detail']['first_name']);
                        jQuery("#last_name_"+traveller_input_count).val(otbjs[obj]['detail']['last_name']);
                        jQuery("#gender_"+traveller_input_count).val(otbjs[obj]['detail']['gender']);
                        jQuery("#birth_date_"+traveller_input_count).val(otbjs[obj]['detail']['birth_date']);
                        jQuery("#nationality_id_"+traveller_input_count).val(otbjs[obj]['detail']['nationality_id']);
                        jQuery("#nationality_name_"+traveller_input_count).val(otbjs[obj]['detail']['nationality_name']);
                        jQuery("#phone_"+traveller_input_count).val(otbjs[obj]['detail']['phone']);
                        jQuery("#note_"+traveller_input_count).val(otbjs[obj]['detail']['note']);
                        jQuery("#traveller_level_id_"+traveller_input_count).val(otbjs[obj]['detail']['traveller_level_id']);
                        jQuery("#expire_date_of_visa_"+traveller_input_count).val(otbjs[obj]['detail']['expire_date_of_visa']);
                        jQuery("#visa_"+traveller_input_count).val(otbjs[obj]['detail']['visa_number']);
                        jQuery("#traveller_id__"+traveller_input_count).val(otbjs[obj]['detail']['id']);
                        jQuery("#address_"+traveller_input_count).val(otbjs[obj]['detail']['address']);
                        jQuery("#passport_"+traveller_input_count).val(otbjs[obj]['detail']['passport']);
                        jQuery("#email_"+traveller_input_count).val(otbjs[obj]['detail']['email']);
                        var traveller_id = otbjs[obj]['detail']['id'];
                        $('detail_link_'+traveller_input_count).innerHTML = '<a target="_blank" href="?page=traveller&id='+traveller_id+'">'+'Xem thông tin khách/ View this guest\'s info'+'#'+traveller_id+'</a>';
                    }
                }
            }
        }
        xmlhttp.open("GET","get_member.php?data=get_member_discount&member="+member_code,true);
        xmlhttp.send();
    }
}
function change_all_traveller_level()
{
	for(var i=101;i<=input_count;i++){
		if(jQuery("#traveller_id_"+i)){	
			jQuery('#traveller_level_id_'+i).val(jQuery('#change_all_traveller_id').val());
		}
	}
}
function onBackEvent()
{
    var at_path = window.parent.location.toString();
    if(at_path.indexOf('&adddd_guest=yes')>-1)
    {
        var ts_path = at_path.replace('&adddd_guest=yes','');
        //alert(ts_path);
        window.parent.location.replace(ts_path);
    }
    else
	   window.parent.location.reload();
}
function checkRoomOut(room_id){
	$return = false;
	if(room_id){
		temp_arr = room_id.split('-');
		for(var j in mi_reservation_room_arr){
			if(mi_reservation_room_arr[j]['room_id']==temp_arr[0] && mi_reservation_room_arr[j]['departure_time']==temp_arr[1]){
				if(mi_reservation_room_arr[j]['status']=='CHECKOUT'){
					$return = true;	
				}
			}
		}
	}
	return $return;
}
jQuery(document).click(function(){
		jQuery('.acResults').css('display','none');
	});
    
    
//luu nguyen giap add function 
function addnew_traveller()
{
    
    mi_add_new_row('mi_traveller');
    myAutocomplete(input_count);
    jQuery('#birth_date_'+(input_count)).mask('99/99/9999');
    $('mi_traveller_room_name_'+input_count).value = '<?php if(Url::get('rr_id') && isset($this->map['room_name'])){ echo $this->map['room_name'];}else{ echo '';}?>';
    $('room_level_'+input_count).value = '<?php if(Url::get('rr_id') && isset($this->map['room_level'])){ echo $this->map['room_level'];}else{ echo '';}?>';
    $('traveller_room_id_'+input_count).value = '<?php if(Url::get('rr_id') && isset($this->map['room_id'])){ echo ($this->map['room_id'].'-'.$this->map['departure_time']);}else{ echo '';}?>';
    if($('index_'+input_count))
    {
        $('index_'+input_count).innerHTML = input_count;
    } 
    init_traveller_action(input_count);
    
    //xu ly de hien thi ra so phong neu co 1 reservation_room trong 1 reservation
    /*Lay ra thong tin  [traveller_room_id] => 49-08/08/2014
    $temp_arr = explode('-',$record['traveller_room_id']);
    $room_id = $temp_arr[0];
    $departure_time = $temp_arr[1];
    --$reservation_room = DB::select('reservation_room','reservation_id='.$id.' and room_id='.$room_id.' and departure_time = \''.Date_Time::to_orc_date($departure_time).'\'');
    'reservation_room_id'=>$reservation_room_id trong reservation_traveller
    **********************************************************/
    var i=0;
    for(var j in mi_reservation_room_arr)
    {
        i++;
    }
    if(i==1)
    {
       for(var j in mi_reservation_room_arr)
       {
            document.getElementById('mi_traveller_room_name_'+input_count).value = mi_reservation_room_arr[j]['room_name'];
            document.getElementById('room_level_'+input_count).value = mi_reservation_room_arr[j]['room_level'];
            document.getElementById('traveller_room_id_'+input_count).value =  mi_reservation_room_arr[j]['room_id']+'-'+  mi_reservation_room_arr[j]['departure_time'];
            break;
       } 
    }
}
//end
//start:KID them ham check neu da tao folio boi khach thi khong duoc xoa khach
function check_is_folio(index)
{      
    <?php echo 'var block_id = '.Module::block_id().';';?> 
    //var traveller_delete = jQuery('#traveller_delete').val();
    var traveller_delete = 1;
    var reservation_traveller_id= jQuery('#reservation_traveller_id_'+index).val();
    jQuery('#loading-layer').fadeIn(100);
    jQuery.ajax({
					url:"form.php?block_id="+block_id,
					type:"POST",
					data:{traveller_delete:traveller_delete,reservation_traveller_id:reservation_traveller_id},
					success:function(html)
                    {
                        if(html==1)
                        {
                            alert('<?php echo Portal::language('guest_have_folio_can_not_delete');?>');
                            return false;
                        }
                        else
                        {
                            jQuery('#traveller_delete').val(jQuery('#traveller_delete').val()+','+jQuery('#reservation_traveller_id_'+index).val());
                            mi_delete_row($('input_group_'+index),'mi_traveller',index,'');
                            event.returnValue=false;
                        }
						jQuery('#loading-layer').fadeOut(0);
					}
		});
     
}
var count = 1;
function checkDoubleClick()
{
    if(count==1)
    {
       //jQuery("#UpdateTravellerForm").submit();
       count++;
       return true;
    }
    else
    {
        count++;
        return false;
    }
}
//end:KID them ham check neu da tao folio boi khach thi khong duoc xoa khach


//oanh add

//function exportExcel(){
//    jQuery("#myTable").battatech_excelexport({
//        containerid: "myTable"
//        ,datatype: 'table'
//    });
//} 
//end oanh
    <?php
        if(USING_PASSPORT && USING_PASSPORT==1)
        {
    ?>
    var ws = new WebSocket("ws://localhost:8090");
		ws.onopen = function() {
		};

		ws.onmessage = function(evt) {
		    var content = JSON.parse(evt.data);
            console.log(content);
            var given_name =  content['Givenname'];   
            var surname =  content['Familyname'];
            var nationality =  content['Nationality'];
            var gender =  content['Sex'];
            var birth_temp =  content['Birthday'];

            var birth = birth_temp.substring(4, 6)+"/"+birth_temp.substring(2, 4)+"/"+"19"+birth_temp.substring(0, 2);
            var passport_no =  content['DocumentNo'];
            
            var url = "check_traveller.php";
            jQuery.ajax({
                url : url,
                dataType : "json",
                type : "POST",
                data : {"passport_no":passport_no,"nationality":nationality},
                success : function(data){
                    addnew_traveller();
                    //console.log(data);
                    jQuery("#mi_traveller_all_elems>span:last-child>span[id^=input_group_] input[id^=first_name]").val(surname);
                    jQuery("#mi_traveller_all_elems>span:last-child>span[id^=input_group_] input[id^=last_name]").val(given_name);
                    jQuery("#mi_traveller_all_elems>span:last-child>span[id^=input_group_] input[id^=passport]").val(passport_no);
                    
                    if(gender=="M")
                    {
                        jQuery("#mi_traveller_all_elems>span:last-child>span[id^=input_group_] select[id^=gender] option[value=1]").attr("selected","selected");
                    }
                    else
                    {
                        jQuery("#mi_traveller_all_elems>span:last-child>span[id^=input_group_] select[id^=gender] option[value=0]").attr("selected","selected");
                    }
                    jQuery("#mi_traveller_all_elems>span:last-child>span[id^=input_group_] input[id^=birth_date]").val(birth);
                    jQuery("#mi_traveller_all_elems>span:last-child>span[id^=input_group_] input[id^=nationality_id]").val(nationality);
                    jQuery("#mi_traveller_all_elems>span:last-child>span[id^=input_group_] input[id^=nationality_name_]").val(data['nationality_name']);
                    if(data['id'])
                    {
                        jQuery("#mi_traveller_all_elems>span:last-child>span[id^=input_group_] input[id^=traveller_id_]").val(data['id']);
                    } 
                    //console.log(data['path']);
                    //jQuery("#img_passport").attr('value'content['path']);
                                         
                }
            }); 
		};
        
		ws.onclose = function() {
			//alert("Connection is closed...");;
		};
     <?php
        }
     ?>    
</script>
