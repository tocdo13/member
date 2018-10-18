<?php
class AddForgotObjectForm extends Form
{
	function AddForgotObjectForm()
	{
		Form::Form('AddForgotObjectForm');
		$this->add('forgot_object.name',new TextType(true,'invalid_name',0,255)); 
		$this->add('forgot_object.object_type',new TextType(true,'invalid_object_type',0,255));
		$this->add('forgot_object.unit',new TextType(true,'invalid_unit',0,255));
		$this->add('forgot_object.quantity',new IntType(true,'invalid_quantity',0,100000)); 
		$this->add('time',new DateType(true,'invalid_time'));
		$this->add('room_id',new IDType(true,'invalid_room_id','room'));
		$this->add('employee_name',new TextType(true,'invalid_employee_name',0,255));
		$this->link_js('packages/core/includes/js/multi_items.js');
		$this->link_js('packages/core/includes/js/dropdown.js');
		$this->link_js('packages/core/includes/js/suggest.js');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');

	}
	function on_submit()
	{
		if (Url::get('add_items'))
		{
		}else
		{
			if($this->check())
			{
				if(isset($_REQUEST['mi_forgot_object']))
				{
					$time = Date_Time::to_time(Url::get('time'));
					$hour = date('G', $time)+Url::get('hour');
					$minute = date('i', $time)+Url::get('minute');
					$time=$time+$hour*3600+$minute*60;
					DB::query('
							SELECT 
								reservation_room.id
							FROM 
								reservation_room
							WHERE 
								reservation_room.room_id='.Url::get('room_id').'
							ORDER BY
								reservation_room.time_out desc
						'
					);
					
					$row=DB::fetch();
					foreach($_REQUEST['mi_forgot_object'] as $key=>$record)
					{
						$record['time'] = $time;
						unset($record['id']);
						$record['room_id']=Url::get('room_id');
						$record['portal_id']=PORTAL_ID;
						$record['employee_name']=Url::get('employee_name');
						$record['reservation_room_id'] = $row['id'];
						$id = DB::insert('forgot_object',$record);
						$room = DB::select('room','id = '.Url::get('room_id').' and portal_id=\''.PORTAL_ID.'\'');
						System::log('add','Add forgot object',
'Code:<a href="?page=forgot_object&id='.$id.'">'.$id.'</a><br>
Room:'.$room['name'].'<br>
Name:'.$record['name'].'<br>
Type:'.$record['object_type'].'<br>
Quantity:'.$record['quantity']);
					}
					Url::redirect_current(array('cmd'=>'detail','time_start','time_end','just_edited_id'=>$id));
				}
			}
		}
	}
	function draw()
	{
		DB::query('
			select
				id, room.name as name
			from room
			where portal_id=\''.PORTAL_ID.'\'
            and room.close_room=1
			order by name
			'
		);
		$room_id_list = String::get_list(DB::fetch_all());
		DB::query('
			select distinct
				object_type as id,object_type as name
			from forgot_object
			where 
				forgot_object.portal_id=\''.PORTAL_ID.'\'
			order by name
			'
		);
		$object_type_list = array(''=>'')+String::get_list(DB::fetch_all()); 
		$this->parse_layout('add',
			array(
				'room_id_list'=>$room_id_list,
				'room_id'=>1,
				'object_type_list'=>$object_type_list,
				'forgot_object_items'=>isset($_REQUEST['table_forgot_object'])?current($_REQUEST['table_forgot_object']):array(),
				'time'=>URL::get('time',date('d/m/Y',time())),
				'hour'=>URL::get('hour',date('G')),
				'minute'=>URL::get('minute',date('i'))
			)
		);
	}
}
?>