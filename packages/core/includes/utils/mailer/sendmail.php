<?
////////////////////////////////////////////////
// Ban khong thay doi cac dong sau:

require("class.phpmailer.php");
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SetLanguage("vn", "");
$mail->Host     = "localhost";
$mail->SMTPAuth = true;

////////////////////////////////////////////////
// Ban hay sua cac thong tin sau cho phu hop

$mail->Username = "email@domain.com";				// SMTP username
$mail->Password = "xxxxxxxxxxx"; 				// SMTP password

$mail->From     = "email@domain.com";				// Email duoc gui tu???
$mail->FromName = "From Name";					// Ten hom email duoc gui
$mail->AddAddress("emailnhan@domain.com","Portal");	 	// Dia chi email va ten nhan
$mail->AddReplyTo("email@domain.com","Information");		// Dia chi email va ten gui lai

$mail->IsHTML(true);						// Gui theo dang HTML

$mail->Subject  =  "Chu de Email";				// Chu de email
$mail->Body     =  "Day la noi dung <b>cua Email</b>";		// Noi dung html


if(!$mail->Send())
{
   echo "Email chua duoc gui di! <p>";
   echo "Loi: " . $mail->ErrorInfo;
   exit;
}
echo "Email da duoc gui!";
?>