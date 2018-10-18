<span style="display:none">
	<span id="MargeRoomInvoice_sample">
		<div id="input_group_#xxxx#">
			<span class="multi-edit-input">
					<input  name="MargeRoomInvoice[#xxxx#][invoice_id]" style="width:150px; height: 32px;" class="multi-edit-text-input" type="text" id="invoice_id_#xxxx#">
			</span>
			<?php 
				if((Url::get('act')=='marge_invoice'))
				{?>
			<span class="multi-edit-input">
					<input  name="MargeRoomInvoice[#xxxx#][other_invoice_id]" style="width:150px; height: 32px;" class="multi-edit-text-input" type="text" id="other_invoice_id_#xxxx#">
			</span>
			
				<?php
				}
				?>
			<span class="multi_edit_input"><span style="width:20px;">
				<a class="w3-btn w3-red" href="" onClick="mi_delete_row($('input_group_#xxxx#'),'MargeRoomInvoice','#xxxx#','');" style="cursor:pointer; text-transform: uppercase; text-decoration: none;"><?php echo Portal::language('delete');?></a>
			</span></span>
		</div><br clear="all">
	</span>
</span>
<?php System::set_page_title(HOTEL_NAME.' - '.Portal::language('title')); ?>
<form name="EditMinibarForm" method="post" >
<table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
	<tr>
		<td width="55%" style="text-transform: uppercase; font-size: 20px;"><i class="fa fa-copy w3-text-orange" style="font-size: 30px;"></i> <?php echo Portal::language('marge_or_split_room_from_group');?></td>
		<?php if(User::can_add(false,ANY_CATEGORY)){?><td width="1%"><input type="submit" value="<?php echo Portal::language('Save_and_close');?>" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase;"/></td><?php }?>
	</tr>
</table>
<div class="global-tab">
	<div class="header">
		<a style="height: 24px; width: 70px; margin-left: 5px; text-align: center; text-transform: uppercase; text-decoration: none;" href="<?php echo Url::build_current(array('act'=>'marge_invoice'));?>" <?php echo (Url::get('act')=='marge_invoice')?'class="selected"':'';?>><?php echo Portal::language('marge');?></a>
		<a style="height: 24px; width: 70px; text-align: center; text-transform: uppercase; text-decoration: none;" href="<?php echo Url::build_current(array('act'=>'split_invoice'));?>" <?php echo (Url::get('act')=='split_invoice')?'class="selected"':'';?>><?php echo Portal::language('split');?></a>
	</div>
	<div class="body">
	<table cellspacing="0" width="400">
		<tr><td style="padding-bottom:30px">
			<input  name="selected_ids" id="selected_ids" type="hidden" value="<?php echo URL::get('selected_ids');?>">
			<input  name="deleted_ids" id="deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>">
			<?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
			<table border="0">
			<tr valign="top">
				<td style="">
				<div>
					<span id="MargeRoomInvoice_all_elems">
						<span style="white-space:nowrap; width:auto;">
							<span class="multi-edit-input_header"><span class="" style="float:left;width:155px;text-align:left;padding-left:2px;"><?php echo Portal::language('bill_number');?></span></span>
							<?php 
				if((Url::get('act')=='marge_invoice'))
				{?>
							<span class="multi-edit-input_header"><span class="" style="float:left;width:155px;text-align:left;padding-left:2px;"><?php echo Portal::language('other_bill_number');?></span></span>
							
				<?php
				}
				?>
							<br clear="all">
						</span>
					</span>
				</div>
				<div>
					<!--<a href="javascript:void(0);" onclick="mi_add_new_row('MargeRoomInvoice');" class="w3-btn w3-cyan w3-text-white" style="text-transform: uppercase; text-decoration: none;"><?php echo Portal::language('Add');?></a></div>-->
	</td></tr></table></td></tr></table>
	</div>
</div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<script>
<?php 	if(isset($_REQUEST['MargeRoomInvoice'])){
			echo 'var MargeRoomInvoice = '.String::array2js($_REQUEST['MargeRoomInvoice']).';';
		}else{
			echo 'var MargeRoomInvoice = [];';
		}
?>
mi_init_rows('MargeRoomInvoice',MargeRoomInvoice);
</script>