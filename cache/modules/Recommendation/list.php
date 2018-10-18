<style>
.simple-layout-middle{
    width: 100%;
}
</style>
<?php System::set_page_title(HOTEL_NAME);?>
<div class="customer-type-pc_supplier-bound">
<form name="ListSupplierForm" method="post">
    <table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr height="40">
            <td width="60%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-file-text w3-text-orange" style="font-size: 26px;"></i> <?php echo Portal::language('require_list');?></td>
            <td width="40%" align="right" nowrap="nowrap" style="padding-right: 30px;">
                <a href="<?php echo URL::build_current(array('cmd'=>'add','group_id','action'));?>"  class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none;"><?php echo Portal::language('Add');?></a>
                <!--<a href="javascript:void(0)" onclick="if(!confirm('<?php echo Portal::language('are_you_sure');?>')){return false};ListSupplierForm.cmd.value='delete_group';ListSupplierForm.submit();"  class="button-medium-delete"><?php echo Portal::language('Delete');?></a>-->
            </td>
        </tr>
    </table>        
    
    <div class="content">
        <fieldset style="font-weight: normal !important;">
            <legend class="title"><?php echo Portal::language('search');?></legend>
            <?php echo Portal::language('recomment_date');?>
            <input  name="date" id="date"  style="width: 80px; height: 24px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('date'));?>">
            <?php echo Portal::language('department');?>:
            <select  name="department_id" id="department_id" style="width: 150px; height: 24px;"><?php
					if(isset($this->map['department_id_list']))
					{
						foreach($this->map['department_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('department_id',isset($this->map['department_id'])?$this->map['department_id']:''))
                    echo "<script>$('department_id').value = \"".addslashes(URL::get('department_id',isset($this->map['department_id'])?$this->map['department_id']:''))."\";</script>";
                    ?>
	
            <?php echo $this->map['department_list'];?>
            </select>
            <?php echo Portal::language('status');?>
            <select  name="status" id="status" style="width: 100px; height: 24px;"><?php
					if(isset($this->map['status_list']))
					{
						foreach($this->map['status_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('status',isset($this->map['status'])?$this->map['status']:''))
                    echo "<script>$('status').value = \"".addslashes(URL::get('status',isset($this->map['status'])?$this->map['status']:''))."\";</script>";
                    ?>
	
            <?php echo $this->map['status_list'];?>
            </select>
            <?php echo Portal::language('user_status');?>
            <select  style="width: 100px; height: 24px;" name="user_status" id="user_status">
                <option value="1">Active</option>
                <option value="0">All</option>
            </select>
            <?php echo Portal::language('person_recomment');?>
            <select  name="person_recomment" id="person_recomment" style="width: 100px; height: 24px;"><?php
					if(isset($this->map['person_recomment_list']))
					{
						foreach($this->map['person_recomment_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('person_recomment',isset($this->map['person_recomment'])?$this->map['person_recomment']:''))
                    echo "<script>$('person_recomment').value = \"".addslashes(URL::get('person_recomment',isset($this->map['person_recomment'])?$this->map['person_recomment']:''))."\";</script>";
                    ?>
	</select>
            <?php echo Portal::language('who_confirm');?>
            <select  name="who_confirm" id="who_confirm" style="width: 100px; height: 24px;"><?php
					if(isset($this->map['who_confirm_list']))
					{
						foreach($this->map['who_confirm_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('who_confirm',isset($this->map['who_confirm'])?$this->map['who_confirm']:''))
                    echo "<script>$('who_confirm').value = \"".addslashes(URL::get('who_confirm',isset($this->map['who_confirm'])?$this->map['who_confirm']:''))."\";</script>";
                    ?>
	</select>
            <input name="search" type="submit" value="<?php echo Portal::language('search');?>" style=" height: 24px;" />
        </fieldset>
        
        <br />
        <?php if(Form::$current->is_error()){?><div><br><?php echo Form::$current->error_messages();?></div><?php }?><br />
        <table width="100%" border="1" cellspacing="0" cellpadding="2" bordercolor="#CCCCCC">
            <tr class="w3-light-gray" style="text-transform: uppercase;">
                <th width="10"><input type="checkbox" id="all_item_check_box"/></th>
                <th width="30"><?php echo Portal::language('stt');?></th>
                <th width="100" align="center"><?php echo Portal::language('recomment_date');?></th>
                <th width="150" align="center"><?php echo Portal::language('person_recomment');?></th>
                <th width="100" align="center"><?php echo Portal::language('department');?></th>
                <!--<th width="10%" align="center"><?php echo Portal::language('status');?></th>-->
                <th width="200" align="center"><?php echo Portal::language('description');?></th>
                <th width="150" align="center" style="white-space: nowrap;"><?php echo Portal::language('last_edit_user');?></th>
                <th width="100" align="center"><?php echo Portal::language('head_of_department');?></th>
                <th width="150" align="center" style="white-space: nowrap;"><?php echo Portal::language('who_confirm');?></th>
                <th width="100" align="center"><?php echo Portal::language('unhead_of_department');?></th>
                <th width="150" align="center"><?php echo Portal::language('who_unconfirm');?></th>
                <th width="150" align="center"><?php echo Portal::language('reason_cancellation');?></th>
                <th width="40" align="center"><?php echo Portal::language('copy');?></th>
                <th width="40" align="center"><?php echo Portal::language('view');?></th>
                <th width="40" align="center"><?php echo Portal::language('edit');?></th>
                <th width="40" align="center"><?php echo Portal::language('delete');?></th>
            </tr>
            <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
            <tr <?php echo $this->map['items']['current']['index']%2==0?' style="background-color: #E8F3FF;"':''?>>
                <td align="center">
                    <input name="item_check_box[]" type="checkbox" class="item-check-box" value="<?php echo $this->map['items']['current']['id'];?>"/>
                </td>
                <td align="center"><?php echo $this->map['items']['current']['index'];?></td>
                <td align="center"><?php echo $this->map['items']['current']['recommend_date'];?></td>
                <td><?php echo $this->map['items']['current']['recommend_person'];?></td>
                <td><?php echo $this->map['items']['current']['department'];?></td>
                <!--
                <td>
                <?php
                switch($this->map['items']['current']['status'])
                {
                    case 'MOVE':
                    {
                        echo 'ĐIỀU CHUYỂN';
                        break;
                    }
                    default:
                    {
                        echo '';
                        break;
                    }
                } 
                ?>
                </td>
                -->
                <td style="cursor: pointer;"><?php echo $this->map['items']['current']['description'];?></td>
                <td style="cursor: pointer; text-align: center;"><?php echo $this->map['items']['current']['last_use'];?></td>
                <td style="text-align: center;">
                <?php
                if(User::can_admin(2071,'1182000000000000000')) 
                {
                    ?>
                    <input type="button" class="w3-btn w3-gray" name="confirm_<?php echo $this->map['items']['current']['id'];?>" id="confirm_<?php echo $this->map['items']['current']['id'];?>"  onclick="confirm_head_of_department(this.name);" <?php echo empty($this->map['items']['current']['confirm'])==false? ' value="Đã xác nhận" disabled="disabled" style="background: lime;"':' value="Xác nhận" style="cursor: pointer;"';?>  /> 
                    <?php 
                }
                ?>
                </td>
                <td align="center" style="white-space: nowrap;">
                    <?php echo ($this->map['items']['current']['time_confirm'])?date('d/m/Y H:i', $this->map['items']['current']['time_confirm']). ' <br /><b>' . $this->map['items']['current']['person_confirm']:''. ' ' . $this->map['items']['current']['person_confirm'].'</b>'; ?>
                </td>
                <td style="text-align: center;">
                <?php
                if(User::can_admin(2071,'1182000000000000000')) 
                {
                    ?>
                    <input type="button" class="w3-btn w3-red" name="unconfirm_<?php echo $this->map['items']['current']['id'];?>" id="unconfirm_<?php echo $this->map['items']['current']['id'];?>"  onclick="var answer = prompt('Vui lòng nhập lý do hủy xác nhận đơn hàng này!', ''); if(answer ==''){ alert('Bạn chưa nhập lý do hủy đơn hàng này!');}else{var str= this.name.split('_');var id = str[1];document.getElementById('id_unconfirm').value=id;document.getElementById('reason_cancellation').value=answer;ListSupplierForm.submit();};" value="Hủy" style="cursor: pointer; display: <?php echo (empty($this->map['items']['current']['confirm'])==true or (empty($this->map['items']['current']['confirm'])==false && $this->map['items']['current']['order_id'] !=''))?'none;':'';?>;"  /> 
                    <?php 
                }
                ?>
                </td>
                <td align="center" style="white-space: nowrap;">
                    <?php echo ($this->map['items']['current']['time_unconfirm'])?date('d/m/Y H:i', $this->map['items']['current']['time_unconfirm']). ' <br /><b>' . $this->map['items']['current']['person_unconfirm']:''. ' ' . $this->map['items']['current']['person_unconfirm'].'</b>'; ?>
                </td>
                <td align="left">
                    <?php echo $this->map['items']['current']['reason_cancellation'];?>
                </td>
                <td align="center" >

                <?php if(User::can_view(false,ANY_CATEGORY)){?><a target="_blank" href="<?php echo Url::build_current(array('cmd'=>'copy','id'=>$this->map['items']['current']['id']));?>"><img src="packages/core/skins/default/images/buttons/copy.png" /></a><?php }?></td>

                <td align="center"><?php if(User::can_view(false,ANY_CATEGORY)){?><a target="_blank" href="<?php echo Url::build_current(array('cmd'=>'view','id'=>$this->map['items']['current']['id']));?>"><i class="fa fa-eye w3-text-indigo" style="font-size: 20px; padding-top: 2px;"></i></a><?php }?></td>
                
                <td align="center"><?php if(User::can_edit(false,ANY_CATEGORY)){ if(empty($this->map['items']['current']['confirm'])==true){?><a  target="_blank" href="<?php echo Url::build_current(array('cmd'=>'edit','id'=>$this->map['items']['current']['id']));?>"><i class="fa fa-pencil w3-text-orange" style="font-size: 20px; padding-top: 2px;"></i></a><?php }}?></td>
                <td align="center"><?php if(User::can_delete(false,ANY_CATEGORY)){ if(empty($this->map['items']['current']['confirm'])==true){?><a class="delete-one-item" href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>$this->map['items']['current']['id']));?>"><i class="fa fa-times-circle w3-text-red" style="font-size: 20px; padding-top: 2px;"></i></a><?php }}?></td>
            </tr>
          <?php }}unset($this->map['items']['current']);} ?>            
        </table>
        <br />
        <div class="paging"><?php echo $this->map['paging'];?></div>
    </div>
    <input  name="cmd" value="" / type ="hidden" value="<?php echo String::html_normalize(URL::get('cmd'));?>">
    <input name="id_confirm" id="id_confirm" type="hidden" value="0" />
    <input name="person_confirm" id="person_confirm" type="hidden" value="<?php echo $this->map['person_confirm']; ?>" />
    <input name="id_unconfirm" id="id_unconfirm" type="hidden" value="0" />
    <input name="reason_cancellation" id="reason_cancellation" type="hidden" value="" />
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			 
</div>
<script type="text/javascript">
    jQuery(document).ready(function(){
        <?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current'] = &$item2;?>
        var confirm = jQuery('#confirm_'+<?php echo $this->map['items']['current']['id'];?>).val();
        if(confirm == 'Đã xác nhận')
        {
            jQuery('#edit_'+<?php echo $this->map['items']['current']['id'];?>).css('display','none');                            
        }
        <?php }}unset($this->map['items']['current']);} ?>
    })
    jQuery("#delete_button").click(function (){
        ListCustomerForm.cmd.value = 'delete';
        ListCustomerForm.submit();
    });
    jQuery(".delete-one-item").click(function (){
        if(!confirm('<?php echo Portal::language('are_you_sure');?>')){
            return false;
        }
    });
    jQuery("#all_item_check_box").click(function (){
        var check  = this.checked;
        jQuery(".item-check-box").each(function(){
            this.checked = check;
        });
    });
    
    jQuery("#date").datepicker();
    function confirm_head_of_department(name)
    {
        var conf = confirm('Bạn chắc chắn muốn xác nhận đề xuất mua hàng ?');
        if(conf == true)
        {
            var str= name.split('_');
            var id = str[1];
            document.getElementById('id_confirm').value=id;
            ListSupplierForm.submit();
        }else
        {
            return false;
        }
    }
    // 7211
    var users = <?php echo String::array2js($this->map['users']);?>;
    for (i in users){
       if(users[i]['is_active']!=1){
          jQuery('option[value='+users[i]['id']+']').remove();  
       }
    }
    jQuery('#user_status').change(function(){
        if(jQuery('#user_status').val()!=1){
            for (i in users){
               if(users[i]['is_active']!=1){
                  jQuery('#person_recomment').append('<option value='+users[i]['id']+'>'+users[i]['id']+'</option>'); 
                  jQuery('#who_confirm').append('<option value='+users[i]['id']+'>'+users[i]['id']+'</option>'); 
               }
            }
        }else{
            for (i in users){
               if(users[i]['is_active']!=1){
                  jQuery('option[value='+users[i]['id']+']').remove();  
               }
            }
        }
    });
    //7211 end
</script>
