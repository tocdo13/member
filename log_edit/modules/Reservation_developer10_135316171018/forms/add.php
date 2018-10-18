<?php
class AddReservationForm extends Form
{
	function AddReservationForm()
	{
		Form::Form('AddReservationForm');
		//---------------------------------------------------------------------------------
		$this->link_css('packages/core/includes/js/jquery/window/css/jquery.window.4.04.css');
		$this->link_js('packages/core/includes/js/jquery/window/jquery.window.js');
		$this->link_js('packages/core/includes/js/jquery/ui/jquery.ui.widget.js');
		$this->link_js('packages/core/includes/js/jquery/ui/jquery.ui.mouse.js');
		$this->link_js('packages/core/includes/js/jquery/ui/jquery.ui.draggable.js');
		$this->link_js('packages/core/includes/js/jquery/ui/jquery.ui.resizable.js');
		$this->link_js('packages/core/includes/js/picker.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.mask.min.js');
		//---------------------------------------------------------------------------------
		$this->link_js('packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js');
		$this->link_css("packages/hotel/skins/default/css/jquery.windows-engine.css");
		//---------------------------------------------------------------------------------
		$this->link_css('packages/hotel/'.Portal::template('reception').'/css/style.css');
		$this->link_css(Portal::template('hotel').'/css/style.css');
		$this->link_js('packages/hotel/includes/js/suggest.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_js('packages/hotel/packages/reception/modules/includes/reservation_table.js');
		$this->link_js('packages/hotel/includes/js/ajax.js');
		$this->link_js('packages/hotel/packages/reception/modules/includes/reservation.js');
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/core/includes/js/jquery/jquery.autocomplete.js');
		$this->link_css('packages/core/skins/default/css/jquery.autocomplete.css');
        $this->link_css('packages/hotel/skins/default/css/w3c.css');
        $this->link_css('packages/hotel/skins/default/css/font-awesome-4.7.0/css/font-awesome.min.css');
		$this->add('note',new TextType(false,'invalid_note',0,200000));
		$this->add('customer_id',new TextType(true,'vui long nhap ten nguon khach',0,255));
		$this->add('tour_id',new TextType(false,'tour_id',0,255));
		$this->add('reservation_room.room_name',new TextType(true,'miss_room',0,255));
		$this->add('reservation_room.room_level_name',new TextType(true,'invalid_room_name',0,255));
		$this->add('reservation_room.room_level_id',new TextType(true,'invalid_room_level_id',0,255));
		
		$this->add('reservation_room.time_in',new TextType(true,'miss_time_in',0,5));
		$this->add('reservation_room.time_out',new TextType(true,'miss_time_out',0,5));
		$this->add('reservation_room.arrival_time',new DateType(true,'arrival_time'));
		$this->add('reservation_room.adult',new IntType(false,'invalid_adult','0','1000'));
		$this->add('reservation_room.child',new IntType(false,'invalid_child','0','1000'));
		$this->add('reservation_room.departure_time',new DateType(true,'departure_time'));
		$this->add('reservation_room.note',new TextType(false,'invalid_note',0,255));
		$this->add('reservation_room.total_amount',new FloatType(false,'invalid_total_amount','0','100000000000'));
		$this->add('reservation_room.reduce_balance',new FloatType(false,'invalid_reduce_balance','0','100000000000'));
		$this->add('reservation_room.deposit',new FloatType(false,'invalid_deposit','0','100000000000'));
		$this->add('reservation_room.tax_rate',new FloatType(false,'invalid_tax_rate','0','100000000000'));
		$this->add('reservation_room.service_rate',new FloatType(false,'invalid_service_rate','0','100000000000'));
		$this->add('reservation_room.status',new SelectType(true,'invalid_status',array('BOOKED'=>Portal::language('booked'),'CHECKIN'=>Portal::language('check_in'),'CHECKOUT'=>Portal::language('check_out'))));
	}
	function on_submit()
	{
	   //System::debug($_REQUEST);exit();
       /** Daund: check trang thai phong clear khong duoc CI */ 
       $mess = '';
       $check = 1;
       foreach($_REQUEST['mi_reservation_room'] as $key => $value)
       {
            if($value['room_id'] !='' AND $value['status'] =='CHECKIN')
            {
                $cond_clean = ' room_status.room_id='.$value['room_id'].' AND room_status.in_date= \''.Date_Time::to_orc_date($value['arrival_time']).'\'';
                if(DB::exists('SELECT room_status.id FROM room_status WHERE'.$cond_clean.'  AND room_status.house_status=\'CLEAN\'  '))
                {
                    $mess .= $value['room_name'] .',';
                    $check = 0;
                }
            }
       }
       if($check == 0)
       {
            $this->error('',$mess .' ở trạng thái CLEAN không thể checkin.');
            return;
       }
       /** END: check trang thai phong clear khong duoc CI */
	   /** Mạnh xử lý lỗi phòng trùng nhau **/
	   $check_price = false;
       $stt_id = 0;
       $check = false;
       $check_room = array();
       
       $sql = "SELECT id FROM customer WHERE customer.id=".Url::get('customer_id');
       $result = DB::fetch($sql);
       
       if(empty($result))
       {
            $this->error('invalid','This_customer_has_been_deleted');
            return;
       }
	
	   /** Manh them de check dong phong **/
       //System::debug($_REQUEST); die;
       $check_close_room = true;
       $list_room_close = array();
       foreach($_REQUEST['mi_reservation_room'] as $mi_id=>$mi_value)
       {
            if($mi_value['room_id']!='' AND $mi_value['status']!='CANCEL')
            {
                $cond_history = '';
                $start_date_his = '';
                $end_date_his = '';
                if( $his_in_date = DB::fetch("select max(in_date) as in_date from room_history where in_date<='".Date_Time::to_orc_date($mi_value['arrival_time'])."' AND portal_id='".PORTAL_ID."'","in_date") )
                {
                    $start_date_his = $his_in_date;
                }
                elseif( $his_in_date = DB::fetch("select min(in_date) as in_date from room_history where in_date>'".Date_Time::to_orc_date($mi_value['arrival_time'])."' AND portal_id='".PORTAL_ID."'","in_date") )
                {
                    $start_date_his = $his_in_date;
                }
                if( $his_in_date = DB::fetch("select max(in_date) as in_date from room_history where in_date<='".Date_Time::to_orc_date($mi_value['departure_time'])."' AND portal_id='".PORTAL_ID."'","in_date") )
                {
                    $end_date_his = $his_in_date;
                }
                elseif( $his_in_date = DB::fetch("select min(in_date) as in_date from room_history where in_date>'".Date_Time::to_orc_date($mi_value['departure_time'])."' AND portal_id='".PORTAL_ID."'","in_date") )
                {
                    $end_date_his = $his_in_date;
                }
                if($start_date_his!='' AND $end_date_his!='')
                {
                    $start_date_his = Date_Time::to_time(Date_Time::convert_orc_date_to_date($start_date_his,'/'));
                    $end_date_his = Date_Time::to_time(Date_Time::convert_orc_date_to_date($end_date_his,'/'));
                    if(!isset($list_room_close[$mi_value['room_id']]))
                    {
                        for($i=$start_date_his;$i<=$end_date_his;$i+=24*3600)
                        {
                            if( DB::exists('SELECT rh.id from room_history rh where rh.in_Date=\''.Date_Time::to_orc_date(date('d/m/Y',$i)).'\' and portal_id=\''.PORTAL_ID.'\'') AND !DB::exists('SELECT rhd.room_id from room_history_detail rhd inner join room_history rh on rh.id=rhd.room_history_id where rh.in_date=\''.Date_Time::to_orc_date(date('d/m/Y',$i)).'\' AND rh.portal_id=\''.PORTAL_ID.'\' AND rhd.close_room=1 AND rhd.room_id='.$mi_value['room_id']))
                            {
                                $check_close_room = false;
                                $list_room_close[$mi_value['room_id']]['name'] = $mi_value['room_name'];
                                $list_room_close[$mi_value['room_id']]['arrival_time'] = $mi_value['arrival_time'];
                                $list_room_close[$mi_value['room_id']]['departure_time'] = $mi_value['departure_time'];
                            }
                        }
                    }
                }
            }
       }
       if($check_close_room==false)
       {
            foreach($list_room_close as $r_close_id=>$r_close_value)
            {
                $this->error('close room'.$r_close_value['name'],'room: '.$r_close_value['name'].' do not use in: '.$r_close_value['arrival_time'].'-'.$r_close_value['departure_time']);    
            }
            return;
       }
       /** end Manh **/
	   /** Manh check phong trung nhau **/
	   $check_price = false;
       $stt_id = 0;
       $check = false;
       $check_room = array();
       foreach($_REQUEST['mi_reservation_room'] as $mi_id=>$mi_value)
       {
            if($mi_value['adult']=='')
            {
                if($mi_value['room_level_id']!='')
                {
                    $adult = DB::fetch("SELECT room_level.num_people as num from room_level Where room_level.id=".$mi_value['room_level_id']);
                    $_REQUEST['mi_reservation_room'][$mi_id]['adult'] = $adult['num'];
                }
                else
                    $_REQUEST['mi_reservation_room'][$mi_id]['adult'] = 1;
            }
            if($mi_value['room_level_name']!='PA')
            {
                if($mi_value['price']>0)
                    $check_price = false;
                else
                    $check_price = true;
            }
            
            $check_book_past_time = false;
            $check_checkin_past_time = false;
            if($mi_value['status']!='CANCEL')
            {
                $stt_id ++;
                $time_in = ($mi_value['time_in']!='')?explode(':',$mi_value['time_in']):0;
                $time_out = ($mi_value['time_out']!='')?explode(':',$mi_value['time_out']):0;
                $arr_time_ = (isset($mi_value['arrival_time']))?Date_Time::to_time($mi_value['arrival_time'])+($time_in[0]*3600+$time_in[1]*60):0;
                $dep_time_ = (isset($mi_value['departure_time']))?Date_Time::to_time($mi_value['departure_time'])+($time_out[0]*3600+$time_out[1]*60):0;
                
                if(sizeof($check_room)>0)
                {
                    foreach($check_room as $id_roo=>$va_roo)
                    {
                        if(($va_roo['room_name']==$mi_value['room_name']) AND ($arr_time_<=$va_roo['departure_time']) AND ($dep_time_>=$va_roo['arrival_time']))
                        {
                            $check = true;
                            break;
                        }
                    }
                }
                $check_room[$stt_id]['room_name'] = $mi_value['room_name'];
                $check_room[$stt_id]['arrival_time'] = Date_Time::to_time($mi_value['arrival_time'])+($time_in[0]*3600+$time_in[1]*60);
                $check_room[$stt_id]['departure_time'] = Date_Time::to_time($mi_value['departure_time'])+($time_out[0]*3600+$time_out[1]*60);
            
                if(Date_Time::to_time($mi_value['arrival_time'])<Date_Time::to_time(Date('d/m/Y')) and $mi_value['status']=='BOOKED')
                    $check_book_past_time = true;
                if(Date_Time::to_time($mi_value['arrival_time'])<Date_Time::to_time(Date('d/m/Y')) and !User::is_admin())
                    $check_checkin_past_time = true;
            }
            unset($_REQUEST['mi_reservation_room'][$mi_id]['package_sale_name']);
       }
       if($check_book_past_time == true)
            $this->add('reservation_room.room_name',new TextType(true,Portal::language('no_booking_past'),255,255));         
       
       if($check_checkin_past_time == true)
            $this->add('reservation_room.room_name',new TextType(true,Portal::language('no_checkin_past_time'),255,255));         
       
       if($check == true)
	    $this->add('reservation_room.room_name',new TextType(true,'phòng trùng nhau',255,255));
       
       /** END mạnh sửa loi phong trung nhau **/
       /** loi luoc do gia bang 0 **/
       if($check_price==true)
        $this->add('reservation_room.price',new FloatType(true,'invalid_price','1','100000000000'));
       else
        $this->add('reservation_room.price',new FloatType(true,'invalid_price','0','100000000000'));
       /** loi luoc doi gia bang 0 **/
        
        if(Url::get('waitingbookid'))
        {
            $id = Url::get('waitingbookid');
            DB::update('waiting_book',array('status'=>'1'),'id='.$id);
        }
        
		if(!Url::get('count_date') and $this->check())
		{
		    /** manh check Allotment **/
            if(isset($_REQUEST['mi_reservation_room']) and USE_ALLOTMENT){
                $customer_id = Url::get('customer_id');
                $update_allotment = array();
                $min_time_in = 0;
                $max_time_out = 0;
                $room_level_ids = '';
                foreach($_REQUEST['mi_reservation_room'] as $key=>$value){
                    if(isset($value['allotment'])){
                        $room_level_id = $value['room_level_id'];
                        $room_level_ids .= $room_level_ids==''?$room_level_id:','.$room_level_id;
                        $time_in = Date_Time::to_time($value['arrival_time']); 
				        $time_out = Date_Time::to_time($value['departure_time']);
                        if($min_time_in==0)
                            $min_time_in = $time_in;
                        if($max_time_out==0)
                            $max_time_out = $time_out;
                        if($min_time_in>=$time_in)
                            $min_time_in = $time_in;
                        if($max_time_out<=$time_out)
                            $max_time_out = $time_out;
                        
                        if($time_in==$time_out){
                            if(isset($update_allotment[$room_level_id]['timeline'][$time_in]))
                                $update_allotment[$room_level_id]['timeline'][$time_in]++;
                            else
                                $update_allotment[$room_level_id]['timeline'][$time_in]=1;
                        }
                        else{
                            for($i=$time_in;$i<$time_out;$i+=86400){
                                if(isset($update_allotment[$room_level_id]['timeline'][$i]))
                                    $update_allotment[$room_level_id]['timeline'][$i]++;
                                else
                                    $update_allotment[$room_level_id]['timeline'][$i]=1;
                            }
                        }
                            
                    }
                }
                //System::debug($update_allotment); die;
                $remain_availability = array();
                if($room_level_ids!=''){
                    $allotment = DB::fetch_all('select 
                                                        room_allotment_avail_rate.*,
                                                        to_char(room_allotment_avail_rate.in_date,\'DD/MM/YYYY\') as in_date,
                                                        room_allotment.room_level_id 
                                                    from 
                                                        room_allotment_avail_rate 
                                                        inner join room_allotment on room_allotment_avail_rate.room_allotment_id=room_allotment.id
                                                    where
                                                        room_allotment.customer_id='.$customer_id.'
                                                        and room_allotment.room_level_id in ('.$room_level_ids.')
                                                        and room_allotment_avail_rate.in_date>=\''.Date_Time::to_orc_date(date('d/m/Y',$min_time_in)).'\'
                                                        and room_allotment_avail_rate.in_date<=\''.Date_Time::to_orc_date(date('d/m/Y',$max_time_out)).'\'
                                                    ');
                    
                    foreach($allotment as $keyAlm=>$valueAlm){
                        if(isset($update_allotment[$valueAlm['room_level_id']]['timeline'][Date_Time::to_time($valueAlm['in_date'])])){
                            if($update_allotment[$valueAlm['room_level_id']]['timeline'][Date_Time::to_time($valueAlm['in_date'])]>$valueAlm['availability']){
                                $this->error('overbook','số lượng phòng trống không đủ cho ngày '.$valueAlm['in_date'],false);
                            }else{
                                if(!isset($remain_availability[$keyAlm])){
                                    $remain_availability[$keyAlm]['old'] = $valueAlm['availability']; 
                                    $remain_availability[$keyAlm]['old_use'] = $valueAlm['availability_use']; 
                                }
                                $remain_availability[$keyAlm]['availability'] = $valueAlm['availability'] - $update_allotment[$valueAlm['room_level_id']]['timeline'][Date_Time::to_time($valueAlm['in_date'])];
                                $remain_availability[$keyAlm]['availability_use'] = $valueAlm['availability_use'] + $update_allotment[$valueAlm['room_level_id']]['timeline'][Date_Time::to_time($valueAlm['in_date'])];
                            }
                        }
                    }
                }
                if($this->is_error())
    			{
    				return;
    			}else{
    			     foreach($remain_availability as $k=>$v){
    			         DB::update('room_allotment_avail_rate',array('availability'=>$v['availability'],'availability_use'=>$v['availability_use']),'id='.$k);
    			     }
    			}
                
            }
            /** end Manh **/ 
			require_once 'packages/hotel/packages/reception/modules/includes/reservation.php';
			$valid_room_array = reservation_check_conflict($this, $add = true);
            check_rooms_repair($this);
            //System::debug($this->is_error()); exit();
			if($this->is_error())
			{
			    /** manh check allotment **/ 
			    if(isset($remain_availability)){
			         foreach($remain_availability as $k=>$v){
    			         DB::update('room_allotment_avail_rate',array('availability'=>$v['old'],'availability_use'=>$v['old_use']),'id='.$k);
    			     }
			    } 
                /** manh check allotment **/ 
				return;
			}
			$old_items = array();
			$old_travellers = array();
			reservation_check_permission($this, false, $old_items);
			if($this->is_error())
			{
			    /** manh check allotment **/ 
			    if(isset($remain_availability)){
			         foreach($remain_availability as $k=>$v){
    			         DB::update('room_allotment_avail_rate',array('availability'=>$v['old']),'id='.$k);
    			     }
			    } 
                /** manh check allotment **/ 
				return;
			}
			$id = DB::insert('reservation',
				array(
					'customer_id',
					'tour_id',
					'note',
					'color',
                    'payment_type1',
                    'booker',
                    'phone_booker',
					'user_id'=>Session::get('user_id'),
                    'email_booker'=>Url::get('email_booker'),
					'time'=>time(),
                    'lastest_user_id'=>Session::get('user_id'),
					'payment'=>str_replace(',','',Url::get('payment')),
					'booking_code',
					'portal_id'=>PORTAL_ID,
                    'is_rate_code'=>isset($_REQUEST['is_rate_code'])?1:0,
                    'last_time'=>time()
				)
			);
			$title = '<p>Make a reservation <a target="_blank" href="?page=reservation&cmd=edit&id='.$id.'">#'.$id.'</a></p>';
            
			$description = ''
				.Portal::language('rcode').': '.$id.' | '.Portal::language('booking_code').': '.Url::get('booking_code').'<br>'
				.(URL::get('tour_id')?Portal::language('tour_name').':<a target="_blank" href="?page=customer&id='.URL::get('customer_id').'">'.DB::fetch('select name from tour where id=\''.URL::get('tour_id').'\'','name').'</a><br>':'')
				.(URL::get('customer_id')?Portal::language('customer_name').':<a target="_blank" href="?page=customer&id='.URL::get('customer_id').'">'.DB::fetch('select name from customer where id=\''.URL::get('customer_id').'\'','name').'</a><br>':'')
				.Portal::language('note').': '.substr(URL::get('note'),0,255).'<br>'
				.'<u class="title">'.Portal::language('room_info').':</u>';
			$customer_name = URL::get('customer_id')?DB::fetch('select customer.name  from customer where id=\''.URL::get('customer_id').'\'','name'):'';
			update_reservation_room($this,$id, $title, $description, $customer_name,$change_status,$old_items);
            
            /** Manh log **/
                $type_log = "ADD";
                $title_log = '<p>Make a reservation <a target="_blank" href="?page=reservation&cmd=edit&id='.$id.'">#'.$id.'</a></p>';
                $description_log = '<h3>Thông tin đặt phòng</h3>
                                        <p>Mã đặt phòng:'.$_REQUEST['booking_code'].'</p>
                                        <p>Mã mặc định:'.$id.'</p>
                                        <p>Tên nguồn khách:'.$_REQUEST['customer_name'].'</p>
                                        <p>Ghi chú: '.$_REQUEST['note'].' </p>
                                        <p>Rate Code: '.(isset($_REQUEST['is_rate_code'])?'true':'false').'
                                    <h3>DANH SÁCH PHÒNG</h3><hr />
                                    ';
                foreach($_REQUEST['mi_reservation_room'] as $key=>$value)
                {
                    // log extrabed và baybycode
                        if(isset($value['extra_bed'])){
                            $extra_bed_des = "<p>ADD extra_bed Phòng ".$value['room_name']." Mã mặc định: ".$id."</p>
                                            <p>Ngày bắt đầu: ".$value['extra_bed_from_date']."</p>
                                            <p>Ngày kết thúc: ".$value['extra_bed_to_date']."</p>
                                            <p>Giá: ".$value['extra_bed_rate']."</p>
                                            ";
                            System::log('EXTRA BED','ADD EXTRA BED RECODE ID:'.$id,$extra_bed_des,$id);
                        }
                        if(isset($value['baby_cot'])){
                            $extra_bed_des = "<p>ADD extra_bed Phòng ".$value['room_name']." Mã mặc định: ".$id."</p>
                                            <p>Ngày bắt đầu: ".$value['baby_cot_from_date']."</p>
                                            <p>Ngày kết thúc: ".$value['baby_cot_to_date']."</p>
                                            <p>Giá: ".$value['baby_cot_rate']."</p>
                                            ";
                            System::log('BABY COT','ADD BABY COT RECODE ID:'.$id,$extra_bed_des,$id);
                        }
                    if(isset($value['net_price']) && $value['net_price']==1)
                    {
                        $net = "YES";
                    }
                    else
                    {
                        $net = "NO";
                    }
                    if($value['reduce_balance']!='')
                    {
                        $reduce = $value['reduce_balance']."%";    
                    }
                    else
                    {
                        $reduce = $value['reduce_amount'];
                    }
                    $foc = $value['foc']?'YES':'NO';
                    $foc_all = isset($value['foc_all'])?'YES':'NO';
                    $description_log .= "<p>Phòng: ".$value['room_name']
                                        ." | Loại phòng: ".$value['room_level_name']
                                        ." | Giá Phòng: ".$value['price']
                                        ." | Trạng thái: ".$value['status']
                                        ." | Từ ngày:".$value['time_in']." ".$value['arrival_time']." Đến ngày: ".$value['time_out']." ".$value['departure_time']
                                        ." | N.Lớn: ".$value['adult']
                                        ." | T.Em: ".$value['child']
                                        ." | T.Em<5: ".$value['child_5'] // add child_5
                                        ." | Giá NET: ".$net
                                        ." | Thuế: ".$value['tax_rate']."%"
                                        ." | Phí DV: ".$value['service_rate']."%"
                                        ." | EI/LO: ".$value['early_checkin'].'/'.$value['late_checkout']
                                        ." | Giảm giá: ".$reduce
                                        ." | Hoa Hồng: ".$value['commission_rate']
                                        ." | FOC/FOC ALL: ".$foc.'/'.$foc_all."</p>"
                                        ;
                }
                $log_id = System::log($type_log,$title_log,$description_log,$id);
                System::history_log('RECODE',$id,$log_id);
            /** end Manh **/
            if($this->is_error())
			{
				return;
			}
            if(Url::get('from_room_using_status') == true)
            {
                echo '<script>
                        opener.location.reload(true);
                    </script>';
            }
			if(Url::get('update'))
            {
				echo '<div id="progress"><img src="packages/core/skins/default/images/updating.gif" /> Updating room status to server...</div>';
				echo '<script>
				if(window.opener && (window.opener.year || window.opener.night_audit))
				{
					window.opener.history.go(0);
					window.close();
				}
				window.setTimeout("location=\''.URL::build_current(array('cmd'=>'edit','id'=>$id)).'\'",2000);
				</script>';
				exit();
			}
            else
            {
                $location = URL::build('room_map',array('just_edited_id'=>$id));
                if(Url::get('layout')=='list')
                {
                    $location = URL::build_current(array('year','month','day', 'status', 'room_id'));
                }
                echo '<script>
                        var r = confirm("Ban co muon them khach khong?");
                        if(r==true)
                        {
                           var newdiv = document.createElement("div");
                           newdiv.setAttribute("id","progress");
                           newdiv.innerHTML = "<img src = \'packages/core/skins/default/images/updating.gif\'/>Updating room status to server...";
                           ni = document.getElementsByTagName("html")[0];
                           ni.appendChild(newdiv);
                           if(window.opener && (window.opener.year || window.opener.night_audit))
            				{
            					window.opener.history.go(0);
            					window.close();
            				} 
                            window.setTimeout("location=\''.URL::build_current(array('cmd'=>'edit','id'=>$id,'r_r_id','year','month','day', 'status', 'room_id','layout','adddd_guest'=>'yes',)).'\'",2000);                          
                        }
                        else
                        {  
                           if(window.opener)
            			   {
            				  //window.close();
            			   }
                           var newdiv = document.createElement("div");
                           newdiv.setAttribute("id","progress");
                           newdiv.innerHTML = "<img src = \'packages/core/skins/default/images/updating.gif\'/>Updating room status to server...";
                           ni = document.getElementsByTagName("html")[0];
                           ni.appendChild(newdiv);
           				   window.setTimeout("location=\''.$location.'\'",2000);  
                        }
                     </script>';
                exit();
			}
		}
	}
	function draw()
	{	   	   
		$this->map = array();
        //start:KID them xu ly lay exchange_rate
        $currency_id = (HOTEL_CURRENCY == 'VND')?'USD':'VND';
		$this->map['exchange_rate'] = DB::fetch('select id,exchange from currency where id=\''.$currency_id.'\'','exchange');
        //end:KID them xu ly lay exchange_rate
		//------------------------------------Auto Arrange---------------------------------------------
		if(!isset($_REQUEST['mi_reservation_room']))
        {
			if(Url::get('room_levels') and Url::get('arrival_time') and Url::get('departure_time'))
            {
				$arrival_time = Url::get('arrival_time');
				$departure_time = Url::get('departure_time');
				if(Url::get('time_in'))
                {
					$time_in = Url::get('time_in');
				}
                else
                {
					$time_in = CHECK_IN_TIME;
				}
				if(Url::get('time_out'))
                {
					$time_out = Url::get('time_out');
				}
                else
                {
					$time_out = CHECK_OUT_TIME;
				}
				$room_level_tr = Url::get('room_levels');
				$room_level_arr = explode('|',$room_level_tr);
				$room_levels = array();
				foreach($room_level_arr as $value)
                {
					$arr = explode(',',$value);
					if($arr[1])
                    {
						$room_levels[$arr[0]]['id'] = $arr[0];
						$room_levels[$arr[0]]['quantity'] = $arr[1];
						$room_levels[$arr[0]]['price'] = $arr[2];
                        $room_levels[$arr[0]]['usd_price'] = $arr[3];
						$room_levels[$arr[0]]['adult'] = $arr[4];
						$room_levels[$arr[0]]['child'] = $arr[5];
						$room_levels[$arr[0]]['note'] = isset($arr[6])?$arr[6]:'';
					}
				}
				$count = 1;
				$all_room_levels = DB::fetch_all('select id,price,name,brief_name from room_level where portal_id = \''.PORTAL_ID.'\' order by name');
				foreach($room_levels as $key=>$value)
                {
					for($i=1;$i<=$value['quantity'];$i++){
						$_REQUEST['mi_reservation_room'][$count] = (Url::get('status')?array('status'=>Url::get('status')):array())+array(
							'room_level_name'=>$all_room_levels[$value['id']]['brief_name'],
							'room_level_id'=>$all_room_levels[$value['id']]['id'],
							'room_id'=>'',
							'room_name'=>'#'.$count,
							'adult'=>$value['adult'],
							'child'=>$value['child'],
							'price'=>$value['price'],
                            'usd_price'=>$value['usd_price'],
							'note'=>$value['note'],
							'arrival_time'=>$arrival_time,
							'departure_time'=>$departure_time,
							'time_in'=>$time_in,
							'time_out'=>$time_out,
							'confirm'=>Url::get('confirm')?Url::get('confirm'):0,
							'booking_code'=>Url::sget('booking_code'),
                            'allotment'=>Url::get('allotment')?Url::get('allotment'):0,
						);
						$count++;
					}
				}
			}
		}
		if(!isset($_REQUEST['mi_reservation_room']))
        {
			if(URL::get('rooms'))
            {
				$rooms = explode('|',URL::get('rooms'));
				$count = 1;
				$room_levels = DB::fetch_all('select id,price,brief_name,name,num_people from room_level where portal_id = \''.PORTAL_ID.'\' order by name');
				foreach($rooms as $params)
				{
					if($params)
                    {
						$params = explode(',',$params);
                        if(DB::fetch('select * from room_status where house_status = \'DIRTY\' AND room_id = '.$params[0].' AND TO_CHAR(in_date,\'ddmmyyyy\') = \''.date('dmY').'\''))
                        {
                            $house_status = 'dirty';
                        }
                        else 
                        { 
                            $house_status = '';
                        }
						$room = DB::select('room',$params[0]);
						$_REQUEST['mi_reservation_room'][$count] = (Url::get('status')?array('status'=>Url::get('status')):array())+array(
							'room_id'=>$params[0],
                            'house_status'=>$house_status,
							'arrival_time'=>$params[1],
							'departure_time'=>$params[2],
							'room_level_name'=>$room_levels[$room['room_level_id']]['brief_name'],
							'room_level_id'=>$room_levels[$room['room_level_id']]['id'],
							'time_in'=>(Url::get('status') and Url::get('status')=='CHECKIN')?date('H:i'):$_REQUEST['time_in'],
							'time_out'=>$_REQUEST['time_out'],
							'room_name'=>$room['name'],
                            'price'=>number_format($room_levels[$room['room_level_id']]['price'],0,".",","),
                            'adult'=>$room_levels[$room['room_level_id']]['num_people']
						);
						$count++;
					}
				}
			}
		}
		$currencys = DB::select_all('currency',false,'name');
		$this->map['reservation_room_items'] = isset($_REQUEST['table_reservation_room'])?current($_REQUEST['table_reservation_room']):array();
		$this->map['vip_card_list'] = array(''=>'')+String::get_list(DB::fetch_all('select code as id,discount_percent as name from vip_card order by code'));
		$this->map['traveller_items'] = isset($_REQUEST['table_traveller'])?current($_REQUEST['table_traveller']):array();
		$this->map['payment_type1_list'] = array(''=>'','By company'=>'By company','By the guest'=>'By the guest','Cash'=>'Cash','Credit card'=>'Credit card','Bank transfer'=>'Bank transfer','Travel agency'=>'Travel agency','Other'=>'Other');
        $holidays = DB::fetch_all('select id,name,charge,in_date from holiday');
		$holiday = array();
		foreach($holidays as $key=>$value)
        {
			$k = Date_Time::convert_orc_date_to_date($value['in_date'],'/');
			$holiday[$k]['id'] = $k;
			$holiday[$k]['name'] = $value['name'];
			$holiday[$k]['charge'] = $value['charge'];
		}
		$this->map['holidays'] = String::array2js($holiday);
		$reservation_types = DB::fetch_all('select id,name from reservation_type');
		$reservation_type_options = '1';
		foreach($reservation_types as $key=>$value){
			$reservation_type_options .= '<option value="'.$key.'" '.(($key==1)?'selected="selected"':'').'>'.$value['name'].'</option>';
		}
		$this->map['reservation_type_options'] = $reservation_type_options;
        
		$this->map['traveller_level_options'] = '
			<option value="">'.Portal::language('select').'</option>
			<option value="REGULAR">'.Portal::language('regular').'</option>
			<option value="VIP">'.Portal::language('VIP').'</option>
		';
		$guest_types = DB::fetch_all('select id,name from guest_type order by position');
		$guest_type_options = '<option value="">'.Portal::language('select').'</option>';
		foreach($guest_types as $key=>$value){
			$guest_type_options .= '<option value="'.$key.'">'.$value['name'].'</option>';
		}
		$this->map['traveller_level_options'] = $guest_type_options;
		$this->map +=array(
			'nationalities'=>DB::fetch_all('select code_1 as id, name_'.Portal::language().' as name from country where 1=1 order by name_'.Portal::language().'')
		);
        $this->map['verify_dayuse_options'] = '';
		$this->map['verify_dayuse_options'] .= '<option value="">0</option>';
		$this->map['verify_dayuse_options'] .= '<option value="5">+0.5</option>';
		$this->map['verify_dayuse_options'] .= '<option value="10">+1</option>';
        /** manh them danh sach loai phong **/
        $this->map['room_level_options'] = '<option value="ALL">ALL</option>';
        $room_level = DB::fetch_all("SELECT * FROM room_level WHERE portal_id='".PORTAL_ID."'");
        foreach($room_level as $key_level=>$value_level)
        {
            $this->map['room_level_options'] .= '<option value="'.$value_level['brief_name'].'">'.$value_level['brief_name'].'</option>';
        }
        /** end manh **/
	/** giap.ln them truong hop hien thi danh sach package co trong khoang arrival time **/
        $this->map['package_sale_options'] = '<option value="">--SELECT--</option>';
        if(isset($_REQUEST['mi_reservation_room']))
        {
            /** Daund cmt lai de fix loi save khi tao phong khong co so phong 
            foreach($_REQUEST['mi_reservation_room'] as $row)
            {
                $arrival_time = explode("/",$row['arrival_time']);
                break;
            }
            $arrival_time = mktime(0,0,0,$arrival_time[1],$arrival_time[0],$arrival_time[2]);
            $package_sales = DB::fetch_all("SELECT * FROM package_sale WHERE (DATE_TO_UNIX(start_date)<=$arrival_time AND DATE_TO_UNIX(end_date)>=$arrival_time AND end_date is not null AND start_date is not null) OR start_date is null OR end_date is null");
            foreach($package_sales as $row)
            {
                $this->map['package_sale_options'] .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';
            }*/
            /** Daund viet lai cho nay */
            foreach($_REQUEST['mi_reservation_room'] as $row)
            {
                if(!empty($row['arrival_time']))
                    $arrival_time = explode("/",$row['arrival_time']);
                    break;
            }
            if(isset($arrival_time))
            {
                $arrival_time = mktime(0,0,0,$arrival_time[1],$arrival_time[0],$arrival_time[2]);
                $package_sales = DB::fetch_all("SELECT * FROM package_sale WHERE (DATE_TO_UNIX(start_date)<=$arrival_time AND DATE_TO_UNIX(end_date)>=$arrival_time AND end_date is not null AND start_date is not null) OR start_date is null OR end_date is null");                
            }
            if(isset($package_sales))
            {
                foreach($package_sales as $row)
                {
                    $this->map['package_sale_options'] .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';
                }
            }
            /** Daund viet lai cho nay */
        }
                
        /** end giap.ln **/
		$this->parse_layout('add',$this->map);
	}
}
?>
