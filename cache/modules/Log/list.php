<form name="LogListForm"  method="post">
<table cellspacing="0" cellpadding="10" width="100%">
	<tr valign="top">
		<td align="center" colspan="2">
			<h2><?php echo Portal::language('list_title_log');?></h2>
		</td>
	</tr>
	<tr valign="top">
		<td>
			<table cellspacing="0" width="100%">
			<tr>
				<td width="100%">
					<fieldset>
					<legend class="title"><?php echo Portal::language('search');?></legend>
					<table>
						<tr>
						  <td nowrap><?php echo Portal::language('keyword');?>: <input  name="keyword" id="keyword" style="width:120px;" / type ="text" value="<?php echo String::html_normalize(URL::get('keyword'));?>"></td>
						  <td nowrap><?php echo Portal::language('date_from');?>: <input  name="date_from" id="date_from" style="width:70px;" onchange="changevalue();" / type ="text" value="<?php echo String::html_normalize(URL::get('date_from'));?>"></td>
						  <td nowrap><?php echo Portal::language('date_to');?>: <input  name="date_to" id="date_to" style="width:70px;" onchange="changefromday();" / type ="text" value="<?php echo String::html_normalize(URL::get('date_to'));?>"></td>
                          
                        <td><?php echo Portal::language('module_id');?>: </td>
                        <td><select  name="module_id" id="module_id" onchange="log_filter();"><?php
					if(isset($this->map['module_id_list']))
					{
						foreach($this->map['module_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('module_id',isset($this->map['module_id'])?$this->map['module_id']:''))
                    echo "<script>$('module_id').value = \"".addslashes(URL::get('module_id',isset($this->map['module_id'])?$this->map['module_id']:''))."\";</script>";
                    ?>
	</select></td>
						<td nowrap><?php echo Portal::language('user_id');?></td>
						<td>:</td>
						<td nowrap>
							<select  name="user_id" id="user_id" onchange="log_filter();"><?php
					if(isset($this->map['user_id_list']))
					{
						foreach($this->map['user_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('user_id',isset($this->map['user_id'])?$this->map['user_id']:''))
                    echo "<script>$('user_id').value = \"".addslashes(URL::get('user_id',isset($this->map['user_id'])?$this->map['user_id']:''))."\";</script>";
                    ?>
	</select></td>
                        <td nowrap><?php echo Portal::language('room_id');?></td>
                        <td nowrap>
                            <select  name="room_id" id="room_id" onchange="log_filter();"><?php
					if(isset($this->map['room_id_list']))
					{
						foreach($this->map['room_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('room_id',isset($this->map['room_id'])?$this->map['room_id']:''))
                    echo "<script>$('room_id').value = \"".addslashes(URL::get('room_id',isset($this->map['room_id'])?$this->map['room_id']:''))."\";</script>";
                    ?>
	</select>
						  	<input type="submit"  onclick="this.disable=true;" value="<?php echo Portal::language('searh');?>">
							<?php if(User::is_admin()){ ?><input name="delete_selected" type="submit"  id="delete_selected" onClick="if(!confirm('<?php echo Portal::language('are_you_sure');?>?')){return false;}" value="<?php echo Portal::language('delete_all');?>"><?php } ?>
						</td>
						</tr>  
					</table>
					</fieldset>
					<p><?php echo $this->map['paging'];?></p><br/>
					<table width="100%" cellpadding="5" cellspacing="0" border="1" bordercolor="#CCCCCC">
						<tr class="table-header">
							<th width="1%"><input type="checkbox" value="1" onclick="var checkboxes = document.getElementsByName('selected_ids[]');for(var i=0;i<checkboxes.length;i++) checkboxes[i].checked=this.checked;"></th>
							<th nowrap align="left">
								<?php echo Portal::language('time');?>
							</th>
							<th nowrap align="left">
								<?php echo Portal::language('module_id');?>
							</th>
							<th nowrap align="left">
								<?php echo Portal::language('type');?>
							</th>
							<th nowrap align="left">
								<?php echo Portal::language('user_id');?>
							</th>
							<th align="left">
								<?php echo Portal::language('title');?>
							</th>
							<th align="left" nowrap="nowrap">
								<?php echo Portal::language('note');?>
							</th>
							<th>&nbsp;</th>
							<th>&nbsp;</th>
						</tr>
						<?php $last_date = false;?><?php if(isset($this->map['items']) and is_array($this->map['items'])){ foreach($this->map['items'] as $key1=>&$item1){if($key1!='current'){$this->map['items']['current'] = &$item1;?>
						<?php
						if($last_date!=$this->map['items']['current']['in_date'])
						{
							$last_date=$this->map['items']['current']['in_date'];
							echo '<tr bgcolor="#FFFF80"><td colspan="9">'.$this->map['items']['current']['in_date'].'</td></tr>';
						}
						?><tr bgcolor="white" valign="top">
							<td><input name="selected_ids[]" type="checkbox" value="<?php echo $this->map['items']['current']['id'];?>" onclick="just_click=true;"></td>
							<td nowrap align="left">
								<?php echo date('d/m/Y H:i:s',$this->map['items']['current']['time']);?>
							</td>
							<td nowrap align="left">
								<?php echo $this->map['items']['current']['module_name'];?>
							</td>
							<td nowrap align="left">
								<?php echo $this->map['items']['current']['type'];?>
							</td>
							<td nowrap align="left">
							  <strong><?php echo $this->map['items']['current']['user_id'];?>						    </strong></td>
							
							<td align="left" width="100%">
								<?php echo $this->map['items']['current']['title'];?>
							</td>
							<td nowrap align="left">
								<?php echo $this->map['items']['current']['note'];?>
							</td>
							<td nowrap>
								<?php if(User::is_admin()){ ?>&nbsp;<a href="<?php echo Url::build_current(array('cmd'=>'edit','id'=>$this->map['items']['current']['id'])); ?>"><img src="packages/core/skins/default/images/buttons/edit.gif" alt="<?php echo Portal::language('edit');?>" width="12" height="12" border="0"></a><?php } ?>
							</td>
							<td nowrap>
								<?php if(User::is_admin()){ ?>&nbsp;<a href="<?php echo Url::build_current(array('cmd'=>'delete','id'=>$this->map['items']['current']['id'])); ?>"><img src="packages/core/skins/default/images/buttons/delete.gif" alt="<?php echo Portal::language('delete');?>" width="12" height="12" border="0"></a> <?php } ?>
							</td>
						</tr>
						<tr bgcolor="#EEEEEE"><td colspan="9"><?php echo $this->map['items']['current']['description'];?></td></tr>
						<?php }}unset($this->map['items']['current']);} ?>
					</table>
				</td>
			</tr>
			</table>
            <br />
            <br />
			<?php echo $this->map['paging'];?>
		</td>
	</tr>
</table>	
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script>
jQuery('#date_from').datepicker();
jQuery('#date_to').datepicker();
function changevalue()
    {
        var myfromdate = $('date_from').value.split("/");
        var mytodate = $('date_to').value.split("/");
        
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#date_to").val(jQuery("#date_from").val());
        }
    }
    function changefromday()
    {
        var myfromdate = $('date_from').value.split("/");
        var mytodate = $('date_to').value.split("/");
        var arr_myfromdate = new Date(myfromdate[2],myfromdate[1],myfromdate[0]);
        var arr_mytodate = new Date(mytodate[2],mytodate[1],mytodate[0]);
        if(arr_myfromdate>arr_mytodate)
        {
            jQuery("#date_from").val(jQuery("#date_to").val());
        }
    }
function log_filter()
{
	location='<?php echo URL::build_current(array('year','month','day'));?>'
		+(($('type').value!='')?'&type='+$('type').value:'')
		+(($('module_id').value!='')?'&module_id='+$('module_id').value:'')
		+(($('user_id').value!='')?'&user_id='+$('user_id').value:'');
}
full_screen();
</script>
