<?php 
class KaraokeReservation extends Module{
	function KaraokeReservation($row){
		Module::Module($row);
		require_once 'db.php';	
		if(User::can_view(false,ANY_CATEGORY)){
			if(Url::check(array('act'=>'cancel','id')) and User::can_delete(false,ANY_CATEGORY)){
				$this->cancel_reservation(Url::iget('id'));
				echo '<script>';
				echo 'if(window.opener){window.opener.location.reload();window.close();}else{window.location = "'.Url::build_current(array('cmd'=>'list_cancel')).'";}';
				echo '</script>';
			}elseif(Url::check(array('act'=>'checkin','id'))){
				DB::update('karaoke_reservation',array('time_in'=>time(),'status'=>'CHECKIN'),'id=\''.Url::get('id').'\'');
				Url::redirect('karaoke_reservation',array('cmd','id'));
			}elseif(Url::check(array('act'=>'checkout','id'))){
				DB::update('karaoke_reservation',array('time_out'=>time(),'status'=>'CHECKOUT'),'id=\''.Url::get('id').'\'');
				$this->check_out(Url::get('id'));
				Url::redirect('karaoke_reservation',array('cmd','id'));
			}elseif(URL::check(array('cmd'=>'recover','id')) and $row = DB::exists_id('karaoke_reservation',Url::iget('id')) and User::can_delete(false,ANY_CATEGORY)){
				DB::update_id('karaoke_reservation', array('status'=>'RESERVATION','cancel_time'=>0),Url::iget('id'));
				URL::redirect_current();
			}elseif(URL::check('selected_ids') and is_array(URL::get('selected_ids')) and sizeof(URL::get('selected_ids'))>0  and User::can_delete(false,ANY_CATEGORY)){
				if(sizeof(URL::get('selected_ids'))>1){
					require_once 'forms/delete_selected.php';
					$this->add_form(new DeleteSelectedKaraokeReservationNewForm());
				}else{
					$ids = URL::get('selected_ids');
					$_REQUEST['id'] = $ids[0];
					require_once 'forms/delete.php';
					$this->add_form(new DeleteKaraokeReservationNewForm());
				}				
			}elseif(
				((
					Url::check('id') and $reservation=DB::exists('select id from karaoke_reservation where id='.Url::iget('id').'') and
					(URL::check(array('cmd'=>'delete'))and User::can_delete(false,ANY_CATEGORY)) 
					or (URL::check(array('cmd'=>'edit')) and (
						User::can_add(false,ANY_CATEGORY) or
						(
							User::can_add(false,ANY_CATEGORY) and $reservation['status'] != 'CHECKOUT'
						) 
					))
					or (URL::check(array('cmd'=>'list_debt')) and User::can_view(false,ANY_CATEGORY))
					or (URL::check(array('cmd'=>'list_free')) and User::can_view(false,ANY_CATEGORY))
					or (URL::check(array('cmd'=>'list_cancel')) and User::can_view(false,ANY_CATEGORY))
					or (URL::check(array('cmd'=>'check_in')) and User::can_add(false,ANY_CATEGORY))
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
					$this->add_form(new DeleteKaraokeReservationNewForm());break;
				case 'edit':
					require_once 'forms/edit.php';
					$this->add_form(new EditKaraokeReservationNewForm());break;
				case 'check_in':
					require_once 'forms/check_in.php';
					$this->add_form(new CheckInKaraokeForm());break;
				case 'detail':
					require_once 'forms/checkio_detail.php';
					$this->add_form(new DetailKaraokeForm());break;
				case 'add':
					require_once 'forms/add.php';
					$this->add_form(new AddKaraokeReservationNewForm());break;
				case 'list_debt':
					require_once 'forms/list.php';
					$this->add_form(new ListKaraokeReservationNewForm());break;
				case 'list_cancel':
					if(Url::get('act')=='delete'){
						$this->delete_reservation_cancel();
					}else{
						require_once 'forms/list.php';
						$this->add_form(new ListKaraokeReservationNewForm());break;
					}
				default: 
					if(URL::check('id') and DB::exists_id('karaoke_reservation',Url::iget('id')))
					{
						require_once 'forms/detail.php';
						$this->add_form(new KaraokeReservationNewForm());
					}
					else
					{
						require_once 'forms/list.php';
						$this->add_form(new ListKaraokeReservationNewForm());
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
	function cancel_reservation($id){
		$log_description = '<div>';
		$row = DB::select('karaoke_reservation','id = '.$id.'');
		$log_description .= '<div>Code: '.$id.'</div>';
		$log_description .= '<div>Status: '.$row['status'].'</div>';
		$log_description .= '<div>Arrival: '.date('H:i\' d/m/Y',$row['arrival_time']).'</div>';
		$log_description .= $row['departure_time']?'<div>Depature: '.date('H:i\' d/m/Y',$row['departure_time']).'</div>':'';
		$log_description .= '</div>';
		System::log('cancel','Cancel karaoke reservation with id: '.$id.'',$log_description,$id);
		DB::update_id('karaoke_reservation',array('status'=>'CANCEL','cancel_time'=>time()),$id);
	}
	function check_out()
	{
		$reservation = DB::select('karaoke_reservation',URL::get('id'));
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
						'content'=>'Use services of karaoke <a href="?page=karaoke_reservation&id='.URL::get('id').'">#'.URL::get('id').'</a> total='.$reservation['total']
					)
				);
			}
		}
	}
	function delete_reservation_cancel()
	{
		if(Url::get('id') and DB::exists_id('karaoke_reservation',intval(Url::get('id'))))
		{
			DB::delete_id('karaoke_reservation_cancel',intval(Url::get('id')));
			Url::redirect_current(array('cmd'));
		}
	}
}
?>