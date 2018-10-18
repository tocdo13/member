<?php
    date_default_timezone_set('Asia/Saigon'); 
	require_once 'packages/core/includes/system/config.php';  
    require_once('webservice/lib/nusoap.php');
    
    $server = new nusoap_server;
    
    $server->configureWSDL('server','urn:server');
    
    $server->wsdl->schemaTargetNamespace = 'urn:server';
    
    $server->wsdl->addComplexType('MyComplexType','complexType','struct','all','',
    array( 'min_price' => array('name' => 'min_price','type' => 'xsd:string'),
            'max_price' => array('name' => 'max_price','type' => 'xsd:string'),
            'date_from' => array('name' => 'date_from','type' => 'xsd:string'),
            'date_to' => array('name' => 'date_to','type' => 'xsd:string')                        
            ));
                  
    $server->Register('checkAvaiableRoom',
            array('info','tns:MyComplexType'),
            array('return'=>'xsd:string'),
            'urn:server',
            'urn:server#checkAvaiableRoom'
        );              
                   
    /**------- Khai bao kieu du lieu cho function reservation ---**/
    $server->wsdl->addComplexType('reservation_detail','complexType','struct','all','',
    array(
          'id'=>array('name'=>'id','type'=>'xsd:string'),
          'child'=>array('name'=>'child','type'=>'xsd:string'),
          'adult'=>array('name'=>'adult','type'=>'xsd:string'),
          'quantity'=>array('name'=>'quantity','type'=>'xsd:integer'),
          'price'=>array('name'=>'price','type'=>'xsd:string'),
          'usd_price'=>array('name'=>'usd_price','type'=>'xsd:string'),
          'net_price'=>array('name'=>'net_price','type'=>'xsd:integer'),
          'exchange_rate'=>array('name'=>'exchange_rate','type'=>'xsd:string'),
          'note'=>array('name'=>'note','type'=>'xsd:string'),
          'room_level_id'=>array('name'=>'room_level_id','type'=>'xsd:string'),
          'time_in'=>array('name'=>'time_in','type'=>'xsd:string'),
          'time_out'=>array('name'=>'time_out','type'=>'xsd:string'),
          'tax_rate'=>array('name'=>'tax_rate','type'=>'xsd:string'),
          'service_rate'=>array('name'=>'service_rate','type'=>'xsd:string')
         ));    
                  
            
    $server->wsdl->addComplexType('reservation','complexType','struct','all','',
    array( 'customer_name' => array('name' => 'customer_name','type' => 'xsd:string'),
            'phone_number' => array('name' => 'phone_number','type' => 'xsd:string'),
            'email' => array('name' => 'email','type' => 'xsd:string'),
            'tax_code' => array('name' => 'tax_code','type' => 'xsd:string'),
            'address' => array('name' => 'address','type' => 'xsd:string'),
            'info' => array('name' => 'info','type' =>'tns:reservation_detail_array')                           
            ));
    
    $server->wsdl->addComplexType(
    'reservation_detail_array',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:reservation_detail[]')
    ),
    'tns:reservation_detail'
    );
        
    
    $server->Register('reservation',
            array('info','tns:reservation'),
            array('return'=>'xsd:string'),
            'urn:server',
            'urn:server#reservation'
        ); 
    /**-------END--- Khai bao kieu du lieu cho function reservation ---**/    
    
    function getCurrentcy(){
        // Lay ti gia online
        $sql = "SELECT id,exchange FROM currency WHERE id='USD'";
        $result = DB::fetch($sql);
        return $result['exchange'];
        // end
    }
    
    function checkAvaiableRoom($info){
        //return array('min_price'=>'11','max_price'=>'1','date_from'=>'1/1/2011','date_to'=>'2/3/2012');
        $date_from = Date_Time::to_time(converDate($info['date_from']));
        $date_to = Date_Time::to_time(converDate($info['date_to']));
        
                
                        
        $sql = "SELECT * FROM room_level WHERE portal_id='#default' AND is_virtual=0 AND price>=".$info['min_price']." AND price<=".$info['max_price'];
        $room_level = DB::fetch_all($sql);
        foreach($room_level as $key=>$value){
            $sql = "SELECT count(id) as count_room FROM room WHERE room_level_id=".$value['id']." AND portal_id='#default'";
            $count_room = DB::fetch($sql);
            
            $sql = "SELECT id, time_in, time_out FROM reservation_room WHERE room_level_id=".$value['id'];
            $result = DB::fetch_all($sql);
            foreach($result as $k=>$v){
                $date_from_current = $v['time_in'];
                $date_to_current = $v['time_out'];
                if(($date_from<=$date_from_current && $date_from_current <=$date_to) || ($date_from<=$date_to_current && $date_to_current<=$date_to) || ($date_from<=$date_from_current && $date_to>=$date_to_current) || ($date_from<=$date_from_current && $date_to>=$date_to_current)){
                    $count_room['count_room']--;
                }
            }
            if($count_room['count_room']==0){
                unset($room_level[$key]);
            }
            else{
                $value['currencyValue']  =  getCurrentcy(); 
                $value['count_room'] = $count_room['count_room'];
                $room_level[$key] = $value;
            }          
        }
        $room_level['date_from'] = $date_from;
        $room_level['date_to'] = $date_to;
        return json_encode($room_level);
    }
    function converDate($value){
        $arr = explode('-',$value);
        return $arr[2].'/'.$arr[1].'/'.$arr[0];
    }
    
    
    function reservation($info){
        require_once 'packages/hotel/packages/mice/includes/create_reservation.php';
        $customer_info = array();
        $customer_info['customer_id'] = 2;
        $customer_info['booker'] = $info['customer_name'];
        $customer_info['phone_booker'] = $info['phone_number'];

        return json_encode(credit_reservation($info['info'],$customer_info,true,''));   
    }
    
    
    
    $HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA)?$HTTP_RAW_POST_DATA:'';
    
    $server->service($HTTP_RAW_POST_DATA);
    
?>