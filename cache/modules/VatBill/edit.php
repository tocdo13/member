<style>
    .simple-layout-middle{
        width:100%;
    }
    .simple-layout-content{
        padding: 0px; border: none;
    }
    #print_date:hover{
        border: 1px dashed #333;
    }
</style>
<form name="VatBillEditForm" method="POST">
    <input  name="type" id="type" / type ="hidden" value="<?php echo String::html_normalize(URL::get('type'));?>">
    <input  name="act" id="act" / type ="hidden" value="<?php echo String::html_normalize(URL::get('act'));?>">
    <input  name="invoice_ids" id="invoice_ids" / type ="hidden" value="<?php echo String::html_normalize(URL::get('invoice_ids'));?>">
    <div id="VatBillEditFrom">
        <div id="VatBillEditFromHeader" style="width: 100% !important;">
            <ul>
                <li class="f-left"><h3><?php echo Portal::language('vat_infomation');?></h3></li>
                
                <?php 
				if((!isset($this->map['status']) OR ($this->map['status']!='CANCEL')))
				{?>
                <li class="f-right"><div class="ButtonIcon w3-indigo" onclick="PreviewGuest();"><i class="fa fa-fw fa-eye"></i><?php echo Portal::language('preview_before_print');?></div></li>
                
				<?php
				}
				?>
                
                <li class="f-right"><?php echo Portal::language('print_type');?>: <select  name="print_type" id="print_type" style="width: 100px;"><?php
					if(isset($this->map['print_type_list']))
					{
						foreach($this->map['print_type_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('print_type',isset($this->map['print_type'])?$this->map['print_type']:''))
                    echo "<script>$('print_type').value = \"".addslashes(URL::get('print_type',isset($this->map['print_type'])?$this->map['print_type']:''))."\";</script>";
                    ?>
	</select></li>
                
                <?php 
				if((!isset($this->map['vat_code']) OR ($this->map['vat_code']=='')))
				{?>
                <li class="f-right"><div class="ButtonIcon w3-gray" onclick="SaveFrom('SAVE_NO_PRINT');"><i class="fa fa-fw fa-qrcode"></i><?php echo Portal::language('save_and_no_print_vat');?></div></li>
                
				<?php
				}
				?>
                
                <?php 
				if((!isset($this->map['status']) OR ($this->map['status']!='CANCEL')))
				{?>
                <?php 
				if(($this->map['count_print']==0))
				{?><li class="f-right"><div class="ButtonIcon w3-blue" onclick="SaveFrom('SAVE_CODE');"><i class="fa fa-fw fa-save"></i><?php echo Portal::language('save_code');?></div></li>
				<?php
				}
				?>
                <li class="f-right "><div class="ButtonIcon w3-orange" onclick="SaveFrom('PRINT');"><i class="fa fa-fw fa-print"></i><?php echo Portal::language('save_and_print');?></div></li>
                
                
				<?php
				}
				?>
                
                <!--<li class="f-right"><div class="ButtonIcon"><i class="fa fa-fw fa-eye"></i><?php echo Portal::language('view_before_print');?></div></li>-->
                <?php 
				if(($this->map['count_print']!=0))
				{?><li class="f-right"><div class="ButtonIcon w3-green" onclick="GetHistory();"><i class="fa fa-fw fa-history"></i><?php echo Portal::language('view_history_print');?></div></li>
				<?php
				}
				?>
                <li class="f-right"><label><?php echo Portal::language('date');?> </label><input  name="print_date" id="print_date" style="width: 100px;" readonly="" / type ="text" value="<?php echo String::html_normalize(URL::get('print_date'));?>"></li>
            </ul>
        </div>
        <?php if(Form::$current->is_error()){ echo Form::$current->error_messages(); }?>
        <div id="VatBillEditFromHistory">
            <ul>
                <li><i class="fa fa-fw fa-info-circle"></i><?php 
				if(($this->map['count_print']==0))
				{?><?php echo Portal::language('first_print');?> <?php }else{ ?><?php echo Portal::language('printed');?>: <?php echo $this->map['count_print'];?> - <?php echo Portal::language('last_print_with');?>: <?php echo $this->map['last_print'];?> 
				<?php
				}
				?></li>
            </ul>
        </div>
        
        <div id="VatBillEditFormInfo">
            <table>
                <tr>
                    <td style="width: 120px;"><?php echo Portal::language('vat_code');?>: </td>
                    <td ><input  name="vat_code" id="vat_code" style="width: 150px" / type ="text" value="<?php echo String::html_normalize(URL::get('vat_code'));?>"></td>
                    <td style="width: 110px;"><?php echo Portal::language('arrival_date');?>: </td>
                    <td><input  name="start_date" id="start_date" style="width: 100px;" readonly="" / type ="text" value="<?php echo String::html_normalize(URL::get('start_date'));?>"></td>
                    <td style="width: 100px;"><?php echo Portal::language('departure_date');?>: </td>
                    <td style="width: 150px;"><input  name="end_date" id="end_date" style="width: 100px" readonly="" / type ="text" value="<?php echo String::html_normalize(URL::get('end_date'));?>"></td>
                </tr>
                <tr>
                    <td style="width: 120px;"><?php echo Portal::language('guest_name');?>: </td>
                    <td ><input  name="guest_name" id="guest_name" style="width: 150px" / type ="text" value="<?php echo String::html_normalize(URL::get('guest_name'));?>"></td>
                    <td style="width: 110px;"><?php echo Portal::language('company_code');?>: </td>
                    <td ><input  name="customer_code" id="customer_code" style="width: 100px;" class="f-left" readonly="" / type ="text" value="<?php echo String::html_normalize(URL::get('customer_code'));?>"></td>
                    <td style="width: 100px;"><?php echo Portal::language('company_name');?>: </td>
                    <td style="width: 400px;" >
                        <input  name="customer_name" id="customer_name" style="width: 298px; text-transform: uppercase;" / type ="text" value="<?php echo String::html_normalize(URL::get('customer_name'));?>"> 
                        <input  name="customer_id" id="customer_id" style="display: none;" / type ="text" value="<?php echo String::html_normalize(URL::get('customer_id'));?>">
                        <a href="#" onclick="window.open('?page=customer&action=select_customer&to=vat','customer')" style="margin-right: 5px;"><i class="fa fa-fw fa-search"></i></a> 
                        <a href="#" onclick="resetcustomer();"><i class="fa fa-fw fa-remove"></i></a>
                    </td>
                </tr>
                <tr>
                    <td style="width: 120px;"><?php echo Portal::language('customer_tax_code');?>: </td>
                    <td ><input  name="customer_tax_code" id="customer_tax_code" style="width: 150px;" / type ="text" value="<?php echo String::html_normalize(URL::get('customer_tax_code'));?>"></td>
                    <td style="width: 110px;"><?php echo Portal::language('customer_address');?>: </td>
                    <td colspan="3"><input  name="customer_address" id="customer_address" style="width: 500px;" / type ="text" value="<?php echo String::html_normalize(URL::get('customer_address'));?>"></td>
                </tr>
                <tr>
                    <td style="width: 120px;"><?php echo Portal::language('payment_method');?>: </td>
                    <td><select  name="payment_method" id="payment_method" style="width: 150px;"><?php
					if(isset($this->map['payment_method_list']))
					{
						foreach($this->map['payment_method_list'] as $key=>$value)
						{
							echo '<option value="'.$key.'"';
							echo '>'.$value.'</option>';
							
						}
					}
					
                    if(URL::get('payment_method',isset($this->map['payment_method'])?$this->map['payment_method']:''))
                    echo "<script>$('payment_method').value = \"".addslashes(URL::get('payment_method',isset($this->map['payment_method'])?$this->map['payment_method']:''))."\";</script>";
                    ?>
	</select></td>
                    <td style="width: 110px;"><?php echo Portal::language('customer_bank_code');?>: </td>
                    <td colspan="3" ><input  name="customer_bank_code" id="customer_bank_code" style="width: 500px;" / type ="text" value="<?php echo String::html_normalize(URL::get('customer_bank_code'));?>"></td>
                </tr>
            </table>
        </div>
    </div>
    
    <div id="VatBillEditFormDetail">
        <fieldset>
            <legend><?php echo Portal::language('print_short_info');?></legend>
            <table>
                <tr>
                    <td style="width: 110px;"><?php echo Portal::language('description_room');?>: </td>
                    <td><input  name="description_room" id="description_room" style="width: 300px;" / type ="text" value="<?php echo String::html_normalize(URL::get('description_room'));?>"></td>
                    <td style="width: 100px;"><?php echo Portal::language('description_bar');?>: </td>
                    <td><input  name="description_bar" id="description_bar" style="width: 300px;" / type ="text" value="<?php echo String::html_normalize(URL::get('description_bar'));?>"></td>
                </tr>
                <tr>
                    <td style="width: 110px;"><?php echo Portal::language('description_banquet');?>: </td>
                    <td><input  name="description_banquet" id="description_banquet" style="width: 300px;" / type ="text" value="<?php echo String::html_normalize(URL::get('description_banquet'));?>"></td>
                    <td style="width: 100px;"><?php echo Portal::language('description_service');?>: </td>
                    <td><input  name="description_service" id="description_service" style="width: 300px;" / type ="text" value="<?php echo String::html_normalize(URL::get('description_service'));?>"></td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 110px;"><?php echo Portal::language('note');?>: </td>
                    <td colspan="3"><textarea  name="note" id="note" style="width: 702px;"><?php echo String::html_normalize(URL::get('note',''));?></textarea></td>
                    <?php 
				if(($this->map['type']!='BAR' && $this->map['type']!='FOLIO' && $this->map['type']!='BANQUET'))
				{?>
                
                    <td rowspan="2" style="padding-left: 20px;" ><a href="#" class="ButtonIcon w3-btn w3-lime" onclick="AddItem();" style="text-transform: uppercase; padding-top: 0px !important; text-decoration: none;"><i class="fa fa-fw fa-plus"></i><?php echo Portal::language('add_invoice_other');?></a></td>
                
                
				<?php
				}
				?>
                </tr>
                
            </table>
        </fieldset>
        <fieldset>
            <legend><?php echo Portal::language('invoice_detail');?></legend>
            <table id="InvoiceDetail" border="1" bordercolor="#EEEEEE" cellpadding="2" cellspacing="2" style="width: 100%;">
                <tr style="height: 35px; text-align: center;">
                    <th><?php echo Portal::language('invoice_id');?></th>
                    <th><?php echo Portal::language('description');?></th>
                    <th><?php echo Portal::language('date_use');?></th>
                    <th style="width: 120px;"><?php echo Portal::language('total_before_tax');?></th>
                    <th style="width: 70px;"><?php echo Portal::language('service_rate');?></th>
                    <th style="width: 120px;"><?php echo Portal::language('service_amount');?></th>
                    <th style="width: 70px;"><?php echo Portal::language('tax_rate');?></th>
                    <th style="width: 120px;"><?php echo Portal::language('tax_amount');?></th>
                    <th style="width: 120px;"><?php echo Portal::language('total_amount');?></th>
                    <th style="width: 50px;"><?php echo Portal::language('delete');?></th>
                </tr>
            </table>
            <table border="1" bordercolor="#EEEEEE" cellpadding="2" cellspacing="2" style="width: 100%;" >
                <tr style="height: 35px; text-align: center;">
                    <th style="text-align: right;"><?php echo Portal::language('total');?>:</th>
                    <th style="width: 120px;"><input  name="total_before_tax" id="total_before_tax" readonly="" style="width: 100px; text-align: right;" / type ="text" value="<?php echo String::html_normalize(URL::get('total_before_tax'));?>"></th>
                    <th style="width: 70px;"></th>
                    <th style="width: 120px;"><input  name="service_amount" id="service_amount" readonly="" style="width: 100px; text-align: right;" / type ="text" value="<?php echo String::html_normalize(URL::get('service_amount'));?>"></th>
                    <th style="width: 70px;"></th>
                    <th style="width: 120px;"><input  name="tax_amount" id="tax_amount" readonly="" style="width: 100px; text-align: right;" / type ="text" value="<?php echo String::html_normalize(URL::get('tax_amount'));?>"></th>
                    <th style="width: 120px;"><input  name="total_amount" id="total_amount" readonly="" style="width: 100px; text-align: right;" / type ="text" value="<?php echo String::html_normalize(URL::get('total_amount'));?>"></th>
                    <th style="width: 50px;"></th>
                </tr>
            </table>
            <table border="1" bordercolor="#EEEEEE" cellpadding="2" cellspacing="2" style="width: 100%;" >
                <tr style="height: 35px; text-align: center;">
                    <th><strong>Số tiền bằng chữ</strong></th>
                    <th id="total_final"></th>
                </tr>
            </table>
        </fieldset>
    </div>
<input type="hidden" name="form_block_id" value="<?php echo isset(Module::$current->data)?Module::$current->data['id']:'';?>">
			</form >
			
			
<table id="TempalteDetail" style="display: none;">
    <tr id="DetailItems_#XXXXXX#" style="text-align: center;">
        <td>
            <input name="detail[#XXXXXX#][id]" id="id_#XXXXXX#" type="hidden" />
            <input name="detail[#XXXXXX#][invoice_detail_id]" id="invoice_detail_id_#XXXXXX#" type="hidden" />
            <input name="detail[#XXXXXX#][invoice_detail_type]" id="invoice_detail_type_#XXXXXX#" type="hidden" />
            <input name="detail[#XXXXXX#][reservation_id]" id="reservation_id_#XXXXXX#" type="hidden" />
            <input name="detail[#XXXXXX#][service_code]" id="service_code_#XXXXXX#" type="hidden" />
            <input name="detail[#XXXXXX#][invoice_id]" id="invoice_id_#XXXXXX#" type="text" readonly="" style="width: 35px; text-align: center;" />
        </td>
        <td><input name="detail[#XXXXXX#][description]" id="description_#XXXXXX#" type="text" readonly="" style="width: 90%;" /></td>
        <td><input name="detail[#XXXXXX#][date_use]" id="date_use_#XXXXXX#" type="text" readonly="" style="width: 80px; text-align: center;" /></td>
        <td><input name="detail[#XXXXXX#][total_before_tax]" id="total_before_tax_#XXXXXX#" type="text" class="input_number" readonly="" style="width: 100px; text-align: right;" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val())));" onchange="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalRow('#XXXXXX#'); GetTotal();" /></td>
        <td><input name="detail[#XXXXXX#][service_rate]" id="service_rate_#XXXXXX#" type="text" class="input_number" readonly="" style="width: 50px; text-align: center;" onchange="if(to_numeric(jQuery(this).val())<0){ jQuery(this).val(0); }else if(to_numeric(jQuery(this).val())>100){ jQuery(this).val(100); } GetTotalRow('#XXXXXX#'); GetTotal();" /></td>
        <td><input name="detail[#XXXXXX#][service_amount]" id="service_amount_#XXXXXX#" type="text" class="input_number" readonly="" style="width: 100px; text-align: right;" /></td>
        <td><input name="detail[#XXXXXX#][tax_rate]" id="tax_rate_#XXXXXX#" type="text" readonly="" class="input_number" style="width: 50px; text-align: center;" onchange="if(to_numeric(jQuery(this).val())<0){ jQuery(this).val(0); }else if(to_numeric(jQuery(this).val())>100){ jQuery(this).val(100); } GetTotalRow('#XXXXXX#'); GetTotal();" /></td>
        <td><input name="detail[#XXXXXX#][tax_amount]" id="tax_amount_#XXXXXX#" type="text" readonly="" class="input_number" style="width: 100px; text-align: right;" /></td>
        <td><input name="detail[#XXXXXX#][total_amount]" id="total_amount_#XXXXXX#" type="text" readonly="" class="input_number" style="width: 100px; text-align: right;" onkeyup="jQuery(this).val(number_format(to_numeric(jQuery(this).val()))); GetTotalRow('#XXXXXX#'); GetTotal();" /></td>
        <td><div class="ButtonIcon f-right" onclick="RemoveItems('#XXXXXX#');"><i class="fa fa-fw fa-remove"></i></div></td>
    </tr>
