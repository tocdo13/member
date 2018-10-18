<?php
class ListBarReservationNewForm extends Form
{
	function ListBarReservationNewForm()
	{
		Form::Form('ListBarReservationNewForm');
		$this->link_css('packages/hotel/packages/vending/skins/default/css/restaurant.css');
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		$this->link_js('packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js');
		$this->link_css("packages/hotel/skins/default/css/jquery.windows-engine.css");
	}
    
	function draw()
	{
	   
        require_once 'packages/hotel/packages/vending/includes/php/vending.php';
        $area = get_area_vending();
        //System::debug($area);
        
		$cond = '
				1>0 and ve_reservation.portal_id=\''.PORTAL_ID.'\''
				.(Url::get('agent_name')!=''?' and lower(ve_reservation.agent_name) LIKE \'%'.strtolower(addslashes(URL::get('agent_name'))).'%\'':'') 
				.(Url::get('from_arrival_time')!=''?(' and ve_reservation.arrival_time>='.Date_Time::to_time(URL::get('from_arrival_time'))):'') 
				.(Url::get('to_arrival_time')!=''?(' and ve_reservation.arrival_time<'.(Date_Time::to_time(URL::get('to_arrival_time'))+(3600*24))):'') 
				.(Url::get('total_from')!=''?' and ve_reservation.total>='.intval(Url::get('total_from')):'')
				.(Url::get('total_to')!=''?' and ve_reservation.total<='.intval(Url::get('total_to')):'')
				.(Url::get('invoice_number')!=''?' and ve_reservation.id = '.trim(Url::iget('invoice_number')).'':'')
                .(Url::get('area_id')?' and ve_reservation.department_id = \''.Url::get('area_id').'\'':'')
				.(Url::get('product_code')?' and upper(ve_reservation_product.product_id) LIKE \'%'.strtoupper(Url::get('product_code')).'%\'':'')
		;
        
        $cond .=' and ve_reservation.department_id in (';    
        foreach($area as $k=>$v)
        {
            $cond.=$k.',';
        }
        $cond = trim($cond,',');
        $cond.= ')';  
        
		$item_per_page = 200;
        
		DB::query('
			select count(*) as acount
			from 
				ve_reservation
				left outer join ve_reservation_product on ve_reservation_product.bar_reservation_id = ve_reservation.id
			where '.$cond.'
		');
		$count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page,10,false,'page_no',array('page_no','product_code','invoice_number','from_arrival_time','to_arrival_time'));
		DB::query('
			select * from(
				select 
					ve_reservation.id, 
					ve_reservation.code,
					ve_reservation.status, 
                    DECODE(
                        ve_reservation.agent_name, \'\',ve_reservation.receiver_name,
                                                    ve_reservation.agent_name 
                    ) as agent_name,
					ve_reservation.arrival_time,
					ve_reservation.time_in,				
					ve_reservation.total,
                    ve_reservation.payment_status,
                    ve_reservation.deposit,
                    ve_reservation.total_paid,
					ve_reservation.user_id,
                    ve_reservation.department_id,
                    ve_reservation.department_code,
					row_number() OVER (order by ABS(ve_reservation.arrival_time - '.time().')) AS rownumber,
                    ve_reservation.lastest_edited_user_id,
                    ve_reservation.is_debit
				from 
					ve_reservation
					left outer join ve_reservation_product on ve_reservation_product.bar_reservation_id = ve_reservation.id
				where '.$cond.'
				'.(URL::get('order_by')?'order by '.URL::get('order_by').(URL::get('order_dir')?' '.URL::get('order_dir'):''):'order by ABS(ve_reservation.arrival_time - '.time().')').' 
			)
			where
				rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		');
		$items = DB::fetch_all();
		if($items)
		{
            $stt = 1;
			foreach($items as $k=>$itm)
			{
                $items[$k]['is_debit'] = $items[$k]['is_debit']?Portal::language('yes'):Portal::language('No');
                if($stt%2==0){
                    $items[$k]['row_class'] = 'row-even';
                }else{
                    $items[$k]['row_class'] = 'row-odd';
                }
                $stt++;
                
				$order_id = '';
				for($i=0;$i<6-strlen($itm['id']);$i++)
				{
					$order_id .= '0';
				}
				$order_id .= $itm['id'];
				$items[$k]['order_id'] = $order_id;
				
				$items[$k]['arrival_date'] = $itm['time_in']!=0?date('d/m/Y H:i',$itm['time_in']):date('d/m/Y H:i',$itm['arrival_time']);
				$time_in = $itm['time_in']!=0?$itm['time_in']:$itm['arrival_time'];
                $items[$k]['time_length'] = '';
				$items[$k]['total'] = System::display_number($items[$k]['total']);
				
			}
		}
        
        
        //System::debug($items);
		$this->parse_layout('list',array(
			'items'=>$items,
			'paging'=>$paging,
            'area_id_list'=>array(''=>Portal::language('All'))+String::get_list($area),
            'area'=>$area,
		));
	}
    
	function get_vend_area()
    {
		$bars = DB::fetch_all('
			SELECT
                department.id,
                department.code,
                department.name_'.Portal::language().' as name,
                portal_department.warehouse_id
			FROM
                department
                inner join portal_department on department.code = portal_department.department_code and portal_department.portal_id = \''.PORTAL_ID.'\'
			WHERE
				1=1  AND department.parent_id = (select id from department where code = \'VENDING\')');
	   return $bars;
	}
}
?>