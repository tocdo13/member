<?php 
    date_default_timezone_set('Asia/Saigon');
    define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
    set_include_path(ROOT_PATH);
    require_once 'packages/core/includes/system/config.php';
    
    /** DONG BO DANH MUC  **/
        
        /** dong bo doi tuong **/
        require_once 'sync_cns/DM/contact.php';
        sync_supplier_contacts();
        sync_customer_contacts();
        sync_account_contacts();
        sync_massage_guest_contacts();
        sync_traveller_contacts();
        sync_other_contacts();
        
        /** dong bo loai tien **/
        require_once 'sync_cns/DM/currency.php';
        sync_currency();
        
        /** dong bo kho **/
       require_once 'sync_cns/DM/warehouse.php';
        sync_warehouse();
        
        /** dong bo don vi **/
        require_once 'sync_cns/DM/unit.php';
        sync_unit();
        
        /** dong bo danh muc san pham **/ 
        require_once 'sync_cns/DM/product_category.php';
       sync_product_category();
        
        /** dong bo san pham **/
       require_once 'sync_cns/DM/product.php';
       sync_product();
        
        /** dong bo san pham dich vu **/ 
        require_once 'sync_cns/DM/case_item.php';
        sync_case_item();
        
        /** dong bo fee item code **/
        require_once 'sync_cns/DM/fee_item.php';
        sync_fee_item();
        
        
    /** DONG BO CHUNG TU **/
        
        /** dong bo pgt **/
        require_once 'sync_cns/CT/pgt.php';
        sync_payment_pgt();
        
        /** dong bo cnt **/
        require_once 'sync_cns/CT/cnt.php';
        sync_payment_cnt();
        
        /** dong bo ggck **/
        require_once 'sync_cns/CT/ggck.php';
        sync_payment_ggck();
        
        /** dong bo pctlt **/
        require_once 'sync_cns/CT/pctlt.php';
        sync_payment_pctlt();
        
        /** dong bo nvtf **/
        require_once 'sync_cns/K/nvtf.php';
        sync_wh_invoice_nvtf();
        
        /** dong bo xvt **/
        require_once 'sync_cns/K/xvt.php';
        sync_wh_invoice_xvt();
        
        /** dong bo nhf **/
        require_once 'sync_cns/K/nhf.php';
        sync_wh_invoice_nhf();
        
        /** dong bo xhh **/
        require_once 'sync_cns/K/xhh.php';
        sync_wh_invoice_xhh();
        
        /** dong bo dcvt ***/
        //require_once 'sync_cns/K/dcvt.php';
        //sync_wh_invoice_dcvt();
        
        /** dong bo xhhdc **/
        //require_once 'sync_cns/K/xhhdc.php';
        //sync_wh_invoice_xhhdc();
        
        /** dong bo hdv **/
        require_once 'sync_cns/CT/hdv.php';
        sync_invoice_hdv();
        
        /** dong bo hha **/
        require_once 'sync_cns/CT/hha.php';
        sync_invoice_hha();
        
        /** dong do phan giam gia **/
        //require_once 'sync_cns/gghd.php';
        //sync_cns_discount();
        
        /** dong do phan giam gia san pham **/
        //require_once 'sync_cns/ggsp.php';
        //sync_cns_discount_product();
        
        $hef = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].'/'.Url::$root.'?page=home';
        echo 'dong bo CNS thanh cong! <a href="'.$hef.'"> Go To HomePage </a>';
?>