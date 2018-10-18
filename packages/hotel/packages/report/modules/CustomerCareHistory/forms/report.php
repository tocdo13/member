<?php
class CustomerCareHistoryForm extends Form
{
    function CustomerCareHistoryForm()
    {
        Form::Form('CustomerCareHistoryForm');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
    }
    function draw()
    {
        $this->map['from_date'] = isset($_REQUEST['from_date'])?$_REQUEST['from_date'] = $_REQUEST['from_date']:$_REQUEST['from_date'] = date('d/m/Y', Date_Time::to_time(date('1/m/Y', time())));
        $this->map['to_date'] = isset($_REQUEST['to_date'])?$_REQUEST['to_date'] = $_REQUEST['to_date']:$_REQUEST['to_date'] = date('d/m/Y', Date_Time::to_time(date('d/m/Y', time()))+86399);
        $from_date = Date_Time::to_time($_REQUEST['from_date']);
        $to_date = Date_Time::to_time($_REQUEST['to_date']) + 86399;
        $cond = '1=1';
        $cond .= ' AND customer_care.time_contact >= \''.$from_date.'\' AND customer_care.time_contact <= \''.$to_date.'\'';
        if(isset($_REQUEST['name_sale']))
        {
            if($_REQUEST['name_sale'] != 'ALL')
            {
                $cond .= ' AND customer.sale_code = \''.$_REQUEST['name_sale'].'\'';                
            }
        }
        if(isset($_REQUEST['customer_id']))
        {
            if($_REQUEST['customer_id'] != '')
            {
                $cond .= ' AND customer.id = \''.$_REQUEST['customer_id'].'\'';                
            }
        }
        $customer_care =DB::fetch_all("
            SELECT 
                customer_care.id,
                customer_care.customer_id,
                customer_care.time_contact,
                customer_care.content_contact,
                customer_care.address_contact,
                customer_care.attorney_hotel,
                customer_care.attorney_customer,
                customer_care.note,
                customer_care.create_time,
                (CASE
                    WHEN customer_care.init_active=1 THEN 'Khách sạn'
                    WHEN customer_care.init_active=2 THEN 'Khách hàng'
                    END) as init_active,
                (CASE
                    WHEN  customer_care.expedeency=1 THEN 'Email'
                    WHEN  customer_care.expedeency=2 THEN 'Điện thoại'
                    WHEN  customer_care.expedeency=3 THEN 'Trực tiếp'
                    ELSE 'Khác' 
                    END) as  expedeency,
                customer.name ,
                party.full_name as sale_code
            FROM 
                customer_care
                INNER JOIN customer on customer.id=customer_care.customer_id
                INNER JOIN party on party.user_id = customer.sale_code 
            WHERE
                ".$cond."
            ORDER BY 
                customer_care.time_contact desc");
        //System::debug($customer_care); 
        
        $this->map['items'] = $customer_care;
        $customer = DB::fetch_all('SELECT id, name FROM customer ORDER BY name ASC');
        $customer_option = '';
        foreach($customer as $k => $v)
        {
            $customer_option .= '<option value=\''.$v['name'].'\' id=\''.$v['id'].'\'>' .$v['id']. '</option>';            
        }
        $this->map['customer_option'] = $customer_option; 
        $users = DB::fetch_all('
                        SELECT 
							account.id
							,party.full_name as name 
						FROM 
                            account 
                            INNER JOIN party on party.user_id = account.id AND party.type=\'USER\'
                            INNER JOIN portal_department on account.portal_department_id = portal_department.id
                            INNER JOIN department on portal_department.department_code = department.code 
                        WHERE 
                            account.type=\'USER\'
                            AND department.code =\'SALES\'  
                        ORDER BY 
                            account.id
        ');
        $this->map['name_sale_list'] = array('ALL'=>Portal::language('all'))+ String::get_list($users);       
        $user_data = Session::get('user_data');
        $this->map['print_person'] = isset($user_data['full_name'])?$user_data['full_name']:Session::get('user_id');
        $this->parse_layout('report', $this->map);       
    }
}
?>
