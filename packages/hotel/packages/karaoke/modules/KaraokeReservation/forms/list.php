<?php
class ListKaraokeReservationNewForm extends Form
{
	function ListKaraokeReservationNewForm()
	{
		Form::Form('ListKaraokeReservationNewForm');
		$this->link_css('skins/default/karaoke.css');		
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		
	}
	function on_submit(){
		if(Url::get('acction') == 1){	
			if(isset($_REQUEST['karaokes'])){
				Session::set('karaoke_id',$_REQUEST['karaokes']);
				$_SESSION['karaoke_id'] = $_REQUEST['karaokes'];
			}
            //System::debug($_REQUEST);
            //System::debug($_SESSION);
			$_REQUEST['karaoke_id'] = Session::get('karaoke_id');
			//Url::redirect_current(array('karaoke_id'=>Session::get('karaoke_id'),'status'));	
		}	
	}
	function draw()
	{
		$cond = '
				1>0 and karaoke_reservation.portal_id=\''.PORTAL_ID.'\''
				.(URL::get('agent_name')!=''?' and lower(karaoke_reservation.agent_name) LIKE \'%'.strtolower(addslashes(URL::get('agent_name'))).'%\'':'') 
				.(URL::get('from_arrival_time')!=''?(' and karaoke_reservation.arrival_time>='.Date_Time::to_time(URL::get('from_arrival_time'))):'') 
				.(URL::get('to_arrival_time')!=''?(' and karaoke_reservation.arrival_time<'.(Date_Time::to_time(URL::get('to_arrival_time'))+(3600*24))):'') 
				//.(URL::get('karaoke_id')?' and karaoke_reservation.karaoke_id=\''.URL::get('karaoke_id').'\'':(Session::get('karaoke_id')?' and karaoke_reservation.karaoke_id=\''.Session::get('karaoke_id').'\'':''))
				.(Url::get('karaoke_id')?' and karaoke_reservation.karaoke_id=\''.Url::get('karaoke_id').'\'':'')
				.(Url::get('total_from')!=''?' and karaoke_reservation.total>='.intval(Url::get('total_from')):'')
				.(Url::get('total_to')!=''?' and karaoke_reservation.total<='.intval(Url::get('total_to')):'')
				.(URL::get('invoice_number')!=''?' and karaoke_reservation.id = '.trim(URL::iget('invoice_number')).'':'')
				.(Url::get('product_code')?' and karaoke_reservation_product.product_id=\''.Url::get('product_code').'\'':'')
		;
		if(isset($_SESSION['karaoke_id'])){
			$_REQUEST['karaokes'] = $_SESSION['karaoke_id'];
		}
		if(Url::get('cmd')=='list_debt')
		{
			$cond.= ' and karaoke_reservation.status=\'CHECKOUT\' and payment_result=\'DEBT\'';	
		}
		elseif(Url::get('cmd')=='list_free')
		{
			$cond.= ' and karaoke_reservation.status=\'CHECKOUT\' and payment_result=\'FREE\'';	
		}
		elseif(Url::get('cmd')=='list_cancel')
		{
			$cond.= ' and karaoke_reservation.status=\'CANCEL\'';	
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
			$cond.=' and karaoke_reservation.status=\''.URL::get('status').'\'';
		}
		DB::query('
			select count(*) as acount
			from 
				karaoke_reservation
				left outer join karaoke_reservation_product on karaoke_reservation_product.karaoke_reservation_id = karaoke_reservation.id
			where '.$cond.'
		');
		$count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page);
		DB::query('
			select * from(
				select 
					karaoke_reservation.id, 
					karaoke_reservation.code,
					karaoke_reservation.status, 
					karaoke_reservation.agent_name,
					karaoke_reservation.arrival_time,
					karaoke_reservation.departure_time,
					karaoke_reservation.prepaid,
					karaoke_reservation.time_in,
					karaoke_reservation.time_out,
					karaoke_reservation.cancel_time,					
					karaoke_reservation.num_table,
					karaoke_reservation.total,
					karaoke.name as karaoke_name,
					room.name as room_name,
					karaoke_reservation.user_id,
					karaoke_reservation.karaoke_id, 
					row_number() OVER (order by ABS(karaoke_reservation.arrival_time - '.time().')) AS rownumber
				from 
					karaoke_reservation
					left outer join karaoke_reservation_product on karaoke_reservation_product.karaoke_reservation_id = karaoke_reservation.id
					left outer join karaoke on karaoke_reservation.karaoke_id = karaoke.id
					left outer join reservation_room on karaoke_reservation.reservation_room_id = reservation_room.id
					left outer join room on reservation_room.room_id = room.id
					left outer join traveller on reservation_room.traveller_id = traveller.id
				where '.$cond.'
				'.(URL::get('order_by')?'order by '.URL::get('order_by').(URL::get('order_dir')?' '.URL::get('order_dir'):''):'order by ABS(karaoke_reservation.arrival_time - '.time().')').' 
			)
			where
				rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		');
		$items = DB::fetch_all();
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
						table_id as id, karaoke_table.name as name
					from 
						karaoke_reservation_table inner join karaoke_table on karaoke_table.id = karaoke_reservation_table.table_id 
					where 
						karaoke_reservation_table.karaoke_reservation_id = \''.$itm['id'].'\'
				');
				$ttbls = DB::fetch_all();
				$tbl_names = '';
				foreach($ttbls as $tky=>$tabl)
				{
					$tbl_names .= ', '.$tabl['name'];
				}
				$items[$k]['table_name'] = substr($tbl_names,1);
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
					$items[$k]['cancel_date'] = date('H:i d/m/Y',$itm['cancel_time']);	
				}
				$items[$k]['total'] = System::display_number($items[$k]['total']);
				
			}
		}
		require_once 'packages/hotel/includes/php/hotel.php';
		require_once 'packages/hotel/packages/karaoke/includes/table.php';
		$cond_admin = Table::get_privilege_karaoke();
		$karaokes = DB::fetch_all('SELECT * FROM karaoke where 1=1 '.$cond_admin.' ORDER BY ID ASC');
		$list_karaokes[0] = '----Select_Karaoke----';
		$list_karaokes = $list_karaokes + String::get_list($karaokes,'name');
        $this->map['karaoke_name'] = DB::fetch('Select * from karaoke where id = '.( Session::get('karaoke_id')!='' ? Session::get('karaoke_id') : '0' ).' ','name');
		$this->map['karaoke_name'] = $this->map['karaoke_name']?$this->map['karaoke_name']:'';
        if(Url::get('cmd')=='list_debt')
		{
			$this->parse_layout('list_debt',$this->map+array(
				'items'=>$items,
				'paging'=>$paging,
				'karaokes_list'=>$list_karaokes
			));
		}
		else
		if(Url::get('cmd')=='list_free')
		{
			$this->parse_layout('list_free',$this->map+array(
				'items'=>$items,
				'paging'=>$paging,
				'karaokes_list'=>$list_karaokes
			));
		}
		else
		if(Url::get('cmd')=='list_cancel')
		{
			$this->parse_layout('list_cancel',$this->map+array(
				'items'=>$items,
				'paging'=>$paging,
				'karaokes_list'=>$list_karaokes
			));
		}		
		else
		{
			$this->parse_layout('list',$this->map+array(
				'items'=>$items,
				'paging'=>$paging,
				'status_list'=>$status,
				'karaokes_list'=>$list_karaokes
			));
		}
	}
}
?>