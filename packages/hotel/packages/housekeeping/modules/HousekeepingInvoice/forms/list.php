<?php
class ListHousekeepingInvoiceForm extends Form
{
	function ListHousekeepingInvoiceForm()
	{
		Form::Form('ListHousekeepingInvoiceForm');
		$this->link_js('packages/core/includes/js/calendar.js');
	}
	function draw()
	{
		$rate = 1;//DB::fetch('select exchange from currency where name="VND"','exchange');
		$cond = '
				type<>\'LAUNDRY\' and type<>\'MINIBAR\' '
				.(URL::get('minibar_id')?'
					and housekeeping_invoice.minibar_id = \''.URL::get('minibar_id').'\'':'') 
				.(URL::get('user_id')?'
					and housekeeping_invoice.user_id = "'.URL::get('user_id').'"':'') 
				.(URL::get('time_start')?' and housekeeping_invoice.time>="'.Date_Time::to_time(URL::get('time_start')).'"':'')
				.(URL::get('time_end')?' and housekeeping_invoice.time<="'.Date_Time::to_time(URL::get('time_end')).'"':'') 
		;
		$item_per_page = 50;
		DB::query('
			select count(*) as acount
			from 
				housekeeping_invoice
				left outer join reservation_room on reservation_room.id=housekeeping_invoice.reservation_room_id 
				left outer join minibar on minibar.id=housekeeping_invoice.minibar_id 
				left outer join user on user.id=housekeeping_invoice.user_id 
			where '.$cond.'
		');
		$count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page);
		$sql = '
			select hs.* , party.name_1 as last_modifier_name from
			(
				SELECT 
					housekeeping_invoice.id
					,housekeeping_invoice.status
					,housekeeping_invoice.time ,
					housekeeping_invoice.total ,
					housekeeping_invoice.tax_rate ,
					housekeeping_invoice.prepaid
					,concat(concat(traveller.first_name,\' \'),traveller.last_name) as reservation_room_id 
					,party.name_'.Portal::language().' as user_name
					,user_id
					,last_modifier_id
					,minibar.name as minibar
					,ROWNUM as rownumber 
				FROM 
					housekeeping_invoice
					inner join minibar on minibar.id=housekeeping_invoice.minibar_id 
					inner join reservation_room on RESERVATION_ROOM.id=housekeeping_invoice.reservation_room_id and reservation_room.room_id=minibar.room_id
					left outer join traveller on traveller.id=reservation_room.traveller_id
					left outer join party on party.user_id = housekeeping_invoice.user_id
				WHERE '.$cond.'
				'.(URL::get('order_by')?'order by '.URL::get('order_by').(URL::get('order_dir')?' '.URL::get('order_dir'):''):'order by time desc').'
			) 	hs 
				left outer join party on party.user_id=hs.last_modifier_id  			
			where 
				rownumber > '.((page_no()-1)*$item_per_page).' and rownumber <= '.(page_no()*$item_per_page).'
		';
		$items = DB::fetch_all($sql);
		foreach($items as $key=>$item)
		{
			$items[$key]['date'] = date('d/m/Y',$item['time']);
			$items[$key]['total']=System::display_number($item['total']); 
			$items[$key]['prepaid']=System::display_number($item['prepaid']); 
			$items[$key]['remain']=System::display_number($item['total']-$item['prepaid']); 
		}
		DB::query('select
			id, name
			from minibar
			order by name
			'
		);
		$minibar_id_list = array(''=>'')+String::get_list(DB::fetch_all());
		$this->parse_layout('list',
			array(
				'items'=>$items,
				'paging'=>$paging,
				'minibar_id_list' => $minibar_id_list, 
				'minibar_id' => URL::get('minibar_id',''), 
				'type_list' => array(''=>'','LAUNDRY'=>'LAUNDRY','MINIBAR'=>'MINIBAR','SERVICE'=>'SERVICE'),
				'type' => URL::get('type','')
			)
		);
	}
}
?>