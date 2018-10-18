<?php
    class SendMailForm extends Form{
        function SendMailForm(){
            Form::Form('SendMailForm');
            $this->link_css(Portal::template('hotel').'/css/style.css');
            //$this->Update_date_send();  
            //$this->check_send_mail(); 
        }
        function On_submit(){
            
        }
        function draw(){
           $sql = '
                    SELECT email_send.*,email_group_event.name
                        FROM email_send
                        LEFT JOIN email_group_event ON email_group_event.id = email_send.email_group_event_id
                    ORDER BY email_send.date_send   
                ';
           $array = array();     
           $array['list_email'] = DB::fetch_all($sql);
           
           $this -> parse_layout('list',$array);
        }
        
       
        /*
        function Update_date_send()
        {
            $code = DB::fetch_all('SELECT email_group_event.id,email_group_event.code FROM email_group_event');
            foreach($code as $key=>$value)
            {
                  $sql='';
                  $sql1='';
                  if($value['code']=='SN')
                  {
                        $sql =('    Update 
                                        (SELECT
                                                email_list.date_send date_mail,
                                                traveller.birth_date birthday
                                         FROM email_list,
                                              traveller
                                         WHERE email_list.traveller_id =  traveller.id AND
                                               traveller.email IS NOT NULL 
                                        )
                                    SET  date_mail = birthday
                                ');
                        
                    
                  }
                  DB::fetch_all($sql);
                  
                  if($value['code']=='NTL')
                  {
                    $sql1 =('
                                    Update
                                        (SELECT
                                                email_list.date_send date_mail,
                                                customer.creart_date create_date
                                          FROM email_list,
                                               customer
                                          WHERE email_list.customer_id = customer.id
                                                AND customer.email IS NOT NULL 
                                        )
                                     SET date_mail = create_date 
                                '); 
                                    
                  }
                   DB::fetch_all($sql1);      
              }
              
             
           }
   

        
        */ 
}
?>