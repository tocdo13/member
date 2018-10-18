<script src="<?php echo Portal::template('core');?>/css/tabs/tabpane.js" type="text/javascript"></script>
<?php 
$title = (URL::get('cmd')=='edit')?Portal::language('edit_privilege'):Portal::language('add_privilege');
$action = (URL::get('cmd')=='edit')?'edit':'add';
System::set_page_title(HOTEL_NAME.' - '.$title);?>
<div class="form-bound">
	<table cellpadding="15" width="100%">
        <tr>
            <td  class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;" width="70%"><?php echo $title;?></td>
            <td><a class="w3-orange w3-btn w3-text-white" href="javascript:void(0)" onclick="EditPrivilegeForm.submit();" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.save.]]</a>
    			<a class="w3-btn w3-green" href="javascript:void(0)" onclick="location=\'<?php echo URL::build_current();?>\';" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.back.]]</a>
    		<?php if($action=='edit'){?>
            
    			<a class="w3-btn w3-red" href="javascript:void(0)" onclick="location=\'<?php echo URL::build_current(array('cmd'=>'delete','id'));?>\';" style="text-transform: uppercase; text-decoration: none; margin-right: 5px;">[[.Delete.]]</a>
    			<a class="w3-btn w3-lime" href="javascript:void(0)" onclick="location=\'<?php echo URL::build_current(array('cmd'=>'grant','id'));?>\';" style="text-transform: uppercase; text-decoration: none; ">[[.grant.]]</a></td><?php }?>
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
		<!--LIST:languages-->
		<div class="tab-page" id="tab-page-ecommerce_product-[[|languages.id|]]">
			<h2 class="tab">[[|languages.name|]]</h2>
			<div class="form_input_label">[[.title.]]:</div>
			<div class="form_input">
					<input name="name_[[|languages.id|]]" type="text" id="name_[[|languages.id|]]" style="width:300px;">
			</div><div class="form_input_label">[[.description.]]:</div>
			<div class="form_input">
					<textarea name="description_[[|languages.id|]]" id="description_[[|languages.id|]]" style="width:100%" rows="10"></textarea><br />
			</div>
		</div>
		<!--/LIST:languages-->
		</div>
		<br clear="all">
        <div class="form_input_label">[[.home_page.]]:</div>
        <div class="form_input">
        	<input name="home_page" type="text" id="home_page" />
        </div>	    
        <div class="form_input_label">[[.package_id.]]:</div>
		<div class="form_input">
				<select name="package_id" id="package_id"></select>
		</div>
		<div class="form_input_label">[[.status.]]:</div>
		<div class="form_input">
				<input name="is_active" type="checkbox" id="is_active" />
		</div>
	<input type="hidden" value="1" name="confirm_edit"/>
	</form>
	</div>
</div>
