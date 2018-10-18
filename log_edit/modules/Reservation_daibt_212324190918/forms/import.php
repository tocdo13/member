<?php
class ImportTravellerForm extends Form{
	function ImportTravellerForm()
    {
		Form::Form('ImportTravellerForm');
		$this->link_css("packages/hotel/skins/default/css/invoice.css");		
		$this->link_js('packages/core/includes/js/jquery/window/jquery.window.js');
		$this->link_js('packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js');
        $this->link_js('packages/hotel/packages/reception/modules/includes/common01.js');
		$this->link_css("packages/hotel/skins/default/css/jquery.windows-engine.css");	
		$this->link_js('packages/hotel/packages/reception/modules/includes/reservation.js');
		$this->link_js('packages/hotel/includes/js/ajax.js');	
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');		
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_css('packages/hotel/'.Portal::template('reception').'/css/style.css');
		$this->link_css(Portal::template('hotel').'/css/style.css');		
		$this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');     
	   $this->_success = '';
       $this->col_name=array('1'=>Portal::language('room_name'),'2'=>Portal::language('frist_name'),'3'=>Portal::language('last_name'),'4'=>Portal::language('hang_khach'),'5'=>Portal::language('time_checkin'),'6'=>Portal::language('time_checkout'));
	}
	function on_submit()
    {
        if(Url::get('save')){
            if(isset($_SESSION)){
				require_once 'packages/hotel/packages/reception/modules/includes/reservation.php';
				$id;$rr_id = null;
                if(Url::get('r_id'))
                {
					$id =  Url::get('r_id');	
				}
                if(Url::get('rr_id'))
                {
					$rr_id =  Url::get('rr_id');	
				}
				$sql = '
					SELECT   
						reservation_traveller.id
						,traveller.first_name
                        ,traveller.last_name 
                        ,traveller.gender 
						,traveller.birth_date
                        ,traveller.is_child
						,traveller.passport
                        ,traveller.member_code
						,reservation_traveller.visa_number as visa
						,reservation_traveller.expire_date_of_visa
						,traveller.note 
						,traveller.phone 
                        ,traveller.fax 
                        ,traveller.address 
						,traveller.email 
						,traveller.nationality_id 
						,reservation_room.reservation_id
						,reservation_traveller.reservation_room_id
						,traveller.id as traveller_id
					FROM
						reservation_traveller
						inner join traveller on traveller.id=reservation_traveller.traveller_id
						left outer join reservation_room on reservation_room.id = reservation_traveller.reservation_room_id
					WHERE
						reservation_traveller.reservation_id='.$id.'
						'.(Url::get('rr_id')?' and reservation_traveller.reservation_room_id='.Url::get('rr_id').'':'').'
				';
				$old_travellers = DB::fetch_all($sql);
                //System::debug($old_travellers);
				$title = ''; 
                $description = ''; 
                $customer_name='';
                $_REQUEST['r_id']= $id;
                
                $_REQUEST['cmd']='group_traveller';
                if(isset($_SESSION['mi_traveller'])){
                    $_REQUEST['mi_traveller']=$_SESSION['mi_traveller'];
                    //System::debug($_REQUEST['mi_traveller']);exit();
                    update_reservation_traveller($this, $id,$rr_id, $old_travellers, $title, $description, $customer_name,$change_status);
                    $this->_success = $_SESSION['error'];
                    session_unset('mi_traveller'); 
                    session_unset('error');  
                    $_SESSION['home_page'] = '';
                }           
            }        	
        }
	}
   
