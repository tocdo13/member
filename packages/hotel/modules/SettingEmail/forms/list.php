<?php
class SettingEmailForm extends Form
{
	static $portal_id = PORTAL_ID;
	function SettingEmailForm()
	{
		Form::Form('SettingEmailForm');
        
		$this->link_css(Portal::template('hotel').'/css/setting.css');
		$this->link_css(Portal::template('core').'/css/jquery/tabs.css');
		$this->link_js('packages/core/includes/js/jquery/jquery.easytabs.min.js');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
	}
	function on_submit()
	{
		require_once 'install_lib.php';
		$content = "<?php \n";
        $content.= "define('EMAIL_DATE_CREART','".trim($_REQUEST['email_date_creart'])."');\n";
		$content.= "define('EMAIL_INVOICE_CREART','".trim($_REQUEST['email_invoice_creart'])."');\n";
		$content.= "define('EMAIL_INVOICE','".trim($_REQUEST['email_invoice'])."');\n";
        $content.= "define('ROOM_INVOICE',".(isset($_REQUEST['room_invoice'])?1:0).");\n";
        $content.= "define('BAR_INVOICE',".(isset($_REQUEST['bar_invoice'])?1:0).");\n";
        $content.= "define('SPA_INVOICE',".(isset($_REQUEST['spa_invoice'])?1:0).");\n";
        $content.= "define('EMAIL_CHECK_OUT',".(isset($_REQUEST['email_check_out'])?1:0).");\n";
        $content.= "define('EMAIL_INVOICE_CONTENT','".addslashes(trim($_REQUEST['email_invoice_content']))."');\n"; 
        $content.= "define('EMAIL_INVOICE_PASSWORD','".addslashes(trim($_REQUEST['email_invoice_password']))."');\n";
        $content.= "define('EMAIL_MARKETING','".trim($_REQUEST['email_marketing'])."');\n";
        $content.= "define('EMAIL_MARKETING_PASSWORD','".addslashes(trim($_REQUEST['email_marketing_password']))."');\n";
        $content.= "define('EMAIL_INVOICE_SMTP','".trim($_REQUEST['email_invoice_smtp'])."');\n";
        $content.= "define('EMAIL_INVOICE_PORT','".trim($_REQUEST['email_invoice_port'])."');\n";
        $content.= "define('EMAIL_MARKETING_SMTP','".trim($_REQUEST['email_marketing_smtp'])."');\n";
        $content.= "define('EMAIL_MARKETING_PORT','".trim($_REQUEST['email_marketing_port'])."');\n";
        
		$content.=" ?>";
		//$portal_id = Url::get('portal_id')?Url::get('portal_id'):PORTAL_ID;
		save_file('config_email',$content,'default');
		Url::redirect_current(array('act'=>'succ'));		
	}
	function draw()
	{
		$portal_id =  SettingEmailForm::$portal_id;	
		if(file_exists('cache/portal/default/config/config_email.php'))
        {
			require_once('cache/portal/default/config/config_email.php');
            $_REQUEST['email_date_creart'] = EMAIL_DATE_CREART;
			$_REQUEST['email_invoice_creart'] = EMAIL_INVOICE_CREART;
            $_REQUEST['email_invoice'] = EMAIL_INVOICE;
            $_REQUEST['room_invoice'] = ROOM_INVOICE;
            $_REQUEST['bar_invoice'] = BAR_INVOICE;
            $_REQUEST['spa_invoice'] = SPA_INVOICE;
            $_REQUEST['email_check_out'] = EMAIL_CHECK_OUT;
            $_REQUEST['email_invoice_content'] = EMAIL_INVOICE_CONTENT;
            $_REQUEST['email_invoice_smtp'] = EMAIL_INVOICE_SMTP;
            $_REQUEST['email_invoice_port'] = EMAIL_INVOICE_PORT;
            $_REQUEST['email_marketing_smtp'] = EMAIL_MARKETING_SMTP;
            $_REQUEST['email_marketing_port'] = EMAIL_MARKETING_PORT;
            $_REQUEST['email_invoice_password'] = EMAIL_INVOICE_PASSWORD;
            $_REQUEST['email_marketing'] = EMAIL_MARKETING;
            $_REQUEST['email_marketing_password'] = EMAIL_MARKETING_PASSWORD;			
		}
        
        else
        {
            $_REQUEST['email_date_creart'] = date('d/m/Y');
            $_REQUEST['email_invoice_creart'] = '';
            $_REQUEST['email_invoice'] = '';
            $_REQUEST['email_invoice_content'] = '';
            $_REQUEST['email_marketing'] = '';
            $_REQUEST['room_invoice'] = 0;
            $_REQUEST['bar_invoice'] = 0;
            $_REQUEST['spa_invoice'] = 0;
            $_REQUEST['email_check_out'] = 0;
        }
        
		$this->map['portals'] = Portal::get_portal_list();
		$this->parse_layout('list',$this->map);
	}
}
?>