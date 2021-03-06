﻿<?php
function sendMailCheckOut()
{           
       $folio_checkout =DB::fetch_all('
               SELECT 
                    folio.id,
                    folio.reservation_traveller_id,
                    traveller.email as email,
                    folio.check_send_mail
               FROM
                    folio
                    LEFT JOIN reservation_room ON reservation_room.id = folio.reservation_room_id
                    LEFT JOIN reservation_traveller ON folio.reservation_traveller_id = reservation_traveller.id
                    LEFT JOIN traveller ON traveller.id = reservation_traveller.traveller_id
               WHERE 
                    folio.check_send_mail =0
                    AND reservation_room.status=\'CHECKOUT\'  
                    AND folio.customer_id IS NULL 
                    AND traveller.email IS NOT NULL
                    
       '); 
       $group_folio = DB::fetch_all('
               SELECT 
                    folio.id,
                    folio.reservation_id,
                    traveller.email as email
               FROM
                    folio
                    INNER JOIN reservation_traveller ON folio.reservation_traveller_id = reservation_traveller.id
                    INNER JOIN traveller ON reservation_traveller.traveller_id = traveller.id
                    INNER JOIN reservation_room ON folio.reservation_id = reservation_room.reservation_id                    
               WHERE 
                    folio.check_send_mail =0 
                    AND reservation_room.status=\'CHECKOUT\'    
                    AND folio.customer_id IS NOT NULL           
                    AND traveller.email IS NOT NULL  
       ');
       if(!empty($group_folio))
       {
           require_once ROOT_PATH_EMAIL.'packages/hotel/includes/email/room/group_invoice/default_group_invoice.php';
           require_once ROOT_PATH_EMAIL.'packages/hotel/includes/email/room/group_invoice/group_invoice.php';
           foreach($group_folio as $k => $v)
           {
                $folio_id = $v['id'];
                $reservation_id = $v['reservation_id'];
                $invoice_group = setGroupInvoice($reservation_id,$folio_id);
                $contentEmail = ContentGroupEmail($invoice_group);
                $addressEmail = $v['email'];
                sendMail($addressEmail,$contentEmail,$folio_id);   
           }
       }
       if(!empty($folio_checkout))
       {
           require_once ROOT_PATH_EMAIL.'packages/hotel/includes/email/room/invoice/default_invoice.php';
           require_once ROOT_PATH_EMAIL.'packages/hotel/includes/email/room/invoice/invoice.php';           
           foreach($folio_checkout as $key => $value)
           {
               $folio_id = $value['id'];
               $traveller_id = $value['reservation_traveller_id'];
               $itemSend= setFolioTraveller($traveller_id,$folio_id);
               $addressEmail =$value['email'];
               $contentEmail = contentEmail($itemSend);
               sendMail($addressEmail,$contentEmail,$folio_id);
           } 
       }
       
/** End Send Mail **/
}
function sendMail($addressEmail,$contentEmail,$folio_id)
{
    require_once ROOT_PATH_EMAIL."packages/hotel/includes/email/class_mail/class.phpmailer.php";
    require_once ROOT_PATH_EMAIL."packages/hotel/includes/email/class_mail/class.smtp.php";
    $mail             = new PHPMailer();
    $mail->IsSMTP(); 
    $mail->Host       = "stmp.gmail.com"; 
    $mail->FromName   = HOTEL_NAME;
    $mail->SMTPDebug  = 1;
    $mail->CharSet = "UTF-8";
    $mail->SMTPAuth   = true; 
    $mail->SMTPSecure = 'ssl';  
    $mail->Host       = EMAIL_INVOICE_SMTP;  
    $mail->Port       = EMAIL_INVOICE_PORT; 
    $mail->Username   = EMAIL_INVOICE;
    $mail->Password   = EMAIL_INVOICE_PASSWORD; 
    $mail->IsHTML(true);
    $mail->Subject    = "Hóa Đơn Tiền Phòng"; 
    $content_invoice = EMAIL_INVOICE_CONTENT.'<br><br>'.$contentEmail;
    $mail->MsgHTML($content_invoice);
    $mail->AddAddress($addressEmail); 
    if(!$mail->Send())
    { 
       DB::update('folio',array('check_send_mail'=>'2'),'id='.$folio_id);
    }  
    else
    {
       DB::update('folio',array('check_send_mail'=>'1'),'id='.$folio_id);
    }       
}    
?>    