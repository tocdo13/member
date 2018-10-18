<fieldset id="toolbar" style="margin-top:2px;">
<form name="ImportForm" method="post" enctype="multipart/form-data">
<?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
<table cellspacing="0" cellpadding="5" width="100%" border="0">	
	<tr valign="top">
		<td align="left" colspan="2">
            <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
                <tr>
                    <td class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;" width="60%"><i class="fa fa-file-excel-o w3-text-green" style="font-size: 26px;"></i> [[.import_product.]]</td>
					<td width="40%" align="right" style="padding-right: 30px;">
                    <input name="save" type="submit" value="[[.Save_and_close.]]" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; margin-right: 5px;"/>
					<input type="button" value="[[.back.]]" class="w3-btn w3-green" onclick="history.go(-1)" style="text-transform: uppercase;"/></td>                    
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
                <tr>
                    <td align="right">G :</td>
                    <td>[[.warehouse_code.]]</td>
                </tr>  
                <tr>
                    <td align="right">H :</td>
                    <td>[[.quantity.]]</td>
                </tr>  
                <tr>
                    <td align="right">I :</td>
                    <td>[[.total_money.]]</td>
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
    	<td><input name="do_upload" type="submit" id="do_upload" value="[[.upload_and_preview.]]" style="width: 180px;"/></td>
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
<!--IF:cond(isset([[=errors=]]) and !empty([[=errors=]]))-->
    <h4 style="color: red;">[[.this_file_contains_errors_do_you_want_to_upload.]]</h4>
<!--/IF:cond-->
<table width="100%" border="1" cellspacing="0" cellpadding="3" bordercolor="#CCCCCC">
<?Php
    if(isset([[=errors=]]) and !empty([[=errors=]])){ //neu co loi thi show loi ra
?>
    <tr style="text-align: center;">
        <th>[[.row_number.]]</th>
        <th>[[.product_id.]]</th>
        <th>[[.product_name.]](VN)</th>
        <th>[[.product_name.]](ENG)</th>
        <th>[[.error.]]</th>
    </tr>
    <!--LIST:errors-->
    <tr>
        <td>[[|errors.row_number|]]</td>
        <td>[[|errors.id|]]</td>
        <td>[[|errors.name_1|]]</td>
        <td>[[|errors.name_2|]]</td>
        <td>[[|errors.note|]]</td>
    </tr>
    <!--/LIST:errors-->
<?php }else{ ?>
    <tr>
        <th>[[.product_id.]]</th>
        <th>[[.product_name.]](VN)</th>
        <th>[[.product_name.]](ENG)</th>
        <th>[[.unit.]]</th>
        <th>[[.type.]]</th>
        <th>[[.category.]]</th>
        <th>[[.warehouse.]]</th>
        <th>[[.quantity.]]</th>
        <th>[[.total_money.]]</th>
    </tr>
    <!--LIST:preview-->
    <tr>
        <td>[[|preview.id|]]</td>
        <td>[[|preview.name_1|]]</td>
        <td>[[|preview.name_2|]]</td>
        <td>[[|preview.unit|]]</td>  
        <td>[[|preview.type|]]</td>
        <td>[[|preview.category|]]</td>
        <td>[[|preview.warehouse_code|]]</td>
        <td style=" text-align: right;"><?php echo System::display_number([[=preview.quantity=]]); ?></td>
        <td style=" text-align: right;"><?php echo System::display_number([[=preview.total_start_term_price=]]); ?></td>
    </tr>
    <!--/LIST:preview-->
<?php } ?>
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
<?php //System::debug($_SESSION['content']); ?>
<?php //System::debug([[=preview=]]); ?>