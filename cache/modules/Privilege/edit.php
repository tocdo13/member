<script src="<?php echo Portal::template('core');?>/css/tabs/tabpane.js" type="text/javascript"></script>
<?php 
$title = (URL::get('cmd')=='edit')?Portal::language('edit_privilege'):Portal::language('add_privilege');
$action = (URL::get('cmd')=='edit')?'edit':'add';
System::set_page_title(HOTEL_NAME.' - '.$title);?>
<div class="form-bound">
	<table cellpadding="15" width="100%">
        <tr>
            <td  class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;" width="70%"><?php echo $title;?></td>
            <td><a class="w3-orange w3-btn w3-text-white" href="javascript:void(0)" onclick="EditPrivilegeForm.submit();" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('save');?></a>
    			<a class="w3-btn w3-green" href="javascript:void(0)" onclick="location=\'<?php echo URL::build_current();?>\';" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('back');?></a>
    		<?php if($action=='edit'){?>
            
    			<a class="w3-btn w3-red" href="javascript:void(0)" onclick="location=\'<?php echo URL::build_current(array('cmd'=>'delete','id'));?>\';" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;"><?php echo Portal::language('Delete');?></a>
    			<a class="w3-btn w3-lime" href="javascript:void(0)" onclick="location=\'<?php echo URL::build_current(array('cmd'=>'grant','id'));?>\';" style="text-transform: uppercase; text-decoration: none; "><?php echo Portal::language('grant');?></a></td><?php }?>
    		<!---<td class="form-title-button">
    			<a  href="<?php echo URL::build('default');?>"><img src="<?php echo Portal::template('core');?>/images/buttons/frontpage.gif" alt=""/><br />Trang ch&#7911;</a></td>--->
        </tr>
   </table>
	<div class="form-content" style="width:1000px;">
<?php if(Form::$current->is_error())
		{
		?>
		<strong>B&#225;o l&#7895;i</strong><br>
		<?php echo Form::$current->error_messages();?><br>
		<?php
		}
		?>
		<form name="EditPrivilegeForm" method="post"  action="?<?php echo htmlentities($_SERVER['QUERY_STRING']);?>">
		<div class="tab-pane-1" id="tab-pane-ecommerce_product">
		<?php if(isset($this->map['languages']) and is_array($this->map['languages'])){ foreach($this->map['languages'] as $key1=>&$item1){if($key1!='current'){$this->map['languages']['current'] = &$item1;?>
		<div class="tab-page" id="tab-page-ecommerce_product-<?php echo $this->map['languages']['current']['id'];?>">
			<h2 class="tab"><?php echo $this->map['languages']['current']['name'];?></h2>
			<div class="form_input_label"><?php echo Portal::language('title');?>:</div>
			<div class="form_input">
					<input  name="name_<?php echo $this->map['languages']['current']['id'];?>" id="name_<?php echo $this->map['languages']['current']['id'];?>" style="width:300px;" type ="text" value="<?php echo String::html_normalize(URL::get('name_'.$this->map['languages']['current']['id']));?>">
			</div><div class="form_input_label"><?php echo Portal::language('description');?>:</div>
			<div class="form_input">
					<textarea  name="description_<?php echo $this->map['languages']['current']['id'];?>" id="description_<?php echo $this->map['languages']['current']['id'];?>" style="width:100%" rows="10"><?php echo String::html_normalize(URL::get('description_'.$this->map['languages']['current']['id'],''));?></textarea><br />
			</div>
		</div>
		<?php }}unset($this->map['languages']['current']);} ?>
		</div>
		<br clear="all">
        <div class="form_input_label"><?php echo Portal::language('home_page');?>:</div>
        <div class="form_input">
        	<input  name="home_page" id="home_page" / type ="text" value="<?php echo String::html_normalize(URL::get('home_page'));?>">
        </div>	    
        <div class="form_input_label"><?php echo Portal::language('package_id');?>:</div>
		<div class="form_input">
				<select  name="package_id" id="package_id"><?php
					if(isset($this->map['package_id_list']))
					{
						foreach($this->map['package_id_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('package_id',isset($this->map['package_id'])?$this->map['package_id']:''))
                    echo "<script>$('package_id').value = \"".addslashes(URL::get('package_id',isset($this->map['package_id'])?$this->map['package_id']:''))."\";</script>";
                    ?>
	</select>
		</div>
		<div class="form_input_label"><?php echo Portal::language('status');?>:</div>
		<div class="form_input">
				<input  name="is_active" id="is_active" / type ="checkbox" value="<?php echo String::html_normalize(URL::get('is_active'));?>">
		</div>
	<input type="hidden" value="1" name="confirm_edit"/>
	<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
	</div>
</div>
