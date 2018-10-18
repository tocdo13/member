<?php
class GuestRegistrationCardForm extends Form
{
	function GuestRegistrationCardForm()
	{
		Form::Form('GuestRegistrationCardForm');
	}
	function draw()
	{	
		$this->map = array();
		$sql = '
			SELECT
				reservation_room.*,room.name as room_name,reservation_room.price as room_rate,
				CONCAT(traveller.first_name,CONCAT(\' \',traveller.last_name)) as full_name,
				traveller.birth_date,country.name_1 as nationality,
				traveller.gender,
				traveller.phone,traveller.passport,
				customer.name as customer_name
			FROM
				reservation_room
				INNER JOIN reservation ON reservation.id = reservation_room.reservation_id
				INNER JOIN room ON room.id = reservation_room.room_id
				LEFT OUTER JOIN traveller ON traveller.id = reservation_room.traveller_id
				LEFT OUTER JOIN country ON country.id = traveller.nationality_id
				LEFT OUTER JOIN customer ON customer.id = reservation.customer_id
			WHERE 
				reservation_room.id='.Url::iget('id');
		if(Url::get('id') and $row = DB::fetch($sql)){
			$row['room_rate'] = System::display_number($row['room_rate']);
			if($row['service_rate']){
				$row['room_rate'] .= '+';
			}
			if($row['tax_rate']){
				$row['room_rate'] .= '+';
			}
			$this->map += $row;
			if(Url::get('form')){
				$layout = 'registration_form';
			}else{
				$layout = 'guest_registration_card';
			}
			$this->parse_layout($layout,$this->map);
		}
	}
}
?>