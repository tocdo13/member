<?php
/**
1, THUẬT NGỮ
    - phòng cũ : phòng khách ở lúc đầu.
    - phòng mới : phòng khách muốn đổi đến.
2, QUY TRÌNH
    A, CHỌN PHÒNG:
        - Phòng cũ : lấy các phòng đang CHECKIN tại thời điểm hiện tại.
        - Phòng mới : lấy các phòng trống(tất cả các phòng - phòng có CHECKIN, BOOKED trong ngày hôm nay).
    B, ĐỔI PHÒNG:
        B1, ĐỔI PHÒNG BOOKED:
            - check conflig (check conflig đặt phòng + check phòng REPAIR, DIRTY trong khoảng thời gian đổi).
            - cập nhật thông tin phòng mới, thời gian mới cho đặt phòng.
            - cập nhật thông tin phòng mới cho các dịch vụ buồng.
            - cập nhật giá mới cho đặt phòng (nếu không chọn giữ nguyên giá cũ).
            - cập nhật thông tin phòng mới cho lược đồ giá:
                + giá lấy theo giá của hạng phòng mới hoặc giữ nguyên giá tùy lựa chọn.
                + ngày trên lược đồ giá = ngày lược đồ giá cũ + (arrival_time mới - arrival_time cũ)
            - ghi log.
        B2, ĐỔI PHÒNG CHECKIN
            - check conflig (check conflig đặt phòng + check phòng REPAIR, DIRTY trong khoảng thời gian đổi).
            - cập nhật lại thông tin cho đặt phòng cũ:
                + price : nếu ngày đến = ngày dổi phòng thì cập nhật giá của phòng đó = 0 luôn.
                + chuyển trạng thái đặt phòng cũ sang CHECKOUT, chuyển ngày đi  = ngày đổi phòng.
                + cập nhật ngày đi, trạng thái của khách.
                + cập nhật xong đặt phòng cũ, tạo đặt phòng mới lấy các thông tin từ đặt phòng cũ.
            - cập nhật dịch vụ EI, LO , EXTRA_BED, BABY_COT:
                + EI giữ nguyên cho đặt phòng cũ, LO tính cho đặt phòng mới.
                + EXTRA_BED, BABY_COT : 
                    . Với trường hợp ngày bắt đầu dịch vụ > ngày đổi phòng thì chuyển cả chặng dịch vụ sang đặt phòng mới.
                    . Với trường hợp ngày bắt đầu dịch vụ < ngày đổi phòng và ngày kết thúc dịch vụ > ngày đổi phòng thì cắt chặng dịch vụ ra(nếu ngày kết thúc dịch vụ < ngày đổi phòng thì k cắt chặng).
                        * phòng cu : chặng dịch vụ : ngày bắt đầu -> ngày đổi phòng : nếu 2 ngày này bằng nhau thì giá dịch vụ = 0.
                        * phòng mới : chặng dịch vụ : ngày đổi phòng -> ngày kết thúc dịch vụ.
            - tạo đặt phòng mới cùng recode với các thông tin từ đặt phòng cũ chuyển sang:
                + tạo đặt phòng mới trong cùng recode, lấy thông tin của đặt phòng cũ, thay đổi các thông tin sau: 
                    . price - lấy theo giá của hạng phòng của phòng mới nếu không chọn giữ nguyên giá.
                    . (time_in,arrival_time) - lấy thời gian là thời gian đổi phòng.
                + cập nhật thông tin change_room_to_rr cho đặt phòng cũ : lưu id của đặt phòng mới.
                + chuyển thông tin khách từ phòng cũ sang phòng mới..
                + tạo lược đồ giá cho đặt phòng mới, giá lấy theo giá hạng phòng của phòng mới nếu không chọn giữ nguyên giá.
                + chuyển house_status ngày đổi phòng của phòng cũ sang DIRTY.
3, CÁC HÀM
    - HÀM ĐỔI PHÒNG : change_room($from_reservation_room_id,$to_room_id,$use_old_price,$note)
    - HÀM ĐỔI PHÒNG BOOKED : change_room_book($from_reservation_room_id,$to_room_id,$use_old_price,$note)
    - HÀM CHECK CONFLIG : check_conflig($reservation_room_id,$to_room_id,$status,&$form)
    - HÀM ĐỔI PHÒNG CHECKIN : change_room_checkin($reservation_room_id,$to_room_id,$use_old_price,$note,&$form)
    - HÀM CẬP NHẬT ĐẶT PHÒNG CŨ : close_old_reservation_room($reservation_room,$from_room_name,$to_room_name,$to_room_id,$use_old_price,$note)
    - HÀM TẠO ĐẶT PHÒNG MỚI : create_new_reservation_room($reservation_room,$from_room_name,$to_room_name,$to_room_id,$use_old_price,$note,$old_reservation_traveller)
    
    - HÀM LẤY RA CÁC ĐẶT PHÒNG CÒN CHECKIN TRONG NGÀY : get_reservation_room()
    - HÀM LẤY RA CÁC PHÒNG CÓ THỂ ĐÔIE ĐẾN : get_available_room($cond=false)
4, ĐƯỜNG ĐI DỮ LIỆU
    - Khi view form đổi phòng gọi đến 2 hàm get_reservation_room, get_available_room để lấy ra các phòng có thể được đổi và các phòng có thể đổi đến.
    - Khi submit thì gọi đến HÀM ĐỔI PHÒNG : change_room($from_reservation_room_id,$to_room_id,$use_old_price,$note). tùy trạng thái của phòng bị đổi mà gọi đến hàm đổi phòng tương ứng.
5, NOTE
    - thêm trường trong bảng RESERVATION_ROOM để dò vết đổi phòng: CHANGE_ROOM_FROM_RR, CHANGE_ROOM_TO_RR
        + CHANGE_ROOM_FROM_RR : reservation_room_id của đặt phòng cũ (đặt phòng bị đổi).
        + CHANGE_ROOM_TO_RR : reservation_room_id của đặt phòng mới (đặt phòng đổi đến).
        VD : A change to B change to C.
        trong trường hợp này thì :
            . A : CHANGE_ROOM_FROM_RR null, CHANGE_ROOM_TO_RR chưa reservation_room_id của B.
            . B : CHANGE_ROOM_FROM_RR chứa reservation_room_id của A(A), CHANGE_ROOM_TO_RR chưa reservation_room_id của C.
            . C : CHANGE_ROOM_FROM_RR chứa reservation_room_id của B và A(A|B), CHANGE_ROOM_TO_RR null.
        =>  * với đặt phòng bình thường thì 2 trường CHANGE_ROOM_FROM_RR, CHANGE_ROOM_TO_RR đều null.
            * với đặt phòng bị đổi đầu tiên (A) thì trường CHANGE_ROOM_FROM_RR null, CHANGE_ROOM_TO_RR khác null.
            * với đặt phòng đổi phòng trung gian (B) thì trường CHANGE_ROOM_FROM_RR khác null, CHANGE_ROOM_TO_RR khác null.
            * với đặt phòng đổi phòng cuối (C) thì trường CHANGE_ROOM_FROM_RR khác null, CHANGE_ROOM_TO_RR null.
**/
class ChangeRoomForm extends Form
{
	function ChangeRoomForm()
	{
		Form::Form('ChangeRoomForm');
		$this->add('change_room_reason',new TextType(true,'you_have_to_input_change_room_reason',3,255));
	}
	function on_submit()
    {
		if($this->check())
        {
            /** START Daund: check phòng đã được tạo bill & được thanh toán hay chưa */
            require_once 'packages/hotel/packages/reception/modules/CreateTravellerFolio/get_reservation_room.php';
            $rr_ids = Url::get('from_reservation_room_id');
            $reservation_room_check = DB::fetch_all('select reservation_room.*,room.name as room_name from reservation_room inner join room on room.id = reservation_room.room_id where reservation_room.id in ('.$rr_ids.')');
            $check_create_pay_arr = array();
            // Check
            $bill = DB::fetch_all(' 
                                SELECT 
                                    folio.id ||\'_\'||traveller_folio.reservation_room_id as id,
                                    folio.id as folio_id,
                                    folio.total,
                                    folio.reservation_traveller_id,
                                    case when traveller_folio.add_payment=1
                                    then reservation_room.id
                                    else traveller_folio.reservation_room_id
                                    end reservation_room_id
                                FROM 
                                    folio
                                    inner join traveller_folio on traveller_folio.folio_id = folio.id 
                                    left join reservation_traveller on reservation_traveller.id = folio.reservation_traveller_id
                                    left join reservation_room on reservation_room.id = reservation_traveller.reservation_room_id
                                WHERE (
                                        (reservation_traveller.reservation_room_id in ('.$rr_ids.') and folio.customer_id is null)
                                        or
                                        (traveller_folio.reservation_room_id in ('.$rr_ids.') and folio.customer_id is not null)
                                    )
                                    and folio.total != 0
            ');
            //System::debug($bill);                         
            foreach($bill as $key=>$value)
            {
                if(!DB::exists('select id from payment where folio_id ='.$value['folio_id']))
                {
                    if(!isset($check_create_pay_arr[$rr_ids]))
                    {
                        $check_create_pay_arr[$rr_ids] = array('room_name'=>$reservation_room_check[$value['reservation_room_id']]['room_name'],
                                                                    'not_create_folio' => 0,
                                                                    'folios_not_paid'=>array($value['folio_id']=>array('payment'=>0,'id'=>$value['folio_id'])));
                    }
                    else
                    {
                        $check_create_pay_arr[$rr_ids]['folios_not_paid'][$value['folio_id']]=array('payment'=>0,'id'=>$value['folio_id']);
                    }
                }
                else
                {
                    $payment = DB::fetch('select sum(amount*exchange_rate) as amount from payment where folio_id ='.$value['folio_id']);
                    if( (HOTEL_CURRENCY == 'VND' and ($value['total'] - $payment['amount']) > 1000) or (HOTEL_CURRENCY == 'USD' and ($value['total'] - $payment['amount']) > 0.1))
                    {
                        if(!isset($check_create_pay_arr[$rr_ids]))
                        {
                            $check_create_pay_arr[$rr_ids] = array('room_name'=>$reservation_room_check[$value['reservation_room_id']]['room_name'],
                                                                        'not_create_folio' => 0,
                                                                        'not_deposit_group' => 0,
                                                                        'folios_not_paid'=>array($value['folio_id']=>array('payment'=>1,'id'=>$value['folio_id'])));
                        }
                        else
                        {
                            $check_create_pay_arr[$rr_ids]['folios_not_paid'][$value['folio_id']]=array('payment'=>1,'id'=>$value['folio_id']);
                        }
                    }
                }
            }
            //System::debug($check_create_pay_arr);
            $massage = '';
            if($check_create_pay_arr)
            {
                foreach($check_create_pay_arr as $key => $value)
                {
                    foreach($value['folios_not_paid'] as $k => $v)
                    {
                        if($v['payment'] == 0)
                        {
                            $massage .= 'Phòng #'.$value['room_name'].' đã tạo bill và chưa thanh toán, xin vui lòng kiểm tra lại. Xin cảm ơn!';                            
                        }else
                        {
                            $massage .= 'Phòng #'.$value['room_name'].' chưa thanh toán hết cho hóa đơn #Ref'.str_pad($v['id'],6,"0",STR_PAD_LEFT) .' Xin cảm ơn!';
                        }
                    }
                }
            }
            if($massage != '')
            {
                $this->error('', $massage);
                return false;
            }
            /** END Daund: check phòng đã được tạo bill & được thanh toán hay chưa */
            //System::debug($_REQUEST);exit();
            //gọi đến hàm đổi phòng
            $result = $this->change_room(Url::get('from_reservation_room_id'),Url::get('to_room_id'),Url::get('use_old_price',0),Url::sget('change_room_reason'));
            if($result == 'success')
            {
                $reservation_id = DB::fetch('select reservation_id from reservation_room where id = '.Url::get('from_reservation_room_id'),'reservation_id');
                Url::redirect( "reservation",array("cmd"=>"edit","id"=>$reservation_id) );
            }
		}
	}
    
    /**
    HÀM ĐỔI PHÒNG : change_room($from_reservation_room_id,$to_room_id,$use_old_price,$note)
    Đổi phòng BOOKED dành cho đổi phòng trên THSDP và đổi phòng CHECKIN.
    *Tham số : 
        - $from_reservation_room_id : reservation_room_id của phòng cũ.
        - $to_room_id : room_id của phòng mới.
        - $use_old_price : 1 (giữ nguyên giá phòng) 0 lấy theo giá của hạng phòng cũ
        - $note : ghi chú đổi phòng
    **/
    function change_room($from_reservation_room_id,$to_room_id,$use_old_price,$note)
    {
        if($record = DB::select_id('reservation_room',$from_reservation_room_id))
        {
            $r_room_status = DB::fetch('select status from reservation_room where id = '.$from_reservation_room_id,'status');
            if($r_room_status == 'BOOKED')
                //đổi phòng BOOKED
                return $this->change_room_book($from_reservation_room_id,$to_room_id,$use_old_price,$note);
            else
                //đổi phòng CHECKIN
                return $this->change_room_checkin($from_reservation_room_id,$to_room_id,$use_old_price,$note,$this);
        } 
    }
    
    /**
    HÀM ĐỔI PHÒNG BOOKED : change_room_book($from_reservation_room_id,$to_room_id,$use_old_price,$note)
    *Tham số : 
        - $from_reservation_room_id : reservation_room_id của phòng cũ.
        - $to_room_id : room_id của phòng được mới.
        - $use_old_price : 1 (giữ nguyên giá phòng) 0 lấy theo giá của hạng phòng cũ
        - $note : ghi chú đổi phòng
    *Tham số phụ : ngoài các tham số truyền trực tiếp vào hàm còn các tham số lấy từ $_REQUEST:
        - start_time : time_in của đặt phòng cũ.
    *Quy trình : 
        - check conflig (check conflig đặt phòng + check phòng REPAIR, DIRTY trong khoảng thời gian đổi).
        - cập nhật thông tin phòng mới, thời gian mới cho đặt phòng:
            update bảng reservation_room : 
            + price theo giá hạng phòng mới nếu không chọn giữ nguyên giá.
            + (room_id,room_level_id,room_type_id) - do phòng thay đổi.
            + (lastest_edited_time,lastest_edited_user_id) - do chỉnh sửa.
            + (time_in,time_out,arrival_time,departure_time) - do thay đổi thời gian đặt phòng.
        - cập nhật phòng mới cho các dịch vụ buồng: update bảng housekeeping_invoice.
        - cập nhật thông tin phòng mới cho lược đồ giá: update bảng room_status:
            + in_date = in_date cũ + arrival_time mới - arrival_time cũ.
            + change_price theo giá hạng phòng mới nếu không chọn giữ nguyên giá.
        - ghi log.
    **/
    function change_room_book($from_reservation_room_id,$to_room_id,$use_old_price,$note)
    {
        if($error = $this->check_conflig($from_reservation_room_id,$to_room_id,'BOOKED',$this)){
            return $error;
  		}
        
        $record = DB::select('reservation_room','id = '.$from_reservation_room_id);
		//Lấy mảng chứa room_level và price của phòng mới
		$room_level = DB::fetch('select room.*, room_level.price  from room inner join room_level on room_level.id = room.room_level_id  where room.id = '.$to_room_id);
        /** START Gán phòng trên THSD phòng, doi phong , chon gan phong Sau khi nhấn OK-> load lại thì phòng được gán trở về vị trí cũ**/
        $from_room_name = $record['room_id']?DB::fetch('select name from room where id = '.$record['room_id'].'','name'):'';
        /** END Gán phòng trên THSD phòng, doi phong , chon gan phong Sau khi nhấn OK-> load lại thì phòng được gán trở về vị trí cũ**/
        $room_level_id = DB::fetch('select room_level_id from room where id = '.$to_room_id.'','room_level_id');
		$room_type_id = DB::fetch('select room_type_id from room where id = '.$to_room_id.'','room_type_id');
		$to_room_name = DB::fetch('select name from room where id = '.$to_room_id.'','name');
		$minibar_id = DB::fetch('select id from minibar where room_id = '.$to_room_id.'','id');
		
        $h = date('H',$record['time_in']);
        $m = date('i',$record['time_in']);
        $s = date('s',$record['time_in']);
        $time_in = Url::get('start_time') + $h*60*60 + $m*60 + $s;
        $time_out = $time_in + $record['time_out'] - $record['time_in'];
        $start_date = Date_Time::convert_time_to_ora_date($time_in);
        $end_date = Date_Time::convert_time_to_ora_date($time_out);
        $lastest_edited_time = date("U");
        $lastest_edited_user_id = Session::get('user_id');
        $extra_time = $record['time_in'] - $time_in;
        
        $update_reservation_room = array('room_id'=>$to_room_id
                                            ,'room_level_id'=>$room_level_id
                                            ,'room_type_id'=>$room_type_id
                                            ,'lastest_edited_time'=>$lastest_edited_time
                                            ,'lastest_edited_user_id'=>$lastest_edited_user_id
                                            ,'time_in'=>$time_in
                                            ,'time_out'=>$time_out
                                            ,'arrival_time'=>$start_date
                                            ,'departure_time'=>$end_date);
        // check doi gia theo setting
        if( (CHANGE_ROOM_BOOKED=='SAME' and $room_level_id!=$record['room_level_id']) OR CHANGE_ROOM_BOOKED=='ALWAY'){
            $update_reservation_room['price'] = $room_level['price'];
        }
        // end check
        $rs = DB::update('reservation_room',$update_reservation_room,'id = '.$from_reservation_room_id);
        
        DB::query('update housekeeping_invoice set MINIBAR_ID = \''.$minibar_id.'\' where type = \'MINIBAR\' AND RESERVATION_ROOM_ID = '.$from_reservation_room_id);
		DB::query('update housekeeping_invoice set MINIBAR_ID = '.$to_room_id.' where type <> \'MINIBAR\' AND RESERVATION_ROOM_ID = '.$from_reservation_room_id);
		
        $room_status = DB::fetch_all('select * from room_status where reservation_room_id = '.$from_reservation_room_id.'');
		foreach($room_status as $r => $room){
            $old_indate = $room['in_date'];
            $in_date = Date_Time::convert_orc_date_to_date($room['in_date'],"/");
            $in_time = Date_Time::to_time($in_date);
            $room['in_date'] = Date_Time::convert_time_to_ora_date($in_time - $extra_time);
            // check doi gia theo setting
            if( (CHANGE_ROOM_BOOKED=='SAME' and $room_level_id!=$record['room_level_id']) OR CHANGE_ROOM_BOOKED=='ALWAY'){
                $room['change_price'] = $room_level['price'];
            }
            // end check
            $room['start_date'] = $start_date;
            $room['end_date'] = $end_date;
            $room['lastest_edited_time'] = $lastest_edited_time;
            $room['lastest_edited_user_id'] = $lastest_edited_user_id;
            $room['room_id'] = $to_room_id;
            
            //trường hợp không phải dayuse thì ngày cuối cùng lược đồ giá  = 0. dùng hàm convert_orc_date_to_date để chuyển hết ngày oracle vd 01-JAN-14, hay 01-JAN-2014 => 01/01/2014 để so sánh
            if(Date_Time::convert_orc_date_to_date($record['departure_time']) != Date_Time::convert_orc_date_to_date($record['arrival_time']) and Date_Time::convert_orc_date_to_date($old_indate) == Date_Time::convert_orc_date_to_date($record['departure_time']))
            {
                $room['change_price'] = 0;
            }
            
            DB::update('room_status',$room,'id = '.$room['id']);
		}
        
		$description = '
			Change room from <strong>'.$from_room_name.'</strong> to room <strong>'.$to_room_name.'</strong><br>
			Arrival time: '.$record['arrival_time'].'<br>
			Departure time: '.$record['departure_time'].'<br>
			Reason: '.$note.'
		';
		$portal_name = DB::fetch('select name_1 from party where user_id = \''.PORTAL_ID.'\'','name_1');
        
        DB::update('reservation',array('last_time'=>time(),'lastest_user_id'=>User::id()),'id='.$record['reservation_id']);
        
		$log_id = System::log('change_room','Change room of '.$portal_name.' from <strong>'.$from_room_name.'</strong> to room <strong>'.$to_room_name.'</strong>',$description,$from_reservation_room_id);
        System::history_log('RECODE',$record['reservation_id'],$log_id);
        return "success";
    }
    
    /**
    HÀM CHECK CONFLIG : check_conflig($reservation_room_id,$to_room_id,$status,&$form)
    *Tham số : 
        - $reservation_room_id : reservation_room_id của phòng cũ.
        - $to_room_id : room_id của phòng mới.
        - $status : trạng thái của đặt phòng. nhận 2 giá trị (BOOKED hoặc CHECKIN).
        - &$form : object của class đổi phòng.
    *Quy trình : 
        - check conflig conflig đặt phòng trong khoảng thời gian: check trong khoản thời gian đổi phòng, phòng mới có tồn tại đặt phòng nào khác hay không.
        - check phòng REPAIR,DIRTY : check trong khoảng thời gian đổi phòng xem phòng mới có REPAIR,DIRTY hay không.
        => nếu 1 trong 2 điều kiện trên xảy ra thì sẽ không được đổi phòng.
    **/
	function check_conflig($reservation_room_id,$to_room_id,$status,&$form)
    {
		$record = DB::select('reservation_room','id = '.$reservation_room_id);
        $room_level_id = DB::fetch('select room_level_id from room where id='.$to_room_id,'room_level_id');
        if($status == 'BOOKED')
        {
            $h = date('H',$record['time_in']);
            $m = date('i',$record['time_in']);
            $s = date('s',$record['time_in']);
            $time_in = Url::get('start_time') + $h*60*60 + $m*60 + $s;
            $time_out = $time_in + $record['time_out'] - $record['time_in'];
        }
        else
        {
            /** START fix loi conflict thoi gian doi phòng**/
            //$time_in = $record['time_in'];
            $time_in = time();
            /** END fix loi conflict thoi gian doi phòng**/
            $time_out = $record['time_out'];
        }
       /** them dk check so luong hang phong **/
		require_once 'packages/hotel/packages/reception/modules/includes/reservation.php';
        if (!OVER_BOOK)
        {
            $items = room_level_check_conflict(array($room_level_id,$time_in,$time_out,false));
            //System::debug($items); die;
            if(!$items || $items <1 )
            {
                $form->error('room_level', Portal::language('Room_level').': '.DB::fetch('select name from room_level where id = '.$room_level_id.'','name').' '.Portal::language('has_only ').$items.' rooms in '.date('d/m/Y',$time_in).' - '.date('d/m/Y',$time_out),false);
                return Portal::language('Room_level').': '.DB::fetch('select name from room_level where id = '.$room_level_id.'','name').' '.Portal::language('has_only ').$items.' rooms in '.date('d/m/Y',$time_in).' - '.date('d/m/Y',$time_out);
            }
        }
        /** them dk check so luong hang phong **/
       $room_name_fr = DB::fetch('SELECT name FROM room WHERE id = '.$record['room_id'].'','name');
		$room_name = DB::fetch('SELECT name FROM room WHERE id = '.$to_room_id.'','name');
        $sql = '
			SELECT
				R.id,R.reservation_id,room.name as room_name
			FROM
				reservation_room R
				INNER JOIN room ON room.id = R.room_id
			WHERE
				room.portal_id = \''.PORTAL_ID.'\' AND R.status<>\'CANCEL\'
				AND R.room_id=\''.$to_room_id.'\'
				'.($record['id']?' AND R.id<>\''.$record['id'].'\'':'').
                ' AND R.status =\'CHECKIN\'';
        if($reservation_room = DB::fetch($sql) and $status == 'CHECKIN')
        {
            $form->error('to_room_id',Portal::language('room').' '.$room_name.' '.Portal::language('conflict_of_time_to_reservation').' <a target="blank" href="?page=reservation&cmd=edit&id='.$reservation_room['reservation_id'].'#'.$reservation_room['id'].'">#'.$reservation_room['reservation_id'].'</a>',false);
			return Portal::language('room').' '.$room_name.' '.Portal::language('conflict_to_reservation');
        }
		$sql = '
			SELECT
				R.id,R.reservation_id
			FROM
				reservation_room R
				INNER JOIN room ON room.id = R.room_id
			WHERE
				room.portal_id = \''.PORTAL_ID.'\' AND R.status<>\'CANCEL\'
				AND R.room_id=\''.$to_room_id.'\'
				'.($record['id']?' AND R.id<>\''.$record['id'].'\'':'').
                ' AND (
				(R.time_in <= '.$time_out.' AND R.time_out >= '.$time_in.')
		)';
        // AND R.status<>\'CHECKOUT\'
        $sql_repair = '
			SELECT
				*
			FROM
				room_status
				INNER JOIN room ON (room.id = room_status.room_id and room_status.room_id = \''.$to_room_id.'\')
			WHERE
				room.portal_id = \''.PORTAL_ID.'\' 
                AND (
				(room_status.in_date <= \''.Date_Time::convert_time_to_ora_date($time_out).'\' AND room_status.in_date >= \''.Date_Time::convert_time_to_ora_date($time_in).'\')) 
                AND (room_status.house_status = \'REPAIR\' or room_status.house_status = \'DIRTY\')';
        
		if($reservation_room = DB::fetch($sql) and $record['status']<>'CANCEL' and $record['status']<>'CHECKOUT')
		{
			$form->error('to_room_id',Portal::language('room').' '.$room_name.' '.Portal::language('conflict_of_time_to_reservation').' <a target="blank" href="?page=reservation&cmd=edit&id='.$reservation_room['reservation_id'].'#'.$reservation_room['id'].'">#'.$reservation_room['reservation_id'].'</a>',false);
			return Portal::language('room').' '.$room_name.' '.Portal::language('conflict_of_time_to_reservation');
		}
        else
        {
            if($house_status = DB::fetch($sql_repair) and $status == 'CHECKIN')
            {
                $form->error('to_room_id','room '.$house_status['house_status'],false);
                return 'room '.$house_status['house_status'];
            }
            else
                return false;
		}
	}
    
    /**
    HÀM ĐỔI PHÒNG CHECKIN : change_room_checkin($reservation_room_id,$to_room_id,$use_old_price,$note,&$form)
    *Tham số : 
        - $from_reservation_room_id : reservation_room_id của phòng cũ.
        - $to_room_id : room_id của phòng mới.
        - $use_old_price : 1 (giữ nguyên giá phòng) 0 lấy theo giá của hạng phòng cũ
        - $note : ghi chú đổi phòng
        - &$form : object của class đổi phòng.
    *Quy trình : 
        - check conflig (check conflig đặt phòng + check phòng REPAIR, DIRTY trong khoảng thời gian đổi).
        - cập nhật lại thông tin cho đặt phòng cũ: gọi hàm $this->close_old_reservation_room($record,$from_room_name,$to_room_name,$to_room_id,$use_old_price,$note);
        - tạo đặt phòng mới cùng recode với các thông tin từ đặt phòng cũ chuyển sang:  gọi hàm $this->create_new_reservation_room($reservation_room,$from_room_name,$to_room_name,$to_room_id,$use_old_price,$note,$old_reservation_traveller);
    **/
    function change_room_checkin($reservation_room_id,$to_room_id,$use_old_price,$note,&$form)
    {
        //check conflig
        if($error = $this->check_conflig($reservation_room_id,$to_room_id,'CHECKIN',$form)){
            return $error;
  		}
        //lấy ra thông tin của đặt phòng cũ
		$record = DB::select('reservation_room','id = '.$reservation_room_id);
        
        //Tên phòng cũ và mới
        $from_room_name = DB::fetch('SELECT name FROM room WHERE id = '.$record['room_id'].'','name');
        $to_room_name = DB::fetch('SELECT name FROM room WHERE id = '.$to_room_id.'','name');
        
        //đóng đặt phòng cũ
        $this->close_old_reservation_room($record,$from_room_name,$to_room_name,$to_room_id,$use_old_price,$note);
        
        return "success";
	}
    
    /**
    HÀM CẬP NHẬT ĐẶT PHÒNG CŨ : close_old_reservation_room($reservation_room,$from_room_name,$to_room_name,$to_room_id,$use_old_price,$note)
    *Tham số : 
        - $reservation_room : bản ghi trong bảng reservation_room của phòng cũ.
        - $from_room_name : tên phòng cũ.
        - $to_room_name : tên phòng mới.
        - $use_old_price : 1 (giữ nguyên giá phòng) 0 lấy theo giá của hạng phòng cũ
        - $note : ghi chú đổi phòng
        - $to_room_id : id của phòng mới.
    *Quy trình : 
        - chuyển trạng thái đặt phòng cũ sang CHECKOUT:
            cập nhật bảng reservation_room : 
            + price : nếu ngày đến = ngày dổi phòng thì cập nhật giá của phòng đó = 0 luôn.
            + (time_out,departure_time)- lấy thời gian đổi phòng làm thời gian đi.
            + note - thêm note về việc đổi phòng.
            + status - CHECKOUT.
            + (lastest_edited_user_id,lastest_edited_time,checked_out_user_id) - cập nhật thông tin người chỉnh sửa.
        - cập nhật dịch vụ EI, LO , EXTRA_BED, BABY_COT:
                + EI giữ nguyên cho đặt phòng cũ, LO tính cho đặt phòng mới.
                + EXTRA_BED, BABY_COT : 
                    . Với trường hợp ngày bắt đầu dịch vụ > ngày đổi phòng thì chuyển cả chặng dịch vụ sang đặt phòng mới.
                    . Với trường hợp ngày bắt đầu dịch vụ < ngày đổi phòng và ngày kết thúc dịch vụ > ngày đổi phòng thì cắt chặng dịch vụ ra(nếu ngày kết thúc dịch vụ < ngày đổi phòng thì k cắt chặng).
                        * phòng cu : chặng dịch vụ : ngày bắt đầu -> ngày đổi phòng : nếu 2 ngày này bằng nhau thì giá dịch vụ = 0.
                        * phòng mới : chặng dịch vụ : ngày đổi phòng -> ngày kết thúc dịch vụ.
        - cập nhật ngày đi, trạng thái của khách
            cập nhật bảng reservation_traveller :
            + (departure_time,departure_date).
            + status - CHECKOUT.
        - cập nhật xong đặt phòng cũ, tạo đặt phòng mới lấy các thông tin từ đặt phòng cũ:
            gọi hàm create_new_reservation_room($reservation_room,$from_room_name,$to_room_name,$to_room_id,$use_old_price,$note,$old_reservation_traveller)
    **/
    function close_old_reservation_room($reservation_room,$from_room_name,$to_room_name,$to_room_id,$use_old_price,$note)
    {
        if(DB::fetch("select late_checkin from extra_service_invoice where reservation_room_id = ".$reservation_room['id']. " and late_checkin = 1",'late_checkin'))
            $reservation_room['late_checkin'] = 1;
        else
            $reservation_room['late_checkin'] = 0;
        
        //Lấy ra các khách còn IN trong đặt phòng cũ, để chuyển sang phòng mới
        $old_reservation_traveller = DB::select_all('reservation_traveller',' status != \'CHANGE\' and status != \'CHECKOUT\' and reservation_room_id = '.$reservation_room['id']);
        
        /** -START- update thông tin cho r_room cũ **/
        $reservation_room_update = array(
                            'time_out'=>time(),
                            'departure_time'=>Date_Time::to_orc_date(date('d/m/Y')),
                            'note'=>$reservation_room['note'].' '.Portal::language('close_and_change_to_room').' '.$to_room_name.' '.Portal::language('from').' '.date('d/m/Y'),
                            'status'=>'CHECKOUT',
                            'lastest_edited_user_id'=>Session::get('user_id'),
                            'lastest_edited_time'=>time(),
                            'checked_out_user_id'=>Session::get('user_id'),
                            'late_checkout'=>0,
                            );
        //nếu ngày đổi phòng = ngày đến và không phải trường hợp late_checkin thì giá phòng được set về 0.
        if(date('d/m/Y') == Date_Time::convert_orc_date_to_date($reservation_room['arrival_time'],"/") and !$reservation_room['late_checkin'])
        {
            $reservation_room_update['price'] = 0;
            $reservation_room_update['usd_price'] = 0;
        }   
            
        /** -START- xử lý cắt chặng extrabed, babycot. **/
        /** - nếu ngày kết thúc của extra_bed hay baby_cot >  ngày hiện tại thì update lại ngày kết thúc của 2 dịch vụ này = ngày hôm nay
            - nếu ngày bắt đầu dịch vụ = ngày hôm nay thì set giá dịch vụ = 0
        **/
        /** EXTRA_BED **/
        if($reservation_room['extra_bed'] and Date_Time::to_time(Date_Time::convert_orc_date_to_date($reservation_room['extra_bed_from_date'],'/')) <= Date_Time::to_time(date('d/m/y')))
        {
            if(Date_Time::to_time(Date_Time::convert_orc_date_to_date($reservation_room['extra_bed_to_date'],'/')) > Date_Time::to_time(date('d/m/y')))
            {
                $reservation_room_update['extra_bed_to_date'] = Date_Time::convert_time_to_ora_date(time());
                if(Date_Time::convert_orc_date_to_date($reservation_room['extra_bed_from_date'],'/') == date('d/m/Y') and $reservation_room['extra_bed_from_date'] != $reservation_room['extra_bed_to_date'])
                    $reservation_room_update['extra_bed_rate'] = 0;
            }
        }
        else{
            $reservation_room_update['extra_bed']=0;
            $reservation_room_update['extra_bed_from_date']='';
            $reservation_room_update['extra_bed_to_date']='';
            $reservation_room_update['extra_bed_rate']=0;
        }
        
        /** BABY_COT **/
        if($reservation_room['baby_cot'] and Date_Time::to_time(Date_Time::convert_orc_date_to_date($reservation_room['baby_cot_from_date'],'/')) <= Date_Time::to_time(date('d/m/y')))
        {
            if(Date_Time::to_time(Date_Time::convert_orc_date_to_date($reservation_room['baby_cot_to_date'],'/')) > Date_Time::to_time(date('d/m/y')))
            {
                $reservation_room_update['baby_cot_to_date'] = Date_Time::convert_time_to_ora_date(time());
                if(Date_Time::convert_orc_date_to_date($reservation_room['baby_cot_from_date'],'/') == date('d/m/Y') and $reservation_room['baby_cot_from_date'] != $reservation_room['baby_cot_to_date'])
                    $reservation_room_update['baby_cot_rate'] = 0;
            }
        }
        else{
            $reservation_room_update['baby_cot']=0;
            $reservation_room_update['baby_cot_from_date']='';
            $reservation_room_update['baby_cot_to_date']='';
            $reservation_room_update['baby_cot_rate']=0;
        }
        /** -END- xử lý cắt chặng extrabed, babycot. **/
                            
        DB::update_id('reservation_room',$reservation_room_update,$reservation_room['id']);
        /** -END- update thông tin cho r_room cũ **/
        /** -START- Thông tin update cho khách trong r_room cũ **/
        $reservation_traveller_update = array(
                    'departure_time'=>time(),
                    'departure_date'=>Date_Time::to_orc_date(date('d/m/Y')),
                    'status'=>'CHECKOUT',
                    );
        DB::update('reservation_traveller',$reservation_traveller_update,' reservation_room_id = '.$reservation_room['id']);
        /** -END- Thông tin update cho khách trong r_room cũ **/
        
        DB::update("room_status",array("house_status"=>"DIRTY")," room_id = ".$reservation_room['room_id']." AND in_date='".$reservation_room['departure_time']."'");
        
        //Mở 1 r_room mới cùng recode
        $this->create_new_reservation_room($reservation_room,$from_room_name,$to_room_name,$to_room_id,$use_old_price,$note,$old_reservation_traveller);
    }
    
    /**
    HÀM TẠO ĐẶT PHÒNG MỚI : create_new_reservation_room($reservation_room,$from_room_name,$to_room_name,$to_room_id,$use_old_price,$note,$old_reservation_traveller)
    *Tham số : 
        - $reservation_room : bản ghi trong bảng reservation_room của phòng cũ.
        - $from_room_name : tên phòng cũ.
        - $to_room_name : tên phòng mới.
        - $to_room_id : id của phòng mới.
        - $use_old_price : 1 (giữ nguyên giá phòng) 0 lấy theo giá của hạng phòng cũ
        - $note : ghi chú đổi phòng
        - $old_reservation_traveller : bản ghi về thông tin khách ở phòng cũ (bảng reservation_traveller);
    *Quy trình : 
        - tạo đặt phòng mới trong cùng recode: lấy lại các thông tin của đặt phòng cũ từ biến $reservation_room. Thay đổi các thông tin sau:
            + change_room_from_rr - lưu reservation_room_id của phòng cũ.
            + (room_id,room_type_id,room_level_id) - lấy thông tin phòng mới.
            + price - tùy theo $use_old_price mà lấy giá theo hạng phòng mới hoặc giữ nguyên giá.
            + (time_in,arrival_time) - lấy thời gian là thời gian đổi phòng.
            + note - note thông tin đổi phòng
            + (user_id, time, checked_in_user_id) - cập nhật thời gian, thông tin người tạo - người đổi phòng.
        - cập nhật dịch vụ EI, LO , EXTRA_BED, BABY_COT:
                + EI giữ nguyên cho đặt phòng cũ, LO tính cho đặt phòng mới.
                + EXTRA_BED, BABY_COT : 
                    . Với trường hợp ngày bắt đầu dịch vụ > ngày đổi phòng thì chuyển cả chặng dịch vụ sang đặt phòng mới.
                    . Với trường hợp ngày bắt đầu dịch vụ < ngày đổi phòng và ngày kết thúc dịch vụ > ngày đổi phòng thì cắt chặng dịch vụ ra(nếu ngày kết thúc dịch vụ < ngày đổi phòng thì k cắt chặng).
                        * phòng cu : chặng dịch vụ : ngày bắt đầu -> ngày đổi phòng : nếu 2 ngày này bằng nhau thì giá dịch vụ = 0.
                        * phòng mới : chặng dịch vụ : ngày đổi phòng -> ngày kết thúc dịch vụ.
        - cập nhật thông tin change_room_to_rr cho đặt phòng cũ : lưu id của đặt phòng mới
            DB::update_id('reservation_room',array('change_room_to_rr'=>$new_reservation_room),$old_reservation_room);
        - chuyển thông tin khách từ phòng cũ sang phòng mới.
            thêm bản ghi mới trong bảng reservation_traveller. 
            lấy các thông tin khách từ phòng cũ lưu trên biến  $old_reservation_traveller. cập nhật các thông tin sau :
            + reservation_room_id - lấy id của đặt phòng mới.
            + (arrival_time, arrival_date) - lấy theo thời gian đổi phòng.
            + old_arrival_time nếu old_arrival_time null.
        - tạo lược đồ giá cho đặt phòng mới, giá tùy theo $use_old_price:
            lấy các bản ghi của đặt phòng cũ trong room_status, đổi giá phòng(nếu không giữ giá cũ), room_id, reservation_room_id điều kiện in_date >= ngày đổi phòng.
            nếu đặt phòng cũ không phải dayuse thì phải cập nhật thêm ngày cuối cùng giá phòng = 0.
        - chuyển house_status ngày đổi phòng của phòng cũ sang DIRTY.
    **/
    function create_new_reservation_room($reservation_room,$from_room_name,$to_room_name,$to_room_id,$use_old_price,$note,$old_reservation_traveller)
    {
        //Lấy mảng chứa room_level và price của phòng mới
		$room_level = DB::fetch('select room.*, room_level.price  from room inner join room_level on room_level.id = room.room_level_id  where room.id = '.$to_room_id);
        //Room type của loại phòng mới
		$room_type_id = DB::fetch('select room_type_id from room where id = '.$to_room_id.'','room_type_id');
        
        //Mã r_room cũ
        $old_reservation_room  = $reservation_room['id'];
        //Mã room_id cũ
        $from_room_id  = $reservation_room['room_id'];
        /** start:KID them xu ly lay exchange_rate **/
        $currency_id = (HOTEL_CURRENCY == 'VND')?'USD':'VND';
		$exchange_rate = DB::fetch('select id,exchange from currency where id=\''.$currency_id.'\'','exchange');
        /** end:KID them xu ly lay exchange_rate **/
        //Từ r_room cũ, loại bỏ và thêm các trường để tạo 1 r_room mới
        unset($reservation_room['id']);
        unset($reservation_room['early_checkin']);
        unset($reservation_room['deposit']);
        unset($reservation_room['deposit_date']);
        // doi gia phong theo setting
        $room_level_old = $reservation_room['room_level_id'];
        if( (CHANGE_ROOM_CHECKIN=='SAME' and $room_level_old!=$room_level['room_level_id']) OR CHANGE_ROOM_CHECKIN=='ALWAY'){
            $reservation_room['price'] = $room_level['price'];
            /** start:KID them xu ly lay exchange_rate **/
            $reservation_room['usd_price'] = round(($room_level['price']/$exchange_rate),2);
            /** end:KID them xu ly lay exchange_rate **/
        }
        // end doi gia
        $reservation_room['change_room_from_rr'] = $reservation_room['change_room_from_rr']?($reservation_room['change_room_from_rr'].','.$old_reservation_room):$old_reservation_room;
        $reservation_room['room_id'] = $to_room_id;
        $reservation_room['room_type_id'] = $room_type_id;
        $reservation_room['room_level_id'] = $room_level['room_level_id'];
        $reservation_room['old_arrival_time'] = $reservation_room['old_arrival_time']?$reservation_room['old_arrival_time']:$reservation_room['time_in'];
        $reservation_room['time_in'] = time();
        $reservation_room['arrival_time'] = Date_Time::to_orc_date(date('d/m/Y'));
        $reservation_room['note'] = Portal::language('change_from_room').' '.$from_room_name.' '.Portal::language('from').' '.date('d/m/Y');
        $reservation_room['total_amount'] = 0;
        $reservation_room['user_id'] = Session::get('user_id');
        $reservation_room['time'] = time();
        unset($reservation_room['lastest_edited_user_id']);
        unset($reservation_room['lastest_edited_time']);
        unset($reservation_room['bill_number']);
        $late_checkin = $reservation_room['late_checkin'];
        unset($reservation_room['late_checkin']);
        $reservation_room['checked_in_user_id'] = Session::get('user_id');
        $reservation_room['note_change_room'] = $note;
        
        /** -START- xử lý cắt chặng extrabed, babycot. **/
        /** nếu ngày kết thúc của extra_bed hay baby_cot >  ngày hiện tại thì tạo tiếp 1 chặng mới cho phòng đổi đến **/
        /** EXTRA_BED **/
        if($reservation_room['extra_bed'] and Date_Time::to_time(Date_Time::convert_orc_date_to_date($reservation_room['extra_bed_from_date'],'/')) <= Date_Time::to_time(date('d/m/y')))
        { 
            if(Date_Time::to_time(Date_Time::convert_orc_date_to_date($reservation_room['extra_bed_to_date'],'/')) > Date_Time::to_time(date('d/m/y')))
            {
                $reservation_room['extra_bed_from_date'] = Date_Time::convert_time_to_ora_date(time());
            }
            else
            {
                unset($reservation_room['extra_bed']);
                unset($reservation_room['extra_bed_from_date']);
                unset($reservation_room['extra_bed_to_date']);
                unset($reservation_room['extra_bed_rate']);
            }
        }
        /** BABY_COT **/
        if($reservation_room['baby_cot'] and Date_Time::to_time(Date_Time::convert_orc_date_to_date($reservation_room['baby_cot_from_date'],'/')) <= Date_Time::to_time(date('d/m/y')))
        { 
            if(Date_Time::to_time(Date_Time::convert_orc_date_to_date($reservation_room['baby_cot_to_date'],'/')) > Date_Time::to_time(date('d/m/y')))
            {
                $reservation_room['baby_cot_from_date'] = Date_Time::convert_time_to_ora_date(time());
            }
            else
            {
                unset($reservation_room['baby_cot']);
                unset($reservation_room['baby_cot_from_date']);
                unset($reservation_room['baby_cot_to_date']);
                unset($reservation_room['baby_cot_rate']);
            }
        }
         /** -END- xử lý cắt chặng extrabed, babycot **/
        //Mã r_room mới
        $new_reservation_room = DB::insert('reservation_room',$reservation_room);
        //DB::update_id('reservation_room',array('change_room_to_rr'=>$new_reservation_room),$old_reservation_room);
        DB::query("UPDATE reservation_room   
                        SET CHANGE_ROOM_TO_RR = CASE  
                                                    WHEN CHANGE_ROOM_TO_RR is not null  THEN CHANGE_ROOM_TO_RR || ',' || '".$new_reservation_room."'   
                                                    ELSE '".$new_reservation_room."' 
                                                END
                    where id in (".$reservation_room['change_room_from_rr'].")");
        
        //Nếu có khách đang check in ở r_room cũ thì sẽ đưa sang phòng mới
        if( $old_reservation_traveller )
        {
            foreach($old_reservation_traveller as $key=>$value)
            {
                //Xóa bỏ các trường thùa đi
                unset($old_reservation_traveller[$key]['id']);
                $old_reservation_traveller[$key]['reservation_room_id'] = $new_reservation_room;
                $old_reservation_traveller[$key]['old_arrival_time'] = $old_reservation_traveller[$key]['old_arrival_time']?$old_reservation_traveller[$key]['old_arrival_time']:$old_reservation_traveller[$key]['arrival_time'];
                $old_reservation_traveller[$key]['arrival_time'] = time();
                $old_reservation_traveller[$key]['arrival_date'] = Date_Time::to_orc_date(date('d/m/Y'));
                DB::insert('reservation_traveller',$old_reservation_traveller[$key]);
            }
        }
        
        $room_status_cut_position = DB::fetch('select * from room_status where room_id = '.$from_room_id.' and reservation_room_id = '.$old_reservation_room.' and in_date = \''.Date_Time::to_orc_date( date('d/m/Y') ).'\' ');
        
        // doi gia phong theo setting
        if( (CHANGE_ROOM_CHECKIN=='SAME' and $room_level_old!=$room_level['room_level_id']) OR CHANGE_ROOM_CHECKIN=='ALWAY'){
            //Up date lại cho phòng mới. giá là giá của hạng phòng mới
            $room_status_update  = array(
                        'room_id'=>$to_room_id,
                        'change_price'=>$room_level['price'],
                        'reservation_room_id'=>$new_reservation_room,
                        'house_status'=>'',
                                    ); 
        }else{
            //Up date lại cho phòng mới.
            $room_status_update  = array(
                            'room_id'=>$to_room_id,
                            'reservation_room_id'=>$new_reservation_room,
                            'house_status'=>'',
                                        ); 
        }
        // end doi gia
        
        //trường hợp phòng late checkin đi luôn trong ngày đổi phòng thì khi đổi phòng : giá phòng chặng đầu vẫn như cũ + lược đồ giá = 0 + late_checkin, giá phòng chặng sau = 0 + lược đồ giá = 0                
        if($late_checkin and Date_Time::to_time(Date_Time::convert_orc_date_to_date($reservation_room['arrival_time'],'/')) == Date_Time::to_time(Date_Time::convert_orc_date_to_date($reservation_room['departure_time'],'/'))) 
            $room_status_update['change_price'] = 0;
        $cond = '
                    room_id = '.$from_room_id.'
                    and reservation_room_id = '.$old_reservation_room.'
                    and in_date >= \''.Date_Time::to_orc_date( date('d/m/Y') ).'\' 
            ';
        DB::update('room_status',$room_status_update,$cond);
        //trường hợp không phải dayuse thì lược đồ giá ngày cuối cùng phải = 0
        if(Date_Time::convert_orc_date_to_date($reservation_room['departure_time']) != date('d-m-Y',$reservation_room['old_arrival_time']))
        {
            $room_status_update  = array(
                    'change_price'=>0
                                );    
            $cond = '
                    room_id = '.$to_room_id.'
                    and reservation_room_id = '.$new_reservation_room.'
                    and TO_CHAR(in_date, \'DD-MM-YYYY\') = \''.Date_Time::convert_orc_date_to_date($reservation_room['departure_time']).'\' 
            ';
            DB::update('room_status',$room_status_update,$cond);
        }
        /** Daund: Update reservation_room mới và description mới*/
        $invoice_arr = DB::fetch_all('
                        SELECT
                            traveller_folio.invoice_id as id,
                            traveller_folio.reservation_room_id as r_r_id
                        FROM
                            traveller_folio
                        WHERE
                            traveller_folio.reservation_room_id = \''.$old_reservation_room.'\'
        ');
        foreach($invoice_arr as $key => $value)
        {
            $room_status = DB::fetch('
                        SELECT
                            room_status.id,
                            room_status.reservation_room_id as r_r_id,
                            room.name as room_name
                        FROM
                            room_status
                            INNER JOIN room ON room_status.room_id = room.id
                        WHERE
                            room_status.id = '.$value['id'].'
            ');
            if($room_status['id'] && ($value['r_r_id'] != $room_status['r_r_id']))
            {
                DB::update('traveller_folio', array('reservation_room_id'=> $room_status['r_r_id'], 'description' => $room_status['room_name'] .' Room charge'),'type = \'ROOM\' AND invoice_id=' .$room_status['id']);
            }            
        }
        /** Daund: Update reservation_room mới và description mới*/
        //Thêm bản ghi giá = 0  cho phòng cũ
        
        unset($room_status_cut_position['id']);
        $room_status_cut_position['change_price'] = 0;
        $room_status_cut_position['house_status'] = 'DIRTY';
        
        DB::insert('room_status',$room_status_cut_position);
        
        
        //Ghi log quá trình
        $description = '
			Checkout room <strong>'.$from_room_name.'</strong>  and change to room <strong>'.$to_room_name.'</strong><br>
			Reason: '.$note.'<br />
            Recode: <a target="_blank" href = '.Url::build('reservation',array('cmd'=>'edit','id'=>$reservation_room['reservation_id'])).'>#'.$reservation_room['reservation_id'].'</a>
		';
		$log_id = System::log('change_room','Checkout room <strong>'.$from_room_name.'</strong>  and change to room <strong>'.$to_room_name.'</strong>',$description,$reservation_room['reservation_id']);
        DB::update('reservation',array('last_time'=>time(),'lastest_user_id'=>User::id()),'id='.$reservation_room['reservation_id']);
        System::history_log('RECODE',$reservation_room['reservation_id'],$log_id);
    }
    
	function draw()
	{
		require_once 'packages/hotel/includes/php/hotel.php';
		$reservation_rooms = $this->get_reservation_room();
        
		if(empty($reservation_rooms)){
			$cond = '';
		}else{
			$cond = 'AND (';
			$i = 0;
			foreach($reservation_rooms as $key=>$value){
				if($i>0){
					$cond .= ' AND ';
				}
				$cond .= 'room.id <> '.$value['room_id'].'';
				$i++;
			}
			$cond .= ')';
		}
        //Loai luon ca may phong book trong cac phong dich' (Luan ad sua 11/6)
        $booked_room = Hotel::get_booked_room();
		if(empty($booked_room)){
			$cond .= '';
		}else{
			$cond .= 'AND (';
			$i = 0;
			foreach($booked_room as $key=>$value){
				if($i>0){
					$cond .= ' AND ';
				}
				$cond .= 'room.id <> '.$value['room_id'].'';
				$i++;
			}
			$cond .= ')';
		}
        
		$this->map = array();
		$this->map['from_reservation_room_id_list'] = String::get_list($reservation_rooms);
		$available_rooms = $this->get_available_room($cond);
        //System::debug($available_rooms);
        /** START: Daund check nhung phong repair khong lay vao doi phong */
        $room_repair = DB::fetch_all('
                            SELECT
                                room.id
                            FROM
                                room
                                INNER JOIN room_status on room_status.room_id = room.id
                            WHERE
                                room_status.in_date >= \''.Date_Time::to_orc_date(date('d/m/Y')).'\'
                                and room_status.house_status = \'REPAIR\'
        ');
        //System::debug($room_repair);
        foreach($available_rooms as $key => $value)
        {
            if(isset($room_repair[$value['id']]))
            {
                unset($available_rooms[$key]);
            }
        }
        /** START: Daund check nhung phong repair khong lay vao doi phong */
		$this->map['to_room_id_list'] = String::get_list($available_rooms);
		$this->parse_layout('change_room',$this->map);
	}
    
    /**
    HÀM LẤY RA CÁC ĐẶT PHÒNG CÒN CHECKIN TRONG NGÀY : get_reservation_room()
    *Quy trình : 
        lấy phòng CHECKIN, chưa close tại thời điểm hiện tại
    **/
    function get_reservation_room()
	{
		$sql = '
			select 
				reservation_room.id
				,concat(CONCAT(traveller.first_name,\' \'),traveller.last_name) as agent_name
				,reservation_room.room_id
				,room.name || \'(\'|| room_level.brief_name ||\')\' || \' - \' || \'Arrival : \' || to_char(reservation_room.arrival_time,\'DD/MM/YYYY\') || \'  Departure : \'  || to_char(reservation_room.departure_time,\'DD/MM/YYYY\') as name
			from 
				reservation_room 
				inner join room on room.id=reservation_room.room_id
                inner join room_level on room.room_level_id=room_level.id
				inner join room_status on room_status.RESERVATION_ID  =  RESERVATION_ROOM.RESERVATION_ID 
				LEFT OUTER JOIN traveller on traveller.id=reservation_room.traveller_id 
			where
				room.portal_id=\''.PORTAL_ID.'\' and reservation_room.status=\'CHECKIN\'
				and reservation_room.time_out > \''.time().'\'
				and (reservation_room.closed is null or reservation_room.closed = 0)
				and room_status.status = \'OCCUPIED\'
				and room_status.in_date = \''.Date_time::to_orc_date(date('d/m/Y')).'\'
                and reservation_room.departure_time >= \''.Date_time::to_orc_date(date('d/m/Y')).'\'
			order by
				room.name
		';
        //giap.ln comment and reservation_room.time_out > \''.time().'\' truong hop lay ra ca nhung phong out qua han
        $arr_reservation_rooms = DB::fetch_all($sql);
		return $arr_reservation_rooms;
	}
    
    /**
    HÀM LẤY RA CÁC PHÒNG CÓ THỂ ĐÔIE ĐẾN : get_available_room($cond=false)
    *Tham số :
        - $cond : điều kiện lấy dữ liệu
    *Quy trình : 
        lấy các phòng trừ phòng có CHECKIN, BOOKED.
    **/
    function get_available_room($cond=false){
		//Start: KID thay cau lenh sql list ra so phng phu hop
		/*
        $rooms = DB::fetch_all('
			SELECT
				room.*,
                room.name || \' (\'|| room_level.brief_name ||\')\'  as name
			FROM
				room
				inner join room_level on room_level.id = room.room_level_id
                left join room_status on ((room.id = room_status.room_id) and (room_status.house_status !=\'REPAIR\' and room_status.in_date =\''.Date_Time::to_orc_date(date('d/m/Y')).'\'))
			WHERE
				room.portal_id = \''.PORTAL_ID.'\' and room_status.house_status !=\'NULL\' 
				'.$cond.'
			ORDER BY
				room.name
		');
        */
        
         $sql=('
			SELECT 
                room.id, 
                room.name || \' (\'|| room_level.brief_name ||\')\'  as name,
                room_status.house_status,
                room_status.id as room_status_id
            FROM 
                room 
                inner join room_level on room_level.id = room.room_level_id
                left join room_status on room.id = room_status.room_id and room_status.in_date = \''.Date_Time::to_orc_date(date('d/m/Y')).'\' and room_status.house_status is not null 
            where 
                room.portal_id =\''.PORTAL_ID.'\' 
            ORDER by room.id,
            room_status.id,  
				room.floor, 
				room.position
		');
        $rooms = DB::fetch_all($sql);
        //System::debug($rooms);
        $items = DB::fetch_all('
                                SELECT 
                                    room_status.room_id as id,
                                    room.name || \' (\'|| room_level.brief_name ||\')\'  as name,
                                    room_status.house_status,
                                    reservation_room.status as reservation_room_status,
                                    reservation_room.time_out
                                FROM 
                                    room_status left join room on room.id = room_status.room_id
                                    inner join room_level on room_level.id = room.room_level_id
                                    left join reservation_room on reservation_room.id = room_status.reservation_room_id  
                                where 
                                    room_status.in_date =\''.Date_Time::to_orc_date(date('d/m/Y')).'\' 
                                    and room.portal_id =\''.PORTAL_ID.'\' 
                                    and (
                                        room_status.house_status = \'REPAIR\'  
                                        or (
                                                room_status.status != \'AVAILABLE\' 
                                                and room_status.status != \'OCCUPIED\'  and room_status.status != \'CANCEL\' and room_status.status != \'NOSHOW\' and (room_status.status != \'NOSHOW\')
                                           ) 
                                        or 
                                           (
                                                room_status.status = \'OCCUPIED\' and room_status.in_date != reservation_room.departure_time 
                                           )
                                        or 
                                           (
                                                reservation_room.status = \'CHECKIN\' and room_status.status = \'OCCUPIED\' and room_status.in_date = reservation_room.departure_time
                                           )            
                                        )
                                ');
        foreach($items as $k=>$v)
        {
            if($v['reservation_room_status']=='CHECKOUT' and $v['time_out']<time() and isset($items[$k])){
                unset($items[$k]);
            }
        }
        foreach($rooms as $key => $value)
        {
            if(isset($items[$value['id']])){
                unset($rooms[$value['id']]);
            }else{
                if($value['house_status']=='DIRTY')
                {
                    $rooms[$key]['name'] = $value['name'].'---Dirty';
                }
            }
            
        }
        //end
        //AND (room_level.is_virtual is null OR room_level.is_virtual = 0)
		return $rooms;
    }
}
?>
