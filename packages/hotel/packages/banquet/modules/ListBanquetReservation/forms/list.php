<?php
class ListBanquetReservationForm extends Form
{
	function ListBanquetReservationForm()
	{
		Form::Form('ListBanquetReservationForm');	
		$this->link_css(Portal::template('core').'/css/jquery/datepicker.css');
		$this->link_js('packages/core/includes/js/jquery/datepicker.js');
		
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
        ;
        
		$item_per_page = 200;
		DB::query('
			select count(*) as acount
			from 
				party_reservation
			where '.$cond.' and party_reservation.status=\'CHECKOUT\'
		');
		$count = DB::fetch();
		require_once 'packages/core/includes/utils/paging.php';
		$paging = paging($count['acount'],$item_per_page, $numpageshow=10,$smart=false,$page_name='page_no',$params=array('from_arrival_time','to_arrival_time','status','product_code','agent_name'));
		
        DB::query('select * from(
				select DISTINCT
					party_reservation.id, 
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
					party_type.name as party_name,
                    party_type.id as party_id,
                    NVL(party_reservation.mice_reservation_id,0) as mice_reservation_id,
					row_number() over (order by  party_reservation.id desc,party_reservation.status) as rownumber
				from 
					party_reservation
					left outer join party_reservation_detail on party_reservation_detail.party_reservation_id = party_reservation.id
					left outer join party_type on party_type.id = party_reservation.party_type
				where 
                    '.$cond.' and party_reservation.status=\'CHECKOUT\' 
                order by 
                    party_reservation.status,party_reservation.checkin_time desc
			)
			where
				rownumber > '.((page_no()-1)*$item_per_page).' and rownumber<='.((page_no())*$item_per_page).'
		');
		$items = DB::fetch_all();
        $vat_bill = DB::fetch_all('select 
                                        vat_bill_detail.invoice_id as id, 
                                        sum(vat_bill_detail.total_amount) as total 
                                    from 
                                        vat_bill_detail 
                                        inner join vat_bill on vat_bill_detail.vat_bill_id=vat_bill.id
                                    where 
                                        vat_bill_detail.type=\'BANQUET\' and (vat_bill.status is null or vat_bill.status!=\'CANCEL\')
                                    group by 
                                        vat_bill_detail.invoice_id ');
        foreach($items as $key=>$value){
            $items[$key]['total'] = $value['total']+$value['deposit_1']+$value['deposit_2']+$value['deposit_3']+$value['deposit_4'];
            $value['total_remain_vat'] = $items[$key]['total'];
            if(isset($vat_bill[$value['id']])) {
                if($items[$key]['total']<=$vat_bill[$value['id']]['total']) {
                    unset($items[$key]);
                } else {
                    $value['total_remain_vat'] = $items[$key]['total']-$vat_bill[$value['id']]['total'];
                }
            }
            if(isset($items[$key])){
                $items[$key]['total_remain_vat'] = $value['total_remain_vat'];
            }
        }
		$this->parse_layout('list',array(
			'items'=>$items,
            'items_js'=>String::array2js($items),
			'paging'=>$paging
		));
	}
}
?>