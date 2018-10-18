<?php 
class BanquetReservation extends Module
{
	function BanquetReservation($row)
	{
 //exit();	
		Module::Module($row);
		require_once 'db.php';	
        //System::debug($_REQUEST);
       
		if(User::can_view(false,ANY_CATEGORY))
		{
			if(Url::check(array('act'=>'checkin','id')))
			{
				DB::update('party_reservation',array('checkin_time'=>time(),'status'=>'CHECKIN'),'id=\''.Url::get('id').'\'');
				Url::redirect('party_reservation',array('cmd','id'));
			}
			else
			if(Url::check(array('act'=>'checkout','id')))
			{
				DB::update('party_reservation',array('checkout_time'=>time(),'status'=>'CHECKOUT'),'id=\''.Url::get('id').'\'');
				Url::redirect('party_reservation',array('cmd','id'));
			}
			else
			if(URL::check(array('cmd'=>'recover','id')) and $row = DB::exists_id('party_reservation',$_REQUEST['id']) and User::can_delete(false,ANY_CATEGORY))
			{
				DB::update_id('party_reservation', array('status'=>'RESERVATION','cancel_time'=>0),$_REQUEST['id']);
				URL::redirect_current();
			}
            //else
//            if(Url::check(array('cmd'=>'cancel','id')) and User::can_delete(false,ANY_CATEGORY))
//			{
//				$this->cancel();
//				Url::redirect_current();
//			}
//			
			else
			if(URL::check('selected_ids') and is_array(URL::get('selected_ids')) and sizeof(URL::get('selected_ids'))>0  and User::can_delete(false,ANY_CATEGORY))
			{
				if(sizeof(URL::get('selected_ids'))>1)
				{
					require_once 'forms/delete_selected.php';
					$this->add_form(new DeleteSelectedBanquetReservationForm());;
				}
				else
				{
					$ids = URL::get('selected_ids');
					$_REQUEST['id'] = $ids[0];
					require_once 'forms/delete.php';
					$this->add_form(new DeleteBanquetReservationForm());
				}				
			}
			else
			if(
				(
					Url::check('id') and $reservation=DB::exists('select id from party_reservation where id=\''.Url::get('id').'\'') and
					(URL::check(array('cmd'=>'delete'))and User::can_delete(false,ANY_CATEGORY)) 
					or (URL::check(array('cmd'=>'edit')) and (
						User::can_edit(false,ANY_CATEGORY) or
						(
							User::can_edit(false,ANY_CATEGORY) and $reservation['status'] != 'CHECKOUT'
						) 
					))
                    or (URL::check(array('cmd'=>'cancel','id')) and User::can_delete(false,ANY_CATEGORY))
					or (URL::check(array('cmd'=>'list_debt')) and User::can_admin(false,ANY_CATEGORY))
					or (URL::check(array('cmd'=>'list_free')) and User::can_admin(false,ANY_CATEGORY))
					or (URL::check(array('cmd'=>'list_cancel')) and User::can_admin(false,ANY_CATEGORY))
					or (URL::check(array('cmd'=>'check_in')) and User::can_edit(false,ANY_CATEGORY))
					or (URL::check(array('cmd'=>'detail')) and User::can_view(false,ANY_CATEGORY))
				    or (URL::check(array('cmd'=>'view_contact')) and User::can_view(false,ANY_CATEGORY))
                    or (URL::check(array('cmd'=>'print_order')) and User::can_view(false,ANY_CATEGORY))
                    or (URL::check(array('cmd'=>'1')) and User::can_view(false,ANY_CATEGORY))
                    or (URL::check(array('cmd'=>'2')) and User::can_edit(false,ANY_CATEGORY))
                    or (URL::check(array('cmd'=>'3')) and User::can_view(false,ANY_CATEGORY))
                    or (URL::check(array('cmd'=>'4')) and User::can_view(false,ANY_CATEGORY))
                    or (URL::check(array('cmd'=>'5')) and User::can_view(false,ANY_CATEGORY))
                    )
				or
				(URL::check(array('cmd'=>'add')) and User::can_add(false,ANY_CATEGORY))
				or !URL::check('cmd')
			)
			{
				switch(URL::get('cmd'))
				{
				case 'delete':
					require_once 'forms/delete.php';
				    $this->add_form(new DeleteBanquetReservationForm());break;
				case 'edit':
					require_once 'forms/edit.php';
					$this->add_form(new EditBanquetReservationForm());break;
				case 'check_in':
					require_once 'forms/check_in.php';
					$this->add_form(new CheckInBarForm());break;
				case 'cancel':
                    $this->cancel();
				    Url::redirect_current();
                case 'detail':
					require_once 'forms/detail.php';
					$this->add_form(new DetailBanquetForm());break;
				case 'add':
					require_once 'forms/edit.php';
					$this->add_form(new EditBanquetReservationForm());break;
				case 'view_contact':
					require_once 'forms/view_contact.php';
					$this->add_form(new ViewContact1());break;
                case 'print_order':
                    require_once 'forms/print_order.php';
                    $this->add_form(new PrintOrder());break;
                case '1':
					require_once 'forms/wedding.php';
					$this->add_form(new Wedding());break;
                case '2':
					//require_once 'forms/birthday.php';
					//$this->add_form(new Birthday());break;
                    require_once 'forms/wedding.php';
					$this->add_form(new Wedding());break;
                case '3':
					//require_once 'forms/meeting.php';
					//$this->add_form(new Meeting());break;
                    require_once 'forms/wedding.php';
					$this->add_form(new Wedding());break;
                case '4':
					//require_once 'forms/meeting_company.php';
					//$this->add_form(new MeetingCompany());break;
                    require_once 'forms/wedding.php';
					$this->add_form(new Wedding());break;
                case '5':
					//require_once 'forms/company.php';
					//$this->add_form(new Company());break; 
                    require_once 'forms/wedding.php';
					$this->add_form(new Wedding());break;
				case 'list_debt':
					require_once 'forms/list.php';
					$this->add_form(new ListBanquetReservationForm());break;
				case 'list_cancel':
					if(Url::get('act')=='delete'){
						$this->delete_reservation_cancel();
					}else{
						require_once 'forms/list.php';
						$this->add_form(new ListBanquetReservationForm());break;
					}
				default: 
					require_once 'forms/list.php';
					$this->add_form(new ListBanquetReservationForm());
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
	
	function cancel()
	{
	   DB::update('party_reservation',array('cancel_time'=>time(),'status'=>'CANCEL'),'id=\''.Url::get('id').'\'');
	}
	function delete_reservation_cancel()
	{
		if(Url::get('id') and DB::exists_id('party_reservation',intval(Url::get('id'))))
		{
			DB::delete_id('party_reservation_cancel',intval(Url::get('id')));
			Url::redirect_current(array('cmd'));
		}
	}
}
?>