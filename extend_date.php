<?php
    /** phan hansd 
    1, them check han trong portal.php
    2, dung file nay gia han
    3, them tay file date.php trong config hco moi portal
    4, bo sung them tao file date.php trong manage_portal 
    ex : extend_date.php?portal_extend=default&date=15/09/2015&pass=0607131989
    **/
    
    date_default_timezone_set('Asia/Saigon');
    define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
    require_once ROOT_PATH.'packages/core/includes/system/config.php';
    if(Url::get("date") and Url::get("portal_extend") and Url::get("pass") and Url::get("pass")=='0607131989')
    {
		$content = "<?php \n";
        $content.= "define('EXPIRE_DATE','".trim(Url::get("date"))."');\n";
        $content.=" ?>";
		$handler = fopen('cache/portal/'.Url::get("portal_extend").'/config/date.php','w+');
    	fwrite($handler,$content);
    	fclose($handler);
        echo "Extend date success!!!!!!";
        exit();
    }
    System::debug(Portal::get_portal_list());
?>