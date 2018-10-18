<?php
class EditHousekeepingEquipmentForm extends Form
{
	function EditHousekeepingEquipmentForm()
	{
		Form::Form('EditHousekeepingEquipmentForm');
		$this->add('id',new IDType(true,'object_not_exists','housekeeping_equipment'));
		$this->add('room_id',new IDType(true,'invalid_room_id','room')); 
		$this->add('housekeeping_equipment.quantity',new FloatType(false,'invalid_quantity','0','100000000000')); 
	}
	function on_submit()
	{
		if($this->check())
		{
            exit();
			echo Url::get('id');
			$row = DB::select('housekeeping_equipment',Url::get('id'));
			DB::update('housekeeping_equipment',array(
				'room_id',
				'quantity',
				),'id='.Url::get('id')
			);
			//$product = DB::select('hk_product',$row['product_id']);
			$product = DB::select('product',$row['product_id']);
            $room = DB::select('room',Url::get('room_id'));
			System::log('edit','Edit housekeeping equipment',
'Code:<a href="?page=housekeeping_equipment&id='.Url::get('id').'">'.Url::get('id').'</a><br>
Room:'.$room['name'].'<br>
Name:<a href="?page=product&id='.$product['id'].'">'.$product['name_'.Portal::language()].'</a><br>
Quantity:'.URL::get('quantity'));
			//Url::redirect_current(array('room_id'));
		}
	}	
	function draw()
	{
		$row = DB::fetch('select * from  housekeeping_equipment where id=\''.Url::get('id').'\'');
		/*
        $product = DB::fetch('
			select 
				hk_product.id as product_id,
				hk_product.name_'.Portal::language().' as name,
				unit.name_'.Portal::language().' as unit_name
			from  
				hk_product 
				left outer join unit on unit.id=hk_product.unit_id 
			where 
				hk_product.id = \''.$row['product_id'].'\''
		);
        */
		$product = DB::fetch('
			select 
				product_price_list.product_id as product_id,
				product.name_'.Portal::language().' as name,
				unit.name_'.Portal::language().' as unit_name
			from  
				product_price_list
                inner join product on product_price_list.product_id = product.id
				inner join unit on unit.id=product.unit_id 
			where 
				product_price_list.product_id = \''.$row['product_id'].'\'
                and product_price_list.portal_id = \''.PORTAL_ID.'\'
                and product_price_list.department_code = \'HK\'
                '
		);
        
		DB::query('select
			id, room.name as name
			from room
		');
		$room_id_list = String::get_list(DB::fetch_all()); 
		
		$this->parse_layout('edit',$row+$product+array(
			'room_id_list'=>$room_id_list, 
		));
	}
}
?>