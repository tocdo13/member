<?php
class ForgotObjectForm extends Form
{
	function ForgotObjectForm()
	{
		Form::Form("ForgotObjectForm");
		$this->add('id',new IDType(true,'object_not_exists','forgot_object'));
	}
	function draw()
	{
		DB::query('
			select 
				forgot_object.id,
				forgot_object.status
				,forgot_object.name ,
				forgot_object.object_type ,
				forgot_object.quantity,
				forgot_object.unit,
				concat(concat(traveller.first_name , \'  \'),traveller.last_name) as full_name ,
				reservation_room.time_in as check_in_date ,
				reservation_room.time_out as check_out_date ,
				forgot_object.time as time,
				room.name as room_id,
				employee_profile.name as employee_id,
				country.name_'.Portal::language().' as country
			from 
			 	forgot_object
				left outer join reservation_room on RESERVATION_ROOM.id=forgot_object.reservation_room_id and reservation_room.room_id =forgot_object.room_id
				left outer join traveller on reservation_room.traveller_id=traveller.id
				left outer join room on room.id=forgot_object.room_id 
				left outer join employee_profile on employee_profile.id=forgot_object.employee_id 
				left outer join country on country.id=traveller.nationality_id 
			where
				forgot_object.id = '.URL::get('id'));
		if($row = DB::fetch())
		{
			if ($row['status']==0)
			{
				$row['status']=Portal::language('notpay');
			}
            else
            if ($row['status']==1)
			{
				$row['status']=Portal::language('pay');
			}
            else
            {
                $row['status']=Portal::language('handled');
            }
		}
		$this->parse_layout('detail',$row);
	}
}
?>