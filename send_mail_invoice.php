<?php
    define('ROOT_PATH_EMAIL', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
    set_include_path(ROOT_PATH_EMAIL); 
    require_once ROOT_PATH_EMAIL.'packages/core/includes/system/config_email.php';
    
    /*Gửi Mail khi khách hàng checkout*/
    require_once ROOT_PATH_EMAIL."packages/hotel/includes/email/room/send_mail_invoice.php";
    sendMailCheckOut();
    
    /*Gửi Mail tới người quản lý khi thanh toán hóa đơn phòng*/
    require_once ROOT_PATH_EMAIL."packages/hotel/includes/email/room/send_mail_creart_folio.php";
    SendManageCreatFolio();
    
    /*Gửi Mail tới người quản lý khi thanh toán hóa đơn SPA*/
    require_once ROOT_PATH_EMAIL."packages/hotel/includes/email/spa/send_mail.php";
    SendMailInvoiceMassage();
    
    /*Gửi Mail tới người quản lý khi thanh toán hóa đơn BAR*/
    require_once ROOT_PATH_EMAIL."packages/hotel/includes/email/bar/send_mail.php";
    SendMailInvoiceBar();
 ?>


