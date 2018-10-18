<?php
class BookingConfirmWaitingBook extends Form
{
    function BookingConfirmWaitingBook()
    {
        Form::Form('BookingConfirmWaitingBook');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');  
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
    }
    function draw()
    {
        $this -> map = array();
        $this->map['payment_method_list']=array(''=>'-------',
                                                    'CASH'=>Portal::language('Cash'),
                                                    'CARD'=>Portal::language('Credit card'),
                                                    'BANK'=>Portal::language('Bank transfer'),
                                                    'COMPANY'=>Portal::language('Company Agency'),
                                                    'GUEST'=>Portal::language('By guest'),
                                                    'OTHERS'=>Portal::language('Others')			
                                                		);
        $booking_confirm = DB::fetch('
                                                         SELECT waiting_book.*,
                                                                customer.address as address,
                                                                customer.mobile as cphone,
                                                                customer.fax as cfax
                                                                ,customer.name as customer_name
                                                         FROM waiting_book 
                                                              LEFT JOIN customer ON customer.id = waiting_book.customer
                                                                
                                                         WHERE waiting_book.id='.Url::get('id'));
       //system::Debug($booking_confirm);die();
       $user_info = DB::fetch('SELECT party.id,party.name_1 as pname, party.phone as pphone FROM party WHERE user_id=\''.User::id().'\'');
        $this->map['waiting_information']= DB::fetch_all('SELECT   waiting_information.*,room_level.name as room_name,customer.name as customer_name
                                             FROM     waiting_information 
                                                      LEFT JOIN waiting_book ON waiting_information.waiting_book_id =  waiting_book.id
                                                      LEFT JOIN room_level ON room_level.id = waiting_information.room_type
                                                      LEFT JOIN customer ON customer.id = waiting_book.customer
                                              WHERE   waiting_information.waiting_book_id ='.Url::get('id')   
                                      );
        $this -> parse_layout('booking_cf',$booking_confirm+$user_info+$this->map);
    }
    
    function on_submit()
    {
        
        $row = array();
            $row['code_booking'] = $_REQUEST['booking_no'];
            $row['contact_name'] = $_REQUEST['contact_name'];
            //$row['content_booking'] = $_REQUEST['content_booking'];
            $row['deposit'] = $_REQUEST['need_deposit'];
            $row['before_date'] = Date_Time::to_orc_date($_REQUEST['before_date']);
            $row['note'] = $_REQUEST['note'];
            $row['payment_method'] = $_REQUEST['payment_method'];
            $row['telephone'] = $_REQUEST['telephone_contact'];
            if(isset($_REQUEST['confirm_feedback']))
            {
                $row['confirm_feedback'] = $_REQUEST['confirm_feedback'];
            }
        $customer = DB::fetch('SELECT customer.id
                                   FROM waiting_book 
                                   LEFT JOIN customer ON customer.id = waiting_book.customer
                                   WHERE waiting_book.id ='.Url::get('id').'
                                   ');    
        DB::update('waiting_book',$row,'id='.Url::get('id'));      
        unset($row);  
        $row = array();
        $row['mobile'] = $_REQUEST['customer_phone'];
        $row['fax'] = $_REQUEST['customer_fax'];
        $row['address'] = $_REQUEST['customer_address'];
        DB::update('customer',$row,'id=\''.$customer['id'].'\'');
    }
}
?>