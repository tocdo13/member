<fieldset id="toolbar" style="margin-top:2px;">
<form name="ImportForm" method="post" enctype="multipart/form-data">
<?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
<table cellspacing="0" cellpadding="5" width="100%" border="0">	
	<tr valign="top">
		<td align="left" colspan="2">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;" width="60%"><i class="fa fa-file-excel-o w3-text-green" style="font-size: 26px;"></i> <?php echo Portal::language('import_product');?></td>
					<td width="40%" align="right" style="padding-right: 30px;">
                    <input name="save" type="submit" value="<?php echo Portal::language('Save_and_close');?>" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; margin-right: 5px;"/>
					<input type="button" value="<?php echo Portal::language('back');?>" class="w3-btn w3-green" onclick="history.go(-1)" style="text-transform: uppercase;"/></td>                    
                </tr>
            </table>
		</td>
	</tr>	
    
    <tr>
		<td colspan="2">
        	<?php if(Url::get('action')=='success'){?>
			<div style="font-weight:bold;color:#009900;"><?php echo Portal::language('update_sucess');?></div>            
            <?php }?>
			<b><?php echo Portal::language('Notice');?></b> : <div><?php echo Portal::language('help_import_data_to_excel');?></div>
			<div style="padding-left:100px;font-weight:bold"><?php echo Portal::language('format_column');?></div>
			<table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                    <td align="right">A :</td>
                    <td><?php echo Portal::language('code');?></td>
                </tr>
                <tr>
                    <td align="right">B :</td>
                    <td><?php echo Portal::language('name_product_vi');?></td>
                </tr>
                <tr>
                    <td align="right">C :</td>
                    <td><?php echo Portal::language('name_product_en');?></td>
                </tr>                
                <tr>
                    <td align="right">D :</td>
                    <td><?php echo Portal::language('unit');?>(<?php echo Portal::language('if_null_then');?> <?php echo Portal::language('default');?> : <?php echo Portal::language('piece');?>)</td>
                </tr>
                <tr>
                    <td align="right">E :</td>
                    <td><?php echo Portal::language('type');?>(<?php echo Portal::language('if_null_then');?> <?php echo Portal::language('default');?> : GOODS)</td>
                </tr>
                <tr>
                    <td align="right">F :</td>
                    <td><?php echo Portal::language('category');?></td>
                </tr>       
                <tr>
                    <td align="right">G :</td>
                    <td><?php echo Portal::language('warehouse_code');?></td>
                </tr>  
                <tr>
                    <td align="right">H :</td>
                    <td><?php echo Portal::language('quantity');?></td>
                </tr>  
                <tr>
                    <td align="right">I :</td>
                    <td><?php echo Portal::language('total_money');?></td>
                </tr>           
			</table>
		</td>
	</tr>
    <tr>
		<td align="right"><?php echo Portal::language('path_file_excel');?></td>
		<td align="left">
			<input id="path" name="path" type="file" value="<?php echo Portal::language('file');?>" id="path"/>
		</td>
	</tr>
    
    <tr>
    	<td>
    	<td><input name="do_upload" type="submit" id="do_upload" value="<?php echo Portal::language('upload_and_preview');?>" style="width: 180px;"/></td>
    </tr>
</table>
<input  name="confirm" id="confirm" value="1"/ type ="hidden" value="<?php echo String::html_normalize(URL::get('confirm'));?>">
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
</fieldset>

<script>
jQuery(document).ready(function(){
    
    jQuery('#do_upload').click(function(){
        if(!jQuery('#path').val())
        {
            alert('<?php echo Portal::language('you_must_choose_file');?>')
            return false;
        }
        
    })
})
</script>
<?php 
				if((isset($this->map['errors']) and !empty($this->map['errors'])))
				{?>
    <h4 style="color: red;"><?php echo Portal::language('this_file_contains_errors_do_you_want_to_upload');?></h4>

				<?php
				}
				?>
