<?php 
class CheckIn extends Module
{
	function CheckIn($row)
	{
		Module::Module($row);
		if(User::can_view(false,ANY_CATEGORY))
		{
			if(URL::check(array('delete_selected','selected_ids')) and is_array(URL::get('selected_ids')) and sizeof(URL::get('selected_ids'))>0 and User::can_delete(false,ANY_CATEGORY))
			{
				if(sizeof(URL::get('selected_ids'))>1)
				{
					require_once 'forms/delete_selected.php';
					$this->add_form(new DeleteSelectedReservationForm());
				}
				else
				{
					$ids = URL::get('selected_ids');
					$_REQUEST['id'] = $ids[0];
					require_once 'forms/delete.php';
					$this->add_form(new DeleteReservationForm());
				}
			}
			else
			if(
				(
					Url::check('id') and $reservation = DB::exists_id('reservation',Url::iget('id')) and
					(
						(
							URL::check(array('cmd'=>'delete'))
							and User::can_delete(false,ANY_CATEGORY)
						)
						or 
						(
							URL::check(array('cmd'=>'edit')) 
							and User::can_edit(false,ANY_CATEGORY)
						)
					)
				)
				or
				(URL::check(array('cmd'=>'add')) and User::can_add(false,ANY_CATEGORY))
				or
				(URL::check(array('cmd'=>'invoice')) and User::can_view(false,ANY_CATEGORY))
				or
				(URL::check(array('cmd'=>'tour_invoice')) and User::can_view(false,ANY_CATEGORY))
				or
				(URL::check(array('cmd'=>'check_availability')) and User::can_view(false,ANY_CATEGORY))
				or
				(URL::check(array('cmd'=>'rooming_list')) and User::can_view(false,ANY_CATEGORY))
				or
				(URL::check(array('cmd'=>'guest_registration_card')) and User::can_view(false,ANY_CATEGORY))
				or
				(URL::check(array('cmd'=>'pay_by_currency')) and User::can_edit(false,ANY_CATEGORY))
				or !URL::check('cmd')
			)
			{
				
				switch(URL::get('cmd'))
				{
				case 'delete':
					require_once 'forms/delete.php';
					$this->add_form(new DeleteReservationForm());break;
				case 'edit':
					require_once 'forms/edit.php';
					$this->add_form(new EditReservationForm());break;
				case 'add':
					require_once 'forms/add.php';
					$this->add_form(new AddReservationForm());break;
				case 'check_availability':
					require_once 'forms/check_availability.php';
					$this->add_form(new CheckAvailabilityForm());break;
				case 'rooming_list':
					require_once 'forms/rooming_list.php';
					$this->add_form(new RoomingListForm());break;	
				case 'guest_registration_card':
					require_once 'forms/guest_registration_card.php';
					$this->add_form(new GuestRegistrationCardForm());break;	
				case 'invoice':
					if(Url::get('id'))
					{
						$this->update_phone();
						require_once 'forms/invoice.php';
						$this->add_form(new InvoiceReservationForm());break;	
					}
					else
					{
						Url::redirect_current();
					}
				case 'pay_by_currency':
					if(Url::get('id'))
					{
						$this->update_phone();
						require_once 'forms/pay_by_currency.php';
						$this->add_form(new PayByCurrencyReservationForm());break;	
					}
					else
					{
						Url::redirect_current();
					}	
				case 'tour_invoice':
					if(Url::get('id'))
					{
						$this->update_phone();
						require_once 'forms/tour_invoice.php';
						$this->add_form(new TourInvoiceReservationForm());break;	
					}
					else
					{
						Url::redirect_current();
					}
				default: 
					{
						require_once 'forms/list.php';
						$this->add_form(new ListReservationForm());
					}
					break;
				}
			}
			else
			{
				Url::redirect_current();
			}
		}
		else
		{
			URL::access_denied();
		}
	}
	function update_phone(){
		//--------------------------------------------------------
		require_once 'packages/hotel/packages/reception/modules/TelephoneList/db.php';
		$file_name = 'http://letan01/DATA/2009/'.date('dmY',time()).'.001';		
		TelephoneListDB::update_telephone_daily($file_name);
		//--------------------------------------------------------
	}
}
?>