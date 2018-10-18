<?php
class HousekeepingEquipmentDamagedForm extends Form
{
	function HousekeepingEquipmentDamagedForm()
	{
		Form::Form('HousekeepingEquipmentDamagedForm');
		$this->add('quantity',new FloatType(true,'invalid_quantity','0','100000000000'));
        $this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js'); 
		//$this->link_js('packages/core/includes/js/calendar.js');
	}
	function on_submit()
	{
		if($this->check())
		{
            
            /*
			DB::query('
				update housekeeping_equipment 
				set quantity = quantity-'.intval(Url::get('quantity')).'
				where 
					room_id=\''.Url::get('room_id').'\' 
                    and product_id=\''.Url::get('product_id').'\'
			');
            */
			DB::query('
				update housekeeping_equipment 
				set damaged_quantity = damaged_quantity + '.intval(Url::get('quantity')).'
				where 
					room_id=\''.Url::get('room_id').'\' 
                    and product_id=\''.Url::get('product_id').'\'
			');
			DB::insert('housekeeping_equipment_damaged',array(
				'room_id',
				'product_id',
				'quantity',
				'note',
				'type',
				'time'=>time(),
                'portal_id'=>PORTAL_ID,
			));
			Url::redirect_current(array('cmd'=>'list_damaged','room_id'));
		}
	}	
	function draw()
	{
        /*
        $row = DB::fetch('
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
        ');
        */
		$row = DB::fetch('
				select 
					housekeeping_equipment.id,
                    housekeeping_equipment.product_id, 
                    (housekeeping_equipment.quantity) - (housekeeping_equipment.damaged_quantity) as quantity, 
                    room.name as room_name 
				from  
					housekeeping_equipment 
					left outer join room on room.id=housekeeping_equipment.room_id 
				where 
					housekeeping_equipment.room_id=\''.Url::get('room_id').'\' 
					and housekeeping_equipment.product_id=\''.Url::get('product_id').'\'
                    and housekeeping_equipment.portal_id = \''.PORTAL_ID.'\'
		');
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
				product_price_list.product_id = \''.Url::get('product_id').'\'
                and product_price_list.portal_id = \''.PORTAL_ID.'\'
                and product_price_list.department_code = \'HK\'
                '

		);
        //System::debug($row);
        //System::debug($product);
		if($product)
		{
			$this->parse_layout('damaged',$row+$product+array(
				'current_date'=>date('d/m/Y',time()),
			));
		}else{
			$this->parse_layout('damaged');
		}
	}
}
?>