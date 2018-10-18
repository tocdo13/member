<?php
class MassageInvoiceForm extends Form
{
	function MassageInvoiceForm()
	{
		Form::Form('MassageInvoiceForm');
		$this->link_css('packages/hotel/'.Portal::template('massage').'/css/style.css');
	}
	function draw()
	{
	   
		$this->map = array(); 
		$this->map['room_number'] = DB::fetch('SELECT NAME FROM MASSAGE_ROOM WHERE ID = '.Url::iget('room_id').'','name');
		$currency=DB::select('currency','id=\'VND\'');
		$exchange_rate=$currency['exchange'];
		$this->map['exchange_rate'] = $exchange_rate;
		$item = MassageDailySummary::$item;
		$total_amount_net = 0;
		$this->map['total_price'] = 0;
		$this->map['total_tip'] = 0;
		$this->map['total_quantity'] = 0;
		$this->map['total_amount_'] = 0;
		if($item)
        {
			$item['time'] = date('d/m/Y',$item['time']);
			$total_amount_net = $item['extra_charge'];
			$item['currencies'] = DB::fetch_all('select currency_id as id,amount,bill_id,exchange_rate,name from pay_by_currency inner join currency on currency.id = currency_id where bill_id='.$item['id'].' and type=\'MASSAGE\'');
			$item['usd_amount'] = $item['total_amount'];
			foreach($item['currencies'] as $k=>$v)
            {
				if($v['id']=='USD'){
					$item['currencies'][$k]['name'] = 'Creadit card';
				}
				$item['usd_amount'] -= round($v['amount']/$v['exchange_rate'],2);
				$item['currencies'][$k]['amount'] = number_format($v['amount']);
			}
			if($item['hotel_reservation_room_id'] and $row = DB::fetch('select reservation_room.id,room.name from reservation_room inner join room on room.id = reservation_room.room_id where reservation_room.id = '.$item['hotel_reservation_room_id'].''))
            {
				$item['hotel_room_number'] = $row['name'];
			}
            else
            {
				$item['hotel_room_number'] = '';
			}
			{
				$sql = '
					SELECT
						massage_staff_room.*,
						massage_staff.full_name
					FROM
						massage_staff_room
						INNER JOIN massage_staff ON massage_staff.id = staff_id
					WHERE
						massage_staff_room.reservation_room_id=\''.$item['id'].'\'
				';
				$mi_staff_group = DB::fetch_all($sql);
				foreach($mi_staff_group as $value)
                {
					$this->map['total_tip'] += $value['tip'];
				}
				$this->map['staffs'] = $mi_staff_group;
			} 
			{
				/*không lấy tk co nua ma lay tk dang xem hoa don
                $sql_user_checkout = '
                    select id,user_checkout
                    from massage_product_consumed
                    where
                    massage_product_consumed.reservation_room_id=\''.$item['id'].'\'
    				'.(Url::get('room_id')?'AND massage_product_consumed.room_id = '.Url::iget('room_id').'':'').'
                    and massage_product_consumed.status = \'CHECKOUT\'
                    and massage_product_consumed.time_out = (SELECT
    						max(massage_product_consumed.time_out) as time_out
                            from massage_product_consumed
    					WHERE
    						massage_product_consumed.reservation_room_id=\''.$item['id'].'\'
    						'.(Url::get('room_id')?'AND massage_product_consumed.room_id = '.Url::iget('room_id').'':'').'
                            and massage_product_consumed.status = \'CHECKOUT\'
                        )
					';
                $user_checkout = DB::fetch($sql_user_checkout,'user_checkout');
                $checkout_user = array();
                $checkout_user = DB::fetch('select id,name_1 as name from party where user_id = \''.$user_checkout.'\'');
                $this->map['user_checkout'] = $checkout_user['name'];
                */
                $user_data = Session::get('user_data');
                $user_data['full_name'];
                $this->map['user_checkout'] = $user_data['full_name'];
                $sql = '
					SELECT
						massage_product_consumed.*,
                        (massage_product_consumed.price * massage_product_consumed.quantity) as amount,
						product.name_'.Portal::language().' as name,
                        product.name_2,
                        product.id as code,
						massage_room.name as room_name
					FROM
						massage_product_consumed
						INNER JOIN massage_room ON massage_room.id = room_id
						INNER JOIN product ON massage_product_consumed.product_id = product.id
					WHERE
						massage_product_consumed.reservation_room_id=\''.$item['id'].'\'
						'.(Url::get('room_id')?'AND massage_product_consumed.room_id = '.Url::iget('room_id').'':'').'
					ORDER BY
						massage_room.name
				';
				$mi_product_group = DB::fetch_all($sql);
				$i=1;
				foreach($mi_product_group as $key=>$value)
                {
					$mi_product_group[$key]['no'] = $i++;
					$mi_product_group[$key]['time_in'] = date('H:i\'',$value['time_in']);
					$mi_product_group[$key]['time_out'] = date('H:i\'',$value['time_out']);
                    $time_in = date('H:i\'',$value['time_in']);
                    $time_out = date('H:i\'',$value['time_out']);
					$total_amount_net +=$value['amount'];
					$mi_product_group[$key]['price'] = System::display_number($value['price']);
					$mi_product_group[$key]['amount'] = System::display_number($value['amount']);
					$this->map['total_price'] += $value['price'];
					$this->map['total_quantity'] += $value['quantity'];
					$this->map['total_amount_'] += $value['amount'];
				}
				$this->map['products'] = $mi_product_group;
			} 
            $this->map['time_in'] = $time_in;
            $this->map['time_out'] = $time_out;
			$this->map['total_amount_'] = System::display_number($this->map['total_amount_']);
			//KID CMT DOAN DUOI VA THAY BANG DOAN TINH THEO SETTING
			//$discount_amount = $total_amount_net*$item['discount']/100;
//			$item['discount_amount'] = System::display_number($discount_amount);
//			$item['tax_amount'] = System::display_number(($total_amount_net-$discount_amount)*$item['tax']/100);
//			$item['total_amount'] = System::display_number($this->map['total_tip'] + $total_amount_net - $discount_amount + (($total_amount_net-$discount_amount)*$item['tax']/100));
			if(DISCOUNT_BEFORE_TAX==1)
            {
                if($item['net_price']==0)
                {
                    $item['discount_amount'] = $item['discount_amount'];
                    $discount_amount = ($total_amount_net*$item['discount']/100);
                    $item['discount_amount_persent'] = System::display_number($discount_amount);
                    $item['service_rate_amount'] = (($total_amount_net-$discount_amount-$item['discount_amount'])*$item['service_rate']/100);
        			$item['tax_amount'] = (($total_amount_net-$discount_amount-$item['discount_amount'])+($total_amount_net-$discount_amount-$item['discount_amount'])*$item['service_rate']/100)*$item['tax']/100;
        			$item['total_amount'] =$total_amount_net - $discount_amount - $item['discount_amount'] + $item['service_rate_amount'] + $item['tax_amount'];
                }
                else
                {
                    $total_amount_net = ($total_amount_net*100/(100+$item['tax']))*100/(100+$item['service_rate']);
                    $discount_amount = ($total_amount_net*$item['discount']/100);
                    $item['discount_amount_persent'] = System::display_number($discount_amount);
                    $item['service_rate_amount'] = (($total_amount_net-$discount_amount-$item['discount_amount'])*$item['service_rate']/100);
        			$item['tax_amount'] = ((($total_amount_net-$discount_amount-$item['discount_amount'])+($total_amount_net-$discount_amount-$item['discount_amount'])*$item['service_rate']/100)*$item['tax']/100);
        			$item['total_amount'] =($total_amount_net - $discount_amount-$item['discount_amount']) + $item['service_rate_amount'] + $item['tax_amount'];
                }
            }
            else
            {
                if($item['net_price']==0)
                {
                    $item['discount_amount'] = $item['discount_amount'];
                    $item['service_rate_amount'] = (($total_amount_net)*$item['service_rate']/100);
                    $item['tax_amount'] = (($total_amount_net)+($total_amount_net)*$item['service_rate']/100)*$item['tax']/100;
                    $discount_amount = (($total_amount_net+$item['service_rate_amount']+$item['tax_amount'])*$item['discount']/100);
                    $item['discount_amount_persent'] = System::display_number($total_amount_net*$item['discount']/100);
                    
                    $item['total_amount'] =$total_amount_net + $item['service_rate_amount'] + $item['tax_amount'] - $discount_amount - $item['discount_amount'];
                    $item['tax_amount'] = $item['total_amount']-$item['total_amount']*100/(100+$item['tax']);
                    $item['service_rate_amount'] = $item['total_amount'] - $item['tax_amount'] - (($item['total_amount'] - $item['tax_amount'])*100/(100+$item['service_rate']));
                     //echo 3;
                }
                else
                {
                    $item['discount_amount'] = $item['discount_amount'];
                    $discount_amount = ($total_amount_net)*$item['discount']/100;
                    $item['discount_amount_persent'] = System::display_number(($total_amount_net*100/(100+$item['tax']))*100/(100+$item['service_rate'])*$item['discount']/100);
                    
                    $item['service_rate_amount'] = (($total_amount_net-$discount_amount-$item['discount_amount'])*$item['service_rate']/100);
                    $item['tax_amount'] = ((($total_amount_net-$discount_amount-$item['discount_amount'])+($total_amount_net-$discount_amount-$item['discount_amount'])*$item['service_rate']/100)*$item['tax']/100);
                    $item['total_amount'] =$total_amount_net - $discount_amount - $item['discount_amount'];
                    $item['tax_amount'] = $item['total_amount']-$item['total_amount']*100/(100+$item['tax']);
                    $item['service_rate_amount'] = $item['total_amount'] - $item['tax_amount'] - (($item['total_amount'] - $item['tax_amount'])*100/(100+$item['service_rate']));
                    //echo 4;
                }
            }
            if(Url::get('id'))
            {
                $payment_list = DB::fetch_all('SELECT payment.id, payment_type.name_'.Portal::language().' as payment_type_name, payment.amount, payment.currency_id FROM payment inner join payment_type on payment_type.def_code=payment.payment_type_id WHERE payment.bill_id=\''.Url::get('id').'\' AND payment.type=\'SPA\'');
                $pay_with_room = DB::fetch('select hotel_reservation_room_id,amount_pay_with_room,payment.currency_id from massage_reservation_room left join payment ON payment.bill_id =massage_reservation_room.id  where massage_reservation_room.id='.Url::get('id')." AND massage_reservation_room.pay_with_room=1");
                if($pay_with_room['hotel_reservation_room_id']>0)
                    array_push($payment_list,array('payment_type_name'=>Portal::language('pay_with_room'),
                                                   'amount'=>$pay_with_room['amount_pay_with_room'],
                                                   'currency_id'=>$pay_with_room['currency_id']
                                                   )
                               );
                $this->map['payment_list'] = $payment_list;
            //system::debug($payment_list);                                   
            }
            //het sua
			$this->map['total_tip'] = System::display_number($this->map['total_tip']);
		}
		$this->map += $item;
        //giap.ln tinh so tien package neu co su dung package
        
        if($item['package_id']!='')
        {
            $price_package = DB::fetch("SELECT * FROM package_sale_detail WHERE id=".$item['package_id']);
            $this->map['package_amount'] = $price_package['price'];
        } 
		$this->parse_layout('invoice',$this->map);
	}
}
?>
