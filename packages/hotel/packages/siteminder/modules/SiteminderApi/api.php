<?php
class api extends restful_api {

	function __construct(){
		parent::__construct();
	}
    
    function GetEchoToken()
	{
        /**
        * Get EchoToken
        * 
        * @return EchoToken with GUID : https://en.wikipedia.org/wiki/Universally_unique_identifier
        */
        if (function_exists('com_create_guid') === true)
        {
            return trim(com_create_guid(), '{}');
        }
        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
	}
    
    function GetTimeStamp()
	{
        /**
        * Get TimeStamp
        * 
        * @return TimeStamp with Timezone GMT+7
        */
        //return gmdate(DATE_ATOM,time());
        return date('Y-m-d').'T'.date('H:i:s').'+07:00';
	}
    
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
    
    function get_inventory(){
        if ($this->method == 'GET'){
            if(Url::get('secretkey') and DB::exists('select id from siteminder_apikey where secretkey=\''.Url::get('secretkey').'\'')){
                $portal_id = '#'.$_REQUEST['portal_id'];
                $inventory = DB::fetch_all('select 
                                                siteminder_soap_body.*
                                            from 
                                                siteminder_soap_body
                                            where 
                                                (siteminder_soap_body.status is null  OR siteminder_soap_body.status != 1 ) 
                                                AND siteminder_soap_body.portal_id=\''.$portal_id.'\'
                                            order by 
                                                siteminder_soap_body.time');
                foreach($inventory as $key=>$value){
                    unset($inventory[$key]['portal_id']);
                    if(is_file(ROOT_PATH.'packages/hotel/packages/siteminder/includes/dataXml/SOAP_'.$value['id'].'.xml')){
                        $inventory[$key]['soap'] = file_get_contents(ROOT_PATH.'packages/hotel/packages/siteminder/includes/dataXml/SOAP_'.$value['id'].'.xml');
                        DB::update('siteminder_soap_body',array('status'=>1),'id='.$value['id']);
                    }else{
                        unset($inventory[$key]);
                        DB::delete('siteminder_soap_body','id='.$value['id']);
                    }
                }
                $this->response(200, json_encode($inventory));
            }else{
                $this->response(500, "FAILED"); // AUTH
            }
        }else{
            $this->response(500, "FAILED"); // METHOD
        }
    }
    
    function room_level_check_conflict($arr)
    {
    	$days = array();
        if($arr[1] > time())
        {
            $arr[1] = $arr[1];
        }
        else
        {
            $arr[1] = Date_Time::to_time(date('d/m/Y'));
        }
    	for($i = $arr[1];$i < $arr[2];$i = $i + 24*3600)
        {
    		$days[$i]['id'] = $i;
    		$days[$i]['value'] = date('d/m',$i);
    	}	
    	$extra_cond = $arr[0]?' rl.id = \''.$arr[0].'\'':' 1>0';
    	$room_level = DB::fetch('
    		SELECT
    			rl.portal_id,
                rl.id,
                rl.name,
                rl.price,
                0 AS min_room_quantity,
                rl.color,
    			(SELECT COUNT(*) FROM room WHERE room_level_id = rl.id) room_quantity
    		FROM	
    			room_level rl
    		WHERE
    			'.$extra_cond.'
    			AND rl.portal_id = \''.PORTAL_ID.'\'
    		ORDER BY	
    			rl.name
    	');
    	$room_status = array();
    	if($room_level['id'])
    	{
    		$sql = '
    			SELECT 
    				r.portal_id,
                    rs.id,
                    rr.status,
                    rr.time_in,
                    rr.time_out,
                    rr.arrival_time,
                    rr.departure_time,
                    rs.in_date,
                    rr.room_level_id,
                    rr.id as rr_id ,
                    rs.house_status ,
                    room.room_level_id as room_level
    			FROM
    				room_status rs
                    LEFT OUTER JOIN room on room.id = rs.room_id
    				LEFT OUTER JOIN reservation_room rr ON rs.reservation_room_id = rr.id 
    				LEFT OUTER JOIN reservation r ON rr.reservation_id = r.id '.($arr[3]?' AND r.id<>'.$arr[3]:'').'
    			WHERE
    				(
                        (
                        rr.status <> \'CANCEL\'
                        AND rr.status <> \'NOSHOW\'
                        AND rr.status <> \'CHECKOUT\'
                        )
                        or rs.house_status = \'REPAIR\'
                    ) 
                    AND (rr.room_level_id='.$room_level['id'].'  or room.room_level_id='.$room_level['id'].'  )
                    AND rs.in_date>=\''.Date_Time::to_orc_date(date('d/m/Y',$arr[1])).'\' 
                    and rs.in_date<=\''.Date_Time::to_orc_date(date('d/m/Y',$arr[2])).'\'
    			ORDER BY
    				rr.room_level_id
    		';	
    	   $room_status = DB::fetch_all($sql);
        }
    	$min = 10000;
    	foreach($days as $k=>$v)
        {
    		$room_quantity = $room_level['room_quantity'];
    		foreach($room_status as $kk=>$vv)
            {
    			if($vv['room_level_id'] == $room_level['id'] and  Date_Time::convert_orc_date_to_date($vv['in_date'],'/') == date('d/m/Y',$k) and $vv['departure_time'] != $vv['in_date'])
                {
                    if(date('d/m/Y',$k)==date('d/m/Y',$arr[2]))
                    {
                        if($arr[2]>$vv['time_in'])
                            $room_quantity -= 1;
                    }
                    else
        				$room_quantity -= 1;
    			}
                elseif($vv['room_level']==$room_level['id'] and Date_Time::convert_orc_date_to_date($vv['in_date'],'/') == date('d/m/Y',$k) and $vv['house_status']=='REPAIR'){
                    $room_quantity -= 1;
                }
    		}
    		if($min > $room_quantity)
            {
    			$min = $room_quantity;
    		}
    	}
        /** check allotment **/
        if(USE_ALLOTMENT and $room_level['id']){
            //foreach($min as $keyMin=>$valueMin){
                $cond_allotment = '1=1 ';
                $cond_allotment .= ' and room_allotment_avail_rate.in_date>=\''.Date_Time::to_orc_date(date('d/m/Y',$arr[1])).'\' and room_allotment_avail_rate.in_date<=\''.Date_Time::to_orc_date(date('d/m/Y',$arr[2])).'\'';
                $cond_allotment .= ' and room_allotment.room_level_id = '.$room_level['id'];
                
                $avail_max = DB::fetch('
                                        SELECT
                                            max(room_allotment_avail_rate.availability) as avail
                                        FROM
                                            room_allotment_avail_rate
                                            inner join room_allotment on room_allotment.id=room_allotment_avail_rate.room_allotment_id
                                        WHERE
                                            '.$cond_allotment.'
                                        ','avail');
                $avail_max = $avail_max?$avail_max:0;
                $min -= $avail_max;
            //}
        }
        /** end check allotment **/
    	return $min;
    }
    
    function ReserationRes($reservation,$portal_id){
        require_once 'packages/hotel/packages/reception/modules/includes/reservation.php';
        /**
         * @return XML
        **/
        $reponse_xml = "";
        $booking = 1;
        // Mutil reservation
        foreach($reservation as $key=>$value){
            /** khoi tao bien 
             * @Create : $booking_reservation , $reponse_xml , $SOAPBody
            **/
            $booking_reservation = array();
            if($reponse_xml!=''){
                $reponse_xml .= "<OTANOTIFRQ>";
            }
            $EchoToken = api::GetEchoToken();
            $TimeStamp = api::GetTimeStamp();
            $reponse_xml .= "<OTA_NotifReportRQ xmlns=\"http://www.opentravel.org/OTA/2003/05\" EchoToken=\"".$EchoToken."\" TimeStamp=\"".$TimeStamp."\" Version=\"1\">";
            $SOAPBody = "";
            $SOAPBody .= "<NotifDetails>";
                $SOAPBody .= "<HotelNotifReport>";
                    $SOAPBody .= "<HotelReservations>";
                        if($value['ResStatus']=='Book'){
                            $SOAPBody .= "<HotelReservation CreateDateTime=\"".$TimeStamp."\" ResStatus=\"".$value['ResStatus']."\">";
                        }elseif($value['ResStatus']=='Modify' OR $value['ResStatus']=='Cancel'){
                            $SOAPBody .= "<HotelReservation LastModifyDateTime=\"".$TimeStamp."\" ResStatus=\"".$value['ResStatus']."\">";
                        }
                        $SOAPBody .= "<UniqueID Type=\"".$value['UniqueIDMessageIDType']."\" ID=\"".$value['UniqueIDMessageID']."\"/>";
            /** end khoi tao **/
            
            /** check isset customer **/
            if(isset($value['SourceCode']))
            {
                /** check customer map OTA channel **/
                if($customer_id = DB::fetch('select customer_id from siteminder_map_customer where booking_channel_code=\''.$value['SourceCode'].'\' and portal_id=\''.$portal_id.'\'','customer_id'))
                {
                    /** tao gia tri cho bang reservation **/
                    $customer = DB::fetch('select * from customer where id='.$customer_id);
                    $currency_id = (SITEMINDER_HOTELCURRENCY == 'VND')?'USD':'VND';
                    $exchange_rate = DB::fetch('select id,exchange from currency where id=\''.$currency_id.'\'','exchange');
                    $booking_reservation['customer_id'] = $customer_id;
                    $booking_reservation['time'] = time();
                        /** tai khoan siteminder la tai khoan mac dinh **/
                    if($value['ResStatus']=='Book'){
                        $booking_reservation['user_id'] = 'siteminder';
                    }else{
                        $booking_reservation['lastest_user_id'] = 'siteminder';
                    }
                        ///////////////////////////////////////////////////////
                        /** ghi chu va khoan thanh toan duoc tao de luu vao file trong folder dataRes **/
                    $booking_reservation['note'] = '';
                    if(isset($value['ResGlobalInfoGuaranteeCardHolderName']))
                        $booking_reservation['note'] .= '<p>Payment Cardholder Name : '.$value['ResGlobalInfoGuaranteeCardHolderName'].'</p>';
                    if(isset($value['ResGlobalInfoGuaranteeCardNumber']))
                        $booking_reservation['note'] .= '<p>Payment Card Number : '.$value['ResGlobalInfoGuaranteeCardNumber'].'</p>';
                    if(isset($value['ResGlobalInfoGuaranteeCardCode']))
                        $booking_reservation['note'] .= '<p>Payment Card Type : '.$value['ResGlobalInfoGuaranteeCardCode'].'</p>';
                    if(isset($value['ResGlobalInfoGuaranteeExpireDate']))
                        $booking_reservation['note'] .= '<p>Payment Expiry Date : '.$value['ResGlobalInfoGuaranteeExpireDate'].'</p>';
                    if(isset($value['ResGlobalInfoDepositPaymentsAmount']))
                        $booking_reservation['note'] .= '<p>Deposit Amount : '.$value['ResGlobalInfoDepositPaymentsAmount'].'</p>';
                    if(isset($value['ResGlobalInfoDepositPaymentsCurrencyCode']))
                        $booking_reservation['note'] .= '<p>Deposit Currency Code : '.$value['ResGlobalInfoDepositPaymentsCurrencyCode'].'</p>';
                    if(isset($value['ResGlobalInfoDepositPaymentsPercent']))
                        $booking_reservation['note'] .= '<p>Deposit Percent : '.$value['ResGlobalInfoDepositPaymentsPercent'].'</p>';
                    if(isset($value['ResGlobalInfoDepositPaymentsDescription']))
                        $booking_reservation['note'] .= '<p>Deposit Description : '.$value['ResGlobalInfoDepositPaymentsDescription'].'</p>';
                    if(isset($value['ResGlobalInfoFees'])){
                        foreach($value['ResGlobalInfoFees'] as $keyFees=>$valueFees){
                            if(isset($valueFees['Type']))
                                $booking_reservation['note'] .= '<p>Fees Type : '.$valueFees['Type'].'</p>';
                            if(isset($valueFees['Code']))
                                $booking_reservation['note'] .= '<p>Fees Code : '.$valueFees['Code'].'</p>';
                            if(isset($valueFees['Amount']))
                                $booking_reservation['note'] .= '<p>Fees Amount : '.$valueFees['Amount'].'</p>';
                            if(isset($valueFees['TaxesAmount']))
                                $booking_reservation['note'] .= '<p>Fees TaxesAmount : '.$valueFees['TaxesAmount'].'</p>';
                            if(isset($valueFees['DescriptionValue']))
                                $booking_reservation['note'] .= '<p>Fees Description : '.$valueFees['DescriptionValue'].'</p>';
                        }
                    }
                        /** note guest + service to reservation **/
                    if(isset($value['Services'])){
                        foreach($value['Services'] as $keySer=>$valueSer){
                            if(isset($valueSer['ServiceInventoryCode']))
                                $booking_reservation['note'] .= '<p>Extra Service Code: '.$valueSer['ServiceInventoryCode'].'</p>';
                            if(isset($valueSer['PriceTotalAmountAfterTax']))
                                $booking_reservation['note'] .= '<p>Extra Service Total Amount: '.$valueSer['PriceTotalAmountAfterTax'].'</p>';
                            if(isset($valueSer['PriceRateDescription']))
                                $booking_reservation['note'] .= '<p>Extra Service Description: '.$valueSer['PriceRateDescription'].'</p>';
                            if(isset($valueSer['ServiceDetailsTimeSpanStart']))
                                $booking_reservation['note'] .= '<p>Extra Service Start: '.$valueSer['ServiceDetailsTimeSpanStart'].'</p>';
                            if(isset($valueSer['ServiceDetailsTimeSpanEnd']))
                                $booking_reservation['note'] .= '<p>Extra Service End: '.$valueSer['ServiceDetailsTimeSpanEnd'].'</p>';
                            
                            $booking_reservation['note'] .= '<hr />';
                            unset($value['Services'][$keySer]);
                        }
                    }
                    if(isset($value['ResGuests'])){
                        foreach($value['ResGuests'] as $keyGue=>$valueGue){
                            if(isset($valueGue['ArrivalTime']))
                                $booking_reservation['note'] .= '<p>Guest ArrivalTime: '.$valueGue['ArrivalTime'].'</p>';
                            if(isset($valueGue['Age']))
                                $booking_reservation['note'] .= '<p>Guest Age: '.$valueGue['Age'].'</p>';
                            if(isset($valueGue['NamePrefix']))
                                $booking_reservation['note'] .= '<p>Guest NamePrefix: '.$valueGue['NamePrefix'].'</p>';
                            if(isset($valueGue['GivenName']))
                                $booking_reservation['note'] .= '<p>Guest GivenName: '.$valueGue['GivenName'].'</p>';
                            if(isset($valueGue['MiddleName']))
                                $booking_reservation['note'] .= '<p>Guest MiddleName: '.$valueGue['MiddleName'].'</p>';
                            if(isset($valueGue['Surname']))
                                $booking_reservation['note'] .= '<p>Guest Surname: '.$valueGue['Surname'].'</p>';
                            if(isset($valueGue['Telephone'])){
                                foreach($valueGue['Telephone'] as $keyTele=>$valueTele){
                                    $booking_reservation['note'] .= '<p>Guest PhoneNumber: '.$valueTele['PhoneNumber'].'</p>';
                                }
                            }
                            if(isset($valueGue['Email']))
                                $booking_reservation['note'] .= '<p>Guest Email: '.$valueGue['Email'].'</p>';
                            if(isset($valueGue['AddressLine'])){
                                foreach($valueGue['AddressLine'] as $keyAdd=>$valueAdd){
                                    $booking_reservation['note'] .= '<p>Guest AddressLine: '.$valueAdd['Name'].'</p>';
                                }
                            }
                            if(isset($valueGue['CityName']))
                                $booking_reservation['note'] .= '<p>Guest CityName: '.$valueGue['CityName'].'</p>';
                            if(isset($valueGue['PostalCode']))
                                $booking_reservation['note'] .= '<p>Guest PostalCode: '.$valueGue['PostalCode'].'</p>';
                            if(isset($valueGue['StateProv']))
                                $booking_reservation['note'] .= '<p>Guest StateProv: '.$valueGue['StateProv'].'</p>';
                            if(isset($valueGue['CountryName']))
                                $booking_reservation['note'] .= '<p>Guest CountryName: '.$valueGue['CountryName'].'</p>';
                            if(isset($valueGue['CompanyName']))
                                $booking_reservation['note'] .= '<p>Guest CompanyName: '.$valueGue['CompanyName'].'</p>';
                            
                            $booking_reservation['note'] .= '<hr />';
                            //unset($value['ResGuests'][$keyGue]);
                        }
                    }
                        /** end note **/
                        ///////////////////////////////////////////////////////
                        
                    $booking_reservation['portal_id'] = $portal_id;
                        /** tao reservation room **/
                    $booking_reservation['reservation_room'] = array();
                        // khai bao bien loi
                    $check_roomstay = 0;
                    $error_messenge = '';
                    foreach($value['RoomStays'] as $keyRoomStay=>$valueRoomStay)
                    {
                        /** check Room level map RoomType **/
                        if($room_level_id = DB::fetch('select room_level_id from siteminder_room_type where inventory_type_code=\''.$valueRoomStay['RoomStayRoomTypeCode'].'\' and portal_id=\''.$portal_id.'\'','room_level_id'))
                        {
                            $booking_reservation['reservation_room'][$keyRoomStay] = array();
                            $start_date = explode('-',$valueRoomStay['RoomStayStartTimeSpan']);
                            $end_date = explode('-',$valueRoomStay['RoomStayEndTimeSpan']);
                            /** check room lelvel overr book **/
                            if(!OVER_BOOK and $value['ResStatus']!='Cancel')
                            {
                                if($value['ResStatus']=='Book'){
                                    $items = api::room_level_check_conflict(array($room_level_id,api::calc_time(CHECK_IN_TIME)+Date_Time::to_time($start_date[2].'/'.$start_date[1].'/'.$start_date[0]),api::calc_time(CHECK_OUT_TIME)+Date_Time::to_time($end_date[2].'/'.$end_date[1].'/'.$end_date[0]),0));
                                    if(!isset($arr_level[$room_level_id]))
                                    {
                                        $arr_level[$room_level_id] = $items - 1; 
                    				}
                                    else
                                    {
                                       $arr_level[$room_level_id] -= 1;
                    				}
                    				if(!$items || $arr_level[$room_level_id] < 0 )
                                    {
                                        $check_roomstay = 1;
                                        $error_messenge = 'Room Level has only from '.$valueRoomStay['RoomStayStartTimeSpan'].' to '.$valueRoomStay['RoomStayEndTimeSpan'];
                                        break;
                    				}
                                }elseif($value['ResStatus']=='Modify'){
                                    if($reservation_id = DB::fetch('select reservation.id from reservation inner join siteminder_reservation on reservation.id=siteminder_reservation.reservation_id where siteminder_reservation.UniqueID=\''.$value['UniqueID'].'\'','id')){
                                        $items = api::room_level_check_conflict(array($room_level_id,api::calc_time(CHECK_IN_TIME)+Date_Time::to_time($start_date[2].'/'.$start_date[1].'/'.$start_date[0]),api::calc_time(CHECK_OUT_TIME)+Date_Time::to_time($end_date[2].'/'.$end_date[1].'/'.$end_date[0]),$reservation_id));
                                        if(!isset($arr_level[$room_level_id]))
                                        {
                                            $arr_level[$room_level_id] = $items - 1; 
                        				}
                                        else
                                        {
                                           $arr_level[$room_level_id] -= 1;
                        				}
                        				if(!$items || $arr_level[$room_level_id] < 0 )
                                        {
                                            $check_roomstay = 1;
                                            $error_messenge = 'Room Level has only from '.$valueRoomStay['RoomStayStartTimeSpan'].' to '.$valueRoomStay['RoomStayEndTimeSpan'];
                                            break;
                        				}
                                    }else{
                                        $check_roomstay = 1;
                                        $error_messenge = "Reservation does not exist";
                                        break;
                                    }
                                }
                            }
                            /** END room lelvel overr book **/
                                    
                            /** check time in **/
                            $arrival_time = api::calc_time(CHECK_IN_TIME)+Date_Time::to_time($start_date[2].'/'.$start_date[1].'/'.$start_date[0]);
                            if($arrival_time<time()){
                                $check_roomstay = 1;
                                $error_messenge = 'Time is less than the current day';
                                break;
                            }
                            /** end check time in **/
                            if($check_roomstay==1){
                                break;
                            }
                            $booking_reservation['reservation_room'][$keyRoomStay]['time_in'] = api::calc_time(CHECK_IN_TIME)+Date_Time::to_time($start_date[2].'/'.$start_date[1].'/'.$start_date[0]);
                            $booking_reservation['reservation_room'][$keyRoomStay]['time_out'] = api::calc_time(CHECK_OUT_TIME)+Date_Time::to_time($end_date[2].'/'.$end_date[1].'/'.$end_date[0]);
                            $booking_reservation['reservation_room'][$keyRoomStay]['arrival_time'] = Date_Time::to_orc_date($start_date[2].'/'.$start_date[1].'/'.$start_date[0]);
                            $booking_reservation['reservation_room'][$keyRoomStay]['departure_time'] = Date_Time::to_orc_date($end_date[2].'/'.$end_date[1].'/'.$end_date[0]);
                            $booking_reservation['reservation_room'][$keyRoomStay]['total_amount'] = 0;
                            $booking_reservation['reservation_room'][$keyRoomStay]['status'] = 'BOOKED';
                            $booking_reservation['reservation_room'][$keyRoomStay]['tax_rate'] = RECEPTION_TAX_RATE;
                            $booking_reservation['reservation_room'][$keyRoomStay]['service_rate'] = RECEPTION_SERVICE_CHARGE;
                            $booking_reservation['reservation_room'][$keyRoomStay]['customer_name'] = $customer['name'];
                            $booking_reservation['reservation_room'][$keyRoomStay]['time'] = time();
                            $booking_reservation['reservation_room'][$keyRoomStay]['reservation_type_id'] = 5;
                            $booking_reservation['reservation_room'][$keyRoomStay]['exchange_rate'] = $exchange_rate;
                            $booking_reservation['reservation_room'][$keyRoomStay]['room_level_id'] = $room_level_id;
                            $booking_reservation['reservation_room'][$keyRoomStay]['breakfast'] = 1;
                            $booking_reservation['reservation_room'][$keyRoomStay]['net_price'] = 1;
                            if(isset($valueRoomStay['RoomStayGuestCounts'])){
                                foreach($valueRoomStay['RoomStayGuestCounts'] as $keyGuest=>$valueGuest){
                                    if($valueGuest['AgeQualifyingCode']==10)
                                        $booking_reservation['reservation_room'][$keyRoomStay]['adult'] = $valueGuest['Count'];
                                    if($valueGuest['AgeQualifyingCode']==8)
                                        $booking_reservation['reservation_room'][$keyRoomStay]['child'] = $valueGuest['Count'];
                                    if($valueGuest['AgeQualifyingCode']==7)
                                        $booking_reservation['reservation_room'][$keyRoomStay]['child_5'] = $valueGuest['Count'];
                                }
                            }
                            $usd_price = 0;
                            $booking_reservation['reservation_room'][$keyRoomStay]['price'] = 0;
                            $booking_reservation['reservation_room'][$keyRoomStay]['usd_price'] = 0;
                            /** Room Status **/
                            $booking_reservation['reservation_room'][$keyRoomStay]['room_status'] = array();
                            $keyRoomStatus = 0;
                            if(isset($valueRoomStay['RoomStayRoomRate']))
                            {
                                foreach($valueRoomStay['RoomStayRoomRate'] as $keyRoomRate=>$valueRoomRate)
                                {
                                    foreach($valueRoomRate['Rates'] as $keyRates=>$valueRates)
                                    {
                                        $EffectiveDate = explode('-',$valueRates['EffectiveDate']);
                                        $ExpireDate = explode('-',$valueRates['ExpireDate']);
                                        $start_time = Date_Time::to_time($EffectiveDate[2].'/'.$EffectiveDate[1].'/'.$EffectiveDate[0]);
                                        $end_time = Date_Time::to_time($ExpireDate[2].'/'.$ExpireDate[1].'/'.$ExpireDate[0]);
                                        /**
                                         * Check Inventory
                                        **/
                                         /** check Availability RoomRate **/
                                        $managed = false;
                                        if(isset($valueRoomRate['RatePlanCode'])){
                                            $managed = DB::fetch('
                                                                    select
                                                                        siteminder_room_rate.id
                                                                    from
                                                                        siteminder_room_rate
                                                                        inner join siteminder_room_type on siteminder_room_rate.siteminder_room_type_id=siteminder_room_type.id
                                                                    where
                                                                        siteminder_room_rate.availability=\'MANAGED\'
                                                                        and siteminder_room_rate.rate_plan_code=\''.$valueRoomRate['RatePlanCode'].'\'
                                                                        and siteminder_room_type.room_level_id='.$room_level_id.'
                                                                    ','id');
                                        }
                                        $RatePlanCode = '';
                                        /** End check Availability RoomRate **/
                                        if($value['ResStatus']=='Book'){
                                            /** xet RoomType **/
                                            $inventory_check_avail = DB::fetch_all('SELECT
                                                                                        siteminder_room_type_time.availability,
                                                                                        to_char(siteminder_room_type_time.in_date,\'DD/MM/YYYY\') as id
                                                                                    FROM
                                                                                        siteminder_room_type_time
                                                                                        inner join siteminder_room_type on siteminder_room_type_time.siteminder_room_type_id=siteminder_room_type.id
                                                                                    where
                                                                                        siteminder_room_type.room_level_id='.$room_level_id.'
                                                                                        and siteminder_room_type_time.in_date>=\''.Date_Time::to_orc_date($EffectiveDate[2].'/'.$EffectiveDate[1].'/'.$EffectiveDate[0]).'\'
                                                                                        and siteminder_room_type_time.in_date<\''.Date_Time::to_orc_date($ExpireDate[2].'/'.$ExpireDate[1].'/'.$ExpireDate[0]).'\'
                                                                                        and siteminder_room_type.portal_id=\''.$portal_id.'\'
                                                                                    ');
                                            for($inven=Date_Time::to_time($EffectiveDate[2].'/'.$EffectiveDate[1].'/'.$EffectiveDate[0]);$inven<Date_Time::to_time($ExpireDate[2].'/'.$ExpireDate[1].'/'.$ExpireDate[0]);$inven+=86400){
                                                $date_inventory = date('d/m/Y',$inven);
                                                if(!isset($inventory_check_avail[$date_inventory]) or (isset($inventory_check_avail[$date_inventory]) and $inventory_check_avail[$date_inventory]<=0)){
                                                    $check_roomstay = 1;
                                                    $error_messenge = 'Room Level has only inventory from '.$valueRoomStay['RoomStayStartTimeSpan'].' to '.$valueRoomStay['RoomStayEndTimeSpan'];
                                                    break;
                                                }
                                            }
                                            if($managed){
                                                $inventory_rate_check_avail = DB::fetch_all('SELECT
                                                                                                    siteminder_rate_avail_time.availability,
                                                                                                    to_char(siteminder_rate_avail_time.in_date,\'DD/MM/YYYY\') as id
                                                                                                FROM
                                                                                                    siteminder_rate_avail_time
                                                                                                    inner join siteminder_room_rate on siteminder_room_rate.id=siteminder_rate_avail_time.siteminder_room_rate_id
                                                                                                    inner join siteminder_room_type on siteminder_room_rate.siteminder_room_type_id=siteminder_room_type.id
                                                                                                where
                                                                                                    siteminder_room_type.room_level_id='.$room_level_id.'
                                                                                                    and siteminder_room_rate.rate_plan_code=\''.$valueRoomRate['RatePlanCode'].'\'
                                                                                                    and siteminder_rate_avail_time.in_date>=\''.Date_Time::to_orc_date($EffectiveDate[2].'/'.$EffectiveDate[1].'/'.$EffectiveDate[0]).'\'
                                                                                                    and siteminder_rate_avail_time.in_date<\''.Date_Time::to_orc_date($ExpireDate[2].'/'.$ExpireDate[1].'/'.$ExpireDate[0]).'\'
                                                                                                    and siteminder_room_type.portal_id=\''.$portal_id.'\'
                                                                                                ');
                                                $check_rate_avail = 0;
                                                for($inven=Date_Time::to_time($EffectiveDate[2].'/'.$EffectiveDate[1].'/'.$EffectiveDate[0]);$inven<Date_Time::to_time($ExpireDate[2].'/'.$ExpireDate[1].'/'.$ExpireDate[0]);$inven+=86400){
                                                    $date_inventory = date('d/m/Y',$inven);
                                                    if(!isset($inventory_rate_check_avail[$date_inventory]) or (isset($inventory_rate_check_avail[$date_inventory]) and $inventory_rate_check_avail[$date_inventory]<=0)){
                                                        $check_rate_avail = 1;
                                                        $error_messenge = 'Room Level has only inventory from '.$valueRoomStay['RoomStayStartTimeSpan'].' to '.$valueRoomStay['RoomStayEndTimeSpan'];
                                                        break;
                                                    }
                                                }
                                                if($check_rate_avail==0){
                                                    $check_roomstay = 0;
                                                    $RatePlanCode = $valueRoomRate['RatePlanCode'];
                                                }
                                            }
                                            
                                        }elseif($value['ResStatus']=='Modify'){
                                            if($reservation_id = DB::fetch('select reservation.id from reservation inner join siteminder_reservation on reservation.id=siteminder_reservation.reservation_id where siteminder_reservation.UniqueID=\''.$value['UniqueID'].'\'','id')){
                                                $inventory_check_avail = DB::fetch_all('SELECT
                                                                                            siteminder_room_type_time.availability,
                                                                                            to_char(siteminder_room_type_time.in_date,\'DD/MM/YYYY\') as id
                                                                                        FROM
                                                                                            siteminder_room_type_time
                                                                                            inner join siteminder_room_type on siteminder_room_type_time.siteminder_room_type_id=siteminder_room_type.id
                                                                                        where
                                                                                            siteminder_room_type.room_level_id='.$room_level_id.'
                                                                                            and siteminder_room_type_time.in_date>=\''.Date_Time::to_orc_date($EffectiveDate[2].'/'.$EffectiveDate[1].'/'.$EffectiveDate[0]).'\'
                                                                                            and siteminder_room_type_time.in_date<\''.Date_Time::to_orc_date($ExpireDate[2].'/'.$ExpireDate[1].'/'.$ExpireDate[0]).'\'
                                                                                            and siteminder_room_type.portal_id=\''.$portal_id.'\'
                                                                                        ');
                                                $reservation_old = DB::fetch_all('select
                                                                                      count(room_status.in_date) as count,
                                                                                      room_status.in_date as id
                                                                                    from
                                                                                      room_status
                                                                                      inner join reservation_room on room_status.reservation_room_id = reservation_room.id
                                                                                      inner join reservation on reservation.id = reservation_room.reservation_id
                                                                                    where
                                                                                      reservation.id='.$reservation_id.' and
                                                                                      reservation_room.room_level_id='.$room_level_id.' and
                                                                                      (
                                                                                        (room_status.in_date!=reservation_room.departure_time and reservation_room.departure_time!=reservation_room.arrival_time)
                                                                                        or
                                                                                        (room_status.in_date=reservation_room.departure_time and reservation_room.departure_time=reservation_room.arrival_time and reservation_room.change_room_to_rr is null)
                                                                                      )
                                                                                    group by room_status.in_date');
                                                
                                                for($inven=Date_Time::to_time($EffectiveDate[2].'/'.$EffectiveDate[1].'/'.$EffectiveDate[0]);$inven<Date_Time::to_time($ExpireDate[2].'/'.$ExpireDate[1].'/'.$ExpireDate[0]);$inven+=86400){
                                                    $date_inventory = date('d/m/Y',$inven);
                                                    if(!isset($inventory_check_avail[$date_inventory])){
                                                        $check_roomstay = 1;
                                                        $error_messenge = 'Room Level has only inventory from '.$valueRoomStay['RoomStayStartTimeSpan'].' to '.$valueRoomStay['RoomStayEndTimeSpan'];
                                                        break;
                                                    }else{
                                                        if(isset($inventory_check_avail[$date_inventory]) and $inventory_check_avail[$date_inventory]<=0 and !isset($reservation_old[$date_inventory])){
                                                            $check_roomstay = 1;
                                                            $error_messenge = 'Room Level has only inventory from '.$valueRoomStay['RoomStayStartTimeSpan'].' to '.$valueRoomStay['RoomStayEndTimeSpan'];
                                                            break;
                                                        }
                                                    }
                                                }
                                                if($managed){
                                                    $inventory_rate_check_avail = DB::fetch_all('SELECT
                                                                                                        siteminder_rate_avail_time.availability,
                                                                                                        to_char(siteminder_rate_avail_time.in_date,\'DD/MM/YYYY\') as id
                                                                                                    FROM
                                                                                                        siteminder_rate_avail_time
                                                                                                        inner join siteminder_room_rate on siteminder_room_rate.id=siteminder_rate_avail_time.siteminder_room_rate_id
                                                                                                        inner join siteminder_room_type on siteminder_room_rate.siteminder_room_type_id=siteminder_room_type.id
                                                                                                    where
                                                                                                        siteminder_room_type.room_level_id='.$room_level_id.'
                                                                                                        and siteminder_room_rate.rate_plan_code=\''.$valueRoomRate['RatePlanCode'].'\'
                                                                                                        and siteminder_rate_avail_time.in_date>=\''.Date_Time::to_orc_date($EffectiveDate[2].'/'.$EffectiveDate[1].'/'.$EffectiveDate[0]).'\'
                                                                                                        and siteminder_rate_avail_time.in_date<\''.Date_Time::to_orc_date($ExpireDate[2].'/'.$ExpireDate[1].'/'.$ExpireDate[0]).'\'
                                                                                                        and siteminder_room_type.portal_id=\''.$portal_id.'\'
                                                                                                    ');
                                                    $check_rate_avail = 0;
                                                    for($inven=Date_Time::to_time($EffectiveDate[2].'/'.$EffectiveDate[1].'/'.$EffectiveDate[0]);$inven<Date_Time::to_time($ExpireDate[2].'/'.$ExpireDate[1].'/'.$ExpireDate[0]);$inven+=86400){
                                                        $date_inventory = date('d/m/Y',$inven);
                                                        if(!isset($inventory_rate_check_avail[$date_inventory]) or (isset($inventory_rate_check_avail[$date_inventory]) and $inventory_rate_check_avail[$date_inventory]<=0)){
                                                            $check_rate_avail = 1;
                                                            $error_messenge = 'Room Level has only inventory from '.$valueRoomStay['RoomStayStartTimeSpan'].' to '.$valueRoomStay['RoomStayEndTimeSpan'];
                                                            break;
                                                        }
                                                        if(!isset($inventory_rate_check_avail[$date_inventory])){
                                                            $check_rate_avail = 1;
                                                            $error_messenge = 'Room Level has only inventory from '.$valueRoomStay['RoomStayStartTimeSpan'].' to '.$valueRoomStay['RoomStayEndTimeSpan'];
                                                            break;
                                                        }else{
                                                            if(isset($inventory_rate_check_avail[$date_inventory]) and $inventory_rate_check_avail[$date_inventory]<=0 and !isset($reservation_old[$date_inventory])){
                                                                $check_rate_avail = 1;
                                                                $error_messenge = 'Room Level has only inventory from '.$valueRoomStay['RoomStayStartTimeSpan'].' to '.$valueRoomStay['RoomStayEndTimeSpan'];
                                                                break;
                                                            }
                                                        }
                                                    }
                                                    if($check_rate_avail==0){
                                                        $check_roomstay = 0;
                                                        $RatePlanCode = $valueRoomRate['RatePlanCode'];
                                                    }
                                                } 
                                            }
                                        }
                                        /** End check Inventory **/
                                        for($i=$start_time;$i<=$end_time;$i+=86400){
                                            if($i<$end_time){
                                                $change_price = 0;
                                                if(isset($valueRates['BaseAmountBeforeTax'])){
                                                    $change_price = $valueRates['BaseAmountBeforeTax'] + (($valueRates['BaseAmountBeforeTax']*RECEPTION_TAX_RATE)/100);
                                                }else{
                                                    if(isset($valueRates['TotalAmountBeforeTax']) and isset($valueRates['UnitMultiplier'])){
                                                        $change_price = round($valueRates['TotalAmountBeforeTax'] / $valueRates['UnitMultiplier'],2);
                                                        $change_price += ($change_price*RECEPTION_TAX_RATE)/100;
                                                    }
                                                }
                                                if(isset($valueRates['BaseAmountAfterTax'])){
                                                    $change_price = $valueRates['BaseAmountAfterTax'];
                                                }else{
                                                    if(isset($valueRates['TotalAmountAfterTax']) and isset($valueRates['UnitMultiplier'])){
                                                        $change_price = round($valueRates['TotalAmountAfterTax'] / $valueRates['UnitMultiplier'],2);
                                                    }
                                                }
                                                $booking_reservation['reservation_room'][$keyRoomStay]['room_status'][$keyRoomStatus]['status'] = 'BOOKED';
                                                $booking_reservation['reservation_room'][$keyRoomStay]['room_status'][$keyRoomStatus]['in_date'] = Date_Time::to_orc_date(date('d/m/Y',$i));
                                                $booking_reservation['reservation_room'][$keyRoomStay]['room_status'][$keyRoomStatus]['in_time'] = date('d/m/Y',$i);
                                                $booking_reservation['reservation_room'][$keyRoomStay]['room_status'][$keyRoomStatus]['change_price'] = (SITEMINDER_HOTELCURRENCY=='VND')?$change_price:($change_price * $exchange_rate);
                                                $booking_reservation['reservation_room'][$keyRoomStay]['room_status'][$keyRoomStatus]['room_level_id'] = $room_level_id;
                                                $booking_reservation['reservation_room'][$keyRoomStay]['room_status'][$keyRoomStatus]['rateplancode'] = $RatePlanCode;
                                                $booking_reservation['reservation_room'][$keyRoomStay]['room_status'][$keyRoomStatus]['inventorytypecode'] = $valueRoomStay['RoomStayRoomTypeCode'];
                                                if($change_price!=0){
                                                    $booking_reservation['reservation_room'][$keyRoomStay]['price'] = (SITEMINDER_HOTELCURRENCY=='VND')?$change_price:($change_price * $exchange_rate);
                                                    $booking_reservation['reservation_room'][$keyRoomStay]['usd_price'] = (SITEMINDER_HOTELCURRENCY=='USD')?$change_price:(round($change_price/$exchange_rate,2));
                                                    $booking_reservation['reservation_room'][$keyRoomStay]['total_amount'] += (SITEMINDER_HOTELCURRENCY=='VND')?$change_price:($change_price * $exchange_rate);
                                                }
                                            }else{
                                                if($end_time==Date_Time::to_time($end_date[2].'/'.$end_date[1].'/'.$end_date[0])){
                                                    $booking_reservation['reservation_room'][$keyRoomStay]['room_status'][$keyRoomStatus]['status'] = 'BOOKED';
                                                    $booking_reservation['reservation_room'][$keyRoomStay]['room_status'][$keyRoomStatus]['in_date'] = Date_Time::to_orc_date(date('d/m/Y',$i));
                                                    $booking_reservation['reservation_room'][$keyRoomStay]['room_status'][$keyRoomStatus]['in_time'] = date('d/m/Y',$i);
                                                    $booking_reservation['reservation_room'][$keyRoomStay]['room_status'][$keyRoomStatus]['change_price'] = 0;
                                                    $booking_reservation['reservation_room'][$keyRoomStay]['room_status'][$keyRoomStatus]['room_level_id'] = $room_level_id;
                                                    $booking_reservation['reservation_room'][$keyRoomStay]['room_status'][$keyRoomStatus]['rateplancode'] = $RatePlanCode;
                                                    $booking_reservation['reservation_room'][$keyRoomStay]['room_status'][$keyRoomStatus]['inventorytypecode'] = $valueRoomStay['RoomStayRoomTypeCode'];
                                                    if($start_date==$end_date){
                                                        $change_price = 0;
                                                        if(isset($valueRates['BaseAmountBeforeTax'])){
                                                            $change_price = $valueRates['BaseAmountBeforeTax'] + (($valueRates['BaseAmountBeforeTax']*RECEPTION_TAX_RATE)/100);
                                                        }else{
                                                            if(isset($valueRates['TotalAmountBeforeTax']) and isset($valueRates['UnitMultiplier'])){
                                                                $change_price = round($valueRates['TotalAmountBeforeTax'] / $valueRates['UnitMultiplier'],2);
                                                                $change_price += ($change_price*RECEPTION_TAX_RATE)/100;
                                                            }
                                                        }
                                                        if(isset($valueRates['BaseAmountAfterTax'])){
                                                            $change_price = $valueRates['BaseAmountAfterTax'];
                                                        }else{
                                                            if(isset($valueRates['TotalAmountAfterTax']) and isset($valueRates['UnitMultiplier'])){
                                                                $change_price = round($valueRates['TotalAmountAfterTax'] / $valueRates['UnitMultiplier'],2);
                                                            }
                                                        }
                                                        $booking_reservation['reservation_room'][$keyRoomStay]['room_status'][$keyRoomStatus]['change_price'] = (SITEMINDER_HOTELCURRENCY=='VND')?$change_price:($change_price * $exchange_rate);
                                                        if($change_price!=0){
                                                            $booking_reservation['reservation_room'][$keyRoomStay]['price'] = (SITEMINDER_HOTELCURRENCY=='VND')?$change_price:($change_price * $exchange_rate);
                                                            $booking_reservation['reservation_room'][$keyRoomStay]['usd_price'] = (SITEMINDER_HOTELCURRENCY=='USD')?$change_price:(round($change_price/$exchange_rate,2));
                                                            $booking_reservation['reservation_room'][$keyRoomStay]['total_amount'] += (SITEMINDER_HOTELCURRENCY=='VND')?$change_price:($change_price * $exchange_rate);
                                                        }
                                                    }
                                                }
                                            }
                                            $keyRoomStatus++;
                                        }
                                    }
                                }
                            }
                            /** End room status **/
                            
                            $booking_reservation['reservation_room'][$keyRoomStay]['note'] = '';
                            /** note Service **/
                            if(isset($valueRoomStay['RoomStayServiceRPHs'])){
                                foreach($valueRoomStay['RoomStayServiceRPHs'] as $keyService=>$valueService){
                                    $PRHService = $valueService['RPH'];
                                    foreach($value['Services'] as $keySer=>$valueSer){
                                        if($valueSer['ServiceRPH']==$PRHService){
                                            if(isset($valueSer['ServiceInventoryCode']))
                                                $booking_reservation['reservation_room'][$keyRoomStay]['note'] .= '<p>Extra Service Code: '.$valueSer['ServiceInventoryCode'].'</p>';
                                            if(isset($valueSer['PriceTotalAmountAfterTax']))
                                                $booking_reservation['reservation_room'][$keyRoomStay]['note'] .= '<p>Extra Service Total Amount: '.$valueSer['PriceTotalAmountAfterTax'].'</p>';
                                            if(isset($valueSer['PriceRateDescription']))
                                                $booking_reservation['reservation_room'][$keyRoomStay]['note'] .= '<p>Extra Service Description: '.$valueSer['PriceRateDescription'].'</p>';
                                            if(isset($valueSer['ServiceDetailsTimeSpanStart']))
                                                $booking_reservation['reservation_room'][$keyRoomStay]['note'] .= '<p>Extra Service Start: '.$valueSer['ServiceDetailsTimeSpanStart'].'</p>';
                                            if(isset($valueSer['ServiceDetailsTimeSpanEnd']))
                                                $booking_reservation['reservation_room'][$keyRoomStay]['note'] .= '<p>Extra Service End: '.$valueSer['ServiceDetailsTimeSpanEnd'].'</p>';
                                            
                                            $booking_reservation['reservation_room'][$keyRoomStay]['note'] .= '<hr />';
                                            unset($value['Services'][$keySer]);
                                        }
                                    }
                                }
                            }
                            /** End note service **/
                            
                            /** note Guest **/
                            if(isset($valueRoomStay['RoomStayResGuestRPHs'])){
                                foreach($valueRoomStay['RoomStayResGuestRPHs'] as $keyGuest=>$valueGuest){
                                    $PRHGuest = $valueGuest['RPH'];
                                    foreach($value['ResGuests'] as $keyGue=>$valueGue){
                                        if($valueGue['ResGuestRPH']==$PRHGuest){
                                            if(isset($valueGue['ArrivalTime']))
                                                $booking_reservation['reservation_room'][$keyRoomStay]['note'] .= '<p>Guest ArrivalTime: '.$valueGue['ArrivalTime'].'</p>';
                                            if(isset($valueGue['Age']))
                                                $booking_reservation['reservation_room'][$keyRoomStay]['note'] .= '<p>Guest Age: '.$valueGue['Age'].'</p>';
                                            if(isset($valueGue['NamePrefix']))
                                                $booking_reservation['reservation_room'][$keyRoomStay]['note'] .= '<p>Guest NamePrefix: '.$valueGue['NamePrefix'].'</p>';
                                            if(isset($valueGue['GivenName']))
                                                $booking_reservation['reservation_room'][$keyRoomStay]['note'] .= '<p>Guest GivenName: '.$valueGue['GivenName'].'</p>';
                                            if(isset($valueGue['MiddleName']))
                                                $booking_reservation['reservation_room'][$keyRoomStay]['note'] .= '<p>Guest MiddleName: '.$valueGue['MiddleName'].'</p>';
                                            if(isset($valueGue['Surname']))
                                                $booking_reservation['reservation_room'][$keyRoomStay]['note'] .= '<p>Guest Surname: '.$valueGue['Surname'].'</p>';
                                            if(isset($valueGue['Telephone'])){
                                                foreach($valueGue['Telephone'] as $keyTele=>$valueTele){
                                                    $booking_reservation['reservation_room'][$keyRoomStay]['note'] .= '<p>Guest PhoneNumber: '.$valueTele['PhoneNumber'].'</p>';
                                                }
                                            }
                                            if(isset($valueGue['Email']))
                                                $booking_reservation['reservation_room'][$keyRoomStay]['note'] .= '<p>Guest Email: '.$valueGue['Email'].'</p>';
                                            if(isset($valueGue['AddressLine'])){
                                                foreach($valueGue['AddressLine'] as $keyAdd=>$valueAdd){
                                                    $booking_reservation['reservation_room'][$keyRoomStay]['note'] .= '<p>Guest AddressLine: '.$valueAdd['Name'].'</p>';
                                                }
                                            }
                                            if(isset($valueGue['CityName']))
                                                $booking_reservation['reservation_room'][$keyRoomStay]['note'] .= '<p>Guest CityName: '.$valueGue['CityName'].'</p>';
                                            if(isset($valueGue['PostalCode']))
                                                $booking_reservation['reservation_room'][$keyRoomStay]['note'] .= '<p>Guest PostalCode: '.$valueGue['PostalCode'].'</p>';
                                            if(isset($valueGue['StateProv']))
                                                $booking_reservation['reservation_room'][$keyRoomStay]['note'] .= '<p>Guest StateProv: '.$valueGue['StateProv'].'</p>';
                                            if(isset($valueGue['CountryName']))
                                                $booking_reservation['reservation_room'][$keyRoomStay]['note'] .= '<p>Guest CountryName: '.$valueGue['CountryName'].'</p>';
                                            if(isset($valueGue['CompanyName']))
                                                $booking_reservation['reservation_room'][$keyRoomStay]['note'] .= '<p>Guest CompanyName: '.$valueGue['CompanyName'].'</p>';
                                            
                                            $booking_reservation['reservation_room'][$keyRoomStay]['note'] .= '<hr />';
                                            unset($value['ResGuests'][$keyGue]);
                                        }
                                    }
                                }
                            }
                            /** end note Guest **/
                        }else{ // End check room level map roomtype
                            $check_roomstay = 1;
                            $error_messenge = 'Room does not exist';
                            break;
                        }
                    }
                    /** End:$value['RoomStays'] : check reservation_room **/
                    ///////////////////
                    
                    
                    if($check_roomstay==1){
                                            $reponse_xml .= "<Errors>";
                                            $reponse_xml .= "<Error Type=\"3\" Code=\"448\">".$error_messenge."</Error>";
                                        $reponse_xml .= "</Errors>";
                                        $reponse_xml .= $SOAPBody;
                                      $reponse_xml .= "</HotelReservation>";
                                    $reponse_xml .= "</HotelReservations>";
                                $reponse_xml .= "</HotelNotifReport>";
                            $reponse_xml .= "</NotifDetails>";
                            $reponse_xml .= "</OTA_NotifReportRQ>";
                        continue;
                    }else{
                        // create booking for database
                        /** $booking_reservation **/
                        $reservation_room = $booking_reservation['reservation_room'];
                        unset($booking_reservation['reservation_room']);
                        /**
                        $booking_reservation['UniqueID'] = $value['UniqueID'];
                        $booking_reservation['UniqueIDType'] = $value['UniqueIDType'];
                        $booking_reservation['UniqueIDMessageID'] = $value['UniqueIDMessageID'];
                        $booking_reservation['UniqueIDMessageIDType'] = $value['UniqueIDMessageIDType'];
                        **/
                        $check_res = 0;
                        $mess_res = '';
                        $setAvail = array();
                        $setAvailRate = array();
                        if($value['ResStatus']=='Book'){
                            $path = ROOT_PATH.'packages/hotel/packages/siteminder/includes/dataRes/paynote_'.$value['UniqueID'].'.php';
                            api::save_file($path,$booking_reservation['note']);
                            unset($booking_reservation['note']);
                            $reservation_id = DB::insert('reservation',$booking_reservation);
                            DB::insert('siteminder_reservation',array(
                                                                    'UniqueID'=>$value['UniqueID']
                                                                    ,'UniqueIDType'=>$value['UniqueIDType']
                                                                    ,'UniqueIDMessageID'=>$value['UniqueIDMessageID']
                                                                    ,'UniqueIDMessageIDType'=>$value['UniqueIDMessageIDType']
                                                                    ,'reservation_id'=>$reservation_id
                                                                    ,'book_time'=>time()
                                                                    ));
                            foreach($reservation_room as $keyResRoom=>$valueResRoom){
                                $room_status = $valueResRoom['room_status'];
                                unset($valueResRoom['room_status']);
                                $valueResRoom['reservation_id'] = $reservation_id;
                                $reservation_room_note = $valueResRoom['note'];
                                unset($valueResRoom['note']);
                                $reservation_room_id = DB::insert('reservation_room',$valueResRoom);
                                $path = ROOT_PATH.'packages/hotel/packages/siteminder/includes/dataRes/paynote_'.$value['UniqueID'].'_RRID_'.$reservation_room_id.'.php';
                                api::save_file($path,$reservation_room_note);
                                
                                foreach($room_status as $keyRmSts=>$valueRmSts){
                                    $in_time = $valueRmSts['in_time'];
                                    unset($valueRmSts['in_time']);
                                    $valueRmSts['reservation_id'] = $reservation_id;
                                    $valueRmSts['reservation_room_id'] = $reservation_room_id;
                                    if($valueRmSts['in_date']!=$valueResRoom['departure_time']){
                                        if($valueRmSts['rateplancode']!=''){
                                            if(!isset($setAvailRate[$valueRmSts['inventorytypecode']]['rate'][$valueRmSts['rateplancode']]['in_date'][$in_time])){
                                                $setAvailRate[$valueRmSts['inventorytypecode']]['rate'][$valueRmSts['rateplancode']]['in_date'][$in_time]['count'] = 1;
                                            }else{
                                                $setAvailRate[$valueRmSts['inventorytypecode']]['rate'][$valueRmSts['rateplancode']]['in_date'][$in_time]['count'] ++;
                                            }
                                        }else{
                                            if(!isset($setAvail[$valueRmSts['room_level_id']]['in_date'][$in_time])){
                                                $setAvail[$valueRmSts['room_level_id']]['in_date'][$in_time]['count'] = 1;
                                            }else{
                                                $setAvail[$valueRmSts['room_level_id']]['in_date'][$in_time]['count'] ++;
                                            }
                                        }
                                    }
                                    DB::insert('room_status',$valueRmSts);
                                }
                            }
                        }elseif($value['ResStatus']=='Modify'){
                            if($reservation_id = DB::fetch('select reservation.id from reservation inner join siteminder_reservation on reservation.id=siteminder_reservation.reservation_id where siteminder_reservation.UniqueID=\''.$value['UniqueID'].'\'','id')){
                                
                                if(DB::exists('select id from reservation_room where (status=\'CHECKIN\' or status=\'CHECKOUT\' or status=\'CANCEL\') and reservation_id='.$reservation_id)){
                                    $check_res = 1;
                                    $mess_res = "Reservation is CHECKIN or CHECKOUT or CANCEL into NEWWAY";
                                }else{
                                    
                                    $reservation_room_old = DB::fetch_all('select * from reservation_room where reservation_id='.$reservation_id);
                                    foreach($reservation_room_old as $keyRROld=>$valueRROld){
                                        if($valueRROld['status']=='CHECKIN' or $valueRROld['status']=='CHECKOUT'){
                                            $check_res = 1;
                                            $mess_res = "Reservation is CHECKIN or CHECKOUT into NEWWAY";
                                            break;
                                        }
                                    }
                                    if($check_res!=1){
                                        $path = ROOT_PATH.'packages/hotel/packages/siteminder/includes/dataRes/paynote_'.$value['UniqueID'].'.php';
                                        api::save_file($path,$booking_reservation['note']);
                                        unset($booking_reservation['note']);
                                        DB::update('siteminder_reservation',array('modify_time'=>time()),'UniqueID=\''.$value['UniqueID'].'\'');
                                        DB::update('reservation',$booking_reservation,'id='.$reservation_id);
                                        foreach($reservation_room_old as $keyRROldDelete=>$valueRROldDelete){
                                            $room_status_old = DB::fetch_all('select in_date as id,to_char(in_date,\'DD/MM/YYYY\') as in_date,rateplancode,inventorytypecode,room_level_id from room_status where reservation_room_id='.$valueRROldDelete['id']);
                                            foreach($room_status_old as $keyRSOld=>$valueRSOld){
                                                $in_time = $valueRSOld['in_date'];
                                                if(($valueRROldDelete['arrival_time']==$valueRROldDelete['departure_time']) or ($valueRROldDelete['arrival_time']!=$valueRROldDelete['departure_time'] and $valueRROldDelete['departure_time']!=$valueRSOld['id'])){
                                                    if($valueRSOld['rateplancode']!=''){
                                                        if(!isset($setAvailRate[$valueRSOld['inventorytypecode']]['rate'][$valueRSOld['rateplancode']]['in_date'][$in_time])){
                                                            $setAvailRate[$valueRSOld['inventorytypecode']]['rate'][$valueRSOld['rateplancode']]['in_date'][$in_time]['count'] = -1;
                                                        }else{
                                                            $setAvailRate[$valueRSOld['inventorytypecode']]['rate'][$valueRSOld['rateplancode']]['in_date'][$in_time]['count'] --;
                                                        }
                                                    }else{
                                                        if(!isset($setAvail[$valueRSOld['room_level_id']]['in_date'][$in_time])){
                                                            $setAvail[$valueRSOld['room_level_id']]['in_date'][$in_time]['count'] = -1;
                                                        }else{
                                                            $setAvail[$valueRSOld['room_level_id']]['in_date'][$in_time]['count'] --;
                                                        }
                                                    }
                                                }
                                            }
                                            DB::delete('room_status','reservation_room_id='.$valueRROldDelete['id']);
                                        }
                                        DB::delete('reservation_room','reservation_id='.$reservation_id);
                                        foreach($reservation_room as $keyResRoom=>$valueResRoom){
                                            $room_status = $valueResRoom['room_status'];
                                            unset($valueResRoom['room_status']);
                                            $reservation_room_note = $valueResRoom['note'];
                                            unset($valueResRoom['note']);
                                            $valueResRoom['reservation_id'] = $reservation_id;
                                            $reservation_room_id = DB::insert('reservation_room',$valueResRoom);
                                            $path = ROOT_PATH.'packages/hotel/packages/siteminder/includes/dataRes/paynote_'.$value['UniqueID'].'_RRID_'.$reservation_room_id.'.php';
                                            api::save_file($path,$reservation_room_note);
                                            
                                            foreach($room_status as $keyRmSts=>$valueRmSts){
                                                $in_time = $valueRmSts['in_time'];
                                                unset($valueRmSts['in_time']);
                                                $valueRmSts['reservation_id'] = $reservation_id;
                                                $valueRmSts['reservation_room_id'] = $reservation_room_id;
                                                if($valueRmSts['in_date']!=$valueResRoom['departure_time']){
                                                    
                                                    if($valueRmSts['rateplancode']!=''){
                                                        if(!isset($setAvailRate[$valueRmSts['inventorytypecode']]['rate'][$valueRmSts['rateplancode']]['in_date'][$in_time])){
                                                            $setAvailRate[$valueRmSts['inventorytypecode']]['rate'][$valueRmSts['rateplancode']]['in_date'][$in_time]['count'] = 1;
                                                        }else{
                                                            $setAvailRate[$valueRmSts['inventorytypecode']]['rate'][$valueRmSts['rateplancode']]['in_date'][$in_time]['count'] ++;
                                                        }
                                                    }else{
                                                        if(!isset($setAvail[$valueRmSts['room_level_id']]['in_date'][$in_time])){
                                                            $setAvail[$valueRmSts['room_level_id']]['in_date'][$in_time]['count'] = 1;
                                                        }else{
                                                            $setAvail[$valueRmSts['room_level_id']]['in_date'][$in_time]['count'] ++;
                                                        }
                                                    }
                                                }
                                                DB::insert('room_status',$valueRmSts);
                                            }
                                        }
                                    }
                                }
                            }else{
                                $check_res = 1;
                                $mess_res = "Reservation does not exist";
                            }
                        }elseif($value['ResStatus']=='Cancel'){
                            if($reservation_id = DB::fetch('select reservation.id from reservation inner join siteminder_reservation on reservation.id=siteminder_reservation.reservation_id where siteminder_reservation.UniqueID=\''.$value['UniqueID'].'\'','id')){
                                if(DB::exists('select id from reservation_room where (status=\'CHECKIN\' or status=\'CHECKOUT\') and reservation_id='.$reservation_id)){
                                    $check_res = 1;
                                    $mess_res = "Reservation is CHECKIN or CHECKOUT into NEWWAY";
                                }else{
                                    $reservation_room_old = DB::fetch_all('select * from reservation_room where reservation_id='.$reservation_id);
                                    foreach($reservation_room_old as $keyRROld=>$valueRROld){
                                        if($valueRROld['status']=='CHECKIN' or $valueRROld['status']=='CHECKOUT'){
                                            $check_res = 1;
                                            $mess_res = "Reservation is CHECKIN or CHECKOUT into NEWWAY";
                                            break;
                                        }
                                    }
                                    if($check_res!=1){
                                        unset($booking_reservation['note']);
                                        DB::update('siteminder_reservation',array('cancel_time'=>time()),'UniqueID=\''.$value['UniqueID'].'\'');
                                        DB::update('reservation',$booking_reservation,'id='.$reservation_id);
                                        foreach($reservation_room_old as $keyRROldDelete=>$valueRROldDelete){
                                            $room_status_old = DB::fetch_all('select in_date as id,to_char(in_date,\'DD/MM/YYYY\') as in_date,rateplancode,inventorytypecode,room_level_id from room_status where reservation_room_id='.$valueRROldDelete['id']);
                                            foreach($room_status_old as $keyRSOld=>$valueRSOld){
                                                $in_time = $valueRSOld['in_date'];
                                                if(($valueRROldDelete['arrival_time']==$valueRROldDelete['departure_time']) or ($valueRROldDelete['arrival_time']!=$valueRROldDelete['departure_time'] and $valueRROldDelete['departure_time']!=$valueRSOld['id'])){
                                                    if($valueRSOld['rateplancode']!=''){
                                                        if(!isset($setAvailRate[$valueRSOld['inventorytypecode']]['rate'][$valueRSOld['rateplancode']]['in_date'][$in_time])){
                                                            $setAvailRate[$valueRSOld['inventorytypecode']]['rate'][$valueRSOld['rateplancode']]['in_date'][$in_time]['count'] = -1;
                                                        }else{
                                                            $setAvailRate[$valueRSOld['inventorytypecode']]['rate'][$valueRSOld['rateplancode']]['in_date'][$in_time]['count'] --;
                                                        }
                                                    }else{
                                                        if(!isset($setAvail[$valueRSOld['room_level_id']]['in_date'][$in_time])){
                                                            $setAvail[$valueRSOld['room_level_id']]['in_date'][$in_time]['count'] = -1;
                                                        }else{
                                                            $setAvail[$valueRSOld['room_level_id']]['in_date'][$in_time]['count'] --;
                                                        }
                                                    }
                                                }
                                            }
                                            DB::update('room_status',array('status'=>'CANCEL'),'reservation_room_id='.$valueRROldDelete['id']);
                                        }
                                        DB::update('reservation_room',array('status'=>'CANCEL'),'reservation_id='.$reservation_id);
                                    }
                                }
                            }else{
                                $check_res = 1;
                                $mess_res = "Reservation does not exist";
                            }
                        }
                        
                        if($check_res==1){
                                            $reponse_xml .= "<Errors>";
                                            $reponse_xml .= "<Error Type=\"3\" Code=\"448\">".$mess_res."</Error>";
                                        $reponse_xml .= "</Errors>";
                                        $reponse_xml .= $SOAPBody;
                                      $reponse_xml .= "</HotelReservation>";
                                    $reponse_xml .= "</HotelReservations>";
                                $reponse_xml .= "</HotelNotifReport>";
                            $reponse_xml .= "</NotifDetails>";
                            $reponse_xml .= "</OTA_NotifReportRQ>";
                            continue;
                        }else{
                            /** xet avail **/
                            if(isset($setAvail)){
                                //System::debug($setAvail);
                                $data_xml = array();
                                foreach($setAvail as $keyAvail=>$valueAvail){
                                    $r_level_id = $keyAvail;
                                    $siteminder_room_type = DB::fetch('select * from siteminder_room_type where room_level_id='.$r_level_id);
                                    $data_xml[$siteminder_room_type['inventory_type_code']]['id'] = $siteminder_room_type['inventory_type_code'];
                                    foreach($valueAvail['in_date'] as $keyAvailTime=>$valueAvailTime){
                                        // availability ban dau trong ngay
                                        $inventory_avail = DB::fetch('select availability from siteminder_room_type_time where siteminder_room_type_id='.$siteminder_room_type['id'].' and time='.Date_Time::to_time($keyAvailTime),'availability');
                                        // set tu dong
                                        $data_xml[$siteminder_room_type['inventory_type_code']]['timeline'][Date_Time::to_time($keyAvailTime)]['time'] = Date_Time::to_time($keyAvailTime);
                                        if($siteminder_room_type['auto_set_avail']==1){
                                            // availability thuc te dang co
                                            $room_levels = check_availability('',' rl.id='.$r_level_id,Date_Time::to_time($keyAvailTime),(Date_Time::to_time($keyAvailTime)+86400));
                                            $vail_real = isset($room_levels[$r_level_id]['day_items'][Date_Time::to_time($keyAvailTime)]['number_room_quantity'])?$room_levels[$r_level_id]['day_items'][Date_Time::to_time($keyAvailTime)]['number_room_quantity']:0;
                                            
                                            $overbook = $siteminder_room_type['overbook_quantity'];
                                            if(($vail_real-$inventory_avail)>$overbook){
                                                DB::update('siteminder_room_type_time',array('status'=>0),'siteminder_room_type_id='.$siteminder_room_type['id'].' and time='.Date_Time::to_time($keyAvailTime));
                                                $data_xml[$siteminder_room_type['inventory_type_code']]['timeline'][Date_Time::to_time($keyAvailTime)]['availability'] = $overbook;
                                            }else{
                                                DB::update('siteminder_room_type_time',array('status'=>0,'availability'=>(($inventory_avail-$valueAvailTime['count'])<=0?0:($inventory_avail-$valueAvailTime['count']))),'siteminder_room_type_id='.$siteminder_room_type['id'].' and time='.Date_Time::to_time($keyAvailTime));
                                                $data_xml[$siteminder_room_type['inventory_type_code']]['timeline'][Date_Time::to_time($keyAvailTime)]['availability'] = ($inventory_avail-$valueAvailTime['count'])<=0?0:($inventory_avail-$valueAvailTime['count']);
                                            }
                                        }else{
                                            DB::update('siteminder_room_type_time',array('status'=>0,'availability'=>(($inventory_avail-$valueAvailTime['count'])<=0?0:($inventory_avail-$valueAvailTime['count']))),'siteminder_room_type_id='.$siteminder_room_type['id'].' and time='.Date_Time::to_time($keyAvailTime));
                                            $data_xml[$siteminder_room_type['inventory_type_code']]['timeline'][Date_Time::to_time($keyAvailTime)]['availability'] = ($inventory_avail-$valueAvailTime['count'])<=0?0:($inventory_avail-$valueAvailTime['count']);
                                        }
                                    }
                                }
                                /** get xml flie **/
                                $data_map = array();
                                foreach($data_xml as $keyXml=>$valueXml){
                                    ksort($valueXml['timeline']);
                                    $data_map[$valueXml['id']]['id'] = $valueXml['id'];
                                    $keyNumMap = 0;
                                    $timeline = 0;
                                    $avail = 'XX';
                                    foreach($valueXml['timeline'] as $keyTime=>$valueTime){
                                        if($timeline==Date_Time::to_time(date('d/m/Y',$valueTime['time']-86400)) and $avail==$valueTime['availability']){
                                            $data_map[$valueXml['id']]['timeline'][$keyNumMap]['EndTime'] = $valueTime['time'];
                                        }else{
                                            $keyNumMap++;
                                            $data_map[$valueXml['id']]['timeline'][$keyNumMap]['StartTime'] = $valueTime['time'];
                                            $data_map[$valueXml['id']]['timeline'][$keyNumMap]['EndTime'] = $valueTime['time'];
                                            $data_map[$valueXml['id']]['timeline'][$keyNumMap]['Avail'] = $valueTime['availability'];
                                        }
                                        $avail=$valueTime['availability'];
                                        $timeline = $valueTime['time'];
                                    }
                                }
                                if(sizeof($data_map)>0){
                                    $SOAPBodyAvail = "<OTA_HotelAvailNotifRQ xmlns=\"http://www.opentravel.org/OTA/2003/05\" EchoToken=\"".$this->GetEchoToken()."\" TimeStamp=\"".$this->GetTimeStamp()."\" Version=\"1\">";
                                        $SOAPBodyAvail .= "<POS>";
                                            $SOAPBodyAvail .= "<Source>";
                                              $SOAPBodyAvail .= "<RequestorID Type=\"22\" ID=\"".SITEMINDER_REQUESTOR_ID."\"/>";
                                            $SOAPBodyAvail .= "</Source>";
                                        $SOAPBodyAvail .= "</POS>";
                                        $SOAPBodyAvail .= "<AvailStatusMessages HotelCode=\"".SITEMINDER_HOTELCODE."\">";
                                    foreach($data_map as $keyMap=>$valueMap){
                                        $inventory_type_code = $valueMap['id'];
                                        if(isset($valueMap['timeline'])){
                                            foreach($valueMap['timeline'] as $kTime=>$vTime){
                                                $SOAPBodyAvail .= "<AvailStatusMessage BookingLimit=\"".$vTime['Avail']."\">";
                                                  $SOAPBodyAvail .= "<StatusApplicationControl Start=\"".date('Y-m-d',$vTime['StartTime'])."\" End=\"".date('Y-m-d',$vTime['EndTime'])."\" InvTypeCode=\"".$inventory_type_code."\"/>";
                                                $SOAPBodyAvail .= "</AvailStatusMessage>";
                                            }
                                        }
                                    }
                                    $SOAPBodyAvail .= "</AvailStatusMessages>";
                                        $SOAPBodyAvail .= "</OTA_HotelAvailNotifRQ>";
                                    
                                    $id_soap = DB::insert('siteminder_soap_body',array('type'=>'AVAIL','time'=>time(),'portal_id'=>PORTAL_ID));
                                    $path = ROOT_PATH.'packages/hotel/packages/siteminder/includes/dataXml/SOAP_'.$id_soap.'.xml';
                                    $this->save_file($path,$SOAPBodyAvail);
                                }
                            }
                            if(isset($setAvailRate)){
                                //System::debug($setAvailRate);
                                $data_rate_xml = array();
                                foreach($setAvailRate as $keyAvail=>$valueAvail){
                                    //$r_level_id = $keyAvail;
                                    $siteminder_room_type = DB::fetch('select * from siteminder_room_type where inventory_type_code=\''.$keyAvail.'\'');
                                    $r_level_id = $siteminder_room_type['room_level_id'];
                                    //$data_rate_xml[$siteminder_room_type['inventory_type_code']]['id'] = $siteminder_room_type['inventory_type_code'];
                                    foreach($valueAvail['rate'] as $keyRateAvail=>$valueRateAvail){
                                        //System::debug($valueRateAvail);
                                        $siteminder_room_rate = DB::fetch('select * from siteminder_room_rate where rate_plan_code=\''.$keyRateAvail.'\' and siteminder_room_type_id='.$siteminder_room_type['id']);
                                        foreach($valueRateAvail['in_date'] as $keyAvailTime=>$valueAvailTime){
                                            // availability ban dau trong ngay
                                            $inventory_avail = DB::fetch('select availability from siteminder_rate_avail_time where siteminder_room_rate_id='.$siteminder_room_rate['id'].' and time='.Date_Time::to_time($keyAvailTime),'availability');
                                            // set tu dong
                                            $data_rate_xml[$siteminder_room_rate['id']]['id'] = $siteminder_room_rate['id'];
                                            $data_rate_xml[$siteminder_room_rate['id']]['rate_plan_code'] = $siteminder_room_rate['rate_plan_code'];
                                            $data_rate_xml[$siteminder_room_rate['id']]['inventory_type_code'] = $siteminder_room_type['inventory_type_code'];
                                            $data_rate_xml[$siteminder_room_rate['id']]['timeline'][Date_Time::to_time($keyAvailTime)]['time'] = Date_Time::to_time($keyAvailTime);
                                            DB::update('siteminder_rate_avail_time',array('status'=>0,'availability'=>(($inventory_avail-$valueAvailTime['count'])<=0?0:($inventory_avail-$valueAvailTime['count']))),'siteminder_room_rate_id='.$siteminder_room_rate['id'].' and time='.Date_Time::to_time($keyAvailTime));
                                            $data_rate_xml[$siteminder_room_rate['id']]['timeline'][Date_Time::to_time($keyAvailTime)]['availability'] = ($inventory_avail-$valueAvailTime['count'])<=0?0:($inventory_avail-$valueAvailTime['count']);
                                        }
                                    }
                                }
                                /** get xml flie **/
                                $data_map = array();
                                foreach($data_rate_xml as $keyXml=>$valueXml){
                                    ksort($valueXml['timeline']);
                                    $data_map[$valueXml['id']]['id'] = $valueXml['id'];
                                    $data_map[$valueXml['id']]['rate_plan_code'] = $valueXml['rate_plan_code'];
                                    $data_map[$valueXml['id']]['inventory_type_code'] = $valueXml['inventory_type_code'];
                                    $keyNumMap = 0;
                                    $timeline = 0;
                                    $avail = 'XX';
                                    foreach($valueXml['timeline'] as $keyTime=>$valueTime){
                                        if($timeline==Date_Time::to_time(date('d/m/Y',$valueTime['time']-86400)) and $avail==$valueTime['availability']){
                                            $data_map[$valueXml['id']]['timeline'][$keyNumMap]['EndTime'] = $valueTime['time'];
                                        }else{
                                            $keyNumMap++;
                                            $data_map[$valueXml['id']]['timeline'][$keyNumMap]['StartTime'] = $valueTime['time'];
                                            $data_map[$valueXml['id']]['timeline'][$keyNumMap]['EndTime'] = $valueTime['time'];
                                            $data_map[$valueXml['id']]['timeline'][$keyNumMap]['Avail'] = $valueTime['availability'];
                                        }
                                        $avail=$valueTime['availability'];
                                        $timeline = $valueTime['time'];
                                    }
                                }
                                if(sizeof($data_map)>0){
                                    $SOAPBodyAvail = "<OTA_HotelAvailNotifRQ xmlns=\"http://www.opentravel.org/OTA/2003/05\" EchoToken=\"".$this->GetEchoToken()."\" TimeStamp=\"".$this->GetTimeStamp()."\" Version=\"1\">";
                                        $SOAPBodyAvail .= "<POS>";
                                            $SOAPBodyAvail .= "<Source>";
                                              $SOAPBodyAvail .= "<RequestorID Type=\"22\" ID=\"".SITEMINDER_REQUESTOR_ID."\"/>";
                                            $SOAPBodyAvail .= "</Source>";
                                        $SOAPBodyAvail .= "</POS>";
                                        $SOAPBodyAvail .= "<AvailStatusMessages HotelCode=\"".SITEMINDER_HOTELCODE."\">";
                                    foreach($data_map as $keyMap=>$valueMap){
                                        $inventory_type_code = $valueMap['inventory_type_code'];
                                        $rate_plan_code = $valueMap['rate_plan_code'];
                                        if(isset($valueMap['timeline'])){
                                            foreach($valueMap['timeline'] as $kTime=>$vTime){
                                                $SOAPBodyAvail .= "<AvailStatusMessage BookingLimit=\"".$vTime['Avail']."\">";
                                                  $SOAPBodyAvail .= "<StatusApplicationControl Start=\"".date('Y-m-d',$vTime['StartTime'])."\" End=\"".date('Y-m-d',$vTime['EndTime'])."\" InvTypeCode=\"".$inventory_type_code."\" RatePlanCode=\"".$rate_plan_code."\"/>";
                                                $SOAPBodyAvail .= "</AvailStatusMessage>";
                                            }
                                        }
                                    }
                                    $SOAPBodyAvail .= "</AvailStatusMessages>";
                                        $SOAPBodyAvail .= "</OTA_HotelAvailNotifRQ>";
                                    
                                    $id_soap = DB::insert('siteminder_soap_body',array('type'=>'AVAIL','time'=>time(),'portal_id'=>PORTAL_ID));
                                    $path = ROOT_PATH.'packages/hotel/packages/siteminder/includes/dataXml/SOAP_'.$id_soap.'.xml';
                                    $this->save_file($path,$SOAPBodyAvail);
                                }
                                
                            }
                            /** end avail **/
                                        $reponse_xml .= "<Success/>";
                                        $reponse_xml .= $SOAPBody;
                                        $reponse_xml .= "<ResGlobalInfo>";
                                            $reponse_xml .= "<HotelReservationIDs>";
                                              $reponse_xml .= "<HotelReservationID ResID_Type=\"14\" ResID_Value=\"".$reservation_id."\"/>";
                                            $reponse_xml .= "</HotelReservationIDs>";
                                          $reponse_xml .= "</ResGlobalInfo>";
                                      $reponse_xml .= "</HotelReservation>";
                                    $reponse_xml .= "</HotelReservations>";
                                $reponse_xml .= "</HotelNotifReport>";
                            $reponse_xml .= "</NotifDetails>";
                            $reponse_xml .= "</OTA_NotifReportRQ>";
                            continue;
                        }
                    }
                }else{ // check customer map false
                                $reponse_xml .= "<Errors>";
                                    $reponse_xml .= "<Error Type=\"3\" Code=\"448\">BookingChannel Code is not mapped with newway</Error>";
                                $reponse_xml .= "</Errors>";
                                $reponse_xml .= $SOAPBody;
                              $reponse_xml .= "</HotelReservation>";
                            $reponse_xml .= "</HotelReservations>";
                        $reponse_xml .= "</HotelNotifReport>";
                    $reponse_xml .= "</NotifDetails>";
                    $reponse_xml .= "</OTA_NotifReportRQ>";
                    continue;
                }
            }else{ // check customer false
                                    $reponse_xml .= "<Errors>";
                                    $reponse_xml .= "<Error Type=\"3\" Code=\"448\">Invalid BookingChannel Code</Error>";
                                $reponse_xml .= "</Errors>";
                                $reponse_xml .= $SOAPBody;
                              $reponse_xml .= "</HotelReservation>";
                            $reponse_xml .= "</HotelReservations>";
                        $reponse_xml .= "</HotelNotifReport>";
                    $reponse_xml .= "</NotifDetails>";
                    $reponse_xml .= "</OTA_NotifReportRQ>";
                continue;
            } // End Check Customer
        }// end Foreach $reservation
        return $reponse_xml;
    }
    
    function read_reservation()
    {
		if ($this->method == 'POST'){
            if(Url::get('secretkey') and DB::exists('select id from siteminder_apikey where secretkey=\''.Url::get('secretkey').'\'')){
                $reservation = json_decode($_REQUEST['reservation'],true);
                $portal_id = '#'.$_REQUEST['portal_id'];
                $reponse_xml = api::ReserationRes($reservation,$portal_id);
                $this->response(200, $reponse_xml);
            }else{
                $this->response(500, "FAILED"); // AUTH
            }
		}
		else{
            $this->response(500, "FAILED"); // METHOD
		}
	}
    function save_file($file,$content)
    {
    	$handler = fopen($file,'w+');
    	fwrite($handler,$content);
    	fclose($handler);
    }
}   

$api = new api();
?>
