<?php
class WaitingBookList extends Form
{
    function WaitingBookList()
    {
        Form::Form('WaitingBookList');
        $this->link_css(Portal::template('hotel').'/css/style.css');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');  
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');          
    }
    function draw()
    {
        $cond = '1=1 ';
        $this->map = array();
        $this->map['date_from'] = Url::get('date_from')?Url::get('date_from'):date('1/m/Y');
        $_REQUEST['date_from'] = $this->map['date_from'];
        $this->map['date_to'] = Url::get('date_to')?Url::get('date_to'):date('t/m/Y');
        $_REQUEST['date_to'] = $this->map['date_to'];
        
        $cond .= ' and waiting_book.portal_id =\''.PORTAL_ID.'\' ';
        $cond .= ' and waiting_book.departure_date<= \''.Date_Time::to_orc_date($this->map['date_to']).'\'';
        $cond .= ' and waiting_book.arrival_date>= \''.Date_Time::to_orc_date($this->map['date_from']).'\'';
        if(Url::get('list_booked'))
            $cond .=' AND waiting_book.status = 1 ';
        elseif(Url::get('list_confirm')){
            $cond .=' AND waiting_book.status = 0 AND waiting_book.confirm_date < \''.Date('d-M-y').'\' ';
        } 
        elseif(Url::get('list_waiting'))
            $cond .=' AND waiting_book.status = 0 AND waiting_book.confirm_date >= \''.Date('d-M-y').'\' ';
        $sql = 'SELECT 
                     waiting_book.id, 
                     TO_CHAR((waiting_book.arrival_date),\'dd/mm/yyyy\') AS arrival_date,
                     TO_CHAR((waiting_book.departure_date),\'dd/mm/yyyy\') AS departure_date,
                     waiting_book.confirm_date,
                     waiting_book.note,
                     waiting_book.contact_name,
                     waiting_book.number_room,
                     waiting_book.status,
                     waiting_book.content_booking,
                     waiting_book.telephone,
                     waiting_book.code_booking,
                     waiting_book.payment_method,
                     customer.name AS customer_name   
                FROM
                    waiting_book
                    LEFT JOIN customer ON customer.id = waiting_book.customer
                WHERE
                    '.$cond.'
                ORDER BY
                    CASE 
                    WHEN waiting_book.arrival_date> \''.Date('d-M-y').'\' 
                    THEN 0 ELSE 1 END,  
                    waiting_book.arrival_date ';
          //system::debug($sql);                 
          $report -> list_customer = DB::fetch_all($sql);
          foreach ($report ->list_customer as $key => $value)
          {
            if($report ->list_customer[$key]['status']!=1)
                $report ->list_customer[$key]['status']=' 
                        <input type="button" id="reservation_'.$report ->list_customer[$key]['id'].'"  onclick="reservation_book('.$report ->list_customer[$key]['id'].');" value="Booking" />';
            else
                $report ->list_customer[$key]['status'] = 'Booked';         
          }
          $sql_price = '
                        SELECT
                            ROW_NUMBER() OVER(ORDER BY room_level.name DESC) as id,
                            room_level.name,
                            room_level.price,
                            count(room.id) as total_room
                        FROM
                            room
                            inner join room_level on room_level.id = room.room_level_id
                        WHERE
                            room_level.is_virtual != 1
                        GROUP BY
                            room_level.name,
                            room_level.price
                        ORDER BY
                            room_level.name DESC
                        ';
        $price = DB::fetch_all($sql_price);            
        
        require_once 'packages/hotel/packages/reception/modules/includes/reservation.php';
		$r_r_id = '';
        $arrival_time = Date_Time::to_time($this->map['date_from']);
        $departure_time = Date_Time::to_time($this->map['date_to']);
        $extra_cond =' 1>0 ';
		$room_levels = check_availability_new($r_r_id,$extra_cond,$arrival_time,$departure_time);
        unset($room_levels[100000]);
        
        $this->parse_layout('list',$this->map+array('customers'=>$report ->list_customer ,'items' =>$room_levels,'price'=>$price));            
    }   
}
      

?>
