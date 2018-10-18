<?php
/**
    CÁC HÀM DÙNG CHUNG TRONG PHẦN QUẢN LÝ THÀNH VIÊN VÀ TÍCH ĐIỂM
**/
?>
<?php
    function create_member_code(){ /** hàm tạo mã thành viên mới **/
        $date = getdate();
        $code = $date['year'].'000000';
        $max_code = DB::fetch("SELECT max(traveller.member_code) as id FROM traveller");
        if($max_code['id']>0){
            $year = substr($max_code['id'],0,4);
            if($year==$date['year']){
                $code = $max_code['id']+1;
            }
        }
        return $code;
    }
    function validate_member_code($member_code)
    {
        $member_code = str_replace('`','',$member_code);
        $member_code = str_replace('~','',$member_code);
        $member_code = str_replace('!','',$member_code);
        $member_code = str_replace('@','',$member_code);
        $member_code = str_replace('#','',$member_code);
        $member_code = str_replace('$','',$member_code);
        $member_code = str_replace('%','',$member_code);
        $member_code = str_replace('^','',$member_code);
        $member_code = str_replace('&','',$member_code);
        $member_code = str_replace('*','',$member_code);
        $member_code = str_replace('(','',$member_code);
        $member_code = str_replace(')','',$member_code);
        $member_code = str_replace('-','',$member_code);
        $member_code = str_replace('_','',$member_code);
        $member_code = str_replace('+','',$member_code);
        $member_code = str_replace('=','',$member_code);
        $member_code = str_replace('{','',$member_code);
        $member_code = str_replace('}','',$member_code);
        $member_code = str_replace('[','',$member_code);
        $member_code = str_replace(']','',$member_code);
        $member_code = str_replace('|','',$member_code);
        $member_code = str_replace('\\','',$member_code);
        $member_code = str_replace('\'','',$member_code);
        $member_code = str_replace('"','',$member_code);
        $member_code = str_replace(';','',$member_code);
        $member_code = str_replace(':','',$member_code);
        $member_code = str_replace('?','',$member_code);
        $member_code = str_replace('/','',$member_code);
        $member_code = str_replace('>','',$member_code);
        $member_code = str_replace('.','',$member_code);
        $member_code = str_replace('<','',$member_code);
        $member_code = str_replace(',','',$member_code);
        
        return $member_code;
    }
    function create_password_radom(){ /** tạo password tự động **/
        $password = rand(10000000,99999999);
        return $password;
    }
    /** hàm gửi mail **/
    function sent_mail_to($email_member,$content){
        require_once "packages/hotel/includes/email/class_mail/class.phpmailer.php";
        require_once "packages/hotel/includes/email/class_mail/class.smtp.php";
        $mail             = new PHPMailer();
        $mail->IsSMTP(); 
        $mail->Host       = "stmp.gmail.com"; 
        $mail->FromName   = HOTEL_NAME;
        $mail->SMTPDebug  = 1;
        $mail->CharSet = "UTF-8";
        $mail->SMTPAuth   = true; 
        $mail->SMTPSecure = 'ssl';  
        $mail->Host       = "smtp.gmail.com";  
        $mail->Port       = 465; 
        $mail->Username   = "manhnv@tcv.vn";
        $mail->Password   = "haitru1bang0"; 
        //$mail->IsHTML(true);
        $mail->Subject    = "Thông Tin Thành Viên"; 
        $mail->AltBody    = "";
        $contentEmail = $content;
        //system::debug($content_invoice);die();
        $mail->MsgHTML($contentEmail);
        $mail->AddAddress($email_member);
        if(!$mail->Send()){
            return false;
        }else{
            return true;
        }
    }
    
    
    /** end gửi mail **/
    /** ************************************************************ **/
    function insert_history($history_member_id,$payment_type_id,$price,$payment_id,$payment_point){
        $change_price_to_point = CHANGE_PRICE_TO_POINT; /** tỉ lệ quy đổi để tích lũy **/
        $change_point_to_price = CHANGE_POINT_TO_PRICE; /** tỉ lệ quy đổi để sử dụng **/
        if($payment_point==1){
            if($payment_type_id=='FOC'){ // thanh toán bằng thẻ tích điểm / tỉ lệ quy đổi để sử dụng
                $history = array(
                    'history_member_id'=>$history_member_id,
                    'payment_type_id'=>$payment_type_id,
                    'price'=>$price,
                    'change_price'=>$change_point_to_price,
                    'payment_id'=>$payment_id,
                    'type_point'=>'POINT_USER,',
                    'payment_type_point'=>'SUB'
                );
                DB::insert('history_member_detail',$history);
            }
        }else{
            $history = array(
                'history_member_id'=>$history_member_id,
                'payment_type_id'=>$payment_type_id,
                'price'=>$price,
                'change_price'=>$change_price_to_point,
                'payment_id'=>$payment_id
            );
            if($payment_type_id=='REFUND'){ // trả lại khách tiền thừa / tỉ lệ quy đổi để tích lũy
                $history['payment_type_point'] = 'SUB';
                $history['type_point'] = 'POINT_USER,POINT';
                DB::insert('history_member_detail',$history);
            }elseif(($payment_type_id=='CASH') OR ($payment_type_id=='CREDIT_CARD') OR ($payment_type_id=='BANK')){
                $history['payment_type_point'] = 'ADD';
                $history['type_point'] = 'POINT_USER,POINT';
                DB::insert('history_member_detail',$history);
            }else{
                $history['price'] = 0;
                $history['payment_type_point'] = '';
                $history['type_point'] = '';
                DB::insert('history_member_detail',$history);
            }
        }
    }
    /** ************************************************************** **/
    function point($payment_type,$price,$payment_point){
        $change_price_to_point = CHANGE_PRICE_TO_POINT; /** tỉ lệ quy đổi để tích lũy **/
        $change_point_to_price = CHANGE_POINT_TO_PRICE; /** tỉ lệ quy đổi để sử dụng **/
        $point_arr = array('point'=>0,'point_user'=>0);
        /** kiểm tra có phải thanh toán bằng thẻ không **/
        if($payment_point==1){/** nếu thanh toán bằng điểm **/
            if($payment_type=='FOC'){
                /** phương thức thanh toán là miễn phí - trừ point_user theo hệ số CHANGE_POINT_TO_PRICE **/
                // số điểm dùng để thanh toán
                $point_user = $price/$change_point_to_price;
                if(($price%$change_point_to_price)!=0){
                    
                    $arr_point_user = explode('.',$point_user);
                    $point_user = $arr_point_user[0]+1;
                }
                    $point_arr['point_user'] -= $point_user;
            }
        }else{/** thanh toán bình thường **/
            //số điểm được quy đổi để trả lại
            $point = $price/$change_price_to_point;
            if(($price%$change_price_to_point)!=0){
                $arr_point = explode('.',$point);
                $point = $arr_point[0];
            }
            if($payment_type=='REFUND'){/** hình thức trả lại - trừ điểm tích lũy và điểm sử dụng theo CHANGE_PRICE_TO_POINT **/
                    $point_arr['point_user'] -= $point; 
                    $point_arr['point'] -= $point;
                
            }elseif(($payment_type=='CASH') OR ($payment_type=='CREDIT_CARD') OR ($payment_type=='BANK')){/** các hình thức khác trừ DEBIT **/
                    $point_arr['point_user'] += $point; 
                    $point_arr['point'] += $point;
            }
        }
        return $point_arr;
    }
    /** ************************************************************************ **/
    /** *********************************************************** ***/
?>