<?php
    require_once('lib/nusoap.php');
    
    $url = "http://newwaypms.ddns.net:8088/version18/service.php/wsdl";
    $client = new nusoap_client($url);
    $err = $client->getError();
    if($err){
      echo "<p><b>".$err."</b></p>";
    }
    if(isset($_POST['check_avaiable_room'])){
    $min_price = isset($_POST['min_price'])? $_POST['min_price'] : 0;
    $max_price = isset($_POST['max_price'])? $_POST['max_price'] : 9999999999;
    
    $date_from = isset($_POST['date_from'])? $_POST['date_from'] : '01/01/1970';
    $date_to = isset($_POST['date_to'])? $_POST['date_to'] : '01/01/2200';
    
    $search_info = array('min_price'=>$min_price,
                         'max_price'=>$max_price,
                         'date_from'=>$date_from,
                         'date_to'=>$date_to   
                        );
    $return = $client->call('checkAvaiableRoom', array($search_info));
    echo $return;
    } 
    else if(isset($_POST['reservation'])){
        $customer_name = $_POST['customer_name'];
        $phone_number = $_POST['phone_number'];
        $email = $_POST['email'];
        $tax_code = $_POST['tax_code'];
        $address = $_POST['address'];
        $info = $_POST['info'];
        
        $infomation = array('customer_name'=>$customer_name,
                         'phone_number'=>$phone_number,
                         'email'=>$email,
                         'tax_code'=>$tax_code,
                         'address'=>$address,
                         'info'=>$info   
                        );
       $result = $client->call('reservation', array($infomation));
       echo $result;
    }            
?>