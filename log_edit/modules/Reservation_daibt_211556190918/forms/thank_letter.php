<?php
class ThankLetterForm extends Form
{
	function ThankLetterForm()
	{
		Form::Form('ThankLetterForm');
	}
	function draw()
	{
        $this->map= array();
        $sql = '
        	SELECT
                traveller.id as id,
        		CONCAT(traveller.first_name,CONCAT(\' \',traveller.last_name)) as full_name
        	FROM
        		reservation_room
        		inner join reservation on reservation.id = reservation_room.reservation_id
                left join room on reservation_room.room_id = room.id
                left join room_type on room.room_type_id = room_type.id
                left join room_level on room.room_level_id = room_level.id
                left outer join reservation_traveller on reservation_room.id = reservation_traveller.reservation_room_id
                left outer join traveller on reservation_traveller.traveller_id = traveller.id       
        	WHERE
        		reservation_room.id='.Url::iget('id');
        $traveller = DB::fetch_all($sql);
        ksort($traveller);
        //System::debug($traveller);
        $this->map['traveller_option'] = '';
        foreach($traveller as $key => $value)
        {
            $this->map['traveller_option'] .= '<option value=\''.$value['full_name'].'\'>' . $value['full_name'] .'</option>';                        
        }
        if(Url::get('cmd') == 'thank_letter')
        {
            if(Url::get('type') == 'en')
            {
                $this->parse_layout('thank_letter_en',$this->map);                
            }else
            {
                $this->parse_layout('thank_letter_vn',$this->map);
            }
        }
	}
}
?>