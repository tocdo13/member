<?php 
class SITEMINDER
{
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
        return gmdate(DATE_ATOM,time());
	}
    
    function CallToSitminder($SOAPBody){
        /**
        * Call To Sitminder
        * 
        * @SOAPBody $SOAPBody post content
        * @SOAPSecurity $SOAPSecurity header for authentication purposes and contain the SOAP Body contains the OTA message
        * @return Soap or error
        */
        $SOAPSecurity = "<SOAP-ENV:Envelope xmlns:SOAP-ENV=\"http://schemas.xmlsoap.org/soap/envelope/\"><SOAP-ENV:Header><wsse:Security xmlns:wsse=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd\"><wsse:UsernameToken><wsse:Username>".SITEMINDER_USERNAME."</wsse:Username><wsse:Password>".SITEMINDER_PASSWORD."</wsse:Password></wsse:UsernameToken></wsse:Security></SOAP-ENV:Header><SOAP-ENV:Body>".$SOAPBody."</SOAP-ENV:Body></SOAP-ENV:Envelope>";
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => SITEMINDER_URI,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => $SOAPSecurity,
          CURLOPT_HTTPHEADER => array(
            "Cache-Control: no-cache",
            "Content-Type: text/xml"
          ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
          return array('error'=>1,'message'=>$err);
        } else {
          return array('error'=>0,'message'=>$response);
        }
    }
    
}
?>
