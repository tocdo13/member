<?php
class AddForm extends Form
{
	function AddForm()
	{
		Form::Form('AddForm');
	}
	function on_submit()
	{
		if(Url::check('update'))
		{
    		$sql='SELECT 
                        room.*
    				FROM 
    					room
                        inner join room_level ON room_level.id=room.room_level_id
    				WHERE
    					room.portal_id=\''.PORTAL_ID.'\'
                        and room_level.is_virtual = 0
    				ORDER BY
    					room.name
    			';
            
    		$rooms=DB::fetch_all($sql);
            
            
            foreach($rooms as $room_id=>$value)
            {
                if(Url::check('check_'.$room_id))
                {
                    $record = array(
                                'portal_id'=>PORTAL_ID,
                                'room_id'=>$room_id,
                                'note'=>Url::get('note_'.$room_id),
                                'portal_id'=>PORTAL_ID,
                                'user_id'=>Session::get('user_id'),
                                'start_time'=>time(),
                                'status'=>'CLEANING'
                                );
                    DB::insert('room_cleanup',$record);
                }
            }
			Url::redirect_current();
		}
	}	
	function draw()
	{		
		$sql='SELECT 
                    room.*
				FROM 
					room
                    inner join room_level ON room_level.id=room.room_level_id
				WHERE
					room.portal_id=\''.PORTAL_ID.'\'
                    and room_level.is_virtual = 0
                    and room.close_room=1
				ORDER BY
					room.name
			';
        
		$rooms=DB::fetch_all($sql);
        //System::debug($rooms);
		$layout='add';
		$this->parse_layout($layout,
			array(
			'rooms'=>$rooms,
			)
		);	
	}
}
?>