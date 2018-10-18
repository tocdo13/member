<?php
    function CreatFileTextFolioRoom()
    {
        if(Url::get('folio_id'))
        {
            $folio_type = DB::fetch('
                             SELECT 
                                  folio.id,
                                  folio.customer_id
                             FROM
                                  folio                  
                             WHERE 
                                  folio.id='.Url::iget('folio_id').'   
                            ');
                            
            if(isset($folio_type['customer_id']))
            {
                
                require_once 'packages/hotel/includes/email/room/group_invoice/default_group_invoice.php';
                require_once 'packages/hotel/includes/email/room/group_invoice/group_invoice.php';
                $folio_id = $folio_type['id'];
                $invoice_group = setGroupInvoice(Url::get('id'),$folio_id);
                $contentEmail = ContentGroupEmail($invoice_group);
                
                if(!file_exists('packages/hotel/includes/Email/room/files'))
                {
                    mkdir('packages/hotel/includes/Email/room/files');
                }
                $check_edit = DB::fetch('select check_edit from folio where id='.Url::get('folio_id'));
                if($check_edit['check_edit']==1)
                {
                   
                    file_put_contents('packages/hotel/includes/Email/room/files/invoice_'.Url::get('folio_id').'_'.time().'.text',$contentEmail);
                    DB::update('folio',array('check_edit'=>'0'),'id='.Url::get('folio_id'));
                }   
                
            }
            else
            {
                
                require_once 'packages/hotel/includes/email/room/invoice/default_invoice.php';
                require_once 'packages/hotel/includes/email/room/invoice/invoice.php';
                $folio_id = $folio_type['id'];
                $itemSend= setFolioTraveller(Url::get('traveller_id'),$folio_id);
                
                $contentEmail = contentEmail($itemSend);
                
                if(!file_exists('packages/hotel/includes/Email/room/files'))
                {
                    mkdir('packages/hotel/includes/Email/room/files');
                }
                $check_edit = DB::fetch('select check_edit from folio where id='.$folio_id);
                
                if($check_edit['check_edit']==1)
                {
                    file_put_contents('packages/hotel/includes/Email/room/files/invoice_'.$folio_id.'_'.time().'.text',$contentEmail);
                    DB::update('folio',array('check_edit'=>'0'),'id='.$folio_id);
                }   
                   
            }
        }
        
                          
    }    
    
    function SendManageCreatFolio()
    {
        require_once "packages/hotel/includes/email/class_mail/class.phpmailer.php";
        require_once "packages/hotel/includes/email/class_mail/class.smtp.php";
        
        if(file_exists(ROOT_PATH_EMAIL.'packages/hotel/includes/Email/room/files'))
        {
            define('PATH_EMAIL_ROOM', 'packages/hotel/includes/Email/room/files/');
            $files = array();
            $dir = opendir(ROOT_PATH_EMAIL.PATH_EMAIL_ROOM);
            
            while ($f = readdir($dir)) 
            {
                if (eregi("\.text", $f))
                array_push($files, $f);
            }
            closedir($dir);
            if (!empty($files)) 
            {
                $i=1;
                foreach ($files as $k => $v) 
                { 
                    if(EMAIL_INVOICE_CREART!='' && ROOM_INVOICE==1)
                    {
                        if(++$i>3)
                            break;
                        ($file =fopen(ROOT_PATH_EMAIL.PATH_EMAIL_ROOM.$v,'r+'));
                        $content='';
                        while(!feof($file))
                        {
                            $content .=  fgets($file);
                        }
                        fclose($file);
                        $mail             = new PHPMailer();
                        $mail->IsSMTP(); 
                        $mail->FromName   = HOTEL_NAME;
                        $mail->SMTPDebug  = 1;
                        $mail->CharSet    = "UTF-8";
                        $mail->SMTPAuth   = true; 
                        $mail->SMTPSecure = 'ssl';  
                        $mail->Host       = EMAIL_INVOICE_SMTP;  
                        $mail->Port       = EMAIL_INVOICE_PORT; 
                        $mail->Username   = EMAIL_INVOICE;
                        $mail->Password   = EMAIL_INVOICE_PASSWORD; 
                        $mail->Subject    = "Hóa Đơn Tiền Phòng"; 
                        $mail->AltBody    = "Hey, check out this new post on ";
                        $mail->IsHTML(true);
                        $mail->MsgHTML($content);
                        $mail->AddAddress(EMAIL_INVOICE_CREART); 
                        
                        if(!$mail->Send())
                        {
                        ?>   
                            <script>
                               alert("Email send error");
                            </script>
                        <?php
                        }  
                        else
                        {
                            
                           unlink(ROOT_PATH_EMAIL.PATH_EMAIL_ROOM.$v);
                        ?>
                            <script>
                               alert("Email send success");
                            </script>
                        
                        <?php
                        }
                    }
                }
            }
        }
        
        
               
    } 
    
?>