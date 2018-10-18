<fieldset id="toolbar" style="margin-top:2px;">
<form name="ImportForm" method="post" enctype="multipart/form-data">
<?php if(Form::$current->is_error()){echo Form::$current->error_messages();}?>
<table cellspacing="0" cellpadding="5" width="100%" border="0">	
	<tr valign="top">
		<td align="left">
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
		<td>
        	<?php if(Url::get('action')=='success'){?>
			<div style="font-weight:bold;color:#009900;">[[.update_sucess.]]</div>            
            <?php }?>
			<b>[[.Notice.]]</b> : <div>[[.help_import_data_to_excel.]]</div>
			<div style="padding-left:100px;">
                <b>[[.format_column.]]</b><br />
                A : Ngày(*)<br />
                B : Mã sản phẩm(*)<br />
                C : Tên sản phẩm(*)<br />
                D : Đơn vị tính(*)<br />
                E : Số lượng(*)<br />
                F : Giá(*)<br />
                G : Tiền(*)<br />
                H : Mã kho(*)<br />
                <!--I : Loại(*)<br />
                J : Danh mục(*)<br />-->
            </div>
		</td>
	</tr>
    <tr>
		<td align="left">
            [[.path_file_excel.]]
			<input id="path" name="path" type="file" value="[[.file.]]" id="path"/>
            <input name="do_upload" type="submit" id="do_upload" value="[[.upload_and_preview.]]" style="width: 180px;"/>
        </td>
    </tr>
</table>
<input name="confirm" type="hidden" id="confirm" value="1"/>
</form>
</fieldset>

<table width='100%' cellspacing='0' cellpadding='0' border='1' style="border-collapse: collapse; border-color: #3089E5;">
    <tr style="background-color: #F1F1F1;">
        <th align='center'>[[.stt.]]</th>
        <th align='center'>[[.date.]]</th>
        <th align='center'>[[.product_id.]]</th>
        <th align='center'>[[.product_name.]]</th>
        <th align='center'>[[.unit.]]</th>
        <th align='center'>[[.quantity.]]</th>
        <th align='center'>[[.price.]]</th>
        <th align='center'>[[.amount.]]</th>
        <th align='center'>[[.warehouse.]]</th>
        <th align='center'>[[.error.]]</th>
        <th align='center'>[[.status.]]</th>
    </tr>
    <!--LIST:items-->
    <tr>
        <td align='center'>[[|items.stt|]]</td>
        <td align='center'>[[|items.create_date|]]</td>
        <td align='left' style="padding-left: 10px;">[[|items.product_id|]]</td>
        <td align='left' style="padding-left: 10px;">[[|items.name|]]</td>
        <td align='left' style="padding-left: 10px;">[[|items.unit|]]</td>
        <td align='right' style="padding-right: 10px;">[[|items.num|]]</td>
        <td align='right' style="padding-right: 10px;"><?php echo System::display_number([[=items.price=]]); ?></td>
        <td align='right' style="padding-right: 10px;"><?php echo System::display_number([[=items.payment_price=]]); ?></td>
        <td align='left' style="padding-left: 10px;">[[|items.warehouse_code|]]</td>
        <td align='left' style="padding-left: 10px; color: red;">[[|items.note|]]</td>
        <td align='center'>
            <?php
            switch([[=items.status=]])
            {
                case -2 : echo '<span style="color: red;">Fail</span>';break;
                case -1 : echo '<span style="color: red;">Error</span>';break;
                case 0 : echo '<span>Wait</span>';break;
                case 1 : echo '<span style="color: green;">Success</span>';break;
            }
            ?>
        </td>
    </tr>
    <!--/LIST:items-->
</table>
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
