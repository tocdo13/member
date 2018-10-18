<?php
/** BÁO CÁO KHÁCH ĂN SÁNG
 * lấy khoảng thời gian được ăn sáng setting theo hệ thống
 * Dayuse: tinh 1 lượt ăn sáng nếu giờ đến nằm trong khoảng thời gian ăn sáng - Đổi phòng dayuse - lấy ra chặng sau!
 * phòng ở dài ngày.
 **/
class BreakfastReportNewForm extends Form
{
	function BreakfastReportNewForm()
	{
		Form::Form('BreakfastReportNewForm');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
	}
	function draw()
	{
	   $this->map['portal_id_list'] = array(''=>Portal::language('all'))+String::get_list(Portal::get_portal_list());
       $this->map['portal_id'] = Url::get('portal_id')?Url::get('portal_id'):'';
       
       $this->map['from_date'] = Url::get('from_date')?Url::get('from_date'):date('d/m/Y');
       $this->map['to_date'] = Url::get('to_date')?Url::get('to_date'):date('d/m/Y');
       
       $this->map['line_per_page'] = Url::get('line_per_page')?Url::get('line_per_page'):32;
       $this->map['no_of_page'] = Url::get('no_of_page')?Url::get('no_of_page'):50;
       $this->map['start_page'] = Url::get('start_page')?Url::get('start_page'):1;
       
       $from_time = Date_Time::to_orc_date($this->map['from_date']);
       $to_time = Date_Time::to_orc_date($this->map['to_date']);
       
              
       $cond = ' (reservation_room.status=\'CHECKIN\' OR reservation_room.status=\'CHECKOUT\' OR reservation_room.status=\'BOOKED\') ';
       $cond .= Url::get('portal_id')?' AND (reservation.portal_id=\''.Url::get('portal_id').'\')':'';
       $cond .= "AND ( voucher_breakfast.in_date<='".$to_time."' AND voucher_breakfast.in_date>='".$from_time."' )";
        $cond .= Url::get('customer_name')?"AND customer.name = '".Url::get('customer_name')."' " :"" ; 
       
       $sql = "SELECT 
                voucher_breakfast.id,
                voucher_breakfast.voucher_id,
                to_char(voucher_breakfast.in_date,'DD/MM/YYYY') as in_date,
                voucher_breakfast.barcode,
                voucher_breakfast.reservation_room_id,
                voucher_breakfast.guest_name,
                to_char(voucher_breakfast.date_use,'DD/MM/YYYY') as date_use,
                voucher_breakfast.status,
                voucher_breakfast.real_use_date,
                voucher_breakfast.is_child,
                voucher_breakfast.reprint,
                customer.name as customer_name,
                room.name as room_name,
                reservation.id as reservation_id
                FROM voucher_breakfast INNER JOIN reservation_room ON voucher_breakfast.reservation_room_id = reservation_room.id
                     INNER JOIN reservation ON reservation.id = reservation_room.reservation_id
                     INNER JOIN room ON room.id = reservation_room.room_id
                     INNER JOIN customer ON customer.id = reservation.customer_id 
                WHERE ".$cond." ORDER BY  room.name,  voucher_breakfast.guest_name   
                ";
       $result = DB::fetch_all($sql);
       $this->map['items'] = $result;
       $this->parse_layout('report',$this->map);
      //System::debug($items); 
	}
    
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
    
}
?>
