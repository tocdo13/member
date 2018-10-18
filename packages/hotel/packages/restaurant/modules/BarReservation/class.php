<?php 
class BarReservation extends Module
{
	function BarReservation($row)
	{
		Module::Module($row);
		require_once 'db.php';
		if(Url::get('bar_id'))
		{
			Session::set('bar_id',intval(Url::get('bar_id')));
		}
		else if(!Session::is_set('bar_id'))
		{
			require_once 'packages/hotel/includes/php/hotel.php';
			$bar = Hotel::get_new_bar();
			if($bar)
			{
				Session::set('bar_id',$bar['id']);
			}
			else
			{
				Session::set('bar_id','');
			}
		}
		$_REQUEST['bar_id'] = Session::get('bar_id');		
		if(User::can_view(false,ANY_CATEGORY))
		{
			if(Url::check(array('act'=>'cancel','id')) and User::can_delete(false,ANY_CATEGORY))
			{
				$this->cancel();
				Url::redirect_current();
			}
			else
			if(Url::check(array('act'=>'checkin','id')))
			{
				DB::update('bar_reservation',array('time_in'=>time(),'status'=>'CHECKIN'),'id=\''.Url::get('id').'\'');
				Url::redirect('bar_reservation',array('cmd','id'));
			}
			else
			if(Url::check(array('act'=>'checkout','id')))
			{
				DB::update('bar_reservation',array('time_out'=>time(),'status'=>'CHECKOUT'),'id=\''.Url::get('id').'\'');
				$this->check_out(Url::get('id'));
				Url::redirect('bar_reservation',array('cmd','id'));
			}
			else
			if(URL::check(array('cmd'=>'recover','id')) and $row = DB::exists_id('bar_reservation',$_REQUEST['id']) and User::can_delete(false,ANY_CATEGORY))
			{
				DB::update_id('bar_reservation', array('status'=>'RESERVATION','cancel_time'=>0),$_REQUEST['id']);
				URL::redirect_current();
			}
			else
			if(URL::check('selected_ids') and is_array(URL::get('selected_ids')) and sizeof(URL::get('selected_ids'))>0  and User::can_delete(false,ANY_CATEGORY))
			{
				if(sizeof(URL::get('selected_ids'))>1)
				{
					require_once 'forms/delete_selected.php';
					$this->add_form(new DeleteSelectedBarReservationForm());
				}
				else
				{
					$ids = URL::get('selected_ids');
					$_REQUEST['id'] = $ids[0];
					require_once 'forms/delete.php';
					$this->add_form(new DeleteBarReservationForm());
				}				
			}
			else
			if(
				((
					Url::check('id') and $reservation=DB::exists('select id from bar_reservation where id=\''.Url::get('id').'\'') and
					(URL::check(array('cmd'=>'delete'))and User::can_delete(false,ANY_CATEGORY)) 
					or (URL::check(array('cmd'=>'edit')) and (
						User::can_edit(false,ANY_CATEGORY) or
						(
							User::can_edit(false,ANY_CATEGORY) and $reservation['status'] != 'CHECKOUT'
						) 
					))
					or (URL::check(array('cmd'=>'list_debt')) and User::can_admin(false,ANY_CATEGORY))
					or (URL::check(array('cmd'=>'list_free')) and User::can_admin(false,ANY_CATEGORY))
					or (URL::check(array('cmd'=>'list_cancel')) and User::can_admin(false,ANY_CATEGORY))
					or (URL::check(array('cmd'=>'check_in')) and User::can_edit(false,ANY_CATEGORY))
					or (URL::check(array('cmd'=>'detail')) and User::can_view(false,ANY_CATEGORY))
					))
				or
				(URL::check(array('cmd'=>'add')) and User::can_add(false,ANY_CATEGORY))
				or !URL::check('cmd')
			)
			{
				switch(URL::get('cmd'))
				{
				case 'delete':
					require_once 'forms/delete.php';
					$this->add_form(new DeleteBarReservationForm());break;
				case 'edit':
					require_once 'forms/edit.php';
					$this->add_form(new EditBarReservationForm());break;
				case 'check_in':
					require_once 'forms/check_in.php';
					$this->add_form(new CheckInBarForm());break;
				case 'detail':
					require_once 'forms/checkio_detail.php';
					$this->add_form(new DetailBarForm());break;
				case 'add':
					require_once 'forms/add.php';
					$this->add_form(new AddBarReservationForm());break;
				case 'list_debt':
					require_once 'forms/list.php';
					$this->add_form(new ListBarReservationForm());break;
				case 'list_cancel':
					if(Url::get('act')=='delete')
					{
						$this->delete_reservation_cancel();
					}
					else
					{
						require_once 'forms/list.php';
						$this->add_form(new ListBarReservationForm());break;
					}
				default: 
					if(URL::check('id') and DB::exists_id('bar_reservation',$_REQUEST['id']))
					{
						require_once 'forms/detail.php';
						$this->add_form(new BarReservationForm());
					}
					else
					{
						require_once 'forms/list.php';
						$this->add_form(new ListBarReservationForm());
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
	
	function cancel()
	{
		DB::update_id('bar_reservation',array('status'=>'CANCEL','cancel_time'=>time()),intval(Url::get('id')));
	}
	
	function check_out()
	{
		$reservation = DB::select('bar_reservation',URL::get('id'));
		if($reservation['reservation_room_id'])
		{
			$travellers = DB::select_all('reservation_traveller','reservation_room_id=\''.$reservation['reservation_room_id'].'\'');
			
			foreach($travellers as $traveller)
			{
				DB::insert('traveller_comment',
					array(
						'user_id'=>Session::get('user_id'),
						'traveller_id'=>$traveller['traveller_id'],
						'time'=>time(),
						'content'=>'Use services of restaurant <a href="?page=bar_reservation&id='.URL::get('id').'">#'.URL::get('id').'</a> total='.$reservation['total']
					)
				);
			}
		}
	}
	function delete_reservation_cancel()
	{
		if(Url::get('id') and DB::exists_id('bar_reservation',intval(Url::get('id'))))
		{
			DB::delete_id('bar_reservation_cancel',intval(Url::get('id')));
			Url::redirect_current(array('cmd'));
		}
	}
}
?>