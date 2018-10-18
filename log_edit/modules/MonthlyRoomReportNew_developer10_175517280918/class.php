<?php 
class MonthlyRoomReportNew extends Module
{
	function MonthlyRoomReportNew($row)
	{
		Module::Module($row);
        require_once 'db_new.php';
        
        /** get ajax **/
        if(Url::get('toolaction'))
        {
            /** check RealTime **/
            $last_time = DB::fetch('select reservation.last_time,reservation.lastest_user_id as user_id from reservation_room inner join reservation on reservation.id=reservation_room.reservation_id where reservation_room.id='.Url::get('reservation_room_id'));
            if($last_time['last_time']!=0 and $last_time['last_time']>Url::get('last_time')){
                $recode_return = array('status'=>404,'user'=>$last_time['user_id'],'time'=>date('H:i:s d/m/Y',$last_time['last_time']));
                echo json_encode($recode_return);
                exit();
            }
            /** End check RealTime **/
            
            if(Url::get('toolaction')=='AsignRoom') {
                $recode_return = array('status'=>0,'recode'=>array()); // trang thai thanh cong
                $reservation_room_id = Url::get('reservation_room_id');
                
                $room_id = Url::get('room_id');
                $time_in = Url::get('time_in');
                $time_out = Url::get('time_out');
                // kiem tra xem dat phong do da duoc gan chua
                if($room_asign = DB::fetch('select room.name from reservation_room inner join room on room.id=reservation_room.room_id where reservation_room.room_id is not null and reservation_room.id='.$reservation_room_id.''))
                {
                    $recode_return['status'] = 1; // phong da duoc gan
                    $recode_return['recode'] = $room_asign;
                }
                else
                {
                    // kiem tra xem phong duoc gan con trong trong khoang thoi gian dat phong khong
                    if($reservation = DB::fetch('select * from reservation_room where time_in<='.$time_out.' and time_out>='.$time_in.' and room_id='.$room_id.' and status!=\'CANCEL\''))
                    {
                        $recode_return['status'] = 2; // phong duoc gan khong con trong
                        $recode_return['recode'] = $reservation;
                    }
                    else
                    {
                        // kiem tra trang thai buong phong co repair, houseuse, closesale vao ngay hom do khong
                        $start_date = Date_Time::to_orc_date(date('d/m/Y',$time_in));
                        $end_date = Date_Time::to_orc_date(date('d/m/Y',$time_out));
                        if($house_status = DB::fetch('select * from room_status where room_id='.$room_id.' and in_date>=\''.$start_date.'\' and in_date<=\''.$end_date.'\' and (house_status=\'REPAIR\' or house_status=\'HOUSEUSE\' or house_status=\'CLOSE\')')) {
                            $recode_return['status'] = 3; // phong dang duoc gan trang thai phong
                            $recode_return['recode'] = $house_status;
                        }
                        else
                        {
                            DB::update('reservation_room',array('room_id'=>$room_id),'id='.$reservation_room_id);
                            DB::update('room_status',array('room_id'=>$room_id),'reservation_room_id='.$reservation_room_id);
                            $reservation_id = DB::fetch('select reservation_id from reservation_room where id='.Url::get('reservation_room_id'),'reservation_id');
                            
                            DB::update('reservation',array('last_time'=>time(),'lastest_user_id'=>User::id()),'id='.$reservation_id);
                            $log_id = System::log('Asign Room','Asign Room Auto Monthly Room','Asign Room Auto Monthly Room: Reservation_Room: '.$reservation_room_id.' to Room Id: '.$room_id,$reservation_id);
                            System::history_log('RECODE',$reservation_id,$log_id);
                        }
                    }
                }
                echo json_encode($recode_return);
            }
            else if(Url::get('toolaction')=='UnAsignRoom') {
                $recode_return = array('status'=>0,'recode'=>array()); // trang thai thanh cong
                DB::update('reservation_room',array('room_id' => ''),'id = '.Url::get('reservation_room_id'));
                DB::update('room_status',array('room_id' => ''),'reservation_room_id = '.Url::get('reservation_room_id'));
                $reservation_id = DB::fetch('select reservation_id from reservation_room where id='.Url::get('reservation_room_id'),'reservation_id');
                DB::update('reservation',array('last_time'=>time(),'lastest_user_id'=>User::id()),'id='.$reservation_id);
                $log_id = System::log('Un Asign Room','Un Asign Room Auto Monthly Room','Asign Room Auto Monthly Room: Reservation_Room: '.Url::get('reservation_room_id'),$reservation_id);
                System::history_log('RECODE',$reservation_id,$log_id);
                echo json_encode($recode_return);
            }
            else if(Url::get('toolaction')=='FlatCheckIn') {
                $recode_return = array('status'=>0,'recode'=>array()); // trang thai thanh cong
                $reservation_room_id = Url::get('reservation_room_id');
                $room_id = Url::get('room_id');
                $time_in = Url::get('time_in');
                $time_out = Url::get('time_out');
                // kiem tra xem trang thai dat phong do con booked khong
                if($status_room = DB::fetch('select status from reservation_room where reservation_room.status!=\'BOOKED\' and reservation_room.id='.$reservation_room_id.''))
                {
                    $recode_return['status'] = 1; // phong co trang thai khac booked
                    $recode_return['recode'] = $status_room;
                }
                elseif(date('d/m/Y',$time_in)!=date('d/m/Y'))
                {
                    $recode_return['status'] = 2; // phong co ngay bat dau khac ngay hien tai
                    $recode_return['recode'] = array('date'=>date('d/m/Y'));
                }
                else
                {
                    // kiem tra xem dat phong duoc checkin con trong trong khoang thoi gian dat phong khong
                    if($reservation = DB::fetch('select * from reservation_room where time_in<='.$time_out.' and time_out>='.$time_in.' and room_id='.$room_id.' and status!=\'CANCEL\' and status!=\'BOOKED\''))
                    {
                        $recode_return['status'] = 3; // phong duoc gan khong con trong
                        $recode_return['recode'] = $reservation;
                    }
                    else
                    {
                        // kiem tra trang thai buong phong co repair, houseuse, closesale vao ngay hom do khong
                        $start_date = Date_Time::to_orc_date(date('d/m/Y',$time_in));
                        $end_date = Date_Time::to_orc_date(date('d/m/Y',$time_out));
                        if($house_status = DB::fetch('select * from room_status where room_id='.$room_id.' and in_date>=\''.$start_date.'\' and in_date<=\''.$end_date.'\' and (house_status=\'REPAIR\' or house_status=\'HOUSEUSE\' or house_status=\'CLOSE\')')) {
                            $recode_return['status'] = 4; // phong dang duoc gan trang thai phong
                            $recode_return['recode'] = $house_status;
                        }
                        else
                        {
                            DB::update('reservation_room',array('status'=>'CHECKIN','time_in'=>time()),'id='.$reservation_room_id);
                            DB::update('room_status',array('status'=>'OCCUPIED'),'reservation_room_id='.$reservation_room_id);
                            $reservation_id = DB::fetch('select reservation_id from reservation_room where id='.Url::get('reservation_room_id'),'reservation_id');
                            DB::update('reservation',array('last_time'=>time(),'lastest_user_id'=>User::id()),'id='.$reservation_id);
                            $log_id = System::log('Fast Checkin','Fast Checkin Room Monthly Room','Asign Room Auto Monthly Room: Reservation_Room: '.$reservation_room_id,$reservation_id);
                            System::history_log('RECODE',$reservation_id,$log_id);
                        }
                    }
                }
                echo json_encode($recode_return);
            }
            exit();
        }
        if(Url::get('ChangeRoomDrop') AND Url::get('r_r_id') AND Url::get('room_id')){
            /** check RealTime **/
            $last_time = DB::fetch('select reservation.last_time,reservation.lastest_user_id as user_id from reservation_room inner join reservation on reservation.id=reservation_room.reservation_id where reservation_room.id='.Url::get('r_r_id'));
            if($last_time['last_time']!=0 and $last_time['last_time']>Url::get('last_time')){
                $recode_return = array('status'=>404,'user'=>$last_time['user_id'],'time'=>date('H:i:s d/m/Y',$last_time['last_time']));
                echo 'RealTime:\n Lưu ý, Phòng đã được tài khoản '.$last_time['user_id'].' chỉnh sửa trước đó, vào lúc :'.date('H:i:s d/m/Y',$last_time['last_time']).' \n Dữ liệu hiện tại của bạn chưa được cập nhập nội dung chỉnh sửa đó \n \n Để tiếp tục thao tác bạn vui lòng tải lại trang !';
                exit();
            }
            /** End check RealTime **/
            require_once 'packages/hotel/packages/reception/modules/Reservation/forms/change_room.php';
            $change_room = new ChangeRoomForm();
            echo $change_room->change_room(Url::get('r_r_id'),Url::get('room_id'),Url::get('use_old_price',0),Url::sget('note_change_room'));
            exit();
        }
        /** end get ajax **/
        
		if(User::can_view(false,ANY_CATEGORY))
        {
            if(Url::get('cmd')=='repair' or Url::get('cmd')=='un_repair') {
                require_once 'forms/status.php';
				$this->add_form(new RoomStatus());
                return;
            }
            else {
                require_once 'forms/report.php';
		        $this->add_form(new MonthlyRoomReportNewForm());
                return;
            }
            
        }
        else
        {
            Url::access_denied();
        }
	}	
}
?>
