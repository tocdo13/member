<?php
	date_default_timezone_set('Asia/Saigon');//Define default time for global system
	define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
    require_once ROOT_PATH.'packages/core/includes/system/config.php';
    
    set_time_limit(-1);
    
    
    require_once("packages/hotel/includes/Email/class_mail_new/lib/class.pop3.php");
    require_once("packages/hotel/includes/Email/class_mail_new/lib/class.smtp.php");
    require_once("packages/hotel/includes/Email/class_mail_new/lib/class.phpmailer.php");
    // Khai báo tạo PHPMailer
    $mail = new PHPMailer();
    //Khai báo gửi mail bằng SMTP
    $mail->IsSMTP();
    //Tắt mở kiểm tra lỗi trả về, chấp nhận các giá trị 0 1 2
    // 0 = off không thông báo bất kì gì, tốt nhất nên dùng khi đã hoàn thành.
    // 1 = Thông báo lỗi ở client
    // 2 = Thông báo lỗi cả client và lỗi ở server
    $mail->SMTPDebug  = 2;
    $mail->CharSet    = "UTF-8"; 
    $mail->Debugoutput = "html"; // Lỗi trả về hiển thị với cấu trúc HTML
    $mail->Host       = "mail.sunrisecentralhotel.com"; //host smtp để gửi mail
    $mail->Port       = 465; // cổng để gửi mail
    $mail->SMTPSecure = "ssl"; //Phương thức mã hóa thư - ssl hoặc tls
    $mail->SMTPAuth   = true; //Xác thực SMTP
    $mail->Username   = "info@sunrisecentralhotel.com"; // Tên đăng nhập tài khoản Gmail
    $mail->Password   = "sunrise3*"; //Mật khẩu của gmail
    $mail->SetFrom("info@sunrisecentralhotel.com", "Sunrice"); // Thông tin người gửi
    $mail->AddReplyTo("info@sunrisecentralhotel.com","Sunrice");// Ấn định email sẽ nhận khi người dùng reply lại.
    $mail->AddAddress('manhnv@tcv.vn');//Email của người nhận
    $mail->Subject    = "Invoice Room Charge";  //Tiêu đề của thư
    //$content_invoice = EMAIL_INVOICE_CONTENT.'<br><br>'.$contentEmail;
    $mail->MsgHTML('<p>Tester</p>'); //Nội dung của bức thư.
    // $mail->MsgHTML(file_get_contents("email-template.html"), dirname(__FILE__));
    // Gửi thư với tập tin html
    $mail->AltBody    = "Invoice Room Charge";//Nội dung rút gọn hiển thị bên ngoài thư mục thư.
    //$mail->AddAttachment(ROOT_PATH_EMAIL.$value['images'],$today.'.jpg');//Tập tin cần attach
     
    if(!$mail->Send())
    { 
       System::debug($mail->ErrorInfo); 
    }  
    else
    {
       echo 'ok!';
    }   
?>
