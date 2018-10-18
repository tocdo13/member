<?php 
$title = (URL::get('cmd')=='delete')?Portal::language('Delete_users'):Portal::language('Users');
$action = (URL::get('cmd')=='delete')?'delete':'list';
System::set_page_title(HOTEL_NAME.' - '.$title);?>
<link href="<?php echo Portal::template('core');?>/css/category.css" rel="stylesheet" type="text/css" />
<div class="form_bound">
<form name="ListManageUserForm" method="post">
    <table cellpadding="0" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
        <tr style="width: 100%;">
            <td class="" width="60%" style="text-transform: uppercase; float: left; font-size: 18px; padding-left: 15px;"><i class="fa fa-user w3-text-orange" style="font-size: 26px;"></i> <?php echo $title;?></td>
            <?php if(URL::get('cmd')=='delete'){?>
            <td width="40%" style="float: right; padding-right: 30px;"><a class="w3-btn w3-red" style="text-transform: uppercase; margin-right: 5px; text-decoration: none;" href="javascript:void(0)" onclick="ListManageUserForm.submit();"><?php echo Portal::language('Delete');?></a>
            <a class="w3-btn w3-green" style="text-transform: uppercase; margin-right: 5px; text-decoration: none;" href="<?php echo URL::build_current(array('join_date_start','join_date_end',  'active'=>isset($_GET['active'])?$_GET['active']:'', 'block'=>isset($_GET['block'])?$_GET['block']:'',  'user_id'=>isset($_GET['user_id'])?$_GET['user_id']:''));?>"><?php echo Portal::language('back');?></a></td>
            <?php }else{ if(User::can_add(false,ANY_CATEGORY)){?>
            <td>
            <a class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; margin-right: 5px; text-decoration: none;" href="<?php echo URL::build_current(array()+array('cmd'=>'add'));?>"><?php echo Portal::language('Add');?></a>
            <?php }?>
            <?php if(User::can_delete(false,ANY_CATEGORY)){?>
            <a class="w3-btn w3-red" style="text-transform: uppercase; text-decoration: none;" href="javascript:void(0)" onclick="ListManageUserForm.cmd.value='delete';ListManageUserForm.submit();"><?php echo Portal::language('Delete');?></a></td>
            <?php }}?>
        </tr>
    </table>
	<div>
            <fieldset>
                <legend class=""><?php echo Portal::language('search');?></legend>
					<table>
						<tr>
						  	<td align="right" nowrap><?php echo Portal::language('username');?></td>
							<td>:</td>
							<td nowrap>
								<input  name="user_name" id="user_name" style="width:150px; height: 24px;"/ type ="text" value="<?php echo String::html_normalize(URL::get('user_name'));?>">
							</td>
							<?php 
				if((User::can_admin(false,ANY_CATEGORY)))
				{?>
							<td align="right" nowrap><?php echo Portal::language('portal_name');?></td>
							<td>:</td>
							<td><select  name="portal_id" id="portal_id" style=" height: 24px;"><?php
					if(isset($this->map['portal_id_list']))
					{
						foreach($this->map['portal_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))
                    echo "<script>$('portal_id').value = \"".addslashes(URL::get('portal_id',isset($this->map['portal_id'])?$this->map['portal_id']:''))."\";</script>";
                    ?>
	</select></td>
                            <td><?php echo Portal::language('status');?></td>
                            <td><select  name="account_status" id="account_status" style=" height: 24px;"><?php
					if(isset($this->map['account_status_list']))
					{
						foreach($this->map['account_status_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('account_status',isset($this->map['account_status'])?$this->map['account_status']:''))
                    echo "<script>$('account_status').value = \"".addslashes(URL::get('account_status',isset($this->map['account_status'])?$this->map['account_status']:''))."\";</script>";
                    ?>
	</select></td>
							
				<?php
				}
				?>
							<td><input type="hidden" name="page_no" value="1"  style=" height: 24px;"/>
								<input type="submit" value="   <?php echo Portal::language('search');?>   " style=" height: 24px;"/>
							</td>
						</tr>
					</table>
					
					
					</fieldset><br />
					<table width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#CCCCCC" rules="cols">
						<tr class="w3-light-gray" style="height: 30px; text-transform: uppercase;">
							<th width="10" title="<?php echo Portal::language('check_all');?>"><input type="checkbox" value="1" id="ManageUser_all_checkbox" onclick="select_all_checkbox(this.form, 'ManageUser',this.checked,'#FFFFEC','white');"<?php if(URL::get('cmd')=='delete') echo ' checked';?>></th>
							<th width="30" align="center"  ><?php echo Portal::language('stt');?></th>
							<th width="100" align="center"  >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='account.id' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'account.id'));?>" title="<?php echo Portal::language('sort');?>">
								<?php if(URL::get('order_by')=='account.id') echo '<img src="'.Portal::template('core').'/images/buttons/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
								<?php echo Portal::language('user_name');?>								</a>							</th>
								<th width="150" align="center"  ><?php echo Portal::language('full_name');?></th>
								<th width="150" align="center" ><?php echo Portal::language('portal_name');?></th>
                                <th width="150" align="center" ><?php echo Portal::language('group_privilege');?></th>
								<th width="80" align="center"  >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='account.active' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'account.active'));?>" title="<?php echo Portal::language('sort');?>">
								<?php if(URL::get('order_by')=='account.active') echo '<img src="'.Portal::template('core').'/images/buttons/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
								<?php echo Portal::language('active');?>								</a>							</th>
                                <th width="100" align="center"  >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='account.block' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'account.block'));?>" title="<?php echo Portal::language('sort');?>">
								<?php if(URL::get('order_by')=='account.block') echo '<img src="'.Portal::template('core').'/images/buttons/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
								<?php echo Portal::language('block');?>								</a>
							</th><th width="70" align="center"  >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='account.create_date' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'account.create_date'));?>" title="<?php echo Portal::language('sort');?>">
								<?php if(URL::get('order_by')=='account.create_date') echo '<img src="'.Portal::template('core').'/images/buttons/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
								<?php echo Portal::language('join_date');?>								</a>
							</th><th width="150" align="center" >
								<a href="<?php echo URL::build_current(((URL::get('order_by')=='zone_id' and URL::get('order_dir')!='desc')?array('order_dir'=>'desc'):array())+array('order_by'=>'zone_id'));?>" title="<?php echo Portal::language('sort');?>">
								<?php if(URL::get('order_by')=='zone_id') echo '<img src="'.Portal::template('core').'/images/buttons/'.((URL::get('order_dir')!='desc')?'down':'up').'_arrow.gif">';?>
								<?php echo Portal::language('zone_id');?>								</a>
							</th>
							<th width="100" align="center" >IP</th>
							<th width="60" align="center" ><?php echo Portal::language('status');?></th>
                            <th width="90" align="center" ><?php echo Portal::language('change_password');?></th>
							<!--<th width="5%" align="left" ><?php echo Portal::language('grant_privilege');?></th>	-->						
							<th width="40" align="center"><?php echo Portal::language('edit');?></th>
						</tr>
						<?php $temp = '';?>
						<?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
						<?php if($temp!=$this->map['items']['current']['description_1']){$temp = $this->map['items']['current']['description_1'];?>
						<tr>
						  <td colspan="14" class="category-group"><?php echo $this->map['items']['current']['description_1'];?></td>
					  </tr>
					  <?php }?>
						<tr <?php echo ($this->map['items']['current']['i']%2==0)?'class="row-odd"':'class="row-even"';?>>
							<td><input name="selected_ids[]" type="checkbox" value="<?php echo $this->map['items']['current']['id'];?>" onclick="select_checkbox(this.form,'ManageUser',this,'#FFFFEC','white');" id="ManageUser_checkbox" <?php if(URL::get('cmd')=='delete') echo 'checked';?>></td>
							<td nowrap align="left"><?php echo $this->map['items']['current']['i'];?></td>
							<td nowrap align="left"><?php echo $this->map['items']['current']['id'];?></td>
                            <td nowrap align="left"><?php echo $this->map['items']['current']['full_name'];?></td>
                            <td nowrap align="left">
								<ul>
								<?php if(isset($this->map['items']['current']['portals']) and is_array($this->map['items']['current']['portals'])){ foreach($this->map['items']['current']['portals'] as $key2=>&$item2){if($key2!='current'){$this->map['items']['current']['portals']['current'] = &$item2;?>
								<li style="list-style:inside"><?php echo $this->map['items']['current']['portals']['current']['name'];?></li>
								<?php }}unset($this->map['items']['current']['portals']['current']);} ?>
								</ul>							</td>
                            <td nowrap align="left">
								<ul>
								<?php if(isset($this->map['items']['current']['group_privilege']) and is_array($this->map['items']['current']['group_privilege'])){ foreach($this->map['items']['current']['group_privilege'] as $key3=>&$item3){if($key3!='current'){$this->map['items']['current']['group_privilege']['current'] = &$item3;?>
								<li style="list-style:inside"><?php echo $this->map['items']['current']['group_privilege']['current']['name'];?></li>
								<?php }}unset($this->map['items']['current']['group_privilege']['current']);} ?>
								</ul>							</td>                                
                            <td nowrap align="center"><?php echo $this->map['items']['current']['active'];?></td>
                            <td nowrap align="center"><?php echo $this->map['items']['current']['block'];?></td>
                            <td nowrap align="center"><?php echo $this->map['items']['current']['create_date'];?></td>
                            <td align="left" nowrap><?php echo $this->map['items']['current']['zone_id'];?></td>
							<td align="center" nowrap><?php echo $this->map['items']['current']['ip'];?></td>
							<td align="center" nowrap><a href="<?php echo Url::build_current(array('logoff_user_id'=>$this->map['items']['current']['id']));?>"><?php echo $this->map['items']['current']['status'];?></a></td>
                            <td nowrap align="center"><input type="button" value="<?php echo Portal::language('change_password');?>" onclick="open_form_change_pass(<?php echo '\''.$this->map['items']['current']['id'].'\'';?>)" /></td>
							<!--<td align="center" nowrap><a href="<?php echo Url::build('grant_moderator',array('cmd'=>'grant','account_id'=>$this->map['items']['current']['id']));?>"><img src="packages/core/skins/default/images/buttons/privilege.png"></a>&nbsp;</td>-->
							<td align="center" nowrap><a href="<?php echo Url::build_current(array('cmd'=>'edit','id'=>$this->map['items']['current']['id']));?>"><img src="packages/core/skins/default/images/buttons/edit.gif" /></a></td>
						</tr>
						<?php }}unset($this->map['items']['current']);} ?>
				  </table>
                    <input type="hidden" name="cmd" />
					<input type="hidden" name="page_no" value="1"/>
					<?php 
				if((URL::get('cmd')=='delete'))
				{?>
					<input type="hidden" name="confirm" value="1" />
					
				<?php
				}
				?>
					
			<?php echo $this->map['paging'];?>
	</div>
    <div id="change_form" style="display:none; position: fixed; top:30%; left:40%; width: 300px;height: 150px; border: 1px solid yellow; background-color: #a3c0c2;">
        <table cellpadding="0" cellspacing="0" width="100%" border="0">
            <tr>
                <td style="padding-top: 5px;" align="center"><?php echo Portal::language('change_pass_for_account');?> <span id="account_change"></span></td>
                <td><a href="javascript:void(0)" onclick="hide_form_change_pass()">X</a></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td> &nbsp;&nbsp;<?php echo Portal::language('new_password');?> :<input id="pass_change" type="text" style="width: 150px;height: 20px;" /></td>
                <td></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td align="center"><span style="color:red;font-style:italic;"><?php echo Portal::language('notify_pass');?></span></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td align="center"><input type="button" value="<?php echo Portal::language('change_password');?>" onclick="reset_password()" /></td>
                <td></td>
            </tr>
            <tr>
                <td align="center"><span id="status_change"></span></td>
                <td></td>
            </tr>
        </table>
        <input type="hidden" name="id_change" id="id_change" value="" />
    </div>
    </div>
    <input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
