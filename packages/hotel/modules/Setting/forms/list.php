<?php
class SettingForm extends Form
{
	static $portal_id = PORTAL_ID;
	function SettingForm()
	{
		Form::Form('SettingForm');
		$this->add('hotel_name',new TextType(true,'invalid_hotel_name',0,255));
		$this->link_css(Portal::template('hotel').'/css/setting.css');
		$this->link_css(Portal::template('core').'/css/jquery/tabs.css');
		$this->link_js('packages/core/includes/js/jquery/jquery.easytabs.min.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.mask.min.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function on_submit()
	{
		if($this->check())
		{
			require_once 'install_lib.php';
			$content = "<?php \n";
			if($_REQUEST['hotel_name'])
			{
				$content.= "define('HOTEL_NAME','".trim($_REQUEST['hotel_name'])."');\n";
                $content.= "define('HOTEL_PLACE','".trim($_REQUEST['hotel_place'])."');\n";
				$content.= "define('HOTEL_ADDRESS','".trim($_REQUEST['hotel_address'])."');\n";
				$content.= "define('HOTEL_PHONE','".trim($_REQUEST['hotel_phone'])."');\n";
				$content.= "define('HOTEL_FAX','".trim($_REQUEST['hotel_fax'])."');\n";
				$content.= "define('HOTEL_TAXCODE','".trim($_REQUEST['hotel_taxcode'])."');\n";
				$content.= "define('HOTEL_CURRENCY','".trim($_REQUEST['hotel_currency'])."');\n";
				if($_REQUEST['hotel_currency']=='VND'){
					$content.= "define('HOTEL_EXCHANGE_CURRENCY','USD');\n";	
				}else{
					$content.= "define('HOTEL_EXCHANGE_CURRENCY','VND');\n";
				}
                $content.= "define('HOTEL_EMAIL','".trim($_REQUEST['hotel_email'])."');\n";
				$content.= "define('HOTEL_WEBSITE','".trim($_REQUEST['hotel_website'])."');\n";
				$content.= "define('CHECK_IN_TIME','".trim($_REQUEST['check_in_time'])."');\n";
				$content.= "define('CHECK_OUT_TIME','".trim($_REQUEST['check_out_time'])."');\n";
				$content.= "define('MODULE_ALLOW_DOUBLE_CLICK',true);\n";
				$content.= "define('EXTRA_CHARGE_ON_SATURDAY',".($_REQUEST['extra_charge_on_saturday']?trim(str_replace(',','',$_REQUEST['extra_charge_on_saturday'])):0).");\n";
				$content.= "define('EXTRA_CHARGE_ON_SUNDAY',".($_REQUEST['extra_charge_on_sunday']?trim(str_replace(',','',$_REQUEST['extra_charge_on_sunday'])):0).");\n";
				$content.= "define('LOCK_DB_FILE','".trim($_REQUEST['lock_db_file'])."');\n";
				$content.= "define('BLOCK_SECOND',".Url::iget('block_second').");\n";
				$content.= "define('BANK_FEE_PERCEN',".Url::iget('bank_fee_percen').");\n";
				$content.= "define('ROUND_PRECISION',".Url::iget('round_precision').");\n";
				$content.= "define('USE_NIGHT_AUDIT',".(isset($_REQUEST['use_night_audit'])?1:0).");\n";				
				$content.= "define('NIGHT_AUDIT_TIME','".trim($_REQUEST['night_audit_time'])."');\n";				
				$content.= "define('NIGHT_AUDIT_CONFIRMATION','".trim($_REQUEST['night_audit_confirmation'])."');\n";
				$content.= "define('EXTRA_BED_QUANTITY',".Url::iget('extra_bed_quantity').");\n";
				$content.= "define('BABY_COT_QUANTITY',".Url::iget('baby_cot_quantity').");\n";
                $content.= "define('BREAKFAST_FROM_TIME','".trim($_REQUEST['breakfast_from_time'])."');\n";
                $content.= "define('BREAKFAST_TO_TIME','".trim($_REQUEST['breakfast_to_time'])."');\n";
				$content.= "define('BREAKFAST_PRICE',".Url::iget('breakfast_price').");\n";
                $content.= "define('BREAKFAST_CHILD_PRICE',".Url::iget('breakfast_child_price').");\n";
                $content.= "define('BREAKFAST_NET_PRICE',".(isset($_REQUEST['breakfast_net_price'])?1:0).");\n";
                $content.= "define('BREAKFAST_SPLIT_PRICE',".(isset($_REQUEST['breakfast_split_price'])?1:0).");\n";
				$content.= "define('PICKUP_PRICE',".Url::iget('pickup_price').");\n";
				$content.= "define('RECEPTION_SERVICE_CHARGE',".Url::iget('reception_service_charge').");\n";
				$content.= "define('RECEPTION_TAX_RATE',".Url::iget('reception_tax_rate').");\n";
                $content.= "define('TIME_BOOK_OVERDUE',".Url::iget('time_book_overdue').");\n";
				$content.= "define('CAN_EDIT_ROOM_PRICE',".(isset($_REQUEST['can_edit_room_price'])?1:0).");\n";
				$content.= "define('CAN_EDIT_CHARGE',".(isset($_REQUEST['can_edit_charge'])?1:0).");\n";
                $content.= "define('USING_PASSPORT',".(isset($_REQUEST['using_passport'])?1:0).");\n";
				$content.= "define('LATE_CHECKIN_AUTO',".(isset($_REQUEST['late_checkin_auto'])?1:0).");\n";
                $content.= "define('DISPLAY_BOOK_OVERDUE',".(isset($_REQUEST['display_book_overdue'])?1:0).");\n";
                $content.= "define('AUTO_CANCEL_BOOKING_EXPIRED',".(isset($_REQUEST['auto_cancel_booking_expired'])?1:0).");\n";
				$content.= "define('NET_PRICE',".(isset($_REQUEST['net_price'])?1:0).");\n";
                $content.= "define('OVER_BOOK',".(isset($_REQUEST['over_book'])?1:0).");\n";
				
                $content.= "define('NET_PRICE_SERVICE',".(isset($_REQUEST['net_price_service'])?1:0).");\n";
				$content.= "define('LAUNDRY_TAX_RATE',".Url::iget('laundry_tax_rate').");\n";		
				$content.= "define('LAUNDRY_SERVICE_CHARGE',".Url::iget('laundry_service_charge').");\n";				
				$content.= "define('MINIBAR_IMPORT_UNLIMIT',".(isset($_REQUEST['minibar_import_unlimit'])?1:0).");\n";
				$content.= "define('MINIBAR_SERVICE_CHARGE',".Url::iget('minibar_service_charge').");\n";				
				$content.= "define('MINIBAR_TAX_RATE',".Url::iget('minibar_tax_rate').");\n";
                $content.= "define('NET_PRICE_MINIBAR',".(isset($_REQUEST['net_price_minibar'])?1:0).");\n";		
				$content.= "define('LAUNDRY_EXPRESS_RATE',".Url::iget('laundry_express_rate').");\n";
                $content.= "define('NET_PRICE_LAUNDRY',".(isset($_REQUEST['net_price_laundry'])?1:0).");\n";
				$content.= "define('CHECK_BROWSER',".(isset($_REQUEST['check_browser'])?1:0).");\n";
                $content.= "define('RATE_CODE',".(isset($_REQUEST['rate_code'])?1:0).");\n";
                $content.= "define('USE_HLS',".(isset($_REQUEST['use_hls'])?1:0).");\n";
                $content.= "define('USE_ALLOTMENT',".(isset($_REQUEST['use_allotment'])?1:0).");\n";
                $content.= "define('ALLOW_OVER_EXPORT',".(isset($_REQUEST['allow_over_export'])?1:0).");\n";
				$content.= "define('RES_TAX_RATE',".Url::iget('res_tax_rate').");\n";	
                $content.= "define('KARAOKE_TAX_RATE',".Url::iget('karaoke_tax_rate').");\n";			
				$content.= "define('RES_SERVICE_CHARGE',".Url::iget('res_service_charge').");\n";
                $content.= "define('KARAOKE_SERVICE_CHARGE',".Url::iget('karaoke_service_charge').");\n";				
				$content.= "define('RES_EXCHANGE_RATE',".Url::iget('res_exchange_rate').");\n";
                $content.= "define('KARAOKE_EXCHANGE_RATE',".Url::iget('karaoke_exchange_rate').");\n";
				$content.= "define('RES_EDIT_PRODUCT_PRICE',".(isset($_REQUEST['res_edit_product_price'])?1:0).");\n";
                $content.= "define('KARAOKE_EDIT_PRODUCT_PRICE',".(isset($_REQUEST['karaoke_edit_product_price'])?1:0).");\n";
                $content.= "define('TELEPHONE_CONFIG',".(isset($_REQUEST['telephone_config'])?1:0).");\n";
				$content.= "define('TELEPHONE_TAX_RATE',".Url::iget('telephone_tax_rate').");\n";
				$content.= "define('TELEPHONE_SERVICE_CHARGE',".Url::iget('telephone_service_charge').");\n";			
				$content.= "define('EXTRA_SERVICE_TAX_RATE',".Url::iget('extra_service_tax_rate').");\n";
				$content.= "define('EXTRA_SERVICE_SERVICE_CHARGE',".Url::iget('extra_service_service_charge').");\n";	
				$content.= "define('VAT_OPTION',".(isset($_REQUEST['vat_option'])?1:0).");\n";
                $content.= "define('VENDING_TAX_RATE',".Url::iget('vending_tax_rate').");\n";
				$content.= "define('VENDING_SERVICE_CHARGE',".Url::iget('vending_service_charge').");\n";
                $content.= "define('VENDING_FULL_RATE',".(isset($_REQUEST['vending_full_rate'])?1:0).");\n";
                $content.= "define('VENDING_FULL_CHARGE',".(isset($_REQUEST['vending_full_charge'])?1:0).");\n";				
				$content.= "define('ALLOW_CREDIT_CARD_TYPE',".(isset($_REQUEST['allow_credit_card_type'])?1:0).");\n";
                $content.= "define('SPA_TAX_RATE',".Url::iget('spa_tax_rate').");\n";				
				$content.= "define('SPA_SERVICE_RATE',".Url::iget('spa_service_rate').");\n";
                $content.= "define('NET_PRICE_SPA',".(isset($_REQUEST['net_price_spa'])?1:0).");\n";
                $content.= "define('DISCOUNT_BEFORE_TAX',".(isset($_REQUEST['discount_before_tax'])?1:0).");\n";
                $content.= "define('USING_CHAT',".(isset($_REQUEST['using_chat'])?1:0).");\n";
				$finish = true;
				if(!@copy('skins/'.$_REQUEST['layout'].'/default.css','packages/core/skins/default/css/global.css'))
				{
					$finish = false;
					$error = 'Kh&#244;ng copy &#273;&#432;&#7907;c file css';
				}
				if(isset($_FILES['logo']) and $_FILES['logo']['name'])
				{
					$temp = preg_split('/[\/\\\\]+/', $_FILES['logo']['name']);
					$file_name = $temp[0];
					if (preg_match('/\.(gif|jpg|png)$/i',$file_name))
					{
						$ext = substr($file_name,strrpos($file_name,'.')+1);
						if(file_exists('resources/interfaces/images/'.str_replace('#','',PORTAL_ID).'/logo.'.$ext))
						{
							unlink('resources/interfaces/images/'.str_replace('#','',PORTAL_ID).'/logo.'.$ext);
						}
						move_uploaded_file($_FILES['logo']['tmp_name'],'resources/interfaces/images/'.str_replace('#','',PORTAL_ID).'/logo.'.$ext);
						$content .= 'define("HOTEL_LOGO","resources/interfaces/images/'.str_replace('#','',PORTAL_ID).'/logo.'.$ext.'");'."\n";
					}
				}
				else
                {
					$content .= 'define("HOTEL_LOGO","'.HOTEL_LOGO.'");';
				}
				if(isset($_FILES['banner']) and $_FILES['banner']['name'])
				{
					$temp = preg_split('/[\/\\\\]+/', $_FILES['banner']['name']);
					$file_name = $temp[0];
					if (preg_match('/\.(gif|jpg|png|jpeg)$/i',$file_name))
					{
						$ext = substr($file_name,strrpos($file_name,'.')+1);
						if(file_exists('resources/interfaces/images/'.str_replace('#','',PORTAL_ID).'/banner.'.$ext))
						{
							unlink('resources/interfaces/images/'.str_replace('#','',PORTAL_ID).'/banner.'.$ext);
						}
						move_uploaded_file($_FILES['banner']['tmp_name'],'resources/interfaces/images/'.str_replace('#','',PORTAL_ID).'/banner.'.$ext);
						$content.= 'define("HOTEL_BANNER","resources/interfaces/images/'.str_replace('#','',PORTAL_ID).'/banner.'.$ext.'");'."\n";
					}
				}
				else
				{
					$content.= 'define("HOTEL_BANNER","'.HOTEL_BANNER.'");';
				}
				if(isset($_FILES['background_url']) and $_FILES['background_url']['name'])
				{
					$temp = preg_split('/[\/\\\\]+/', $_FILES['background_url']['name']);
					$file_name = $temp[0];
					if (preg_match('/\.(gif|jpg|png)$/i',$file_name))
					{
						$ext = substr($file_name,strrpos($file_name,'.')+1);
						if(file_exists('resources/interfaces/images/'.str_replace('#','',PORTAL_ID).'/bg.'.$ext))
						{
							unlink('resources/interfaces/images/'.str_replace('#','',PORTAL_ID).'/bg.'.$ext);
						}
						move_uploaded_file($_FILES['background_url']['tmp_name'],'resources/interfaces/images/'.str_replace('#','',PORTAL_ID).'/bg.'.$ext);
						$content .= 'define("BACKGROUND_URL","resources/interfaces/images/'.str_replace('#','',PORTAL_ID).'/bg.'.$ext.'");'."\n";
					}
				}
				else
                {
					$content .= 'define("BACKGROUND_URL","'.BACKGROUND_URL.'");'."\n";
				}
				$content.=  'define("SKIN_PATH","'.$_REQUEST['layout'].'");'."\n";
				//if(Url::get('confirm')==1 and User::is_admin())
				{
					$content.= "define('HAVE_MINIBAR','".Url::get('have_minibar',0)."');\n";					
					$content.= "define('HAVE_RESTAURANT','".Url::get('have_restaurant',0)."');\n";
					$content.= "define('HAVE_KARAOKE','".Url::get('have_karaoke',0)."');\n";
					$content.= "define('HAVE_MASSAGE','".Url::get('have_massage',0)."');\n";
					$content.= "define('HAVE_TENNIS','".Url::get('have_tennis',0)."');\n";
					$content.= "define('HAVE_SWIMMING','".Url::get('have_swimming',0)."');\n";
					$content.= "define('HAVE_WAREHOUSE','".Url::get('have_warehouse',0)."');\n";
				}
				$content.= "define('PA18_HOTEL_CODE','".Url::get('PA18_HOTEL_CODE',0)."');\n";					
				$content.= "define('PA18_HOTEL_NAME','".Url::get('PA18_HOTEL_NAME',0)."');\n";					
				$content.= "define('PA18_HOTEL_ADDRESS','".Url::get('PA18_HOTEL_ADDRESS',0)."');\n";
				$content.= "define('PA18_HOTEL_USER','".Url::get('PA18_HOTEL_USER',0)."');\n";
				$content.= "define('PA18_DISTRICT_CODE','".Url::get('PA18_DISTRICT_CODE',0)."');\n";
				$content.= "define('PA18_DISTRICT_NAME','".Url::get('PA18_DISTRICT_NAME',0)."');\n";
				$content.= "define('PA18_PROVINCE_CODE','".Url::get('PA18_PROVINCE_CODE',0)."');\n";
				$content.= "define('PA18_PROVINCE_NAME','".Url::get('PA18_PROVINCE_NAME',0)."');\n";
                
                $content.= "define('CHANGE_PRICE_TO_POINT','".Url::get('CHANGE_PRICE_TO_POINT',0)."');\n";
                $content.= "define('CHANGE_POINT_TO_PRICE','".Url::get('CHANGE_POINT_TO_PRICE',0)."');\n";
                $content.= "define('SETTING_POINT',".(isset($_REQUEST['SETTING_POINT'])?1:0).");\n";
                //giap.ln add
                $content.= "define('IS_KEY','".Url::get('IS_KEY',0)."');\n";
                $content .="define('SERVER_KEY',".Url::get('SERVER_KEY').");\n";
                
                $content .="define('CHANGE_ROOM_BOOKED','".Url::get('CHANGE_ROOM_BOOKED')."');\n";
                $content .="define('CHANGE_ROOM_CHECKIN','".Url::get('CHANGE_ROOM_CHECKIN')."');\n";
                //end
               $content.= "define('AUTO_LI_START_TIME','".Url::get('auto_li_start_time',0)."');\n";
               $content.= "define('AUTO_LI_END_TIME','".Url::get('auto_li_end_time',0)."');\n";
                
                // Thanh add phần setting máy chủ nodejs
                $content.= "define('USE_DISPLAY',".(isset($_REQUEST['use_display'])?1:0).");\n";
                $content.= "define('IP_NODEJS_SERVER','".Url::get('ip_nodejs_server',0)."');\n";
                $content.= "define('PORT_NODEJS_SERVER','".Url::get('port_nodejs_server',0)."');\n";
                // end
                // manh sync snc
                $content.= "define('SYNC_CNS',".(isset($_REQUEST['SYNC_CNS'])?1:0).");\n";
                $content.= "define('DATE_SYNC_CNS','".Url::get('DATE_SYNC_CNS')."');\n";
                $content.= "define('LINK_SYNC_CNS','".Url::get('LINK_SYNC_CNS')."');\n";
                $content.= "define('BRANCH_CODE_SYNC_CNS','".Url::get('BRANCH_CODE_SYNC_CNS')."');\n";
                // end manh
		// Son Le Thanh booking confirm
		$content.= "define('CITY','".Url::get('city',0)."');\n";
                $content.= "define('CITY_NAME','".Url::get('city_name',0)."');\n";
		// End Son Le Thanh
                // siteminder
                $content.= "define('SITEMINDER',".(isset($_REQUEST['SITEMINDER'])?1:0).");\n";
                $content.= "define('SITEMINDER_URI','".Url::get('SITEMINDER_URI')."');\n";
                $content.= "define('SITEMINDER_REQUESTOR_ID','".Url::get('SITEMINDER_REQUESTOR_ID')."');\n";
                $content.= "define('SITEMINDER_USERNAME','".Url::get('SITEMINDER_USERNAME')."');\n";
                $content.= "define('SITEMINDER_PASSWORD','".Url::get('SITEMINDER_PASSWORD')."');\n";
                $content.= "define('SITEMINDER_HOTELCODE','".Url::get('SITEMINDER_HOTELCODE')."');\n";
                $content.= "define('SITEMINDER_HOTELCURRENCY','".Url::get('SITEMINDER_HOTELCURRENCY')."');\n";
                $content.= "define('SITEMINDER_ONE_WAY',".(isset($_REQUEST['SITEMINDER_ONE_WAY'])?1:0).");\n";
                $content.= "define('SITEMINDER_TWO_WAY',".(isset($_REQUEST['SITEMINDER_TWO_WAY'])?1:0).");\n";
                
                $content.=" ?>";
				$portal_id = Url::get('portal_id')?Url::get('portal_id'):PORTAL_ID;
				save_file('config',$content,$portal_id);
				Url::redirect_current(array('act'=>'succ','portal_id'=>$portal_id));
			}
		}
	}
	function draw()
	{
		$portal_id =  SettingForm::$portal_id;	
		if(file_exists('cache/portal/'.str_replace('#','',$portal_id).'/config/config.php'))
        {
			require_once('cache/portal/'.str_replace('#','',$portal_id).'/config/config.php');
            //echo 'cache/portal/'.str_replace('#','',$portal_id).'/config/config.php';
			$_REQUEST['hotel_name'] = HOTEL_NAME;
            $_REQUEST['hotel_place'] = HOTEL_PLACE;
			$_REQUEST['hotel_address'] = HOTEL_ADDRESS;
			$_REQUEST['hotel_phone'] = HOTEL_PHONE;
			$_REQUEST['hotel_fax'] = HOTEL_FAX;
			$_REQUEST['hotel_taxcode'] = HOTEL_TAXCODE;
			$_REQUEST['hotel_email'] = HOTEL_EMAIL;
            
			$_REQUEST['hotel_website'] = HOTEL_WEBSITE;
			$_REQUEST['hotel_currency'] = HOTEL_CURRENCY;
			$_REQUEST['check_in_time'] = CHECK_IN_TIME;
			$_REQUEST['check_out_time'] = CHECK_OUT_TIME;		
			$_REQUEST['lock_db_file'] = LOCK_DB_FILE;
			$_REQUEST['block_second'] = BLOCK_SECOND;		
			$_REQUEST['bank_fee_percen'] = BANK_FEE_PERCEN;
			$_REQUEST['extra_charge_on_saturday'] = System::display_number(EXTRA_CHARGE_ON_SATURDAY);
			$_REQUEST['extra_charge_on_sunday'] = System::display_number(EXTRA_CHARGE_ON_SUNDAY);
			$_REQUEST['use_night_audit'] = USE_NIGHT_AUDIT;
			$_REQUEST['night_audit_time'] = NIGHT_AUDIT_TIME;
			$_REQUEST['night_audit_confirmation'] = NIGHT_AUDIT_CONFIRMATION;
			$_REQUEST['round_precision'] = ROUND_PRECISION;
			$_REQUEST['extra_bed_quantity'] = EXTRA_BED_QUANTITY;
			$_REQUEST['baby_cot_quantity'] = BABY_COT_QUANTITY;	
            $_REQUEST['breakfast_from_time'] = BREAKFAST_FROM_TIME;
            $_REQUEST['breakfast_to_time'] = BREAKFAST_TO_TIME;	
			$_REQUEST['breakfast_price'] = BREAKFAST_PRICE;
            $_REQUEST['breakfast_child_price'] = BREAKFAST_CHILD_PRICE;
            $_REQUEST['breakfast_net_price'] = BREAKFAST_NET_PRICE;
            $_REQUEST['breakfast_split_price'] = BREAKFAST_SPLIT_PRICE;
			$_REQUEST['pickup_price'] = PICKUP_PRICE;
			$_REQUEST['allow_credit_card_type'] = ALLOW_CREDIT_CARD_TYPE;
			//if(User::is_admin())
			{
				$_REQUEST['have_minibar'] = HAVE_MINIBAR;
				$_REQUEST['have_restaurant'] = HAVE_RESTAURANT;			
				$_REQUEST['have_karaoke'] = HAVE_KARAOKE;
				$_REQUEST['have_massage'] = HAVE_MASSAGE;
				$_REQUEST['have_tennis'] = HAVE_TENNIS;
				$_REQUEST['have_swimming'] = HAVE_SWIMMING;
				$_REQUEST['have_warehouse'] = HAVE_WAREHOUSE;			
			}
			$_REQUEST['reception_service_charge'] = RECEPTION_SERVICE_CHARGE;
			$_REQUEST['reception_tax_rate'] = RECEPTION_TAX_RATE;
            $_REQUEST['time_book_overdue'] = TIME_BOOK_OVERDUE;
			$_REQUEST['can_edit_room_price'] = CAN_EDIT_ROOM_PRICE;
			$_REQUEST['can_edit_charge'] = CAN_EDIT_CHARGE;
            $_REQUEST['using_passport'] = USING_PASSPORT;
			$_REQUEST['late_checkin_auto'] = LATE_CHECKIN_AUTO;
            $_REQUEST['display_book_overdue'] = DISPLAY_BOOK_OVERDUE;
            $_REQUEST['auto_cancel_booking_expired'] = AUTO_CANCEL_BOOKING_EXPIRED;
			$_REQUEST['net_price'] = NET_PRICE;
            $_REQUEST['over_book'] = OVER_BOOK;
            $_REQUEST['using_chat'] = USING_CHAT;
			
            $_REQUEST['net_price_service'] = NET_PRICE_SERVICE;
			$_REQUEST['minibar_import_unlimit'] = MINIBAR_IMPORT_UNLIMIT;
			$_REQUEST['minibar_service_charge'] = MINIBAR_SERVICE_CHARGE;
			$_REQUEST['minibar_tax_rate'] = MINIBAR_TAX_RATE;
            $_REQUEST['net_price_minibar'] = NET_PRICE_MINIBAR;
			$_REQUEST['laundry_tax_rate'] = LAUNDRY_TAX_RATE;
			$_REQUEST['laundry_service_charge'] = LAUNDRY_SERVICE_CHARGE;
			$_REQUEST['laundry_express_rate'] = LAUNDRY_EXPRESS_RATE;
            $_REQUEST['net_price_laundry'] = NET_PRICE_LAUNDRY;
			$_REQUEST['check_browser'] = CHECK_BROWSER;
            $_REQUEST['rate_code'] = RATE_CODE;
            $_REQUEST['use_hls'] = USE_HLS;
            $_REQUEST['use_allotment'] = USE_ALLOTMENT;
            $_REQUEST['allow_over_export'] = ALLOW_OVER_EXPORT; 
			$_REQUEST['res_tax_rate'] = RES_TAX_RATE;
			$_REQUEST['res_service_charge'] = RES_SERVICE_CHARGE;			
			$_REQUEST['res_edit_product_price'] = RES_EDIT_PRODUCT_PRICE;
			$_REQUEST['res_exchange_rate'] = RES_EXCHANGE_RATE;
            $_REQUEST['karaoke_tax_rate'] = KARAOKE_TAX_RATE;
            $_REQUEST['karaoke_service_charge'] = KARAOKE_SERVICE_CHARGE;
            $_REQUEST['karaoke_edit_product_price'] = KARAOKE_EDIT_PRODUCT_PRICE;
            $_REQUEST['karaoke_exchange_rate'] = KARAOKE_EXCHANGE_RATE;
            $_REQUEST['telephone_config'] = TELEPHONE_CONFIG;
			$_REQUEST['telephone_tax_rate'] = TELEPHONE_TAX_RATE;
			$_REQUEST['telephone_service_charge'] = TELEPHONE_SERVICE_CHARGE;
			$_REQUEST['extra_service_tax_rate'] = EXTRA_SERVICE_TAX_RATE;
			$_REQUEST['extra_service_service_charge'] = EXTRA_SERVICE_SERVICE_CHARGE;
            $_REQUEST['vat_option'] = VAT_OPTION;	
			$_REQUEST['vending_tax_rate'] = VENDING_TAX_RATE;
			$_REQUEST['vending_service_charge'] = VENDING_SERVICE_CHARGE;
            $_REQUEST['vending_full_rate'] = VENDING_FULL_RATE;
            $_REQUEST['vending_full_charge'] = VENDING_FULL_CHARGE;		
			$_REQUEST['spa_tax_rate'] = SPA_TAX_RATE;
			$_REQUEST['spa_service_rate'] = SPA_SERVICE_RATE;
            $_REQUEST['net_price_spa'] = NET_PRICE_SPA;
            $_REQUEST['discount_before_tax'] = DISCOUNT_BEFORE_TAX;
            $_REQUEST['PA18_HOTEL_CODE'] = PA18_HOTEL_CODE;
			$_REQUEST['PA18_HOTEL_NAME'] = PA18_HOTEL_NAME;
			$_REQUEST['PA18_HOTEL_ADDRESS'] = PA18_HOTEL_ADDRESS;
			$_REQUEST['PA18_HOTEL_USER'] = PA18_HOTEL_USER;
			$_REQUEST['PA18_DISTRICT_CODE'] = PA18_DISTRICT_CODE;
			$_REQUEST['PA18_DISTRICT_NAME'] = PA18_DISTRICT_NAME;
			$_REQUEST['PA18_PROVINCE_CODE'] = PA18_PROVINCE_CODE;
			$_REQUEST['PA18_PROVINCE_NAME'] = PA18_PROVINCE_NAME;
            
            $_REQUEST['CHANGE_PRICE_TO_POINT'] = CHANGE_PRICE_TO_POINT;
            $_REQUEST['CHANGE_POINT_TO_PRICE'] = CHANGE_POINT_TO_PRICE;
            $_REQUEST['SETTING_POINT'] = SETTING_POINT;
            
            $_REQUEST['IS_KEY'] = IS_KEY;
            $_REQUEST['SERVER_KEY'] =SERVER_KEY;
            $_REQUEST['CHANGE_ROOM_BOOKED'] =CHANGE_ROOM_BOOKED;
            $_REQUEST['CHANGE_ROOM_CHECKIN'] =CHANGE_ROOM_CHECKIN;
            
            $_REQUEST['AUTO_LI_START_TIME'] = AUTO_LI_START_TIME;
            $_REQUEST['AUTO_LI_END_TIME'] = AUTO_LI_END_TIME;
            
            // Thanh add phần setting máy chủ nodejs
            $_REQUEST['use_display'] = USE_DISPLAY;
            $_REQUEST['ip_nodejs_server'] = IP_NODEJS_SERVER;
            $_REQUEST['port_nodejs_server'] = PORT_NODEJS_SERVER;
            // end   
            // sync cns
            $_REQUEST['SYNC_CNS'] = SYNC_CNS;
            $_REQUEST['DATE_SYNC_CNS'] = DATE_SYNC_CNS;
            $_REQUEST['LINK_SYNC_CNS'] = LINK_SYNC_CNS;
            $_REQUEST['BRANCH_CODE_SYNC_CNS'] = BRANCH_CODE_SYNC_CNS;
	    // Son Le Thanh booking confirm
            $_REQUEST['city'] = CITY;
            $_REQUEST['city_name'] = CITY_NAME;
	    // Son Le Thanh booking confirm
            // siteminder
            $_REQUEST['SITEMINDER'] = SITEMINDER;
            $_REQUEST['SITEMINDER_URI'] = SITEMINDER_URI;
            $_REQUEST['SITEMINDER_REQUESTOR_ID'] = SITEMINDER_REQUESTOR_ID;
            $_REQUEST['SITEMINDER_USERNAME'] = SITEMINDER_USERNAME;
            $_REQUEST['SITEMINDER_PASSWORD'] = SITEMINDER_PASSWORD;
            $_REQUEST['SITEMINDER_HOTELCODE'] = SITEMINDER_HOTELCODE;
            $_REQUEST['SITEMINDER_HOTELCURRENCY'] = SITEMINDER_HOTELCURRENCY;
            $_REQUEST['SITEMINDER_ONE_WAY'] = SITEMINDER_ONE_WAY;
            $_REQUEST['SITEMINDER_TWO_WAY'] = SITEMINDER_TWO_WAY;
		}
        else
        {
			$_REQUEST['hotel_name'] = DB::fetch('select name_1 from party where user_id = \''.Url::get('portal_id').'\'','name_1');
			$_REQUEST['hotel_currency'] = 'VND';
			$_REQUEST['check_in_time'] = '13:00';
			$_REQUEST['check_out_time'] = '12:00';		
			$_REQUEST['block_second'] = '6';		
			$_REQUEST['bank_fee_percen'] = '0';
			$_REQUEST['extra_charge_on_saturday'] = '0';
			$_REQUEST['extra_charge_on_sunday'] = '0';
			$_REQUEST['night_audit_time'] = '06:00';
			$_REQUEST['round_precision'] = '0';
			$_REQUEST['extra_bed_quantity'] = 0;
			$_REQUEST['baby_cot_quantity'] = 0;
            $_REQUEST['breakfast_from_time'] = '00:00';
            $_REQUEST['breakfast_to_time'] = '09:00';	
            $_REQUEST['breakfast_net_price'] = 0;
            $_REQUEST['breakfast_split_price'] = 0;					
			//if(User::is_admin())
			{
				$_REQUEST['have_minibar'] = '0';
				$_REQUEST['have_restaurant'] = '0';			
				$_REQUEST['have_karaoke'] = '0';
				$_REQUEST['have_massage'] = '0';
				$_REQUEST['have_tennis'] = '0';
				$_REQUEST['have_swimming'] = '0';
				$_REQUEST['have_warehouse'] = '0';			
			}
			$_REQUEST['reception_service_charge'] = 5;
			$_REQUEST['reception_tax_rate'] = 10;
            $_REQUEST['time_book_overdue'] = 10;
			$_REQUEST['can_edit_room_price'] = 0;
			$_REQUEST['can_edit_charge'] = 0;
            $_REQUEST['using_passport'] = 0;
			$_REQUEST['late_checkin_auto'] = 0;
            $_REQUEST['display_book_overdue'] = 0;
            $_REQUEST['auto_cancel_booking_expired'] = 0;
			$_REQUEST['net_price'] = 0;
            $_REQUEST['over_book'] = 0;
            $_REQUEST['using_chat'] = 0;
			
            $_REQUEST['net_price_service'] = 0;
			$_REQUEST['res_tax_rate'] = 0;
			$_REQUEST['res_service_charge'] = 0;			
			$_REQUEST['res_edit_product_price'] = 0;
			$_REQUEST['res_exchange_rate'] = DB::fetch('select exchange from currency where id = \'VND\'','exchange');
			$_REQUEST['karaoke_tax_rate'] = 0;
            $_REQUEST['karaoke_service_charge'] = 0;
            $_REQUEST['karaoke_edit_product_price'] = 0;
            $_REQUEST['karaoke_exchange_rate'] = DB::fetch('select exchange from currency where id = \'VND\'','exchange');
			
			$_REQUEST['allow_credit_card_type'] = '0';
			$_REQUEST['minibar_import_unlimit'] = '1';
			$_REQUEST['minibar_service_charge'] = 5;
			$_REQUEST['minibar_tax_rate'] = 10;
            $_REQUEST['net_price_minibar'] = 0;
			$_REQUEST['laundry_tax_rate'] = 10;
			$_REQUEST['laundry_service_charge'] = 0;
			$_REQUEST['laundry_express_rate'] = 0;
            $_REQUEST['net_price_laundry'] = 0;	
            $_REQUEST['telephone_config'] = 0;	
			$_REQUEST['telephone_tax_rate'] = 0;
			$_REQUEST['telephone_service_charge'] = 0;
			$_REQUEST['vending_tax_rate'] = 10;
			$_REQUEST['vending_service_charge'] = 5;
            $_REQUEST['vending_full_rate'] = 1;
            $_REQUEST['vending_full_charge'] = 1;
			$_REQUEST['extra_service_tax_rate'] = 0;
			$_REQUEST['extra_service_service_charge'] = 0;
            $_REQUEST['vat_option'] = 0;
			$_REQUEST['check_browser'] = 1;
            $_REQUEST['rate_code'] = 1;
            $_REQUEST['use_hls'] = 1;
            $_REQUEST['use_allotment'] = 1;
            $_REQUEST['allow_over_export'] = 1;
			$_REQUEST['spa_tax_rate'] = 0;
			$_REQUEST['spa_service_rate'] = 0;
            $_REQUEST['net_price_spa'] = 0;
            $_REQUEST['discount_before_tax'] = 0;
			$_REQUEST['PA18_HOTEL_CODE'] = '';
			$_REQUEST['PA18_HOTEL_NAME'] = '';
			$_REQUEST['PA18_HOTEL_ADDRESS'] = '';
			$_REQUEST['PA18_HOTEL_USER'] = '';
			$_REQUEST['PA18_DISTRICT_CODE'] = '';
			$_REQUEST['PA18_DISTRICT_NAME'] = '';
			$_REQUEST['PA18_PROVINCE_CODE'] = '';
			$_REQUEST['PA18_PROVINCE_NAME'] = '';
            
            $_REQUEST['CHANGE_PRICE_TO_POINT'] = 0;
            $_REQUEST['CHANGE_POINT_TO_PRICE'] = 0;
            $_REQUEST['SETTING_POINT'] = 1;
            //giap.ln add keys
            $_REQUEST['IS_KEY'] =0;
            $_REQUEST['SERVER_KEY'] =1;
            
            $_REQUEST['CHANGE_ROOM_BOOKED'] = 'KEEP';
            $_REQUEST['CHANGE_ROOM_CHECKIN'] = 'KEEP';
            
            $_REQUEST['auto_li_start_time'] = "";
            $_REQUEST['auto_li_end_time'] = "";
            //giap.ln end
            
            // Thanh add phần setting máy chủ nodejs
            $_REQUEST['use_display'] = USE_DISPLAY;
            $_REQUEST['ip_nodejs_server'] = IP_NODEJS_SERVER;
            $_REQUEST['port_nodejs_server'] = PORT_NODEJS_SERVER;
            // end
            $_REQUEST['SYNC_CNS'] =0;
            $_REQUEST['DATE_SYNC_CNS'] ='';
            $_REQUEST['LINK_SYNC_CNS'] ='';
            $_REQUEST['BRANCH_CODE_SYNC_CNS'] ='';
	    // Son Le Thanh booking confirm
            $_REQUEST['city'] = '';
            $_REQUEST['city_name'] = '';
	    // Son Le Thanh booking confirm
            // siteminder
            $_REQUEST['SITEMINDER'] = 0;
            $_REQUEST['SITEMINDER_URI'] = '';
            $_REQUEST['SITEMINDER_REQUESTOR_ID'] = '';
            $_REQUEST['SITEMINDER_USERNAME'] = '';
            $_REQUEST['SITEMINDER_PASSWORD'] = '';
            $_REQUEST['SITEMINDER_HOTELCODE'] = '';
            $_REQUEST['SITEMINDER_HOTELCURRENCY'] = '';
            $_REQUEST['SITEMINDER_ONE_WAY'] = 0;
            $_REQUEST['SITEMINDER_TWO_WAY'] = 0;
		}
        
		$this->map['portals'] = Portal::get_portal_list();
		$this->map['hotel_currency_list'] = String::get_list(DB::fetch_all('select id,concat(concat(id,\' - \'),name) as name from currency where id =\'VND\' or id = \'USD\''));
		$this->parse_layout('list',$this->map);
	}
}
?>
