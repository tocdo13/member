<?php
class DeleteBarReservationNewForm extends Form
{
	function DeleteBarReservationNewForm()
	{
		Form::Form("DeleteBarReservationNewForm");
		$this->add('id',new IDType(true,'object_not_exists','ve_reservation'));
	}
	function on_submit()
	{
		if($this->check() and URL::get('confirm') and $product = DB::select('ve_reservation','id=\''.Url::iget('id').'\''))
		{
			//DB::update_id('bar_reservation',array('cancel_note'),Url::get('id'));
			$this->delete(Url::iget('id'));
			Url::redirect_current(array('bar_reservation_receptionist_id'));
		}
	}
	function draw()
	{
        if(Url::iget('id'))
        {
    		DB::query('
    			select 
    				ve_reservation.id
    				,ve_reservation.code 
    				,ve_reservation.status 
    				,ve_reservation.arrival_time
    				,ve_reservation.agent_name 
    				,ve_reservation.agent_address 
    				,ve_reservation.time
    				,ve_reservation.agent_phone 
    				,ve_reservation.note 
    			 from 
    			 	ve_reservation
    			where
    				ve_reservation.id = '.URL::get('id'));
    		if($row = DB::fetch())
    		{
    			$this->parse_layout('delete',$row);
    		}
        }
        else
        {
            Url::redirect_current(array('bar_reservation_receptionist_id'));
        }

	}
	function delete($id)
	{
		if(User::can_delete(false,ANY_CATEGORY))
        {
			$can_delete = false;
			if($row = DB::fetch('select * from ve_reservation where id = '.$id.''))
            {
				if(User::can_admin())
                {
					$can_delete = true;
				}
				if(1==1)//($can_delete)
                {
					$log_description = '
                        Total: '.System::debug($row['total']).'<br>
						Agent name: '.$row['agent_name'].'<br>
						Agent address: '.$row['agent_address'].'<br>
                        Note user delete : '.Url::get('cancel_note').'
					';
					DB::delete_id('ve_reservation', $id);
					DB::delete('ve_reservation_product', 'bar_reservation_id = '.$id);
                                        DB::delete('payment','payment.bill_id=\''.$id.'\' AND payment.type=\'VEND\'');
					System::log('delete','Delete from vending reservation with id: \''.$id.'\'',$log_description,$id);
                    
                    require_once 'packages/hotel/includes/php/product.php';
                    DeliveryOrders::delete_delivery_order($id,'VENDING');
				}
			}
		}
        else
        {
			echo  '
				<script>alert("'.Portal::language('you_have_no_right_to_delete').'");window.location="'.Url::build_current(array('status')).'";</script>;
			';
			exit();
		}
	}
}
?>