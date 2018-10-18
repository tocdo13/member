<?php 
    require_once 'PhpSocket.php';
    function proccess_cmd($str_client,$ip,$port,&$data)
    {
        //$data = '³CP³';
        //return 0;
        //$data='³LT³4³101³102³³³CI³2³13³1455051114³14201114³PMS³';
       // return 0;
        $conn = new PhpSocket($ip, $port, '\final\\');
        if(!$conn)
        {
            $conn->Stop();
            return -1;// "³SI³";// 
        }
        if(!$conn->Start())   
        {    
            $conn->Stop();
            return -2;// "³SC³";
        } 
        
        if(!$conn->Send($str_client))
        {    
            $conn->Stop();
            return -3;//"³SS³";
        }
        
        $data = $conn->read(5);
        //$data = "³CN³2³2F45A2C3³";//tra ve create card 
        //$data ="³LT³2³303³201³202³203³CI³2³³³12210115³PMS³";//tra ve khi doc the 
        if(!$data)
        {    
            $conn->Stop();
            return -4;//"³SR³";
        }
        $conn->Stop();
        
        return 0;
    }
    function display_error_socket($num)
    {
        $result ='';
        switch($num)
        {
            case -1:
            {
                $result ='ERROR: Cant not create socket';
                break;   
            }
            case -2:
            {
                $result ='ERROR: Cant not connect to interface';
                break;
            }
            case -3:
            {
                $result ='ERROR: Cant not send command to interface';
                break;
            }
            case -4:
            {
                $result ='ERROR: Cant not receive result from interface';
                break;
            }
            
            default:
                break;
        }
        return $result;
    }
    function proccess_data($data)
    {
        $result ='';
        if(strToHex($data) == '15')
        {
            $result ='ERROR: receive from PC interface is NAK';
        }
        else
        {
            $arr_result = explode('³',$data);
            $len = count($arr_result);
            unset($arr_result[0]);
            unset($arr_result[$len-1]);
            
            switch(substr($arr_result[1],0,2))
            {
                case 'CO':
                case 'CP':
                case 'LT'://doc the
                {
                    $result = 'success';
                    break;
                } 
                //case 'CO'://check out
                //case 'CP'://truong hop mat the phong
                case 'CN'://tao the  
                case 'CC'://copy the 
                {
                    $result ='Create card success';
                    break;
                }
                case 'ES':
                {
                    $result = 'ERROR: Syntax message error!';
                    break;
                }
                case 'NC':
                {
                    $result ='ERROR: No communications <br/> encoder does not answer!';
                    break;
                }
                case 'NF':
                {
                    $result ='ERROR: No files database in interface!';
                    break;
                }
                case 'OV':
                {
                    $result ='ERROR: the encoder is still busy executing!';
                    break;
                }
                case 'EP':
                {
                    $result ='ERROR: Card not found or wrongly inserted!';
                    break;
                }
                case 'EF':
                {
                    $result ='ERROR: Format card error';
                    break;
                }
                case 'TD':
                {
                    $result = 'ERROR: Unknown room!';
                    break;
                }
                case 'ED':
                {
                    $result ='ERROR: the encoder has been waiting too long!';
                    break;
                }
                case 'EA':
                {
                    $result ='ERROR: Room is checked out';
                    break;
                }
                case 'OS':
                {
                    $result ='ERROR: Room is out of service';
                    break;
                }
                case 'EO':
                {
                    $result ='ERROR: The requeste conflict by another station.';
                    break;
                }
                case 'EV':
                {
                    $result ='ERROR: the command belongs to a valid staff user';
                    break;
                }
                case 'ER':
                {
                    $result ='ERROR: General  error';
                    break;
                }
                
                default:
                {
                    $result = 'Undefined error.';
                    break;
                }
            }
        }
        return $result;
    }
    
    function generate_card($arr_param)
    {
        $in = "³";
        foreach($arr_param as $value)
        {
            $in .= $value.'³';//³³
        }
        $in .= "";
		//$in="³CN1³1³E³201³³³³³³0917190115³1200200115³³³³³1³";
        $check = 0x00;
        
        for($i = 1; $i < strlen($in); $i++)
        {
            $check = $check ^ hexdec(strToHex($in[$i]));
        }
        $check = sprintf("%c", $check);
        $in .= $check;
    	return $in;
    }
    function strToHex($string)
    {
        $hex='';
        for ($i=0; $i < strlen($string); $i++)
        {
            $hex .= dechex(ord($string[$i]));
        }
        return $hex;
    }
    

    function addNumberKeys($room,$number_keys)
    {
        $keys = DB::fetch("select NVL(number_keys_res,0) as keys from room where id=".$room,"keys");
        updateNumberKeys($room,$number_keys+$keys);
    }
    
    function updateNumberKeys($room,$number_keys)
    {
        $file = 'D:/result.txt';
        file_put_contents($file, $room."_".$number_keys);
        //DB::update('room',array('number_keys_res'=>$number_keys),'id='.$room);
        //connect
        $conn = oci_connect('denhatmulti_new', 'hotel2012', 'localhost/XE');
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }   
        //
        $stid = oci_parse($conn, "UPDATE room
                                    SET number_keys_res = ".$number_keys."
                                    WHERE id =".$room);
        oci_execute($stid);
        oci_commit($conn);
    }
?>
