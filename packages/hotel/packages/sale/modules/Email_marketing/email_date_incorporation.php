<?php
function send_mail_date_incorporation()
{
    $type_mail =DB::fetch('
            SELECT email_send.id,
                                   email_send.title,
                                   email_send.content,
                                   email_send.images,
                                   email_group_event.code
                            FROM email_send
                                 inner JOIN email_group_event ON  email_send.email_group_event_id = email_group_event.id
                            WHERE email_group_event.code = \'SN\'       
            ');               
    if(!empty($type_mail))
    {
        ($file =fopen(ROOT_PATH_EMAIL.$type_mail['content'],'r'));
        $content='';
        while(!feof($file))
        {
            ($content .=  fgets($file));
        }
        (fclose($file));
        require_once "packages/hotel/includes/email/class_mail/class.phpmailer.php";
        require_once "packages/hotel/includes/email/class_mail/class.smtp.php";
        $list_customer = DB::fetch_all('
                                        SELECT id,
                                               name AS full_name,
                                               email,
                                               TO_CHAR(customer.creart_date,\'dd-mm\')      
                                        FROM customer
                                        WHERE email is not null AND creart_date is not null 
                                              AND TO_CHAR(customer.creart_date,\'dd-mm\') = \''.date('d-m').'\'
                                              AND check_send_mail != \''.date('Y').'\'
                                        ORDER BY id
                                        ');  
       System::debug($list_customer);
       if(!empty($list_customer))
       {
           foreach($list_customer as $k => $v)
           {
                $mail             = new PHPMailer();
                $mail->IsSMTP();
                $mail->FromName   =HOTEL_NAME;
                $mail->SMTPDebug  = 1;
                $mail->CharSet = "UTF-8";                  
                $mail->SMTPAuth   = true;
                $mail->SMTPSecure = 'ssl';
                $mail->Host       = EMAIL_MARKETING_SMTP; 
                $mail->Port       = EMAIL_MARKETING_PORT;
                $mail->Username   = EMAIL_MARKETING;
                $mail->Password   = EMAIL_MARKETING_PASSWORD;
                $mail->IsHTML(true);
                $mail->Subject    = $type_mail['title'];
                $mail->AltBody    = "Hey, check out this new post on ";
                $mail->MsgHTML($content);
                $mail->AddAddress($v['email']);
                $images=$type_mail['images'];
                $mail->AddAttachment(ROOT_PATH_EMAIL.$images,date('d/m/y').$k.'.jpg'); 
                if(!$mail->Send())
                {
                  echo "Mailer Error: " . $mail->ErrorInfo;
                  DB::update('customer',array('check_send_mail'=>'2'),'id='.$v['id']);  
                }
                else
                {
                   echo "Message sent!";
                   DB::update('customer',array('check_send_mail'=>date('Y')),'id='.$v['id']); 
                }
                return;
           }
       }                                                                                           
        
      
    }
    
                    
         
}
?>