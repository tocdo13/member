<?php
class GuestRegistrationCardFormNew extends Form
{
	function GuestRegistrationCardFormNew()
	{
		Form::Form('GuestRegistrationCardFormNew');
	}
	function draw()
	{
		$this->map = array();
		$sql = '
			SELECT
				reservation_room.*,
                traveller.id as id,
                room.name as room_name,reservation_room.price as room_rate,
				reservation_room.net_price,
                reservation_room.reservation_id as reservation_id,
                                to_char(reservation_room.arrival_time,\'DD/MM/YYYY\') as arrival_time,
                                to_char(reservation_room.departure_time,\'DD/MM/YYYY\') as departure_time,
                reservation_room.time_in as reservation_time_in,
                reservation_room.time_out as reservation_time_out,               
				CONCAT(traveller.first_name,CONCAT(\' \',traveller.last_name)) as full_name,
				to_char(traveller.birth_date,\'DD/MM/YYYY\') as birth_date,
                                country.name_1 as nationality,
				traveller.gender, traveller.address, traveller.email,
                                CONCAT(traveller.phone, CONCAT(\' \',traveller.email)) as contact_details,
				traveller.phone,traveller.passport,traveller.gender,
				customer.name as customer_name,
                                traveller.address as customer_address,
                                customer.mobile as customer_mobile,
                                customer.phone as customer_phone,
                                customer.fax as customer_fax,
                                customer.email as customer_email,
                                room_level.brief_name as room_level,
                                reservation_traveller.arrival_time as time_in,
                                reservation_traveller.departure_time as time_out,
                                room_type.brief_name as room_type,
				reservation_traveller.flight_code,
                                to_char(reservation_traveller.entry_date,\'DD/MM/YYYY\') as date_entry,
                                to_char(reservation_traveller.expire_date_of_visa,\'DD/MM/YYYY\') as expire_date_of_visa,
                                reservation_traveller.port_of_entry as port,
                                reservation_traveller.flight_code_departure,
                                reservation_traveller.flight_arrival_time,
                                reservation_traveller.flight_departure_time,                                                                                                
                                reservation_traveller.flight_arrival_time,
                                reservation_traveller.flight_departure_time,
                                reservation_traveller.visa_number,
                                reservation_traveller.note,
                                reservation.id as reservation_id,
                                customer_group.name as reservation_type,
    				customer_group.show_price
			FROM
	                reservation_room
	                inner join reservation on reservation.id = reservation_room.reservation_id
                    left join reservation_type on reservation_room.reservation_type_id = reservation_type.id
                    left join room on reservation_room.room_id = room.id
                    left join room_type on room.room_type_id = room_type.id
                    left join room_level on room.room_level_id = room_level.id
                    left outer join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
                    left outer join traveller on reservation_traveller.traveller_id = traveller.id
                    left outer join country on traveller.nationality_id = country.id
                    left outer join customer on reservation.customer_id = customer.id
                    left outer join customer_group on customer_group.id=customer.group_id
                                
			WHERE
				    reservation_room.id='.Url::iget('id'); 
       
		if(Url::get('id') and $row = DB::fetch_all($sql)){
		  system::debug($row);
          $guest = array();
          $row1 = array();
          foreach($row as $key=>$value)
          {
            $guest[$key]['id'] = $value['id'];
            $guest[$key]['name'] = $value['full_name'];
            $row1 = $value;
           	$this->map['guest']['1'] = $value;
          }
          //System::debug($guest); 
          if(Url::get('guest_name')){
            $id_traveller = Url::get('guest_name');
            //echo $id_traveller;
            $row1 = $row[$id_traveller];
            
            $this->map['guest']['1'] = $row[$id_traveller];
          }
          $this->map['guest_name_list'] = array($value['id']=>$value['full_name'])+String::get_list($guest);
            
            
			$this->map += $row1;
            
            
			if(Url::get('form')==1){
				$layout = 'registration_form';
			}elseif(Url::get('form')==2){
				$layout = 'guest_confirmation_form';
			}elseif(Url::get('form')==3){
				$layout = 'guest_confirmation_form_vn';
			}else{
				$layout = 'guest_registration_card_new';
			}            
			$this->parse_layout($layout,$this->map);
            }
	}
}
?>