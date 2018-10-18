<?php
    define('ROOT_PATH_EMAIL', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
    set_include_path(ROOT_PATH_EMAIL);
    require_once 'packages/core/includes/system/config_email.php';
	
    
    require_once "packages/hotel/packages/sale/modules/Email_marketing/email_birthday.php";
    send_mail_birthday();

    
    require_once "packages/hotel/packages/sale/modules/Email_marketing/email_birthday_another.php";
    send_mail_birthday_another();
    
    require_once "packages/hotel/packages/sale/modules/Email_marketing/email_date_incorporation.php";
    send_mail_date_incorporation();
    
    require_once "packages/hotel/packages/sale/modules/Email_marketing/email_event.php";
    send_mail_event();

?>


