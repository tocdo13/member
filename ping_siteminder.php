<?php 
    date_default_timezone_set('Asia/Saigon');
    define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
    set_include_path(ROOT_PATH);
    require_once 'packages/core/includes/system/config.php';
    set_time_limit(-1);
        $request = new HttpRequest();
        $request->setUrl('https://cmtpi.siteminder.com/pmsxchangev2/services/NEWWAY');
        $request->setMethod(HTTP_METH_POST);
        
        $request->setHeaders(array(
          'cache-control' => 'no-cache',
          'content-type' => 'text/xml'
        ));
        
        $request->setBody('<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"><SOAP-ENV:Header><wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd"><wsse:UsernameToken><wsse:Username>NewWayTest</wsse:Username><wsse:Password>f7ASm3bHJBe4RbDxAMgq8WxN</wsse:Password></wsse:UsernameToken></wsse:Security></SOAP-ENV:Header><SOAP-ENV:Body><OTA_PingRQ xmlns="http://www.opentravel.org/OTA/2003/05" EchoToken="20CF4EDF-1849-4584-9715-82464BE8E542" TimeStamp="2018-07-13T12:12:35+07:00" Version="1"><EchoData> Hello World </EchoData></OTA_PingRQ></SOAP-ENV:Body></SOAP-ENV:Envelope>');
        
        try {
          $response = $request->send();
          echo $response->getBody();
        } catch (HttpException $ex) {
          echo $ex;
        }
?>