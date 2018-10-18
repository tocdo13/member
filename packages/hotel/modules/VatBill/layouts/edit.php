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
    <input name="type" type="hidden" id="type" />
    <input name="act" type="hidden" id="act" />
    <input name="invoice_ids" type="hidden" id="invoice_ids" />
    <div id="VatBillEditFrom">
        <div id="VatBillEditFromHeader" style="width: 100% !important;">
            <ul>
                <li class="f-left"><h3>[[.vat_infomation.]]</h3></li>
                
                <!--IF:cond_status(!isset([[=status=]]) OR ([[=status=]]!='CANCEL'))-->
                <li class="f-right"><div class="ButtonIcon w3-indigo" onclick="PreviewGuest();"><i class="fa fa-fw fa-eye"></i>[[.preview_before_print.]]</div></li>
                <!--/IF:cond_status-->
                
                <li class="f-right">[[.print_type.]]: <select name="print_type" id="print_type" style="width: 100px;"></select></li>
                
                <!--IF:cond_vat_code(!isset([[=vat_code=]]) OR ([[=vat_code=]]==''))-->
                <li class="f-right"><div class="ButtonIcon w3-gray" onclick="SaveFrom('SAVE_NO_PRINT');"><i class="fa fa-fw fa-qrcode"></i>[[.save_and_no_print_vat.]]</div></li>
                <!--/IF:cond_vat_code-->
                
                <!--IF:cond_status(!isset([[=status=]]) OR ([[=status=]]!='CANCEL'))-->
                <!--IF:cond([[=count_print=]]==0)--><li class="f-right"><div class="ButtonIcon w3-blue" onclick="SaveFrom('SAVE_CODE');"><i class="fa fa-fw fa-save"></i>[[.save_code.]]</div></li><!--/IF:cond-->
                <li class="f-right "><div class="ButtonIcon w3-orange" onclick="SaveFrom('PRINT');"><i class="fa fa-fw fa-print"></i>[[.save_and_print.]]</div></li>
                
                <!--/IF:cond_status-->
                
                <!--<li class="f-right"><div class="ButtonIcon"><i class="fa fa-fw fa-eye"></i>[[.view_before_print.]]</div></li>-->
                <!--IF:cond([[=count_print=]]!=0)--><li class="f-right"><div class="ButtonIcon w3-green" onclick="GetHistory();"><i class="fa fa-fw fa-history"></i>[[.view_history_print.]]</div></li><!--/IF:cond-->
                <li class="f-right"><label>[[.date.]] </label><input name="print_date" type="text" id="print_date" style="width: 100px;" readonly="" /></li>
            </ul>
        </div>
        <?php if(Form::$current->is_error()){ echo Form::$current->error_messages(); }?>
        <div id="VatBillEditFromHistory">
            <ul>
                <li><i class="fa fa-fw fa-info-circle"></i><!--IF:cond([[=count_print=]]==0)-->[[.first_print.]]<!--ELSE-->[[.printed.]]: [[|count_print|]] - [[.last_print_with.]]: [[|last_print|]] <!--/IF:cond--></li>
            </ul>
        </div>
        
        <div id="VatBillEditFormInfo">
            <table>
                <tr>
                    <td style="width: 120px;">[[.vat_code.]]: </td>
                    <td ><input name="vat_code" type="text" id="vat_code" style="width: 150px" /></td>
                    <td style="width: 110px;">[[.arrival_date.]]: </td>
                    <td><input name="start_date" type="text" id="start_date" style="width: 100px;" readonly="" /></td>
                    <td style="width: 100px;">[[.departure_date.]]: </td>
                    <td style="width: 150px;"><input name="end_date" type="text" id="end_date" style="width: 100px" readonly="" /></td>
                </tr>
                <tr>
                    <td style="width: 120px;">[[.guest_name.]]: </td>
                    <td ><input name="guest_name" type="text" id="guest_name" style="width: 150px" /></td>
                    <td style="width: 110px;">[[.company_code.]]: </td>
                    <td ><input name="customer_code" type="text" id="customer_code" style="width: 100px;" class="f-left" readonly="" /></td>
                    <td style="width: 100px;">[[.company_name.]]: </td>
                    <td style="width: 400px;" >
                        <input name="customer_name" type="text" id="customer_name" style="width: 298px; text-transform: uppercase;" /> 
                        <input name="customer_id" type="text" id="customer_id" style="display: none;" />
                        <a href="#" onclick="window.open('?page=customer&action=select_customer&to=vat','customer')" style="margin-right: 5px;"><i class="fa fa-fw fa-search"></i></a> 
                        <a href="#" onclick="resetcustomer();"><i class="fa fa-fw fa-remove"></i></a>
                    </td>
                </tr>
                <tr>
                    <td style="width: 120px;">[[.customer_tax_code.]]: </td>
                    <td ><input name="customer_tax_code" type="text" id="customer_tax_code" style="width: 150px;" /></td>
                    <td style="width: 110px;">[[.customer_address.]]: </td>
                    <td colspan="3"><input name="customer_address" type="text" id="customer_address" style="width: 500px;" /></td>
                </tr>
                <tr>
                    <td style="width: 120px;">[[.payment_method.]]: </td>
                    <td><select name="payment_method" id="payment_method" style="width: 150px;"></select></td>
                    <td style="width: 110px;">[[.customer_bank_code.]]: </td>
                    <td colspan="3" ><input name="customer_bank_code" type="text" id="customer_bank_code" style="width: 500px;" /></td>
                </tr>
            </table>
        </div>
    </div>
    
    <div id="VatBillEditFormDetail">
        <fieldset>
            <legend>[[.print_short_info.]]</legend>
            <table>
                <tr>
                    <td style="width: 110px;">[[.description_room.]]: </td>
                    <td><input name="description_room" type="text" id="description_room" style="width: 300px;" /></td>
                    <td style="width: 100px;">[[.description_bar.]]: </td>
                    <td><input name="description_bar" type="text" id="description_bar" style="width: 300px;" /></td>
                </tr>
                <tr>
                    <td style="width: 110px;">[[.description_banquet.]]: </td>
                    <td><input name="description_banquet" type="text" id="description_banquet" style="width: 300px;" /></td>
                    <td style="width: 100px;">[[.description_service.]]: </td>
                    <td><input name="description_service" type="text" id="description_service" style="width: 300px;" /></td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 110px;">[[.note.]]: </td>
                    <td colspan="3"><textarea name="note" id="note" style="width: 702px;"></textarea></td>
                    <!--IF:cond([[=type=]]!='BAR' && [[=type=]]!='FOLIO' && [[=type=]]!='BANQUET')-->
                
                    <td rowspan="2" style="padding-left: 20px;" ><a href="#" class="ButtonIcon w3-btn w3-lime" onclick="AddItem();" style="text-transform: uppercase; padding-top: 0px !important; text-decoration: none;"><i class="fa fa-fw fa-plus"></i>[[.add_invoice_other.]]</a></td>
                
                <!--/IF:cond-->
                </tr>
                
            </table>
        </fieldset>
        <fieldset>
            <legend>[[.invoice_detail.]]</legend>
            <table id="InvoiceDetail" border="1" bordercolor="#EEEEEE" cellpadding="2" cellspacing="2" style="width: 100%;">
                <tr style="height: 35px; text-align: center;">
                    <th>[[.invoice_id.]]</th>
                    <th>[[.description.]]</th>
                    <th>[[.date_use.]]</th>
                    <th style="width: 120px;">[[.total_before_tax.]]</th>
                    <th style="width: 70px;">[[.service_rate.]]</th>
                    <th style="width: 120px;">[[.service_amount.]]</th>
                    <th style="width: 70px;">[[.tax_rate.]]</th>
                    <th style="width: 120px;">[[.tax_amount.]]</th>
                    <th style="width: 120px;">[[.total_amount.]]</th>
                    <th style="width: 50px;">[[.delete.]]</th>
                </tr>
            </table>
            <table border="1" bordercolor="#EEEEEE" cellpadding="2" cellspacing="2" style="width: 100%;" >
                <tr style="height: 35px; text-align: center;">
                    <th style="text-align: right;">[[.total.]]:</th>
                    <th style="width: 120px;"><input name="total_before_tax" type="text" id="total_before_tax" readonly="" style="width: 100px; text-align: right;" /></th>
                    <th style="width: 70px;"></th>
                    <th style="width: 120px;"><input name="service_amount" type="text" id="service_amount" readonly="" style="width: 100px; text-align: right;" /></th>
                    <th style="width: 70px;"></th>
                    <th style="width: 120px;"><input name="tax_amount" type="text" id="tax_amount" readonly="" style="width: 100px; text-align: right;" /></th>
                    <th style="width: 120px;"><input name="total_amount" type="text" id="total_amount" readonly="" style="width: 100px; text-align: right;" /></th>
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
</form>
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
            <h3 class="f-left"><i class="fa fa-fw fa-history"></i>[[.history_printed.]]</h3>
            <p class="f-right" onclick="jQuery('#LightBoxContentHistory').css('display','none');" style="cursor: pointer;"><i class="fa fa-fw fa-remove"></i></p>
        </div>
        <div id="LightBoxContentHistoryMain">
            <!--LIST:history-->
                <p><i class="fa fa-fw fa-circle-o"></i> [[.print_no.]] <b>[[|history.stt|]]</b> -  [[.user_print.]]: <b>[[|history.user_print|]]</b> [[.in_time.]]: <b>[[|history.time_print|]]</b></p>
            <!--/LIST:history-->
        </div>
        <div id="LightBoxContentHistoryFooter">
            <p class="f-left"><i class="fa fa-fw fa-info-circle"></i> [[.history_is_recorded_when_the_user_manipulates_the_record_and_prints.]]</p>
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
    <?php if(Url::get('cmd')=='edit' and (!isset([[=vat_type=]]) or [[=vat_type=]]!='SAVE_NO_PRINT')){ ?>
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
            alert('[[.vat_code_is_not_null.]]');
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