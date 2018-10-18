<?php
class ReportEmailForm extends Form
{
    function ReportEmailForm()
    {
        Form::Form('ReportEmailForm');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_css('packages/core/includes/js/jquery/window/css/jquery.window.4.04.css');
		$this->link_js('packages/core/includes/js/jquery/window/jquery.window.js');
		$this->link_js('packages/core/includes/js/jquery/ui/jquery.ui.widget.js');
		$this->link_js('packages/core/includes/js/jquery/ui/jquery.ui.mouse.js');
		$this->link_js('packages/core/includes/js/jquery/ui/jquery.ui.draggable.js');
		$this->link_js('packages/core/includes/js/jquery/ui/jquery.ui.resizable.js');
		$this->link_js('packages/core/includes/js/picker.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.mask.min.js');
		$this->link_js('packages/hotel/includes/js/suggest.js');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_js('packages/hotel/packages/reception/modules/includes/reservation_table.js');
		$this->link_js('packages/hotel/includes/js/ajax.js');
        $this->link_js('packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js');
		$this->link_css("packages/hotel/skins/default/css/jquery.windows-engine.css");                
    }
    function draw()
    {
        $this->map=array();
        $this->map['portal_id_list'] = array(''=>Portal::language('all')) + String::get_list(Portal::get_portal_list());
        $this->map['type_mail_list']=array(
                          'invoice'=>Portal::language('invoice')
                          ,'booking_confirm'=>Portal::language('booking_confirm')			
                    		);
        $this->map['email_status_list']=array(''=>Portal::language('all'),
                          '0'=>Portal::language('pending'),
                          '1'=>Portal::language('sent'),
                          '2'=>Portal::language('error')			
                    		);
        $cond=' 1=1';
                        
/** -----------------------------------------------------Booking Confirm------------------------------------------------------------------ **/          
        if(Url::get('type_mail')=="booking_confirm")
        {
            if(Url::get('portal_id'))
            {
                $portal_id = Url::get('portal_id');
                $cond .= ' AND reservation.portal_id=\''.$portal_id.'\' ';
            }
            if(Url::get('email_status')!='')
                $cond .= " AND reservation.check_send_mail =".Url::get('email_status');
            if(Url::get('date_from')=='' || Url::get('date_to')=='')
            {
                $date_from = Date_Time::to_orc_date(date('d/m/y'));
                $date_to  = Date_Time::to_orc_date(date('d/m/y'));
                $cond .= ' AND reservation.date_send_mail >=\''.$date_from.'\' AND reservation.date_send_mail <= \''.$date_to.'\' ';
            }
            else
            {
                $date_from = Date_Time::to_orc_date(Url::get('date_from'));
                $date_to  = Date_Time::to_orc_date(Url::get('date_to'));
                //system::debug($date_from);
               // system::debug($date_to);
                $cond .= ' AND reservation.date_send_mail >=\''.$date_from.'\' AND reservation.date_send_mail <= \''.$date_to.'\' ';
            }
            $sql = 'SElECT 
                                                        reservation.id,
                                                        reservation.CUT_OF_DATE as dealine_deposit,
                                                        CASE
                                                            WHEN reservation.edited_bcf=0 or reservation.edited_bcf is null
                                                            Then reservation.note
                                                            ELSE reservation.NOTE_BOOKING_CONFIRM
                                                        END as NOTE, 
                                                        reservation.tour_id,
                                                        reservation.customer_id,
                                                        reservation.booking_code as booking_code_lt,
                                                        reservation.date_send_mail,
                                                        reservation.check_send_mail,
                                                        reservation.email_to_address,
                                                        BCF_PAYMENT,
                                                        customer.id as customer_id,
                                                        customer.NAME as ctm_name, 
                                                        customer.PHONE as ctm_phone,
                                                        customer.email as email
                                                   FROM reservation
                                                        left outer join customer on customer.id = reservation.customer_id
                                                   WHERE '.$cond.' AND customer.email is not null
                                                   ORDER BY reservation.id DESC' 
                                                        
                               ;
           $this->map['booking'] = DB::fetch_all($sql);                   
            //system::debug($sql);                   
          
           
           // system::debug($this->map['booking']);
                              
            $this->parse_layout('default_booking_cf',$this->map);   
        }
/** -----------------------------------------------------INVOICE------------------------------------------------------------------ **/        
        else
        {
            if(Url::get('portal_id'))
            {
                $portal_id = Url::get('portal_id');
                $cond .= ' AND folio.portal_id=\''.$portal_id.'\' ';
            }
            if(Url::get('email_status')!='')
                $cond .= " AND folio.check_send_mail =".Url::get('email_status');
            if(Url::get('date_from')=='' || Url::get('date_to')=='')
            {
                $date_from = Date_Time::to_time(date('d/m/y'));
                $date_to = Date_Time::to_time(date('d/m/y'));
            }
            else
            {
                $date_from = Date_Time::to_time(Url::get('date_from'));
                $date_to = Date_Time::to_time(Url::get('date_to'));
            }
                
            $sql = '
                    SELECT 
                           folio.id,
                           folio.customer_id,
                           folio.reservation_id,
                           to_char((from_unixtime(folio.create_time)),\'dd/mm/yyyy HH:mm \') AS create_time,
                           folio.create_time as create_time_2,
                           reservation_traveller.id AS traveller_id,
                           traveller.id as travellerid,
                           traveller.phone,
                           CASE 
                               WHEN folio.customer_id IS NULL THEN 0
                               ELSE 1
                           END type_folio,   
                           folio.check_send_mail,
                           CONCAT(CONCAT(traveller.first_name,\' \'),traveller.last_name) AS fullname,
                           traveller.email as email 
                    FROM folio
                         INNER JOIN reservation_traveller ON folio.reservation_traveller_id = reservation_traveller.id
                         INNER JOIN traveller ON reservation_traveller.traveller_id = traveller.id
                    where '.$cond.' AND(folio.create_time>= \''.$date_from.'\' AND folio.create_time < \''.($date_to+86400).'\' )
                        AND traveller.email is not null
                    ORDER BY id DESC';      
            $this->map['folio'] = DB::fetch_all($sql);
            
            $this->parse_layout('default_folio',$this->map);   
        }    
    }    
}
?>