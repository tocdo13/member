<?php
class ListForgotObjectForm extends Form
{
	function ListForgotObjectForm()
	{
		Form::Form('ListForgotObjectForm');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
	}
	function draw()
	{
        //Start Luu Nguyen Giap add portal
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
            $cond ="  forgot_object.portal_id ='".$portal_id."' ";
        }
        else
        {
            $cond=" 1=1 ";
        } 
        //End Luu Nguyen Giap add portal
        
		$cond .=(URL::get('room_id')?'and forgot_object.room_id = '.URL::get('room_id').'':'') 
				.(URL::get('employee_id')?' and employee_name LIKE \'%'.URL::get('employee_id').'%\'':'') 
				.(URL::get('name')?' and forgot_object.name LIKE \'%'.URL::get('name').'%\'':'') 
				.(URL::get('object_type')?' and forgot_object.object_type LIKE \'%'.URL::get('object_type').'%\'':'') 
				.(URL::get('customer_name')?' and concat(concat(traveller.first_name,\' \'),traveller.last_name) LIKE \'%'.URL::get('customer_name').'%\'':'') 
				.(URL::get('time_start')?' and forgot_object.time>='.Date_Time::to_time(URL::get('time_start')).'':'')
				.(URL::get('time_end')?' and forgot_object.time<='.(Date_Time::to_time(URL::get('time_end'))+24*3600).'':'') 
				.(URL::get('status')!=''?' and forgot_object.status = '.URL::get('status').'':'');
		$item_per_page = 50;
		DB::query('
			select count(*) as acount
			from 
				forgot_object
				left outer join reservation_room on reservation_room.id=forgot_object.reservation_room_id and reservation_room.room_id =forgot_object.room_id
				left outer join traveller on reservation_room.traveller_id=traveller.id
				left outer join room on room.id=forgot_object.room_id 
			where '.$cond.'
		');
		$count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page);
		$sql = '
			select * from
			( 
				SELECT temp.*,ROWNUM as rownumber FROM
				(	select 
						forgot_object.id,forgot_object.time,
						forgot_object.name ,
						forgot_object.object_type,
						forgot_object.quantity,
						forgot_object.unit ,
						traveller.last_name ,
						traveller.first_name ,
						country.name_'.Portal::language().' as country,
						forgot_object.status 
						,room.name as room_name,
                        forgot_object.object_code,
                        forgot_object.reason,
                        forgot_object.position,
                        forgot_object.guest_name,
                        forgot_object.company_name
					from 
						forgot_object
						left outer join reservation_room on reservation_room.id=forgot_object.reservation_room_id
						left outer join traveller on reservation_room.traveller_id=traveller.id
						left outer join room on room.id=forgot_object.room_id 
						left outer join country on country.id=traveller.nationality_id 
					where '.$cond.'
					'.(URL::get('order_by')?'order by '.URL::get('order_by').(URL::get('order_dir')?' '.URL::get('order_dir'):''):'order by time desc').'
				) temp
			)
			where rownumber > '.((page_no()-1)*$item_per_page).' and rownumber <= '.(page_no()*$item_per_page).'
		';
		if($items = DB::fetch_all($sql))
		{
			foreach ($items as $key=>$value)
			{
				$hour = date('G', $value['time']);
				$mins = date('i', $value['time']);
				$date = date('d/m/Y',$value['time']);
				$items[$key]['time']=$hour.':'.$mins.'\'  '.$date;
			}
		}
		DB::query('
			select
				id,name
			from
				room
			where
				room.portal_id=\''.PORTAL_ID.'\'
			order by
				name
			');
		$rooms = DB::fetch_all();
		DB::query('select
			id, country.name_'.Portal::language().' as name
			from country
			order by name
			'
		);
		$country_id_list = array(''=>'')+String::get_list(DB::fetch_all()); 
		DB::query('select
			distinct
			object_type as id,object_type as name
			from forgot_object
			where forgot_object.portal_id=\''.PORTAL_ID.'\'
			order by name
			'
		);
		$object_type_list = array(''=>'')+String::get_list(DB::fetch_all());
        //System::debug($items);
        $this->map = array();
        //Start : Luu Nguyen GIap add portal
        $this->map['portal_id_list'] = array('ALL'=>Portal::language('all'))+String::get_list(Portal::get_portal_list()); 
        //End   :Luu Nguyen GIap add portal 
        
		$this->parse_layout('list', $this->map+
			array(
				'items'=>$items,
				'paging'=>$paging,
				'rooms'=>$rooms,  
				'country_id_list'=>$country_id_list,
				'country_id'=>URL::get('country_id'),
				'object_type_list'=>$object_type_list,
				'object_type'=>URL::get('object_type')
			)
		);
	}
}
?>