</div>
<script>
function hide_form_change_pass(){
    jQuery('#change_form').css('display','none');
    jQuery('#pass_change').val('');
    jQuery('#status_change').html('');
}
function open_form_change_pass(id){
    jQuery('#account_change').html(id);
    jQuery('#change_form').css('display','block');
	jQuery("#change_form").fadeIn(300);
    jQuery('#id_change').val(id);
}
function change(){
    
}
function reset_password(){
    if(jQuery('#pass_change').val()==''){
        alert('<?php echo Portal::language('empty_pass');?>');
    }else{
        jQuery.ajax({
        type: "POST",
        data: "password="+jQuery('#pass_change').val(),
        url: "get_customer_search.php",
        success: function(result){ 
        if(result == 2)
            { 
                alert('<?php echo Portal::language('notify_pass');?>');
                return false;
            }else if(result == 3)
            {  
            var id=jQuery('#id_change').val();
                jQuery.ajax({
                type: "POST",
                data: {"change_password":id,"pass":jQuery('#pass_change').val()},
                url: "get_customer_search.php",
                success: function(result){
                    if(result == 1)
                    {
                       jQuery('#status_change').html('<?php echo Portal::language('successfull');?>');
                       setTimeout(hide_form_change_pass,800); 
                    }else
                    {
                        jQuery('#status_change').html('<?php echo Portal::language('unsuccessfull');?>');
                        jQuery('#status_change').focus();  
                    }    
                }                 
                });   
           }    
        }                 
        });
    }
}
</script>