<table width="100%" border="1" cellspacing="0" cellpadding="3" bordercolor="#CCCCCC">
<?Php
    if(isset($this->map['errors']) and !empty($this->map['errors'])){ //neu co loi thi show loi ra
?>
    <tr style="text-align: center;">
        <th><?php echo Portal::language('row_number');?></th>
        <th><?php echo Portal::language('product_id');?></th>
        <th><?php echo Portal::language('product_name');?>(VN)</th>
        <th><?php echo Portal::language('product_name');?>(ENG)</th>
        <th><?php echo Portal::language('error');?></th>
    </tr>
    <?php if(isset($this->map['errors']) and is_array($this->map['errors'])){ foreach($this->map['errors'] as $key1=>&$item1){if($key1!='current'){$this->map['errors']['current'] = &$item1;?>
    <tr>
        <td><?php echo $this->map['errors']['current']['row_number'];?></td>
        <td><?php echo $this->map['errors']['current']['id'];?></td>
        <td><?php echo $this->map['errors']['current']['name_1'];?></td>
        <td><?php echo $this->map['errors']['current']['name_2'];?></td>
        <td><?php echo $this->map['errors']['current']['note'];?></td>
    </tr>
    <?php }}unset($this->map['errors']['current']);} ?>
<?php }else{ ?>
    <tr>
        <th><?php echo Portal::language('product_id');?></th>
        <th><?php echo Portal::language('product_name');?>(VN)</th>
        <th><?php echo Portal::language('product_name');?>(ENG)</th>
        <th><?php echo Portal::language('unit');?></th>
        <th><?php echo Portal::language('type');?></th>
        <th><?php echo Portal::language('category');?></th>
        <th><?php echo Portal::language('warehouse');?></th>
        <th><?php echo Portal::language('quantity');?></th>
        <th><?php echo Portal::language('total_money');?></th>
    </tr>
    <?php if(isset($this->map['preview']) and is_array($this->map['preview'])){ foreach($this->map['preview'] as $key2=>&$item2){if($key2!='current'){$this->map['preview']['current'] = &$item2;?>
    <tr>
        <td><?php echo $this->map['preview']['current']['id'];?></td>
        <td><?php echo $this->map['preview']['current']['name_1'];?></td>
        <td><?php echo $this->map['preview']['current']['name_2'];?></td>
        <td><?php echo $this->map['preview']['current']['unit'];?></td>  
        <td><?php echo $this->map['preview']['current']['type'];?></td>
        <td><?php echo $this->map['preview']['current']['category'];?></td>
        <td><?php echo $this->map['preview']['current']['warehouse_code'];?></td>
        <td style=" text-align: right;"><?php echo System::display_number($this->map['preview']['current']['quantity']); ?></td>
        <td style=" text-align: right;"><?php echo System::display_number($this->map['preview']['current']['total_start_term_price']); ?></td>
    </tr>
    <?php }}unset($this->map['preview']['current']);} ?>
<?php } ?>
    <?php 
        if( isset($this->map['update']) && !empty($this->map['update']))
        {
    ?>
        <tr>
            <th colspan="8" style="text-align: center;"><?php echo Portal::language('product_update');?>:</th>
        </tr>
        <?php if(isset($this->map['update']) and is_array($this->map['update'])){ foreach($this->map['update'] as $key3=>&$item3){if($key3!='current'){$this->map['update']['current'] = &$item3;?>
        <tr>
            <td style="cursor:pointer;"><?php echo $this->map['update']['current']['id'];?></td>
            <td style="cursor:pointer;"><?php echo $this->map['update']['current']['name_1'];?></td>
            <td style="cursor:pointer;"><?php echo $this->map['update']['current']['name_2'];?></td>
            <td style="cursor:pointer;"><?php echo $this->map['update']['current']['unit'];?></td>  
            <td style="cursor:pointer;"><?php echo $this->map['update']['current']['type'];?></td>
            <td style="cursor:pointer;"><?php echo $this->map['update']['current']['category'];?></td>
        </tr>
        <?php }}unset($this->map['update']['current']);} ?>
    <?php
        }
    ?>
</table>
<?php //System::debug($_SESSION['content']); ?>
<?php //System::debug($this->map['preview']); ?>