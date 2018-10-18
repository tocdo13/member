<?php
function send_mail_invoice_resent()
{
    if(Url::get('traveller_id'))
    {
        require 'packages/hotel/includes/email/room/invoice/default_invoice.php';
        require 'packages/hotel/includes/email/room/invoice/invoice.php';
        $folio_id = Url::get('folio_id');
        $traveller_id = Url::get('traveller_id');
        $itemSend = setFolioTraveller($traveller_id,$folio_id);
        
        $addressEmail =Url::get('email');
        $contentEmail = contentEmail($itemSend);
        sendMail($addressEmail,$contentEmail,$folio_id);
    }
    if(Url::get('reservation_id'))
    {
        require 'packages/hotel/includes/email/room/group_invoice/default_group_invoice.php';
        require 'packages/hotel/includes/email/room/group_invoice/group_invoice.php';
        $folio_id = Url::get('folio_id');
        $reservation_id = Url::get('reservation_id');
        $invoice_group = setGroupInvoice($reservation_id,$folio_id);
        $contentEmail = ContentGroupEmail($invoice_group);
        $addressEmail = Url::get('email');
        sendMail($addressEmail,$contentEmail,$folio_id);
    }
        
}
function sendMail($addressEmail,$contentEmail,$folio_id)
{
    require "packages/hotel/includes/email/class_mail/class.phpmailer.php";
    require "packages/hotel/includes/email/class_mail/class.smtp.php";
    $mail             = new PHPMailer();
    $mail->IsSMTP(); 
    $mail->Host       = "smtp.zoho.com"; 
    $mail->FromName   = HOTEL_NAME;
    $mail->SMTPDebug  = 1;
    $mail->CharSet = "UTF-8";
    $mail->SMTPAuth   = true; 
    $mail->SMTPSecure = 'ssl';  
    $mail->Host       = "smtp.zoho.com";  
    $mail->Port       = 465; 
    $mail->Username   = EMAIL_INVOICE;
    $mail->Password   = EMAIL_INVOICE_PASSWORD; 
    //$mail->IsHTML(true);
    $mail->Subject    = "Hóa Đơn Tiền Phòng"; 
    $mail->AltBody    = "Hey, check out this new post on ";
    $mail->MsgHTML($contentEmail);
    $mail->AddAddress($addressEmail); 
    if(!$mail->Send())
    {
       DB::update('folio',array('check_send_mail'=>'2'),'id='.$folio_id);
    }  
    else
    {
       DB::update('folio',array('check_send_mail'=>'1'),'id='.$folio_id);
       
       //echo "Invoice sent!"; 
    } 
}
?>