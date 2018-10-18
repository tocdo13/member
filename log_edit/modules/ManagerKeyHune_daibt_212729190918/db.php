<?php 
    require_once 'PhpSocket.php';
    function process_client($str,$ip,$port)
    {
        $conn = new PhpSocket($ip, $port, '\final\\');
        set_time_limit(0);
        if(!$conn)
        {
            $conn->Stop();
            return -1; 
        }
        
        if(!$conn->Start())
        {
            $conn->Stop();
            return -2;
        }
        
        if($conn->Send($str)<=0)
        {
            $conn->Stop();
            return -3;
        }
        $receive = $conn->Receive(); 
        if(!isset($receive))
        {
            $conn->Stop();
            return -4;
        }
        $conn->Stop();
        return $receive; 
    }
   
?>
