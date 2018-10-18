<?php
    function SendMailBar()
    {
        require_once 'packages/hotel/includes/email/bar/default_bar.php';
        require_once 'packages/hotel/includes/email/bar/form_bar.php';
        $item_invoice = BarInvoiceEmailForm();
        $content = BarDefaulEmail($item_invoice);
        
        if(!file_exists('packages/hotel/includes/Email/bar/files'))
        {
            mkdir('packages/hotel/includes/Email/bar/files');
        }
        $check_edit = DB::fetch('select check_edit from bar_reservation where id='.Url::get('id'));
        if($check_edit['check_edit']==1 && BAR_INVOICE==1)
        {
             file_put_contents('packages/hotel/includes/Email/bar/files/bar_'.Url::get('id').'_'.time().'.text',$content);
             DB::update('bar_reservation',array('check_edit'=>'0'),'id='.Url::get('id'));
        }   
        
    }
    
    function SendMailInvoiceBar()
    {
        require_once "packages/hotel/includes/email/class_mail/class.phpmailer.php";
        require_once "packages/hotel/includes/email/class_mail/class.smtp.php";
                
        if(EMAIL_INVOICE_CREART !='')
        {
            if(file_exists(ROOT_PATH_EMAIL.'packages/hotel/includes/Email/bar/files'))
            {
                define('PATH_EMAIL_BAR', 'packages/hotel/includes/Email/bar/files/');
                $files = array();
                $dir = opendir(ROOT_PATH_EMAIL.PATH_EMAIL_BAR);
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
                        ($file =fopen(ROOT_PATH_EMAIL.PATH_EMAIL_BAR.$v,'r+'));
                        $content='';
                        while(!feof($file))
                        {
                            ($content .=  fgets($file));
                        }
                        (fclose($file));
                        $mail             = new PHPMailer();
                        $mail->IsSMTP(); 
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
                        $mail->Subject    = "Hóa Đơn Nhà Hàng"; 
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
                           unlink(ROOT_PATH_EMAIL.PATH_EMAIL_BAR.$v);
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
