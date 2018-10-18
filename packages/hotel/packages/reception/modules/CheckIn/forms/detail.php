<?php
class ReservationForm extends Form
{
	function ReservationForm()
	{
		Form::Form("ReservationForm");
		$this->add('id',new IDType(true,'object_not_exists','reservation'));
	}
	function draw()
	{
		DB::query('
			select 
				`reservation`.id
				,`reservation`.`code` ,`reservation`.`note` 
				

				,if(customer.full_name<>"",customer.full_name,customer.name_1) as customer_name

				,`tour`.`name` as tour_id 
			from 
			 	`reservation`
				

				left outer join `customer` on `customer`.id=`reservation`.customer_id 

				left outer join `tour` on `tour`.id=`reservation`.tour_id 
			where
				`reservation`.id = '.URL::get('id'));
		if($row = DB::fetch())
		{
		}
		

		DB::query('
			select
				`reservation_room`.id
				,`reservation_room`.`price` ,IF(`reservation_room`.`time_in`<>"0000-00-00",DATE_FORMAT(`reservation_room`.`time_in`,"%d/%m/%Y"),"") as time_in ,IF(`reservation_room`.`time_out`<>"0000-00-00",DATE_FORMAT(`reservation_room`.`time_out`,"%d/%m/%Y"),"") as time_out ,IF(`reservation_room`.`arrival_time`<>"0000-00-00",DATE_FORMAT(`reservation_room`.`arrival_time`,"%d/%m/%Y"),"") as arrival_time ,`reservation_room`.`adult` ,IF(`reservation_room`.`departure_time`<>"0000-00-00",DATE_FORMAT(`reservation_room`.`departure_time`,"%d/%m/%Y"),"") as departure_time ,`reservation_room`.`note`,`reservation_room`.`total_amount` ,`reservation_room`.`reduce_balance` ,`reservation_room`.`deposit`,`reservation_room`.`reason` ,IF(`reservation_room`.`credit_limit`<>"0000-00-00",DATE_FORMAT(`reservation_room`.`credit_limit`,"%d/%m/%Y"),"") as credit_limit ,`reservation_room`.`tax_rate` ,`reservation_room`.`service_rate` 
				,`room`.name as room_id_name ,
				`room_level`.name_1 as room_level_id_name ,`currency`.name as currency_id_name ,
				`payment_type`.name_1 as payment_type_id_name 
			from
				`reservation_room`
				left outer join `room` on `reservation_room`.room_id = `room`.id left outer join `room_level` on `reservation_room`.room_level_id = `room_level`.id left outer join `currency` on `reservation_room`.currency_id = `currency`.id left outer join `payment_type` on `reservation_room`.payment_type_id = `payment_type`.id 
			where
				`reservation_room`.reservation_id='.$_REQUEST['id']
		);
		$row['reservation_room_items'] = DB::fetch_all();
			foreach($row['reservation_room_items'] as $key=>$value)
		{
				

			$row['reservation_room_items'][$key]['price'] = System::display_number($value['price']);    

			$row['reservation_room_items'][$key]['adult'] = System::display_number($value['adult']);    
			$row['reservation_room_items'][$key]['child'] = System::display_number($value['child']);
			$row['reservation_room_items'][$key]['total_amount'] = System::display_number($value['total_amount']); 

			$row['reservation_room_items'][$key]['reduce_balance'] = System::display_number($value['reduce_balance']); 
			$row['reservation_room_items'][$key]['deposit'] = System::display_number($value['deposit']); 
			$row['reservation_room_items'][$key]['tax_rate'] = System::display_number($value['tax_rate']); 
			$row['reservation_room_items'][$key]['service_rate'] = System::display_number($value['service_rate']); 
		} 

		DB::query('
			select
				`traveller`.id
				,`traveller`.`first_name` ,`traveller`.`last_name` ,`traveller`.`gender` ,IF(`traveller`.`birth_date`<>"0000-00-00",DATE_FORMAT(`traveller`.`birth_date`,"%d/%m/%Y"),"") as birth_date ,`traveller`.`passport` ,`traveller`.`visa` ,`traveller`.`note` ,`traveller`.`phone` ,`traveller`.`fax` ,`traveller`.`address` ,`traveller`.`email` 
				,`country`.name as nationality_id_name 
			from
				`traveller`
				left outer join `country` on `traveller`.nationality_id = `country`.id 
			where
				`traveller`.reservation_id='.$_REQUEST['id']
		);
		$row['traveller_items'] = DB::fetch_all();
			foreach($row['traveller_items'] as $key=>$value)
		{
				  

			$defintition = array('');
			if(isset($defintition[$value['gender']]))
			{
				$row['traveller_items'][$key]['gender'] = $defintition[$value['gender']];
			}
			else
			{
				$row['traveller_items'][$key]['gender'] = '';
			}         
		} 
		$this->parse_layout('detail',$row);
	}
}
?>