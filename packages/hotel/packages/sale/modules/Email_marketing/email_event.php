<?php
function send_mail_event()
{
        $sql_customer = '
                    SELECT 
                        email_list.id,
                        email_send_id,
                        CASE
                        when email_list.traveller_id IS NULL
                            THEN customer.email
                            ELSE traveller.email
                        end email,
                        email_send.title,
                        email_send.content,
                        email_send.images,
                        email_send.date_send AS send_date
                    FROM email_list
                        INNER JOIN email_send ON email_send.id = email_list.email_send_id
                        LEFT JOIN customer ON customer.id = email_list.customer_id
                        LEFT JOIN traveller ON traveller.id = email_list.traveller_id
                    WHERE email_list.status !=1 
                          AND (
                                    (customer.email IS NOT NULL and email_list.traveller_id IS NULL)
                                or
                                     (traveller.email IS NOT NULL and email_list.customer_id IS NULL)
                                 )
                         AND  email_send.date_send =\''.Date_time::to_orc_date(date('d/m/y')).'\' 
                      '; 
         
         $data = DB::fetch_all($sql_customer);
         //System::debug($data);
         if(!empty($data))
         {
            $i=0;
            foreach($data as $key => $value)
            {
                ($file =fopen(ROOT_PATH_EMAIL.$value['content'],'r'));
                $content='';
                while(!feof($file))
                {
                    ($content .=  fgets($file));
                }
                (fclose($file));
                $today =date('d-M-y');
                require_once "packages/hotel/includes/email/class_mail/class.phpmailer.php";
                require_once "packages/hotel/includes/email/class_mail/class.smtp.php";
                $mail             = new PHPMailer();
                $mail->IsSMTP(); 
                $mail->FromName   = HOTEL_NAME;
                $mail->SMTPDebug  = 1;
                $mail->CharSet    = "UTF-8"; 
                $mail->SMTPAuth   = true;
                $mail->SMTPSecure = 'ssl';  
                $mail->Host       = EMAIL_MARKETING_SMTP;  
                $mail->Port       = EMAIL_MARKETING_PORT; 
                $mail->Username   = EMAIL_MARKETING;
                $mail->Password   = EMAIL_MARKETING_PASSWORD; 
                $mail->IsHTML(true);
                $mail->Subject    = $value['title']; 
                $mail->AltBody    = "Hey, check out this new post on ";
                $mail->MsgHTML($content); 
                $mail->AddAttachment(ROOT_PATH_EMAIL.$value['images'],$today.'.jpg');
                $mail->AddAddress($value['email']);
                if(!$mail->Send())
                {                        
                    DB::update('email_list',array('status'=>'2'),'id='.$value['id']);
                    echo 'email send error';
                }
                else
                {
                    DB::update('email_list',array('status'=>'1'),'id='.$value['id']); 
                    echo 'email send success';
                }
                if(++$i>3)
                    break;
                
            } 
         
        }
     
     
      
}
?>