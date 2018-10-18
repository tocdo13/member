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
    			<legend class="title">Nhà cung cấp</legend>
    			<table border="0" cellspacing="0" cellpadding="2">
    				<tr>
    					<td class="label">&nbsp;</td>
    					<td style="vertical-align: top;border-right: 1px dashed #EEEEEE;;">
                        <span style="color: red; font-weight: bold; text-transform: uppercase;">Chú ý:</span> Download file excel mẫu và làm theo hướng dẫn bên dưới.<br /><br /><br />
                        <a href="packages/hotel/packages/purchasing/modules/SupplierPrice/mau_baogia_sp.rar" style="background: -webkit-linear-gradient(rgba(236,236,236,0), rgba(215,215,215,1)); background: -o-linear-gradient(rgba(236,236,236,0), rgba(215,215,215,1)); background: -moz-linear-gradient(rgba(236,236,236,0), rgba(215,215,215,1)); background: linear-gradient(rgba(236,236,236,0), rgba(215,215,215,1)); padding: 10px; color: #555555; font-weight: bold; border: 5px solid #ccfefa; text-decoration: none;">[[.download_excel_pattern.]]</a><br /><br /><br />
                        <b>Định dạng trường dữ liệu</b>
                        <p>Cột <strong>A</strong>: Nhập Số thứ tự </p>
                        <p>Cột <strong>B</strong>: Nhập Mã SP<span style="color: red;">(*)</span></p>
                        <p>Cột <strong>C</strong>: Nhập Tên SP<span style="color: red;">(*)</span></p>
                        <p>Cột <strong>D</strong>: Nhập Mã nhà cung cấp (mã nhà cung cấp phải tồn tại <a target="_blank" href="?page=pc_supplier">ở đây</a>)</p>
                        <p>Cột <strong>E</strong>: Nhập Từ ngày -định dạng dd/mm/YYYY</p>
                        <p>Cột <strong>F</strong>: Nhập Đến ngày -định dạng dd/mm/YYYY</p>
                        <p>Cột <strong>G</strong>: Nhập Giá SP<span style="color: red;">(*)</span></p>
                        <p>Cột <strong>H</strong>: Thuế</p>
                        <p>Cột <strong>I</strong>: Thành tiền<span style="color: red;">(*)</span></p>
                        <p style="font-weight: bold; text-transform: uppercase; padding: 0px;"><span style="color: red;">(*)</span>: là bắt buộc phải nhập</p>
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
                    <th width="5%" align="left">[[.stt.]]</th>
                    <th width="20%" align="left">Mã SP</th>
                    <th width="20%" align="left">Tên SP</th>
                    <th width="10%" align="left">Ngày bắt đầu</th>
                    <th width="10%" align="left">Ngày kết thúc</th>
                    <th width="10%" align="left">Giá</th>
                    <th width="10%" align="left">Thuế</th>
                    <th width="10%" align="left">Giá sau thuế</th>
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
                        <td style="cursor:pointer;">[[|error.code_product|]]</td>
                        <td style="cursor:pointer;">[[|error.name_product|]]</td>
                        <td style="cursor:pointer;">[[|error.starting_date|]]</td>
        				<td style="cursor:pointer;">[[|error.ending_date|]]</td>
        				<td style="cursor:pointer;">[[|error.price|]]</td>
                        <td width="20%" style="cursor:pointer;">[[|error.note|]]</td>   
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
                        <td style="cursor:pointer;">[[|preview.code_product|]]</td>
                        <td style="cursor:pointer;">[[|preview.name_product|]]</td>
                        <td style="cursor:pointer;">[[|preview.starting_date|]]</td>
        				<td style="cursor:pointer;">[[|preview.ending_date|]]</td>
        				<td style="cursor:pointer;">[[|preview.price|]]</td>
                        <td style="cursor:pointer;">[[|preview.tax|]]</td>
                        <td style="cursor:pointer;">[[|preview.price_after_tax|]]</td>
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





