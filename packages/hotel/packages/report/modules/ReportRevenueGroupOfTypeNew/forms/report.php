<?php
class ReportRevenueGroupOfTypeNewForm extends Form
{
	function ReportRevenueGroupOfTypeNewForm()
	{
		Form::Form('ReportRevenueGroupOfTypeNewForm');
        $this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
    	$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        $this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
        $this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');     
        $this->link_js('packages/core/includes/js/jquery/jquery.battatech.excelexport.js');
	}
    function on_submit()
    {
        //System::debug($_REQUEST);exit();
    }
	function draw()
	{
	   $this->map = array();
       /** chọn portal xem - SELECTBOX_STRING **/
       $this->map['portal_id'] = Url::get('portal_id')?Url::get('portal_id'):PORTAL_ID;
       $this->map['portal_id_list'] = String::get_list(Portal::get_portal_list());
       
       /** chọn các bộ phận trong portal dó - SELECTBOX_STRING **/
       $active_department = DB::fetch_all('Select 
                                                department.code as id,
                                                department.name_'.Portal::language().' as name 
                                            from 
                                                department 
                                                inner join portal_department on department.code = portal_department.department_code 
                                            where
                                                portal_department.portal_id = \''.$this->map['portal_id'].'\'
                                                and department.parent_id = 0 AND department.code != \'WH\'
                                        ');
       //
//       $this->map['list_list'] = array(''=>Portal::language('all')
//                                        ,'ROOM_CHARGE'=>portal::language('room_charge')
//                                        ,'EXTRA_ROOM_CHARGE'=>portal::language('extra_room_charge')
//                                        ,'EXTRA_SERVICE'=>portal::language('extra_service')
//                                        ,'HOUSEKEEPING'=>portal::language('housekeeping')
//                                        ,'BAR'=>portal::language('bar')
//                                        ,'PARTY'=>portal::language('party')
//                                        ,'VEND'=>portal::language('vending')
//                                        ,'SPA'=>portal::language('spa')
//                                        ,'KARAOKE'=>portal::language('karaoke')
//                                        );  
            /** Nhom nguon khach */
            $this->map['group_customer'] = Url::get('group_customer_ids','');                        
            $g_customer = DB::fetch_all($sql='
                            select 
                				customer_group.id
                				,customer_group.structure_id
                				,customer_group.name 
                			from 
                			 	customer_group
                			where 1=1
                            AND customer_group.structure_id!= 1000000000000000000
                			order by customer_group.structure_id
            ');       
            //System::debug($g_customer);                         
            $this->map['customer_group'] = String::array2js(explode(',',Url::get('group_customer_id_')));
            //System::debug(Url::get('group_customer_id_'));            
            $group_customer = '<div id="checkboxes_group_customer">';
            foreach($g_customer as $key=>$value)
            {                
                $group_customer .= '<label for="group_customer_'.$value['id'].'">';    
                $group_customer .= '<input name="group_customer_'.$value['id'].'" type="checkbox" id="group_customer_'.$value['id'].'" flag="'.$value['id'].'" class="group_customer" onclick="get_ids(\'group_customer\');"/>'.$value['name'].'</label>';                                    
            }   
            $group_customer .= '</div>';            
            $this->map['list_group_customer'] = $group_customer;                                                                      
            /** Nhom nguon khach */  
            /** Nguon khach **/
            $this->map['customer'] = Url::get('customer_ids','');                               
            $l_customer = DB::fetch_all($sql='
                           select customer.id,customer.name from customer order by customer.name
            ');       
            //System::debug($this->map['customer']);                         
            $this->map['customer_js'] = String::array2js(explode(',',Url::get('customer_id_')));            
            $customer = '<div id="checkboxes_customer">';
            foreach($l_customer as $key=>$value)
            {                
                $customer .= '<label for="customer_'.$value['id'].'">';    
                $customer .= '<input name="customer_'.$value['id'].'" type="checkbox" id="customer_'.$value['id'].'" flag="'.$value['id'].'" class="customer" onclick="get_ids(\'customer\');"/>'.$value['name'].'</label>';                                    
            }   
            $customer .= '</div>';            
            $this->map['list_customer'] = $customer; 
            /** Nguon khach **/   
        /** Danh muc **/          
       $this->map['list'] = Url::get('str_id')?Url::get('str_id'):''; 
       //$this->map['str_id'] = Url::get('str_id');
       $this->map['category'] = String::array2js(explode(',',Url::get('str_id')));
       //System::debug($this->map['category']);                      
       $list_category = '<div id="checkboxes">';       
           $list_category .= '<label for="cateory_ROOM_CHARGE">';
           $list_category .= '<input name="cateory_ROOM_CHARGE" type="checkbox" id="cateory_ROOM_CHARGE" flag="ROOM_CHARGE" class="category" onclick="get_ids(\'category\');"/>'.portal::language('room_charge').'</label>';
           $list_category .= '<label for="cateory_EXTRA_ROOM_CHARGE">';
           $list_category .= '<input name="cateory_EXTRA_ROOM_CHARGE" type="checkbox" id="cateory_EXTRA_ROOM_CHARGE" flag="EXTRA_ROOM_CHARGE" class="category" onclick="get_ids(\'category\');"/>'.portal::language('extra_room_charge').'</label>';
           $list_category .= '<label for="cateory_EXTRA_SERVICE">';
           $list_category .= '<input name="cateory_EXTRA_SERVICE" type="checkbox" id="cateory_EXTRA_SERVICE" flag="EXTRA_SERVICE" class="category" onclick="get_ids(\'category\');"/>'.portal::language('extra_service').'</label>';
           $list_category .= '<label for="cateory_HOUSEKEEPING">';
           $list_category .= '<input name="cateory_HOUSEKEEPING" type="checkbox" id="cateory_HOUSEKEEPING" flag="HOUSEKEEPING" class="category" onclick="get_ids(\'category\');"/>'.portal::language('housekeeping').'</label>';
           $list_category .= '<label for="cateory_BAR">';
           $list_category .= '<input name="cateory_BAR" type="checkbox" id="cateory_BAR" class="category" flag="BAR" onclick="get_ids(\'category\');"/>'.portal::language('bar').'</label>';
           $list_category .= '<label for="cateory_PARTY">';
           $list_category .= '<input iname="cateory_PARTY" type="checkbox" id="cateory_PARTY" class="category" flag="PARTY" onclick="get_ids(\'category\');"/>'.portal::language('party').'</label>';
           /*
           $list_category .= '<label for="cateory_VEND">';
           $list_category .= '<input name="cateory_VEND" type="checkbox" id="cateory_VEND" class="category" flag="VEND" onclick="get_ids(\'category\');"/>'.portal::language('vending').'</label>';
           $list_category .= '<label for="cateory_SPA">';
           $list_category .= '<input name="cateory_SPA" type="checkbox" id="cateory_SPA" class="category" flag="SPA" onclick="get_ids(\'category\');"/>'.portal::language('spa').'</label>';
           $list_category .= '<label for="cateory_KARAOKE">';
           $list_category .= '<input name="cateory_KARAOKE" type="checkbox" id="cateory_KARAOKE" flag="KARAOKE" class="category" onclick="get_ids(\'category\');"/>'.portal::language('karaoke').'</label>';
           */
       $list_category .= '</div>';
       
       $this->map['list_category'] = $list_category;                                                                                           
       /** Danh muc **/
       $this->map['status_list'] = array(''=>Portal::language('all'),'BOOKED'=>'BOOKED','CHECKIN'=>'CHECKIN','CHECKOUT'=>'CHECKOUT');
       $this->map['status'] = Url::get('status')?Url::get('status'):'';
       /** chọn người tạo - SELECTBOX_STRING **/
       $users = DB::fetch_all('select account.id,party.full_name as name from account INNER JOIN party on party.user_id = account.id AND party.type=\'USER\' WHERE account.type=\'USER\' ORDER BY account.id');
       $this->map['create_user_list'] = array(''=>Portal::language('all'))+String::get_list($users);
       $this->map['create_user'] = Url::get('create_user')?Url::get('create_user'):'';
       
       /** chọn thời gian - DATE **/
       $this->map['date_from'] = Url::get('date_from')?Url::get('date_from'):date('d/m/Y');
       $this->map['date_to'] = Url::get('date_to')?Url::get('date_to'):date('d/m/Y');
       
       /** chọn giờ **/
       $this->map['time_in'] = Url::get('time_in')?Url::get('time_in'):'00:00';
       $this->map['time_out'] = Url::get('time_out')?Url::get('time_out'):'23:59';
       
       /** chọn mã mặc định - NUMBER **/
       $this->map['re_code'] = Url::get('re_code')?Url::get('re_code'):'';
       
       /** chọn số phiếu - STRING **/
       $this->map['number_of_vote'] = Url::get('number_of_vote')?Url::get('number_of_vote'):'';
       
       /** chọn số phòng - STRING **/
       $this->map['room_number'] = Url::get('room_number')?Url::get('room_number'):'';
       //$this->map['customer_id'] = Url::get('customer_id')?Url::get('customer_id'):'';
       //$this->map['customer_name'] = Url::get('customer_name')?Url::get('customer_name'):'';
       $this->map['status'] = Url::get('status')?Url::get('status'):'';
       /** khởi tạo option search **/
       /** Nhom nguon khach **/
       $cond_group_customer = 'customer.id in ('.$this->map['group_customer'].')';
       /** lễ tân **/
       $cond_resservation = '( room_status.in_date >=\''.Date_Time::to_orc_date($this->map['date_from']).'\' AND room_status.in_date <=\''.Date_Time::to_orc_date($this->map['date_to']).'\' )';
       /** dịch vụ **/
       $cond_extra_service = '( extra_service_invoice_detail.in_date >=\''.Date_Time::to_orc_date($this->map['date_from']).'\' AND extra_service_invoice_detail.in_date <=\''.Date_Time::to_orc_date($this->map['date_to']).'\' )';
       /** buồng **/
       $cond_housekeeping_invoice = '( housekeeping_invoice.time >='.(Date_Time::to_time($this->map['date_from'])+$this->calc_time('00:00')).' AND housekeeping_invoice.time <='.(Date_Time::to_time($this->map['date_to'])+$this->calc_time('23:59')).' )';
       /** bar **/
       $cond_bar = '( bar_reservation.arrival_time <='.(Date_Time::to_time($this->map['date_to'])+$this->calc_time($this->map['time_out'])).' AND bar_reservation.arrival_time >='.(Date_Time::to_time($this->map['date_from'])+$this->calc_time($this->map['time_in'])).' ) AND ( bar_reservation.status=\'CHECKIN\' OR bar_reservation.status=\'CHECKOUT\' OR bar_reservation.status=\'BOOKED\' )';
       /** Spa **/
       $cond_spa = '(massage_reservation_room.time<='.(Date_Time::to_time($this->map['date_to'])+$this->calc_time('23:59')).' AND massage_reservation_room.time>='.(Date_Time::to_time($this->map['date_from'])+$this->calc_time('00:00')).')';
       /** đặt tiệc **/
       $cond_party = '(party_reservation.checkin_time<='.(Date_Time::to_time($this->map['date_to'])+$this->calc_time('23:59')).' AND party_reservation.checkin_time>='.(Date_Time::to_time($this->map['date_from'])+$this->calc_time('00:00')).')';
       /** bán hàng **/
       $cond_vend = '( ve_reservation.time >='.(Date_Time::to_time($this->map['date_from'])+$this->calc_time('00:00')).' AND ve_reservation.time <='.(Date_Time::to_time($this->map['date_to'])+$this->calc_time('23:59')).' ) AND ( ve_reservation.status=\'CHECKIN\' OR ve_reservation.status=\'CHECKOUT\' OR ve_reservation.status=\'BOOKED\' )';
       /** bán vé **/
       $cond_ticket = '( ticket_invoice.date_used >=\''.Date_Time::to_orc_date($this->map['date_from']).'\' AND ticket_invoice.date_used <=\''.Date_Time::to_orc_date($this->map['date_to']).'\' )';
       /** karaoke **/
       $cond_karaoke = '( karaoke_reservation.time >='.(Date_Time::to_time($this->map['date_from'])+$this->calc_time('00:00')).' AND karaoke_reservation.time <='.(Date_Time::to_time($this->map['date_to'])+$this->calc_time('23:59')).' ) AND ( karaoke_reservation.status=\'CHECKIN\' OR karaoke_reservation.status=\'CHECKOUT\' OR karaoke_reservation.status=\'BOOKED\' )';
       
       
       /** tìm kiếm theo portal **/
       if($this->map['portal_id']!='')
       {             
             $cond_resservation .= 'AND (reservation.portal_id=\''.$this->map['portal_id'].'\')';
             $cond_extra_service .= 'AND (extra_service_invoice.portal_id=\''.$this->map['portal_id'].'\')';
             $cond_housekeeping_invoice .= 'AND (housekeeping_invoice.portal_id=\''.$this->map['portal_id'].'\')';
             $cond_bar .= 'AND (bar_reservation.portal_id=\''.$this->map['portal_id'].'\')';
             $cond_spa .= 'AND (massage_reservation_room.portal_id=\''.$this->map['portal_id'].'\')';
             $cond_party .= 'AND (party_reservation.portal_id=\''.$this->map['portal_id'].'\')';
             $cond_vend .= 'AND (ve_reservation.portal_id=\''.$this->map['portal_id'].'\')';
             $cond_ticket .= 'AND (ticket_invoice.portal_id=\''.$this->map['portal_id'].'\')';
             //$cond_karaoke .= 'AND (karaoke_reservation.portal_id=\''.$this->map['portal_id'].'\')';
       }
       /** Tim kiem theo nhom nguon khach **/
       if($this->map['group_customer'] !='')
       {            
             $cond = 'AND (customer_group.id in ('.$this->map['group_customer'].'))';             
             $cond_resservation .= $cond;
             $cond_extra_service .= $cond;             
             $cond_vend .= $cond;
             $cond_housekeeping_invoice .= $cond;
             $cond_bar .= $cond;
             $cond_party .= $cond;
             $cond_spa .= $cond;
       }
       /** Tim kiem theo nhom nguon khach **/ 
       
       /** tìm kiếm theo mã mặc định **/
       if($this->map['re_code']!='')
       {
             $cond = 'AND (reservation.id='.$this->map['re_code'].')';
             $cond_resservation.=$cond; 
             $cond_extra_service.=$cond;
             $cond_housekeeping_invoice.=$cond;
             $cond_bar.=$cond;
//             $cond_karaoke.=$cond;
             $cond_spa .= $cond;
             $cond_party .= 'AND (party_reservation.id=0)';
             $cond_vend .= 'AND (ve_reservation.id=0)';
             $cond_ticket .= 'AND (ticket_invoice.id=0)';
       }
       /** tìm kiếm theo số phiếu **/
       if($this->map['number_of_vote']!='')
       {
             if(is_numeric($this->map['number_of_vote']))
             {
                $cond_resservation.='AND (folio.id='.$this->map['number_of_vote'].')';
                $cond_housekeeping_invoice.='AND (housekeeping_invoice.id='.$this->map['number_of_vote'].'AND housekeeping_invoice.type=\'EQUIP\')';
                $cond_extra_service.='AND (extra_service_invoice.bill_number=\''.$this->map['number_of_vote'].'\')';
                $cond_bar.='AND (bar_reservation.code like \'%'.$this->map['number_of_vote'].'%\')';
//                $cond_karaoke.='AND (karaoke_reservation.code=\'\')';
                $cond_spa .= '';
                $cond_party .= 'AND (party_reservation.id=\''.$this->map['number_of_vote'].'\')';
                $cond_vend .= 'AND (ve_reservation.id=0)';
                $cond_ticket .= 'AND (ticket_invoice.id=0)';
             }
             else
             {
                $cond_resservation.='AND (reservation.id=0)';
                $cond_extra_service.='AND (extra_service_invoice.bill_number=\''.strtoupper($this->map['number_of_vote']).'\')';
                $cond_bar.='AND (bar_reservation.code=\''.$this->map['number_of_vote'].'\')';
//                $cond_karaoke.='AND (karaoke_reservation.code=\''.$this->map['number_of_vote'].'\')';
                $cond_vend.='AND (ve_reservation.code=\''.$this->map['number_of_vote'].'\')';
                $cond_ticket.='AND (upper(ticket.name)=\''.strtoupper($this->map['number_of_vote']).'\')';
                if(strpos($this->map['number_of_vote'],'_'))
                {
                    $arr_vote = explode("_",$this->map['number_of_vote']);
                    if(strtoupper($arr_vote[0])=='MN')
                    {
                        $cond_housekeeping_invoice.='AND (housekeeping_invoice.position='.$arr_vote[1].'AND housekeeping_invoice.type=\'MINIBAR\')';
                    }
                    elseif(strtoupper($arr_vote[0])=='LD')
                    {
                        $cond_housekeeping_invoice.='AND (housekeeping_invoice.position='.$arr_vote[1].'AND housekeeping_invoice.type=\'LAUNDRY\')';
                    }
                    elseif(strtoupper($arr_vote[0])=='EQ')
                    {
                        $cond_housekeeping_invoice.='AND (housekeeping_invoice.position='.$arr_vote[1].'AND housekeeping_invoice.type=\'EQUIP\')';
                    }
                    else
                    {
                        $cond_housekeeping_invoice.= 'AND (housekeeping_invoice.type=\'OTHER\')';
                    }
                }
                else
                {
                    $cond_housekeeping_invoice.= 'AND (housekeeping_invoice.type=\'OTHER\')';
                }
                $cond_spa .= 'AND (massage_reservation_room.id=0)';
                $cond_party .= 'AND (party_reservation.id=0)';
             }
       }
       /** tìm kiếm theo người tạo **/
       if($this->map['create_user']!='')
       {
             $cond = 'AND (party.user_id=\''.$this->map['create_user'].'\')';
             $cond_resservation.=$cond; 
             $cond_extra_service.=$cond;
             $cond_housekeeping_invoice.=$cond;
             $cond_bar.=$cond;
//             $cond_karaoke.=$cond;
             $cond_spa.=$cond;
             $cond_party.=$cond;
             $cond_vend.=$cond;
             $cond_ticket.=$cond;
       }
       /** tìm kiếm theo số phòng **/
       if($this->map['room_number']!='')
       {
             $cond = 'AND (room.name=\''.$this->map['room_number'].'\')';
             $cond_resservation.=$cond; 
             $cond_extra_service.=$cond;
             $cond_housekeeping_invoice.=$cond;
             $cond_bar.=$cond;
//             $cond_karaoke.=$cond;
             $cond_spa.=$cond;
             $cond_party .= 'AND (party_reservation.id=0)';
             $cond_vend .= 'AND (ve_reservation.id=0)';
             $cond_ticket.= 'AND (ticket_invoice.id=0)';
       }
       if($this->map['customer']!='')
       {
             $cond = 'AND (reservation.customer_id in ('.$this->map['customer'].'))';
             $cond_resservation.=$cond; 
             $cond_extra_service.=$cond;
             $cond_housekeeping_invoice.=$cond;
             $cond_bar.='AND (reservation.customer_id in ('.$this->map['customer'].') or bar_reservation.customer_id in ('.$this->map['customer'].'))';
//             $cond_karaoke.='AND (reservation.customer_idin ('.$this->map['customer'].') or karaoke_reservation.customer_id in ('.$this->map['customer'].'))';
             //$cond_spa.=$cond;
             //$cond_party .= 'AND (party_reservation.id=0)';
             $cond_party .='AND (mice_reservation.customer_id in ('.$this->map['customer'].') or party_reservation.customer_id in ('.$this->map['customer'].'))';
             $cond_vend .= 'AND (reservation.customer_id in ('.$this->map['customer'].') or ve_reservation.customer_id in ('.$this->map['customer'].'))';
             //$cond_ticket.= 'AND (ticket_invoice.id=0)';
       }
       if($this->map['status']!='')
       {
             $cond = 'AND (reservation_room.status=\''.$this->map['status'].'\')';
             $cond_resservation.=$cond; 
       }
       $this->map['list'] = explode(',',$this->map['list']);
       /** lấy dữ liệu bộ phận lễ tân **/
       $reservation = array();       
      // if(($this->map['list']=='' OR $this->map['list']=='ROOM_CHARGE') AND $this->id_like_array('REC',$active_department))
//       {
//             $reservation = $this->get_reservation_revenue($cond_resservation);
//             //System::debug($reservation);
//       }
       
       $extra_service = array();
       $list_extra_service = array();
       $str_category = Url::get('str_id','');
        
       //System::debug($this->map['list']);     
       /** lấy dữ liệu bộ phận lễ tân **/                    
        if(in_array('',$this->map['list']) OR in_array('ROOM_CHARGE',$this->map['list']) AND $this->id_like_array('REC',$active_department)){
            $reservation = $this->get_reservation_revenue($cond_resservation);
        }
        /** lấy dữ liệu bộ phận lễ tân **/
        /** lấy dữ liệu dịch vụ khác **/        
        if(in_array('',$this->map['list']) OR in_array('EXTRA_ROOM_CHARGE',$this->map['list']) OR in_array('EXTRA_SERVICE',$this->map['list']) OR in_array('ROOM_CHARGE',$this->map['list']))
        {
            if(in_array('EXTRA_ROOM_CHARGE',$this->map['list']))
            {
                if(in_array('EXTRA_SERVICE',$this->map['list']))
                {                    
                    $cond_extra_service .= 'AND (extra_service.type in (\'ROOM\',\'SERVICE\') and extra_service.code != \'LATE_CHECKIN\')';
                }
                else{                    
                    $cond_extra_service .= 'AND (extra_service.type=\'ROOM\' and extra_service.code != \'LATE_CHECKIN\') ';    
                }
                
            }                    
            elseif(in_array('EXTRA_SERVICE',$this->map['list'])){                    
                $cond_extra_service .= ' AND extra_service.type=\'SERVICE\' ';
            }                    
            elseif(in_array('ROOM_CHARGE',$this->map['list'])){                    
                $cond_extra_service .= 'AND (extra_service.code = \'LATE_CHECKIN\') ';   
            }                                
            elseif(in_array('EXTRA_ROOM_CHARGE',$this->map['list']) AND in_array('ROOM_CHARGE',$this->map['list']))  
            {
                $cond_extra_service .= 'AND (extra_service.type=\'ROOM\' and extra_service.code != \'LATE_CHECKIN\') AND (extra_service.code = \'LATE_CHECKIN\') ';
            }                                                                   
            elseif(in_array('EXTRA_SERVICE',$this->map['list']) AND in_array('ROOM_CHARGE',$this->map['list'])) 
            {                                    
                $cond_extra_service .= 'AND extra_service.type=\'SERVICE\' AND (extra_service.code = \'LATE_CHECKIN\') ';
            }                                    
            $extra_service = $this->get_extra_service_revenue($cond_extra_service);
        }
        $key_word = '';
       foreach($extra_service as $key=>$value)
       {
            $key_arr = explode('_',$key);
            if($key_arr[0]!=$key_word)
            {
                $key_word = $key_arr[0];
                $list_extra_service[$key_word]['id'] = $key_word;
                $list_extra_service[$key_word]['total'] = $value['price'];
            }
            else
            {
                $list_extra_service[$key_word]['total'] += $value['price'];
            }
       }
       
       //if($this->map['list']=='' OR $this->map['list']=='EXTRA_ROOM_CHARGE' OR $this->map['list']=='EXTRA_SERVICE' OR $this->map['list']=='ROOM_CHARGE')
//       {
//            if($this->map['list']=='EXTRA_ROOM_CHARGE')
//                $cond_extra_service .= "AND (extra_service.type='ROOM' and extra_service.code != 'LATE_CHECKIN') ";
//            elseif($this->map['list']=='EXTRA_SERVICE')
//                $cond_extra_service .= ' AND extra_service.type=\'SERVICE\' ';
//            elseif($this->map['list']=='ROOM_CHARGE')
//                $cond_extra_service .= "AND (extra_service.code = 'LATE_CHECKIN') ";
//            $extra_service = $this->get_extra_service_revenue($cond_extra_service);
//       }
       
       
       /** lấy dữ liệu minibar **/
       $minibar = array();
       
       foreach($this->map['list'] as $k=>$v)
       {                
            if(($v=='' OR $v=='HOUSEKEEPING') AND $this->id_like_array('HK',$active_department))
            $minibar = $this->get_housekeeping($cond_housekeeping_invoice,'MINIBAR');
           
           /** lấy dữ liệu giặt là **/
           $laundry = array();
           if(($v=='' OR $v=='HOUSEKEEPING') AND $this->id_like_array('HK',$active_department))
            $laundry = $this->get_housekeeping($cond_housekeeping_invoice,'LAUNDRY');
           
           /** lấy dữ liệu HĐ đền bù **/
           $equipment = array();
           if(($v=='' OR $v=='HOUSEKEEPING') AND $this->id_like_array('HK',$active_department))
            $equipment = $this->get_housekeeping($cond_housekeeping_invoice,'EQUIP');
           
           /** lấy dữ liệu nhà hàng **/
           $list_bar = DB::fetch_all('select * from bar');
           $bar = array();
           if(($v=='' OR $v=='BAR') AND $this->id_like_array('RES',$active_department))
           {
                foreach($list_bar as $key=>$value)
                {
                    $bar[$value['code']]['child']=$this->get_bar($cond_bar,$value['code']);
                    $bar[$value['code']]['name'] = $value['name'];
                    if(sizeof($bar[$value['code']]['child'])==0)
                    {
                        unset($bar[$value['code']]);
                    }
                }
           }
           /** lấy dữ liệu Spa **/
           $spa = array();
           if(($v=='' OR $v=='SPA') AND $this->id_like_array('SPA',$active_department))
           $spa = $this->get_spa($cond_spa);
           
           /** lấy dữ liệu đặt tiệc **/
           $party=array();                      
           if(($v=='' OR $v=='PARTY') AND $this->id_like_array('BANQUET',$active_department))
           $party = $this->get_party($cond_party);                                           
           //System::debug($party);
           /** lấy dữ liệu bán hàng **/
           $vend=array();
           if(($v=='' OR $v=='VEND') AND $this->id_like_array('VENDING',$active_department))
           $vend = $this->get_vend($cond_vend);
           
           /** lấy dữ liệu bán vé **/
           $ticket = array();
           if(($v=='' OR $v=='TICKET') AND $this->id_like_array('TICKET',$active_department))
           $ticket = $this->get_ticket($cond_ticket);
           
           /** lấy dữ liệu karaoke **/
           $karaoke = array();
           if(($v=='' OR $v=='KARAOKE') AND $this->id_like_array('KARAOKE',$active_department))
           $karaoke = $this->get_karaoke($cond_karaoke);
       }       
       
       
       /** truyền layout **/
       $_REQUEST += $this->map;
       $this->parse_layout('report',array(
                                        'reservation'=>$reservation,
                                        'extra_service'=>$extra_service,
                                        'list_extra_service'=>$list_extra_service,
                                        'minibar'=>$minibar,
                                        'laundry'=>$laundry,
                                        'equip'=>$equipment,
                                        'bar'=>$bar,
                                        'spa'=>$spa,
                                        'party'=>$party,
                                        'vend'=>$vend,
                                        'ticket'=>$ticket,
                                        'karaoke'=>$karaoke
                                        )+$this->map);
       //**************************************************************************************************\\
	}
    
    /** hàm lấy doanh thu phòng **/
    function get_reservation_revenue($cond)
    {
        $sql = "
                SELECT 
                    room_status.id as id
                    ,room_status.in_date
                    ,room_status.change_price as price
                    ,room.name as room
                    ,reservation_room.id as reservation_room_id
                    ,reservation.id as reservation_id
                    ,party.name_".Portal::language()." as user_name
                    ,reservation_room.note
                    ,reservation_room.net_price
                    ,reservation_room.tax_rate
                    ,reservation_room.service_rate
                    ,reservation_room.reduce_balance
                    ,reservation_room.reduce_amount
                    ,reservation_room.arrival_time
                    ,reservation_room.departure_time
                    ,reservation_room.status as reservation_room_status
                    ,NVL(reservation_room.change_room_from_rr,0) as change_from
                    ,NVL(reservation_room.change_room_to_rr,0) as change_to
                    ,reservation_room.foc
                    ,NVL(reservation_room.foc_all,0) as foc_all
                    ,customer.name as customer_name
                    ,reservation.customer_id as customer_id
                    ,folio.id as folio_id
		    ,reservation_room.adult
		    ,reservation_room.child
		    ,reservation_room.child_5
                FROM
                    room_status
                    inner join reservation_room on room_status.reservation_room_id=reservation_room.id
                    left join party on reservation_room.user_id=party.user_id
                    inner join reservation on reservation_room.reservation_id=reservation.id
                    left join room on reservation_room.room_id=room.id
                    left join room_level on reservation_room.room_level_id=room_level.id
                    inner join customer on reservation.customer_id = customer.id
                    inner join customer_group on customer.group_id = customer_group.id
                    left join traveller_folio on traveller_folio.type='ROOM' AND traveller_folio.invoice_id=room_status.id
                    left join folio on traveller_folio.folio_id=folio.id                    
                WHERE
                    ".$cond." AND reservation_room.status != 'CANCEL' AND reservation_room.status!='NOSHOW'
                    AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                ORDER BY
                    room.name ,room_status.in_date,reservation_room.id
                ";                        
        $report = DB::fetch_all($sql);
        //System::debug($sql);        
        $sql_payment = "
                SELECT 
                    'RESERVATION_' || '_' || room_status.id || '_' || folio.id || '_' || mice_invoice.id as id
                    ,folio.id as folio_id
                    ,room_status.id as room_status_id
                    ,room_status.in_date
                    ,reservation_room.arrival_time
                    ,reservation_room.departure_time
                    ,reservation_room.status as reservation_room_status
                    ,NVL(reservation_room.change_room_from_rr,0) as change_from
                    ,NVL(reservation_room.change_room_to_rr,0) as change_to
                    ,reservation_room.foc
                    ,NVL(reservation_room.foc_all,0) as foc_all
                    ,reservation.customer_id as customer_id
                    ,party.name_".Portal::language()." as user_name
                    ,payment.time as payment_time
                    ,nvl(traveller_folio.total_amount,0) as total_payment
                    ,folio.total as total_folio
                    ,nvl(mice_invoice_detail.total_amount,0) as total_payment_mice
                    ,mice_invoice.payment_time as payment_time_mice
                    ,mice_invoice.total_amount as total_mice
                FROM
                    room_status
                    inner join reservation_room on room_status.reservation_room_id=reservation_room.id
                    inner join reservation on reservation_room.reservation_id=reservation.id
                    left join party on reservation_room.user_id=party.user_id
                    inner join room_level on reservation_room.room_level_id=room_level.id
                    left join customer on reservation.customer_id = customer.id
                    left join customer_group on customer.group_id = customer_group.id
                    left join traveller_folio on traveller_folio.type='ROOM' AND traveller_folio.invoice_id=room_status.id
                    left join folio on traveller_folio.folio_id=folio.id
                    left join payment on payment.folio_id=folio.id
                    left join room on reservation_room.room_id=room.id
                    left join mice_invoice_detail on mice_invoice_detail.type='ROOM' AND mice_invoice_detail.invoice_id=room_status.id
                    left join mice_invoice on mice_invoice_detail.mice_invoice_id=mice_invoice.id
                    
                WHERE
                    ".$cond." AND reservation_room.status != 'CANCEL' AND reservation_room.status!='NOSHOW'
                    AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                ORDER BY
                    room_status.in_date,reservation_room.id
                ";
        $payment_arr = DB::fetch_all($sql_payment);
        //System::debug($payment_arr);        
        foreach($report as $key=>$value)
        {
            
            /** tinh gia **/
            if(($value['arrival_time']==$value['departure_time'] AND $value['change_to']!=0) OR ($value['arrival_time']!=$value['departure_time'] AND $value['in_date']==$value['departure_time']))
            {
                /** loai bo th doi phong dayuse **/
                unset($report[$key]);
            }
            else
            {
                if($value['net_price']==1 AND $value['reduce_balance']==0 AND $value['reduce_amount']==0)
                {
                    /** gia da co thue phi va khong co giam gia **/
                    $report[$key]['price'] = $value['price'];
                    $report[$key]['price_before_tax'] = $value['price']/(1+$value['tax_rate']/100);
                }
                else
                {
                    if($value['net_price']==1)
                    {
                        $value['price'] = $value['price']/((1+$value['tax_rate']/100)*(1+$value['service_rate']/100));
                    }
                    $value['price'] = $value['price'] - ($value['price']*$value['reduce_balance']/100);
                    if($value['in_date']==$value['arrival_time'])
                    {
                        $value['price'] = $value['price'] - $value['reduce_amount'];
                    }
                    $report[$key]['price_before_tax'] = $value['price']*(1+$value['service_rate']/100);
                    $value['price'] = $value['price']*((1+$value['tax_rate']/100)*(1+$value['service_rate']/100));
                    $report[$key]['price'] = $value['price'];
                }
                if($value['foc']!='' OR $value['foc_all']!=0)
                {
                    $report[$key]['price_before_tax'] = 0;
                    $report[$key]['price'] = 0;
                    $report[$key]['note'] = $value['note']."<i> ( FOC )</i>";
                }
                if($value['reservation_room_status']=='CANCEL')
                {
                    $report[$key]['price'] = 0;
                    $report[$key]['note'] = $value['note']."<i> ( Phòng đổi trạng thái từ BOOK->CANCEL )</i>";
                }
                $report[$key]['payment_price'] = 0;
            }
            if(isset($report[$key]))
            {
                $report[$key]['in_date'] = Date_Time::convert_orc_date_to_date($value['in_date']);
                //$report[$key]['link'] = "?page=reservation&cmd=edit&id=".$value['reservation_id']."&r_r_id=".$value['reservation_room_id'];
                /** Minh fix link sang hoa don **/                
                $report[$key]['link'] = '?page=view_traveller_folio&cmd=group_invoice&customer_id='.$value['customer_id'].'&id='.$value['reservation_id'].'&folio_id='.$value['folio_id'];
                $report[$key]['link_detail'] = "?page=reservation&cmd=edit&id=".$value['reservation_id']."&r_r_id=".$value['reservation_room_id'];                
                $report[$key]['number_of_vote'] = $value['reservation_id'];
            }
        }
        foreach($payment_arr as $key=>$value)
        {
            /** tinh gia **/
            if(($value['arrival_time']==$value['departure_time'] AND $value['change_to']!=0) OR ($value['arrival_time']!=$value['departure_time'] AND $value['in_date']==$value['departure_time']))
            {
                
            }
            else
            {
                if(isset($report[$value['room_status_id']]))
                {
                    if($value['foc']=='' AND $value['foc_all']==0)
                    {
                        if($value['payment_time']!='' || ($value['total_folio']!='' and $value['total_folio']<=0))
                        {
                            $report[$value['room_status_id']]['payment_price'] +=  $value['total_payment'];
                        }
                        if($value['payment_time_mice']!='' || ($value['total_mice']!='' and $value['total_mice']<=0))
                        {
                            $report[$value['room_status_id']]['payment_price'] += $value['total_payment_mice'];
                        }
                    }
                }
            }
        }
        return $report;
    }
    /** hàm lấy doanh thu dịch vụ **/
    function get_extra_service_revenue($cond)
    {
        $sql = "
                SELECT
                    concat(concat(extra_service.name,'_'),extra_service_invoice_detail.id) as id
                    ,extra_service_invoice_detail.price
                    ,extra_service_invoice_detail.quantity+nvl(extra_service_invoice_detail.change_quantity,0) as quantity
                    ,extra_service_invoice_detail.in_date
                    ,extra_service_invoice_detail.percentage_discount
                    ,extra_service_invoice_detail.amount_discount
                    ,extra_service_invoice_detail.note
                    ,room.name as room
                    ,party.name_".Portal::language()." as user_name
                    ,NVL(reservation_room.foc_all,0) as foc_all
                    ,reservation_room.id as reservation_room_id
                    ,reservation_room.status as reservation_room_status
                    ,reservation.id as reservation_id
                    ,extra_service_invoice.id as extra_service_invoice_id
                    ,extra_service_invoice.bill_number
                    ,extra_service_invoice.net_price
                    ,extra_service_invoice.tax_rate
                    ,extra_service_invoice.service_rate
                    ,customer.name as customer_name
                FROM
                    extra_service_invoice_detail
                    inner join extra_service_invoice on extra_service_invoice.id=extra_service_invoice_detail.invoice_id
                    inner join extra_service on extra_service.id = extra_service_invoice_detail.service_id
                    left join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
                    left join reservation on reservation_room.reservation_id=reservation.id
                    left join room on room.id=reservation_room.room_id
                    left join room_level on reservation_room.room_level_id=room_level.id
                    left join party on party.user_id=extra_service_invoice.user_id
                    left join customer on reservation.customer_id = customer.id
                    left join customer_group on customer.group_id = customer_group.id
                WHERE
                    ".$cond."
                    AND ((reservation_room.status != 'CANCEL' AND reservation_room.status!='NOSHOW') or reservation_room.status is null)
                    AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                ORDER BY
                    extra_service.name, extra_service_invoice_detail.id
                ";
        $report = DB::fetch_all($sql);
        //System::debug($report);        
        $sql_payment = "
                SELECT
                    concat(concat(extra_service_invoice_detail.id,folio.id),mice_invoice.id) as id
                    ,concat(concat(extra_service.name,'_'),extra_service_invoice_detail.id) as key
                    ,NVL(reservation_room.foc_all,0) as foc_all
                    ,extra_service_invoice_detail.in_date
                    ,payment.time as payment_time
                    ,nvl(traveller_folio.total_amount,0) as total_payment
                    ,folio.total as total_folio
                    ,nvl(mice_invoice_detail.total_amount,0) as total_payment_mice
                    ,mice_invoice.payment_time as payment_time_mice
                    ,mice_invoice.total_amount as total_mice
                FROM
                    extra_service_invoice_detail
                    inner join extra_service_invoice on extra_service_invoice.id=extra_service_invoice_detail.invoice_id
                    inner join extra_service on extra_service.id = extra_service_invoice_detail.service_id
                    left join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
                    left join reservation on reservation_room.reservation_id=reservation.id
                    left join room on room.id=reservation_room.room_id
                    left join room_level on reservation_room.room_level_id=room_level.id
                    left join party on party.user_id=extra_service_invoice.user_id
                    left join customer on reservation.customer_id = customer.id
                    left join customer_group on customer.group_id = customer_group.id
                    left join traveller_folio on traveller_folio.type='EXTRA_SERVICE' AND traveller_folio.invoice_id=extra_service_invoice_detail.id
                    left join folio on traveller_folio.folio_id=folio.id
                    left join payment on payment.folio_id=folio.id
                    left join mice_invoice_detail on mice_invoice_detail.type='EXTRA_SERVICE' AND mice_invoice_detail.invoice_id=extra_service_invoice_detail.id
                    left join mice_invoice on mice_invoice_detail.mice_invoice_id=mice_invoice.id
                WHERE
                    ".$cond."
                    AND ((reservation_room.status != 'CANCEL' AND reservation_room.status!='NOSHOW') or reservation_room.status is null)
                    AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                ORDER BY
                    extra_service.name, extra_service_invoice_detail.id
                ";
        $payment_arr = DB::fetch_all($sql_payment);
        //System::debug($report);        
        foreach($report as $key=>$value)
        {
            if($value['net_price']==1)
            {
                $value['price'] = $value['price']/((1+$value['tax_rate']/100)*(1+$value['service_rate']/100));
            }
            $value['price'] -= $value['amount_discount'];
            $value['price']  = $value['price'] - ($value['price']*$value['percentage_discount']/100);
            $report[$key]['price_before_tax'] = $value['price']*$value['quantity']*(1+$value['service_rate']/100);
            $value['price'] = $value['price']*((1+$value['tax_rate']/100)*(1+$value['service_rate']/100));
            $report[$key]['price'] = $value['price']*$value['quantity'];
            if($value['foc_all']!=0)
            {
                $report[$key]['price'] = 0;
                $report[$key]['price_before_tax'] = 0;
                $report[$key]['note'] = $value['note']."<i> ( FOC )</i>";
            }
            $report[$key]['payment_price'] = 0;
            if($value['reservation_room_status']=='CANCEL')
            {
                $report[$key]['price'] = 0;
                $report[$key]['note'] = $value['note']."<i> ( Phòng đổi trạng thái từ BOOK->CANCEL )</i>";
            }
            $report[$key]['link'] = "?page=extra_service_invoice&cmd=view_receipt&id=".$value['extra_service_invoice_id'];
            $report[$key]['number_of_vote'] = $value['bill_number'];
            $report[$key]['link_recode'] = "?page=reservation&cmd=edit&id=".$value['reservation_id']."&r_r_id=".$value['reservation_room_id'];
            $report[$key]['in_date'] = Date_Time::convert_orc_date_to_date($value['in_date']);
            
            unset($report[$key]['extra_service_invoice_id']);
            unset($report[$key]['foc_all']);
            unset($report[$key]['quantity']);
            unset($report[$key]['percentage_discount']);
            unset($report[$key]['amount_discount']);
            unset($report[$key]['net_price']);
            unset($report[$key]['tax_rate']);
            unset($report[$key]['service_rate']);
        }
        foreach($payment_arr as $key=>$value)
        {
            if(isset($report[$value['key']]))
            {
                if($value['foc_all']==0)
                {
                    if($value['payment_time']!='' || ($value['total_folio']!='' and $value['total_folio']<=0))
                    {
                        $report[$value['key']]['payment_price'] +=  $value['total_payment'];
                    }
                    if($value['payment_time_mice']!='' || ($value['total_mice']!='' and $value['total_mice']<=0))
                    {
                        $report[$value['key']]['payment_price'] += $value['total_payment_mice'];
                    }
                }
            }
        }
        return $report;
    }
    /** hàm lấy doanh thu buồng **/
    function get_housekeeping($cond,$type)
    {
        $sql = "
                SELECT
                    concat('".$type."_',housekeeping_invoice.id) as id
                    ,housekeeping_invoice.id as housekeeping_invoice_id
                    ,housekeeping_invoice.position
                    ,housekeeping_invoice.time as in_date
                    ,housekeeping_invoice.total as price
                    ,housekeeping_invoice.tax_rate
                    ,room.name as room
                    ,party.name_".Portal::language()." as user_name
                    ,NVL(reservation_room.foc_all,0) as foc_all
                    ,reservation_room.id as reservation_room_id
                    ,reservation_room.status as reservation_room_status
                    ,reservation.id as reservation_id
                    ,housekeeping_invoice.note
                    ,customer.name as customer_name
                    ,payment.time as payment_time
                    ,(select sum(traveller_folio.total_amount) from traveller_folio where (traveller_folio.type= 'EQUIPMENT' or traveller_folio.type= 'MINIBAR' or traveller_folio.type= 'LAUNDRY') AND traveller_folio.invoice_id=housekeeping_invoice.id) as total_payment
                FROM
                    housekeeping_invoice
                    inner join reservation_room on reservation_room.id=housekeeping_invoice.reservation_room_id
                    inner join reservation on reservation_room.reservation_id=reservation.id
                    inner join room on room.id=reservation_room.room_id
                    inner join room_level on room.room_level_id=room_level.id
                    inner join party on party.user_id=housekeeping_invoice.user_id
                    left join customer on reservation.customer_id = customer.id
                    left join customer_group on customer.group_id = customer_group.id
                    left join traveller_folio on (traveller_folio.type= 'EQUIPMENT' or traveller_folio.type= 'MINIBAR' or traveller_folio.type= 'LAUNDRY') AND traveller_folio.invoice_id=housekeeping_invoice.id
                    left join folio on traveller_folio.folio_id=folio.id
                    left join payment on payment.folio_id=folio.id
                WHERE
                    ".$cond."
                    AND ((reservation_room.status != 'CANCEL' AND reservation_room.status!='NOSHOW') or reservation_room.status is null)
                    AND housekeeping_invoice.type = '".$type."'
                    AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                ORDER BY
                    housekeeping_invoice.time, reservation_room.id
                ";
        $report = DB::fetch_all($sql);        
        $sql_payment = "
                SELECT
                    concat(concat(housekeeping_invoice.id,folio.id),mice_invoice.id) as id
                    ,concat('".$type."_',housekeeping_invoice.id) as key
                    ,NVL(reservation_room.foc_all,0) as foc_all
                    ,housekeeping_invoice.time as in_date
                    ,payment.time as payment_time
                    ,case 
                    when folio.id is not null
                    then nvl(traveller_folio.total_amount,0)
                    else housekeeping_invoice.total
                    end as total_payment
                    ,folio.total as total_folio
                    ,nvl(mice_invoice_detail.total_amount,0) as total_payment_mice
                    ,mice_invoice.payment_time as payment_time_mice
                    ,mice_invoice.total_amount as total_mice
                FROM
                    housekeeping_invoice
                    inner join reservation_room on reservation_room.id=housekeeping_invoice.reservation_room_id
                    inner join reservation on reservation_room.reservation_id=reservation.id
                    inner join room on room.id=reservation_room.room_id
                    inner join room_level on reservation_room.room_level_id=room_level.id
                    inner join party on party.user_id=housekeeping_invoice.user_id
                    left join customer on reservation.customer_id = customer.id
                    left join customer_group on customer.group_id = customer_group.id
                    left join traveller_folio on (traveller_folio.type= 'EQUIPMENT' or traveller_folio.type= 'MINIBAR' or traveller_folio.type= 'LAUNDRY') AND traveller_folio.invoice_id=housekeeping_invoice.id
                    left join folio on traveller_folio.folio_id=folio.id
                    left join payment on payment.folio_id=folio.id
                    left join mice_invoice_detail on (mice_invoice_detail.type= 'EQUIPMENT' or mice_invoice_detail.type= 'MINIBAR' or mice_invoice_detail.type= 'LAUNDRY') AND mice_invoice_detail.invoice_id=housekeeping_invoice.id
                    left join mice_invoice on mice_invoice_detail.mice_invoice_id=mice_invoice.id
                WHERE
                    ".$cond."
                    AND ((reservation_room.status != 'CANCEL' AND reservation_room.status!='NOSHOW') or reservation_room.status is null)
                    AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                ORDER BY
                    housekeeping_invoice.time, reservation_room.id
                ";
        $payment_arr = DB::fetch_all($sql_payment);
        //System::debug($payment_arr);
        foreach($report as $key=>$value)
        {
            $report[$key]['price_before_tax'] = $value['price']/(1+$value['tax_rate']/100);
            if($value['foc_all']!=0)
            {
                $report[$key]['price'] = 0;
                $report[$key]['price_before_tax'] = 0;
                $report[$key]['note'] = $value['note']."<i> ( FOC )</i>";
            }
            $report[$key]['payment_price'] = 0;
            if($value['reservation_room_status']=='CANCEL')
            {
                $report[$key]['price'] = 0;
                $report[$key]['price_before_tax'] = 0;
                $report[$key]['note'] = $value['note']."<i> ( Phòng đổi trạng thái từ BOOK->CANCEL )</i>";
            }
            $report[$key]['number_of_vote'] = $value['position'];
            /** Minh fix link sang hoa don **/
            if($type=='MINIBAR')
            {
                $report[$key]['link'] = "?page=minibar_invoice&cmd=detail&id=".$value['housekeeping_invoice_id'];
                $report[$key]['link_detail'] = "?page=minibar_invoice";
            }                
            elseif($type=='LAUNDRY')
            {
                $report[$key]['link'] = "?page=laundry_invoice&cmd=detail&id=".$value['housekeeping_invoice_id'];
                $report[$key]['link_detail'] = "?page=laundry_invoice";
            }                
            else
            {
                $report[$key]['link'] = "?page=equipment_invoice&cmd=detail&id=".$value['housekeeping_invoice_id'];
                $report[$key]['link_detail'] = "?page=equipment_invoice";
                $report[$key]['number_of_vote'] = $value['housekeeping_invoice_id'];
            }
            $report[$key]['in_date'] = date('d-m-Y',$value['in_date']);
            $report[$key]['link_recode'] = "?page=reservation&cmd=edit&id=".$value['reservation_id']."&r_r_id=".$value['reservation_room_id'];
            unset($report[$key]['foc_all']);
            unset($report[$key]['housekeeping_invoice_id']);
        }
        foreach($payment_arr as $key=>$value)
        {
            if(isset($report[$value['key']]))
            {
                if($value['foc_all']==0)
                {
                    if($value['payment_time']!='' || ($value['total_folio']!='' and $value['total_folio']<=0))
                    {
                        $report[$value['key']]['payment_price'] +=  $value['total_payment'];
                    }
                    if($value['payment_time_mice']!='' || ($value['total_mice']!='' and $value['total_mice']<=0))
                    {
                        $report[$value['key']]['payment_price'] += $value['total_payment_mice'];
                    }
                }
            }
        }
        //System::debug($report);
        return $report;
    }
    /** hàm lấy doanh thu nhà hàng **/
    function get_bar($cond,$bar)
    {
        $sql = "
                SELECT
                    concat('BAR_',bar_reservation.id) as id
                    ,bar_reservation.code
                    ,bar.code as bar_code
                    ,bar_reservation.arrival_time as in_date
                    ,bar_reservation.total as price
                    ,bar_reservation.tax_rate
                    ,bar_reservation.id as bar_reservation_id
                    ,bar_reservation.bar_id as bar_id
                    ,NVL(reservation_room.foc_all,0) as foc_all
                    ,reservation_room.id as reservation_room_id
                    ,reservation.id as reservation_id
                    ,room.name as room
                    ,party.name_".Portal::language()." as user_name
                    ,bar_reservation.note
                    ,cr.name as customer_name_r
                    ,cb.name as customer_name_b
                    ,payment.time as payment_time
                    ,case
                    when folio.id is not null
                    then (select sum(traveller_folio.total_amount) from traveller_folio where traveller_folio.type='BAR' AND traveller_folio.invoice_id=bar_reservation.id)
                    else bar_reservation.total
                    end as total_payment
                FROM
                    bar_reservation
                    inner join bar on bar_reservation.bar_id = bar.id
                    left join reservation_room on reservation_room.id = bar_reservation.reservation_room_id
                    left join reservation on reservation_room.reservation_id=reservation.id
                    left join room on room.id=reservation_room.room_id
                    left join room_level on room.room_level_id=room_level.id
                    inner join party on party.user_id=bar_reservation.user_id
                    left join customer cr on reservation.customer_id = cr.id
                    left join customer cb on bar_reservation.customer_id = cb.id 
                    left join customer_group on cr.group_id = customer_group.id                                   
                    left join traveller_folio on traveller_folio.type='BAR' AND traveller_folio.invoice_id=bar_reservation.id
                    left join folio on traveller_folio.folio_id=folio.id
                    left join payment on (payment.type='BAR' AND payment.bill_id=bar_reservation.id) OR (payment.folio_id=folio.id)
                WHERE
                    ".$cond."
                    AND ((reservation_room.status != 'CANCEL' AND reservation_room.status!='NOSHOW') or reservation_room.status is null)
                    and bar.code = '".$bar."'
                    AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                ORDER BY
                    bar_reservation.time, reservation_room.id
                ";
        $report = DB::fetch_all($sql);
        $sql_payment = "
                SELECT
                    concat(concat(concat('BAR_',bar_reservation.id),folio.id),mice_invoice.id) as id
                    ,concat('BAR_',bar_reservation.id) as key
                    ,bar_reservation.arrival_time as in_date
                    ,NVL(reservation_room.foc_all,0) as foc_all
                    ,payment.time as payment_time
                    ,case 
                    when folio.id is not null
                    then nvl(traveller_folio.total_amount,0)
                    else bar_reservation.total
                    end as total_payment
                    ,folio.total as total_folio
                    ,nvl(mice_invoice_detail.total_amount,0) as total_payment_mice
                    ,mice_invoice.payment_time as payment_time_mice
                    ,mice_invoice.total_amount as total_mice
                    ,party.name_".Portal::language()." as user_name
                FROM
                    bar_reservation
                    left join reservation_room on reservation_room.id = bar_reservation.reservation_room_id
                    left join reservation on reservation_room.reservation_id=reservation.id
                    left join room_level on reservation_room.room_level_id=room_level.id
                    inner join party on party.user_id=bar_reservation.user_id
                    left join room on room.id=reservation_room.room_id
                    left join customer cr on reservation.customer_id = cr.id
                    left join customer cb on bar_reservation.customer_id = cb.id  
                    left join customer_group on cr.group_id = customer_group.id                                      
                    left join traveller_folio on traveller_folio.type='BAR' AND traveller_folio.invoice_id=bar_reservation.id
                    left join folio on traveller_folio.folio_id=folio.id
                    left join payment on (payment.type='BAR' AND payment.bill_id=bar_reservation.id AND payment.type_dps is null) OR (payment.folio_id=folio.id)
                    left join mice_invoice_detail on mice_invoice_detail.type='BAR' AND mice_invoice_detail.invoice_id=bar_reservation.id
                    left join mice_invoice on mice_invoice_detail.mice_invoice_id=mice_invoice.id
                WHERE
                    ".$cond."
                    AND ((reservation_room.status != 'CANCEL' AND reservation_room.status!='NOSHOW') or reservation_room.status is null)
                    AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                ORDER BY
                    bar_reservation.time, reservation_room.id
                ";
        $payment_arr = DB::fetch_all($sql_payment);
        //System::debug($payment_arr);
        foreach($report as $key=>$value)
        {
            $report[$key]['price_before_tax'] = $value['price']/(1+$value['tax_rate']/100);
            if($value['foc_all']!=0)
            {
                $report[$key]['price'] = 0;
                $report[$key]['price_before_tax'] = 0;
                $report[$key]['note'] = $value['note']."<i> ( FOC )</i>";
            }
            $report[$key]['payment_price'] = 0;
            
            $report[$key]['link']="?page=touch_bar_restaurant&cmd=detail&316c9c3ed45a83ee318b1f859d9b8b79=d001cd500100d09a0a074a7a7ea55702&5ebeb6065f64f2346dbb00ab789cf001=1&id=".$value['bar_reservation_id']."&bar_id=".$value['bar_id']."";
            
            $report[$key]['in_date'] = date('d-m-Y',$value['in_date']);
            $report[$key]['link_recode'] = "?page=reservation&cmd=edit&id=".$value['reservation_id']."&r_r_id=".$value['reservation_room_id'];
            $report[$key]['link_detail'] = "?page=bar_reservation";
            unset($report[$key]['bar_reservation_id']);
            unset($report[$key]['bar_id']);
            unset($report[$key]['foc_all']);
        }
        //System::debug($payment_arr);
        foreach($payment_arr as $key=>$value)
        {
            if(isset($report[$value['key']]))
            {
                if($value['foc_all']==0)
                {
                    if($value['payment_time']!='' || ($value['total_folio']!='' and $value['total_folio']<=0))
                    {
                        $report[$value['key']]['payment_price'] +=  $value['total_payment'];
                    }
                    if($value['payment_time_mice']!='' || ($value['total_mice']!='' and $value['total_mice']<=0))
                    {
                        $report[$value['key']]['payment_price'] += $value['total_payment_mice'];
                    }
                }
            }
        }
        return $report;
    }
    
    /** Hàm lấy doanh thu spa **/
    function get_spa($cond)
    {
        $sql = "
                SELECT
                    concat('SPA_',massage_reservation_room.id) as id
                    ,massage_reservation_room.id as massage_reservation_room_id
                    ,massage_reservation_room.time as in_date
                    ,massage_reservation_room.total_amount as price
                    ,massage_reservation_room.tax as tax_rate
                    ,massage_reservation_room.note as note
                    ,reservation_room.id as reservation_room_id
                    ,NVL(reservation_room.foc_all,0) as foc_all
                    ,reservation.id as reservation_id
                    ,party.name_".Portal::language()." as user_name
                    ,room.name as room
                    ,customer.name as customer_name                                                                                
                    ,payment.time as payment_time
                    ,case
                    when folio.id is not null
                    then (select sum(traveller_folio.total_amount) from traveller_folio where traveller_folio.type='MASSAGE' AND traveller_folio.invoice_id=massage_reservation_room.id)
                    else massage_reservation_room.total_amount
                    end as total_payment
                FROM
                    massage_reservation_room
                    left join reservation_room on reservation_room.id=massage_reservation_room.hotel_reservation_room_id
                    left join reservation on reservation_room.reservation_id=reservation.id
                    left join room on room.id=reservation_room.room_id
                    left join room_level on room.room_level_id=room_level.id
                    inner join party on party.user_id=massage_reservation_room.user_id
                    left join customer on reservation.customer_id = customer.id
                    left join customer_group on customer.group_id = customer_group.id                                        
                    left join traveller_folio on traveller_folio.type='MASSAGE' AND traveller_folio.invoice_id=massage_reservation_room.id
                    left join folio on traveller_folio.folio_id=folio.id
                    left join payment on (payment.type='SPA' AND payment.bill_id=massage_reservation_room.id) OR (payment.folio_id=folio.id)
                WHERE
                    ".$cond."
                    AND ((reservation_room.status != 'CANCEL' AND reservation_room.status!='NOSHOW') or reservation_room.status is null)
                    AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                ORDER BY
                    massage_reservation_room.time, massage_reservation_room.id
                ";
        $report = DB::fetch_all($sql);
        $sql_payment = "
                SELECT
                    concat(concat(concat('SPA_',massage_reservation_room.id),folio.id),mice_invoice.id) as id
                    ,concat('SPA_',massage_reservation_room.id) as key
                    ,NVL(reservation_room.foc_all,0) as foc_all
                    ,massage_reservation_room.time as in_date
                    ,payment.time as payment_time
                    ,case 
                    when folio.id is not null
                    then nvl(traveller_folio.total_amount,0)
                    else massage_reservation_room.total_amount
                    end as total_payment
                    ,folio.total as total_folio
                    ,nvl(mice_invoice_detail.total_amount,0) as total_payment_mice
                    ,mice_invoice.payment_time as payment_time_mice
                    ,mice_invoice.total_amount as total_mice
                    ,party.name_".Portal::language()." as user_name
                FROM
                    massage_reservation_room
                    left join reservation_room on reservation_room.id=massage_reservation_room.hotel_reservation_room_id
                    left join reservation on reservation_room.reservation_id=reservation.id
                    left join customer on reservation.customer_id = customer.id
                    left join customer_group on customer.group_id = customer_group.id
                    left join room_level on reservation_room.room_level_id=room_level.id
                    inner join party on party.user_id=massage_reservation_room.user_id
                    left join room on room.id=reservation_room.room_id
                    left join traveller_folio on traveller_folio.type='MASSAGE' AND traveller_folio.invoice_id=massage_reservation_room.id
                    left join folio on traveller_folio.folio_id=folio.id
                    left join payment on (payment.type='SPA' AND payment.bill_id=massage_reservation_room.id) OR (payment.folio_id=folio.id)
                    left join mice_invoice_detail on mice_invoice_detail.type='MASSAGE' AND mice_invoice_detail.invoice_id=massage_reservation_room.id
                    left join mice_invoice on mice_invoice_detail.mice_invoice_id=mice_invoice.id
                WHERE
                    ".$cond."
                    AND ((reservation_room.status != 'CANCEL' AND reservation_room.status!='NOSHOW') or reservation_room.status is null)
                    AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                ORDER BY
                    massage_reservation_room.time, massage_reservation_room.id
                ";
        $payment_arr = DB::fetch_all($sql_payment);
        foreach($report as $key=>$value)
        {
            $report[$key]['price_before_tax'] = $value['price']/(1+$value['tax_rate']/100);
            if($value['foc_all']!=0)
            {
                $report[$key]['price'] = 0;
                $report[$key]['price_before_tax'] = 0;
                $report[$key]['note'] = $value['note']."<i> ( FOC )</i>";
            }
            $report[$key]['payment_price'] = 0;
            $report[$key]['link']="?page=massage_daily_summary&cmd=invoice&id=".$value['massage_reservation_room_id']."";
            $report[$key]['in_date'] = date('d-m-Y',$value['in_date']);
            $report[$key]['link_recode'] = "?page=reservation&cmd=edit&id=".$value['reservation_id']."&r_r_id=".$value['reservation_room_id'];
            unset($report[$key]['foc_all']);
        }
        foreach($payment_arr as $key=>$value)
        {
            if(isset($report[$value['key']]))
            {
                if($value['foc_all']==0)
                {
                    if($value['payment_time']!='' || ($value['total_folio']!='' and $value['total_folio']<=0))
                    {
                        $report[$value['key']]['payment_price'] +=  $value['total_payment'];
                    }
                    if($value['payment_time_mice']!='' || ($value['total_mice']!='' and $value['total_mice']<=0))
                    {
                        $report[$value['key']]['payment_price'] += $value['total_payment_mice'];
                    }
                }
            }
        }
        return $report;      
    }
    /** hàm lấy doanh thu đặt tiệc **/
    function get_party($cond)
    {
        $sql = "
                SELECT
                    concat('PARTY_',party_reservation.id) as id
                    ,party_reservation.id as party_reservation_id
                    ,party_reservation.party_type
                    ,party_reservation.checkin_time as in_date
                    ,party_reservation.total as price
                    ,party_reservation.vat as tax_rate
                    ,party_type.note || ' ' || party_reservation.note as note
                    ,party.name_".Portal::language()." as user_name
                    ,payment.time as payment_time
                    ,payment.amount as total_payment
                    ,customer.name as customer_name
                FROM
                    party_reservation
                    left join party_type on party_type.id=party_reservation.party_type
                    left join party on party.user_id=party_reservation.user_id
                    left join mice_reservation on mice_reservation.id = party_reservation.mice_reservation_id
                    left join customer on mice_reservation.customer_id = customer.id
                    left join customer_group on customer_group.id = customer.group_id
                    left join payment on payment.type='BANQUET' AND payment.bill_id=party_reservation.id                    
                WHERE
                    ".$cond."
                    AND party_reservation.status!='CANCEL'
                ORDER BY
                    party_reservation.time, party_reservation.id
                ";
        //System::debug($sql);
        $report = DB::fetch_all($sql);                
        $sql_payment = "
                SELECT
                    concat(concat('PARTY_',party_reservation.id),mice_invoice.id) as id
                    ,concat('PARTY_',party_reservation.id) as key
                    ,party_reservation.checkin_time as in_date
                    ,payment.time as payment_time
                    ,party_reservation.total as total_payment
                    ,nvl(mice_invoice_detail.total_amount,0) as total_payment_mice
                    ,mice_invoice.payment_time as payment_time_mice
                    ,mice_invoice.total_amount as total_mice
                    ,customer.name as customer_name
                FROM
                    party_reservation
                    left join party_type on party_type.id=party_reservation.party_type
                    left join party on party.user_id=party_reservation.user_id
                    left join mice_reservation on mice_reservation.id = party_reservation.mice_reservation_id
                    left join customer on mice_reservation.customer_id = customer.id
                    left join customer_group on customer_group.id = customer.group_id
                    left join payment on payment.type='BANQUET' AND payment.bill_id=party_reservation.id
                    left join mice_invoice_detail on mice_invoice_detail.type='BANQUET' AND mice_invoice_detail.invoice_id=party_reservation.id
                    left join mice_invoice on mice_invoice_detail.mice_invoice_id=mice_invoice.id
                WHERE
                    ".$cond."
                    AND party_reservation.status!='CANCEL'
                ORDER BY
                    party_reservation.time, party_reservation.id
                ";                       
        $payment_arr = DB::fetch_all($sql_payment);
        foreach($report as $key=>$value)
        {
            $report[$key]['price_before_tax'] = $value['price']/(1+$value['tax_rate']/100);
            $report[$key]['payment_price'] = 0;
            $report[$key]['link']="?page=banquet_reservation&cmd=".$value['party_type']."&action=edit&id=".$value['party_reservation_id'];
            $report[$key]['in_date'] = date('d-m-Y',$value['in_date']);
            $report[$key]['link_recode'] = "#";
        }
        foreach($payment_arr as $key=>$value)
        {
            if(isset($report[$value['key']]))
            {
                //if($value['foc_all']==0)
                //{
                    if($value['payment_time']!='')
                    {
                        $report[$value['key']]['payment_price'] +=  $value['total_payment'];
                    }
                    if($value['payment_time_mice']!='' || ($value['total_mice']!='' and $value['total_mice']<=0))
                    {
                        $report[$value['key']]['payment_price'] += $value['total_payment_mice'];
                    }
                //}
            }
        }        
        return $report;
    }
    
    /** hàm lấy doanh thu bán hàng **/
    function get_vend($cond)
    {
        $sql = "
                SELECT
                    concat('VEND_',ve_reservation.id) as id
                    ,ve_reservation.code
                    ,NVL(reservation_room.foc_all,0) as foc_all
                    ,ve_reservation.time as in_date
                    ,ve_reservation.total as price
                    ,ve_reservation.tax_rate
                    ,ve_reservation.id as ve_reservation_id
                    ,ve_reservation.department_id
                    ,ve_reservation.department_code
                    ,party.name_".Portal::language()." as user_name
                    ,ve_reservation.note
                    ,reservation_room.id as reservation_room_id
                    ,reservation_room.reservation_id as reservation_id
                    ,customer.name as customer_name
                    ,payment.time as payment_time
                    ,case
                    when folio.id is not null
                    then (select sum(traveller_folio.total_amount) from traveller_folio where traveller_folio.type='VEND' AND traveller_folio.invoice_id=ve_reservation.id)
                    else ve_reservation.total
                    end as total_payment
                FROM
                    ve_reservation
                    inner join party on party.user_id=ve_reservation.user_id
                    LEFT JOIN reservation_room ON ve_reservation.reservation_room_id = reservation_room.id
                    LEFT JOIN reservation ON reservation_room.reservation_id = reservation.id
                    left join customer on reservation.customer_id = customer.id
                    left join customer_group on customer.group_id = customer_group.id
                    left join traveller_folio on traveller_folio.type='VE' AND traveller_folio.invoice_id=ve_reservation.id
                    left join folio on traveller_folio.folio_id=folio.id
                    left join payment on (payment.type='VEND' AND payment.bill_id=ve_reservation.id) OR (payment.folio_id=folio.id)
                WHERE
                    ".$cond."
                    AND ((reservation_room.status != 'CANCEL' AND reservation_room.status!='NOSHOW') or reservation_room.status is null)
                ORDER BY
                    ve_reservation.time, ve_reservation.id
                ";
        $report = DB::fetch_all($sql);
        $sql_payment = "
                SELECT
                    concat(concat(concat('VEND_',ve_reservation.id),folio.id),mice_invoice.id) as id
                    ,concat('VEND_',ve_reservation.id) as key
                    ,NVL(reservation_room.foc_all,0) as foc_all
                    ,ve_reservation.code
                    ,ve_reservation.time as in_date
                    ,payment.time as payment_time
                    ,case 
                    when folio.id is not null
                    then nvl(traveller_folio.total_amount,0)
                    else ve_reservation.total
                    end as total_payment
                    ,folio.total as total_folio
                    ,nvl(mice_invoice_detail.total_amount,0) as total_payment_mice
                    ,mice_invoice.payment_time as payment_time_mice
                    ,mice_invoice.total_amount as total_mice
                FROM
                    ve_reservation
                    inner join party on party.user_id=ve_reservation.user_id
                    LEFT JOIN reservation_room ON ve_reservation.reservation_room_id = reservation_room.id
                    LEFT JOIN reservation ON reservation_room.reservation_id = reservation.id
                    left join customer on reservation.customer_id = customer.id
                    left join customer_group on customer.group_id = customer_group.id
                    left join traveller_folio on traveller_folio.type='VE' AND traveller_folio.invoice_id=ve_reservation.id
                    left join folio on traveller_folio.folio_id=folio.id
                    left join payment on (payment.type='VEND' AND payment.bill_id=ve_reservation.id) OR (payment.folio_id=folio.id)
                    left join mice_invoice_detail on mice_invoice_detail.type='VEND' AND mice_invoice_detail.invoice_id=ve_reservation.id
                    left join mice_invoice on mice_invoice_detail.mice_invoice_id=mice_invoice.id
                WHERE
                    ".$cond."
                    AND ((reservation_room.status != 'CANCEL' AND reservation_room.status!='NOSHOW') or reservation_room.status is null)
                ORDER BY
                    ve_reservation.time, ve_reservation.id
                ";
        $payment_arr = DB::fetch_all($sql_payment);
        foreach($report as $key=>$value)
        {
            $report[$key]['price_before_tax'] = $value['price']/(1+$value['tax_rate']/100);
            $report[$key]['payment_price'] = 0;
            $report[$key]['link']="?page=automatic_vend&cmd=edit&id=".$value['ve_reservation_id']."&department_id=".$value['department_id']."&department_code=".$value['department_code'];
            
            $report[$key]['in_date'] = date('d-m-Y',$value['in_date']);
            $report[$key]['link_recode'] = "?page=reservation&cmd=edit&id=".$value['reservation_id']."&r_r_id=".$value['reservation_room_id'];
        }
        foreach($payment_arr as $key=>$value)
        {
            if(isset($report[$value['key']]))
            {
                if($value['foc_all']==0)
                {
                    if($value['payment_time']!='' || ($value['total_folio']!='' and $value['total_folio']<=0))
                    {
                        $report[$value['key']]['payment_price'] +=  $value['total_payment'];
                    }
                    if($value['payment_time_mice']!='' || ($value['total_mice']!='' and $value['total_mice']<=0))
                    {
                        $report[$value['key']]['payment_price'] += $value['total_payment_mice'];
                    }
                }
            }
        }
        return $report;
    }
    /** hàm lấy doanh thu bán vé **/
    function get_ticket($cond)
    {
        $sql = "
                SELECT
                    concat('TICKET_',ticket_invoice.id) as id
                    ,ticket_invoice.total as price
                    ,ticket_invoice.date_used as in_date
                    ,ticket.name as number_of_vote
                    ,party.name_".Portal::language()." as user_name
                    ,'Ticket group ' || ticket_group.name as note
                    ,payment.time as payment_time
                    ,case
                    when folio.id is not null
                    then (select sum(traveller_folio.total_amount) from traveller_folio where traveller_folio.type='TICKET' AND traveller_folio.invoice_id=ticket_invoice.id)
                    else ticket_invoice.total
                    end as total_payment
                FROM 
                    ticket_invoice
                    inner join ticket on ticket.id=ticket_invoice.ticket_id
                    inner join ticket_group on ticket_group.id=ticket_invoice.ticket_area_id
                    inner join party on party.user_id=ticket_invoice.user_id
                    left join traveller_folio on traveller_folio.type='TICKET' AND traveller_folio.invoice_id=ticket_invoice.id
                    left join folio on traveller_folio.folio_id=folio.id
                    left join payment on (payment.type='TICKET' AND payment.bill_id=ticket_invoice.id) OR (payment.folio_id=folio.id)
                WHERE
                    ".$cond."
                ORDER BY
                    ticket_invoice.date_used, ticket_invoice.id
                ";
        $report = DB::fetch_all($sql);
        $sql_payment = "
                SELECT
                    concat(concat(concat('TICKET_',ticket_invoice.id),folio.id),mice_invoice.id) as id
                    ,concat('TICKET_',ticket_invoice.id) as key
                    ,ticket_invoice.total as price
                    ,ticket_invoice.date_used as in_date
                    ,payment.time as payment_time
                    ,case 
                    when folio.id is not null
                    then nvl(traveller_folio.total_amount,0)
                    else ticket_invoice.total
                    end as total_payment
                    ,folio.total as total_folio
                    ,nvl(mice_invoice_detail.total_amount,0) as total_payment_mice
                    ,mice_invoice.payment_time as payment_time_mice
                    ,mice_invoice.total_amount as total_mice
                FROM
                    ticket_invoice
                    inner join ticket on ticket.id=ticket_invoice.ticket_id
                    inner join ticket_group on ticket_group.id=ticket_invoice.ticket_area_id
                    inner join party on party.user_id=ticket_invoice.user_id
                    left join traveller_folio on traveller_folio.type='TICKET' AND traveller_folio.invoice_id=ticket_invoice.id
                    left join folio on traveller_folio.folio_id=folio.id
                    left join payment on (payment.type='TICKET' AND payment.bill_id=ticket_invoice.id) OR (payment.folio_id=folio.id)
                    left join mice_invoice_detail on mice_invoice_detail.type='TICKET' AND mice_invoice_detail.invoice_id=ticket_invoice.id
                    left join mice_invoice on mice_invoice_detail.mice_invoice_id=mice_invoice.id
                WHERE
                    ".$cond."
                ORDER BY
                    ticket_invoice.date_used, ticket_invoice.id
                ";
        $payment_arr = DB::fetch_all($sql_payment);
        foreach($report as $key=>$value)
        {
            $report[$key]['link']="#";
            $report[$key]['in_date'] = Date_Time::convert_orc_date_to_date($value['in_date']);
            $report[$key]['link_recode'] = "#";
            $report[$key]['payment_price'] = 0;
        }
        foreach($payment_arr as $key=>$value)
        {
            if(isset($report[$value['key']]))
            {
                if($value['payment_time']!='' || ($value['total_folio']!='' and $value['total_folio']<=0))
                {
                    $report[$value['key']]['payment_price'] +=  $value['total_payment'];
                }
                if($value['payment_time_mice']!='' || ($value['total_mice']!='' and $value['total_mice']<=0))
                {
                    $report[$value['key']]['payment_price'] += $value['total_payment_mice'];
                }
            }
        }
        return $report;
    }
    /** hàm lấy doanh thu karaoke **/
    function get_karaoke($cond)
    {
        $sql = "
                SELECT
                    concat('KARAOKE_',karaoke_reservation.id) as id
                    ,karaoke_reservation.code
                    ,karaoke_reservation.time as in_date
                    ,karaoke_reservation.total as price
                    ,karaoke_reservation.tax_rate
                    ,karaoke_reservation.id as karaoke_reservation_id
                    ,karaoke_reservation.karaoke_id as karaoke_id
                    ,NVL(reservation_room.foc_all,0) as foc_all
                    ,reservation_room.id as reservation_room_id
                    ,reservation.id as reservation_id
                    ,room.name as room
                    ,party.name_".Portal::language()." as user_name
                    ,karaoke_reservation.note
                    ,payment.time as payment_time
                    ,case
                    when folio.id is not null
                    then (select sum(traveller_folio.total_amount) from traveller_folio where traveller_folio.type='KARAOKE' AND traveller_folio.invoice_id=karaoke_reservation.id)
                    else karaoke_reservation.total
                    end as total_payment
                FROM
                    karaoke_reservation
                    left join reservation_room on reservation_room.id = karaoke_reservation.reservation_room_id
                    left join reservation on reservation_room.reservation_id=reservation.id
                    left join room on room.id=reservation_room.room_id
                    left join room_level on room.room_level_id=room_level.id
                    inner join party on party.user_id=karaoke_reservation.user_id
                    left join traveller_folio on traveller_folio.invoice_id=karaoke_reservation.id AND traveller_folio.type='KARAOKE'
                    left join folio on traveller_folio.folio_id=folio.id
                    left join payment on (payment.type='KARAOKE' AND payment.bill_id=karaoke_reservation.id) OR (payment.folio_id=folio.id)
                WHERE
                    ".$cond."
                    AND ((reservation_room.status != 'CANCEL' AND reservation_room.status!='NOSHOW') or reservation_room.status is null)
                    AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                ORDER BY
                    karaoke_reservation.time, reservation_room.id
                ";
        $report = DB::fetch_all($sql);
        $sql_payment = "
                SELECT
                    concat(concat(concat('KARAOKE_',karaoke_reservation.id),folio.id),mice_invoice.id) as id
                    ,karaoke_reservation.code
                    ,NVL(reservation_room.foc_all,0) as foc_all
                    ,karaoke_reservation.time as in_date
                    ,payment.time as payment_time
                    ,case 
                    when folio.id is not null
                    then nvl(traveller_folio.total_amount,0)
                    else karaoke_reservation.total
                    end as total_payment
                    ,folio.total as total_folio
                    ,nvl(mice_invoice_detail.total_amount,0) as total_payment_mice
                    ,mice_invoice.payment_time as payment_time_mice
                    ,mice_invoice.total_amount as total_mice
                FROM
                    karaoke_reservation
                    left join reservation_room on reservation_room.id = karaoke_reservation.reservation_room_id
                    left join reservation on reservation_room.reservation_id=reservation.id
                    left join room_level on reservation_room.room_level_id=room_level.id
                    left join traveller_folio on traveller_folio.invoice_id=karaoke_reservation.id AND traveller_folio.type='KARAOKE'
                    left join folio on traveller_folio.folio_id=folio.id
                    left join payment on (payment.type='KARAOKE' AND payment.bill_id=karaoke_reservation.id) OR (payment.folio_id=folio.id)
                    left join mice_invoice_detail on mice_invoice_detail.type='KARAOKE' AND mice_invoice_detail.invoice_id=karaoke_reservation.id
                    left join mice_invoice on mice_invoice_detail.mice_invoice_id=mice_invoice.id
                WHERE
                    ".$cond."
                    AND ((reservation_room.status != 'CANCEL' AND reservation_room.status!='NOSHOW') or reservation_room.status is null)
                    AND (room_level.is_virtual=0 OR room_level.is_virtual is null)
                ORDER BY
                    karaoke_reservation.time, reservation_room.id
                ";
        $payment_arr = DB::fetch_all($sql_payment);
        foreach($report as $key=>$value)
        {
            $report[$key]['price_before_tax'] = $value['price']/(1+$value['tax_rate']/100);
            if($value['foc_all']==0)
            {
                $report[$key]['price_before_tax'] = 0;
                $report[$key]['price'] = 0;
                $report[$key]['note'] = $value['note']."<i> ( FOC )</i>";
            }
            $report[$key]['link']="?page=karaoke_touch&cmd=detail&316c9c3ed45a83ee318b1f859d9b8b79=f7531e2d0ea27233ce00b5f01c5bf335&5ebeb6065f64f2346dbb00ab789cf001=1&id=".$value['karaoke_reservation_id']."&karaoke_id=".$value['karaoke_id']."";
            $report[$key]['payment_price'] = 0;
            $report[$key]['in_date'] = date('d-m-Y',$value['in_date']);
            $report[$key]['link_recode'] = "?page=reservation&cmd=edit&id=".$value['reservation_id']."&r_r_id=".$value['reservation_room_id'];
        }
        foreach($payment_arr as $key=>$value)
        {
            if(isset($report[$value['key']]))
            {
                if($value['foc_all']==0)
                {
                    if($value['payment_time']!='' || ($value['total_folio']!='' and $value['total_folio']<=0))
                    {
                        $report[$value['key']]['payment_price'] +=  $value['total_payment'];
                    }
                    if($value['payment_time_mice']!='' || ($value['total_mice']!='' and $value['total_mice']<=0))
                    {
                        $report[$value['key']]['payment_price'] += $value['total_payment_mice'];
                    }
                }
            }
        }
        return $report;
    }
    
    /** hàm định dạng giờ sang unit **/
    function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
    
    /** hàm kiểm tra sự tồn tại của một Id trong mảng **/
    
    function id_like_array($id,$array)
    {
        if(sizeof($array)>0)
        {
            $check  = false;
            foreach($array as $key=>$value)
            {
                if($id==$key)
                {
                    $check = true;
                }
            }
            return $check;
        }
        else
        {
            return false;
        }
    }
    
}
?>
