<?php
class ListBanquetOrderForm extends Form
{
	function ListBanquetOrderForm()
	{
		Form::Form('ListBanquetOrderForm');
		$this->link_css('skins/default/restaurant.css');		
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
        
        $this->link_js('packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js');
        $this->link_js('packages/hotel/packages/reception/modules/includes/common01.js');
		$this->link_css("packages/hotel/skins/default/css/jquery.windows-engine.css");	
		
	}
	function on_submit(){
		if(Url::get('selected_ids'))
		{
			$deleted_ids = Url::get('selected_ids');
			if(Url::get('cmd')=='delete_selected' and User::can_delete(false,ANY_CATEGORY))
			{
				require_once 'delete.php';
				foreach($deleted_ids as $value)
				{
					DeleteBanquetOrderForm::delete($value);
				}
			}
		}
	}
	function draw()
	{
		$cond = '
				1>0 '
				.(URL::get('agent_name')!=''?' and lower(party_reservation.agent_name) LIKE \'%'.strtolower(addslashes(URL::get('agent_name'))).'%\'':'') 
				.(URL::get('from_arrival_time')!=''?(' and party_reservation.checkin_time>='.Date_Time::to_time(URL::get('from_arrival_time'))):'') 
				.(URL::get('to_arrival_time')!=''?(' and party_reservation.checkin_time<'.(Date_Time::to_time(URL::get('to_arrival_time'))+(3600*24))):'') 
				//.(URL::get('bar_id')!=''?' and bar_id=\''.URL::get('bar_id').'\'':'')
				.(Url::get('total_from')!=''?' and total>='.intval(Url::get('total_from')):'')
				.(Url::get('total_to')!=''?' and total<='.intval(Url::get('total_to')):'')
				.(Url::get('full_name')?' and FN_CONVERT_TO_VN(UPPER(party_reservation.full_name)) LIKE UPPER(\'%'.Url::get('full_name').'%\')':'')
		;
		$item_per_page = 200;
        /*
		$status = array(
			''=>Portal::language('All_status'),
			'AWAITING'=>Portal::language('awaiting'),
			'ACCEPTED'=>Portal::language('accepted'),
			'CANCEL'=>Portal::language('cancel')
		);
        */
		$status =  array(
            ''=>Portal::language('All_status'),
			'BOOKED'=>Portal::language('booked'),
			'CHECKIN'=>Portal::language('checkin'),
			'CHECKOUT'=>Portal::language('checkout'),
			'CANCEL'=>Portal::language('cancel')
			);

		if(Url::get('status'))
		{
			$cond.=' and party_reservation.status=\''.URL::get('status').'\'';
		}
		DB::query('
			select count(*) as acount
			from 
				party_reservation
				left outer join party_reservation_detail on party_reservation_detail.party_reservation_id = party_reservation.id
			where '.$cond.'
		');
		$count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page, $numpageshow=10,$smart=false,$page_name='page_no',$params=array('from_arrival_time','to_arrival_time','status','product_code','agent_name'));
		DB::query('select * from(
				select DISTINCT
					party_reservation.id, 
					party_reservation.status, 
					party_reservation.full_name,
					party_reservation.checkin_time,
					party_reservation.checkout_time,
					party_reservation.num_table,
                    party_reservation.deposit_1,
                    party_reservation.deposit_2,
                    party_reservation.deposit_3,
                    party_reservation.deposit_4,
					party_reservation.num_people,
                    party_reservation.meeting_num_people,					
					party_reservation.total as total,
					party_reservation.user_id,
                    party_reservation.contact_url,
					party_type.name as party_name,
                    party_type.id as party_id,
					row_number() over (order by  party_reservation.id desc,party_reservation.status) as rownumber
				from 
					party_reservation
					left outer join party_reservation_detail on party_reservation_detail.party_reservation_id = party_reservation.id
					left outer join party_type on party_type.id = party_reservation.party_type
				where '.$cond.' order by party_reservation.status,party_reservation.checkin_time desc
			)
			where
				rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		');
		$items = DB::fetch_all();
		if($items)
		{
			foreach($items as $k=>$itm)
			{
				$items[$k]['arrival_date'] = date('H:i d/m/Y',$itm['checkin_time']);
				$items[$k]['total'] = System::display_number($items[$k]['total']);
			}
		}
		require_once 'packages/hotel/includes/php/hotel.php';
		$this->parse_layout('list',array(
			'items'=>$items,
			'paging'=>$paging,
			'status_list'=>$status,
		));
	}
}
?>