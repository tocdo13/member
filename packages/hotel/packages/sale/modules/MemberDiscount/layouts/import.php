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
    			<legend class="title">[[.member_discount.]]</legend>
    			<table border="0" cellspacing="0" cellpadding="2">
    				<tr>
    					<td class="label">&nbsp;</td>
    					<td style="vertical-align: top;border-right: 1px dashed #EEEEEE;;">
                        <span style="color: red; font-weight: bold; text-transform: uppercase;">Chú ý:</span> Download file excel mẫu và làm theo hướng dẫn bên dưới.<br /><br /><br />
                        <a href="packages/hotel/packages/sale/modules/MemberDiscount/mau_uu_dai_tv.rar" style="background: -webkit-linear-gradient(rgba(236,236,236,0), rgba(215,215,215,1)); background: -o-linear-gradient(rgba(236,236,236,0), rgba(215,215,215,1)); background: -moz-linear-gradient(rgba(236,236,236,0), rgba(215,215,215,1)); background: linear-gradient(rgba(236,236,236,0), rgba(215,215,215,1)); padding: 10px; color: #555555; font-weight: bold; border: 5px solid #ccfefa; text-decoration: none;">[[.download_excel_pattern.]]</a><br /><br /><br />
                        <b>Định dạng trường dữ liệu</b>
                        <p>Cột <strong>A</strong>: Nhập điểm dịch vụ </p>
                        <p>Cột <strong>B</strong>: Nhập loại thẻ</p>
                        <p>Cột <strong>C</strong>: Nhập tiêu đề</p>
                        <p>Cột <strong>D</strong>: Nhập ngày bắt đầu</p>
                        <p>Cột <strong>E</strong>: Nhập ngày kết thúc</p>
                        <p>Cột <strong>F</strong>: Nhập toán tử (=,>,>=,<,<=)</p>
                        <p>Cột <strong>G</strong>: Nhập số người đi kèm</p>
                        <p>Cột <strong>H</strong>: Nhập diễn giải</p>
                        <p>Cột <strong>I</strong>: Nhập (%) Giảm giá</p>
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
                    <th width="15%" align="left">[[.pin_service.]]</th>
                    <th width="10%" align="left">[[.card_type.]]</th>
                    <th width="10%" align="left">[[.title.]]</th>
                    <th width="5%" align="left">[[.start_date.]]</th>
                    <th width="8%" align="left">[[.end_date.]]</th>
                    <th width="5%" align="left">[[.operator.]]</th>
                    <th width="5%" align="left">[[.number_people.]]</th>
                    <th width="15%" align="left">[[.direction.]]</th>
                    <th width="20%" align="left">[[.note.]]</th>
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
                        <td style="cursor:pointer;">[[|error.pin_service|]]</td>
                        <td style="cursor:pointer;">[[|error.card_type|]]</td>
                        <td style="cursor:pointer;">[[|error.title|]]</td>
                        <td style="cursor:pointer;">[[|error.start_date|]]</td>
                        <td style="cursor:pointer;">[[|error.end_date|]]</td>
                        <td style="cursor:pointer;">[[|error.math|]]</td>
                        <td style="cursor:pointer;">[[|error.number_people|]]</td>
                        <td style="cursor:pointer;">[[|error.direction|]]</td>
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
                        <td style="cursor:pointer;">[[|preview.pin_service|]]</td>
                        <td style="cursor:pointer;">[[|preview.card_type|]]</td>
                        <td style="cursor:pointer;">[[|preview.title|]]</td>
                        <td style="cursor:pointer;">[[|preview.start_date|]]</td>
                        <td style="cursor:pointer;">[[|preview.end_date|]]</td>
                        <td style="cursor:pointer;">[[|preview.math|]]</td>
                        <td style="cursor:pointer;">[[|preview.number_people|]]</td>
                        <td style="cursor:pointer;">[[|preview.direction|]]</td>
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





