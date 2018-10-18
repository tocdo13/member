<?php
class ListLessBarReservationNewForm extends Form
{
	function ListLessBarReservationNewForm()
	{
		Form::Form('ListLessBarReservationNewForm');
		$this->link_css('skins/default/restaurant.css');		
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		
	}
	function on_submit(){
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
        //Nếu in 1 nhóm tùy chọn	
        if(Url::get('print_group_vat'))
        {
            if(!Url::get('selected_ids'))
            {
                $this->error('miss_folio',Portal::language('you_must_choose_folio'),false);
            }
            else
            {
                
                $selected_ids = Url::get('selected_ids');
                $b_r_id ='';
                if(!empty($selected_ids))
                {
        			foreach( $selected_ids as $id)
        			{
                        $b_r_id.= $id.',';
        			}
                    Url::redirect('vat_bill',array('cmd'=>'entry_restaurant','b_r_id'=>trim($b_r_id,','),'department'=>'RESTAURANT','arrival_time'=>Url::get('from_arrival_time'),'departure_time'=>Url::get('from_arrival_time')));  
                }
            }    
        }
        else
        if(Url::get('print_all_vat'))//in tất cả trong ngày
        {
            $date = Url::get('from_arrival_time')?Url::get('from_arrival_time'):date('d/m/Y');
            $cond = '
                        1>0 and bar_reservation.portal_id=\''.PORTAL_ID.'\'
                        and bar_reservation.arrival_time>='.Date_Time::to_time( $date ).'
                        and bar_reservation.arrival_time<'.(Date_Time::to_time( $date )+(3600*24)).'
                    '
                    .(Url::get('bars')!=0?' and bar_id='.Url::get('bars').'':'')
                    .(Url::get('status')?' and bar_reservation.status=\''.Url::get('status').'\'':'')
                    ;
    		DB::query('
    				select 
    					bar_reservation.id
    				from 
    					bar_reservation
    					left outer join bar_reservation_product on bar_reservation_product.bar_reservation_id = bar_reservation.id
    					left outer join bar on bar_reservation.bar_id = bar.id
    				where 
                        '.$cond.'
                        and bar_reservation.status!=\'BOOKED\'
                        and bar_reservation.status!=\'CANCEL\'
                        and bar_reservation.total <200000
                    order by 
                        id desc
    		');
    		$items = DB::fetch_all();
            if(!empty($items))
            {
                $b_r_id ='';
    			foreach( $items as $key=>$value)
    			{
                    $b_r_id.= $value['id'].',';
    			}
                Url::redirect('vat_bill',array('cmd'=>'entry_restaurant','b_r_id'=>trim($b_r_id,','),'department'=>'RESTAURANT','arrival_time'=>$date,'departure_time'=>$date));  
            }   
        }
	}
	function draw()
	{
        $this->map = array();
        $this->map['date'] = Url::get('from_arrival_time')?Url::get('from_arrival_time'):date('d/m/Y');
        $_REQUEST['from_arrival_time'] = $this->map['date'];
        //.(Url::get('to_arrival_time')!=''?(' and bar_reservation.arrival_time<'.(Date_Time::to_time(Url::get('to_arrival_time'))+(3600*24))):'')
		$cond = '
				1>0 and bar_reservation.portal_id=\''.PORTAL_ID.'\''
				.(Url::get('agent_name')!=''?' and lower(bar_reservation.agent_name) LIKE \'%'.strtolower(addslashes(Url::get('agent_name'))).'%\'':'') 
				.(
                    Url::get('from_arrival_time')!=''?( 
                                                    ' and bar_reservation.arrival_time>='.Date_Time::to_time( $this->map['date'] )
                                                    . 'and bar_reservation.arrival_time<'.(Date_Time::to_time( $this->map['date'] )+(3600*24)) 
                                                    ):''
                 ) 
				.(Url::get('bars')!=0?' and bar_id='.Url::get('bars').'':'')
				.(Url::get('total_from')!=''?' and total>='.intval(Url::get('total_from')):'')
				.(Url::get('total_to')!=''?' and total<='.intval(Url::get('total_to')):'')
				.(Url::get('invoice_number')!=''?' and bar_reservation.id = '.trim(Url::iget('invoice_number')).'':'')
		;
		if(isset($_SESSION['bar_id'])){
			$_REQUEST['bars'] = $_SESSION['bar_id'];
		}
		$item_per_page = 100;
		$status = array(
			''=>Portal::language('All_status'),
			'CHECKIN'=>'CHECKIN',
			'CHECKOUT'=>'CHECKOUT'
			);
		if(Url::get('status'))
		{
			$cond.=' and bar_reservation.status=\''.Url::get('status').'\'';
		}
        else
        {
            $cond.=' and bar_reservation.status=\'CHECKOUT\'';
        }
        
        $cond .= '
                and bar_reservation.status!=\'BOOKED\'
                and bar_reservation.status!=\'CANCEL\'
                and bar_reservation.total <200000
                ';
		DB::query('
			select count(*) as acount
			from 
				bar_reservation
			where '.$cond.'
		');
        //System::debug($cond);
		$count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page,10,false,'page_no',array('cmd','invoice_number','from_arrival_time','to_arrival_time','status','bars'));
		DB::query('
			select * from(
				select 
					bar_reservation.id, 
					bar_reservation.code,
					bar_reservation.status, 
					bar_reservation.receiver_name as agent_name,
					bar_reservation.arrival_time,
					bar_reservation.departure_time,
					bar_reservation.prepaid,
					bar_reservation.time_in,
					bar_reservation.time_out,
					bar_reservation.cancel_time,					
					bar_reservation.num_table,
					bar_reservation.total,
					bar.name as bar_name,
					room.name as room_name,
					bar_reservation.user_id,
                    vat_bill.bar_reservation,
                    \'\' as vat_bill_code,
					row_number() OVER (order by ABS(bar_reservation.arrival_time - '.time().')) AS rownumber
				from 
					bar_reservation
                    left join vat_bill on bar_reservation.id = vat_bill.bar_reservation
					left outer join bar on bar_id = bar.id
					left outer join reservation_room on bar_reservation.reservation_room_id = reservation_room.id
					left outer join room on reservation_room.room_id = room.id
					left outer join traveller on reservation_room.traveller_id = traveller.id
				where 
                    '.$cond.' and vat_bill.code is null
                order by 
                    id desc 
			)
			where
				rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		');
		$items = DB::fetch_all();
		if($items)
		{
            //Lấy ra các id của bar_reservation
            $b_r_ids = array_keys($items);
            //Tạo cond
            $cond = '';
			for($i=0;$i<count($b_r_ids);$i++)
            {
				if($b_r_ids[$i] && $b_r_ids[$i]!='')
                {
					$cond .= ($i==0)?(' bar_reservation_id LIKE \''.$b_r_ids[$i].'%\''):(' OR bar_reservation_id LIKE \''.$b_r_ids[$i].'%\'');  	
				}
			}
            //Lấy ra các VAT của các bar_reservation_id trên
            $vat_bill = DB::fetch_all('select vat_bill.*  from vat_bill where ( '.$cond.' ) and portal_id = \''.PORTAL_ID.'\' and department = \'RESTAURANT\' ');
            //System::debug($cond);
            //System::debug($vat_bill);
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
						table_id as id, bar_table.name as name
					from 
						bar_reservation_table inner join bar_table on bar_table.id = bar_reservation_table.table_id 
					where 
						bar_reservation_table.bar_reservation_id = \''.$itm['id'].'\'
				');
				$ttbls = DB::fetch_all();
				$tbl_names = '';
				foreach($ttbls as $tky=>$tabl)
				{
					$tbl_names .= ', '.$tabl['name'];
				}
				$items[$k]['table_name'] = substr($tbl_names,1);
				$items[$k]['total'] = System::display_number($items[$k]['total']);
                
                //Gán VAT code vào từng item
                foreach($vat_bill as $key=>$value)
                {
                    //so sánh chuỗi để gán VAT code => có thể bị nhầm vì vat code có b_r_id = 12,11,10 thì bar_res_id = 1 vẫn nhận VAT code này
                    //=> phân tích chuỗi thành mảng, kiểm tra in_array
                    if( in_array( $itm['id'] , explode( ',' , $value['bar_reservation_id']  ) ) )
                    {
                        $items[$k]['vat_bill_code'] = $value['code'];
                        //break => lấy ra VAT code mới nhất vì 1 bar_res_id GROUP có thể đc in ở nhiều. ví dụ 6,7 và 6,7,8
                        break;
                    }
                }	
			}
		}
		//require_once 'packages/hotel/includes/php/hotel.php';
		require_once 'packages/hotel/packages/restaurant/includes/table.php';
		$cond_admin = Table::get_privilege_bar();
		$bars = DB::fetch_all('SELECT * FROM BAR where 1=1 '.$cond_admin.' ORDER BY ID ASC');
		$list_bars[0] ='----'.Portal::language('select_bar').'----';
		$list_bars = $list_bars + String::get_list($bars,'name');
		
        $this->parse_layout('list_less',array(
			'items'=>$items,
			'paging'=>$paging,
			'status_list'=>$status,
			'bars_list'=>$list_bars
		));
	}
}
?>