	function draw()
    {	
		$this->map = array();
        //tieubinh làm trên stand , (trên develop lỗi)
        $this->map['result'] = array();
        $this->map['show_popub']=0;
        $this->map['room_double']=array();
        $this->map['check'] = 1;
        $room_double = DB::fetch_all('
                                     select 
                                        ROW_NUMBER()
                                        OVER (ORDER BY room.id) AS id
                                        ,CASE WHEN room.name is null 
                                            THEN reservation_room.temp_room
                                        ELSE room.name 
                                        END name
                                        ,reservation_room.time_in
                                        ,reservation_room.time_out
                                        ,count(room.name) as count_room 
                                     from 
                                        reservation_room
                                            inner join reservation on reservation_room.reservation_id = reservation.id
                                            left join room on reservation_room.room_id  = room.id
                                        where reservation.id = '.Url::get('r_id').'
                                     group by
                                        room.name ,reservation_room.temp_room ,time_in,time_out, room.id
                                     ');
        $j=0;  
                               
        foreach($room_double as $key=>$val){
            $room_double[$val['name']][$j]['time_in']=$val['time_in'];
            $room_double[$val['name']][$j]['time_out']=$val['time_out'];
            $j++;
            unset($room_double[$key]);
        }
        foreach($room_double as $key=>$val){
            if(count($val)<=1)
                unset($room_double[$key]);       
        }                 
        $this->map['room_double']=$room_double;
        if(Url::get('do_upload'))
		{
            $excel =array();
            $file = $this->save_file('path_file');	
            require_once 'packages/core/includes/utils/PHPExcel/IOFactory.php';
			$objReader = new PHPExcel_Reader_Excel5();
			$objPHPExcel = $objReader->load($file);			
			$excel = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
            if(!empty($excel)){
                unset($excel[1]);
                //System::debug($excel);
                $result = $this->parse_excel($excel,$room_double);
                $this->map['result']=$result['result'];
                $this->map['preview'] = $result['preview'];
                foreach($result['preview'] as $k=>$v)
                {
                    //unset($result['preview'][$k]['traveller_level_id']);
                }
                $_SESSION['mi_traveller'] = $result['preview'];
                //System::debug($_SESSION['mi_traveller']);
                $_SESSION['error'] = $result['error'];
            }
            
            $title = array('1'=>'Room name','2'=>'Frist name','3'=>'Last name','4'=>'Hạng khách','5'=>'Time Checkin','6'=>'Time checkout');
            if(count($this->col_name)>0){
                foreach($title as $k=>$v){
                    if(!isset($this->col_name[$k])){
                        $this->col_name[$k]=$v;
                        unset($title[$k]);
                    }
                }
            };
		}
       ksort($this->col_name);
       $this->map['col_name'] = $this->col_name;
       foreach($this->map['result'] as $key=>$val){
            $key_miss=array_diff_key($this->col_name,$val);
            foreach($key_miss as $k=>$v){
                $this->map['result'][$key][$k]='';
            }
            ksort($this->map['result'][$key]);
       }
       $this->map['success']= $this->_success;
	   $this->parse_layout('import',$this->map);
	}
    
    
    
    function parse_excel($excel,$room_double)
	{
        require_once'packages/core/includes/utils/vn_code.php';
        $row_content=array();
        $this->result = array();
        $preview = array();
        $i = 0;
        $title = array('A','B','C','D','E','F','G','H','I',
                       'J','K','L','M','N','O','P','Q','R',
                       'S','T','U','V','W','X','Y','Z','AA',
                       'AB','AC','AD','AE','AF','AG','AH','AI','AJ');
        $content_key = array(
                            'stt','mi_traveller_room_name','first_name','last_name',
                            'passport','transit','visa','expire_date_of_visa',
                            'gender','birth_date','nationality_id','province_id',
                            'traveller_level_id','phone','email','address',
                            'arrival_hour','traveller_arrival_date','departure_hour','traveller_departure_date',
                            'to_judge','note',
                            'flight_code','flight_arrival_hour','flight_arrival_date','car_note_arrival',
                            'flight_code_departure','flight_departure_hour','flight_departure_date','car_note_departure',
                            'pickup','pickup_foc','see_off','see_off_foc',
                            'member_code','is_child'
                            );                    
        foreach($excel as $key=>$col)
        {
            
            $j=0;
            foreach($title as $k=>$val){
                if($col[$title[$k]] !=''){
                    $j=1;break;
                }    
            }
            if($j==0)
                unset($excel[$key]);
        }  

        foreach($excel as $key=>$col){
            $i++;
            foreach($content_key as $k=>$val){
                $row_content[$i][$content_key[$k]]=trim($col[$title[$k]]);
                $row_content[$i]['reservation_room_id']='';
            }
            unset($excel[$key]);
        } 
        $room = DB::fetch_all('select name as id,name from room');
        $room_in_reservation = DB::fetch_all('select room.name as id
                                            from reservation_room inner join room on reservation_room.room_id =room.id
                                            where reservation_id='.Url::get('r_id').'');
        $traveller_in_room = DB::fetch_all('select 
                                                traveller.id as id,
                                                traveller.first_name,
                                                traveller.last_name, 
                                                traveller.passport
                                            from
                                                reservation_room
                                                inner join room on reservation_room.room_id =room.id
                                                inner join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
                                                left join traveller on reservation_traveller.traveller_id = traveller.id
                                            where
                                                reservation_room.reservation_id='.Url::get('r_id').'
                        
        ');
        //System::debug($traveller_in_room);
        //$check=true;
        foreach($row_content as $key=>$val){
            $this->date_checkin='';
            $this->hour_checkin='';
            $this->hour_checkout='';
            $this->date_checkout='';
            $time_checkin='';
            $time_checkout='';
            $row_content[$key]['reservation_traveller_id']='';
            $row_content[$key]['traveller_room_id']=''; 
            
            $province = DB::fetch_all('select UPPER(code) as id,id as province_id from province');
            $passport = DB::fetch_all('select passport as id,id as traveller_id from traveller where passport !=\'?\'');
            $count = DB::fetch_all('select code_1 as id,code_1 from country');
            $guest_type = DB::fetch_all('select UPPER(name) as id, id as guest_id from guest_type');
            $this->result[$key]['error'] = '';
            $date_regex = '/(0[1-9]|[12][0-9]|3[01])[\/.](0[1-9]|1[012])[\/.](19|20)\d\d/';
            $time_regex='/(0[0-9]|[10-24]):0[0-9]|[10-59]{2}/';
            $phone_regex = "/^[0-9]+$/";
            $this->check_data($val['mi_traveller_room_name'],'Tên phòng không được để trống','true','',$key,'',1,'Room name','1_mi_traveller_room_name','');
            $this->check_data($val['first_name'],'First name không được để trống','true','',$key,'',2,'First name','2_first_name','');
            $this->check_data($val['last_name'],'Last name không được để trống','true','',$key,'',3,'Last name','3_last_name','');
            $this->check_data($val['traveller_level_id'],'Tên Hạng khách không đúng','off','',$key,'',4,'Tên hạng khách','4_traveller_level_id',$guest_type);
            $this->check_data($val['arrival_hour'],'Giờ check in không đúng định dạng (H:i)',$time_regex,'hour_checkin',$key,':',5,'Time/Date check in','5_arrival_hour','');
            $this->check_data($val['departure_hour'],'Giờ check out không đúng định dạng thời gian (H:i)',$time_regex,'hour_checkout',$key,':',6,'Time/Date check out','5_departure_hour','');
            $this->check_data($val['traveller_arrival_date'],'Ngày check in Không đúng định dạng (d/m/Y)',$date_regex,'date_checkin',$key,'/',5,'Time/Date check in','6_traveller_arrival_date','');
            $this->check_data($val['traveller_departure_date'],'Ngày check out Không đúng định dạng (d/m/Y)',$date_regex,'date_checkout',$key,'/',6,'Time/Date check out','6_traveller_departure_date','');
            $this->check_data($val['expire_date_of_visa'],'Hạn visa Không đúng định dạng (d/m/Y)',$date_regex,'',$key,'/',9,'Hạn visa','7_expire_date_of_visa','');
            $this->check_data($val['birth_date'],'Ngày sinh Không đúng định dạng (d/m/Y)',$date_regex,'',$key,'/',10,'Ngày sinh','8_birth_date','');
            $this->check_data($val['flight_arrival_hour'],'Giờ Máy bay đến không đúng định dạng (H:i)',$time_regex,'',$key,'/',11,'Giờ máy bay đến','9_flight_arrival_hour','');
            $this->check_data($val['flight_departure_hour'],'Ngày máy bay đến không đúng định dạng thời gian (H:i)',$time_regex,'',$key,'/',12,'Ngày máy bay đi','10_flight_departure_hour','');
            $this->check_data($val['flight_arrival_date'],'Giờ Máy bay đi Không đúng định dạng (d/m/Y)',$date_regex,'',$key,'/',13,'Giờ máy bay đi','11_flight_arrival_date','');
            $this->check_data($val['flight_departure_date'],'Ngày Máy bay đi Không đúng định dạng (d/m/Y)',$date_regex,'',$key,'/',14,'Ngày máy bay đi','12_flight_departure_date','');  
            $this->check_data($val['email'],'Không đúng định dạng Email','email','',$key,'',15,'Email','13_email','');
            $this->check_data($val['phone'],'Phone phải có định dạng số 0 đầu tiên,các số theo sau từ 0->9',$phone_regex,'',$key,'',16,'Phone','14_phone','');
            $this->check_data($val['gender'],'Giới tính chỉ ghi (nam | nu | hoặc để trống)','gender','',$key,'',17,'Giới tính','15_gender','');
            $this->check_data($val['transit'],'Transit chỉ 1 trong 2 trạng thái(on | off)','on','',$key,'',18,'Transit','16_transit','');
            $this->check_data($val['pickup'],'Pickup chỉ 1 trong 2 trạng thái(on | off)','on','',$key,'',19,'Pickup','pickup','');
            $this->check_data($val['pickup_foc'],'Pickup_foc chỉ 1 trong 2 trạng thái(on | off)','on','',$key,'',20,'Pickup foc','17_pickup_foc','');
            $this->check_data($val['see_off'],'See_off chỉ 1 trong 2 trạng thái(on | off)','on','',$key,'',21,'See off','18_see_off','');
            $this->check_data($val['see_off_foc'],'See_off_foc chỉ 1 trong 2 trạng thái(on | off)','on','',$key,'',22,'See off foc','19_see_off_foc','');
            $this->check_data($val['member_code'],'Member_code chỉ 1 trong 2 trạng thái(on | off)','on','',$key,'',23,'Member code','20_member_code','');
            $this->check_data($val['province_id'],'Mã tỉnh không đúng','off','',$key,'',24,'Province','24_province_id',$province);
            $this->check_data($val['is_child'],'Is child chỉ 1 trong 2 trạng thái(yes | no)','yes','',$key,'',25,'Child','25_is_child','');
            
            
            if(!isset($room_in_reservation[$val['mi_traveller_room_name']])){
                $this->result[$key]['error'] .='<span style="color: red;display:block">Phòng này không nằm trong các phòng đã đặt.</span>'; 
            }
            $row_content[$key]['traveller_id_']='';   
            if($val['passport'] !='' and trim($val['passport']) !='?'){
                if(isset($passport[$val['passport']])){
                    $row_content[$key]['traveller_id_']=$passport[$val['passport']]['traveller_id'];                
                }
                else
                //chỗ này sẽ xóa đi sau
                    $row_content[$key]['traveller_id_']='';           
            }             
            if(isset($room_double[$val['mi_traveller_room_name']])){
                if($val['arrival_hour'] =='' or $val['traveller_arrival_date'] =='' or $val['departure_hour'] =='' or $val['traveller_departure_date'] ==''){
                    $this->result[$key]['error'] .='<span style="color: red;display:block">Phòng này bắt buộc phải nhập thời gian checkin,</span><span>checkout,ngày checkin checkout.</span>'; 
                }else{
                    if($this->hour_checkin and $this->date_checkin)
                        $time_checkin= mktime($this->hour_checkin[0],$this->hour_checkin[1],0,$this->date_checkin[1],$this->date_checkin[0],$this->date_checkin[2]);
                    if($this->hour_checkout and $this->date_checkout)
                        $time_checkout= mktime($this->hour_checkout[0],$this->hour_checkout[1],0,$this->date_checkout[1],$this->date_checkout[0],$this->date_checkout[2]);
                    if($time_checkin and $time_checkout){
                        foreach($room_double as $k=>$v){
                        $j=0;
                        foreach($v as $v_time){
                            if($v_time['time_in'] ==$time_checkin and $v_time['time_out']==$time_checkout){
                                $j=1;break;
                            } 
                        }
                        if($j==0 or $v_time = '')
                            $this->result[$key]['error'] .='<span style="color: red;display:block">Thời gian và ngày checkin,checkout của phòng này chưa đúng với thời gian checkin , check của ngày hôm đấy.</span>'; 
                        }
                    }    
                }
            }
            /** Daund: check time in & out của phòng khi nhập tên khách */
            /*$time_in = Date_Time::to_time($val['traveller_arrival_date']) + $this->calc_time($val['arrival_hour']);
            $time_out = Date_Time::to_time($val['traveller_departure_date']) + $this->calc_time($val['departure_hour']);
            $cond_nd = 'reservation_room.time_in <= \''.$time_in.'\'';
            $cond_nd .= ' AND reservation_room.time_out >= \''.$time_out.'\'';
            $arr_time = DB::fetch('
                            SELECT 
                                reservation_room.id,
                                CASE 
                                    WHEN reservation_room.room_id is not null
                                    THEN concat(room.id,concat(\'-\',to_char(reservation_room.departure_time,\'DD/MM/YYYY\'))) 
                                    ELSE concat(reservation_room.temp_room,concat(\'-\',to_char(reservation_room.departure_time,\'DD/MM/YYYY\'))) 
                                END as traveller_room_id
                            FROM
                                reservation_room
                                inner join room on reservation_room.room_id =room.id
                            WHERE
                                '.$cond_nd.'
                                AND reservation_room.reservation_id = \''.Url::get('r_id').'\'
            ');
            if(empty($arr_time))
            {
                $this->result[$key]['error'] .='<span style="color: red;display:block">Thời gian và ngày checkin,checkout của phòng này chưa đúng với thời gian checkin , check của ngày hôm đấy.</span>';
            }else
            {
                if($this->hour_checkin and $this->date_checkin)
                    $time_checkin= mktime($this->hour_checkin[0],$this->hour_checkin[1],0,$this->date_checkin[1],$this->date_checkin[0],$this->date_checkin[2]);
                if($this->hour_checkout and $this->date_checkout)
                    $time_checkout= mktime($this->hour_checkout[0],$this->hour_checkout[1],0,$this->date_checkout[1],$this->date_checkout[0],$this->date_checkout[2]);
                $row_content[$key]['reservation_room_id'] = $arr_time['id'];
                $row_content[$key]['traveller_room_id'] = $arr_time['traveller_room_id'];
            }*/
            /** End */
            if(!isset($room[$val['mi_traveller_room_name']])){
                $this->result[$key]['error'] .='<span style="color: red;display:block">không tồn tại tên phòng</span>';
                //$check=false;
            }
            if(!empty($traveller_in_room))
            {
                foreach($traveller_in_room as $traveller => $v)
                {
                    if(($v['first_name'] == $val['first_name']) && ($v['last_name'] == $val['last_name']) && ($v['passport'] == $val['passport']))
                    {
                        $this->result[$key]['error'] .='<span style="color: red;display:block">Khách đã tồn tại trong phòng</span>';
                    }                    
                }
            }
            $this->result[$key]['1']= $val['mi_traveller_room_name']; 
            $this->result[$key]['2']= $val['first_name'];  
            $this->result[$key]['3']= $val['last_name'];
            //$this->result[$key]['5']= str_replace('|',':',$val['arrival_hour']).'-'.$val['traveller_arrival_date'];  
            //$this->result[$key]['6']= str_replace('|',':',$val['departure_hour']).'-'.$val['traveller_departure_date'];  
            $this->result[$key]['5']= str_replace('|',':',$val['arrival_hour']);
            $this->result[$key]['6']= str_replace('|',':',$val['departure_hour']);
            $this->result[$key]['4']= $val['traveller_level_id'];
            
            $reservation_room = DB::fetch_all('select reservation_room.id 
                                ,reservation_room.room_id
                                ,reservation_room.time_in
                                ,reservation_room.time_out
                                ,CASE WHEN reservation_room.room_id is not null
                                THEN concat(room.id,concat(\'-\',to_char(reservation_room.departure_time,\'DD/MM/YYYY\'))) 
                                ELSE concat(reservation_room.temp_room,concat(\'-\',to_char(reservation_room.departure_time,\'DD/MM/YYYY\'))) 
                                END as traveller_room_id
                                from
                                reservation_room 
                                inner join room on reservation_room.room_id = room.id
                                where room.name = \''.$val['mi_traveller_room_name'].'\' and room.portal_id = \'#default\'
                                and reservation_room.reservation_id = '.Url::get('r_id').'');
            
            $row_content[$key]['id'] = '';
            if(count($reservation_room)>1){
                foreach($reservation_room as $rr){
                    if($time_checkin and $time_checkin ==$rr['time_in'] and $time_checkout and $time_checkout ==$rr['time_out']){
                        $row_content[$key]['traveller_room_id'] = $rr['traveller_room_id'];
                        $row_content[$key]['reservation_room_id'] = $rr['id'];
                    }
                }
            }else{
                foreach($reservation_room as $rr){
                $row_content[$key]['reservation_room_id'] = $rr['id'];
                $row_content[$key]['traveller_room_id'] = $rr['traveller_room_id'];
                }
            }
            //System::debug($row_content);
            if($row_content[$key]['reservation_room_id'] !='' and trim($row_content[$key]['traveller_id_']) !='' ){
                $rr=$row_content[$key]['reservation_room_id'];
                $tr = $row_content[$key]['traveller_id_'];
                $reservation_traveller = DB::fetch('select id,reservation_room_id from reservation_traveller where reservation_room_id='.$rr.' and traveller_id ='.$tr.'');
                $row_content[$key]['reservation_traveller_id'] =$reservation_traveller['id'];
                $row_content[$key]['id'] = $reservation_traveller['id'];
            }
            if(strtoupper($val['gender'])== trim('NAM'))
                $row_content[$key]['gender'] = 1;
            elseif(strtoupper($val['gender'])== trim('NU'))    
                $row_content[$key]['gender'] = 0;
            else
                $row_content[$key]['gender'] = 2;  
            
            if(strtoupper($val['pickup'])== trim('ON'))
                $row_content[$key]['pickup'] = 1;
            else    
                unset($row_content[$key]['pickup']);  
                
            if(strtoupper($val['see_off'])== trim('ON'))
                $row_content[$key]['see_off'] = 1;
            else    
                unset($row_content[$key]['see_off']);
                 
            if(strtoupper($val['pickup_foc'])== trim('ON'))
                $row_content[$key]['pickup_foc'] = 1;
            else    
                unset($row_content[$key]['pickup_foc']); 
            
            if(strtoupper($val['see_off_foc'])== trim('ON'))
                $row_content[$key]['see_off_foc'] = 1;
            else    
                unset($row_content[$key]['see_off_foc']);      
                   
            if(isset($guest_type[trim(strtoupper($val['traveller_level_id']))]))
            {
                $row_content[$key]['traveller_level_id'] = $guest_type[trim(strtoupper($val['traveller_level_id']))]['guest_id'];
                //$row_content[$key]['traveller_level_id'] = $val['traveller_level_id'];
            }
            if(isset($province[trim(strtoupper($val['province_id']))]))
                $row_content[$key]['province_id'] = $province[trim(strtoupper($val['province_id']))]['province_id'];
            
            if(isset($row_content[$key]['is_child']))
            {
                if(mb_strtolower($row_content[$key]['is_child'])== 'yes')
                {    
                    $row_content[$key]['is_child'] = 'on';
                }else
                {
                    $row_content[$key]['is_child'] = 'off';                    
                }                
            }
            unset($row_content[$key]['stt']);
            if($this->result[$key]['error']=='')
                unset($this->result[$key]);
        }
        $error = 1;
        foreach($this->result as $key=>$val){
            if(trim($val['error']) !=''){
                $error=0;
                break;
            }
        }
        if($error==1)
        {
            $preview=$row_content;
            $error = 'Thêm khách thành công !';
        }else{
            $error = 'Thêm không thành công !';
        }      
        return array('result'=>$this->result,'preview'=>$preview,'error'=>$error);
    }
    
    function check_data($value,$error,$regex,$_data,$key,$re,$num,$col_name,$key_name,$data_2){
        $check=0;
        if($value !=''){
            if($regex =='email' or $regex=='gender' or $regex=='on' or $regex=='off' or $regex =='yes' or $regex == 'no'){
                $gender = array('nam'=>'nam','nu'=>'nu');
                $checkbox = array('on'=>'on','off'=>'off','yes'=>'yes','no'=>'no');
                
                if($regex =='off'){
                    if(!isset($data_2[trim(strtoupper($value))]))
                       $check=1;
                }
                if($regex =='email'){
                    if(!filter_var($value,FILTER_VALIDATE_EMAIL))
                         $check=1;
                }
                if($regex=='gender'){
                    if(!isset($gender[$value]) or !isset($gender[$value]))
                        $check=1;
                }
                if($regex=='on'){
                    if(!isset($checkbox[$value]) or !isset($checkbox[$value]))
                        $check=1;
                }
            }elseif($regex !='' and $regex !='true'){
                if(!preg_match($regex,$value))
                    $check = 1;
                else{
                    if($_data !='')
                        $this->$_data = explode($re,$value);
                }  
            }  
        }else{
            if($regex=='true')
                $check=1;
        }
        if($check==1){
            $this->result[$key]['error'] .='<span style="color: red;display:block">'.$error.'</span>';
            $this->result[$key][$num] = $value;
            $this->col_name[$num]=$col_name;
        }
            
    }
    
     function calc_time($string)
    {
        $arr = explode(':',$string);
        return $arr[0]*3600 + $arr[1]*60;
    }
    function save_file($file)
	{
		require_once 'packages/core/includes/utils/upload_file.php';
		$dir = 'excel';
		update_upload_file('path',$dir);
		return Url::get('path');
	}
}
?>
