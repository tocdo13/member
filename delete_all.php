<?php
define('DEVELOPING',false);
define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
require_once ROOT_PATH.'packages/core/includes/system/config.php';
if(User::is_admin() and Session::get('user_id') == 'developer05' or Session::get('user_id') == 'daibt')//and Session::get('user_id') == 'developer02'
{
    
    //xoa du lieu dat phong, khach
	DB::query('truncate table reservation');
	DB::query('truncate table reservation_room');
	DB::query('truncate table reservation_traveller');
	DB::query('truncate table reservation_room_service');
    DB::query('truncate table traveller');
	DB::query('truncate table traveller_comment');
	DB::query('truncate table room_status');
    DB::query('truncate table ROOM_PRODUCT');
	DB::query('truncate table ROOM_PRODUCT_DETAIL');
    DB::query('truncate table booking_confirm');
    DB::query('truncate table room_allotment');
    DB::query('truncate table room_allotment_avail_rate');
    
    //xoa du lieu customer
    DB::query('truncate table CUSTOMER');
    DB::query('truncate table CUSTOMER_CARE');
    DB::query('truncate table CUSTOMER_CONTACT');
    DB::query('truncate table CUSTOMER_DEBT_SETTLEMENT');
    DB::query('truncate table CUSTOMER_RATE_COMMISSION');
    DB::query('truncate table CUSTOMER_RATE_POLICY');
    DB::query('truncate table FILE_CUSTOMER');
    DB::query('truncate table CUSTOMER_REVIEW_DEBT');
    
    DB::query('truncate table daily_quest');
	
    //xoa du lieu dich vu khac
	DB::query('truncate table EXTRA_SERVICE_INVOICE');
	DB::query('truncate table EXTRA_SERVICE_INVOICE_DETAIL');
    DB::query('truncate table EXTRA_SERVICE_INVOICE_TABLE');
    DB::query('truncate table AMENITIES_USED');
    DB::query('truncate table AMENITIES_USED_DETAIL');
    
    //xoa du lieu khach hang
    
	
    //xoa du lieu tong dai
	DB::query('truncate table TELEPHONE_REPORT_DAILY');

	//DB::query('truncate table res_product');
    //xoa du lieu nha hang	
    DB::query('truncate table BAR_CHARGE');
    DB::query('truncate table BAR_NOTE');
    DB::query('truncate table BAR_SHIFT');
	DB::query('truncate table bar_reservation');
	DB::query('truncate table bar_reservation_cancel');
	DB::query('truncate table bar_reservation_product');
    DB::query('truncate table bar_reservation_set_product');
	DB::query('truncate table bar_reservation_table');
    DB::query('truncate table bar_set_menu');
    DB::query('truncate table bar_set_menu_product');
    
    DB::query('truncate table chat');
    
    //xoa du lieu ban hang	
    DB::query('truncate table VE_RESERVATION');
    DB::query('truncate table VE_RESERVATION_PRODUCT');
    DB::query('truncate table VENDING_START_TERM_DEBIT');
    
    //xoa du lieu karaoke	
    DB::query('truncate table KARAOKE_CHARGE');
    DB::query('truncate table KARAOKE_NOTE');
    DB::query('truncate table KARAOKE_SHIFT');
	DB::query('truncate table karaoke_reservation');
	DB::query('truncate table karaoke_reservation_product');
	DB::query('truncate table karaoke_reservation_table');
	
    //xoa du lieu buong
	DB::query('truncate table HK_WH_INVOICE');
	DB::query('truncate table HK_WH_INVOICE_DETAIL');
	DB::query('truncate table HOUSEKEEPING_INVOICE');
	DB::query('truncate table HOUSEKEEPING_INVOICE_DETAIL');
    //DB::query('truncate table HOUSEKEEPING_EQUIPMENT');
	DB::query('truncate table HOUSEKEEPING_EQUIPMENT_DAMAGED');
	
    //xoa du lieu karaoke cu
	DB::query('truncate table KA_RESERVATION');
	DB::query('truncate table KA_RESERVATION_CANCEL');
	DB::query('truncate table KA_RESERVATION_PRODUCT');
	DB::query('truncate table KA_RESERVATION_ROOM');
    
    DB::query('truncate table NOTE');
    DB::query('truncate table ORDER_DETAIL');
    
    //xoa du lieu spa
	DB::query('truncate table MASSAGE_RESERVATION');
	DB::query('truncate table MASSAGE_RESERVATION_ROOM');
	DB::query('truncate table MASSAGE_PRODUCT_CONSUMED');
	DB::query('truncate table MASSAGE_STAFF_ROOM');

    //xoa du lieu ho boi, tennis, shop
	DB::query('truncate table SWIMMING_POOL_RESERVATION');
	DB::query('truncate table SWIMMING_POOL_RESERVATION_POOL');

	DB::query('truncate table TENNIS_RESERVATION');
	DB::query('truncate table TENNIS_RESERVATION_COURT');
	
	DB::query('truncate table SHOP_INVOICE');
	DB::query('truncate table SHOP_INVOICE_DETAIL');
    
    //xoa du lieu tiec
    DB::query('truncate table PARTY_PROMOTIONS');
    DB::query('truncate table PARTY_RESERVATION');
    DB::query('truncate table PARTY_RESERVATION_DETAIL');
    DB::query('truncate table PARTY_RESERVATION_ROOM');
    
    DB::query('truncate table PC_DEPARTMENT_PRODUCT');
    DB::query('truncate table PC_ORDER');
    DB::query('truncate table PC_ORDER_DETAIL');
    DB::query('truncate table PC_RECOMMEND_DETAIL');
    DB::query('truncate table PC_RECOMMEND_DETAIL_ORDER');
    DB::query('truncate table PC_RECOMMENDATION');
    DB::query('truncate table PC_SUP_PRICE');
    
    DB::query('truncate table RATE_CODE');
    DB::query('truncate table RATE_CODE_TIME');
    DB::query('truncate table RATE_CUSTOMER_GROUP');
    DB::query('truncate table RATE_ROOM_LEVEL');
    
    DB::query('truncate table REVEN_EXPEN_ITEMS');
    	
    //xoa du lieu kho
	DB::query('truncate table WH_INVOICE');
	DB::query('truncate table WH_INVOICE_DETAIL');
	
    DB::query('truncate table PAY_BY_CURRENCY');
	DB::query('truncate table PAYMENT');
	DB::query('truncate table TRAVELLER_FOLIO');
	DB::query('truncate table FOLIO');
	
	DB::query('truncate table FORGOT_OBJECT');
	
	DB::query('truncate table night_audit');
	DB::query('truncate table log');
    DB::query('truncate table history_log');
    if(is_dir('packages/user/modules/Log/file')){
        $log_file = scandir('packages/user/modules/Log/file');
        for($i_file=0;$i_file<sizeof($log_file);$i_file++){
            if($i_file>1){
                unlink('packages/user/modules/Log/file/'.$log_file[$i_file]);
            }
        }
    }
    if(is_dir('packages/hotel/log')){
        $log_file = scandir('packages/hotel/log');
        for($i_file=0;$i_file<sizeof($log_file);$i_file++){
            if($i_file>1){
                unlink('packages/hotel/log/'.$log_file[$i_file]);
            }
        }
    }
    DB::query('truncate table DEBIT_RECEPTION');
    
    //xoa du lieu tiec
    DB::query('truncate table ticket_cancelation');
    DB::query('truncate table ticket_invoice');
    DB::query('truncate table ticket_invoice_detail');
    DB::query('truncate table ticket_reservation');
    
    //xoa du lieu email
    DB::query('truncate table EMAIL_GROUP_EVENT_CUSTOMER');
    DB::query('truncate table EMAIL_LIST');
    DB::query('truncate table EMAIL_SEND');
    
    //
    DB::query('truncate table EMPLOYEE');
    DB::query('truncate table EMPLOYEE_PROFILE');
	
    // xoa du lieu quan ly mua hang
    DB::query('truncate table PURCHASES_DETAIL');
    DB::query('truncate table PURCHASES_GROUP_INVOICE');
    DB::query('truncate table PURCHASES_INVOICE');
    DB::query('truncate table PURCHASES_INVOICE_DETAIL');
    DB::query('truncate table PURCHASES_PROPOSED');

    // xoa du lieu quan ly tich diem
    DB::query('truncate table MEMBER_DISCOUNT');
    DB::query('truncate table MEMBER_LEVEL');
    DB::query('truncate table MEMBER_LEVEL_DISCOUNT');
    DB::query('truncate table MEMBER_LEVEL_POLICIES');
    DB::query('truncate table HISTORY_MEMBER');
    DB::query('truncate table HISTORY_MEMBER_DETAIL');
    DB::query('truncate table GROUP_TRAVELLER');
    DB::query('truncate table STATUS_TRAVELLER');
    
    // xoa du lieu package
    DB::query('truncate table PACKAGE_SERVICE');
    DB::query('truncate table PACKAGE_SALE');
    DB::query('truncate table PACKAGE_SALE_DETAIL');
    // xoa du lieu quan ly don hang
    DB::query('truncate table HANDOVER_INVOICE');
    DB::query('truncate table HANDOVER_INVOICE_DETAIL');
    DB::query('truncate table PC_ORDER');
    DB::query('truncate table PC_ORDER_DETAIL');
    //xoa du lieu rate code 
    DB::query('truncate table RATE_CODE');
    DB::query('truncate table RATE_CUSTOMER_GROUP');
    DB::query('truncate table RATE_ROOM_LEVEL');
    
    // xoa du lieu mice
    DB::query('truncate table MICE_BOOKING');
    DB::query('truncate table MICE_EXTRA_SERVICE');
    DB::query('truncate table MICE_INVOICE');
    DB::query('truncate table MICE_INVOICE_DETAIL');
    DB::query('truncate table MICE_PARTY');
    DB::query('truncate table MICE_PARTY_DETAIL_PRODUCT');
    DB::query('truncate table MICE_PARTY_DETAIL_ROOM');
    DB::query('truncate table MICE_RESERVATION');
    DB::query('truncate table MICE_RESTAURANT');
    DB::query('truncate table MICE_RESTAURANT_PRODUCT');
    DB::query('truncate table MICE_SETUP_BEO');
    DB::query('truncate table MICE_SPA');
    DB::query('truncate table MICE_SPA_PRODUCT');
    DB::query('truncate table MICE_SPA_SERVICE');
    DB::query('truncate table MICE_TICKET_RESERVATION');
    DB::query('truncate table MICE_VENDING');
    DB::query('truncate table MICE_VENDING_PRODUCT');
    
    DB::query('truncate table siteminder_map_customer');
    DB::query('truncate table siteminder_ota_cta_time');
    DB::query('truncate table siteminder_ota_ctd_time');
    DB::query('truncate table siteminder_ota_max_stay_time');
    DB::query('truncate table siteminder_ota_min_stay_time');
    DB::query('truncate table siteminder_ota_rates_time');
    DB::query('truncate table siteminder_ota_stop_sell_time');
    DB::query('truncate table siteminder_rate_avail_time');
    DB::query('truncate table siteminder_rate_cta_time');
    DB::query('truncate table siteminder_rate_ctd_time');
    
    DB::query('truncate table siteminder_rate_max_stay_time');
    DB::query('truncate table siteminder_rate_min_stay_time');
    DB::query('truncate table siteminder_rate_stop_sell_time');
    DB::query('truncate table siteminder_reservation');
    DB::insert('siteminder_room_rate',array('rate_plan_code'=>'ROOT','rate_name'=>'ROOT','structure_id'=>1000000000000000000));
    DB::query('truncate table siteminder_room_rate_ota');
    DB::query('truncate table siteminder_room_rate_ota_over');
    DB::query('truncate table siteminder_room_rate_over');
    DB::query('truncate table siteminder_room_rate_time');
    DB::query('truncate table siteminder_room_type');
    DB::query('truncate table siteminder_room_type_time');
    DB::query('truncate table siteminder_soap_body');
    if(is_dir('packages/hotel/packages/siteminder/includes/dataXml')){
        $log_file = scandir('packages/hotel/packages/siteminder/includes/dataXml');
        for($i_file=0;$i_file<sizeof($log_file);$i_file++){
            if($i_file>1 and is_file('packages/hotel/packages/siteminder/includes/dataXml/'.$log_file[$i_file])){
                unlink('packages/hotel/packages/siteminder/includes/dataXml/'.$log_file[$i_file]);
            }
        }
    }
    if(is_dir('packages/hotel/packages/siteminder/includes/dataRes')){
        $log_file = scandir('packages/hotel/packages/siteminder/includes/dataRes');
        for($i_file=0;$i_file<sizeof($log_file);$i_file++){
            if($i_file>1 and is_file('packages/hotel/packages/siteminder/includes/dataRes/'.$log_file[$i_file])){
                unlink('packages/hotel/packages/siteminder/includes/dataRes/'.$log_file[$i_file]);
            }
        }
    }
    
    
	echo '<h3>Newway: Deleted all successfull...!</h3>';
}
else
{
    echo '<h3>'.Portal::language('you_do_not_have_permission_to_do_this_action_please_contact_tcv').'<h3>';
}
?>