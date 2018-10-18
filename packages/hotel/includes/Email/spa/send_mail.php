<?php
    function SendMailMassage()
    {
        require_once 'packages/hotel/includes/email/spa/default_spa.php';
        require_once 'packages/hotel/includes/email/spa/form_spa.php';
        
        $item_invoice = MassageInvoiceEmailForm();
        $content = MassageDefaulEmail($item_invoice);
        
        if(!file_exists('packages/hotel/includes/Email/spa/files'))
        {
            mkdir('packages/hotel/includes/Email/spa/files');
        }
        $check_edit = DB::fetch('select check_edit from MASSAGE_RESERVATION_ROOM where id='.Url::get('id'));
        if($check_edit['check_edit']==1)
        {
            file_put_contents('packages/hotel/includes/Email/spa/files/spa_'.Url::get('id').'_'.time().'.text',$content);
            DB::update('MASSAGE_RESERVATION_ROOM',array('check_edit'=>'0'),'id='.Url::get('id'));
        }   
        
        
    }
    function SendMailInvoiceMassage()
    {
        require_once "packages/hotel/includes/email/class_mail/class.phpmailer.php";
        require_once "packages/hotel/includes/email/class_mail/class.smtp.php";
        if(EMAIL_INVOICE_CREART!='' && SPA_INVOICE==1)
        {
            if(file_exists(ROOT_PATH_EMAIL.'packages/hotel/includes/Email/spa/files'))
            {
                define('PATH_EMAIL_SPA', 'packages/hotel/includes/Email/spa/files/');
                $files = array();
                $dir = opendir(ROOT_PATH_EMAIL.PATH_EMAIL_SPA);
                while ($f = readdir($dir)) 
                {
                    if (eregi("\.text", $f))
                    array_push($files, $f);
                }
                closedir($dir);
                if(!empty($files))
                {
                    $i=1;
                    foreach($files as $k => $v)
                    {
                        if(++$i>3)
                            break;
                        $file =fopen(ROOT_PATH_EMAIL.PATH_EMAIL_SPA.$v,'r+');
                        $content='';
                        while(!feof($file))
                        {
                            $content .=  fgets($file);
                        }
                        fclose($file);  
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
                        $mail->Subject    = "Hóa Đơn SPA"; 
                        $mail->AltBody    = "Hey, check out this new post on ";
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
                            unlink(ROOT_PATH_EMAIL.PATH_EMAIL_SPA.$v);
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