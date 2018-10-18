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
	function draw()
	{
		$cond = '1>0 and bar_reservation.portal_id=\''.PORTAL_ID.'\' and bar_reservation.status=\'CHECKOUT\'';
        if(Url::get('invoice_number')) {
            $cond .= ' and bar_reservation.code=\''.Url::get('invoice_number').'\'';
        } else {
            if(Url::get('bars')) 
                    $cond .= 'and bar_reservation.bar_id='.Url::get('bars');
            if(Url::get('from_arrival_time')!='' and Url::get('to_arrival_time')!='') {
                $cond .= ' and bar_reservation.time_in<='.(Date_Time::to_time(Url::get('to_arrival_time'))+86399).' and bar_reservation.time_out>='.Date_Time::to_time(Url::get('from_arrival_time'));
            }
        }
        if(isset($_SESSION['bar_id'])) {
			$_REQUEST['bars'] = Url::get('bars')?Url::get('bars'):$_SESSION['bar_id'];
		}
        
		$bar_reservation = DB::fetch_all('
                select 
					bar_reservation.id, 
					bar_reservation.code, 
                    bar_reservation.receiver_name,
					bar_reservation.time_in,
					bar_reservation.time_out,					
					bar_reservation.num_table,
					bar_reservation.total,
                    bar.id as bar_id,
					bar.name as bar_name,
					bar_reservation.user_id,
                    bar_reservation.package_id
				from 
					bar_reservation
					inner join bar on bar_reservation.bar_id = bar.id
				where 
                    '.$cond.' and (bar_reservation.pay_with_room is null or bar_reservation.pay_with_room=0)
                order by 
                    bar_reservation.code desc 
                ');
        //System::debug($cond);
        $bar_reservation_table = DB::fetch_all('select 
                                                    bar_reservation_table.id,
                                                    bar_reservation_table.table_id,
                                                    bar_table.name,
                                                    bar_table.bar_area_id,
                                                    bar_reservation_table.bar_reservation_id 
                                                from 
                                                    bar_reservation_table 
                                                    inner join bar_reservation on bar_reservation_table.bar_reservation_id=bar_reservation.id
                                                    inner join bar_table on bar_reservation_table.table_id=bar_table.id 
                                                where 
                                                    '.$cond.' and (bar_reservation.pay_with_room is null or bar_reservation.pay_with_room=0)');
        $table_list = array();
        foreach($bar_reservation_table as $key=>$value) {
            if(!isset($table_list[$value['bar_reservation_id']])) {
                $table_list[$value['bar_reservation_id']]['name'] = $value['name'];
                $table_list[$value['bar_reservation_id']]['table_id'] = $value['table_id'];
                $table_list[$value['bar_reservation_id']]['bar_area_id'] = $value['bar_area_id'];
            }
            else {
                $table_list[$value['bar_reservation_id']]['name'] .= ', '.$value['name'];
            }
        }
        $vat_bill = DB::fetch_all('select 
                                        vat_bill_detail.invoice_id as id, 
                                        sum(vat_bill_detail.total_amount) as total 
                                    from 
                                        vat_bill_detail 
                                        inner join vat_bill on vat_bill_detail.vat_bill_id=vat_bill.id
                                    where 
                                        vat_bill_detail.type=\'BAR\' and (vat_bill.status is null or vat_bill.status!=\'CANCEL\')
                                    group by 
                                        vat_bill_detail.invoice_id ');
        
        $items = array();
        foreach($bar_reservation as $key=>$value) {
            $value['total_remain_vat'] = $value['total'];
            if(isset($vat_bill[$value['id']])) {
                if($value['total']<=$vat_bill[$value['id']]['total']) {
                    unset($bar_reservation[$key]);
                } else {
                    $value['total_remain_vat'] = $value['total']-$vat_bill[$value['id']]['total'];
                }
            }
            if(isset($bar_reservation[$key])) {
                $value['time_in'] = date('H:i d/m/Y',$value['time_in']);
                $value['time_out'] = date('H:i d/m/Y',$value['time_out']);
                $value['table_name'] = '';
                $table_id = '';
                $bar_area_id = '';
                if(isset($table_list[$key])) {
                    $table_id = $table_list[$key]['table_id'];
                    $bar_area_id = $table_list[$key]['bar_area_id'];
                    $value['table_name'] = $table_list[$key]['name'];
                }
                $value['total'] = System::display_number($value['total']);
                $value['total_remain_vat'] = System::display_number($value['total_remain_vat']);
                $value['href'] = '?page=touch_bar_restaurant&cmd=detail&316c9c3ed45a83ee318b1f859d9b8b79=d001cd500100d09a0a074a7a7ea55702&5ebeb6065f64f2346dbb00ab789cf001=1&id='.$value['id'].'&bar_id='.$value['bar_id'].'&table_id='.$table_id.'&bar_area_id='.$bar_area_id.'&package_id='.$value['package_id'];
                $items[$key] = $value;
            }
        }
        
        //System::debug($items);
		$bars = DB::fetch_all('SELECT * FROM BAR where portal_id=\''.PORTAL_ID.'\' ORDER BY ID ASC');
		$list_bars[0] = '----'.Portal::language('select_bar').'----';
		$list_bars = $list_bars + String::get_list($bars,'name');
        $list_status_print = array(0=>Portal::language('non_print'),1=>Portal::language('printed'));
        //die;
        $this->parse_layout('list',array(
			'items'=>$items,
            'items_js'=>String::array2js($items),
			'bars_list'=>$list_bars,
            'status_print_list'=>$list_status_print
		));
	}
}
?>