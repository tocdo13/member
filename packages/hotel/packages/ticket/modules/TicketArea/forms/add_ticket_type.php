<?php
class AddTicketTypeForm extends Form
{
    function AddTicketTypeForm()
    {
        Form::Form('AddTicketTypeForm');
    }
    
    function on_submit()
    {
        $portal_id = PORTAL_ID;
        DB::delete('ticket_area_type','portal_id = \''.$portal_id.'\' and ticket_area_id = '.Url::get('ticket_area_id') );
        if(Url::get('selected_ids') && is_array(Url::get('selected_ids')))
        {
            //System::debug(Url::get('selected_ids'));
            //exit();
            foreach(Url::get('selected_ids') as $key=>$value)
            {
                $row = array(
                                'ticket_id'=> $value,
                                'portal_id'=> $portal_id,
                                'ticket_area_id'=> Url::get('ticket_area_id'),
                            );
                //System::debug($row);
                //insert cac department dc active vao portal
                DB::insert('ticket_area_type',$row);
            }
        }
        Url::redirect_current();
    }
    
    function draw()
    {
        if(Url::get('cmd')=='add_ticket_type')
        {
            $this->map = array();
            $this->map['ticket_area_type'] = DB::fetch_all('Select * from ticket_area_type where portal_id = \''.PORTAL_ID.'\' and ticket_area_id = '.Url::get('ticket_area_id') );
            //Dung mang js de check dept nao dc active o ben layout
            $this->map['ticket_area_type_js'] = String::array2js($this->map['ticket_area_type']);
            //echo Url::get('portal_id');
            
            $this->map['ticket'] = DB::fetch_all('Select * from ticket order by ticket.id ');
            foreach($this->map['ticket'] as $key => $value)
            {
                $this->map['ticket'][$key]['desc'] = '';
                $service = DB::fetch_all('Select 
                                            ticket_service.*,
                                            (ticket_service.price - ticket_service_grant.discount_money) - (ticket_service.price - ticket_service_grant.discount_money) * ticket_service_grant.discount_percent/100 as price 
                                        from 
                                            ticket_service_grant inner join ticket_service on ticket_service.id = ticket_service_grant.ticket_service_id  
                                        where 
                                            ticket_service_grant.ticket_id = '.$key.' order by ticket_service.id ');
                //System::debug($service);
                foreach($service as $k => $v)
                {
                    $this->map['ticket'][$key]['desc'] .=  $v['name_1'].' ('.(System::display_number($v['price'])).'), ';
                }
            }
            //System::debug($this->map['ticket'] );
            //System::debug($this->map['department']);
            
            //$this->map['department_js'] = String::array2js($this->map['department']);  
               
            //System::debug($this->map['portal_department_js']);   
            $this->map['title'] = Portal::language('add_ticket_type');
            //System::debug($_REQUEST);
            //System::debug($this->map);
            $this->parse_layout('add_ticket_type', $this->map);
        }
    }
}

?>