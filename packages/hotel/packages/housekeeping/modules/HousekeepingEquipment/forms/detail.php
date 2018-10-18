<?php
class HousekeepingEquipmentForm extends Form
{
	function HousekeepingEquipmentForm()
	{
		Form::Form("HousekeepingEquipmentForm");
		$this->add('id',new IDType(true,'object_not_exists','housekeeping_equipment'));
	}
	function draw()
	{
		$row = DB::fetch('
			select 
				housekeeping_equipment.id, 
                housekeeping_equipment.product_id, 
                housekeeping_equipment.quantity, 
                room.name as room_name 
			from  
				housekeeping_equipment 
				left outer join room on room.id=housekeeping_equipment.room_id 
			where 
                housekeeping_equipment.id=\''.Url::get('id').'\'
                and housekeeping_equipment.portal_id = \''.PORTAL_ID.'\'
                '
		);
                                                                
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
		
		$this->parse_layout('detail',$row+$product);
	}
}
?>