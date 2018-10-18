<fieldset id="toolbar" style="margin-top:2px;">
<form name="ImportForm" method="post" enctype="multipart/form-data">
<?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
<table cellspacing="0" cellpadding="5" width="100%" border="0">	
	<tr valign="top">
		<td align="left" colspan="2">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="" width="70%" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;">[[.import_product.]]</td>
					<td width="30%" align="right"><input name="save" type="submit" value="[[.Save_and_close.]]" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; margin-right: 5px;"/>
                    <input type="button" value="[[.back.]]" class="w3-btn w3-green" onclick="window.location='<?php echo Url::build_current();?>'" style="text-transform: uppercase; margin-right: 5px;"/></td>                    
                </tr>
            </table>
		</td>
	</tr>	
    
    <tr>
		<td colspan="2">
        	<?php if(Url::get('action')=='success'){?>
			<div style="font-weight:bold;color:#009900;">[[.update_sucess.]]</div>            
            <?php }?>
			<div style="padding-left:100px;font-weight:bold">[[.format_column.]]</div>
			<table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr>
                    <td align="right">A :</td>
                    <td>[[.code.]]</td>
                </tr>
                <tr>
                    <td align="right">B :</td>
                    <td>[[.name_product_vi.]]</td>
                </tr>
                <tr>
                    <td align="right">C :</td>
                    <td>[[.material.]]</td>
                </tr>                
                <tr>
                    <td align="right">D :</td>
                    <td>[[.material_name.]]</td>
                </tr>
                <tr>
                    <td align="right">E :</td>
                    <td>[[.quantity.]]</td>
                </tr>               
			</table>
		</td>
	</tr>
    <tr>
		<td align="right">[[.path_file_excel.]]</td>
		<td align="left">
			<input id="path" name="path" type="file" value="[[.file.]]" id="path"/>
		</td>
	</tr>
    
    <tr>
    	<td>
    	<td><input name="do_upload" type="submit" id="do_upload" value="[[.upload_and_preview_10_record.]]" style="width: 185px;"/></td>
    </tr>
</table>
<input name="confirm" type="hidden" id="confirm" value="1"/>
</form>
</fieldset>

<script>
jQuery(document).ready(function(){
    
    jQuery('#do_upload').click(function(){
        if(!jQuery('#path').val())
        {
            alert('[[.you_must_choose_file.]]')
            return false;
        }
        
    })
})
</script>

<table width="100%" border="1" cellspacing="0" cellpadding="3" bordercolor="#CCCCCC">
    <tr bgcolor="#F1F1F1">
        <th width="10%" align="left">[[.product_id.]]</th>
        <th width="20%" align="left">[[.product_name.]](VN)</th>
        <th width="20%" align="left">[[.material.]]</th>
        <?php 
        if( isset($this->map['error']) && !empty($this->map['error']))
        {
        ?>
        <th width="10%" align="left">[[.reason.]]</th>
        <?php
        }
        ?>
    </tr>
    
    <?php 
        if( isset($this->map['preview']) && !empty($this->map['preview']))
        {
    ?>
        <!--LIST:preview-->
        <tr>  
            <td style="cursor:pointer;">[[|preview.product_id|]]</td>
            <td style="cursor:pointer;">[[|preview.product_name_1|]]</td>
            <td style="cursor:pointer;">[[|preview.desc_materials|]]</td>                    
        </tr>
        <!--/LIST:preview-->
    <?php
        }
    ?>
    <?php 
        if( isset($this->map['error']) && !empty($this->map['error']))
        {
    ?>
        <tr>
            <th colspan="8" style="text-align: center; color: red;">[[.error.]]: [[.cannot_upload_the_follow_records.]]</th>
        </tr>
        <!--LIST:error-->
        <tr>
            <td style="cursor:pointer;">[[|error.product_id|]]</td>
            <td style="cursor:pointer;">[[|error.product_name_1|]]</td>
            <td style="cursor:pointer;">[[|error.desc_materials|]]</td>  
            <td style="cursor:pointer; color: red;">[[|error.error_desc|]]</td>                 
        </tr>
        <!--/LIST:error-->
    <?php
        }
    ?>
</table>