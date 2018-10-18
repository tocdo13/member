<?php
class ListBanquetReservationForm extends Form
{
	function ListBanquetReservationForm()
	{
		Form::Form('ListBanquetReservationForm');
		$this->link_css('skins/default/restaurant.css');		
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		
        $this->link_js('packages/hotel/packages/reception/modules/includes/jquery.windows-engine.js');
        $this->link_js('packages/hotel/packages/reception/modules/includes/common01.js');
		$this->link_css("packages/hotel/skins/default/css/jquery.windows-engine.css");
	}
	function on_submit(){
	}
	function draw()
	{
		$cond = '
				1>0 '
				.(URL::get('full_name')!=''?' and lower(party_reservation.full_name) LIKE \'%'.strtolower(addslashes(URL::get('full_name'))).'%\'':'') 
				.(URL::get('from_arrival_time')!=''?(' and party_reservation.checkin_time>='.Date_Time::to_time(URL::get('from_arrival_time'))):'') 
				.(URL::get('to_arrival_time')!=''?(' and party_reservation.checkin_time<'.(Date_Time::to_time(URL::get('to_arrival_time'))+(3600*24))):'') 
				.(Url::get('total_from')!=''?' and total>='.intval(Url::get('total_from')):'')
				.(Url::get('total_to')!=''?' and total<='.intval(Url::get('total_to')):'')
                .((URL::get('party_name'))?' AND party_reservation.party_type = '.Url::get('party_name').'':'').''
		;
        if(Url::get('cmd')=='list_debt')
		{
            //giap.ln lay ra danh sach ban no : hinh thuc thanh toan = debit
			//$cond.= ' and party_reservation.status=\'CHECKOUT\'';	
            $cond .=" and exists(SELECT * FROM payment WHERE bill_id=party_reservation.id and type='BANQUET' and payment_type_id='DEBIT') ";
		}
		elseif(Url::get('cmd')=='list_free')
		{
            //giap.ln lay ra danh sach ban mien phi : hinh thuc thanh toan = foc
			//$cond.= ' and party_reservation.status=\'CHECKOUT\'';	
            $cond .=" and exists(SELECT * FROM payment WHERE bill_id=party_reservation.id and type='BANQUET' and payment_type_id='FOC')";
		}
		elseif(Url::get('cmd')=='list_cancel')
		{
			$cond.= ' and party_reservation.status=\'CANCEL\'';	
		}
		$item_per_page = 200;
		$status = array(
			''=>Portal::language('All_status'),
			'CHECKIN'=>'CHECKIN',
			'CHECKOUT'=>'CHECKOUT',
			'BOOKED'=>'BOOKED',
            'CANCEL'=>Portal::language('cancel'),
            'CANCEL_HAVE_CHARGE'=>Portal::language('cancel_have_charge'),
            'CANCEL_NOT_CHARGE'=>Portal::language('cancel_not_charge')
			);

		if(Url::get('status'))
		{
			if(Url::get('status')!='CANCEL_HAVE_CHARGE' and Url::get('status')!='CANCEL_NOT_CHARGE')
            {
                $cond.=' and party_reservation.status=\''.URL::get('status').'\'';
            }
            else
            {
                $cond.=' and party_reservation.status=\'CANCEL\'';
                if(Url::get('status')=='CANCEL_HAVE_CHARGE')
                $cond.=' and (party_reservation.cancel_charge_percent is not null or party_reservation.cancel_charge_amount is not null)';
                else
                $cond.=' and (party_reservation.cancel_charge_percent is null and party_reservation.cancel_charge_amount is null)';
            }
		}
 	    if(isset($_REQUEST['party_room'])&&$_REQUEST['party_room']!='0'){
 	      $cond.=' and party_room.id='.$_REQUEST['party_room'].'';
          $_REQUEST['party_room_select']=$_REQUEST['party_room'];
 	    }
		DB::query('
			select count(*) as acount
			from 
				party_reservation
                left join party_reservation_room on party_reservation_room.party_reservation_id=party_reservation.id
                left join party_room on party_reservation_room.party_room_id=party_room.id 
			where '.$cond.'
		');
		$count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page, $numpageshow=10,$smart=false,$page_name='page_no',$params=array('from_arrival_time','to_arrival_time','status','product_code','agent_name'));
		$this->map['party_name'] = Url::get('party_name')?DB::fetch('SELECT ID,NAME FROM party_type WHERE ID = '.Url::iget('party_name').'','name'):'';
       // System::debug($this->map['party_name']);
        $this->map['party_name_list'] = String::get_list(DB::select_all('party_type','1=1','name'));
       //System::debug($cond);exit();
        DB::query('select * from(
				select DISTINCT
					party_reservation.id,
                    party_room.name as room_name,
                    party_room.group_name, 
					party_reservation.status, 
                    party_reservation.note,
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
                    party_reservation.cancel_time,
                    party_reservation.cancel_charge_amount,
                    party_reservation.cancel_charge_percent,
                    party_reservation.mice_reservation_id,
                    party_reservation.advance_payment,
					party_type.name as party_name,
                    party_type.id as party_id,
                    party_reservation.night_audit_date,
					row_number() over (order by  party_reservation.id desc,party_reservation.status) as rownumber
				from 
					party_reservation_room 
                    left join party_reservation on party_reservation_room.party_reservation_id=party_reservation.id
                    left join party_room on party_reservation_room.party_room_id=party_room.id                                        
					left outer join party_reservation_detail on party_reservation_detail.party_reservation_id = party_reservation.id
					left outer join party_type on party_type.id = party_reservation.party_type
				where '.$cond.' order by party_reservation.status,party_reservation.checkin_time desc
			)
			where
				rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		');
		$items = DB::fetch_all();
       // System::debug($items);
		if($items)
		{
			foreach($items as $k=>$itm)
			{
				$items[$k]['arrival_date'] = date('H:i d/m/Y',$itm['checkin_time']);
				$items[$k]['total'] = System::display_number($items[$k]['total']);
                $items[$k]['cancel_charge_amount'] = ($itm['cancel_charge_amount']!='')?System::display_number($items[$k]['cancel_charge_amount']):'';
			}
		}
		require_once 'packages/hotel/includes/php/hotel.php';
        $room_paty=DB::fetch_all('select id,name,group_name from party_room order by group_name');
        foreach($room_paty as $k=>$v){
            $room_paty.='<option value='.$v['id'].'>'.$v['group_name'].'-'.$v['name'].'</option>';
        }
        $this->map['room_party']=$room_paty;
        //System::debug($items);
		$this->parse_layout('list',array(
			'items'=>$items,
			'paging'=>$paging,
			'status_list'=>$status,
            'status_list'=>$status,
            'party_name_list'=>$this->map['party_name_list'],
		)+$this->map);
	}
}
?>