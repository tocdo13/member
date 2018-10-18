<?php
class DeleteForgotObjectForm extends Form
{
	function DeleteForgotObjectForm()
	{
		Form::Form("DeleteForgotObjectForm");
		$this->add('id',new IDType(true,'object_not_exists','forgot_object'));
	}
	function on_submit()
	{
		if($this->check() and Url::get('cmd')=='delete')
		{
			$this->delete($_REQUEST['id']);
			Url::redirect_current(array('time_start','time_end'));
		}
	}
	function draw()
	{
		$sql = '
			select 
				forgot_object.id,
				forgot_object.status,
				forgot_object.name,
				forgot_object.object_type ,
				forgot_object.quantity,
				forgot_object.unit,
				concat(concat(traveller.first_name , \'  \'),traveller.last_name) as full_name ,
				CASE WHEN reservation_room.time_in>0 THEN to_char(FROM_UNIXTIME(reservation_room.time_in)) ELSE \'\' END check_in_date,
				CASE WHEN reservation_room.time_out>0 THEN to_char(FROM_UNIXTIME(reservation_room.time_out)) ELSE \'\' END  as check_out_date ,
				CASE WHEN forgot_object.time>0 THEN to_char(FROM_UNIXTIME(forgot_object.time)) ELSE \'\' END AS time,
				room.name as room_id,
				country.name_'.Portal::language().' as country
			from 
			 	forgot_object
				left outer join reservation_room on RESERVATION_ROOM.id=forgot_object.reservation_room_id and reservation_room.room_id =forgot_object.room_id
				left outer join traveller on reservation_room.traveller_id=traveller.id
				left outer join room on room.id=forgot_object.room_id 
				left outer join country on country.id=traveller.nationality_id 
			where
				forgot_object.id = '.URL::get('id');
		if($row = DB::fetch($sql))
		{
			if ($row['status']==0)
			{
				$row['status']=Portal::language('notpay');
			}
		}
		$this->parse_layout('delete',$row);
	}
	function permanent_delete($id)
	{
		DB::delete_id('forgot_object', $id);
	}
	function delete($id)
	{
		$row = DB::select('forgot_object',$id);
		DeleteForgotObjectForm::permanent_delete($id);
		$room = DB::select('room',$row['room_id']);
		System::log('delete','Delete forgot object',
'Code:<a href="?page=forgot_object&id='.$id.'">'.$id.'</a><br>
Room:'.$room['name'].'<br>
Name:'.$row['name'].'<br>
Type:'.$row['object_type'].'<br>
Quantity:'.$row['quantity']);
	}
}
?>