</table>
<div id="LightBoxContentHistory" style="display: none;">
    <div id="LightBoxContentHistoryBody">
        <div id="LightBoxContentHistoryHeader">
            <h3 class="f-left"><i class="fa fa-fw fa-history"></i><?php echo Portal::language('history_printed');?></h3>
            <p class="f-right" onclick="jQuery('#LightBoxContentHistory').css('display','none');" style="cursor: pointer;"><i class="fa fa-fw fa-remove"></i></p>
        </div>
        <div id="LightBoxContentHistoryMain">
            <?php if(isset($this->map['history']) and is_array($this->map['history'])){ foreach($this->map['history'] as $key1=>&$item1){if($key1!='current'){$this->map['history']['current'] = &$item1;?>
                <p><i class="fa fa-fw fa-circle-o"></i> <?php echo Portal::language('print_no');?> <b><?php echo $this->map['history']['current']['stt'];?></b> -  <?php echo Portal::language('user_print');?>: <b><?php echo $this->map['history']['current']['user_print'];?></b> <?php echo Portal::language('in_time');?>: <b><?php echo $this->map['history']['current']['time_print'];?></b></p>
            <?php }}unset($this->map['history']['current']);} ?>
        </div>
        <div id="LightBoxContentHistoryFooter">
            <p class="f-left"><i class="fa fa-fw fa-info-circle"></i> <?php echo Portal::language('history_is_recorded_when_the_user_manipulates_the_record_and_prints');?></p>
        </div>
    </div>
