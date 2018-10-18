<span style="display:none;clear:both;">
	<span id="mi_portal_group_sample">
		<div id="input_group_#xxxx#" style="width:100%;text-align:left;">
			<input  name="mi_portal_group[#xxxx#][id]" type="hidden" id="id_#xxxx#">
			<span class="multi-input" id="bound_group_id_#xxxx#">
				<select   name="mi_portal_group[#xxxx#][parent_id]" style="width:200px;"  id="parent_id_#xxxx#"><option value=""></option><?php echo $this->map['group_options'];?>
				</select>
			</span>
			<span class="multi-input">
				<select   name="mi_portal_group[#xxxx#][group_privilege_id]" style="width:160px;"  id="group_privilege_id_#xxxx#"><option value=""></option><?php echo $this->map['group_privilege_options'];?>
				</select>
			</span>            
			<span class="multi-input">
					<input  name="mi_portal_group[#xxxx#][join_date]" style="width:120px;" type="text" id="join_date_#xxxx#" value="<?php echo date('d/m/Y');?>">
			</span>
			<span class="multi-input">
					<input  type="checkbox" value="1" name="mi_portal_group[#xxxx#][is_active]" id="is_active_#xxxx#" style="width:50px;" checked>
			</span>
			<span class="multi-input"><span style="width:10px;">
				<img src="<?php echo Portal::template('core');?>/images/buttons/delete.gif" onClick="mi_delete_row($('input_group_#xxxx#'),'mi_portal_group','#xxxx#','group_');if(document.all)event.returnValue=false; else return false;" style="cursor:hand;"/>
			</span></span>
		</div><br clear="all"/>
	</span> 
