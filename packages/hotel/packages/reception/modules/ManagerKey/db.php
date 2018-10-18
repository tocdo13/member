<?php 
    require_once 'PhpSocket.php';
    //giap.ln add function process on client; return 1 if success or -1 if unsuccess
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
            return -1;
        }
        
        if($conn->Send($str)<=0)
        {
            $conn->Stop();
            return -1;
        }
        $receive = $conn->Receive(); 
        if($receive=='-1')
        {
            $conn->Stop();
            return -1;
        }
        $conn->Stop();
        return $receive; 
    }
    //end giap.ln
    //giap.ln add Read Card function 03-10-2014
    function ReadKey($str,$ip,$port)
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
            return -1;
        }
        
        if($conn->Send($str)<=0)
        {
            $conn->Stop();
            return -1;
        }
        $result = $conn->Receive();
        $conn->Stop();
        
        if($result==0)
            return -1;
        return $result;
    }
    //end giap.ln
   
?>
