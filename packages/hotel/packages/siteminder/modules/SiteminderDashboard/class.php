<?php 
class SiteminderDashboard extends Module
{
	function SiteminderDashboard($row)
	{
	    if(Url::get('status')=='LOADSECRETKEY')
        {
           $data = DB::fetch_all('SELECT * from siteminder_apikey order by lifetimes DESC');
           foreach($data as $key=>$value){
                $data[$key]['lifetimes'] = date('H:i d/m/Y',$value['lifetimes']);
           }
           echo json_encode($data);
           exit();
        }elseif(Url::get('status')=='LOADBOOKINGCHANNELTYPE'){
           $data = DB::fetch_all('SELECT * FROM siteminder_ota_bct order by id');
           echo json_encode($data);
           exit();
        }elseif(Url::get('status')=='LOADBOOKINGCHANNEL'){
           $data = DB::fetch_all('
                                    SELECT 
                                        siteminder_booking_channel.*,
                                        customer.code || \'_\' || siteminder_booking_channel.code as id,
                                        customer.id as customer_id,
                                        customer.code as customer_code,
                                        customer.name as customer_name 
                                    FROM 
                                        siteminder_booking_channel 
                                        left join siteminder_map_customer on (siteminder_booking_channel.code=siteminder_map_customer.booking_channel_code and siteminder_map_customer.portal_id=\''.PORTAL_ID.'\')
                                        left join customer on (customer.id=siteminder_map_customer.customer_id and customer.portal_id=\''.PORTAL_ID.'\')
                                    order by customer.code,siteminder_booking_channel.code');
           echo json_encode($data);
           exit();
        }elseif(Url::get('status')=='LOADSERVICEANDEXTRA'){
           $data = DB::fetch_all('
                                    SELECT 
                                        siteminder_extra_service.*,
                                        extra_service.code || \'_\' || siteminder_extra_service.code as id,
                                        extra_service.code as extra_service_code,
                                        extra_service.name as extra_service_name 
                                    FROM 
                                        siteminder_extra_service 
                                        left join extra_service on extra_service.code=siteminder_extra_service.extra_service_code
                                    order by extra_service.code,siteminder_extra_service.code');
           echo json_encode($data);
           exit();
        }elseif(Url::get('status')=='LOADOTAPAYMENTCARD'){
           $data = DB::fetch_all('
                                    SELECT 
                                        siteminder_ota_pcpc.*,
                                        credit_card.code || \'_\' || siteminder_ota_pcpc.code as id,
                                        credit_card.code as credit_card_code,
                                        credit_card.name as credit_card_name 
                                    FROM 
                                        siteminder_ota_pcpc 
                                        left join credit_card on credit_card.code=siteminder_ota_pcpc.credit_card_code
                                    order by credit_card.code,siteminder_ota_pcpc.code');
           echo json_encode($data);
           exit();
        }elseif(Url::get('status')=='GETOTAPAYMENTCARD' AND Url::get('code')){
           $data = DB::fetch('
                                SELECT 
                                    siteminder_ota_pcpc.*,
                                    credit_card.code || \'_\' || siteminder_ota_pcpc.code as id,
                                    credit_card.code as credit_card_code,
                                    credit_card.name as credit_card_name 
                                FROM 
                                    siteminder_ota_pcpc 
                                    left join credit_card on credit_card.code=siteminder_ota_pcpc.credit_card_code
                                WHERE
                                    siteminder_ota_pcpc.code=\''.Url::get('code').'\'');
           echo json_encode($data);
           exit();
        }elseif(Url::get('status')=='GETSERVICEANDEXTRA' AND Url::get('code')){
           $data = DB::fetch('
                                SELECT 
                                    siteminder_extra_service.*,
                                    extra_service.code || \'_\' || siteminder_extra_service.code as id,
                                    extra_service.code as extra_service_code,
                                    extra_service.name as extra_service_name 
                                FROM 
                                    siteminder_extra_service 
                                    left join extra_service on extra_service.code=siteminder_extra_service.extra_service_code
                                WHERE
                                    siteminder_extra_service.code=\''.Url::get('code').'\'');
           echo json_encode($data);
           exit();
        }elseif(Url::get('status')=='GETBOOKINGCHANNEL' AND Url::get('code')){
           $data = DB::fetch('
                                SELECT 
                                    siteminder_booking_channel.*,
                                    siteminder_booking_channel.code as id,
                                    customer.id as customer_id,
                                    customer.code as customer_code,
                                    customer.name as customer_name 
                                FROM 
                                    siteminder_booking_channel 
                                    left join siteminder_map_customer on (siteminder_booking_channel.code=siteminder_map_customer.booking_channel_code and siteminder_map_customer.portal_id=\''.PORTAL_ID.'\')
                                    left join customer on (customer.id=siteminder_map_customer.customer_id and customer.portal_id=\''.PORTAL_ID.'\')
                                WHERE
                                    siteminder_booking_channel.code=\''.Url::get('code').'\'');
           echo json_encode($data);
           exit();
        }elseif(Url::get('status')=='SAVEOTAPAYMENTCARD'){
           DB::update('siteminder_ota_pcpc',array('credit_card_code'=>Url::get('credit_card')),'code=\''.Url::get('code').'\'');
           echo 'success!';
           exit();
        }elseif(Url::get('status')=='SAVESERVICEANDEXTRA'){
           DB::update('siteminder_extra_service',array('extra_service_code'=>Url::get('extra_service')),'code=\''.Url::get('code').'\'');
           echo 'success!';
           exit();
        }elseif(Url::get('status')=='SAVEBOOKINGCHANNEL'){
           if(Url::get('action')=='ADD'){
                if(DB::exists('select code from siteminder_booking_channel where code=\''.Url::get('codechannel').'\''))
                    echo 'Conflict!';
                else{
                    DB::query('INSERT INTO SITEMINDER_BOOKING_CHANNEL(CODE,NAME) VALUES(\''.Url::get('codechannel').'\',\''.Url::get('namechannel').'\')');
                    if(Url::get('customer')!='')
                        DB::insert('siteminder_map_customer',array('booking_channel_code'=>Url::get('codechannel'),'customer_id'=>Url::get('customer'),'portal_id'=>PORTAL_ID));
                    
                    echo 'success!';
                }
           }else{
                if(DB::exists('select booking_channel_code from siteminder_map_customer where booking_channel_code=\''.Url::get('customer').'\' and portal_id=\''.PORTAL_ID.'\' ')){
                    DB::update('siteminder_map_customer',array('customer_id'=>Url::get('customer')),'booking_channel_code=\''.Url::get('customer').'\' and portal_id=\''.PORTAL_ID.'\'');
                }else{
                    DB::insert('siteminder_map_customer',array('booking_channel_code'=>Url::get('codechannel'),'customer_id'=>Url::get('customer'),'portal_id'=>PORTAL_ID));
                }
                echo 'success!';
           }
           exit();
        }
		Module::Module($row);
        if(User::can_edit(false,ANY_CATEGORY))
        {
            switch(URL::get('cmd'))
			{
                case 'map_customer':
    				require_once 'forms/map_customer.php';
                    $this->add_form(new MapCustomerSiteminderForm());
                    break;
                case 'map_extra_service':
    				require_once 'forms/map_extra_service.php';
                    $this->add_form(new MapExtraServiceSiteminderForm());
                    break;
                case 'map_payment_card':
    				require_once 'forms/map_payment_card.php';
                    $this->add_form(new MapPaymentCardSiteminderForm());
                    break;
                case 'ping_siteminder':
    				require_once 'forms/ping_siteminder.php';
                    $this->add_form(new PingSiteminderForm());
                    break;
    			default:
				{
					require_once 'forms/list.php';
					$this->add_form(new ListSiteminderDashboardForm());
				}
				break;
			}
        }else{
            Url::access_denied();
        }
	}
}
?>