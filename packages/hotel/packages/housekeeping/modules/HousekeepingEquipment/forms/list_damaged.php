<?php
class ListHousekeepingEquipmentDamagedForm extends Form
{
	function ListHousekeepingEquipmentDamagedForm()
	{
		Form::Form('ListHousekeepingEquipmentDamagedForm');
		$this->link_js('packages/core/includes/js/calendar.js');
		if(Url::get('act')=='delete')
		{
			DB::query('
				update housekeeping_equipment 
				set quantity=quantity+'.Url::get('quantity').'
				where 
					room_id=\''.Url::get('room_id').'\' and
					product_id=\''.Url::get('product_id').'\'
			');
			DB::delete('housekeeping_equipment_damaged','room_id=\''.Url::get('room_id').'\' and product_id=\''.Url::get('product_id').'\'');
			Url::redirect_current(array('cmd'=>'list_damaged','room_id'));
		}
	}
    
    function on_submit()
    {
        $selected_ids = Url::get('selected_ids');
		if(!empty($selected_ids))
		{
            foreach($selected_ids as $id)
            {
                if($row = DB::select_id('housekeeping_equipment_damaged',$id))
                {
                    DB::query('
        				update housekeeping_equipment 
        				set damaged_quantity = damaged_quantity -'.$row['quantity'].'
        				where 
        					room_id=\''.$row['room_id'].'\' 
                            and product_id=\''.$row['product_id'].'\'
        			     ');
                    DB::delete('housekeeping_equipment_damaged','id=\''.$id.'\'');
                }
                
            }
		}
    }
    
	function draw()
	{
		$cond = '
				1>0 and housekeeping_equipment_damaged.portal_id=\''.PORTAL_ID.'\''
				.(Url::get('room_id')!=''?' and housekeeping_equipment_damaged.room_id=\''.Url::get('room_id').'\'':'')
				.(Url::get('product_id')!=''?' and housekeeping_equipment_damaged.product_id=\''.Url::get('product_id').'\'':'')
				.(Url::get('damaged_type')!=''?' and upper(housekeeping_equipment_damaged.type)=\''.strtoupper(Url::get('damaged_type')).'\'':'')
		;
		$item_per_page = 100;
        
		DB::query('
			select count(*) as acount
			from 
				housekeeping_equipment_damaged
				inner join product on product.id=housekeeping_equipment_damaged.product_id 
				left outer join room on room.id=housekeeping_equipment_damaged.room_id 
			where '.$cond.'
		');
		$count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page,10,false,'page_no',array('room_id',"product_id",'cmd'));		
		/*
        $sql = '
			select * from
			(
				select hs.*, ROWNUM as rownumber from
				(	
					select
						housekeeping_equipment_damaged.id,
						housekeeping_equipment_damaged.product_id,
                        housekeeping_equipment_damaged.room_id,
                        housekeeping_equipment_damaged.type,
                        to_char(FROM_UNIXTIME(housekeeping_equipment_damaged.time),\'DD/MM/YYYY\') as time,
                        sum(housekeeping_equipment_damaged.quantity) as quantity,
                        product.name_'.Portal::language().' as product_name,
                        room.name as room_name,
                        housekeeping_equipment_damaged.note
					from 
						housekeeping_equipment_damaged
						inner join product on product.id=housekeeping_equipment_damaged.product_id 
						left outer join room on room.id=housekeeping_equipment_damaged.room_id 
					where '.$cond.'
					group by
						housekeeping_equipment_damaged.id,
						housekeeping_equipment_damaged.product_id,
                        housekeeping_equipment_damaged.room_id,
                        housekeeping_equipment_damaged.type,
                        to_char(FROM_UNIXTIME(housekeeping_equipment_damaged.time),\'DD/MM/YYYY\'),
                        product.name_'.Portal::language().',
                        room.name,
                        housekeeping_equipment_damaged.note
					order by 
                        housekeeping_equipment_damaged.product_id desc
				) hs 
			) where rownumber > '.((page_no()-1)*$item_per_page).' and rownumber <= '.(page_no()*$item_per_page).'
		';
		*/
        
        $sql = '
			select * from
			(	
					select
						housekeeping_equipment_damaged.id,
						housekeeping_equipment_damaged.product_id,
                        housekeeping_equipment_damaged.room_id,
                        housekeeping_equipment_damaged.type,
                        to_char(FROM_UNIXTIME(housekeeping_equipment_damaged.time),\'DD/MM/YYYY\') as time,
                        (housekeeping_equipment_damaged.quantity) as quantity,
                        product.name_'.Portal::language().' as product_name,
                        room.name as room_name,
                        housekeeping_equipment_damaged.note,
                        ROWNUM as rownumber
					from 
						housekeeping_equipment_damaged
						inner join product on product.id=housekeeping_equipment_damaged.product_id 
						left outer join room on room.id=housekeeping_equipment_damaged.room_id 
					where 
                        '.$cond.'
					order by 
                        housekeeping_equipment_damaged.product_id desc
			) 
            where rownumber > '.((page_no()-1)*$item_per_page).' and rownumber <= '.(page_no()*$item_per_page).'
		';
        //System::debug($sql);
		$items = DB::fetch_all($sql);
		$i=1;
		foreach ($items as $key=>$value)
		{
			$items[$key]['i']=$i++;
			$items[$key]['damaged_type']=$value['type']=='LOST'?Portal::language('lost'):Portal::language('damaged');
			$items[$key]['date']= str_replace('-','/',$value['time']);
		}
		DB::query('select
        			     id, room.name as name
        			from 
                        room
        			where 
                        portal_id=\''.PORTAL_ID.'\'
        			order 
                        by name
        		');
		$room_id_list = array(''=>'----')+String::get_list(DB::fetch_all()); 

		$just_edited_id['just_edited_ids'] = array();
		if (Url::get('selected_ids'))
		{
			if(is_string(Url::get('selected_ids')))
			{
				if (strstr(Url::get('selected_ids'),','))
				{
					$just_edited_id['just_edited_ids']=explode(',',Url::get('selected_ids'));
				}
				else
				{
					$just_edited_id['just_edited_ids']=array('0'=>Url::get('selected_ids'));
				}
			}
		}
		$this->parse_layout('list_damaged',$just_edited_id+
			array(
				'items'=>$items,
				'paging'=>$paging,
				'room_id_list'=>$room_id_list,
				'room_id'=>0, 
			)
		);
	}
}
?>