<?php
    define( 'ROOT_PATH', strtr(dirname( __FILE__ ) ."/",array('\\'=>'/')));
    set_include_path(ROOT_PATH);
    require_once 'packages/core/includes/system/config.php';
    function get_member_discount()
   	{
   	    if($_REQUEST['member']){
            $member = $_REQUEST['member'];
   	    }
        /** kết nối database **/
        $conn = oci_connect('standard', 'hotel2014', 'localhost/XE');
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        $data_member = array();
        /** lấy điều kiện cho dữ liệu trả về **/
        $sql = oci_parse($conn, 'SELECT traveller.id,
                                    traveller.member_level_id
                                    FROM 
                                    traveller
                                    WHERE 
                                    traveller.member_code='.$member.'');
        oci_execute($sql);
        while($record = oci_fetch_array($sql, OCI_ASSOC+OCI_RETURN_NULLS)) {
            $data_member['info_member'] = $record;
        }
        if(isset($data_member['info_member']))
        $n = sizeof($data_member['info_member']);
        else
        $n=0;
        if($n<1){
            $data_member['info_member']['no_member'] = 0;
        }else{
            $data_member['info_member']['no_member'] = 1;
            $data_member['info_member']['detail'] = DB::fetch("
                    SELECT 
        				traveller.id,
        				traveller.first_name,
        				traveller.last_name,
        				traveller.gender,
        				traveller.nationality_id,
        				to_char(traveller.birth_date,'DD/MM/YYYY') as birth_date,
        				traveller.address,
        				traveller.phone,
        				traveller.note,
        				traveller.traveller_level_id,
        				traveller.passport,
        				traveller.email,
                        traveller.member_code,
        				reservation_traveller.visa_number,
        				reservation_traveller.expire_date_of_visa,
        				country.name_".Portal::language()." as nationality_name,
        				rownum
        			FROM
        				traveller
                        left outer join reservation_traveller on traveller.id = reservation_traveller.traveller_id
        				left outer join country on country.id = traveller.nationality_id   
        			WHERE 
        				traveller.member_code = ".$member."
        			ORDER BY traveller.id DESC
            ");
        }
        $date = getdate(); $to_day = $date['mday']."-".convert_month_to_orcl($date['mon'])."-".$date['year'];
        $data_member['info_member']['create_member_date'] = $to_day;  
        return $data_member;
   	}
    function get_member_info(){
        if($_REQUEST['member_level_id']){
            $member_level_id = $_REQUEST['member_level_id'];
   	    }
        if($_REQUEST['date']){
            $to_day = $_REQUEST['date'];
   	    }
        /** kết nối database **/
        $conn = oci_connect('standard', 'hotel2014', 'localhost/XE');
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        $data_member = array();
        /** lấy điều kiện cho dữ liệu trả về **/
            $stid = oci_parse($conn, "SELECT 
                                        member_level_discount.*,
                                        member_level.name,
                                        member_discount.title,
                                        member_discount.description 
                                    FROM 
                                        member_level_discount
                                        inner join member_level on member_level.id = member_level_discount.member_level_id
                                        inner join member_discount on member_discount.code = member_level_discount.member_discount_code
                                    WHERE
                                        member_level.id=$member_level_id AND ((member_level_discount.start_date<='$to_day' AND member_level_discount.end_date>='$to_day'))
                                    ORDER BY member_level_discount.id DESC");
            oci_execute($stid);
            $test = false;
            while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
                $data_member[$row['id']] = $row;
                $test=true;
            }
            if($test==true){
                $data_member['info_member']['no_discount'] = 1;
            }else{
                $data_member['info_member']['no_discount'] = 0;
            }  
        return $data_member;
    }
    function get_point_user()
   	{
   	    require_once 'packages/hotel/includes/member.php';
   	    if($_REQUEST['member']){
            $member = $_REQUEST['member'];
   	    }
        /** kết nối database **/
        $conn = oci_connect('standard', 'hotel2014', 'localhost/XE');
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        $data_member = array();
        /** lấy điều kiện cho dữ liệu trả về **/
        $sql = oci_parse($conn, 'SELECT traveller.id,
                                    traveller.point_user
                                    FROM 
                                    traveller
                                    WHERE 
                                    traveller.member_code='.$member.'');
        oci_execute($sql);
        while($record = oci_fetch_array($sql, OCI_ASSOC+OCI_RETURN_NULLS)) {
            $data_member['point_user'] = $record;
        } 
        return $data_member;
   	}
    
    function get_passport(){
        if($_REQUEST['passport']){
            $passport = $_REQUEST['passport'];
        }
        /** kết nối database **/
        $conn = oci_connect('standard', 'hotel2014', 'localhost/XE');
        if (!$conn) {
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }
        $data_member = array();
        /** lấy điều kiện cho dữ liệu trả về **/
        $sql = oci_parse($conn, 'SELECT traveller.id
                                    FROM 
                                    traveller
                                    WHERE 
                                    traveller.passport=\''.$passport.'\'');
        oci_execute($sql);
        $test=false;
        while($record = oci_fetch_array($sql, OCI_ASSOC+OCI_RETURN_NULLS)) {
            $data_member['passport'] = $record;
            $test=true;
        }
        if($test==true){
            $data_member['passport']['check'] = 1;
        }else{
            $data_member['passport']['check'] = 0;
        }
        return $data_member;
    }
    /** trả về dữ liệu cho hàm gọi **/
    switch($_REQUEST['data'])
    {
        case "get_member_discount":
        {
            echo json_encode(get_member_discount()); break;
        }
        case "get_member_info":
        {
            echo json_encode(get_member_info()); break;
        }
        case "get_point_user":
        {
            echo json_encode(get_point_user()); break;
        }
        case "get_passport":
        {
            echo json_encode(get_passport()); break;
        }
        default: echo '';break;
    }
    
    
    
    
    function convert_month_to_orcl($month){
        if($month==1){
            $month="JAN"; return $month;
        }elseif($month==2){
            $month="FEB"; return $month;
        }elseif($month==3){
            $month="MAR"; return $month;
        }elseif($month==4){
            $month="APR"; return $month;
        }elseif($month==5){
            $month="MAY"; return $month;
        }elseif($month==6){
            $month="JUN"; return $month;
        }elseif($month==7){
            $month="JUL"; return $month;
        }elseif($month==8){
            $month="AUG"; return $month;
        }elseif($month==9){
            $month="SEP"; return $month;
        }elseif($month==10){
            $month="OCT"; return $month;
        }elseif($month==11){
            $month="NOV"; return $month;
        }else{
            $month="DEC"; return $month;
        }
    }
?>