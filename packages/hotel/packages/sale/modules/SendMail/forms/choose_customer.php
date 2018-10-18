<?php
class ChooseCustomer extends Form
{
    function ChooseCustomer()
    {
        Form::Form('ChooseCustomer');
    }
    function draw()
    {
        $cond_customer='';
        $group_customer = Url::get('group_customer');
        if($group_customer)
            $cond_customer .= "AND customer.group_id='$group_customer'";
            
        $cond_traveller ='1=1 ';
        $get_gender = Url::get('gender');
        if($get_gender!='')
            $cond_traveller .="AND gender='$get_gender'";
        $country_id = Url::get('country_option');
        if($country_id!='')
            $cond_traveller .="  AND nationality_id='$country_id'";  
        //$cond_traveller .=Url::get('country_id')?' AND nationality_id=\''.Url::get('country_id').'\'':'';
        $traveller_level = Url::get('traveller_level');
        if($traveller_level!='')
            $cond_traveller .= " AND traveller_level_id='$traveller_level'";  
        //$cond_traveller .=Url::get('traveller_level')?' AND traveller_level_id=\''.Url::get('traveller_level').'\'':'';
        $groups = DB::fetch_all('SELECT ID,NAME FROM CUSTOMER_GROUP WHERE '.IDStructure::child_cond(ID_ROOT,1).'');
		$group_customer = array(''=>'---') + String::get_list($groups);
        $gender=array(''=>Portal::language('all'),
                      '0'=>Portal::language('male'),
                      '1'=>Portal::language('female')			
                		);
        $guest_types = DB::fetch_all('select id,name from guest_type order by position');
		$guest_option = array(''=>'---') + String::get_list($guest_types);                
        
        $country = DB::fetch_all('SELECT id,name_2 as name from country ORDER BY name_2');
        $country_option = array(''=>'---') + String::get_list($country);
        
/*-------------------------------------------------------------------------------------------------------------------------*/       
        $list_check_customer = DB::fetch_all("
                                            SELECT email_list.id,email_list.customer_id,email_list.traveller_id
                                            FROM email_list
                                                LEFT JOIN email_send ON email_send.id = email_list.email_send_id
                                                LEFT JOIN email_group_event ON email_group_event.id = email_send.email_group_event_id 
                                            WHERE email_group_event.id=".Url::get('group_event')." AND email_list.email_send_id=".Url::get('send_mail_id')
                                            ); 
                                         
           
        $arr_list_check_customer =array();
        $arr_list_check_traveller =array();
        foreach($list_check_customer as $key=>$value)
        {
           $arr_list_check_customer[] =  $value['customer_id'];
           $arr_list_check_traveller[] =  $value['traveller_id']; 
        }
        
        /*----------------------------------------------------------------------------------------------------*/
        $list_customer = DB::fetch_all('
                                        SELECT id,name,email
                                        FROM customer
                                        WHERE 1>0 '.$cond_customer.'
                                        ORDER BY name'
                                       );
       
        foreach($list_customer as $key=>$value)
        {
            $list_customer[$key]['check'] = 0;
            foreach($arr_list_check_customer as $id=>$content)
            {
                if($value['id']==$content)
                    $list_customer[$key]['check'] = 1;
            }
        }
        /*----------------------------------------------------------------------------------------------------*/
        $list_traveller = DB::fetch_all("
                                           SELECT traveller.id,CONCAT(CONCAT(traveller.first_name,' '),traveller.last_name) AS fullname,traveller.email,traveller.traveller_level_id
                                           FROM traveller
                                                LEFT JOIN guest_type ON traveller.traveller_level_id = guest_type.id
                                           WHERE ".$cond_traveller.""
                                        );  
        foreach($list_traveller as $key=>$value)
        {
            $list_traveller[$key]['check'] = 0;
            foreach($arr_list_check_traveller as $id=>$content)
            {
                if($value['id']==$content)
                    $list_traveller[$key]['check'] = 1;
            }
        }
    
    
        $this ->parse_layout('choose_customer',array(
                                                    'list_traveller'=>$list_traveller,
                                                    'list_customer'=>$list_customer,
                                                    //'check'=>$code_group_email,
                                                    'group_customer_list'=>$group_customer,
                                                    'gender_list'=>$gender,
                                                    'traveller_level_list'=>$guest_option,
                                                    'country_option_list'=>$country_option
                                                    )
                            ); 
        
    }
    function On_submit()
    {
        $list_customer = DB::fetch_all('
                                        SELECT  customer.id,
                                                customer.id as customerid
                                        FROM customer
                                        ORDER BY customerid  
                                       ');
        
        $row_check_customer = array();
        foreach($list_customer as $key=>$value)
        {
            if(isset($_POST['customer_'.$value['id']]))
            {
                $row_check_customer [] = $value['id'];
            }       
        }
        
        $customer_group_event= array();
        
        $group_event = DB::fetch_all('select customer_id as id from email_list WHERE customer_id IS NOT NULL AND email_send_id ='.Url::get('send_mail_id'));
        foreach($group_event as $k => $v)
        {
            $customer_group_event[]= $v['id'];
        }
        
        $customer_update = array_diff($row_check_customer,$customer_group_event);
        if(!empty($customer_update))
        {
            foreach($customer_update as $v)
            {
                DB::insert('email_list',array('customer_id'=>$v,'email_send_id'=>Url::get('send_mail_id')));
            }
        }
        $customer_delete = array_diff($customer_group_event,$row_check_customer);
        if(!empty($customer_delete))
        {
            foreach($customer_delete as $v)
            {
                DB::delete('email_list','email_send_id='.Url::get('send_mail_id').' AND customer_id='.$v);
            }
        }
        
        /*-------------------------------------------------------------------------------------------------*/
     
        $list_traveller = DB::fetch_all('
                                        SELECT  traveller.id       
                                        FROM traveller 
                                        ');
        $row_check_traveller = array();
        foreach($list_traveller as $key=>$value)
        {
            if(isset($_POST['traveller_'.$value['id']]))
            {
                $row_check_traveller []=$value['id'];
            }       
        }
        $traveller_group_event= array();
        $group_event = DB::fetch_all('select traveller_id as id from email_list WHERE traveller_id IS NOT NULL  AND email_send_id ='.Url::get('send_mail_id'));
        foreach($group_event as $k => $v)
        {
            $traveller_group_event[]= $v['id'];
        }
        
        $traveller_update = array_diff($row_check_traveller,$traveller_group_event);
        if(!empty($traveller_update))
        {
            foreach($traveller_update as $v)
            {
                DB::insert('email_list',array('traveller_id'=>$v,'email_send_id'=>Url::get('send_mail_id')));
            }
        }
        $traveller_delete = array_diff($traveller_group_event,$row_check_traveller);
        if(!empty($traveller_delete))
        {
            foreach($traveller_delete as $v)
            {
                DB::delete('email_list','email_send_id='.Url::get('send_mail_id').' AND traveller_id='.$v);
            }
        }
        
        
    }
}
?>