<?php
class EditCustomerCareForm extends Form
{
    function EditCustomerCareForm()
    {
        Form::Form('EditCustomerCareForm');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
        $this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_css('packages/core/skins/default/css/jquery/datepicker.css');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
    }
    function on_submit()
    {
        /*
        09-05-2014
        Start: lay ra thong tin customer care
        */ 
        if(Url::get('cmd')=='edit' || Url::get('cmd')=='add')
        {
            if(Url::iget('id'))
            {
               $customer_id = Url::iget('id');
            }
            $sale_code = Url::sget('sale_code');
            $date_contact = Url::sget('date_contact');
            $str_date_contact = explode('/',$date_contact);
            
            $time_contact = Url::sget('time_contact');
            $str_time_contact = explode(':',$time_contact);
            
            $time = mktime($str_time_contact[0],$str_time_contact[1],0,$str_date_contact[1],$str_date_contact[0],$str_date_contact[2]);
            
            $expedeency = Url::iget('expedeency');
            $init_active = Url::iget('init_active');
            
            $address_contact = Url::sget('address_contact');
            $content_contact = Url::sget('content_contact');
            $attorney_customer = Url::sget('attorney_customer');
            $attorney_hotel = Url::sget('attorney_hotel');
            $note = Url::sget('note');
            
            //khoi tao 1 dong de insert vao database
            $object_customer_care = array(
            'customer_id'=>$customer_id,
            'time_contact'=>$time,
            'content_contact'=>$content_contact,
            'address_contact'=>$address_contact,
            'attorney_hotel'=>$attorney_hotel,
            'attorney_customer'=>$attorney_customer,
            'init_active'=>$init_active,
            'expedeency'=>  $expedeency,
            'note'=>$note,
            'create_time'=>time());
            //End
            if(Url::get('cmd')=='edit')
            {
               $customer_care_id = $_REQUEST['id_customer_care'];
               DB::update('customer_care',$object_customer_care,'id=\''.$customer_care_id.'\''); 
            }
            if(Url::get('cmd')=='add')
            {
               DB::insert('customer_care',$object_customer_care); 
               Url::redirect_current(array('cmd'=>'view','id'=>$customer_id));
            }
        }
        else
        {
            $customer_id = Url::iget('id');
            Url::redirect_current(array('cmd'=>'view','id'=>$customer_id)); 
        }     
    }
    function draw()
    {
        $this->map['title'] = Portal::language('edit_customer_care');
        /* 09-05-2014
            Start: lay ra danh sach:chu dong(Khach san,Khach hang) 
            Phuong Tien(email,dienthoai,truc tiep,khac) 
            */
        $init_active = array('1'=>'Khách sạn','2'=>'Khách hàng');
        $expedeency = array('1'=>'Email','2'=>'Điện thoại','3'=>'Trực tiếp','4'=>'Khác');
            //End
       
        if(Url::get('cmd')=='edit')//truong hop sua
        {
             //lay ra id_customer_care hien thi thong tin 1 doi tuong
             $customer_care_id = Url::iget('id_customer_care');
             $sql = "SELECT address_contact,attorney_hotel,
             attorney_customer,note,time_contact,customer_id,id as id_customer_care,
             init_active,expedeency,content_contact 
             FROM customer_care 
             WHERE id=".$customer_care_id;
             $items = @DB::fetch($sql);
            
            //xu ly tao date_contact va time_contact
            
            $date =date('d/m/Y',$items['time_contact']);
            $time = date('H:i',$items['time_contact']);
            
            unset($items['time_contact']); 
            $items = array('date_contact'=>$date) + $items;
            $items = array('time_contact'=>$time) + $items;
             foreach($items as $key=>$value)
             {
                 if(is_string($value))
                 {
                     $_REQUEST[$key] = $value;
                 }
       
             }
             
             /* 09/05/2014
            Start: lay ra thong tin customer theo id(name,sale_code)
            */
            $customer_id =$items['customer_id'];
            
            
             
             
        }
        else  //truong hop xem hoac them moi
        {
           /* 09/05/2014
            Start: lay ra thong tin customer theo id(name,sale_code)
            */
            $customer_id = Url::iget('id');
            $_REQUEST['cmd'] = 'add';
            //End
        }
        
        $sql = "
        SELECT customer.id,customer.name,customer.sale_code
        FROM customer
        WHERE id=".$customer_id;
        $items = @DB::fetch($sql);
        
        foreach($items as $key=>$value)
        {
            if(is_string($value) and !isset($_POST[$key]))
            {
                $_REQUEST[$key] = $value;
            }
        }
        //End
        
        /*10-05-2014
        Start: lay ra danh sach khach hang dang duoc cham soc: customer_care
        */
        $sql ="SELECT customer_care.id,customer_care.customer_id,
        customer_care.time_contact,customer_care.content_contact,
        customer_care.address_contact,customer_care.attorney_hotel,
        customer_care.attorney_customer,customer_care.note,
        (CASE
            WHEN customer_care.init_active=1 THEN 'Khách sạn'
            WHEN customer_care.init_active=2 THEN 'Khách hàng'
            END) as init_active,
        (CASE
            WHEN  customer_care.expedeency=1 THEN 'Email'
            WHEN  customer_care.expedeency=2 THEN 'Điện thoại'
            WHEN  customer_care.expedeency=3 THEN 'Trực tiếp'
            ELSE 'Khác' 
            END) as  expedeency,customer.name 
        FROM customer_care
        INNER JOIN customer on customer.id=customer_care.customer_id and customer.id=".$customer_id." 
        ORDER BY customer_care.time_contact desc";
        $items = DB::fetch_all($sql);
        $i=1;
        foreach($items as $key=>$value)
        {
            $items[$key]['i'] = $i++;
            $date_contact = date('d/m/Y',$value['time_contact']);
            $time_contact =  date('H:i',$value['time_contact']) ;
            $items[$key] = array('date_contact'=>$date_contact) + $items[$key];
            $items[$key]  = array('time'=>$time_contact)+ $items[$key];
        }
        $this->map['items'] = $items;
        $this->parse_layout('edit',$this->map +array(
            'init_active_list'=>$init_active,
            'expedeency_list'=>$expedeency));
    }
}
?>
