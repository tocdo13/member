<?php
function send_mail_bk($reservation_id,$addressEmail,$titleEmail,$contentEmail)
{
    require_once "packages/hotel/includes/email/class_mail/class.phpmailer.php";
    require_once "packages/hotel/includes/email/class_mail/class.smtp.php";
    $mail             = new PHPMailer();
    $mail->IsSMTP();
    $mail->FromName   = HOTEL_NAME;
    $mail->CharSet = "UTF-8";
    $mail->SMTPAuth   = true; 
    $mail->SMTPSecure = 'ssl';  
    $mail->Host       = EMAIL_INVOICE_SMTP;  
    $mail->Port       = EMAIL_INVOICE_PORT; 
    $mail->Username   = EMAIL_INVOICE;
    $mail->Password   = EMAIL_INVOICE_PASSWORD; 
    $mail->IsHTML(true);
    $mail->Subject    = $titleEmail; 
    $mail->AltBody    = "Hey, check out this new post on ";
    $mail->MsgHTML($contentEmail);
    $mail->AddAddress($addressEmail); 
    if(!$mail->Send())
    {
       DB::update('reservation',array('check_send_mail'=>'2','email_to_address'=>$addressEmail,'date_send_mail'=>Date_Time::to_orc_date(date('d/m/y'))),'id='.$reservation_id); 
    ?>   
    <script>
           alert("Email send error");
    </script>
    <?php
    }  
    else
    {
    ?>
    <script>
            alert("Email send success");
    </script>
    <?php
       DB::update('reservation',array('check_send_mail'=>'1','email_to_address'=>$addressEmail,'date_send_mail'=>Date_Time::to_orc_date(date('d/m/y'))),'id='.$reservation_id); 
    }       
}
?>