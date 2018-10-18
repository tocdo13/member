<?php 
class MassageDailySummary extends Module
{
	public static $item = array();
	function MassageDailySummary($row)
	{
        	if(Url::get('action') == 'CheckRoomCheckOut'){   
            $items = DB::fetch('select reservation_room.id,room.name,reservation_room.status from reservation_room inner join room on room.id = reservation_room.room_id where reservation_room.id = '.Url::get('hotel_reservation_room_id').'');
            echo json_encode($items);
            exit();    
        }
		Module::Module($row);
		if(URL::get('reset'))
		{
			URL::redirect_current();
		}
		else
		{
			if(User::can_view(false,ANY_CATEGORY))
			{
				switch(Url::get('cmd'))
                {
					case 'delete':
						if(User::can_delete(false,ANY_CATEGORY) and Url::get('id') and $row = DB::fetch('SELECT * FROM MASSAGE_RESERVATION_ROOM WHERE ID = '.Url::iget('id').''))
                        {
							DB::delete('massage_staff_room','reservation_room_id = '.$row['id']);
							DB::delete('massage_reservation_room','id = '.$row['id']);
							DB::delete('massage_product_consumed','reservation_room_id = '.$row['id']);
							DB::delete('pay_by_currency','bill_id = '.$row['id'].' and type=\'MASSAGE\'');
							System::log('delete','Delete massage invoice','Delete massage invoice id: '.Url::get('id').'',Url::get('id'));
							echo '<script>
									if(window.opener)
									{
										window.opener.history.go(0);
										window.close();
									}
									window.location = "'.Url::build('massage_reservation').'";
								  </script> ';
							exit();
						}
						break;
					case 'add':
						//if(Url::get('room_id') and DB::exists('SELECT id FROM MASSAGE_ROOM WHERE id = '.Url::iget('room_id').'')){
							require_once 'forms/edit.php';
							$this->add_form(new MassageEditForm());
						//}else{
							//Url::redirect_current();
						//}						
						break;
					case 'edit':
						if(MassageDailySummary::$item = DB::fetch('select massage_reservation_room.*,room.name as hotel_room, 
                        concat(TRAVELLER.FIRST_NAME, concat(\' \',concat(TRAVELLER.LAST_NAME,concat(\' \',massage_reservation_room.full_name)))) as guest_name, 
                        massage_guest.code 
                        from massage_reservation_room
                        left join reservation_room on reservation_room.id =  massage_reservation_room.hotel_reservation_room_id
                        left join room on reservation_room.room_id = room.id
                        left join traveller on reservation_room.traveller_id = traveller.id
                        left outer join massage_guest on massage_guest.id = guest_id where massage_reservation_room.id='.Url::iget('id')))
                        {
							require_once 'forms/edit.php';
							$this->add_form(new MassageEditForm());
						}
                        else
                        {
							Url::redirect_current();
						}						
						break;									
					case 'invoice':
						if(User::can_view(false,ANY_CATEGORY) and Url::get('id') and MassageDailySummary::$item = DB::fetch('select massage_reservation_room.*,room.name as hotel_room, 
                        concat(TRAVELLER.FIRST_NAME, concat(\' \',concat(TRAVELLER.LAST_NAME,concat(\' \',massage_reservation_room.full_name)))) as guest_name, 
                        massage_guest.code 
                        from massage_reservation_room
                        left join reservation_room on reservation_room.id =  massage_reservation_room.hotel_reservation_room_id
                        left join room on reservation_room.room_id = room.id
                        left join traveller on reservation_room.traveller_id = traveller.id
                        left outer join massage_guest on massage_guest.id = guest_id where massage_reservation_room.id='.Url::iget('id')))
                        {
							require_once 'forms/invoice.php';
							$this->add_form(new MassageInvoiceForm());
						}
                        else
                        {
							Url::access_denied();
						}
						break;		
					default:
						require_once 'forms/report.php';
						$this->add_form(new MassageDailySummaryReportForm());
						break;					
				}
			}
			else
			{
				URL::access_denied();
			}
		}
	}
}
?>