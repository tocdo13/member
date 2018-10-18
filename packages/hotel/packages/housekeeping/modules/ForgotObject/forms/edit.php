<?php
class EditForgotObjectForm extends Form
{
	function EditForgotObjectForm()
	{
		Form::Form('EditForgotObjectForm');
		$this->add('id',new IDType(true,'object_not_exists','forgot_object'));
		$this->add('name',new TextType(true,'invalid_name',0,255)); 
		$this->add('object_type',new TextType(true,'invalid_object_type',0,255)); 
		$this->add('status',new TextType(false,'invalid_status',0,255)); 
		$this->add('employee_name',new TextType(true,'invalid_employee_name',3,255));
		$this->link_js('packages/core/includes/js/suggest.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function on_submit()
	{
	   //System::debug($_REQUEST);exit();
		if (Url::get('add_items'))
		{
		}else
		{
			//$row = DB::select('forgot_object',Url::get('id'));
			if($this->check() and $row = DB::select( 'forgot_object',Url::get('id') ) )
			{
				$time = Date_Time::to_time(Url::get('time'));
				$hour = date('G', $time)+Url::get('hour');
				$minute = date('i', $time)+Url::get('minute');
				$time=$time+$hour*3600+$minute*60;
				/*
                DB::query('
						SELECT 
							reservation_room.id
						FROM 
							reservation_room
						WHERE 
							reservation_room.room_id='.Url::get('room_id').'
							and reservation_room.time_in<'.$time.'
						ORDER BY
							reservation_room.time_out desc
					'
				);
				$reservation_room=DB::fetch();
                */
                //System::debug($reservation_room);
				DB::update_id('forgot_object', 
					array(
						'employee_name',
						'name', 
                        'object_type', 
                        'quantity',
						'unit', 
                        'time'=>$time, 
						'status', 
                        'date_paid'=>Date_Time::to_time(Url::get('date_paid')),
                        'object_code',
                        'reason',
                        'position'
					),
					Url::get('id')
				);
                //'room_id',
                //'reservation_room_id'=>$reservation_room['id'],
				//$row = DB::select('forgot_object',Url::get('id'));
				if($row['room_id'])
                    $room = DB::select('room',$row['room_id']);
				System::log('edit','Edit forgot object',
                            'Code:<a href="?page=forgot_object&id='.Url::get('id').'">'.Url::get('id').'</a><br>
                            Room:'.( isset($room['name'])? $room['name'] : '' ).'<br>
                            Name:'.$_REQUEST['name'].'<br>
                            Type:'.$_REQUEST['object_type'].'<br>
                            Quantity:'.$_REQUEST['quantity']);
				Url::redirect_current(array('time_start','time_end','just_edited_id'=>Url::get('id')));
			}
		}
	}	
	function draw()
	{	
		DB::query('
			select 
				forgot_object.*,
                room.name as room_name,
                reservation_room.status as reservation_room_status,
                to_char(reservation_room.arrival_time, \'DD/MM/YYYY\') as arrival_time,
                to_char(reservation_room.departure_time, \'DD/MM/YYYY\') as departure_time
			from 
			 	forgot_object
                left outer join reservation_room on reservation_room.id=forgot_object.reservation_room_id
                left outer join room on room.id=forgot_object.room_id 
			where
				forgot_object.id = '.Url::get('id').'
			order by forgot_object.id desc
		');
		$row = DB::fetch();
        //System::debug($row);
		$row['hour']=date('G',$row['time']);
        	
		$row['minute']=date('i',$row['time']);
		$row['time']=date('d/m/Y',$row['time']);
		$row['date_paid']=date('d/m/Y',$row['date_paid']==0?time():$row['date_paid']);
		DB::query('select
			id, room.name as name
			from room
			order by name
			'
		);
		$room_id_list = String::get_list(DB::fetch_all());
        //System::debug($row); 
		foreach($row as $key=>$value)
		{
			if(is_string($value) and !isset($_REQUEST[$key]))
			{
				$_REQUEST[$key] = $value;
			}
		}
        $this->map['room_info'] =  $row['room_name'].' '.Portal::language('status').' : '.$row['reservation_room_status'].' '.Portal::language('arrival_time').' : '.$row['arrival_time'].' '.Portal::language('departure_time').' : '.$row['departure_time'];
		DB::query('select
			distinct
			object_type as id,object_type as name
			from forgot_object
			order by name
			'
		);
	$_REQUEST['employee_name']=trim($row['employee_name']);
        $_REQUEST['object_type'] = trim($row['object_type']);
        $_REQUEST['name'] = trim($row['name']);
        $_REQUEST['unit'] = trim($row['unit']);
		$object_type_list = String::get_list(DB::fetch_all()); 
		$this->parse_layout('edit',
			$row+
			array(
			 'room_id_list'=>$room_id_list, 
			 'object_type_list'=>$object_type_list
			)+$this->map
		);
	}
}
?>