</span>
<?php 
$title = (URL::get('cmd')=='edit')?Portal::language('Edit_user'):Portal::language('Add_user');
$action = (URL::get('cmd')=='edit')?'edit':'add';?>
<div>
	<table cellpadding="0" cellspacing="0" width="900" border="0" bordercolor="#CCCCCC" class="table-bound">
		<tr height="40">
			<td width="70%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><?php echo $title;?></td>
			<td width="30%"><a class="w3-btn w3-orange w3-text-white" href="javascript:void(0)" onclick="savefrom();" style="text-transform: uppercase; margin-right: 5px; text-decoration: none;"><?php echo Portal::language('save');?></a>
			<a class="w3-btn w3-green" href="javascript:void(0)" onclick="location='<?php echo URL::build_current();?>';" style="text-transform: uppercase; margin-right: 5px; text-decoration: none;"><?php echo Portal::language('back');?></a>
			<?php if($action=='edit'){?>
			<a class="w3-btn w3-red" href="javascript:void(0)" onclick="location='<?php echo URL::build_current(array('cmd'=>'delete','id'));?>';" style="text-transform: uppercase; text-decoration: none;"><?php echo Portal::language('Delete');?></a></td>
			<?php }?>
		</tr>
	</table>
	<div class="content">
	<?php if(Form::$current->is_error()){?><strong>B&#225;o l&#7895;i</strong><br>
	<?php echo Form::$current->error_messages();?><br>
	<?php }?>
	<form name="EditManageUserForm" method="post" id="EditManageUserForm">
	<input type="hidden" name="group_deleted_ids" id="group_deleted_ids" value=""/><br />
	<table cellpadding="5" cellspacing="0" width="100%" border="1" bordercolor="#CCCCCC" class="table-bound">
	  <tr>
		<td width="150" bgcolor="#EFEFEF"><strong><?php echo Portal::language('user_name');?></strong> (*):</td>
		<td width="300">
		  <input  name="id" id="id" style="width:150px;font-weight:bold;" type ="text" value="<?php echo String::html_normalize(URL::get('id'));?>"></td>
        <?php if(Url::get('cmd')=='edit'){ ?>
            <td></td>
            <td></td>
        <?php }else{ ?>
  		  <td bgcolor="#EFEFEF"><?php echo Portal::language('password');?>:</td>
		  <td><input  name="password" id="password" style="width:150px" / type ="text" value="<?php echo String::html_normalize(URL::get('password'));?>"><span style="color:red;font-style:italic;"><?php echo Portal::language('notify_pass');?></span> </td>
        <?php } ?>
	  </tr>
	  <tr>
		<td bgcolor	="#EFEFEF"><?php echo Portal::language('email');?>:</td>
		<td><input  name="email" id="email" style="width:150px" / type ="text" value="<?php echo String::html_normalize(URL::get('email'));?>">    </td>
		<td bgcolor="#EFEFEF"><?php echo Portal::language('full_name');?>:</td>
		<td><input  name="full_name" id="full_name" style="width:150px" / type ="text" value="<?php echo String::html_normalize(URL::get('full_name'));?>">    </td>
	  </tr>
	  <tr>
		<td bgcolor="#EFEFEF"><?php echo Portal::language('gender');?> (*):</td>
		<td><input name="gender" id="gender" type="radio" value="1" <?php echo (URL::get('gender')?'checked':'');?> />
			<label for="gender"><?php echo Portal::language('male');?></label>
			<input name="gender" id="gender1" type="radio" value="0" <?php echo (URL::get('gender')?'':'checked');?> />
		  <label for="gender1"><?php echo Portal::language('female');?></label>    </td>
            <td bgcolor="#EFEFEF"><?php echo Portal::language('restricted_access_ip');?>:</td>
            <td><input  name="restricted_access_ip" id="restricted_access_ip" style="width:150px" / type ="text" value="<?php echo String::html_normalize(URL::get('restricted_access_ip'));?>"> <span class="note">(<?php echo Portal::language('ex');?>: 192.168)</span></td>
	  </tr>
	<tr>
		<td bgcolor="#EFEFEF"><?php echo Portal::language('active');?>:</td>
		<td><input name="active" id="active" type="checkbox" onclick="check_active();" value="1" <?php echo (URL::get('active')?'checked':'');?> /></td>
        <td bgcolor="#EFEFEF"><?php echo Portal::language('language');?></td>
        <td><select  name="language_id" id="language_id"><?php
					if(isset($this->map['language_id_list']))
					{
						foreach($this->map['language_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('language_id',isset($this->map['language_id'])?$this->map['language_id']:''))
                    echo "<script>$('language_id').value = \"".addslashes(URL::get('language_id',isset($this->map['language_id'])?$this->map['language_id']:''))."\";</script>";
                    ?>
	</select></td>
    </tr>
	  <tr>
		<td bgcolor="#EFEFEF"><?php echo Portal::language('block');?>:</td>
		<td><input name="block" id="block" type="checkbox" onclick="check_block();" value="1" <?php echo (URL::get('block')?'checked':'');?> />    </td>
		<td bgcolor="#EFEFEF"><?php echo Portal::language('birth_day');?>:</td>
		<td><input  name="birth_day" id="birth_day" style="width:150px" type ="text" value="<?php echo String::html_normalize(URL::get('birth_day'));?>"></td>
	  </tr>
	  <tr>
		<td bgcolor="#EFEFEF"><?php echo Portal::language('address');?>:</td>
		<td><input  name="address" id="address" style="width:150px" / type ="text" value="<?php echo String::html_normalize(URL::get('address'));?>">    </td>
		<td bgcolor="#EFEFEF"><?php echo Portal::language('phone_number');?>:</td>
		<td><input  name="phone_number" id="phone_number" style="width:150px" / type ="text" value="<?php echo String::html_normalize(URL::get('phone_number'));?>">    </td>
	  </tr>
	  <tr>
		<td bgcolor="#EFEFEF"><?php echo Portal::language('zone_id');?>:</td>
		<td><select  name="zone_id" id="zone_id"><?php
					if(isset($this->map['zone_id_list']))
					{
						foreach($this->map['zone_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('zone_id',isset($this->map['zone_id'])?$this->map['zone_id']:''))
                    echo "<script>$('zone_id').value = \"".addslashes(URL::get('zone_id',isset($this->map['zone_id'])?$this->map['zone_id']:''))."\";</script>";
                    ?>
	
		</select>    </td>
		<td bgcolor="#EFEFEF"><?php echo Portal::language('join_date');?>:</td>
		<td><input  name="join_date" id="join_date" style="width:150px"/ type ="text" value="<?php echo String::html_normalize(URL::get('join_date'));?>">    </td>
	  </tr>
	  <tr>
		<td bgcolor="#EFEFEF"><?php echo Portal::language('home_page');?> (*):</td>
		<td><select  name="home_page" id="home_page" style="width:150px"><?php
					if(isset($this->map['home_page_list']))
					{
						foreach($this->map['home_page_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('home_page',isset($this->map['home_page'])?$this->map['home_page']:''))
                    echo "<script>$('home_page').value = \"".addslashes(URL::get('home_page',isset($this->map['home_page'])?$this->map['home_page']:''))."\";</script>";
                    ?>
	</select></td>
		<td bgcolor="#EFEFEF"><?php echo Portal::language('department');?>:</td>
		<td><select  name="description_1" id="description_1" style="width:150px"><?php
					if(isset($this->map['description_1_list']))
					{
						foreach($this->map['description_1_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('description_1',isset($this->map['description_1'])?$this->map['description_1']:''))
                    echo "<script>$('description_1').value = \"".addslashes(URL::get('description_1',isset($this->map['description_1'])?$this->map['description_1']:''))."\";</script>";
                    ?>
	
        <?php echo $this->map['department_list'];?>
        </select>
        <script>
            jQuery('#description_1').val('<?php echo $this->map['description_1'];?>');
        </script>
        </td>
	  </tr>
	</table>
		<fieldset <?php if(User::can_edit(false,ANY_CATEGORY)){echo 'style="display:block;padding:10px;"';} else{ echo 'style="display:none"';}?>>
			<legend class="title"><?php echo Portal::language('portal');?></legend>
                <!--luu nguyen giap add change room status column account -->
                <div style="float: left; width: 100%; margin-bottom: 10px;">
                <!--luu nguyen giap add change room status-->
                
                <input name="change_room_status" type="checkbox" onclick="Change_status(this)" id="change_room_status" <?php 
                if(isset($_REQUEST['change_room_status']) )
                {
                    if($_REQUEST['change_room_status']==1)
                    {
                       echo ' value="1" checked="checked" '; 
                    }
                    else
                    {
                        echo ' value="0"';
                    }
                }
                 
                ?>>
                <!-- end -->
                <label for="change_room_status"><?php echo Portal::language('change_room_status');?>(Cancel=>Book; Checkout=>Checkin)</label>
                </div>
                
                <div style="float: left; width: 100%; margin-bottom: 10px;">
                
                
                <input name="change_checkin_book" type="checkbox" id="change_checkin_book" onclick="Change_status(this)" <?php 
                if(isset($_REQUEST['change_checkin_book']) )
                {
                    if($_REQUEST['change_checkin_book']==1)
                    {
                       echo ' value="1" checked="checked" '; 
                    }
                    else
                    {
                        echo ' value="0"';
                    }
                }
                ?>>

                <label for="change_checkin_book"><?php echo Portal::language('change_room_status');?>(Checkin=>Book)</label>
                </div>
                <!-- end-->
				<span id="mi_portal_group_all_elems" style="text-align:left;">
					<span>
						<span class="multi-input-header" style="width:195px;"><?php echo Portal::language('portal_id');?></span>
						<span class="multi-input-header" style="width:155px;"><?php echo Portal::language('group_privilege');?></span>                        
						<span class="multi-input-header" style="width:120px;"><?php echo Portal::language('join_date');?></span>
						<span class="multi-input-header" style="width:80px;"><?php echo Portal::language('active');?></span>
						<br clear="all"/>
					</span>
				</span>
			<input class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; margin-top: 5px;" type="button" value="   <?php echo Portal::language('add_item');?>   " onclick="mi_add_new_row('mi_portal_group');">
		</fieldset> 
		<input type="hidden" value="1" name="confirm_edit"/>
		<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
	</div>
</div>
<script type="text/javascript">
<?php
    if(Url::get('cmd')=='edit')
    {
?>
var home_page = '<?php echo $_REQUEST['home_page']; ?>';
jQuery("#home_page option").each(function(){
    var value = jQuery(this).attr("value");
    if(value==home_page)
    {
        jQuery(this).attr("selected","selected");
    }
});
<?php
    }
?>
jQuery('#password').on('change', function() {
    var newpass= jQuery('#password').val();
        jQuery.ajax({
                type: "POST",
                data: "password="+newpass,
                url: "get_customer_search.php",
                success: function(result){ 
                    if(result == 2)
                    { 
                      alert('<?php echo Portal::language('notify_pass');?>');
                      return false;
                    }  
                }                 
            });
});
function savefrom(){
    <?php if(Url::get('cmd')=='edit'){?>
        document.getElementById("EditManageUserForm").submit();
    <?php }else{?>
          var newpass= jQuery('#password').val();
         if(newpass==''){
            alert('<?php echo Portal::language('empty_pass');?>');
         }else{
             jQuery.ajax({
                    type: "POST",
                    data: "password="+newpass,
                    url: "get_customer_search.php",
                    success: function(result){ 
                        if(result == 2)
                        { 
                          alert('<?php echo Portal::language('notify_pass');?>');
                          return false;
                        }else if(result == 3)
                        {
                            document.getElementById("EditManageUserForm").submit();   
                        }    
                    }                 
                });
         }
    <?php } ?>  
}


function check_active()
{
    if(jQuery('#active').attr('checked'))
    {
        if(jQuery('#block').attr('checked'))
        {
            jQuery('#block').prop('checked',false);
        }
    }
}

function check_block()
{
    if(jQuery('#block').attr('checked'))
    {
        if(jQuery('#active').attr('checked'))
        {
            jQuery('#active').prop('checked',false);
        }
    }
}

var data = <?php echo String::array2autosuggest($this->map['users']);?>;
jQuery(document).ready(function(){
	jQuery("#id").autocomplete(data,{
		minChars: 0,
		width: 305,
		matchContains: true,
		autoFill: false,
		formatItem: function(row, i, max) {
			return '<span style="color:#993300"> ' + row.name + '</span>';
		},
		formatMatch: function(row, i, max) {
			return row.id + ' ' + row.name;
		},
		formatResult: function(row) {			
			return row.id;
		}
	});
});
jQuery(function(){
	jQuery('#birth_day').datepicker();
	jQuery('#join_date').datepicker();
});
<?php 
				if((Url::get('cmd')=='edit'))
				{?>
$('id').readOnly = true;

				<?php
				}
				?>
mi_init_rows('mi_portal_group',<?php if(isset($_REQUEST['mi_portal_group'])){echo String::array2js($_REQUEST['mi_portal_group']);}else{echo '{}';}?>); 

function Change_status(obj)
{
    if(obj.checked)
    {
        obj.value =1;
    }
    else
    {
        obj.value =0;
    }
}
</script>