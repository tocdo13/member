<?php
class ListBarReservationNewForm extends Form
{
	function ListBarReservationNewForm()
	{
		Form::Form('ListBarReservationNewForm');
		$this->link_css('skins/default/restaurant.css');		
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		
	}
	function on_submit(){
		if(Url::get('acction') == 1){	
			if(Url::get('bars')){
				Session::set('bar_id',Url::get('bars'));
				$_SESSION['bar_id'] = Url::get('bars');
			}
			$_REQUEST['bar_id'] = Session::get('bar_id');
			//Url::redirect_current(array('bar_id'=>Session::get('bar_id'),'status'));	
		}	
	}
	function draw()
	{
        require_once 'packages/core/includes/utils/vn_code.php';
        if(isset($_SESSION['errors']))
        {
            $this->error('','Hóa đơn đã được tạo bill or folio không thể xóa!');
            unset($_SESSION['errors']);                    
        }
        $cond = ' 1=1';
        $cond .= Url::get('invoice_number')?' AND (
                                                        (LOWER(FN_CONVERT_TO_VN(concat(concat(bar_reservation.code,\' \'),bar_reservation.code))) LIKE \'%'.convert_utf8_to_latin(mb_strtolower(Url::get('invoice_number'),'utf-8')).'%\')
                                                        OR
                                                        (LOWER(FN_CONVERT_TO_VN(bar_reservation.code)) LIKE \'%'.convert_utf8_to_latin(mb_strtolower(Url::get('invoice_number'),'utf-8')).'%\')
                                                        OR
                                                        (LOWER(FN_CONVERT_TO_VN(bar_reservation.code)) LIKE \'%'.convert_utf8_to_latin(mb_strtolower(Url::get('invoice_number'),'utf-8')).'%\')
                                                        )':'';
		$cond .= '
				AND bar_reservation.portal_id=\''.PORTAL_ID.'\''
				.(URL::get('agent_name')!=''?' and lower(bar_reservation.agent_name) LIKE \'%'.strtolower(addslashes(URL::get('agent_name'))).'%\'':'') 
				.(URL::get('from_arrival_time')!=''?(' and bar_reservation.arrival_time>='.Date_Time::to_time(URL::get('from_arrival_time'))):'') 
				.(URL::get('to_arrival_time')!=''?(' and bar_reservation.arrival_time<'.(Date_Time::to_time(URL::get('to_arrival_time'))+(3600*24))):'') 
				.((URL::get('bar_id') && URL::get('bar_id')!='ALL')?' and bar_reservation.bar_id=\''.URL::get('bar_id').'\'':'')
				.(Url::get('total_from')!=''?' and bar_reservation.total>='.intval(Url::get('total_from')):'')
				.(Url::get('total_to')!=''?' and bar_reservation.total<='.intval(Url::get('total_to')):'')
				
				.(Url::get('product_code')?' and bar_reservation_product.product_id=\''.Url::get('product_code').'\'':'')
		;
		if(Url::get('cmd')=='list_debt')
		{
			$cond.= ' and bar_reservation.status=\'CHECKOUT\' and payment_result=\'DEBT\'';	
		}
		elseif(Url::get('cmd')=='list_free')
		{
			$cond.= ' and bar_reservation.status=\'CHECKOUT\' and payment_result=\'FREE\'';	
		}
		elseif(Url::get('cmd')=='list_cancel')
		{
			$cond.= ' and bar_reservation.status=\'CANCEL\'';	
		}
		$item_per_page = 200;
		$status = array(
			''=>Portal::language('All_status'),
			'CHECKIN'=>'CHECKIN',
			'CHECKOUT'=>'CHECKOUT',
			'BOOKED'=>'BOOKED'
			);
		if(Url::get('status'))
		{
			$cond.=' and bar_reservation.status=\''.URL::get('status').'\'';
		}
		DB::query('
			select count(*) as acount
			from 
				bar_reservation
				left outer join bar_reservation_product on bar_reservation_product.bar_reservation_id = bar_reservation.id
			where '.$cond.'
		');
		$count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page);
		$sql=('
			select * from(
				select 
					bar_reservation.id, 
					bar_reservation.code,
					bar_reservation.status, 
					bar_reservation.agent_name as agent_name,
                    bar_reservation.receiver_name as receiver_name,
                    --KimTan: xu ly neu ban tra ve phong thi show ra ten khach va ten nguon khach con khong thi lay ten trong dat ban
                    case
                    when bar_reservation.pay_with_room = 1
                    then CONCAT(concat(concat(traveller.first_name,\' \'),traveller.last_name),concat(\'</br>\',customer.name)) 
                    else CONCAT(concat(bar_reservation.receiver_name,\'</br>\'),bar_reservation.agent_name) 
                    end as name,
                    -- end KimTan
					bar_reservation.arrival_time,
					bar_reservation.departure_time,
					bar_reservation.prepaid,
					bar_reservation.time_in,
                    bar_reservation.package_id,
					bar_reservation.time_out,
					bar_reservation.cancel_time,					
					bar_reservation.num_table,
					bar_reservation.total,
					bar.name as bar_name,
					room.name as room_name,
					bar_reservation.user_id,
                    reservation_room.customer_name,
                    bar_reservation.pay_with_room,
					bar_reservation.bar_id, 
                    bar_reservation.mice_reservation_id,
					row_number() OVER (order by ABS(bar_reservation.arrival_time - '.time().')) AS rownumber
				from 
					bar_reservation
					left outer join bar_reservation_product on bar_reservation_product.bar_reservation_id = bar_reservation.id
					left outer join bar on bar_reservation.bar_id = bar.id
					left outer join reservation_room on bar_reservation.reservation_room_id = reservation_room.id
					left outer join room on reservation_room.room_id = room.id
					left outer join reservation_traveller on bar_reservation.reservation_traveller_id = reservation_traveller.id
                    left outer join reservation on reservation_room.reservation_id = reservation.id
                    left outer join customer on reservation.customer_id = customer.id
                    left outer join traveller on reservation_traveller.traveller_id = traveller.id
				where '.$cond.'
				'.(URL::get('order_by')?'order by '.URL::get('order_by').(URL::get('order_dir')?' '.URL::get('order_dir'):''):'order by ABS(bar_reservation.arrival_time - '.time().')').' 
			)
			where
				rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		');
		$items = DB::fetch_all($sql);
        //System::debug($sql);
		if($items)
		{
			foreach($items as $k=>$itm)
			{
				$order_id = '';
				for($i=0;$i<6-strlen($itm['id']);$i++)
				{
					$order_id .= '0';
				}
				$order_id .= $itm['id'];
				$items[$k]['order_id'] = $order_id;
				
				$items[$k]['arrival_date'] = $itm['time_in']!=0?date('d/m/Y H:i',$itm['time_in']):date('d/m/Y H:i',$itm['arrival_time']);
				$time_in = $itm['time_in']!=0?$itm['time_in']:$itm['arrival_time'];
				$time_out = $itm['time_out']!=0?$itm['time_out']:$itm['departure_time'];
				if($itm['departure_time']>$itm['arrival_time'])
				{
					$items[$k]['time_length'] = date('H\h i',strtotime('1/1/2000')+$time_out-$time_in);
				}
				else
				{
					$items[$k]['time_length'] = '';
				}
				DB::query('
					select 
						table_id as id, bar_table.name as name, bar_table.bar_area_id, bar_area.print_automatic_bill
					from 
						bar_reservation_table 
                        inner join bar_table on bar_table.id = bar_reservation_table.table_id
                        inner join bar_area on bar_table.bar_area_id=bar_area.id
					where 
						bar_reservation_table.bar_reservation_id = \''.$itm['id'].'\'
				');
				$ttbls = DB::fetch_all();
				$tbl_names = '';
                $tbl_ids = '';
                $tbl_area_ids = '';
				foreach($ttbls as $tky=>$tabl)
				{
					$tbl_names .= ', '.$tabl['name'];
                    if($tbl_ids=='' AND $tbl_area_ids=='')
                    {
                        if($tabl['print_automatic_bill']!='')
                        {
                            $tbl_ids = $tabl['id'];
                            $tbl_area_ids = $tabl['bar_area_id'];
                        }
                    }
				}
                if($tbl_ids=='' AND $tbl_area_ids=='')
                {
                    foreach($ttbls as $tky1=>$tabl1)
                    {
                        $tbl_ids = $tabl1['id'];
                        $tbl_area_ids = $tabl1['bar_area_id']; 
                    }
                }
				$items[$k]['table_name'] = substr($tbl_names,1);
                $items[$k]['table_id'] = $tbl_ids;
                $items[$k]['bar_area_id'] = $tbl_area_ids;
				//--- Debt --
				if(Url::get('cmd')=='list_debt')
				{
					$items[$k]['debt_total'] = System::display_number($itm['total']-$itm['prepaid']);	
				}
				elseif(Url::get('cmd')=='list_free')
				{
					$items[$k]['free_total'] = System::display_number_report($itm['total']-$itm['prepaid']);
				}
				elseif(Url::get('cmd')=='list_cancel')
				{
					$items[$k]['cancel_date'] = date('d/m/Y H:i',$itm['cancel_time']);	
				}
				$items[$k]['total'] = System::display_number($items[$k]['total']);
				
			}
		}
        //System::debug($items);
		require_once 'packages/hotel/includes/php/hotel.php';
		require_once 'packages/hotel/packages/restaurant/includes/table.php';
		$cond_admin = Table::get_privilege_bar();
		$bars = DB::fetch_all('SELECT * FROM BAR where 1=1 '.$cond_admin.' ORDER BY ID ASC');
		$list_bars['ALL'] = '----Select_Bar----';
		$list_bars = $list_bars + String::get_list($bars,'name');
        $this->map['bar_name'] = (Session::get('bar_id') && Session::get('bar_id')!='ALL')? DB::fetch('Select * from bar where id ='.Session::get('bar_id'),'name'): '';
        if(Url::get('cmd')=='list_debt')
		{
			$this->parse_layout('list_debt',$this->map+array(
				'items'=>$items,
				'paging'=>$paging,
				'bars_list'=>$list_bars
			));
		}
		else
		if(Url::get('cmd')=='list_free')
		{
			$this->parse_layout('list_free',$this->map+array(
				'items'=>$items,
				'paging'=>$paging,
				'bars_list'=>$list_bars
			));
		}
		else
		if(Url::get('cmd')=='list_cancel')
		{
		  //System::debug($items);
			$this->parse_layout('list_cancel',$this->map+array(
				'items'=>$items,
				'paging'=>$paging,
				'bars_list'=>$list_bars
			));
		}		
		else
		{
			$this->parse_layout('list',$this->map+array(
				'items'=>$items,
				'paging'=>$paging,
				'status_list'=>$status,
				'bars_list'=>$list_bars
			));
		}
	}
}
?>
