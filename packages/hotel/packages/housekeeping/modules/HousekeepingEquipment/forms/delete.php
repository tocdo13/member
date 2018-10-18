<?php
class DeleteHousekeepingEquipmentForm extends Form
{
	function DeleteHousekeepingEquipmentForm()
	{
		Form::Form("DeleteHousekeepingEquipmentForm");
		$this->add('id',new IDType(true,'object_not_exists','housekeeping_equipment'));
	}
	function on_submit()
	{		
		if($this->check())
		{
			$sql = '
				select 
					housekeeping_equipment.id, 
                    housekeeping_equipment.product_id,
                    sum(housekeeping_equipment.quantity) as quantity,
                    room.name as room_name 
				from  
					housekeeping_equipment 
					left outer join room on room.id=housekeeping_equipment.room_id 
				where 
					housekeeping_equipment.room_id=\''.Url::get('room_id').'\' 
					and housekeeping_equipment.product_id=\''.Url::get('product_id').'\' 
                    and housekeeping_equipment.portal_id = \''.PORTAL_ID.'\'
				group by 
					housekeeping_equipment.id,
                    housekeeping_equipment.product_id,
                    room.name
			';
			$row = DB::fetch($sql);
			DB::delete_id('housekeeping_equipment', Url::get('id'));
			$product = DB::select('product',$row['product_id']);
			System::log('edit','Edit housekeeping equipment',
'Code:<a href="?page=housekeeping_equipment&id='.Url::get('id').'">'.Url::get('id').'</a><br>
Room:'.$row['room_name'].'<br>
Name:<a href="?page=product&id='.$product['id'].'">'.$product['name_'.Portal::language()].'</a><br>
Quantity:'.$row['quantity']);
			Url::redirect_current(array('room_id'));
		}
	}
	function draw()
	{
		$sql = '
				select 
					housekeeping_equipment.id, 
                    housekeeping_equipment.product_id, 
                    housekeeping_equipment.quantity, 
                    room.name as room_name 
				from  
					housekeeping_equipment 
					left outer join room on room.id=housekeeping_equipment.room_id 
				where 
					housekeeping_equipment.room_id=\''.Url::get('room_id').'\' 
					and housekeeping_equipment.product_id=\''.Url::get('product_id').'\' 
                    and housekeeping_equipment.portal_id = \''.PORTAL_ID.'\'
		';
		if(!($row = DB::fetch($sql)))
		{
			$row = array();
		}
		
		$sql = '
			select 
				product_price_list.product_id,
				product.name_'.Portal::language().' as name,
				unit.name_'.Portal::language().' as unit_name
			from  
				product_price_list
                inner join product on product_price_list.product_id = product.id
				inner join unit on unit.id=product.unit_id 
			where 
				product_price_list.product_id = \''.Url::get('product_id').'\'
                and product_price_list.portal_id = \''.PORTAL_ID.'\'
                and product_price_list.department_code = \'HK\'
                
                ';
		if(!($product = DB::fetch($sql)))
		{
			$product = array();
		}
		$this->parse_layout('delete',$row+$product);
	}
}
?>