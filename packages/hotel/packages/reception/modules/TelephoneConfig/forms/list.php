<?php
class ListTelephoneConfigForm extends Form
{
	function ListTelephoneConfigForm()
	{
		Form::Form('ListTelephoneConfigForm');
		$this->link_js('packages/core/includes/js/jquery/jquery.maskedinput.js');		
	}
	function on_submit()
	{
		require_once 'packages/hotel/includes/php/hotel.php';	
		require_once 'packages/core/includes/utils/vn_code.php';
		require_once 'packages/hotel/packages/reception/Modules/includes/telephone_fox.php';
		if(Url::get('ajust'))
		{
			if(Url::get('an_reservation_room_id'))
			{
				$guest = $this->get_guest_telephone(Url::get('an_reservation_room_id'));
				TelephoneLib::set_telephone_command($guest['phone_number'],'AN',convert_utf8_to_latin($guest['full_name']));
			}
			echo '<script>alert("'.Portal::language('ajust_guest_name_success').'");window.location="'.Url::build_current().'"</script>';
		}
		if(Url::get('ajust_all'))
		{
			$reservation_rooms = Hotel::get_reservation_room();
			foreach($reservation_rooms as $key=>$value)
			{
				$guest = $this->get_guest_telephone($key);
				TelephoneLib::set_telephone_command($guest['phone_number'],'AN',convert_utf8_to_latin($guest['full_name']));
			}
			echo '<script>alert("'.Portal::language('ajust_all_guest_name_success').'");window.location="'.Url::build_current().'"</script>';			
		}
		if(Url::get('wake_up'))
		{
			TelephoneLib::set_telephone_command(Url::get('wu_telephone_number'),'WC');
			if(Url::get('wu_time'))
			{
				TelephoneLib::set_telephone_command(Url::get('wu_telephone_number'),'WU',str_replace(':','',Url::get('wu_time')));
			}
			echo '<script>alert("'.Portal::language('wake_up_room_success').'");window.location="'.Url::build_current().'"</script>';
		}
		if(Url::get('wake_up_group'))
		{
			if(Url::get('wu_group_id'))
			{
				$reservation_rooms = $this->get_reservation_room_group(intval(Url::get('wu_group_id')));
				foreach($reservation_rooms as $key=>$value)
				{
					$guest = $this->get_guest_telephone($key);
					TelephoneLib::set_telephone_command($guest['phone_number'],'WC');
					if(Url::get('wu_group_time')){
						TelephoneLib::set_telephone_command($guest['phone_number'],'WU',str_replace(':','',Url::get('wu_group_time')));
					}
				}
				echo '<script>alert("'.Portal::language('wake_up_all_room_success').'");window.location="'.Url::build_current().'"</script>';
			}
		}
		if(Url::get('open_telephone'))
		{
			if(Url::get('open_room_id'))
			{
				$guest = $this->get_guest_room(Url::get('open_room_id'));
				TelephoneLib::set_telephone_command($guest['phone_number'],'OUT','');
				TelephoneLib::set_telephone_command($guest['phone_number'],'IN',convert_utf8_to_latin($guest['full_name']));
			}
			echo '<script>alert("'.Portal::language('open_telephone_for_room').'");window.location="'.Url::build_current().'"</script>';			
		}
		if(Url::get('close_telephone'))
		{
			if(Url::get('close_room_id'))
			{
				$guest = $this->get_guest_room(Url::get('close_room_id'));
				TelephoneLib::set_telephone_command($guest['phone_number'],'OUT','');
			}
			echo '<script>alert("'.Portal::language('close_telephone_for_room').'");window.location="'.Url::build_current().'"</script>';			
		}
	}
	function draw()
	{
		require_once 'packages/hotel/includes/php/hotel.php';
		$this->map['wu_telephone_number_list'] = String::get_list(Hotel::get_telephone_room());
		$this->map['an_reservation_room_id_list'] = String::get_list(Hotel::get_reservation_room());
		$this->map['open_room_id_list'] = String::get_list(Hotel::get_available_room());
		$this->map['close_room_id_list'] = String::get_list(Hotel::get_available_room());		
		$this->map['wu_group_id_list'] = String::get_list($this->get_group());
		$this->parse_layout('list',$this->map);
	}
	function get_guest_telephone($reservation_room_id)
	{
		$guest = DB::fetch('
			SELECT
				reservation_room.id,
				CONCAT(traveller.first_name,CONCAT(\' \',traveller.last_name)) as full_name,
				room.name,
				telephone_number.phone_number
			FROM
				reservation_room
				LEFT OUTER JOIN reservation_traveller ON reservation_room.id = reservation_traveller.reservation_room_id
				LEFT OUTER JOIN traveller ON traveller.id = reservation_traveller.traveller_id
				INNER JOIN room ON room.id = reservation_room.room_id
				INNER JOIN telephone_number ON telephone_number.room_id = room.id
			WHERE
				reservation_room.id = '.$reservation_room_id.'
		');
		return $guest;
	}
	function get_guest_room($room_id)
	{
		$guest = DB::fetch('
			SELECT
				reservation_room.id,
				CONCAT(traveller.first_name,CONCAT(\' \',traveller.last_name)) as full_name,
				room.name,
				telephone_number.phone_number
			FROM
				room
				INNER JOIN telephone_number ON telephone_number.room_id = room.id
				LEFT OUTER JOIN reservation_room ON room.id = reservation_room.room_id AND reservation_room.status=\'CHECKIN\'
				LEFT OUTER JOIN reservation_traveller ON reservation_room.id = reservation_traveller.reservation_room_id
				LEFT OUTER JOIN traveller ON traveller.id = reservation_traveller.traveller_id
			WHERE
				room.id = '.$room_id.'
		');
		return $guest;
	}	
	function get_group()
	{
		return DB::fetch_all('
			SELECT
				reservation.id,
				CONCAT(\'RE\',CONCAT(reservation.id,CONCAT(\' - \',customer.name))) as name
			FROM
				reservation
				LEFT OUTER JOIN customer on customer.id = reservation.customer_id
			ORDER BY
				reservation.id
		');
	}
	function get_reservation_room_group($id)
	{
		return DB::fetch_all('
			SELECT
				reservation_room.id
			FROM
				reservation_room
				INNER JOIN reservation ON reservation.id = reservation_room.reservation_id
			WHERE
				reservation.id = '.$id.' AND reservation_room.status=\'CHECKIN\'
		');
	}
}
?>