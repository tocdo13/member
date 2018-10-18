<?php
class DeleteBarReservationNewForm extends Form
{
	function DeleteBarReservationNewForm()
	{
		Form::Form("DeleteBarReservationNewForm");
		$this->add('id',new IDType(true,'object_not_exists','bar_reservation'));
	}
	function on_submit()
	{
		if($this->check() and URL::get('confirm') and $product = DB::select('bar_reservation','id=\''.Url::iget('id').'\''))
		{
			//DB::update_id('bar_reservation',array('cancel_note'),Url::get('id'));
            if(DB::exists('
                        SELECT
                            mice_invoice_detail.invoice_id as id
                        FROM
                            mice_invoice_detail
                            INNER JOIN bar_reservation on bar_reservation.id = mice_invoice_detail.invoice_id
                        WHERE
                            bar_reservation.id =\''.Url::get('id').'\' and mice_invoice_detail.type = \'BAR\'
                                    
            '))
            {
                $this->error('','Hóa đơn nhà hàng '.Url::get('id').' đã được tạo Bill tại mice không được xóa!');
                return false;                
            }
            if(DB::exists('
                        SELECT
                            traveller_folio.invoice_id as id
                        FROM
                            traveller_folio
                            INNER JOIN bar_reservation on bar_reservation.id = traveller_folio.invoice_id
                        WHERE
                            bar_reservation.id =\''.Url::get('id').'\' and traveller_folio.type = \'BAR\'
            '))
            {
                $this->error('','Hóa đơn nhà hàng '.Url::get('id').' đã được tạo folio tại phòng không được xóa!');
                return false;                  
            }
			$this->delete(Url::iget('id'));
			Url::redirect_current(array('bar_reservation_receptionist_id'));
		}
	}
	function draw()
	{
		DB::query('
			select 
				bar_reservation.id
				,bar_reservation.code 
				,bar_reservation.status 
				,bar_reservation.num_table 
				,bar_reservation.arrival_time
				,bar_reservation.agent_name 
				,bar_reservation.agent_address 
				,bar_reservation.time
				,bar_reservation.agent_phone 
				,bar_reservation.departure_time
				,bar_reservation.note 
			 from 
			 	bar_reservation
				left outer join reservation_room on reservation_room.id=bar_reservation.reservation_room_id 
				left outer join traveller on traveller.id=reservation_room.traveller_id
			where
				bar_reservation.id = '.URL::get('id'));
		if($row = DB::fetch())
		{
			$this->parse_layout('delete',$row);
		}
	}
	function delete($id)
	{
		if(User::can_delete(false,ANY_CATEGORY)){
			$can_delete = false;
			if($row = DB::fetch('select * from bar_reservation where id = '.$id.'')){
				if($row['status']=='CHECKOUT'){
					$can_delete = false;
				}else{
					$can_delete = true;
				}
				if(User::can_admin(false,ANY_CATEGORY)){
					$can_delete = true;
				}
				if($can_delete){
					$log_description = '
						Agent name: '.$row['agent_name'].'<br>
						Agent address: '.$row['agent_address'].'<br>
						Agent phone: '.$row['agent_phone'].'<br>
						Agent fax: '.$row['agent_fax'].'<br>
						Arrival time: '.date('H:i d/m/Y',$row['arrival_time']).'<br>
						Departure time:  '.date('H:i d/m/Y',$row['departure_time']).'<br>
						Note: '.$row['note'].'
					';
					DB::delete_id('bar_reservation', $id);
					DB::delete('bar_reservation_table', 'bar_reservation_id = '.$id);
					DB::delete('bar_reservation_product', 'bar_reservation_id = '.$id);
                    DB::delete('bar_reservation_set_product', 'bar_reservation_id = '.$id);
					DB::delete('payment', 'bill_id='.$id.' and type = \'BAR\'');
					require_once 'packages/hotel/includes/php/product.php';
					if($bars =DB::fetch('select bar.* from bar where id='.$row['bar_id'].' and portal_id=\''.PORTAL_ID.'\'')){
						DeliveryOrders::delete_delivery_order($id,$bars['code']);
					}
					System::log('delete','Delete from bar reservation with id: \''.$id.'\'',$log_description,$id);
				}
			}
		}else{
			echo  '
				<script>alert("'.Portal::language('you_have_no_right_to_delete').'");window.location="'.Url::build_current(array('status')).'";</script>;
			';
			exit();
		}
	}
}
?>