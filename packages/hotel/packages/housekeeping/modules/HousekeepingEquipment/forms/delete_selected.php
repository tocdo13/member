<?php
class DeleteSelectedHousekeepingEquipmentForm extends Form
{
	function DeleteSelectedHousekeepingEquipmentForm()
	{
		Form::Form("DeleteSelectedHousekeepingEquipmentForm");
		$this->add('confirm',new TextType(true,false,0, 20));
	}
	function on_submit()
	{
		if(URL::get('confirm'))
		{
			require_once 'delete.php';
			foreach(URL::get('selected_ids') as $id)
			{
				DB::delete_id('housekeeping_equipment', $id);
			}
			Url::redirect_current(array('room_id'));
		}
	}
	function draw()
	{
		$cond = '1>1';
		foreach(URL::get('selected_ids') as $value)
		{
			$temp = explode(',',$value);
			$cond .= ' or (housekeeping_equipment.product_id =\''.$temp[0].'\' and housekeeping_equipment.room_id =\''.$temp[1].'\') '; 
		}
		$sql = '
			select 
				housekeeping_equipment.id,
                housekeeping_equipment.quantity,
                product_price_list.product_id as product_code,
                product.name_'.Portal::language().' as product_name,
                room.name as room_name,
                to_char( FROM_UNIXTIME(housekeeping_equipment.time), \'DD/MM/YYYY\' ) as time
			from 
			 	housekeeping_equipment
				left join product_price_list on product_price_list.product_id=housekeeping_equipment.product_id 
                left join product on product_price_list.product_id = product.id
				inner join room on room.id = housekeeping_equipment.room_id 
			where
				'.$cond.'
                and housekeeping_equipment.portal_id = \''.PORTAL_ID.'\'
			order by
				product_code, room.id
		';
		$items = DB::fetch_all($sql);
		
		$this->parse_layout('delete_selected',
			array(
				'items'=>$items
			)
		);
	}
}
?>