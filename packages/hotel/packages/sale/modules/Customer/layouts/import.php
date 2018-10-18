<div class="product-bill-bound">
    <form name="ImportAcreageForm" method="post" enctype="multipart/form-data">
    	<input  name="action" id="action" type="hidden"/>
        <!--input dung de luu id(product_price_list) khi an nut xoa tung` multi row-->
        <input  name="group_deleted_ids" id="group_deleted_ids" type="hidden" value="<?php echo URL::get('deleted_ids');?>"/>
    	
        <table cellpadding="15" cellspacing="0" width="100%" border="0" bordercolor="#CCCCCC" class="table-bound">
    		<tr>
            	<td width="70%" class="form-title">[[.import_from_excel.]]</td>
                <td width="30%" align="right" nowrap="nowrap">
                	
                    <?php if(User::can_add(false,ANY_CATEGORY)){?>
                    <input name="save" type="submit" value="[[.Save.]]" class="button-medium-save"/>
                    <?php }?>
    				
                    <?php if(User::can_delete(false,ANY_CATEGORY)){?>
                    <a href="<?php echo Url::build_current(array('type'));?>"  class="button-medium-back">[[.back.]]</a>
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
    			<legend class="title">[[.apartment.]]</legend>
    			<table border="0" cellspacing="0" cellpadding="2">
    				<tr>
    					<td class="label"></td>
    					<td style="vertical-align: top;border-right: 1px dashed #EEEEEE;;">
                        <span style="color: red; font-weight: bold; text-transform: uppercase;">Chú ý:</span> Download file excel mẫu và làm theo hướng dẫn bên dưới.<br /><br /><br />
                        <a href="packages\hotel\packages\sale\modules\Customer\mau_khach.rar" style="background: -webkit-linear-gradient(rgba(236,236,236,0), rgba(215,215,215,1)); background: -o-linear-gradient(rgba(236,236,236,0), rgba(215,215,215,1)); background: -moz-linear-gradient(rgba(236,236,236,0), rgba(215,215,215,1)); background: linear-gradient(rgba(236,236,236,0), rgba(215,215,215,1)); padding: 10px; color: #555555; font-weight: bold; border: 5px solid #ccfefa; text-decoration: none;">[[.download_excel_pattern.]]</a><br /><br /><br />
                        <b>Định dạng trường dữ liệu</b>
                        <p>Cột <strong>A</strong>: Nhập Số thứ tự</span></p>
                        <p>Cột <strong>B</strong>: Nhập Tên viết tắt <span style="color: red;">(*)</span></p>
                        <p>Cột <strong>C</strong>: Nhập Tên đầy đủ</p>
                        <p>Cột <strong>D</strong>: Nhập địa chỉ</p>
                        <p>Cột <strong>E</strong>: Nhập quốc tịch (mã quốc tịch tồn tại <a target="_blank" href="?page=customer&cmd=list_national&search_na=national">ở đây</a>)</p>
                        <p>Cột <strong>F</strong>: Nhập tỉnh/thành phố (danh sách Tỉnh/thành phố thuộc VN <a target="_blank" href="?page=customer&cmd=list_national&search_na=city">ở đây</a>)</p>
                        <p>Cột <strong>G</strong>: Nhập quận huyện (danh sách Quận/huyện thuộc VN <a target="_blank" href="?page=customer&cmd=list_national&search_na=district">ở đây</a>)</p>
                        <p>Cột <strong>H</strong>: Nhập Loại (loại tồn tại <a target="_blank" href="?page=customer_group">ở đây</a>)<span style="color: red;">(*)</span></p>
                        <p>Cột <strong>I</strong>: Nhập Ngành nghề (Ngành nghề tồn tại <a target="_blank" href="?page=sectors">ở đây</a>)</p>
                        <p style="font-weight: bold; text-transform: uppercase; padding: 0px;"><span style="color: red;">(*)</span>: là bắt buộc phải nhập</p>
                        </td>
                        <td>
                        <p><strong>&nbsp;</strong></p>
                        <p><strong>&nbsp;</strong></p>
                        <p><strong>&nbsp;</strong></p>
                        <p>Cột <strong>J</strong>: Nhập điện thoại</p>
                        <p>Cột <strong>K</strong>: Nhập fax</p>
                        <p>Cột <strong>L</strong>: Nhập E-mail</p>
                        <p>Cột <strong>M</strong>: Nhập mã sôs thuế</p>
                        <p>Cột <strong>N</strong>: Nhập số tài khoản</p>
                        <p>Cột <strong>O</strong>: Nhập ngân hàng (Ngân hàng tồn tại <a target="_blank" href="?page=bank">ở đây</a>)</p>
                        <p>Cột <strong>P</strong>: Nhập trạng thái (đang giao dịch, tiềm năng, ngừng giao dịch)</p>
                        <p>Cột <strong>Q</strong>: Nhập ngày thành lập</p>
                        <p>Cột <strong>R</strong>: Nhập ghi chú</p>
                        <p>Cột <strong>S</strong>: Nhập mã sale</p>
                        <p><strong>&nbsp;</strong></p>
                        </td>
    				</tr>
    			</table>
            </fieldset>	
                <br />
            <fieldset>
    			<legend class="title">[[.upload.]]</legend>
    			<table border="0" cellspacing="0" cellpadding="2">
    				<tr>
                        <td><input name="path" id="path" type="file"  /></td>
                        <td><input name="do_upload" type="submit" id="do_upload" value="[[.upload_and_preview.]]" style="width: 180px;"/></td>
    				</tr>
    			</table>
            </fieldset>
            <br />
            
            <table width="100%" border="1" cellspacing="0" cellpadding="3" bordercolor="#CCCCCC">
                <tr bgcolor="#F1F1F1">
                    <th width="3%" align="left">[[.stt.]]</th>
                    <th width="10%" align="left">[[.company_name_def.]]</th>
                    <th width="10%" align="left">[[.national.]]</th>
                    <th width="10%" align="left">[[.city.]]</th>
                    <th width="10%" align="left">[[.district.]]</th>
                    <th width="10%" align="left">[[.style_customer.]]</th>
                    <th width="10%" align="left">[[.telephone_company.]]</th>
                    <th width="10%" align="left">[[.email.]]</th>
                    <th width="10%" align="left">[[.tax_code.]]</th>
                    <th width="10%" align="left">[[.bank_account.]]</th>
                    <th width="10%" align="left">[[.bank.]]</th>
                    <th width="10%" align="left">[[.creart_date.]]</th>
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
                        <td style="cursor:pointer;">[[|error.name|]]</td>
                        <td style="cursor:pointer;">[[|error.country|]]</td>
                        <td style="cursor:pointer;">[[|error.city|]]</td>
                        <td style="cursor:pointer;">[[|error.district|]]</td>
                        <td style="cursor:pointer;">[[|error.group_id|]]</td>
                        <td style="cursor:pointer;">[[|error.mobile|]]</td>
                        <td style="cursor:pointer;">[[|error.email|]]</td>
                        <td style="cursor:pointer;">[[|error.tax_code|]]</td>
                        <td style="cursor:pointer;">[[|error.bank_code|]]</td>
                        <td style="cursor:pointer;">[[|error.bank_id|]]</td>
                        <td style="cursor:pointer;">[[|error.creart_date|]]</td>
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
                        <td style="cursor:pointer;">[[|preview.name|]]</td>
                        <td style="cursor:pointer;">[[|preview.country|]]</td>
                        <td style="cursor:pointer;">[[|preview.city|]]</td>
                        <td style="cursor:pointer;">[[|preview.district|]]</td>
                        <td style="cursor:pointer;">[[|preview.group_id|]]</td>
                        <td style="cursor:pointer;">[[|preview.mobile|]]</td>
                        <td style="cursor:pointer;">[[|preview.email|]]</td>
                        <td style="cursor:pointer;">[[|preview.tax_code|]]</td>
                        <td style="cursor:pointer;">[[|preview.bank_code|]]</td>
                        <td style="cursor:pointer;">[[|preview.bank_id|]]</td>
                        <td style="cursor:pointer;">[[|preview.creart_date|]]</td>
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




