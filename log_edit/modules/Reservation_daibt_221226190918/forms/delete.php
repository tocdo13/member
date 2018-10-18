<?php
class DeleteReservationForm extends Form
{
	function DeleteReservationForm()
	{
		Form::Form('DeleteReservationForm');
		$this->add('id',new IDType(true,'object_not_exists','reservation'));
        require_once 'packages/hotel/includes/php/product.php';
	}
	function on_submit()
	{
		if($this->check())
		{
		echo '</script><script src="packages/hotel/includes/js/ajax.js"></script>';
			require_once 'packages/hotel/packages/reception/modules/includes/reservation.php';
			$this->delete($_REQUEST['id']);
			if(!$this->is_error())
			{
				echo '<div id="progress">Updating room status to server...</div>
					<script>
						if(window.opener && (window.opener.year || window.opener.night_audit))
						{
							window.opener.history.go(0);
							window.close();
						}
						window.setTimeout("location=\''.Url::build('room_map').'\'",1000);
					</script>';
				exit();
			}
		}
	}
	function draw()
	{
		DB::query('
			select
				reservation.id,reservation.note
				,customer.name as customer_name
				,tour.name as tour_id
			from
			 	reservation
				left outer join customer on customer.id=reservation.customer_id
				left outer join tour on tour.id=reservation.tour_id
			where
				reservation.id = '.URL::get('id'));
		$row = DB::fetch();
		$this->parse_layout('delete',$row);
	}
	function permanent_delete($id)
	{
		$row = DB::select('reservation',$id);
		$item_rows = DB::select_all('reservation_room','reservation_id='.$id.(URL::get('reservation_room_id')?' and id=\''.URL::get('reservation_room_id').'\'':''));
		foreach($item_rows as $item_row)
		{
			DB::delete('reservation_traveller','reservation_room_id='.$item_row['id']);
			DB::delete('traveller_folio','reservation_room_id='.$item_row['id'].'');
			DB::delete('folio','reservation_room_id='.$item_row['id'].'');
            // LDD them de update cac ticket_reservation, bar_reservation, ve_reservation
            DB::update('ticket_reservation',array('reservation_room_id'=>0),'reservation_room_id = '.$item_row['id']);
            
            //DB::update('ve_reservation',array('reservation_room_id'=>'', 'reservation_room_id = '.$item_row['id']));
		    //Start:KID them xoa hoa don minibar, giặt là
            $minibar = DB::fetch_all('select housekeeping_invoice.id 
                                    from housekeeping_invoice
                                    where housekeeping_invoice.reservation_room_id ='.$item_row['id'] .' and type=\'MINIBAR\'');
            foreach($minibar as $mi => $ni)
            {
                DB::delete('housekeeping_invoice_detail','invoice_id='.$mi);
                DB::delete('housekeeping_invoice','id='.$mi);
                DeliveryOrders::delete_delivery_order($mi,'MINIBAR');
            }
            
            $laundry = DB::fetch_all('select housekeeping_invoice.id 
                                        from housekeeping_invoice
                                        where housekeeping_invoice.reservation_room_id ='.$item_row['id'] .'and type=\'LAUNDRY\'');
            foreach($laundry as $la => $dry)
            {
                DB::delete('housekeeping_invoice_detail','invoice_id='.$la);
                DB::delete('housekeeping_invoice','id='.$la);
            }
            //End:KID them xoa hoa don minibar, giặt là 
            
            //Start: giap.ln xu ly xoa tat ca thong tin spa, nha hang co chuyen ve phong
            $restaurants = DB::fetch_all("SELECT * FROM bar_reservation WHERE reservation_room_id=".$item_row['id']);
            if(!empty($restaurants))
            {
                DB::delete('bar_reservation','reservation_room_id = '.$item_row['id']);
                foreach($restaurants as $row_bar)
                {
                    
                    DB::delete('bar_reservation_table','bar_reservation_id = '.$row_bar['id']);
                    
                    DB::delete('bar_reservation_product','bar_reservation_id = '.$row_bar['id']);
                    
                    DB::delete('payment','bill_id='.$row_bar['id'].' AND type=\'BAR\'');
                }
            }
            else
                DB::update('bar_reservation',array('reservation_room_id'=>0),'reservation_room_id = '.$item_row['id']);
                
            $messages = DB::fetch_all("SELECT id FROM massage_reservation_room WHERE hotel_reservation_room_id=".$item_row['id']);
            if(!empty($messages))
            {
                DB::delete('massage_reservation_room','hotel_reservation_room_id = '.$item_row['id']);
                foreach($messages as $row_spa)
                {
                    
                    //xoa nhan vien thuc hien
                    DB::delete('massage_staff_room','reservation_room_id = '.$row_spa['id']);
                    //xoa cac product su dung 
                    DB::delete('massage_product_consumed','reservation_room_id = '.$row_spa['id']);
                    //xoa currency thanh toan 
                   //	DB::delete('pay_by_currency','bill_id = '.$row_spa['id'].' and type=\'MASSAGE\'');
                    //xoa tat ca nhung thanh toan cua spa do 
                    DB::delete('payment','bill_id='.$row_spa['id'].' AND type=\'SPA\'');
                }
            }
               
            
            //End 
            
            
            //Start: giap.ln xoa thong tin ve karaoke chuyen ve phong
            $karaokes = DB::fetch_all("SELECT id FROM karaoke_reservation WHERE reservation_room_id=".$item_row['id']);
            if(!empty($karaokes))
            {
                DB::delete('karaoke_reservation','reservation_room_id='.$item_row['id']);
                foreach($karaokes as $row)
                {
                    DB::delete('karaoke_reservation_table','karaoke_reservation_id='.$row['id']);
                    DB::delete('payment','payment.bill_id='.$row['id'].' AND type=\'KARAOKE\'');
                }
            } 
            //END 
        }
		DB::delete('reservation_traveller','reservation_id = '.$row['id']);
		DB::update('room_status',array('reservation_id'=>0,'status'=>'AVAILABLE'),'reservation_id='.$id);
		DB::delete('payment',' reservation_id='.$id.'');
		DB::delete('folio',' reservation_id = '.$id.'');
		DB::delete('traveller_folio',' reservation_id = '.$id.'');
        
        $arr_invoice_id = DB::fetch_all("select extra_service_invoice.id 
                                        from extra_service_invoice
                                        inner join reservation_room on reservation_room.id = extra_service_invoice.reservation_room_id
                                        where reservation_room.reservation_id =".$id);
        if($arr_invoice_id)
        {
            foreach($arr_invoice_id as $key => $value)
            {
                DB::delete('extra_service_invoice_detail','invoice_id='.$key);
                DB::delete('extra_service_invoice','id='.$key);
            }
        }
		DB::delete('reservation_room', 'reservation_id='.$id.(URL::get('reservation_room_id')?' and id=\''.URL::get('reservation_room_id').'\'':''));
		if(!URL::get('reservation_room_id'))
		{
			DB::delete_id('reservation', $id);
		}
	}
	function delete($id)
	{
		if($row = DB::fetch('
			select
				reservation.id,
				reservation.customer_id,
				customer.name as customer_name,
				reservation.note
			from
				reservation
				left outer join customer on customer.id=reservation.customer_id
			where reservation.id=\''.$id.'\''
		))
		{
			if(!URL::get('reservation_room_id'))
			{
				$title = 'Delete reservation #'.$id;
				$description = ''
				.'Code:'.substr($id,0,255).'<br>  '
				.'Note:'.substr($row['note'],0,255).'<br>  '
				.($row['customer_id']?'Customer name:'.$row['customer_name'].'<br>  ':'');
				$reservation_rooms = DB::fetch_all('
					select
						reservation_room.id,
						reservation_room.status,
						arrival_time,
						departure_time,
						price,
						room.name as room_name
					from
						reservation_room
						inner join room on room_id=room.id
					where reservation_room.reservation_id='.$id
				);
				$description .= '<u>Rooms:</u>';
				foreach($reservation_rooms as $reservation_room)
				{
					if(($reservation_room['status']=='CHECKIN' or $reservation_room['status']=='CHECKOUT') and !USER::can_admin(false,ANY_CATEGORY))
					{
						$this->error('','Cannot delete reservation because it has been checkin or checkout');
					}
					$description .= '<li>Room:'.$reservation_room['room_name'].' '.$reservation_room['price'].' from '.$reservation_room['arrival_time'].' to '.$reservation_room['departure_time'].'</li>';
				}
				if(!$this->is_error())
				{
					DeleteReservationForm::permanent_delete($id);
					$log_id = System::log('delete',$title,$description,$id);
                    System::history_log('RECODE',$id,$log_id);
				}
				else
				{
					$log_id = System::log('delete',$title.'. But dont have permission!',$description,$id);
                    System::history_log('RECODE',$id,$log_id);
				}
			}
			else
			{
				$this->delete_reservation_room($id,URL::get('reservation_room_id'));
			}
		}
	}
	function delete_reservation_room($reservation_id, $reservation_room_id)
	{
		if($reservation_room = DB::fetch('
			select
				reservation_room.id,
				reservation_room.status,
				arrival_time,
				departure_time,
				reservation_room.price,
				room.name as room_name
			from
				reservation_room
				inner join room on room_id=room.id
			where reservation_room.id='.$reservation_room_id
		))
		{
			$title = 'Delete room '.$reservation_room['room_name'].' from reservation <a href=\'?page=reservation&cmd=edit&id='.$reservation_id.'\'>#'.$reservation_id.'</a>';
			$description = 'Room:'.$reservation_room['room_name'].' '.$reservation_room['price'].' from '.$reservation_room['arrival_time'].' to '.$reservation_room['departure_time'];
			if(($reservation_room['status']=='CHECKIN' or $reservation_room['status']=='CHECKOUT') and !USER::can_admin(false,ANY_CATEGORY))
			{
				$this->error('','Cannot delete reservation because it has been checkin or checkout');
				$log_id = System::log('delete',$title.'. But dont have permission!',$description,$reservation_id);
                System::history_log('RECODE',$reservation_id,$log_id);
			}
			else
			{
			    // LDD them de update cac ticket_reservation, bar_reservation, ve_reservation
                DB::update('ticket_reservation',array('reservation_room_id'=>0),'reservation_room_id = '.$reservation_room['id']);
                DB::update('bar_reservation',array('reservation_room_id'=>0),'reservation_room_id = '.$reservation_room['id']);
				DB::update('room_status',array('status'=>'AVAILABLE','reservation_id'=>0),'reservation_room_id='.$reservation_room['id']);
				DB::delete('reservation_traveller','reservation_room_id='.$reservation_room['id']);
				DB::delete_id('reservation_room',$reservation_room['id']);
				DB::delete('payment',' reservation_room_id = '.$reservation_room['id'].'');
				DB::delete('folio',' reservation_room_id = '.$reservation_room['id'].'');
				DB::delete('traveller_folio','  reservation_room_id = '.$reservation_room['id'].'');
				DB::delete('reservation_room_service','reservation_room_id='.$reservation_room['id'].'');
                $arr_invoice_id = DB::fetch_all("select extra_service_invoice.id 
                                        from extra_service_invoice
                                        where extra_service_invoice.reservation_room_id =".$reservation_room['id']);
                //System::debug($arr_invoice_id);exit();
                if($arr_invoice_id)
                {
                    foreach($arr_invoice_id as $key => $value)
                    {
                        DB::delete('extra_service_invoice_detail','invoice_id='.$key);
                        DB::delete('extra_service_invoice','id='.$key);
                    }
                }
                //Start:KID them xoa hoa don minibar, giặt là
                $minibar = DB::fetch_all('select housekeeping_invoice.id 
                                        from housekeeping_invoice
                                        where housekeeping_invoice.reservation_room_id ='.$reservation_room['id'] .'and type=\'MINIBAR\'');
                foreach($minibar as $mi => $ni)
                {
                    DB::delete('housekeeping_invoice_detail','invoice_id='.$mi);
                    DB::delete('housekeeping_invoice','id='.$mi);
                    DeliveryOrders::delete_delivery_order($mi,'MINIBAR');
                }
                
                $laundry = DB::fetch_all('select housekeeping_invoice.id 
                                        from housekeeping_invoice
                                        where housekeeping_invoice.reservation_room_id ='.$reservation_room['id'] .'and type=\'LAUNDRY\'');
                foreach($laundry as $la => $dry)
                {
                    DB::delete('housekeeping_invoice_detail','invoice_id='.$la);
                    DB::delete('housekeeping_invoice','id='.$la);
                }
                //End:KID them xoa hoa don minibar, giặt là 
                //Start: giap.ln xu ly xoa tat ca thong tin spa co chuyen ve phong
                
                $messages =DB::fetch_all("SELECT id FROM massage_reservation_room WHERE hotel_reservation_room_id=".$reservation_room['id']);
                if(!empty($messages))
                {
                    foreach($messages as $row)
                    {
                        DB::delete('massage_reservation_room','id = '.$row['id']);
                        //xoa nhan vien thuc hien
                        DB::delete('massage_staff_room','reservation_room_id = '.$row['id']);
                        //xoa cac product su dung 
                        DB::delete('massage_product_consumed','reservation_room_id = '.$row['id']);
                        //xoa currency thanh toan 
                       	DB::delete('pay_by_currency','bill_id = '.$row['id'].' and type=\'MASSAGE\'');
                        //xoa tat ca nhung thanh toan cua spa do 
                        DB::delete('payment','bill_id='.$row['id'].' AND type=\'SPA\'');
                    }
                }
               
                //End
				$log_id = System::log('delete',$title,$description,$reservation_id);
                System::history_log('RECODE',$reservation_id,$log_id);
			}
		}
	}
}
?>