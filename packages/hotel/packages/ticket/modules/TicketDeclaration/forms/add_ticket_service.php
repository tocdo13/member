<?php
class AddTicketServiceForm extends Form
{
    function AddTicketServiceForm()
    {
        Form::Form('AddTicketServiceForm');
    }
    
    function on_submit()
    {
        //System::debug($_REQUEST);
        $portal_id = PORTAL_ID;
        DB::delete('ticket_service_grant','portal_id = \''.$portal_id.'\' and ticket_id = '.Url::get('ticket_id') );
        if(Url::get('selected_ids') && is_array(Url::get('selected_ids')))
        {
            //System::debug(Url::get('selected_ids'));
            //exit();
            foreach(Url::get('selected_ids') as $key=>$value)
            {
                $row = array(
                                'ticket_service_id'=> $value,
                                'portal_id'=> $portal_id,
                                'ticket_id'=> Url::get('ticket_id'),
                                'discount_money'=>Url::get('discount_money_'.$value)?System::calculate_number(Url::get('discount_money_'.$value)):0,
                                'discount_percent'=>Url::get('discount_percent_'.$value)?System::calculate_number(Url::get('discount_percent_'.$value)):0,
                            );
                //System::debug($row);
                //insert cac department dc active vao portal
                DB::insert('ticket_service_grant',$row);
            }
        }
        Url::redirect_current();
    }
    
    function draw()
    {
        if(Url::get('cmd')=='add_ticket_service')
        {
            $this->map = array();
            $this->map['ticket_service_grant'] = DB::fetch_all('Select * from ticket_service_grant where portal_id = \''.PORTAL_ID.'\' and ticket_id = '.Url::get('ticket_id') );
            //Dung mang js de check dept nao dc active o ben layout
            $this->map['ticket_service_grant_js'] = String::array2js($this->map['ticket_service_grant']);
            //echo Url::get('portal_id');
            
            $this->map['ticket_service'] = DB::fetch_all('Select * from ticket_service order by ticket_service.id ');
            //System::debug($this->map['department']);
            
            //$this->map['department_js'] = String::array2js($this->map['department']);  
               
            //System::debug($this->map['portal_department_js']);   
            $this->map['title'] = Portal::language('add_ticket_service');
            //System::debug($_REQUEST);
            //System::debug($this->map);
            $this->parse_layout('add_ticket_service', $this->map);
        }
    }
}

?>