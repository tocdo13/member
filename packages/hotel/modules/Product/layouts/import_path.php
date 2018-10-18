<fieldset id="toolbar" style="margin-top:2px;">
<form name="ImportForm" method="post" enctype="multipart/form-data">
<?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
<table cellspacing="0" cellpadding="5" width="100%" border="0">	
	<tr valign="top">
		<td align="left" colspan="2">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="form-title" width="100%">[[.import_product.]]</td>
					<td width="" align="right"><input name="save" type="submit" value="[[.Save.]]" class="button-medium-save"/></td>
					<td width="" align="right"><input type="button" value="[[.back.]]" class="button-medium-back" onclick="history.go(-1)"/></td>                    
                </tr>
            </table>
		</td>
	</tr>	
    
    <tr>
		<td colspan="2">
        	<?php if(Url::get('action')=='success'){?>
			<div style="font-weight:bold;color:#009900;">[[.update_sucess.]]</div>            
            <?php }?>
			<b>[[.Notice.]]</b> : <div>[[.help_import_data_to_excel.]]</div>
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
                    <td>[[.name_product_en.]]</td>
                </tr>                
                <tr>
                    <td align="right">D :</td>
                    <td>[[.unit.]]([[.if_null_then.]] [[.default.]] : [[.piece.]])</td>
                </tr>
                <tr>
                    <td align="right">E :</td>
                    <td>[[.type.]]([[.if_null_then.]] [[.default.]] : GOODS)</td>
                </tr>
                <tr>
                    <td align="right">F :</td>
                    <td>[[.category.]]</td>
                </tr>                
			</table>
		</td>
	</tr>
    <tr>
		<td align="right">[[.path_file_excel.]]</td>
		<td align="left">
			<!--<input id="path" name="path" type="file" value="[[.file.]]" id="path"/>-->
            <input id="file_path" name="file_path" type="text" style="width: 400px;"/>
		</td>
	</tr>
    
    <tr>
        <td></td>
    	<td>
            <input name="do_upload" type="submit" id="do_upload" value="[[.upload_and_preview.]]" style="width: 180px;"/>
        </td>
    </tr>
</table>
<input name="confirm" type="hidden" id="confirm" value="1"/>
</form>
</fieldset>

<script>
jQuery(document).ready(function(){
    
    jQuery('#do_upload').click(function(){
        if(!jQuery('#file_path').val())
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
        <th width="20%" align="left">[[.product_name.]](ENG)</th>
        <th width="10%" align="left">[[.unit.]]</th>
        <th width="10%" align="left">[[.type.]]</th>
        <th width="10%" align="left">[[.category.]]</th>
    </tr>
    
    <?php 
        if( isset($this->map['preview']) && !empty($this->map['preview']))
        {
    ?>
        <tr><td colspan="6" align="center"><strong>[[.first_10_items.]]</strong></td></tr>
        <!--LIST:preview-->
        <tr>  
            <td style="cursor:pointer;">[[|preview.id|]]</td>
            <td style="cursor:pointer;">[[|preview.name_1|]]</td>
            <td style="cursor:pointer;">[[|preview.name_2|]]</td>
            <td style="cursor:pointer;">[[|preview.unit|]]</td>  
            <td style="cursor:pointer;">[[|preview.type|]]</td>
            <td style="cursor:pointer;">[[|preview.category|]]</td>
        </tr>
        <!--/LIST:preview-->
    <?php
        }
    ?>
    
    
    
    <?php 
        if( isset($this->map['update']) && !empty($this->map['update']))
        {
    ?>
        <tr>
            <th colspan="8" style="text-align: center;">[[.product_update.]]:</th>
        </tr>
        <!--LIST:update-->
        <tr>
            <td style="cursor:pointer;">[[|update.id|]]</td>
            <td style="cursor:pointer;">[[|update.name_1|]]</td>
            <td style="cursor:pointer;">[[|update.name_2|]]</td>
            <td style="cursor:pointer;">[[|update.unit|]]</td>  
            <td style="cursor:pointer;">[[|update.type|]]</td>
            <td style="cursor:pointer;">[[|update.category|]]</td>
        </tr>
        <!--/LIST:update-->
    <?php
        }
    ?>
</table>