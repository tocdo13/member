<div class="product-bill-bound">
    <form name="ImportAcreageForm" method="post" enctype="multipart/form-data">
    	<input  name="action" id="action" type="hidden"/>
        <!--input dung de luu id(product_price_list) khi an nut xoa tung` multi row-->
        <input  name="group_deleted_ids" id="group_deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>"/>
    	
        <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
    		<tr>
            	<td width="60%" class="" style="text-transform: uppercase; font-size: 18px; padding-left: 15px;"><i class="fa fa-file-excel-o w3-text-green" style="font-size: 26px;"></i> [[.import_from_excel.]]</td>
                <td width="40%" align="right" nowrap="nowrap" style="padding-right: 30px;">
                	
                    <?php if(User::can_add(false,ANY_CATEGORY)){?>
                    <input name="save" type="submit" value="[[.Save_and_close.]]" class="w3-btn w3-orange w3-text-white" style="text-transform: uppercase; margin-right: 5px;"/>
                    <?php }?>
    				
                    <?php if(User::can_delete(false,ANY_CATEGORY)){?>
                    <a href="<?php echo Url::build_current(array('type'));?>"  class="w3-btn w3-green" style="text-transform: uppercase; text-decoration: none;">[[.back.]]</a>
                    <?php }?>
                </td>
            </tr>
        </table>
        
    	<div class="content">
    		<?php if(Form::$current->is_error()){?>
            <div>
            <br/>
            <?php echo Form::$current->error_messages();?>
            </div>
            <?php }?>
            <?php if(Url::get('action')=='success'){?>
			<div style="font-weight:bold;color:#009900;">[[.update_sucess.]]</div>            
            <?php }?>
            <fieldset>
    			<legend class="" style="text-transform: uppercase;">[[.import_excel.]]</legend>
    			<table border="0" cellspacing="0" cellpadding="2">
    				<tr>
    					<td colspan="13" style="vertical-align: top;border-right: 1px dashed #EEEEEE;;">
                        <span style="color: red; font-weight: bold; text-transform: uppercase;">Chú ý:</span> Download file excel mẫu và làm theo hướng dẫn bên dưới.<br /><br /><br />
                        <a href="packages/hotel/packages/purchasing/modules/PCSupplier/mau_nhacungcap.rar" style="background: -webkit-linear-gradient(rgba(236,236,236,0), rgba(215,215,215,1)); background: -o-linear-gradient(rgba(236,236,236,0), rgba(215,215,215,1)); background: -moz-linear-gradient(rgba(236,236,236,0), rgba(215,215,215,1)); background: linear-gradient(rgba(236,236,236,0), rgba(215,215,215,1)); padding: 10px; color: #555555; font-weight: bold; border: 5px solid #ccfefa; text-decoration: none;">[[.download_excel_pattern.]]</a><br /><br /><br />
                        <b>Định dạng trường dữ liệu</b>
                        <p style="font-weight: bold; text-transform: uppercase; padding: 0px;"><span style="color: red;">(*)</span>: là bắt buộc phải nhập</p>
                        </td>
    				</tr>
                    <tr style="text-align: center;">
                        <td class="w3-border" style="font-weight: bold;">A</td>
                        <td class="w3-border" style="font-weight: bold;">B</td>
                        <td class="w3-border" style="font-weight: bold;">C</td>
                        <td class="w3-border" style="font-weight: bold;">D</td>
                        <td class="w3-border" style="font-weight: bold;">E</td>
                        <td class="w3-border" style="font-weight: bold;">F</td>
                        <td class="w3-border" style="font-weight: bold;">G</td>
                        <td class="w3-border" style="font-weight: bold;">H</td>
                        <td class="w3-border" style="font-weight: bold;">I</td>
                        <td class="w3-border" style="font-weight: bold;">J</td>
                        <td class="w3-border" style="font-weight: bold;">K</td>
                        <td class="w3-border" style="font-weight: bold;">L</td>
                        <td class="w3-border" style="font-weight: bold;">M</td>
                    </tr>
                    <tr>
                        <td class="w3-border">Nhập Số thứ tự</td>
                        <td class="w3-border">Nhập Mã nhà cung cấp<span style="color: red;">(*)</span></td>
                        <td class="w3-border">Nhập Tên nhà cung cấp<span style="color: red;">(*)</span></td>
                        <td class="w3-border">Nhập ĐT cơ quan</td>
                        <td class="w3-border">Nhập Fax</td>
                        <td class="w3-border">Nhập Hotline</td>
                        <td class="w3-border">Nhập E-mail</td>
                        <td class="w3-border">Nhập Mã số thuế</td>
                        <td class="w3-border">Nhập Địa chỉ</td>
                        <td class="w3-border">Nhập Người liên hệ</td>
                        <td class="w3-border">Nhập ĐT liên hệ</td>
                        <td class="w3-border">Nhập Mobile người LH</td>
                        <td class="w3-border">Nhập E-mail người LH</td>
                    </tr>
    			</table>
            </fieldset>	
            <br />
            
            <fieldset>
    			<legend class="title">[[.upload.]]</legend>
    			<table border="0" cellspacing="0" cellpadding="2">
    				<tr>
                        <td><input name="path" id="path" type="file" style="height: 24px;" /></td>
                        <td><input name="do_upload" type="submit" id="do_upload" value="[[.upload_and_preview.]]" style="width: 180px;height: 24px;"/></td>
    				</tr>
    			</table>
            </fieldset>
            <br />
            
            <table width="100%" border="1" cellspacing="0" cellpadding="3" bordercolor="#CCCCCC">
                <tr bgcolor="#F1F1F1">
                    <th width="30" align="center">[[.stt.]]</th>
                    <th width="100" align="center">[[.supplier_code.]]</th>
                    <th width="200" align="center">[[.supplier_name.]]</th>
                    <th width="100" align="center">[[.telephone_number.]]</th>
                    <th width="100" align="center">[[.fax.]]</th>
                    <th width="100" align="center">[[.hotline.]]</th>
                    <th width="150" align="center">[[.email.]]</th>
                    <th width="100" align="center">[[.tax_code.]]</th>
                    <th width="200" align="center">[[.address.]]</th>
                    <th width="150" align="center">[[.person_contact.]]</th>
                    <th width="100" align="center">[[.person_phone.]]</th>
                    <th width="100" align="center">[[.mobiphone_number.]]</th>
                    <th width="150" align="center">[[.person_email.]]</th>
                </tr>
                
                <?php 
                    if( isset($this->map['error']) && !empty($this->map['error']))
                    {
                ?>
                    <tr>
                        <td style="text-align: left;" colspan="4"><strong>danh sách lỗi</strong></td>
                    </tr>
                    <!--LIST:error-->
                    <tr>  
                        <td style="cursor:pointer;">[[|error.stt|]]</td>
                        <td style="cursor:pointer;">[[|error.code|]]</td>
                        <td style="cursor:pointer;">[[|error.name|]]</td>
                        <td style="cursor:pointer;">[[|error.phone|]]</td>
                        <td style="cursor:pointer;">[[|error.fax|]]</td>
                        <td style="cursor:pointer;">[[|error.mobile|]]</td>
                        <td style="cursor:pointer;">[[|error.email|]]</td>
        				<td style="cursor:pointer;">[[|error.tax_code|]]</td>
                        <td style="cursor:pointer;">[[|error.address|]]</td>
        				<td style="cursor:pointer;">[[|error.contact_person_name|]]</td>
                        <td style="cursor:pointer;">[[|error.contact_person_phone|]]</td>
                        <td style="cursor:pointer;">[[|error.contact_person_mobile|]]</td>
                        <td style="cursor:pointer;">[[|error.contact_person_email|]]</td>
                        <td style="cursor:pointer;">[[|error.note|]]</td>   
                    </tr>
                    <!--/LIST:error-->
                <?php
                    }
                ?>
                <?php 
                    if( isset($this->map['preview']) && !empty($this->map['preview']))
                    {
                ?>
                    <tr>
                        <td style="text-align: left;" colspan="4"><strong>danh sách đúng</strong></td>
                    </tr>
                    <!--LIST:preview-->
                    <tr>  
                        <td style="cursor:pointer;">[[|preview.stt|]]</td>
                        <td style="cursor:pointer;">[[|preview.code|]]</td>
                        <td style="cursor:pointer;">[[|preview.name|]]</td>
                        <td style="cursor:pointer;">[[|preview.name|]]</td>
                        <td style="cursor:pointer;">[[|preview.phone|]]</td>
                        <td style="cursor:pointer;">[[|preview.mobile|]]</td>
                        <td style="cursor:pointer;">[[|preview.email|]]</td>
        				<td style="cursor:pointer;">[[|preview.tax_code|]]</td>
        				<td style="cursor:pointer;">[[|preview.address|]]</td>
                        <!--<td style="cursor:pointer;">[[|preview.phone|]]</td>-->
                        <td style="cursor:pointer;">[[|preview.contact_person_name|]]</td>
                        <td style="cursor:pointer;">[[|preview.contact_person_phone|]]</td>
                        <td style="cursor:pointer;">[[|preview.contact_person_mobile|]]</td>
                        <td style="cursor:pointer;">[[|preview.contact_person_email|]]</td>
                        <td></td>
                    </tr>
                    <!--/LIST:preview-->
                <?php
                    }
                ?>
    		</table>
   
    	</div>
    </form>	
</div>

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





