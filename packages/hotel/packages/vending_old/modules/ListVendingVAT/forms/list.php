<?php
class ListVendingVATForm extends Form
{
	function ListVendingVATForm()
	{
		Form::Form('ListVendingVATForm');
		$this->link_css('skins/default/restaurant.css');		
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');		
	}
	function on_submit()
    {
	}
	function draw()
	{
		$cond = '
				1>0 and ve_reservation.portal_id=\''.PORTAL_ID.'\''
				.(URL::get('customer_name')!=''?' and lower(ve_reservation.agent_name) LIKE \'%'.strtolower(addslashes(URL::get('customer_name'))).'%\'':'') 
				.(URL::get('from_arrival_time')!=''?(' and ve_reservation.time>='.Date_Time::to_time(URL::get('from_arrival_time'))):'') 
				.(URL::get('to_arrival_time')!=''?(' and ve_reservation.time<'.(Date_Time::to_time(URL::get('to_arrival_time'))+(3600*24))):'') 
				.(URL::get('bars')!=0?' and bar_id='.URL::get('bars').'':'')
				.(Url::get('total_from')!=''?' and total>='.intval(Url::get('total_from')):'')
				.(Url::get('total_to')!=''?' and total<='.intval(Url::get('total_to')):'')
				.(URL::get('invoice_number')!=''?' and ve_reservation.id = '.trim(URL::iget('invoice_number')).'':'')
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
                and ve_reservation.total >=200000
                ';
		DB::query('
			select count(*) as acount
			from 
				ve_reservation
			where '.$cond.'
		');
		$count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page,10,false,'page_no',array('cmd','invoice_number','from_arrival_time','to_arrival_time','status','bars'));
		$sql = '
			select * from(
				select 
					ve_reservation.id,
                    ve_reservation.code,  
					ve_reservation.agent_name as agent_name,
					ve_reservation.time,
					ve_reservation.total,
					ve_reservation.user_id,
                    --vat_bill.code as vat_bill_code,
					row_number() OVER (order by ABS(ve_reservation.time - '.time().')) AS rownumber
				from 
					ve_reservation
                    left join vat_bill on to_char(ve_reservation.id)= vat_bill.ve_reservation_id
				where 
                    '.$cond.'
                order by 
                    ve_reservation.id desc 
			)
			where
				rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		';
        //System::debug($sql);
		$items = DB::fetch_all($sql);
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