<?php 
    date_default_timezone_set('Asia/Saigon');
    define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
    set_include_path(ROOT_PATH);
    require_once 'packages/core/includes/system/config.php';
    
    DB::query('update supplier set sync_cns=0');
    DB::query('update customer set sync_cns=0');
    DB::query('update account set sync_cns=0');
    DB::query('update massage_guest set sync_cns=0');
    DB::query('update traveller set sync_cns=0');
    DB::query('update currency set sync_cns=0');
    DB::query('update ticket set sync_cns_case=0,sync_cns_fee=0');
    DB::query('update product_category set sync_cns=0');
    DB::query('update department set sync_cns=0');
    DB::query('update unit set sync_cns=0');
    DB::query('update warehouse set sync_cns=0');
    DB::query('update payment set sync_cns=0');
    DB::query('update ticket_reservation set sync_cns=0');
    DB::query('update ve_reservation set sync_cns_vt=0,sync_cns_hh=0');
    DB::query('update bar_reservation set sync_cns_vt=0,sync_cns_hh=0');
    DB::query('update massage_reservation_room set sync_cns_vt=0,sync_cns_hh=0');
    DB::query('update folio set sync_cns_vt=0,sync_cns_hh=0');
    DB::query('update mice_invoice set sync_cns_vt=0,sync_cns_hh=0');
    DB::query('update product set sync_cns_vt=0,sync_cns_hh=0');
    DB::query('update wh_invoice set sync_cns_vt=0,sync_cns_hh=0');
    DB::query('update product_service_cns set sync_cns_case=0,sync_cns_fee=0');
    
    $hef = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=home';
    echo 'RESET dong bo CNS thanh cong! <a href="'.$hef.'"> Go To HomePage </a>';
?>