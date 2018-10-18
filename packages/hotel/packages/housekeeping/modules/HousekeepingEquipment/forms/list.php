<?php
class ListHousekeepingEquipmentForm extends Form
{
	function ListHousekeepingEquipmentForm()
	{
		Form::Form('ListHousekeepingEquipmentForm');
		$this->link_js('packages/core/includes/js/calendar.js');
	}
	function draw()
	{
        $this->map=array();
        
        //Start : Luu Nguyen GIap add portal
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list()); 
        
        if(Url::get('portal_id'))
        {
           $portal_id =  Url::get('portal_id');
        }
        else
        {
            $portal_id =PORTAL_ID;
        }
        if($portal_id!="ALL")
        {
            $cond ="  housekeeping_equipment.portal_id ='".$portal_id."' ";
            //$cound_r =" room.portal_id='".$portal_id."'";
        }
        else
        {
            $cond=" 1=1 ";
           // $cound_r=" 1=1 ";
        } 
        //End Luu Nguyen Giap add portal
		$cond .= 
				(Url::get('room_id')!=''?' and housekeeping_equipment.room_id=\''.Url::get('room_id').'\'':'')
				.(Url::get('product_id')!=''?' and UPPER(housekeeping_equipment.product_id)=\''.strtoupper(Url::get('product_id')).'\'':'')
		;
		$item_per_page = 100;
		DB::query('
			select count(*) as acount
			from 
				housekeeping_equipment
                inner join product on product.id = housekeeping_equipment.product_id
				left outer join room on room.id=housekeeping_equipment.room_id 
			where 
                '.$cond.' and room.close_room=1');
		$count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page);
        
        
        $sql = '
			select * from
			(
				select hs.* , hs.product_id || \',\'|| hs.room_id as id , ROWNUM as rownumber
                from
				(
					select
						housekeeping_equipment.room_id,
                        housekeeping_equipment.product_id,
                        max(housekeeping_equipment.time) as time,
                        sum(housekeeping_equipment.quantity) - sum(housekeeping_equipment.damaged_quantity) as quantity,
                        sum(housekeeping_equipment.damaged_quantity) as damaged_quantity,
                        product.name_'.Portal::language().' as product_name,
                        room.name as room_name
					from 
						housekeeping_equipment
						inner join product on product.id = housekeeping_equipment.product_id 
						left outer join room on room.id=housekeeping_equipment.room_id 
					where 
                        '.$cond.' and room.close_room=1
					group by						
						housekeeping_equipment.room_id,
                        housekeeping_equipment.product_id,
                        product.name_'.Portal::language().',
                        room.name
					order by 
						room.name,
                        housekeeping_equipment.product_id
				) hs
			)
			where rownumber > '.((page_no()-1)*$item_per_page).' and rownumber <= '.(page_no()*$item_per_page).'
		';
        
		$items = DB::fetch_all($sql);
		$i=1;
		foreach ($items as $key=>$value)
		{
			$items[$key]['i']=$i++;
			$items[$key]['date']=date('d/m/Y',$value['time']);
		}		
		
        //Lay het cac phong
        DB::query('select
        			id, room.name as name
        			from room 
        		  where
    				portal_id=\''.PORTAL_ID.'\'
                     and room.close_room=1
    			 order by room.name
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
		$this->parse_layout('list',$just_edited_id+
			array(
				'items'=>$items,
				'paging'=>$paging,
				'room_id_list'=>$room_id_list,
                'room_id'=>0, 
			) + $this->map
		);
	}
}
?>