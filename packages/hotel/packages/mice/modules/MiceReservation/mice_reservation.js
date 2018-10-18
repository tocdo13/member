/**
* Mice JS plugin
* - Mutils Items All Element
* - Function pulic all
* - User Mice Reservation Add / Edit
* - Writer Tocdo - 13/04/2016 
**/
var Spa_InputCount = 100;
var Spa_InputCount_Service = 100;
var Spa_InputCount_Product = 100;
/** ----------------------------- **/
var Party_InputCount = 100;
var Party_InputCount_Room = 100;
var Party_InputCount_Product = 100;
/** ----------------------------- **/
var Booking_InputCount = 100;
/** ----------------------------- **/
var Extra_InputCount = 100;
/** ----------------------------- **/
var Bar_InputCount = 100;
var Bar_InputCount_Product = 100;
/** ----------------------------- **/
var Vending_InputCount = 100;
var Vending_InputCount_Product = 100;
/** ----------------------------- **/
function AddItems(key,mi_row=false)
{
    if(key=='SPA')
    {
        var input_count = Spa_InputCount++;
        var content = jQuery("#TemplateSpa").html().replace(/X######X/g,input_count);
        jQuery("#MutilsSPA").append(content);
        if(mi_row)
        {
            jQuery("#spa_id_"+input_count).val(mi_row['id']);
            if(mi_row['net_price']==undefined || (mi_row['net_price'] && mi_row['net_price']==0))
                document.getElementById("spa_net_price_"+input_count).checked=false;
            else
                document.getElementById("spa_net_price_"+input_count).checked=true;
            if(mi_row['discount_before_tax']==undefined || (mi_row['discount_before_tax'] && mi_row['discount_before_tax']==0))
                document.getElementById("spa_discount_before_tax_"+input_count).checked=false;
            else
                document.getElementById("spa_discount_before_tax_"+input_count).checked=true;
            jQuery("#spa_discount_percent_"+input_count).val(number_format(mi_row['discount_percent']));
            jQuery("#spa_discount_amount_"+input_count).val(number_format(mi_row['discount_amount']));
            jQuery("#spa_tax_rate_"+input_count).val(number_format(mi_row['tax_rate']));
            jQuery("#spa_service_rate_"+input_count).val(number_format(mi_row['service_rate']));
            jQuery("#spa_total_before_tax"+input_count).val(number_format(mi_row['total_before_tax']));
            jQuery("#spa_total_amount_"+input_count).val(number_format(mi_row['total_amount']));
        }
        
    }
    else if(key=='BANQUET')
    {
        var input_count = Party_InputCount++;
        var content = jQuery("#TemplateParty").html().replace(/X######X/g,input_count);
        jQuery("#MutilsPARTY").append(content);
        
        jQuery("#party_in_date_"+input_count).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
        jQuery("#party_time_in_"+input_count).mask('99:99');
        jQuery("#party_time_out_"+input_count).mask('99:99');
        if(mi_row)
        {
            jQuery("#party_id_"+input_count).val(mi_row['id']);
            jQuery("#party_party_type_"+input_count).val(mi_row['party_type']);
            jQuery("#party_in_date_"+input_count).val(mi_row['in_date']);
            jQuery("#party_time_in_"+input_count).val(mi_row['time_in']);
            jQuery("#party_time_out_"+input_count).val(mi_row['time_out']);
            jQuery("#party_discount_"+input_count).val(number_format(mi_row['discount']));
            jQuery("#party_discount_percent_"+input_count).val(number_format(mi_row['discount_percent']));
            jQuery("#party_service_rate_"+input_count).val(number_format(mi_row['service_rate']));
            jQuery("#party_tax_rate_"+input_count).val(number_format(mi_row['tax_rate']));
            jQuery("#party_promotions_"+input_count).val(mi_row['promotions']);
            jQuery("#party_total_before_tax_"+input_count).val(number_format(mi_row['total_before_tax']));
            jQuery("#party_total_"+input_count).val(number_format(mi_row['total']));
            jQuery("#party_party_reservation_id_"+input_count).val(mi_row['party_reservation_id']);
            
            if(mi_row['party_reservation_id']!='' && mi_row['party_reservation_id']!=0 )
            {
                jQuery(".cover_party_"+input_count).css('display','');
                jQuery(".cover_party_"+input_count).dblclick(function(){
                    window.open('?page=banquet_reservation&cmd='+mi_row['party_type']+'&action=edit&id='+mi_row['party_reservation_id'],'_blank');
                });
            }
            
        }
    }
    else if(key=='RES')
    {
        var input_count = Bar_InputCount++;
        var content = jQuery("#TemplateRes").html().replace(/X######X/g,input_count);
        jQuery("#MutilsRES").append(content);
        
        jQuery("#bar_in_date_"+input_count).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
        jQuery("#bar_time_in_"+input_count).mask('99:99');
        jQuery("#bar_time_out_"+input_count).mask('99:99');
        if(mi_row)
        {
            jQuery("#bar_id_"+input_count).val(mi_row['id']);
            jQuery("#bar_bar_id_"+input_count).val(mi_row['bar_id']);
            jQuery("#bar_table_id_"+input_count).val(mi_row['table_id']);
            jQuery("#bar_in_date_"+input_count).val(mi_row['in_date']);
            jQuery("#bar_time_in_"+input_count).val(mi_row['time_in']);
            jQuery("#bar_time_out_"+input_count).val(mi_row['time_out']);
            console.log(mi_row['full_rate']);
            console.log(mi_row['full_charge']);
            
            if(mi_row['full_rate']==undefined || (mi_row['full_rate'] && to_numeric(mi_row['full_rate'])==0)){
                document.getElementById('bar_full_rate_'+input_count).checked = false;
            }else{
                document.getElementById('bar_full_rate_'+input_count).checked = true;
            }
            if(mi_row['full_charge']==undefined || (mi_row['full_charge'] && to_numeric(mi_row['full_charge'])==0)){
                document.getElementById('bar_full_charge_'+input_count).checked = false;
            }else{
                document.getElementById('bar_full_charge_'+input_count).checked = true;
            }
            
            jQuery("#bar_service_rate_"+input_count).val(number_format(mi_row['service_rate']));
            jQuery("#bar_tax_rate_"+input_count).val(number_format(mi_row['tax_rate']));
            jQuery("#bar_banquet_order_type_"+input_count).val(mi_row['banquet_order_type']);
            jQuery("#bar_num_people_"+input_count).val(mi_row['num_people']);
            jQuery("#bar_bar_reservation_id_"+input_count).val(mi_row['bar_reservation_id']);
            jQuery("#bar_discount_"+input_count).val(number_format(mi_row['discount']));
            jQuery("#bar_discount_percent_"+input_count).val(number_format(mi_row['discount_percent']));
            if(mi_row['bar_reservation_id']!='' && mi_row['bar_reservation_id']!=0 )
            {
                jQuery(".cover_res_"+input_count).css('display','');
                jQuery(".cover_res_"+input_count).dblclick(function(){
                    window.open('?page=touch_bar_restaurant&cmd=edit&id='+mi_row['bar_reservation_id']+'&table_id='+mi_row['table_id']+'&bar_id='+mi_row['bar_id'],'_blank');
                });
            }
            parselayouttable(input_count);
        }
    }
    else if(key=='VENDING')
    {
        var input_count = Vending_InputCount++;
        var content = jQuery("#TemplateVending").html().replace(/X######X/g,input_count);
        jQuery("#MutilsVENDING").append(content);
        
        jQuery("#vending_in_date_"+input_count).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
        jQuery("#vending_time_in_"+input_count).mask('99:99');
        if(mi_row)
        {
            jQuery("#vending_id_"+input_count).val(mi_row['id']);
            jQuery("#vending_department_id_"+input_count).val(mi_row['department_id']);
            jQuery("#vending_department_code_"+input_count).val(mi_row['department_code']);
            jQuery("#vending_in_date_"+input_count).val(mi_row['in_date']);
            jQuery("#vending_time_in_"+input_count).val(mi_row['time_in']);
            jQuery("#vending_exchange_rate_"+input_count).val(mi_row['exchange_rate']);
            jQuery("#vending_ve_reservation_id_"+input_count).val(mi_row['ve_reservation_id']);
            
            if(mi_row['full_rate']==undefined || (mi_row['full_rate'] && mi_row['full_rate']==0))
                document.getElementById("vending_full_rate_"+input_count).checked=false;
            else
                document.getElementById("vending_full_rate_"+input_count).checked=true;
            
            if(mi_row['full_charge']==undefined || (mi_row['full_charge'] && mi_row['full_charge']==0))
                document.getElementById("vending_full_charge_"+input_count).checked=false;
            else
                document.getElementById("vending_full_charge_"+input_count).checked=true;
            
            jQuery("#vending_service_rate_"+input_count).val(number_format(mi_row['service_rate']));
            jQuery("#vending_tax_rate_"+input_count).val(number_format(mi_row['tax_rate']));
            jQuery("#vending_discount_"+input_count).val(number_format(mi_row['discount']));
            jQuery("#vending_discount_percent_"+input_count).val(number_format(mi_row['discount_percent']));
            
            if(mi_row['ve_reservation_id']!='' && mi_row['ve_reservation_id']!=0 )
            {
                jQuery(".cover_ve_"+input_count).css('display','');
                jQuery(".cover_ve_"+input_count).dblclick(function(){
                    window.open('?page=automatic_vend&cmd=edit&id='+mi_row['ve_reservation_id']+'&department_id='+mi_row['department_id']+'&department_code='+mi_row['department_code'],'_blank');
                });
            }
        }
    }
    else if(key=='REC')
    {
        var input_count = Booking_InputCount++;
        var content = jQuery("#TemplateRec").html().replace(/X######X/g,input_count);
        jQuery("#MutilsREC").append(content);
        
        jQuery("#booking_from_date_"+input_count).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
        jQuery("#booking_to_date_"+input_count).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
        jQuery("#booking_time_in_"+input_count).mask('99:99');
        jQuery("#booking_time_out_"+input_count).mask('99:99');
        if(mi_row)
        {
            jQuery("#booking_id_"+input_count).val(mi_row['id']);
            jQuery("#booking_room_level_id_"+input_count).val(mi_row['room_level_id']);
            jQuery("#booking_quantity_"+input_count).val(mi_row['quantity']);
            jQuery("#booking_child_"+input_count).val(mi_row['child']);
            jQuery("#booking_adult_"+input_count).val(mi_row['adult']);
            jQuery("#booking_time_in_"+input_count).val(mi_row['time_in']);
            jQuery("#booking_time_out_"+input_count).val(mi_row['time_out']);
            jQuery("#booking_from_date_"+input_count).val(mi_row['from_date']);
            jQuery("#booking_to_date_"+input_count).val(mi_row['to_date']);
            jQuery("#booking_price_"+input_count).val(number_format(mi_row['price']));
            jQuery("#booking_exchange_rate_"+input_count).val(mi_row['exchange_rate']);
            jQuery("#booking_usd_price_"+input_count).val(number_format(mi_row['usd_price']));
            jQuery("#booking_total_amount_"+input_count).val(number_format(mi_row['total_amount']));
            jQuery("#booking_recode_"+input_count).val(mi_row['recode']);
            
            if(mi_row['net_price']==undefined || (mi_row['net_price'] && mi_row['net_price']==0))
                document.getElementById("booking_net_price_"+input_count).checked=false;
            else
                document.getElementById("booking_net_price_"+input_count).checked=true;
                
            jQuery("#booking_service_rate_"+input_count).val(number_format(mi_row['service_rate']));
            jQuery("#booking_tax_rate_"+input_count).val(number_format(mi_row['tax_rate']));
            jQuery("#booking_note_"+input_count).val(mi_row['note']);
            
            if(mi_row['recode']!='' && mi_row['recode']!=0)
            {
                jQuery(".cover_rec_"+input_count).css('display','');
                jQuery(".cover_rec_"+input_count).dblclick(function(){
                    window.open('?page=reservation&cmd=edit&id='+mi_row['recode'],'_blank');
                });
            }
        }
    }
    else if(key=='EXS')
    {
        var input_count = Extra_InputCount++;
        var content = jQuery("#TemplateExs").html().replace(/X######X/g,input_count);
        jQuery("#MutilsEXS").append(content);
        jQuery("#extra_start_date_"+input_count).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
        jQuery("#extra_end_date_"+input_count).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
        jQuery("#extra_in_date_"+input_count).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
        if(mi_row)
        {
            jQuery("#extra_id_"+input_count).val(mi_row['id']);
            jQuery("#extra_type_"+input_count).val(mi_row['type']);
            jQuery("#extra_service_id_"+input_count).val(mi_row['service_id']);
            jQuery("#extra_service_name_"+input_count).val(mi_row['name']);
            jQuery("#extra_in_date_"+input_count).val(mi_row['in_date']);
            jQuery("#extra_start_date_"+input_count).val(mi_row['start_date']);
            jQuery("#extra_end_date_"+input_count).val(mi_row['end_date']);
            jQuery("#extra_quantity_"+input_count).val(mi_row['quantity']);
            jQuery("#extra_percentage_discount_"+input_count).val(mi_row['percentage_discount']);
            jQuery("#extra_price_"+input_count).val(number_format(mi_row['price']));
            jQuery("#extra_amount_discount_"+input_count).val(number_format(mi_row['amount_discount']));
            
            if(mi_row['net_price']==undefined || (mi_row['net_price'] && mi_row['net_price']==0))
                document.getElementById("extra_net_price_"+input_count).checked=false;
            else
                document.getElementById("extra_net_price_"+input_count).checked=true;
                
            if(mi_row['close']==undefined || (mi_row['close'] && mi_row['close']==0))
                document.getElementById("extra_close_"+input_count).checked=false;
            else
                document.getElementById("extra_close_"+input_count).checked=true;
                
            jQuery("#extra_service_rate_"+input_count).val(number_format(mi_row['service_rate']));
            jQuery("#extra_tax_rate_"+input_count).val(number_format(mi_row['tax_rate']));
            
            jQuery("#extra_total_before_tax_"+input_count).val(number_format(mi_row['total_before_tax']));
            jQuery("#extra_total_amount_"+input_count).val(number_format(mi_row['total_amount']));
            jQuery("#extra_note_"+input_count).val(mi_row['note']);
            jQuery("#extra_extra_id_"+input_count).val(mi_row['extra_id']);
            
            if(mi_row['extra_id']!='' && mi_row['extra_id']!=0)
            {
                jQuery(".cover_exs_"+input_count).css('display','');
                jQuery(".cover_exs_"+input_count).dblclick(function(){
                    window.open('?page=extra_service_invoice&cmd=edit&id='+mi_row['extra_id']+'&type='+mi_row['type'],'_blank');
                });
            }
        }else{
            selecttypeserviceextra(input_count);
        }
        
    }
}

function AddServiceSpa(input_count,mi_row=false)
{
    var input_detail = Spa_InputCount_Service++;
    var content = jQuery("#TemplateSpaService").html().replace(/X######X/g,input_count).replace(/Y######Y/g,input_detail);
    jQuery("#MutilsSPAService_"+input_count).append(content);
    
    jQuery("#spa_"+input_count+"_child_service_in_date_"+input_detail).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
    jQuery("#spa_"+input_count+"_child_service_time_in_"+input_detail).mask('99:99');
    jQuery("#spa_"+input_count+"_child_service_time_out_"+input_detail).mask('99:99');
    if(mi_row)
    {
        jQuery("#spa_"+input_count+"_child_service_id_"+input_detail).val(mi_row['id']);
        jQuery("#spa_"+input_count+"_child_service_product_id_"+input_detail).val(mi_row['product_id']);
        jQuery("#spa_"+input_count+"_child_service_price_id_"+input_detail).val(mi_row['price_id']);
        jQuery("#spa_"+input_count+"_child_service_product_name_"+input_detail).val(mi_row['product_name']);
        jQuery("#spa_"+input_count+"_child_service_spa_room_name_"+input_detail).val(mi_row['spa_room_name']);
        jQuery("#spa_"+input_count+"_child_service_spa_room_id_"+input_detail).val(mi_row['spa_room_id']);
        jQuery("#spa_"+input_count+"_child_service_staff_ids_"+input_detail).val(mi_row['staff_ids']);
        jQuery("#spa_"+input_count+"_child_service_in_date_"+input_detail).val(mi_row['in_date']);
        jQuery("#spa_"+input_count+"_child_service_time_in_"+input_detail).val(mi_row['time_in']);
        jQuery("#spa_"+input_count+"_child_service_time_out_"+input_detail).val(mi_row['time_out']);
        jQuery("#spa_"+input_count+"_child_service_quantity_"+input_detail).val(number_format(mi_row['quantity']));
        if(mi_row['edit_price'])
            jQuery("#spa_"+input_count+"_child_service_price_"+input_detail).val(number_format(mi_row['edit_price']));
        if(mi_row['price'])
            jQuery("#spa_"+input_count+"_child_service_price_"+input_detail).val(number_format(mi_row['price']));
    }
}

function AddProductSpa(input_count,mi_row=false)
{
    var input_detail = Spa_InputCount_Product++;
    var content = jQuery("#TemplateSpaProduct").html().replace(/X######X/g,input_count).replace(/Y######Y/g,input_detail);
    jQuery("#MutilsSPAProduct_"+input_count).append(content);
    
    jQuery("#spa_"+input_count+"_child_product_in_date_"+input_detail).datepicker({ minDate: new Date(CURRENT_YEAR, CURRENT_MONTH, CURRENT_DAY) });
    jQuery("#spa_"+input_count+"_child_product_time_in_"+input_detail).mask('99:99');
    jQuery("#spa_"+input_count+"_child_product_time_out_"+input_detail).mask('99:99');
    
    if(mi_row)
    {
        jQuery("#spa_"+input_count+"_child_product_id_"+input_detail).val(mi_row['id']);
        jQuery("#spa_"+input_count+"_child_product_product_id_"+input_detail).val(mi_row['product_id']);
        jQuery("#spa_"+input_count+"_child_product_price_id_"+input_detail).val(mi_row['price_id']);
        jQuery("#spa_"+input_count+"_child_product_product_name_"+input_detail).val(mi_row['product_name']);
        jQuery("#spa_"+input_count+"_child_product_in_date_"+input_detail).val(mi_row['in_date']);
        jQuery("#spa_"+input_count+"_child_product_time_in_"+input_detail).val(mi_row['time_in']);
        jQuery("#spa_"+input_count+"_child_product_time_out_"+input_detail).val(mi_row['time_out']);
        jQuery("#spa_"+input_count+"_child_product_quantity_"+input_detail).val(number_format(mi_row['quantity']));
        if(mi_row['edit_price'])
            jQuery("#spa_"+input_count+"_child_product_price_"+input_detail).val(number_format(mi_row['edit_price']));
        if(mi_row['price'])
            jQuery("#spa_"+input_count+"_child_product_price_"+input_detail).val(number_format(mi_row['price']));
        jQuery("#spa_"+input_count+"_child_product_amount_"+input_detail).val(number_format(mi_row['amount']));
    }
}

function AddProductParty(input_count,mi_row=false)
{
    var input_detail = Party_InputCount_Product++;
    var content = jQuery("#TemplatePartyProduct").html().replace(/X######X/g,input_count).replace(/Y######Y/g,input_detail);
    jQuery("#MutilsPARTYProduct_"+input_count).append(content);
    if(mi_row)
    {
        jQuery("#party_"+input_count+"_child_product_id_"+input_detail).val(mi_row['id']);
        jQuery("#party_"+input_count+"_child_product_product_id_"+input_detail).val(mi_row['product_id']);
        jQuery("#party_"+input_count+"_child_product_product_name_"+input_detail).val(mi_row['product_name']);
        jQuery("#party_"+input_count+"_child_product_unit_name_"+input_detail).val(mi_row['unit_name']);
        jQuery("#party_"+input_count+"_child_product_unit_id_"+input_detail).val(mi_row['unit_id']);
        jQuery("#party_"+input_count+"_child_product_quantity_"+input_detail).val(number_format(mi_row['quantity']));
        jQuery("#party_"+input_count+"_child_product_discount_percent_"+input_detail).val(number_format(mi_row['discount_percent']));
        jQuery("#party_"+input_count+"_child_product_price_"+input_detail).val(number_format(mi_row['price']));
        jQuery("#party_"+input_count+"_child_product_type_"+input_detail).val(mi_row['type']);
    }
}

function AddRoomParty(input_count,mi_row=false)
{
    var input_detail = Party_InputCount_Room++;
    var content = jQuery("#TemplatePartyRoom").html().replace(/X######X/g,input_count).replace(/Y######Y/g,input_detail);
    jQuery("#MutilsPARTYRoom_"+input_count).append(content);
    if(mi_row)
    {
        jQuery("#party_"+input_count+"_child_room_id_"+input_detail).val(mi_row['id']);
        jQuery("#party_"+input_count+"_child_room_party_room_id_"+input_detail).val(mi_row['party_room_id']);
        jQuery("#party_"+input_count+"_child_room_time_type_"+input_detail).val(mi_row['time_type']);
        jQuery("#party_"+input_count+"_child_room_address_"+input_detail).val(mi_row['address']);
        jQuery("#party_"+input_count+"_child_room_price_"+input_detail).val(number_format(mi_row['price']));
        jQuery("#party_"+input_count+"_child_room_discount_percent_"+input_detail).val(number_format(mi_row['discount_percent']));
        jQuery("#party_"+input_count+"_child_room_type_"+input_detail).val(mi_row['type']);
        jQuery("#party_"+input_count+"_child_room_note_"+input_detail).val(mi_row['note']);
    }
}

function AddProductBar(input_count,mi_row=false)
{
    var input_detail = Bar_InputCount_Product++;
    var content = jQuery("#TemplateResProduct").html().replace(/X######X/g,input_count).replace(/Y######Y/g,input_detail);
    jQuery("#MutilsRESProduct_"+input_count).append(content);
    if(mi_row)
    {
        jQuery("#bar_"+input_count+"_child_id_"+input_detail).val(mi_row['id']);
        jQuery("#bar_"+input_count+"_child_product_id_"+input_detail).val(mi_row['product_id']);
        jQuery("#bar_"+input_count+"_child_price_id_"+input_detail).val(mi_row['price_id']);
        jQuery("#bar_"+input_count+"_child_product_name_"+input_detail).val(mi_row['product_name']);
        jQuery("#bar_"+input_count+"_child_unit_name_"+input_detail).val(mi_row['unit_name']);
        jQuery("#bar_"+input_count+"_child_unit_id_"+input_detail).val(mi_row['unit_id']);
        jQuery("#bar_"+input_count+"_child_quantity_"+input_detail).val(number_format(mi_row['quantity']));
        jQuery("#bar_"+input_count+"_child_price_"+input_detail).val(number_format(mi_row['price']));
        jQuery("#bar_"+input_count+"_child_quantity_discount_"+input_detail).val(number_format(mi_row['quantity_discount']));
        jQuery("#bar_"+input_count+"_child_discount_rate_"+input_detail).val(number_format(mi_row['discount_rate']));
        jQuery("#bar_"+input_count+"_child_note_"+input_detail).val(mi_row['note']);
        
    }
}

function AddProductVending(input_count,mi_row=false)
{
    var input_detail = Vending_InputCount_Product++;
    var content = jQuery("#TemplateVendingProduct").html().replace(/X######X/g,input_count).replace(/Y######Y/g,input_detail);
    jQuery("#MutilsVENDINGProduct_"+input_count).append(content);
    if(mi_row)
    {
        jQuery("#vending_"+input_count+"_child_id_"+input_detail).val(mi_row['id']);
        jQuery("#vending_"+input_count+"_child_product_id_"+input_detail).val(mi_row['product_id']);
        jQuery("#vending_"+input_count+"_child_price_id_"+input_detail).val(mi_row['price_id']);
        jQuery("#vending_"+input_count+"_child_product_name_"+input_detail).val(mi_row['product_name']);
        jQuery("#vending_"+input_count+"_child_unit_name_"+input_detail).val(mi_row['unit_name']);
        jQuery("#vending_"+input_count+"_child_unit_id_"+input_detail).val(mi_row['unit_id']);
        jQuery("#vending_"+input_count+"_child_quantity_"+input_detail).val(number_format(mi_row['quantity']));
        jQuery("#vending_"+input_count+"_child_price_"+input_detail).val(number_format(mi_row['price']));
        jQuery("#vending_"+input_count+"_child_quantity_discount_"+input_detail).val(number_format(mi_row['quantity_discount']));
        jQuery("#vending_"+input_count+"_child_discount_rate_"+input_detail).val(number_format(mi_row['discount_rate']));
        jQuery("#vending_"+input_count+"_child_note_"+input_detail).val(mi_row['note']);
    }
}

/** coppy **/
function CoppyBar(input_count)
{
    count = to_numeric(jQuery("#coppy_bar_"+input_count).val());
    if(count>0)
    {
        mi_row = new Array();
        mi_row['id'] = '';
        
        mi_row['bar_id'] = jQuery("#bar_bar_id_"+input_count).val();
        mi_row['table_id'] = jQuery("#bar_table_id_"+input_count).val();
        mi_row['in_date'] = jQuery("#bar_in_date_"+input_count).val();
        mi_row['time_in'] = jQuery("#bar_time_in_"+input_count).val();
        mi_row['time_out'] = jQuery("#bar_time_out_"+input_count).val();
        
        if(document.getElementById("bar_full_rate_"+input_count).checked==true)
            mi_row['full_rate']=1;
        else
            mi_row['full_rate']=0;
        
        if(document.getElementById("bar_full_charge_"+input_count).checked==true)
            mi_row['full_charge']=1;
        else
            mi_row['full_charge']=0;
        mi_row['bar_reservation_id'] = '';
        mi_row['service_rate'] = number_format(jQuery("#bar_service_rate_"+input_count).val());
        mi_row['tax_rate'] = number_format(jQuery("#bar_tax_rate_"+input_count).val());
        mi_row['banquet_order_type'] = jQuery("#bar_banquet_order_type_"+input_count).val();
        mi_row['num_people'] = jQuery("#bar_num_people_"+input_count).val();
        mi_row['discount'] = number_format(jQuery("#bar_discount_"+input_count).val());
        mi_row['discount_percent'] = number_format(jQuery("#bar_discount_percent_"+input_count).val());
        var count_table = -1;
        for(var c in list_bar_tables)
        {
            if(list_bar_tables[c]['bar_id']==mi_row['bar_id'])
                count_table++;
        }
        if(count>count_table)
        {
            alert('<h3>BAR</h3><span>So luong Coppy vuot qua so luong ban cua nha hang</span><br/>');
            return false;
        }
        else
        {
            for(var k=1;k<=count;k++)
            {
                AddItems('RES',mi_row);
                var i = Bar_InputCount-1;
                
                for(var input_detail=100;input_detail<=Bar_InputCount_Product;input_detail++)
                {
                    if(jQuery("#bar_"+input_count+"_child_id_"+input_detail).val()!=undefined)
                    {
                        mi_row_product = new Array();
                        mi_row_product['id'] = '';
                        mi_row_product['product_id'] = jQuery("#bar_"+input_count+"_child_product_id_"+input_detail).val();
                        mi_row_product['price_id'] = jQuery("#bar_"+input_count+"_child_price_id_"+input_detail).val();
                        mi_row_product['product_name'] = jQuery("#bar_"+input_count+"_child_product_name_"+input_detail).val();
                        mi_row_product['unit_name'] = jQuery("#bar_"+input_count+"_child_unit_name_"+input_detail).val();
                        mi_row_product['unit_id'] = jQuery("#bar_"+input_count+"_child_unit_id_"+input_detail).val();
                        mi_row_product['quantity'] = number_format(jQuery("#bar_"+input_count+"_child_quantity_"+input_detail).val());
                        mi_row_product['price'] = number_format(jQuery("#bar_"+input_count+"_child_price_"+input_detail).val());
                        mi_row_product['quantity_discount'] = number_format(jQuery("#bar_"+input_count+"_child_quantity_discount_"+input_detail).val());
                        mi_row_product['discount_rate'] = number_format(jQuery("#bar_"+input_count+"_child_discount_rate_"+input_detail).val());
                        mi_row_product['note'] = jQuery("#bar_"+input_count+"_child_note_"+input_detail).val();
                        AddProductBar(i,mi_row_product);
                    }
                }
            }
        }
        
        GetTotalAmount();
        return true;
    }
    else
    {
        alert('<h3>BAR</h3><span>So luong Coppy phai > hoac = (1)</span><br/>');
        return false
    }
}
function CoppyVending(input_count)
{
    count = to_numeric(jQuery("#coppy_vending_"+input_count).val());
    if(count>0)
    {
        mi_row = new Array();
        mi_row['id'] = '';
        
        mi_row['department_id'] = jQuery("#vending_department_id_"+input_count).val();
        mi_row['department_code'] = jQuery("#vending_department_id_"+input_count).val();
        mi_row['in_date'] = jQuery("#vending_in_date_"+input_count).val();
        mi_row['time_in'] = jQuery("#vending_time_in_"+input_count).val();
        mi_row['exchange_rate'] = jQuery("#vending_exchange_rate_"+input_count).val();
        mi_row['ve_reservation_id'] = '';
        if(document.getElementById("vending_full_rate_"+input_count).checked==true)
            mi_row['full_rate']=1;
        else
            mi_row['full_rate']=0;
        
        if(document.getElementById("vending_full_charge_"+input_count).checked==true)
            mi_row['full_charge']=1;
        else
            mi_row['full_charge']=0;
        
        mi_row['service_rate'] = number_format(jQuery("#vending_service_rate_"+input_count).val());
        mi_row['tax_rate'] = number_format(jQuery("#vending_tax_rate_"+input_count).val());
        mi_row['banquet_order_type'] = jQuery("#vending_banquet_order_type_"+input_count).val();
        mi_row['num_people'] = jQuery("#vending_num_people_"+input_count).val();
        mi_row['discount'] = number_format(jQuery("#vending_discount_"+input_count).val());
        mi_row['discount_percent'] = number_format(jQuery("#vending_discount_percent_"+input_count).val());
        for(var k=1;k<=count;k++)
        {
            AddItems('VENDING',mi_row);
            var i = Vending_InputCount-1;
            
            for(var input_detail=100;input_detail<=Vending_InputCount_Product;input_detail++)
            {
                if(jQuery("#vending_"+input_count+"_child_id_"+input_detail).val()!=undefined)
                {
                    mi_row_product = new Array();
                    mi_row_product['id'] = '';
                    mi_row_product['product_id'] = jQuery("#vending_"+input_count+"_child_product_id_"+input_detail).val();
                    mi_row_product['price_id'] = jQuery("#vending_"+input_count+"_child_price_id_"+input_detail).val();
                    mi_row_product['product_name'] = jQuery("#vending_"+input_count+"_child_product_name_"+input_detail).val();
                    mi_row_product['unit_name'] = jQuery("#vending_"+input_count+"_child_unit_name_"+input_detail).val();
                    mi_row_product['unit_id'] = jQuery("#vending_"+input_count+"_child_unit_id_"+input_detail).val();
                    mi_row_product['quantity'] = number_format(jQuery("#vending_"+input_count+"_child_quantity_"+input_detail).val());
                    mi_row_product['price'] = number_format(jQuery("#vending_"+input_count+"_child_price_"+input_detail).val());
                    mi_row_product['quantity_discount'] = number_format(jQuery("#vending_"+input_count+"_child_quantity_discount_"+input_detail).val());
                    mi_row_product['discount_rate'] = number_format(jQuery("#vending_"+input_count+"_child_discount_rate_"+input_detail).val());
                    mi_row_product['note'] = jQuery("#vending_"+input_count+"_child_note_"+input_detail).val();
                    AddProductVending(i,mi_row_product);
                }
            }
        }
        
        GetTotalAmount();
        return true;
    }
    else
    {
        alert('<h3>BAR</h3><span>So luong Coppy phai > hoac = (1)</span><br/>');
        return false
    }
}
/** end coppy **/

function GetTotalAmount()
{
    var $total_amount = 0;
/**-----------------------------------**/
var $total_spa = 0;
var $quantity_spa = 0;
/**-----------------------------------**/
    /** SPA **/
    for(var i=100;i<=Spa_InputCount;i++)
    {
        if(jQuery("#spa_id_"+i).val()!=undefined)
        {
            $totalitem = 0;
            $totalamountitem = 0;
            $quantity_spa++;
            if(document.getElementById("spa_net_price_"+i).checked==true)
                $net = 1;
            else
                $net=0;
            if(document.getElementById("spa_discount_before_tax_"+i).checked==true)
                $discount_before_tax = 1;
            else
                $discount_before_tax=0;
            $discount_percent = to_numeric(jQuery("#spa_discount_percent_"+i).val());
            $discount_amount = to_numeric(jQuery("#spa_discount_amount_"+i).val());
            $service_rate = to_numeric(jQuery("#spa_service_rate_"+i).val());
            $tax_rate = to_numeric(jQuery("#spa_tax_rate_"+i).val());
            
            /**************** tinh tien dich vu *******************/
            for(var j=100;j<=Spa_InputCount_Service;j++)
            {
                if(jQuery("#spa_"+i+"_child_service_id_"+j).val()!=undefined)
                {
                    $service_quantity = to_numeric(jQuery("#spa_"+i+"_child_service_quantity_"+j).val());
                    $service_price = to_numeric(jQuery("#spa_"+i+"_child_service_price_"+j).val());
                    
                    /** kiem tra gia net - lay tien truoc thue phi **/
                    if($net==1)
                        $service_price = $service_price / ( (1+$service_rate/100)*(1+$tax_rate/100) );
                    
                    /** lay tong tien truoc thue phi **/
                    $service_price = $service_price * $service_quantity;
                    
                    /** tinh giam gia truoc thue phi **/
                    if($discount_before_tax==1)
                    {
                        $service_price = $service_price - ($service_price * ($discount_percent/100));
                        $service_price = $service_price - $discount_amount;
                    }
                    $totalitem += $service_price; 
                    /** tinh tong tien sau thue phi **/
                    $service_price = $service_price * (1+$service_rate/100)*(1+$tax_rate/100);
                    
                    /** tinh giam gia sau thue phi **/
                    if($discount_before_tax==0)
                    {
                        $service_price = $service_price - ($service_price * ($discount_percent/100));
                        $service_price = $service_price - $discount_amount;
                    }
                    /** lay tong tien cuoi cung **/
                    $total_spa += $service_price;
                    $totalamountitem += $service_price; 
                }
            }
            
            /**************** tinh tien san pham *******************/
            for(var j=100;j<=Spa_InputCount_Product;j++)
            {
                if(jQuery("#spa_"+i+"_child_product_id_"+j).val()!=undefined)
                {
                    $product_quantity = to_numeric(jQuery("#spa_"+i+"_child_product_quantity_"+j).val());
                    $product_price = to_numeric(jQuery("#spa_"+i+"_child_product_price_"+j).val());
                    
                    /** kiem tra gia net - lay tien truoc thue phi **/
                    if($net==1)
                        $product_price = $product_price / ( (1+$service_rate/100)*(1+$tax_rate/100) );
                    
                    /** lay tong tien truoc thue phi **/
                    $product_price = $product_price * $product_quantity;
                    
                    /** tinh giam gia truoc thue phi **/
                    if($discount_before_tax==1)
                    {
                        $product_price = $product_price - ($product_price * ($discount_percent/100));
                        $product_price = $product_price - $discount_amount;
                    }
                    $totalitem += $product_price; 
                    /** gan vao tong san pham **/
                    jQuery("#spa_"+i+"_child_product_amount_"+j).val(number_format($product_price));
                                        
                    /** tinh tong tien sau thue phi **/
                    $product_price = $product_price * (1+$service_rate/100)*(1+$tax_rate/100);
                    
                    /** tinh giam gia sau thue phi **/
                    if($discount_before_tax==0)
                    {
                        $product_price = $product_price - ($product_price * ($discount_percent/100));
                        $product_price = $product_price - $discount_amount;
                    }
                    
                    /** lay tong tien cuoi cung **/
                    $total_spa += $product_price; 
                    $totalamountitem += $product_price; 
                }
            }
            jQuery("#spa_total_before_tax_"+i).val(number_format($totalitem));
            jQuery("#spa_total_amount_"+i).val(number_format($totalamountitem));
        }
    }
    $total_amount += $total_spa;
    jQuery("#summary_quantity_SPA").html($quantity_spa);
    jQuery("#summary_total_SPA").html(number_format($total_spa));
    jQuery("#MiceDepartment_SPA .DepartmentMessenger").html($quantity_spa);
    /** END SPA **/
    
    
    
/** ----------------------------- **/
var $total_party = 0;
var $quantity_party = 0;
/** ----------------------------- **/
    /** PARTY **/
    for(var i=100;i<=Party_InputCount;i++)
    {
        if(jQuery("#party_id_"+i).val()!=undefined)
        {
            $quantity_party++;
            $service_rate = to_numeric(jQuery("#party_service_rate_"+i).val());
            $tax_rate = to_numeric(jQuery("#party_tax_rate_"+i).val());
            
            $totalitem = 0;
            $totalamountitem = 0;
            for(var j=100;j<=Party_InputCount_Product;j++)
            {
                if(jQuery("#party_"+i+"_child_product_id_"+j).val()!=undefined)
                {
                    $quantity = to_numeric(jQuery("#party_"+i+"_child_product_quantity_"+j).val());
                    $price = to_numeric(jQuery("#party_"+i+"_child_product_price_"+j).val());
                    
                    $amount = $quantity*$price;
                    $totalitem += $amount;
                    
                    $amount = $amount * (1+$service_rate/100)*(1+$tax_rate/100);
                    $totalamountitem += $amount;
                    $total_party += $amount;
                }
            }
            for(var j=100;j<=Party_InputCount_Room;j++)
            {
                if(jQuery("#party_"+i+"_child_room_id_"+j).val()!=undefined)
                {
                    $quantity = 1;
                    $price = to_numeric(jQuery("#party_"+i+"_child_room_price_"+j).val());
                    
                    $amount = $quantity*$price;
                    $totalitem += $amount;
                    
                    $amount = $amount * (1+$service_rate/100)*(1+$tax_rate/100);
                    $totalamountitem += $amount;
                    $total_party += $amount;
                }
            }
            jQuery("#party_total_before_tax_"+i).val(number_format($totalitem));
            jQuery("#party_total_"+i).val(number_format($totalamountitem));
        }
    }
    $total_amount += $total_party;
    jQuery("#summary_quantity_BANQUET").html($quantity_party);
    jQuery("#summary_total_BANQUET").html(number_format($total_party));
    jQuery("#MiceDepartment_BANQUET .DepartmentMessenger").html($quantity_party);
    /** END PARTY **/




/** ------------------------------------ **/
var $total_booking = 0;
var $quantity_booking = 0;
/** ------------------------------------ **/
    /** BOOKING **/
    for(var i=100;i<=Booking_InputCount;i++)
    {
        if(jQuery("#booking_id_"+i).val()!=undefined)
        {
            $quantity_booking++;
            if(document.getElementById("booking_net_price_"+i).checked==true)
                $net = 1;
            else
                $net=0;
            $service_rate = to_numeric(jQuery("#booking_service_rate_"+i).val());
            $tax_rate = to_numeric(jQuery("#booking_tax_rate_"+i).val());
            
            $quantity = to_numeric(jQuery("#booking_quantity_"+i).val());
            $price = to_numeric(jQuery("#booking_price_"+i).val());
            $arrival_time = jQuery("#booking_from_date_"+i).val();
            $departure_time = jQuery("#booking_to_date_"+i).val();
            
            $quantity_date = count_date($arrival_time,$departure_time);
            if($quantity_date<0)
            {
                //alert('<h3>BOOKING</h3><br/><span><i class="fa fa-calendar-times-o fa-fw"></i> Ngay den phai nho hon ngay di</span>');
            }
            else 
            {
                if($quantity_date==0)
                    $quantity_date = 1;
                
                if($net==1)
                    $price = $price / ( (1+$service_rate/100)*(1+$tax_rate/100) );
                /** tinh tien dem phong **/
                $amount = $price*$quantity_date;
                /** tinh tien so luong phong **/
                $amount = $amount*$quantity;
                /** tinh thue phi **/
                $amount = $amount * (1+$service_rate/100)*(1+$tax_rate/100);
                if(jQuery("#booking_recode_"+i).val()=='' || jQuery("#booking_recode_"+i).val()==0)
                {
                    jQuery("#booking_total_amount_"+i).val(number_format($amount));
                    $total_booking += $amount;
                }
                else
                {
                    $total_booking += to_numeric(jQuery("#booking_total_amount_"+i).val());
                }
                
            }
                
        }
    }
    $total_amount += $total_booking;
    jQuery("#summary_quantity_REC").html($quantity_booking);
    jQuery("#summary_total_REC").html(number_format($total_booking));
    jQuery("#MiceDepartment_REC .DepartmentMessenger").html($quantity_booking);
    /** END BOOKING **/


/** ------------------------------------ **/
var $total_extra = 0;
var $quantity_extra = 0;
/** ------------------------------------ **/
    /** EXTRA **/
    for(var i=100;i<=Extra_InputCount;i++)
    {
        if(jQuery("#extra_id_"+i).val()!=undefined)
        {
            $quantity_extra++;
            $arrival_time = jQuery("#extra_start_date_"+i).val();
            $departure_time = jQuery("#extra_end_date_"+i).val();
            $quantity_date = count_date($arrival_time,$departure_time);
            $quantity_date ++;
            $quantity = to_numeric(jQuery("#extra_quantity_"+i).val());
            $quantity = $quantity*$quantity_date;
            
            $price = to_numeric(jQuery("#extra_price_"+i).val());
            $service_rate = to_numeric(jQuery("#extra_service_rate_"+i).val());
            $tax_rate = to_numeric(jQuery("#extra_tax_rate_"+i).val());
            $discount_percent = to_numeric(jQuery("#extra_percentage_discount_"+i).val());
            $discount_amount = to_numeric(jQuery("#extra_amount_discount_"+i).val());
            $amount = $price;
            
            if(document.getElementById("extra_net_price_"+i).checked==true)
                $amount = $price/((1+$service_rate/100)*(1+$tax_rate/100));
            
            $amount = $amount*$quantity;
            
            $amount = $amount - ($amount*($discount_percent/100));
            $amount = $amount - $discount_amount;
            
            jQuery("#extra_total_before_tax_"+i).val(number_format($amount));
            
            $amount = $amount * (1+$service_rate/100)*(1+$tax_rate/100);
            
            jQuery("#extra_total_amount_"+i).val(number_format($amount));
            
            $total_extra += $amount;
            
        }
    }
    $total_amount += $total_extra;
    jQuery("#summary_quantity_EXS").html($quantity_extra);
    jQuery("#summary_total_EXS").html(number_format($total_extra));
    jQuery("#MiceDepartment_EXS .DepartmentMessenger").html($quantity_extra);
    /** END EXTRA **/

/** -------------------------------------------- **/
var $total_bar = 0;
var $quantity_bar = 0;
/** -------------------------------------------- **/
    /** BAR **/
    for(var i=100;i<=Bar_InputCount;i++)
    {
        if(jQuery("#bar_id_"+i).val()!=undefined)
        {
            $quantity_bar++;
            if(document.getElementById("bar_full_rate_"+i).checked==true)
                $full_rate = 1;
            else
                $full_rate = 0;
            if(document.getElementById("bar_full_charge_"+i).checked==true)
                $full_charge = 1;
            else
                $full_charge = 0;
            $service_rate = to_numeric(jQuery("#bar_service_rate_"+i).val());
            $tax_rate = to_numeric(jQuery("#bar_tax_rate_"+i).val());
            $discount_amount = to_numeric(jQuery("#bar_discount_"+i).val());
            $discount_percent = to_numeric(jQuery("#bar_discount_percent_"+i).val());
            $discount_after_tax = jQuery("#discount_after_tax_"+i).val();
            for(var j=100;j<=Bar_InputCount_Product;j++)
            {
                if(jQuery("#bar_"+i+"_child_id_"+j).val()!=undefined)
                {
                    $quantity = to_numeric(jQuery("#bar_"+i+"_child_quantity_"+j).val());
                    $price = to_numeric(jQuery("#bar_"+i+"_child_price_"+j).val());
                    $quantity_discount = to_numeric(jQuery("#bar_"+i+"_child_quantity_discount_"+j).val());
                    $discount_product_percent = to_numeric(jQuery("#bar_"+i+"_child_discount_rate_"+j).val());
                    if($full_rate==1)
                        $price = $price / ( (1+$service_rate/100)*(1+$tax_rate/100) );
                    else if($full_charge==1)
                        $price = $price / (1+$service_rate/100);
                    
                    $quantity = $quantity-$quantity_discount;
                    $amount = $price*$quantity;
                    //$amount = $amount - ($amount*(($discount_product_percent+$discount_percent)/100));
                    $amount = $amount - ($amount*(($discount_product_percent)/100));
                    //$amount = $amount * (1+$service_rate/100)*(1+$tax_rate/100);
                    $total_bar += $amount;
                }
            }
            if($discount_after_tax)
            {
                $total_bar=$total_bar * (1+$service_rate/100)*(1+$tax_rate/100);
                $total_bar -= $discount_amount;
            }
            else
            {
                $total_bar = $total_bar-($total_bar*(($discount_percent)/100))-$discount_amount;
                $total_bar=$total_bar * (1+$service_rate/100)*(1+$tax_rate/100);
            }
        }
    }
    $total_amount += $total_bar;
    jQuery("#summary_quantity_RES").html($quantity_bar);
    jQuery("#summary_total_RES").html(number_format($total_bar));
    jQuery("#MiceDepartment_RES .DepartmentMessenger").html($quantity_bar);
    /** END BAR **/

/** -------------------------------------------- **/
var $total_vending = 0;
var $quantity_vending = 0;
/** -------------------------------------------- **/
    /** VENDING **/
    for(var i=100;i<=Vending_InputCount;i++)
    {
        if(jQuery("#vending_id_"+i).val()!=undefined)
        {
            $quantity_vending++;
            if(document.getElementById("vending_full_rate_"+i).checked==true)
                $full_rate = 1;
            else
                $full_rate = 0;
            if(document.getElementById("vending_full_charge_"+i).checked==true)
                $full_charge = 1;
            else
                $full_charge = 0;
            $service_rate = to_numeric(jQuery("#vending_service_rate_"+i).val());
            $tax_rate = to_numeric(jQuery("#vending_tax_rate_"+i).val());
            $discount_amount = to_numeric(jQuery("#vending_discount_"+i).val());
            $discount_percent = to_numeric(jQuery("#vending_discount_percent_"+i).val());
            
            for(var j=100;j<=Vending_InputCount_Product;j++)
            {
                if(jQuery("#vending_"+i+"_child_id_"+j).val()!=undefined)
                {
                    $quantity = to_numeric(jQuery("#vending_"+i+"_child_quantity_"+j).val());
                    $price = to_numeric(jQuery("#vending_"+i+"_child_price_"+j).val());
                    $quantity_discount = to_numeric(jQuery("#vending_"+i+"_child_quantity_discount_"+j).val());
                    $discount_product_percent = to_numeric(jQuery("#vending_"+i+"_child_discount_rate_"+j).val());
                    if($full_rate==1)
                        $price = $price / ( (1+$service_rate/100)*(1+$tax_rate/100) );
                    else if($full_charge==1)
                        $price = $price / (1+$service_rate/100);
                    
                    $quantity = $quantity-$quantity_discount;
                    
                    $amount = $price*$quantity;
                    $amount = $amount - ($amount*(($discount_product_percent+$discount_percent)/100));
                    
                    $amount = $amount * (1+$service_rate/100)*(1+$tax_rate/100);
                    $total_vending += $amount;
                }
            }
            $total_vending -= $discount_amount;
        }
    }
    $total_amount += $total_vending;
    jQuery("#summary_quantity_VENDING").html($quantity_vending);
    jQuery("#summary_total_VENDING").html(number_format($total_vending));
    jQuery("#MiceDepartment_VENDING .DepartmentMessenger").html($quantity_vending);
    /** END VENDING **/
    
    /****/
    jQuery("#total_amount").val(number_format($total_amount));
    $mice_discount_amount = to_numeric(jQuery("#discount_amount").val());
    jQuery("#discount_amount").val(number_format($mice_discount_amount));
    jQuery("#grand_total_amount_payment").val(number_format($total_amount-$mice_discount_amount));
    if(jQuery("#deposit").val()!=undefined)
    {
        var depositmice = to_numeric(jQuery("#deposit").val());
    }
    else
    {
        var depositmice = 0;
    }
    if(jQuery("#payment").val()!=undefined)
    {
        var paymentmice = to_numeric(jQuery("#payment").val());
    }
    else
    {
        var paymentmice = 0;
    }
    if(jQuery("#remain").val()!=undefined)
    {
        var remainmice = to_numeric(jQuery("#total_amount").val()) - (depositmice+paymentmice);
        jQuery("#remain").val(number_format(remainmice));
    }
}