</div>
<div id="LightBoxPreview" style="display: none;">
    <div onclick="jQuery('#LightBoxPreview').css('display','none');" style="width: 100%; height: 100%; position: absolute; top: 0px; left: 0px;"></div>
    <div onclick="jQuery('#LightBoxPreview').css('display','none');" id="LightBoxPreviewControlRemote"><i class="fa fa-fw fa-remove" style="line-height: 30px;"></i></div>
    <div id="LightBoxPreviewBody">
        <div id="LightBoxPreviewContent">
            
        </div>
    </div>
</div>
<div id="LightBoxPreviewGuest" style="display: none;">
    <div onclick="jQuery('#LightBoxPreviewGuest').css('display','none');" style="width: 100%; height: 100%; position: absolute; top: 0px; left: 0px;"></div>
    <div onclick="jQuery('#LightBoxPreviewGuest').css('display','none');" id="LightBoxPreviewGuestControlRemote"><i class="fa fa-fw fa-remove" style="line-height: 30px;"></i></div>
    <div onclick="LightBoxPreviewGuestPrint();" id="LightBoxPreviewGuestControlPrint"><i class="fa fa-fw fa-print" style="line-height: 30px;"></i></div>
    <div id="LightBoxPreviewGuestBody">
        <div id="LightBoxPreviewGuestContent">
            
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function(){
        jQuery('select[name=payment_method]').val('CK/TM');
        jQuery('#total_final').html(ConvertNumberToCharacter.show(to_numeric(jQuery("#total_amount").val().trim())));
    })
    //console.log(jQuery("#type").val());
    <?php if(Url::get('cmd')=='edit' and (!isset($this->map['vat_type']) or $this->map['vat_type']!='SAVE_NO_PRINT')){ ?>
    jQuery("#vat_code").attr('readonly','readonly');
    <?php } ?>
    jQuery("#start_date").datepicker();
    jQuery("#end_date").datepicker();
    jQuery("#print_date").datepicker();
    var $input_count = 100;
    var $mi_detail = <?php echo String::array2js($_REQUEST['detail']); ?>;
    //console.log($mi_detail);
    for(var $i in $mi_detail) {
        AddItem($mi_detail[$i]);
    }
    GetTotal();
    function AddItem($mi_row=false) {
        $input_count++;
        var content = jQuery("#TempalteDetail").html().replace(/#XXXXXX#/g,$input_count);
        jQuery("#InvoiceDetail").append(content);
        jQuery("#date_use_"+$input_count).datepicker();
        if($mi_row) {
            jQuery("#id_"+$input_count).val($mi_row['id']);
            jQuery("#invoice_detail_id_"+$input_count).val($mi_row['invoice_detail_id']);
            jQuery("#reservation_id_"+$input_count).val($mi_row['reservation_id']);
            jQuery("#service_code_"+$input_count).val($mi_row['service_code']);
            jQuery("#invoice_detail_type_"+$input_count).val($mi_row['invoice_detail_type']);
            jQuery("#service_rate_"+$input_count).val(to_numeric($mi_row['service_rate']));
            jQuery("#tax_rate_"+$input_count).val(to_numeric($mi_row['tax_rate']));
            jQuery("#invoice_id_"+$input_count).val($mi_row['invoice_id']);
            jQuery("#description_"+$input_count).val($mi_row['description']);
            jQuery("#date_use_"+$input_count).val($mi_row['date_use']);
            jQuery("#total_before_tax_"+$input_count).val(number_format($mi_row['total_before_tax']));
            jQuery("#service_amount_"+$input_count).val(number_format($mi_row['service_amount']));
            jQuery("#tax_amount_"+$input_count).val(number_format($mi_row['tax_amount']));
            jQuery("#total_amount_"+$input_count).val(number_format($mi_row['total_amount']));
            
            jQuery("#total_amount_"+$input_count).removeAttr('readonly');
        }
        else {
            jQuery("#total_amount_"+$input_count).removeAttr('readonly');
            jQuery("#service_rate_"+$input_count).removeAttr('readonly');
            jQuery("#tax_rate_"+$input_count).removeAttr('readonly');
            jQuery("#date_use_"+$input_count).removeAttr('readonly');
            jQuery("#description_"+$input_count).removeAttr('readonly');
        }
        
    }
    
    function GetTotalRow($key) {
        var $total_amount = to_numeric(jQuery("#total_amount_"+$key).val());
        var $service_rate = to_numeric(jQuery("#service_rate_"+$key).val());
        var $tax_rate = to_numeric(jQuery("#tax_rate_"+$key).val());
        
        $total_before_tax = $total_amount/( (1+$service_rate/100)*(1+$tax_rate/100) );
        //console.log($total_before_tax);
        $service_amount = $total_before_tax * ($service_rate/100);
        $tax_amount = $total_amount - ($total_before_tax+$service_amount);
        //$tax_amount = Math.round($total_amount/( (100/100+$tax_rate) ),2);
        //$service_amount = $total_amount - ($total_before_tax+$tax_amount);
        
        
        jQuery("#total_before_tax_"+$key).val(number_format($total_before_tax));
        jQuery("#service_amount_"+$key).val(number_format($service_amount));
        jQuery("#tax_amount_"+$key).val(number_format($tax_amount));
        jQuery("#total_amount_"+$key).val(number_format($total_amount));
    }
    
    function GetTotal() {
        var $total_before_tax = 0;
        var $service_amount = 0;
        var $tax_amount = 0;
        var $total_amount = 0;
        for(var $i=101;$i<=$input_count;$i++)
        {
            if(jQuery("#id_"+$i).val()!=undefined) {
                $total_before_tax += to_numeric(jQuery("#total_before_tax_"+$i).val());
                $service_amount += to_numeric(jQuery("#service_amount_"+$i).val());
                $tax_amount += to_numeric(jQuery("#tax_amount_"+$i).val());
                $total_amount += to_numeric(jQuery("#total_amount_"+$i).val());
            }
        }
        //var $tax_amount = Math.round($total_amount) - Math.round($total_before_tax) - Math.round($service_amount);
        jQuery("#total_before_tax").val(number_format(Math.round($total_before_tax)));
        jQuery("#service_amount").val(number_format(Math.round($service_amount)));
        jQuery("#tax_amount").val(number_format(Math.round($tax_amount)));
        jQuery("#total_amount").val(number_format(Math.round($total_amount)));
    }
    function GetHistory() {
        jQuery('#LightBoxContentHistory').css('display','');
    }
    function SaveFrom(act) {
        $check = true;
        if(jQuery("#vat_code").val()=='' && act!='SAVE_NO_PRINT') {
            $check=false;
            alert('<?php echo Portal::language('vat_code_is_not_null');?>');
        }
        if($check) {
            if(jQuery("#print_type").val()=='FULL' && act=='PRINT'){
                $count_full_items = 0;
                for(var $i=101;$i<=$input_count;$i++) {
                    if(jQuery("#id_"+$i).val()!=undefined)
                        $count_full_items++;
                }
                if($count_full_items>15){
                    //if(confirm('BẠN ĐANG IN KIỂU ĐẦY ĐỦ CHI TIẾT HÓA ĐƠN \n ============================= \n [-] Tuy nhiên số lượng hàng hóa, dịch vụ quá nhiều ảnh hưởng đến việc hiển thị \n [-] Chúng tôi đề xuất bạn chọn hình thức \' IN RÚT GỌN \' cho hóa đơn VAT này \n [-] Ấn \' OK \' để chuyển đổi kiểu in \n [-] hoặc Ấn \' CANCEL \' để tiếp tục in theo kiểu đầy đủ ')){
                        //jQuery("#print_type").val('SHORT');
                        //SaveFrom(act);
                        //return;
                    //}else{
                        jQuery(".ButtonIcon").css('display','none');
                        jQuery("#act").val(act);
                        VatBillEditForm.submit();
                    //}
                }else{
                    jQuery(".ButtonIcon").css('display','none');
                    jQuery("#act").val(act);
                    VatBillEditForm.submit();
                }
            }else{
                jQuery(".ButtonIcon").css('display','none');
                jQuery("#act").val(act);
                VatBillEditForm.submit();
            }
        }
    }
    function resetcustomer() {
        jQuery("#customer_id").val('');
        jQuery("#customer_name").val('');
        jQuery("#customer_address").val('');
        jQuery("#customer_tax_code").val('');
        jQuery("#customer_bank_code").val('');
    }
    function RemoveItems($key) {
        jQuery("#DetailItems_"+$key).remove();
        GetTotal();
        jQuery('#total_final').html(ConvertNumberToCharacter.show(to_numeric(jQuery("#total_amount").val().trim())));
    }
    function LightBoxPreviewGuestPrint(){
        var user_id = '<?php echo User::id(); ?>';
        printWebPart('LightBoxPreviewGuestContent',user_id);
    }
    function PreviewGuest(){
        document.getElementById("LightBoxPreviewGuestContent").innerHTML='';
        var content_table_arr = {};
        var $recode_arr = {};
        //$count_full_items = 0;
        for(var $i=101;$i<=$input_count;$i++) {
            if(jQuery("#id_"+$i).val()!=undefined) {
                //$count_full_items++;
                if(jQuery("#reservation_id_"+$i).val()!='' && jQuery("#reservation_id_"+$i).val()!=null && jQuery("#reservation_id_"+$i).val()!=undefined){
                    $recode_arr[jQuery("#reservation_id_"+$i).val()] = jQuery("#reservation_id_"+$i).val();
                }
                if(jQuery("#type").val()==''){
                    content_table_arr[$i] = {'description':jQuery('#description_'+$i).val(),'total_amount':to_numeric(jQuery('#total_before_tax_'+$i).val())};
                }else{
                    if( jQuery("#type").val()=='FOLIO' && ( jQuery("#invoice_detail_type_"+$i).val()=='ROOM' || (jQuery("#invoice_detail_type_"+$i).val()=='EXTRA_SERVICE' && (jQuery("#service_code_"+$i).val()=='LATE_CHECKIN' || jQuery("#service_code_"+$i).val()=='LATE_CHECKOUT' || jQuery("#service_code_"+$i).val()=='EARLY_CHECKIN' || jQuery("#service_code_"+$i).val()=='ROOM')) ) ){
                        var $id_pay = 'PAYMENT_ROOM';
                        var $description = jQuery("#description_room").val();
                    }else if(jQuery("#type").val()=='BAR' || (jQuery("#type").val()=='FOLIO' && jQuery("#invoice_detail_type_"+$i).val()=='BAR')){
                        var $id_pay = 'PAYMENT_BAR';
                        var $description = jQuery("#description_bar").val();
                    }else if(jQuery("#type").val()=='BANQUET'){
                        var $id_pay = 'PAYMENT_PARTY';
                        var $description = jQuery("#description_banquet").val();
                    }else{
                        var $id_pay = 'PAYMENT_EXTRA_SERVICE';
                        var $description = jQuery("#description_service").val();
                    }
                    if(!content_table_arr[$id_pay]){
                        content_table_arr[$id_pay] = {'description':$description,'total_amount':0};
                    }
                    content_table_arr[$id_pay]['total_amount'] += to_numeric(jQuery('#total_before_tax_'+$i).val());
                }
            }
        }
        /*
        if(jQuery("#print_type").val()=='FULL' && $count_full_items>15){
            if(confirm('BẠN ĐANG IN KIỂU ĐẦY ĐỦ CHI TIẾT HÓA ĐƠN \n ============================= \n [-] Tuy nhiên số lượng hàng hóa, dịch vụ quá nhiều ảnh hưởng đến việc hiển thị \n [-] Chúng tôi đề xuất bạn chọn hình thức \' IN RÚT GỌN \' cho hóa đơn VAT này \n [-] Ấn \' OK \' để chuyển đổi kiểu in \n [-] hoặc Ấn \' CANCEL \' để tiếp tục in theo kiểu đầy đủ ')){
                jQuery("#print_type").val('SHORT');
                PreviewGuest();
                return;
            }
        }
        */
        var content_table = '';
        var $stt = 0;
        var count_detail = content_table_arr.length;
        var $total_b_f = 0;
        for(var $k in content_table_arr){
            $stt++;
            content_table += '<tr>';
                content_table += '<td style="text-align: center;">'+$stt+'</td>';
                content_table += '<td>'+content_table_arr[$k]['description']+'</td>';
                content_table += '<td></td>';
                content_table += '<td></td>';
                content_table += '<td></td>';
            if( $stt==count_detail ){
                content_table += '<td style="text-align: right; font-size: 15px;">'+number_format(Math.round(to_numeric(jQuery("#total_before_tax").val())-$total_b_f))+'</td>';
            }else{
                content_table += '<td style="text-align: right; font-size: 15px;">'+number_format(Math.round(content_table_arr[$k]['total_amount']))+'</td>';
            }
            content_table += '</tr>';
            $total_b_f += Math.round(to_numeric(content_table_arr[$k]['total_amount']));
        }
        
        var $recode = '';
        for(var $j in $recode_arr){
            $recode += $recode==''?to_numeric($recode_arr[$j]):(to_numeric($recode_arr[$j])<to_numeric($recode)?to_numeric($recode_arr[$j]):to_numeric($recode));
        }
        var content = '<table style="width: 100%;">';
            content += '<tr>';
                content += '<td style="vertical-align: top;">';
                    content += '<?php echo HOTEL_NAME; ?><br />';
                    content += 'Địa chỉ (Address): <?php echo HOTEL_ADDRESS; ?>';
                content += '</td>';
                content += '<td style="vertical-align: top;">';
                    content += 'Số hóa đơn: '+jQuery('#vat_code').val()+'<br />';
                    content += 'Ngày HĐ (Invoice date): '+jQuery('#print_date').val()+'';
                content += '</td>';
            content += '</tr>';
            content += '<tr>';
                content += '<td colspan="2" style="text-align: center;">';
                    content += '<h1>HÓA ĐƠN GIÁ TRỊ GIA TĂNG (VAT INVOICE)</h1><br />';
                    content += '( Bản xem trước )';
                content += '</td>';
            content += '</tr>';
            content += '<tr>';
                content += '<td colspan="2">';
                    content += '<table style="width: 100%;" border="1" bordercolor="#000000">';
                        content += '<tr>';
                            content += '<td>';
                                content += '<table style="width: 100%;">';
                                    content += '<tr>';
                                        content += '<td style="width: 50%;">Tên khách hàng (Guest name) '+jQuery('#guest_name').val()+'</td>';
                                        content += '<td>Mã đặt phòng (Booking) '+$recode+'</td>';
                                    content += '</tr>';
                                    content += '<tr>';
                                        content += '<td style="width: 50%;" rowspan="2">Đơn vị mua hàng (Invoice to) <span style="text-transform: uppercase;">'+jQuery('#customer_name').val()+'</span></td>';
                                        content += '<td>Ngày giờ đến (Arrival) '+jQuery('#start_date').val()+'</td>';
                                    content += '</tr>';
                                    content += '<tr>';
                                        content += '<td>Ngày giờ đi (Departure) '+jQuery('#end_date').val()+'</td>';
                                    content += '</tr>';
                                    content += '<tr>';
                                        content += '<td style="width: 50%;" rowspan="2">Địa chỉ KH (Address) '+jQuery("#customer_address").val()+'</td>';
                                        content += '<td>Hình thứ thanh toán TM/CK</td>';
                                    content += '</tr>';
                                    content += '<tr>';
                                        content += '<td>(Kind of Payment)</td>';
                                    content += '</tr>';
                                    content += '<tr>';
                                        content += '<td style="width: 50%;">Mã số thuế (Tax code) '+jQuery("#customer_tax_code").val()+'</td>';
                                        content += '<td></td>';
                                    content += '</tr>';
                                content += '</table>';
                            content += '</td>';
                        content += '</tr>';
                        content += '<tr>';
                            content += '<td>';
                                content += '<table style="width: 100%; border-collapse: collapse;" border="1" bordercolor="#000000">';
                                    content += '<tr style="text-align: center;">';
                                        content += '<th>STT<br />(No)</th>';
                                        content += '<th>Tên hàng hóa, dịch vụ<br />Description</th>';
                                        content += '<th>Đơn vị tính<br />Unit</th>';
                                        content += '<th>Số lượng<br />Quantities</th>';
                                        content += '<th>Đơn giá<br />Unit Price</th>';
                                        content += '<th>Thành tiền<br />Amount</th>';
                                    content += '</tr>';
                                    content += content_table;
                                content += '</table>';
                            content += '</td>';
                        content += '</tr>';
                        content += '<tr>';
                            content += '<td>';
                                content += '<table style="width: 100%;">';
                                    content += '<tr>';
                                        content += '<td style="width: 33%;">Thuế xuất GTGT (VAT Rate) 10%</td>';
                                        content += '<td>Cộng tiền (Total charge)</td>';
                                        content += '<td style="width: 33%; text-align: right;">'+jQuery("#total_before_tax").val()+'</td>';
                                    content += '</tr>';
                                    content += '<tr>';
                                        content += '<td style="width: 33%;"></td>';
                                        content += '<td>Phí dịch vụ (Service charge)</td>';
                                        content += '<td style="width: 33%; text-align: right;">'+jQuery("#service_amount").val()+'</td>';
                                    content += '</tr>';
                                    content += '<tr>';
                                        content += '<td style="width: 33%;"></td>';
                                        content += '<td>Thuế GTGT (VAT)</td>';
                                        content += '<td style="width: 33%; text-align: right;">'+jQuery("#tax_amount").val()+'</td>';
                                    content += '</tr>';
                                    content += '<tr>';
                                        content += '<td style="width: 33%;"></td>';
                                        content += '<td>Tổng cộng tiền thanh toán (Total Amount)</td>';
                                        content += '<td style="width: 33%; text-align: right;">'+jQuery("#total_amount").val()+'</td>';
                                    content += '</tr>';
                                content += '</table>';
                            content += '</td>';
                        content += '</tr>';
                        content += '<tr>';
                            content += '<td>';
                                content += 'Bằng chữ (In word): '+ConvertNumberToCharacter.show(to_numeric(jQuery("#total_amount").val().trim()))+'';
                            content += '</td>';
                        content += '</tr>';
                    content += '</table>';
                content += '</td>';
            content += '</tr>';
        content += '</table>';
        document.getElementById("LightBoxPreviewGuestContent").innerHTML=content;
        jQuery("#LightBoxPreviewGuest").css('display','');
    }
    function Preview(){
        document.getElementById("LightBoxPreviewContent").innerHTML='';
        var content_table_arr = {};
        var $recode_arr = {};
        $count_full_items = 0;
        for(var $i=101;$i<=$input_count;$i++) {
            if(jQuery("#id_"+$i).val()!=undefined) {
                $count_full_items++;
                if(jQuery("#reservation_id_"+$i).val()!='' && jQuery("#reservation_id_"+$i).val()!=null && jQuery("#reservation_id_"+$i).val()!=undefined){
                    $recode_arr[jQuery("#reservation_id_"+$i).val()] = jQuery("#reservation_id_"+$i).val();
                }
                if(jQuery("#print_type").val()=='FULL'){
                    content_table_arr[$i] = {'description':jQuery('#description_'+$i).val(),'total_amount':to_numeric(jQuery('#total_before_tax_'+$i).val())};
                }else{
                    if( jQuery("#type").val()=='FOLIO' && ( jQuery("#invoice_detail_type_"+$i).val()=='ROOM' || (jQuery("#invoice_detail_type_"+$i).val()=='EXTRA_SERVICE' && (jQuery("#service_code_"+$i).val()=='LATE_CHECKIN' || jQuery("#service_code_"+$i).val()=='LATE_CHECKOUT' || jQuery("#service_code_"+$i).val()=='EARLY_CHECKIN' || jQuery("#service_code_"+$i).val()=='ROOM')) ) ){
                        var $id_pay = 'PAYMENT_ROOM';
                        var $description = jQuery("#description_room").val();
                    }else if(jQuery("#type").val()=='BAR' || (jQuery("#type").val()=='FOLIO' && jQuery("#invoice_detail_type_"+$i).val()=='BAR')){
                        var $id_pay = 'PAYMENT_BAR';
                        var $description = jQuery("#description_bar").val();
                    }else if(jQuery("#type").val()=='BANQUET'){
                        var $id_pay = 'PAYMENT_PARTY';
                        var $description = jQuery("#description_banquet").val();
                    }else{
                        var $id_pay = 'PAYMENT_EXTRA_SERVICE';
                        var $description = jQuery("#description_service").val();
                    }
                    if(!content_table_arr[$id_pay]){
                        content_table_arr[$id_pay] = {'description':$description,'total_amount':0};
                    }
                    content_table_arr[$id_pay]['total_amount'] += to_numeric(jQuery('#total_before_tax_'+$i).val());
                }
            }
        }
        if(jQuery("#print_type").val()=='FULL' && $count_full_items>15){
            if(confirm('BẠN ĐANG IN KIỂU ĐẦY ĐỦ CHI TIẾT HÓA ĐƠN \n ============================= \n [-] Tuy nhiên số lượng hàng hóa, dịch vụ quá nhiều ảnh hưởng đến việc hiển thị \n [-] Chúng tôi đề xuất bạn chọn hình thức \' IN RÚT GỌN \' cho hóa đơn VAT này \n [-] Ấn \' OK \' để chuyển đổi kiểu in \n [-] hoặc Ấn \' CANCEL \' để tiếp tục in theo kiểu đầy đủ ')){
                jQuery("#print_type").val('SHORT');
                Preview();
                return;
            }
        }
        var content_table = '';
        var $stt = 0;
        var count_detail = content_table_arr.length;
        var $total_b_f = 0;
        for(var $k in content_table_arr){
            $stt++;
            content_table += '<tr style="height: 28px; font-weight: bold;">';
                content_table += '<td style="width: 85px; font-size: 15px; text-align: center;">'+$stt+'</td>';
                content_table += '<td style="width: 240px; font-size: 15px;">'+content_table_arr[$k]['description']+'</td>';
            if( jQuery("#print_type").val()!='FULL' && $stt==count_detail ){
                
                content_table += '<td style="text-align: right; font-size: 15px;">'+number_format(Math.round(to_numeric(jQuery("#total_before_tax").val())-$total_b_f))+'</td>';
            }else{
                content_table += '<td style="text-align: right; font-size: 15px;">'+number_format(Math.round(content_table_arr[$k]['total_amount']))+'</td>';
            }
            content_table += '</tr>';
            $total_b_f += to_numeric(content_table_arr[$k]['total_amount']);
        }
        
        var $recode = '';
        for(var $j in $recode_arr){
            $recode += $recode==''?$recode_arr[$j]:','+$recode_arr[$j];
        }
        var content = '<span style="position: absolute; top: 115px; left: 790px; font-size: 15px;">'+jQuery('#print_date').val()+'</span>';
        console.log(jQuery('#print_date').val());
        content += '<span style="position: absolute; top: 277px; left: 290px; font-size: 15px; width: 280px;">'+jQuery('#guest_name').val()+'</span>';
        content += '<span style="position: absolute; top: 277px; left: 755px; font-size: 15px;">'+$recode+'</span>';
        
        content += '<span style="position: absolute; top: 303px; left: 80px; font-size: 15px; line-height: 20px; width: 500px; text-indent: 200px; text-transform: uppercase;">'+jQuery('#customer_name').val()+'</span>';
        content += '<span style="position: absolute; top: 303px; left: 755px; font-size: 15px; line-height: 20px;">'+jQuery('#start_date').val()+'</span>';
        content += '<span style="position: absolute; top: 335px; left: 760px; font-size: 15px;">'+jQuery('#end_date').val()+'</span>';
        
        content += '<span style="position: absolute; top: 363px; left: 80px; font-size: 15px; width: 500px; line-height: 20px; text-indent: 150px;">'+jQuery("#customer_address").val()+'</span>';
        content += '<span style="position: absolute; top: 363px; left: 735px; font-size: 15px; line-height: 20px;">TM/CK</span>';
        
        content += '<span style="position: absolute; top: 391px; left: 717px; font-size: 15px;">'+jQuery("#customer_bank_code").val()+'</span>';
        
        content += '<span style="position: absolute; top: 420px; left: 240px; font-size: 15px;">'+jQuery("#customer_tax_code").val()+'</span>';
        
        content += '<table style="width: 830px; position: absolute; top: 505px; left: 60px; font-size: 15px;">';
        content += content_table;
        content += '</table>';
        content += '<span style="position: absolute; top: 940px; width:95px; left: 290px; font-size: 15px;">10%</span>';
        content += '<span style="position: absolute; top: 940px; width:330px; right: 70px; font-size: 15px; text-align: right;">'+jQuery("#total_before_tax").val()+'</span>';
        content += '<span style="position: absolute; top: 960px;  width:300px; right: 70px; font-size: 15px; text-align: right;">'+jQuery("#service_amount").val()+'</span>';
        content += '<span style="position: absolute; top: 980px;  width:370px; right: 70px; font-size: 15px; text-align: right;">'+jQuery("#tax_amount").val()+'</span>';
        content += '<span style="position: absolute; top: 1000px;  width:200px; right: 70px; font-size: 15px; text-align: right;">'+jQuery("#total_amount").val()+'</span>';
        content += '<span style="position: absolute; top: 1040px; left: 220px; font-size: 15px;">'+ConvertNumberToCharacter.show(to_numeric(jQuery("#total_amount").val().trim()))+'</span>';
        
        document.getElementById("LightBoxPreviewContent").innerHTML=content;
        jQuery("#LightBoxPreview").css('display','');
    }
    var ConvertNumberToCharacter = function(){
    var t=["không","một","hai","ba","bốn","năm","sáu","bảy","tám","chín"],r=function(r,n){
        var o="",a=Math.floor(r/10),e=r%10;
        return a>1?(o=" "+t[a]+" mươi",1==e&&(o+=" mốt")):1==a?(o=" mười",1==e&&(o+=" một")):n&&e>0&&(o=" lẻ"),5==e&&a>=1?o+=" lăm":4==e&&a>=1?o+=" bốn":(e>1||1==e&&0==a)&&(o+=" "+t[e]),o},
        n=function(n,o){
            var a="",e=Math.floor(n/100),n=n%100;
            return o||e>0?(a=" "+t[e]+" trăm",a+=r(n,!0)):a=r(n,!1),a},
            o=function(t,r){
                var o="",a=Math.floor(t/1e6),t=t%1e6;a>0&&(o=n(a,r)+" triệu",r=!0);
                var e=Math.floor(t/1e3),t=t%1e3;
                return e>0&&(o+=n(e,r)+" nghìn",r=!0),t>0&&(o+=n(t,r)),o};
                return{show:function(r){
                    if(0==r)return t[0];
                    var n="",a="";
                    do ty=r%1e9,r=Math.floor(r/1e9),n=r>0?o(ty,!0)+a+n:o(ty,!1)+a+n,a=" tỷ";
                    while(r>0);
                    return n.substring(1,2).toUpperCase()+n.substring(2).trim()+ " đồng"
                    }
                }
}(); 
</script>