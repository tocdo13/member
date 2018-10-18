<?php
class ListTicketVATForm extends Form
{
	function ListTicketVATForm()
	{
		Form::Form('ListTicketVATForm');
		$this->link_css('skins/default/restaurant.css');		
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		
	}
	function on_submit()
    {
        /*
		if(Url::get('acction') == 1){	
			if(Url::get('bars')){
				Session::set('bar_id',Url::get('bars'));
				$_SESSION['bar_id'] = Url::get('bars');
			}
			$_REQUEST['bar_id'] = Session::get('bar_id');
			//Url::redirect_current(array('bar_id'=>Session::get('bar_id'),'status'));	
		}
        */	
	}
	function draw()
	{
		$cond = '
				1>0 and ticket_reservation.portal_id=\''.PORTAL_ID.'\''
				.(URL::get('customer_name')!=''?' and lower(ticket_reservation.customer_name) LIKE \'%'.strtolower(addslashes(URL::get('customer_name'))).'%\'':'') 
				.(URL::get('from_arrival_time')!=''?(' and ticket_reservation.time>='.Date_Time::to_time(URL::get('from_arrival_time'))):'') 
				.(URL::get('to_arrival_time')!=''?(' and ticket_reservation.time<'.(Date_Time::to_time(URL::get('to_arrival_time'))+(3600*24))):'') 
				.(URL::get('bars')!=0?' and bar_id='.URL::get('bars').'':'')
				.(Url::get('total_from')!=''?' and total>='.intval(Url::get('total_from')):'')
				.(Url::get('total_to')!=''?' and total<='.intval(Url::get('total_to')):'')
				.(URL::get('invoice_number')!=''?' and ticket_reservation.id = '.trim(URL::iget('invoice_number')).'':'')
		;
		if(isset($_SESSION['bar_id']))
        {
			$_REQUEST['bars'] = $_SESSION['bar_id'];
		}
		$item_per_page = 100;
		$status = array(
			''=>Portal::language('All_status'),
			'CHECKIN'=>'CHECKIN',
			'CHECKOUT'=>'CHECKOUT'
			);
        $cond .= '
                and ticket_reservation.total >=200000
                ';
		DB::query('
			select count(*) as acount
			from 
				ticket_reservation
			where '.$cond.'
		');
		$count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page,10,false,'page_no',array('cmd','invoice_number','from_arrival_time','to_arrival_time','status','bars'));
		DB::query('
			select * from(
				select 
					ticket_reservation.id, 
					ticket_reservation.customer_name as agent_name,
					ticket_reservation.time,
					ticket_reservation.total,
					room.name as room_name,
					ticket_reservation.user_id,
                    vat_bill.code as vat_bill_code,
					row_number() OVER (order by ABS(ticket_reservation.time - '.time().')) AS rownumber
				from 
					ticket_reservation
					left outer join reservation_room on ticket_reservation.reservation_room_id = reservation_room.id
					left outer join room on reservation_room.room_id = room.id
					left outer join traveller on reservation_room.traveller_id = traveller.id
                    left join vat_bill on ticket_reservation.id = vat_bill.ticket_reservation_id
				where 
                    '.$cond.'
                order by 
                    ticket_reservation.id desc 
			)
			where
				rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		');
		$items = DB::fetch_all();
        //System::debug($items);
		if($items)
		{
			foreach($items as $k=>$itm)
			{
			     $items[$k]['time'] = date('d/m/Y',$itm['time']);
                 $items[$k]['total'] = System::display_number($itm['total']);
			}
		}
		
        $this->parse_layout('list',array(
			'items'=>$items,
			'paging'=>$paging
		));
	}
}
?>